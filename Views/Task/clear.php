<?php
include_once '../../vendor/autoload.php';
use TO_DO_LIST\Task\Task;
use TO_DO_LIST\Utility\Utility;
$task = new Task();
$debugger = new Utility();

$data = $task->filter($_POST)->clear_completed();
// $debugger->debug($_POST);