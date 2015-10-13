/**
 * @package     Dolly
 * @author:     aat <aat@norse.digital>
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 17 07 2015
 */

(function ($) {
    var heightProducts;
    var heightMenu;
    var path;

    var selectMenu = function (e) {
        e.preventDefault();
        var currentLi = $(this);
        currentLi.parent().find('.active').removeClass('active');
        currentLi.addClass('active');
        $('#menu-update, #menu-create, #menu-products, #available-products').addClass('hidden');
        var id = currentLi.find('a').data('id');
        path = currentLi.find('a').attr('href');
        if (id > 0) {
            $.post(path + '/update', function (resData) {
                if (resData.length > 1) {
                    var menuUpdateContainer = $('#menu-update');
                    menuUpdateContainer.html(resData).removeClass('hidden');
                } else {
                    alert('Error to load menu');
                }
            });
            $.post(path + '/products', function (resData) {
                if (resData.length > 1) {
                    $('#menu-products').html(resData).removeClass('hidden');
                    heightMenu = $('#menu-products .list-group').height();
                    setMaxHeight(heightProducts, heightMenu);
                    initSearchMenuProducts();
                } else {
                    alert('Error to load menu products');
                }
                _dragDropEvents();
            });
            postProductsNotInMenu();
        } else {
            $.post(path, function (resData) {
                if (resData.length > 1) {
                    $('#menu-create').html(resData).removeClass('hidden');
                } else {
                    alert('Error to load created form');
                }
            });
        }

    };

    var postProductsNotInMenu = function (page, search) {
        if (page == 0) {
            return;
        }
        if (!page) {
            page = 1;
        }
        $.post(path + '/notmenuproducts/' + page, {query: search}, function (resData) {
            if (resData.length > 1) {
                $('#available-products').html(resData).removeClass('hidden');
                setMaxHeight();
            } else {
                alert('Error to load products');
            }
            _dragDropEvents();
            initSearchAvailableProducts();
        });
    };

    var loadProductsNotInMenu = function (e) {
        e.preventDefault();
        var page = parseInt($(this).children().text());
        if (this.className == 'next') {
            page = parseInt($('.pagination li.active').children().text());
            page = page + 1;
        }
        if (this.className == 'previous') {
            page = parseInt($('#pagination li.active').children().text());
            page = page - 1;
        }
        if (this.className == 'previous disabled') {
            page = 0;
        }
        if (this.className == 'next disabled') {
            page = 0;
        }
        postProductsNotInMenu(page);
    };

    var searchProductsNotInMenu = function (e) {
        e.preventDefault();
        var search = $('#avalible-search').val();
        if (search.length < 2) {
            return;
        }
        postProductsNotInMenu(1, search);
    };
    var setMaxHeight = function () {
        var h1 = $('#available-products .list-group').css('height', 'auto').height();
        var h2 = $('#menu-products .list-group').css('height', 'auto').height();
        if (h1 && h2) {
            if (h1 - 140 < h2) {
                $('#available-products .list-group').height(h2 + 140);
            } else {
                $('#menu-products .list-group').height(h1 - 140);
            }
        }
    };

    var initSearchAvailableProducts = function () {
        $(".js-avalible-product-children-cont").searcher({
            itemSelector: ".grid__elm", // jQuery selector for the data item element
            textSelector: "p, h1, h2, h3", // jQuery selector for the element which contains the text
            inputSelector: $('#avalible-search')  // jQuery selector for the input element
        });
    };

    var initSearchMenuProducts = function () {
        $(".js-in-menu-children-cont").searcher({
            itemSelector: ".grid__elm", // jQuery selector for the data item element
            textSelector: "p, h1, h2, h3", // jQuery selector for the element which contains the text
            inputSelector: $('#in-menu-searcher')  // jQuery selector for the input element
        });
    };

    var showHideSettings = function (e) {
        e.preventDefault();
        $('.js-menu-settings').toggle('slow').toggleClass('v');
        setMaxHeight();
    };
    var processDeleteMenu = function (e) {
        e.preventDefault();
        var delPath = $(this).attr('href');
        if (confirm('Really delete?')) {
            $.post(delPath, function (resData) {
                if (resData.errorCode == 204) {
                    $('#menu-list').find('.active').remove();
                    $('#menu-update, #menu-create, #menu-products, #available-products').addClass('hidden');
                } else {
                    alert('Error delete menu');
                }
            });
        }
    };

    var postAddProduct = function (productId, callback) {
        $.post(path + '/addproduct/' + productId, callback);
    };

    var postRemoveProduct = function (productId, callback) {
        $.post(path + '/removeproduct/' + productId, callback);
    };

    var removeProduct = function (e, $this, drag) {
        if (e) {
            e.preventDefault();
        }
        var product = $(this).parent().parent();
        if ($this) {
            product = $this;
        }
        var productId = product.data('id');
        var menuProductsContainer = $('.js-draggable-cont-menu .list-group');
        var availableProductsContainer = $('.js-draggable-cont-products .list-group');
        var callback = function (resData) {
            if (resData.errorCode == '200') {
                availableProductsContainer.append(product);
                availableProductsContainer.append($('div.navigations'));
                availableProductsContainer.find('.js-not-found-text').addClass('hidden');
                availableProductsContainer.find('.js-change-product-priority').addClass('hidden');
                product.find('a.btn-danger').addClass('hidden');
                product.find('a.btn-primary').removeClass('hidden');
                if (menuProductsContainer.children().length < 3) {
                    menuProductsContainer.find('.js-not-found-text').removeClass('hidden');
                }
                $('.product-thumb__actions').addClass('hidden');
                setMaxHeight();
            } else {
                alert('Error remove product from menu');
            }
            clearStyles(product);
        };
        if (drag) {
            postRemoveProduct(productId, callback);
        } else if (!drag && confirm('Remove this product?')) {
            postRemoveProduct(productId, callback);
        } else {
            clearStyles(product);
        }
    };

    var addProduct = function (e, $this, drag) {
        if (e) {
            e.preventDefault();
        }
        var product = $(this).parent().parent();
        if ($this) {
            product = $this;
        }
        var productId = product.data('id');
        var menuProductsContainer = $('.js-draggable-cont-menu .list-group');
        var availableProductsContainer = $('.js-draggable-cont-products .list-group');
        var callback = function (resData) {
            if (resData.errorCode == '200') {
                menuProductsContainer.append(product);
                menuProductsContainer.find('.js-not-found-text').addClass('hidden');
                menuProductsContainer.find('.js-change-product-priority').removeClass('hidden');
                product.find('a.btn-danger').removeClass('hidden');
                product.find('a.btn-primary').addClass('hidden');
                if (availableProductsContainer.children().length < 3) {
                    availableProductsContainer.find('.js-not-found-text').removeClass('hidden');
                }
                $('.product-thumb__actions').addClass('hidden');
                setMaxHeight();
            } else {
                alert('Error add product to menu');
            }
            clearStyles(product);
        };
        if (drag) {
            postAddProduct(productId, callback);
        } else if (!drag && confirm('Add this product?')) {
            postAddProduct(productId, callback);
        } else {
            clearStyles(product);
        }
    };

    var navigateProduct = function (e) {
        e.preventDefault();
        alert('Not created');
    };
    var showHideProductActions = function (e) {
        e.preventDefault();
        var isCurrentVisible = $(this).parent().find('.product-thumb__actions').hasClass('hidden');
        $('.product-thumb__actions').addClass('hidden');
        if (isCurrentVisible) {
            $(this).parent().find('.product-thumb__actions').toggleClass('hidden');
        }
    };

    var clearStyles = function (product) {
        product.css('left', 'auto').css('top', 'auto').css('z-index', '');
    };

    var _dragDropEvents = function () {
        var appendToMenu = false;
        var appendToAvailable = false;
        var menuProductsContainer = $('.js-draggable-cont-menu .list-group');
        var availableProductsContainer = $('.js-draggable-cont-products .list-group');
        if (availableProductsContainer.children().length < 3) {
            availableProductsContainer.find('.js-not-found-text').removeClass('hidden');
        }
        if (menuProductsContainer.children().length < 3) {
            menuProductsContainer.find('.js-not-found-text').removeClass('hidden');
        }
        var product;

        $('.draggable').draggable({
            containment: "#content",
            start: function () {
                product = $(this);
                product.css('z-index', '999');
            },
            stop: function () {
                if (product.parent().parent().hasClass('js-draggable-cont-menu')) {
                    appendToMenu = false;
                }
                if (product.parent().parent().hasClass('js-draggable-cont-products')) {
                    appendToAvailable = false;
                }
                if (appendToMenu) {
                    addProduct(null, $(this), true);
                }
                if (appendToAvailable) {
                    removeProduct(null, $(this), true);
                }
                if (!appendToMenu && !appendToAvailable) {
                    clearStyles(product);
                }
                appendToMenu = false;
                appendToAvailable = false;

            }
        });
        menuProductsContainer.droppable({
            drop: function () {
                appendToMenu = true;
            }
        });
        availableProductsContainer.droppable({
            drop: function () {
                appendToAvailable = true;
            }
        });
    };
    var downProductPriority = function (e) {
        e.preventDefault();
        var product = $(this).parents('.grid__elm');
        var productId = product.data('id');

        $.post(path + '/downpriority/' + productId, function (resData) {
            if (resData.errorCode == 200) {
                product.before(product.next());
            } else {
                alert('Error to change priority');
            }
        });
    };

    var upProductPriority = function (e) {
        e.preventDefault();
        var product = $(this).parents('.grid__elm');
        var productId = product.data('id');
        $.post(path + '/uppriority/' + productId, function (resData) {
            if (resData.errorCode == 200) {
                product.after(product.prev());
            } else {
                alert('Error to change priority');
            }
        });
    };

    var validateCreateUpdeateForm = function (e) {
        var form = $(this);
        var priority = form.find('#app_menu_type_priority').val();
        var name = form.find('#app_menu_type_name').val();
        if (priority.length > 3) {
            alert('Field "Priority" has a limit');
            e.preventDefault();
        }
        if (name.length > 25) {
            alert('Field "Name" has a limit');
            e.preventDefault();
        }
    };

    var _attachEvent = function () {
        $('#menu-update').on('click', '#showHideSettings', showHideSettings);
        $('#menu-update').on('click', '#deleteLink', processDeleteMenu);
        $('#menu-list').on('click', 'li', selectMenu);
        $('.container').on('click',
            '.list-group .product-thumb__actions a.btn-danger',
            removeProduct);
        $('.container').on('click',
            '.list-group .product-thumb__actions a.btn-primary',
            addProduct);
        $('.container').on('click',
            '.list-group .product-thumb__actions a.btn-default',
            navigateProduct);
        $(document).on('click', 'div.product-thumb', showHideProductActions);
        $(document).on('click', '#pagination li', loadProductsNotInMenu);
        $(document).on('click', '#avalible-search-reset-button', loadProductsNotInMenu);
        $(document).on('click', '#avalible-search-button', searchProductsNotInMenu);
        $('#menu-products').on('click', '.js-up-product-priority', upProductPriority);
        $('#menu-products').on('click', '.js-down-product-priority', downProductPriority);
        $(document).on('submit', 'form', validateCreateUpdeateForm);
    };
    var _initialize = function () {
        _attachEvent();
    };

    _initialize();
})
(jQuery);