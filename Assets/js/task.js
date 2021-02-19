function show_edit_option(id) {
    $(".task_item_" + id).hide();
    $(".task_edit_" + id).show();
    var input = $("#task_input_" + id);
    input.focus();
input[0].selectionStart = input[0].selectionEnd = input.val().length;
input.css('border-color', '#a7a4a4');

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
    // console.log(option);
    $.post("filter.php", {
        option: option
    }, function(result) {
        // $("span").html(result);
        // console.log(result);
        location.reload();

    });
}

function deleteCompleted() {
    var option = '<?php $selectedoption ?>';
    console.log(option);
    // $.post("clear.php", {
    //     option: option
    // }, function(result) {
    //     // $("span").html(result);
    //     // console.log(result);
   // location.reload();

    // });
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

$(function() {
    $(".task_item").hover(function() {
        var taskId = $(this).attr('id');
        var isComplete = $(this).attr('data-completed');
        if (isComplete == 1) {
            $('.delete').hide();
        } else {
            $('.delete').hide();
            $('.someicon_' + taskId).show();
        }

    }, function() {
        // $(this).css("background-color", "pink");
    });
    $('.task_item').mouseout(function() {
        $('.delete').hide();
    });


});

$(document).ready(function() {
    var option = '<?=$selectedoption?>';
    console.log('selected: '+option);
    $(".add-task").val('');
    if(option != 'completed'){
        $("#addTaskInputFiled").focus();
    }
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