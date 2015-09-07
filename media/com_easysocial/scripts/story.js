EasySocial.module("story", function($){

var module = this;

// This speeds up story initialization during development mode.
// Do not add this to the manifest file.
EasySocial.require()
	.language(
		"COM_EASYSOCIAL_WITH_FRIENDS",
		"COM_EASYSOCIAL_AND_ONE_OTHER",
		"COM_EASYSOCIAL_AND_MANY_OTHERS",
		"COM_EASYSOCIAL_LOCATION_PERMISSION_ERROR"
	)
	.view(
		"apps/user/links/story.attachment.item",
		"apps/user/locations/suggestion",
		"site/albums/upload.item"
	)
	.done();

EasySocial.require()
	.library("expanding")
	.script('site/stream/item')
	.language(
		"COM_EASYSOCIAL_STORY_SUBMIT_ERROR",
		"COM_EASYSOCIAL_STORY_CONTENT_EMPTY"
	)
	.done(function(){

		EasySocial.Controller("Story",
		{
			defaultOptions: {

				plugin: {

				},

				attachment: {
					limit: 1,
					lifo: true
				},

				"{header}": "[data-story-header]",
				"{body}"  : "[data-story-body]",
				"{footer}": "[data-story-footer]",

				"{form}"        : "[data-story-form]",
				"{textField}"   : "[data-story-textField]:not(.shadow)",
				"{target}"      : "[data-story-target]",
				"{submitButton}": "[data-story-submit]",
				"{privacyButton}": "[data-story-privacy]",

				"{panelContents}" : "[data-story-panel-contents]",
				"{panelContent}"  : "[data-story-panel-content]",
				"{panelButton}"   : "[data-story-panel-button]",

				"{attachmentContainer}"     : "[data-story-attachment-container]",
				"{attachmentIcon}"          : "[data-story-attachment-icon]",
				"{attachmentButtons}"       : "[data-story-attachment-buttons]",
				"{attachmentButton}"        : "[data-story-attachment-button]",
				"{attachmentItems}"         : "[data-story-attachment-items]",
				"{attachmentItem}"          : "[data-story-attachment-item]",
				"{attachmentContent}"       : "[data-story-attachment-content]",
				"{attachmentToolbar}"       : "[data-story-attachment-toolbar]",
				"{attachmentDragHandle}"    : "[data-story-attachment-drag-handle]",
				"{attachmentRemoveButton}"  : "[data-story-attachment-remove-button]",
				"{attachmentClearButton}"   : "[data-story-attachment-clear-button]",

				//stream listing
				"{streamContainer}"	 		: "[data-streams]",
				"{streamItem}"	 			: "[data-streamItem]",
			},

			hostname: "story"
		},
		function(self){ return {

			init: function() {

				// Temporary for development purpose
				window.___story = self;

				// Find out what's my story id
				self.id = self.element.data("story");


				// Create plugin repository
				$.each(self.options.plugin, function(pluginName, pluginOptions) {

					var plugin = self.plugins[pluginName] = pluginOptions;

					// Pre-count the number of available attachment type
					if (plugin.type=="attachment") {
						self.attachments.max++;
					}

					// Add selector property
					plugin.selector = self.getPluginSelector(pluginName);
				});


				// Resolve story instance
				$.module("story-" + self.id).resolve(self);
			},

			"{textField} click": function(textField) {

				var story = self.element;

				// First time only
				if (!story.hasClass("active")) {

					story.addClass("active");

					textField
						.expandingTextarea()
						.focus();

					setTimeout(function(){
						story
							.addTransitionClass("no-transition")
							.addClass("expanded");
					}, 500);
				}
			},

			//
			// PLUGINS
			//

			plugins: {},

			getPluginName: function(element) {
				return $(element).data("story-plugin-name");
			},

			getPluginSelector: function(pluginName) {
				return "[data-story-plugin-name=" + pluginName + "]";
			},

			hasPlugin: function(pluginName, pluginType) {

				var plugin = self.plugins[pluginName];

				if (!plugin) return false;

				// Also check for pluginType
				if (pluginType) {
					return (plugin.type===pluginType);
				}

				return true;
			},

			buildPluginSelectors: function(selectorNames, plugin, pluginControllerType) {

				var selectors = {};

				$.each(selectorNames, function(i, selectorName){

					var selector = self[selectorName].selector + plugin.selector;

					if (pluginControllerType=="function") {
						selectors[selectorName] = function() {
							return self.find(selector);
						};
					} else {
						selectors["{"+selectorName+"}"] = selector;
					}
				});

				return selectors;
			},

			"{self} addPlugin": function(element, event, pluginName, pluginController, pluginOptions, pluginControllerType) {

				// Prevent unregistered plugin from extending onto story
				if (!self.hasPlugin(pluginName)) return;

				var plugin = self.plugins[pluginName],
					extendedOptions = {};

				// See plugin type and build the necessary options for them
				switch (plugin.type) {

					case "attachment":
						var attachmentSelectors = [
							"attachmentIcon",
							"attachmentButton",
							"attachmentItem",
							"attachmentContent",
							"attachmentToolbar",
							"attachmentDragHandle",
							"attachmentRemoveButton"
						];
						extendedOptions = self.buildPluginSelectors(attachmentSelectors, plugin, pluginControllerType);
						break;

					case "panel":
						var panelSelectors = [
							"panelButton",
							"panelContent"
						];
						extendedOptions = self.buildPluginSelectors(panelSelectors, plugin, pluginControllerType);
						break;
				}

				$.extend(pluginOptions, extendedOptions);
			},

			"{self} registerPlugin": function(element, event, pluginName, pluginInstance) {

				// Prevent unregistered plugin from extending onto story
				if (!self.hasPlugin(pluginName)) return;

				var plugin = self.plugins[pluginName];

				plugin.instance = pluginInstance;
			},

			//
			// PANELS
			//

			panels: {},

			currentPanel: null,

			getPanel: function(pluginName) {

				// If plugin is not a panel, stop.
				if (!self.hasPlugin(pluginName, "panel")) return;

				var plugin = self.plugins[pluginName];

                       // Return existing panel entry if it has been created,
				return self.panels[plugin.name] ||

					   // or create panel entry and return it.
					   (self.panels[plugin.name] = {
					       plugin: plugin,
					       button: self.panelButton(plugin.selector),
					       content: self.panelContent(plugin.selector)
					   });
			},

			togglePanel: function(pluginName) {

				// Get current panel
				var currentPanel = self.currentPanel;

				// If current panel exists
				if (currentPanel) {
					self.deactivatePanel(currentPanel);
				}

				// Do not reactivate panel that
				// was deactivated just now.
				if (currentPanel===pluginName) return;

				self.activatePanel(pluginName);
			},

			activatePanel: function(pluginName) {

				// Get panel
				var panel = self.getPanel(pluginName);

				// If panel does not exist, stop.
				if (!panel) return;

				// Deactivate current panel
				self.deactivatePanel(self.currentPanel);

				var panelContents = self.panelContents();

				// Activate panel container
				panelContents.addClass("active");

				// Activate panel
				panel.button.addClass("active");
				panel.content
					.appendTo(panelContents)
					.addClass("active");

				// Invoke plugin's activate method if exists
				self.invokePlugin(pluginName, "activatePanel", [panel]);

				// Trigger panel activate event
				self.trigger("activatePanel", [pluginName]);
			},

			deactivatePanel: function(pluginName) {

				// Get panel
				var panel = self.getPanel(pluginName);

				// If panel does not exist, stop.
				if (!panel) return;

				// Deactivate panel
				panel.button.removeClass("active");
				panel.content.removeClass("active");

				// Deactivate panel container
				self.panelContents().removeClass("active");

				// Invoke plugin's deactivate method if exists
				self.invokePlugin(pluginName, "deactivatePanel", [panel]);

				// Trigger panel deactivate event
				self.trigger("deactivatePanel", [pluginName]);
			},

			addPanelCaption: function(pluginName, panelCaption) {

				// Get panel
				var panel = self.getPanel(pluginName);

				// If panel does not exist, stop.
				if (!panel) return;

				panel.button
					.addClass("has-data")
					.find(".with-data").html(panelCaption);
			},

			removePanelCaption: function(pluginName) {

				// Get panel
				var panel = self.getPanel(pluginName);

				// If panel does not exist, stop.
				if (!panel) return;

				panel.button
					.removeClass("has-data")
					.find(".with-data").empty();
			},

			"{self} activatePanel": function(story, event, pluginName) {

				self.currentPanel = pluginName;
			},

			"{self} deactivatePanel": function(story, event, pluginName) {

				// If the deactivated panel is the current panel,
				if (self.currentPanel===pluginName) {

					// set current panel to null.
					self.currentPanel = null;
				}
			},

			"{panelButton} click": function(panelButton, event) {

				var pluginName = self.getPluginName(panelButton);

				self.togglePanel(pluginName);
			},

			//
			// ATTACHMENTS
			//

			attachments: {
				length: 0, // Pseudo array
				max: 0
			},

			currentAttachment: null,

			getAttachment: function(pluginName) {

				// If plugin is not an attachment, stop.
				if (!self.hasPlugin(pluginName, "attachment")) return;

				return self.attachments[pluginName];
			},

			addAttachment: function(pluginName) {

				// Do not allow non-attachment plugin to add attachment
				if (!self.hasPlugin(pluginName, "attachment")) return false;

				// Get plugin
				var plugin = self.plugins[pluginName];

				// Get master attachment list
				var attachments = self.attachments,
					attachment = attachments[pluginName];

				// Return existing attachment if exists
				if (attachment) return attachment;

				// Create attachment
				var createAttachment = function(){

					var attachment = {
							plugin: plugin,
							button: self.attachmentButton(plugin.selector),
							icon  : self.attachmentIcon(plugin.selector)
						};

						attachment.item =
							self.attachmentItem(plugin.selector)
								.prependTo(self.attachmentItems());

					// Add to master attachments
					attachments[plugin.name] = attachment;
					attachments.length++;

					// Invoke plugin's add method if exists
					self.invokePlugin(pluginName, "addAttachment", [attachment]);

					// Trigger addAttachment event
					self.trigger("addAttachment", [attachment]);
				}

				// Check attachment limit
				var options = self.options.attachment,
					lifo = options.lifo,
					limitExceeded = (options.limit > 0 && attachments.length >= options.limit);

				// If exceeded attachment limit
				if (limitExceeded) {
					// but we allow new attachment to replace oldest attachment
					if (lifo) {

						// Create new attachment
						createAttachment();

						// then remove old attachment
						var oldestAttachmentName = self.getPluginName(self.attachmentItem(":last"));
						self.removeAttachment(oldestAttachmentName);

						return attachment;
					} else {
						// else prevent adding of new attachment.
						return false;
					}
				}

				// Create new attachment
				createAttachment();

				return attachment;
			},

			removeAttachment: function(pluginName) {

				var attachment = self.getAttachment(pluginName);

				// If attachment does not exist, skip.
				if (!attachment) return;

				// Invoke plugin's remove method if exists
				self.invokePlugin(pluginName, "removeAttachment", [attachment]);

				// Trigger removeAttachment event
				self.trigger("removeAttachment", [attachment]);

				// Remove attachment item
				attachment.icon.removeClass("active");
				attachment.button.removeClass("active");

				setTimeout(function(){
					attachment.item.removeClass("active");
				}, 0);

				// Remove from master attachments
				delete self.attachments[pluginName];
				self.attachments.length--;

				// Invoke plugin's remove method if exists
				self.invokePlugin(pluginName, "destroyAttachment", [attachment]);

				// Trigger removeAttachment event
				self.trigger("destroyAttachment", [attachment]);

				return attachment;
			},

			clearAttachment: function() {

				var removedPluginNames =
					$.map(self.attachments, function(plugin, pluginName) {

						// Ignore pseudo-array properties
						if (/length|max/.test(pluginName)) return;

						// Remove attachment
						self.removeAttachment(pluginName);

						// Add it to the list of removed plugins
						return pluginName;
					});

				// Trigger removeAttachment event
				self.trigger("clearAttachment", [removedPluginNames]);
			},

			activateAttachment: function(pluginName) {

				var attachment = self.getAttachment(pluginName);

				// If attachment does not exist, skip.
				if (!attachment) return;

				// Deactivate current attachment
				self.deactivateAttachment(self.currentAttachment);

				// Activate attachment
				attachment.icon.addClass("active");
				attachment.button.addClass("active");

				setTimeout(function(){
					attachment.item.addClass("active");
				}, 0);

				// Invoke plugin's activate method if exists
				self.invokePlugin(pluginName, "activateAttachment", [attachment]);

				// Trigger activateAttachment event
				self.trigger("activateAttachment", [attachment]);
			},

			deactivateAttachment: function(pluginName) {

				var attachment = self.attachments[pluginName];

				// If attachment does not exist, skip.
				if (!attachment) return;

				// Remove active class from attachment item and button
				// attachment.item.removeClass("active");
				attachment.icon.removeClass("active");
				attachment.button.removeClass("active");

				setTimeout(function(){
					attachment.item.removeClass("active");
				}, 0);

				// Invoke plugin's deactivate method is exists
				self.invokePlugin(pluginName, "deactivateAttachment", [attachment]);

				// Trigger deactivateAttachment event
				self.trigger("deactivateAttachment", [attachment]);
			},

			"{self} activateAttachment": function(story, event, attachment) {

				var pluginName = attachment.plugin.name;

				self.currentAttachment = pluginName;

				self.body().addClass("active");

				self.element.addClass("attaching-" + pluginName);
			},

			"{self} deactivateAttachment": function(story, event, attachment) {

				var pluginName = attachment.plugin.name;

				// If the deactivated panel is the current panel,
				if (self.currentAttachment===pluginName) {

					// set current panel to null.
					self.currentAttachment = null;
				}

				self.body().removeClass("active");

				self.element.removeClass("attaching-" + pluginName);
			},

			"{attachmentButton} click": function(attachmentButton, event) {

				var pluginName = self.getPluginName(attachmentButton);

				// Text clears attachments
				if (pluginName=="text") {

					// Activate button only
					attachmentButton.addClass("active");

					// Focus textfield
					self.textField().focus();

					return;
				}

				// If the attachment hasn't been created
				if (!self.getAttachment(pluginName)) {

					// Create the attachment
					var attachment = self.addAttachment(pluginName);

					// If unable to create attachment, stop.
					if (!attachment) return;
				}

				// Activate attachment
				self.activateAttachment(pluginName);
			},

			"{attachmentClearButton} click": function() {
				self.clearAttachment();
			},

			"{self} addAttachment": function(story, event, attachment) {

				var pluginName = attachment.plugin.name;

				self.attachmentButton(".for-text").removeClass("active");

				self.activateAttachment(pluginName);
			},

			"{self} removeAttachment": function(el, event, attachment) {

				var pluginName = attachment.plugin.name;

				self.element.removeClass("attaching-" + pluginName);
			},

			"{self} destroyAttachment": function(el, event, attachment) {

				if (self.attachments.length < 1) {

					self.body().removeClass("active");

					self.attachmentButton(".for-text").addClass("active");
				}
			},

			//
			// ATTACHMENT TOOLBAR
			//

			"{attachmentRemoveButton} click": function(attachmentRemoveButton, event) {

				var pluginName = self.getPluginName(attachmentRemoveButton);

				self.removeAttachment(pluginName);
			},


			//
			// SAVING
			//
			saving: false,

			save: function() {

				if (self.saving) return;

				self.saving = true;

				// Create save object
				var save = $.Deferred();

					save.data = {};

					save.tasks = [];

					save.addData = function(plugin, props) {

						var pluginName = plugin.options.name,
							pluginType = plugin.options.type;

						if (pluginType=="attachment") {

							// Stop attachment plugins other than the current
							// one from adding stuff to the save data.
							if (pluginName!==self.currentAttachment) return;

							// Don't decorate the attachment property we know
							// there are proper attachment data coming in
							// from the attachment plugin.
							save.data.attachment = self.currentAttachment;
						}

						if ($.isPlainObject(props)) {
							$.each(props, function(key, val){
								save.data[pluginName + "_" + key] = val;
							});
						} else {
							save.data[pluginName] = props;
						}
					};

					save.addTask = function(name) {
						var task = $.Deferred();
						task.name = name;
						task.save = save;
						save.tasks.push(task);
						return task;
					};

					save.process = function() {

						if (save.state()==="pending") {
							$.when.apply($, save.tasks)
								.done(function(){
									// If content & attachment is empty, reject.
									if (!save.data.content && !save.data.attachment) {
										save.reject($.language("COM_EASYSOCIAL_STORY_CONTENT_EMPTY"), "warning");
										return;
									}
									save.resolve();
								})
								.fail(save.reject);
						}

						return save;
					};

				self.clearMessage();

				// Trigger the save event
				self.trigger("save", [save]);

				self.element.addClass("saving");

				save.process()
					.done(function(){

						// then the ajax call to save story.
						EasySocial.ajax("site/controllers/story/create", save.data)
							.done(function(){
								self.trigger("create", arguments);
								self.clear();
							})
							.fail(function(message){
								self.trigger("fail", arguments);
								if (!message) return;
								self.setMessage(message.message, message.type);
							})
							.always(function(){
								self.element.removeClass("saving");
								self.saving = false;
							});
					})
					.fail(function(message, messageType){

						if (!message) {
							message = $.language("COM_EASYSOCIAL_STORY_SUBMIT_ERROR");
							messageType = "error";
						}

						self.setMessage(message, messageType);
						self.element.removeClass("saving");
						self.saving = false;
					});
			},

			clear: function() {

				self.textField().val('');

				self.trigger("clear");

				self.deactivateAttachment(self.currentAttachment);

				self.deactivatePanel(self.currentPanel);

				// Activate button only
				self.attachmentButton(".for-text").addClass("active");

				self.clearMessage();

				// Focus textfield
				self.textField().focus().expandingTextarea("resize");
			},

			"{self} save": function(element, event, save) {

				var content = $.trim(self.textField().val());

				save.data.content = content;
				save.data.target  = self.target().val();
				save.data.privacy = self.find("[data-privacy-hidden]").val();
				save.data.privacyCustom = self.find("[data-privacy-custom-hidden]").val();
			},

			"{submitButton} click": function(submitButton, event) {

				self.save();
			},


			//
			// Privacy
			//
			"{privacyButton} click": function(el) {

				setTimeout(function(){
					var isActive = el.find("[data-es-privacy-container]").hasClass("active");
					self.footer().toggleClass("allow-overflow", isActive);
				}, 1);
			}

		}});

		module.resolve();
	});

});
