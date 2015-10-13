'use strict';

var PopupController = require('../PopupController.js');
var CustomerDetailsController = require('./CustomerDetailsController.js');
var OfferDetailsController = require('./OfferDetailsController.js');
var OfferArchiveController = require('./OfferArchiveController.js');

var CustomerModel = require('../../../models/CustomerModel.js');

module.exports = PopupController.extend({

	template: Templates['popup__offer__container'],

	customerDetails: null,
	offerDetails: null,
	offerArchive: null,

	offer: null,
	customer: null,

	initialize: function() {
		PopupController.prototype.initialize.apply(this, arguments);
		this.customer = new CustomerModel();
	},

	render: function() {
		PopupController.prototype.render.apply(this, arguments);
		this.$content.html(this.template());
		this.$content.find('#customer-details').render(this.customerDetails.render().el);
		this.$content.find('#offer-details').render(this.offerDetails.render().el);
		this.$content.find('#offer-archive').render(this.offerArchive.render().el);
		window.Helpers.initialize(this.$el);
		return this;
	},

	close: function() {
		if (this.customerDetails) {
			this.customerDetails.remove();
			this.customerDetails = null;
		}
		if (this.offerDetails) {
			this.offerDetails.remove();
			this.offerDetails = null;
		}
		if (this.offerArchive) {
			this.offerArchive.remove();
			this.offerDetails = null;
		}
		if (this.offer) {
			this.offer.off('sync');
		}
		if (this.customer) {
			this.customer.off('sync');
			this.customer.clear({silent:true});
		}
		
		PopupController.prototype.close.apply(this, arguments);
	},

	openOffer: function(offer) {
		window.CC.log('Opening offer #'+offer.id);

		this.offer = null;
		if (offer) {
			this.offer = offer;
			this.customer.set('id', this.offer.get('c_id'));
		}

		this.customerDetails = new CustomerDetailsController({
			model: this.customer
		});
		this.offerDetails = new OfferDetailsController({
			model: this.offer
		});
		this.offerArchive = new OfferArchiveController({
			model: this.customer
		});

		if (offer) {
			this.customer.fetch();
			this.offer
				.once('sync', this.runOpen.bind(this))
				.fetch();
		} else {
			this.runOpen();
		}
	},

	runOpen: function() {
		this.render().open();
	}
	
});
