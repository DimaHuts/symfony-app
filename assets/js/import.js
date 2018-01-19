(function () {

    const form = $("form[name='csv_file']");

    form.change(function(e){
        e.preventDefault();
        
        $.ajax({
            url: 'http://127.0.0.1:8000/en/import-products',
            type: 'POST',
            data: new FormData(form[0]),
            processData: false,
            contentType: false,
            cache: false,
            success: function(data) {
                // form.find(".text-info").html(JSON.parse(data));
                // form.find("input[type='file']").val("");
                location.reload()
            }
        })
    });

})();