(function () {

    const form = $("form[name='csv_file']");

    form.change(function(e){
        e.preventDefault();

        $.ajax({
            url: '/import-products/' + $("html").attr('lang'),
            type: 'POST',
            data: new FormData(form[0]),
            processData: false,
            contentType: false,
            cache: false,
            success: function(data) {
                console.log(JSON.parse(data))
                // form.find(".text-danger").html(JSON.parse(data)[0]['csvFile'][0])
                form.find(".text-info").html(JSON.parse(data));
                // if (!data) {
                //
                //     form.after(JSON.parse(data));
                // }

                form.find("input[type='file']").val("");
            }
        })
    });

})();