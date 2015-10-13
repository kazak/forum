/**
 * @package     Dolly
 * @author:     aat <aat@norse.digital>
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 26 08 2015
 */

(function ($) {

    var setDefault = function (e) {
        e.preventDefault();
        var currentButton = $(this);
        var defaultIdent = currentButton.parent().parent().parent().find('.js-default-text');
        var path = currentButton.attr('href');
        $.post(path, function (resData) {
            if (resData.errorCode == 200) {
                $('.js-set-default').removeClass('hidden');
                $('.js-default-text').addClass('hidden');
                defaultIdent.removeClass('hidden');
                currentButton.addClass('hidden');
            } else {
                alert('Error to set as default page');
            }
        });
    };

    var changePriority = function (e) {
        e.preventDefault();
        var currentButton = $(this);
        var tableRow = currentButton.parent().parent().parent();
        var path = currentButton.attr('href');
        $.post(path, function (resData) {
            if (resData.errorCode == 200) {
                if (path.slice(-15, -1) == "upblockpriorit") {
                    tableRow.after(tableRow.prev());
                } else {
                    tableRow.before(tableRow.next());
                }

            } else {
                alert('Error to change priority');
            }
        });
    };

    var deleteBlock = function (e) {
        e.preventDefault();
        var currentButton = $(this);
        var tableRow = currentButton.parent().parent().parent();
        var path = currentButton.attr('href');
        if (confirm('Remove this block?')) {
            $.post(path, function (resData) {
                if (resData.errorCode == 204) {
                    tableRow.remove();
                } else {
                    alert('Error to delete block');
                }
            });
        }
    };

    var deletePage = function (e) {
        e.preventDefault();
        var currentButton = $(this);
        var tableRow = currentButton.parent().parent().parent();
        var isDefault = currentButton.parent().find('.js-set-default').hasClass('hidden');
        var path = currentButton.attr('href');
        if (!isDefault) {
            if (confirm('Remove this page?')) {
                $.post(path, function (resData) {
                    if (resData.errorCode == 204) {
                        tableRow.remove();
                    } else {
                        alert('Error to delete block');
                    }
                });
            }
        } else {
            alert('Can not be removed default page');
        }

    };

    var deleteHero = function (e) {
        e.preventDefault();
        var currentButton = $(this);
        var path = currentButton.attr('href');
        if (confirm('Remove hero image?')) {
            $.post(path, function (resData) {
                if (resData.errorCode == 200) {
                    $('.additional_to_upload').css('background-image', 'url()');
                    $('#app_front_page_type_hero_image_id').val('');
                } else {
                    alert('Error to delete block');
                }
            });
        }

    };

    var _attachEvent = function () {
        $('.container').on('click', '.js-set-default', setDefault);
        $('.container').on('click', '.js-change-priority', changePriority);
        $('.container').on('click', '.js-delete-block', deleteBlock);
        $('.container').on('click', '.js-delete-page', deletePage);
        $('.container').on('click', '.js-delete-hero', deleteHero);
    };
    var _initialize = function () {
        _attachEvent();
    };

    _initialize();
})
(jQuery);