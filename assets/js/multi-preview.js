(function () {

    function previewImages() {

        const $preview = $('#list').empty();
        if (this.files) $.each(this.files, readAndPreview);

        function readAndPreview(i, file) {

            if (!/\.(jpe?g|png|gif)$/i.test(file.name)){
                return alert(file.name +" is not an image");
            }

            var reader = new FileReader();

            $(reader).on("load", function() {
                const imageContainer = $('<div class="list__image-container">');
                $preview.append(imageContainer.append($("<img/>", {src:this.result, class: 'product-image'})));
            });

            reader.readAsDataURL(file);
        }

    }

    $('input[type="file"]').on("change", previewImages);

})();

