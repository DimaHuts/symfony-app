(function () {
    'use strict';

    function readURL(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('.vich-image .vich-image__uploaded-img').attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("input[type='file']").change(function(){
        readURL(this);
    });
})();