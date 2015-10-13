/**
 * @package     OpenSolution
 * @author:     pm <pm@nxc.no>
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 28 05 2015
 */
;(function($) {

    "use strict";

    var DRRManager = function(model, view) {
        this.model = model;
        this.view = view;
    };

    DRRManager.prototype = DRRManager.prototype;

    DRRManager.prototype.init = function() {
        this.model.init(this);
        this.view.init(this);
    };

    DRRManager.prototype.add = function(entity) {
        this.model.add(entity);
        this.view.add(entity);
    };

    DRRManager.prototype.remove = function(id) {
        this.view.remove(this.model.storage[id]);
        this.model.remove(id);
    };

    DRRManager.Model = function() {
        this.storage = {};
    };

    DRRManager.Model.prototype = DRRManager.Model.prototype;

    DRRManager.Model.prototype.init = function(controller) {

        this.controller = controller;

        this.load(this.controller.view.getData());
    };

    DRRManager.Model.prototype.load = function(data) {
        this.storage = data;
    };

    DRRManager.Model.prototype.add = function(entity) {

        entity.pri = entity.pri || 1;

        this.storage[entity.id] = entity;

        return this.storage[entity.id];
    };

    DRRManager.Model.prototype.remove = function(id) {

        delete this.storage[id];

        return id;
    };

    DRRManager.View = function() {

        this.entityTplSel = '.drr-listitem';

        this.listSel = '[data-app-list="drr"]';
        this.entitySel = this.listSel + ' [data-app-entity="drr"]';
        this.listEmptyMsgSel = '[data-app-block="no-drr"]';

        this.actionAddSel = '[data-app-action="add-entity"]';
        this.actionRemoveSel = '[data-app-action="remove-entity"]';

        this.sourceSel = '[data-app-source="drr"]';

        this.$entityTpl = $(this.entityTplSel);
        this.$entities = $(this.entitySel);
        this.$source = $(this.sourceSel);
        this.$listEmptyMsg = $(this.listEmptyMsgSel);
    };

    DRRManager.View.prototype = DRRManager.View.prototype;

    DRRManager.View.prototype.init = function(controller) {

        var view = this;

        this.controller = controller;

        $(this.actionAddSel).on('click', function() {

            var
                id = view.$source.val(),
                name = view.$source.find('option[value="' + id + '"]').text(),
                entity = {
                    id: id,
                    name: name
                }
            ;

            view.controller.add(entity);
        });

        $(this.listSel).on('click', this.actionRemoveSel, function() {

            var id = $(this).parents(view.entitySel).data("entityid");

            view.controller.remove(id);
        });
    };

    DRRManager.View.prototype.getData = function() {

        var data = {}, jqData;

        this.$entities.each(function(i, el) {

            jqData = $(el).data();

            data[jqData.entityid] = {
                id: jqData.entityid,
                name: jqData.entityname,
                pri: jqData.entitypri
            }
        });

        return data;
    };

    DRRManager.View.prototype.add = function(entity) {

        var $el = $(this.processTpl(this.$entityTpl.html(), entity));
        var $sources = this.$source.find('option');

        this.$entities.remove('[data-entityid="' + entity.id + '"]');

        this.$entities = this.$entities.add($el);
        $(this.listSel).append($el);

        $sources.remove('[value="' + entity.id + '"]');

        if (!--$sources.length) {
            $(this.actionAddSel).add(this.$source).prop('disabled', true);
        }

        this.$listEmptyMsg.hide();
    };

    DRRManager.View.prototype.remove = function(entity) {

        this.$entities.remove('[data-entityid="' + entity.id + '"]');
        this.$entities = this.$entities.filter('[data-entityid!="' + entity.id + '"]');

        this.$source.append('<option value="' + entity.id + '">' + entity.name + '</option>');

        $(this.actionAddSel).add(this.$source).prop( 'disabled', false );

        if (!this.$entities.length) {
            this.$listEmptyMsg.show();
        }
    };

    DRRManager.View.prototype.processTpl = function(tpl, data) {

        var re = new RegExp('\#{' + Object.keys(data).join('}|\#{') + '}', 'gi');

        return tpl.replace(re, function(matched){
            return data[matched.replace(/\#{|}/gi, '')] || '';
        });
    };

    var model = new DRRManager.Model();
    var view = new DRRManager.View();
    var controller = new DRRManager(model, view);

    controller.init();

    //$('[name$="[disabledFrom]"], [name$="[disabledTo]"]').parents('.input-group').datetimepicker({
    $('[name$="[disabledFrom]"], [name$="[disabledTo]"]').datetimepicker({
        sideBySide: true,
        format: 'DD/MM/YYYY HH:mm',
        stepping: 5
    });

}(jQuery));
