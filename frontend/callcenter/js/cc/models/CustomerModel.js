'use strict';

var BaseModel = require('./BaseModel.js');

module.exports = BaseModel.extend({

	rpcOptions: {
		methods: {
			read: {
				method: 'callcenter/getCustomer',
				params: {
					id: 'attributes.id'
				}
			}
		}
	},

	initialize: function(attributes, options) {
		BaseModel.prototype.initialize.apply(this, arguments);
	}

});
