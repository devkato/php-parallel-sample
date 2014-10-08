<?php   
/**
 *
 * initialize variables(stored in redis) and
 * publish splitted task to each processes.
 *
 */
require './task.php';

$num_process = intval($argv[1]);
 
$redis = new Redis();    
$redis->connect($app_config['redis']['host'], $app_config['redis']['port']);

$redis->set('start_time', microtime(true));
$redis->set('total_process',  $num_process);
$redis->set('active_process', $num_process);

publish_tasks($redis, $num_process);

$redis->close();
