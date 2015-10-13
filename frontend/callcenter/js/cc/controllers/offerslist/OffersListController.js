'use strict';

var OfferCollection = require('../../collections/OfferCollection.js');
var OffersRowController = require('./OffersRowController.js');

module.exports = Backbone.View.extend({

	el: '#offers-view',
	listEl: '#offers-list-container',
	$list: null,
	collection: null,
	selectedOffer: null,

	initialize: function() {
		this.$list = this.$(this.listEl);
		this.collection = new OfferCollection();
		this.collection.on('add', this.addOffer.bind(this));
	},

	addOffer: function(model) {
		var row = new OffersRowController({model: model});
		row
			.on('SELECT_OFFER', this.selectOffer.bind(this))
			.on('OPEN_OFFER', this.openOffer.bind(this));
		this.$list.append(row.render().el);
	},

	selectOffer: function(row) {
		row.$el
			.addClass('selected')
			.siblings().removeClass('selected');
		this.selectOffer = row.model;
	},

	openOffer: function(offer) {
		this.trigger('OPEN_OFFER', offer);
	}
});
