'use strict';

var OfferModel = require('../../models/OfferModel.js');

module.exports = Backbone.View.extend({

	tagName: 'tr',
	template: Templates['offerslist__row'],

	events: {
		'click': 'clickedOfferRow',
		'dblclick': 'dblClickedOfferRow'
	},

	initialize: function() {
		this.model.on('change', this.render.bind(this));
	},

	render: function() {
		this.$el.html(this.template(this.model.toJSON()));
		return this;
	},

	clickedOfferRow: function() {
		this.trigger('SELECT_OFFER', this);
	},

	dblClickedOfferRow: function() {
		this.trigger('OPEN_OFFER', this.model);
	}

});
