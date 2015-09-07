FD31.installer("EasyBlog", "resources", function($){
$.require.template.loader({"easyblog\/media\/recent.item":"<div class=\"recentItem loading\">\n\t<div class=\"item-wrap\">\n\t\t<i><\/i>\n\t\t<div class=\"item-image\">\n\t\t\t<div class=\"item-image-wrap\">\n\t\t\t\t<img class=\"itemIcon\" src=\"[%= meta.icon.url %]\" \/>\n\t\t\t<\/div>\n\t\t<\/div>\n\t\t<div class=\"itemTitle\">\n\t\t\t<span>[%= meta.title %]<\/span>\n\t\t\t<span class=\"itemProgress\">Inserting item.<\/span>\n\t\t<\/div>\n\t<\/div>\n<\/div>\n","easyblog\/media\/browser":"<div class=\"modalHeader\">\n\t<div class=\"modalTitle\">\n\t\t<span class=\"modalTitleSnippet title-browse\">Browse media<\/span>\n\t\t<span class=\"modalTitleSnippet title-blogimage\">Select blog image<\/span>\n\t<\/div>\n\t<div class=\"modalButtons\">\n\t\t<button class=\"dashboardButton modalButton\"><i><\/i><\/button>\n\t\t[% if (canUpload) { %]\n\t\t<button class=\"uploaderButton modalButton\"><i><\/i>Upload Media<\/button>\n\t\t[% } %]\n\t<\/div>\n\t<div class=\"browserSearch\">\n\t\t<i class=\"cancelSearchButton\"><\/i>\n\t\t<input type=\"text\" class=\"searchInput\" \/>\n\t<\/div>\n<\/div>\n\n<div class=\"modalToolbar browserHeader\">\n\t<div class=\"navigation\">\n\t\t<div class=\"navigationInfo\">You are selecting this item:<\/div>\n\t\t<div class=\"navigationPathway browserNavigation\"><\/div>\n\t<\/div>\n\t<div class=\"topActions browserItemActions\">\n\t\t<span class=\"browserItemActionSet type-folder\">\n\t\t\t<button class=\"insertAsGalleryButton button green-button\"><i><\/i>Insert as gallery<\/button>\n\t\t\t<button class=\"removeItemButton button\"><i><\/i>Delete<\/button>\n\t\t<\/span>\n\t\t<span class=\"browserItemActionSet type-item\">\n\t\t\t<button class=\"insertBlogImageButton button green-button\"><i><\/i>Use as blog image<\/button>\n\t\t\t<button class=\"insertItemButton button green-button\"><i><\/i>Insert<\/button>\n\t\t\t<button class=\"customizeItemButton button\"><i><\/i>Customize<\/button>\n\t\t\t<button class=\"removeItemButton button\"><i><\/i>Delete<\/button>\n\t\t<\/span>\n\t<\/div>\n<\/div>\n\n<div class=\"modalContent browserContent\">\n\t<div class=\"browserTreeItemField\">\n\t<\/div>\n\t<div class=\"browserItemField\">\n\t\t<div class=\"hints\">\n\t\t\t<div class=\"hint hint-loading\">\n\t\t\t\t<i><\/i>\n\t\t\t\t<span class=\"hint-content\">Getting file list from server...<\/span>\n\t\t\t<\/div>\n\t\t\t<div class=\"hint hint-error\">\n\t\t\t\t<i><\/i>\n\t\t\t\t<span class=\"hint-content\">Unable to get file list from server<\/span>\n\t\t\t\t<button class=\"retryPopulateButton button\">Try again<\/button>\n\t\t\t<\/div>\n\t\t\t<div class=\"hint hint-empty\">\n\t\t\t\t<span class=\"hint-content\">There are no contents in this folder.<\/span>\n\t\t\t\t[% if (canUpload) { %]\n\t\t\t\t<button class=\"uploaderButton button\"><i><\/i>Upload to this folder<\/button>\n\t\t\t\t[% } %]\n\t\t\t<\/div>\n\t\t\t<div class=\"hint hint-emptySearch\">\n\t\t\t\t<span class=\"hint-content\">Your search returns no result.<\/span>\n\t\t\t\t<button class=\"cancelSearchButton button\"><i><\/i>Back<\/button>\n\t\t\t<\/div>\n\t\t\t<div class=\"hint hint-flickr\">\n\t\t\t\t<span class=\"hint-content\">You have not authorized your Flickr account with us yet.<br \/>To link to your Flickr account, you will need to sign in with your Flickr account.<\/span>\n\t\t\t\t<button class=\"button green-button flickrLoginButton\"\n\t\t\t\t\tdata-callback=\"[%= flickrCallback %]\"\n\t\t\t\t\tdata-login=\"[%= flickrLogin %]\"><i><\/i>Sign in to Flickr<\/button>\n\t\t\t<\/div>\t\t\t\n\t\t<\/div>\n\t<\/div>\n<\/div>\n\n<div class=\"modalFooter browserFooter\">\n\t<div class=\"browserTreeItemFooter\">\n\t\t<div class=\"footerActions left-actions\">\n\t\t\t<button class=\"createFolderButton button\"><i><\/i>Create folder<\/button>\n\t\t<\/div>\n\t<\/div>\n\t<div class=\"browserItemFooter\">\n\t\t<div class=\"footerActions left-actions browserViewButtons\">\n\t\t\t<button class=\"browserListViewButton button\"><i><\/i><\/button>\n\t\t\t<button class=\"browserTileViewButton button active\"><i><\/i><\/button>\n\t\t<\/div>\n\t\t<div class=\"footerActions right-actions browserPagination\">\n\t\t\t<button class=\"nextPageButton button\"><i><\/i><\/button>\n\t\t\t<label class=\"pageNumber\"><ul class=\"pageSelection\"><li class=\"paginationPage page1 selected\" data-page=\"1\">Page 1<\/li><\/ul><span class=\"pageBreakdown\"><span class=\"currentPage\"><\/span>\/<span class=\"totalPage\"><\/span><\/span><\/label>\n\t\t\t<button class=\"prevPageButton button\"><i><\/i><\/button>\n\t\t<\/div>\n\t<\/div>\n<\/div>\n\n<div class=\"modalPrompt\">\n\t<div class=\"modalPromptDialogs\">\n\t\t<div class=\"modalPromptDialog createFolderPrompt\">\n\t\t\t<div class=\"promptState state-default\">\n\t\t\t\t<div class=\"promptTitle\">Create folder<\/div>\n\t\t\t\t<span class=\"promptText\">You are creating a folder inside <span class=\"folderPath\"><\/span>.<\/span>\n\t\t\t\t<div class=\"promptForm\"><label>New folder name <input type=\"text\" class=\"folderInput text\" val=\"\" \/><\/label><\/div>\n\t\t\t\t<div class=\"promptActions\">\n\t\t\t\t\t<button class=\"button promptCancelButton\"><i><\/i>Cancel<\/button>\n\t\t\t\t\t<button class=\"button green-button confirmCreateFolderButton\"><i><\/i>Create folder<\/button>\n\t\t\t\t<\/div>\n\t\t\t<\/div>\n\t\t\t<div class=\"promptState state-progress\">\n\t\t\t\t<div class=\"promptTitle\">Creating folder...<\/div>\n\t\t\t\t<span class=\"promptText\">Please wait while folder <span class=\"folderCreationPath\"><\/span> is being created on the server.<\/span>\n\t\t\t\t<div class=\"promptActions\">\n\t\t\t\t\t<button class=\"button promptCancelButton\"><i><\/i>Cancel<\/button>\n\t\t\t\t<\/div>\n\t\t\t\t<div class=\"promptLoader\"><\/div>\n\t\t\t<\/div>\n\t\t\t<div class=\"promptState state-fail\">\n\t\t\t\t<div class=\"promptTitle\">Create folder failed.<\/div>\n\t\t\t\t<span class=\"promptText\">Unable to create the folder <span class=\"folderCreationPath\"><\/span>. <span class=\"folderCreationFailedReason\"><\/span><\/span>\n\t\t\t\t<div class=\"promptActions\">\n\t\t\t\t\t<button class=\"button promptCancelButton\"><i><\/i>Cancel<\/button>\n\t\t\t\t\t<button class=\"button green-button createFolderButton\"><i><\/i>Try again<\/button>\n\t\t\t\t<\/div>\n\t\t\t<\/div>\n\t\t<\/div>\n\t\t<div class=\"modalPromptDialog removeItemPrompt\">\n\t\t\t<div class=\"promptState state-default\">\n\t\t\t\t<div class=\"promptTitle\">Delete item<\/div>\n\t\t\t\t<span class=\"promptText\">Do you want to delete the item <span class=\"removeItemFilename\"><\/span>?<br\/><br\/>Note: This operation only deletes the file on the server. If this item is being used in an existing article, you will need to manually remove it from the article content.<\/small><\/span>\n\t\t\t\t<div class=\"promptActions\">\n\t\t\t\t\t<button class=\"button promptCancelButton\"><i><\/i>Cancel<\/button>\n\t\t\t\t\t<button class=\"button green-button confirmRemoveItemButton\"><i><\/i>Delete<\/button>\n\t\t\t\t<\/div>\n\t\t\t<\/div>\n\t\t\t<div class=\"promptState state-progress\">\n\t\t\t\t<div class=\"promptTitle\">Deleting item<\/div>\n\t\t\t\t<span class=\"promptText\">Please wait while the item <span class=\"removeItemFilename\"><\/span> is being deleted from the server.<\/span>\n\t\t\t\t<span class=\"promptText\"><\/span>\n\t\t\t\t<div class=\"promptActions\">\n\t\t\t\t\t<button class=\"button promptCancelButton\"><i><\/i>Cancel<\/button>\n\t\t\t\t<\/div>\n\t\t\t\t<div class=\"promptLoader\"><\/div>\n\t\t\t<\/div>\n\t\t\t<div class=\"promptState state-fail\">\n\t\t\t\t<div class=\"promptTitle\">Delete item failed<\/div>\n\t\t\t\t<span class=\"promptText\">Unable to delete the item %1s from the server. <span class=\"removeItemFailedReason\"><\/span><\/span>\n\t\t\t\t<div class=\"promptActions\">\n\t\t\t\t\t<button class=\"button promptCancelButton\"><i><\/i>Cancel<\/button>\n\t\t\t\t\t<button class=\"button green-button confirmRemoveItemButton\"><i><\/i>Try again<\/button>\n\t\t\t\t<\/div>\n\t\t\t<\/div>\n\t\t<\/div>\n\t\t<div class=\"overlay\"><\/div>\n\t<\/div>\n<\/div>\n","easyblog\/media\/browser.item-group":"<div class=\"browserItemGroup\"><\/div>\n","easyblog\/media\/browser.item":"<div class=\"browserItem\">\n\t<div class=\"item-wrap\">\n\t<div class=\"item-wrap-outer\">\n\t<div class=\"item-wrap-inner\">\n\t\t<i class=\"loading\"><\/i>\n\t\t<img class=\"itemIcon\" \/>\n\t\t<div class=\"itemTitle\"><span>[%= meta.title %]<\/span><\/div>\n\t<\/div>\n\t<\/div>\n\t<\/div>\n<\/div>\n","easyblog\/media\/browser.tree-item-group":"<div class=\"browserTreeItemGroup\"><\/div>\n","easyblog\/media\/browser.tree-item":"<div class=\"browserTreeItem\"><i class=\"treeItemToggle\"><\/i><span class=\"treeItemTitle\">[%= title %]<\/span><\/div>\n","easyblog\/media\/browser.pagination-page":"<li class=\"paginationPage page[%= page %]\" data-page=\"[%= page %]\">Page [%= page %]<\/li>\n","easyblog\/media\/browser.uploader":"\n<div class=\"modalHeader\">\n\t<div class=\"modalTitle\">Upload Media<\/div>\n\t<div class=\"modalButtons\">\n\t\t<button class=\"dashboardButton modalButton\"><i><\/i><\/button>\n\t\t<button class=\"browserButton modalButton\"><i><\/i>Browse media<\/button>\n\t<\/div>\n<\/div>\n\n<div class=\"modalToolbar\">\n\t<div class=\"navigation\">\n\t\t<div class=\"navigationInfo\">You are uploading to this folder:<\/div>\n\t\t<div class=\"navigationPathway uploadNavigation\"><\/div>\n\t<\/div>\n\n\t<div class=\"topActions\">\n\t\t<button class=\"uploadButton button green-button\">\n\t\t\t<i><\/i>Add media\t\t<\/button>\n\t\t<button class=\"changeUploadFolderButton button\"><i><\/i>Change upload folder<\/button>\n\t\t<div class=\"uploadInstructions button-tooltip\">\n\t\t\t<i><\/i>\n\t\t\t<span>You can upload up to <span class=\"uploadSize\">[%= uploadSize %]<\/span> per file.\t\t\t<span class=\"uploadExtensions\">Supports <span class=\"uploadExtensionList\">[%= uploadExtensionList %]<\/span>.<\/span><\/span>\n\t\t<\/div>\n\t<\/div>\n<\/div>\n\n<div class=\"modalContent\">\n\t<div class=\"uploadItemGroup empty\"><\/div>\n\t<div class=\"uploadDropHint hints\"><div class=\"hint hint-dropUpload\"><i><\/i><span class=\"hint-label\">Drop a file here to upload.<\/span><\/div><\/div>\n<\/div>\n\n<div class=\"modalFooter\">\n\t<div class=\"footerActions left-actions\">\n\t\t<button class=\"clearListButton button\"><i><\/i>Clear list<\/button>\n\t<\/div>\n<\/div>\n\n<div class=\"modalPrompt\">\n\t<div class=\"modalPromptDialogs\">\n\t\t<div class=\"modalPromptDialog changeUploadFolderPrompt\">\n\t\t\t<div class=\"promptTitle\">Change upload folder<\/div>\n\t\t\t<div class=\"promptContent\">\n\t\t\t\t<div class=\"browserTreeItemField\">\n\t\t\t\t<\/div>\n\t\t\t<\/div>\n\t\t\t<div class=\"promptActions\">\n\t\t\t\t<button class=\"button promptCancelButton\"><i><\/i>Cancel<\/button>\n\t\t\t\t<button class=\"button green-button selectFolderButton\"><i><\/i>Select folder<\/button>\n\t\t\t<\/div>\n\t\t<\/div>\n\t<\/div>\n<\/div>\n","easyblog\/media\/browser.uploader.item":"<div class=\"uploadItem\">\n\t<div class=\"item-wrap\">\n\t\t<i class=\"uploadIcon uploadRemoveButton\"><\/i>\n\t\t<span class=\"uploadFilename\"><\/span>\n\t\t<div class=\"uploadProgressBar\">\n\t\t\t<progress max=\"100\" value=\"0\"><\/progress>\n\t\t\t<div class=\"progress-alt\"><img src=\"http:\/\/hoteclick.com\/media\/com_easyblog\/scripts_\/media\/progress.gif\"\/><\/div>\n\t\t<\/div>\n\t\t<span class=\"uploadStatus\">Pending<\/span>\n\t\t<div class=\"uploadActions\">\n\t\t\t<button class=\"insertBlogImageButton button green-button\"><i><\/i>Use as blog image<\/button>\n\t\t\t<button class=\"insertItemButton green-button button\"><i><\/i>Insert<\/button>\n\t\t\t<button class=\"locateItemButton button\"><i><\/i>Locate<\/button>\n\t\t<\/div>\n\t<\/div>\n<\/div>\n","easyblog\/media\/editor":"<div class=\"modalHeader\">\n\t<div class=\"modalTitle\">Customize<\/div>\n\t<div class=\"modalButtons\">\n\t\t<button class=\"dashboardButton modalButton\"><i><\/i><\/button>\n\t<\/div>\n<\/div>\n\n<div class=\"modalToolbar\">\n\t<div class=\"navigation\">\n\t\t<div class=\"navigationInfo\">You are customizing this item:<\/div>\n\t\t<div class=\"navigationPathway itemPath\"><\/div>\n\t<\/div>\n\t<div class=\"topActions\">\n\t\t<button class=\"insertItemButton button green-button\"><i><\/i>Insert<\/button>\n\t\t<button class=\"cancelEditingButton button\"><i><\/i>Cancel<\/button>\n\t<\/div>\n<\/div>\n\n<div class=\"modalContent\">\n\t<div class=\"editorLoading\"><div class=\"loadingHint\"><i><\/i><\/div><\/div>\n<\/div>\n","easyblog\/media\/editor.viewport":"<div class=\"editorViewport\">\n\t<div class=\"editorPanel\"><\/div>\n\t<div class=\"editorPreview\">\n\t\t<div class=\"previewContainer\"><\/div>\n\n\t\t<div class=\"previewDialogGroup\">\n\t\t\t<div class=\"previewDialog dialog-loading\"><\/div>\n\t\t<\/div>\n\t<\/div>\n<\/div>\n","easyblog\/media\/navigation.item":"<span class=\"navigationItem\"><i><\/i>[%= title %]<\/span>\n","easyblog\/media\/navigation.itemgroup":"<span class=\"navigationItemGroup\"><span class=\"navigationItemStub\">...<\/span><\/span>\n","easyblog\/media\/editor.audio":"\n<div class=\"panelSection infoPanel active\">\n\t<div class=\"panelSectionContent\">\n\n\t\t<div class=\"itemInfo\">\n\t\t\t<i><\/i>\n\t\t\t<span class=\"itemFilename\">[%= meta.title %]<\/span>\n\t\t\t<span class=\"itemFilesize\">[%= meta.filesize %]<\/span>\n\t\t\t<span class=\"itemCreationDate\">[%= meta.creationDate %]<\/span>\n\t\t<\/div>\n\n\t\t<div class=\"itemExtraInfo\">\n\t\t\t<span class=\"itemUrl\">[%= meta.url %]<\/span>\n\t\t<\/div>\n\t<\/div>\n<\/div>\n\n<div class=\"panelSection insertOptionsPanel active\">\n\t<div class=\"panelSectionHeader\">\n\t\t<div class=\"panelSectionTitle\"><i><\/i>Insert Options<\/div>\n\t<\/div>\n\n\t<div class=\"panelSectionContent\">\n\t\t<ul class=\"reset-ul list-form\">\n\t\t\t<li>\n\t\t\t\t<label>Autoplay:<\/label>\n\t\t\t\t<select name=\"autoplay\" class=\"autoplay\">\n\t\t\t\t\t<option value=\"1\">Yes<\/option>\n\t\t\t\t\t<option value=\"0\">No<\/option>\n\t\t\t\t<\/select>\n\t\t\t<\/li>\n\t\t<\/ul>\n\t<\/div>\n<\/div>\n","easyblog\/media\/editor.audio.player":"\n<div id=\"[%= id %]\" class=\"playerContainer\"><\/div>\n","easyblog\/media\/editor.file":"\n<div class=\"panelSection infoPanel active\">\n\t<div class=\"panelSectionContent\">\n\t\t<div class=\"itemInfo\">\n\t\t\t<i><\/i>\n\t\t\t<span class=\"itemFilename\">[%= meta.title %]<\/span>\n\t\t\t<span class=\"itemFilesize\">[%= meta.filesize %]<\/span>\n\t\t\t<span class=\"itemCreationDate\">[%= meta.creationDate %]<\/span>\n\t\t<\/div>\n\n\t\t<div class=\"itemExtraInfo\">\n\t\t\t<span class=\"itemUrl\">[%= meta.url %]<\/span>\n\t\t<\/div>\n\t<\/div>\n<\/div>\n\n<div class=\"panelSection insertOptionsPanel active\">\n\t<div class=\"panelSectionHeader\">\n\t\t<div class=\"panelSectionTitle\"><i><\/i>Insert Options<\/div>\n\t<\/div>\n\n\t<div class=\"panelSectionContent\">\n\t\t<ul class=\"reset-ul list-form\">\n\t\t\t<li>\n\t\t\t\t<label>Link item to:<\/label>\n\t\t\t\t<select name=\"insertAs\" class=\"insertAs\">\n\t\t\t\t\t<option value=\"_blank\">A new page<\/option>\n\t\t\t\t\t<option value=\"_self\">The same page<\/option>\n\t\t\t\t<\/select>\n\t\t\t<\/li>\n\t\t\t<li>\n\t\t\t\t<label>Caption:<\/label>\n\t\t\t\t<textarea style=\"width:250px;height:80px\" class=\"insertCaption\">[%= meta.title %]<\/textarea>\n\t\t\t<\/li>\n\t\t<\/ul>\n\t<\/div>\n<\/div>\n","easyblog\/media\/editor.file.preview":"<div class=\"filePreviewContainer\"><a title=\"[%= meta.title %]\" target=\"[%= target %]\" href=\"[%= meta.url %]\">[%= content %]<\/a><\/div>\n","easyblog\/media\/editor.image":"\n<div class=\"panelSection infoPanel active\">\n\t<div class=\"panelSectionContent\">\n\n\t\t<div class=\"itemInfo\">\n\t\t\t<i><\/i>\n\t\t\t<span class=\"itemFilename\">[%= meta.title %]<\/span>\n\t\t\t<span class=\"itemFilesize\">[%= meta.filesize %]<\/span>\n\t\t\t<span class=\"itemCreationDate\">[%= meta.creationDate %]<\/span>\n\t\t<\/div>\n\n\t\t<div class=\"itemExtraInfo\">\n\t\t\t<span class=\"itemUrl\">[%= meta.url %]<\/span>\n\t\t<\/div>\n\t<\/div>\n<\/div>\n\n<div class=\"panelSection imageVariationPanel active\">\n\t<div class=\"panelSectionHeader\">\n\t\t<div class=\"panelSectionTitle\"><i><\/i>Available sizes<\/div>\n\t<\/div>\n\n\t[% var readOnly = (!acl.canCreateVariation && !acl.canDeleteVariation); %]\n\n\t<div class=\"panelSectionContent\">\n\t\t<div class=\"imageVariationList [%= (readOnly) ? \"readOnly\" : \"\" %]\">\n\t\t\t<div class=\"imageVariations\">\n\t\t\t<\/div>\n\n\t\t\t[% if (!readOnly) { %]\n\t\t\t<div class=\"imageVariationActions\">\n\n\t\t\t\t[% if (acl.canDeleteVariation) { %]\n\t\t\t\t<button type=\"button\" class=\"removeVariationButton\"><i><\/i>Remove<\/button>\n\t\t\t\t[% } %]\n\n\t\t\t\t[% if (acl.canCreateVariation) { %]\n\t\t\t\t<button type=\"button\" class=\"addVariationButton\"><i><\/i>New size<\/button>\n\t\t\t\t[% } %]\n\t\t\t<\/div>\n\t\t\t[% } %]\n\t\t<\/div>\n\t<\/div>\n<\/div>\n\n<div class=\"panelSection insertOptionsPanel active\">\n\t<div class=\"panelSectionHeader\">\n\t\t<div class=\"panelSectionTitle\"><i><\/i>Appearance<\/div>\n\t<\/div>\n\n\t<div class=\"panelSectionContent\">\n\t\t<ul class=\"reset-ul list-form\">\n\t\t\t<li class=\"field hide-field-content\">\n\t\t\t\t<input type=\"checkbox\" class=\"imageCaptionOption\" name=\"imageCaptionOption\" \/>\n\t\t\t\t<label>Add image caption<\/label>\n\t\t\t\t<div class=\"field-content\">\n\t\t\t\t\t<input type=\"text\" class=\"imageCaption\" name=\"imageCaption\" value=\"[%= meta.title %]\" \/>\n\t\t\t\t<\/div>\n\t\t\t<\/li>\n\t\t\t<li class=\"field [%= (enableLightbox) ? \"\" : \"hide-field-content\" %]\">\n\t\t\t\t<input type=\"checkbox\" class=\"imageZoomOption\" name=\"imageZoomOption\" [%= (enableLightbox) ? \"checked\" : \"\" %] \/>\n\t\t\t\t<label>Enable lightbox<\/label>\n\t\t\t\t<div class=\"field-content\">\n\t\t\t\t\tUse large image:\n\t\t\t\t\t<select class=\"imageZoomLargeImageSelection\"><\/select>\n\t\t\t\t<\/div>\n\t\t\t<\/li>\n\t\t\t<li class=\"field hide-field-content\">\n\t\t\t\t<input type=\"checkbox\" class=\"imageEnforceDimensionOption\" name=\"imageEnforceDimension\" [%= (enforceImageDimension) ? \"checked\" : \"\" %]\/>\n\t\t\t\t<label>Enforce image dimension<\/label>\n\t\t\t\t<div class=\"field-content imageEnforceDimension\">\n\t\t\t\t\t<label style=\"width: 70px; text-align: right; padding: 0 5px; display: inline-block; line-height: 25px;\">Width<\/label><input style=\"width: 80px; height: 15px;\" type=\"text\" class=\"imageEnforceWidth\" name=\"imageEnforceWidth\" value=\"[%= enforceImageWidth %]\" initial=\"[%= enforceImageWidth %]\" \/>\n\t\t\t\t\t<br\/>\n\t\t\t\t\t<label style=\"width: 70px; text-align: right; padding: 0 5px; display: inline-block; line-height: 25px;\">Height<\/label><input style=\"width: 80px; height: 15px;\" type=\"text\" class=\"imageEnforceHeight\" name=\"imageEnforceHeight\" value=\"[%= enforceImageHeight %]\" initial=\"[%= enforceImageHeight %]\" \/>\n\t\t\t\t\t<div class=\"imageEnforceRatio locked\"><\/div>\n\t\t\t\t\t<input class=\"imageEnforceLockRatio\" type=\"checkbox\" checked=\"checked\" \/>\n\t\t\t\t<\/div>\n\t\t\t<\/li>\n\t\t<\/ul>\n\t<\/div>\n<\/div>\n\n<div class=\"modalPrompt\">\n\t<div class=\"modalPromptDialogs\">\n\t\t<div class=\"modalPromptDialog createNewImageVariationPrompt\">\n\t\t\t<div class=\"promptState state-default\">\n\t\t\t\t<div class=\"promptTitle\">Create new image variation<\/div>\n\t\t\t\t<span class=\"promptText\">You are creating a new image variation<\/span>\n\t\t\t\t<div class=\"promptForm imageVariationForm\">\n\t\t\t\t\t<div class=\"formGroup\">\n\t\t\t\t\t\t<label class=\"formLabel\">Name<\/label>\n\t\t\t\t\t\t<div class=\"formControl\"><input type=\"text\" class=\"imageSizeInput newVariationName\"><\/div>\n\t\t\t\t\t<\/div>\n\t\t\t\t\t<div class=\"formGroup\">\n\t\t\t\t\t\t<label class=\"formLabel\">Width<\/label>\n\t\t\t\t\t\t<div class=\"formControl\"><input type=\"text\" class=\"imageSizeInput newVariationWidth\"><\/div>\n\t\t\t\t\t<\/div>\n\t\t\t\t\t<div class=\"formGroup\">\n\t\t\t\t\t\t<label class=\"formLabel\">Height<\/label>\n\t\t\t\t\t\t<div class=\"formControl\"><input type=\"text\" class=\"imageSizeInput newVariationHeight\"><\/div>\n\t\t\t\t\t<\/div>\n\t\t\t\t\t<div class=\"newVariationRatio locked\"><\/div>\n\t\t\t\t\t<input class=\"newVariationLockRatio\" type=\"checkbox\" checked=\"checked\" \/>\n\t\t\t\t<\/div>\n\t\t\t\t<div class=\"promptActions\">\n\t\t\t\t\t<button class=\"button promptCancelButton cancelVariationButton\"><i><\/i>Cancel<\/button>\n\t\t\t\t\t<button class=\"button green-button createVariationButton\"><i><\/i>Create<\/button>\n\t\t\t\t<\/div>\n\t\t\t<\/div>\n\t\t\t<div class=\"promptState state-progress\">\n\t\t\t\t<div class=\"promptTitle\">Creating variation<\/div>\n\t\t\t\t<span class=\"promptText\">Please wait while the variation <span class=\"variationName\">.<\/span> (<span class=\"variationWidth\">.<\/span> x <span class=\"variationHeight\">.<\/span>) is being created on the server.<\/span>\n\t\t\t\t<div class=\"promptLoader\"><\/div>\n\t\t\t<\/div>\n\t\t\t<div class=\"promptState state-fail\">\n\t\t\t\t<div class=\"promptTitle\">Create variation failed<\/div>\n\t\t\t\t<span class=\"promptText\">Unable to create variation <span class=\"variationName\">.<\/span> (<span class=\"variationWidth\">.<\/span> x <span class=\"variationHeight\">.<\/span>)<\/span>\n\t\t\t\t<div class=\"promptActions\">\n\t\t\t\t\t<button class=\"button promptCancelButton cancelVariationButton\"><i><\/i>Cancel<\/button>\n\t\t\t\t\t<button class=\"button green-button tryCreateVariationButton\"><i><\/i>Try again<\/button>\n\t\t\t\t<\/div>\n\t\t\t<\/div>\n\t\t<\/div>\n\t\t<div class=\"overlay\"><\/div>\n\t<\/div>\n<\/div>\n","easyblog\/media\/editor.image.variation":"<div class=\"imageVariation\">\n\t<i><\/i>\n\t<span class=\"variationName\">[%= $.String.capitalize(variation.name) %]<\/span>\n\t[% if ($.isNumeric(variation.width) && $.isNumeric(variation.height) ){ %]\n\t<span class=\"variationDimension\">[%= variation.width %]x[%=variation.height %]<\/span>\n\t[% } %]\n<\/div>\n","easyblog\/media\/editor.image.caption":"<div class=\"imageCaptionText\">[%= caption %]<\/div>\n","easyblog\/media\/editor.video":"\n<div class=\"panelSection infoPanel active\">\n\t<div class=\"panelSectionContent\">\n\n\t\t<div class=\"itemInfo\">\n\t\t\t<i><\/i>\n\t\t\t<span class=\"itemFilename\">[%= meta.title %]<\/span>\n\t\t\t<span class=\"itemFilesize\">[%= meta.filesize %]<\/span>\n\t\t\t<span class=\"itemCreationDate\">[%= meta.creationDate %]<\/span>\n\t\t<\/div>\n\n\t\t<div class=\"itemExtraInfo\">\n\t\t\t<span class=\"itemUrl\">[%= meta.url %]<\/span>\n\t\t<\/div>\n\t<\/div>\n<\/div>\n\n<div class=\"panelSection insertOptionsPanel active\">\n\t<div class=\"panelSectionHeader\">\n\t\t<div class=\"panelSectionTitle\"><i><\/i>Insert Options<\/div>\n\t<\/div>\n\n\t<div class=\"panelSectionContent\">\n\t\t<ul class=\"reset-ul list-form\">\n\t\t\t<li>\n\t\t\t\t<label>Width:<\/label>\n\t\t\t\t<input type=\"text\" style=\"width:250px\" value=\"[%= insertWidth %]\" class=\"insertWidth\" \/>\n\t\t\t<\/li>\n\t\t\t<li>\n\t\t\t\t<label>Height:<\/label>\n\t\t\t\t<input type=\"text\" style=\"width:250px\" value=\"[%= insertHeight %]\" class=\"insertHeight\" \/>\n\t\t\t<\/li>\n\t\t\t<li>\n\t\t\t\t<label>Autoplay:<\/label>\n\t\t\t\t<select name=\"autoplay\" class=\"autoplay\">\n\t\t\t\t\t<option value=\"1\">Yes<\/option>\n\t\t\t\t\t<option value=\"0\">No<\/option>\n\t\t\t\t<\/select>\n\t\t\t<\/li>\n\t\t<\/ul>\n\t<\/div>\n<\/div>\n","easyblog\/media\/editor.video.player":"\n<div id=\"[%= id %]\" class=\"playerContainer\"><\/div>\n","easyblog\/dashboard\/dashboard.tags.item":"<li class=\"tag-item\">\n\t<a href=\"javascript: void(0);\" class=\"remove-tag\">x<\/a>\n\t<span class=\"tag-title\">[%= title %]<\/span>\n<\/li>\n"});
$.require.language.loader({"COM_EASYBLOG_MM_UNABLE_TO_FIND_EXPORTER":"Unable to find exporter for this item type.","COM_EASYBLOG_MM_GETTING_IMAGE_SIZES":"Getting image sizes.","COM_EASYBLOG_MM_UNABLE_TO_RETRIEVE_VARIATIONS":"Unable to retrieve image variations.","COM_EASYBLOG_MM_ITEM_INSERTED":"Item inserted.","COM_EASYBLOG_MM_UPLOADING":"Uploading","COM_EASYBLOG_MM_UPLOADING_STATE":"Uploading...","COM_EASYBLOG_MM_UPLOADING_PENDING":"Pending","COM_EASYBLOG_MM_UPLOAD_COMPLETE":"Complete.","COM_EASYBLOG_MM_UPLOAD_PREPARING":"Preparing to upload.","COM_EASYBLOG_MM_UPLOAD_UNABLE_PARSE_RESPONSE":"Unable to parse server response.","COM_EASYBLOG_MM_UPLOADING_LEFT":"left","COM_EASYBLOG_MM_CONFIRM_DELETE_ITEM":"Are you sure you want to delete this item?","COM_EASYBLOG_MM_CANCEL_BUTTON":"Cancel","COM_EASYBLOG_MM_YES_BUTTON":"Yes","COM_EASYBLOG_MM_ITEM_DELETE_CONFIRMATION":"Are you sure you want to delete the item "});
});
