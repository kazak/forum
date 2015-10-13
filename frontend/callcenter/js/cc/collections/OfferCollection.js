'use strict';

var BaseCollection = require('./BaseCollection.js');
var OfferModel = require('../models/OfferModel.js');

module.exports = BaseCollection.extend({

	model: OfferModel,

	sub: {
		'topic/offer': 'addList'
	},

	initialize: function() {
		this.on('update', function() {
			window.CC.log('Updated offer list.');
		});

		BaseCollection.prototype.initialize.apply(this, arguments);
	}
});
