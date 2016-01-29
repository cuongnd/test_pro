/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
    config.extraPlugins = 'filebrowser,codemirror';
/*
    config.filebrowserBrowseUrl = '/admin/content/filemanager.aspx?path=Userfiles/File&editor=FCK';
    config.filebrowserImageBrowseUrl = '/admin/content/filemanager.aspx?type=Image&path=Userfiles/Image&editor=FCK';
*/
    config.filebrowserImageBrowseUrl = this_host+'/ckfinder/ckfinder.html?type=Images';
    config.filebrowserFlashBrowseUrl = this_host+'/ckfinder/ckfinder.html?type=Flash';
    config.filebrowserUploadUrl = this_host+'/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files';
    config.filebrowserImageUploadUrl = this_host+'/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images';
    config.ilebrowserFlashUploadUrl = this_host+'/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash';

};
