/**
 * Javascript for files support
 * 
 * @version $Id$
 * @package ARTIO Booking
 * @subpackage assets
 * @copyright Copyright (C) 2010 ARTIO s.r.o.. All rights reserved.
 * @author ARTIO s.r.o., http://www.artio.net
 * @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
 * @link http://www.artio.net Official website
 */

var AFiles = {
	fileUnselect : 'file pointer',
	fileSelect : 'file fileOver pointer',
	fileMainHidden : 'file blind',
	fileMainVisible : 'file',
	fileGallery : 'file pointer',
	buttonHidden : 'button blind',
	buttonVisible : 'button',
	init : function() {
		var browseFiles = this.getAll(false);
		var galleryFiles = this.getAll(true);
		for ( var i = 0; i < browseFiles.length; i++) {
			var id = browseFiles[i];
			if (this.inArray(id, galleryFiles)) {
				var file = this.getElementById(this.getFileBrowserId(id),
						false);
				file.className = this.fileSelect;
			}
		}
	},
	updateFileParams : function (id,param) {
		
		hidden =  this.getElementById(this.getHiddenGalleryId(id),false);
		checkbox = this.getElementById(param==1 ? this.getGalleryShowId(id) : this.getGallerySendId(id),false);

		if (checkbox && hidden)	{
			params = hidden.value.split('::');
			params[param] = checkbox.checked ? 1 : 0;
			hidden.value = params.join('::');
		}
	}, 
	mark : function(id, inBrowser) {
		var id = inBrowser ? this.getFileBrowserId(id) : this
				.getFileGalleryId(id);
		var file = this.getElementById(id, !inBrowser);
		file.className = file.className == this.fileUnselect ? this.fileSelect
				: this.fileUnselect;
	},
	setMain : function(close) {
		var selected = this.getSelected();
		if (selected.length == 0) {
			alert(selectFile);
		} else {
			var id = selected[0];
			var browserFile = this.getElementById(this.getFileBrowserId(id),
					false);
			var browserHidden = this.getElementById(
					this.getHiddenBrowserId(id), false);
			this.updateMain(browserFile.src, this.fileMainVisible,
					browserHidden.value, this.buttonVisible, true);
			if (close) {
				this.close();
			}
		}
	},
	removeMain : function() {
		this.updateMain('', this.fileMainHidden, '', this.buttonHidden, false);
	},
	updateMain : function(mainFileSrc, mainFileClassname, mainHiddenValue,
			removeClassname, onParent) {
		var mainFile = this.getElementById(this.getFileMainId(), onParent);
		var mainHidden = this.getElementById(this.getHiddenMainId(), onParent);
		var remove = this.getElementById(this.getMainRemoveId(), onParent);
		mainHidden.value = mainHiddenValue;
		mainFile.src = mainFileSrc;
		mainFile.className = mainFileClassname;
		remove.className = removeClassname;
	},
	hideRemoveMain : function() {
		var remove = this.getElementById(this.getMainRemoveId(), false);
		remove.className = this.buttonHidden;
	},
	close : function() {
		window.parent.SqueezeBox.close();
	},
	getSelected : function() {
		var files = this.getParentFiles(false);
		var selected = new Array();
		for ( var i = 0; i < files.length; i++) {
			var file = files[i];
			if (file.className == this.fileSelect) {
				var id = this.getId(file.id);
				selected.push(id);
			}
		}
		return selected;
	},
	getAll : function(onParent) {
		var files = this.getParentFiles(onParent);
		var all = new Array();
		for ( var i = 0; i < files.length; i++) {
			var file = files[i];
			var id = this.getId(file.id);
			
			fileClasses = new Array(this.fileUnselect,this.fileSelect,this.fileMainHidden,this.fileMainVisible,this.fileGallery)

			if (this.inArray(file.className,fileClasses))
				all.push(id);
		}
		return all;
	},
	setGallery : function(close) {
		var parent = this.getParent(true);
		var browseFiles = this.getAll(false);
		var galleryFiles = this.getAll(true);
		var selectedFiles = this.getSelected();
		for ( var i = 0; i < browseFiles.length; i++) {
			var id = browseFiles[i];
			var isSelect = this.inArray(id, selectedFiles);
			var isAdded = this.inArray(id, galleryFiles);
			if (!isSelect && isAdded) {
				this.removeGalleryFile(id, true);
			} else if (isSelect && !isAdded) {
				
				var galleryFile = window.parent.document.createElement('div');
				var galleryImg = window.parent.document.createElement('img');
				var galleryFilename = window.parent.document.createElement('span');
				//var galleryLabel1 = window.parent.document.createElement('label');
				//var galleryLabel2 = window.parent.document.createElement('label');
				var galleryInput1 = window.parent.document.createElement('input');
				var galleryInput2 = window.parent.document.createElement('input');
				var galleryHidden = window.parent.document.createElement('input');
				
				var browseFile = this.getElementById(this.getFileBrowserId(id), false);
				var browseHidden = this.getElementById(this.getHiddenBrowserId(id), false);
							
				parentAFiles = window.parent.AFiles; //this is important
				
				galleryImg.src = browseFile.getElementsByTagName('img')[0].src;
				
				galleryFile.id = this.getFileGalleryId(id);
				galleryFile.className = this.fileGallery;				
				galleryFile.onclick = function() {parentAFiles.mark(id,false);}.bind(parentAFiles,id);
				
				galleryFilename.className = 'filename';
				galleryFilename.innerHTML = browseHidden.value; /* browseFile.getElementsByTagName('span')[0].innerHTML;*/
					
				//galleryLabel1.innerHTML=langDisplayFrontend;
				//galleryLabel2.innerHTML=langSendWithReservation;
	
				galleryInput1.type="checkbox";
				galleryInput1.id = this.getGalleryShowId(id);
				galleryInput2.type="checkbox";
				galleryInput2.id = this.getGallerySendId(id);
				galleryInput1.checked = true;
				galleryInput2.checked = true;
				galleryInput1.onchange = function (){parentAFiles.updateFileParams(id,1);}.bind(parentAFiles,id);	
				galleryInput2.onchange = function (){parentAFiles.updateFileParams(id,2);}.bind(parentAFiles,id);	

				//galleryLabel1.appendChild(galleryInput1);
				//galleryLabel2.appendChild(galleryInput2);
				galleryFile.appendChild(galleryImg);
				galleryFile.appendChild(galleryFilename);
				//galleryFile.appendChild(galleryLabel1);
				//galleryFile.appendChild(galleryLabel2);
				parent.appendChild(galleryFile);
				
				galleryHidden.id = this.getHiddenGalleryId(id);
				galleryHidden.type = 'hidden';
				galleryHidden.name = 'files[]';
				galleryHidden.value = browseHidden.value+"::1::1";
				parent.appendChild(galleryHidden);
				
			}
		}
		this.updateGalleryToolbar(true);
		if (close) {
			this.close();
		}
	},
	removeGallery : function() {
		var selected = this.getSelected();
		for ( var i = 0; i < selected.length; i++) {
			var id = selected[i];
			this.removeGalleryFile(id, false);
		}
		this.updateGalleryToolbar(false);
	},
	removeGalleryFile : function(id, onParent) {
		var parent = this.getParent(onParent);
		var galleryFile = this.getElementById(this.getFileGalleryId(id),
				onParent);
		var galleryHidden = this.getElementById(this.getHiddenGalleryId(id),
				onParent);
		parent.removeChild(galleryFile);
		parent.removeChild(galleryHidden);
	},
	updateGalleryToolbar : function(onParent) {
		var remove = this.getElementById(this.getGalleryRemoveId(), onParent);
		var checkAll = this.getElementById(this.getGalleryCheckAllId(),
				onParent);
		var galleryFiles = this.getAll(onParent);
		var className = galleryFiles.length ? this.buttonVisible
				: this.buttonHidden;
		remove.className = className;
		checkAll.className = className;
	},
	checkAll : function(el, inBrowser) {
		var files = this.getAll(false);
		var className = el.checked ? this.fileSelect : this.fileUnselect;
		for ( var i = 0; i < files.length; i++) {
			var id = files[i];
			id = inBrowser ? this.getFileBrowserId(id) : this
					.getFileGalleryId(id);
			var file = this.getElementById(id, false);
			file.className = className;
		}
	},
	upload : function() {
		this.submit('upload');
	},
	remove : function() {
		var parent = this.getParent(false);
		// files in current window page
		var browseFiles = this.getAll(false);
		// files selected into gallery
		var galleryFiles = this.getAll(true);
		// files selected in current window page by user
		var selectedFiles = this.getSelected();
		for ( var i = 0; i < browseFiles.length; i++) {
			var id = browseFiles[i];
			if (!this.inArray(id, selectedFiles)) {
				id = this.getHiddenBrowserId(id);
				var hidden = this.getElementById(id);
				parent.removeChild(hidden);
			}
		}
		for ( var i = 0; i < selectedFiles.length; i++) {
			var id = selectedFiles[i];
			if (this.inArray(id, galleryFiles)) {
				this.removeGalleryFile(id, true);
			}
		}
		this.submit('remove');
	},
	submit : function(task) {
		document.adminForm.task.value = task;
		document.adminForm.submit();
	},
	reset : function() {
		document.adminForm.filter.value = '';
		this.submit('');
	},
	inArray : function(search, array) {
		for ( var i = 0; i < array.length; i++) {
			var value = array[i];
			if (value == search) {
				return true;
			}
		}
		return false;
	},
	getGalleryShowId : function(id) {
		return 'fileGalleryShow' + id;
	},
	getGallerySendId : function(id) {
		return 'fileGallerySend' + id;
	},
	getFileBrowserId : function(id) {
		return 'fileBrowserSource' + id;
	},
	getHiddenBrowserId : function(id) {
		return 'fileBrowserHidden' + id;
	},
	getFileGalleryId : function(id) {
		return 'fileGallerySource' + id;
	},
	getHiddenGalleryId : function(id) {
		return 'fileGalleryHidden' + id;
	},
	getFileMainId : function() {
		return 'fileMainSource';
	},
	getHiddenMainId : function() {
		return 'fileMainHidden';
	},
	getMainRemoveId : function() {
		return 'fileMainRemove';
	},
	getGalleryRemoveId : function() {
		return 'filesGalleryRemove';
	},
	getGalleryCheckAllId : function() {
		return 'filesGalleryCheckAll';
	},
	getId : function(value) {
		var value = ACommon.parseInt(value);
		return value;
	},
	getParent : function(onParent) {
		return this.getElementById('files', onParent);
	},
	getParentFiles : function(onParent) {
		var parent = this.getParent(onParent);
		var files = parent.getElementsByTagName('div');
		return files;
	},
	getElementById : function(id, onParent) {
		if (onParent) {
			return window.parent.document.getElementById(id);
		} else {
			return document.getElementById(id);
		}
	},
	mkdir : function() {
		if (trim(document.adminForm.dirname.value) == '') {
			alert(LGAddDirname);
			return false;
		}
		this.submit('mkdir');
	},
	changeDir : function(dir) {
		document.adminForm.dir.value = dir;	
		this.submit('');
	}
}