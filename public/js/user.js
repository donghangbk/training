$(document).ready(function() {
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

    $(".deleteUser").click(function () {
        if (confirm("Are you sure !!!")) {
            var id = $(this).attr("data-id");
            var token = $("#token").val();
            // $.ajaxSetup({
            //     headers: {
            //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //     }
            // });
            $.ajax({
                type: "POST",
                url: '/deleteUser',
                dataType: 'json',
                data: {id:id, _token: token},
                success: function (e) {
                    if (e.error == "") {
                        $("#" + id + " > td:nth-child(7)").html("");
                        $("#" + id + " > td:nth-child(5)").html("<span class='badge badge-secondary'>Suspended</span>");
                    }
                },
                error: function (e) {
                    alert("Co loi trong qua trinh xu ly");
                }
            });
        }
    });

    // show fomr add task
    var i = 2;
    $("#add").click(function(e) {
        $("#listTask").append("<div class='row' style='margin-top:10px'>" +
        "<div class='col-3'>"+
          "<input type='text' class='form-control' placeholder='task id' name='task_id"+i+"' >"+
        "</div>"+
        "<div class='col-5'>"+
          "<textarea rows='2' class='form-control' placeholder='content' name='content"+i+"' required></textarea>"+
        "</div>"+
        "<div class='col-2'>"+
          "<input type='number' class='form-control' placeholder='time ( minutes)' name='time"+i+"' required>"+
        "</div>"+
      "</div>"+
 "</div>");
        i++;
    })

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
});