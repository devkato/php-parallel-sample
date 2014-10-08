<?php
/**
 *
 * subscribe 'result' channel that aggregate calculated result.
 *
 */

require './task.php';

print("waiting result... \n");

$redis = new Redis();
$redis->pconnect($app_config['redis']['host'], $app_config['redis']['port']);
$redis->subscribe(array('result'), 'aggregate_callback');
