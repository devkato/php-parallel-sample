<?php
/**
 *
 * Single process task dispatcher.
 *
 */
require './task.php';

$filesize = filesize($filename) - 1;

$start_time = microtime(true);

$result = run_task("0:$filesize");

$end_time = microtime(true);

print("result: $result \n");
print("finished, time " . ($end_time - $start_time) . " [sec]\n");
