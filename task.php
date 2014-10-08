<?php

ini_set('default_socket_timeout', -1);
ini_set('memory_limit', '1024M');

//
// application config
//
$app_config = array(
  'redis' => array(
    'host' => '127.0.0.1',
    'port' => 6379
  ),
  'file' => array(
    'name' => './data/bin.dat.large'
  ),
  'result' => array(
    'total' => 0
  )
);

// calculate filesize
$app_config['file']['size'] = filesize($app_config['file']['name']);

// $total = 0;

/**
 * publish task to each process.
 *
 * @param $redis
 * @param $num_process
 */
function publish_tasks($redis, $num_process) {
  global $app_config;

  for ($i = 0; $i < $num_process; $i++) {
    $start_pos = floor($i * $app_config['file']['size'] / $num_process);
    $end_pos = floor(($i + 1) * $app_config['file']['size'] / $num_process - 1);

    # channel name to publish
    $channel = "chan-" . ($i + 1);

    # message to send
    $task = "$start_pos:$end_pos";
  
    print("[$channel] assigned task $task\n");
  
    $redis->publish($channel, $task);
  }
}

/**
 * task to do in parallel.
 *
 * @param $msg
 */
function run_task($msg) {

  global $app_config;

  $positions = split(':', $msg);

  $start_pos = intval($positions[0]);
  $end_pos = intval($positions[1]);

  $file = fopen($app_config['file']['name'], 'rb');
  fseek($file, $start_pos);

  $str = fread($file, $end_pos - $start_pos + 1);

  return substr_count($str, '1');
}

/**
 * called when each subscribing processes receive new message.
 *
 * @param $redis
 * @param $channel
 * @param $msg
 */
function subscribe_callback($redis, $channel, $msg) {

  global $app_config;

  $result = run_task($msg);

  $redis_in_func = new Redis();
  $redis_in_func->connect($app_config['redis']['host'], $app_config['redis']['port']);
  $redis_in_func->publish('result', $result);

  print("result " . $result . " published\n");

  $redis_in_func->close();
}
 
 
/**
 * called when aggregation processes receive new message.
 *
 * @param $redis
 * @param $channel
 * @param $msg
 */
function aggregate_callback($redis, $channel, $msg) {

  global $app_config;

  $result = intval($msg);
  $app_config['result']['total'] += $result;

  $redis_in_func = new Redis();
  $redis_in_func->connect($app_config['redis']['host'], $app_config['redis']['port']);
  $remain_process = $redis_in_func->decr('active_process');
  $total_process = $redis_in_func->get('total_process');

  print("receive: " . $result . ", total: " . $app_config['result']['total'] . ", process: " . $remain_process . " / " . $total_process . "\n");

  if ($remain_process == 0) {
    $end_time = microtime(true);
    $start_time = floatval($redis_in_func->get('start_time'));

    print("result: " . $app_config['result']['total'] . " \n");
    print("finished, time " . ($end_time - $start_time) . " [sec]\n");

    $app_config['result']['total'] = 0;
  }

  $redis_in_func->close();
}

