<script>
    $(document).on('click','.btn',function(){
        var obj = $(this);

        console.log(obj);
        var vUserUuid = obj.attr("vUserUuid");
        var tiIsAdminApproved = obj.attr("tiIsAdminApproved");
        // console.log(vUserUuid,tiIsAdminApproved);
        var reference = "{{ $reference }}";
        // alert(reference);
        $.toast().reset('all');

        $.ajax({
            type: "POST",
            dataType: "json",
            url: "{{ route('backend.adminApproved') }}",
            data: {
                'vUserUuid': vUserUuid,
                'tiIsAdminApproved': tiIsAdminApproved,
                'reference': reference,
                '_token': '{{ csrf_token() }}'
            },
            success: function(res) {
                var html = '';
                if(res.status == true) {
                    if(tiIsAdminApproved == 1) {
                        html = '<button type="button" vUserUuid="'+vUserUuid+'" tiIsAdminApproved="'+tiIsAdminApproved+'" class="btn btn-block btn-success btn-sm w-50">Approved</button>';
                    } else {
                        html = '<button type="button" vUserUuid="'+vUserUuid+'" tiIsAdminApproved="'+tiIsAdminApproved+'" class="btn btn-block btn-danger btn-sm w-50">unApproved</button>';
                    }
                    console.log(html);
                    obj.html(html);
                    $.toast({
                        heading: 'Success',
                        text: 'Status Changed successfully',
                        position: String('top-center'),
                        icon: 'success',
                        loaderBg: '#f96868',
                        hideAfter: false,
                        hideAfter: 5000
                    });
                } else {

                }
            }
        });

    });
</script>
