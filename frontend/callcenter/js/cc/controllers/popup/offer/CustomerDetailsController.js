'use strict';

module.exports = Backbone.View.extend({

	template: Templates['popup__offer__customer'],
	
	render: function() {
		var data = this.model ? this.model.toJSON() : {};
		this.$el.html(this.template(data));
		return this;
	}
});
