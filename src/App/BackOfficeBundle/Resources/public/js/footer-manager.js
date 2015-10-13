/**
 * @package     OpenSolution
 * @author:     pm <pm@nxc.no>
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 26 06 2015
 */
;
(function ($) {

    "use strict";

    /*
     <tr>
     <th scope="row"><div class="input-sm">3</div></th>
     <td><input type="text" id="footer_content_0_links_3_title" name="footer_content[0][links][3][title]" class="form-control input-sm" value="Restauranter"></td>
     <td><input type="text" id="footer_content_0_links_3_href" name="footer_content[0][links][3][href]" class="form-control input-sm" value="/restauranter"></td>
     <td class="text-right"><button class="btn btn-danger btn-sm" type="button" data-app-link-remove=""><i class="glyphicon glyphicon-remove"></i></button></td>
     </tr>
     */
    function getRowHTML($block, is_file) {

        var
            blockID = $block.data().appBlock,
            linkId = $block.find('[data-app-link]').length;
        var type = 'text';

        if (is_file) {
            type = 'file';
        }
        var $linkTpl = $('<tr data-app-link="' + linkId + '"></tr>');

        $linkTpl.append('<th scope="row"><div class="input-sm">' + linkId + '</div></th>');

        $linkTpl.append('<td><input type="text" id="footer_content_' + blockID + '_links_' + linkId + '_title" name="footer_content[' + blockID + '][links][' + linkId + '][title]" class="form-control input-sm" /></td>');

        $linkTpl.append('<td><input type="' + type + '" id="footer_content_' + blockID + '_links_' + linkId + '_href" name="footer_content[' + blockID + '][links][' + linkId + '][href]" class="form-control input-sm" /></td>');

        $linkTpl.append('<td class="text-right"><button class="btn btn-danger btn-sm" type="button" data-app-link-remove=""><i class="glyphicon glyphicon-remove"></i></button></td>');

        return $linkTpl;
    }

    function getBlockHTML($form) {

        var
            blockID = $form.find('[data-app-block]').length;

        var $blockTpl = $('<div class="panel panel-default" data-app-block="' + blockID + '"></div>');

        $blockTpl.append('<div class="panel-heading"><div class="row form-group-sm"><div class="col-md-6"><input type="text" id="footer_content_' + blockID + '_title" name="footer_content[' + blockID + '][title]" class="form-control" value=""></div><div class="col-md-6 text-right"><button class="btn btn-danger btn-sm" type="button" data-app-block-remove>Remove block</button></div></div></div>');

        var $tableTpl = $('<table class="table"></table>');
        var $tableHeadTRTpl = $('<tr></tr>');
        $tableHeadTRTpl.append('<th scope="row" width="20"><div class="input-sm">#</div></th>');
        $tableHeadTRTpl.append('<th><div class="input-sm">Link title</div></th>');
        $tableHeadTRTpl.append('<th><div class="input-sm">URL</div></th>');
        $tableHeadTRTpl.append('<th width="20"></th>');
        var $tableHeadTpl = $('<thead></thead>');
        $tableHeadTpl.append($tableHeadTRTpl);

        var $tableBodyTRTpl = $('<tr data-app-link="0"></tr>');
        $tableBodyTRTpl.append('<th scope="row"><div class="input-sm">0</div></th>');
        $tableBodyTRTpl.append('<td><input type="text" id="footer_content_' + blockID + '_links_0_title" name="footer_content[' + blockID + '][links][0][title]" class="form-control input-sm" /></td>');
        $tableBodyTRTpl.append('<td><input type="text" id="footer_content_' + blockID + '_links_0_href" name="footer_content[' + blockID + '][links][0][href]" class="form-control input-sm" /></td>');
        $tableBodyTRTpl.append('<td class="text-right"><button class="btn btn-danger btn-sm" type="button" data-app-link-remove=""><i class="glyphicon glyphicon-remove"></i></button></td>');
        var $tableBodyTpl = $('<thead></thead>');
        $tableBodyTpl.append($tableBodyTRTpl);

        $tableTpl.append($tableHeadTpl);
        $tableTpl.append($tableBodyTpl);

        $blockTpl.append($tableTpl);

        $blockTpl.append('<div class="panel-body"><div class="text-right"><button class="btn btn-primary btn-sm" type="button" data-app-link-add="">Add link</button></div></div></div>');

        return $blockTpl;
    }

    var $app = $('[data-app="footer-manager"]');

    $app.on('click', '[data-app-link-add]', function () {

        var
            $block = $(this).parents('[data-app-block]'),
            $link = getRowHTML($block);

        $block.find('[data-app-link]:last').after($link);
    });

    $app.on('click', '[data-app-file-add]', function () {

        var
            $block = $(this).parents('[data-app-block]'),
            $link = getRowHTML($block, true);

        $block.find('[data-app-link]:last').after($link);
    });

    $app.on('click', '[data-app-link-remove]', function () {

        var
            $block = $(this).parents('[data-app-block]'),
            $link = $(this).parents('[data-app-link]');
        if ($link.find('input')[1].value.slice(1, 8) == "uploads") {
            $.post('/app_dev.php/backoffice/footer/removefile/' + $link.find('input')[1].value.slice(9),
                function (resData) {
                    if (resData.deleted) {
                        $link.remove();
                    } else {
                        alert('Error to remove file');
                    }
                });
        } else {
            $link.remove();
        }

        $block.find('[data-app-link]').each(function (i, el) {
            $(el).attr('data-app-link', i).find('th div').html(i);
        });


    });

    $app.on('click', '[data-app-block-add]', function () {

        var
            $form = $(this).parents('form'),
            $block = getBlockHTML($form);

        $form.find('[data-app-block]:last').after($block);

    });

    $app.on('click', '[data-app-block-remove]', function () {

        var
            $block = $(this).parents('[data-app-block]'),
            $form = $(this).parents('form');

        $block.remove();

        $form.find('[data-app-block]').each(function (i, el) {
            $(el).attr('data-app-block', i);
        });

    });

}(jQuery));