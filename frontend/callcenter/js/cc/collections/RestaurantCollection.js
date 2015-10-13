'use strict';

var BaseCollection = require('./BaseCollection.js');
var RestaurantModel = require('../models/RestaurantModel.js');

module.exports = BaseCollection.extend({

	model: RestaurantModel,

	rpcOptions: {
		methods: {
			read: {
				method: 'callcenter/getRestaurants'
			}
		}
	},

	initialize: function() {
		BaseCollection.prototype.initialize.apply(this, arguments);
		var that = this;
		that
			.on('sync', function(){
				//console.log('Restaurants sync', that.models);
			})
			.on('error', function(resp){
				console.log('Restaurants error', resp);	
			});
	}

});
