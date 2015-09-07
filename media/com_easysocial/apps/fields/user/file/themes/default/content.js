<?php
/**
* @package 		EasySocial
* @copyright	Copyright (C) 2010 - 2013 Stack Ideas Sdn Bhd. All rights reserved.
* @license 		Proprietary Use License http://stackideas.com/licensing.html
* @author 		Stack Ideas Sdn Bhd
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );
?>
EasySocial.module('field.file', function($) {
	var module = this;

	EasySocial.Controller('Field.File', {
		defaultOptions: {
			required: false,

			id: null,

			inputName: '',

			maxFile: 0,

			'{field}': '[data-field-file]',

			'{list}': '[data-field-file-list]',

			'{item}': '[data-field-file-item]',

			'{add}': '[data-field-file-add]'
		}
	}, function(self) {
		return {
			init: function() {
				self.options.maxFile = self.field().data('maxfile');

				self.item().addController('EasySocial.Controller.Field.File.Item', {
					controller: {
						parent: self
					}
				});
			},

			'{add} click': function(el, ev) {
				if(self.options.maxFile < 1 || (self.item().length < self.options.maxFile)) {
					var key = self.item().length;

					var item = $('<div class="data-field-file-item" data-field-file-item></div>');

					item.data('key', key);

					item.html('<?php echo JText::_( 'COM_EASYSOCIAL_WORKING' ); ?>');

					item.addController('EasySocial.Controller.Field.File.Item', {
						controller: {
							parent: self
						}
					});

					self.list().append(item);

					EasySocial.ajax('fields/user/file/getUploadHtml', {
						id: self.options.id,
						key: key
					}).done(function(html) {
						item.html(html);
					});

					if(self.options.maxFile > 1 && self.item().length >= self.options.maxFile) {
						el.hide();
					}
				}
			},

			'{item} uploadDone': function() {
				self.add().click();
			}
		}
	});

	EasySocial.Controller('Field.File.Item', {
		defaultOptions: {
			required: false,

			key: null,

			'{upload}'		: '[data-field-file-upload]',

			'{progress}'	: '[data-field-file-progress]',

			'{delete}'		: '[data-field-file-delete]',

			'{clear}'		: '[data-field-file-clear]',

			'{id}'			: '[data-field-file-id]',

			'{tmp}'			: '[data-field-file-tmp]'
		}
	}, function(self) {
		return {
			init: function() {
				self.options.key = self.element.data('key');
			},

			'{upload} change': function(el, ev) {
				self.element.html('<?php echo JText::_( 'COM_EASYSOCIAL_WORKING' ); ?>');

				EasySocial.ajax('fields/user/file/upload', {
					id: self.parent.options.id,
					files: el,
					key: self.options.key
				}, {
					type: 'iframe'
				}).done(function(html) {
					self.element.html(html);

					self.trigger('uploadDone');
				}).fail(function(msg) {
					self.element.html(msg || self.getErrorMsg());
				});
			},

			'{delete} click': function(el, ev) {
				var tmp = self.tmp().val();
				var id = self.id().val();

				self.element.html('<?php echo JText::_( 'COM_EASYSOCIAL_WORKING' ); ?>');

				EasySocial.ajax('fields/user/file/delete', {
					id: self.parent.options.id,
					key: self.options.key,
					tmp: tmp,
					fileid: id
				}).done(function(html) {
					self.element.html(html);

					self.trigger('fileDeleted');
				}).fail(function(msg) {
					self.element.html(msg || self.getErrorMsg());
				});
			},

			'{clear} click': function(el, ev) {
				self.element.html('<?php echo JText::_( 'COM_EASYSOCIAL_WORKING' ); ?>');

				EasySocial.ajax('fields/user/file/getUploadHtml', {
					id: self.parent.options.id,
					key: self.options.key
				}).done(function(html) {
					self.element.html(html);
				});
			},

			getErrorMsg: function() {
				msg = $('<span class="alert field-file-error"><?php echo JText::_( 'PLG_FIELDS_FILE_ERROR_UNKNOWN_ERROR_OCCURED' ); ?><button class="close" type="button" data-field-file-clear>×</button></span>');

				return msg;
			}
		}
	});

	module.resolve();
});
