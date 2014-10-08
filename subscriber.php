<?php
/**
 *
 * subscribe specified redis channel, and
 * wait for new message(including task info) dispatched by publisher.
 *
 */

require './task.php';

$channel_no = $argv[1];
$channel_name = "chan-$channel_no";

print("subscribe $channel_name \n");
 
$redis = new Redis();
$redis->pconnect($app_config['redis']['host'], $app_config['redis']['port']);
$redis->subscribe(array($channel_name), 'subscribe_callback');
