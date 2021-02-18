<?php
include_once '../../vendor/autoload.php';

use TO_DO_LIST\Task\Task;
use TO_DO_LIST\Utility\Utility;
$task = new Task();
$debugger = new Utility();

$data = $task->prepare($_POST)->complete_task();
// $debugger->debug($_POST);