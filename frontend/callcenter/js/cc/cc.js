'use strict';

var RestaurantCollection = require('./collections/RestaurantCollection.js');
var OffersListController = require('./controllers/offerslist/OffersListController.js');
var OfferPopupController = require('./controllers/popup/offer/OfferPopupController.js');

module.exports = {

	restaurants: null,
	offersList: null,
	offerPopup: null,
	logCount: 0,

	initialize: function() {
		this.initSocketConnection();
	},

	log: function(str, spin) {
		$('#debug-log').prepend('<span><strong>'+(++this.logCount)+':</strong> '+str+'</span> ');
		$('#debug-log').find('.spin').remove();
		if (spin) {
			$('#debug-log').prepend('<span class="spin"></span>');
		}
		console.log('CC.log:', str);
	},

	initSocketConnection: function() {
		window.CC.log('Connecting to server.');
        Backbone.RPC2.setClient(new WS.connect('ws://'+window.WS_HOST+window.WS_PATH));
        Backbone.RPC2.socket.on('socket/connect', this.initRestaurants.bind(this));
        Backbone.RPC2.socket.on('socket/disconnect', function (error) {
            console.log('Disconnected for ' + error.reason + ' with code ' + error.code);
        });
	},

	initRestaurants: function() {
		window.CC.log('Connected - fetching restaurants.');
		this.restaurants = new RestaurantCollection();
		this.restaurants.once('sync', this.initControllers.bind(this));
		this.restaurants.fetch();
	},

	initControllers: function() {
		window.CC.log('Fetched restaurants - fetching orders.');
		this.offersList = new OffersListController();
		this.offerPopup = new OfferPopupController();
		this.offersList.on('OPEN_OFFER', this.offerPopup.openOffer.bind(this.offerPopup));
	}
};