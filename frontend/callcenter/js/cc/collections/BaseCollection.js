'use strict';

module.exports = Backbone.RPC2.Collection.extend({

	initialize: function() {
		Backbone.RPC2.Collection.prototype.initialize.apply(this, arguments);
	},

	addList: function(topic, payload) {
		this.set(payload.data,{remove: false});
	}
});
