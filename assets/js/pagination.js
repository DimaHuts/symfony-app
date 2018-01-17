(function () {
    'use strict';
    
    var productsContainer = $('.all-products');
    var paginationControlBlock = $(".pagination.pagination-sm");
    var minPage = paginationControlBlock.find('.pagination-number').first().data('page');
    var maxPage = paginationControlBlock.find('.pagination-number').last().data('page');
    var currentPage = 1;

    /**
     * Add css class active to a current pagination control button
     */
    function setActiveItem() {
        paginationControlBlock.find('li.active').removeClass('active');
        paginationControlBlock.find('a[data-page="' + currentPage + '"]').parent().addClass('active');
    }

    /**
     * Remove css class disabled from a pagination control arrow
     */
    function removeDisableClass() {
        paginationControlBlock.find('li.disabled').removeClass('disabled');
    }

    /**
     * Set css class disabled to a pagination control arrow in case current page equals minPage or maxPage
     *
     */
    function setDisabledArrow() {
        removeDisableClass();

        if (currentPage === minPage) {
            $(paginationControlBlock).find('a[data-arrow=left]').parent().addClass('disabled');
        }
        else if (currentPage === maxPage) {
            $(paginationControlBlock).find('a[data-arrow=right]').parent().addClass('disabled');
        }
    }

    /**
     * Add product row to the product table
     *
     * @param productRow
     */
    function addProductToTable(productRow) {
        productsContainer.append(productRow);
    }

    function getProductAvatar(src) {
        if (src !== null) {
            src = "uploads/products-images/" + src;
        }
        return $('<img class="product-image">').attr('src', src || 'img/undefined.jpg');
    }

    /**
     * Create one product row for the product table
     *
     * @param data
     */
    function createProductRows(data) {

        for (var i=0; i < data.length; i++) {
            var oneProduct = $('<div class="all-products__one-products col-lg-2 col-md-2">');
            var tdAvatar = $('<div class="text-center">').append(getProductAvatar(data[i]['imageName']));
            oneProduct.append($('<a href=' + getChangeLink(data[0]['id'], $('html').attr('lang'), 'edit') + '>').append(tdAvatar));
            oneProduct.append('<div class="text-center align-middle">' + data[0]['name'] + '</div>');
            oneProduct.append('<div class="text-center align-middle">' + data[0]['price'] + '</div>');
            oneProduct.append('<div class="text-center align-middle">' + data[0]['description'] + '</div>');
            addProductToTable(oneProduct);
        }


        // console.log(window.location.protocol);
        // console.log(window.location.host);
        // console.log(window.location.pathname);

        // var editLink = getChangeLink(data[k]['id'], window.location.pathname.split('/')[1], 'edit');
        // var deleteLink = getChangeLink(data[k]['id'], window.location.pathname.split('/')[1], 'delete');
        //
        // oneProduct.append('<td>' + '<a href="' + editLink + '"><span class="glyphicon glyphicon-edit"></span></a>' + '<input type="submit" class="delete-product" data-href="' + deleteLink + '"><span class="glyphicon glyphicon-remove-circle"></span></input>' + '</td>');

    }

    function getChangeLink(id, locale, action) {
        return window.location.protocol + '//' + window.location.host + '/' + action + '/' + id + '/' +  locale;
    }

    /**
     * Click through pagination control buttons: numbers and rows
     *
     */
    $('.pagination > li > a').click(function (e) {
        e.preventDefault();

        currentPage = defineCurrentPage($(e.target));

        setActiveItem();
        setDisabledArrow();
        console.log(currentPage);
        getProducts();
    });

    /**
     * Define current page depending on what pagination control button was clicked: number or row
     *
     * @param target
     * @returns {number}
     */
    function defineCurrentPage(target) {

        if (target.hasClass("pagination-number")) {
            return target.data('page');
        }
        else {
            var dataArrow = target.data('arrow');

            if (dataArrow === 'left') {
                if (currentPage !== minPage) {
                    return currentPage - 1;
                }
            }
            else if (dataArrow === 'right') {
                if (currentPage !== maxPage) {
                    return currentPage + 1;
                }
            }
        }

        return currentPage;
    }

    /**
     * Get all products from db
     */
    function getProducts() {
        $.ajax({
            type: "POST",
            data: 'page=' + currentPage,
            url: '/all-products/'
        }).done(function (data) {
            productsContainer.empty();
            var products = JSON.parse(data)[0];

            createProductRows(products);
        });
    }

    productsContainer.on('click', ".delete-product",  function (e) {
        e.preventDefault();

        console.log($(e.target).data('href'));
        $.post($(e.target).data('href'));
    });


    $(document).ready(function () {
        getProducts();
    });

})();