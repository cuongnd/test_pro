var AImages = {
	imageUnselect : 'thumb pointer',
	imageSelect : 'thumb thumbOver pointer',
	imageMainHidden : 'thumb blind',
	imageMainVisible : 'thumb',
	imageGallery : 'thumb pointer',
	buttonHidden : 'button blind',
	buttonVisible : 'button',
	init : function() {
		var browseImages = this.getAll(false);
		var galleryImages = this.getAll(true);
		for ( var i = 0; i < browseImages.length; i++) {
			var id = browseImages[i];
			if (this.inArray(id, galleryImages)) {
				var image = this.getElementById(this.getImageBrowserId(id),
						false);
				image.className = this.imageSelect;
			}
		}
	},
	mark : function(id, inBrowser) {
		var id = inBrowser ? this.getImageBrowserId(id) : this
				.getImageGalleryId(id);
		var image = this.getElementById(id, !inBrowser);
		// switch marked/unmarked
		if (image.hasClass(this.imageUnselect)) // unmarked
			image.removeClass(this.imageUnselect).addClass(this.imageSelect); // mark
		else // marked
			image.removeClass(this.imageSelect).addClass(this.imageUnselect); // unmark
	},
	setMain : function(close) {
		var selected = this.getSelected();
		if (selected.length == 0) {
			alert(selectImage);
		} else {
			var id = selected[0];
			var browserImage = this.getElementById(this.getImageBrowserId(id),
					false);
			var browserHidden = this.getElementById(
					this.getHiddenBrowserId(id), false);
			this.updateMain(browserImage.src, this.imageMainVisible,
					browserHidden.value, this.buttonVisible, true);
			if (close) {
				this.close();
			}
		}
	},
	removeMain : function() {
		this.updateMain('', this.imageMainHidden, '', this.buttonHidden, false);
	},
	updateMain : function(mainImageSrc, mainImageClassname, mainHiddenValue,
			removeClassname, onParent) {
		var mainImage = this.getElementById(this.getImageMainId(), onParent);
		var mainHidden = this.getElementById(this.getHiddenMainId(), onParent);
		var remove = this.getElementById(this.getMainRemoveId(), onParent);
		mainHidden.value = mainHiddenValue;
		mainImage.src = mainImageSrc;
		mainImage.className = mainImageClassname;
		remove.className = removeClassname;
	},
	hideRemoveMain : function() {
		var remove = this.getElementById(this.getMainRemoveId(), false);
		
	},
	close : function() {
		try {
			parent.jQuery('body').removeClass('modal-open');
			parent.jQuery("#modal-new").each(function(i, e) {
				jQuery(e).removeClass('in').hide().attr('aria-hidden', true);
			});
			parent.jQuery('.modal-backdrop').remove();
			parent.jQuery('div#toolbar_images-popup-new.btn-group button.btn').trigger('click');
		} catch(e) {}
		window.parent.SqueezeBox.close();
	},
	getSelected : function() {
		var images = this.getParentImages(false);
		var selected = new Array();
		for ( var i = 0; i < images.length; i++) {
			var image = images[i];
			if (image.hasClass('thumbOver')) {
				var id = this.getId(image.id);
				selected.push(id);
			}
		}
		return selected;
	},
	getAll : function(onParent) {
		var images = this.getParentImages(onParent);
		var all = new Array();
		for ( var i = 0; i < images.length; i++) {
			var image = images[i];
			var id = this.getId(image.id);
			all.push(id);
		}
		return all;
	},
	setGallery : function(close) {
		var parent = this.getParent(true);
		var browseImages = this.getAll(false);
		var galleryImages = this.getAll(true);
		var selectedImages = this.getSelected();
		for ( var i = 0; i < browseImages.length; i++) {
			var id = browseImages[i];
			var isSelect = this.inArray(id, selectedImages);
			var isAdded = this.inArray(id, galleryImages);
			if (!isSelect && isAdded) {
				this.removeGalleryImage(id, true);
			} else if (isSelect && !isAdded) {
				var galleryImage = window.parent.document.createElement('img');
				var galleryHidden = window.parent.document
						.createElement('input');
				var browseImage = this.getElementById(this
						.getImageBrowserId(id), false);
				var browseHidden = this.getElementById(this
						.getHiddenBrowserId(id), false);
				galleryImage.id = this.getImageGalleryId(id);
				galleryImage.src = browseImage.src;
				galleryImage.className = this.imageGallery;
				galleryImage.onclick = function() {
					this.className = this.className == 'thumb pointer' ? 'thumb thumbOver pointer'
							: 'thumb pointer';
				};
				galleryHidden.id = this.getHiddenGalleryId(id);
				galleryHidden.type = 'hidden';
				galleryHidden.name = 'images[]';
				galleryHidden.value = browseHidden.value;
				parent.appendChild(galleryImage);
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
			this.removeGalleryImage(id, false);
		}
		this.updateGalleryToolbar(false);
	},
	removeGalleryImage : function(id, onParent) {
		var parent = this.getParent(onParent);
		var galleryImage = this.getElementById(this.getImageGalleryId(id),
				onParent);
		var galleryHidden = this.getElementById(this.getHiddenGalleryId(id),
				onParent);
		parent.removeChild(galleryImage);
		parent.removeChild(galleryHidden);
		if (galleryHidden.value == $('image').value)
			$('image').value = '';
	},
	
	updateGalleryToolbar : function(onParent) {
		var remove = this.getElementById(this.getGalleryRemoveId(), onParent);		
		var defaul = this.getElementById(this.getGalleryDefaultId(), onParent);
		var checkall = this.getElementById(this.getGalleryCheckAllId(), onParent);
		var uncheckall = this.getElementById(this.getGalleryUnCheckAllId(), onParent);
		var galleryImages = this.getAll(onParent);
		var className = galleryImages.length ? this.buttonVisible : this.buttonHidden;
		remove.className = className;
		defaul.className = className;
		checkall.className = className;
		uncheckall.className = className;
	},
	checkAll : function(el, inBrowser, check) {
		var images = this.getAll(false);
		for ( var i = 0; i < images.length; i++) {
			var id = images[i];
			id = inBrowser ? this.getImageBrowserId(id) : this
					.getImageGalleryId(id);
			var image = this.getElementById(id, false);
			check ? image.addClass('thumbOver') : image.removeClass('thumbOver');
		}
	},
	upload : function() {
		this.submit('upload');
	},
	remove : function() {
		var parent = this.getParent(false);
		// images in current window page
		var browseImages = this.getAll(false);
		// images selected into gallery
		var galleryImages = this.getAll(true);
		// images selected in current window page by user
		var selectedImages = this.getSelected();
		for ( var i = 0; i < browseImages.length; i++) {
			var id = browseImages[i];
			if (!this.inArray(id, selectedImages)) {
				id = this.getHiddenBrowserId(id);
				var hidden = this.getElementById(id);
				parent.removeChild(hidden);
			}
		}
		for ( var i = 0; i < selectedImages.length; i++) {
			var id = selectedImages[i];
			if (this.inArray(id, galleryImages)) {
				this.removeGalleryImage(id, true);
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
	getImageBrowserId : function(id) {
		return 'imageBrowserSource' + id;
	},
	getHiddenBrowserId : function(id) {
		return 'imageBrowserHidden' + id;
	},
	getImageGalleryId : function(id) {
		return 'imageGallerySource' + id;
	},
	getHiddenGalleryId : function(id) {
		return 'imageGalleryHidden' + id;
	},
	getImageMainId : function() {
		return 'imageMainSource';
	},
	getHiddenMainId : function() {
		return 'imageMainHidden';
	},
	getMainRemoveId : function() {
		return 'imageMainRemove';
	},
	getGalleryRemoveId : function() {
		return 'imagesGalleryRemove';
	},
	getGalleryDefaultId : function() {
		return 'imagesGalleryDefault';
	},
	getGalleryCheckAllId : function() {
		return 'imagesGalleryCheckAll';
	},
	getGalleryUnCheckAllId : function() {
		return 'imagesGalleryUnCheckAll';
	},
	getId : function(value) {
		var value = ACommon.parseInt(value);
		return value;
	},
	getParent : function(onParent) {
		return this.getElementById('images', onParent);
	},
	getParentImages : function(onParent) {
		var parent = this.getParent(onParent);
		var images = parent.getElementsByTagName('img');
		return images;
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
	},
	
	/**
	 * Set first selected image as default.
	 */
	setDefault : function() {
		$$('img.thumbDefault').removeClass('thumbDefault'); // reset current default
		$('image').value = '';
		if ($$('img.thumbOver').length > 0) { // all selected
			$('image').value = $(this.getHiddenGalleryId(this.getId($$('img.thumbOver')[0].id))).value;
			$$('img.thumbOver')[0].addClass('thumbDefault'); // set first as default
		} else
			alert(LGSelectToDefault);
		$$('img.thumbOver').removeClass('thumbOver'); // reset current selection
	}
}