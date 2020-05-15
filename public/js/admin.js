$(document).ready(function() {
    //delete user
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
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '/users/'+id+'/delete',
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
})