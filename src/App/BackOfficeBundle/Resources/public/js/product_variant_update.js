(function(window){

// ---------------------------------------------------- HELPERS

	var transformUrl = function(url) {
        var re = new RegExp('app.*\.php', 'gi'),
            pathArray = window.location.pathname.split('/');
        if (re.test(pathArray[1])) {
            url = '/' + pathArray[1] + url;
        }
        return url;
    };

	var injectUrl = function(url, data) {
        for (var d in data) {
        	url = url.replace('{:'+d+'}', data[d]);
        }
        return url;
    };

	var parseResponse = function(response) {
		if (!response.error && response.data) {
			return response.data;
		}
		return response;
	};

	var compileTemplate = function(selector) {
		var $template = $(selector);
		return $template.length ? Handlebars.compile($template.html()) : null;
	};

	var serializeInputData = function($elm) {
		var data = {};
		$elm.find('[name]').each(function(index, elm) {
			var $elm = $(elm);
			data[$elm.attr('name')] = $elm.val();
		});
		return data;
	}

	var createOsAutocomplete = function($elm) {
		var $input = $('<input>');
			$input
				.attr('type', 'hidden')
				.attr('name', $elm.attr('name')+'_id')
				.insertAfter($elm);

		$elm.typeahead({
			ajax: {
				url: transformUrl(window.API_PATH_OSPRODUCTS),
				preProcess: function(data) {
					return parseResponse(data);
				}
			},
			onSelect: function(item) {
				$input.val(item.value);
			}
		});
		return $elm.data('typeahead');
	}

// ---------------------------------------------------- PRODUCT VARIANT MODEL

	var ProductVariantModel = Backbone.Model.extend({

		parse: parseResponse,
		productId: null,

		urlRoot: function() {
			var that = this;
			return transformUrl(injectUrl(window.API_PATH_VARIANTS, { productId: that.productId }));
		},

		initialize: function(options) {
			var that = this;
			that.productId = options.productId;
		},

		save: function(attrs, options) {
			var that = this;
			that.set(attrs);
			Backbone.Model.prototype.save.call(that, null, options);
		}
	});

// ---------------------------------------------------- PRODUCT VARIANT COLLECTION

	var ProductVariantCollection = Backbone.Collection.extend({

		model: ProductVariantModel,
		parse: parseResponse,
		productId: null,

		url: function() {
			var that = this;
			return transformUrl(injectUrl(window.API_PATH_VARIANTS, { productId: that.productId }));
		},

		initialize: function(options) {
			var that = this;
			that.productId = options.productId;
			that
				.on('add', function(model) {
					model.productId = that.productId;
				})
				.on('MOVE_UP', function(model) {
					console.log('MOVE_UP', model);
				})
				.on('MOVE_DOWN', function(model) {
					console.log('MOVE_DOWN', model);
				});
		}
	});

// ---------------------------------------------------- PRODUCT VARIANT VIEW

	var ProductVariantView = Backbone.View.extend({

		tagName: 'tr',
		template: compileTemplate('#productVariantTemplate'),

		events: {
			'click [data-product-variant-edit-settings]': 'editSettings',
			'click [data-product-variant-remove]': 'removeVariant',
			'click [data-product-variant-move-up]': 'moveUp',
			'click [data-product-variant-move-down]': 'moveDown'
		},

		initialize: function() {
			var that = this;
			that.model.on('destroy', that.remove, that);
		},

		render: function() {
			var that = this;
			that.$el.html(that.template(that.model.toJSON()));
			return that;
		},

		removeVariant: function() {
			var that = this;
			var check = confirm('Are you sure you want to delete variant "'+that.model.get('name')+'"?');
			if (check) {
				that.model.destroy();
			}
			return false;
		},

		editSettings: function() {
			var that = this;
			that.model.trigger('EDIT_SETTINGS', that.model);
			return false;
		},

		moveUp: function() {
			var that = this;
			that.model.trigger('MOVE_UP');
			return false;
		},

		moveDown: function() {
			var that = this;
			that.model.trigger('MOVE_DOWN');
			return false;
		}
	});

// ---------------------------------------------------- PRODUCT VARIANT CREATOR VIEW

	var ProductVariantCreateView = Backbone.View.extend({

		tagName: 'tr',
		template: compileTemplate('#productVariantCreateTemplate'),
		model: null,
		osAutocomplete: null,

		events: {
			'click [data-product-variant-add]': 'addVariant'
		},

		initialize: function(options) {
			var that = this;
			that.productId = options.productId;
			that.model = new ProductVariantModel({
				productId: that.productId
			});
			that.model.on('sync', that.variantAdded, that);
		},

		render: function() {
			var that = this;
			if (that.osAutocomplete) {
				that.osAutocomplete.destroy();
			}
			that.$el.html(that.template());
			that.osAutocomplete = createOsAutocomplete(that.$('#os-product'));
			return that;
		},

		addVariant: function() {
			var that = this,
				data = serializeInputData(that.$el);

			console.log('saving data', data);

			that.model.save(data);
			return false;
		},

		variantAdded: function() {
			var that = this;
			that.trigger('VARIANT_ADDED', that.model.clone());
			that.model.clear();
		}
	});

// ---------------------------------------------------- PRODUCT VARIANT EDIT VIEW

	var ProductVariantEditView = Backbone.View.extend({

		el: '#product-variant-editor-container',
		template: compileTemplate('#productVariantEditTemplate'),
		settingTemplateBoolean: compileTemplate('#productVariantEditSettingBooleanTemplate'),
		settingTemplateText: compileTemplate('#productVariantEditSettingTextTemplate'),
		osAutocomplete: null,

		events: {
			'change #settings_template_id': 'changeSettingsTemplate',
			'click .js-reset-to-default-product-variant': 'resetSettings',
			'submit form': 'submitVariant'
		},

		open: function(model) {
			var that = this;
			that.model = model;
			that.render();
			that.osAutocomplete = createOsAutocomplete(that.$('#os-product'));
			that.model
				.on('sync', that.modelSaved, that)
				.on('change:settings', that.renderSettings.bind(that));
			that.$('.modal')
				.modal('show')
				.on('hide.bs.modal', function() {
					that.close();
				});
		},

		close: function() {
			var that = this;
			that.$('.modal').off('hide.bs.modal');
			that.model.off('sync change:settings');
			that.osAutocomplete.destroy();
		},

		modelSaved: function() {
			var that = this;
			that.$('.modal').modal('hide');
		},

		render: function() {
			var that = this;
			that.$el.html(that.template(that.model.toJSON()));
			that.renderSettings();
			return that;
		},

		renderSettings: function() {
			var that = this;
			var settings = that.sortSettings(that.model.get('settings'));

			that.$('#settings-container').html('');
			for (var key in settings) {
				that.$('#settings-container').append(that.renderSettingItem(key, key, settings[key]));
			}
		},

		sortSettings: function(settings) {
			var that = this,
				sortedObj = {},
				keys = _.keys(settings);

			keys = _.sortBy(keys, function(key){
				return key;
			});

			_.each(keys, function(key) {
				if(typeof settings[key] === 'object' && settings[key] && !(settings[key] instanceof Array)){
					sortedObj[key] = that.sortSettings(settings[key]);
				} else {
					sortedObj[key] = settings[key];
				}
			});

			return sortedObj;
		},

		parseSettings: function(settings, template) {
			var that = this;
			for (var k in template) {

				if (typeof template[k] === 'object' && template[k] !== null) {
					if (!settings[k]) {
						settings[k] = {};
					}
					settings[k] = that.parseSettings(settings[k], template[k]);
				} else if(typeof settings[k] === 'undefined') {
					switch (typeof template[k]) {
						case 'boolean':
							settings[k] = false;
							break;
						case 'string':
							settings[k] = '';
							break;
						case 'object':
							settings[k] = null;
							break;
					}
				}
			}
			return settings;
		},

		renderSettingItem: function(name, key, val) {
			var that = this;
			var template;
			var html = '';
			if (typeof val === 'object' && val !== null) {
				for (var i in val) {
					html += that.renderSettingItem(name+' - '+i, key+']['+i, val[i]);
				}
			} else {
				switch (typeof val) {
					case 'boolean':
						template = that.settingTemplateBoolean;
						break;
					case 'string':
					case 'object':
						template = that.settingTemplateText;
				}
				html += template({
					name: name,
					key: key,
					val: val
				});
			}
			return html;
		},

		/**
		 * TODO: Overwrite settings when settings_template is changed.
		 */
		changeSettingsTemplate: function() {

		},

		submitVariant: function() {
			var that = this,
				data = that.$('form').serializeObject();
			data.settings = that.parseSettings(data.settings, that.model.get('settings_template').storage);
			console.log('update variant', data);
			that.model.save(data);
			return false;
		},
		resetSettings: function() {
			var that = this,
				data = that.$('form').serializeObject();
			data.settings = null;
			console.log('update variant', data);
			that.model.save(data);
			return false;
		}
	});

// ---------------------------------------------------- PRODUCT VIEW

	var ProductView = Backbone.View.extend({

		el: '[data-product]',
		id: null,
		collection: null,
		variantCreator: null,
		variantContainer: null,
		variantEditor: null,

		initialize: function() {
			var that = this;
			that.id = that.$el.data('product');
			that.variantContainer = that.$('#product-variant-container');
			that.initCollection();
			that.initVariantCreator();
			that.initVariantEditor();
		},

		initVariantCreator: function() {
			var that = this;
			that.variantCreator = new ProductVariantCreateView({
				productId: that.id
			});
			that.variantCreator.on('VARIANT_ADDED', that.collection.add, that.collection);
			that.variantContainer.html(that.variantCreator.render().el);
		},

		initVariantEditor: function() {
			var that = this;
			that.variantEditor = new ProductVariantEditView();
		},

		initCollection: function() {
			var that = this;
			that.collection = new ProductVariantCollection({
				productId: that.id
			});
			that.collection
				.on('add', that.variantAdded, that)
				.on('remove', that.variantRemoved, that)
				.on('EDIT_SETTINGS', that.editVariant, that)
				.fetch();
		},

		variantAdded: function(model) {
			var that = this;
			var variant = new ProductVariantView({
				model: model
			});
			that.variantCreator.$el.before(variant.render().el);
		},

		variantRemoved: function(model) {
			var that = this;
			console.log('removed variant', model);
		},

		editVariant: function(model) {
			var that = this;
			console.log('editVariant', model);
			that.variantEditor.open(model);
		}
	});

// ---------------------------------------------------- INIT

	window.variants = new ProductView();

}(window));