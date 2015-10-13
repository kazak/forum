/**
 * @package     OpenSolution
 * @author:     pm <pm@nxc.no>
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 09 06 2015
 */

;(function($) {

    "use strict";

    var RestaurantsManager = function(model, view) {
        this.model = model;
        this.view = view;
    };

    RestaurantsManager.prototype = RestaurantsManager.prototype;

    RestaurantsManager.prototype.init = function() {
        this.model.init(this);
        this.view.init(this);
    };

    RestaurantsManager.Model = function(options) {
        this.settings = options || {};
        this.storage = {};
    };

    RestaurantsManager.Model.prototype = RestaurantsManager.Model.prototype;

    RestaurantsManager.Model.prototype.init = function(controller) {
        this.controller = controller;

        this.data = this.settings.source || {};
        this.data.__proto__ = null;

        for( var id in this.data ) {
            this.storage = {
                location: this.data['entity'].location
            }
        }
    };

    RestaurantsManager.View = function(options) {

        this.settings = options || {};

        this.map = null;
        this.mapOptions = {
            zoom: 15,
            scrollwheel: false,
            center: this.settings.mapCenter || {
                lat: 59.913916, lng: 10.713189
            },
            streetViewControl: false,
            panControl: false,
            mapTypeControl: false,
            zoomControlOptions: {
                style: google.maps.ZoomControlStyle.SMALL
            }
        };

        this.markers = [];

        this.mapSel = '[data-app-block="map"]';
        this.listSel = '[data-app-list="restaurant"]';
        this.itemSel = this.listSel + ' [data-app-item="restaurant"]';
        this.searchSel = '[data-app-input="restaurant-search"]';

        this.locationContainerSel = '.location';
        this.distanceContainerSel = 'span.distance';

        this.$map = $(this.mapSel);
        this.$list = $(this.listSel);
        this.$items = $(this.itemSel);
        this.$search = $(this.searchSel);
    };

    RestaurantsManager.View.prototype = RestaurantsManager.View.prototype;

    RestaurantsManager.View.prototype.init = function(controller) {

        this.controller = controller;

        this.map = new google.maps.Map(this.$map[0], this.mapOptions);
        this.infobox = new google.maps.InfoWindow;
        this.autoComplete = null;
        this.geocoder = new google.maps.Geocoder;

        var view = this;

        google.maps.event.addListener(this.map, 'click', function() {
            view.infobox.close();
        });

        this.entries = [];



            this.entries.push(controller.model.storage);


        for( var i in this.entries ) {
            this.addMapMarker(this.entries[i]);
        }

        "undefined" != typeof google.maps.places && this.initAutocomplete();

        this.$search.parents('form').on('submit', function() {
            view.locate(view.$search.val(), view.sortEntriesByDistanceTo.bind(view));
            return false;
        });
    };

    RestaurantsManager.View.prototype.initAutocomplete = function() {
        this.autoComplete = new google.maps.places.Autocomplete(this.$search[0]);
    };

    RestaurantsManager.View.prototype.addMapMarker = function(entry) {

        if (!entry.location.lat || !entry.location.lng) {
            return;
        }

        var view = this;

        var marker = {
            position: entry.location
        };

        marker = new google.maps.Marker(marker);
        marker.clickListener = google.maps.event.addListener(marker, 'click', function() {
            view.highlight(entry);
        });

        entry.marker = marker;

        marker.setMap(this.map);
    };

    RestaurantsManager.View.prototype.highlight = function(entry) {
        var
            infobox = this.infobox,
            $node = this.getNode(entry);

        infobox.close();
        infobox.setContent($node.html() || entry.id);
        infobox.open(this.map, entry.marker);

        $node.addClass('highlighted').siblings().removeClass('highlighted');
    };

    RestaurantsManager.View.prototype.getNode = function(entry) {

        if( entry.node ) {
            return entry.node;
        }


        entry.node = $('#entity');
        return entry.node;
    };

    RestaurantsManager.View.prototype.toRad_ = function(coord) {
        return coord * Math.PI / 180;
    };

    RestaurantsManager.View.prototype.distance = function(a, b) {
        var
            c = this.toRad_(b.lat()),
            d = this.toRad_(b.lng()),
            b = this.toRad_(a.lat()),
            e = this.toRad_(a.lng());

        a = b - c;
        d = e - d;
        c = Math.sin(a/2) * Math.sin(a/2) + Math.cos(c) * Math.cos(b) * Math.sin(d/2) * Math.sin(d/2);
        return 12742 * Math.atan2(Math.sqrt(c), Math.sqrt(1 - c))
    };

    var model = new RestaurantsManager.Model({source: restaurantsData});
    var view = new RestaurantsManager.View({mapCenter: restaurantsMapCenter});
    var controller = new RestaurantsManager(model, view);

    controller.init();

}(jQuery));

