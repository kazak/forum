/**
 * @package     OpenSolution
 * @author:     pm <pm@nxc.no>
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 13 07 2015
 */

;(function($) {

    "use strict";

    $('.ajaxgetter').on('click', function() {

        var
            $this = $(this),
            source = $this.data().source;

        if (!source)
            return false;

        $.get(source, function(data) {
            console.log(data);
        });

        return false;
    });

    $('.ajaxposter').on('submit', function() {

        var
            $this = $(this),
            post = $this.serialize();

        $.post($this.attr('action'), post, function(data) {
            console.log(data);
        });

        return false;
    });

}(jQuery));