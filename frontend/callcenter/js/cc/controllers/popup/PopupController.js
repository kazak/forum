'use strict';

module.exports = Backbone.View.extend({

	el: '#popup',
	containerTemplate: Templates['popup__popup'],
	$content: null,

	initialize: function() {
		this.$el.on('CLOSED_TOGGLE', this.close.bind(this));
	},

	render: function() {
		this.$el.html(this.containerTemplate());
		this.$content = this.$('#popup-content');
		return this;
	},

	open: function() {
		window.Helpers.runToggle('popup-open', this.$el);
	},

	close: function() {}
	
});
