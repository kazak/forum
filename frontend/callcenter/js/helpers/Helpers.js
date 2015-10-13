'use strict';

module.exports = {

	initialize: function(target) {
		var that = this;
		that.initLinkBlock(target);
		that.initToggle(target);
		that.initToggleOpen(target);
		that.initDropdowns(target);
		that.initExpand(target);
		that.initCheckRadioStyle(target);
		that.initSelectStyle(target);
		that.initClamp(target);
		that.initQuantityPickers(target);
		that.initRadioOpen(target);
		that.initCustomSelect(target);
		that.initSelectOpen(target);
		that.initClearFields(target);
	},

	getTarget: function(target, selector) {
		if (typeof target !== 'undefined' && target) {
			if (target.is(selector)) {
				return target;
			} else {
				return target.find(selector);
			}
		} else {
			return $(selector);
		}
	},

	initLinkBlock: function(_target) {
		var that = this;
		that.getTarget(_target, '[data-link-block]')
			.off('click').on('click', function(e){
				var href = $(this).data('link-block');
				var a = $(this).find('a:first');

				if (!href || href==='' || href==='#') {
					href = a.attr('href');
				}
				if (href === '' || href.charAt(0) === '#') {
					if (a.get(0) !== e.target) {
						a.trigger('click');
						e.stopPropagation();
					}
				} else {
					window.location.href = href;
				}
				return false;
			});
	},

	initToggle: function(_target) {
		var that = this;
		that.getTarget(_target, '[data-toggle]')
			.off('click').on('click',function() {
				var $this = $(this);
				var hidden = $this.data('toggle-hidden');
				var $hidden = hidden ? $(hidden) : null;
				that.runToggle($this.data('toggle'), $hidden);
				return false;
			});
	},

	runToggle: function(className, $hidden) {

		console.log('running toggle', $hidden, $hidden.hasClass('hidden'));

		if ($hidden) {
			if ($hidden.hasClass('hidden')) {
				$hidden.removeClass('hidden').trigger('OPENED_TOGGLE');
				setTimeout(function(){
					$('html').toggleClass(className);
				}, 50);
			} else {
				$('html').toggleClass(className);
				setTimeout(function(){
					$hidden.addClass('hidden').trigger('CLOSED_TOGGLE');
				}, 300);
			}
		} else {
			$('html').toggleClass(className);
		}
	},

	initToggleOpen: function(_target) {
		var that = this;
		that.getTarget(_target, '[data-toggle-open]')
			.off('click').on('click',function() {
				var target = $($(this).data('toggle-open'));
				if (target.length) {
					target.toggleClass('open');
					if (target.hasClass('open')) {
						$(this).addClass('open');
						target.trigger('OPENED');
					} else {
						$(this).removeClass('open');
						target.trigger('CLOSED');
					}
				}
				return false;
			});
	},

	initDropdowns: function(_target) {
		var that = this;
		var target = that.getTarget(_target, '[data-toggle-dropdown]');

		target.each(function(index, elm){
	        var $d = $(elm).data('toggle-dropdown');
			if($d) {
				$($d).addClass('closed');
			}
		});
		target.on('click',function(e){
			var $this = $(this),
	        $d = $this.data('toggle-dropdown'),
	        $dropdown;

			if($d) {
				$dropdown = $($d);
			}

	        if ($dropdown && $dropdown.length) {
	            e.preventDefault();
				e.stopPropagation();
	            that.toggleDropdown($dropdown);
	            return false;
	        }
		});
	},

	toggleDropdown: function($dropdown) {
		var that = this;
        $dropdown.toggleClass('closed');
        if (!$dropdown.hasClass('closed')) {
            $(document).on('click',function() {
        		that.toggleDropdown($dropdown);
				$(document).off('click');
        		$dropdown.trigger('CLOSED_DROPDOWN');
			});
        	$dropdown.trigger('OPENED_DROPDOWN');
        } else {
			$(document).off('click');
        }
	},

	initExpand: function(_target) {
		var that = this;
	    var target = that.getTarget(_target, '[data-expand]');
		target.data('ddTimeout', null).on('click', function(e) {
	        var $this = $(this),
	            $dropdown,
	            expand = $this.data('expand'),
	            $collapse,
	            collapse = $this.data('collapse');

	        if(expand) {
	        	$dropdown = $(expand);
	        }

	        if(collapse) {
	        	$collapse = $(collapse);
	        }

	        if ($dropdown && $dropdown.length) {
	            e.preventDefault();

	            var ddTimeout = $dropdown.data('ddTimeout');

	            var heightRemoveTransition = '-webkit-transition-property:none !important; -webkit-transition-duration:0 !important;';
	            var heightCheckStyle = heightRemoveTransition + 'height:auto !important;';
	            $dropdown.attr('style', heightCheckStyle);

	            var heightSetStyle = 'height: ' + $dropdown.outerHeight() + 'px;';
	            $dropdown.attr('style', heightRemoveTransition + heightSetStyle);

	            setTimeout(function(){
	                $dropdown.attr('style', heightSetStyle);
	                $dropdown.toggleClass('closed');
	                if (!$dropdown.hasClass('closed')) {
	                    if (ddTimeout) {
	                        clearTimeout(ddTimeout);
	                        ddTimeout = null;
	                    }
	                    ddTimeout = setTimeout(function(){
	                        $dropdown.attr('style', heightCheckStyle);
	                        ddTimeout = null;
	                    }, 270);
	                    $dropdown.trigger('OPENED_EXPANDED');

	                    if ($collapse) {
	                    	$collapse.each(function(index, elm){
								var $elm = $(elm);
								if (!$elm.hasClass('closed')) {
									$('[data-expand="#'+$elm.attr('id')+'"]').trigger('click');
								}
							});
	                    }
	                } else {
	                    $dropdown.trigger('CLOSED_EXPANDED');
	                }
	            }, 30);
	            $this.toggleClass('is-open');

	            return false;
	        }
	    });

		target.each(function(index, elm){
			var $elm = $(elm);
			if ($elm.is('[data-expand-onload]')) {
				setTimeout(function(){
					$elm.trigger('click');
				}, 50);
			}
		});
	},

	initCheckRadioStyle: function(_target) {
		var that = this;
		that.getTarget(_target, 'input[type="radio"],input[type="checkbox"]').checkboxRadioUI({
			label: false
		});
	},

	initSelectStyle: function(_target) {
		var that = this;
		that.getTarget(_target, '.form-group select:not([data-disable-custom],:has([data-subtext],[data-content]))').customSelect({
			customClass: 'form-group__select'
		});
	},

	initClamp: function(_target) {
		var that = this;
		var target = that.getTarget(_target, '[data-clamp]');
		$.each(target, function(index, elm) {
			if($(elm).text()) {
				$clamp($(elm).get(0), {
					clamp: parseInt($(elm).data('clamp'))
				});
			}
		});
	},

	initQuantityPickers: function(_target) {
		var that = this;
		that.getTarget(_target, '.quantity-picker').each(function(index, elm){
			var $elm = $(elm);
			var $input = $elm.find('input');
			$elm.find('.quantity-picker__controller--minus').click(function(e) {
				e.preventDefault();
				var val = parseInt($input.val());
				if (val > 0) {
					$input.val(val-1).trigger('input');
				}
			});
			$elm.find('.quantity-picker__controller--plus').click(function(e) {
				e.preventDefault();
				var val = parseInt($input.val());
				$input.val(val+1).trigger('input');
			});
			$elm.find('.quantity-picker__controller--remove').click(function(e) {
				e.preventDefault();
				$input.trigger('REMOVE');
			});
			$input.on('input', function() {
				var val = parseInt($input.val());
				if (val > 1) {
					$elm.removeClass('quantity-picker--removable');
				} else {
					$elm.addClass('quantity-picker--removable');
				}
			}).trigger('input');
		});
	},

	initRadioOpen: function(_target) {
		var that = this;
		that.getTarget(_target, '[data-radio-open]').each(function(index, elm){
			var $elm = $(elm);
			var $target = $($elm.data('radio-open'));
			if (!$target.length) {
				return;
			}
			if (!$elm.is(':checked')) {
				$target.addClass('hidden');
			}
			$elm.on('change', function() {
				if ($elm.is(':checked')) {
					$target.removeClass('hidden');

					if ($elm.attr('type').toLowerCase() === 'radio') {
						$($('[name="'+$elm.attr('name')+'"]').not($elm).data('radio-open')).addClass('hidden');
					}
				} else {
					$target.addClass('hidden');
				}
			}).trigger('change');
		});
	},

	initCustomSelect: function(_target) {
		var that = this;
		that.getTarget(_target, 'select:has([data-subtext],[data-content])').each(function(index, select){
			var $select = $(select);
			console.log('found custom select');
			$select.selectpicker();
		});
	},

	initSelectOpen: function(_target) {
		var that = this;
		that.getTarget(_target, 'select[data-select-open]').each(function(index, select){
			var $select = $(select);
			$select.on('change', function() {
				$select.find('[data-select-open-target]').each(function(index, option) {
					var $option = $(option),
						$target = $($option.data('select-open-target'));
					if ($option.is(':checked')) {
						$target.removeClass('hidden');
					} else {
						$target.addClass('hidden');
					}
				});
			}).trigger('change');
		});
	},

	initClearFields: function(_target) {
		var that = this;
		that.getTarget(_target, '[data-clear-fields]').click(function(){
			var $elm = $(this);
			var $target = $($elm.data('clear-fields'));

			console.log('clear fields', $target);

			if ($target.length) {
				
			}
			return false;
		});
	}
};
