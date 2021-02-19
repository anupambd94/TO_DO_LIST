<?php
include_once '../../vendor/autoload.php';
use TO_DO_LIST\Task\Task;
use TO_DO_LIST\Utility\Utility;
$task = new Task();
$debugger = new Utility();
$data = array();
$arrayVariable = array(
    'option' => 'all',
);
$selectedoption = 'all';
$totalCompleted = 0;
$totalActive = 0;
if (isset($_SESSION['data'])) {
    $data = $_SESSION['data'];
    unset($_SESSION['data']);

} else {
    $data = $task->filter($arrayVariable)->index();
}

if (isset($_SESSION['option']) && !empty($_SESSION['option'])) {
    $selectedoption = $_SESSION['option'];
} else {
    $selectedoption = 'all';
}

$totalCompleted = $task->getTotalCompleted();
$totalActive = $task->getTotalActive();
// echo '<pre>';
// print_r($total);
// echo '</pre>';

if (isset($_SESSION['msg']) && !empty($_SESSION['msg'])) {
    echo $_SESSION['msg'];
    unset($_SESSION['msg']);

}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <!--  This file has been downloaded from bootdey.com    @bootdey on twitter -->
    <!--  All snippets are MIT license http://bootdey.com/license -->
    <title>WeDevs todo list</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="http://netdna.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/pretty-checkbox@3.0/dist/pretty-checkbox.min.css" rel="stylesheet">
    <link href="../../Assets/css/all.min.css" rel="stylesheet">
    <script src="https://use.fontawesome.com/5e7608e55e.js"></script>
    <link href="../../Assets/css/style.css" rel="stylesheet">

    <style type="text/css">

    </style>
</head>

<body>
    <div class="main">
        <h1>todos</h1><br>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-white">
                    <div class="card-body">
                        <form id="taskInputForm" action="store.php" method="post">
                            <div class="inputField">
                                <?php if ($totalActive > 0 || $totalCompleted > 0): ?>
                                <i class="fas fa-chevron-down" style="color:rgba(204, 204, 204, 0.466);"></i>
                                <?php endif;?>
                                <input type="text" name="name" class="no-outline add-task element" autocomplete="off"
                                    placeholder="What need to be done?">

                                <input class="insert" type="hidden" name="status" value="1">
                            </div>

                        </form>

                        <div class="todo-list">
                            <!-- <i class="fas fa-user"></i> -->
                            <?php if (!empty($data) && isset($data) and count($data) > 0): ?>

                            <?php foreach ($data as $task): ?>
                            <div class="task_item task_item_<?=$task['id']?>">
                                <div class=" innerPosition">
                                    <div class="pretty p-icon p-round">
                                        <input type="checkbox" class="checkbox"
                                            title="<?=($task['is_completed'] == '1') ? 'Already completed' : 'Check if this task is completed'?>"
                                            <?=($task['is_completed'] == '1' && $task['status'] == '3') ? 'checked' : ''?>
                                            onclick="completeThisTask('<?=$task['id']?>')" />
                                        <div class="state">
                                            <i class="icon fas fa-check"></i>
                                            <!-- <i class="fas fa-check"></i> -->
                                            <label title="Edit this task" style=""
                                                <?=($task['is_completed'] != '1' && $task['status'] != '3') ? 'onclick="show_edit_option(' . $task['id'] . ')"' : '';?>
                                                <?=($task['is_completed'] == '1' && $task['status'] == '3') ? 'style="color: #ccc;pointer-events: none;-webkit-text-decoration-line: line-through;text-decoration-line: line-through;"' : ''?>>
                                                <span class="hidden-resizer" id="taskName_<?=$task['id']?>"
                                                    style="display: inline-block;font-size: 26px;"><?=$task['task_name']?></span>
                                            </label>
                                        </div>
                                    </div>


                                </div>

                            </div>
                            <form id="taskEditForm_<?=$task['id']?>" class="task_edit task_edit_<?=$task['id']?>"
                                action="update.php" method="post" style="display:none">
                                <input id="task_input_<?=$task['id']?>" data-id="<?=$task['id']?>"
                                    onmouseout="updateThisTask('<?=$task['id']?>')" value="<?=$task['task_name']?>"
                                    type="text" autocomplete="off" name="name" class="taskEditInput">

                                <input type="hidden" name="id" value="<?=$task['id']?>">
                                <input type="hidden" name="status" value="1">

                            </form>
                            <form id="taskDeleteForm_<?=$task['id']?>" class="task_delete_<?=$task['id']?>"
                                action="delete.php" method="post" style="display:none">
                                <input value="<?=$task['task_name']?>" type="hidden" name="name" class="">

                                <input type="hidden" name="id" value="<?=$task['id']?>">
                                <input type="hidden" name="status" value="3">
                                <input type="hidden" name="completed" value="1">

                            </form>
                            <?php endforeach;?>

                            <?php endif;?>
                        </div>
                        <!-- <div class="paper">Testing</div> -->
                        <?php if ($totalActive > 0 || $totalCompleted > 0): ?>
                        <div class="stacked">
                            <div class="button_elementes">
                                <ul class="nav nav-pills todo-nav">
                                    <li role="presentation" class="nav-item all-task">
                                        <label for="itemsleft" class="nav-link"><?=$totalActive?> items left</label>

                                    </li>
                                    <li role="presentation" class="nav-item all-task " onclick="filterTasks('all')"><a
                                            class="nav-link <?=($selectedoption == 'all') ? 'selected' : ''?>">All</a>
                                    </li>
                                    <li role="presentation" class="nav-item active-task"
                                        onclick="filterTasks('active')"><a
                                            class="nav-link <?=($selectedoption == 'active') ? 'selected' : ''?>">Active</a>
                                    </li>
                                    <li role="presentation" class="nav-item completed-task"
                                        onclick="filterTasks('completed')"><a
                                            class="nav-link <?=($selectedoption == 'completed') ? 'selected' : ''?>">Completed</a>
                                    </li>
                                    <?php if ($totalCompleted > 0): ?>
                                    <li role="presentation" class="nav-item all-task">
                                        <label for="itemsleft" class="nav-link completed_button"
                                            title="Delete all completed tasks" onclick="deleteCompleted()">Clear
                                            Completed</label>

                                    </li>
                                    <?php endif;?>
                                </ul>
                            </div>
                        </div>
                        <?php endif;?>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../../Assets/js/jquery-1.10.2.min.js"></script>
    <script src="../../Assets/js/bootstrap.min.js"></script>
    <script src="../../Assets/js/all.js"></script>

    <script type="text/javascript">
    function show_edit_option(id) {
        $(".task_item_" + id).hide();
        $(".task_edit_" + id).show();
        $("#task_input_" + id).focus();

    }

    function completeThisTask(id) {
        var oForm = $("#taskDeleteForm_" + id);
        oForm.action = "delete.php";
        oForm.post = "post";
        oForm.submit();
        // console.log(id);

    }

    function updateThisTask(formId) {
        var oForm = $("#taskEditForm_" + formId);
        oForm.action = "update.php";
        oForm.post = "post";
        oForm.submit();
    }

    function filterTasks(option) {
        console.log(option);
        $.post("filter.php", {
            option: option
        }, function(result) {
            // $("span").html(result);
            console.log(result);
            location.reload();

        });
    }

    function deleteCompleted() {
        var option = '<?=$selectedoption?>';
        // console.log(option);
        $.post("clear.php", {
            option: option
        }, function(result) {
            // $("span").html(result);
            // console.log(result);
        });
        location.reload();
    }

    $(function() {
        $("span").each(function(index) {
            var spanId = $(this).attr('id');
            var span = $(this);
            var spanWidth = span.width();
            var fontSize = parseInt(span.css('font-size'));
            // console.log(spanId, spanWidth, fontSize);
            while (spanWidth > 450) {

                fontSize--;
                span.css('font-size', fontSize.toString() + 'px');
                spanWidth = span.width();
                fontSize = parseInt(span.css('font-size'));
                // console.log(spanId, spanWidth, fontSize);

            }
        });

    });

    $(document).ready(function() {
        $(".add-task").val('');
        $(".add-task").keypress(function(e) {
            if ((e.which == 13) && (!$(this).val().length == 0)) {
                var oForm = $("#taskInputForm");
                oForm.action = "store.php";
                oForm.post = "post";
                oForm.submit();
                // $(this).val('');
            } else if (e.which == 13) {
                // alert('Please enter new task');
            }
        });
        $(".taskEditInput").on('blur', function(e) {
            var dataId = $(this).attr("data-id");
            var oForm = $("#taskEditForm_" + dataId);
            oForm.action = "update.php";
            oForm.post = "post";
            oForm.submit();
        });
    });
    </script>
</body>

</html>