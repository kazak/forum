/**
 * @package     Dolly
 * @author:     aat <aat@norse.digital>
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 27 09 2015
 */

(function ($) {

    var processDelete = function (e) {
        e.preventDefault();
        var button = $(this);
        var delPath = button.attr('href');
        if (confirm('Really delete?')) {
            $.post(delPath, function (resData) {
                if (resData.errorCode == 204) {
                    removeRow(button);
                } else {
                    alert('Error delete setting');
                }
            });
        }
    };

    var getSettingsPopup = function () {
        var path = $(this).attr('href');
        var container = $('#product-default-settings--action').find('.modal-content form');
        $.post(path, function (resData) {
            if (resData.length > 1) {
                container.html(resData);
                $('#product-default-settings--action').modal('show');
            } else {
                alert('Error to load setting');
            }
        });
    };

    var getOptionsPopup = function () {
        var path = $(this).attr('href');
        var container = $('#product-options-settings--action').find('.modal-content form');
        $.post(path, function (resData) {
            if (resData.length > 1) {
                container.html(resData);
                $('#product-options-settings--action').modal('show');
            } else {
                alert('Error to load setting');
            }
        });
    };

    var getTableHtmlRow = function (type, serialName, name) {
        var row = '<tr>' +
            '<td>';
        if (type == 'checkbox') {
            row = row + '<div class="checkbox"><label>' +
                '<input type="' + type + '" name="settings[' + serialName + ']" class="form-control input-sm"' +
                ' placeholder="Field name"' +
                'style="height: 16px; width: 16px;"' +
                'id="new-field-' + serialName + '">' +
                serialName +
                '</label></div>';
        } else {
            row = row + '<label>' + name + '</label>' +
                '<input type="' + type + '" name="settings[' + serialName + ']" class="form-control input-sm"' +
                ' placeholder="Field value"' +
                'id="new-field-' + serialName + '">';
        }
        row = row +
            '</td>' +
            '<td>' +
            '</td>' +
            '<td>' +
            '<div class="btn-group btn-group-sm" role="group">' +
            '<a class="btn btn-success js-remove-field" href="#" title="Remove field" role="button">' +
            '<i class="glyphicon glyphicon-minus"></i></a>' +
            '</div>' +
            '</td>' +
            '</tr>';
        return row;
    };

    var processAddField = function () {
        var row = $(this).parents('tr');
        var fieldType = row.find('select').val();
        var fieldName = row.find('input').val();
        if (fieldName.length < 2) {
            return;
        }
        var serializeName = fieldName.toLowerCase().replace(' ', '_');
        var newRow = getTableHtmlRow(fieldType, serializeName, fieldName);
        row.before(newRow);
    };

    var getTableHtmlOptionRow = function (val) {
        return '<tr>' +
            '<td>' +
            '<input type="text" name="values[]" class="form-control input-sm"' +
            ' placeholder="Option value" value="' + val + '">' +
            '</td>' +
            '<td>' +
            '<div class="btn-group btn-group-sm" role="group">' +
            '<a class="btn btn-success js-remove-field" href="#" title="Remove field" role="button">' +
            '<i class="glyphicon glyphicon-minus"></i></a>' +
            '</div>' +
            '</td>' +
            '</tr>';
    };

    var processAddOptionValue = function () {
        var row = $(this).parents('tr');
        var fieldValue = row.find('input').val();
        var newRow = getTableHtmlOptionRow(fieldValue);
        row.before(newRow);
    };

    var removeRow = function (button) {
        if (button.originalEvent instanceof MouseEvent) {
            button = $(this);
        }
        var row = button.parents('tr');
        row.remove();
    };

    var serializeForm = function (form) {
        var inputs = form.find('input');
        var inputsVal = {};
        inputsVal['settings'] = {};
        inputsVal['settings']['availability'] = {};
        var writeLike = function (inp, val, bol, text, key, key1, key2) {
            if (bol) {
                if (key1) {
                    if (key2) {
                        inputsVal[key][key1][key2] = val.checked;
                    } else {
                        inputsVal[key][key1] = val.checked;
                    }
                } else {
                    inputsVal[key] = val.checked;
                }
            } else if (text) {
                if (key1) {
                    if (key2) {
                        inputsVal[key][key1][key2] = val.value;
                    } else {
                        inputsVal[key][key1] = val.value;
                    }
                } else {
                    inputsVal[key] = val.value;
                }
            }
            return inp;
        };
        $.each(inputs, function (unuse, val) {
            var writeLikeBool = val.type == 'checkbox';
            var writeLikeString = val.type == 'text' && val.name != 'newField';
            if (val.name.substr(0, 8) == "settings") {
                var kay = val.name.slice(8);
                if (kay.indexOf("[") > -1) {
                    var secondKay = kay.slice(kay.indexOf('[') + 1, kay.indexOf("]"));
                    var tempStringKey = kay.slice(kay.indexOf('[') + 1).slice(kay.indexOf(']'));
                    if (tempStringKey.indexOf("[") > -1) {
                        var thirdKay = tempStringKey.slice(tempStringKey.indexOf('[') + 1, tempStringKey.indexOf("]"));
                        writeLike(inputsVal, val, writeLikeBool, writeLikeString, 'settings', secondKay, thirdKay);
                    } else {
                        writeLike(inputsVal, val, writeLikeBool, writeLikeString, 'settings', secondKay);
                    }
                } else {
                    writeLike(inputsVal, val, writeLikeBool, writeLikeString, 'settings', val.name.slice(val.name.indexOf("[") + 1, val.name.indexOf("]")));
                }
            } else {
                writeLike(inputsVal, val, writeLikeBool, writeLikeString, val.name);
            }
        });
        return inputsVal;
    };

    var processSubmit = function (e) {
        e.preventDefault();
        var form = $(this);
        var path = form.find('#form-submit-path').val();

        var container = $('#product-default-settings--action').find('.modal-content form');
        $.post(path, {data: serializeForm(form)}, function (resData) {
            if (resData.length > 1) {
                container.html(resData);
                $('#product-default-settings--action').modal('show');
                _initSettingsList();
            } else {
                alert('Error to load setting');
            }
        });
    };

    var processOptionSubmit = function (e) {
        e.preventDefault();
        var form = $(this);
        var path = form.find('#form-submit-path').val();

        var container = $('#product-options-settings--action').find('.modal-content form');
        $.post(path, {
            data: {
                name: $('#options-form input[name=name]').val()
            }
        }, function (resData) {
            if (resData.length > 1) {
                container.html(resData);
                $('#product-options-settings--action').modal('show');
                _initOptionsList();
            } else {
                alert('Error to load setting');
            }
        });
    };
    var _initSettingsList = function () {
        var parentPopup = $('#product-default-settings');
        var pathToGetList = parentPopup.data('pathtosettingslist');
        $.post(pathToGetList, function (resData) {
            parentPopup.find('.modal-body').html(resData);
        });
    };
    var _initOptionsList = function () {
        var parentPopup = $('#product-options-settings');
        var pathToGetList = parentPopup.data('pathtooptionslist');
        $.post(pathToGetList, function (resData) {
            parentPopup.find('.modal-body').html(resData);
        });
    };
    var _attachEvent = function () {
        $(document).on('click', '.js-delete-setting', processDelete);
        $(document).on('click', '.js-delete-option', processDelete);
        $(document).on('click', '.js-edit-setting', getSettingsPopup);
        $(document).on('click', '.js-create-setting', getSettingsPopup);
        $(document).on('click', '.js-edit-option', getOptionsPopup);
        $(document).on('click', '.js-create-option', getOptionsPopup);
        $(document).on('click', '.js-add-new-field', processAddField);
        $(document).on('click', '.js-add-new-option-value', processAddOptionValue);
        $(document).on('click', '.js-remove-field', removeRow);
        $(document).on('submit', '#settings-form', processSubmit);
        $(document).on('submit', '#options-form', processOptionSubmit);
    };
    var _initialize = function () {
        _initSettingsList();
        _initOptionsList();
        _attachEvent();
    };

    _initialize();
})
(jQuery);