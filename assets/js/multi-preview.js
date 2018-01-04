(function () {

    function previewImages() {

        var $preview = $('#list').empty();
        if (this.files) $.each(this.files, readAndPreview);

        function readAndPreview(i, file) {

            if (!/\.(jpe?g|png|gif)$/i.test(file.name)){
                return alert(file.name +" is not an image");
            }

            var reader = new FileReader();

            $(reader).on("load", function() {
                var imageContainer = $('<div class="list__image-container">');
                imageContainer.append('<a class="list__image-container_delete-link img-circle"><span class="glyphicon glyphicon-remove text-danger"></span></a>');
                $preview.append(imageContainer.append($("<img/>", {src:this.result, class: 'product-image'})));
            });

            reader.readAsDataURL(file);
        }

    }

    $('input[type="file"]').on("change", previewImages);

})();

