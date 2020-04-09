// config select2

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
                    if (e.res) {
                        console.log(e);
                        $("#"+id). remove();
                    }
                },
                error: function (e) {
                    alert("Co loi trong qua trinh xu ly");
                }
            });
        }
    });
});