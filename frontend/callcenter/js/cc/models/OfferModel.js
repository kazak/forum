'use strict';

var BaseModel = require('./BaseModel.js');

module.exports = BaseModel.extend({

	rpcOptions: {
		methods: {
			read: {
				method: 'callcenter/getOffer',
				params: {
					id: 'attributes.o_id'
				}
			}
		}
	},
		
	idAttribute: 'o_id',

	initialize: function(attributes, options) {
		BaseModel.prototype.initialize.apply(this, arguments);

		this.on('change:s_rid', this.setRestaurant.bind(this));
		if (attributes.s_rid) {
			this.setRestaurant();
		}

		this.on('change:o_date', this.formatDate.bind(this));
		if (attributes.o_date) {
			this.formatDate();
		}

		this.on('change:o_time', this.formatTime.bind(this));
		if (attributes.o_time) {
			this.formatTime();
		}

		this.on('change:d_date', this.formatDeliveredDateTime.bind(this));
		if (attributes.d_date) {
			this.formatDeliveredDateTime();
		}
		
		this
			.on('sync', function(resp){
				console.log('Offer sync', resp);
			})
			.on('error', function(resp){
				console.log('Offer sync error', resp);
			});
	},

	setRestaurant: function() {
		var rid = this.get('s_rid');
		var r = window.CC.restaurants.get(rid);
		this.set('formatted_s_rtitle', r ? r.get('title') : rid);
	},

	formatDate: function() {
		var d = this.get('o_date');
		var date = new Date(d);
		this.set('formatted_o_date', 
			('0' + date.getUTCDate()).slice(-2) + '.' +
			('0' + (date.getUTCMonth()+1)).slice(-2) + '.' +
			date.getUTCFullYear().toString().slice(-2)
		);
	},

	formatTime: function() {
		var d = this.get('o_time');
		this.set('formatted_o_time', 
			d.split(':').slice(0,2).join(':')
		);
	},

	formatDeliveredDateTime: function() {
		var d = this.get('d_date');
		var date = new Date(d);
		this.set('formatted_d_date', 
			('0' + date.getUTCDate()).slice(-2) + '.' +
			('0' + (date.getUTCMonth()+1)).slice(-2) + '.' +
			date.getUTCFullYear().toString().slice(-2) + ' ' +
			("0" + date.getUTCHours()).slice(-2) + ':' +
			("0" + date.getUTCMinutes()).slice(-2)
		);
	}

});
