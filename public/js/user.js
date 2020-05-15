$(document).ready(function() {
    // show form add task
    $("#add").click(function(e) {
        let key = Date.now() + (Math.random().toFixed(2) * 100)
        $("#listTask").append(
            "<div class='row task' style='margin-top:10px'>" +
                "<div class='col-3'>"+
                "<input type='text' class='form-control' placeholder='task id' name='task["+key+"][taskId]' >"+
                "</div>"+
                "<div class='col-5'>"+
                "<textarea rows='2' class='form-control' placeholder='content' name='task["+key+"][content]' required></textarea>"+
                "</div>"+
                "<div class='col-2'>"+
                "<input type='number' class='form-control' placeholder='time ( minutes)' name='task["+key+"][time]' required>"+
                "</div>"+
                "<div class='col-2'>"+
                "<i class='fas fa-minus-circle' style='color:red'></i>"+
                "</div>"+
            "</div>");
    })

    // remove task
    $(document).on('click', '.fa-minus-circle', function(e) {
        $(this).closest('.task').remove();
    });

    // approved
    $('.approve').on('change', function () {
        var id = $(this).attr("data-id");
        var token = $("#token").val();
        $.ajax({
            type: "POST",
            url: '/approve',
            data: {id:id, _token: token},
            success: function (e) {
                if (e.error == "") {
                    $("#"+id).attr('readonly', true);
                    $("#"+id).attr('disabled', true);
                    alert("Approve successfully");
                } else {
                    alert(e.error);
                }
            },
            error: function (e) {
                alert("Co loi trong qua trinh xu ly");
            }
        });
    });

    // search
    $(function () {
        $('#datetimepicker7').datetimepicker({
            format: 'YYYY-MM-DD',
            defaultDate: moment()
        });
        $('#datetimepicker8').datetimepicker({
            format: 'YYYY-MM-DD',
            defaultDate: moment()
        });
        $("#datetimepicker7").on("change.datetimepicker", function (e) {
            $('#datetimepicker8').datetimepicker('minDate', e.date);
        });
        $("#datetimepicker8").on("change.datetimepicker", function (e) {
            $('#datetimepicker7').datetimepicker('maxDate', e.date);
        });
    });

    // show image after choosing
    $("#selectImg").change(function (e) {
        if ($(this)[0].files && $(this)[0].files[0]) {
            var match= ["image/jpeg","image/png","image/jpg", "image/gif"];
            if(match.indexOf($(this)[0].files[0].type) < 0) {
                $(this).val('');
                alert("Type file is not supported");
                return false;
            } else {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#showImage').attr("src", e.target.result);
                };
                reader.readAsDataURL($(this)[0].files[0]);
            }
        }
    });
});