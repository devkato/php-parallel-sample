<?php
/**
 *
 * Single process task dispatcher.
 *
 */
require './task.php';

$start_time = microtime(true);

$result = run_task("0:" . $app_config['file']['size']);

$end_time = microtime(true);

print("result: $result \n");
print("finished, time " . ($end_time - $start_time) . " [sec]\n");
