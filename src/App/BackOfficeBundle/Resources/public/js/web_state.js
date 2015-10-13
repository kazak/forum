/**
 * @package     Dolly
 * @author:     dss <dss@nxc.no>
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 02 06 2015
 */

$('#update-message').on('click', function() {
    "use strict";

    $('#close-web').val(0);
    $('#update-status').submit();
});

$('#message').on('change', function() {
    if ($(this).val()) {
        $('input[type=submit], input[type=button]').prop("disabled", false);
    } else {
        $('input[type=submit], input[type=button]').prop("disabled", true);
        if ($('.alert.alert-warning').attr('data-content')) {
            $('input[type=submit]').prop("disabled", false);
        }
    }
});