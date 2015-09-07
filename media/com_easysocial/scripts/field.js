EasySocial.module('field', function($) {
	var module = this;

	EasySocial.Controller('Field.Base', {
		defaultOptions: {
			regPrefix	: 'easysocial/',

			modPrefix	: 'field.',

			ctrlPrefix	: 'EasySocial.Controller.Field.',

			fieldname	: '',

			element		: null,

			id			: null,

			userid		: null,

			required	: false,

			mode		: 'edit',

			'{field}'	: '[data-field]',

			'{notice}'	: '[data-check-notice]'
		}
	}, function(self) {
		return {
			init: function() {

				self.options.fieldname = self.element.data('fieldname');

				self.options.element = self.options.element || self.element.data('element');

				self.options.id = self.element.data('id');

				self.options.required = !!self.element.data('required');

				self.initHandler();

				self.initMode();
			},

			initHandler: function() {
				var modName = self.options.modPrefix + self.options.element,
					regName = self.options.regPrefix + modName,
					ctrlName = self.options.ctrlPrefix + $.String.capitalize(self.options.element);

				if($.module.registry[regName] !== undefined) {
					EasySocial.module(modName).done(function(handler) {
						handler = handler || {};

						if($.isController(ctrlName)) {
							self.field().addController(ctrlName, {
								required: self.options.required,
								fieldname: self.options.fieldname,
								id: self.options.id,
								userid: self.options.userid,
								mode: self.options.mode
							});

							return;
						}

						if($.isFunction(handler)) {
							handler(self.field());

							return;
						}

						if($.isPlainObject(handler)) {
							self.field().on(handler);

							return;
						}
					});
				}
			},

			initMode: function() {
				// Trigger the necessary mode here for field to do necessary init
				switch(self.options.mode)
				{
					case 'register':
						self.field().trigger('onRegister');
						break;
					case 'edit':
						self.field().trigger('onEdit');
						break;
					case 'adminedit':
						self.field().trigger('onAdminEdit');
						break;
					case 'sample':
						self.field().trigger('onSample');
						break;
					case 'display':
						self.field().trigger('onDisplay');
						break;
				}
			},

			// Some base triggers/functions
			'{field} error': function(el, ev, state, msg) {
				state = state !== undefined ? state : true;

				if($.isString(state)) {
					msg = state;
					state = true;
				}

				if($.isBoolean(state)) {
					self.field().toggleClass('error', state);
				}

				if(msg !== undefined) {
					self.notice().html(msg);
				}
			},

			'{field} clear': function(el, ev) {
				self.field().removeClass('error');
			},

			'{self} show': function() {
				self.field().trigger('onShow');
			}
		}
	});

	module.resolve();
});
