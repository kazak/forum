/**
 * @package     Dolly
 * @author:     aat <aat@norse.digital>
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 04 10 2015
 */
var curentImageBlock;
(function ($) {

    var modalWindow;
    var modalContainer;


    var initModalWithImages = function (e) {
        e.preventDefault();
        curentImageBlock = $(this).parent().parent();
        var callback = function (resData) {
            if (resData.length > 1) {
                modalContainer.html(resData);
                modalWindow.modal('show');
            } else {
                alert('Error to load images');
                modalWindow.modal('hide');
            }
        };
        postLoadImages(1, null, callback);
    };

    var postLoadImages = function (page, folder, callback) {
        if (page == 0) {
            return;
        }
        if (!page) {
            page = 1;
        }
        modalWindow = $('#exist-images');
        modalContainer = modalWindow.find('.modal-body');
        $.post(modalWindow.data('path') + '/' + page, {folder: folder}, callback);
    };

    var loadImagesByPage = function (e) {
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
        var folder = $('.js-btn-folders').find('.active').data('folder');
        var callback = function (resData) {
            if (resData.length > 1) {
                modalContainer.html(resData);
            } else {
                alert('Error to load images');
                modalWindow.modal('hide');
            }
        };
        postLoadImages(page, folder, callback);
    };

    var loadImagesByFolder = function (e) {
        e.preventDefault();
        var currentButton = $(this);
        if (currentButton.hasClass('active')) {
            return;
        }
        var folder = currentButton.data('folder');

        var callback = function (resData) {
            if (resData.length > 1) {
                modalContainer.html(resData);
            } else {
                alert('Error to load images');
                modalWindow.modal('hide');
            }
        };
        postLoadImages(1, folder, callback);
    };

    var processSelectImage = function (e) {
        e.preventDefault();
        var imageBlock = $(this).parents('div');
        var imageId = imageBlock.data('id');
        var imagePath = imageBlock.data('path');
        var idInput = curentImageBlock.find('input[type="hidden"]');
        var previewButton = curentImageBlock.find('button.additional_to_upload');
        idInput.val(imageId);
        previewButton.css(
            'background-image', 'url(' + imagePath + ')',
            'background-repeat', 'no-repeat',
            'background-size', 'contain'
        );
        $('#exist-images').modal('hide');
    };

    var processDelete = function (e) {
        e.preventDefault();
        var button = $(this);
        var delPath = button.attr('href');
        if (confirm('Remove image?')) {
            $.post(delPath, function (resData) {
                if (resData.errorCode == 204) {
                    button.parent().remove();
                    $('#exist-images').modal('show');
                } else if (resData.errorCode == 403) {
                    if (confirm('This image it used. Realy remove?')) {
                        $.post(delPath,{checkOnUsed:false}, function (resData) {
                            if (resData.errorCode == 204) {
                                button.parent().remove();
                                $('#exist-images').modal('show');
                            } else {
                                alert('Error to remove image');
                            }
                        })
                    }
                } else {
                    alert('Error to remove image');
                }
            });
        }
    };

    var _attachEvent = function () {
        $('.container').on('click', '.js-add-exist-image', initModalWithImages);
        $(document).on('click', '#image_pagination li', loadImagesByPage);
        $(document).on('click', '.js-select-image', processSelectImage);
        $(document).on('click', '.js-select-folder', loadImagesByFolder);
        $(document).on('click', '.js-delete-exist-image', processDelete);
    };
    var _initialize = function () {
        _attachEvent();
    };

    _initialize();
})
(jQuery);