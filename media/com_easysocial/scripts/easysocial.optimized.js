FD31.installer("EasySocial", "definitions", function($){
$.module(["easysocial/admin","easysocial/admin/sidebar/sidebar","easysocial/admin/alerts/discover","easysocial/progress/progress","easysocial/admin/badges/discover","easysocial/admin/grid/grid","easysocial/admin/grid/sort","easysocial/admin/grid/publishing","easysocial/admin/grid/ordering","easysocial/admin/indexer/indexer","easysocial/admin/mailer/mailer","easysocial/admin/migrators/migrator","easysocial/admin/points/discover","easysocial/admin/privacy/discover","easysocial/admin/profiles/avatar","easysocial/uploader/uploader","easysocial/uploader/queue","easysocial/admin/profiles/fields","easysocial/field","easysocial/admin/profiles/members","easysocial/admin/profiles/profiles","easysocial/admin/reports/reporters","easysocial/admin/reports/reports","easysocial/admin/users/form","easysocial/admin/users/privacy","easysocial/admin/users/users","easysocial/admin/widgets/news","easysocial/albums/album","easysocial/albums/browser","easysocial/albums/editor","easysocial/albums/editor/sortable","easysocial/albums/editor/uploader","easysocial/albums/uploader","easysocial/albums/uploader.item","easysocial/albums/item","easysocial/easysocial","easysocial/site/likes/likes","easysocial/site/reports/reports","easysocial/site/repost/repost","easysocial/site/share/share","easysocial/site/layout/dialog","easysocial/site/layout/responsive","easysocial/site/layout/elements","easysocial/site/photos/photos","easysocial/photos","easysocial/photos/viewer","easysocial/site/users/login","easysocial/site/profile/popbox","easysocial/site/conversations/composer","easysocial/site/friends/suggest","easysocial/location","easysocial/site/privacy/privacy","easysocial/site/locations/popbox","easysocial/site/sidebar/sidebar","easysocial/locations","easysocial/oauth/facebook","easysocial/pagination","easysocial/photos/avatar","easysocial/photos/browser","easysocial/photos/cover","easysocial/photos/editor","easysocial/photos/item","easysocial/photos/tags","easysocial/photos/tagger","easysocial/photos/navigation","easysocial/privacy","easysocial/sharing","easysocial/site/activities/activities","easysocial/site/activities/sidebar","easysocial/site/activities/sidebar.item","easysocial/site/activities/apps","easysocial/site/activities/item","easysocial/site/activities/list","easysocial/site/apps/apps","easysocial/site/badges/badge","easysocial/site/comments/control","easysocial/site/comments/frame","easysocial/site/comments/item","easysocial/site/conversations/api","easysocial/site/conversations/conversations","easysocial/site/conversations/mailbox","easysocial/site/conversations/item","easysocial/site/conversations/filter","easysocial/site/conversations/read","easysocial/site/dashboard/apps","easysocial/site/dashboard/dashboard","easysocial/site/dashboard/feeds","easysocial/site/dashboard/sidebar","easysocial/site/followers/api","easysocial/site/followers/followers","easysocial/site/friends/api","easysocial/site/friends/friends","easysocial/site/friends/list","easysocial/site/friends/item","easysocial/site/notifications/list","easysocial/site/points/history","easysocial/site/popbox/popbox","easysocial/site/profile/about","easysocial/site/profile/avatar","easysocial/site/profile/edit","easysocial/validate","easysocial/site/profile/friends","easysocial/site/profile/header","easysocial/site/profile/subscriptions","easysocial/site/profile/miniheader","easysocial/site/profile/notifications","easysocial/site/profile/privacy","easysocial/site/profile/profile","easysocial/site/registrations/registrations","easysocial/site/search/item","easysocial/site/search/list","easysocial/site/search/search","easysocial/site/search/sidebar","easysocial/site/search/toolbar","easysocial/site/stream/item","easysocial/site/stream/stream","easysocial/site/subscriptions/follow","easysocial/site/toolbar/conversations","easysocial/site/toolbar/friends","easysocial/site/toolbar/login","easysocial/site/toolbar/notifications","easysocial/site/toolbar/story","easysocial/site/toolbar/system","easysocial/site/toolbar/profile","easysocial/site/users/users","easysocial/story","easysocial/story/friends","easysocial/story/links","easysocial/story/locations","easysocial/story/photos","easysocial/albums/photo","easysocial/stream","easysocial/comment","easysocial/tab","easysocial/toggle","easysocial/uploader/item","easysocial/utilities/alias"]);
$.require.template.loader(["easysocial/site/loading/small","easysocial/site/uploader/queue.item","easysocial/admin/profiles/form.fields.editorItem","easysocial/admin/profiles/form.fields.stepItem","easysocial/admin/profiles/form.fields.editorPage","easysocial/admin/profiles/form.fields.config","easysocial/site/albums/browser.list.item","easysocial/site/albums/upload.item","easysocial/site/dialog/default","easysocial/site/friends/suggest.item","easysocial/site/location/delete.confirmation","easysocial/apps/user/locations/suggestion","easysocial/site/photos/tags.item","easysocial/site/photos/tags.menu.item","easysocial/admin/profiles/form.privacy.custom.item","easysocial/site/friends/default.empty","easysocial/site/friends/list.assign","easysocial/site/registration/dialog.error","easysocial/apps/user/links/story.attachment.item","easysocial/site/likes/item","easysocial/site/uploader/preview"]);
$.require.language.loader(["COM_EASYSOCIAL_SCAN_COMPLETED","COM_EASYSOCIAL_INDEXER_REINDEX_PROCESSING","COM_EASYSOCIAL_INDEXER_REINDEX_FINISHED","COM_EASYSOCIAL_INDEXER_REINDEX_RESTART","COM_EASYSOCIAL_PROFILES_FORM_FIELDS_REQUIRE_MANDATORY_FIELDS","COM_EASYSOCIAL_PROFILES_FORM_FIELDS_ITEM_CONFIG_LOADING","COM_EASYSOCIAL_PROFILES_FORM_FIELDS_DELETE_PAGE_DIALOG_TITLE","COM_EASYSOCIAL_PROFILES_FORM_FIELDS_DELETE_PAGE_DIALOG_CONFIRMATION","COM_EASYSOCIAL_PROFILES_FORM_FIELDS_DELETE_PAGE_DIALOG_CONFIRM","COM_EASYSOCIAL_PROFILES_FORM_FIELDS_DELETE_PAGE_DIALOG_CANCEL","COM_EASYSOCIAL_PROFILES_FORM_FIELDS_DELETE_PAGE_DIALOG_DELETING","COM_EASYSOCIAL_PROFILES_FORM_FIELDS_DELETE_ITEM_DIALOG_TITLE","COM_EASYSOCIAL_PROFILES_FORM_FIELDS_DELETE_ITEM_DIALOG_CONFIRMATION","COM_EASYSOCIAL_PROFILES_FORM_FIELDS_DELETE_ITEM_DIALOG_CONFIRM","COM_EASYSOCIAL_PROFILES_FORM_FIELDS_DELETE_ITEM_DIALOG_CANCEL","COM_EASYSOCIAL_PROFILES_FORM_FIELDS_DELETE_ITEM_DIALOG_DELETING","COM_EASYSOCIAL_PROFILES_FORM_FIELDS_PARAMS_CORE_UNIQUE_KEY_SAVE_FIRST","COM_EASYSOCIAL_PROFILES_FORM_FIELDS_SAVING","COM_EASYSOCIAL_PROFILES_FORM_FIELDS_SAVED","COM_EASYSOCIAL_PROFILES_FORM_FIELDS_CONFIGURE_PAGE","COM_EASYSOCIAL_PROFILES_FORM_FIELDS_CONFIGURE_FIELD","COM_EASYSOCIAL_PROFILES_FORM_FIELDS_UNSAVED_CHANGES","COM_EASYSOCIAL_PROFILES_FORM_FIELDS_INVALID_VALUES","COM_EASYSOCIAL_CANCEL_BUTTON","COM_EASYSOCIAL_ASSIGN_BUTTON","COM_EASYSOCIAL_PROFILES_ASSIGN_USER_DIALOG_TITLE","COM_EASYSOCIAL_CLOSE_BUTTON","COM_EASYSOCIAL_REPORTS_VIEW_REPORTS_DIALOG_TITLE","COM_EASYSOCIAL_REPORTS_ACTIONS_DIALOG_TITLE","COM_EASYSOCIAL_FRIENDS_REQUEST_SENT","COM_EASYSOCIAL_CONVERSATIONS_ERROR_EMPTY_RECIPIENTS","COM_EASYSOCIAL_CONVERSATIONS_ERROR_EMPTY_MESSAGE","COM_EASYSOCIAL_PRIVACY_TOOLTIPS_SHARED_WITH_PUBLIC","COM_EASYSOCIAL_PRIVACY_TOOLTIPS_SHARED_WITH_MEMBER","COM_EASYSOCIAL_PRIVACY_TOOLTIPS_SHARED_WITH_FRIENDS_OF_FRIEND","COM_EASYSOCIAL_PRIVACY_TOOLTIPS_SHARED_WITH_FRIEND","COM_EASYSOCIAL_PRIVACY_TOOLTIPS_SHARED_WITH_ONLY_ME","COM_EASYSOCIAL_PRIVACY_TOOLTIPS_SHARED_WITH_CUSTOM","COM_EASYSOCIAL_AT_LOCATION","COM_EASYSOCIAL_ACTIVITY_APPS_UNHIDE_SUCCESSFULLY","COM_EASYSOCIAL_COMMENTS_STATUS_SAVE_ERROR","COM_EASYSOCIAL_COMMENTS_STATUS_LOADING","COM_EASYSOCIAL_COMMENTS_STATUS_LOAD_ERROR","COM_EASYSOCIAL_COMMENTS_STATUS_DELETING","COM_EASYSOCIAL_COMMENTS_STATUS_DELETE_ERROR","COM_EASYSOCIAL_LIKES_LIKE","COM_EASYSOCIAL_LIKES_UNLIKE","COM_EASYSOCIAL_COMMENTS_LOADED_OF_TOTAL","COM_EASYSOCIAL_COMMENTS_STATUS_SAVING","COM_EASYSOCIAL_COMMENTS_STATUS_SAVED","COM_EASYSOCIAL_NO_BUTTON","COM_EASYSOCIAL_CONVERSATION_REPLY_POSTED_SUCCESSFULLY","COM_EASYSOCIAL_CONVERSATION_REPLY_FORM_EMPTY","COM_EASYSOCIAL_FRIENDS_REQUEST_SENT_PENDING_APPROVAL","COM_EASYSOCIAL_FRIENDS_REQUEST_DIALOG_TITLE","COM_EASYSOCIAL_FRIENDS_CANCEL_REQUEST_DIALOG_CANCELLED","COM_EASYSOCIAL_FRIENDS_DIALOG_CANCEL_REQUEST","COM_EASYSOCIAL_YES_CANCEL_MY_REQUEST_BUTTON","COM_EASYSOCIAL_REGISTRATION_ERROR_DIALOG_TITLE","COM_EASYSOCIAL_FRIENDS_REQUEST_SENT_NOTICE","COM_EASYSOCIAL_SEARCH_LOAD_MORE_ITEMS","COM_EASYSOCIAL_STREAM_LOAD_PREVIOUS_STREAM_ITEMS","COM_EASYSOCIAL_SUBSCRIPTION_INFO","COM_EASYSOCIAL_FRIENDS_REQUEST_REJECTED","COM_EASYSOCIAL_WITH_FRIENDS","COM_EASYSOCIAL_AND_ONE_OTHER","COM_EASYSOCIAL_AND_MANY_OTHERS","COM_EASYSOCIAL_LOCATION_PERMISSION_ERROR","COM_EASYSOCIAL_STORY_SUBMIT_ERROR","COM_EASYSOCIAL_STORY_CONTENT_EMPTY","COM_EASYSOCIAL_SUBSCRIPTION_DIALOG_UNSUBSCRIBE","COM_EASYSOCIAL_SUBSCRIPTION_DIALOG_SUBSCRIBE","COM_EASYSOCIAL_SUBSCRIPTION_BUTTON_OK","COM_EASYSOCIAL_SUBSCRIPTION_BUTTON_SUBMIT","COM_EASYSOCIAL_SUBSCRIPTION_BUTTON_CANCEL","COM_EASYSOCIAL_SUBSCRIPTION_BUTTON_UNSUBSCRIBE","COM_EASYSOCIAL_SUBSCRIPTION_ARE_YOU_SURE_UNSUBSCRIBE","COM_EASYSOCIAL_SUBSCRIPTION_BUTTON_SUBSCRIBE","COM_EASYSOCIAL_STREAM_DIALOG_FEED","COM_EASYSOCIAL_STREAM_BUTTON_CLOSE"]);
(function(){
var stylesheetNames = ["easysocial/imgareaselect/default"];
var state = ($.stylesheet({"content":""})) ? "resolve" : "reject";
$.each(stylesheetNames, function(i, stylesheet){ $.require.stylesheet.loader(stylesheet)[state](); });
})();
});
FD31.installer("EasySocial", "scripts", function($){
EasySocial.module("admin", function($){

	var module = this;

	EasySocial.require()
		.library(
			"uniform",
			"chosen",
			"flot"
		)
		.script( 
			"admin/sidebar/sidebar"
		)
		.done(function($){

			// Once uniform.js is implemented, we want to apply uniform to the elements.
			$(".uniform, .check :checkbox, .radio :radio, input:file[data-uniform], .usergroups :checkbox").uniform();

			// Apply chosen
			$( '[data-chosen]' ).chosen(
			{
				disable_search 	: true
			});

			$( '[data-chosen-search]' ).chosen(
			{
				disable_search 	: false
			});

			// Ajax checks for pending users.
			$('[data-sidebar]' ).implement( EasySocial.Controller.Sidebar.Sidebar );


			$( '[data-sidebar-menu-toggle]' ).on( 'click' , function()
			{
				var parent 		= $( this ).parent( 'li' ),
					child 		= parent.find( 'ul' ),
					isActive 	= $( this ).parent( 'li' ).hasClass( 'active' );

				if( isActive )
				{
					parent.removeClass( 'active' );
					child.removeClass( 'in' );
				}
				else
				{
					parent.addClass( 'active' );
					child.addClass( 'in' );
				}
			});

			module.resolve();
		});
});

EasySocial.module( 'admin/sidebar/sidebar' , function($) {

	var module = this;

	EasySocial.require()
	.done(function($){

		EasySocial.Controller(
				'Sidebar.Sidebar',
				{
					defaultOptions:
					{
						intervalPendingUsers 	: 5000,

						"{versionNotice}"		: "[data-easysocial-version]",
						"{usersBadge}"			: ".menu-user > a .badge",
						"{pendingUsersBadge}"	: ".menu-user .menu-ies-vcard > .badge",


					}
				},
				function( self )
				{
					return {

						init: function()
						{
							// Perform version checking
							self.versionChecks();

							// Check for pending users.
							self.checkPendingUsers();
						},

						versionChecks: function()
						{
							EasySocial.ajax( 'admin/controllers/easysocial/versionChecks' )
							.done(function( contents , outdated , local , latest )
							{
								if( outdated )
								{
									// Show sidebar menu to be outdated
									$( '[data-es-version-header]' )
										.removeClass( 'latest' )
										.addClass( 'outdated' );

									$( '[data-es-version-header]' )
										.find( '[data-es-outdated]' )
										.data( 'local-version' , local )
										.data( 'online-version' , latest );
								}

								self.versionNotice().html( contents ).show();
							});
						},

						monitorPendingUsers: function()
						{
							// Debug
							if( EasySocial.debug )
							{
								var seconds 	= self.options.intervalVersionChecks / 100;

								console.info( 'Start monitoring pending users with interval of ' + self.options.intervalPendingUsers + ' seconds.' );	
							}

							self.options.state	= setTimeout( self.checkPendingUsers , self.options.intervalPendingUsers );
						},

						checkPendingUsers: function()
						{
							// Stop monitoring so that there wont be double calls at once.
							self.stopMonitorPendingUsers();

							// Needs to run in a loop since we need to keep checking for new notification items.
							setTimeout( function(){

								EasySocial.ajax( 'admin/controllers/users/getTotalPending' , {},
								{
									type : "jsonp"
								})
								.done( function( total )
								{
									if( total > 0 )
									{
										self.usersBadge().html( total );
										self.pendingUsersBadge().html( total );
									}
									else
									{
										self.usersBadge().html( '' );
									}
									// Continue monitoring.
									self.monitorPendingUsers();
								});

							}, self.options.intervalPendingUsers );

						},
						stopMonitorPendingUsers: function()
						{
							// Debug
							if( EasySocial.debug )
							{
								// console.info( 'Stop monitoring conversation notifications.' );	
							}

							clearTimeout( self.options.state );
						},
					}
				}
		);
	
		module.resolve();
	});

});

EasySocial.module( 'admin/alerts/discover' , function($) {

	var module = this;

	EasySocial.require()
	.script( 'progress/progress' )
	.language( 'COM_EASYSOCIAL_SCAN_COMPLETED' )
	.done(function($){

		EasySocial.Controller(
			'Alerts.Discover',
			{
				// A list of selectors we define
				// and expect template makers to follow.
				defaultOptions:
				{
					// Controller Properties.
					files 			: [],

					// Progress bar controller
					progressController : null,

					// Start button
					"{startButton}"	: "[data-alerts-discovery-start]",

					// Progress Bar
					"{progressBar}" : "[data-alerts-discovery-progress]",

					// Logging results
					"{results}"		: "[data-alerts-discovery-result]"
				}
			},
			function(self){

				return {

					init: function()
					{
						// Initialize progress bar.
						self.initProgressBar();
					},

					// Resets the scan.
					reset: function()
					{
						// Reset the logs
						self.results().html('');

						// Reset progress bar.
						self.options.progressController.reset();
					},

					initProgressBar: function()
					{
						// Implement progressbar
						self.progressBar().implement( EasySocial.Controller.Progress );

						// Set this to the options so that we can easily access the controller.
						self.options.progressController	= self.progressBar().controller();
					},

					addLog: function( message )
					{
						$( '<tr>' ).append( $( '<td>' ).html( message ) ).appendTo( self.results() );
					},

					startIterating: function()
					{
						// Get the file from the shelf
						var file 	= self.options.files.shift();

						EasySocial.ajax( 'admin/controllers/alerts/scan' ,
						{
							"file"	: file
						})
						.always(function( data , message , completeMessage ){

							// As long as the files list are not empty yet, we still need to process it.
							if( self.options.files.length > 0 )
							{
								// Update once.
								self.options.progressController.touch( '...' );

								// Append message to the result list.
								self.addLog( message );

								// Run this again.
								self.startIterating();
							}
							else
							{
								// Update once.
								self.options.progressController.touch( '...' );

								// Append message to the result list.
								self.addLog( $.language( 'COM_EASYSOCIAL_SCAN_COMPLETED' ) );

								// Make the scan button work again.
								self.startButton().removeAttr( 'disabled' );
							}
						});
					},

					"{startButton} click" : function( element )
					{
						self.reset();

						// Disable start button.
						self.startButton().attr( 'disabled' , 'disabled' );

						// Discover the list of files.
						EasySocial.ajax( 'admin/controllers/alerts/discoverFiles' , {})
						.done(function( files , message )
						{
							self.reset();

							// Set the files to the properties.
							self.options.files 	= files;

							if( self.options.files.length > 0 )
							{
								// Begin progress.
								self.options.progressController.begin( self.options.files.length );

								// Add logging
								self.addLog( message );

								// Begin to loop through each files.
								self.startIterating();
							}
						});
					}
				}

			}
		);

		module.resolve();
	});

});

EasySocial.module( 'progress/progress' , function($) {

	var module = this;

	EasySocial.Controller(
		'Progress',
		{
			// A list of selectors we define
			// and expect template makers to follow.
			defaultOptions:
			{
				// Controller Properties.
				current 		: 0,
				eachWidth 		: null,
				total	 		: null,

				progressClass	: "progress progress-info progress-striped",

				// Controller Elements
				"{progressBar}"		: ".bar",
				"{progressResult}"	: ".progress-result",

				// View items.
				view			:
				{
				}
			}
		},
		function(self){

			return {

				init: function()
				{
				},

				reset: function()
				{
					self.options.current 	= 0;
					self.eachWidth 			= null;
					self.total 				= null;

					self.progressBar().css( 'width' , '0%' ).html( '' );
				},

				begin: function( total )
				{
					// Set the total number of items
					self.options.total 	= total;

					// Set the width of each item.
					self.options.eachWidth	= 100 / total;


					// Only show progress bar when the there's more than 1 item.
					if( total > 0 )
					{
						self.element
							.addClass( self.options.progressClass )
							.show();
					}
				},

				touch : function( message )
				{
					self.options.current 	+= self.options.eachWidth;

					//ensure the progress bar do not exceed 100%
					if( self.options.current > 100 )
					{
						self.options.current = 100;
					}

					self.progressBar().css( 'width' , self.options.current + '%' );
					self.progressResult().html( Math.round( self.options.current ) + '%' );
				},

				completed: function( message )
				{
					self.options.current 	= 100;

					self.progressBar().css( 'width' , self.options.current + '%' );
					self.progressResult().html( Math.round( self.options.current ) + '%' );
				}
			}

		}
	);

	module.resolve();

});

EasySocial.module( 'admin/badges/discover' , function($) {

	var module = this;

	EasySocial.require()
	.script( 'progress/progress' )
	.language( 'COM_EASYSOCIAL_SCAN_COMPLETED' )
	.done(function($){

		EasySocial.Controller(
			'Badges.Discover',
			{
				// A list of selectors we define
				// and expect template makers to follow.
				defaultOptions:
				{
					// Controller Properties.
					files 			: [],

					// Progress bar controller
					progressController : null,

					// Start button
					"{startButton}"	: "[data-badgesDiscover-start]",

					// Progress Bar
					"{progressBar}" : "[data-badgesDiscover-progress]",

					// Logging results
					"{results}"		: "[data-badgesDiscover-result]",

					// View logs button.
					"{viewLog}"		: "[data-badgesDiscover-viewLog]"
				}
			},
			function(self){

				return {

					init: function()
					{
						// Initialize progress bar.
						self.initProgressBar();

						// Initialize the logging area.
						self.initLogging();
					},

					// Resets the scan.
					reset: function()
					{
						// Reset the logs
						self.results().html('');

						// Hide the viewlog button
						self.initLogging();

						// Reset progress bar.
						self.options.progressController.reset();
					},

					initLogging: function()
					{
						// Ensure view log button is always hidden.
						self.viewLog().hide();
					},

					initProgressBar: function()
					{
						// Implement progressbar
						self.progressBar().implement( EasySocial.Controller.Progress );

						// Set this to the options so that we can easily access the controller.
						self.options.progressController	= self.progressBar().controller();
					},

					addLog: function( message )
					{
						$( '<tr>' ).append( $( '<td>' ).html( message ) ).appendTo( self.results() );
					},

					startIterating: function()
					{
						// Get the file from the shelf
						var file 	= self.options.files.shift();

						EasySocial.ajax( 'admin/controllers/badges/scan' ,
						{
							"file"	: file
						})
						.always(function( data , message )
						{

							// As long as the files list are not empty yet, we still need to process it.
							if( self.options.files.length > 0 )
							{
								// Update once.
								self.options.progressController.touch( '...' );

								// Append message to the result list.
								self.addLog( message );

								// Run this again.
								self.startIterating();
							}
							else
							{
								// Update once.
								self.options.progressController.touch( '...' );

								// Append message to the result list.
								self.addLog( message );

								// Append completed message to the result list since we know this is the last item.
								self.addLog( $.language( 'COM_EASYSOCIAL_SCAN_COMPLETED' ) );

								// Show view log button.
								self.viewLog().show();

								// Make the scan button work again.
								self.startButton().removeAttr( 'disabled' );
							}
						});
					},

					"{startButton} click" : function( element )
					{
						self.reset();

						// Disable start button.
						self.startButton().attr( 'disabled' , 'disabled' );

						// Discover the list of files.
						EasySocial.ajax( 'admin/controllers/badges/discoverFiles' , {})
						.done(function( files , message )
						{
							// Set the files to the properties.
							self.options.files 	= files;

							if( self.options.files.length > 0 )
							{
								// Begin progress.
								self.options.progressController.begin( self.options.files.length );

								// Add logging
								self.addLog( message );

								// Begin to loop through each files.
								self.startIterating();
							}
							else
							{
								// Update once.
								self.options.progressController.begin( 1 );
								self.options.progressController.completed( 'Discover Completed' );

								// Append message to the result list.
								self.addLog( $.language( 'COM_EASYSOCIAL_SCAN_COMPLETED' ) );

								// Make the scan button work again.
								self.startButton().removeAttr( 'disabled' );
							}

						});
					},

					"{viewLog} click" : function()
					{
						self.results().toggle();
					}
				}

			}
		);

		module.resolve();
	});

});

EasySocial.module( 'admin/grid/grid' , function($) {

	var module = this;

	EasySocial.require()
	.script( 'admin/grid/sort' , 'admin/grid/publishing')
	.done(function($)
	{
		EasySocial.Controller(
			'Grid',
			{
				defaultOptions : 
				{
					"{sortColumns}"		: "[data-table-grid-sort]",
					"{ordering}"		: "[data-table-grid-ordering]",
					"{direction}"		: "[data-table-grid-direction]",

					"{task}"			: "[data-table-grid-task]",
					
					"{searchInput}"		: "[data-table-grid-search-input]",
					"{search}"			: "[data-table-grid-search]",
					"{resetSearch}"		: "[data-table-grid-search-reset]",

					"{checkAll}"		: "[data-table-grid-checkall]",
					"{checkboxes}"		: "[data-table-grid-id]",

					"{publishItems}"	: "[data-table-grid-publishing]",

					"{itemRow}"			: "tr",

					"{boxChecked}"		: "[data-table-grid-box-checked]",
					"{filters}"			: "[data-table-grid-filter]"
				}
			},
			function( self )
			{
				return {

					init : function()
					{
						// Implement sortable items.
						self.implementSortable();

						// Implement publish / unpublish
						self.implementPublishing();
					},

					"{filters} change" : function()
					{
						// Always reset the task before submitting.
						self.setTask( '' );

						self.submitForm();
					},

					"{search} click" : function()
					{
						self.submitForm();
					},

					"{resetSearch} click" : function()
					{
						self.searchInput().val( '' );
						self.submitForm();
					},

					submitForm: function()
					{
						self.element.submit();
					},

					setTask: function( task )
					{
						self.task().val( task );
					},

					setOrdering: function( ordering )
					{
						self.ordering().val( ordering );
					},

					setDirection: function( direction )
					{
						self.direction().val( direction );
					},

					setTotalChecked: function( total )
					{
						self.boxChecked().val( total );
					},

					toggleSelectRow: function( row )
					{
						var checkbox 	= row.find( 'input[name=cid\\[\\]]' );

						if( $( checkbox ).prop( 'checked' ) == true )
						{
							$( checkbox ).prop( 'checked' , false );	
						}
						else
						{
							$( checkbox ).prop( 'checked' , true );
						}
						
					},
					selectRow: function( row )
					{
						var checkbox 	= row.find( 'input[name=cid\\[\\]]' );

						$( checkbox ).prop( 'checked' , true );
					},

					implementSortable: function()
					{
						self.sortColumns().implement( EasySocial.Controller.Grid.Sort ,
						{
							"{parent}" 	: self
						});
					},

					implementPublishing: function()
					{
						self.publishItems().implement( EasySocial.Controller.Grid.Publishing,
						{
							"{parent}"	: self
						});
					},

					"{checkAll} change": function( element , event )
					{
						// Find all checkboxes in the grid.
						self.checkboxes().prop( 'checked' , $( element ).is( ':checked' ) );

						// Update the total number of checkboxes checked.
						var total 	= $( element ).is( ':checked' ) ? self.checkboxes().length : 0;


						self.setTotalChecked( total );
					}
				}
			}
		);
			
		module.resolve();
	});


});
EasySocial.module( 'admin/grid/sort' , function($) {

	var module = this;

	EasySocial.Controller(
		'Grid.Sort',
		{
			defaultOptions : 
			{
				items 	: "[data-grid-sort-item]"
			}
		},
		function( self )
		{
			return {

				init : function()
				{
				},

				"{self} click": function()
				{
					var direction 	= self.element.data( 'direction' ),
						column 		= self.element.data( 'sort' );

					// Set the ordering
					self.parent.setOrdering( column );

					// Set the direction
					self.parent.setDirection( direction );

					// Remove any task associated to the form.
					self.parent.setTask( '' );
					
					// Submit the form.
					self.parent.submitForm();
				}
			}
		}
	);
		
	module.resolve();

});
EasySocial.module( 'admin/grid/publishing' , function($) {

	var module = this;

	EasySocial.Controller(
		'Grid.Publishing',
		{
			defaultOptions : 
			{
			}
		},
		function( self )
		{
			return {

				init : function()
				{
				},

				"{self} click": function( el )
				{
					var row 	= self.element.parents( 'tr' ),
						task 	= self.element.data( 'task' );

					self.parent.selectRow( row );

					self.parent.setTask( task );

					self.parent.submitForm();
				}
			}
		}
	);
		
	module.resolve();

});
EasySocial.module( 'admin/grid/ordering' , function($) {

	var module = this;

	EasySocial.Controller(
		'Grid.Ordering',
		{
			defaultOptions : 
			{
				"{moveUp}" 		: "[data-grid-order-up]",
				"{moveDown}"	: "[data-grid-order-down]",
				row 	: null
			}
		},
		function( self )
		{
			return {

				init : function()
				{
					// Get the parent row
					self.options.row 	= self.element.parents( 'tr' );
				},

				selectRow : function()
				{
					var checkbox 	= self.options.row.find('input[name=cid\\[\\]]' );

					// Ensure that the checkbox is checked
					$( checkbox ).prop( 'checked' , true );
				},

				"{moveUp} click" : function()
				{
					self.selectRow();
					$.Joomla( 'submitform' , [ 'moveUp' ] );
				},

				"{moveDown} click" : function()
				{
					self.selectRow();
					$.Joomla( 'submitform' , ['moveDown' ] );
				}
			}
		}
	);
		
	module.resolve();

});
EasySocial.module( 'admin/indexer/indexer' , function($){

	var module	= this;

	EasySocial.require()
	.language(
		'COM_EASYSOCIAL_INDEXER_REINDEX_PROCESSING',
		'COM_EASYSOCIAL_INDEXER_REINDEX_FINISHED',
		'COM_EASYSOCIAL_INDEXER_REINDEX_RESTART'
		)
	.view( 'site/loading/small' )
	.done(function($){

		EasySocial.Controller(
		'Indexer',
		{
			defaultOptions:
			{
				// Elements
				"{startButton}"	: "[data-start-button]",
				"{indexerBar}" : "[data-indexer-bar]",
				"{indexerResult}" : "[data-indexer-result]",
				"{indexerMessage}" : "[data-indexer-message]",

				view :
				{
					loadingContent 	: "site/loading/small"
				}
			}
		},
		function( self ){
			return {

				init : function(){},

				"{startButton} click" : function()
				{
					self.runIndex( 0 );
					self.indexerMessage().html( $.language('COM_EASYSOCIAL_INDEXER_REINDEX_PROCESSING') );
					self.indexerMessage().show();
					self.startButton().hide();
				},

				runIndex : function( max ){

					//ajax call here.
					EasySocial.ajax( 'admin/controllers/indexer/indexing',
					{
						"max" 		: max,

					},
					{
						beforeSend: function()
						{
							// self.startButton().html( self.view.loadingContent() );
						}
					})
					.done(function( max, progress )
					{
						if( max < 0 )
						{
							progress = '100';
						}

						self.updateProgress( progress );

						if( max >= 0)
						{
							self.runIndex( max );
						}

					})
					.fail(function( message ){
						self.setMessage( message );
					})
					.always(function(){

					});


				},

				updateProgress: function( progress )
				{
					self.indexerBar().css( 'width', progress + '%')
					self.indexerResult().html( progress + '%' );

					if( progress == 100 )
					{
						self.indexerMessage().html( $.language( 'COM_EASYSOCIAL_INDEXER_REINDEX_FINISHED' ) );
						self.startButton().html( $.language( 'COM_EASYSOCIAL_INDEXER_REINDEX_RESTART' ) );
						self.startButton().show();
					}
				},



			}
		});

		module.resolve();
	});

});

EasySocial.module( 'admin/mailer/mailer' , function($) {

	var module = this;

	EasySocial.Controller(
		'Mailer',
		{
			defaultOptions :
			{
				"{item}"	: "[data-mailer-item]"
			}
		},
		function( self )
		{
			return {
				init : function()
				{
					self.item().implement( EasySocial.Controller.Mailer.Item );
				}
			}
		});

	EasySocial.Controller(
		'Mailer.Item',
		{
			defaultOptions :
			{
				"{preview}"	: "[data-mailer-item-preview]"
			}
		},
		function( self )
		{
			return {
				init : function()
				{
					self.options.id 	= self.element.data( 'id' );
				},

				"{preview} click" : function( el , event )
				{
					EasySocial.dialog(
					{
						content 	: EasySocial.ajax( 'admin/views/mailer/preview' , { 'id' : self.options.id } )
					})
					console.log( self.options.id );
					// EasySocial.dialog(
					// {
					// 	title 		: $.language( 'COM_EASYSOCIAL_MAILER_DIALOG_PREVIEW_TITLE' ),
					// 	content 	: $.rootPath + 'administrator/index.php?option=com_easysocial&view=mailer&layout=preview&tmpl=component&id=' + self.options.id,
					// 	width 		: 700,
					// 	height 		: 680,
					// 	buttons 	:
					// 	[
					// 		{
					// 			name 	: $.language( 'COM_EASYSOCIAL_CLOSE_BUTTON' ),
					// 			classNames : "btn btn-es",
					// 			click	: function()
					// 			{
					// 				EasySocial.dialog().close();
					// 			}
					// 		}
					// 	]
					// });
				}
			}
		});

	module.resolve();

});
EasySocial.module( 'admin/migrators/migrator' , function($) {

	var module = this;

	EasySocial.require()
	.script( 'progress/progress' )
	.done(function($){

		EasySocial.Controller(
			'Migrators.Migrator',
			{
				// A list of selectors we define
				// and expect template makers to follow.
				defaultOptions:
				{
					// Controller Properties.
					component 			: null,

					processState 		: 0,

					// Progress bar controller
					progressController : null,

					mapping 			: null,


					"{initiateButton}"	: "[data-initiate-migration]",
					"{progressBar}" 	: ".discoverProgress",
					"{results}"			: ".scannedResult",
					"{viewLog}"			: ".viewLog",
					"{customFieldsMap}" : "[data-custom-fields-map]",

					"{resultForm}"		: "[data-migration-result]",

					"{startWidget}"		: "[data-start-widget]",
					"{fieldItem}"		: "[data-field-item]",

					"{startMigrationButton}" : "[data-start-migration]",

					"{rows}"			: "[data-row-item]",
					"{selection}"		: "[data-field-item]",

					"{jomsocialBackButton}" : "[data-jomsocial-back-button]"
				}
			},
			function(self){

				return {

					init: function()
					{
						// Initialize progress bar.
						self.initProgressBar();

						// Initialize the logging area.
						self.initLogging();
					},

					showCustomFields: function()
					{
						// Hide the initial section
						self.startWidget().slideUp();

						//Show the custom fields map.
						self.customFieldsMap().slideDown();
					},

					showResultForm: function()
					{
						self.customFieldsMap().slideUp();

						self.resultForm().slideDown();
					},

					startMigration: function()
					{
						// Disable start button.
						// self.startButton().attr( 'disabled' , 'disabled' );

						self.showResultForm();

						// to prevent user click multiple times.
						if( self.options.processState == 1 )
						{
							return;
						}
						else
						{
							self.options.processState = 1;
						}

						self.reset();


						// Discover the list of files.
						EasySocial.ajax( 'admin/controllers/migrators/check' ,
						{
							'component' : self.options.component
						})
						.done(function( data ){

							if( data.isvalid )
							{
								// Begin progress.
								self.options.progressController.begin( data.count );

								// Add logging
								// self.addLog( 'Found a total of ' + data.count + ' items to migrate.' );

								// Begin to loop through each files.
								self.startIterating('');
							}
							else
							{
								// Ensure results is always hidden.
								self.results().show();

								// Add logging
								self.addLog( 'Error: ' + data.message );

								// Make the scan button work again.
								// self.startButton().removeAttr( 'disabled' );

								// reopen the process state.
								self.options.processState = 0;
							}

						});
					},

					// Resets the scan.
					reset: function()
					{
						// Reset the logs
						self.results().html('');

						// Hide the viewlog button
						self.initLogging();

						// Reset progress bar.
						self.options.progressController.reset();
					},

					initLogging: function()
					{
						// Ensure view log button is always hidden.
						self.viewLog().hide();
					},

					initProgressBar: function()
					{
						// Implement progressbar
						self.progressBar().implement( EasySocial.Controller.Progress );

						// Set this to the options so that we can easily access the controller.
						self.options.progressController	= self.progressBar().controller();
					},

					addLog: function( message )
					{
						$( '<li>' ).html( message )
							.appendTo( self.results() );
					},

					startIterating: function( item )
					{

						if( self.options.mapping == null )
						{
							if( self.selection().length > 0 )
							{
								self.options.mapping = $('#adminForm').serializeArray();
							}
						}

						EasySocial.ajax( 'admin/controllers/migrators/process' ,
						{
							"component"	: self.options.component,
							"item" 		: item,
							"mapping"	: self.options.mapping
						})
						.always(function( data ){

							// As long as the files list are not empty yet, we still need to process it.
							if( data["continue"] )
							{
								// Update once.
								self.options.progressController.touch( 'Discovering...' );

								// Append message to the result list.
								self.addLog( data.message );

								// Run this again.
								self.startIterating( data.item );
							}
							else
							{
								// Update once.
								self.options.progressController.touch( 'Discover Completed' );

								// Append message to the result list.
								self.addLog( data.message );

								// Append completed message to the result list since we know this is the last item.
								self.addLog( 'migration process completed.' );

								// Show view log button.
								self.viewLog().show();

								// Make the scan button work again.
								self.jomsocialBackButton().show();

								// reopen the process state.
								self.options.processState = 0;
							}
						});
					},

					"{fieldItem} change" : function( el )
					{
						var value 	= $( el ).val();

						// Add error class on row
						if( value == '' )
						{
							$( el ).parents( '[data-row-item]' ).removeClass( 'success' ).addClass( 'error' );
						}
						else
						{
							$( el ).parents( '[data-row-item]' ).removeClass( 'error' ).addClass( 'success' );
						}
					},

					"{startMigrationButton} click" : function()
					{
						// If there's error, show dialog and confirm that the user doesn't want to migrate
						// selected fields.
						if( self.selection().length > 0 )
						{
							self.selection().each( function( i, el ) {

								if( $( el ).val() == "" )
								{
									$( el ).parents( '[data-row-item]' ).removeClass( 'success' ).addClass( 'error' );
								}
								else
								{
									$( el ).parents( '[data-row-item]' ).removeClass( 'error' ).addClass( 'success' );
								}
							});
						}

						var hasError = self.rows().hasClass( 'error' );

						if( hasError )
						{
							EasySocial.dialog(
							{
								content 	: EasySocial.ajax( 'admin/views/migrators/confirmMigration' ),
								bindings 	:
								{
									"{submitButton} click" : function()
									{
										self.startMigration();

										EasySocial.dialog().close();
									}
								}
							});
						}
						else
						{
							// do lets this.
							self.startMigration();

						}

					},

					"{initiateButton} click" : function( element )
					{
						self.showCustomFields();
					},

					"{viewLog} click" : function()
					{
						self.results().toggle();
					}
				}

			}
		);

		module.resolve();
	});

});

EasySocial.module( 'admin/points/discover' , function($) {

	var module = this;

	EasySocial.require()
	.script( 'progress/progress' )
	.language( 'COM_EASYSOCIAL_SCAN_COMPLETED' )
	.done(function($){

		EasySocial.Controller(
			'Points.Discover',
			{
				// A list of selectors we define
				// and expect template makers to follow.
				defaultOptions:
				{
					// Controller Properties.
					files 			: [],

					// Progress bar controller
					progressController : null,

					// Start button
					"{startButton}"	: "[data-points-discovery-start]",

					// Progress Bar
					"{progressBar}" : ".discoverProgress",

					// Logging results
					"{results}"		: "[data-points-discovery-result]"
				}
			},
			function(self){

				return {

					init: function()
					{
						// Initialize progress bar.
						self.initProgressBar();
					},

					// Resets the scan.
					reset: function()
					{
						// Reset the logs
						self.results().html('');

						// Reset progress bar.
						self.options.progressController.reset();
					},

					initProgressBar: function()
					{
						// Implement progressbar
						self.progressBar().implement( EasySocial.Controller.Progress );

						// Set this to the options so that we can easily access the controller.
						self.options.progressController	= self.progressBar().controller();
					},

					addLog: function( message )
					{
						$( '<tr>' ).append( $( '<td>' ).html( message ) ).appendTo( self.results() );
					},

					startIterating: function()
					{
						// Get the file from the shelf
						var file 	= self.options.files.shift();

						EasySocial.ajax( 'admin/controllers/points/scan' ,
						{
							"file"	: file
						})
						.always(function( data , message , completeMessage ){

							// As long as the files list are not empty yet, we still need to process it.
							if( self.options.files.length > 0 )
							{
								// Update once.
								self.options.progressController.touch( '...' );

								// Append message to the result list.
								self.addLog( message );

								// Run this again.
								self.startIterating();
							}
							else
							{
								// Update once.
								self.options.progressController.touch( '...' );

								// Append message to the result list.
								self.addLog( $.language( 'COM_EASYSOCIAL_SCAN_COMPLETED' ) );

								// Show view log button.
								self.viewLog().show();

								// Make the scan button work again.
								self.startButton().removeAttr( 'disabled' );
							}
						});
					},

					"{startButton} click" : function( element )
					{
						self.reset();

						// Disable start button.
						self.startButton().attr( 'disabled' , 'disabled' );

						// Discover the list of files.
						EasySocial.ajax( 'admin/controllers/points/discoverFiles' , {})
						.done(function( files , message )
						{
							self.reset();

							// Set the files to the properties.
							self.options.files 	= files;

							if( self.options.files.length > 0 )
							{
								// Begin progress.
								self.options.progressController.begin( self.options.files.length );

								// Add logging
								self.addLog( message );

								// Begin to loop through each files.
								self.startIterating();
							}
							else
							{
								// Update once.
								self.options.progressController.begin( 1 );
								self.options.progressController.completed( 'Discover Completed' );

								// Append message to the result list.
								self.addLog( $.language( 'COM_EASYSOCIAL_SCAN_COMPLETED' ) );

								// Make the scan button work again.
								self.startButton().removeAttr( 'disabled' );
							}


						});
					}
				}

			}
		);

		module.resolve();
	});

});

EasySocial.module( 'admin/privacy/discover' , function($) {

	var module = this;

	EasySocial.require()
	.script( 'progress/progress' )
	.done(function($){

		EasySocial.Controller(
			'Privacy.Discover',
			{
				// A list of selectors we define
				// and expect template makers to follow.
				defaultOptions:
				{
					// Controller Properties.
					files 			: [],

					// Progress bar controller
					progressController : null,

					// Start button
					"{startButton}"	: ".scanRules",

					// Progress Bar
					"{progressBar}" : ".discoverProgress",

					// Logging results
					"{results}"		: ".scannedResult",

					// View logs button.
					"{viewLog}"		: ".viewLog",

					// View items.
					view			:
					{
					}
				}
			},
			function(self){

				return {

					init: function()
					{
						// Initialize progress bar.
						self.initProgressBar();

						// Initialize the logging area.
						self.initLogging();
					},

					// Resets the scan.
					reset: function()
					{
						// Reset the logs
						self.results().html('');

						// Hide the viewlog button
						self.initLogging();

						// Reset progress bar.
						self.options.progressController.reset();
					},

					initLogging: function()
					{
						// Ensure view log button is always hidden.
						self.viewLog().hide();
					},

					initProgressBar: function()
					{
						// Implement progressbar
						self.progressBar().implement( EasySocial.Controller.Progress );

						// Set this to the options so that we can easily access the controller.
						self.options.progressController	= self.progressBar().controller();
					},

					addLog: function( message )
					{
						$( '<li>' ).html( message )
							.appendTo( self.results() );
					},

					startIterating: function()
					{
						// Get the file from the shelf
						var file 	= self.options.files.shift();

						EasySocial.ajax( 'admin/controllers/privacy/scan' ,
						{
							"file"	: file
						})
						.always(function( data ){

							// As long as the files list are not empty yet, we still need to process it.
							if( self.options.files.length > 0 )
							{
								// Update once.
								self.options.progressController.touch( 'Discovering...' );

								// Append message to the result list.
								self.addLog( 'Scanned ' + data.file + ' : ' + data.rules.length + ' rules installed.' );

								// Run this again.
								self.startIterating();
							}
							else
							{
								// Update once.
								self.options.progressController.touch( 'Discover Completed' );

								// Append message to the result list.
								self.addLog( 'Scanned ' + data.file + ' : ' + data.rules.length + ' rules installed.' );

								// Append completed message to the result list since we know this is the last item.
								self.addLog( 'Scanning completed.' );

								// Show view log button.
								self.viewLog().show();

								// Make the scan button work again.
								self.startButton().removeAttr( 'disabled' );
							}
						});
					},

					"{startButton} click" : function( element )
					{
						self.reset();

						// Disable start button.
						self.startButton().attr( 'disabled' , 'disabled' );

						// Discover the list of files.
						EasySocial.ajax( 'admin/controllers/privacy/discoverFiles' , {})
						.done(function( files ){

							// Set the files to the properties.
							self.options.files 	= files;

							if( self.options.files.length > 0 )
							{
								// Begin progress.
								self.options.progressController.begin( self.options.files.length );

								// Ensure results is always hidden.
								self.results().hide();

								// Add logging
								self.addLog( 'Found a total of ' + files.length + ' rules file in the site.' );

								// Begin to loop through each files.
								self.startIterating();
							}
							else
							{
								// Update once.
								self.options.progressController.begin( 1 );
								self.options.progressController.completed( 'Discover Completed' );

								// Append message to the result list.
								self.addLog( $.language( 'COM_EASYSOCIAL_SCAN_COMPLETED' ) );

								// Make the scan button work again.
								self.startButton().removeAttr( 'disabled' );
							}

						});
					},

					"{viewLog} click" : function()
					{
						self.results().toggle();
					}
				}

			}
		);

		module.resolve();
	});

});

EasySocial.module( 'admin/profiles/avatar' , function($){

	var module	= this;

	EasySocial.require()
	.script( 'uploader/uploader' )
	.done( function(){

		EasySocial.Controller(
			'Profiles.Avatar',
			{
				defaultOptions:
				{
					// Properties
					token 				: null,

					// Elements
					"{fileUploader}"		: "[data-profile-avatars-uploader]",
					"{startUploadButton}"	: "[data-profile-avatars-startupload]",
					"{avatarList}"			: "[data-profile-avatars-list]",
					"{avatarEmpty}"			: "[data-profile-avatars-empty]",
					"{avatarItem}"			: "[data-profile-avatars-item]",
					"{messagePlaceholder}"	: "[data-profile-avatars-message]",
					"{removeFile}"			: ".removeFile",
					"{clearUploadedItems}"	: "[data-uploader-clear]"
				}
			},
			function(self)
			{
				return {

					init: function()
					{
						// Get the current profile id
						self.options.id 	= self.element.data( 'id' );

						// Initialize upload controller
						self.initUploader();

						// Initialize avatar controller
						self.initAvatar();
					},

					initUploader: function()
					{
						// Apply uploader controller on the file uploader.
						self.fileUploader().implement( EasySocial.Controller.Uploader,
							{
								url : $.indexUrl + '?option=com_easysocial&namespace=admin/controllers/profiles/uploadDefaultAvatars&' + self.options.token + '=1&tmpl=component&format=ajax&uid=' + self.options.id
							});
					},

					initAvatar: function()
					{
						// Apply controller to avatar items.
						self.avatarItem().implement( 'EasySocial.Controller.Profiles.Avatar.Item',
						{
							"{parent}"	: self,
							items		: self.avatarItem
						});
					},

					addMessage: function( message )
					{
						// Clear previous messages first
						self.clearMessage();

						self.setMessage( message );
					},
					/**
					 * Override the file removal click event.
					 */
					"{removeFile} click" : function( el , event )
					{
						var id 	= $(el).parents( 'li' ).attr( 'id' );

						self.fileUploader().controller().removeItem( id );
					},

					/**
					 * Bind the click event on the start upload button.
					 */
					"{startUploadButton} click" : function()
					{
						var controller	= self.fileUploader().controller();

						controller.startUpload();
					},

					/**
					 * Track the progress of the uploaded item.
					 */
					"{fileUploader} UploadProgress" : function( el , event , file )
					{
						// Get the upload progress.
						var progress	= file.percent,
							elementId	= '#' + file.id,
							progressBar	= $( elementId ).find( '.progressBar' );

						// Show the progress bar.
						progressBar.show();

						// Update the width of the progress bar.
						progressBar.find( '.bar' ).css( 'width' , progress + '%' );
					},

					// Bind the UploadComplete method provided by uploader
					"{fileUploader} FileUploaded" : function( el, event, file, response )
					{
						if( response[ 0 ] != undefined )
						{
							var contents 	= response[0].data[ 0 ];

							// Hide empty if any
							self.avatarEmpty().hide();

							// Prepend the item
							self.avatarList().prepend( contents );

							self.clearUploadedItems().show();

							// Apply the controller
							self.initAvatar();
						}
					},

					"{clearUploadedItems} click" : function()
					{
						var controller 	= self.fileUploader().controller();

						// Reset the queue
						controller.reset();

						// Hide itself since there's no history now.
						self.clearUploadedItems().hide();
					}
				}
			}
		);

		/**
		 * Avatar item controller.
		 */
		EasySocial.Controller(
			'Profiles.Avatar.Item',
			{
				defaultOptions:
				{
					// Properties.
					id 		: null,

					"{deleteLink}"			: "[data-avatar-delete]",
					"{setDefaultAvatar}"	: "[data-avatar-default]"
				}
			},
			function( self )
			{
				return {

					init : function()
					{
						self.options.id 	= self.element.data( 'id' );
					},

					/**
					 * Sets an avatar as the default avatar.
					 */
					"{setDefaultAvatar} click" : function(el , event )
					{
						EasySocial.ajax(
						'admin/controllers/avatars/setDefault',
						{
							"id" : self.options.id
						})
						.done(function( message )
						{
							// Remove all default class
							self.parent.avatarItem().removeClass( 'default' );

							// Add a default class to itself
							self.element.addClass( 'default' );

							self.parent.addMessage( message );
						});
					},

					"{deleteLink} click": function()
					{
						EasySocial.dialog(
						{
							content 	: EasySocial.ajax( 'admin/views/profiles/confirmDeleteAvatar' ),
							bindings	: 
							{
								"{deleteButton} click" : function( el , event )
								{
									$( el ).addClass( 'btn-loading' );
									
									EasySocial.ajax( 'admin/controllers/avatars/delete' , 
									{
										"id" : self.options.id
									})
									.done(function( message )
									{										
										// Remove the element
										self.element.remove();

										if( self.parent.avatarList().children().length == 0 )
										{
											self.parent.avatarEmpty().show();
										}

										self.parent.addMessage( message );

										// Hide the dialog
										EasySocial.dialog().close();										
									});
								}
							}
						})
					}
				}
			});

		module.resolve();

	});

});



EasySocial.module( 'uploader/uploader' , function($){

	var module 	= this;

	EasySocial.require()
	.library( 'plupload' )
	.view( 'site/uploader/queue.item' )
	.script( 'uploader/queue' )
	.done( function(){

		EasySocial.Controller(
			'Uploader',
			{
				defaults:
				{
					url				: $.indexUrl + '?option=com_easysocial&controller=uploader&task=uploadTemporary&format=json&tmpl=component&' + EasySocial.getToken() + '=1',
					uploaded		: [],

					// Allows caller to define their custom query.
					query				: "",

					plupload 			: '',
					dropArea			: 'uploaderDragDrop',
					extensionsAllowed 		: 'jpg,jpeg,png,gif',

					temporaryUpload 	: false,

					// Contains a list of files in the queue so others can manipulate this.
					files 			: [],

					'{uploaderForm}' 	: '[data-uploader-form]',
					'{uploadButton}'	: '[data-uploader-browse]',
					'{uploadArea}'		: '.uploadArea',

					// This contains the file list queue.
					'{queue}'			: '[data-uploaderQueue]',

					// The queue item.
					'{queueItem}'		: '[data-uploaderQueue-item]',

					// When the queue doesn't have any item, this is the container.
					'{emptyFiles}'			: '[data-uploader-empty]',

					// This is the file removal link.
					'{removeFile}'			: '[data-uploaderQueue-remove]',
					'{uploadCounter}'		: '.uploadCounter',

					view :
					{
						queueItem : "site/uploader/queue.item"
					}
				}
			},
			function( self ){ return { 

				init: function(){

					// Implement the uploader queue.
					self.queue().implement( EasySocial.Controller.Uploader.Queue );

					if( self.options.temporaryUpload )
					{
						self.options.url 	= $.indexUrl + '?option=com_easysocial&controller=uploader&task=uploadTemporary&format=json&tmpl=component&' + EasySocial.getToken() + '=1';
					}

					if( self.options.query != '' )
					{
						self.options.url 	= self.options.url + '&' + self.options.query;
					}

					// Initialize the uploader element
					self.uploaderForm().implement(
						'plupload',
						{
							settings:
							{
								url				: self.options.url,
								drop_element	: self.options.dropArea,
								filters			: [{
									title		: 'Allowed File Type',
									extensions	: self.options.extensionsAllowed
								}]
							},
							'{uploader}'		: '[data-uploader-form]',
							'{uploadButton}'	: '[data-uploader-browse]'
						},
						function()
						{
							// Get the plupload options
							self.options.plupload = this.plupload;
						}
					);
				},

				"{uploaderForm} FilesAdded": function(el, event, uploader, files )
				{
					// Add a file to the queue when files are selected.
					self.addFiles( files );

					// Begin the upload immediately if needed
					if( self.options.temporaryUpload )
					{
						self.startUpload();
					}

				},

				"{uploaderForm} UploadProgress" : function( el , event , uploader , file ){

					// Trigger upload progress on the queue item.
					self.queueItem( '#' + file.id ).trigger( 'UploadProgress' , file );

				},

				'{uploaderForm} FileUploaded' : function( el , event, uploader, file , response ){

					// console.log( 'here' );

					// Trigger upload progress on the queue item.
					self.queueItem( '#' + file.id ).trigger( 'FileUploaded' , [file , response] );
				},
				
				"{uploaderForm} UploadComplete" : function( el , event , uploader , files )
				{
					self.options.uploading 	= false;
				},

				/**
				 * Error handling should come here
				 */
				'{uploaderForm} Error': function(el, event, uploader, error)
				{
					// Clear previous message
					self.clearMessage();
					
					var obj = { 'message' : error.message , 'type' : 'error' };

					self.setMessage( obj );
				},

				'{uploaderForm} FileError': function(el, event, uploader, file, response)
				{
					var obj = { 'message' : response.message , 'type' : 'error' };

					self.setMessage(obj);

					self.queueItem( '#' + file.id ).trigger('FileError', [file, response]);

					// queueItem.find('[data-uploaderqueue-progress]')
					// self.removeItem(file.id);
				},

				/**
				 * Adds an item into the upload queue.
				 */
				addFiles: function( files )
				{
					// Go through each of the files.
					$.each( files , function( index , file )
					{
						// Get the file size.
						file.size 		= self.formatSize( file.size );

						// Get the upload queue content.						
						var content 	= self.view.queueItem(
											{ 
												"file"	: file,
												"temporaryUpload" : self.options.temporaryUpload
											});

						// Implement the queue item controller.
						$( content ).implement( EasySocial.Controller.Uploader.Queue.Item ,
						{
							"{uploader}"	: self
						});

						// Add this item into our own queue.
						self.options.files.push( file );

						// Hide the "No files" value
						self.emptyFiles().hide();

						// Append the queue item into the queue
						self.queue().append( content );
					});
				},

				/**
				 * Formats the size in bytes into kilobytes.
				 */
				formatSize: function( bytes )
				{
					// @TODO: Currently this only converts bytes to kilobytes.
					var val = parseInt( bytes / 1024 );

					return val;
				},

				/**
				 * Clears the list of upload items in the queue.
				 */
				reset: function()
				{
					// Remove the item from the list.
					self.queueItem().remove();
				},

				/**
				 * Removes an item from the upload queue.
				 */
				removeItem: function( id )
				{
					var element 	= $( '#' + id );
					
					// When an item is removed, we need to send an ajax call to the server to delete this record
					var uploaderId	= $( element ).find( 'input[name=upload-id\\[\\]]' ).val();

					EasySocial.ajax( 'site/controllers/uploader/delete' , { "id" : uploaderId } )
					.done(function()
					{
						// Remove the item from the attachment list.
						$( '#' + id ).remove();

						// Now remove the item from the plupload queue.
						self.options.plupload.removeFile( self.options.plupload.getFile( id ) );
					});
				},

				/**
				 * Begins the upload process.
				 */
				startUpload: function()
				{
					self.upload();
				},

				upload: function()
				{
					if(self.options.plupload.files.length > 0)
					{
						self.options.uploading 	= true;
						self.options.plupload.start();
					}
				},

				/**
				 * Determines if there's any files in the queue currently.
				 */
				 hasFiles: function(){
				 	return self.options.files.length > 0;
				 }

			} }
		);

		module.resolve();
	});

	
});

EasySocial.module( 'uploader/queue' , function($){

	var module 	= this;

	EasySocial.require()
	.view( 'site/uploader/queue.item' )
	.done( function($){

		EasySocial.Controller(
			'Uploader.Queue',
			{
				defaults:
				{
					"{item}"	: "[data-uploaderQueue-item]"
				}
			},
			function( self ){

				return {

					init: function()
					{
						self.item().implement( EasySocial.Controller.Uploader.Queue.Item );
					}
				}
			}
		);

		EasySocial.Controller( 
			'Uploader.Queue.Item',
			{
				defaultOptions:
				{
					"{delete}"	: "[data-uploaderQueue-remove]",
					"{progress}": "[data-uploaderQueue-progress]",
					"{progressBar}" : "[data-uploaderQueue-progressBar]",
					"{status}"		: "[data-uploaderQueue-status]",
					"{id}"			: "[data-uploaderQueue-id]"
				}
			},
			function( self ){
				return {
					init : function()
					{

						if( self.uploader.options.temporaryUpload )
						{						
							// Store it as template and remove it
							self.idTemplate = self.id().toHTML();
							self.id().remove();
						}
					},

					"{delete} click" : function()
					{
						self.uploader.removeItem( self.element.attr( 'id' ) );
					},

					"{self} FileUploaded" : function( el , event , file , response )
					{
						// var response	= response[0];

						if( self.uploader.options.temporaryUpload )
						{
							// Create a hidden input containing the id
							$.buildHTML(self.idTemplate)
								.val(response.id)
								.appendTo(self.element);
						}

						if( file.status == 5 )
						{
							self.element.addClass( 'is-done' );
							self.status().html( 'Done' );
						}
					},

					"{self} UploadProgress" : function( el , event , progress )
					{
						// Set the progress.
						self.status().html( progress.percent + '%' );

						self.progressBar().css( 'width' , progress.percent + '%');
					},

					"{self} FileError": function()
					{
						self.element.removeClass("is-done is-queue").addClass("is-error");

						self.progress()
							.removeClass("progress-danger progress-success progress-info progress-warning")
							.addClass("progress-danger");

						self.status().html( 'Error' );
					}
				}
			}
		);

		module.resolve();
	});
});

EasySocial.module('admin/profiles/fields', function($) {
	var module = this;

	EasySocial.require()
	.library(
		'ui/draggable',
		'ui/sortable',
		'ui/droppable'
	)
	.script(
		'field'
	)
	.view(
		'admin/profiles/form.fields.editorItem',
		'admin/profiles/form.fields.stepItem',
		'admin/profiles/form.fields.editorPage',
		'admin/profiles/form.fields.config'
	)
	.language(
		'COM_EASYSOCIAL_PROFILES_FORM_FIELDS_REQUIRE_MANDATORY_FIELDS',
		'COM_EASYSOCIAL_PROFILES_FORM_FIELDS_ITEM_CONFIG_LOADING',
		'COM_EASYSOCIAL_PROFILES_FORM_FIELDS_DELETE_PAGE_DIALOG_TITLE',
		'COM_EASYSOCIAL_PROFILES_FORM_FIELDS_DELETE_PAGE_DIALOG_CONFIRMATION',
		'COM_EASYSOCIAL_PROFILES_FORM_FIELDS_DELETE_PAGE_DIALOG_CONFIRM',
		'COM_EASYSOCIAL_PROFILES_FORM_FIELDS_DELETE_PAGE_DIALOG_CANCEL',
		'COM_EASYSOCIAL_PROFILES_FORM_FIELDS_DELETE_PAGE_DIALOG_DELETING',
		'COM_EASYSOCIAL_PROFILES_FORM_FIELDS_DELETE_ITEM_DIALOG_TITLE',
		'COM_EASYSOCIAL_PROFILES_FORM_FIELDS_DELETE_ITEM_DIALOG_CONFIRMATION',
		'COM_EASYSOCIAL_PROFILES_FORM_FIELDS_DELETE_ITEM_DIALOG_CONFIRM',
		'COM_EASYSOCIAL_PROFILES_FORM_FIELDS_DELETE_ITEM_DIALOG_CANCEL',
		'COM_EASYSOCIAL_PROFILES_FORM_FIELDS_DELETE_ITEM_DIALOG_DELETING',
		'COM_EASYSOCIAL_PROFILES_FORM_FIELDS_PARAMS_CORE_UNIQUE_KEY_SAVE_FIRST',
		'COM_EASYSOCIAL_PROFILES_FORM_FIELDS_SAVING',
		'COM_EASYSOCIAL_PROFILES_FORM_FIELDS_SAVED',
		'COM_EASYSOCIAL_PROFILES_FORM_FIELDS_CONFIGURE_PAGE',
		'COM_EASYSOCIAL_PROFILES_FORM_FIELDS_CONFIGURE_FIELD',
		'COM_EASYSOCIAL_PROFILES_FORM_FIELDS_UNSAVED_CHANGES',
		'COM_EASYSOCIAL_PROFILES_FORM_FIELDS_INVALID_VALUES'
	)
	.done(function() {

		// Controller instance
		var $Parent, $Browser, $Editor, $Steps, $Config;

		// Data registry
		var $Apps = {}, $Core = {}, $Check = {}, $Fields = {}, $Pages = {};

		// Delete registry
		var $Deleted = {
			pages: [],
			fields: []
		}

		EasySocial.Controller('Fields', {
			defaultOptions: {
				id: 0,

				'{wrap}'	: '[data-fields-wrap]',

				'{browser}'	: '[data-fields-browser]',
				'{editor}'	: '[data-fields-editor]',
				'{steps}'	: '[data-fields-steps]',
				'{config}'	: '[data-fields-config]',
				'{saveForm}': '[data-fields-save]',

				view: {
					config: 'admin/profiles/form.fields.config'
				}
			}
		}, function(self) {
			return {

				init: function()
				{
					$Parent = self;

					// The id's are bound in data-id
					self.options.id = self.element.data('id');

					// Get the controller for field browser.
					$Browser	= self.addPlugin('browser');

					// Get the controller for field editor.
					$Editor		= self.addPlugin('editor');

					// Get the controller for steps.
					$Steps		= self.addPlugin('steps');

					var controllers = [$Browser.state, $Editor.state, $Steps.state];

					// Only trigger when all of the states is resolved
					$.when.apply(null, controllers).done(function() {
						$Parent.trigger('controllersReady');
					});
				},

				changed: false,

				change: function() {
					self.changed = true;
				},

				'{window} beforeunload': function(el, ev) {
					if(self.changed) {
						return $.language('COM_EASYSOCIAL_PROFILES_FORM_FIELDS_UNSAVED_CHANGES');
					}
				},

				/**
				 * When save form is called, call each page's export function to get the data
				 */
				'{saveForm} click': function()
				{
					self.save();
				},

				/**
				 * Send the data to the controller to process the fields.
				 */
				save: function()
				{
					var dfd = $.Deferred();

					// If no changes, then skip this saving
					if( !self.changed )
					{
						dfd.resolve();

						return dfd;
					}

					EasySocial.dialog({
						// showOverlay: true,
						content: $.language('COM_EASYSOCIAL_PROFILES_FORM_FIELDS_SAVING'),
						width: 400,
						height: 150
					});

					// Trigger saving event
					$Parent.trigger('saving');

					// If config is open, we run a internal populate first on the config
					if($Config && $Config.state) {
						if(!$Config.checkConfig()) {

							dfd.reject($.language('COM_EASYSOCIAL_PROFILES_FORM_FIELDS_INVALID_VALUES'));

							return dfd;
						}
					}

					// Clone a non-referenced $Core object into $Check
					$Check = $.extend(true, {}, $Core);

					var data = [];

					// Loop through each step
					$.each($Steps.step(), function(i, step) {
						step = $(step);

						// Get the step's page controller
						var page = $Editor.getPage(step.data('id'));

						// Call the page's export function to get the data of the page
						data.push(page._export());
					});

					// Check if all core apps has been used
					if($._.keys($Check).length > 0) {
						// Trigger saved event and pass in false to indicate error
						$Parent.trigger('saved', [false]);

						dfd.reject($.language('COM_EASYSOCIAL_PROFILES_FORM_FIELDS_REQUIRE_MANDATORY_FIELDS'));

						return dfd;
					}

					$Parent.changed = false;

					data = JSON.stringify(data);

					EasySocial.ajax('admin/controllers/profiles/saveFields', {
						id: self.options.id,
						data: data,
						deleted: $Deleted
					}).done(function(result) {
						// Trigger saved event
						$Parent.trigger('saved');

						// Update the dialog then close it
						EasySocial.dialog({
							content: $.language('COM_EASYSOCIAL_PROFILES_FORM_FIELDS_SAVED'),
							width: 400,
							height: 150
						});

						setTimeout(EasySocial.dialog().close, 2000)

						// Update each step/fields based on the returned result that contained assigned id
						// self.updateResult(result);

						dfd.resolve();
					});

					return dfd;
				},

				/**
				 * Update the form based on the returned data
				 */
				updateResult: function(data)
				{
					// It has the same format as the data
					$.each(data, function(i, dataStep) {
						// Get the step based on index (sequence)
						var step = $Steps.step().eq(i);

						// Assign step id first
						var stepid = step.data('id');

						// Get the page
						var page = $Editor.getPage(stepid);

						// Update the step id
						$Steps.updateResult(i, dataStep.id);

						// Update the page id
						page.updateResult(stepid, dataStep);
					});
				},

				'{self} doneConfiguring': function() {
					self.element.removeClass('editting');
				},

				loadConfiguration: function(item, type) {
					self.element.addClass('editting');

					// var config = self.config().clone();
					var config = $(self.view.config());

					$Config = config.addController('EasySocial.Controller.Fields.Config', {
						controller: {
							item: item
						}
					});

					if(type === 'page')
					{
						item.pageHeader().append(config);
					}
					else
					{
						item.element.append(config);
					}

					// $('body').append(config);

					self.element.trigger('loadingConfig', [type]);
				}
			}
		});

		/* Browser Controller */
		EasySocial.Controller('Fields.Browser', {
			defaultOptions: {
				'{browser}'		: '[data-fields-browser]',

				'{mandatory}'	: '[data-fields-browser-group-mandatory]',
				'{standard}'	: '[data-fields-browser-group-standard]',

				'{list}'		: '[data-fields-browser-list]',
				'{item}'		: '[data-fields-browser-item]',

				'affixClass'	: 'es-browser-affix'
			}
		}, function(self) {
			return {
				state: $.Deferred(),

				init: function() {
					// Things to do before resolving self
					self.registerApps();

					self.ready();

					self.affixHandler();

					self.initAffix();
				},

				ready: function() {
					self.state.resolve();
				},

				'{parent} controllersReady': function() {
					var id = $Steps.getCurrentStep().data('id');

					self.initDraggable(id);
				},

				'{parent} pageChanged': function(el, ev, page, uid) {
					self.item().draggable('destroy');

					self.initDraggable(uid);
				},

				'{parent} pageAdded': function(el, ev, page, uid) {
					self.item().draggable('destroy');

					self.initDraggable(uid);
				},

				initDraggable: function(id) {
					self.item().draggable({
						revert: 'invalid',
						helper: 'clone',
						connectToSortable: '[data-fields-editor-page-items-' + id + ']'
					});
				},

				affixHandler: function() {
					var parent = $(window),
						wrap = self.parent.wrap(),
						height = wrap.offset().top,
						scroll = parent.scrollTop();

					if(scroll > height && !self.browser().hasClass(self.options.affixClass)) {
						self.browser().addClass(self.options.affixClass);
					}

					if(scroll <= height && self.browser().hasClass(self.options.affixClass)) {
						self.browser().removeClass(self.options.affixClass);
					}
				},

				initAffix: function() {
					$(window).scroll(self.affixHandler);
				},

				registerApps: function() {
					// Register all available apps into an object
					$.each(self.item(), function(index, item) {
						item = $(item);

						var id = item.data('id');

						$Apps[id] = {
							id: id,
							element: item.data('element'),
							title: item.data('title'),
							params: item.data('params'),
							core: item.data('core'),
							unique: item.data('unique'),
							item: item
						};

						// Keep a list of core apps id in $Core
						if(item.data('core')) {
							$Core[id] = $Apps[id];
						}
					});
				},

				/**
				 * Used to check if core apps has been used in saving. Core apps have to be completely used to saved.
				 */
				checkout: function(id) {
					if($Check[id] !== undefined) {
						delete $Check[id];
					}
				},

				/**
				 * This is the event handler for the field items selection.
				 */
				'{item} click': function(el) {
					// Get the current page.
					var currentPage = $Editor.currentPage();

					// Get the app id of the item clicked
					var appid = el.data('id');

					// Add new item to the page
					currentPage.addNewField(appid);
				},

				/**
				 * Carry out any necessary actions when app is added as a field
				 */
				'{parent} fieldAdded': function(el, event, appid) {
					var app = $Apps[appid];

					if(app && app.core) {
						app.item.hide();

						// If core app is added, check if there are any remaining core app left to hide the core group
						var items = self.mandatory().find(self.item.selector).filter(':visible');

						self.mandatory().toggle((items.length > 0));
					}

					if(app && app.unique) {
						app.item.hide();
					}
				},

				/**
				 * Carry out any necessary actions when field is removed
				 */
				'{parent} fieldDeleted': function(el, event, appid, fieldid) {
					var app = $Apps[appid];

					if(app && app.core) {
						app.item.show();

						// If core app is deleted, then the browser group for core fields have to definitely show
						self.mandatory().show();

						return;
					}

					if(app && app.unique) {
						app.item.show();

						return;
					}
				}
			}
		});

		/* Config Controller */
		EasySocial.Controller('Fields.Config', {
			defaultOptions: {
				'{config}'		: '[data-fields-config]',

				'{header}'		: '[data-fields-config-header]',

				'{close}'		: '[data-fields-config-close]',

				'{form}'		: '[data-fields-config-form]',

				'{param}'		: '[data-fields-config-param]',

				'{tabnav}'		: '[data-fields-config-tab-nav]',
				'{tabcontent}'	: '[data-fields-config-tab-content]',

				'{done}'		: '[data-fields-config-done]'
			}
		}, function(self) {
			return {
				init: function() {
				},

				state: false,

				load: function(config) {
					// Set state to true to indicate editting mode
					self.state = true;

					// Apply multi choices
					config.find('[data-fields-config-param-choices]').addController('EasySocial.Controller.Config.Choices', {
						controller: {
							item: self.item
						}
					});

					// Hide the field title
					config.find( 'h4' ).hide();

					// Update the header
					self.header().html( config.find( 'h4' ).html() );

					// Inject the html into the form
					self.form().html(config);

					// Carry out necessary actions after config has been loaded if this is a new field
					if(self.item.options.newfield) {

						// Disable the unique key field if it is a new field
						self.param('[data-fields-config-param-field-unique_key]')
							.attr('disabled', true)
							.val($.language('COM_EASYSOCIAL_PROFILES_FORM_FIELDS_PARAMS_CORE_UNIQUE_KEY_SAVE_FIRST'));
					}

					// Load the first tab as active
					if(self.tabnav().length > 0) {
						self.tabnav().find('a[data-tabname="core"]').tab('show');
					}

					self.populateConfig();

					// Get the config height for css fix
					var configHeight = self.element.height();

					$Parent.wrap().css('padding-bottom', configHeight + 'px');

					$Parent.trigger('configLoaded');
				},

				'{close} click': function(el, ev) {
					self.closeConfig();
				},

				'{done} click': function(el, ev) {
					self.closeConfig();
				},

				closeConfig: function() {
					var values = self.populateConfig();

					// Check through the values
					var state = self.checkConfig(values);

					if(state) {
						self.item.updateHtml(self.form().html());

						self.item.content().trigger('onConfigSave', [values]);

						self.element.remove();

						$Config = null;

						$Parent.trigger('doneConfiguring');
					} else {
						EasySocial.dialog({
							content: $.language('COM_EASYSOCIAL_PROFILES_FORM_FIELDS_INVALID_VALUES'),
							width: 400,
							height: 100
						});
					}
				},

				'{parent} loadingConfig': function(el, ev, header) {

					// Set the config header
					if(header !== undefined && header != 'field' )
					{
						var headerText = $.language('COM_EASYSOCIAL_PROFILES_FORM_FIELDS_CONFIGURE_' + header.toUpperCase());
						self.header().html(headerText);
					}

					// Show the config panel
					self.config().show();

					// Hide the close button first
					self.close().hide();

					// Set the loading state
					self.form().html($.language('COM_EASYSOCIAL_PROFILES_FORM_FIELDS_ITEM_CONFIG_LOADING'));
				},

				'{parent} configLoaded': function(el, ev) {
					self.close().show();
				},

				'{param} change': function(el) {
					self.paramChanged(el);
				},

				'{param} keyup': function(el) {
					self.paramChanged(el);
				},

				paramChanged: function(el) {
					var name = el.data('name'),
						value = self.getConfigValue(name);

					var field = self.item.appParams[name];

					// Manually convert boolean field into boolean value for toggle to work properly
					if(field.type === 'boolean') {
						value = !!value;
					}

					self.item.content().trigger('onConfigChange', [name, value]);

					$Parent.change();
				},

				getConfigValue: function(name) {
					var field = self.item.appParams[name],
						// element = self.param('[data-fields-config-param-field-' + name +']');
						element = self.param().filterBy('name', name);

					if(element.length === 0) {
						return undefined;
					}

					var values = '';

					switch(field.type) {
						case 'choices':
							values = [];

							$.each(element.find('li'), function(i, choice) {
								choice = $(choice);

								var titleField = choice.find('[data-fields-config-param-choice-title]'),
									valueField = choice.find('[data-fields-config-param-choice-value]'),
									defaultField = choice.find('[data-fields-config-param-choice-default]');

								values.push({
									'id': choice.data('id'),
									'title': titleField.val(),
									'value': valueField.val(),
									'default': defaultField.val()
								});

								titleField.attr('value', titleField.val());
								valueField.attr('value', valueField.val());
								defaultField.attr('value', defaultField.val());
							});
						break;

						case 'boolean':
							var tmp = element.val();

							values = (tmp === 'true' || tmp === '1' || tmp === 1) ? 1 : 0;

							element.attr('value', values);
						break;

						case 'checkbox':
							values = [];
							$.each(field.option, function(k, option) {
								var checkbox = element.filter('[data-fields-config-param-option-' + option.name + ']');

								if(checkbox.length > 0 && checkbox.is(':checked')) {
									values.push(option.name);

									checkbox.attr('checked', 'checked');
								} else {
									checkbox.removeAttr('checked');
								}
							});
						break;

						case 'list':
						case 'select':
						case 'dropdown':
							values = element.length > 0 ? element.val() : field["default"] || '';

							element.find('option').prop('selected', false);

							element.find('option[value="' + values + '"]').prop('selected', true);
						break;

						case 'input':
					case 'text':
						default:
							values = element.length > 0 ? element.val() : field["default"] || '';

							element.attr('value', values);
						break;
					}

					return values;
				},

				populateConfig: function() {
					var data = {};

					$.each(self.item.appParams, function(name, field) {
						var value = self.getConfigValue(name);

						if(value === undefined) {
							// If getConfigValue returns undefined, means this field is not found, then skip to the next field
							return false;
						}

						data[name] = value;
					});

					self.item.trigger('onPopulateConfig', [data]);

					return data;
				},

				checkConfig: function(values) {
					if(values === undefined) {
						values = self.populateConfig();
					}

					// Perform custom checks here
					var state = true;

					$.each(values, function(name, value) {
						var field = self.item.appParams[name];

						switch(field.type) {
							// custom check for choices
							case 'choices':
								// Get all the values first
								var choiceValues = [];

								$.each(value, function(i, choice) {
									if($.isEmpty(choice.value) && !$.isEmpty(choice.title)) {
										choice.value = choice.title.toLowerCase().replace(' ', '');
									}

									if(!$.isEmpty(choice.value) && $.inArray(choice.value, choiceValues) > -1) {
										state = false;
										return false;
									}

									choiceValues.push(choice.value);

									// if((!$.isEmpty(choice.title) && $.isEmpty(choice.value)) || ($.isEmpty(choice.title) && !$.isEmpty(choice.value))) {
									// 	state = false;
									// 	return false;
									// }
								});
							break;
						}

						if(state === false) {
							return false;
						}
					});

					return state;
				},

				'{parent} fieldDeleted': function() {
					if(self.state) {
						$Parent.trigger('doneConfiguring');
					}
				},

				'{parent} pageDeleted': function() {
					if(self.state) {
						$Parent.trigger('doneConfiguring');
					}
				},

				'{parent} pageAdded': function() {
					if(self.state) {
						$Parent.trigger('doneConfiguring');
					}
				},

				'{parent} pageChanged': function() {
					if(self.state) {
						$Parent.trigger('doneConfiguring');
					}
				}
			}
		});

		/* Steps Controller */
		EasySocial.Controller('Fields.Steps', {
			defaultOptions: {
				'{steps}'	: '[data-fields-step]',

				// The step item.
				'{step}'	: '[data-fields-step-item]',

				// The link of each step.
				'{stepLink}': '[data-fields-step-item-link]',

				// The add step button
				'{add}'		: '[data-fields-step-add]',

				view: {
					stepItem: 'admin/profiles/form.fields.stepItem'
				}
			}
		}, function(self) {

			return {
				state: $.Deferred(),

				init: function() {
					self.ready();
				},

				ready: function() {
					self.state.resolve();
				},

				// Delayed init
				'{parent} controllersReady': function() {
					self.initSort();
				},

				initSort: function() {
					self.steps().sortable({
						items: self.step.selector,
						placeholder: 'ui-state-highlight',
						cursor: 'move',
						helper: 'clone',
						forceHelperSize: true,
						stop: function() {
							// Manually remove all the freezing tooltip due to conflict between bootstrap tooltip and jquery sortable
							$('.tooltip-es').remove();
						}
					});
				},

				'{parent} pageDeleted': function(el, event, uid) {
					self.deleteStep(uid);

					// Load the first step as the active page
					if($Steps.step().length > 0) {
						$Steps.stepLink(':first').tab('show');
					}
				},

				'{step} click': function(el, ev) {
					if(!el.hasClass('active')) {
						var id = el.data('id');
						$Parent.trigger('pageChanged', [$Editor.getPage(id), id]);
					}
				},

				/**
				 * Creates a new step.
				 */
				'{add} click': function() {
					// Generate an unique id to link between step and page
					var stepuid = $.uid('step');

					// Add a new step progress at the progress list.
					self.addStep(stepuid);

					// Add a new page form.
					$Editor.addPage(stepuid);

					// Go to the last page automatically since the last page would be the item that is created.
					self.stepLink(':last').tab('show');
				},

				addStep: function(uid) {
					// Always add new step before before the add button
					self.add().before(self.view.stepItem({
						uid: uid
					}));

				},

				getStep: function(uid) {
					return self.step().filterBy('id', uid);
				},

				getStepLink: function(uid) {
					return self.stepLink().filterBy('id', uid);
				},

				deleteStep: function(uid) {
					self.getStep(uid).remove();
				},

				getCurrentStep: function() {
					return self.step('.active');
				},

				currentStepIndex: function() {
					return self.step().index(self.step('.active')) + 1;
				},

				updateResult: function(sequence, newid) {
					var step = self.step(':eq(' + sequence + ')');

					if(step.data('id') != newid) {
						var oldid = step.data('id');

						step.removeAttr('data-fields-step-item-' + oldid);

						step.attr('data-fields-step-item-' + newid, true);

						step.data('id', newid);

						step.attr('data-id', newid);

						var stepLink = self.stepLink().eq(sequence);

						stepLink.removeAttr('data-fields-step-item-link-' + oldid);

						stepLink.attr('data-fields-step-item-link-' + newid, true);

						stepLink.attr('href', '#formStep_' + newid);
					}
				},

				toObject: function() {

				}
			}
		});

		/* Editor Controller */
		EasySocial.Controller('Fields.Editor', {
			defaultOptions: {
				'{editor}'	: '[data-fields-editor]',

				'{page}'	: '[data-fields-editor-page]',

				'{items}'	: '[data-fields-editor-page-items]',
				'{item}'	: '[data-fields-editor-page-item]',

				view: {
					editorPage: 'admin/profiles/form.fields.editorPage'
				}
			}
		}, function(self) {
			return {
				state: $.Deferred(),

				init: function() {
					self.ready();
				},

				ready: function() {
					self.state.resolve();
				},

				'{parent} controllersReady': function() {
					// Implements page controller to all pages
					self.page().addController('EasySocial.Controller.Fields.Editor.Page');
				},

				/**
				 * Returns the current page's controller
				 */
				currentPage: function() {
					return self.page('.active').controller();
				},

				/**
				 * Creates a new page container.
				 */
				addPage: function(uid) {
					// Create a new page item
					var newPage = self.view.editorPage({
						uid: uid
					});

					// Initialize the page controller
					newPage.addController('EasySocial.Controller.Fields.Editor.Page', {
						uid: uid,
						newpage: true,
					});

					// Append the new page
					// self.pages().append(newPage.element);
					self.editor().append(newPage);

					// Trigger pageAdded event on all the pages
					self.page().trigger('pageAdded', [newPage, uid]);

					$Parent.change();
				},

				/**
				 * Returns a page controller container based on uid
				 */
				getPage: function(uid) {
					return self.page().filterBy('id', uid).controller();
				},

				/**
				 * Carry out the necessary action when form is saving
				 */
				'{parent} saving': function(el, event) {
					self.element.addClass('saving');
				},

				/**
				 * Carry out the necessary action when form is saved
				 */
				'{parent} saved': function(el, event, state) {
					// If state is false, this means error during saving
					if(state === false) {
						// TODO: Dialog box needed
					}

					self.element.removeClass('saving');
				}
			}
		});

		/* Editor Page Controller */
		EasySocial.Controller('Fields.Editor.Page', {
			defaultOptions: {
				// This is the stepid stored in the db
				pageid						: 0,

				// This is the unique id generated if the page is a new page
				uid							: 0,

				newpage						: false,

				'{items}'					: '[data-fields-editor-page-items]',
				'{item}'					: '[data-fields-editor-page-item]',

				'{pageHeader}'				: '[data-fields-editor-page-header]',

				// $Config compatibility
				'{content}'					: '[data-fields-editor-page-header]',

				'{pageTitle}'				: '[data-fields-editor-page-title]',
				'{pageDescription}'			: '[data-fields-editor-page-description]',

				'{inputTitle}'				: '[data-fields-editor-page-title-input]',
				'{inputDescription}'		: '[data-fields-editor-page-description-input]',

				'{pageVisibleRegistration}'	: '[data-fields-editor-page-visible-registration]',
				'{pageVisibleEdit}'			: '[data-fields-editor-page-visible-edit]',
				'{pageVisibleView}'			: '[data-fields-editor-page-visible-view]',
				'{pageDelete}'				: '[data-fields-editor-page-delete]',
				'{pageEdit}'				: '[data-fields-editor-page-edit]',
				'{pageInfo}'				: '[data-fields-editor-page-info]',
				'{pageInfoDone}'			: '[data-fields-editor-page-done]',

				view: {
					editorItem: 'admin/profiles/form.fields.editorItem'
				}
			}
		}, function(self) {

			return {
				init: function() {

					// Assign uid as pageid if this is not a new page
					if(!self.options.newpage)
					{
						self.options.uid = self.options.pageid = self.element.data('id');
					}

					// Register self into Pages registry
					self.registerPage();

					self.item().addController('EasySocial.Controller.Fields.Editor.Item', {
						pageid: self.options.uid
					});

					// Check for delete button state
					self.checkPageDeleteButton();

					// Init the sorting
					self.initSort();
				},

				// Keep a registry of current page's fields
				fields: {},

				getStep: function() {
					return $Steps.getStep(self.options.uid);
				},

				registerPage: function() {
					$Pages[self.options.uid] = self;
				},

				initSort: function() {
					self.items().sortable({
						items: self.item.selector,
						handle: '[data-fields-editor-page-item-handle]',
						placeholder: 'ui-state-highlight',
						cursor: 'move',
						helper: 'clone',
						forceHelperSize: true,
						stop: function(event, ui) {
							if(ui.item.is($Browser.item.selector)) {
								var appid = ui.item.data('id');

								// Create a placeholder first
								var placeholder = self.createPlaceholder();
								ui.item.replaceWith(placeholder);

								// Create new field and let it replace the placeholder
								self.createNewField(appid, placeholder);
							}
						}
					});
				},

				addNewField: function(appid) {
					// Append a placeholder first
					var placeholder = self.createPlaceholder();
					self.items().append(placeholder);

					$.scrollTo(placeholder, 200);

					// Create new field and let new field replace the placeholder
					self.createNewField(appid, placeholder);

					$Parent.change();
				},

				createPlaceholder: function() {
					// Generate a uid first
					var uid = $.uid('newfield');

					// Generate a placeholder
					var placeholder = self.view.editorItem({
						uid: uid
					});

					return placeholder;
				},

				createNewField: function(appid, placeholder) {
					// Trigger fieldAdded event
					$Parent.trigger('fieldAdded', [appid]);

					// get the html asyncly
					self.getFieldHtml(appid)
						.done(function(html) {
							// Third parameter set to true to preserve script tags
							html = $.parseHTML(html, document, true);

							// Wrap the whole parsed html as jquery object
							html = $(html);

							// Replace the original loading placeholder with the html object
							placeholder.replaceWith(html);

							// Retrieve the main div to implement item controller
							var div = html.filter('[data-appid="' + appid + '"]');

							// Implement the item controller
							div.addController('EasySocial.Controller.Fields.Editor.Item', {
								controller: {
									page: self
								},

								appid: appid,
								pageid: self.options.uid,
								newfield: true,
							});
						}).fail(function(msg) {
							placeholder.html(msg);
						});
				},

				getFieldHtml: function(appid) {
					var state = $.Deferred();

					if($Apps[appid].html === undefined) {
						EasySocial.ajax('admin/controllers/fields/renderSample', {
							appid: appid,
							profileid: $Parent.options.id
						}).done(function(html) {
							$Apps[appid].html = html;

							state.resolve(html);
						}).fail(function(msg) {
							state.reject(msg);
						});
					} else {
						state.resolve($Apps[appid].html);
					}

					return state;
				},

				'{pageHeader} click': function(el, event) {
					var clickedTarget = $(event.target);

					if(clickedTarget.not('[data-fields-editor-page-delete]') && !el.hasClass('editting')) {

						if($Config && $Config.state) {

							var state = $Config.checkConfig();

							// Remove itself from other field
							if(state) {
								$Config.closeConfig();
							} else {
								EasySocial.dialog({
									content: $.language('COM_EASYSOCIAL_PROFILES_FORM_FIELDS_INVALID_VALUES'),
									width: 400,
									height: 100
								});

								return;
							}
						}

						self.loadConfiguration();
					}
				},

				loadConfiguration: function() {
					$Parent.loadConfiguration(self, 'page');

					self.pageHeader().addClass('editting');

					self.getPageConfig()
						.done(function() {
							var pageConfig = $(self.html);

							$Config.load(pageConfig);
						})
						.fail(function(msg) {
							$Config.load(msg);
						});
				},

				updateHtml: function(html) {
					self.html = html;
				},

				getPageConfig: function() {
					var state = $.Deferred();

					if(!$.isEmptyObject(self.params)) {
						state.resolve();
					} else {
						EasySocial.ajax('admin/controllers/profiles/getPageConfig', {
							pageid: self.options.pageid
						})
						.done(function(params, values, html) {
							self.params = params;
							self.values = values;
							self.html = html;

							// Compatibility with $Config
							self.appParams = params;

							state.resolve();
						})
						.fail(function(msg) {
							state.reject(msg);
						});
					}

					return state;
				},

				getConfigValues: function() {
					return self.values;
				},

				'{content} onConfigChange': function(el, ev, name, value) {

					self.values[name] = value;

					var step = $Steps.getStepLink(self.options.uid);


					if(name === 'title') {

						step.text(value);

						self.pageTitle().html(value);
					}

					if(name === 'description') {
						// Used attr('data-original-title') instead of data('original-title') because the tooltip reads the attribute directly while data() adds the value back as a jQuery data on to the element
						step.attr('data-original-title', value);

						self.pageDescription().html(value);
					}

					$Parent.change();
				},

				'{pageDelete} click': function(el) {
					if(el.enabled()) {
						el.disabled(true);

						// If it is the last page, then it shouldn't delete.
						if($Editor.page().length == 1) {
							el.enabled(true);

							// @TODO: error box needed

							return false;
						}

						EasySocial.dialog({
							width: 400,
							height: 150,
							title: $.language('COM_EASYSOCIAL_PROFILES_FORM_FIELDS_DELETE_PAGE_DIALOG_TITLE'),
							content: $.language('COM_EASYSOCIAL_PROFILES_FORM_FIELDS_DELETE_PAGE_DIALOG_CONFIRMATION'),
							showOverlay: false,
							buttons: [
								{
									// CANCEL button
									name: $.language('COM_EASYSOCIAL_PROFILES_FORM_FIELDS_DELETE_PAGE_DIALOG_CANCEL'),
									classNames: 'btn btn-es',
									click: function() {
										EasySocial.dialog().close();
									}
								},
								{
									// DELETE button
									name: $.language('COM_EASYSOCIAL_PROFILES_FORM_FIELDS_DELETE_PAGE_DIALOG_CONFIRM'),
									classNames: 'btn btn-es-danger',
									click: function() {
										// Update the dialog content first
										EasySocial.dialog().update({
											content: $.language('COM_EASYSOCIAL_PROFILES_FORM_FIELDS_DELETE_PAGE_DIALOG_DELETING')
										});

										// Start deleting the page
										self.deletePage();

										// Close the dialog
										EasySocial.dialog().close();
									}
								}
							]
						});
					}
				},

				deletePage: function() {
					// Trigger pageDeleted event
					self.item().trigger('pageDeleted');
					$Parent.trigger('pageDeleted', [self.options.uid]);

					// Remove self from $Pages registry
					delete $Pages[self.options.uid];

					// Add self into $DeletedPages registry
					if(!self.options.newpage) {
						$Deleted.pages.push(self.options.uid);
					}

					// Removed current page
					self.element.remove();

					// Check for delete button
					$.each($Editor.page(), function(i, page) {
						$(page).controller().checkPageDeleteButton();
					});

					$Parent.change();
				},

				_export: function() {
					var fields = [];

					$.each(self.item(), function(j, item) {
						item = $(item).controller();

						if(item !== undefined)
						{
							fields.push(item._export());
						}
					});

					var data = {
						fields: fields,
						newpage: self.options.newpage,
						id: self.options.uid
					}

					if(self.values !== undefined) {
						var data = $.extend(data, self.values);
					}

					return data;
				},

				updateResult: function(oldid, data) {
					if(self.options.newpage) {

						// Update the page element id attribute (to correspond with the step tab structure)
						self.element.attr('id', 'formStep_' + data.id);

						// Remove the old selector and add in the new selector
						self.element.removeAttr('data-fields-editor-page-' + oldid);
						self.element.attr('data-fields-editor-page-' + data.id, true);

						// Assign pageid to self.options
						self.options.pageid = self.options.uid = data.id;

						// Update the $Pages registry
						$Pages[data.id] = $.extend(true, {}, $Pages[oldid]);
						delete $Pages[oldid];

						// Since the page has been saved, then it should not be a new page anymore
						self.options.newpage = false;
					}

					if(data.fields !== undefined) {
						$.each(data.fields, function(i, field) {
							// Go by sequence
							var item = self.item().eq(i).controller();

							item.updateResult(field);
						});
					}
				},

				/**
				 * Carry out necessary action when a new page is added
				 */
				'{self} pageAdded': function(el, event, page) {
					self.checkPageDeleteButton();

					$Parent.change();
				},

				checkPageDeleteButton: function() {
					if($Editor.page().length > 1) {
						self.pageDelete().show();
					} else {
						self.pageDelete().hide();
					}
				},

				'{parent} loadingConfig': function() {
					self.pageHeader().removeClass('editting');
					self.item().removeClass('editting');
				},

				'{parent} doneConfiguring': function() {
					self.pageHeader().removeClass('editting');
					self.item().removeClass('editting');
				}
			}
		});

		/* Editor Item Controller */
		EasySocial.Controller('Fields.Editor.Item', {
			defaultOptions: {
				appid			: 0,
				fieldid			: 0,
				pageid			: 0,

				newfield		: false,

				'{edit}'		: '[data-fields-editor-page-item-edit]',
				'{deleteButton}': '[data-fields-editor-page-item-delete]',
				'{moveButton}'	: '[data-fields-editor-page-item-move]',
				'{content}'		: '[data-fields-editor-page-item-content]',

				'{config}'		: '[data-fields-config]',

				'{closeConfig}'	: '[data-fields-config-close]'
			}
		}, function(self) {

			return {
				app: {},

				field: {
					id: 0,
					appid: 0,
					params: {}
				},

				state: $.Deferred(),

				appParams: {},

				init: function() {

					// Check if it has a valid appid or not
					if(self.options.appid == 0 && self.element.data('appid') !== undefined) {
						self.options.appid = self.element.data('appid');
					}

					// Check if this field's app is a valid app or not
					if($Apps[self.options.appid] !== undefined) {

						// Link the reference copy to self.app from $Apps registry
						self.app = $Apps[self.options.appid];
					}

					// Check if it has fieldid or not
					if(self.options.fieldid == 0 && self.element.data('id') !== undefined) {
						self.options.fieldid = self.element.data('id');
					}

					// Register $Fields
					self.registerFields();

					// Generate a unique id to identify configuration tabs
					self.uniqueid = $.uid(self.app.id + '_');

					self.loadedInit();
				},

				registerFields: function() {
					if(self.options.fieldid != 0) {
						$Fields[self.options.fieldid] = {
							id: self.options.fieldid,
							appid: self.options.appid,
							params: self.field.params || {}
						}

						// Link the reference copy to self.field if this is an existing field
						self.field = $Fields[self.options.fieldid];
					}
				},

				loadedInit: function() {

					// Implement field base controller
					self.element.addController('EasySocial.Controller.Field.Base', {
						mode: 'sample',
						element: self.app.element,
						'{field}': self.content.selector
					});

					// Implement a common config controller on the item
					self.content().addController('EasySocial.Controller.Fields.Editor.Item.Config');
				},

				// export data during save
				_export: function() {
					// Call checkout function from browser to check if all core apps has been used
					$Browser.checkout(self.options.appid);

					// Initialise export data with appid and fieldid
					// If fieldid == 0, means it is a new field
					// If appid == 0, means it is a non valid application
					var exportData 	= {
						"fieldid"	: self.options.fieldid,
						"appid"		: self.options.appid,
						"newfield"	: self.options.newfield
					};

					// Add in parameter values into export data
					exportData = $.extend(exportData, self.expandConfig(self.field.params));

					return exportData;
				},

				'{self} click': function(el, event) {
					var clickedElement = $(event.target);

					// Click on anywhere of the element except the delete button to load the configuration panel
					if(!clickedElement.is(self.deleteButton.selector) && !clickedElement.is(self.moveButton.selector) && !clickedElement.is(self.config.selector) && !clickedElement.is(self.closeConfig.selector) && !el.hasClass('editting')) {

						// If config state is true, means it is editting other field
						if($Config && $Config.state) {

							var state = $Config.checkConfig();

							// Remove itself from other field
							if(state) {
								$Config.closeConfig();
							} else {
								EasySocial.dialog({
									content: $.language('COM_EASYSOCIAL_PROFILES_FORM_FIELDS_INVALID_VALUES'),
									width: 400,
									height: 100
								});

								return;
							}
						}

						self.loadConfiguration();
					}
				},

				loadConfiguration: function() {
					// $Parent.trigger('loadingConfig', ['field']);
					$Parent.loadConfiguration(self, 'field');

					self.element.addClass('editting');

					self.getAppParams()
						.done(function() {

							var html = $(self.field.html);

							// Pass objects to config panel
							$Config.load(html);
						})
						.fail(function() {

						});
				},

				updateHtml: function(html) {
					self.field.html = html;
				},

				/**
				 * Get field parameters from the server.
				 */
				getAppParams: function() {
					var state = $.Deferred();

					if(self.field.html) {
						state.resolve();
					} else {
						EasySocial.ajax('admin/controllers/fields/renderConfiguration', {
							// Send the application id
							appid		: self.options.appid,

							// Send the field id.
							fieldid		: self.options.fieldid
						})
						.done(function(params, values, html) {

							self.app.params = params;

							self.field.params = values;

							self.field.html = html;

							// This will keep a flat list of the available parameters
							self.populateAppParams();

							state.resolve();
						})
						.fail(function(msg) {
							state.reject(msg);
						});
					}

					return state;
				},

				/**
				 * Populate parameters data
				 */
				populateAppParams: function() {
					$.each(self.app.params, function(i, paramProperties) {
						$.each(paramProperties.fields, function(name, field) {

							if(field.subfields) {
								$.each(field.subfields, function(subname, subfield) {
									self.appParams[name + '_' + subname] = subfield;
								});
							} else {
								self.appParams[name] = field;
							}
						});
					});
				},

				/**
				 * To return the field parameters value
				 */
				getConfigValues: function() {
					return self.field.params;
				},

				/**
				 * Converts flatten config data to expanded data for saving purposes
				 */
				expandConfig: function() {
					var newData = {
						params: {},
						choices: {}
					};

					$.each(self.field.params, function(name, value) {

						var field = self.appParams[name];

						if(!field) {
							return false;
						}

						var type = field.type == 'choices' ? 'choices' : 'params';

						newData[type][name] = value;
					});

					if(self.options.newfield) {
						newData.params.unique_key = '';
					}

					return newData;
				},

				'{deleteButton} click': function(el) {

					if(el.enabled()) {
						el.disabled(true);

						EasySocial.dialog(
						{
							content	: EasySocial.ajax( 'admin/views/profiles/confirmDeleteField' , {}),
							bindings :
							{
								"{cancelButton} click" : function()
								{
									// Close the dialog
									EasySocial.dialog().close();

									// Enable the delete button
									el.enabled( true );
								},
								"{deleteButton} click" : function()
								{
									// Update the dialog content to show loading status
									EasySocial.dialog().update({
										content: $.language('COM_EASYSOCIAL_PROFILES_FORM_FIELDS_DELETE_ITEM_DIALOG_DELETING')
									});

									// Start deleting field
									self.deleteField();

									// Close the dialog
									EasySocial.dialog().close();
								}

							}
						});
					}
				},

				deleteField: function() {
					// Trigger fieldDeleted event
					$Parent.trigger('fieldDeleted', [self.options.appid, self.options.fieldid]);

					if(!self.options.newfield) {

						// Delete fields in registry
						delete $Fields[self.options.fieldid];

						// Add this field into the deleted registry
						$Deleted.fields.push(self.options.fieldid);
					}

					// Remove field element
					self.element.remove();

					$Parent.change();
				},

				'{self} pageDeleted': function() {
					self.deleteField();
				},

				'{content} onConfigChange': function(el, event, name, value) {
					self.field.params[name] = value;
				},

				'{self} onPopulateConfig': function(el, event, values) {
					self.field.params = values;
				},

				// Unused
				updateResult: function(data) {
					// Update the unique key
					self.field.params.unique_key = data.unique_key;
					self.itemParam('[data-fields-config-param-field-unique_key]').val(data.unique_key);

					// If this is a new field, the some things need to be updated
					if(self.options.newfield) {
						// Set newfield to false because post-save, this will no longer be a new field
						self.options.newfield = false;

						// Set the fieldid
						self.options.fieldid = data.fieldid;
						self.element.data('id', data.fieldid);

						// Enable the unique key field
						self.itemParam('[data-fields-editor-page-item-param-field-unique_key]').removeAttr('disabled');

						// Register into $Fields registry
						self.registerFields();
					}

					if(data.choices !== undefined) {
						$.each(data.choices, function(name, choices) {
							var element = self.itemParam('[data-fields-config-param-field-' + name + ']');

							$.each(choices, function(i, choice) {
								// Go by sequence
								var item = element.find('li').eq(i);

								if(!item.data('id')) {
									item.attr('data-id', choice.id);
									item.data('id', choice.id);
								}
							});
						});
					}
				}
			}
		});

		/* Config Choices Controller */
		EasySocial.Controller( 'Config.Choices', {
			defaultOptions: {
				'{choiceItems}'	: '[data-fields-config-param-choice]',

				unique			: 1
			}
		}, function(self) {

			return {
				init: function() {
					self.options.unique = self.element.data('unique') !== undefined ? self.element.data('unique') : 1;

					self.choiceItems().implement( EasySocial.Controller.Config.Choices.Choice, {
						controller: {
							'item': self.item,
							'choices': self
						}
					});

					self.initSortable();
				},

				initSortable: function() {
					self.element.sortable({
						items: self.choiceItems.selector,
						placeholder: 'ui-state-highlight',
						cursor: 'move',
						forceHelperSize: true,
						handle: '[data-fields-config-param-choice-drag]',
						stop: function() {
							// Manually remove all the freezing tooltip due to conflict between bootstrap tooltip and jquery sortable
							$('.tooltip-es').remove();
						}
					});
				}
			}
		});

		/* Config Choices Choice Controller */
		EasySocial.Controller( 'Config.Choices.Choice', {
			defaultOptions: {
				'{choiceValue}'		: '[data-fields-config-param-choice-value]',
				'{choiceTitle}'		: '[data-fields-config-param-choice-title]',
				'{choiceDefault}'	: '[data-fields-config-param-choice-default]',
				'{addChoice}'		: '[data-fields-config-param-choice-add]',
				'{removeChoice}'	: '[data-fields-config-param-choice-remove]',
				'{setDefault}'		: '[data-fields-config-param-choice-setdefault]',

				'{defaultIcon}'		: '[data-fields-config-param-choice-defaulticon]'
			}
		}, function(self) {

			return {

				init: function() {
				},

				'{choiceTitle} keyup': $._.debounce(function(el, event) {
					var index = self.element.index();

					self.item.content().trigger('onChoiceTitleChanged', [index, el.val()]);

					$Parent.change();
				}, 500),

				'{choiceValue} keyup': $._.debounce(function(el, event) {
					var index = self.element.index();

					self.item.content().trigger('onChoiceValueChanged', [index, el.val()]);

					$Parent.change();
				}, 500),

				'{addChoice} click' : function() {
					// Clone a new item from current clicked element
					var newItem = self.element.clone();

					// Let's leave the value blank by default.
					var inputElement = newItem.find('input[type="text"]');

					inputElement.attr('value', '');

					inputElement.val('');

					// Set the default as 0 and the icon to unfeatured
					var inputDefault = newItem.find('input[type="hidden"]');

					inputDefault.attr('value', 0);

					inputDefault.val(0);

					var defaultLabel = newItem.find('[data-fields-config-param-choice-defaulticon]');

					defaultLabel.removeClass('es-state-featured').addClass('es-state-default');

					// set id = 0
					newItem.attr('data-id', 0);
					newItem.data('id', 0);

					// Implement the controller for this choice
					newItem.implement(EasySocial.Controller.Config.Choices.Choice, {
						controller: {
							'item': self.item,
							'choices': self.choices
						}
					});

					// Append this item
					self.element.after(newItem);

					// Get the index of the new item
					var index = newItem.index();

					self.item.content().trigger('onChoiceAdded', [index]);

					$Parent.change();
				},

				'{removeChoice} click' : function() {
					// We need to minus one because we're trying to remove ourself also.
					var remaining = self.choices.choiceItems().length - 1;

					// If this is the last item, we wouldn't want to allow the last item to be removed.
					if( remaining >= 1 ) {
						// Get the index of the new item
						var index = self.element.index();

						self.item.content().trigger('onChoiceRemoved', [index]);

						self.element.remove();

						// Manually remove the tooltip generated on the remove button
						$('.tooltip-es').remove();
					}

					$Parent.change();
				},

				'{setDefault} click': function() {
					var index = self.element.index(),
						title = self.choiceTitle().val(),
						value = self.choiceValue().val();

					self.choices.choiceItems().trigger( 'toggleDefault', [index] );

					self.item.content().trigger('onChoiceToggleDefault', [index, parseInt(self.choiceDefault().val())]);

					$Parent.change();
				},

				'{self} toggleDefault': function(el, ev, i) {
					var index = self.element.index(),
						value = parseInt(self.choiceDefault().val());

					if(index === i) {
						if(value) {
							self.defaultIcon()
								.removeClass('es-state-featured')
								.addClass('es-state-default');

							self.choiceDefault().val(0);
						} else {
							self.defaultIcon()
								.removeClass('es-state-default')
								.addClass('es-state-featured');

							self.choiceDefault().val(1);
						}
					} else {
						if(self.choices.options.unique) {
							self.defaultIcon()
								.removeClass('es-state-featured')
								.addClass('es-state-default');

							self.choiceDefault().val(0);
						}
					}
				}
			}
		});

		/* Editor Item Common Controller */
		// This is the common item config controller to implement on item
		EasySocial.Controller('Fields.Editor.Item.Config', {
			defaultOptions: {
				'{required}'			: '[data-required]',

				'{title}'				: '[data-title]',
				'{description}'			: '[data-description]',

				'{displayTitle}'		: '[data-display-title]',
				'{displayDescription}'	: '[data-display-description]'
			}
		}, function(self) {
			return {
				init: function() {

				},

				'{self} onConfigChange': function(el, event, name, value) {
					switch(name) {
						case 'display_title':
							self.displayTitle().toggle(!!value);
						break;

						case 'title':
							self.title().text(value);
						break;

						case 'display_description':
							self.displayDescription().toggle(!!value);
						break;

						case 'description':
							self.description().text(value);
						break;

						case 'required':
							self.required().toggle(!!value);
						break;
					}
				}
			}
		});

		module.resolve();
	}); // require end

}); // module end

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

EasySocial.module( 'admin/profiles/members' , function($) {

	var module = this;

	EasySocial
	.require()
	.language( 
		'COM_EASYSOCIAL_CANCEL_BUTTON',
		'COM_EASYSOCIAL_ASSIGN_BUTTON',
		'COM_EASYSOCIAL_PROFILES_ASSIGN_USER_DIALOG_TITLE'
	)
	.done( function($)
	{
		EasySocial.Controller(
			'Profiles.Members',
			{
				defaultOptions :
				{
					"{addUser}"	: "[data-profiles-addUser]",
					"{row}"		: "[data-profiles-members-row]"
				}
			},
			function(self)
			{
				return {

					init : function()
					{
						self.options.id 	= self.element.data( 'id' );
					},

					"{memberList} userSelected": function( el , event , id , name )
					{
						EasySocial.ajax( 'admin/controllers/profiles/insertMember', 
						{
							"id"			: id,
							"profile_id"	: self.options.id
						})
						.done( function( row )
						{
							self.row().append( row );

							// Close the dialog.
							EasySocial.dialog().close();
						});
					},

					"{addUser} click" : function()
					{
						var callbackId 	= $.callback( function(memberList){
							self.addPlugin( 'memberList' , memberList );
						});

						var url 		= $.indexUrl + "?option=com_easysocial&view=users&tmpl=component&callback=" + callbackId;

						EasySocial.dialog({
							title 		: $.language( 'COM_EASYSOCIAL_PROFILES_ASSIGN_USER_DIALOG_TITLE' ),
							content		: url,
							showOverlay	: false,
							width 		: 700,
							height 		: 600,
							buttons		:
							[
								{
									"name"			: $.language( "COM_EASYSOCIAL_CANCEL_BUTTON" ),
									"classNames"	: "btn btn-es",
									"click"			: function()
									{
										EasySocial.dialog().close();
									}
								}
							]
						});
					}

				}
			});

		module.resolve();

	});

});
EasySocial.module( 'admin/profiles/profiles' , function($) {

	var module = this;

	EasySocial
	.require()
	.done( function($)
	{

		EasySocial.Controller(
			'Profiles',
			{
				defaultOptions :
				{
					"{updateOrdering}"	: "[data-profiles-update-ordering]",
					"{item}"	: "[data-profiles-item]",

					view :
					{
						deleteConfirmation : 'admin/profiles/dialog.delete.confirm'
					}
				}
			},
			function(self)
			{
				return {

					init : function()
					{
						// Implement controller on each row.
						self.item().implement( EasySocial.Controller.Profiles.Item );
					},

					"{updateOrdering} click" : function()
					{
						// Check in all items
						$( '[data-table-checkall]' ).prop( 'checked' , true ).trigger( 'change' );

						$.Joomla( 'submitform' , [ 'updateOrdering' ] );
					}
				}
			});

		EasySocial.Controller(
		'Profiles.Item',
		{
			defaultOptions : 
			{
				"{insertLink}"		: "[data-profile-insert]"
			}
		},
		function( self )
		{
			return {
				init : function()
				{
					self.options.title 	= self.element.data( 'title' );
					self.options.id 	= self.element.data( 'id' );
				},

				"{insertLink} click" : function()
				{
					self.trigger( 'profileSelected' , [ self.options.id , self.options.title ] );
				}
			}
		});

		module.resolve();

	});

});
EasySocial.module( 'admin/reports/reporters' , function($) {

	var module = this;

	EasySocial.Controller(
		'Reports.Reporters',
		{
			defaultOptions : 
			{
				"{item}"		: "[data-reporters-item]"
			}
		},
		function( self )
		{
			return {
				init : function()
				{
					self.item().implement( EasySocial.Controller.Reports.Reporters.Item ,
						{
							"{parent}"	: self
						});
				}
			}
		}
	);

	EasySocial.Controller(
		'Reports.Reporters.Item',
		{
			defaultOptions :
			{
				"{removeItem}"	: "[data-remove-item]"			
			}
		},
		function( self )
		{
			return {
				init : function()
				{
					self.options.id 	= self.element.data( 'id' );
				},

				"{removeItem} click" : function()
				{
					// Remove any messages.
					self.parent.clearMessage();

					EasySocial.ajax( 'admin/controllers/reports/removeItem' ,
					{
						"id"	: self.options.id
					})
					.done(function( result )
					{
						self.parent.setMessage( result.message , result.type );

						self.element.remove();
					});
					
				}
			}
		}
	);

	module.resolve();

});
EasySocial.module( 'admin/reports/reports' , function($) {

	var module = this;

	EasySocial
	.require()
	.language( 
		'COM_EASYSOCIAL_CANCEL_BUTTON',
		'COM_EASYSOCIAL_CLOSE_BUTTON',
		'COM_EASYSOCIAL_REPORTS_VIEW_REPORTS_DIALOG_TITLE',
		'COM_EASYSOCIAL_REPORTS_ACTIONS_DIALOG_TITLE'
	)
	.done( function($)
	{

		EasySocial.Controller(
			'Reports',
			{
				defaultOptions : 
				{
					"{item}"		: "[data-reports-item]"
				}
			},
			function( self )
			{
				return {
					init : function()
					{
						self.item().implement( EasySocial.Controller.Reports.Item )
					}
				}
			});

		EasySocial.Controller(
			'Reports.Item',
			{
				defaultOptions :
				{
					"{action}"		: "[data-reports-item-view-actions]",
					"{viewReports}"	: "[data-reports-item-view-reports]"
				}
			},
			function( self )
			{
				return {
					init : function()
					{
						self.options.id 		= self.element.data( 'id' );
						self.options.extension	= self.element.data( 'extension' );
						self.options.uid 		= self.element.data( 'uid' );
						self.options.type 		= self.element.data( 'type' );
					},

					"{viewReports} click" : function()
					{

						EasySocial.dialog(
						{
							title 		: $.language( 'COM_EASYSOCIAL_REPORTS_VIEW_REPORTS_DIALOG_TITLE' ),
							content 	: EasySocial.ajax( 'admin/controllers/reports/getReporters' , 
											{ 
												id 			: self.options.id
											}),
							width 		: 600,
							height 		: 450
						});

					},

					"{action} click" : function()
					{
						EasySocial.dialog( 
						{
							title 		: $.language( 'COM_EASYSOCIAL_REPORTS_ACTIONS_DIALOG_TITLE' ),
							content		: '<div>Perform some actions on the item</div>',
							width 		: 500,
							height 		: 250,
							buttons 	: 
							[
								{
									name 		: $.language( 'COM_EASYSOCIAL_CLOSE_BUTTON' ),
									classNames	: "btn btn-es",
									click 		: function()
									{
										EasySocial.dialog().close();
									}
								}
							]
						})
					}
				}
			})

		module.resolve();
	});

});
EasySocial.module( 'admin/users/form' , function($) {

	var module = this;

	EasySocial.require()
	.script('field')
	.done(function($)
	{
		EasySocial.Controller(
		'Users.Form',
		{
			defaultOptions:
			{
				userid				: null,

				mode				: 'adminedit',

				"{selectProfile}"	: "[data-user-select-profile]",
				"{content}"			: "[data-user-new-content]",
				"{profileTitle}"	: "[data-profile-title]",

				"{fieldItem}"		: "[data-profile-adminedit-fields-item]",

				"{tabnav}"			: "[data-tabnav]",
				"{tabcontent}"		: "[data-tabcontent]",

				"{stepnav}"			: "[data-stepnav]",
				"{stepcontent}"		: "[data-stepcontent]",

				view:
				{
					loading : "site/loading/large"
				}
			}
		},
		function( self )
		{
			return {

				init : function()
				{
					window.selectedProfile 	= self.selectedProfile;

					self.fieldItem().addController('EasySocial.Controller.Field.Base', {
						userid: self.options.userid,
						mode: self.options.mode
					});
				},

				selectedProfile : function( profileId )
				{
					EasySocial.dialog().close();

					window.location.href	= 'index.php?option=com_easysocial&view=users&layout=form&profileId=' + profileId;
				},

				"{selectProfile} click" : function()
				{
					EasySocial.dialog(
					{
						content 	: EasySocial.ajax( 'admin/views/profiles/browse' )
					});
				},

				errorFields: [],

				'{fieldItem} error': function(el, ev) {
					var id = el.data('id');

					if($.inArray(id, self.errorFields) < 0) {
						self.errorFields.push(id);
					}

					var stepid = el.parents(self.stepcontent.selector).data('for');

					self.stepnav().filterBy('for', stepid).trigger('error');

					var tabid = el.parents(self.tabcontent.selector).data('for');

					self.tabnav().filterBy('for', tabid).trigger('error');
				},

				'{fieldItem} clear': function(el, ev) {
					var fieldid = el.data('id');

					self.errorFields = $.without(self.errorFields, fieldid);

					var stepid = el.parents(self.stepcontent.selector).data('for');

					self.stepnav().filterBy('for', stepid).trigger('clear');

					var tabid = el.parents(self.tabcontent.selector).data('for');

					self.tabnav().filterBy('for', tabid).trigger('clear');
				},

				'{stepnav} error': function(el) {
					el.addClass('error');
				},

				'{tabnav} error': function(el) {
					el.addClass('error');
				},

				'{stepnav} clear': function(el) {
					if(self.errorFields.length < 1) {
						el.removeClass('error');
					}
				},

				'{tabnav} clear': function(el) {
					if(self.errorFields.length < 1) {
						el.removeClass('error');
					}
				}
			}
		});

		module.resolve();
	});

});

EasySocial.module( 'admin/users/privacy' , function($){

	var module 	= this;

	EasySocial.require()
	.library( 'textboxlist' )
	.view( 'site/loading/small' )
	.done(function($){

		EasySocial.Controller(
			'Profile.Privacy',
			{
				defaultOptions:
				{
					userId	: '',

					"{privacyItem}" : "[data-privacy-item]",

					//input form
					"{privacyForm}" : "[data-profile-privacy-form]",

					view :
					{
						loading : "site/loading/small"
					}
				}
			},
			function( self )
			{
				return {

					init : function()
					{
						self.privacyItem().implement( EasySocial.Controller.Profile.Privacy.Item ,
						{
							"{parent}"	: self
						});
					}
				}
			}
		);


		EasySocial.Controller(
			'Profile.Privacy.Item',
			{
				defaultOptions :
				{
					"{selection}"		: "[data-privacy-select]",
					"{hiddenCustom}" 	: "[data-hidden-custom]",
					"{customForm}" 		: "[data-privacy-custom-form]",

					"{customTextInput}" : "[data-textfield]",
					"{customItems}"		: "input[]",
					"{customHideBtn}"	: "[data-privacy-custom-hide-button]",
					"{customInputItem}"	: "[data-textboxlist-item]",
					"{customEditBtn}"   : "[data-privacy-custom-edit-button]"
				}
			},
			function( self )
			{
				return {
					init : function()
					{
						self.customTextInput().textboxlist(
							{
								unique: true,

								plugin: {
									autocomplete: {
										exclusive: true,
										minLength: 2,
										cache: false,
										query: function( keyword ) {

											var users = self.getTaggedUsers();

											var ajax = EasySocial.ajax("site/views/privacy/getfriends",
												{
													q: keyword,
													userid: self.parent.options.userId,
													exclude: users
												});
											return ajax;
										}
									}
								}
							}
						);

						self.textboxlistLib = self.customTextInput().textboxlist("controller");
					},

					getTaggedUsers: function()
					{
						var users = [];
						var items = self.customInputItem();

						if( items.length > 0 )
						{
							$.each( items, function( idx, element ) {
								users.push( $( element ).data('id') );
							});
						}

						return users;
					},

					// event listener for adding new name
					"{customTextInput} addItem": function(el, event, data) {

						// lets get the exiting ids string
						var ids    = self.hiddenCustom().val();
						var values = '';

						if( ids == '')
						{
							values = data.id;
						}
						else
						{
							var idsArr = ids.split(',');
							idsArr.push( data.id );

							values = idsArr.join(',');
						}

						//now update the customhidden value.
						self.hiddenCustom().val( values );
					},

					// event listener for removing name
					"{customTextInput} removeItem": function(el, event, data ) {
						// lets get the exiting ids string
						var ids    = self.hiddenCustom().val();
						var values = '';
						var newIds = [];

						var idsArr = ids.split(',');

						for( var i = 0; i < idsArr.length; i++ )
						{
							if( idsArr[i] != data.id )
							{
								newIds.push( idsArr[i] );
							}
						}

						if( newIds.length <= 0 )
						{
							values = '';
						}
						else
						{
							values = newIds.join(',');
						}

						//now update the customhidden value.
						self.hiddenCustom().val( values );
					},

					"{customEditBtn} click" : function( el )
					{
						self.customForm().toggle();
					},

					"{selection} change" : function( el )
					{
						var selected = el.val();

						if( selected == 'custom' )
						{
							self.customForm().show();
							self.customEditBtn().show();
						}
						else
						{
							self.customForm().hide();
							self.customEditBtn().hide();
						}

						return;
					},

					"{customHideBtn} click" : function()
					{
						self.customForm().hide();
						self.customEditBtn().show();

						self.textboxlistLib.autocomplete.hide();

						return;
					}
				}
			});


		module.resolve();
	});

});

EasySocial.module( 'admin/users/users' , function($) {

	var module = this;

	EasySocial
	.require()
	.library( 'expanding' )
	.done( function($)
	{

		EasySocial.Controller(
			'Users',
			{
				defaultOptions : 
				{
					"{item}"	: "[data-user-item]"
				}
			},
			function( self )
			{
				return {
					init : function()
					{
						self.item().implement( EasySocial.Controller.Users.Item );
					}
				}
			});

		EasySocial.Controller(
			'Users.Item',
			{
				defaultOptions : 
				{
					"{insertLink}"	: "[data-user-item-insertLink]"
				}
			},
			function( self )
			{
				return {
					init : function()
					{
						self.options.name 	= self.element.data( 'name' );
						self.options.avatar	= self.element.data( 'avatar' );
						self.options.email	= self.element.data( 'email' );
						self.options.id 	= self.element.data( 'id' );
					},

					"{insertLink} click" : function()
					{
						self.trigger( 'userSelected' , [ self.options.id , self.options.name , self.options.avatar , self.options.email ] );
					}
				}
			});


		EasySocial.Controller(
			'Users.Pending',
			{
				defaultOptions : 
				{
					"{item}"	: "[data-pending-item]"
				}
			},
			function( self )
			{
				return {
					init : function()
					{
						self.item().implement( EasySocial.Controller.Users.Pending.Item );
					}
				}
			});


		EasySocial.Controller(
			'Users.Pending.Item',
			{
				defaultOptions : 
				{
					"{approve}" : "[data-pending-approve]",
					"{reject}"	: "[data-pending-reject]"
				}
			},
			function( self )
			{
				return {
					init : function()
					{
						self.options.id 	= self.element.data( 'id' );
					},

					"{approve} click" : function()
					{
						EasySocial.dialog(
						{
							content 	: EasySocial.ajax( 'admin/views/users/confirmApprove' , { "id" : self.options.id } ),
							bindings 	:
							{
								"{approveButton} click" : function()
								{
									$( '[data-users-approve-form]' ).submit();
								}
							}
						});
					},

					"{reject} click" : function()
					{
						EasySocial.dialog(
						{
							content 	: EasySocial.ajax( 'admin/views/users/confirmReject' , { "id" : self.options.id } )
						});

					}
				}
			})		
		module.resolve();

	});

});
EasySocial.module( 'admin/widgets/news' , function($) {

	var module = this;

	EasySocial.require()
	.done(function($){

		EasySocial.Controller(
				'News',
				{
					defaultOptions: {
						
						// Properties
						loadOnInit 	: true,

						// Elements
						"{items}"	: "[widget-news-items]",
						"{placeholder}"	: "[widget-news-placeholder]"
					}
				},
				function( self ){

					return {

						init: function()
						{
							// When page loads, obtain the news
							if( self.options.loadOnInit )
							{
								self.getNews();
							}
						},

						/**
						 * Gets the news items from the server.
						 */
						getNews: function()
						{
							EasySocial.ajax( 'admin/controllers/news/getnews' )
							.done(function( content )
							{
								// Append the news.
								self.items().append( content );

								// Hide placeholder
								self.placeholder().remove();

							});
						}
					}
				}
		);
	
		module.resolve();
	});

});

EasySocial.module("albums/album", function($){

	var module = this;

	EasySocial.require()
		.library(
			"tinyscrollbar"
		)
		.done(function(){

			EasySocial.Controller("Albums.Album",
			{
				defaultOptions: {
					"{title}"        : "[data-album-title]",
					"{count}"        : "[data-album-count]",
					"{privacy}"      : "[data-album-privacy]",
					"{cover}"        : "[data-album-cover]",
					"{coverImage}"   : "[data-album-cover-image]",
					"{link}"         : "[data-album-link]",
					"{menu}"         : "[data-album-menu]",
					"{menuActions}"  : "[data-album-menu-actions]",
					"{shareButton}"  : "[data-album-share-button]",
					"{shareContent}" : "[data-sharing]",
					"{followButton}" : "[data-album-follow-button]",
					"{reportButton}" : "[data-album-report-button]",
					"{deleteButton}" : "[data-album-delete-button]",

					"{likeButton}"        : "[data-album-like-button]",
					"{commentButton}"     : "[data-album-comment-button]",

					"{countsButton}"      : "[data-album-counts-button]",
					"{commentCount}"      : "[data-album-comment-count]",
					"{likeCount}"         : "[data-album-like-count]",

					"{actions}"           : "[data-item-actions]",
					"{actionContent}"     : "[data-item-action-content]",
					"{actionCloseButton}" : "[data-item-action-close-button]",
					"{actionsMenu}"       : "[data-item-actions-menu]",

					"{likesHolder}"       : "[data-album-likes-holder]",
					"{commentsHolder}"    : "[data-album-comments-holder]",
					"{responseHolder}"    : "[data-album-response-holder]",

					"{comments}": "[data-comments]"
				}
			},
			function(self) { return {

				init: function()
				{
					self.id = self.element.data("album-id");

					self.actionContent()
						.tinyscrollbar();

					if (self.actions().hasClass("open")) {
						self.loadResponse();
						self.element.addClass("show-all");
					}
				},

				remove: function()
				{
					self.element.remove();
				},

				"{coverImage} click": function() {

					window.location = self.link().attr("href");
				},

				"{shareButton} click": function()
				{
					self.shareContent().show();
				},

				"{deleteButton} click": function()
				{
					EasySocial.dialog(
					{
						content: EasySocial.ajax( "site/views/albums/confirmDelete", { id: self.id })
					});
				},

				like: function() {

					EasySocial.ajax(
						"site/controllers/albums/like",
						{
							id: self.id
						}
					)
					.done(function(like) {

						// TODO: Update like count
						self.likeCount().html( like.count );

						// TODO: Change like text
						if( like.state )
						{
							self.likeButton().addClass( "liked" );
						}
						else
						{
							self.likeButton().removeClass("liked");
						}

						// TODO: Update like summary
						self.likesHolder().html( like.html );

						// To determine whether or not to like or unlike
						// self.likeButton().hasClass("liked")
					});
				},

				loadResponse: function() {

					var loader = self.loadResponse.loader;

					if (!loader || loader.state()=="rejected") {

						self.loadResponse.loader =
							EasySocial.ajax(
								"site/views/albums/response",
								{
									id: self.id
								}
							)
							.done(function(html) {

								self.responseHolder().html(html);

								self.actionContent()
									.removeClass("loading")
									.tinyscrollbar_update();
							});
					}
				},

				getButton: function(toggle) {

					var toggle = $(toggle),
						countsButton = self.countsButton(),
						commentButton = self.commentButton();

						if (toggle.is(countsButton) ||
							toggle.parents().filter(countsButton).length > 0) {
							return countsButton;
						}

						if (toggle.is(commentButton) ||
							toggle.parents().filter(commentButton).length > 0)
							return commentButton;

						return $();
				},

				lastButton: $(),

				"{actions} dropdownOpen": function(actions, event, toggle) {

					// Show likes & comments
					self.loadResponse();

					// Make dropdown persistent even when hovered away
					self.element
						.addClass("show-all");

					var actionContent = self.actionContent(),
						button = self.lastButton = self.getButton(toggle),
						offset = (button.position().left + (button.width() / 2)) - (actionContent.width() / 2);

						actionContent
							.css("margin-left", offset)
							.tinyscrollbar_update();
				},

				"{actions} dropdownClose": function(actions, event, toggle) {

					self.element.removeClass("show-all");

					var button = self.getButton(toggle),
						lastButton = self.lastButton;

					if (!button.is(lastButton)) {
						setTimeout(function(){button.trigger("click")}, 0);
					}
				},

				"{actionCloseButton} click": function(el) {

					self.hideActionContent();
				},

				"{likeButton} click": function() {
					self.like();
				},

				"{comments} newCommentSaved": function() {

					var stat = self.comments().controller("EasySocial.Controller.Comments.Stat");
					self.commentCount().html(stat.total());

					self.actionContent()
						.tinyscrollbar_update("bottom");
				},

				"{comments} commentDeleted": function() {

					var stat = self.comments().controller("EasySocial.Controller.Comments.Stat");
					self.commentCount().html(stat.total());

					self.actionContent()
						.tinyscrollbar_update();
				},

				"{actionsMenu} dropdownOpen": function() {
					self.element.addClass("show-all");
				},

				"{actionsMenu} dropdownClose": function() {
					self.element.removeClass("show-all");
				}

			}});

			module.resolve();

		});
});


EasySocial.module("albums/browser", function($){

	var module = this;

	EasySocial.require()
		.library(
			"history"
		)
		.view(
			"site/albums/browser.list.item"
		)
		.done(function(){

			EasySocial.Controller("Albums.Browser",
			{
				hostname: "browser",

				defaultOptions: {

					view: {
						listItem: "site/albums/browser.list.item"
					},

					itemRenderOptions: {},

					"{sidebar}": "[data-album-browser-sidebar]",
					"{content}": "[data-album-browser-content]",

					"{createAlbumButton}"    : "[data-album-create-button]",
					"{createAlbumButtonLink}": "[data-album-create-button] > a",

					"{listItemGroup}": "[data-album-list-item-group]",
					"{listItemRegularGroup}": "[data-album-list-item-group=regular]",
					"{listItemCoreGroup}": "[data-album-list-item-group=core]",

					"{listItem}"     : "[data-album-list-item]",
					"{listItemLink}" : "[data-album-list-item] > a",
					"{listItemTitle}": "[data-album-list-item-title]",
					"{listItemCover}": "[data-album-list-item-cover]",
					"{listItemCount}": "[data-album-list-item-count]",

					"{albumItem}": "[data-album-item]",

					"{photoBrowser}": "[data-photo-browser]"
				}
			},
			function(self) { return {

				init: function() {

					// Attach existing album items as subscriber
					self.albumItem().each(function(){
						self.addSubscriber($(this).controller("EasySocial.Controller.Albums.Item"));
					})
				},

				setLayout: function(layout) {

					// Don't switch layout on dialog.
					if (self.element.hasClass("layout-dialog")) return;

					self.element
						.data("layout", layout)
						.switchClass("layout-" + layout);
				},

				open: function(view) {

					var args = $.makeArray(arguments);

					self.trigger("contentload", args);

					var method = "view" + $.String.capitalize(view),
						loader = self[method].apply(self, args.slice(1));

					loader
						.done(self.displayContent(function(){
							self.trigger("contentdisplay", args);
							return arguments;
						}))
						.fail(function(){
							self.trigger("contentfail", args);
						})
						.always(function(){
							self.trigger("contentcomplete", args);
						});

					return loader;
				},

				"{self} contentdisplay": function(el, event, view) {

					if (/album|albumform/gi.test(view)) {
						self.setLayout("album");
					}

					if (/photo/gi.test(view)) {
						self.setLayout("photo");
					}
				},

				displayContent: $.Enqueue(function(html){

					var scripts = [],
						content = $($.buildFragment([html], document, scripts));

					// Insert content
					self.content().html(content);

					// Remove scripts
					$(scripts).remove();
				}),

				viewAlbum: function(albumId) {

					// Remove loading indicator from any existing ones
					self.listItem().removeClass("active loading");

					var listItem =
						self.getListItem(albumId)
							.addClass("active loading");

					// Don't route if we're on dialog layout
					if (self.element.data("layout")!=="dialog") {
						listItem.find("> a").route();
					}

					var loader = 
						EasySocial.ajax(
							"site/views/albums/item",
							{
								id: albumId,
								renderOptions: self.options.itemRenderOptions
							})
							.fail(function(){

							})
							.always(function(){

								listItem.removeClass("loading");
							});

					return loader;
				},

				viewAlbumForm: function() {

					// Remove loading indicator from any existing ones
					var listItems = self.listItem().removeClass("active loading"),
						listItem = 
							self.view.listItem({})
								.addClass("active loading new")
								.prependTo(self.listItemRegularGroup());

					var loader = 
						EasySocial.ajax(
							"site/views/albums/form"
							)
							.fail(function(){

							})
							.always(function(){

								listItem.removeClass("loading");
							});

					return loader;
				},

				viewPhoto: function(photoId) {

					var loader = 
						EasySocial.ajax(
							"site/views/photos/item",
							{
								id: photoId,
								browser: 1
							})
							.fail(function(){
							})
							.always(function(){
							});

					return loader;
				},

				"{listItem} click": function(listItem) {

					// Don't do anything on new album item
					if (listItem.hasClass("new")) return;

					var albumId = listItem.data("albumId");

					// Load album
					self.open("Album", albumId);
				},

				"{listItemLink} click": function(listItemLink, event) {

					// Progressive enhancement, no longer refresh the page.
					event.preventDefault();

					// Prevent item from getting into :focus state
					listItemLink.blur();
				},

				"{createAlbumButton} click": function() {

					self.open("AlbumForm");

					// Don't route if we're on dialog layout
					if (self.element.data("layout")!=="dialog") {

						self.createAlbumButtonLink().route();
					}
				},

				"{createAlbumButtonLink} click": function(el, event) {

					event.preventDefault();
				},

				"{albumItem} init.albums.item": function(el, event, albumItem) {

					self.addSubscriber(albumItem);
				},

				getListItem: function(albumId, context) {

					var listItem = 
						(!albumId) ?
							self.listItem(".new") :
							self.listItem().filterBy("albumId", albumId);

					if (!context) return listItem;

					return listItem.find(self["listItem" + $.String.capitalize(context)].selector);
				},

				updateListItemCount: function(albumId, val, append) {

					var stat = self.getListItem(albumId, "count");

					// If no stat element found, stop.
					if (stat.length < 0) return;

					// Get current stat count
					var statCount;

					if (append) {
						statCount = (parseInt(stat.text()) || 0) + (parseInt(val) || 0);
					} else {
						statCount = val;
					}
					
					// Always stays at 0 if less than that
					if (statCount < 0) statCount = 0;
					
					// Update stat count
					stat.text(statCount);
				},

				"{albumItem} albumSave": function(el, event, task) {

					task.done(function(album){

						// For new albums
						// Remove item link's new state
						self.getListItem()
							.attr("data-album-id", album.id)
							.removeClass("new")

						// Update item link & route url	
						self.getListItem(album.id)						
							.find("> a")
							.attr({
								href : album.permalink,
								title: album.title
							})
							.route();

						// For existing albums
						self.getListItem(album.id, "title")
							.html(album.title);
					});
				},
				
				"{albumItem} titleChange": function(el, event, title, album) {

					self.getListItem(album.id, "title")
						.html($.trim(title) || "&nbsp;");
				},

				"{albumItem} coverChange": function(el, event, photo, album) {

					self.getListItem(album.id, "cover")
						.css("backgroundImage", $.cssUrl(photo.sizes.thumbnail.url));
				},

				"{albumItem} coverRemove": function(el, event, album) {

					self.getListItem(album.id, "cover")
						.css("backgroundImage", "");
				},

				"{albumItem} photoAdd": function(el, event, photoItem, photoData, album) {

					self.updateListItemCount(album.id, 1, true);
				},

				"{albumItem} photoMove": function(el, event, task, photo) {

					task
						.done(function(){
							self.updateListItemCount(photo.album.id, -1, true);
						});
				},

				"{albumItem} photoDelete": function(el, event, task, photo) {

					task
						.done(function(){
							self.updateListItemCount(photo.album.id, -1, true);
						});
				},

				"{photoBrowser} init.photos.browser": function(el, event, photoBrowser) {

					// Attach browser to photo browser
					self.addSubscriber(photoBrowser);
				},

				"{self} contentload": function() {

					// Remove any new item because there can only be one 
					self.listItem(".new").remove();
				}
				
			}});

			module.resolve();

		});
});
EasySocial.module("albums/editor", function($){

	var module = this;

	// Constants
	var photoEditorController = "EasySocial.Controller.Photos.Editor"

	// Non-essential dependencies
	EasySocial.require()
		.script(
			"albums/editor/sortable",
			"albums/editor/uploader"
		)
		.done();

	// Essential dependencies
	var Controller = 

	EasySocial.Controller("Albums.Editor",
	{
		hostname: "editor",

		defaultOptions: {

			view: {
		        uploadItem: "site/albums/upload.item"
			},

			canReorder: false,
			canUpload: true,

			"{titleField}"        : "[data-album-title-field]",
			"{captionField}"      : "[data-album-caption-field]",
			"{coverField}"        : "[data-album-cover-field]",

			"{location}"          : "[data-album-location]",
			"{locationCaption}"   : "[data-album-location-caption]",
			"{addLocationButton}" : "[data-album-addLocation-button]",
			"{date}"              : "[data-album-date]",
			"{dateCaption}"       : "[data-album-date-caption]",
			"{addDateCaption}"    : "[data-album-addDate-button]",
			"{privacy}"           : "[data-album-privacy]",

			"{uploadButton}"      : "[data-album-upload-button]",
			"{deleteButton}"      : "[data-album-delete-button]",
			"{moreButton}"        : "[data-album-more-button]",			

			"{privacy}"			  : "[data-privacy-hidden]",
			"{privacycustom}"	  : "[data-privacy-custom-hidden]",

			"{uploadItem}"        : "[data-photo-upload-item]",

			"{dateDay}"		    : "[data-date-day]",
			"{dateMonth}"		: "[data-date-month]",
			"{dateYear}"		: "[data-date-year]",

			"{editButton}"     : "[data-album-edit-button]",
			"{editButtonLink}" : "[data-album-edit-button] > a",
			"{doneButton}"     : "[data-album-done-button]",
			"{doneButtonLink}" : "[data-album-done-button] > a",

			"{locationWidget}"  : ".es-album-location-form .es-location",
			"{latitude}"        : "[data-location-lat]",
			"{longitude}"       : "[data-location-lng]"
		}
	},
	function(self) { return {

		init: function() {

			self.id = self.element.data("album-id");

			var options = self.options;

			// If we can sort photos, load & implement sortable.
			if (options.canReorder) {
				EasySocial.module("albums/sortable")
					.done(function(SortableController){
						self.addPlugin("sortable", SortableController);
					});
			}

			// If we can upload photos, load & implement uploader.
			if (options.canUpload) {

				EasySocial.module("albums/editor/uploader")
					.done(function(UploaderController){
						self.uploader = self.addPlugin("uploader", UploaderController);
					});
			}

			// If this is an existing album, there's no need to create album
			if (self.id) {
				self.createAlbum.task = $.Deferred().resolve();
				self.createStream = 0;
			} else {
				self.createStream = 1;
			}
		},

		data: function() {

			var title         = self.titleField().val(),
				caption       = self.captionField().val(),
				date          = self.formatDate(),
				address       = self.locationCaption().html(),
				latitude      = self.latitude().val(),
				longitude     = self.longitude().val(),
				privacy       = self.privacy().val(),
				privacycustom = self.privacycustom().val();

			return {
				id           : self.id,
				title        : title,
				caption      : caption,
				date         : date,
				address      : address,
				latitude     : latitude,
				longitude    : longitude,
				privacy      : privacy,
				privacycustom: privacycustom,
				createStream : self.createStream
			}
		},

		createAlbum: function() {

			var task = self.createAlbum.task;

			if (!task) {

				task = self.createAlbum.task = 

					self.save({
							createStream: 0
						})
						.done(function(album){
							self.deleteButton().disabled(false);
							self.element.attr("data-album-id", self.id = album.id);
						})
						.fail(function(message, type){
							self.setMessage(message, type);
						});
			}

			return task;
		},

		save: function(options) {

			self.trigger("beforeAlbumSave", [self]);

			// Build save data
			var data = $.extend(self.data(), options);

				data.photos = 
					$.map(
						self.album.photoItem(),
						function(photoItem, i){
							var editor = $(photoItem).controller("EasySocial.Controller.Photos.Editor");
							return (editor) ? editor.data() : null;
						});

				// TODO: Get photo ordering
				// data.ordering = self.getPhotoOrdering();

			// Clear any messages
			self.clearMessage();

			// Save album
			var task = EasySocial.ajax("site/controllers/albums/store", data);

			// Trigger albumSave event
			self.trigger("albumSave", [task, self]);

			// Return task
			return task;
		},

		"{self} photoAdd": function(el, event, photoItem, photoData) {

			// Set cover if this is the first photo
			if (self.album.photoItem().length <= 1) {
				self.changeCover(photoData);
			}
		},

		setCover: function(photoId) {

			var task = 
				EasySocial.ajax(
					"site/controllers/albums/setCover",
					{
						albumId: self.id,
						coverId: photoId
					}
				)
				.done(function(photo){
					self.changeCover(photo);
				})
				.fail(function(){

				});

			return task;
		},

		removeCover: function() {

			self.trigger("coverRemove", [self.album]);
		},

		changeCover: function(photo) {

			self.trigger("coverChange", [photo, self]);
		},

		"{self} coverChange": function(el, event, photo) {

			self.coverField()
				.removeClass("no-cover")
				.css("backgroundImage", $.cssUrl(photo.sizes.thumbnail.url));				
		},

		"{self} coverRemove": function() {

			self.coverField()
				.addClass("no-cover")
				.css("backgroundImage", "");			
		},

		"{editButton} click": function() {

			// Change viewer layout
			self.album.setLayout("form");

			// Change address bar url
			self.editButtonLink().route();
		},

		"{editButtonLink} click": function(editButtonLink, event) {

			event.preventDefault();
		},

		"{doneButton} click": function() {

			self.save()
				.done(function(album, html){
					$.buildHTML(html).replaceAll(self.element);
				})
				.progress(function(message, type){
					self.setMessage(message, type);
				});
		},

		"{doneButtonLink} click": function(doneButtonLink, event) {
			event.preventDefault();
		},

		"{deleteButton} click": function(deleteButton) {

			if (deleteButton.disabled()) return;

			EasySocial.dialog({
				content: EasySocial.ajax("site/views/albums/confirmDelete", {id: self.id})
			});
		},

		formatDate: function() {
			var day = self.dateDay().val() || self.dateDay().data('date-default'),
				month = self.dateMonth().val() || self.dateMonth().data('date-default'),
				year = self.dateYear().val() || self.dateYear().data('date-default');

			return year + '-' + month + '-' + day;
			},

		updateDate: function() {

			self.date().addClass("has-data");
			var dateCaption = self.dateDay().val() + ' ' + $.trim(self.dateMonth().find(":selected").html()) + ' ' + self.dateYear().val();
			self.dateCaption().html(dateCaption);
		},

		"{dateDay} keyup": function() {
			self.updateDate();
		},

		"{dateMonth} change": function() {
			self.updateDate();
		},

		"{dateYear} keyup": function() {
			self.updateDate();
		},

		"{titleField} keyup": function(titleField) {

			self.trigger("titleChange", [titleField.val(), self]);
		},

		"{locationWidget} locationChange": function(el, event, location) {

			var address = location.formatted_address;
			self.locationCaption().html(address);
			self.location().addClass("has-data");
		}

	}});

	module.resolve(Controller);
});

EasySocial.module("albums/editor/sortable", function($){

	var module = this;

	EasySocial.require()
		.library(
			"ui/sortable"
		)
		.done(function(){

			var Controller = 

			EasySocial.Controller("Albums.Editor.Sortable",
			{
				defaultOptions: {

				}
			},
			function(self) { return {

				init: function() {

					return;

					self.photoItemGroup()
						.sortable({
							forcePlaceholderSize: true,
							items: self.photoItem.selector,
							placeholder: 'es-photo-item placeholder',
							tolerance: 'pointer',
							delay: 150
						});
				},

				getPhotoOrdering: function() {

					var ordering = {};

					self.photoItem().each(function(i){
						var id = $(this).data("photoId");
						ordering[id] = i;
					});

					return ordering;
				},				

				"{parent.photoItemGroup} sortstart": function(el, event, ui) {

					ui.item.addClass("dragging");
					el.addClass("ordering");
					self.setLayout();
				},

				"{parent.photoItemGroup} sortchange": function(el, event, ui) {
					self.setLayout();
				},

				"{parent.photoItemGroup} sortstop": function(el, event, ui) {
					ui.item.removeClass("dragging");
					el.removeClass("ordering");
					self.setLayout();

					EasySocial.ajax(
						"site/controllers/photos/reorder",
						{
							id: ui.item.controller().id,
							order: ui.item.index()
						});					
				}
				
			}});

			module.resolve(Controller);

		});
});

EasySocial.module("albums/editor/uploader", function($){

	var module = this;

	EasySocial.require()
		.script(
			"albums/uploader"
		)
		.view(
			"site/albums/upload.item"
		)		
		.done(function(){

			var Controller = 

			EasySocial.Controller("Albums.Editor.Uploader",
			{
				defaultOptions: {

				}
			},
			function(self) { return {

				init: function() {

					// Shortcuts
					self.album = self.editor.album;

					// Get upload settings
					var settings = self.album.options.uploader;

					// Implement uploader
					self.uploader =
						self.addPlugin(
							"uploader",
							EasySocial.Controller.Albums.Uploader,
							{
								settings: settings,
								"{uploadButton}"   : self.editor.uploadButton.selector,
								"{uploadItemGroup}": self.album.photoItemGroup.selector,
								"{uploadDropsite}" : self.album.content.selector
							}
						);
				},

				setLayout: function() {

					self.album.setLayout_();
				},

				"{self} beforeAlbumSave": function() {

					// Stop existing upload process.
					self.uploader.stop();
				},

				"{self} albumSave": function(el, event, task) {

					task.done(function(album){

						var url = 
							$.uri(self.uploader.settings("url"))
								.replaceQueryParam("albumId", album.id)
								.toString();

						self.uploader.settings("url", url);							
					});
				},

				"{self} layoutChange": function(el, event, layoutName) {

					// Stop any running upload process
					// and clear upload items.
					self.uploader.stop();
					self.uploader.clear();

					var url = 
						$.uri(self.uploader.settings("url"))
							.replaceQueryParam("createStream", layoutName=="form" ? 0 : 1)
							.replaceQueryParam("layout", layoutName)
							.toString();

					self.uploader.settings("url", url);
				},

				"{self} QueueCreated": function(el, event, uploadItem) {
					
					// Give upload item a layout when we're under editor
					if (self.album.currentLayout()=="form") {
						uploadItem.element.addClass("layout-form");
					}

					self.setLayout();
				},
				
				startUpload: $.Enqueue(),

				"{uploader} FilesAdded": function(el, event, uploader, files) {

					// If this is a new album
					if (!self.id) {

						// Create the album first
						self.editor.createAlbum()
							.done(
								// Before we start uploading
								self.startUpload(function(){
									self.uploader.start();
								})
							);

					// Else start uploading straightaway
					} else {
						self.uploader.start();
					}

					self.setLayout();
				},

				"{uploader} FilesRemoved": function() {

					self.setLayout();
				},

				"{uploader} FileUploaded": function(el, event, uploader, file, response) {

					var uploadItem = self.uploader.getItem(file),

						photoItem = $.buildHTML(response.html),

						photoData = response.data;

						// Initialize photo item
						photoItem
							.addClass("new-item")
							.insertAfter(uploadItem.element);

						setTimeout(function(){
							photoItem.removeClass("new-item");
						}, 1);

						self.uploader.removeItem(file.id);

						self.trigger("photoAdd", [photoItem, photoData, self.album]);

						self.setLayout();
				}

			}});

			module.resolve(Controller);

		});
});

// module: start
EasySocial.module("albums/uploader", function($) {

    var module = this;

    // require: start
    EasySocial.require()
    .library(
        "plupload"
    )
    .script(
        "albums/uploader.item"
    )
    .view(
    	"site/albums/upload.item"
    )
    .done(function(){

        // controller: start
        EasySocial.Controller("Albums.Uploader",

        	{
        		defaultOptions: {

                    view: {
                        uploadItem: "site/albums/upload.item"
                    },

                    "{uploadButton}"   : "[data-upload-button]",
                    "{uploadItemGroup}": "[data-upload-item-group]",
                    "{uploadItem}"     : "[data-upload-item]",
                    "{uploadDropsite}" : "[data-upload-dropsite]"
        		}
        	},

            function(self) { return {

                init: function() {

                    var uploader = self.element;

                    // Plupload controller
                    self.pluploadController =
                        self.element
                            .addController(
                                "plupload",
                                $.extend({
                                    "{uploadButton}" : self.uploadButton.selector,
                                    "{uploadDropsite}": self.uploadDropsite.selector
                                },
                                self.options.settings)
                            );

                    // Plupload
                    self.plupload = self.pluploadController.plupload;

                    // Indicate uploader supports drag & drop
                    if (!$.IE && self.plupload.runtime=="html5") {

                        uploader.addClass("can-drop-file");
                    }

                    // Indicate uploader is ready
                    uploader.addClass("can-upload");
        		},

        		setLayout: function() {

                    self.uploadItemGroup().toggleClass("no-upload-items", self.uploadItem().length < 1);
        		},

                items: {},

                getItem: function(file) {

                    var id;

                    // By id
                    if ($.isString(file)) id = file;

                    // By file object
                    if (file && file.id) id = file.id;

                    return self.items[id];
                },

                createItem: function(file) {

                    // Create item controller
                    var item =
                        self.view.uploadItem({file: file})
                            .addController(
                                "EasySocial.Controller.Albums.Uploader.Item",
                                {
                                    "{uploader}": self,
                                    file: file
                                }
                            );

                    // Add to item group
                    item.element
                        .prependTo(self.uploadItemGroup());

                    // Keep a copy of the item in our registry
                    self.items[file.id] = item;

                    self.setLayout();

                    self.trigger("QueueCreated", [item]);

                    return item;
                },

                settings: function(key, val) {

                    var settings = self.plupload.settings;

                    // Setter
                    if (val!==undefined) {
                        settings[key] = val;
                    }

                    // Getter
                    return (key) ? settings[key] : settings;
                },

                start: function() {

                    return self.plupload.start();
                },

                stop: function() {

                    return self.plupload.stop();
                },                

                "{self} FilesAdded": function(el, event, uploader, files) {

                    // Wrap the entire body in a try...catch scope to prevent
                    // browser from trying to redirect and load the file if anything goes wrong here.
                    try {

                        // Reverse upload ordering as we are prepending.
                        files.reverse();

                        $.each(files, function(i, file) {

                            // The item may have been created before, e.g.
                            // when plupload error event gets triggered first.
                            if (self.getItem(file)) return;

                            self.createItem(file);
                        });

                    } catch (e) {

                        console.error(e);
                    };
                },

                "{self} BeforeUpload": function(el, event, uploader, file) {

                    var item = self.getItem(file);
                    if (!item) return;

                    item.setState("preparing");
                },

                "{self} UploadFile": function(el, event, uploader, file) {

                    var item = self.getItem(file);
                    if (!item) return;

                    item.setState("uploading");
                },

                "{self} UploadProgress": function(el, event, uploader, file) {

                    var item = self.getItem(file);
                    if (!item) return;

                    item.setState("uploading");
                    item.setProgress();
                },

                "{self} FileUploaded": function(el, event, uploader, file, response) {

                    var item = self.getItem(file);
                    if (!item) return;

                    // If the response is not a valid object
                    if (!$.isPlainObject(response)) {

                        // Set upload item state to failed.
                        item.setState("failed");
                        return;
                    }

                    item.setState("done");
                },

                "{self} FileError": function(el, event, uploader, file, response) {

                    var item = self.getItem(file);

                    // If the item hasn't been created, create first.
                    if (!item) item = self.createItem(file);

                    item.setState("failed");
                    item.setMessage(response.message);
                },

                "{self} Error": function(el, event, uploader, error) {

                    // If the returned error object also returns a file object
                    if (error.file) {

                        // Check if the upload item has been created
                        var file = error.file,
                            item = self.getItem(file);

                        // If the upload item doesn't exist
                        if (!item) item = self.createItem(file);

                        item.setState("failed");
                        item.setMessage(error.message);
                    }
                },

                removeItem: function(id) {

                    var item = self.getItem(id);
                    if (!item) return;

                    // Remove item
                    self.plupload.removeFile(item.file());
                    item.element.remove();
                    delete self.items[id];

                    self.setLayout();
                },

                clear: function(id) {

                    $.each(self.items, function(id, item){

                        // Remove item
                        self.plupload.removeFile(item.file());
                        item.element.remove();
                        delete self.items[id];
                    });

                    self.items = {};
                }

        	}}

        );
        // controller: end

    module.resolve();

    });
    // require: end

});
// module: end

EasySocial.module("albums/uploader.item", function($) {

	var module = this;

	EasySocial.Controller("Albums.Uploader.Item",

	    {
	        defaultOptions: {
	        	"{status}"       : ".upload-status",
	            "{filename}"     : ".upload-filename",
	            "{progressBar}"  : ".upload-progress-bar",
	            "{percentage}"   : ".upload-percentage",
	            "{filesizeTotal}": ".upload-filesize-total",
	            "{filesizeLeft}" : ".upload-filesize-left",
	            "{details}"      : ".upload-details",
	            "{detailsButton}": ".upload-details-button",
	            "{removeButton}" : ".upload-remove-button",
	            "{message}"      : ".upload-message"
	        }
	    },

		// Instance properties
		function(self) { return {

			init: function() {

				self.id = self.element.attr("id");

				var file = self.file();

				// Set filename
				self.filename().html(file.name);

				// Set state
				self.setState("pending");

				// Set progress & filesize
				self.setProgress();

				var html4 = self.uploader.plupload.runtime=="html4";

				if ($.IE < 10 || html4) {
					// So upload item will display with indefinite progressbar
					self.element.addClass("indefinite-progress");
				}

				if (html4) {
					self.element.addClass("no-filesize");
				}
			},

	        file: function() {

	            var file = self.uploader.plupload.getFile(self.id) || self.options.file;

	            if (file) {
	            	var noFilesize = (file.size===undefined || file.size=="N/A");
	            	file.percentage = file.percent + "%";
	                file.filesize   = (noFilesize) ? "" : $.plupload.formatSize(file.size);
	                file.remaining  = (noFilesize) ? "" : $.plupload.formatSize(file.size - (file.loaded || 0));
	            }

	            return file;
	        },

	        setProgress: function() {

				var file = self.file();

				// Progress bar width
				self.progressBar()
					.width(file.percentage);

				// Progress bar percentage
				self.percentage()
					.html(file.percentage);

				// Total filesize
				self.filesizeTotal()
					.html(file.filesize);

				// Remaining filesize
				self.filesizeLeft()
					.html(file.remaining);
	        },

	        setState: function(state) {

				self.element
					.removeClass("pending preparing uploading failed done")
					.addClass(state);

				self.state = state;
	        },

			setMessage: function(message) {

			   	self.detailsButton()
			   		.attr("data-popbox", message);
			},

			"{removeButton} click": function(el, event) {

			    self.uploader.removeItem(self.id);
			}

	    }}
	);

	module.resolve();

});

EasySocial.module("albums/item", function($){

	var module = this;

	// Non-essential dependencies
	EasySocial.require()
		.script("albums/editor")
		.done();

	// Essential dependencies
	EasySocial.require()
		.library(
			"masonry"
		)
		.done(function(){

			EasySocial.Controller("Albums.Item",
			{
				hostname: "album",

				defaultOptions: {

					tilesPerRow: 4,
					editable: false,
					multipleSelection: false,

					"{header}"        : "[data-album-header]",
					"{content}"       : "[data-album-content]",
					"{footer}"        : "[data-album-footer]",

					"{info}"          : "[data-album-info]",

					"{title}"         : "[data-album-title]",
					"{caption}"       : "[data-album-caption]",
					"{location}"      : "[data-album-location]",
					"{date}"          : "[data-album-date]",
					"{cover}"         : "[data-album-cover]",

					"{photoItemGroup}": "[data-photo-item-group]",
					"{photoItem}"     : "[data-photo-item]",
					"{photoImage}"    : "[data-photo-image]",
					"{featuredItem}"  : "[data-photo-item].featured",
					"{featuredImage}" : "[data-photo-item].featured [data-photo-image]",
					"{uploadItem}"    : "[data-photo-upload-item]",

					"{moreButton}"    : "[data-album-more-button]",
					"{viewButton}"    : "[data-album-view-button]",

					"{share}"			  : "[data-repost-action]",
					"{likes}"			  : "[data-likes-action]",
					"{likeContent}" 	  : "[data-likes-content]",
					"{repostContent}" 	  : "[data-repost-content]",
					"{counterBar}"	  	  : "[data-stream-counter]"
				}
			},
			function(self) { return {

				init: function()
				{
					self.id = self.element.data("album-id");

					self.nextStart = self.element.data("album-nextstart") || -1;

					// If this viewer is editable, load & implement editor.
					if (self.options.editable)
					{
						EasySocial.module("albums/editor")
							.done(function(EditorController)
							{
								self.editor = self.addPlugin("editor", EditorController);
							});
					}

					// Set layout when window is resized
					self.setLayout();

					// Let setLayout sink in first.
					setTimeout(function(){
						self.content().addClass("ready");
					}, 1);

					// Attach existing photo items as subscribers
					self.addSubscriber(
						self.photoItem()
							.controllers("EasySocial.Controller.Photos.Item")
					);
				},

				"{window} resize": $.debounce(function(){
					self.setLayout();
				}, 250),

				currentLayout: function() {

					return self.element.data("albumLayout");
				},

				setLayout_: $.debounce(function(){

					self.setLayout();
				}, 100),

				setLayout: function(layoutName) {

					var photoItemGroup = self.photoItemGroup(),

						// Build layout state
						currentLayout = self.currentLayout(),
						layoutName    = layoutName || currentLayout,
						seed          = self.setLayout.seed,
						intact        = (seed == photoItemGroup.width() && currentLayout==layoutName)
						hasPhotoItem  = self.photoItem().length > 0,
						hasUploadItem = self.uploadItem().length > 0,
						hasItem       = hasPhotoItem || hasUploadItem,
						masonry       = photoItemGroup.data("masonry"),

						// Put them in an object
						layout = {
							currentLayout: currentLayout,
							seed         : seed,
							intact       : intact,
							hasPhotoItem : hasPhotoItem,
							hasUploadItem: hasUploadItem,
							hasItem      : hasItem,
							masonry      : masonry
						};

					// Determine if we need to switch layout
					if (!intact) {

						// Switch layout
						self.element
							.data("albumLayout", layoutName)
							.switchClass("layout-" + layoutName);

						// Switch all photo item's layout
						self.photoItem()
							.switchClass("layout-" + layoutName);

						// Reset viewport width to force layout redraw
						self.setLayout.seed = layout.seed = null;

						// Trigger layout change event
						self.trigger("layoutChange", [layoutName, layout]);
					}

					// Show upload hint when content is empty
					self.element.toggleClass("has-photos", hasItem);

					// If there's no item from the list
					if (!hasItem) {

						// If this is coming from deleting the last item
						// from the list, we need to keep the container
						// on zero height.
						photoItemGroup.css("opacity", 1);
					}

					// Execute layout handler
					var layoutHandler = "set" + $.String.capitalize(layoutName) + "Layout";
					self[layoutHandler](layout);

					// Save current layout
					self.setLayout.seed = photoItemGroup.width();
				},

				setItemLayout: function(layout) {

					if (layout.intact) {

						// Just reload masonry
						layout.masonry && layout.masonry.reload();

					// Else recalculate sizes
					} else {

						var tilesPerRow = 4;

						// Override tilesPerRow depending on mobile sizes
						if ($("#es-wrap").hasClass("w600")) {
							tilesPerRow = 2;
						}

						// Override tilesPerRow depending on mobile sizes
						if ($("#es-wrap").hasClass("w320")) {
							tilesPerRow = 1;
						}

						// Get photoItemGroup
						var photoItemGroup 		= self.photoItemGroup(),
							photoItem      		= self.photoItem(),
							tilesPerRow         = tilesPerRow,

							viewportWidth       = photoItemGroup.width(),
							containerWidth      = Math.floor(viewportWidth / tilesPerRow) * tilesPerRow,
							tileWidth           = containerWidth / tilesPerRow,
							tileWidthOffset     = photoItem.outerWidth(true) - photoItem.width(),
							tileHeight          = tileWidth,
							tileHeightOffset    = photoItem.outerHeight(true) - photoItem.height(),

							itemWidth           = tileWidth  - tileWidthOffset,
							itemHeight          = tileHeight - tileHeightOffset,
							imageWidth          = itemWidth,
							imageHeight         = itemHeight;

							if (tilesPerRow >= 4) {
								var featuredItemWidth   = (tileWidth  * 2) - tileWidthOffset;
								var featuredItemHeight  = (tileHeight * 2) - tileHeightOffset;
							} else {
								var featuredItemWidth   = itemWidth;
								var featuredItemHeight  = itemHeight;
							}

							var featuredImageWidth  = featuredItemWidth;
							var featuredImageHeight = featuredItemHeight;							

						self.photoItem
							.css({
								width : itemWidth,
								height: itemHeight
							});

						self.photoImage
							.css({
								width : imageWidth,
								height: imageHeight
							});

						self.featuredItem
							.css({
								width : featuredItemWidth,
								height: featuredItemHeight
							});

						self.featuredImage
							.css({
								width : featuredImageWidth,
								height: featuredImageHeight
							});

						self.uploadItem
							.css({
								width : itemWidth,
								height: itemHeight
							});

						photoItemGroup
							.masonry({
								columnWidth: tileWidth
							});

						setTimeout(function(){
							photoItemGroup
								.addTransitionClass("no-transition")
								.masonry("reload");
						}, 1);							
					}
				},

				setFormLayout: function(layout) {

					// Destroy masonry if we are on form layout
					layout.masonry && layout.masonry.destroy();

					// Reset layout
					self.clearLayout();
				},

				setDialogLayout: function() {

					// Destroy masonry if we are on form layout
					layout.masonry && layout.masonry.destroy();

					// Reset layout
					self.clearLayout();
				},

				setThumbnailLayout: function() {

				},

				setRowLayout: function() {

					self.clearLayout();
				},

				clearLayout: function() {

					self.photoItemGroup()
						.addClass("no-transition");
						
					self.photoItem
						.css().remove();

					self.photoImage
						.css().remove();

					self.featuredItem
						.css().remove();

					self.featuredImage
						.css().remove();

					self.uploadItem
						.css().remove();

					self.setLayout.seed = null;
				},

				getSelectedItems: function() {

					var selectedPhotos = self.photoItem(".selected");

					var data = [];

					selectedPhotos.each(function(i, photo){
						data.push($(photo).controller("EasySocial.Controller.Photos.Item").data());
					});

					return data;
				},

				"{photoItem} init.photos.item": function(el, event, photoItem) {

					self.addSubscriber(photoItem);
				},

				"{photoItem} destroyed": function() {

					self.setLayout();
				},

				"{photoItem} activate": function(photoItem, event, photo) {

					// Activate is a non-standard IE event,
					// if photo is undefined then it is coming
					// from the browser not photo item controller.
					if (!photo) return;

					var currentLayout = self.currentLayout();

					switch (currentLayout) {

						case "item":
						case "row":

							// Show loading indicator
							photoItem.addClass("loading");

							// If browser is available, ask browser
							// to load photo view via ajax.
							if (self.browser) {

								// View photo
								self.browser
									.open("photo", photo.id)
									.always(function(){

										// Remove loading indicator
										photoItem.removeClass("loading");
									});

								// Change address bar url
								photo.imageLink().route();

							// If browser is not available,
							// just load the photo view normally.
							} else {
								window.location = photo.imageLink().attr("href");
							}
							break;

						case "form":
							// photo.editor && photo.editor.enable();
							break;

						case "dialog":

							var selectedPhotos = self.photoItem(".selected");

							if (!self.options.multipleSelection) {

								var selected = photoItem.hasClass("selected");

								// In case it came from multiple selection
								selectedPhotos.removeClass("selected");

								photoItem.toggleClass("selected", !selected);

							} else {

								photoItem.toggleClass("selected");
							}
							break;
					}
				},

				"{photoItem} photoFeature": function(el, event, task, photo, featured) {

					// Set layout to accomodate double size photo item
					self.setLayout();

					// When a photo fail to be featured, it shrinks
					task
						.fail(function(){

							// So we're resetting layout again
							self.setLayout();
						});
				},

				"{photoItem} photoMove": function(el, event, task, photo, targetAlbumId) {

					self.clearMessage();

					task
						.done(function(){

							// Remove photo
							photo.element.remove();

							// Set layout
							self.setLayout();

							// If there are no more photos, remove cover
							if (self.photoItem().length < 1) {
								self.trigger("coverRemove", [self]);
							}							
						})
						.fail(function(message, type){
							self.setMessage(message, type);
						});
				},

				"{photoItem} photoDelete": function(el, event, task, photo) {

					self.clearMessage();

					task
						.done(function(){

							// Remove photo
							photo.element.remove();

							// Set layout
							self.setLayout();

							// If there are no more photos, remove cover
							if (self.photoItem().length < 1) {
								self.trigger("coverRemove", [self]);
							}
						})
						.fail(function(message, type){
							self.setMessage(message, type);
						});
				},

				// These are coming from album editor
				"{self} albumSave": function(el, event, task) {

					task.done(function(album){
						self.id = album.id;
					});
				},

				"{self} coverChange": function(el, event, photo, album) {

					self.cover()
						.css("backgroundImage", $.cssUrl(photo.sizes.thumbnail.url));
				},

				"{self} coverRemove": function() {

					self.cover()
						.css("backgroundImage", "");
				},

				"{viewButton} click": function(viewButton, event) {
					if (self.browser)
					{
						event.preventDefault();
						self.element.addClass("loading");
						self.browser.open("Album", self.id);
					}
				},

				"{moreButton} click": function(moreButton) {

					// If nextStart is -1, means no more photos
					if (self.nextStart == -1) {
						return;
					}

					if (moreButton.disabled()) return;

					// Disable this button
					moreButton.disabled(true);

					// Set the button into loading state
					// moreButton.addClass('loading');

					// Get the new photos content
					EasySocial.ajax(
						"site/controllers/albums/loadMore",
						{
							albumId: self.id,
							start: self.nextStart,
							layout: self.currentLayout()
						})
						.done(function(htmls, nextStart) {

							self.nextStart = nextStart;

							var photoItemGroup = self.photoItemGroup();

							$.each(htmls, function(i, html){
								$.buildHTML(html).appendTo(photoItemGroup);
							});							

							// If there is no more photos to load, hide the button
							if (nextStart < 0) moreButton.hide();

							self.setLayout();
						})
						.always(function(){

							moreButton.disabled(false);
						});
				},

                "{share} create": function(el, event, itemHTML) {
                	self.counterBar().removeClass('hide');
                },

 				"{likes} onLiked": function(el, event, data) {

					//need to make the data-stream-counter visible
					self.counterBar().removeClass( 'hide' );
				},

				"{likes} onUnliked": function(el, event, data) {

					var isLikeHide 		= self.likeContent().hasClass('hide');
					var isRepostHide 	= self.repostContent().hasClass('hide');

					if( isLikeHide && isRepostHide )
					{
						self.counterBar().addClass( 'hide' );
					}
				}

			}});

			module.resolve();
		});
});

EasySocial.require()
	.script(
		"site/likes/likes",
		"site/reports/reports",
		"site/repost/repost",
		"site/share/share",
		"site/layout/dialog",
		"site/layout/responsive",
		"site/layout/elements",
		"site/photos/photos",
		"site/users/login",
		"site/profile/popbox",
		"site/privacy/privacy",
		"site/locations/popbox",
		"site/sidebar/sidebar",
		"site/friends/api",
		"site/conversations/api",
		"site/followers/api",
		"site/popbox/popbox"
	)
	.done();

EasySocial.module('site/likes/likes', function($){

	var module = this;

	$(document)
		.on("click.es.likes.action", "[data-likes-action]", function(){

			var button = $(this),
				data = {
					id   : button.data("id"),
					type : button.data("type"),
					group: button.data("group")
				},
				key = data.type + "-" + data.group + "-" + data.id;

			EasySocial.ajax("site/controllers/likes/toggle", data)
				.done(function(content, label, showOrHide, verb, count) {

					// Update like label
					button.text(label);

					// Update like content
					$("[data-likes-" + key + "]")
						.html(content)
						.toggleClass("hide", showOrHide)
						.toggle(!showOrHide);

					// Furnish data with like count
					data.uid   = data.id; // inconsistency
					data.count = count;

					// verb = like/unlike
					button.trigger((verb=="like") ? "onLiked" : "onUnliked", [data]);
				})
				.fail(function(message) {

					console.log(message);
				});
		})
		.on("click.es.likes.others", "[data-likes-others]", function(){

			var button = $(this),
				content = button.parents("[data-likes-content]"),
				data = {
					uid    : content.data("id"),
					type   : content.data("type"),
					exclude: button.data("authors")
				};

			EasySocial.dialog({
				content: EasySocial.ajax("site/controllers/likes/showOthers", data)
			});
		});

	module.resolve();
});

EasySocial.module("site/reports/reports", function($) {

	$(document).on("click.es.reports.link", "[data-reports-link]", function(){

		var button = $(this),
			props  = "url,extension,uid,type,object,title,description".split(","),
			data   = {};

		$.each(props, function(i, prop){
			data[prop] = button.data(prop);
		});
		
		EasySocial.dialog({

			content: EasySocial.ajax(
				"site/views/reports/confirmReport",
				{
					title: data.title,
					description: data.description
				}),

			selectors: {
				"{message}"     : "[data-reports-message]",
				"{reportButton}": "[data-report-button]",
				"{cancelButton}": "[data-cancel-button]"
			},

			bindings: {

				"{reportButton} click": function() {

					var message	= this.message().val();

					EasySocial.dialog({
						content: EasySocial.ajax(
							"site/controllers/reports/store",
							{
								url      : data.url,
								extension: data.extension,
								uid      : data.uid,
								type     : data.type,
								title    : data.object,
								message  : message
							})
					});
				},

				"{cancelButton} click": function() {
					EasySocial.dialog().close();
				}		
			}	
		});
	});

	this.resolve();

});

EasySocial.module("site/repost/repost", function($){

	$(document)
		.on("click.es.repost.action", "[data-repost-action]", function(){

			var button = $(this),
				data = {
					id     : button.data('id'),
					element: button.data('element'),
					group  : button.data('group')
				},
				key = data.element + '-' + data.group + '-' + data.id;

			EasySocial.dialog({
				content: EasySocial.ajax("site/views/repost/form", data),
				bindings:
				{
					"{sendButton} click": function(sendButton)
					{
						var dialog = this.parent,
							content = $.trim(this.repostContent().val());

						// Add data content
						data.content = content;

						dialog.loading( true );

						EasySocial.ajax("site/controllers/repost/share", data )
							.done(function(content, isHidden, count, streamHTML)
							{
								var content = $.buildHTML(content);

								actionContent = 
									$('[data-repost-' + key + ']')
										.toggleClass("hide", isHidden)
										.toggle(!isHidden);

								actionContent.find("span.repost-counter")
									.html(content);

								button.trigger("create", [streamHTML]);
							})
							.fail(function(message)
							{
								dialog.clearMessage();
								dialog.setMessage( message );
							})
							.always(function()
							{
								dialog.loading( false );
								dialog.close();
							});
					}
				}
			});
		});

	EasySocial.module("repost/authors", function(){

		this.resolve(function(popbox){

			var repost = popbox.button.parents("[data-repost-content]")
				data = {
					id     : repost.data("id"),
					element: repost.data("element")
				};

			return {
				content: EasySocial.ajax('site/controllers/repost/getSharers', data),
				id: "es-wrap",
				type: "repost",
				position: "bottom-right"
			}
		});
	});

	this.resolve();
});

EasySocial.module("site/share/share", function($){

	$(document)
		.on("click.es.share.button", "[data-es-share-button]", function(){

			var button = $(this);

			EasySocial.dialog({
				title: button.text(),
				content:
					EasySocial.ajax(
						"site/views/sharing/shareDialog",
						{
							url: button.data("url"),
							title: button.data("title")
						})
			});
		});

	this.resolve();
});
EasySocial.module( 'site/layout/dialog' , function($){

	var module = this;

	// Dialog
	EasySocial.require()
		.library('dialog')
		.view('site/dialog/default')
		.done(function($){

			EasySocial.dialog = function(options) {

				// TODO: Isolate this from global dialog
				if (window.parentDialog) {
					return window.parentDialog.update(options);
				}

				// Normalize arguments
				if (typeof options === "string" || $.isDeferred(options)) {
					var afterShow = arguments[1];
					options = {
						content: options,
						afterShow: ($.isFunction(afterShow)) ? afterShow : $.noop
					}
				}

				var dialogElement = $('#es-wrap.es-dialog.global');

				if (dialogElement.length < 1) {

					dialogElement =
						$(EasySocial.View('site/dialog/default'))
							.addClass('global')
							.appendTo('body');
				};

				var defaultOptions = {
						showOverlay: false
					},
					options = $.extend(defaultOptions, options);

				var dialogController = dialogElement.controller("Dialog");

				if (!dialogController) {
					dialogController = dialogElement.addController("Dialog", options);
				} else {
					dialogController.update(options);
				}

				return dialogController;
			}

			module.resolve();
		});
});

EasySocial.module('site/layout/responsive', function($){

	var module = this;

	$(function(){
		$('.es-responsive')
			.responsive([
				{at: 1200, switchTo: 'wide'},
				{at: 960,  switchTo: 'wide w960'},
				{at: 818,  switchTo: 'wide w960 w768'},
				{at: 600,  switchTo: 'wide w960 w768 w600'},
				{at: 560,  switchTo: 'wide w960 w768 w600 w480'},
				{at: 480,  switchTo: 'wide w960 w768 w600 w480 w320'}
			]);
	});

	module.resolve();

});

EasySocial.module('site/layout/elements', function($){

	var module = this;

	// Initialize yes/no buttons.
	$(document).on( 'click.button.data-fd-api', '[data-fd-toggle-value]', function() {

		var parent = $(this).parents('[data-foundry-toggle="buttons-radio"]');

		if(parent.hasClass('disabled')) {
			return;
		}

		// This means that this toggle value belongs to a radio button
		if (parent.length > 0) {

			// Get the current button that's clicked.
			var value = $(this).data( 'fd-toggle-value' );

			// Set the value here.
			// Have to manually trigger the change event on the input
			parent.find( 'input[type=hidden]' ).val( value ).trigger('change');
			return;
		}
	});


	// Tooltips
	// TODO: Update to [data-es-provide=tooltip]
	$(document).on('mouseover.tooltip.data-es-api', '[data-es-provide=tooltip]', function() {

		$(this)
			.tooltip({
				delay: {
					show: 200,
					hide: 100
				},
				animation: false,
				template: '<div class="tooltip tooltip-es"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',
				container: 'body'
			})
			.tooltip("show");
	});

	// Popovers
	// TODO: Update to [data-es-provide=popover]
	$(document).on('mouseover.popover.data-es-api', '[data-es-provide=popover]', function() {
		$(this)
			.popover({
				delay: {
					show: 200,
					hide: 100
				},
				animation: false,
				trigger: 'hover',
				container: 'body'
			})
			.popover("show");
	});


	var ly = function(yr) { return (yr%400)?((yr%100)?((yr%4)?false:true):false):true; };

	$(document).on("keyup", "[data-date-form] [data-date-day]", function(){

		if (!$.trim($(this).val())) return;

		var year   = parseInt($(this).siblings("[data-date-year]").val()  || $(this).siblings("[data-date-year]").data("dateDefault")),

		    month  = parseInt($(this).siblings("[data-date-month]").val() || $(this).siblings("[data-date-month]").data("dateDefault")),

		    day    = parseInt($(this).val() || $(this).data("dateDefault")),

			maxDay = /1|3|5|7|8|10|12/.test(month) ? 31 : 30;

			if (month==2) maxDay = ly(year) ? 29 : 28;

			if (day < 1) day = 1;

			if (day > maxDay) day = maxDay;

			if ($.isNumeric(day)) {
				$(this).val(day);
			} else {
				$(this).val("");
			}
	});

	$(document).on("keyup", "[data-date-form] [data-date-year]", function(){

		if (!$.trim($(this).val())) return;

		var year = parseInt($(this).val());
		if (year < 1) year = 1;

		if ($.isNumeric(year)) {
			$(this).val(year);
		} else {
			$(this).val("");
		}
	});

	module.resolve();

});

EasySocial.module('site/photos/photos', function($){

	var module = this;

	EasySocial.require()
		.script("photos")
		.done(function(){

			EasySocial.photos = $("body").addController("EasySocial.Controller.Photos");

			module.resolve();
		});
});

EasySocial.module("photos", function($){

	var module = this;

	EasySocial.require()
		.script("photos/viewer")
		.done(function(){

			EasySocial.Controller("Photos",
			{
				defaultOptions: {

					view: {
						popup:  "site/photos/popup",
						viewer: "site/photos/popup.viewer"
					},

					"{photo}"       : "[data-es-photo]",

					"{popup}"       : "[data-photo-popup]",
					"{viewer}"      : "[data-photo-viewer]",
					"{closeButton}" : "[data-popup-close-button]"
				},

				// Schema for photo object
				photo: {
					data   : {},   // Exported data from photo table
					image  : {},   // Holds detached image element. image[variation] = element.
					content: null, // Detached html elment containing photo content
					tags   : []    // Array of tag objects
				}
			},
			function(self) { return {

				init: function() {

					// Popup frame
					// self.popupElement = self.view.popup();
				},

				createAvatar: function(id, options) {

					if (id===undefined) return;

					if (!options) options = {};

					var avatarOptions = {id: id};

					if (options.redirect) {
						avatarOptions.redirect = options.redirect;
						delete options.redirect;
					}

					if (options.uid) {
						avatarOptions.uid = options.uid;
						delete options.uid;
					}

					EasySocial.dialog(
						$.extend({
					    	content: EasySocial.ajax("site/views/photos/avatar", avatarOptions)
						}, options)
					);
				},

				selectPhoto: function(options) {

					var task = $.Deferred(),
						dialog = EasySocial.ajax("site/views/albums/dialog"),
						browser = EasySocial.require().script("albums/browser").done();

					// Show a loading indicator first
					EasySocial.dialog(
						$.extend({
						    content: task
						}, options)
					);

					$.when(browser, dialog)
						.done(function(){
							dialog.done(function(html){
								task.resolve(html);
							});
						});
				},

				display: function(photoId, playlist, options) {

					var photoId = photoId + '', // Ensure it is string
						popup = self.popup(),
						viewer = self.viewer();

					// Remove existing viewer.
					// The viewer will listen to the destroyed event
					// and detach all photo content & image elements.
					viewer.remove();

					// Show popup
					self.show();

					// Create a new viewer
					self.currentViewer =
						self.view.viewer()
							.appendTo(popup)
							.addController(
								"EasySocial.Controller.Photos.Viewer",
								$.extend({
									mode: "popup",
									initialPhoto: photoId,
									playlist: playlist
								}, options)
							);
				},

				// Stores array of exported album table
				albums: {},

				albumLoaders: {},

				getAlbum: function(albumId, reload) {

					var albumId = albumId + '', // Ensure it is string
						loaders = self.albumLoaders,
						loader  = loaders[albumId];

					if (reload || !loader || loader.state()=="failed") {

						loader = loaders[albumId] =
							EasySocial.ajax(
								"site/controllers/albums/getAlbum",
								{
									id: albumId
								}
							)
							.done(function(album) {
								self.albums[albumId] = album;
							});
					}

					return loader.promise();
				},

				// Stores array of exported photo table
				photos: {},

				photoLoaders: {},

				getPhoto: function(photoId, reload) {

					var photoId = photoId + '', // Ensure it is string
						loaders = self.photoLoaders,
						loader  = loaders[photoId];

					if (reload || !loader || loader.state()=="failed") {

						loader = loaders[photoId] =
							EasySocial.ajax(
								"site/controllers/photos/getPhoto",
								{
									id: photoId,
									attr: ["content", "tags"]
								}
							)
							.done(function(photo){

								self.photos[photoId] = photo;

								var content = photo.content;
									photo.content = {};

								$.each(content, function(mode, content) {
									photo.content[mode] = $($.trim(content)).appendTo(self.element).detach();
								});
							});
					}

					return loader.promise();
				},

				images: {},

				imageLoaders: {},

				getImage: function(photoId, size, reload) {

					var photoId     = photoId + '', // Ensure it is string
						loaders     = self.imageLoaders,
						loaderSizes = loaders[photoId] || (loaders[photoId] = {}),
						loader      = loaderSizes[size];

					if (reload || !loader || loader.state()=="failed") {

						loader = loaderSizes[size] = $.Deferred();

						self.getPhoto(photoId)
							.done(function(photo) {

								var url = photo.sizes[size].url;

								$.Image.get(url)
									.done(function(image){

										var images     = self.images,
											imageSizes = images[photoId] || (images[photoId] = {});
											imageSizes[size] = image;

										loader.resolve(image);
									})
									.fail(loader.reject);
							})
							.fail(loader.reject);
					}

					return loader.promise();
				},

				show: function() {

					self.popupElement
						.appendTo("body")
						.addClass("active")
						.trigger("show");
				},

				hide: function() {

					self.popupElement
						.removeClass("active")
						.trigger("hide")
						.detach();
				},

				"{self} click": function(el, event) {
					if (event.target===self.popup()[0]) {
						self.hide();
					}
				},

				// Album playlist
				//
				// <div data-es-album="4">
				//     <a data-es-photo="499">
				// </div>

				// Element-based playlist
				//
				// <div data-es-photos>
				//     <a data-es-photo="1">
				//     <a data-es-photo="2">
				//     <a data-es-photo="3">
				// </div>

				// Custom playlist
				// Ideal for large playlist where not all items are shown.
				//
				// <div data-es-photos="400,401,402,403,405,406,407,408">
				//     <a data-es-photo="400">
				//     <a data-es-photo="401">
				//     <a data-es-photo="402">
				//     <a data-es-photo="403">
				//     <a data-es-photo="404">
				//     <!-- The rest of the thumbnails not shown, but the popup will have it. -->
				// </div>

				"{photo} click": function(photo, event) {

					// Get photo id
					var photoId = photo.data("es-photo");

					// Album playlist
					var album = photo.parents("[data-es-album]");
					if (album.length > 0) {
						var albumId = album.data("es-album");
						self.display(photoId, albumId);
						return event.preventDefault();
					}

					// Custom playlist
					var photos = photo.parents("[data-es-photos]");

					if (photos.length > 0) {

						// This is for photo containers with unfinished photo items
						// <div data-es-photos="34,35,36,37">
						var playlist = photos.data("es-photos");

						if (playlist!=="" && playlist!==undefined) {
							playlist = playlist.split(",");
							self.display(photoId, playlist);
							return event.preventDefault();
						}

						// This is for photos containers with element-based playlist
						playlist = [];
						photos.find("[data-es-photo]").each(function(i, e){
							playlist.push($(this).data("es-photo"));
						});
						self.display(photoId, playlist);
						return event.preventDefault();
					}
				},

				"{closeButton} click": function() {
					self.hide();
				},

				like: function(photoId) {

					return EasySocial.ajax(
						"site/controllers/photos/like",
						{
							id: photoId
						}
					);
				}

			}});

			module.resolve();
		});
});

EasySocial.module("photos/viewer", function($){

	var module = this;

	// Non-essential dependencies
	EasySocial.require()
		.script(
			"photos/tagger",
			"photos/avatar"
		);

	EasySocial.require()
		.library(
			"image",
			"tinyscrollbar"
		)
		.done(function(){

			EasySocial.Controller("Photos.Viewer",
			{
				defaultOptions: {

					view: {
					},

					"{title}"             : "[data-photo-title]",
					"{caption}"           : "[data-photo-caption]",
					"{date}"              : "[data-photo-date]",
					"{viewport}"          : "[data-photo-viewport]",
					"{image}"             : "[data-photo-image]",
					"{photoItem}"         : "[data-photo-item]",

					"{nextButton}"        : "[data-photo-next-button]",
					"{prevButton}"        : "[data-photo-prev-button]",

					"{thumbnailsHolder}"  : "[data-photo-thumbnails-holder]",

					"{contentHolder}"     : "[data-photo-content-holder]",
					"{content}"           : "[data-photo-content]",
					"{contentViewport}"   : "[data-photo-content-viewport]",

					"{comments}"          : "[data-comments]",
					"{commentInput}"      : "[data-comments-form-input]",

					"{likeButton}"        : "[data-photo-like-button]",
					"{commentButton}"     : "[data-photo-comment-button]",

					"{likesHolder}"       : "[data-photo-likes-holder]",
					"{commentsHolder}"    : "[data-photo-comments-holder]",

					size: "large",
					throttle: 250,
					mode: 'inline'
				}
			},
			function(self) { return {

				init: function() {

					if (self.options.mode=="inline") {
						self.initial = true;
					}

					self.update();

					// Add tagger plugin
					EasySocial.module("photos/tagger")
						.done(function(){
							self.tagger = self.addPlugin("tagger", EasySocial.Controller.Photos.Tagger);
						});

					// Add avatar plugin
					EasySocial.module("photos/avatar")
						.done(function(){
							self.avatar = self.addPlugin("avatar", EasySocial.Controller.Photos.Avatar);
						});
				},

				update: function() {

					var playlist     = self.playlist = self.options.playlist,
						initialPhoto = self.options.initialPhoto;

					// If an album id was given, get album
					if (!$.isArray(playlist)) {

						var albumId = playlist;

						EasySocial.photos.getAlbum(albumId)
							.done(function(album){

								self.options.playlist = $.map(album.photos, function(photo) {
									return photo.id + '';
								});

								self.update();
							});

						EasySocial.ajax(
							"site/views/photos/thumbnails",
							{
								albumId: albumId
							})
							.done(function(thumbnailsHtml){

								self.thumbnailsHolder()
									.html(thumbnailsHtml);
							});

						return;
					}

					// Get index of initial photo
					var i = $._.indexOf(playlist, initialPhoto);

					// Just in case the initial photo could not be found.
					if (i < 0) i = 0;

					self.displayItem(i);
				},

				"{window} resize": $._.debounce(function() {

					self.setLayout();
				}, 100),

				setLayout: function() {
					self.setContentLayout();
					self.setImageLayout();
				},

				setImageLayout: function() {

					var viewport = self.viewport(),
						image = self.image();

					image.css(
						$.Image.resizeWithin(
							image.data("width"),
							image.data("height"),
							viewport.width(),
							viewport.height()
						)
					);
				},

				setContentLayout: function() {

					if (self.options.mode!=="popup") return;

					self.contentViewport()
						.height(self.content().height());

					self.content()
						.tinyscrollbar_update();
				},

				getPhoto: function(i) {
					return EasySocial.photos.photos[self.playlist[i]];
				},

				getImage: function(i) {
					return (EasySocial.photos.images[self.playlist[i]] || {})[self.options.size];
				},

				currentItem: 0,

				currentId: null,

				gotoItem: function(n) {

					var playlist = self.playlist,
						max      = playlist.length - 1,
						i        = self.currentItem + n;

					if (i < 0)   i = max;
					if (i > max) i = 0;

					return i;
				},

				displayItem: $._.debounce(function(i) {
					self.currentItem = i;
					self.currentId = self.playlist[i];
					self.trigger("displayItem", [i]);
				}, 25),

				"{self} displayItem": function(el, event, i) {

					// Show loading indicator
					self.viewport().addClass("loading");

					self.renderContent(i);
					self.renderImage(i);
				},

				renderContent: function(i) {

					if (self.initial) return;

					// Detach any existing content
					self.content().detach();

					var photo = self.getPhoto(i),
						photoId = self.playlist[i];

					// If the photo exist
					if (photo) {

						var mode = self.options.mode;

						// Display content immediately
						self.contentHolder()
							.append(photo.content[mode]);

						if (mode=="popup") {
							// Set up tiny scrollbar on comments
							self.content()
								.tinyscrollbar();
						}

						self.setContentLayout();

						// Trigger renderContent event
						self.trigger("renderContent", [i]);

					} else {

						setTimeout(function(){

							// The current requested photo has changed, stop.
							// This happens when user is clicking next/prev button quickly.
							if (self.currentId!==photoId) return;

							EasySocial.photos.getPhoto(photoId)
								.done(function(photo) {
									self.renderContent(i);
								})
								.fail(function() {

									// TODO: Show error message
								});

						}, self.options.throttle);
					}
				},

				renderImage: function(i) {

					// Detach any existing image
					self.image().detach();

					var photo    = self.getPhoto(i),
						photoId  = self.playlist[i],
						image    = self.getImage(i),
						size     = self.options.size,
						viewport = self.viewport();

					if (image) {

						// Append image to viewport invisibly
						image
							.addClass("es-photo-image")
							.removeClass("active")
							.attr("data-photo-image", "")
							.prependTo(viewport);

						// Disable transition on intial photo
						if (self.initial) {
							image.removeClass("initial");
						}

						// Resize the image to fit within viewport
						self.setImageLayout();

						// Remove loading indicator
						viewport.removeClass("loading");

						// Show image.
						// It is placed in a setTimeout to ensure
						// transition queue is cleared up.
						setTimeout(function(){

							image.addClass("active").removeClass("initial");

							// Trigger renderImage event
							self.trigger("renderImage", [i, photo, image]);

							self.reset();

						}, 1);

					} else {

						setTimeout(function(){

							// The current requested photo has changed, stop.
							// This happens when user is clicking next/prev button quickly.
							if (self.currentId!==photoId) return;

							EasySocial.photos.getImage(photoId, size)
								.done(function(image){

									// To note that this is the first time
									// showing this image
									image.addClass("initial");

									self.renderImage(i);
								})
								.fail(function(){
									// TODO: Show error message
									// console.log("error loading image");
								})
								.always(function(){

									// Remove loading indicator
									viewport.removeClass("loading");
								});

						}, self.options.throttle);
					}
				},

				reset: function() {

					// Remove css inserted image (initial image)
					self.viewport()
						.removeAttr("style")
						.removeClass("no-transition");

					// And we're done for the initial silent cycle
					self.initial = false;
				},

				"{nextButton} click": function() {
					self.reset();
					self.displayItem(self.gotoItem(1));
				},

				"{prevButton} click": function() {
					self.reset();
					self.displayItem(self.gotoItem(-1));
				},

				"{comments} newCommentSaved": function() {

					if (self.options.mode!=="popup") return;

					self.content()
						.tinyscrollbar_update("bottom");
				},

				"{comments} commentDeleted": function() {

					if (self.options.mode!=="popup") return;

					self.content()
						.tinyscrollbar_update();
				},

				"{comments} oldCommentsLoaded": function() {

					if (self.options.mode!=="popup") return;

					self.content()
						.tinyscrollbar_update();
				},

				"{commentButton} click": function() {

					if (self.options.mode=="popup") {
						self.content()
							.tinyscrollbar_update("bottom");
					}

					self.commentInput()
						.focus();
				},

				"{likeButton} click": function() {

					EasySocial.photos.like(self.currentId)
						.done(function(like) {

							// self.likeCount()
							// 	.html(like.count);

							self.likeButton()
								.toggleClass("liked", like.state);

							self.likesHolder()
								.html(like.html);
						});
				}

			}});

			module.resolve();

		});
});

EasySocial.module('site/users/login', function($){

	var module = this;

	EasySocial.require()
		.library('dialog')
		.done(function(){

			EasySocial.login = function() {
				EasySocial.dialog({
					'content': EasySocial.ajax( 'site/views/login/form' , {})
				});
			}

			module.resolve();
		});
});
EasySocial.module("site/profile/popbox", function($) {

	var module = this;

	EasySocial.require()
		.library("popbox")
		.done(function(){

			EasySocial.module("profile/popbox", function($) {

				this.resolve(function(popbox){

					var id = popbox.button.data("userId"),
						position = popbox.button.attr("data-popbox-position") || "top-left";

					return {
						content: EasySocial.ajax("site/views/profile/popbox", {id: id}),
						id: "es-wrap",
						type: "profile",
						position: position
					}
				})
			});

		});

	// Non-essential dependency
	EasySocial.require()
		.script("site/conversations/composer")
		.done();

	EasySocial.Controller("Profile.Popbox",
	{
		defaultOptions: {
			// The current user being viewed
			id: null,

			"{addButton}"	    : "[data-popbox-friends-add]",
			"{friendsButton}"	: "[data-popbox-friends-friends]",
			"{respondButton}"	: "[data-popbox-friends-respond]",
			"{requestedButton}"	: "[data-popbox-friends-requested]",
			"{messageButton}"	: "[data-popbox-message]",
			"{friendsSubmenu}"	: "[data-friends-submenu]",

			"{cancelFriend}"	: "[data-popbox-friends-friends-cancel]",
			"{cancelFriendRequest}" : "[data-popbox-friends-requested-cancel]",
			"{approveFriend}"	: "[data-popbox-friends-respond-approve]",
			"{rejectFriend}"	: "[data-popbox-friends-respond-reject]"
		}
	},
	function(self) { return {

		init: function() {

			self.options.id = self.element.find("[data-user-id]").data("userId");

			EasySocial.module("site/conversations/composer")
				.done(function(){
					self.messageButton()
						.implement( EasySocial.Controller.Conversations.Composer.Dialog, { "recipient" : { "id" : self.options.id } } );
				});
		},

		"{self} popboxActivate": function() {

			self.friendsSubmenu().parent().removeClass("open");
		},

		"{addButton} click": function() {

			EasySocial.ajax("site/controllers/friends/request",
			{
				"id"	: self.options.id
			})
			.done(function()
			{
				// Add a loader
				// self.addButton().html( self.view.loader() );

				// Replace the button
				EasySocial.ajax( 'site/views/profile/getButton' , { "button" : "button.requested" , "id" : self.options.id } )
				.done(function( button )
				{
					// We know that the existing button is a request button
					self.addButton().replaceWith( button );
				});
			});
		},
		
		"{cancelFriend} click": function(el, event) {

			var friendId = $( el ).data( 'friendid' );

			EasySocial.dialog(
			{
				content		: EasySocial.ajax( 'site/views/profile/confirmRemoveFriend' , { "id" : self.options.id } ),
				bindings	: 
				{
					"{confirmButton} click" : function()
					{
						EasySocial.ajax( 'site/controllers/friends/unfriend' , { "id" : friendId } )
						.done(function()
						{
							// Display tot he user that they are no longer friends now.
							EasySocial.dialog(
							{
								content 	: EasySocial.ajax( 'site/views/profile/friendRemoved' , { "id" : self.options.id } )
							});

							// Replace the button
							EasySocial.ajax( 'site/views/profile/getButton' , { "button" : "button.add" } )
							.done(function( button )
							{
								self.friendsSubmenu().remove();

								self.friendsButton().replaceWith( button );
							});
						});
					}
				}
			});

		},

		"{cancelFriendRequest} click": function(el, event) {

			var friendId = $( el ).data( 'friendid' );

			EasySocial.dialog(
			{
				content: EasySocial.ajax( 'site/views/profile/confirmCancelRequest' ,
							{
								"id"	: friendId
							}),
				bindings: 
				{
					"{confirmButton} click" : function()
					{
						// Close the dialog
						EasySocial.dialog().close();

						EasySocial.ajax( 'site/controllers/friends/cancelRequest' , { "id" : friendId } )
						.done(function()
						{
							// Replace the button
							EasySocial.ajax( 'site/views/profile/getButton' , { "button" : "button.add" } )
							.done(function( button )
							{
								// Hide the submenu
								self.friendsSubmenu().remove();

								// We know that the existing button is a request button
								self.requestedButton().replaceWith( button );
							});
						});
					}
				}
			});

		},

		"{approveFriend} click": function( el , event ) {

			var friendId = $( el ).data( 'friendid' );

			EasySocial.ajax( 'site/controllers/friends/approve' , { "id" : friendId } )
			.done(function()
			{
				EasySocial.dialog(
				{
					content: EasySocial.ajax( 'site/views/profile/confirmFriends' , { "id" : self.options.id } )
				});

				// Replace the button
				EasySocial.ajax( 'site/views/profile/getButton' , { "button" : "button.friends" } )
				.done(function( button )
				{
					// Hide the submenu
					self.friendsSubmenu().remove();

					// We know that the existing button is a request button
					self.respondButton().replaceWith( button );
				});
			});
		},

		"{rejectFriend} click" : function(el, event) {

			var friendId = $( el ).data( 'friendid' );

			EasySocial.ajax("site/controllers/friends/reject",
			{
				id: friendId
			})
			.done(function(){

				EasySocial.dialog({
					content: EasySocial.ajax("site/views/profile/rejected", { "id" : self.options.id } )
				});

				// Replace the button
				EasySocial.ajax("site/views/profile/getButton",
				{
					"button" : "button.add" 
				})
				.done(function(button){

					// Hide the submenu
					self.friendsSubmenu().remove();

					// We know that the existing button is a request button
					self.respondButton().replaceWith( button );
				});
			});
		}

	}});

	// Popovers can implement themselves
	$(document).on("mouseover.es.profile.popbox", "[data-popbox-tooltip=profile]", function(){
		$(this).addController("EasySocial.Controller.Profile.Popbox");
	});

	module.resolve();

});

EasySocial.module( 'site/conversations/composer' , function($){

	var module 	= this;

	EasySocial.require()
	.library( 'expanding' )
	.script( 'site/friends/suggest' , 'uploader/uploader' , 'location' )
	.language(
		'COM_EASYSOCIAL_CONVERSATIONS_ERROR_EMPTY_RECIPIENTS',
		'COM_EASYSOCIAL_CONVERSATIONS_ERROR_EMPTY_MESSAGE'
	)
	.done(function($){

		EasySocial.Controller(
			'Conversations.Composer',
			{
				defaultOptions:
				{
					// Determines if these features should be enabled.
					attachments 		: true,
					location 			: true,

					// Uploader properties.
					extensionsAllowed	: "",

					// File uploads
					"{uploader}"		: "[data-composer-attachment]",

					// Location service.
					"{location}"		: "[data-composer-location]",

					// The text editor.
					"{editor}"			: "[data-composer-editor]",

					// Wrapper for suggest to work.
					"{friendSuggest}"		: "[data-friends-suggest]",

					"{recipients}"		: "input[name=uid\\[\\]]",

					"{recipientRow}"	: "[data-composer-recipients]",
					"{messageRow}"		: "[data-composer-message]",

					// Submit button
					"{submit}"			: "[data-composer-submit]"
				}
			},
			function( self ){
				return {

					init: function()
					{
						// Initialize the participants textbox.
						self.initSuggest();

						// Initialize editor.
						self.initEditor();

						// Initialize uploader
						if( self.options.attachments )
						{
							self.initUploader();
						}

						// Initialize location
						if( self.options.location )
						{
							self.initLocation();	
						}
					},

					/**
					 * Initializes the location form.
					 */
					initLocation: function()
					{
						self.location().implement( EasySocial.Controller.Location.Form );
					},

					/**
					 * Resets the conversation form.
					 */
					resetForm: function()
					{
						self.editor().val('');
					},

					/**
					 * Initializes the uploader.
					 */
					initUploader: function()
					{
						// Implement uploader controller.
						self.uploader().implement( EasySocial.Controller.Uploader ,
						{
							// We want the uploader to upload automatically.
							temporaryUpload	: true,
							query 			: "type=conversations",
							type 				: 'conversations',
							extensionsAllowed : self.options.extensionsAllowed
						});

						if( EasySocial.environment == 'development' )
						{
							console.log( 'Extensions Allowed: ' + self.options.extensionsAllowed );
							console.log( 'Maximum individual file size: ' + self.options.maxSize );
						}
					},

					/**
					 * Initializes and converts the normal textbox into a suggest list.
					 */
					initSuggest: function()
					{
						self.friendSuggest()
							.addController(EasySocial.Controller.Friends.Suggest);
					},

					/**
					 * Initializes the editor
					 *
					 */
					initEditor : function()
					{
						self.editor().expandingTextarea();
					},

					/**
					 * Check for errors on the conversation form.
					 */
					checkErrors: function()
					{
						if( self.recipients().length <= 0 )
						{
							self.recipientRow().addClass( 'error' );
							self.clearMessage();
							self.setMessage( $.language( 'COM_EASYSOCIAL_CONVERSATIONS_ERROR_EMPTY_RECIPIENTS' ) , 'error' );

							return true;
						}
						else
						{
							self.recipientRow().removeClass( 'error' );
						}

						if( self.editor().val() == '' )
						{
							self.messageRow().addClass( 'error' );
							self.clearMessage();
							self.setMessage( $.language( 'COM_EASYSOCIAL_CONVERSATIONS_ERROR_EMPTY_MESSAGE' ) , 'error' );

							return true;
						}
						else
						{
							self.messageRow().removeClass( 'error' );
						}

						return false;
					},

					/**
					 * Submit button.
					 */
					"{submit} click" : function( el , event )
					{
						// Prevent form submission since this is a submit button.
						event.preventDefault();

						// Check for errors on this page.
						if( self.checkErrors() )
						{
							return false;
						}

						if( self.options.attachments )
						{
							var uploaderController 	= self.uploader().controller();

							// Do not allow user to submit this when the items are still being uploaded.
							if( uploaderController.options.uploading && uploaderController.hasFiles() )
							{
								return false;
							}
						}

						// Submit the form when we're ready.
						self.element.submit();
					}
				}
			}
		);

		EasySocial.Controller(
			'Conversations.Composer.Dialog',
			{
				defaultOptions:
				{
					// Default options
					recipient 		: {},
				}
			},
			function( self ){
				return {
					init: function()
					{

					},

					"{self} click" : function()
					{
						EasySocial.dialog(
						{
							"content"	: EasySocial.ajax( 'site/views/conversations/composer' , { "id" : self.options.recipient.id } ),
							"bindings"	:
							{
								"{sendButton} click" : function()
								{
									var recipient 	= $( '[data-composer-recipient]' ).val(),
										message 	= $( '[data-composer-message]' ).val();


									EasySocial.ajax( 'site/controllers/conversations/store' ,
									{
										"uid"		: recipient,
										"message"	: message
									})
									.done(function( link )
									{
										EasySocial.dialog(
										{
											"content"	: EasySocial.ajax( 'site/views/conversations/sent' , { "id" : self.options.recipient.id }),
											"bindings"	:
											{
												"{viewButton} click" : function()
												{
													document.location 	= link;
												}
											}
										});
									})
									.fail( function( message )
									{
										self.setMessage( message );
									});
								}
							}
						});
					}
				}
		});

		module.resolve();
	});

});


EasySocial.module( 'site/friends/suggest' , function($){

	var module 	= this;

	EasySocial.require()
	.view( 'site/friends/suggest.item' )
	.library( 'textboxlist' )
	.language( 'COM_EASYSOCIAL_FRIENDS_REQUEST_SENT' )
	.done(function($){


		EasySocial.Controller(
			'Friends.Suggest.User',
			{
				defaultOptions:
				{
					"{addButton}"		: "[data-friend-suggest-add]",
					"{button}"			: "[data-friend-suggest-button]"

				}
			},
			function( self ){

				return {
					init: function(){
						// console.log( 'friends suggest');
					},

					"{addButton} click" : function( el ){

						// Implement controller on add friend.
						EasySocial.ajax( 'site/controllers/friends/request' ,
						{
							"id"	: self.element.data( 'uid' )
						})
						.done( function( friendId )
						{
							// replace the button with done message.
							self.button().html( $.language('COM_EASYSOCIAL_FRIENDS_REQUEST_SENT') );

						})
						.fail(function( message )
						{
							self.button().html( message );
						});
					}

				}

			}

		);

		EasySocial.Controller("Friends.Suggest",
		{
			defaultOptions:
			{
				max: null,
				exclusive: true,
				exclusion: [],
				minLength: 1,
				highlight: true,
				name: "uid[]",

				view: {
					suggestItem: "site/friends/suggest.item"
				}
			}
		},
		function(self) { return {

			init: function() {

				var options = self.options;

				// Implement the textbox list on the implemented element.
				self.element
					.textboxlist({
						name: options.name,
						max: options.max,
						plugin: {
							autocomplete: {

								exclusive: options.exclusive,
								minLength: options.minLength,
								highlight: options.highlight,
								showLoadingHint: true,
								showEmptyHint: true,

								query: function(keyword) {

									return EasySocial.ajax("site/controllers/friends/suggest", {search: keyword});
								}
							}
						}
					})
					.textboxlist("enable");
			},

			"{self} filterItem": function(el, event, item) {

				var html = 
					self.view.suggestItem(true, {
						item: item,
						name: self.options.name
					});

				item.title    = item.screenName;
				item.menuHtml = html;
				item.html     = html;

				return item;
			},			

			"{self} filterMenu": function(el, event, menu, menuItems, autocomplete, textboxlist) {

				// Get list of excluded users
				var items = textboxlist.getAddedItems(),
					users = $.pluck(items, "id"),
					users = users.concat(self.options.exclusion);

				menuItems.each(function(){

					var menuItem = $(this),
						item = menuItem.data("item");

					// If this user is excluded, hide the menu item
					menuItem.toggleClass("hidden", $.inArray(item.id.toString(), users) > -1);
				});
			}

		}});

		module.resolve();
	});

});


EasySocial.module("location", function($) {

var module = this;

// require: start
EasySocial.require()
	.library( "ui/autocomplete" )
	.view( "site/location/delete.confirmation" )
	.done(function(){


		EasySocial.Controller(
			"Location.Form",
			{
				defaultOptions: {

					// Map properties
					language				: 'en',
					initialLocation			: null,
					mapType					: "ROADMAP",
					staticMap 				: 'https://www.google.com/maps?q=',
					showTip 				: true,

					// Location input
					"{locationInput}"		: "[data-locationForm-input]",

					// Location geographics (Longitude / Latitude display)
					"{locationCoordinates}"	: "[data-locationForm-coordinates]",
					"{locationLatitude}"	: "[data-locationForm-latitude]",
					"{locationLongitude}"	: "[data-locationForm-longitude]",
					"{latitudeDisplay}"		: "[data-locationForm-latitudeDisplay]",
					"{longitudeDisplay}"	: "[data-locationForm-longitudeDisplay]",

					// Map display
					"{locationMap}"			: "[data-locationForm-map]",
					"{locationMapWrapper}"	: ".locationMapWrapper",
					
					"{viewStaticMap}"		: ".viewStaticMap",

					// Buttons
					"{editGeographic}"		: "[data-locationForm-edit]",
					"{updateGeographic}"	: "[data-locationForm-update]",
					"{cancelUpdateGeographic}" : "[data-locationForm-cancel]",
					"{searchButton}"		: "[data-locationForm-searchAddress]",
					"{autoDetectButton}"	: "[data-locationForm-autodetect]",
					"{clearLocation}"		: "[data-locationForm-clear]"
				}
			},

			function(self) {

				return {

					init: function()
					{

						var mapReady = $.uid("ext");

						window[mapReady] = function() {
							$.___GoogleMaps.resolve();
						}

						if (!$.___GoogleMaps)
						{
							$.___GoogleMaps = $.Deferred();

							EasySocial.require()
							.script( { prefetch: false }, "http://maps.googleapis.com/maps/api/js?sensor=true&language=" + self.options.language + "&callback=" + mapReady );
						}

						// Defer instantiation of controller until Google Maps library is loaded.
						$.___GoogleMaps.done(function()
						{
							self._init();
						});
					},

					_init: function() {

						self.geocoder		= new google.maps.Geocoder();

						self.hasGeolocation = navigator.geolocation!==undefined;

						if (!self.hasGeolocation)
						{
							self.autoDetectButton().remove();
						}
						else
						{
							self.autoDetectButton().show();
						}

						// Apply auto complete on the address input.
						self.locationInput()
							.autocomplete(
							{
								delay: 300,
								minLength: 0,
								source: self.retrieveSuggestions,
								select: function(event, ui)
								{
									self.locationInput()
										.autocomplete("close");

									self.setLocation(ui.item.location);
								}
							})
							.prop("disabled", false);

						self.autocomplete = self.locationInput().autocomplete("widget");

						self.autocomplete.addClass("location-suggestion");

						var initialLocation = $.trim(self.options.initialLocation);

						if (initialLocation)
						{
							self.getLocationByAddress( initialLocation, function(location)
							{
									self.setLocation(location[0]);
							});
						};

						self.busy(false);
					},

					// Adds a loader class on the location input.
					busy: function(isBusy)
					{
						self.locationInput().toggleClass("loading", isBusy);
					},

					getUserLocations: function(callback)
					{
						self.getLocationAutomatically(function(locations)
						{
							self.userLocations = self.buildDataset(locations);
							
							callback && callback(locations);
						});
					},

					getLocationByAddress: function(address, callback)
					{
						self.geocoder.geocode({ "address" : address }, callback );
					},

					getLocationByCoords: function(latitude, longitude, callback)
					{
						self.geocoder.geocode( { "location" : new google.maps.LatLng(latitude, longitude) }, callback);
					},

					getLocationAutomatically: function(success, fail) {

						if (!navigator.geolocation) {
							return fail("ERRCODE", "Browser does not support geolocation or do not have permission to retrieve location data.")
						}

						navigator.geolocation.getCurrentPosition(
							// Success
							function(position) {
								self.getLocationByCoords(position.coords.latitude, position.coords.longitude, success)
							},
							// Fail
							fail
						);
					},

					renderMap: function(location, tooltipContent)
					{
						// Add loading
						self.busy(true);

						self.locationMapWrapper().show();

						var map	= new google.maps.Map(
							self.locationMap()[0],
							{
								zoom: 15,
								center: location.geometry.location,
								mapTypeId: google.maps.MapTypeId[self.options.mapType],
								disableDefaultUI: true
							}
						);

						var marker = new google.maps.Marker(
							{
								draggable: true,
								position: location.geometry.location,
								center	: location.geometry.location,
								title	: location.formatted_address,
								map		: map
							}
						);

						if( self.showTip )
						{
							var infoWindow = new google.maps.InfoWindow({ content: tooltipContent });

							google.maps.event.addListener(map, "tilesloaded", function() {
								infoWindow.open(map, marker);
								self.busy(false);
							});
						}

						// Add listener event when drag is end so we can update the latitude and longitude.
						google.maps.event.addListener(marker, 'dragend', function ( event ) {

							self.getLocationByCoords( this.getPosition().lat() , this.getPosition().lng() , function(locations){
								
								self.userLocations = self.buildDataset(locations);
								self.suggestUserLocations();
								// self.locationInput().val();
							});
							
							// Update the new latitude and longitude values.
							self.locationLatitude().val( this.getPosition().lat() );
							self.locationLongitude().val( this.getPosition().lng() );

							// Update the new latitude and longitude display values. This is not the input.
							self.latitudeDisplay().html( this.getPosition().lat() );
							self.longitudeDisplay().html( this.getPosition().lng() );
						});

					},

					setLocation: function(location) {

						if (!location) return;

						self.locationResolved = true;

						self.lastResolvedLocation = location;

						self.locationInput()
							.val(location.formatted_address);

						self.locationLatitude()
							.val(location.geometry.location.lat());

						self.locationLongitude()
							.val(location.geometry.location.lng());

						self.latitudeDisplay()
							.html( location.geometry.location.lat() );

						self.longitudeDisplay()
							.html( location.geometry.location.lng() );

						self.renderMap(location, location.formatted_address);

						// Trigger when the location is selected.
						self.trigger( 'locationSelected' , [location] );
					},

					/**
					 * Removes any detected location from the form.
					 */
					removeLocation: function()
					{
						self.locationResolved = false;

						// Empty the address input.
						self.locationInput().val('');

						// Empty the coordinates
						self.locationLatitude().val('');
						self.locationLongitude().val('');

						// Remove the display values
						self.latitudeDisplay().html('');
						self.longitudeDisplay().html( '' );

						// Hide the map.
						self.locationMapWrapper().hide();

						// Hide the coordinates.
						self.locationCoordinates().hide();
					},

					buildDataset: function(locations) {

						var dataset = $.map(locations, function(location){
							return {
								label: location.formatted_address,
								value: location.formatted_address,
								location: location
							};
						});

						return dataset;
					},

					retrieveSuggestions: function(request, response) {

						self.busy(true);

						var address = request.term,

							respondWith = function(locations) {
								response(locations);
								self.busy(false);
							};

						// User location
						if (address=="") {

							respondWith(self.userLocations || []);

						// Keyword search
						} else {

							self.getLocationByAddress(address, function(locations) {

								respondWith(self.buildDataset(locations));
							});
						}
					},

					suggestUserLocations: function() {

						if (self.hasGeolocation && self.userLocations) {
	//						self.removeLocation();

							self.locationInput()
								.autocomplete("search", "");
						}

						self.busy(false);
					},

					"{locationInput} blur": function() {

						// Give way to autocomplete
						setTimeout(function(){

							var address = $.trim(self.locationInput().val());

							// Location removal
							if (address=="") {

								self.removeLocation();

							// Unresolved location, reset to last resolved location
							} else if (self.locationResolved) {

								if (address != self.lastResolvedLocation.formatted_address) {

									self.setLocation(self.lastResolvedLocation);
								}
							} else {
								self.removeLocation();
							}

						}, 250);
					},

					// Trigger when a location is selected from the autocomplete list.
					"{self} locationSelected": function( el , event , location ) {

						// Find the geographic data and display to the user.
						self.locationCoordinates().show();

						// Allow triggers
						self.options.onLocationSelected && self.options.onLocationSelected(location);

						// Show the view larger map
						self.viewStaticMap().attr( 'href' , self.options.staticMap + encodeURIComponent( location.formatted_address ) );
					},

					"{cancelUpdateGeographic} click" : function()
					{
						self.editGeographic().click();
					},

					"{editGeographic} click" : function(){
						self.locationCoordinates().toggleClass( 'editMode' );
					},

					"{updateGeographic} click" : function(){

						// Updating location.
						self.editGeographic().click();
					},

					/**
					 * When user wants to remove the location.
					 */
					"{clearLocation} click" : function()
					{
						self.removeLocation();
					},

					/**
					 * Prompts user to share their location.
					 */
					"{autoDetectButton} click": function()
					{
						// Add a busy indicator to the input.
						self.busy( true );

						if (self.hasGeolocation && !self.userLocations)
						{
							self.getUserLocations(self.suggestUserLocations);

							return;
						}

						self.suggestUserLocations();
					}
				}
		});

		EasySocial.Controller(

			"Location.Map",

			{
				defaultOptions: {
					animation: 'drop',
					language: 'en',
					useStaticMap: false,
					disableMapsUI: true,
					
					// Show address in a tooltip.
					showTip: true,

					// fitBounds = true will disobey zoom
					// single location with fitBounds = true will set zoom to max (by default from Google)
					// locations.length == 1 will set fitBounds = false unless explicitly specified
					// locations.length > 1 will set fitBounds = true unless explicitly specified
					zoom: 5,
					fitBounds: null,

					minZoom: null,
					maxZoom: null,

					// location in center has to be included in locations array
					// center will default to first object in locations
					// latitude and longitude always have precedence over address
					// {
					// 	"latitude": latitude,
					// 	"longitude": longitude,
					// 	"address": address
					// }
					center: null,

					// address & title are optional
					// latitude and longitude always have precedence over address
					// title will default to geocoded address
					// first object will open info window
					// [
					// 	{
					// 		"latitude": latitude,
					// 		"longitude": longitude,
					// 		"address": address,
					// 		"title": title
					// 	}
					// ]
					locations: [],

					// Default map type to be road map. Can be overriden.
					mapType: "ROADMAP",

					width: 500,
					height: 400,

					"{locationMap}": ".locationMap",
					"{locationMapWrapper}"	: ".locationMapWrapper",

					// Actions
					"{removeUserLocation}"	: '.removeUserLocation',

					// Views
					view : {
						deleteConfirmation	: 'site/location/delete.confirmation'
					}
				}
			},

			function(self) { return {

				init: function() {
					self.mapLoaded = false;

					var mapReady = $.uid("ext");

					window[mapReady] = function() {
						$.___GoogleMaps.resolve();
					}

					if(self.options.useStaticMap == true) {
						var language = '&language=' + String(self.options.language);
						var dimension = '&size=' + String(self.options.width) + 'x' + String(self.options.height);
						var zoom = '&zoom=' + String(self.options.zoom);
						var center = '&center=' + String(parseFloat(self.options.locations[0].latitude).toFixed(6)) + ',' + String(parseFloat(self.options.locations[0].longitude).toFixed(6));
						var maptype = '&maptype=' + google.maps.MapTypeId[ self.options.mapType ];
						var markers = '&markers=';
						var url = 'http://maps.googleapis.com/maps/api/staticmap?sensor=false' + language + dimension;

						if(self.options.locations.length == 1) {
							markers += String(parseFloat(self.options.locations[0].latitude).toFixed(6)) + ',' + String(parseFloat(self.options.locations[0].longitude).toFixed(6));

							url += zoom + center + maptype + markers;
						} else {
							var temp = new Array();
							$.each(self.options.locations, function(i, location) {
								temp.push(String(parseFloat(location.latitude).toFixed(6)) + ',' + String(parseFloat(location.longitude).toFixed(6)));
							})
							markers += temp.join('|');

							url += markers + maptype;
						}

						self.locationMap().html('<img src="' + url + '" />');
						self.locationMapWrapper().show();

						self.busy(false);
					} else {
						var mapReady = $.uid("ext");

						window[mapReady] = function() {
							$.___GoogleMaps.resolve();
						}

						if (!$.___GoogleMaps) {

							$.___GoogleMaps = $.Deferred();

							EasySocial.require()
								.script(
									{prefetch: false},
									"http://maps.googleapis.com/maps/api/js?sensor=true&language=" + self.options.language + "&callback=" + mapReady
								);
						}

						// Defer instantiation of controller until Google Maps library is loaded.
						$.___GoogleMaps.done(function() {
							self._init();
						});
					}
				},

				_init: function() {

					// initialise fitBounds according to locations.length
					if(self.options.fitBounds === null) {
						if(self.options.locations.length == 1) {
							self.options.fitBounds = false;
						} else {
							self.options.fitBounds = true;
						}
					}

					// initialise disableMapsUI value to boolean
					self.options.disableMapsUI = Boolean(self.options.disableMapsUI);

					// initialise all location object
					self.locations = new Array();
					$.each(self.options.locations, function(i, location) {
					    if( location.latitude != 'null' && location.longitude != 'null' ) {
							self.locations.push(new google.maps.LatLng(location.latitude, location.longitude));
						}
					});

					if(self.locations.length > 0) {
						self.renderMap();
					}

					self.busy(false);
				},

				busy: function(isBusy) {
					self.locationMap().toggleClass("loading", isBusy);
				},

				renderMap: function() {
					self.busy(true);

					self.locationMapWrapper().show();

					var latlng;

					if(self.options.center) {
						latlng = new google.maps.LatLng(center.latitude, center.longitude);
					} else {
						latlng = self.locations[0];
					}

					self.map = new google.maps.Map(
						self.locationMap()[0],
						{
							zoom: parseInt( self.options.zoom ),
							minZoom: parseInt( self.options.minZoom ),
							maxZoom: parseInt( self.options.maxZoom ),
							center: latlng,
							mapTypeId: google.maps.MapTypeId[ self.options.mapType ],
							disableDefaultUI: self.options.disableMapsUI
						}
					);

					google.maps.event.addListener(self.map, "tilesloaded", function() {
						if(self.mapLoaded == false) {
							self.mapLoaded = true;
							self.loadLocations();
						}
					});
				},

				loadLocations: function() {
					self.bounds = new google.maps.LatLngBounds();
					self.infoWindow = new Array();

					var addLocations = function() {
						$.each(self.locations, function(i, location) {
							self.bounds.extend(location);
							var placeMarker = function() {
								self.addMarker(location, self.options.locations[i]);
							}

							setTimeout(placeMarker, 100 * ( i + 1 ) );
						});

						if(self.options.fitBounds) {
							self.map.fitBounds(self.bounds);
						}
					};

					setTimeout(addLocations, 500);
				},

				addMarker: function(location, info) {
					if (!location) return;

					var marker = new google.maps.Marker(
						{
							position: location,
							map: self.map
						}
					);

					marker.setAnimation(google.maps.Animation.DROP);


					self.addInfoWindow(marker, info)
				},

				addInfoWindow: function(marker, info) {

					if( !self.showTip )
					{
						return;
					}

					var content = info.content;

					if(!content) {
						content = info.address;
					}

					var infoWindow = new google.maps.InfoWindow();
					infoWindow.setContent(content);
					self.infoWindow.push(infoWindow);

					if(self.options.locations.length > 1) {
						google.maps.event.addListener(marker, 'click', function() {
							$.each(self.infoWindow, function(i, item) {
								item.close();
							});
							infoWindow.open(self.map, marker);
						});
					} else {
						google.maps.event.addListener(marker, 'click', function() {
							infoWindow.open(self.map, marker);
						});

						infoWindow.open(self.map, marker);
					}
				},
				"{removeUserLocation} click" : function(){

					// @TODO: Run some ajax calls to remove this.
					var id 			= self.element.data( 'id' );
						content		= self.view.deleteConfirmation({});
					$.dialog({
						content : content,
						buttons : [{

							name 	: $.language( 'COM_EASYSOCIAL_YES_BUTTON' ),
							click	: function(){
								EasySocial.ajax( 'site/controllers/location/delete' , {
									"id"	: id
								}, function(){

									// Hide the dialog
									$.dialog().close();
									
									// Remove the entire map section.
									self.element.remove();
								});
							}
						},
						{
							name	: $.language( 'COM_EASYSOCIAL_CANCEL_BUTTON' ),
							click	: function(){
								$.dialog().close();
							}
						}
						]
					})


				}
			}}
		);

		module.resolve();

	});
// require: end
});

EasySocial.module("site/privacy/privacy", function($){

	var module	= this;

	EasySocial.require()
	.library("textboxlist")
	.language(
		'COM_EASYSOCIAL_PRIVACY_TOOLTIPS_SHARED_WITH_PUBLIC',
		'COM_EASYSOCIAL_PRIVACY_TOOLTIPS_SHARED_WITH_MEMBER',
		'COM_EASYSOCIAL_PRIVACY_TOOLTIPS_SHARED_WITH_FRIENDS_OF_FRIEND',
		'COM_EASYSOCIAL_PRIVACY_TOOLTIPS_SHARED_WITH_FRIEND',
		'COM_EASYSOCIAL_PRIVACY_TOOLTIPS_SHARED_WITH_ONLY_ME',
		'COM_EASYSOCIAL_PRIVACY_TOOLTIPS_SHARED_WITH_CUSTOM'
	)
	.done(function($){

		EasySocial.Controller("Privacy",
		{
			defaultOptions: {

				"{menu}"  : "[data-privacy-menu]",
				"{item}"  : "[data-privacy-item]",
				"{icon}"  : "[data-privacy-icon]",
				"{button}": "[data-privacy-toggle]",
				"{tooltip}": "[data-original-title]",

				"{key}"   : "[data-privacy-hidden]"
			}
		},
		function(self){ return {

			init: function() {

				self.instanceId = $.uid();

				self.addPlugin("custom");
			},

			getData: function(item) {
				return $._.pick(item.data(), "uid", "utype", "value", "pid", "icon");
			},

			"{self} click" : function(el, event) {

				var target = $(event.target),
					button = self.button();

				// If the area being clicked is the toggle button,
				if (target.parents().andSelf().filter(button).length > 0) {

					// then we toggle privacy menu.
					self.toggle();
				}
			},

			"{item} click" : function(item) {

				// Retrieve data from this privacy item
				var data = self.getData(item);

				// Trigger privacy changed event
				self.trigger("privacyChange", [data]);

				if (!data.preventSave) {

					// Save new privacy settings
					self.save(data);

					// Deactivate menu
					self.deactivate();
				}
			},

			"{self} privacyChange": function(el, event, data) {

				// Deactivate other privacy item
				self.item()
					.removeClass("active")

					// and activate current privacy item.
					.filter("[data-value=" + data.value + "]")
					.addClass("active");
			},

			toggle: function() {
				var isActive = self.element.hasClass("active");
				self[(isActive) ? "deactivate" : "activate"]();
			},

			activate: function() {
				self.element.addClass("active");

				self.trigger("activate", [self]);
				$(window).trigger("activatePrivacy", [self]);

				var windowClick = "click.privacy." + self.instanceId;

				$(document).on(windowClick, function(event){

					var clickedTarget = $(event.target);

					// Don't do anything if we're clicking ourself
					if (clickedTarget.parents().andSelf().filter(self.element).length > 0
						|| clickedTarget.parents('[data-textboxlist-autocomplete]').length > 0
						|| clickedTarget.parents('[data-textboxlist-item]').length > 0 )
					{
						return;
					}

					$(document).off(windowClick);
					self.deactivate();
				});
			},

			deactivate: function() {
				self.element.removeClass("active");

				self.trigger("deactivate", [self]);
				$(window).trigger("deactivatePrivacy", [self]);
			},

			"{window} activatePrivacy": function(el, event, instance) {
				if (instance!==self) {
					self.deactivate();
				}
			},

			save: function( data ) {

				// Set privacy value
				self.key().val(data.value);

				// Set privacy icon
				self.icon().attr("class", data.icon);

				// Trigger save event
				self.trigger("privacySave", [data]);

				// update tooltips
				self.element.attr('data-original-title', $.language( 'COM_EASYSOCIAL_PRIVACY_TOOLTIPS_SHARED_WITH_' + data.value.toUpperCase() ) );

				// If saving is done via ajax, save now.
				if (self.element.data("privacyMode")=="ajax") {

					EasySocial.ajax("site/controllers/privacy/update",
						{
							uid 	: data.uid,
							utype	: data.utype,
							value 	: data.value,
							pid 	: data.pid,
							custom 	: data.custom
						})
						.done(function(){

						})
						.fail(function(){
							// Unable to set privacy settings
						});
				}
			}
		}});


		EasySocial.Controller("Privacy.Custom",
			{
				defaultOptions: {
					"{textField}"   : "[data-textfield]",
					"{saveButton}" 	: "[data-save-button]",
					"{cancelButton}": "[data-cancel-button]",
					"{customItem}"  : "[data-privacy-item][data-value=custom]",
					"{customKey}"   : "[data-privacy-custom-hidden]"
				}
			},
			function(self) { return {

				init: function() {

					self.textField()
						.textboxlist({
							unique: true,
							plugin: {
								autocomplete: {
									exclusive: true,
									minLength: 1,
									cache: false,
									query: function(keyword) {

										var users = self.getIds();

										var ajax = EasySocial.ajax("site/views/privacy/getfriends",
											{
												q: keyword,
												exclude: users
											});
										return ajax;
									}
								}
							}
						});

					self.textboxlist = self.textField().controller("TextboxList");
				},

				getIds: function() {

					var items =
						self.textField()
							.textboxlist("controller")
							.getAddedItems();

					return $.map(items, function(item, idx) {
						return item.id;
					});
				},

				updateIds: function() {

					var ids = self.getIds();
					self.customKey().val(ids.join(","));
				},

				"{parent} privacyChange": function(el, event, data) {

					var isCustomPrivacy = (data.value=="custom");

					self.element.toggleClass("custom-privacy", isCustomPrivacy);

					// If user no longer selects custom privacy
					if (!isCustomPrivacy) {

						// Clear any existing custom privacy
						self.textField()
							.textboxlist("controller")
							.clearItems();
					} else {

						// Prevent privacy from saving
						data.preventSave = true;
					}
				},

				"{parent} privacySave": function(el, event, data) {
					// for now do nothing.
				},

				"{parent} deactivate": function() {
					self.textboxlist.autocomplete.hide();
				},

				"{cancelButton} click" : function(){
					self.element.removeClass("custom-privacy");
					self.textboxlist.autocomplete.hide();
				},

				"{saveButton} click" : function(){

					var parent = self.parent,
						customItem = self.customItem();

					var data = parent.getData(customItem);
					data.custom = self.customKey().val();

					self.parent.save(data );
					self.parent.deactivate();
				},

				// event listener for adding new name
				"{textField} addItem": function() {
					self.updateIds();
				},

				// event listener for removing name
				"{textField} removeItem": function() {
					self.updateIds();
				}
		}});

		$(document).on('click.es.privacy',  '[data-es-privacy-container]', function(){

			var privacyButton = $(this),
				privacyController = "EasySocial.Controller.Privacy";

			if (privacyButton.hasController(privacyController)) return;

			privacyButton.addController(privacyController).toggle();
		});

		module.resolve();
	});

});

EasySocial.module('site/locations/popbox', function($){

	EasySocial.module("locations/popbox", function($){

		this.resolve(function(popbox){

			var button = popbox.button,
				lat = button.data("lat"),
				lng = button.data("lng"),
				link = "//maps.google.com/?q=" + lat + "," + lng,				
				url = "//maps.googleapis.com/maps/api/staticmap?size=400x200&sensor=true&zoom=15&center=" + lat + "," + lng + "&markers=" + lat + "," + lng;

			return {
				id: "es-wrap",
				type: "location",
				position: "bottom",
				content: '<a href="' + link + '" target="_blank"><img src="' + url + '" width="400" height="200" /></a>'
			}
		});

	});

	this.resolve();

});

EasySocial.module('site/sidebar/sidebar', function($) {
	var module = this;

	$(function() {
		var toggle = $('[data-sidebar-toggle]');

		toggle.on('click', function() {
			var sidebar = $('[data-sidebar]');

			sidebar.toggleClass('sidebar-open');

			sidebar.trigger('sidebarToggle');
		});
	});

	module.resolve();
});

EasySocial.module("locations", function($){

	var module = this;
	
	EasySocial.require().library("gmaps").done();

	EasySocial
		.require()
		.library(
			"scrollTo"
		)
		.view(
			"apps/user/locations/suggestion"
		)
		.language(
			"COM_EASYSOCIAL_AT_LOCATION"
		)
		.done(function(){

			// Constants
			var KEYCODE = {
				BACKSPACE: 8,
				COMMA: 188,
				DELETE: 46,
				DOWN: 40,
				ENTER: 13,
				ESCAPE: 27,
				LEFT: 37,
				RIGHT: 39,
				SPACE: 32,
				TAB: 9,
				UP: 38
			};

			EasySocial.Controller("Locations",
				{
					defaultOptions: {

						view: {
							suggestion: "apps/user/locations/suggestion"
						},

						map: {
							lat: 0,
							lng: 0
						},

						"{textField}": "[data-location-textField]",
						"{detectLocationButton}": "[data-detect-location-button]",

						"{suggestions}": "[data-location-suggestions]",
						"{suggestion}": "[data-story-location-suggestion]",

						"{mapPreview}": "[data-location-map]",

						"{latitude}" : "[data-location-lat]",
						"{longitude}": "[data-location-lng]"
					}
				},
				function(self) { return {

					init: function() {

						// I have access to:
						// self.panelButton()
						// self.panelContent()

						// Only show auto-detect button if the browser supports geolocation
						if (navigator.geolocation) {
							self.detectLocationButton().show();
						}

						// Allow textfield input only when controller is implemented
						EasySocial.require().library("gmaps")
							.done(function(){
								self.textField().removeAttr("disabled");
							});
						
					},

					navigate: function(lat, lng) {

						var mapPreview = self.mapPreview(),
							map = self.map;

						// Initialize gmaps if not initialized
						if (map===undefined) {

							map = self.map =
								mapPreview
									.show()
									.gmaps(self.options.map);
						}

						map.setCenter(lat, lng);
						map.removeMarkers();
						map.addMarker({lat: lat, lng: lng});
					},

					// Memoized locations
					locations: {},

					lastQueryAddress: null,

					"{textField} keypress": function(textField, event) {

						switch (event.keyCode) {

							case KEYCODE.UP:

								var prevSuggestion = $(
									self.suggestion(".active").prev(self.suggestion.selector)[0] ||
									self.suggestion(":last")[0]
								);

								// Remove all active class
								self.suggestion().removeClass("active");

								prevSuggestion
									.addClass("active")
									.trigger("activate");

								self.suggestions()
									.scrollTo(prevSuggestion, {
										offset: prevSuggestion.height() * -1
									});

								event.preventDefault();

								break;

							case KEYCODE.DOWN:

								var nextSuggestion = $(
									self.suggestion(".active").next(self.suggestion.selector)[0] ||
									self.suggestion(":first")[0]
								);

								// Remove all active class
								self.suggestion().removeClass("active");

								nextSuggestion
									.addClass("active")
									.trigger("activate");

								self.suggestions()
									.scrollTo(nextSuggestion, {
										offset: nextSuggestion.height() * -1
									});

								event.preventDefault();

								break;

							case KEYCODE.ENTER:

								var activeSuggestion = self.suggestion(".active"),
									location = activeSuggestion.data("location");
									self.set(location);

								self.suggestions().hide();
								break;

							case KEYCODE.ESCAPE:
								break;
						}

					},

					"{textField} keyup": function(textField, event) {

						switch (event.keyCode) {

							case KEYCODE.UP:
							case KEYCODE.DOWN:
							case KEYCODE.ENTER:
							case KEYCODE.ESCAPE:
								// Don't repopulate if these keys were pressed.
								break;

							default:
								var address = $.trim(textField.val());

								if (address==="") {
									self.suggestions().hide();
								}

								if (address==self.lastQueryAddress) return;

								var locations = self.locations[address];

								// If this location has been searched before
								if (locations) {

									// Just use cached results
									self.suggest(locations);

									// And set our last queried address to this address
									// so that it won't repopulate the suggestion again.
									self.lastQueryAddress = address;

								// Else ask google to find it out for us
								} else {

									self.lookup(address);
								}
								break;
						}
					},

					lookup: $._.debounce(function(address){

						$.GMaps.geocode({
							address: address,
							callback: function(locations, status) {

								if (status=="OK") {

									// Store a copy of the results
									self.locations[address] = locations;

									// Suggestion locations
									self.suggest(locations);

									self.lastQueryAddress = address;
								}
							}
						});

					}, 250),

					suggest: function(locations) {

						var suggestions = self.suggestions();

						// Clear location suggestions
						suggestions
							.hide()
							.empty();

						$.each(locations, function(i, location){

							// Create suggestion and append to list
							self.view.suggestion({
									location: location
								})
								.data("location", location)
								.appendTo(suggestions);
						});

						suggestions.show();
					},

					"{suggestion} activate": function(suggestion, event) {

						var location = suggestion.data("location");

						self.navigate(
							location.geometry.location.lat(),
							location.geometry.location.lng()
						);
					},

					"{suggestion} mouseover": function(suggestion) {

						// Remove all active class
						self.suggestion().removeClass("active");

						suggestion
							.addClass("active")
							.trigger("activate");
					},

					"{suggestion} click": function(suggestion, event) {

						var location = suggestion.data("location");

						self.set(location);

						self.suggestions().hide();
					},

					set: function(location) {

						self.currentLocation = location;

						var address = location.formatted_address;

						self.textField().val(address);

						// var caption = $.language("COM_EASYSOCIAL_AT_LOCATION", location.address_components[0].long_name);
						// self.story.addPanelCaption("locations", caption);

						self.latitude()
							.val(location.geometry.location.lat());

						self.longitude()
							.val(location.geometry.location.lng());

						self.trigger("locationChange", [location]);
					},

					"{detectLocationButton} click": function() {

						var map = self.map;

						$.GMaps.geolocate({
							success: function(position) {

								$.GMaps.geocode({
									lat: position.coords.latitude,
									lng: position.coords.longitude,
									callback: function(locations, status){
										if (status=="OK") {
											self.suggest(locations);
											self.textField().focus();
										}
									}
								});
							},
							error: function(error) {
								// error.message
							},
							always: function() {

							}
						});
					},

					"{story} save": function(el, element, save) {

						var currentLocation = self.currentLocation;

						if (!currentLocation) return;

						save.addData(self, {
							short_address    : currentLocation.address_components[0].long_name,
							formatted_address: currentLocation.formatted_address,
							lat              : currentLocation.geometry.location.lat(),
							lng              : currentLocation.geometry.location.lng()
						});
					},

					"{story} clear": function() {

						self.unset();
					}

				}}
			);

			// Resolve module
			module.resolve();

		});

});

EasySocial.module( 'oauth/facebook', function($) {
	
	var module = this;

	EasySocial.require()
	.done(function() {

		EasySocial.Controller( 'OAuth.Facebook',
		{
			defaultOptions :
			{
				"{login}"	: "[data-oauth-facebook-login]",
				"{revoke}"	: "[data-oauth-facebook-revoke]",

				"{pushInput}"	: "[data-oauth-facebook-push]"
			}
		},
		function( self )
		{
			return {
				init : function()
				{
				},

				openDialog : function( url )
				{
					var left	= (screen.width/2)-( 300 /2),
						top		= (screen.height/2)-( 300 /2);
						
					window.open( url , "" , 'scrollbars=no,resizable=no,width=300,height=300,left=' + left + ',top=' + top );
				},

				"{pushInput} change" : function( el )
				{
					var enabled 	= $(el).val();
					
					if( enabled == 1 && self.options.requestPush )
					{
						self.openDialog( self.options.addPublishURL )
					}

					if( enabled == 0 )
					{
						self.openDialog( self.options.revokePublishURL );
					}
				},

				"{login} click" : function()
				{
					self.openDialog( self.options.url );
				},

				"{revoke} click" : function()
				{
					var callback 	= self.element.data( 'callback' );
					
					EasySocial.dialog(
					{
						content 	: EasySocial.ajax( 'site/views/oauth/confirmRevoke' , { "client" : 'facebook' , "callbackUrl" : callback } )
					});
				}
			}
		});

		module.resolve();
	});

}); // module end

EasySocial.module( 'pagination' , function(){

var module	= this;


// Module begins here.
EasySocial.require()
.done(function($){

	EasySocial.Controller(
		'Pagination',
		{
			defaultOptions:
			{
				"{pages}"		: ".pageItem",
				"{limitstart}"	: "#limitstart",
				"{previousItem}": ".previousItem",
				"{nextItem}"	: ".nextItem"
			}
		},
		function( self ) { return {
				init: function()
				{
					// Implement page item controller.
					self.pages().implement( EasySocial.Controller.Pagination.Page , {
						pagination : self
					} );
				},

				"{previousItem} click" : function( elem )
				{
					var limitstart 	= $( elem ).data( 'limitstart' );

					if( $( elem ).hasClass( 'disabled' ) )
					{
						return;
					}
					
					self.submitForm( limitstart );
				},

				"{nextItem} click" : function( elem )
				{
					var limitstart 	= $( elem ).data( 'limitstart' );

					if( $( elem ).hasClass( 'disabled' ) )
					{
						return;
					}

					self.submitForm( limitstart );
				},

				submitForm: function( limitstart )
				{
					// Update the limitstart value in the page.
					self.limitstart().val( limitstart );

					// Send a submit for the form.
					Joomla.submitform();
				}
			} }

		);


	EasySocial.Controller(
		'Pagination.Page',
		{
			defaultOptions:
			{
				pagination	: null,
				limitstart	: 0
			}
		},
		function( self )
		{
			return {
				init: function()
				{
					self.options.limitstart 	= self.element.data( 'limitstart' );
				},

				"{self} click" : function()
				{

					// If the page is currently active, we can just ignore this.
					if( self.element.hasClass( 'active' ) )
					{
						return false;
					}

					// Submit the form.
					self.options.pagination.submitForm( self.options.limitstart );
				}
			}
		}
	);

	// Once require is done, we mark this module as resolved.
	module.resolve();

});


});

EasySocial.module("photos/avatar", function($){

	var module = this;

	EasySocial.require()
		.library(
			"imgareaselect"
		)
		.stylesheet(
			"imgareaselect/default"
		)
		.done(function(){

			EasySocial.Controller("Photos.Avatar",
			{
				defaultOptions: {

					view: {
						selection: "site/photos/avatar.selection"
					},

					redirect: true,

					"{image}"   : "[data-photo-image]",
					"{viewport}": "[data-photo-avatar-viewport]",
					"{photoId}" : "[data-photo-id]",
					"{userId}"  : "[data-user-id]",
					"{createButton}": "[data-create-button]",
					"{selection}"   : "[data-selection-box]",
					"{loadingIndicator}": "[data-photos-avatar-loading]"
				}
			},
			function(self) { return {

				init: function() {

					self.setLayout();
				},

				data: function() {

					var viewport = self.viewport(),

						width  = viewport.width(),

						height = viewport.height(),

						selection =
							viewport
								.imgAreaSelect({instance: true})
								.getSelection(),

						data = {
							id    : self.photoId().val(),
							uid   : self.userId().val(),
							top   : selection.y1 / height,
							left  : selection.x1 / width,
							width : selection.width / width,
							height: selection.height / height
						};

					return data;
				},

				imageLoaders: {},

				setLayout: function() {

					var imageHolder   = self.image(),
					    imageUrl      = $.uri(imageHolder.css("backgroundImage")).extract(0),
					    imageLoaders  = self.imageLoaders,
					    imageLoader   = imageLoaders[imageUrl] || (self.imageLoaders[imageUrl] = $.Image.get(imageUrl));


					imageLoader
					    .done(function(imageEl, image){

							var size = $.Image.resizeWithin(
									image.width,
									image.height,
									imageHolder.width(),
									imageHolder.height()
								),
								min = Math.min(size.width, size.height),
								x1  = Math.floor((size.width  - min) / 2),
								y1  = Math.floor((size.height - min) / 2),
								x2  = x1 + min,
								y2  = y1 + min;

							self.viewport()
								.css(size)
								.imgAreaSelect({
									handles: true,
									aspectRatio: "1:1",
									parent: self.image(),
									x1: x1,
									y1: y1,
									x2: x2,
									y2: y2,
									onSelectEnd: function(viewport, selection) {
										var hasSelection = !(selection.width=="0" && selection.height=="0");
										self.createButton().enabled(hasSelection);
									}
								});
					    });
				},

				"{createButton} click": function(createButton) {

					var data = self.data(),
					
						task =
							EasySocial.ajax(
								"site/controllers/photos/createAvatar",
								data
								)
								.done(function(photo, user, profileUrl){
									if (self.options.redirect) {
										window.location = profileUrl;
									}
								})
								.fail(function(message, type){
									self.setMessage(message, type);
								});

					self.trigger("avatarCreate", [task, data, self]);
				}

			}});

			module.resolve();

		});
});

EasySocial.module("photos/browser", function($){

	var module = this;

	// Non-essential dependencies
	EasySocial.require()
		.library("masonry")
		.done();

	EasySocial.require()
		.library(
			"history"
		)
		.done(function(){

			EasySocial.Controller("Photos.Browser",
			{
				defaultOptions: {

					// For masonry
					tilesPerRow: 4,

					"{sidebar}": "[data-photo-browser-sidebar]",
					"{content}": "[data-photo-browser-content]",

					"{backButton}"    : "[data-photo-back-button]",
					"{backButtonLink}": "[data-photo-back-button-link]",

					"{listItemGroup}" : "[data-photo-list-item-group]",
					"{listItem}"      : "[data-photo-list-item]",
					"{listItemLink}"  : "[data-photo-list-item] > a",
					"{listItemTitle}" : "[data-photo-list-item-title]",
					"{listItemCover}" : "[data-photo-list-item-cover]",
					"{listItemImage}" : "[data-photo-list-item-image]",

					"{featuredListItem}"      : "[data-photo-list-item].featured",
					"{featuredListItemImage}" : "[data-photo-list-item].featured [data-photo-list-item-image]",

					"{photoItem}": "[data-photo-item]",
				}
			},
			function(self) { return {

				init: function() {

					// If there's masonry, activate it.
					$.module("masonry")
						.done(function(){
							self.setLayout();	
						});
				},

				setLayout: function() {

					var listItemGroup = self.listItemGroup().addClass("masonry"),

						// Build layout state
						seed          = self.setLayout.seed,
						intact        = seed == listItemGroup.width()
						hasPhotoItem  = self.listItem().length > 0,	
						masonry       = listItemGroup.data("masonry");

					if (intact) {

						// Just reload masonry
						masonry && masonry.reload();

					// Else recalculate sizes
					} else {						

						// Get listItemGroup
						var listItemGroup 		= self.listItemGroup(),
							listItem      		= self.listItem(),
							tilesPerRow         = self.options.tilesPerRow,

							viewportWidth       = listItemGroup.width(),
							containerWidth      = Math.floor(viewportWidth / tilesPerRow) * tilesPerRow,
							tileWidth           = containerWidth / tilesPerRow,
							tileWidthOffset     = listItem.outerWidth(true) - listItem.width(),
							tileHeight          = tileWidth,
							tileHeightOffset    = listItem.outerHeight(true) - listItem.height(),

							itemWidth           = tileWidth  - tileWidthOffset,
							itemHeight          = tileHeight - tileHeightOffset,
							imageWidth          = itemWidth,
							imageHeight         = itemHeight,
							featuredItemWidth   = (tileWidth  * 2) - tileWidthOffset,
							featuredItemHeight  = (tileHeight * 2) - tileHeightOffset,
							featuredImageWidth  = featuredItemWidth,
							featuredImageHeight = featuredItemHeight;

						self.listItem
							.css({
								width : itemWidth,
								height: itemHeight
							});

						self.listItemImage
							.css({
								width : imageWidth,
								height: imageHeight
							});

						self.featuredListItem
							.css({
								width : featuredItemWidth,
								height: featuredItemHeight
							});

						self.featuredListItemImage
							.css({
								width : featuredImageWidth,
								height: featuredImageHeight
							});

						listItemGroup
							.masonry({
								columnWidth: tileWidth
							})
							.masonry("reload");
					}

					// Save current layout
					self.setLayout.seed = listItemGroup.width();		
				},

				open: function(view) {

					var args = $.makeArray(arguments);

					self.trigger("contentLoad", args);

					var method = "view" + $.String.capitalize(view),
						loader = self[method].apply(self, args.slice(1));

					loader
						.done(self.displayContent(function(){
							self.trigger("contentDisplay", args);
							return arguments;
						}))
						.fail(function(){
							self.trigger("contentFail", args);
						})
						.always(function(){
							self.trigger("contentComplete", args);
						});

					return loader;
				},

				displayContent: $.Enqueue(function(html){

					var scripts = [],
						content = $($.buildFragment([html], document, scripts));

					// Insert content
					self.content().html(content);

					// Remove scripts
					$(scripts).remove();
				}),

				viewPhoto: function(photoId) {

					var state = "active loading",

						listItem = 
							self.listItem()
								.removeClass(state)
								.filterBy("photoId", photoId)
								.addClass(state),

						loader = 
							EasySocial.ajax(
								"site/views/photos/item",
								{
									id: photoId,
									browser: false
								})
								.fail(function(){
								})
								.always(function(){
									listItem.removeClass("loading");
								});

					return loader;
				},

				"{listItem} click": function(listItem) {

					var photoId = listItem.data("photoId");

					// Load album
					self.open("Photo", photoId);

					// Change address bar url
					listItem.find("> a").route();
				},

				"{listItemLink} click": function(listItemLink, event) {

					// Progressive enhancement, no longer refresh the page.
					event.preventDefault();

					// Prevent item from getting into :focus state
					listItemLink.blur();
				},

				"{backButtonLink} click": function(albumsButtonLink, event) {

					var browser = self.browser;

					// If albums browser exists, use it to load album
					if (browser) {

						var albumId = self.element.data("albumId");

						browser.open("album", albumId);

						event.preventDefault();

						albumsButtonLink.route();

					}
				},

				getListItem: function(photoId, context) {

					var listItem = 
						(!photoId) ?
							self.listItem(".new") :
							self.listItem().filterBy("photoId", photoId);

					if (!context) return listItem;

					return listItem.find(self["listItem" + $.String.capitalize(context)].selector);
				},

				getNextListItem: function(photoId) {

					var listItem = 
						self.getListItem(photoId)
							.next(self.listItem.selector);

					if (listItem.length < 1) {
						listItem = self.listItem(":first");
					}

					return listItem;
				},

				getPrevListItem: function(photoId) {

					var listItem = 
						self.getListItem(photoId)
							.prev(self.listItem.selector);

					if (listItem.length < 1) {
						listItem = self.listItem(":last");
					}

					return listItem;
				},

				removeListItem: function(photoId, loadPreviousItem) {

					var listItem = self.getListItem(photoId),
						prevListItem = self.getPrevListItem(photoId);

					// Remove list item
					listItem.remove();

					// Reset list item masonry layout
					self.setLayout();
				
					// If there are no more items on the list
					if (self.listItem().length < 1) {

						self.element.addClass("loading");

						// Go back to albums
						return window.location = self.backButtonLink().attr("href");
					}

					// Else load previous item
					if (loadPreviousItem) {

						prevListItem.click();
					}
				},

				"{photoItem} init.photos.item": function(el, event, photoItem) {

					// Attach browser plugin to album
					self.addSubscriber(photoItem);
				},

				"{photoItem} photoSave": function(el, event, task) {

					// Update list item title when photo is updated.
					task.done(function(photo, html){

						self.getListItem(photo.id, "title")
							.html(photo.title);
					});
				},

				"{photoItem} photoNext": function(el, event, photo) {

					var listItem = self.getNextListItem(photo.id);
					listItem.click();
				},

				"{photoItem} photoPrev": function(el, event, photo) {

					var listItem = self.getPrevListItem(photo.id);
					listItem.click();
				},

				"{photoItem} photoMove": function(el, event, task, photo, targetAlbumId) {

					task
						.done(function(){
							self.removeListItem(photo.id, true);
						});
				},

				"{photoItem} photoDelete": function(el, event, task, photo) {

					task
						.done(function(){
							self.removeListItem(photo.id, true);
						});
				},

				"{photoItem} photoFeature": function(el, event, task, photo, featured) {

					var item = self.getListItem(photo.id);

					item.toggleClass("featured", featured);
					self.setLayout();

					task
						.fail(function(){
							item.toggleClass("featured", !featured);
							self.setLayout();
						});
				}
				
			}});

			module.resolve();

		});
});
EasySocial.module("photos/cover", function($){

	var module = this;

	EasySocial.require()
		.library("image")
		.done(function(){

			EasySocial.Controller("Photos.Cover",
			{
				defaultOptions: {
					"{image}"        : "[data-cover-image]",
					"{editButton}"   : "[data-cover-edit-button]",
					"{doneButton}"   : "[data-cover-done-button]",
					"{cancelButton}" : "[data-cover-cancel-button]",
					"{uploadButton}" : "[data-cover-upload-button]",
					"{selectButton}" : "[data-cover-select-button]",
					"{removeButton}" : "[data-cover-remove-button]",
					"{menu}"         : "[data-cover-menu]"
				}
			},
			function(self) { return {

				init: function() {

					// Automatically enable cover editing if not manually disabled
					// if (!self.options.disabled) { self.start("url"); }

					self.setLayout();

					if (self.element.hasClass("editing")) {
						self.enable();
					}
				},

				"{window} resize": $.debounce(function() {
					self.setLayout();
				}, 250),

				"{editButton} click": function() {
					self.enable();
				},

				"{cancelButton} click": function() {
					self.disable();
				},

				ready: false,

				disabled: true,

				toggle: function() {
					self[(self.disabled) ? "enable" : "disable"]();
				},

				enable: function() {
					self.setLayout();
					self.disabled = false;
					self.element.addClass("editing");
				},

				disable: function() {
					self.disabled = true;
					self.element.removeClass("editing");

					var profileUrl = 
						$.uri(window.location.href)
							.deleteQueryParam("cover_id")
							.toString();

					History.pushState({state: 0}, window.title, profileUrl);
				},				

				imageLoaders: {},

				setLayout: function() {

					var cover = self.image(),
						image = self.setLayout.image;

					// Ensure cover viewport is always on 3:1 aspect ratio
					var viewportWidth = self.element.width(),
						viewportHeight = viewportWidth / 3;
						self.element.height(viewportHeight);

					if (!image) {

						// Extract image url from cover
						var url = $.uri(cover.css("backgroundImage")).extract(0);

						// If no url given, stop.
						if (!url) return;

						// Load image
						var imageLoaders = self.imageLoaders,
							imageLoader = 
								(imageLoaders[url] || (imageLoaders[url] = $.Image.get(url)))
									.done(function(image) {

										// Set it as current image
										self.setLayout.image = image;

										// Then set layout again
										self.setLayout();
									});

							return;
					}

					// Get measurements
					var imageWidth  = image.data("width"),
						imageHeight = image.data("height"),
						coverWidth  = cover.width(),
						coverHeight = cover.height(),
						size = $.Image.resizeProportionate(
							imageWidth, imageHeight,
							coverWidth, coverHeight,
							"outer"
						);

					self.availableWidth  = coverWidth  - size.width;
					self.availableHeight = coverHeight - size.height;
				},

				setCover: function(id, url) {

					// Show loading indicator
					self.element.addClass("loading");

					// Make sure the image has been properly loading
					$.Image.get(url)
						.done(function(){

							self.image()
								.data("photoId", id)
								.css({
									backgroundImage: $.cssUrl(url),
									backgroundPosition: "50% 50%"
								});

							// Reset position
							self.x = 0.5;
							self.y = 0.5;

							self.enable();
						})
						.fail(function(){
							self.disable();
						})
						.always(function(){

							self.element.removeClass("loading");
						});	
				},

				drawing: false,

				moveCover: function(dx, dy, image) {

					// Optimization: Pass in reference to image
					// so we don't have to query all the time.
					if (!image) { image = self.image(); }

					var w = self.availableWidth,
						h = self.availableHeight,
						x = (w==0) ? 0 : self.x + ((dx / w) || 0),
					    y = (h==0) ? 0 : self.y + ((dy / h) || 0);

					// Always stay within 0 to 1.
					if (x < 0) x = 0; if (x > 1) x = 1;
					if (y < 0) y = 0; if (y > 1) y = 1;

					// Set position on cover
					image.css("backgroundPosition", 
						((self.x = x) * 100) + "% " +
					    ((self.y = y) * 100) + "% "
					);
				},

				x: 0.5,

				y: 0.5,

				"{image} mousedown": function(selection, event) {

					if (self.disabled) return;

					if (event.target === self.image()[0]) {
						event.preventDefault();
					}

					self.drawing = true;
					self.element.addClass("active");

					// Initial cover position
					var image = self.image(),
						position = self.image().css("backgroundPosition").split(" ");
						self.x = parseInt(position[0]) / 100;
						self.y = parseInt(position[1]) / 100;

					// Initial cursor position
					var x = event.pageX,
						y = event.pageY;

					$(document)
						.on("mousemove.movingCover mouseup.movingCover", function(event) {

							if (!self.drawing) return;

							self.moveCover(
								(x - (x = event.pageX)) * -1,
								(y - (y = event.pageY)) * -1,
								image
							);
						})
						.on("mouseup.movingCover", function() {

							$(document).off("mousemove.movingCover mouseup.movingCover");

							self.element.removeClass("active");
						});
				},

				save: function() {

					var photoId = self.image().data("photoId");

					var task = 
						EasySocial.ajax(
							"site/controllers/photos/createCover",
							{
								id: photoId,
								x: self.x,
								y: self.y
							}
						)
						.done(function(cover){

							// Set cover
							self.element
								.css({
									backgroundImage: $.cssUrl(cover.url),
									backgroundPosition: cover.position
								})
								.removeClass("no-cover");

							// Disable editing
							self.disable();
						});

					return task;
				},

				"{doneButton} click": function() {

					self.save();
				},

                "{menu} dropdownOpen": function() {
                     self.element.addClass("show-all");
                },

                "{menu} dropdownClose": function() {
                     self.element.removeClass("show-all");
                },

                "{selectButton} click": function() {

                	EasySocial.photos.selectPhoto({
                		bindings: {
                			"{self} photoSelected": function(el, event, photos) {

                				// Photo selection dialog returns an array,
                				// so just pick the first one.
                				var photo = photos[0];

                				// If no photo selected, stop.
                				if (!photo) return;

                				// Set it as cover to reposition
                				self.setCover(photo.id, photo.sizes.large);

                				this.parent.close();
                			}
                		}
                	});
                },

                "{uploadButton} click": function() {

					EasySocial.dialog({
						content: EasySocial.ajax("site/views/profile/uploadCover"),
						bindings: {
							"{self} upload": function(el, event, task, filename) {

								task.done(function(photo){
									// Set cover
									self.setCover(photo.id, photo.sizes.large.url);
								});
							}
                		}
                	});
                },

                "{removeButton} click": function() {

                	EasySocial.ajax("site/controllers/photos/removeCover")
                		.done(function(defaultCoverUrl){

							self.element
								.css({
									backgroundImage: $.cssUrl(defaultCoverUrl),
									backgroundPosition: "50% 50%"
								})
								.addClass("no-cover");

                			self.disable();
                		});
                }

			}});

			module.resolve();

		});
});
EasySocial.module("photos/editor", function($){

	var module = this;

	EasySocial.require()
		.done(function(){

			var Controller = 
			EasySocial.Controller("Photos.Editor",
			{
				defaultOptions: {

					view: {
						uploadItem: "upload.item",
						photoForm : "site/albums/photo.form"
					},

					"{titleField}"  : "[data-photo-title-field]",
					"{captionField}": "[data-photo-caption-field]",

					"{location}"          : "[data-photo-location]",
					"{locationCaption}"   : "[data-photo-location-caption]",
					"{addLocationButton}" : "[data-photo-addLocation-button]",
					"{date}"              : "[data-photo-date]",
					"{dateCaption}"       : "[data-photo-date-caption]",
					"{addDateCaption}"    : "[data-photo-adddate-button]",

					"{locationWidget}"  : ".es-photo-location-form .es-location",
					"{latitude}"        : "[data-location-lat]",
					"{longitude}"       : "[data-location-lng]",

					"{dateDay}"  : "[name=date-day]",
					"{dateMonth}": "[name=date-month]",
					"{dateYear}" : "[name=date-year]",

					"{actionsMenu}"  : "[data-item-actions-menu]",
					"{featureButton}": "[data-photo-feature-button]",
					"{coverButton}"  : "[data-photo-cover-button]",

					"{editButton}"    : "[data-photo-edit-button]",
					"{editButtonLink}": "[data-photo-edit-button] > a",
					"{doneButton}"     : "[data-photo-done-button]",
					"{doneButtonLink}" : "[data-photo-done-button] > a",

					"{moveButton}"  : "[data-photo-move-button]",
					"{deleteButton}": "[data-photo-delete-button]",

					"{rotateLeftButton}": "[data-photo-rotateLeft-button]",
					"{rotateRightButton}": "[data-photo-rotateRight-button]",

					"{profileAvatarButton}": "[data-photo-profileAvatar-button]",
					"{profileCoverButton}": "[data-photo-profileCover-button]"
				}
			},
			function(self) { return {

				init: function() {
				},

				data: function() {

					return {
						id        : self.photo.id,
						title     : self.titleField().val(),
						caption   : self.captionField().val(),
						date      : self.formatDate(),
						address   : self.locationCaption().html(),
						latitude  : self.latitude().val(),
						longitude : self.longitude().val()
					}
				},

				save: function() {

					var data = self.data();

					self.clearMessage();

					var task =
						EasySocial.ajax(
							"site/controllers/photos/update",
							data
						)
						.done(function(photo){

							self.photo.setLayout("item");
						})
						.fail(function(){

							self.setMessage(message, "error");
						})
						.progress(function(message, type){

							if (type=="success") {
								self.setMessage(message);
							}
						});

					self.trigger("photoSave", [task, self]);

					return task;
				},

				enable: function() {

					self.photo.setLayout("form");

					// If we are running under an album frame
					var album = self.photo.album;

					if (album) {
						self.element.addClass("active");
					}

					self.trigger("enabled", [self]);
				},

				disable: function() {

					self.photo.setLayout("item");

					// If we are running under an album frame
					var album = self.photo.album;
					
					if (album) {
						self.element.removeClass("active");
					}

					self.trigger("disabled", [self]);
				},

				setImage: function(type) {

					var image       = self.photo.image(),
						imageSource = self.photo[type + "Image"](),
						imageUrl    = imageSource.data("src"),
						imageLoader = imageSource.data("loader");

					// If this image hasn't been loaded before
					if (!imageLoader) {

						// Create an image loader
						imageLoader = $.Image.get(imageUrl);

						// Store a reference of the loader within the element
						imageSource.data("loader", imageLoader); 
					}

					imageLoader
						.done(function(){
							image.css("backgroundImage", "url(" + imageUrl + ")");
						});

					return imageLoader;
				},

				"{featureButton} click": function(featureButton, event) {

					event.stopPropagation();

					var isFeatured = self.element.hasClass("featured");

					// Add featured class & switch image size
					self.element
						.addTransitionClass("featuring", 800)
						.toggleClass("featured", !isFeatured);

					setTimeout(function(){
						self.setImage((isFeatured) ? "thumbnail" : "featured");
					}, 1000);

					// Perform an ajax call to mark the photo as featured
					var task = 
						EasySocial.ajax(
							"site/controllers/photos/feature",
							{
								id: self.photo.id
							}
						)
						.done(function( message , isFeatured ) {
							
							// If this is not under album, show a message
							// if (!self.photo.album) {
							// 	self.clearMessage();
							// 	self.setMessage( message );	
							// }
							
							featureButton.toggleClass('btn-es-primary', isFeatured);
						})
						.fail(function() {

							// Revert changes
							self.element
								.addTransitionClass("featuring", 800)
								.toggleClass("featured", isFeatured);

							setTimeout(function(){
								self.setImage((!isFeatured) ? "thumbnail" : "featured");
							}, 1000);
						});

					self.trigger("photoFeature", [task, self.photo, !isFeatured]);
				},			

				"{coverButton} click": function() {

					var album = self.photo.album;

					// When viewing photos invidually,
					// there is no reference to album,
					// the button itself should't be visible anyway.
					if (!album) return;

					// If the editor is available, set cover.
					album.editor && album.editor.setCover(self.photo.id);
				},

				"{dateDay} keyup": function() {
					self.updateDate();
				},

				"{dateMonth} change": function() {
					self.updateDate();
				},

				"{dateYear} keyup": function() {
					self.updateDate();
				},

				updateDate: function() {

					setTimeout(function(){
						self.date().addClass("has-data");
						var dateCaption = self.dateDay().val() + ' ' + $.trim(self.dateMonth().find(":selected").text() + ' ' + self.dateYear().val());
						self.dateCaption().html(dateCaption);
					}, 1);
				},

				formatDate: function() {
					var day = self.dateDay().val() || self.dateDay().data('date-default'),
						month = self.dateMonth().val() || self.dateMonth().data('date-default'),
						year = self.dateYear().val() || self.dateYear().data('date-default');

					return year + '-' + month + '-' + day;
 				},

				"{locationWidget} locationChange": function(el, event, location) {

					var address = location.formatted_address;
					self.locationCaption().html(address);
					self.location().addClass("has-data");
				},

				rotate: function(angle) {

					var photo = self.photo;

					// Show loading indicator
					photo.content().addClass("loading");

					var task = 
						EasySocial.ajax(
							"site/controllers/photos/rotate",
							{
								id: photo.id,
								angle: angle
							}
						)
						.done(function(photoObj) {

							var url;

							if (self.photo.album) {
								url = photoObj.sizes.thumbnail.url;
							} else {
								url = photoObj.sizes.large.url;
							}

							// Replace image url
							photo.image()
								.css({
									backgroundImage: $.cssUrl(url)
								});

							self.element
								.addTransitionClass("rotating-ready", 150)
								.removeClass("rotating-right rotating-left");
						})
						.fail(function(message, type) {

							self.setMessage(message, type);
						})
						.always(function(){

							photo.content().removeClass("loading");
						});

					self.trigger("photoRotate", [task, angle, photo])
				},

				"{rotateRightButton} click": function() {

					self.element.addClass("rotating-right");
					self.rotate(90);
				},

				"{rotateLeftButton} click": function() {

					self.element.addClass("rotating-left");
					self.rotate(-90);
				},				

				"{moveButton} click": function() {

					var photo = self.photo;

					var dialog =
						EasySocial.dialog({
							content: EasySocial.ajax(
								"site/views/photos/moveToAnotherAlbum",
								{
									id: photo.id
								}
							),
							bindings: {
								"{moveButton} click": function() {

									var targetAlbumId = this.albumSelection().val();

									var task = 
										EasySocial.ajax(
											"site/controllers/photos/move",
											{
												id: photo.id,
												albumId: targetAlbumId
											}
										)
										.always(function(){
											dialog.close();
										});

									self.trigger("photoMove", [task, photo, targetAlbumId]);
								}
							}
						});
				},

				"{deleteButton} click": function() {

					var photo = self.photo;

					EasySocial.dialog({
						content: EasySocial.ajax(
							"site/views/photos/confirmDelete",
							{
								id: photo.id
							}
						),
						bindings: {
							"{deleteButton} click": function(deleteButton) {

								var dialog = this.parent;

								deleteButton.disabled(true);

								var task = 
									EasySocial.ajax(
										"site/controllers/photos/delete",
										{
											id: photo.id
										}
									)
									.always(function(){
										dialog.close();
									});

								self.trigger("photoDelete", [task, photo]);
							}
						}
					});
				},

				"{editButton} click": function() {

					// Change viewer layout
					self.photo.setLayout("form");

					// Change address bar url
					self.editButtonLink().route();
				},

				"{editButtonLink} click": function(editButtonLink, event) {

					event.preventDefault();
				},

				"{doneButton} click": function() {

					self.save()
						.done(function(){

							// Change album layout
							self.photo.setLayout("item");

							// Change address bar url
							self.doneButtonLink().route();
						})
						.fail(function(){

						});
				},

				"{doneButtonLink} click": function(doneButtonLink, event) {
					event.preventDefault();
				},

				"{profileAvatarButton} click": function() {
					EasySocial.photos.createAvatar(self.photo.id);
				}			
			}});

			module.resolve(Controller);

		});
});

EasySocial.module("photos/item", function($){

	var module = this;

	// Non-essential dependencies
	EasySocial.require()
		.script(
			"photos/tags",
			"photos/editor",
			"photos/tagger",
			"photos/navigation"
		)
		.done();

	// Essential dependencies
	EasySocial.require()
		.library(
			"image"
		)
		.done(function(){

			var Controller =
			EasySocial.Controller("Photos.Item",
			{
				hostname: "photo",

				defaultOptions: {

					editable: false,
					taggable: false,
					navigation: false,

					"{header}"            : "[data-photo-header]",
					"{content}"           : "[data-photo-content]",
					"{footer}"            : "[data-photo-footer]",

					"{info}"              : "[data-photo-info]",
					"{title}"             : "[data-photo-title]",
					"{titleLink}"         : "[data-photo-title-link]",
					"{caption}"           : "[data-photo-caption]",

					"{image}"             : "[data-photo-image]",
					"{imageLink}"         : "[data-photo-image-link]",
					"{thumbnailImage}"    : "[data-photo-image-thumbnail]",
					"{featuredImage}"     : "[data-photo-image-featured]",
					"{largeImage}"        : "[data-photo-image-large]",

					"{menu}"              : "[data-photo-menu]",
					"{actions}"           : "[data-item-actions]",
					"{actionsMenu}"       : "[data-item-actions-menu]",

		            "{comments}"          : "[data-comments]",
					"{share}"			  : "[data-repost-action]",
					"{likes}"			  : "[data-likes-action]",
					"{likeContent}" 	  : "[data-likes-content]",
					"{repostContent}" 	  : "[data-repost-content]",
					"{counterBar}"	  	  : "[data-stream-counter]",

					"{privacy}"           : "[data-es-privacy-container]",

					"{likeCount}"    : "[data-photo-like-count]",
					"{commentCount}" : "[data-photo-comment-count]",
					"{tagCount}"     : "[data-photo-tag-count]"
				}
			},
			function(self) { return {

				init: function() {

					self.id = self.element.data("photoId");

					// Also implement tags when it is available
					EasySocial.module("photos/tags")
						.done(function(TagsController){
							self.tags = self.addPlugin("tags", TagsController);
						});

					// If this photos is editable, load & implement editor.
					if (self.options.editable) {
						EasySocial.module("photos/editor")
							.done(function(EditorController){
								self.editor = self.addPlugin("editor", EditorController);
							});
					}

					if (self.options.taggable) {
						EasySocial.module("photos/tagger")
							.done(function(TaggerController){
								self.tagger = self.addPlugin("tagger", TaggerController);
							});
					}

					if (self.options.navigation) {
						EasySocial.module("photos/navigation")
							.done(function(NavigationController){
								self.navigation = self.addPlugin("navigation", NavigationController);
							});
					}
				},

				data: function() {

					return {
						id        : self.id,
						title     : $.trim(self.title().text()),
						caption   : $.trim(self.caption().text()),
						sizes: {
							thumbnail: self.thumbnailImage().data("src"),
							featured : self.featuredImage().data("src"),
							large    : self.largeImage().data("src")
						}
					}
				},				

				setLayout: function(layoutName) {

					// Switch layout
					self.element
						.data("photoLayout", layoutName)
						.switchClass("layout-" + layoutName);

					// Trigger layout change event
					self.trigger("layoutChange", [layoutName, self]);
				},

				"{self} click": function(el, event) {

					var target = $(event.target),
						menu = self.menu();

					// If the area being click is the photo menu, stop.
					if (target.parents().andSelf().filter(menu).length > 0) return;

					// Activate item
					self.trigger("activate", [self]);
				},

				"{self} photoSave": function(el, event, task) {

					task
						.done(function(photo, html){
							self.info().replaceWith(html);
						});
				},

				"{self} photoDelete": function(el, event, task) {

					task
						.done(function(){
						})
						.fail(function(message, type){
							self.setMessage(message, type);
						});
				},
				
				"{imageLink} click": function(imageLink, event) {

					event.preventDefault();
				},

				"{titleLink} click": function(titleLink, event) {

					// event.preventDefault();
				},

                "{self} dropdownOpen": function() {
                     self.element.addClass("show-all");
                },

                "{self} dropdownClose": function() {
                     self.element.removeClass("show-all");
                },

                "{share} create": function(el, event, itemHTML) {
                	self.counterBar().removeClass('hide');
                },

 				"{likes} onLiked": function(el, event, data) {

					//need to make the data-stream-counter visible
					self.counterBar().removeClass( 'hide' );
					self.count("like", 1, true);
				},

				"{likes} onUnliked": function(el, event, data) {

					var isLikeHide 		= self.likeContent().hasClass('hide');
					var isRepostHide 	= self.repostContent().hasClass('hide');

					if( isLikeHide && isRepostHide )
					{
						self.counterBar().addClass( 'hide' );
					}

					self.count("like", -1, true);
				},

				"{self} tagAdd": function() {
					self.count("tag", 1, true);
				},

				"{self} tagRemove": function() {
					self.count("tag", -1, true);
				},

				"{comments} newCommentSaved": function() {
					self.count("comment", 1, true);
				},

				"{comments} commentDeleted": function() {
					self.count("comment", -1, true);
				},

				"{privacy} activate": function() {
					setTimeout(function(){
						self.element.addClass("show-all")
					}, 0);
				},

				"{privacy} deactivate": function() {
					self.element.removeClass("show-all");
				},

				count: function(subject, val, append) {

					var statSelector = self[subject + "Count"];

					if (!$.isFunction(statSelector)) return;

					// Get stat element
					var stat = statSelector();

					// If no stat element found, stop.
					if (stat.length < 0) return;

					// Get current stat count
					var statCount;

					if (append) {
						statCount = (parseInt(stat.text()) || 0) + (parseInt(val) || 0);
					} else {
						statCount = val;
					}
					
					// Always stays at 0 if less than that
					if (statCount < 0) statCount = 0;
					
					// Update stat count
					stat.text(statCount);
				}

			}});

			module.resolve(Controller);

		});
});


EasySocial.module("photos/tags", function($){

	var module = this;

	// Non essential dependencies
	EasySocial.require()
		.library("scrollTo")
		.done();

	EasySocial.require()
		.done(function(){

			var Controller =
			EasySocial.Controller("Photos.Tags",
			{
				hostname: "tags",

				defaultOptions: {

					"{viewport}"    : "[data-photo-tag-viewport]",
					"{tagItem}"     : "[data-photo-tag-item]",
					"{tagButton}"   : "[data-photo-tag-button]",

					"{tagListItemGroup}": "[data-photo-tag-list-item-group]",
					"{tagListItem}"     : "[data-photo-tag-list-item]"
				}
			},
			function(self) { return {

				init: function() {

					self.setLayout();
				},

				imageLoaders: {},

				setLayout: function(callback) {

					var imageHolder   = self.photo.image(),
						imageUrl      = $.uri(imageHolder.css("backgroundImage")).extract(0),
						imageLoaders  = self.imageLoaders,
						imageLoader   = imageLoaders[imageUrl] || (self.imageLoaders[imageUrl] = $.Image.get(imageUrl));

					imageLoader
						.done(function(imageEl, image){

							self.viewport()
								.css(
									$.Image.resizeWithin(
										image.width,
										image.height,
										imageHolder.width(),
										imageHolder.height()
									)
								);

							callback && callback();
						});
				},

				getTagItem: function(tagId) {
					return self.tagItem().filterBy("photoTagId", tagId);
				},

				getTagListItem: function(tagId) {
					return self.tagListItem().filterBy("photoTagId", tagId);
				},

				getTaggedUsers: function() {

					var users = [];

					self.tagListItem("[data-photo-tag-uid]")
						.each(function(){
							users.push($(this).data("photoTagUid"));
						});

					return $.uniq(users);
				},

				activateTag: function(tagId) {

					self.getTagItem(tagId)
						.addClass("active");

					self.getTagListItem(tagId)
						.addClass("active");
				},

				deactivateTag: function(tagId) {

					self.getTagItem(tagId)
						.removeClass("active");

					self.getTagListItem(tagId)
						.removeClass("active");
				},

				"{tagListItem} click": function(el) {

					var method = (el.hasClass('active') ? "deactivate" : "activate") + "Tag",
						tagId  = el.data("photoTagId");

					// Toggle tag
					self[method](tagId);
				},

				"{tagListItem} mouseover": function(el) {

					self.getTagItem(el.data("photoTagId"))
						.addClass("focus");
				},

				"{tagListItem} mouseout": function(el) {

					self.getTagItem(el.data("photoTagId"))
						.removeClass("focus");
				},

				"{self} tagRemove": function(el, event, task, tagId) {

					task.done(function(){

						// Remove tag item
						self.getTagItem(tagId).remove();

						// Remove tag list item
						self.getTagListItem(tagId).remove();
					});
				},

				"{self} photoRotate": function(el, event, task, angle, photo) {

					task.done(function(photoObj, tags){

						setTimeout(function(){

							self.setLayout(function(){

								var tagItems = self.tagItem();

								$.each(tags, function(i, tag){

									var tagItem = tagItems.filterBy("photoTagId", tag.id);

									tagItem
										.css({
											width : (tag.width  * 100) + "%",
											height: (tag.height * 100) + "%",
											top   : (tag.top    * 100) + "%",
											left  : (tag.left   * 100) + "%"
										});
								});

							});

						}, 1);

					});

				}
			}});

			module.resolve(Controller);

		});
});


EasySocial.module("photos/tagger", function($){

	var module = this;

	var KEYCODE = {
		BACKSPACE: 8,
		COMMA: 188,
		DELETE: 46,
		DOWN: 40,
		ENTER: 13,
		ESCAPE: 27,
		LEFT: 37,
		RIGHT: 39,
		SPACE: 32,
		TAB: 9,
		UP: 38
	};

	// Non essential dependencies
	EasySocial.require()
		.library("scrollTo")
		.done();

	EasySocial.require()
		.view(
			"site/photos/tags.item",
			"site/photos/tags.menu.item"
		)
		.done(function(){

			var Controller =
			EasySocial.Controller("Photos.Tagger",
			{
				hostname: "tagger",

				defaultOptions: {

					view: {
						tagItem: "site/photos/tags.item"
					},

					width: 100,
					height: 100,

					drawTolerance: 30,

					"{viewport}"    : "[data-photo-tag-viewport]",
					"{tagItem}"     : "[data-photo-tag-item]",
					"{tagSelection}": "[data-photo-tag-item].new",
					"{tagButton}"   : "[data-photo-tag-button]",

					"{tagListItemGroup}": "[data-photo-tag-list-item-group]",
					"{tagListItem}"     : "[data-photo-tag-list-item]",

					"{tagRemoveButton}" : "[data-photo-tag-remove-button]"
				}
			},
			function(self) { return {

				init: function() {
				},

				newTagItem: function() {

					// Use existing tag item if created
					var viewport = self.viewport(),
						newTagItem = self.tagItem(".new");

					// Else create one
					if (newTagItem.length < 1) {
						newTagItem =
							self.view.tagItem()
								.addClass("new")
								.appendTo(viewport);

						self.addSubscriber(
							newTagItem.addController("EasySocial.Controller.Photos.Tag")
						);
					}

					return newTagItem;
				},

				"{tagButton} click": function(tagButton) {

					self[tagButton.data("photoTagButton") || "toggle"]();
				},

				disabled: true,

				enable: function() {

					self.disabled = false;
					self.element.addClass("tagging");

					// If there is scrollTo
					if ($.scrollTo) {
						$.scrollTo(self.photo.content(), 250, {offset: {top: -100}});
					}

					self.trigger("tagEnter");
				},

				disable: function() {

					// Remove tag selection
					self.tagSelection().remove();

					// Unfocus any tags which are in focus
					self.tagItem(".focus").removeClass("focus");

					self.disabled = true;
					self.element.removeClass("tagging");

					self.trigger("tagLeave");
				},

				toggle: function() {

					self[(self.disabled) ? "enable" : "disable"]();
				},

				area: {},

				calculateArea: function(collision, offset) {

					// Normalize arguments
					if (!collision) { collision = "clip" };
					if (!offset)    { offset = {x: 0, y: 0} };

					// Calculate image area
					var viewportEl   = self.viewport(),
						viewport        = viewportEl.offset();
						viewport.width  = viewportEl.width();
						viewport.height = viewportEl.height();
						viewport.right  = viewport.width  + viewport.left;
						viewport.bottom = viewport.height + viewport.top;

					// Calculate area relative to screen
					// top, left, width, height, right, bottom
					var area = self.area;
					area.top    = ((area.startY <= area.endY) ? area.startY : area.endY) + offset.y;
					area.left   = ((area.startX <= area.endX) ? area.startX : area.endX) + offset.x;
					area.width  = Math.abs(area.endX - area.startX);
					area.height = Math.abs(area.endY - area.startY);
					area.right  = area.width  + area.left;
					area.bottom = area.height + area.top;

					// Collision handling
					if (collision=="clip") {

						// Cap area within image boundaries
						if (area.top    <= viewport.top   ) {area.top    = viewport.top;   }
						if (area.bottom >= viewport.bottom) {area.bottom = viewport.bottom;}
						if (area.left   <= viewport.left  ) {area.left   = viewport.left;  }
						if (area.right  >= viewport.right ) {area.right  = viewport.right; }

						// Resize tag
						area.width  = area.right  - area.left;
						area.height = area.bottom - area.top;
					}

					// Reposition tag
					if (collision=="flip") {

						if (area.top <= viewport.top) {
							area.top = viewport.top;
						}

						if (area.left <= viewport.left) {
							area.left = viewport.left;
						}

						if (area.right >= viewport.right) {
							area.right = viewport.right;
							area.left  = area.right - area.width;
						}

						if (area.bottom >= viewport.bottom) {
							area.bottom = viewport.bottom;
							area.top    = area.bottom - area.height;
						}
					}

					// Pixel unit
					area.pixel = {
						top   : area.top  - viewport.top,
						left  : area.left - viewport.left,
						width : area.width,
						height: area.height
					};

					// Decimal unit
					area.decimal = {
						top   : area.pixel.top  / viewport.height,
						left  : area.pixel.left / viewport.width,
						width : area.width      / viewport.width,
						height: area.height     / viewport.height
					}

					// Percentage unit
					area.percentage = {
						top   : (area.decimal.top    * 100) + "%",
						left  : (area.decimal.left   * 100) + "%",
						width : (area.decimal.width  * 100) + "%",
						height: (area.decimal.height * 100) + "%"
					};

					// Decide whether tag should be on custom size
					var tolerance = self.options.drawTolerance;

					self.autodraw =
						(area.width  < tolerance &&
						 area.height < tolerance);

					return area;
				},

				setPivot: function(type, x, y) {

					var area = self.area;
						area[type + "X"] = x;
						area[type + "Y"] = y;
				},

				drawing: false,

				autodraw: false,

				drawTag: function() {

					var area = self.calculateArea();
						options = self.options;

					if (self.autodraw) {

						area.endX = area.startX + options.width;
						area.endY = area.startY + options.height;

						self.calculateArea("flip", {
							x: options.width / -2,
							y: options.height / -2
						});
					}

					self.newTagItem()
						.css(area.percentage)
						.trigger("focusInput");
				},

				"{viewport} mousedown": function(viewport, event) {

					if (self.disabled) return;

					if (event.target!==viewport[0]) return;

					event.preventDefault();

					// Hide last created tag item which are currently in focus
					self.tagItem(".focus").removeClass("focus");

					self.drawing = true;
					self.setPivot("start", event.pageX, event.pageY);

					$(document)
						.on("mousemove.tagger", function(event) {
							if (!self.drawing) return;
							self.setPivot("end", event.pageX, event.pageY);
							self.drawTag();
						})
						.on("mouseup.tagger", function(event) {
							self.setPivot("end", event.pageX, event.pageY);
							self.drawTag();
							$(document).off("mousemove.tagger mouseup.tagger");
						});
				},

				createTag: function(data) {

					var data = $.extend(
						{photo_id: self.photo.id}, data, self.area.decimal
					);

					var task = EasySocial.ajax("site/controllers/photos/createTag", data);

					self.trigger("tagCreate", [task, data, self]);

					return task;
				},

				removeTag: function(id) {

					var task = EasySocial.ajax("site/controllers/photos/removeTag", {id: id});

					self.trigger("tagRemove", [task, id, self]);

					return task;
				},

				addTag: function(data, tagItemHtml, tagListItemHtml) {

					// Add tag to viewport and focus on tag
					var tagItem =
						$.buildHTML(tagItemHtml)
							.addClass("focus")
							.appendTo(self.viewport());

					// Add tag list item to tag list
					var tagListItem =
						$.buildHTML(tagListItemHtml)
							.appendTo(self.tagListItemGroup());

					self.trigger("tagAdd", [data, tagItem, tagListItem, self]);
				},

				"{self} avatarEnter": function() {

					// When entering avatar mode, hide all tags.
					self.tagItem().hide();

					// Disable tagging mode
					self.disable();
				},

				"{self} avatarLeave": function() {

					// When leaving avatar mode, display all tags.
					self.tagItem().show();
				},

				"{tagRemoveButton} click": function(button, event) {

					var tagId = button.data("photoTagId");

					self.removeTag(tagId);

					event.stopPropagation();
				},

				// Give priority to remove button,
				// make tag viewport appear above of
				// navigation buttons when they are hovered.
				"{tagRemoveButton} mouseover": function() {

					self.viewport().addClass("active");
				},

				"{tagRemoveButton} mouseout": function() {

					self.viewport().removeClass("active");
				}

			}});

			EasySocial.Controller("Photos.Tag",
			{
				defaultOptions: {

					view: {
						menuItem: "site/photos/tags.menu.item"
					},

					"{form}"        : "[data-photo-tag-form]",
					"{title}"       : "[data-photo-tag-title]",
					"{removeButton}": "[data-photo-tag-remove-button]",
					"{textField}"   : "[data-photo-tag-input]",
					"{menu}"        : "[data-photo-tag-menu]",
					"{menuItem}"    : "[data-photo-tag-menu-item]"
				}
			},
			function(self) { return {

				init: function() {

					self.data = self.options.data;
				},

				"{self} focusInput": function() {

					self.textField().focus();
				},

				"{textField} keyup": $._.debounce(function(el, event) {

					var keyword = $.trim(self.textField().val());

					switch (event.keyCode) {

						case KEYCODE.UP:
						case KEYCODE.DOWN:
						case KEYCODE.ENTER:
						case KEYCODE.ESCAPE:
							// Don't repopulate if these keys were pressed.
							break;

						default:
							// Build a list of users to exclude
							var users = self.tagger.photo.tags.getTaggedUsers();

							EasySocial.ajax(
							   "site/controllers/friends/suggestPhotoTagging",
							   {
							   	   search: keyword,
							   	   exclude: users,
							   	   includeme: '1'
							   })
								.done(self.render());
							break;
					}

				}, 250),

				"{textField} keypress": function(textField, event) {

					var keyword = $.trim(self.textField().val());

					// Get active menu item
					var activeMenuItem = self.menuItem(".active");

					switch (event.keyCode) {

						// If up key is pressed
						case KEYCODE.UP:

							// Deactivate all menu item
							self.menuItem().removeClass("active");

							// If no menu items are activated,
							if (activeMenuItem.length < 1) {

								// activate the last one.
								self.menuItem(":last").addClass("active");

							// Else find the menu item before it,
							} else {

								// and activate it.
								activeMenuItem.prev(self.menuItem.selector)
									.addClass("active");
							}

							event.preventDefault();
							break;

						// If down key is pressed
						case KEYCODE.DOWN:

							// Deactivate all menu item
							self.menuItem().removeClass("active");

							// If no menu items are activated,
							if (activeMenuItem.length < 1) {

								// activate the first one.
								self.menuItem(":first").addClass("active");

							// Else find the menu item after it,
							} else {

								// and activate it.
								activeMenuItem.next(self.menuItem.selector)
									.addClass("active");
							}

							event.preventDefault();
							break;

						// If enter is pressed
						case KEYCODE.ENTER:

							// Use menu item
							if (activeMenuItem.length > 0) {

 								activeMenuItem.trigger("click");

 							// Create custom label
 							} else {
								self.create({
									type: "label",
									label: keyword
								});
 							};

							self.menu().hide();
							break;

						// If escape is pressed,
						case KEYCODE.ESCAPE:

							// hide menu.
							self.menu().hide();
							break;
					}
				},

				"{menuItem} mouseover": function(menuItem) {

					self.menuItem().removeClass("active");

					menuItem.addClass("active");
				},

				"{menuItem} mouseout": function(menuItem) {

					self.menuItem().removeClass("active");
				},

				render: $.Enqueue(function(items) {

					var menu = self.menu();

					if (!items || items.length < 1) {
						menu.hide();
						return;
					}

					menu.empty();

					$.each(items, function(i, item) {

						self.view.menuItem({item: item})
							.data("item", item)
							.appendTo(menu);

						menu.show();
					});
				}),

				create: function(data) {

					var tag = self.element;

					// Store tag data
					self.data = data;

					// Update tag title
					self.title()
						.html(data.label);

					// Do not submit empty label
					if ($.trim(data.label)==="") return;

					// Create tag
					self.tagger.createTag(data)
						.done(function(tag, tagItemHtml, tagListItemHtml){

							// Add new tag
							self.tagger.addTag(tag, tagItemHtml, tagListItemHtml);

							// Destroy myself
							self.element.remove();
						})
						.fail(function(){
							tag.remove();
						});
				},

				remove: function() {

					var tag = self.element,
						tagId = (self.data || {}).id;

					// If this is a new tag, just remove element;
					if (!tagId) return tag.remove();

					// Remove tag
					self.tagger.removeTag(tagId)
						.done(function(){
							tag.remove();
						});
				},

				"{menuItem} click": function(menuItem) {

					var item = menuItem.data("item");

					self.create({
						uid  : item.id,
						type : "person",
						label: item.screenName,
					});
				},

				"{removeButton} click": function() {

					self.remove();
				}

			}});

			module.resolve(Controller);

		});
});

EasySocial.module("photos/navigation", function($){


	$.fn.intersectsWith = function(top, left, width, height) {

		var offset = this.offset(),

			reference = {
				top   : offset.top,
				left  : offset.left,
				bottom: offset.top  + (sourceHeight = this.height()),
				right : offset.left + (sourceWidth  = this.width()),
				width : sourceWidth,
				height: sourceHeight
			},

			subject = {
				top   : top,
				left  : left,
				bottom: top  + (height || (height = 0)),
				right : left + (width  || (width  = 0)),
				width : width,
				height: height 
			},

			intersects = (
				reference.left <= subject.right    &&
				subject.left   <= reference.right  &&
	          	reference.top  <= subject.bottom   &&
	          	subject.top    <= reference.bottom
			);

		return (intersects) ? {reference: reference, subject: subject} : false;
	};

	var module = this;

	var Controller =

		EasySocial.Controller("Photos.Navigation",
		{
			hostname: "navigation",

			defaultOptions: {
				"{navButton}" : ".es-photo-nav-button",
				"{nextButton}": "[data-photo-next-button]",
				"{prevButton}": "[data-photo-prev-button]"
			}
		},
		function(self) { return {

			init: function() {

			},

			disabled: false,

			disable: function() {
				self.disabled = true;
			},

			enable: function() {
				self.disabled = false;
			},

			"{window} mousemove": function(el, event) {

				if (self.disabled) return;

				self.currentDirection = null;

				self.navButton().removeClass("active");

				// If user is not moving within the photo content, stop.
				if ($(event.target).parents().filter(self.photo.content.selector).length < 1) return;

				var offset = 
						self.photo.content()
							.intersectsWith(event.pageY, event.pageX);

				if (offset) {

					var direction = 
						(offset.subject.left < (offset.reference.right - (offset.reference.width / 2))) ?
							"prev" : "next",

						button = self[direction + "Button"]().addClass("active");

						self.currentDirection = direction;
				}
			},

			"{self} tagEnter": function() {
				self.disable();
			},

			"{self} tagLeave": function() {
				self.enable();
			},

			"{self} click": function(el, event) {

				if (self.disabled) return;

				// If user is not clicking within the photo content, stop.
				if ($(event.target).parents().filter(self.photo.content.selector).length < 1) return;

				var direction = self.currentDirection;

				if (!direction) return;

				self.trigger("photo" + $.String.capitalize(direction), [self.photo]);
			},

			"{self} photoNext": function() {
				// Photo browser handles this
			},

			"{self} photoPrev": function() {
				// Photo browser handles this
			}

		}});

	module.resolve(Controller);

});

EasySocial.module( 'privacy' , function($) {

var module = this;

	EasySocial.require()
	.library( 'dialog' )
	.view( 'admin/profiles/form.privacy.custom.item' )
	.done(function($){

		EasySocial.Controller(
				'Profiles.Form.Privacy',
				{
					defaultOptions: {

						path: 'admin',

						// Elements
						"{selection}"			: ".privacySelection",
						"{browseButton}"    	: ".browseButton",
						"{userDeleteButton}"	: ".userDeleteButton",
						"{customContainer}"		: ".customContainer",

						view: {
							customItem : 'admin/profiles/form.privacy.custom.item'
						}

					}
				},
				function( self ){

					return {

						init: function()
						{

						},

						/**
						 * Binds the privacy's rule type select.
						 */
						"{selection} change" : function( el , event ){

							var selected	= $(el).val();
							var eleName		= $(el).attr( 'name' );

							if( selected == 'custom' )
							{
								self.customContainer().show();
							}
							else
							{
								self.customContainer().hide();
							}

						},

						"{userDeleteButton} click" : function( el, event ) {
							$(el).parents('li').remove();
						},

						"{browseButton} click" : function( el, event ) {


							var eleId		= $(el).attr( 'id' );
							var eleIndex 	= $(el).data('index');

							var userlistingpath = $.rootPath + 'administrator/index.php?option=com_easysocial&view=users&layout=listing&show=iframe';

							if( self.options.path == 'site' )
								userlistingpath = $.rootPath + 'index.php?option=com_easysocial&view=friends&layout=listing&show=iframe';

 							$.dialog({
 								title: 'Browse Users & Groups',
 								content: userlistingpath,
					            body: {
					                css: {
					                    width: 400,
					                    height: 300
					                }
					            },
								buttons: [
									{
										name : 'Assign',
										click : function(){

											var users = $('.foundryDialog').find('iframe').contents().find('input:checked');

											if( users.length > 0 )
											{
												for(var i = 0; i < users.length; i++)
												{
													var eleName = $(users[i]).attr('name');

													if( eleName == 'toggle')
														continue;

													var userId = $(users[i]).val();
													var userName = $('.foundryDialog').find('iframe').contents().find('input[name="user_' +userId+ '"]').val();

													var addedUsers = $('ul#privacy_ul' + eleIndex + ' input:hidden');
													var doAdd      = true;

													if( addedUsers.length > 0 )
													{
														for( var j=0; j < addedUsers.length; j++ )
														{
															if( $(addedUsers[j]).val() == userId )
															{
																doAdd = false;
																break;
															}
														}
													}

													if( doAdd )
													{
														html = self.view.customItem({
														 	userName : userName,
															eleName : eleId,
															userId  : userId
														});

														$('ul#privacy_ul' + eleIndex ).append( html );
													}
												}
											}

											//$("#google").contents().find("#hplogo").remove());

											$.dialog().close();
										}
									},
									{
										name : 'Close',
										click : function(){
											$.dialog().close();
										}
									}

								]
 							});



						}


					} //end return
				}//end function(self)
		);

		module.resolve();
	});

});

EasySocial.module('sharing', function($) {

	var module = this;

	EasySocial.require().library('textboxlist').done(function() {

		EasySocial.Controller('Sharing', {
			defaultOptions: {
				'{vendors}'		: '[data-sharing-vendor]',

				'{emailForm}'	: '[data-sharing-email]'
			}
		}, function(self) {
			return {
				init: function() {
					self.initLinks();

					self.initEmail();
				},

				initLinks: function() {
					$.each(self.vendors(), function(i, vendor) {

						vendor = $(vendor);

						if(!vendor.data('loaded')) {

							// Extract the href
							var link = vendor.attr('href');

							// Assign it to a data
							vendor.data('href', link);

							// Assign a void to the href
							vendor.attr('href', 'javascript:void(0);');

							// Assign loaded state
							vendor.attr('loaded', true);
						}
					});
				},

				initEmail: function() {
					$.each(self.emailForm(), function(i, form) {

						form = $(form);

						if(!form.data('loaded')) {

							// Implement email form controller
							self.addPlugin('email');

							// Assign loaded state
							form.attr('loaded', true);
						}
					});
				},

				'{vendors} click': function(el, ev) {
					var optionString = el.data('options') || '';

					window.open(el.data('href'), '', optionString);
				}
			}
		});

		$.template('sharing/recipientContent', '[%= title %]<input type="hidden" name="items" value="[%= title %]" />');

		EasySocial.Controller('Sharing.Email', {
			defaultOptions: {
				token			: '',

				'{container}'	: '[data-sharing-email]',

				'{frames}'		: '[data-sharing-email-frame]',

				'{recipients}'	: '[data-sharing-email-recipients]',

				'{input}'		: '[data-sharing-email-input]',

				'{content}'		: '[data-sharing-email-content]',

				'{send}'		: '[data-sharing-email-send]',

				// Frames
				'{frames}'		: '[data-sharing-email-frame]',

				'{frameForm}'	: '[data-sharing-email-form]',

				'{frameSending}': '[data-sharing-email-sending]',

				'{frameDone}'	: '[data-sharing-email-done]',

				'{frameFail}'	: '[data-sharing-email-fail]',

				'{failMsg}'		: '[data-sharing-email-fail-msg]'
			}
		}, function(self) {
			return {
				init: function() {

					self.options.token = self.container().data('token');

					// Initiate textboxlist plugin
					var test = self.recipients().textboxlist({
						view: {
							itemContent: 'sharing/recipientContent'
						}
					});

					self.originalPosition = self.container().css('position');
				},

				getRecipients: function() {
					var items = self.recipients().controller('textboxlist').getAddedItems();

					var recipients = [];

					$.each(items, function(i, item) {
						recipients.push(item.title);
					});

					var input = self.input().val();

					if(recipients.length < 1 && !$.isEmpty(input)) {
						recipients.push(input);
					}

					return recipients;
				},

				getContent: function() {
					return self.content().val();
				},

				'{send} click': function(el, ev) {
					if(el.enabled()) {
						el.disabled(true);

						// Control frames
						self.frames().hide();
						self.frameSending().show();

						// Get the data
						var token = self.options.token,
							recipients = self.getRecipients(),
							content = self.getContent();

						/// Make the ajax call
						self.submitForm(token, recipients, content)
							.done(function() {
								// Control frames
								self.frames().hide();
								self.frameDone().show();

								// Show the form after 1 second
								setTimeout(function() {
									// Clear recipients
									self.recipients().controller('textboxlist').clearItems();

									// Clear content
									self.content().val('');

									// Control frames
									self.frameDone().hide();
									self.frameForm().show();
								}, 1000);
							})
							.fail(function(msg) {
								// Control frames
								self.frames().hide();
								self.frameFail().show();
								self.frameForm().show();

								if(msg !== undefined) {
									self.failMsg().html(msg);
								}
							})
							.always(function() {
								el.enabled(true);
							});
					}

				},

				// Add email address in if comma is pressed
				'{input} keypress': function(el, ev) {
					if(ev.which === 44) {
						self.recipients().controller('textboxlist').addItem(el.val());
						el.val('');
						return false;
					}
				},

				submitForm: function(token, recipients, content) {
					return EasySocial.ajax('site/controllers/sharing/send', {
						token: token,
						recipients: recipients,
						content: content
					});
				}
			}
		});

		module.resolve();

	});
});

EasySocial.module( 'site/activities/activities' , function($){

	var module	= this;

	EasySocial.require()
	.script( 'site/activities/sidebar', 'site/activities/sidebar.item' )
	.view( 'site/loading/small' )
	.done(function($){

		EasySocial.Controller(
		'Activities',
		{
			defaultOptions:
			{
				// Properties
				items		: null,

				// Elements
				"{container}"	: "[data-activities]",

				"{contentTitle}": "[data-activities-content-title]",
				"{content}"		: "[data-activities-content]",
				"{sidebar}"		: "[data-activities-sidebar]",


				"{sidebarItem}"	: "[data-sidebar-item]",

				view :
				{
					loadingContent 	: "site/loading/small"
				}
			}
		},
		function( self ){
			return {

				init : function()
				{
					// Implement sidebar controller.
					self.sidebar().implement( EasySocial.Controller.Activities.Sidebar ,
					{
						"{parent}"	: self
					});

					self.sidebarItem().implement( EasySocial.Controller.Activities.Sidebar.Item ,
					{
						"{parent}"	: self
					});					
				},


				/**
				 * Add a loading icon on the content layer.
				 */
				updatingContents: function()
				{
					self.content().html( self.view.loadingContent() );
				},				

				updateContent: function( content, title )
				{
					self.content().html( content );
					self.contentTitle().html( title );
				}
							
			}
		});

		module.resolve();
	});

});
EasySocial.module( 'site/activities/sidebar' , function($){

	var module	= this;

	EasySocial.require()
	.done(function($){

		EasySocial.Controller(
			'Activities.Sidebar',
			{
				defaultOptions:
				{
					"{menuItem}"	: "[data-sidebar-menu]"
				}
			},
			function( self ){
				return {

					init: function()
					{
					},

					"{menuItem} click" : function( el , event )
					{
						// Remove all active class.
						self.menuItem().removeClass( 'active' );

						// Add active class on this item.
						$( el ).addClass( 'active' );
					}
				}
			});

		module.resolve();
	});

});
EasySocial.module( 'site/activities/sidebar.item' , function($){

	var module	= this;

	EasySocial.require()
	.library( 'history' )
	.done(function($){

		EasySocial.Controller(
			'Activities.Sidebar.Item',
			{
				defaultOptions:
				{
				}
			},
			function( self ){
				return {

					init: function()
					{
					},

					"{self} click" : function( el , event )
					{

						var type 	= self.element.data( 'type' ),
							url 	= self.element.data( 'url' ),
							title 	= self.element.data( 'title' ),
							desc 	= self.element.data( 'description' );

						// If this is an embedded layout, we need to play around with the push state.
						History.pushState( {state:1} , title , url );

						self.parent.updatingContents();

						//ajax call here.
						EasySocial.ajax( 'site/controllers/activities/getActivities',
						{
							"type"		: type
						})
						.done(function( html )
						{
							self.parent.updateContent( html, title );	
						})
						.fail(function( message ){
							console.log( message );
						});

						self.parent.updateContent();
					}
				}
			});

		module.resolve();
	});

});
EasySocial.module( 'site/activities/apps' , function($){

	var module	= this;

	EasySocial.require()
	.view( 'site/loading/small' )
	.language( 'COM_EASYSOCIAL_ACTIVITY_APPS_UNHIDE_SUCCESSFULLY' )
	.done(function($){


		EasySocial.Controller(
			'Activities.Apps.List',
			{
				defaultOptions:
				{
					// Elements
					"{item}"	: "[data-hidden-app-item]",


					// loading gif
					view :
					{
						loadingContent 	: "site/loading/small"
					}

				}
			},
			function( self ){
				return {

					init : function()
					{
						self.item().implement( EasySocial.Controller.Activities.Apps.Item );
					}

				}
			});


		EasySocial.Controller(
			'Activities.Apps.Item',
			{
				defaultOptions:
				{
					// Properties
					id 			: "",
					context 	: "",

					"{unhideLink}" : "[data-hidden-app-unhide]",

					"{content}" : "[data-hidden-app-content]",

					// loading gif
					view :
					{
						loadingContent 	: "site/loading/small"
					}

				}
			},
			function( self ){
				return {

					init : function()
					{
						self.options.id 		= self.element.data( 'id' );
						self.options.context 	= self.element.data( 'context' );
					},

					"{unhideLink} click" : function(){

						EasySocial.ajax( 'site/controllers/activities/unhideapp',
						{
							"context"		: self.options.context,
							"id" 			: self.options.id
						})
						.done(function()
						{
							self.content().html( $.language( 'COM_EASYSOCIAL_ACTIVITY_APPS_UNHIDE_SUCCESSFULLY' ) );

						})
						.fail(function( message ){
							console.log( message );
						});

					}

				}
			});



		module.resolve();
	});

});

EasySocial.module( 'site/activities/item' , function($){

	var module	= this;

	EasySocial.require()
	.script()
	.done(function($){

		EasySocial.Controller(
			'Activities.Item',
			{
				defaultOptions:
				{
					// Elements
					"{toggle}"		: "[data-activity-toggle]",
					"{deleteBtn}"	: "[data-activity-delete]"

				}
			},
			function( self ){
				return {

					init : function()
					{
						// Implement sidebar controller.
					},

					"{toggle} click" : function( el , event )
					{
						EasySocial.ajax( 'site/controllers/activities/toggle',
						{
							"id"		: self.element.data('id'),
							"curState" 	: self.element.data('current-state')
						})
						.done(function( lbl, isHidden)
						{
							$( el ).text( lbl );
							self.element.data('current-state', isHidden);

							if( isHidden )
							{
								self.element.children( "div.es-stream" ).addClass( 'isHidden' );
							}
							else
							{
								self.element.children( "div.es-stream" ).removeClass( 'isHidden' );
							}
						})
						.fail(function( message ){

							console.log( message );
						});
					},

					"{deleteBtn} click" : function()
					{
						var uid = self.element.data('id');

						EasySocial.dialog({
							content		: EasySocial.ajax( 'site/views/activities/confirmDelete' ),
							bindings	:
							{
								"{deleteButton} click" : function()
								{
									EasySocial.ajax( 'site/controllers/activities/delete',
									{
										"id"		: uid,
									})
									.done(function( html )
									{
										self.element.fadeOut();

										// close dialog box.
										EasySocial.dialog().close();
									});
								}
							}
						});

					}


				}
			});

		module.resolve();
	});

});

EasySocial.module( 'site/activities/list' , function($){

	var module	= this;

	EasySocial.require()
	.view( 'site/loading/small' )
	.script('site/activities/item')
	.done(function($){


		// TODO: Move this away from here
		$.fn.visible = function(partial){

			var $t	= $(this),
				$w	= $(window),
			viewTop	= $w.scrollTop(),
			viewBottom	= viewTop + $w.height(),
			_top		= $t.offset().top,
			_bottom		= _top + $t.height(),
			compareTop	= partial === true ? _bottom : _top,
			compareBottom	= partial === true ? _top : _bottom;

			return ((compareBottom <= viewBottom) && (compareTop >= viewTop));
	    };		

		EasySocial.Controller(
			'Activities.List',
			{
				defaultOptions:
				{
					// Elements
					"{item}"	: "[data-activity-item]",


					"{pagination}"  : "[data-activity-pagination]",

					// loading gif
					view :
					{
						loadingContent 	: "site/loading/small"
					}

				}
			},
			function( self ){
				return {

					init : function()
					{
						// self.item()
						// 	.addController(
						// 		"EasySocial.Controller.Activities.Item"
						// 	);

						self.item().implement( EasySocial.Controller.Activities.Item );		

						self.on("scroll.activities", window, $._.debounce(function(){

							if (self.loading) return;

							if (self.pagination().visible()) {

								self.loadMore();
							}

						}, 250));							
					},


					loadMore: function() {

						var type 		= $("[data-sidebar-menu].active").data( 'type' );
						var startlimit 	= self.pagination().data('startlimit');

						if( startlimit == '')
						{
							return;
						}

						self.loading = true;

						EasySocial.ajax( 'site/controllers/activities/getActivities' ,
						{
							"limitstart" : startlimit,
							"loadmore" : '1',
							"type" : type
						},
						{
							beforeSend: function()
							{
								self.pagination().html( self.view.loadingContent() );
							}
						})
						.done(function( contents, startlimit )
						{
							// update next start date
							self.pagination().data('startlimit', startlimit );

							// append stream into list.
							self.pagination().before( contents );

							//re-implement controller on new items
							self.item().implement( EasySocial.Controller.Activities.Item );	


						})
						.fail( function( messageObj ){
							
							return messageObj;
						})
						.always(function(){

							self.loading = false;
							self.pagination().html('');
						});
					}




				}
			});

		module.resolve();
	});

});
EasySocial.module( 'site/apps/apps' , function($){

	var module 				= this;

	EasySocial.require()
	.library( 'history' )
	.view(
		'site/loading/small'
	)
	.done(function($){

		EasySocial.Controller(
			'Apps',
			{
				defaultOptions :
				{
					requireTerms	: true,
					"{content}"	: "[data-apps-listing]",
					"{sort}"	: "[data-apps-sort]",
					"{filter}"	: "[data-apps-filter]",
					"{item}"	: "[data-apps-item]",
					"{sorting}"	: "[data-apps-sorting]",
					"{title}"	: "[data-page-apps-title]",

					view :
					{
						loading 	: 'site/loading/small'
					}
				}
			},
			function( self )
			{
				return {

					init : function()
					{
						// Implement apps item controller.
						self.initAppItem();
					},

					initAppItem : function()
					{
						self.item().implement( EasySocial.Controller.Apps.Item ,
						{
							requireTerms 	: self.options.requireTerms
						});
					},

					"{filter} click" : function( el , event )
					{
						// Remove all active classes on the left
						self.sort().removeClass( 'active' );
						self.filter().removeClass( 'active' );


						// Add active class to the current filter item.
						$( el ).addClass( 'active' );

						// Get the sort type.
						var filter 	= $( el ).data( 'apps-filter-type' ),
							url 	= $( el ).data( 'apps-filter-url' ),
							title 	= $( el ).data( 'apps-title' );

						// Set the title.
						self.title().html( title );

						// Set the current active filter
						self.options.filter 	= filter;


						History.pushState( {state:1} , '' , url );

						// If the filter is 'mine' , we don't want to show the sorting options
						if( filter == 'mine' )
						{
							self.sorting().hide();
						}
						else
						{
							self.sorting().show();
						}

						EasySocial.ajax( 'site/controllers/apps/getApps',
						{
							"filter"	: filter,
						},
						{
							beforeSend: function()
							{
								// Set the default sorting type to alphabetically ordered.
								self.sort( '.alphabetical' ).addClass( 'active' );

								self.content().html( self.view.loading() );
							}
						})
						.done( function( output )
						{

							// Append the output back.
							self.content().html( output );

							// Reapply the item controller
							self.initAppItem();
						});
					},

					"{sort} click" : function( el , event )
					{
						// Get the sort type and filter type.
						var type 	= $( el ).data( 'apps-sort-type' ),
							url 	= $( el ).data( 'apps-sort-url' );

						History.pushState( {state:1} , '' , url );

						// Add the active state on the current element.
						self.sort().removeClass( 'active' );

						$( el ).addClass( 'active' );

						EasySocial.ajax( 'site/controllers/apps/getApps',
						{
							"sort"	: type,
							"filter": self.options.filter
						},
						{
							beforeSend: function()
							{
								self.content().html( self.view.loading() );
							}
						})
						.done( function( output )
						{

							// Append the output back.
							self.content().html( output );

							// Reapply the item controller
							self.initAppItem();
						});
					}
				}
			});

		EasySocial.Controller(
			'Apps.Item',
			{
				defaultOptions :
				{
					id				: null,
					requireTerms 	: true,

					"{install}"		: "[data-apps-item-install]",
					"{installed}"	: "[data-apps-item-installed]",
					"{settings}"	: "[data-apps-item-settings]",

					view :
					{
						installAppForm : "site/apps/dialog.install",
						uninstallAppForm: "site/apps/dialog.uninstall"
					}
				}
			},
			function( self ) {
				return {

					init : function() {
						if(self.element.data('id')) {
							self.options.id = self.element.data('id');
						}
					},

					"{install} click" : function( el )
					{
						EasySocial.dialog({
							content: EasySocial.ajax('site/views/apps/getTnc' ),
							bindings:
							{
								'{cancelButton} click': function() {

									EasySocial.dialog().close();
								},

								'{installButton} click': function()
								{
									var agreed = !self.options.requireTerms || this.agreeCheckbox().is(':checked');

									if( agreed )
									{
										this.termsError().hide();
										self.installApp();
									}
									else
									{
										this.termsError().show();
									}
								}
							}
						});
					},

					installApp: function()
					{

						var installing = EasySocial.ajax('site/controllers/apps/installApp', {
							id: self.options.id
						});

						EasySocial.dialog({
							content: installing,
							bindings:
							{
								"{closeButton} click" : function(){
									EasySocial.dialog().close();
								}
							}
						});

						installing.done(function()
						{
							self.install().enabled(true);

							self.install().hide();

							self.installed().show();

							self.settings().hide();
						});
					},

					"{settings} click" : function( el , event )
					{
						EasySocial.dialog(
						{
							content 	: EasySocial.ajax( "site/views/apps/settings" , { "id" : self.options.id } ),
							bindings	:
							{
							}
						})
					},

					'{installed} click': function(el) {
						if(el.enabled()) {

							el.disabled(true);

							EasySocial.dialog({
								content		: EasySocial.ajax('site/views/apps/confirmUninstall'),
								bindings	:
								{
									'{parent.closeButton} click': function() {
										self.installed().enabled(true);
									},

									'{cancelButton} click': function() {
										self.installed().enabled(true);

										EasySocial.dialog().close();
									},

									'{uninstallButton} click': function()
									{
										self.uninstallApp();
									}
								}
							});
						}
					},

					uninstallApp: function() {
						var uninstalling = EasySocial.ajax('site/controllers/apps/uninstallApp', {
							id: self.options.id
						});

						EasySocial.dialog({
							content: uninstalling,
							bindings:
							{
								'{closeButton} click' : function()
								{
									EasySocial.dialog().close();
								}
							}
						});

						uninstalling.done(function()
						{
							self.installed().enabled(true);

							self.installed().hide();

							self.settings().hide();

							self.install().show();
						});
					}
				}
			});

		module.resolve();
	});


});

EasySocial.module('site/badges/badge', function($) {
	var module = this;

	EasySocial.Controller('Badges.Badge', {
		defaultOptions: {
			id					: 0,
			total				: 0,

			'{achieversList}'	: '[data-badge-achievers-list]',

			'{achiever}'		: '[data-badge-achievers-achiever]',

			'{loadIndicator}'	: '[data-badge-achievers-loading]',

			'{loadButton}'		: '[data-badge-achievers-load]',
		}
	}, function(self) {
		return {
			init: function() {
				self.options.id = self.element.data('id');
				self.options.total = self.element.data('total-achievers');
			},

			'{loadButton} click': function(el) {
				var current = self.achiever().length;

				if(el.enabled() && current < self.options.total) {
					el.disabled(true);

					el.hide();

					self.loadIndicator().show();

					EasySocial.ajax('site/controllers/badges/loadAchievers', {
						id: self.options.id,
						start: current
					}).done(function(html) {

						self.achieversList().append(html);

						el.enabled(true);

						self.loadIndicator().hide();

						if(self.achiever().length < self.options.total) {
							el.show();
						}

					}).fail(function(msg) {

					});
				}
			},

			loadAchievers: function() {

			}
		}
	});

	module.resolve();
});

EasySocial.module('site/comments/control', function($) {
	var module = this;

	/**
	 *	Comments update controller
	 *	Should only exist once on the page
	 *	Act as a data handler between server and client for comments update (add/delete/edit etc)
	 *	Global functions should be here as well
	 */

	EasySocial.Controller('CommentsControl', {
		defaultOptions: {
			interval: 30
		}
	}, function(self) { return {
		init: function() {
			self.startUpdate();
		},

		// Comments block registry
		$Blocks: {},

		startUpdate: function() {
			self.options.monitoring = true;
			self.updateBlocks();
		},

		stopUpdate: function() {
			self.options.monitoring = false;
		},

		updateBlocks: function(){

			(self.updateBlocks = $._.debounce(function() {

				var data = self.populate();

				if(!self.options.monitoring)
				{
					return false;
				}

				EasySocial.ajax('site/controllers/comments/getUpdates', {
					data: data
				}).done(function(result) {

					// Push updates to each comment block
					$.each(result, function(element, block) {
						$.each(block, function(uid, comments) {

							var comment = self.$Blocks[element][uid];

							if (comment._destroyed) return;

							comment.updateComment(comments);
						});
					});

				}).always(function() {

					self.updateBlocks();
				});
			}, self.options.interval * 1000))();
		},

		register: function(instance) {
			var group = instance.options.group,
				element = instance.options.element,
				uid = instance.options.uid;

			var key = element + '.' + group;

			if(self.$Blocks[key] === undefined) {
				self.$Blocks[key] = {};
			}

			self.$Blocks[key][uid] = instance;

			instance.trigger('commentBlockRegistered');
		},

		populate: function() {
			var data = {};

			$.each(self.$Blocks, function(key, block) {
				data[key] = {};

				$.each(block, function(uid, comments) {
					data[key][uid] = comments._export();
				});
			});

			return data;
		}
	} });


	EasySocial.ready(function(){

		// Implement this controller on to es-wrap
		EasySocial.Comments = $('body').addController('EasySocial.Controller.CommentsControl');

		module.resolve();
	});

});

EasySocial.module('site/comments/frame', function($) {
	var module = this;

	EasySocial
		.require()
		.library('expanding')
		.script('site/comments/item')
		.language(
			'COM_EASYSOCIAL_COMMENTS_LOADED_OF_TOTAL',
			'COM_EASYSOCIAL_COMMENTS_STATUS_SAVING',
			'COM_EASYSOCIAL_COMMENTS_STATUS_SAVED'
		)
		.done(function() {

			/**
			 *	Parent comments controller
			 */
			EasySocial.Controller('Comments', {
				defaultOptions: {
					'group'				: 'user',
					'element'			: 'stream',
					'uid'				: 0,

					'url'				: '',

					'{actionContent}'	: '[data-action-contents-comments]',
					'{actionLink}'		: '[data-stream-action-comments]',

					'{stat}'			: '[data-comments-stat]',

					'{load}'			: '[data-comments-load]',

					'{list}'			: '[data-comments-list]',

					'{item}'			: '[data-comments-item]',

					'{form}'			: '[data-comments-form]'
				}
			}, function(self) { return {

				// List all the triggers here made to parent
				// newCommentSaving
				// newCommentSaved(comment)
				// newCommentSaveError(errormsg)
				// oldCommentsLoaded(comments)
				// oldCommentsLoadError(errormsg)
				// commentDeleted(id)

				// Item triggers
				// commentEditLoading(id)
				// commentEditLoaded(id, rawcomment)
				// commentEditLoadError(id, errormsg)
				// commentEditSaving(id, newcomment)
				// commentEditSaved(id, newcomment)
				// commentEditSaveError(id, errormsg)
				// commentDeleting(id)
				// commentDeleteError(id, errormsg)

				init: function() {

					// Initialise uid
					self.options.uid = self.element.data('uid') || self.options.uid;

					// Initialise element
					self.options.element = self.element.data('element') || self.options.element;

					// Initialise group
					self.options.group = self.element.data('group') || self.options.group;

					// Initialise url
					self.options.url = self.element.data('url') || self.options.url;

					self.$Stat = self.addPlugin('stat');
					self.$Load = self.addPlugin('load');
					self.$List = self.addPlugin('list');
					self.$Form = self.addPlugin('form');

					// Comment Control needs to be required once when there is a frame on the page
					EasySocial.require().script('site/comments/control').done(function() {

						// This block needs to be registered
						EasySocial.Comments.register(self);
					});

					// Trigger commentInit on self
					self.trigger('commentInit', [self]);
				},

				// Create a registry of items
				$Comments: {},

				registerComment: function(instance) {
					var id = instance.options.id;

					self.$Comments[id] = instance;
				},

				'{actionLink} click' : function(){
					self.actionContent().toggle();
				},

				_export: function() {
					var data = {
						total: self.$Stat.total(),
						count: self.$Stat.count(),
						ids: $._.keys(self.$Comments)
					};

					return data;
				},

				updateComment: function(comments) {
					var newComments = [];

					$.each(comments['ids'], function(commentid, state) {
						if(state !== true) {
							if(state === false) {

								// Trigger commentDeleted event on self (as parent)
								self.trigger('commentDeleted', [commentid]);

							} else {
								var appended = false;

								// Search for the next larger id as the node to insert before
								$.each(self.$Comments, function(id, comment) {
									if(id > commentid) {
										self.$List.addToList(state, id, false);

										appended = true;
										return false;
									}
								});

								// If no node found, then just append it to the list
								if(!appended) {
									self.$List.addToList(state, 'append', false);
								}

								// Add this comment into the list of new comments
								newComments.push(state);
							}
						}
					});

					// Update the new total count
					self.$Stat.total(comments['total']);

					// Trigger oldCommentsLoaded event
					self.trigger('oldCommentsLoaded', [newComments]);
				}
			} });
			/**
			 *	List controller
			 */
			EasySocial.Controller('Comments.List', {
				defaultOptions: {
					'{list}': '[data-comments-list]',

					'{item}': '[data-comments-item]'
				}
			}, function(self) { return {
				init: function() {
					// Multiple instances of items
					self.initItemController(self.item(), false);
				},

				initItemController: function(item, isNew) {
					item.addController('EasySocial.Controller.Comments.Item', {
						controller: {
							parent: self.parent
						},

						isNew: isNew
					});

					return item;
				},

				'{parent} newCommentSaved': function(el, event, comment) {
					// Add the comment to the list
					self.addToList(comment);
				},

				addToList: function(comment, type, isNew) {
					// Set type to append by default
					type = type === undefined ? 'append' : type;

					// Set isNew to true by default
					isNew = isNew === undefined ? true : isNew;

					// Wrap comment in jQuery
					comment = $(comment);

					// Implement item controller on comment
					self.initItemController(comment, isNew);

					// Check if type is append/prepend
					if(type == 'append' || type == 'prepend') {

						// Prepare function values based on type (append/prepend)
						var filter = type == 'append' ? ':last' : ':first',
							action = type == 'append' ? 'after' : 'before';

						// Add the comment item into list
						if(self.item().length === 0) {
							// If no comments yet then add the html into the list
							self.list().html(comment);
						} else {
							// If there are existing comments, then append/prepend comment into the list
							self.item(filter)[action](comment);
						}
					} else {

						// If type is neither append or prepend, then type could be the comment id
						var item = self.parent.$Comments[type];

						// Check if type is a valid comment, if it is then by this means prepend on top
						if(item !== undefined) {
							item.element.before(comment);
						}
					}

					// Show the whole comment block because the block could be hidden
					self.parent.actionContent().show();
				},

				'{parent} commentDeleted': function(el, event, id) {
					// Remove this comment from comment registry
					if(self.parent.$Comments[id] !== undefined) {

						// Remove the element
						self.parent.$Comments[id].element.remove();

						// Remove the controller reference in the registry
						delete self.parent.$Comments[id];
					}
				}
			} });

			/**
			 *	Statistic controller
			 */
			EasySocial.Controller('Comments.Stat', {
				defaultOptions: {
					'{stats}'	: '[data-comments-stats]',

					count	: 0,
					total	: 0,

					limit	: 10
				}
			}, function(self) { return {
				init: function() {
					self.options.count = self.element.data('count');
					self.options.total = self.element.data('total');
				},

				// Get / set total comments
				total: function(count) {
					if(count !== undefined) {
						self.options.total = parseInt(count);
						self.stats().text($.language('COM_EASYSOCIAL_COMMENTS_LOADED_OF_TOTAL', self.count(), self.total()));
					}

					return self.options.total;
				},

				// Get / set current comments
				count: function(count) {
					if(count !== undefined) {
						self.options.count = parseInt(count);
						self.stats().text($.language('COM_EASYSOCIAL_COMMENTS_LOADED_OF_TOTAL', self.count(), self.total()));
					}

					return self.options.count;
				},

				getNextCycle: function() {
					var start = Math.max(self.total() - self.count() - self.options.limit, 0);

					var limit = self.total() - self.count() - start;

					return {
						start: start,
						limit: limit
					}
				},

				'{parent} oldCommentsLoaded': function(el, event, comments) {
					var count = comments.length;

					self.count(self.count() + count);
				},

				'{parent} newCommentSaved': function() {
					self.total(self.total() + 1);

					self.count(self.count() + 1);
				},

				'{parent} commentDeleted': function() {
					self.total(self.total() - 1);

					self.count(self.count() - 1);
				}
			} });

			/**
			 *	Load more controller
			 */
			EasySocial.Controller('Comments.Load', {
				defaultOptions: {
					'{load}'		: '[data-comments-load]',

					'{loadMore}'	: '[data-comments-load-loadMore]'
				}
			}, function(self) { return {
				init: function() {

				},

				'{loadMore} click': function(el, event) {
					if(el.enabled()) {

						// Disable the button
						el.disabled(true);

						// Get boundary details
						var cycle = self.parent.$Stat.getNextCycle();

						// If limit is 0, means no comment to load
						if(cycle.limit == 0) {
							return false;
						}

						// Send load comments command to the server
						self.loadComments(cycle.start, cycle.limit)
							.done(function(comments) {
								// Comments come in with chronological order array
								// Hence need to reverse comment and prepend from bottom

								// Create a copy of reverse comments to not affect the original array
								// Slice is to create a non reference copy of the array
								var reversedComments = comments.slice().reverse();

								$.each(reversedComments, function(index, comment) {
									self.parent.$List.addToList(comment, 'prepend', false);
								});

								// Trigger oldCommentsLoaded event
								self.parent.trigger('oldCommentsLoaded', [comments]);

								// Enable the button
								el.enabled(true);

								// If start is 0, means this is the last round of comments to load
								cycle.start == 0 && self.load().hide();
							})
							.fail(function(msg) {

								// Trigger oldCommentsLoadError event
								self.parent.trigger('oldCommentsLoadError', [msg]);
							});
					}
				},

				loadComments: function(start, limit) {
					limit = limit || 10;
					return EasySocial.ajax('site/controllers/comments/load', {
						uid: self.parent.options.uid,
						element: self.parent.options.element,
						group: self.parent.options.group,
						start: start,
						length: limit
					});
				}
			} });

			/**
			 *	Form controller
			 */
			EasySocial.Controller('Comments.Form', {
				defaultOptions: {
					'{input}'	: '[data-comments-form-input]',

					'{submit}'	: '[data-comments-form-submit]',

					'{status}'	: '[data-comments-form-status]'
				}
			}, function(self) { return {
				init: function() {

					// Implement expanding textarea only
					// when comment form is clicked.
					var input = self.input();
					input.one("click", function(){
						input.expandingTextarea();
					});
				},

				'{input} keypress': function(el, event) {
					if(event.keyCode == 13 && !( event.shiftKey || event.altKey || event.ctrlKey || event.metaKey ) ) {
						self.submitComment();
					}
				},

				'{submit} click': function(el, event) {
					if(el.enabled()) {
						self.submitComment();
					}
				},

				submitComment: function() {
					var comment = self.input().val();

					// If comment value is empty, then don't proceed
					if($.trim(comment) == '') {
						return false;
					}

					// Trigger newCommentSaving event
					self.parent.trigger('newCommentSaving');

					// Execute save
					self.save()
						.done(function(comment) {
							// Rather than using commentItem ejs, let PHP return a full block of HTML codes
							// This is to unify 1 single theme file to use loading via static or ajax

							// trigger parent's commentSaved event
							self.parent.trigger('newCommentSaved', [comment]);

							// Enable the submit button
							self.submit().enabled(true);
						})
						.fail(function(msg) {
							self.parent.trigger('newCommentSaveError', [msg.message]);
						});
				},

				save: function() {
					var data = {
						url: self.parent.options.url
					};

					return EasySocial.ajax('site/controllers/comments/save', {
						uid: self.parent.options.uid,
						element: self.parent.options.element,
						group: self.parent.options.group,
						input: self.input().val(),
						data: data
					});
				},

				disableForm: function() {
					// Disable input
					self.input().attr('disabled', true);

					// Disable submit button
					self.submit().disabled(true);
				},

				enableForm: function() {
					// Enable and reset input
					self.input().removeAttr('disabled');

					// Enable submit button
					self.submit().enabled(true);
				},


				'{parent} newCommentSaving': function() {
					// Show the status as it could be hidden by other actions
					self.status().show();

					// Set the status as success
					self.status().removeClass('label-important label-success label-info');
					self.status().addClass('label-info');

					// Set the status
					self.status().text($.language('COM_EASYSOCIAL_COMMENTS_STATUS_SAVING'));

					// Disable comment form
					self.disableForm();
				},

				'{parent} newCommentSaved': function() {
					// Show the status bar of the form
					self.status().show();

					// Set the text of the status bar
					self.status().text($.language('COM_EASYSOCIAL_COMMENTS_STATUS_SAVED'));

					// Set the status as success
					self.status().removeClass('label-important label-success label-info');
					self.status().addClass('label-success');

					// Fade out the status bar after 2 second
					setTimeout(function() {
						self.status().fadeOut('fast');
					}, 2000);

					// Enable comment form
					self.enableForm();

					// Reset comment input
					self.input().val('');

					self.input().expandingTextarea('resize');
				},

				'{parent} newCommentSaveError': function(el, event, msg) {
					// Show the status bar of the form
					self.status().show();

					// Set the status as error
					self.status().removeClass('label-important label-success label-info');
					self.status().addClass('label-important');

					// Add the error message
					self.status().text(msg);

					// Enable comment form
					self.enableForm();
				}
			} });

			module.resolve();
		});
})

EasySocial.module('site/comments/item', function($) {
	var module = this;

	EasySocial.require()
		.language(
			'COM_EASYSOCIAL_COMMENTS_STATUS_SAVE_ERROR',
			'COM_EASYSOCIAL_COMMENTS_STATUS_LOADING',
			'COM_EASYSOCIAL_COMMENTS_STATUS_LOAD_ERROR',
			'COM_EASYSOCIAL_COMMENTS_STATUS_DELETING',
			'COM_EASYSOCIAL_COMMENTS_STATUS_DELETE_ERROR',
			'COM_EASYSOCIAL_LIKES_LIKE',
			'COM_EASYSOCIAL_LIKES_UNLIKE'
		)
		.done(function() {
			/**
			 *	Item controller
			 */
			EasySocial.Controller('Comments.Item', {
				defaultOptions: {
					'id'			: 0,

					'isNew'			: false,

					'{frame}'		: '[data-comments-item-frame]',

					'{avatar}'		: '[data-comments-item-avatar]',

					'{commentFrame}': '[data-comments-item-commentFrame]',

					'{author}'		: '[data-comments-item-author]',

					'{action}'		: '[data-comments-item-actions]',
					'{edit}'		: '[data-comments-item-actions-edit]',
					'{delete}'		: '[data-comments-item-actions-delete]',
					'{spam}'		: '[data-comments-item-actions-spam]',

					'{comment}'		: '[data-comments-item-comment]',

					'{meta}'		: '[data-comments-item-meta]',

					'{date}'		: '[data-comments-item-date] a',

					'{like}'		: '[data-comments-item-like]',
					'{likeCount}'	: '[data-comments-item-likeCount]',

					'{editFrame}'	: '[data-comments-item-editFrame]',
					'{editInput}'	: '[data-comments-item-edit-input]',
					'{editSubmit}'	: '[data-comments-item-edit-submit]',
					'{editStatus}'	: '[data-comments-item-edit-status]',

					'{statusFrame}'	: '[data-comments-item-statusFrame]'
				}
			}, function(self) { return {
				init: function() {
					// Initialise comment id
					self.options.id = self.element.data('id');

					// Register self into the registry of comments
					self.parent.registerComment(self);

					// Add the status plugin
					// self.status = self.addPlugin('status');

					// Using add Controller instead of addPlugin because the parent should reference the item's parent, not the item itself
					self.status = self.element.addController('EasySocial.Controller.Comments.Item.Status', {
						controller: {
							parent: self.parent,
							item: self
						}
					})
				},

				'{like} click': function(el) {
					if(el.enabled()) {
						// Disable the like button
						el.disabled(true);

						// Send the like to the server
						self.likeComment()
							.done(function(liked, count, string) {

								// Enable the button
								el.enabled(true);

								// Set the likes count
								self.likeCount().text(count);

								// Strip off tags from the like text
								string = $('<div></div>').html(string).text();

								// Set the like text
								self.likeCount().attr('data-original-title', string);

								// Set the like button text
								self.like().find('a').text($.language(liked ? 'COM_EASYSOCIAL_LIKES_UNLIKE' : 'COM_EASYSOCIAL_LIKES_LIKE'));
							})
							.fail(function() {

							});
					}
				},

				likeComment: function() {
					return EasySocial.ajax('site/controllers/comments/like', {
						id: self.options.id
					});
				},

				'{likeCount} click': function() {
					EasySocial.dialog({
						content: self.getLikedUsers()
					});
				},

				getLikedUsers: function() {
					return EasySocial.ajax('site/controllers/comments/likedUsers', {
						id: self.options.id
					});
				},

				'{edit} click': function(el) {
					if(el.enabled()) {

						// Disable the edit button
						el.disabled(true);

						// Trigger commentEditLoading event
						self.trigger('commentEditLoading', [self.options.id]);

						self.getRawComment()
							.done(function(comment) {

								// Trigger commentEditLoaded event
								self.trigger('commentEditLoaded', [self.options.id], comment);

								// Set the edit input to the raw comment value
								self.editInput().val(comment);

								// Focus on the edit input
								self.editInput().focus();

								// Init expanding textarea
								self.editInput().expandingTextarea();
							})
							.fail(function(msg) {

								// Trigger commentEditLoadError event
								self.trigger('commentEditLoadError', [self.options.id, msg]);
							});
					}
				},

				getRawComment: function() {
					return EasySocial.ajax('site/controllers/comments/getRawComment', {
						id: self.options.id
					});
				},

				'{editInput} keyup': function(el, event) {
					if(event.which == 13 || event.which == 27) {
						switch(event.which) {
							case 13:
								if(!(event.shiftKey || event.ctrlKey || event.altKey || event.metaKey)) {
									self.submitEdit();
								}

								break;

							case 27:
								// Trigger commentEditCancel event
								self.trigger('commentEditCancel', [self.options.id]);

								break;
						}

						// Enable the edit button
						self.edit().enabled(true);
					}
				},

				'{editSubmit} click': function() {
					self.submitEdit();
				},

				submitEdit: function() {
					// Get and trim the edit value
					var input = $.trim(self.editInput().val());

					// Do not proceed if value is empty
					if(input == '') {
						return false;
					}

					// Trigger commentEditSaving event
					self.trigger('commentEditSaving', [self.options.id, input]);

					// Send the edit to the server
					self.saveEdit(input)
						.done(function(comment) {

							// Trigger commentEdited event
							self.trigger('commentEditSaved', [self.options.id, comment]);

							// Update the comment content
							self.comment().html(comment);

							self.edit().enabled(true);
						})
						.fail(function(msg) {

							// Trigger commentEditError event
							self.trigger('commentEditSaveError', [self.options.id, msg]);
						});
				},

				saveEdit: function(input) {
					return EasySocial.ajax('site/controllers/comments/update', {
						id: self.options.id,
						input: input
					});
				},

				'{delete} click': function(el) {
					if(el.enabled()) {

						// Disable the button first
						el.disabled(true);

						// Prepare the item properly first
						self.frame().hide();
						self.commentFrame().show();

						// Clone the whole item to place in the dialog
						var comment = self.element.clone();

						EasySocial.dialog({
							content: EasySocial.ajax('site/views/comments/confirmDelete', {
								id: self.options.id
							}),
							selectors: {
								"{deleteButton}"  : "[data-delete-button]",
								"{cancelButton}"  : "[data-cancel-button]"
							},
							bindings: {
								"{deleteButton} click": function() {

									// Close the dialog
									EasySocial.dialog().close();

									// Trigger commentDeleting event on parent to announce to sibling frames
									self.parent.trigger('commentDeleting', [self.options.id]);

									// Trigger commentDeleting event on self to announce to child frames
									self.trigger('commentDeleting');

									// Send delete command to server
									self.deleteComment()
										.done(function() {

											// Trigger commentDeleted event on parent, since this element will be remove, no point triggering on self
											self.parent.trigger('commentDeleted', [self.options.id]);

											// Enable the button
											el.enabled(true);
										})
										.fail(function(msg) {

											// Trigger commentDeleteError event on parent to announce to sibling frames
											self.parent.trigger('commentDeleteError', [self.options.id, msg]);

											// Trigger commentDeleteError event on self to announce to child frames
											self.trigger('commentDeleteError', [self.options.id, msg]);
										});
								},

								"{cancelButton} click": function() {

									// Close the dialog
									EasySocial.dialog().close();

									// Enable the button
									el.enabled(true);
								}
							}
						});
					}
				},

				deleteComment: function() {
					return EasySocial.ajax('site/controllers/comments/delete', {
						id: self.options.id
					});
				}
			} });

			/**
			 *	Status frame controller
			 */
			EasySocial.Controller('Comments.Item.Status', {
				defaultOptions: {
					'{frame}'		: '[data-comments-item-frame]',

					'{statusFrame}'	: '[data-comments-item-statusFrame] div',

					'{commentFrame}': '[data-comments-item-commentFrame]',

					'{editFrame}'	: '[data-comments-item-editFrame]'
				}
			}, function(self) { return {

				// commentEditLoading(id)
				// commentEditLoaded(id, rawcomment)
				// commentEditLoadError(id, errormsg)
				// commentEditCancel(id)
				// commentEditSaving(id, newcomment)
				// commentEditSaved(id, newcomment)
				// commentEditSaveError(id, errormsg)
				// commentDeleting(id)
				// commentDeleted(id)
				// commentDeleteError(id, errormsg)

				init: function() {

				},

				setStatus: function(html) {
					self.frame().hide();

					self.statusFrame().html(html);

					self.statusFrame().show();
				},

				'{self} commentEditLoading': function() {
					self.setStatus($.language('COM_EASYSOCIAL_COMMENTS_STATUS_LOADING'));
				},

				'{self} commentEditLoaded': function() {
					self.frame().hide();

					self.editFrame().show();
				},

				'{self} commentEditLoadError': function() {
					self.setStatus($.language('COM_EASYSOCIAL_COMMENTS_STATUS_LOAD_ERROR'));
				},

				'{self} commentEditCancel': function() {
					self.frame().hide();

					self.commentFrame().show();
				},

				'{self} commentEditSaving': function() {
					self.setStatus($.language('COM_EASYSOCIAL_COMMENTS_STATUS_SAVING'));
				},

				'{self} commentEditSaved': function() {
					self.frame().hide();

					self.commentFrame().show();
				},

				'{self} commentEditSaveError': function() {
					self.setStatus($.language('COM_EASYSOCIAL_COMMENTS_STATUS_SAVE_ERROR'));
				},

				'{self} commentDeleting': function() {
					self.setStatus($.language('COM_EASYSOCIAL_COMMENTS_STATUS_DELETING'));
				},

				'{self} commentDeleteError': function(el, event, id, msg) {
					msg = msg || $.language('COM_EASYSOCIAL_COMMENTS_STATUS_DELETE_ERROR')
					self.setStatus(msg);
				}
			} });

			module.resolve();
		});
});

EasySocial.module('site/conversations/api', function($){

	var module = this;

	EasySocial.require()
		.library('dialog')
		.done(function(){

			// Data API
			$(document)
				.on('click.es.conversations.compose', '[data-es-conversations-compose]', function(){

					

					var element 	= $(this),
						userId 		= element.data( 'es-conversations-id')


					EasySocial.dialog(
					{
						"content"	: EasySocial.ajax( 'site/views/conversations/composer' , { "id" : userId } ),
						"bindings"	:
						{
							"{sendButton} click" : function()
							{
								var recipient 	= $( '[data-composer-recipient]' ).val(),
									message 	= $( '[data-composer-message]' ).val();


								EasySocial.ajax( 'site/controllers/conversations/store' ,
								{
									"uid"		: recipient,
									"message"	: message
								})
								.done(function( link )
								{
									EasySocial.dialog(
									{
										"content"	: EasySocial.ajax( 'site/views/conversations/sent' , { "id" : userId }),
										"bindings"	:
										{
											"{viewButton} click" : function()
											{
												document.location 	= link;
											}
										}
									});
								})
								.fail( function( message )
								{
									self.setMessage( message );
								});
							}
						}
					});
				})

			module.resolve();
		});
});
EasySocial.module( 'site/conversations/conversations' , function($){

	var module 	= this;


	EasySocial.require()
	.script( 'site/conversations/mailbox' , 'site/conversations/item' , 'site/conversations/filter' )
	.language( 'COM_EASYSOCIAL_NO_BUTTON' )
	.done( function($){

		EasySocial.Controller(
			'Conversations',
			{
				defaultOptions:
				{
					"{mailbox}"	: "[data-conversations-mailbox]",
					"{list}"	: "[data-conversations-list]",
					"{content}"	: "[data-conversations-content]",

					"{item}"		: "[data-conversations-item]",

					// Conversation actions
					"{actions}"		: "[data-conversations-actions]",

					// Conversations filter
					"{filterItem}"	: "[data-conversations-filter]",

					// Check All
					"{checkAll}"	: "[data-conversations-checkAll]",
					"{checkbox}"	: "[data-conversationItem-checkbox]",

					// Actions that can be performed on the conversations
					"{delete}"		: "[data-conversations-delete]",
					"{archive}"		: "[data-conversations-archive]",
					"{unarchive}"	: "[data-conversations-unarchive]",
					"{unread}"		: "[data-conversations-unread]",
					"{read}"		: "[data-conversations-read]"
				}
			},
			function( self ){

				return {

					init: function()
					{
						// Implement mailbox controller.
						self.mailbox().implement( EasySocial.Controller.Conversations.Mailbox ,
						{
							"{parent}"	: self
						});

						self.item().implement( EasySocial.Controller.Conversations.Item , {
							"{parent}"	: self
						});

						self.filterItem().implement( EasySocial.Controller.Conversations.Filter ,
						{
							"{parent}"	: self
						});
					},

					"{filterItem} click" : function( el )
					{
						// Remove all active classes on filter link.
						self.filterItem().removeClass( 'active' );

						// Add active class on active element.
						$( el ).addClass( 'active' );
					},

					"{checkbox} change" : function( el )
					{
						// See if there's any more checked items.
						if( self.checkbox( ':checked' ).length <= 0 && !el.is( ':checked' ) )
						{
							return self.actions().removeClass( 'is-checked' );
						}

						self.actions().addClass( 'is-checked' );
					},

					/**
					 * Checks all checkbox on the page.
					 */
					"{checkAll} click" : function( el )
					{
						// If there's nothing to check, we do not let them to check anything.
						if( self.checkbox().length <= 0 )
						{
							// Uncheck this.
							$( el ).prop( 'checked' , false );
							
							return false;
						}

						if( el.is( ':checked' ) )
						{
							// We don't want to trigger the checked items since they are already checked.
							self.checkbox( ':not(:checked)' ).click();
						}
						else
						{
							self.checkbox( ':checked' ).click();
						}
					},

					/**
					 * Allows caller to add an is-empty to the list.
					 */
					showEmpty: function()
					{
						self.content().addClass( 'is-empty' );
					},

					/**
					 * Allows caller to add an is-empty to the list.
					 */
					hideEmpty: function()
					{
						self.content().removeClass( 'is-empty' );
					},

					/**
					 * Toggles the loading class on the content.
					 */
					toggleLoading: function()
					{
						self.content().removeClass( 'is-empty' )
							.toggleClass( 'is-loading' );
					},

					/**
					 * Allows caller to trigger this method to update the conversations content.
					 */
					updateContent : function( content , mailbox )
					{
						if( mailbox != undefined )
						{
							self.content().addClass( 'layout-' + mailbox );
						}
						else
						{
							self.content().removeClass( 'layout-archives' );
						}
						// Whenever updateContent is called, we need to hide the actions
						self.actions().removeClass('is-checked');
						self.checkAll().removeAttr( 'checked' );
						
						self.list().html( content );
					},

					getSelectedConversations : function()
					{
						// Let's see if there's any checked items.
						if( self.checkbox(':checked').length <= 0 )
						{
							return false;
						}

						var selected	= new Array;
						self.checkbox(':checked').each( function( i , checkedItem ){
							selected.push( $( checkedItem ).val() );
						});

						return selected;
					},

					"{archive} click" : function()
					{
						var selected	= self.getSelectedConversations();

						EasySocial.dialog(
						{
							content 	: EasySocial.ajax( 'site/views/conversations/confirmArchive' , { "ids" : selected } ),
							bindings 	:
							{
								"{confirmButton} click" : function()
								{
									$( '[data-conversation-archive-form]' ).submit();
								}
							}
						});
					},

					"{unarchive} click" : function()
					{
						var selected	= self.getSelectedConversations();

						EasySocial.dialog(
						{
							content 	: EasySocial.ajax( 'site/views/conversations/confirmUnarchive' , { "ids" : selected } ),
							bindings 	:
							{
								"{confirmButton} click" : function()
								{
									$( '[data-conversation-archive-form]' ).submit();
								}
							}
						})
					},

					"{delete} click" : function()
					{
						var selected	= self.getSelectedConversations();

						EasySocial.dialog(
						{
							content		: EasySocial.ajax( 'site/views/conversations/confirmDelete' , { "ids" : selected }),
							bindings	:
							{
								"{deleteButton} click" : function()
								{
									$( '[data-conversation-delete-form]' ).submit();
								}
							}
						});

					},

					"{read} click" : function()
					{
						// If there's nothing to mark as unread, just ignore.
						if( self.checkbox( ':checked' ).length <= 0 )
						{
							return false;
						}
						
						var ids = new Array();

						// Loop through each checked items.
						self.checkbox( ':checked' ).each( function( i , checkedItem ){
							ids.push( $( checkedItem ).val() );
						});

						EasySocial.ajax( 'site/controllers/conversations/markRead' , 
						{
							"ids"	: ids
						})
						.done( function(){
							
							// Add unread class on the items.
							self.checkbox()
								.parents( '[data-conversations-item]' )
								.removeClass( 'unread' )
								.addClass( 'read' );

							// We need to tell the mailbox controller to update the count.
							self.mailbox().controller().updateCounters();
						})
						.fail(function( message )
						{
							self.setMessage( message );
						});

					},

					"{unread} click" : function()
					{
						// If there's nothing to mark as unread, just ignore.
						if( self.checkbox( ':checked' ).length <= 0 )
						{
							return false;
						}
						
						var ids = new Array();

						// Loop through each checked items.
						self.checkbox( ':checked' ).each( function( i , checkedItem ){
							ids.push( $( checkedItem ).val() );
						});

						EasySocial.ajax( 'site/controllers/conversations/markUnread' , 
						{
							"ids"	: ids
						})
						.done( function(){
							
							// Add unread class on the items.
							self.checkbox()
								.parents( '[data-conversations-item]' )
								.removeClass( 'read' )
								.addClass( 'unread' );

							// We need to tell the mailbox controller to update the count.
							self.mailbox().controller().updateCounters();

						})
						.fail(function( message ){
							console.log( message );
						});

					}
				}
			}
		);

		module.resolve();
	});

});


EasySocial.module( 'site/conversations/mailbox' , function($){

	var module 	= this;


	EasySocial.require()
	.library( 'history' )
	.done( function($){

		EasySocial.Controller(
			'Conversations.Mailbox',
			{
				defaultOptions:
				{
					"{item}"	: "[data-mailboxItem]"
				}
			},
			function( self ){

				return {

					init: function()
					{
						self.item().implement( EasySocial.Controller.Conversations.Mailbox.Item ,
						{
							"{parent}"	: self
						});
					},

					updateCounters: function()
					{
						self.item( '.active' ).controller().updateCounter();
					},

					updateContent : function( items , mailbox )
					{
						// Request the parent to update the contents.
						self.parent.updateContent( items , mailbox );
					},

					showEmpty: function()
					{
						self.parent.showEmpty();
					},

					hideEmpty: function()
					{
						self.parent.hideEmpty();
					},

					toggleLoading: function()
					{
						self.parent.toggleLoading();
					}
				}
			}
		);

		EasySocial.Controller(
			'Conversations.Mailbox.Item',
			{
				defaultOptions:
				{
					"{counter}"	: "[data-mailboxItem-counter]",

					view :
					{
						emptyTemplate : "site/conversations/default.item.empty"
					}
				}
			},
			function( self ){
				return {

					init: function()
					{

					},

					updateCounter: function()
					{
						EasySocial.ajax( 'site/controllers/conversations/getCount' ,
						{
							"mailbox"	: self.element.data( 'mailbox' )
						})
						.done(function( total ){

							// If there's no more new items, hide it.
							if( total <= 0 )
							{
								self.counter().html( '' );

								return;
							}

							self.counter().html( '(' + total + ')' );
						})
					},

					toggleLoading: function()
					{
						self.element.toggleClass( 'loading' );
					},

					"{self} click" : function()
					{
						var url 	= self.element.data( 'url' ),
							title 	= self.element.data( 'title' ),
							mailbox	= self.element.data( 'mailbox' );

						// Remove active class on all mailboxes.
						self.parent.item().removeClass( 'active' );

						// Add active class to this.
						self.element.addClass( 'active' );

						History.pushState( {state:1} , title , url );

						// Get contents via ajax.
						EasySocial.ajax( 'site/views/conversations/getItems' ,
						{
							"mailbox"	: mailbox,
							"limitstart" : 0
						},
						{
							beforeSend: function()
							{
								// Add loading indicator to the mailbox list.
								self.toggleLoading();

								// Add loading indicator.
								self.parent.toggleLoading();
							}
						})
						.done(function( content , empty ){

							// Remove loading class on the element.
							self.toggleLoading();

							// Remove loading class on the content.
							self.parent.toggleLoading();

							if( content.length <= 0 )
							{
								// Empty the contents too to maintain the integrity of the checkbox
								self.parent.updateContent( '' );
								return self.parent.showEmpty();
							}

							// Hide empty class if it has items.
							self.parent.hideEmpty();

							// Now we'd need to update the content.
							self.parent.updateContent( content , self.element.data( 'mailbox' ) );

						});
					}
				}
		});

		module.resolve();
	});


});


EasySocial.module( 'site/conversations/item' , function($){

	var module 	= this;


	EasySocial.require()
	.script( 'site/conversations/mailbox' )
	.done( function($){

		EasySocial.Controller(
			'Conversations.Item',
			{
				defaultOptions:
				{
					"{checkbox}"	: "[data-conversationItem-checkbox]"
				}
			},
			function( self ){

				return {

					init: function()
					{
					},

					"{checkbox} change": function( el ){

						var checked = $( el ).is( ':checked' );

						if( checked )
						{
							return self.element.addClass( 'selected' );
						}

						return self.element.removeClass( 'selected' );
					}
				}
			}
		);

		module.resolve();
	});

});


EasySocial.module( 'site/conversations/filter' , function($){

	var module 	= this;


	EasySocial.require()
	.done( function($){

		EasySocial.Controller(
			'Conversations.Filter',
			{
				defaultOptions:
				{
				}
			},
			function( self ){

				return {

					init: function()
					{
					},

					"{self} click" : function()
					{
						var type 		= self.element.data( 'filter' ),
							selector	= '.' + type,
							total 		= self.parent.item( selector ).length;

						if( $("[data-mailboxitem]").filter(".active").length == 1 )
						{
							var curActiveMenu = $("[data-mailboxitem]").filter(".active");

							var url 	= curActiveMenu.data( 'url' ),
								title 	= curActiveMenu.data( 'title' ),
								mailbox	= curActiveMenu.data( 'mailbox' );


							History.pushState( {state:1} , title , url );

							// Get contents via ajax.
							EasySocial.ajax( 'site/views/conversations/getItems' ,
							{
								"mailbox"	: mailbox,
								"filter" 	: type,
								"limitstart": 0
							},
							{
								beforeSend: function()
								{
									// Add loading indicator.
									self.parent.toggleLoading();
								}
							})
							.done(function( content , empty ){


								// Remove loading class on the content.
								self.parent.toggleLoading();

								if( content.length <= 0 )
								{
									// Empty the contents too to maintain the integrity of the checkbox
									self.parent.updateContent( '' );
									return self.parent.showEmpty();
								}

								// Hide empty class if it has items.
								self.parent.hideEmpty();

								// Now we'd need to update the content.
								self.parent.updateContent( content , mailbox );

							});



						}

						// if( type == 'all' )
						// {
						// 	if( self.parent.item().length == 0 )
						// 	{
						// 		self.parent.showEmpty();
						// 	}
						// 	else
						// 	{
						// 		self.parent.hideEmpty();
						// 	}

						// 	self.parent.item().show();
						// }
						// else
						// {

						// 	// Hide all conversations initially.
						// 	self.parent.item().hide();


						// 	if( total == 0 )
						// 	{
						// 		// Show empty.
						// 		self.parent.showEmpty();
						// 	}
						// 	else
						// 	{
						// 		// Always hide empty when there are items.
						// 		self.parent.hideEmpty();
						// 	}

						// 	// Only show the necessary item.
						// 	self.parent.item( "." + type ).show();
						// }
					}
				}
			}
		);

		module.resolve();
	});

});


EasySocial.module( 'site/conversations/read' , function($){

	var module 	= this;

	EasySocial.require()
	.library( 'dialog' )
	.script( 'site/conversations/composer' , 'site/friends/suggest' )
	.language(
		'COM_EASYSOCIAL_CONVERSATION_REPLY_POSTED_SUCCESSFULLY',
		'COM_EASYSOCIAL_CONVERSATION_REPLY_FORM_EMPTY'
	)
	.done(function($){

		EasySocial.Controller(
			'Conversations.Read',
			{
				defaultOptions:
				{
					// Conversation id.
					id 	: "",

					// Determines if these features should be enabled.
					attachments 		: true,
					location 			: false,
					maxSize 			: "3mb",

					extensionsAllowed	 : "",
					attachmentController : null,
					composerController	 : null,

					// Conversation items.
					"{item}"		: "[data-readConversation-item]",
					"{items}"		: "[data-readConversation-items]",

					// Form composer
					"{composer}"	: "[data-readConversation-composer]",

					// Buttons
					"{replyButton}"	: "[data-readConversation-replyButton]",

					// Add participant to a conversation.
					"{addParticipant}"	: "[data-readConversation-addParticipant]",

					// Leave conversation.
					"{leaveConversation}"	: "[data-readConversation-leaveConversation]",

					// Delete conversation.
					"{delete}"			: "[data-readConversation-delete]",

					// Attachments
					"{attachments}"		: "[data-uploaderQueue-id]",

					// Notice message on reply form.
					"{replyNotice}"		: "[data-readConversation-replyNotice]",

					// Load previous message button.
					"{readLoadMore}"		: "[data-readconversation-load-more]",


					// Views
					view	:
					{
						messageItem		: 'site/conversations/read.message'
					}
				}
			},
			function( self ){
				return {

					init: function()
					{
						// Implement the composer on the reply form
						self.composer().implement( EasySocial.Controller.Conversations.Composer ,
						{
							"{uploader}"		: "[data-readConversation-attachment]",
							"{location}"		: "[data-readConversation-location]",
							maxSize 			: self.options.maxSize,
							extensionsAllowed	: self.options.extensionsAllowed
						});

						// Get the composer controller.
						self.options.composerController 	= self.composer().controller();

						if( self.options.attachments )
						{
							// Get the uploader controller.
							self.options.attachmentController = self.options.composerController.uploader().controller();
						}

						if( self.options.location )
						{
							// Get the location controller.
							self.options.locationController = self.options.composerController.location().controller();
						}

						// Initialize message item.
						self.item().implement( EasySocial.Controller.Conversations.Read.Item );

						// Set the conversation id.
						self.options.id 	= self.element.data( 'id' );
					},

					resetForm: function()
					{
						// Reset the editor form.
						self.options.composerController.resetForm();

						if( self.options.location )
						{
							// Reset the location.
							self.options.locationController.removeLocation();
						}

						if( self.options.attachments )
						{
							// Reset the uploader.
							self.options.attachmentController.reset();
						}
					},

					"{readLoadMore} click" : function( el )
					{
						var id = $( el ).data( 'id' ),
							limitstart = $(el).data( 'limitstart' );

						self.readLoadMore().hide();
						$( '.loading-indicator' ).show();

						var options 	=	{
												"id"			: id,
												"limitstart"	: limitstart
											};

						// Do an ajax call to submit the reply.
						EasySocial.ajax( 'site/controllers/conversations/loadPrevious' , options )
						.done(function( html, nextlimit )
						{
							$.buildHTML(html)
								.prependTo(self.items())
								.addController("EasySocial.Controller.Conversations.Read.Item");

							if( nextlimit == 0 )
							{
								self.readLoadMore().hide();
							}
							else
							{
								self.readLoadMore().show();
								$(el).data( 'limitstart', nextlimit );
							}

						})
						.always(function()
						{
							$( '.loading-indicator' ).hide();
						});


					},


					"{replyButton} click" : function( el , event )
					{
						// Stop bubbling up.
						event.preventDefault();

						var content 	= self.options.composerController.editor().val(),
							files 		= new Array;


						if( content.length <= 0 )
						{
							self.replyNotice().html( $.language( 'COM_EASYSOCIAL_CONVERSATION_REPLY_FORM_EMPTY' ) ).addClass( 'alert alert-error' ).removeClass( 'alert-success' );
							return false;
						}

						if( self.options.attachments )
						{
							// Get through each attachments.
							self.attachments().each( function( i , attachment ){
								files.push( $( attachment ).val() );
							});
						}

						var options 	=	{
												"id"		: self.options.id,
												"message"	: content
											};

						if( self.options.attachments )
						{
							options[ 'upload-id' ]	= files;
						}

						if( self.options.location )
						{
							options.address 	= self.options.locationController.locationInput().val();
							options.latitude	= self.options.locationController.locationLatitude().val();
							options.longitude	= self.options.locationController.locationLongitude().val();
						}

						// Disable submit button.
						self.replyButton().attr( 'disabled' , true );

						// Do an ajax call to submit the reply.
						EasySocial.ajax( 'site/controllers/conversations/reply' , options )
						.done(function( html )
						{
							self.replyNotice().html( $.language( 'COM_EASYSOCIAL_CONVERSATION_REPLY_POSTED_SUCCESSFULLY' ) ).addClass( 'alert alert-success' ).removeClass( 'alert-error' );

							// Apply controller on the appended item.
							var item 	= $( html );

							item.implement( EasySocial.Controller.Conversations.Read.Item );

							// Append the data back to the list.
							self.items().append( item );

							// Reset the composer form.
							self.resetForm();
						})
						.always(function()
						{
							// Re-activate button.
							self.replyButton().attr( 'disabled' , false );
						});


						return false;
					},

					"{leaveConversation} click" : function()
					{
						EasySocial.dialog({
							content : EasySocial.ajax( 'site/views/conversations/confirmLeave' , { id : self.options.id } )
						});
					},

					"{addParticipant} click" : function()
					{
						EasySocial.dialog(
						{
							content 	: EasySocial.ajax( 'site/views/conversations/addParticipantsForm' , { "id" : self.options.id })
						});
					},

					"{delete} click" : function()
					{
						EasySocial.dialog(
						{
							content : EasySocial.ajax( 'site/views/conversations/confirmDelete' , { "ids" : [ self.options.id ] } ),
							bindings:
							{
								"{deleteButton} click" : function()
								{
									$( '[data-conversation-delete-form]' ).submit();
								}
							}
						});
					},

					/**
					 * Adds a new item into the reading list.
					 */
					addItem: function( obj ){
						// Append the message item into the list.
						self.messageList().append(
							self.view.messageItem({
								item: obj
							})
						);

						// Now we need to empty the message.
						self.textMessage().val( '' ).focus();
					}
				}
		});

		EasySocial.Controller(
			'Conversations.Read.Item',
			{
				defaultOptions :
				{
					id 	: null,

					"{attachmentsWrapper}" : "[data-conversation-attachment-wrapper]",
					"{attachments}"		: "[data-conversation-attachment]"
				}
			},
			function( self )
			{
				return {

					init : function()
					{
						// Get the message id.
						self.options.id 	= self.element.data( 'id' );

						// Implement attachment items.
						self.attachments().implement( EasySocial.Controller.Conversations.Read.Item.Attachment ,
							{
								"{parent}" : self
							});
					},

					removeAttachment : function( el , event )
					{
						// Remove the attachment item
						$( el ).remove();

						// Check to see if there are any more attachments.
						if( self.attachments().length == 0 )
						{
							self.attachmentsWrapper().hide();
						}
					}
				}
			});

		EasySocial.Controller(
			'Conversations.Read.Item.Attachment',
			{
				defaultOptions :
				{
					"{deleteAttachment}" 	: "[data-attachment-delete]"
				}
			},
			function( self )
			{
				return {
					init : function()
					{

					},

					"{deleteAttachment} click" : function( el , event )
					{
						var attachmentId 	= $( el ).data( 'id' );

						EasySocial.dialog(
						{
							content : EasySocial.ajax( 'site/views/conversations/confirmDeleteAttachment', { "id" : attachmentId } ),
							bindings :
							{
								"{deleteButton} click" : function()
								{
									EasySocial.ajax( 'site/controllers/conversations/deleteAttachment',
									{
										id 	: attachmentId
									})
									.done( function()
									{
										// Remove the attachment element.
										self.parent.removeAttachment( self.element );

										EasySocial.dialog(
										{
											content 	: EasySocial.ajax( 'site/views/conversations/attachmentDeleted' , {} )
										});
									})
									.fail( function( message )
									{
										self.setMessage( message );
									})
								}
							}
						});
					}
				}
			})
		module.resolve();
	});
});

EasySocial.module( 'site/dashboard/apps' , function($){

	var module 				= this;

	EasySocial.require()
	.library( 'history' )
	.done(function($){

		EasySocial.Controller(
			'Dashboard.Apps',
			{
				defaultOptions:
				{
					parent		: null,
					pageTitle	: null,
					"{item}"	: "[data-dashboardApps-item]"
				}
			},
			function(self){

				return{

					init : function()
					{
						self.item().implement( EasySocial.Controller.Dashboard.Apps.Item ,
						{
							"{parent}"		: self,
							"{dashboard}"	: self.parent,
							pageTitle 		: self.options.pageTitle
						});
					}
				}
			});

		EasySocial.Controller(
			'Dashboard.Apps.Item',
			{
				defaultOptions:
				{
				}
			}, function(self){

				return{

					init : function()
					{
					},

					"{self} click" : function( el , event )
					{
						// Prevent from bubbling up.
						event.preventDefault();

						// Get the layout meta.
						var layout 	= self.element.data( 'layout' ),
							url 	= self.element.data( layout + '-url' ),
							title 	= self.element.data( 'title' ),
							desc 	= self.element.data( 'description' ),
							appId 	= self.element.data( 'id' );

						// If this is a canvas layout, redirect the user to the canvas view.
						if( layout == 'canvas' )
						{
							window.location 	= url;
							return;
						}

						title 	= $._.isEmpty( self.options.pageTitle ) ? title : self.options.pageTitle;

						// If this is an embedded layout, we need to play around with the push state.
						History.pushState( {state:1} , title , url );

						// Notify the dashboard that it's starting to fetch the contents.
						self.dashboard.content().html("");
						self.dashboard.updatingContents();

						self.element.addClass( 'loading' );

						// Send a request to the dashboard to update the content from the specific app.
						EasySocial.ajax( 'site/controllers/dashboard/getAppContents' ,
						{
							"appId"		: appId
						})
						.done( function( contents )
						{
							self.dashboard.updateHeading( title , desc );

							self.dashboard.updateContents( contents );

						})
						.fail(function( messageObj ){

							return messageObj;

						})
						.always(function(){

							self.element.removeClass( 'loading' );

						});

					}


				}
			});
		module.resolve();
	});

});

EasySocial.module( 'site/dashboard/dashboard' , function($){

	var module 				= this;

	EasySocial.require()
	.script( 'site/dashboard/apps' , 'site/dashboard/feeds' , 'site/dashboard/sidebar' )
	.done(function($){

		EasySocial.Controller(
			'Dashboard',
			{
				defaultOptions:
				{
					"{heading}"			: "[data-dashboard-heading]",
					"{sidebar}"			: "[data-dashboard-sidebar]",
					"{content}"			: "[data-dashboard-real-content]",

					// Feeds.
					"{feeds}"			: "[data-dashboard-feeds]",

					// Applications.
					"{apps}"			: "[data-dashboard-apps]"
				}
			},
			function(self){

				return{

					init: function()
					{
						// Implement sidebar controller.
						self.sidebar().implement( EasySocial.Controller.Dashboard.Sidebar ,
						{
							"{parent}"	: self
						});

						// Implement app controller on all app items.
						self.feeds().implement( EasySocial.Controller.Dashboard.Feeds ,
						{
							"{parent}"	: self
						});

						// Implement app controller on all app items.
						self.apps().implement( EasySocial.Controller.Dashboard.Apps ,
						{
							"{parent}"	: self,
							pageTitle	: self.options.pageTitle
						});

					},

					/**
					 * Responsible to update the heading area in the dashboard.
					 */
					updateHeading: function( title , description )
					{
						self.heading().find( '[data-heading-title]' ).html( title );
						self.heading().find( '[data-heading-desc]' ).html( description );
					},

					/**
					 * Add a loading icon on the content layer.
					 */
					updatingContents: function()
					{
						self.element.addClass("loading");
					},

					/**
					 * Responsible to update the content area in the dashboard.
					 */
					updateContents : function( contents )
					{
						self.element.removeClass("loading");

						// Hide the content first.
						self.content().html( contents );
					}
				}
			});

		module.resolve();
	});

});

EasySocial.module( 'site/dashboard/feeds' , function($){

	var module 				= this;

	EasySocial.require()
	.library( 'history' )
	.done(function($){

		EasySocial.Controller(
			'Dashboard.Feeds',
			{
				defaultOptions:
				{
					"{item}"	: "[data-dashboardFeeds-item]"
				}
			},
			function(self){

				return{

					init : function()
					{
						// Implement each feed links.
						self.item().implement( EasySocial.Controller.Dashboard.Feeds.Item ,
						{
							"{parent}"		: self,
							"{dashboard}"	: self.parent
						});
					}
				}
			});

		EasySocial.Controller(
			'Dashboard.Feeds.Item',
			{
				defaultOptions:
				{
				}
			},
			function(self)
			{

				return{

					init : function()
					{
					},

					/**
					 * Fires when a feed link is clicked.
					 */
					"{self} click" : function()
					{
						//remove no-stream class if any
						$('.es-streams').removeClass( 'no-stream' );

						var type 	= self.element.data( 'type' ),
							id		= self.element.data( 'id' ),
							url 	= self.element.data( 'url' ),
							title 	= self.element.data( 'title' ),
							desc 	= self.element.data( 'description' );

						// if( type == 'me' )
						// {
						// 	// clear the new feed notification counter.
						// 	$( '[data-dashboard-feeds]' ).find('li:first-child').removeClass( 'has-notice' );
						// }


						// clear new feed counter
						self.element.removeClass( 'has-notice' );

						// If this is an embedded layout, we need to play around with the push state.
						History.pushState( {state:1} , title , url );

						// Notify the dashboard that it's starting to fetch the contents.
						self.dashboard.content().html("");
						self.dashboard.updatingContents();

						self.element.addClass( 'loading' );

						EasySocial.ajax( 'site/controllers/dashboard/getStream' ,
						{
							"type"	: type,
							"id"	: id,
							"view"  : 'dashboard',
						})
						.done(function( contents, count )
						{
							self.dashboard.updateHeading( title , desc );

							if( count == 0)
							{
								$('.es-streams').addClass( 'no-stream' );
							}

							self.dashboard.updateContents( contents );
						})
						.fail( function( messageObj ){

							return messageObj;
						})
						.always(function(){

							self.element.removeClass( 'loading' );

						});


					}
				}
			});
		module.resolve();
	});

});

EasySocial.module( 'site/dashboard/sidebar' , function($){

	var module 				= this;

	EasySocial.require()
	.done(function($){

		EasySocial.Controller(
			'Dashboard.Sidebar',
			{
				defaultOptions:
				{
					"{menuItem}"	: "[data-dashboardSidebar-menu]"
				}
			},
			function(self){

				return{ 

					init: function()
					{
					},

					"{menuItem} click" : function( el , event )
					{
						// Remove all active class.
						self.menuItem().removeClass( 'active' );

						// Add active class on this item.
						$( el ).addClass( 'active' );
					}
				}
			});

		module.resolve();
	});
	
});

EasySocial.module('site/followers/api', function($){

	var module = this;

	EasySocial.require()
		.library('dialog', 'popbox')
		.done(function(){


			// Data API
			$(document)
				.on('click.es.followers.follow', '[data-es-followers-follow]', function(){

					var element 		= $(this),
						userId 			= element.data( 'es-followers-id'),
						popboxContent 	= $.Deferred();

						element.popbox(
						{
							content	: popboxContent,
							id 		: "es-wrap",
							type 	: "followers",
							toggle 	: "click"
						});

						element.popbox( 'show' );

						// Let's do an ajax call to follow the user.
						EasySocial.ajax( 'site/controllers/profile/follow' ,
						{
							"id"	: userId,
							"type"	: 'user'
						})
						.done(function( button )
						{
							EasySocial.ajax( 'site/views/profile/popboxFollow' , { "id" : userId } )
							.done(function(content)
							{
								popboxContent.resolve( content );
							});
						});
				})

			// Data API
			$(document)
				.on('click.es.followers.unfollow', '[data-es-followers-unfollow]', function(){

					var element 		= $(this),
						userId 			= element.data( 'es-followers-id'),
						popboxContent 	= $.Deferred();

						element.popbox(
						{
							content	: popboxContent,
							id 		: "es-wrap",
							type 	: "followers",
							toggle 	: "click"
						});

						element.popbox( 'show' );

						// Let's do an ajax call to follow the user.
						EasySocial.ajax( 'site/controllers/profile/unfollow' ,
						{
							"id"	: userId,
							"type"	: 'user'
						})
						.done(function( button )
						{
							EasySocial.ajax( 'site/views/profile/popboxUnfollow' , { "id" : userId } )
							.done(function(content)
							{
								popboxContent.resolve( content );
							});
							
						});
				});

			module.resolve();
		});
});
EasySocial.module( 'site/followers/followers' , function($){

	var module 	= this;

	EasySocial.require()
	.view( 'site/loading/small' )
	.script( 'site/conversations/composer' )
	.done(function($){

		EasySocial.Controller(
			'Followers',
			{
				defaultOptions :
				{
					"{content}"	: "[data-followers-content]",
					"{filter}"	: "[data-followers-filter]",
					"{items}"	: "[data-followers-item]",
					"{followingCounter}" : "[data-following-count]",
					view :
					{
						loader 				: "site/loading/small"
					}
				}
			},
			function( self )
			{
				return {

					init : function()
					{
						self.initItemController();
					},

					initItemController: function()
					{
						self.items().implement( EasySocial.Controller.Followers.Item ,
						{
							"{parent}"	: self
						});
					},

					updateFollowingCounter: function( value )
					{
						var current 	= self.followingCounter().html(),
							updated		= parseInt( current ) + value;

						self.followingCounter().html( updated );
					},

					updateContents : function( contents )
					{
						self.content().html( contents );
					},

					"{filter} click" : function(filter, event) {

						var type 	= filter.data( 'followers-filter-type' ),
							title 	= filter.data( 'followers-filter-title' ),
							id 		= filter.data( 'followers-filter-id' ),
							url 	= filter.data( 'followers-filter-url' );

						// Remove active class on all filters
						self.filter().removeClass("active");
						
						// Add active class to current filter
						filter.addClass("active");

						History.pushState({state:1}, title, url);

						EasySocial.ajax(
							"site/controllers/followers/filter",
							{
								id: id,
								type: type
							})
							.done(function(contents){
								self.updateContents(contents);
								self.initItemController();
							});
					}
				}
			});

			EasySocial.Controller(
				'Followers.Item',
				{
					defaultOptions : 
					{
						"{unfollowButton}"	: "[data-followers-item-unfollow]",
						"{composer}"		: "[data-followers-item-compose]"
					}
				},
				function( self )
				{
					return {
						init : function()
						{
							self.options.id 			= self.element.data( 'id' );

							self.initComposer();
						},

						initComposer: function()
						{
							self.composer().implement( EasySocial.Controller.Conversations.Composer.Dialog,
							{
								"recipient"	:
								{
									"id"	: self.options.id
								}
							});
						},

						"{unfollowButton} click" : function()
						{
							EasySocial.dialog(
							{
								content 	: EasySocial.ajax( 'site/views/followers/confirmUnfollow' , { 'id' : self.options.id }),
								bindings 	:
								{
									"{unfollowButton} click" : function()
									{
										EasySocial.ajax( 'site/controllers/followers/unfollow' , { "id" : self.options.id} )
										.done(function()
										{
											// Update the counter
											self.parent.updateFollowingCounter( -1 );

											// Remove this item
											self.element.remove();

											EasySocial.dialog().close();
										});
									}
								}
							});
						}
					}
				});
		module.resolve();
	});
});

EasySocial.module('site/friends/api', function($){

	var module = this;

	EasySocial.require()
		.library('dialog', 'popbox' )
		.done(function(){

			$( document )
				.on( 'click.es.friends.cancel' , '[data-es-friends-cancel]', function()
				{
					var element 	= $(this),
						friendId 	= element.data( 'es-friends-id' );

						// Show confirmation dialog
						EasySocial.dialog({
							content: EasySocial.ajax( 'site/views/friends/confirmCancel' ),
							bindings:
							{
								"{confirmButton} click": function()
								{
									EasySocial.ajax( 'site/controllers/friends/cancelRequest' ,
									{
										"id"	: friendId
									})
									.done( function()
									{
										// Hide the dialog once the request has been cancelled.
										EasySocial.dialog().close();
									});
								}
							}
						});
				});

			// Data API
			$(document)
				.on('click.es.friends.add', '[data-es-friends-add]', function(){

					var element 	= $(this),
						userId 		= element.data( 'es-friends-id'),
						popboxContent 	= $.Deferred();

						element.popbox(
						{
							content	: popboxContent,
							id 		: "es-wrap",
							type 	: "friends",
							toggle 	: "click"
						});

						element.popbox( 'show' );

						// Run an ajax call now to perform the add friend request.
						EasySocial.ajax( 'site/controllers/friends/request' ,
						{
							"viewCallback"	: "popboxRequest",
							"id"			: userId
						})
						.done(function( content )
						{
							popboxContent.resolve( content );
						});
				})

			module.resolve();
		});
});
EasySocial.module( 'site/friends/friends' , function($){

	var module 	= this;

	EasySocial.require()
	.script(
		'site/friends/list' ,
		'site/friends/item' ,
		'site/friends/suggest',
		'site/conversations/composer'
	)
	.view(
		'site/loading/small' ,
		'site/friends/default.empty' ,
		'site/friends/list.assign'
	)
	.done(function($){

		EasySocial.Controller(
			'Friends.Birthday',
			{
				defaultOptions:
				{
					"{messageButton}"	: "[data-upcoming-birthday-message-button]"
				}
			},
			function( self ){
				return {
					init: function()
					{
						// Get the id of the current user.
						self.options.id 	= self.element.data( 'id' ),
						self.options.name 	= self.element.data( 'name' ),
						self.options.avatar	= self.element.data( 'avatar' );

						self.messageButton().implement( EasySocial.Controller.Conversations.Composer.Dialog,
						{
							"recipient"	:
							{
								"id"	: self.options.id,
								"name"	: self.options.name,
								"avatar": self.options.avatar
							}
						});
					}

				}
			}

		);


		EasySocial.Controller(
			'Friends',
			{
				defaultOptions:
				{
					// Get the default active list if there is any.
					activeList 		: null,

					// Left side friend's list.
					"{friendList}"	: "[data-friends-list]",

					// Content area.
					"{content}"		: "[data-friends-content]",

					// Result
					"{friendItems}"	: "[data-friends-items]",
					"{friendItem}"	: "[data-friends-item]",
					"{emptyList}"	: "[data-friends-emptyItems]",
					"{activeTitle}"	: "[data-friends-activeTitle]",

					// Friends filter
					"{filterItem}"	: "[data-friends-filter]",

					// Friend list actions
					"{friendListActions}"	: "[data-friendList-actions]",

					// Button to add a friend to the list.
					"{addFriendToList}"	: "[data-friends-add]",

					// Counters
					"{friendsCounter}"	: "[data-total-friends]",
					"{pendingCounter}"	: "[data-total-friends-pending]",
					"{suggestionCounter}": "[data-total-friends-suggestion]",
					"{requestCount}"	: "[data-frields-request-sent-count]",

					view :
					{
						loader 				: "site/loading/small",
						emptyFriendItems 	: "site/friends/default.empty",
						addUserForm			: "site/friends/list.assign"
					}

				}
			},
			function( self ){
				return {

					init: function()
					{
						// Implement friend list controller.
						self.friendList().implement( EasySocial.Controller.Friends.List ,
						{
							// parent : self,
							"{parent}" : self
						});

						//Initialize friend item controllers
						self.initFriendItems();
					},

					initFriendItems: function()
					{
						// Apply the friend list actions
						self.friendListActions().implement( EasySocial.Controller.Friends.List.Actions ,
						{
							"{parent}"	: self
						})

						// Implement friend item controller.
						self.friendItem().implement( EasySocial.Controller.Friends.Item ,
						{
							"{parent}"	: self
						});
					},

					updateFriendsCounter: function()
					{
						EasySocial.ajax( 'site/controllers/friends/getCounters' )
						.done(function( totalFriends , totalPending , totalRequests , totalSuggestion )
						{
							self.friendsCounter().html( totalFriends );

							self.pendingCounter().html( totalPending );

							self.requestCount().html( totalRequests );

							self.suggestionCounter().html( totalSuggestion );
						});
					},

					updateListCounters: function()
					{
						EasySocial.ajax( 'site/controllers/friends/getListCounts' ,
						{
						})
						.done( function( lists ){

							$( lists ).each( function( i , list){
								var listController = $( '[data-list-' + list.id + ']').controller();

								listController.updateCounter( list.count );
							});

						});
					},

					insertItem: function( item )
					{
						// Hide any empty notices.
						self.emptyList().hide();

						// Update the counter for the list items.
						self.updateListCounters();

						$( item ).implement( EasySocial.Controller.Friends.Item ,
						{
							"{parent}"	: self
						})
						.prependTo( self.friendItems() );

					},

					removeItem: function( id )
					{
						// Remove item from the list.
						self.friendItem( '[data-friendItem-' + id + ']' ).remove();

						if( self.friendItem().length <= 0 )
						{
							self.emptyList().show();
						}

						// Update the counter for the list items.
						self.updateListCounters();

					},

					updateFriendRequestCount: function( value )
					{
						curCount = parseInt( self.requestCount().text(), 10 );
						if( curCount != NaN )
						{
							curCount = curCount + value;
							self.requestCount().text( curCount );
						}
					},

					updateContent: function( html )
					{
						// Update the content on the friends list.
						self.content().html( html );

						self.initFriendItems();
					},

					removeActiveFilter: function()
					{
						self.filterItem().removeClass( 'active' );
					},

					"{filterItem} click" : function(filterItem, event )
					{
						var filterType 	= filterItem.data( 'filter' ),
							title 		= filterItem.data( 'title' ),
							userid 		= filterItem.data( 'userid' ),
							url 		= filterItem.data( 'url' );


						// Removes all active state from the friend lists
						if( self.friendList().length > 0)
						{
							self.friendList().controller().removeActiveList();
						}

						// Remove all active state on the filter links.
						self.filterItem().removeClass("active");

						// Add active class to this filter.
						filterItem.addClass( 'active' );

						History.pushState( {state:1} , title , url );

						filterItem.addClass( 'loading' );

						EasySocial.ajax(
							"site/controllers/friends/filter",
							{
								"filter"	: filterType,
								"userid"	: userid

							})
							.done(function(html){

								self.updateContent( html );
							})
							.always(function(){

								// Remove loading on the element.
								filterItem.removeClass("loading");
							});
					}
				}
			}
		);

		module.resolve();
	});
});

EasySocial.module( 'site/friends/list' , function($){

	var module 	= this;

	EasySocial.require()
	.view( 
		'site/loading/small'
	)
	.library( 'history' )
	.script( 'site/friends/suggest' )
	.done(function($){

		EasySocial.Controller(
			'Friends.List',
			{
				defaultOptions:
				{
					parent 		: null,

					"{item}"	: "[data-friends-listItem]",
					"{items}"	: "[data-friends-listItems]",

					"{loadMoreButton}"	: ".loadMoreButton",
					"{loadMore}"		: ".loadMore",

					view :
					{
						loader 		: "site/loading/small",
						items 		: "site/friends/default.lists"
					}
				}
			},
			function( self ){
				return {

					init: function()
					{
						self.item().implement( EasySocial.Controller.Friends.List.Item ,
						{
							"{parent}"	: self
						});
					},

					removeActiveList: function()
					{
						self.item().removeClass( 'active' );
					},

					setDefault : function( id )
					{
						// Remove all items with default class
						self.item().removeClass( 'default' );

						// Add default class on the item
						self.item( '.item-' + id ).addClass( 'default' );
					},

					"{item} click" : function( el )
					{
						var title 	= $(el).data( 'title' ),
							url 	= $(el).data( 'url' );

						History.pushState( {state:1} , title , url );

						// Remove all active class from filters.
						self.parent.removeActiveFilter();

						// Remove all active class from list
						self.item().removeClass( 'active' );

						// Add active class to this element.
						self.item( el ).addClass( 'active' );

						var id 	= $( el ).data( 'id' );

						// Set the active list.
						self.parent.options.activeList	= id;
						
						// Get list of friends.
						EasySocial.ajax( 'site/controllers/friends/getListFriends',
						{
							"id"	: id
						},
						{
							beforeSend: function()
							{
								$( el ).addClass( 'loading' );
							}
						})
						.done(function( html ){

							// Hide loading.
							$( el ).removeClass( 'loading' );

							// Trigger friends list to update with appropriate content.
							self.parent.updateContent( html );

						});
					},

					"{loadMoreButton} click" : function() {
						
						// Get current limit start.
						var limitstart	= self.loadMoreButton().data( 'limitstart' );

						self.loadMore().html( self.view.loader() );

						// Get list of friends.
						EasySocial.ajax(
							"site/controllers/friends/getLists",
							{
								limitstart: limitstart
							})
							.done(function( items ){

								// Hide load more button since nothing to load anymore.
								self.loadMore().hide();

								self.view.items({
									"items"	: items
								}).appendTo( self.items() );
							});
					}
				}
			}
		);

		EasySocial.Controller(
			'Friends.List.Item',
			{
				defaultOptions: 
				{
					id 			: null,

					"{counter}"	: "[data-list-counter]"
				}
			},
			function( self )
			{
				return {

					init: function()
					{
						self.options.id 	= self.element.data( 'id' );
					},

					updateCounter: function( total )
					{
						self.counter().html( total );
					}
				}
			});

		EasySocial.Controller(
			'Friends.List.Actions',
			{
				defaultOptions:
				{
					"{delete}"	: "[data-friendListActions-delete]",
					"{add}"		: "[data-friendListActions-add]",
					"{default}"	: "[data-friendListActions-default]"
				}
			},
			function( self )
			{
				return {

					init : function()
					{
						self.options.id 	= self.element.data( 'id' );
						self.options.title 	= self.element.data( 'title' );
						self.options.userId	= self.element.data( 'userid' );
					},

					"{default} click" : function()
					{
						EasySocial.ajax( 'site/controllers/friends/setDefault' ,
						{
							"id"	: self.options.id
						})
						.done(function()
						{
							// Set the default class on the list item
							self.parent.friendList().controller().setDefault( self.options.id );
						});
					},

					"{add} click" : function()
					{
						EasySocial.dialog(
						{
							content 	: EasySocial.ajax( 'site/views/friends/assignList' , { "id" : self.options.id } ),
							bindings 	:
							{
								"{insertButton} click" : function()
								{
									var items = this.suggest().textboxlist("controller").getAddedItems();

									EasySocial.ajax( 'site/controllers/friends/assign' ,
									{
										"uid"		: $.pluck(items, "id"),
										"userId"	: self.options.userId,
										"listId"	: self.options.id
									})
									.done(function( contents ){

										// Hide any notice messages.
										$( '[data-assignFriends-notice]' ).hide();
										

										$( contents ).each(function( i , item ){

											// Pass the item to the parent so it gets inserted into the friends list.
											self.parent.insertItem( item );
											
											// Close the dialog
											EasySocial.dialog().close();
										});
									})
									.fail( function( message ){
										$( '[data-assignFriends-notice]' ).addClass( 'alert alert-error' )
											.html( message.message );
									});
								}
							}
						});
					},

					"{delete} click" : function()
					{
						EasySocial.dialog(
						{
							content 	: EasySocial.ajax( "site/views/friends/confirmDeleteList" , { "id" : self.options.id } ),
							bindings	:
							{
								"{deleteButton} click" : function()
								{
									$( '[data-friends-list-delete-form]' ).submit();
								}
							}
						});
					}
				}
			}
		);

		module.resolve();
	});
});

EasySocial.module( 'site/friends/item' , function($){

	var module 	= this;

	EasySocial.require()
	.script( 'site/conversations/composer' )
	.language( 'COM_EASYSOCIAL_FRIENDS_REQUEST_SENT_PENDING_APPROVAL', 'COM_EASYSOCIAL_FRIENDS_REQUEST_DIALOG_TITLE', 'COM_EASYSOCIAL_FRIENDS_CANCEL_REQUEST_DIALOG_CANCELLED' )
	.done(function($){

		EasySocial.Controller(
			'Friends.Item',
			{
				defaultOptions:
				{
					id 					: null,
					name 				: null,
					friendId 			: null,

					"{removeFromList}"	: "[data-lists-removeFriend]",
					"{unfriend}"		: "[data-friends-unfriend]",
					"{addfriend}"		: "[data-friends-addfriend]",
					"{block}"			: "[data-friends-block]",
					"{message}"			: "[data-friendItem-message]",
					"{reject}"			: "[data-friendItem-reject]",
					"{approve}"			: "[data-friendItem-approve]",
					"{cancelRequest}"	: "[data-friendItem-cancel-request]"
				}
			},
			function( self ){
				return {

					init: function()
					{
						self.options.id 		= self.element.data( 'id' );
						self.options.name 		= self.element.data( 'name' );
						self.options.friendId	= self.element.data( 'friendid' );
						self.options.avatar 	= self.element.data( 'avatar' );

						// Initialize conversation links
						self.initConversation();
					},

					initConversation : function()
					{
						// Implement conversation controller on the message link.
						self.message().implement( EasySocial.Controller.Conversations.Composer.Dialog ,
						{
							"recipient"	:
							{
								"name"		: self.options.name,
								"id"		: self.options.id,
								"avatar"	: self.options.avatar
							}
						});

					},

					"{removeFromList} click" : function()
					{
						EasySocial.dialog(
						{
							content 	: EasySocial.ajax( 'site/views/friends/confirmRemoveFromList' , { "id" : self.options.id }),
							bindings 	:
							{
								"{removeButton} click" : function()
								{
									EasySocial.ajax( 'site/controllers/friends/removeFromList' ,
									{
										"listId"	: self.parent.options.activeList,
										"userId"	: self.options.id
									})
									.done( function(){

										// Remove the item from the list.
										self.parent.removeItem( self.options.id );

										// Update the counter.

										// Update the dialog to notify the user that the user has been removed from the list.
										EasySocial.dialog(
										{
											"title"		: "User removed from list",
											"content"	: "The user has been removed from the list.",
											"buttons"	:
											[
												{
													"name"			: "Done",
													"classNames"	: "btn btn-es",
													"click"			: function()
													{
														EasySocial.dialog().close();
													}
												}
											]
										})
									})
									.fail( function(message){
										console.log( message );
									});
								}
							}
						});

					},

					"{reject} click" : function()
					{
						EasySocial.dialog(
						{
							content 	: EasySocial.ajax( 'site/views/friends/confirmReject' , { "id" : self.options.id } ),
							bindings	:
							{
								// "{rejectButton} click" : function()
								// {
								// 	$( '[data-friends-reject-form]' ).submit();
								// }


								"{rejectButton} click" : function()
								{
									EasySocial.ajax( 'site/controllers/friends/reject' ,
									{
										"id"	: self.options.friendId
									})
									.done(function()
									{
										// Remove itself from the list.
										self.parent.removeItem( self.options.id );

										// Update the counter
										self.parent.updateFriendsCounter();

										EasySocial.dialog(
										{
											content 	: EasySocial.ajax( 'site/views/friends/friendRejected' )
										});
									});
								}



							}
						});
					},

					"{unfriend} click" : function()
					{
						EasySocial.dialog(
						{
							content 	: EasySocial.ajax( 'site/views/friends/confirmUnfriend' , { "id" : self.options.id }),
							bindings 	:
							{
								"{unfriendButton} click" : function()
								{
									EasySocial.ajax( 'site/controllers/friends/unfriend' ,
									{
										"id"	: self.options.friendId
									})
									.done(function()
									{
										// Remove itself from the list.
										self.parent.removeItem( self.options.id );

										// Update the counter
										self.parent.updateFriendsCounter();

										EasySocial.dialog(
										{
											content 	: EasySocial.ajax( 'site/views/friends/friendRemoved' , { "id" : self.options.id } )
										});
									});
								}
							}
						});

					},

					"{cancelRequest} click" : function()
					{
						EasySocial.dialog(
						{
							content 	: EasySocial.ajax( 'site/views/friends/confirmCancelRequest' , { "id" : self.options.id }),
							bindings 	:
							{
								"{cancelRequestButton} click" : function()
								{
									EasySocial.ajax( 'site/controllers/friends/cancelRequest' ,
									{
										"id"	: self.options.friendId
									})
									.done(function()
									{
										// Remove itself from the list.
										self.parent.removeItem( self.options.id );

										// update count.
										self.parent.updateFriendRequestCount( -1 );

										EasySocial.dialog(
										{
											content 	: EasySocial.ajax( 'site/views/friends/requestCancelled' , { "id" : self.options.id } )
										});
									});
								}
							}
						});

					},

					"{approve} click" : function( el )
					{
						EasySocial.ajax( 'site/controllers/friends/approve',
						{
							"id" : self.options.friendId
						})
						.done(function()
						{
							// Update the counter
							self.parent.updateFriendsCounter();

							// Remove this item from the pending list.
							self.element.remove();
						});
					},

					"{addfriend} click" : function( el )
					{

						// Implement controller on add friend.
						EasySocial.ajax( 'site/controllers/friends/request' ,
						{
							"id"	: self.options.id
						})
						.done( function( friendId )
						{
							// update count
							self.parent.updateFriendRequestCount( 1 );

							EasySocial.dialog({
								title: $.language('COM_EASYSOCIAL_FRIENDS_REQUEST_DIALOG_TITLE'),
								content: $.language('COM_EASYSOCIAL_FRIENDS_REQUEST_SENT_PENDING_APPROVAL', self.options.name )
							});

							// Remove itself from the list.
							self.parent.removeItem( self.options.id );
						})
						.fail(function( message )
						{
							EasySocial.dialog({
								title: 'Info',
								content: message
							});
						});
					},

					"{block} click" : function()
					{
						console.log( 'block' );
					}
				}
			}
		);

		module.resolve();
	});
});


EasySocial.module( "site/notifications/list", function($){

	var module = this;


	EasySocial.require()
	.done( function($)
	{
		EasySocial.Controller( 'NotificationsList',
		{
			defaultOptions:
			{
				"{item}"		: "[data-notifications-list-item]",
				"{list}" 		: "[data-notifications-list]",
				"{allread}" 	: "[data-notification-all-read]",
				"{allclear}" 	: "[data-notification-all-clear]",

				"{notiLoadMoreBtn}" : "[data-notification-loadmore-btn]"
			}
		},
		function( self )
		{
			return {
				init : function()
				{
					self.item().implement( EasySocial.Controller.NotificationsList.Item ,
						{
							"{parent}"	: self
						});
				},

				"{allread} click" : function()
				{
					EasySocial.ajax( 'site/controllers/notifications/setAllState' ,
					{
						"state"	: "read"
					})
					.done(function()
					{
						self.item().removeClass( 'is-read is-hidden is-unread' ).addClass( 'is-read' );
					});
				},

				"{allclear} click" : function()
				{
					// show dialog to get confimation from user.
					var dialog =
						EasySocial.dialog({
							content: EasySocial.ajax(
								"site/views/notifications/clearAllConfirm"
							),
							bindings: {
								"{clearButton} click": function() {

									EasySocial.ajax( 'site/controllers/notifications/setAllState' ,
									{
										"state"	: "clear"
									})
									.done(function()
									{
										self.item().removeClass( 'is-read is-hidden is-unread is-read' ).addClass( 'is-remove' );
										EasySocial.dialog().close();
									});

								}
							}
						});
				},

				"{notiLoadMoreBtn} click" : function( el, event )
				{
					var startlimit 	= $(el).data( 'startlimit' );
					if( startlimit < 0 )
					{
						return;
					}

					EasySocial.ajax( 'site/controllers/notifications/loadmore' ,
					{
						"startlimit" : startlimit
					})
					.done(function( contents, nextlimit )
					{
						// update next limit
						$(el).data( 'startlimit', nextlimit );

						console.log( nextlimit );

						if( contents.length > 0 )
						{
							$.buildHTML(contents)
							 	.insertBefore( self.notiLoadMoreBtn() );
							 	// .addController("NotificationsList.Item");

							 //add controller
							 self.item().implement( EasySocial.Controller.NotificationsList.Item );
						}

						if( nextlimit < 0)
						{
							// no more item. let hide the loadmore button.
							self.notiLoadMoreBtn().hide();
						}

					})
					.fail( function( messageObj ){
						return messageObj;
					})
					.always(function(){

						// self.loading = false;
					});


				}



			}
		});

		EasySocial.Controller( 'NotificationsList.Item' ,
		{
			defaultOptions :
			{
				"{unread}"	: "[data-notifications-list-item-unread]",
				"{read}"	: "[data-notifications-list-item-read]",
				"{delete}"	: "[data-notifications-list-item-delete]"
			}
		},
		function(self)
		{
			return {
				init : function()
				{
					self.options.id 	= self.element.data( 'id' );

				},

				"{unread} click" : function()
				{
					EasySocial.ajax( 'site/controllers/notifications/setState' ,
					{
						"id"	: self.options.id,
						"state"	: "unread"
					})
					.done(function()
					{
						self.element.removeClass( 'is-read is-hidden is-unread' ).addClass( 'is-unread	' );
					});
				},

				"{read} click" : function()
				{
					EasySocial.ajax( 'site/controllers/notifications/setState' ,
					{
						"id"	: self.options.id,
						"state"	: "read"
					})
					.done(function()
					{
						self.element.removeClass( 'is-read is-hidden is-unread' ).addClass( 'is-read' );
					});
				},

				"{delete} click" : function()
				{


					var dialog =
						EasySocial.dialog({
							content: EasySocial.ajax(
								"site/views/notifications/clearConfirm"
							),
							bindings: {
								"{clearButton} click": function() {

									EasySocial.ajax( 'site/controllers/notifications/setState' ,
									{
										"id"	: self.options.id,
										"state"	: "clear"
									})
									.done(function()
									{
										self.element.removeClass( 'is-read is-hidden is-unread is-read' ).addClass( 'is-remove' );
										EasySocial.dialog().close();
									})
									.fail(function( msg )
									{
										EasySocial.dialog({
											content: msg.message
										});
									});
								}
							}
						});



					// EasySocial.ajax( 'site/controllers/notifications/setState' ,
					// {
					// 	"id"	: self.options.id,
					// 	"state"	: "hidden"
					// })
					// .done(function()
					// {
					// 	self.element.removeClass( 'is-read is-hidden is-unread' ).addClass( 'is-hidden' );
					// });


				}
			}
		});

		module.resolve();
	});

});

EasySocial.module( 'site/points/history' , function(){

	var module	= this;

	EasySocial.require()
	.done(function($)
	{
		EasySocial.Controller(
			'Points.History',
			{
				defaultOptions :
				{
					"{loadMore}"	: "[data-points-history-pagination]",
					"{timeline}"	: "[data-points-history-timeline]"
				}
			},
			function( self )
			{
				return {
					init : function()
					{
					},

					"{loadMore} click" : function( el , event )
					{
						var current 	= $( el ).data( 'current' );

						EasySocial.ajax( 'site/views/points/getHistory' , 
						{
							"limitstart"	: current,
							"id"			: self.options.id
						}).done(function( contents , nextLimit , done )
						{
							self.timeline().append( contents );

							$( el ).data( 'current' , nextLimit );

							if( done )
							{
								$( el ).hide();
								// $( el ).attr( 'disabled' , 'disabled' );
							}
						});
					}
				}
			});

		module.resolve();
	});
});

EasySocial.module("notifications/popbox", function($){

	this.resolve(function(popbox)
	{

		return {
			content: EasySocial.ajax( "site/controllers/notifications/getSystemItems", 
			{
				layout	: "popbox.notifications"
			}),
			id: "es-wrap",
			type: "notifications",
			position: "bottom"
		};
	});
});

EasySocial.module("conversations/popbox", function($){

	this.resolve(function(popbox)
	{
		return {
			content: EasySocial.ajax( "site/controllers/notifications/getConversationItems", 
			{
				usemax 	: "1",
				layout	: "popbox.conversations"
			}),
			id: "es-wrap",
			type: "notifications",
			position: "bottom"
		};
	});
});

EasySocial.module("friends/popbox", function($){

	this.resolve(function(popbox){

		return {
			content: EasySocial.ajax( "site/controllers/notifications/friendsRequests", 
			{
				layout	: "popbox.friends"
			}),
			id: "es-wrap",
			type: "notifications",
			position: "bottom"
		};
	});
});
EasySocial.module( 'site/profile/about', function($){
	var module = this;

	EasySocial.require().script('field').done(function($) {
		EasySocial.Controller('Profile.About', {
			defaultOptions: {
				userid			: null,

				'{stepItem}'	: '[data-profile-about-step-item]',
				'{stepContent}'	: '[data-profile-about-step-content]',

				'{fieldItem}'	: '[data-profile-about-fields-item]'
			}
		}, function(self) {
			return {
				init: function() {
					self.fieldItem().addController('EasySocial.Controller.Field.Base', {
						userid: self.options.userid,
						mode: 'display'
					});
				},

				'{stepItem} click': function(el, ev) {
					var target = el.data('for');

					self.stepItem().removeClass('active');

					el.addClass('active');

					self.stepContent().trigger('activateTab', [target]);
				},

				'{stepContent} activateTab': function(el, ev, target) {
					var id = el.data('id');

					el.toggleClass('active', target == id);
				}
			}
		});

		module.resolve();
	});
});

EasySocial.module('site/profile/avatar' , function($){

	var module = this;

	EasySocial.Controller("Profile.Avatar",
		{
			defaultOptions: {
				"{menu}": "[data-avatar-menu]",
				"{uploadButton}": "[data-avatar-upload-button]",
				"{selectButton}": "[data-avatar-select-button]",
				"{removeButton}": "[data-avatar-remove-button]"
			}
		},
		function(self) { return {

			init: function() {
			},

			"{uploadButton} click": function() {

				EasySocial.dialog({
					content: EasySocial.ajax("site/views/profile/uploadAvatar")
				});
			},

			"{selectButton} click": function() {

				EasySocial.photos.selectPhoto({
					bindings: {
						"{self} photoSelected": function(el, event, photos) {

							// Photo selection dialog returns an array,
							// so just pick the first one.
							var photo = photos[0];

							// If no photo selected, stop.
							if (!photo) return;

							EasySocial.photos.createAvatar(photo.id);
						}
					}
				});
			},

            "{menu} dropdownOpen": function() {
                 self.element.addClass("show-all");
            },

            "{menu} dropdownClose": function() {
                 self.element.removeClass("show-all");
            },

			"{removeButton} click": function() {

			}

		}}
	);

	module.resolve();


});

EasySocial.module( 'site/profile/edit' , function($){

	var module 				= this;

	EasySocial.require()
	.script( 'validate', 'field', 'oauth/facebook' )
	.done(function($){

		EasySocial.Controller(
			'Profile.Edit',
			{
				defaultOptions:
				{
					userid				: null,

					"{stepContent}"		: "[data-profile-edit-fields-content]",
					"{stepItem}"		: "[data-profile-edit-fields-step]",

					// Forms.
					"{profileForm}"		: "[data-profile-fields-form]",

					// Content for profile editing
					"{profileContent}"	: "[data-profile-edit-fields]",

					"{fieldItem}"		: "[data-profile-edit-fields-item]",

					// Submit buttons
					"{save}"			: "[data-profile-fields-save]",

					// Delete Profile
					"{deleteProfile}"	: "[data-profile-edit-delete]"
				}
			},
			function( self )
			{
				return {

					init: function()
					{
						self.fieldItem().addController('EasySocial.Controller.Field.Base', {
							userid: self.options.userid,
							mode: 'edit'
						});
					},

					errorFields: [],

					// Support field throwing error internally
					'{fieldItem} error': function(el, ev) {
						self.triggerStepError(el);
					},

					// Support for field resolving error internally
					'{fieldItem} clear': function(el, ev) {
						self.clearStepError(el);
					},

					// Support validate.js throwing error externally
					'{fieldItem} onError': function(el, ev) {
						self.triggerStepError(el);
					},

					triggerStepError: function(el) {
						var fieldid = el.data('id'),
							stepid = el.parents(self.stepContent.selector).data('id');

						if($.inArray(fieldid, self.errorFields) < 0 ) {
							self.errorFields.push(fieldid);
						}

						self.stepItem().filterBy('for', stepid).trigger('error');
					},

					clearStepError: function(el) {
						var fieldid = el.data('id'),
							stepid = el.parents(self.stepContent.selector).data('id');

						self.errorFields = $.without(self.errorFields, fieldid);

						self.stepItem().filterBy('for', stepid).trigger('clear');
					},

					"{stepItem} click" : function( el , event )
					{
						var id 	= $( el ).data( 'for' );

						// Profile form should be hidden
						self.profileContent().show();

						// Hide all profile steps.
						self.stepContent().hide();

						// Remove active class on step item
						self.stepItem().removeClass( 'active' );

						// Add active class on the selected item.
						self.stepItem( el ).addClass( 'active' );

						// Get the step content element
						var stepContent = self.stepContent( '.step-' + id );

						// Show active profile step.
						stepContent.show();

						// Trigger onShow on the field item in the content
						stepContent.find(self.fieldItem.selector).trigger( 'show' );
					},

					"{stepItem} error": function(el) {
						el.addClass('error');
					},

					"{stepItem} clear": function(el) {
						if(self.errorFields.length < 1) {
							el.removeClass('error');
						}
					},

					"{tabItem} click" : function( el , event )
					{
						var item 	= $( el ).data( 'for' );

						// Hide all tab headers
						self.tabHeaderItem().hide();

						// Show active header.
						self.tabHeaderItem( '.' + item + 'Header' ).show().addClass( 'active' );
					},

					"{save} click" : function( el , event )
					{
						// Run some error checks here.
						event.preventDefault();

						$( el ).addClass( 'btn-loading' );

						self.profileForm()
							.validate()
							.fail( function()
							{
								$( el ).removeClass( 'btn-loading' );
								EasySocial.dialog(
								{
									content 	: EasySocial.ajax( 'site/views/profile/showFormError' )
								});
							})
							.done( function()
							{
								self.profileForm().submit();
							});

						return false;
					},


					"{deleteProfile} click" : function()
					{
						EasySocial.dialog(
						{
							content 	: EasySocial.ajax( 'site/views/profile/confirmDelete' )
						});
					}
				}
		});

		module.resolve();
	});

});

EasySocial.module('validate', function ($) {
/*
<div data-check>
	<div>
		<label>Text</label>
		<input data-check-type="text" data-check-field />
	</div>
	<div data-check-notice></div>
</div>
<div data-check>
	<div>
		<label>Checkboxes</label>
		<div data-check-type="checkbox" data-check-field data-check-required>
			<input type="checkbox" name="group[]" value="1" />
			<input type="checkbox" name="group[]" value="1" />
		</div>
	</div>
</div>
<div data-check>
	<div>
		<label>Validate format</label>
		<input data-check-field data-check-validate="regex" />
	</div>
	<div data-check-notice></div>
</div>
*/

	var module = this;

	if(!$.isController('EasySocial.Controller.Validator')) {
		EasySocial.Controller('Validator', {
			defaultOptions: {
				checks			: ['required', 'validate'],

				typeAttr		: 'data-check-type',
				formatAttr		: 'data-check-format',
				modifierAttr	: 'data-check-modifier',

				errorTrigger	: 'onError',
				submitTrigger	: 'onSubmit',

				'{container}'	: '[data-check]',
				'{notice}'		: '[data-check-notice]',

				'{required}'	: '[data-check-required]',
				'{validate}'	: '[data-check-validate]',
			}
		}, function(self) {
			return {
				// temporary variables
				vars		: {},

				// register of elements returned by fields
				register	: [],

				// deferreds return by elements
				deferreds	: [],

				// errors return by elements
				errors		: [],

				// state of validator
				state		: $.Deferred(),

				init: function () {

				},

				reset: function() {
					self.vars		= {};

					self.register	= [];
					self.deferreds	= [];
					self.errors		= [];

					self.state		= $.Deferred();

					self.container().removeClass('error');
				},

				start: function() {
					self.reset();

					$.each(self.container(), function(i, container) {
						self.vars.container = container = $(container);

						container.trigger(self.options.submitTrigger, [self.register]);

						$.each(self.getFields(), function(j, field) {
							self.vars.field = field = $(field);

							$.each(self.options.checks, function(i, check) {
								self.vars.check = check;

								self[check + 'Check']();
							});
						});
					});

					$.each(self.register, function(i, result) {
						if($.isDeferred(result)) {
							self.deferreds.push(result);
						} else {
							if(!result) {
								self.errors.push(i);
								return true;
							}
						}
					});

					// If have static errors, then reject state
					if(self.errors.length > 0) {
						self.state.reject();
					} else {
						// If no static errors, then check if have deferreds
						if(self.deferreds.length > 0) {
							// This is because $.when accepts n amount of parameters instead of array, so we use .apply to pass in the array
							$.when.apply(null, self.deferreds)
								.done(function() {
									self.state.resolve();
								})
								.fail(function() {
									self.state.reject();
								});
						} else {
							// If no deferreds, then just resolve
							self.state.resolve();
						}
					}

					return self.state;
				},

				getFields: function() {
					return $.merge(self.vars.container.find(self.required.selector), self.vars.container.find(self.validate.selector));
				},

				requiredCheck: function() {
					if(self.vars.field.is(self.required.selector)) {
						var fieldType = self.vars.field.attr(self.options.typeAttr) || self.vars.field.attr('type') || 'text';

						if(fieldType === 'text' && $.trim(self.vars.field.val()) == '' ) {
							self.raiseError();
						}

						if(fieldType === 'checkbox' && self.vars.field.find('input[type="checkbox"]').filter(':checked').length < 1) {
							self.raiseError();
						}
					}
				},

				validateCheck: function() {
					if(self.vars.field.attr(self.options.formatAttr) !== undefined) {
						var format = self.vars.field.attr(self.options.formatAttr) || '';

						var modifier = self.vars.field.attr(self.options.modifierAttr) || '';

						var regex = new RegExp(format, modifier);

						if(!regex.test(self.vars.field.val())) {
							self.raiseError();
						}
					}
				},

				raiseError: function () {
					self.vars.container.addClass('error');

					self.vars.container.trigger(self.options.errorTrigger, [self.vars.check, self.vars.field]);

					self.register.push(false);
				}
			};
		});
	}

	$.fn.validate = function(options){
		var element = this;

		if(element.length > 0) {
			var controller = this.addController("EasySocial.Controller.Validator", options);
			return controller.start();
		}

		return false;
	};

	module.resolve();
});


EasySocial.module( 'site/profile/friends' , function($){

	var module 				= this;

	EasySocial.require()
	.view( 'site/loading/small')
	.language(
		'COM_EASYSOCIAL_FRIENDS_DIALOG_CANCEL_REQUEST',
		'COM_EASYSOCIAL_CANCEL_BUTTON',
		'COM_EASYSOCIAL_YES_CANCEL_MY_REQUEST_BUTTON'
	)
	.done(function($){

		EasySocial.Controller(
			'Profile.Friends.Request',
			{
				defaultOptions:
				{
					id 		: null,
					callback		: null,

					// Elements
					"{addButton}"		: "[data-profileFriends-add]",
					"{manageButton}"	: "[data-profileFriends-manage]",
					"{pendingButton}"	: "[data-profileFriends-pending]",
					"{respondButton}"	: "[data-profileFriends-respond]",
					"{cancelRequest}"	: "[data-profileFriends-cancelRequest]",

					"{unfriend}"		: "[data-friends-unfriend]",
					"{approve}"			: "[data-friends-response-approve]",
					"{reject}"			: "[data-friends-response-reject]",

					// The current add friend / cancel friend btuton.
					"{button}"			: "[data-profileFriends-button]",

					// Dropdown
					"{dropdown}"		: "[data-profileFriends-dropdown]",

					view :
					{
						loader 			: "site/loading/small",
					}
				}
			},
			function(self)
			{
				return{

					init: function()
					{
						// Set the friend id.
						self.options.id 		= self.element.data( 'friend' );

						// Set the target id
						self.options.target 	= self.element.data( 'id' );

						// Set the callback url
						self.options.callback 	= self.element.data( 'callback' );
					},

					showDropDown : function()
					{
						self.element.addClass( 'open' );
					},

					hideDropDown : function()
					{
						self.element.removeClass( 'open' );
					},

					"{addButton} click" : function( el ) {

						var button = self.button();
						
						button.addClass("loading");

						EasySocial.ajax(
							"site/controllers/friends/request",
							{
								id: self.options.target
							})
							.done( function(friendId, button){

								// Remove any previous dropdown
								self.dropdown().remove();

								// After the request is complete, set the correct friend id.
								self.options.id = friendId;

								// Replace the button
								self.button().replaceWith(button);
							})
							.fail(function(){
								// self.dropdown().html( message );
								button.removeClass("loading");
							});
					},

					"{cancelRequest} click" : function( el , event )
					{
						// If user can click on the cancel request, they should have a valid friend id by now.
						var friendId 	= self.options.id;

						// Hide any dropdown that's open
						self.hideDropDown();

						// Show confirmation dialog
						EasySocial.dialog({
							content: EasySocial.ajax( 'site/views/friends/confirmCancel' ),
							bindings:
							{
								"{confirmButton} click": function()
								{
									EasySocial.ajax( 'site/controllers/friends/cancelRequest' ,
									{
										"id"	: self.options.id
									})
									.done( function( button )
									{
										// Remove any previous dropdowns.
										self.dropdown().remove();

										// Update the button
										self.button().replaceWith( button );

										// Hide the dialog once the request has been cancelled.
										EasySocial.dialog().close();
									});
								}
							}
						});

					},

					"{unfriend} click" : function()
					{
						// Implement controller on add friend.
						EasySocial.dialog(
						{
							content		: EasySocial.ajax( 'site/views/friends/confirmUnfriend' , { "id"	: self.options.id } ),
							bindings 	: 
							{
								"{unfriendButton} click" : function()
								{
									EasySocial.ajax( 'site/controllers/friends/unfriend' ,
									{
										"id"	: self.options.id
									})
									.done(function( button )
									{
										// Remove any previous dropdowns.
										self.dropdown().remove();

										// Update the button
										self.button().replaceWith( button );

										// Close the dialog
										EasySocial.dialog().close();
									});
								}
							}
						});
					},

					"{approve} click" : function( el , event )
					{
						var friendId 	= self.options.id;

						// Remove any previous dropdown
						self.dropdown().remove();

						EasySocial.ajax( 'site/controllers/friends/approve' ,
						{
							"id"	: friendId
						})
						.done( function( button )
						{
							// Replace the button.
							self.button().replaceWith( button );
						});
					},

					"{reject} click" : function( el , event )
					{
						var friendId 	= self.options.id;

						// Remove any previous dropdown
						self.dropdown().remove();

						EasySocial.dialog(
						{
							content 	: EasySocial.ajax( 'site/views/friends/confirmReject' ),
							bindings :
							{
								"{rejectButton} click" : function()
								{
									EasySocial.ajax( 'site/controllers/friends/reject' ,
									{
										"id"	: friendId
									})
									.done( function( button )
									{
										// Update the button.
										self.button().replaceWith( button );

										EasySocial.dialog(
										{
											content 	: EasySocial.ajax( 'site/views/friends/friendRejected' ),
										});
										
									});

									
								}
							}
						});
					},

					"{dropdown} click" : function( el , event )
					{
						// Disallow clicking of events to trigger parent items.
						event.stopPropagation();
					},

					"{approveRequest} click" : function()
					{
						// Update the task
						self.respondForm().find( 'input[name=task]' ).val( 'approve' );

						// Update the friend id
						self.respondForm().find( 'input[name=id]' ).val( self.options.friendId );

						// Update the return url.
						self.respondForm().find( 'input[name=return]' ).val( self.options.callback );

						// Submit the form.
						self.respondForm().submit();
					}
				}
		});


		module.resolve();
	});

})

EasySocial.module( 'site/profile/header' , function($){

	var module 				= this;

	EasySocial.require()
	.script(
		'site/profile/friends' ,
		'site/profile/subscriptions' ,
		'site/conversations/composer'
	)
	.done(function($){

		EasySocial.Controller(
			'Profile.Header',
			{
				defaultOptions:
				{
					// Properties
					id			: null,

					// Elements
					"{friendRequest}"	: "[data-profile-friends]",
					"{subscribe}"		: "[data-profile-followers]",
					"{conversation}"	: "[data-profile-conversation]"
				}
			},
			function(self)
			{
				return {

					init: function()
					{
						// Get the id of the current user.
						self.options.id 	= self.element.data( 'id' ),
						self.options.name 	= self.element.data( 'name' ),
						self.options.avatar	= self.element.data( 'avatar' );

						// Implement friends controller on the friend request button.
						self.friendRequest().implement( EasySocial.Controller.Profile.Friends.Request,
						{
							"{parent}"	: self
						});

						// Implement subscription controller on the subscribe button.
						self.subscribe().implement( EasySocial.Controller.Profile.Subscriptions,
						{
							"{parent}"	: self
						});

						self.conversation().implement( EasySocial.Controller.Conversations.Composer.Dialog,
						{
							"recipient"	:
							{
								"id"	: self.options.id,
								"name"	: self.options.name,
								"avatar": self.options.avatar
							}
						});
					}
				}
			}
		);


		module.resolve();
	});


});

EasySocial.module( 'site/profile/subscriptions' , function($){

	var module 				= this;

	EasySocial.Controller(
		'Profile.Subscriptions',
		{
			defaultOptions:
			{
				// Properties
				id			: null,

				"{follow}"	: "[data-subscription-follow]",
				"{unfollow}": "[data-subscription-unfollow]",
				"{message}"	: "[data-subscription-message]",
				"{button}"	: "[data-subscription-button]"
			}
		},
		function(self)
		{
			return{

				init: function()
				{
					self.options.id 	= self.element.data( 'id' );
				},

				toggleDropDown : function()
				{
					self.element.toggleClass( 'open' );
				},

				"{unfollow} click" : function()
				{
					// Toggle dropdown.
					self.toggleDropDown();

					// Let's do an ajax call to follow the user.
					EasySocial.ajax( 'site/controllers/profile/unfollow' ,
					{
						"id"	: self.options.id,
						"type"	: 'user'
					})
					.done(function(button)
					{
						self.button().replaceWith( button );
					})
				},

				"{follow} click" : function()
				{
					// Toggle dropdown.
					self.toggleDropDown();

					// Let's do an ajax call to follow the user.
					EasySocial.ajax( 'site/controllers/profile/follow' ,
					{
						"id"	: self.options.id,
						"type"	: 'user'
					})
					.done(function( button )
					{
						self.button().replaceWith( button );
					});
				}
			}
		});

		module.resolve();

});

EasySocial.module( 'site/profile/miniheader' , function($){

	var module = this;

	EasySocial.require()
	.library(
		'scrollTo'
	)
	.done(function($){

		EasySocial.Controller(
			'Profile.MiniHeader',
			{
				defaultOptions: {

					"{viewport}": "[data-appscroll-viewport]",
					"{content}": "[data-appscroll-content]",
					"{apps}": "[data-appscroll-content] li",
					"{buttons}": "[data-appscroll-buttons]",
					"{nextButton}": "[data-appscroll-next-button]",
					"{prevButton}": "[data-appscroll-prev-button]"
				}
			},
			function(self){ return {

				init: function() {

					self.setLayout();

					// When page is refreshed, scroll value might be retained.
					self.viewport().scrollTo(0);
				},

				"{window} resize": $.debounce(function(){

					self.setLayout();

				}, 300),

				setLayout: function() {

					var viewport = self.viewport(),
						width = 5;

					if ($("#es-wrap.es-main").hasClass("w480")) {
						
						self.content().css({
							width: "auto"
						});
						
						self.enabled = false;

						return;
					}

					self.apps().each(function(){ width += $(this).outerWidth(true) });

					if (width > viewport.width()) {

						self.content()
							.css({
								width: width,
								float: "none"
							});

						self.buttons()
							.css("opacity", 1);

						self.enabled = true;
					}
				},

				enabled: false,

				"{nextButton} click": function() {

					if (!self.enabled) return;

					var viewport = self.viewport(),
						width = viewport.width() - 80; // 80 offset

					viewport.scrollTo('+=' + width + 'px', 800, {axis: 'x', easing: 'easeInOutCubic'});
				},

				"{prevButton} click": function() {

					if (!self.enabled) return;

					var viewport = self.viewport(),
						width = viewport.width() - 80; // 80 offset

					viewport.scrollTo('-=' + width + 'px', 800, {axis: 'x', easing: 'easeInOutCubic'});
				}

			}});


		module.resolve();
	});


});

EasySocial.module('site/profile/notifications', function($) {

	var module = this;

	EasySocial.require()
		.script('site/profile/header')
		.done(function() {
			EasySocial.Controller('Profile.Notifications', {
				defaultOptions: {
					// App item
					"{sidebarItem}"	: "[data-notification-item]",
					"{contentItem}"	: "[data-notification-content]",

					//input form
					"{notificationForm}" : "[data-notifications-form]"
				},
			}, function(self) {
				return {

					init : function() {
					},

					"{sidebarItem} click": function(el, event) {
						self.sidebarItem().removeClass('active');

						el.addClass('active');

						self.contentItem().hide();

						var element = el.data('alert-element');

						self.contentItem('[data-alert-element="' + element + '"]').show();
					}
				}
			});

			module.resolve();
		});
});

EasySocial.module( 'site/profile/privacy' , function($){

	var module 	= this;

	EasySocial.require()
	.script( 'site/profile/header' )
	.library( 'history', 'textboxlist' )
	.view( 'site/loading/small' )
	.done(function($){

		EasySocial.Controller(
			'Profile.Privacy',
			{
				defaultOptions:
				{
					// App item
					"{sidebarItem}"	: "[data-profile-privacy-item]",
					"{contentItem}"	: "[data-privacy-content]",

					"{privacyItem}" : "[data-privacy-item]",

					//input form
					"{privacyForm}" : "[data-profile-privacy-form]",

					view :
					{
						loading : "site/loading/small"
					}
				}
			},
			function( self )
			{
				return {

					init : function()
					{
						// Implement profile header.
						self.sidebarItem().implement( EasySocial.Controller.Profile.Privacy.Sidebar ,
						{
							"{parent}"	: self
						});

						self.privacyItem().implement( EasySocial.Controller.Profile.Privacy.Item ,
						{
							"{parent}"	: self
						});
					},

					updateContent : function( group )
					{
						self.contentItem().hide();
						$( '.privacy-content-' + group ).show();
					}
				}
			}
		);

		EasySocial.Controller(
			'Profile.Privacy.Sidebar',
			{
				defaultOptions :
				{
					"{sidebarItem}"		: "[data-profile-privacy-item]",
				}
			},
			function( self )
			{
				return {
					init : function()
					{

					},

					"{self} click" : function( el )
					{
						// Prevent from bubbling up.
						// event.preventDefault();

						$('[data-profile-privacy-item]').removeClass( 'active' );
						$( el ).addClass( 'active' );

						var group = self.element.data( 'group' );
						self.parent.updateContent( group );
					}
				}
			});


		EasySocial.Controller(
			'Profile.Privacy.Item',
			{
				defaultOptions :
				{
					"{selection}"		: "[data-privacy-select]",
					"{hiddenCustom}" 	: "[data-hidden-custom]",
					"{customForm}" 		: "[data-privacy-custom-form]",

					"{customTextInput}" : "[data-textfield]",
					"{customItems}"		: "input[]",
					"{customHideBtn}"	: "[data-privacy-custom-hide-button]",
					"{customInputItem}"	: "[data-textboxlist-item]",
					"{customEditBtn}"   : "[data-privacy-custom-edit-button]"
				}
			},
			function( self )
			{
				return {
					init : function()
					{

						self.customTextInput().textboxlist(
							{
								unique: true,

								plugin: {
									autocomplete: {
										exclusive: true,
										minLength: 2,
										cache: false,
										query: function( keyword ) {

											var users = self.getTaggedUsers();

											var ajax = EasySocial.ajax("site/views/privacy/getfriends", {
												q: keyword,
												exclude: users
											});
											return ajax;
										}
									}
								}
							}
						);

						self.textboxlistLib = self.customTextInput().textboxlist("controller");
					},

					getTaggedUsers: function()
					{
						var users = [];
						var items = self.customInputItem();

						if( items.length > 0 )
						{
							$.each( items, function( idx, element ) {
								users.push( $( element ).data('id') );
							});
						}

						return users;
					},

					// event listener for adding new name
					"{customTextInput} addItem": function(el, event, data) {

						// lets get the exiting ids string
						var ids    = self.hiddenCustom().val();
						var values = '';

						if( ids == '')
						{
							values = data.id;
						}
						else
						{
							var idsArr = ids.split(',');
							idsArr.push( data.id );

							values = idsArr.join(',');
						}

						//now update the customhidden value.
						self.hiddenCustom().val( values );
					},

					// event listener for removing name
					"{customTextInput} removeItem": function(el, event, data ) {
						// lets get the exiting ids string
						var ids    = self.hiddenCustom().val();
						var values = '';
						var newIds = [];

						var idsArr = ids.split(',');

						for( var i = 0; i < idsArr.length; i++ )
						{
							if( idsArr[i] != data.id )
							{
								newIds.push( idsArr[i] );
							}
						}

						if( newIds.length <= 0 )
						{
							values = '';
						}
						else
						{
							values = newIds.join(',');
						}

						//now update the customhidden value.
						self.hiddenCustom().val( values );
					},

					"{customEditBtn} click" : function( el )
					{
						self.customForm().toggle();
					},

					"{selection} change" : function( el )
					{
						var selected = el.val();

						if( selected == 'custom' )
						{
							self.customForm().show();
							self.customEditBtn().show();
						}
						else
						{
							self.customForm().hide();
							self.customEditBtn().hide();
						}

						return;
					},

					"{customHideBtn} click" : function()
					{
						self.customForm().hide();

						self.customEditBtn().show();

						self.textboxlistLib.autocomplete.hide();

						return;
					}
				}
			});


		module.resolve();
	});

});

EasySocial.module( 'site/profile/profile' , function($){

	var module 	= this;

	EasySocial.require()
	.script( 'site/profile/header' )
	.library( 'history' )
	.done(function($){

		EasySocial.Controller(
			'Profile',
			{
				defaultOptions:
				{
					// The current user being viewed
					id 	: null,

					// Elements
					"{header}"	: "[data-profile-header]",

					// App item
					"{app}"		: "[data-profile-apps-item]",
					"{action}"	: "[data-profile-apps-menu]",

					// Contents
					"{contents}"	: "[data-profile-real-content]"

				}
			},
			function( self )
			{
				return {

					init : function()
					{
						// Get the user's id.
						self.options.id 	= self.element.data( 'id' );

						// Implement profile header.
						self.header().implement( EasySocial.Controller.Profile.Header ,
						{
							"{parent}"	: self
						});

						// Implement the apps
						self.app().implement( EasySocial.Controller.Profile.Apps.Item ,
							{
								"{parent}"	: self
							});

					},

					"{app} click" : function( el )
					{
						// Remove active class.
						self.app().removeClass( 'active' );

						// Add active class to this current item.
						$( el ).addClass( 'active' );
					},

					updateContent : function( content )
					{
						self.element.removeClass("loading");

						self.contents().html( content );
					},

					loading: function()
					{
						// self.contents().html( self.view.loading({}) );
						self.contents().html("");
						self.element.addClass("loading");
					}
				}
			}
		);

		EasySocial.Controller(
			'Profile.Apps.Item',
			{
				defaultOptions :
				{

				}
			},
			function( self )
			{
				return {
					init : function()
					{
						self.options.layout 		= self.element.data( 'layout' );
						self.options.id 			= self.element.data( 'id' );
						self.options.url 			= self.element.data( self.options.layout + '-url' );
						self.options.namespace 		= self.element.data( 'namespace' );
						self.options.title 			= self.element.data( 'title' );
						self.options.description	= self.element.data( 'description' );
						self.options.appId 			= self.element.data( 'app-id' );

					},

					"{self} click" : function( el , event )
					{
						// Prevent from bubbling up.
						event.preventDefault();

						// If this is a canvas layout, redirect the user to the canvas view.
						if( self.options.layout == 'canvas' )
						{
							window.location 	= self.options.url;
							return;
						}

						// If this is an embedded layout, we need to play around with the push state.
						History.pushState( {state:1} , self.options.title , self.options.url );

						// Send a request to the dashboard to update the content from the specific app.
						EasySocial.ajax( self.options.namespace ,
						{
							"id"		: self.options.id,
							"view"		: "profile",
							"appId"		: self.options.appId
						},
						{
							beforeSend 	: function()
							{
								// Notify the dashboard that it's starting to fetch the contents.
								self.parent.loading();
							}
						})
						.done( function( contents )
						{
							// Update the content with proper value
							self.parent.updateContent( contents );
						})
						.fail(function( messageObj ){

							return messageObj;

						});
					}
				}
			});

		module.resolve();
	});

});

EasySocial.module( 'site/registrations/registrations' , function($){

	var module 				= this;

	EasySocial.require()
	.script( 'validate', 'field' )
	.view( 'site/registration/dialog.error' )
	.language( 'COM_EASYSOCIAL_CLOSE_BUTTON' , 'COM_EASYSOCIAL_REGISTRATION_ERROR_DIALOG_TITLE' )
	.done(function($){

		EasySocial.Controller(
			'Registrations.Form',
			{
				defaultOptions:
				{
					// passed in by caller
					previousLink	 : null,

					"{submit}"		: "[data-registration-submit]",
					"{field}"		: "[data-registration-fields-item ]",
					"{previous}"	: "[data-registration-previous]",

					view :
					{
						formError 	: "site/registration/dialog.error"
					}
				}
			},
			function(self)
			{

				return{

					init: function()
					{
						self.field().addController('EasySocial.Controller.Field.Base', {
							mode: 'register'
						});
					},

					"{previous} click" : function( el , event )
					{
						event.preventDefault();


						window.location.href	= self.options.previousLink;

						return false;
					},

					"{submit} click" : function( el , event )
					{
						event.preventDefault();

						// Apply loading class on button
						$( el ).addClass( 'btn-loading' );
						
						$( self.element ).validate()
						.fail( function()
						{
							// Remove loading class
							$( el ).removeClass( 'btn-loading' );

							EasySocial.dialog(
							{
								"title"		: $.language( 'COM_EASYSOCIAL_REGISTRATION_ERROR_DIALOG_TITLE' ),
								"content"	: self.view.formError({}),
								"width"		: 400,
								"height"	: 150,
								"buttons"	:
								[
									{
										"name"	: $.language( 'COM_EASYSOCIAL_CLOSE_BUTTON' ),
										"classNames"	: "btn btn-es-primary",
										"click"	: function()
										{
											EasySocial.dialog().close();
										}
									}
								]

							});
						})
						.done( function()
						{
							self.element.submit();
						});

						return false;
					}
				}
			});

		module.resolve();
	});

});

EasySocial.module( 'site/search/item' , function($){

	var module	= this;

	EasySocial.require()
	.view(
		'site/loading/small'
	)
	.language(
		'COM_EASYSOCIAL_FRIENDS_REQUEST_SENT_NOTICE'
	)
	.done(function($){

		EasySocial.Controller(
			'Search.Item',
			{
				defaultOptions:
				{
					// Elements
					"{toggle}"		: "[data-activity-toggle]",
					"{deleteBtn}"	: "[data-activity-delete]",

					"{addFriendButton}" : "[data-search-friend-button]",
					"{pendingFriendButton}" : "[data-search-friend-pending-button]",
					// Dropdown
					"{dropdown}"		: "[data-profileFriends-dropdown]",

					view :
					{
						loader 			: "site/loading/small"
					}
				}
			},
			function( self ){
				return {

					init : function()
					{
						// Implement sidebar controller.
						friendid 		: null
					},

					"{pendingFriendButton} click" : function( el )
					{
						self.dropdown().html(
							$.language( 'COM_EASYSOCIAL_FRIENDS_REQUEST_SENT_NOTICE' )
						);
					},

					/**
					 * Triggered when the add friend button is clicked
					 */
					"{addFriendButton} click" : function( el )
					{
						var id = self.element.data('friend-uid');

						$( el ).addClass( 'btn-loading' );

						// Implement controller on add friend.
						EasySocial.ajax( 'site/controllers/friends/request' ,
						{
							"viewCallback"	: "usersRequest",
							"id"	: id
						})
						.done( function( button )
						{
							// After the request is complete, set the correct friend id.
							// self.options.friendid 	= friendId;

							$( el ).replaceWith( button );

							self.dropdown().remove();

							// Remove the loading state from the button
							$( el ).removeClass( 'btn-loading' );

						})
						.fail(function( message )
						{
							self.dropdown().html( message );
						});

					}

				}
			});

		module.resolve();
	});

});

EasySocial.module( 'site/search/list' , function($){

	var module	= this;

	EasySocial.require()
	.view( 'site/loading/small' )
	.script('site/search/item')
	.language( 'COM_EASYSOCIAL_SEARCH_LOAD_MORE_ITEMS' )
	.done(function($){


		// TODO: Move this away from here
		// $.fn.visible = function(partial){

		// 	var $t	= $(this),
		// 		$w	= $(window),
		// 	viewTop	= $w.scrollTop(),
		// 	viewBottom	= viewTop + $w.height(),
		// 	_top		= $t.offset().top,
		// 	_bottom		= _top + $t.height(),
		// 	compareTop	= partial === true ? _bottom : _top,
		// 	compareBottom	= partial === true ? _top : _bottom;

		// 	return ((compareBottom <= viewBottom) && (compareTop >= viewTop));
	 //    };


		EasySocial.Controller(
			'Search.List',
			{
				defaultOptions:
				{
					// Elements
					"{item}"	: "[data-search-item]",


					"{pagination}"  : "[data-search-pagination]",
					"{loadmorebutton}" : "[data-search-loadmore-button]",

					// loading gif
					view :
					{
						loadingContent 	: "site/loading/small"
					}

				}
			},
			function( self ){
				return {

					init : function()
					{
						self.item().implement( EasySocial.Controller.Search.Item );

						self.on("scroll.search", window, $._.debounce(function(){

							if (self.loading) return;

							if (self.pagination().visible()) {

								self.loadMore();
							}

						}, 250));
					},

					"{loadmorebutton} click": function(){
						self.loadMore();
					},


					loadMore: function() {

						var query 		= $("[data-search-query]").val();
						var type 		= $("[data-sidebar-menu].active").data( 'type' );
						var next_limit 	= self.pagination().data('last-limit');
						var last_type 	= self.pagination().data('last-type');

						if( next_limit == '-1')
						{
							self.loadmorebutton.hide();
							return;
						}

						self.loading = true;

						EasySocial.ajax( 'site/controllers/search/getItems' ,
						{
							"next_limit" : next_limit,
							"last_type" : last_type,
							"type" : type,
							"q" : query,
							"loadmore" : '1'
						},
						{
							beforeSend: function()
							{
								self.pagination().html( self.view.loadingContent() );
							}
						})
						.done(function( contents, next_type, next_limit )
						{
							// update next last-update and last-type
							self.pagination().data('last-limit', next_limit );
							self.pagination().data('last-type', next_type );


							// append stream into list.
							self.pagination().before( contents );

							//re-implement controller on new items
							self.item().implement( EasySocial.Controller.Search.Item );

							if ( next_limit == '-1') {
								self.pagination().html('');
							} else {
								//append the anchor link.
								link = '<a class="btn btn-es-primary btn-stream-updates" href="javascript:void(0);" data-search-loadmore-button><i class="ies-refresh"></i>	' + $.language( 'COM_EASYSOCIAL_SEARCH_LOAD_MORE_ITEMS' ) + '</a>';
								self.pagination().html( link );
							}


						})
						.fail( function( messageObj ){

							return messageObj;
						})
						.always(function(){

							self.loading = false;
							//self.pagination().html('');
						});
					}




				}
			});

		module.resolve();
	});

});

EasySocial.module( 'site/search/search' , function($){

	var module	= this;

	EasySocial.require()
	.library( 'history' )
	.script( 'site/search/sidebar', 'site/profile/friends' )
	.view( 'site/loading/small' )
	.done(function($){

		EasySocial.Controller(
		'Search',
		{
			defaultOptions:
			{
				// Properties
				items		: null,

				// Elements
				"{container}"	: "[data-search]",

				"{contentTitle}": "[data-search-content-title]",
				"{content}"		: "[data-search-content]",
				"{sidebar}"		: "[data-search-sidebar]",


				"{sidebarItem}"	: "[data-sidebar-item]",


				view :
				{
					loadingContent 	: "site/loading/small"
				}
			}
		},
		function( self ){
			return {

				init : function()
				{
					// Implement sidebar controller.
					self.sidebar().implement( EasySocial.Controller.Search.Sidebar ,
					{
						"{parent}"	: self
					});

					self.sidebarItem().implement( EasySocial.Controller.Search.Sidebar.Item ,
					{
						"{parent}"	: self
					});
				},


				/**
				 * Add a loading icon on the content layer.
				 */
				updatingContents: function()
				{
					self.content().html( self.view.loadingContent() );
				},

				updateContent: function( content )
				{
					self.content().html( content );
				}

			}
		});

		module.resolve();
	});

});

EasySocial.module( 'site/search/sidebar' , function($){

	var module	= this;

	EasySocial.require()
	.done(function($){

		EasySocial.Controller(
			'Search.Sidebar',
			{
				defaultOptions:
				{
					"{menuItem}"	: "[data-sidebar-menu]"
				}
			},
			function( self ){
				return {

					init: function()
					{
					},

					"{menuItem} click" : function( el , event )
					{
						// Remove all active class.
						self.menuItem().removeClass( 'active' );

						// Add active class on this item.
						$( el ).addClass( 'active' );
					}
				}
			});


		EasySocial.Controller(
			'Search.Sidebar.Item',
			{
				defaultOptions:
				{
				}
			},
			function( self ){
				return {

					init: function()
					{
					},

					"{self} click" : function( el , event )
					{

						var type 	= self.element.data( 'type' ),
							url 	= self.element.data( 'url' );

							
						var query = $("[data-search-query]").val();

		
						// If this is an embedded layout, we need to play around with the push state.
						History.pushState( {state:1} , '' , url );

						self.parent.updatingContents();

						// console.log( query );
						//return;

						//ajax call here.
						EasySocial.ajax( 'site/controllers/search/getItems',
						{
							"type"		: type,
							"q" 		: query
						})
						.done(function( html )
						{
							self.parent.updateContent( html );	
						})
						.fail(function( message ){
							console.log( message );
						});

						self.parent.updateContent();
					}
				}
			});		

		module.resolve();
	});

});
EasySocial.module( 'site/search/toolbar' , function($){

	var module	= this;

	EasySocial.require()
	.library( 'history' )
	.view( 'site/loading/small' )
	.done(function($){

		EasySocial.Controller(
		'Search.Toolbar',
		{
			defaultOptions:
			{
				// Properties
				items		: null,

				// Elements
				"{searchInput}"		: "[data-nav-search-input]",
				"{seachDropdown}"	: "[data-nav-search-dropdown]",
				"{searchResult}"	: "li.navSearchItem",

				view :
				{
					loadingContent 	: "site/loading/small"
				}
			}
		},
		function( self ){
			return {

				init : function() {

					$(document).on("click.toolbar.search", function(event){

						var target = event.target;

						if (target==self.element[0] ||
							$(target).parents(self.element.data("directSelector")).length > 0) {
							return;
						}

						self.seachDropdown().hide();
					});
				},

				"{self} destroy": function() {

					$(document).off("click.toolbar.search");
				},

				"{searchInput} keyup": $._.debounce( function(el, event) {
				    // so that after 250ms from last keyup only will execute function here

				 	if( self.loading )
				 		return;

					var search = el.val();

					if( search.length >= 3)
					{
						self.loading = true;

						//ajax call here.
						EasySocial.ajax( 'site/controllers/search/getItems',
						{
							"q" 		: search,
							"mini"		: "1"
						},
						{
							beforeSend: function()
							{
								self.seachDropdown().show();	
								self.seachDropdown().html( self.view.loadingContent() );
							}	
						})
						.done(function( content )
						{
							self.seachDropdown().html( content );	
						})
						.fail(function( message ){
							console.log( message );
						})
						.always(function(){

							self.loading = false;
						});						

					}				 
				}, 250),

				"{searchInput} focus" : function() {

					cnt = self.searchResult().length;
					if( cnt > 0)
					{
						self.seachDropdown().show();
					}
				}				
							
			}
		});

		module.resolve();
	});

});
EasySocial.module( 'site/stream/item' , function(){

	var module	= this;

	EasySocial.require()
	.library( 'dialog' )
	.done(function($){

		EasySocial.Controller(
		'Stream.Item',
		{
			defaultOptions:
			{
				// Properties
				id 			: "",
				context 	: "",

				// Elements
				"{deleteFeed}"	: "[data-stream-delete]",
				"{hideLink}"	: "[data-stream-hide]",
				"{unHideLink}"	: "[data-stream-show]",

				"{hideAppLink}"	: "[data-stream-hide-app]",
				"{unHideAppLink}"	: "[data-stream-show-app]",

				"{hideNotice}"	: "[data-stream-hide-notice]",

				"{actions}"		: "[data-streamItem-actions]",
				"{contents}"	: "[data-streamItem-contents]",

				"{streamData}"	: "[data-stream-item]",

				"{likes}"			: "[data-likes-action]",
				"{counterBar}"		: "[data-stream-counter]",
				"{likeContent}" 	: "[data-likes-content]",
				"{repostContent}" 	: "[data-repost-content]",

				"{share}"			: "[data-repost-action]",

				// for stream comment
				"{streamCommentLink}" 	: "[data-stream-action-comments]",
				"{streamCommentBlock}" 	: "[data-comments]"
			}
		},
		function( self )
		{
			return {

				init: function()
				{

					// Set the stream's unique id.
					self.options.id 		= self.element.data( 'id' );
					self.options.context 	= self.element.data( 'context' );
					self.options.ishidden 	= self.element.data( 'ishidden' );

					// Render core actions
					// self.initActions();

				},

				plugins: {},


				"{likes} onLiked": function(el, event, data) {

					//need to make the data-stream-counter visible
					self.counterBar().removeClass( 'hide' );

				},

				"{likes} onUnliked": function(el, event, data) {

					var isLikeHide 		= self.likeContent().hasClass('hide');
					var isRepostHide 	= self.repostContent().hasClass('hide');

					if( isLikeHide && isRepostHide )
					{
						self.counterBar().addClass( 'hide' );
					}
				},

				"{share} create": function(el, event, itemHTML) {

					//need to make the data-stream-counter visible
					self.counterBar().removeClass( 'hide' );

				},


				"{streamCommentLink} click" : function()
				{
					self.streamCommentBlock().toggle();
				},

				/**
				 * Executes when a stream action is clicked.
				 */
				"{actions} click" : function( el , event )
				{
					// Remove active class on all action links
					self.actions().removeClass( 'active' );

					// Add active class on itself.
					$( el ).addClass( 'active' );
				},

				/**
				 * Delete a stream item
				 */

				 "{deleteFeed} click" : function()
				 {
					var uid = self.options.id

					EasySocial.dialog({
						content		: EasySocial.ajax( 'site/views/stream/confirmDelete' ),
						bindings	:
						{
							"{deleteButton} click" : function()
							{
								EasySocial.ajax( 'site/controllers/stream/delete',
								{
									"id"		: uid,
								})
								.done(function( html )
								{

									EasySocial.dialog({
										content: html
									});

									self.element.fadeOut();

									// close dialog box.
									//EasySocial.dialog().close();
								})
								.fail(function( message ){

									EasySocial.dialog({
										content: message
									});


								});

							}
						}
					});

				 },


				/**
				 * Hide's a stream item.
				 */
				"{hideLink} click" : function()
				{
					// Add hide class
					self.streamData().addClass( 'es-feed-loading' );

					EasySocial.ajax( 'site/controllers/stream/hide',
					{
						"id"		: self.options.id
					})
					.done(function( html )
					{
						self.streamData().removeClass( 'es-feed-loading' );

						self.streamData().hide();
						self.element.append( html );
					})
					.fail(function( message ){

					});
				},

				/**
				 * Hide's a stream item.
				 */
				"{hideAppLink} click" : function()
				{
					// self.actions().trigger( "onHideStream" , self.options.id );
					EasySocial.ajax( 'site/controllers/stream/hideapp',
					{
						"context"		: self.options.context
					})
					.done(function( html )
					{
						// self.streamData().hide();
						// self.element.append( self.view.hiddenItem() );

						// hide itself.
						self.streamData().hide();

						// hide all feeds that belong to this context.
						$( '.stream-context-' + self.options.context ).addClass('hide-stream');

						self.element.append( html );

					})
					.fail(function( message ){
						console.log( message );
					});
				},

				/**
				 * unHide's a stream item.
				 */
				"{unHideLink} click" : function()
				{

					EasySocial.ajax( 'site/controllers/stream/unhide',
					{
						"id"		: self.options.id
					})
					.done(function()
					{
						self.hideNotice().remove();
						self.streamData().show();

					})
					.fail(function( message ){
						console.log( message );
					});
				},

				/**
				 * unHide's a stream item.
				 */
				"{unHideAppLink} click" : function()
				{

					EasySocial.ajax( 'site/controllers/stream/unhideapp',
					{
						"context"		: self.options.context
					})
					.done(function()
					{
						self.hideNotice().remove();

						//show itself.
						self.streamData().show();

						// show all the items with same context
						$( '.stream-context-' + self.options.context ).removeClass('hide-stream');

					})
					.fail(function( message ){
						console.log( message );
					});
				}

			}
		});

		module.resolve();
	});
});

EasySocial.module( 'site/stream/stream' , function(){

	var module	= this;

	EasySocial.require()
	.script( 'site/stream/item' )
	.view( 'site/loading/small' )
	.language( 'COM_EASYSOCIAL_STREAM_LOAD_PREVIOUS_STREAM_ITEMS' )
	.done(function($){

		EasySocial.Controller(
		'Stream',
		{
			defaultOptions:
			{
				// Check every 30 seconds by default.
				interval	: 30,

				// Properties
				checknew	: null,
				source      : null,
				sourceId    : null,
				autoload	: true,

				// Elements
				"{story}"       : "[data-story]",
				"{share}"		: "[data-repost-action]",
				"{list}"	 	: "[data-stream-list]",
				"{newNotiBar}"	: "[data-stream-notification-bar]",
				"{newNotiButton}" : "[data-stream-notification-button]",

				"{item}"	 		: "[data-streamItem]",
				"{pagination}"   	: "[data-stream-pagination]",
				"{paginationGuest}" : "[data-stream-pagination-guest]",

				// loading gif
				view :
				{
					loadingContent 	: "site/loading/small"
				}
			}
		},
		function( self ){
			return {

				init : function()
				{

					// Implement stream item controller.
					self.item()
						.addController(EasySocial.Controller.Stream.Item);

					// do not run updates checking when viewing single stream page.
					if( self.options.source != 'stream' && self.options.source != 'unity' )
					{
						// run the checking on new updates
						if( self.options.checknew == true )
						{
							self.startMonitoring();
						}
					}

					if( self.options.autoload == true )
					{

						self.on("scroll.stream", window, $._.debounce(function(){

							if (self.loading) return;

							if( self.options.source == 'unity' )
							{
								if (self.paginationGuest().visible()) {
									self.loadMoreGuest();
								}
							}
							else
							{
								if (self.pagination().visible()) {

									self.loadMore();
								}
							}

						}, 250));

					}

					self.setLayout();
				},

				setLayout: function() {

					self.element.find('[data-es-cover]').each(function(){
						var cover = $(this);
						cover.height(cover.width() / 3);
					});
				},

				"{window} resize": $.debounce(function() {

					self.setLayout();

				}, 500),

				"{story} create": function(el, event, itemHTML, ids ) {

					$.buildHTML(itemHTML)
						.prependTo(self.list())
						.addController("EasySocial.Controller.Stream.Item");

					self.list().children( "li.empty" ).remove();

					// update the current date so that the next new stream notification will not include this item.
					self.updateCurrentDate();

					if( ids != '' )
					{
						self.updateExcludeIds( ids );
					}
				},

				"{share} create": function(el, event, itemHTML) {

					$.buildHTML(itemHTML)
						.prependTo(self.list())
						.addController("EasySocial.Controller.Stream.Item");

					self.list().children( "li.empty" ).remove();

					// update the current date so that the next new stream notification will not include this item.
					self.updateCurrentDate();
				},

				updateCurrentDate: function()
				{

					EasySocial.ajax( 'site/controllers/stream/getCurrentDate' ,
					{
					})
					.done(function( currentdate )
					{
						// update next start date
						self.element.data('currentdate', currentdate );

					})
					.fail( function( messageObj ){

					});

				},

				updateExcludeIds: function( id )
				{
					ids = self.element.data('excludeids' );
					newIds = '';

					if( ids != '' && ids != undefined )
					{
						newIds = ids + ',' + id;
					}
					else
					{
						newIds = id;
					}

					self.element.data('excludeids', newIds );
				},

				clearExcludeIds: function()
				{
					self.element.data('excludeids', '' );
				},

				/**
				 * Start running checks.
				 */
				startMonitoring: function()
				{
					if (self._destroyed) return self.stopMonitoring();

					var interval 	= self.options.interval * 1000;

					// Debug
					if( EasySocial.debug )
					{
						// console.info( 'Start monitoring new stream requests at interval of ' + self.options.interval + ' seconds.' );
					}

					self.options.state	= setTimeout( self.check , interval );
					// self.check();
				},

				/**
				 * Stop running any checks.
				 */
				stopMonitoring: function()
				{
					// Debug
					if( EasySocial.debug )
					{
						// console.info( 'Stop monitoring new stream requests.' );
					}

					clearTimeout( self.options.state );
				},

				"{self} destroyed": function() {

					self.stopMonitoring();
				},

				/**
				 * Check for new updates
				 */
				check: function(){

					// Stop monitoring so that there wont be double calls at once.
					self.stopMonitoring();

					var interval 	= self.options.interval * 1000;

					var type 		= $("[data-dashboardsidebar-menu].active").data( 'type' );
					var id 			= $("[data-dashboardsidebar-menu].active").data( 'id' );
					var currentdate = self.element.data('currentdate');

					var excludeIds  = self.element.data('excludeids');

					var pageNottiContent = $.trim( self.newNotiBar().html() );

					// debug code. do not remove!
					// console.log( 'currentdate: ' + currentdate, excludeIds );


					if( type == undefined && id == undefined )
					{
						if( self.options.source == 'profile' )
						{
							type = 'me';
							id 	 = self.options.sourceId;
						}
					}

					// console.log( type );
					// console.log( id );
					// console.log( currentdate );


					EasySocial.ajax( 'site/controllers/stream/checkUpdates' ,
					{
						"type"		  : type,
						"id"		  : id,
						"currentdate" : currentdate,
						"exclude" 	  : excludeIds,
						"source"	  : self.options.source,
						"view"	  	  : self.options.source
					},
					{
						type : "jsonp"
					})
					.done( function( data, contents, nextupdate )
					{
						if (self._destroyed) return self.stopMonitoring();

						// update current date
						// self.element.data('currentdate', nextupdate );


						//clear all the notice class
						// $( '[data-dashboard-feeds]' ).find('li')
						// 	.removeClass( 'has-notice' );


						if( data.length > 0 )
						{

							for( var i = 0 ; i < data.length; i++ )
							{
								item = data[ i ];

								if( item.cnt > 0 )
								{
									var key = '[data-stream-counter-' + item.type + ']';

									// console.log( key );

									$( key ).html( item.cnt );
									$( key ).parents('li').addClass('has-notice');
								}

								//console.log( item.cnt );
							}

							contents = $.trim( contents );

							if( contents.length > 0 )
							{
								// append notification into list.
								self.newNotiBar().html( contents );
							}

						}

						// Continue monitoring.
						self.startMonitoring();
					});

				},

				"{newNotiButton} click" : function(el, event) {

					var type 		= $(el).data( 'type' );
					var id 			= $(el).data( 'uid' );
					var currentdate = $(el).data( 'since' );

					EasySocial.ajax( 'site/controllers/stream/getUpdates' ,
					{
						"type"		  : type,
						"id"		  : id,
						"currentdate" : currentdate,
						"source"	  : self.options.source,
						"view"	  	  : self.options.source
					})
					.done( function( contents, nextupdate )
					{
						// clear the stream counter on the currect active filter bar.
						var key = '[data-stream-counter-';

						if( type == 'list' )
						{
							key = key + type + '-' + id;
						}
						else
						{
							key = key + type;
						}

						key = key + ']';

						$( key ).parents('li').removeClass('has-notice');


						//clear the new feeds notification.
						self.newNotiBar().html('');

						// append stream into list.
						$.buildHTML(contents)
						 	.prependTo( self.list() )
						 	.addController("EasySocial.Controller.Stream.Item");

						 // lets clear the exclude ids
						 self.clearExcludeIds();

						 // update the next update date
						 self.element.data('currentdate', nextupdate );

					});

				},


				"{paginationGuest} click" : function() {

					self.loadMoreGuest();
				},

				loadMoreGuest: function() {


					var pagination = self.paginationGuest(),
						startlimit = pagination.data("nextlimit");

					var view = self.options.source;

					if (!startlimit) return;


					self.loading = true;

					pagination.html( self.view.loadingContent({content: ""}) );

					EasySocial.ajax(
						"site/controllers/stream/loadmoreGuest",
						{
							startlimit: startlimit,
							view: view
						})
						.done(function(contents, nextlimit ) {

							// Update start & end date
							pagination.data({
								nextlimit: nextlimit
							});

							var contents = $.buildHTML(contents);

								contents
									.insertBefore(pagination)
									.filter(self.item.selector)
									.addController("EasySocial.Controller.Stream.Item");

							//if (self.options.autoload || nextlimit=="") {
							if ( nextlimit=="" ) {
								pagination.html('');
							} else {
								//append the anchor link.
								link = '<a class="btn btn-es-primary btn-stream-updates" href="javascript:void(0);"><i class="ies-refresh"></i> ' + $.language('COM_EASYSOCIAL_STREAM_LOAD_PREVIOUS_STREAM_ITEMS') + '</a>';
								pagination.html( link );
							}
						})
						.fail( function( messageObj ){

							return messageObj;
						})
						.always(function(){

							self.loading = false;
						});
				},


				"{pagination} click" : function() {
					self.loadMore();
				},

				loadMore: function() {

					var currentSidebarMenu = $("[data-dashboardsidebar-menu].active"),
						type = currentSidebarMenu.data('type'),
						id   = currentSidebarMenu.data('id');

					var pagination = self.pagination(),
						startdate = pagination.data("startdate"),
						enddate   = pagination.data("enddate");

					var view = self.options.source;

					if (!startdate) return;

					if( type == undefined && id == undefined )
					{
						if( self.options.source == 'profile' )
						{
							type = 'me';
							id 	 = self.options.sourceId;
						}
					}

					self.loading = true;

					pagination.html( self.view.loadingContent({content: ""}) );

					EasySocial.ajax(
						"site/controllers/stream/loadmore",
						{
							id: id,
							type: type,
							startdate: startdate,
							enddate: enddate,
							view: view
						})
						.done(function(contents, startdate, enddate) {

							// Update start & end date
							pagination.data({
								startdate: startdate,
								enddate: enddate
							});

							var contents = $.buildHTML(contents);

								contents
									.insertBefore(pagination)
									.filter(self.item.selector)
									.addController("EasySocial.Controller.Stream.Item");

							self.setLayout();

							//if (self.options.autoload || startdate=="") {
							if ( startdate=="" ) {
								pagination.html('');
							} else {
								//append the anchor link.
								link = '<a class="btn btn-es-primary btn-stream-updates" href="javascript:void(0);"><i class="ies-refresh"></i> ' + $.language('COM_EASYSOCIAL_STREAM_LOAD_PREVIOUS_STREAM_ITEMS') + '</a>';
								pagination.html( link );
							}
						})
						.fail( function( messageObj ){

							return messageObj;
						})
						.always(function(){

							self.loading = false;
						});
				}
			}
		});

		module.resolve();
	});
});

EasySocial.module( 'site/subscriptions/follow' , function(){

	var module	= this;

	EasySocial.require()
	.language(
		'COM_EASYSOCIAL_SUBSCRIPTION_INFO')
	.done(function($){

		EasySocial.Controller(
		'Follow',
		{
			defaultOptions:
			{

			}
		},
		function( self )
		{
			return {

				init: function()
				{
				},

				"{self} click" : function()
				{
					EasySocial.ajax( 'site/controllers/subscriptions/toggle' ,
					{
						"uid"	: self.element.data('id'),
						"type"	: self.element.data('type'),
						"notify": "1"
					})
					.done(function( content , label )
					{
						// update the label
						self.element.text( label );

						EasySocial.dialog({
							title: $.language('COM_EASYSOCIAL_SUBSCRIPTION_INFO'),
							content: content
						});

					})
					.fail( function( message ){
						self.setMessage( message, 'error' );
					});

				}
			}
		});

		module.resolve();
	});
});

EasySocial.module( 'site/toolbar/conversations' , function($){

	var module 				= this;

	EasySocial.require()
	.view( 'site/loading/small' )
	.library( 'tinyscrollbar' )
	.done(function($){

		EasySocial.Controller(
			'Notifications.Conversations',
			{
				defaultOptions:
				{

					// Check every 10 seconds by default.
					interval	: 30,

					// Views
					view	:
					{
						loadingIcon		: 'site/loading/small'
					},

					// Elements within this container.
					"{counter}"			: "[data-notificationConversation-counter]",
					"{dropdown}"		: "[data-notificationConversation-dropdown]",
					"{loader}"			: "[data-notificationConversation-loader]",

					// Notification items
					"{items}"			: "[data-notificationConversation-items]",

					"{scrollBar}"		: "[data-notificationConversation-scrollbar]"
				}
			},
			function(self){ return{

				init: function()
				{
					// Start the automatic checking of new notifications.
					self.startMonitoring();
				},

				/**
				 * Start running checks.
				 */
				startMonitoring: function()
				{
					var interval 	= self.options.interval * 1000;

					// Debug
					if( EasySocial.debug )
					{
						// console.info( 'Start monitoring conversation notifications at interval of ' + self.options.interval + ' seconds.' );
					}

					self.options.state	= setTimeout( self.check , interval );
				},

				/**
				 * Stop running any checks.
				 */
				stopMonitoring: function()
				{
					// Debug
					if( EasySocial.debug )
					{
						// console.info( 'Stop monitoring conversation notifications.' );
					}

					clearTimeout( self.options.state );
				},


				"{self} showDropdown" : function()
				{
					// Perform an ajax call to retrieve notification items.
					EasySocial.ajax( 'site/controllers/notifications/getConversationItems',
					{
						usemax: 1,
						// filter: "unread",

						beforeSend: function()
						{
							// Show loader
							self.loader().show();

							// Hide items first.
							self.items().hide();
						}
					})
					.done(function( content ){

						// Hide loader.
						self.loader().hide();

						self.items().html( content );

						// Show items.
						self.items().show();

						// Apply tinyscrollbar on the dropdown.
						self.scrollBar().tinyscrollbar();
					});
				},


				/**
				 * Check for new updates
				 */
				check: function()
				{

					// Stop monitoring so that there wont be double calls at once.
					self.stopMonitoring();

					var interval 	= self.options.interval * 1000;

					// Needs to run in a loop since we need to keep checking for new notification items.
					setTimeout( function(){

						EasySocial.ajax( 'site/controllers/notifications/getConversationCounter' , {},
						{
							type : "jsonp"
						})
						.done( function( total ){

							if( total > 0 )
							{
								// Add new notice on the toolbar
								self.element.addClass( 'has-notice' );

								// Update the counter's count.
								self.counter().html( total );
							}
							else
							{
								self.element.removeClass( 'has-notice' );
							}

							// Continue monitoring.
							self.startMonitoring();
						});

					}, interval );

				},

				"{dropdown} click" : function( el , event )
				{
					// Disallow clicking of events to trigger parent items.
					event.stopPropagation();
				}
			}}
		);

		module.resolve();
	});

});

EasySocial.module( 'site/toolbar/friends' , function($){

	var module 				= this;

	EasySocial.require()
	.view( 'site/loading/small' )
	.library( 'tinyscrollbar' )
	.language( 'COM_EASYSOCIAL_FRIENDS_REQUEST_REJECTED' )
	.done(function($){

		EasySocial.Controller(
			'Notifications.Friends',
			{
				defaultOptions:
				{

					// Check every 10 seconds by default.
					interval: 3,

					// The return url when the friend approval is approved.
					returnURL 	: "",

					// Views
					view	:
					{
						loadingIcon		: 'site/loading/small'
					},

					// Elements within this container.
					"{counter}"		: "[data-notificationFriends-counter]",
					"{dropdown}"	: "[data-notificationFriends-dropdown]",
					"{loader}"		: "[data-notificationFriends-loader]",
					"{loadRequestsButton}" : ".loadRequestsButton",

					// Friend request items.
					"{itemList}"	: "[data-notificationFriends-list]",
					"{items}"		: ".requestItem",

					"{scrollBar}"	: "[data-notificationFriends-scrollbar]"
				}
			},
			function(self){ return{

				init: function()
				{
					// Start the automatic checking of new notifications.
					self.startMonitoring();
				},

				/**
				 * Start running checks.
				 */
				startMonitoring: function()
				{
					var interval 	= self.options.interval * 1000;

					// Debug
					if( EasySocial.debug )
					{
						// console.info( 'Start monitoring friend requests at interval of ' + self.options.interval + ' seconds.' );
					}

					self.options.state	= setTimeout( self.check , interval );
				},

				/**
				 * Stop running any checks.
				 */
				stopMonitoring: function()
				{
					// Debug
					if( EasySocial.debug )
					{
						// console.info( 'Stop monitoring friend requests.' );
					}

					clearTimeout( self.options.state );
				},


				"{self} showDropdown" : function()
				{
					// Clear off all items.
					self.items().remove();

					// Perform an ajax call to retrieve notification items.
					EasySocial.ajax( 'site/controllers/notifications/friendsRequests' ,
					{
						beforeSend: function()
						{
							// Hide results initially to prevent jagged pop ups
							self.itemList().hide();

							self.loader().show();
						}
					})
					.done(function( content ){

						// Hide loader
						self.loader().hide();

						// Append content to the results.
						self.itemList().html( content );

						// Implement controller now.
						self.itemList().find( '[data-notification-friend-item]' ).implement( EasySocial.Controller.Notifications.Friends.Item );

						// Show the results.
						self.itemList().show();

						// Apply tinyscrollbar on the dropdown.
						self.scrollBar().tinyscrollbar();
					});
				},


				/**
				 * Check for new updates
				 */
				check: function(){

					// Stop monitoring so that there wont be double calls at once.
					self.stopMonitoring();

					var interval 	= self.options.interval * 1000;

					// Needs to run in a loop since we need to keep checking for new notification items.
					setTimeout( function(){

						EasySocial.ajax( 'site/controllers/notifications/friendsCounter' ,
						{},
						{
							type : "jsonp"
						})
						.done( function( total )
						{

							if( total > 0 )
							{
								// Update element
								self.element.addClass( 'has-notice' );

								// Update the counter's count.
								self.counter().html( total );
							}
							else
							{
								self.element.removeClass( 'has-notice' );
							}

							// Continue monitoring.
							self.startMonitoring();
						});

					}, interval );

				},

				"{dropdown} click" : function( el , event )
				{
					// Disallow clicking of events to trigger parent items.
					event.stopPropagation();
				}
			}}
		);

		EasySocial.Controller(
			'Notifications.Friends.Item',
			{
				defaultOptions:
				{
					"{actionsWrapper}" 	: "[data-friend-item-actions]",
					"{acceptFriend}"	: "[data-friend-item-accept]",
					"{rejectFriend}"	: "[data-friend-item-reject]",
					"{actions}"			: "[data-friend-item-action]",
					"{title}"			: "[data-friend-item-title]",
					"{mutual}" 			: "[data-friend-item-mutual]",

					// Views
					view	:
					{
						loader 		: 'site/loading/small'
					},
				}
			},
			function( self ){
				return {

					init: function()
					{

					},

					"{acceptFriend} click" : function( el , event )
					{
						// Stop other events from being triggered.
						event.stopPropagation();

						self.actionsWrapper().addClass( 'friend-adding' );

						// Send an ajax request to approve the friend.
						EasySocial.ajax( 'site/controllers/friends/approve' ,
						{
							viewCallback	: "notificationsApprove",
							id				: $( el ).data( 'id' )
						})
						.done(function( title , mutualFriendsContent )
						{
							// Update the current state
							self.actionsWrapper().removeClass( 'friend-adding' ).addClass( 'added-friends' );

							self.title().html( title );

							self.mutual().html( mutualFriendsContent );
						})
						.fail( function( message )
						{
							// Append error message.
							self.element.html( message.message );
						});

					},


					"{rejectFriend} click" : function( el , event )
					{
						event.stopPropagation();

						EasySocial.ajax( 'site/controllers/friends/reject' ,
						{
							"id"	: $( el ).data( 'id' )
						})
						.done( function( button )
						{
							self.actionsWrapper().html( $.language( 'COM_EASYSOCIAL_FRIENDS_REQUEST_REJECTED' ) );
						})
						.fail( function( message )
						{
							// Append error message.
							self.element.html( message.message );
						});

					}
				}
			}
		);

		module.resolve();
	});

});

EasySocial.module( 'site/toolbar/login' , function($){

	var module 				= this;

	EasySocial.require()
	.library( 'popbox' )
	.done(function($){

		EasySocial.Controller(
			'Toolbar.Login',
			{
				defaultOptions:
				{
					"{dropdown}"		: "[data-toolbar-login-dropdown]"
				}
			},
			function(self){ return{ 

				init: function()
				{
					var html = self.dropdown().html();

					// Remove the temporary dropdown.
					self.dropdown().remove();

					// Implement popbox when the profile button is initiated
					self.element.popbox(
					{
						content 	: html,
						id			: "es-wrap",
						type		: "toolbar",
						toggle 		: "click"
					})
					.attr("data-popbox", "");

				},

				"{self} popboxActivate" : function( el , event , popbox )
				{
					$( popbox.tooltip ).find( 'label' ).on( 'click' , function( event )
					{
						// Prevent propagation
						event.stopPropagation();
					});
					// $( popbox.tooltip ).implement( EasySocial.Controller.Toolbar.Login.User );
				}
			}}
		);

		module.resolve();
	});

});

EasySocial.module( 'site/toolbar/notifications' , function($){

	var module 				= this;

	EasySocial.require()
	.script( 
		'site/toolbar/friends', 
		'site/toolbar/story',
		'site/toolbar/system',
		'site/toolbar/profile',
		'site/toolbar/login',
		'site/toolbar/conversations'
	)
	.done(function($){

		EasySocial.Controller(
			'Notifications',
			{
				defaultOptions:
				{
					friendsInterval			: 30,
					systemInterval 			: 30,

					"{friendNotifications}"	: "[data-notifications-friends]",
					"{conversationNotifications}"	: "[data-notifications-conversations]",
					"{systemNotifications}"	: "[data-notifications-system]",
					"{profileItem}"			: "[data-toolbar-profile]",
					"{storyForm}"			: "[data-toolbar-story]",
					"{login}"				: "[data-toolbar-login]",
					"{item}"				: "[data-toolbar-item]"
				}
			},
			function(self){
				return { 

					init: function()
					{
						// Implement toolbar login controller
						self.login().addController( EasySocial.Controller.Toolbar.Login );

						// Initialize profile controller for toolbar.
						self.profileItem().addController( EasySocial.Controller.Toolbar.Profile , 
						{
							interval 	: self.options.systemInterval
						});

						// Initialize system notifications controller.
						self.systemNotifications().addController( EasySocial.Controller.Notifications.System , 
						{
							interval 	: self.options.systemInterval
						});

						// Initialize friends controller.
						self.friendNotifications().addController( EasySocial.Controller.Notifications.Friends ,
						{
							interval 	: self.options.friendsInterval
						});

						// Initialize conversations controller.
						self.conversationNotifications().addController( EasySocial.Controller.Notifications.Conversations ,
						{
							interval 	: self.options.friendsInterval
						});
						
						// Initialize story form controller.
						self.storyForm().addController( EasySocial.Controller.Notifications.Story );

						// Initialize responsive layout for the notification bar.
						self.setLayout();

						// Monitor clicks on the body. So that all dropdowns should be hidden whenever clicks are made on the body.
						$( 'body' ).on( 'click.out-of-dropdown' , function(){
							self.item().removeClass( 'open' );
						});
					},

					"{window} resize": $.debounce(function(){
						self.setLayout();
					}, 250),

					setLayout: function() {

						var elem = self.element,
							toolbarWidth = elem.outerWidth(true) - 80,
							allItemWidth = 0;

							// Calculate how much width toolbar items are taking
							self.item().each(function(){
								allItemWidth += $(this).outerWidth(true);
							});

						var exceeded = (allItemWidth > toolbarWidth);

						elem.toggleClass("narrow", exceeded).toggleClass("wide", !exceeded);
					}
				}
			});

		module.resolve();
	});

});

EasySocial.module( 'site/toolbar/story' , function($){

	var module 				= this;

	EasySocial.require()
	.done(function($){

		EasySocial.Controller(
			'Notifications.Story',
			{
				defaultOptions:
				{
					"{loadFormButton}"	: ".loadFormButton",
					"{dropdown}"		: ".dropdown-menu"
				}
			},
			function(self){ return{ 

				init: function()
				{
				},

				"{dropdown} click" : function( el , event )
				{
					// event.stopPropagation();
				},

				"{self} hideDropdown" : function()
				{
					// self.element.removeClass( 'open' );
				},

				"{dropdown} click" : function( el , event )
				{
					// Disallow clicking of events to trigger parent items.
					event.stopPropagation();
				}
			}}
		);

		module.resolve();
	});

});

EasySocial.module( 'site/toolbar/system' , function($){

	var module 				= this;

	EasySocial.require()
	.view( 'site/loading/small' )
	.library( 'tinyscrollbar' )
	.done(function($){

		EasySocial.Controller(
			'Notifications.System',
			{
				defaultOptions:
				{

					// Check every 10 seconds by default.
					interval	: 30,

					// Views
					view	:
					{
						loadingIcon		: 'site/loading/small'
					},

					// Elements within this container.
					"{counter}"			: "[data-notificationSystem-counter]",
					"{dropdown}"		: "[data-notificationSystem-dropdown]",
					"{loader}"			: "[data-notificationSystem-loader]",

					// Notification items
					"{items}"			: "[data-notificationSystem-items]",
					"{scrollBar}"		: "[data-notificationSystem-scrollbar]"
				}
			},
			function(self){ return{ 

				init: function()
				{
					// Start the automatic checking of new notifications.
					self.startMonitoring();
				},

				/**
				 * Start running checks.
				 */
				startMonitoring: function()
				{
					var interval 	= self.options.interval * 1000;

					// Debug
					if( EasySocial.debug )
					{
						// console.info( 'Start monitoring system notifications at interval of ' + self.options.interval + ' seconds.' );	
					}

					self.options.state	= setTimeout( self.check , interval );
				},

				/**
				 * Stop running any checks.
				 */
				stopMonitoring: function()
				{
					// Debug
					if( EasySocial.debug )
					{
						// console.info( 'Stop monitoring system notifications.' );	
					}

					clearTimeout( self.options.state );
				},


				"{self} showDropdown" : function()
				{
					// Perform an ajax call to retrieve notification items.
					EasySocial.ajax( 'site/controllers/notifications/getSystemItems',
					{
						beforeSend: function()
						{
							self.loader().show();

							self.items().hide();
						}
					})
					.done(function( content ){

						self.loader().hide();
						
						// Append the output to the items.
						self.items().html( content );

						self.items().show();

						// Apply tinyscrollbar on the dropdown.
						self.scrollBar().tinyscrollbar();
					});
				},


				/**
				 * Check for new updates
				 */
				check: function(){

					// Stop monitoring so that there wont be double calls at once.
					self.stopMonitoring();

					var interval 	= self.options.interval * 1000;

					// Needs to run in a loop since we need to keep checking for new notification items.
					setTimeout( function(){

						EasySocial.ajax( 'site/controllers/notifications/getSystemCounter' , {},
						{
							type : "jsonp"
						})
						.done( function( total ){

							if( total > 0 )
							{
								// Update toolbar item element
								self.element.addClass( 'has-notice' );
								
								// Update the counter's count.
								self.counter().html( total );
							}
							else
							{
								self.element.removeClass( 'has-notice' );
							}
							// Continue monitoring.
							self.startMonitoring();
						});

					}, interval );

				},

				"{dropdown} click" : function( el , event )
				{
					// Disallow clicking of events to trigger parent items.
					event.stopPropagation();
				}
			}}
		);

		module.resolve();
	});

});

EasySocial.module( 'site/toolbar/profile' , function($){

	var module 				= this;

	EasySocial.require()
	.library( 'popbox' )
	.done(function($){

		EasySocial.Controller(
			'Toolbar.Profile',
			{
				defaultOptions:
				{
					"{dropdown}"		: "[data-toolbar-profile-dropdown]"
				}
			},
			function(self){ return{ 

				init: function()
				{
					var html = self.dropdown().html();

					// Implement popbox when the profile button is initiated
					self.element.popbox(
					{
						content 	: html,
						id			: "es-wrap",
						type		: "toolbar",
						toggle 		: "click"
					})
					.attr("data-popbox", "");

				},

				"{self} popboxActivate" : function( el , event , popbox )
				{
					$( popbox.tooltip ).implement( EasySocial.Controller.Toolbar.Profile.Logout );
				}
			}}
		);

		EasySocial.Controller(
			'Toolbar.Profile.Logout',
			{
				defaultOptions: 
				{
					// Elements within this container.
					"{logoutForm}"		: "[data-toolbar-logout-form]",
					"{logoutButton}"	: "[data-toolbar-logout-button]"
				}
			},
			function(self)
			{
				return{ 
					/**
					 * Logs user out from the site.
					 */
					logout: function()
					{
						self.logoutForm().submit();
					},

					"{logoutButton} click" : function()
					{
						self.logout();
					}
				}
			});

		module.resolve();
	});

});

EasySocial.module( 'site/users/users' , function($){

	var module 				= this;

	EasySocial.require()
	.library( 'history' )
	.view(
		'site/loading/small'
	)
	.done(function($){

		EasySocial.Controller(
			'Users',
			{
				defaultOptions :
				{
					"{content}"	: "[data-users-content]",
					"{listing}"	: "[data-users-listing]",
					"{sort}"	: "[data-users-sort]",
					"{filter}"	: "[data-users-filter]",
					"{items}"	: "[data-users-item]",
					"{pagination}" : "[data-users-pagination]",

					view :
					{
						loading 	: 'site/loading/small'
					}
				}
			},
			function( self )
			{
				return {

					init : function()
					{
						// Implement user item controller
						self.initUserController();
					},

					initUserController : function()
					{
						self.items().implement( EasySocial.Controller.Users.Item ,
						{
							"{parent}"	: self
						});
					},

					"{filter} click" : function( el , event )
					{
						event.preventDefault();

						// Remove any active states for filters and sort items
						self.sort().removeClass( 'active' );
						self.filter().each(function(){
							$(this).parent().removeClass( 'active' );
						});

						// Add active class to the current filter item.
						$( el ).parent().addClass( 'active' );

						// Get the sort type.
						var filter 	= $( el ).data( 'filter' );

						self.options.filter 	= filter;
						$( el ).route();

						// Add loading state to the content.
						self.listing().html( self.view.loading() );

						// Set the first sort item as the active item
						self.sort( ':first' ).addClass( 'active' );

						// Perform the ajax call to retrieve the new users listing
						EasySocial.ajax( 'site/controllers/users/getUsers',
						{
							"filter" 			: filter,
							"showpagination"	: 1
						})
						.done(function( output )
						{
							self.content().html( output );

							// Re-apply controller
							self.initUserController();
						});
					},

					"{sort} click" : function( el , event )
					{
						event.preventDefault();

						// Get the sort type
						var type 	= $( el ).data( 'type' );

						$( el ).route();

						// Add the active state on the current element.
						self.sort().removeClass( 'active' );

						$( el ).addClass( 'active' );

						// Add loading state to the content.
						self.listing().html( self.view.loading() );

						// Remove pagination
						self.pagination().remove();
						
						EasySocial.ajax( 'site/controllers/users/getUsers' ,
						{
							"sort"				: type,
							"filter"			: self.options.filter,
							"isSort" 			: true,
							"showpagination" 	: 1
						})
						.done(function(contents)
						{


							self.listing().html( contents );

							// Re-apply controller
							self.initUserController();
						});

					}
				}
			});

		EasySocial.Controller(
		'Users.Item',
		{
			defaultOptions:
			{
				id 					: null,
				"{addFriend}"		: "[data-users-add-friend]",
				"{friendsButton}" 	: "[data-users-friends-button]",
				"{compose}"			: "[data-users-friends-compose]",
				"{unfriend}"		: "[data-users-friends-unfriend]"
			}
		},
		function( self )
		{
			return {

				init: function()
				{
					self.options.id 	= self.element.data( 'id' );
				},

				"{addFriend} click" : function( el , event )
				{
					// Add a loading state to the button
					$( el ).addClass( 'btn-loading' );

					// Append loading state on the button
					EasySocial.ajax( 'site/controllers/friends/request' ,
					{
						"viewCallback"	: "usersRequest",
						"id"		: self.options.id
					})
					.done(function( pendingButton )
					{
						// Replace the button
						$( el ).replaceWith( pendingButton );

						// Remove the loading state from the button
						$( el ).removeClass( 'btn-loading' );
					});
				}

			}
		});

		module.resolve();
	});


});

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

EasySocial.module("story/friends", function($){

	var module = this;

	// $.template("easysocial/story/linkItem", '<div class="es-story-linkitem" data-story-linkItem><h6><a href="[%= url %]">[%= info.title %]</a></h6><p>[%= info.description %][%= JSON.stringify(info) %]</p><a class="ies-cancel-2 remove-linkitem" data-story-removeLinkItem></a>');

	EasySocial.require()
		.library(
			// "mentions",
			"textboxlist"
		)
		.language(
			"COM_EASYSOCIAL_WITH_FRIENDS",
			"COM_EASYSOCIAL_AND_ONE_OTHER",
			"COM_EASYSOCIAL_AND_MANY_OTHERS"
		)
		.done(function(){

			EasySocial.Controller("Story.Friends",
				{
					defaultOptions: {

						"{friendList}": ".es-story-friends-textbox",

						showSummary: false,
						summarizeNamesAfter: 2, // people
						concatNamesWith: ', ', // character
					}
				},
				function(self) { return {

					init: function() {

						// I have access to:
						// self.panelButton()
						// self.panelContent()

						// Friend tagging
						self.friendList()
							.textboxlist({
								plugin: {
									autocomplete: {
										exclusive : true,
										cache	  : false,
										query     : self.search,
										filterItem: self.createMenuItem
									}
								}
							});

						// Friend mentioning
						// self.story.textField()
						// 	.mentionsInput({
						// 	    elastic: false,
						// 	    minChars: 1,
						// 		onDataRequest: self.mention
						// 	});
					},

					search: function(keyword) {

						var users = self.getTaggedUsers();

						return EasySocial.ajax(
								   "site/controllers/friends/suggest",
								   {
								   	   "search": keyword,
								   	   "exclude": users
								   });
					},

					getTaggedUsers: function()
					{
						var users = [];
						var items = $( "[data-textboxlist-item]" );
						if( items.length > 0 )
						{
							$.each( items, function( idx, element ) {
								users.push( $( element ).data('id') );
							});
						}

						return users;
					},

					//
					// Tagging
					//
					createMenuItem: function(item, keyword) {

						item.title = item.screenName;

						var avatar = $(new Image())
							.addClass("textboxlist-menu-avatar")
							.attr({
								src: item.avatar
							}).toHTML();

						item.html     = avatar + ' ' + item.title;
						item.menuHtml = avatar + ' ' + item.title;

						return item;
					},

					updatePanelCaption: function() {

						var options = self.options,
							friendList = self.friendList().controller("textboxlist"),
							addedItems = friendList.getAddedItems();

						var total = addedItems.length,
							limit = options.summarizeNamesAfter,
							sliceAt = Math.max(limit - 1, total - 1),
							balance = total - limit,
							caption = total;

						if (options.showFriendSummary) {
							caption =
								$.language(
									"COM_EASYSOCIAL_WITH_FRIENDS",
									$.map(addedItems.slice(0, sliceAt), function(item){
										return item.screenName;
									}).join(options.concatNamesWith)
								);

							if (balance == 1) {
								caption += $.language("COM_EASYSOCIAL_AND_ONE_OTHER");
							}

							if (balance > 1) {
								caption += $.language("COM_EASYSOCIAL_AND_MANY_OTHERS", balance);
							}
						}

						self.story.addPanelCaption("friends", caption);
					},

					"{friendList} addItem": function() {
						self.updatePanelCaption();
					},

					"{friendList} removeItem": function() {
						self.updatePanelCaption();
					},

					//
					// Mentions
					//
					mention: function (mode, query, callback) {

						self.search(query)
							.done(function(users){

								var friends = [];

								$.each(users, function(i, user){

									friends.push({
										id: user.id,
										name: user.screenName,
										avatar: user.avatar,
										type: 'contact'
									});
								});

								callback(friends);
							});
					},

					"{story} save": function(el, event, save) {

						var friendList = self.friendList().controller("textboxlist");

						var tags =
							friendList.getAddedItems().map(function(friend){
								return friend.id;
							});

						save.addData(self, {
							tags: tags
						});

						// self.story.textField()
						// 	.mentionsInput("val", function(markup){
						// 		save.data.friend_markup = markup;
						// 	})
						// 	.mentionsInput("getMentions", function(mentions){
						// 		save.data.friend_mentions = $.map(mentions, function(friend){
						// 			return friend.id;
						// 		});
						// 	});
					},

					"{story} clear": function() {

						var friendList = self.friendList().controller("textboxlist");

						friendList.clearItems();
					}
				}}
			);

			// Resolve module
			module.resolve();

		});

});

EasySocial.module("story/links", function($){

	var module = this;

	EasySocial.require()
		.view(
			"apps/user/links/story.attachment.item"
		)
		.done(function(){

			EasySocial.Controller("Story.Links",
				{
					defaultOptions: {

						view: {
							linkItem: "apps/user/links/story.attachment.item"
						},

						urlParser: /(^|\s)((https?:\/\/)?[\w-]+(\.[\w-]+)+\.?(:\d+)?(\/\S*)?)/gi,
						// /[-a-zA-Z0-9@:%_\+.~#?&//=]{2,256}\.[a-z]{2,4}\b(\/[-a-zA-Z0-9@:%_\+.~#?&//=]*)?/gi,
						
						// Attachment item
						"{linkForm}"        : "[data-story-link-form]",
						"{linkInput}"       : "[data-story-link-input]",

						"{linkContent}"     : "[data-story-link-content]",
						"{linkItem}"        : "[data-story-link-item]",
						"{linkTitle}"       : "[data-story-link-title]",
						"{linkDescription}" : "[data-story-link-description]",
						"{linkImages}"      : "[data-story-link-images]",
						"{linkImage}"       : "[data-story-link-image]",
						"{titleTextfield}"      : "[data-story-link-title-textfield]",
						"{descriptionTextfield}": "[data-story-link-description-textfield]",

						"{attachButton}"    : "[data-story-link-attach-button]",
						"{removeButton}"    : "[data-story-link-remove-button]",
						"{removeThumbnail}"	: "[data-story-link-remove-image]"
					}
				},
				function(self) { return {

					init: function() {
						// I have access to:
						// self.story
						// self.attachmentButton()
						// self.attachmentItem()
						// self.attachmentContent()
						// self.attachmentToolbar()
						// self.attachmentDragHandle()
						// self.attachmentRemoveButton()
					},

					activateAttachment: function() {

						if (self.doNotFocus) return;

						setTimeout(function(){
							self.linkInput().focus();
							self.doNotFocus = false;
						}, 500);
					},

					//
					// Link manipulation
					//
					links: {},

					currentLink: null,

					crawling: false,

					extractUrls: function(str) {

						var urlParser = self.options.urlParser,
							urls = str.match(urlParser);

						// Discard non http/https protocols
						if ($.isArray(urls)) {
							return $.map(urls, function(url, i){
								return $.trim(url);
							});
						} else {
							return [];
						}
					},

					getLink: function(urls) {

						// If a block of string was given,
						// extract urls from it.
						if ($.isString(urls)) {
							urls = self.extractUrls(urls);
						}

						// If there are no urls, stop.
						if (urls.length < 0) return;

						// Get only the first url
						var url = urls[0];

						// If this is a new url,
						// create a new link object for it.
						link = self.links[url] || self.createLink(url);

						// When the link is resolved,
						// add link to the attachment item.
						return link;
					},

					createLink: function(url) {

						// Create a new link object
						var link = self.links[url] = $.Deferred();

						// Add url property
						link.url = url;

						self.crawling = true;

						// Get link info from crawler
						EasySocial.ajax(
								"site/controllers/crawler/fetch",
								{
									urls: [url]
								}
							)
							.done(function(links){

								var info = links[url];

								if (!info) link.reject();

								// Add link info
								// Properties: charset, description, images, keywords, opengraph, title
								link.info = info;

								// Prefer opengraph over general meta
								var og = info.opengraph || {};

								link.data = {
									title :  og.title || info.title,
									desc  :  og.desc  || info.description,
									url   :  url,
									images: (og.image) ? [og.image] : info.images
								};

								// Create link item
								link.item =
									self.view.linkItem(link.data)
										.data("link", link)
								
								link.item
									.addController("EasySocial.Controller.Story.Links.Item");

								link.resolve(link);
							})
							.fail(function(){

								link.reject();
							})
							.always(function(){
								self.crawling = false;
							});

						return link;
					},

					addLink: function(link) {

						// Add link item to attachment item
						self.linkContent()
							.empty()
							.append(link.item);

						self.linkForm()
							.hide();

						self.currentLink = link;
					},

					removeLink: function() {

						self.linkItem()
							.detach();

						self.linkForm()
							.show();

						self.currentLink = null;
					},

					//
					// Link form
					//
					"{attachButton} click": function() {

						var linkInput = self.linkInput(),
							linkForm  = self.linkForm(),
							url       = $.trim(self.linkInput().val());

						// If there's no url, stop.
						if (url==="") return;
	
						// If there's no protocol, use "http".
						url = $.uri(url);
						if (!/http|https/.test(url.protocol())) {
							url.setProtocol("http");
						}
						url = url.toString();

						// Set fixed link back to input box
						self.linkInput().val(url);

						// Set link form as busy
						linkForm.addClass("busy");

						// Get link
						self.getLink(url)
							.done(function(link){
								self.addLink(link);
							})
							.always(function(){
								linkForm.removeClass("busy");
							});
					},

					"{removeButton} click": function(button) {

						self.currentLink.disabled = true;

						self.removeLink();
					},
					
					"{story.textField} keypress": $._.debounce(function(textField, event) {

						// Don't look for links if we've already added one
						if (self.currentLink || self.crawling) return;

						var content = textField.val(),
							url = self.extractUrls(content)[0];

						if (!url) return;

						var link = self.links[url];

						// Check if link has been crawled before
						if (link && link.disabled) return;

						// Set the url as the value
						self.linkInput().val(url);						

						// Do not focus when attachment is activated
						self.doNotFocus = true;					

						// Trigger links attachment
						self.attachmentButton().click();

						// Add link
						self.attachButton().click();

					}, 750),

					//
					// Saving
					//
					"{story} save": function(element, event, save){

						if (!self.currentLink) return;

						var data = {
							title      : self.titleTextfield().val(),
							description: self.descriptionTextfield().val(),
							url        : self.currentLink.url
						};

						if (!self.removeThumbnail().is(":checked")) {
							data.image = self.linkImage(".active").attr("src");
						}

						save.addData(self, data);
					},

					"{story} clear": function() {

						self.linkInput().val("");

						self.removeLink();
					}
				}}
			);

			EasySocial.Controller('Story.Links.Item',
			{
				defaultOptions: {

					"{previousImage}"	: "[data-story-link-image-prev]",
					"{nextImage}"		: "[data-story-link-image-next]",
					"{image}"			: "[data-story-link-image]",
					"{imagesWrapper}"	: "[data-story-link-images]",
					"{imageIndex}"		: "[data-story-link-image-index]",
					"{removeThumbnail}"	: "[data-story-link-remove-image]",

					"{title}"       : "[data-story-link-title]",
					"{description}" : "[data-story-link-description]",
					"{titleTextfield}"      : "[data-story-link-title-textfield]",
					"{descriptionTextfield}": "[data-story-link-description-textfield]"
				}
			},
			function(self) { return {

					init: function()
					{
					},

					"{removeThumbnail} click" : function()
					{
						var isChecked 	= self.removeThumbnail().is( ':checked' );

						if( isChecked )
						{
							self.imagesWrapper().hide();
						}
						else
						{
							self.imagesWrapper().show();
						}

						self.element.toggleClass("has-images", !isChecked);
					},

					"{previousImage} click" : function()
					{
						var prevImage 		= self.image('.active' ).prev(),
							currentImage 	= self.image( '.active' ),
							index 			= parseInt( self.imageIndex().html() ),
							nextIndex 		= index - 1;

						if( prevImage.length > 0 )
						{
							$( currentImage ).removeClass( 'active' );
							$( prevImage ).addClass( 'active' );


							self.imageIndex().html( nextIndex );
						}
						else
						{
							// Disable this button.
						}
					},

					"{nextImage} click" : function()
					{
						var nextImage 		= self.image('.active' ).next(),
							currentImage 	= self.image( '.active' ),
							index 			= parseInt( self.imageIndex().html() ),
							nextIndex 		= index + 1;

						if( nextImage.length > 0 )
						{
							$( currentImage ).removeClass( 'active' );
							$( nextImage ).addClass( 'active' );

							self.imageIndex().html( nextIndex );
						}
						else
						{
							// Disable this button.
						}
					},

					"{title} click": function() {

						var editingTitle = self.element.hasClass("editing-title");

						self.element.toggleClass("editing-title", !editingTitle);

						if (!editingTitle) {
							self.editTitle();
						}
					},

					editTitleEvent: "click.es.story.editLinkTitle",

					editTitle: function() {

						self.element.addClass("editing-title");

						setTimeout(function(){

							self.titleTextfield()
								.val(self.title().text())
								.focus()[0].select();

							$(document).on(self.editTitleEvent, function(event) {
								if (event.target!==self.titleTextfield()[0]) {
									self.saveTitle("save");
								}
							});
						}, 1);
					},

					saveTitle: function(operation) {

						if (!operation) operation = save;

						var value = self.titleTextfield().val();

						switch (operation) {

							case "save":
								if (value==="") {
									value = self.title().data("default");
								}
								
								self.title().html(value);
								break;

							case "revert":
								break;
						}

						self.element.removeClass("editing-title");

						$(document).off(self.editTitleEvent);
					},

					"{titleTextfield} keyup": function(el, event) {

						// Escape
						if (event.keyCode==27) {
							self.saveTitle("revert");
						}
					},

					"{description} click": function() {

						var editingDescription = self.element.hasClass("editing-description");

						self.element.toggleClass("editing-description", !editingDescription);

						if (!editingDescription) {
							self.editDescription();
						}
					},

					editDescriptionEvent: "click.es.story.editLinkDescription",

					editDescription: function() {

						self.element.addClass("editing-description");

						setTimeout(function(){

							var descriptionClone = self.description().clone(),
								noDescription = descriptionClone.hasClass("no-description");

							descriptionClone.wrapInner(self.descriptionTextfield());

							if (noDescription) {
								self.descriptionTextfield().val("");
							}

							self.descriptionTextfield()
								.expandingTextarea();		

							self.descriptionTextfield()
								.focus()[0].select();

							$(document).on(self.editDescriptionEvent, function(event) {

								if (event.target!==self.descriptionTextfield()[0]) {
									self.saveDescription("save");
								}
							});
						}, 1);
					},

					saveDescription: function(operation) {

						if (!operation) operation = save;

						var value = self.descriptionTextfield().val().replace(/\n/g, "<br//>");

						switch (operation) {

							case "save":

								var noValue = (value==="");

								self.description()
									.toggleClass("no-description", noValue);

								if (noValue) {
									value = self.descriptionTextfield().attr("placeholder");
								}

								self.description()
									.html(value);
								break;

							case "revert":
								break;
						}

						self.descriptionTextfield()
							.expandingTextarea("destroy");

						self.element.find(".textareaClone").remove();

						self.element.removeClass("editing-description");

						$(document).off(self.editDescriptionEvent);
					},

					"{descriptionTextfield} keyup": function(el, event) {

						// Escape
						if (event.keyCode==27) {
							self.saveDescription("revert");
						}
					}					
				}
			});

			// Resolve module
			module.resolve();

		});
});

EasySocial.module("story/locations", function($){

	var module = this;

	EasySocial
		.require()
		.library(
			"gmaps",
			"scrollTo"
		)
		.view(
			"apps/user/locations/suggestion"
		)
		.language(
			"COM_EASYSOCIAL_LOCATION_PERMISSION_ERROR"
		)
		.done(function(){

			// Constants
			var KEYCODE = {
				BACKSPACE: 8,
				COMMA: 188,
				DELETE: 46,
				DOWN: 40,
				ENTER: 13,
				ESCAPE: 27,
				LEFT: 37,
				RIGHT: 39,
				SPACE: 32,
				TAB: 9,
				UP: 38
			};

			EasySocial.Controller("Story.Locations",
				{
					defaultOptions: {

						view: {
							suggestion: "apps/user/locations/suggestion"
						},

						map: {
							lat: 0,
							lng: 0
						},

						"{form}": "[data-story-location-form]",

						"{textField}"   : "[data-story-location-textField]",
						"{detectButton}": "[data-story-location-detect-button]",

						"{autocomplete}": "[data-story-location-autocomplete]",
						"{suggestions}": "[data-story-location-suggestions]",
						"{suggestion}": "[data-story-location-suggestion]",

						"{viewport}": "[data-story-location-viewport]",

						"{textbox}": "[data-story-location-textbox]",

						"{removeButton}": "[data-story-location-remove-button]"
					}
				},
				function(self) { return {

					init: function() {

						// I have access to:
						// self.panelButton()
						// self.panelContent()

						// Only show auto-detect button if the browser supports geolocation
						if (navigator.geolocation) {
							self.detectButton().show();
						}

						// Allow textfield input only when controller is implemented
						self.textField().removeAttr("disabled");
					},

					"{window} resize": $.debounce(function(){

						var currentLocation = self.currentLocation;

						if (!currentLocation) return;

						var viewport = self.viewport();

						if (viewport.data("width") !== viewport.width()) {

							var coords = currentLocation.geometry.location,
								lat = coords.lat(),
								lng = coords.lng();

							self.navigate(lat, lng);
						}

					}, 250),

					navigate: function(lat, lng) {

						var viewport = self.viewport(),
							width    = viewport.width(),
							height   = viewport.height(),
							url =
								$.GMaps.staticMapURL({
									size: [width, height],
									lat: lat,
									lng: lng,
									markers: [
										{lat: lat, lng: lng}
									]
								});

						self.viewport()
							.css({
								backgroundImage: $.cssUrl(url)
							})
							.data({
								width: width,
								height: height
							});

						self.form().addClass("has-location");
					},

					// Memoized locations
					locations: {},

					lastQueryAddress: null,

					"{textField} keypress": function(textField, event) {

						switch (event.keyCode) {

							case KEYCODE.UP:

								var prevSuggestion = $(
									self.suggestion(".active").prev(self.suggestion.selector)[0] ||
									self.suggestion(":last")[0]
								);

								// Remove all active class
								self.suggestion().removeClass("active");

								prevSuggestion
									.addClass("active")
									.trigger("activate");

								self.suggestions()
									.scrollTo(prevSuggestion, {
										offset: prevSuggestion.height() * -1
									});

								event.preventDefault();

								break;

							case KEYCODE.DOWN:

								var nextSuggestion = $(
									self.suggestion(".active").next(self.suggestion.selector)[0] ||
									self.suggestion(":first")[0]
								);

								// Remove all active class
								self.suggestion().removeClass("active");

								nextSuggestion
									.addClass("active")
									.trigger("activate");

								self.suggestions()
									.scrollTo(nextSuggestion, {
										offset: nextSuggestion.height() * -1
									});

								event.preventDefault();

								break;

							case KEYCODE.ENTER:

								var activeSuggestion = self.suggestion(".active"),
									location = activeSuggestion.data("location");
									self.set(location);

								self.hideSuggestions();
								break;

							case KEYCODE.ESCAPE:
								self.hideSuggestions();
								break;
						}

					},

					"{textField} keyup": function(textField, event) {

						switch (event.keyCode) {

							case KEYCODE.UP:
							case KEYCODE.DOWN:
							case KEYCODE.LEFT:
							case KEYCODE.RIGHT:
							case KEYCODE.ENTER:
							case KEYCODE.ESCAPE:
								// Don't repopulate if these keys were pressed.
								break;

							default:
								var address = $.trim(textField.val());

								if (address==="") {
									self.form().removeClass("has-location");
									self.hideSuggestions();
								}

								// if (address==self.lastQueryAddress) return;

								var locations = self.locations[address];

								// If this location has been searched before
								if (locations) {

									// And set our last queried address to this address
									// so that it won't repopulate the suggestion again.
									self.lastQueryAddress = address;

									// Just use cached results
									self.suggest(locations);

								// Else ask google to find it out for us
								} else {

									self.lookup(address);
								}
								break;
						}
					},

					lookup: $._.debounce(function(address){

						self.textbox().addClass("busy");

						$.GMaps.geocode({
							address: address,
							callback: function(locations, status) {

								self.textbox().removeClass("busy");

								if (status=="OK") {

									// Store a copy of the results
									self.locations[address] = locations;

									// Suggestion locations
									self.suggest(locations);

									self.lastQueryAddress = address;
								}
							}
						});

					}, 250),

					suggest: function(locations) {

						var suggestions = self.suggestions();

						// Clear location suggestions
						suggestions
							.empty();

						if (locations.length < 0) return;

						$.each(locations, function(i, location){

							// Create suggestion and append to list
							self.view.suggestion({
									location: location
								})
								.data("location", location)
								.appendTo(suggestions);
						});

						self.showSuggestions();
					},

					showSuggestions: function() {

						self.focusSuggestion = true;

						self.element.find(".es-story-footer")
							.addClass("swap-zindex");

						setTimeout(function(){

							self.autocomplete().addClass("active");
						}, 500);
					},

					hideSuggestions: function() {

						self.focusSuggestion = false;

						self.autocomplete().removeClass("active");

						setTimeout(function(){

							if (self.focusSuggestion) return;

							self.element.find(".es-story-footer")
								.removeClass("swap-zindex");

						}, 500);
					},

					"{suggestion} activate": function(suggestion, event) {

						var location = suggestion.data("location");

						self.navigate(
							location.geometry.location.lat(),
							location.geometry.location.lng()
						);
					},

					"{suggestion} mouseover": function(suggestion) {

						// Remove all active class
						self.suggestion().removeClass("active");

						suggestion
							.addClass("active")
							.trigger("activate");
					},

					"{suggestion} click": function(suggestion, event) {

						var location = suggestion.data("location");

						self.set(location);

						self.hideSuggestions();
					},

					set: function(location) {

						self.currentLocation = location;

						self.navigate(
							location.geometry.location.lat(),
							location.geometry.location.lng()
						);

						var address = location.formatted_address;

						self.textField().val(address);

						self.lastQueryAddress = address;

						var caption = location.formatted_address;

						self.story.addPanelCaption("locations", caption);

						self.form().addClass("has-location");
					},

					unset: function() {

						self.currentLocation = null;

						self.textField().val('');

						self.story.removePanelCaption("locations");

						self.viewport().css("backgroundImage", "");

						self.form().removeClass("has-location");
					},

					activatePanel: function() {

						setTimeout(function(){
							self.textField().focus();
						}, 500);
					},

					deactivatePanel: function() {

						var location = self.currentLocation;

						if (location) {
							self.set(location);
						}
					},

					detectTimer: null,

					"{detectButton} click": function() {

						var story = self.story,
							textbox = self.textbox();
							textbox.addClass("busy");

						clearTimeout(self.detectTimer);

						self.detectTimer = setTimeout(function(){
							story.setMessage($.language("COM_EASYSOCIAL_LOCATION_PERMISSION_ERROR"));
							textbox.removeClass("busy");
						}, 8000);

						$.GMaps.geolocate({
							success: function(position) {

								story.clearMessage();

								$.GMaps.geocode({
									lat: position.coords.latitude,
									lng: position.coords.longitude,
									callback: function(locations, status){
										if (status=="OK") {
											self.suggest(locations);
											self.textField().focus();
										}
									}
								});
							},
							error: function(error) {
								story.setMessage(error.message, "error");
							},							
							always: function() {
								clearTimeout(self.detectTimer);
								textbox.removeClass("busy");
							}
						});

					},

					"{removeButton} click": function() {
						self.unset();
						self.hideSuggestions();
					},

					"{story} save": function(event, element, save) {

						var currentLocation = self.currentLocation;

						if (!currentLocation) return;

						save.addData(self, {
							short_address    : currentLocation.address_components[0].long_name,
							formatted_address: currentLocation.formatted_address,
							lat              : currentLocation.geometry.location.lat(),
							lng              : currentLocation.geometry.location.lng()
						});
					},

					"{story} clear": function() {

						self.unset();

						self.hideSuggestions();
					}

				}}
			);

			// Resolve module
			module.resolve();

		});

});

EasySocial.module("story/photos", function($){

	var module = this;

	EasySocial.require()
		.script(
			"albums/uploader"
		)
		.done(function(){

			EasySocial.Controller("Story.Photos",
				{
					defaultOptions: {

						"{albumView}"     : "[data-album-view]",
						"{albumContent}"  : "[data-album-content]",
						"{uploadButton}"  : "[data-album-upload-button]",

						"{photoItemGroup}": "[data-photo-item-group]",
						"{photoItem}"     : "[data-photo-item]",
						"{photoImage}"    : "[data-photo-image]",
						"{photoRemoveButton}": "[data-photo-remove-button]",
						"{uploadItem}"    : "[data-photo-upload-item]"
					}
				},
				function(self) { return {

					init: function() {

						// I have access to:
						// self.story
						// self.attachmentButton()
						// self.attachmentItem()
						// self.attachmentContent()
						// self.attachmentToolbar()
						// self.attachmentDragHandle()
						// self.attachmentRemoveButton()

						self.uploader =
							self.albumView()
								.addController(
									EasySocial.Controller.Albums.Uploader,
									$.extend({
										"{uploadButton}"   : self.uploadButton.selector,
										"{uploadItemGroup}": self.photoItemGroup.selector,
										"{uploadDropsite}" : self.albumContent.selector
									},
									{settings: self.options.uploader})
								);

						// Difference from album viewer
						self.photoItemGroup()
							.css("opacity", 1);

						self.addPlugin("uploader", self.uploader);

						self.setLayout();
					},

					hasItems: function() {

						var hasPhotoItem  = self.photoItem().length > 0,
							hasUploadItem = self.uploadItem().length > 0;

						return hasPhotoItem || hasUploadItem;
					},

					setLayout: function() {

						// Show upload hint when content is empty
						self.albumView()
							.toggleClass("has-photos", self.hasItems());
					},

					activateAttachment: function() {

						// self.initialize();

						// if (self.attachedPhotos.length < 1) {
						// 	self.showPhotosForm();
						// }
					},

					removePhoto: function(id) {

						// Remove photo item
						self.photoItem()
							.filterBy('photoId', id)
							.remove();
						
						self.setLayout();
					},

					clearPhoto: function(){

						self.photoItem().remove();

						self.setLayout();
					},

					"{uploader} FilesAdded": function() {
						self.setLayout();
						self.uploader.start();
					},

					"{uploader} FileUploaded": function(el, event, uploader, file, response) {

						var uploadItem = self.uploader.getItem(file),

							photoItem = $($.parseHTML($.trim(response.html)));

							photoData = response.data;

							// Initialize photo item
							photoItem
								.data("photo", photoData)
								.addClass("new-item")
								.insertAfter(uploadItem.element);

							self.uploader.removeItem(file.id);

							self.setLayout();

							setTimeout(function(){
								photoItem.removeClass("new-item");
							}, 1);

							self.save();
					},

					"{uploader} FileError": function(el, event, uploader, file, response) {

						self.story.setMessage(response.message, "error");

						var uploadingPhoto = self.uploadingPhoto;

						if (uploadingPhoto) {
							uploadingPhoto.reject();
							delete self.uploadingPhoto;
						}
					},

					"{uploader} Error": function(el, event, uploader, error) {

						self.story.setMessage(error.message, "error");

						var uploadingPhoto = self.uploadingPhoto;

						if (uploadingPhoto) {
							uploadingPhoto.reject();
							delete self.uploadingPhoto;
						}
					},

					"{photoRemoveButton} click": function(photoRemoveButton) {

						var photoId = 
							photoRemoveButton
								.parent(self.photoItem.selector)
								.data("photoId");

						self.removePhoto(photoId);
					},

					//
					// Saving
					//

					"{story} save": function(element, event, save) {

						if (!self.hasItems()) return;

						self.uploadingPhoto = save.addTask("uploadingPhoto");
						self.save();
					},

					save: function() {

						var uploadingPhoto = self.uploadingPhoto;

						if (!uploadingPhoto) return;

						var uploadItems = self.uploadItem();

						if (uploadItems.length < 1) {

							var photos = [],
								save = uploadingPhoto.save;

							self.photoItem().each(function(){
								photos.push($(this).data("photoId"));
							});

							save.addData(self, photos);

							uploadingPhoto.resolve();

							delete self.uploadingPhoto;
						}
					},					

					"{story} clear": function() {

						self.clearPhoto();
					}
				}}
			);

			// Resolve module
			module.resolve();

		});
});


EasySocial.module( 'stream' , function(){

	var module	= this;


	EasySocial.require()
	.library( 'dialog' )
	.script( 'comment' )
	.view( 'site/likes/item' )
	.language(
		'COM_EASYSOCIAL_SUBSCRIPTION_DIALOG_UNSUBSCRIBE',
		'COM_EASYSOCIAL_SUBSCRIPTION_DIALOG_SUBSCRIBE',
		'COM_EASYSOCIAL_SUBSCRIPTION_BUTTON_OK',
		'COM_EASYSOCIAL_SUBSCRIPTION_BUTTON_SUBMIT',
		'COM_EASYSOCIAL_SUBSCRIPTION_BUTTON_CANCEL',
		'COM_EASYSOCIAL_SUBSCRIPTION_BUTTON_UNSUBSCRIBE',
		'COM_EASYSOCIAL_SUBSCRIPTION_ARE_YOU_SURE_UNSUBSCRIBE',
		'COM_EASYSOCIAL_SUBSCRIPTION_BUTTON_SUBSCRIBE',
		'COM_EASYSOCIAL_STREAM_DIALOG_FEED',
		'COM_EASYSOCIAL_STREAM_BUTTON_CLOSE'
	)
	.done(function($){

		EasySocial.Controller(
			'Stream.Item',
			{
				defaultOptions: {
					id : '',

					'{streamItem}' : '.streamItem',
					'{streamData}' : '.streamData',

					'{streamResponds}' : '.stream-responds',

					'{likeItem}' : '.likeItem',
					'{likeItemList}' : '.likeItemList',

					'{commentLink}' : '.commentLink',
					'{commentFrame}' : '.commentFrame',
					'{commentInput}' : '.commentInput',

					'{followItem}' : '.followItem',
					'{unfollowItem}' : '.unfollowItem',

					'{hideItem}' : '.hideItem',
					'{unhideItem}' : '.unhideItem'
				}
			},
			function( self ){ return {

				init: function(){
					self.commentFrame().implement('EasySocial.Controller.Comments', {						
						uid: self.element.data('id'),
						pagination: new CommentPagination({
							total: self.commentFrame().data('total')
						}),
						commentlist: new Comment.List()
					});
				},

				"{likeItem} click" : function(){
					EasySocial.ajax( 'site:/controllers/likes/toggle' ,
						{
							'id' 		: self.element.data('id'),
							'type'		: 'stream'
						} ,
						{
							success: function( obj ){

								var content = '';

								if( obj.likeCount > 0 )
								{
									content = self.view.likeitem({
									 	likeCount : obj.likeCount
									});

									// temp solution bcos ejs cannot process html code.
									content.find(".likeText").html(obj.message);
								}

								// update the like text
								self.likeItemList().html(content);

								// update the label
								self.likeItem().text( obj.label );

							},
							fail: function(){

							}
						});

				},

				"{unfollowItem} click" : function(){

					var subId = self.element.data('sid');

					if( subId )
					{
						// perform unsubscription.
						$.dialog({
							title: $.language( 'COM_EASYSOCIAL_SUBSCRIPTION_DIALOG_UNSUBSCRIBE' ),
							content: $.language( 'COM_EASYSOCIAL_SUBSCRIPTION_ARE_YOU_SURE_UNSUBSCRIBE' ),
							buttons:
							[
								{
									name: $.language( 'COM_EASYSOCIAL_SUBSCRIPTION_BUTTON_UNSUBSCRIBE' ),
									click: function(){

										EasySocial.ajax( 'site:/controllers/subscriptions/remove' ,
											{
												'id' 				: subId
											} ,
											{
												success: function( obj ){
													$.dialog({
														title: $.language( 'COM_EASYSOCIAL_SUBSCRIPTION_DIALOG_UNSUBSCRIBE' ),
														content: obj.message,
														buttons: [
															{
																name: $.language( 'COM_EASYSOCIAL_SUBSCRIPTION_BUTTON_OK' ),
																click: function(){
																	self.unfollowItem().removeClass( 'unfollowItem' );
																	$.dialog().close();
																}
															}

														]
													});
												},
												fail: function(){

												}
											});

									}

								},
								{
									name: $.language( 'COM_EASYSOCIAL_SUBSCRIPTION_BUTTON_CANCEL' ),
									click: function(){
										$.dialog().close();
									}
								}
							]
						});

					}

				},

				"{followItem} click" : function(){

					// perform subscription.
					EasySocial.ajax( 'site:/controllers/subscriptions/form' ,
						{
							'contentId' 		: self.element.data('id'),
							'contentType'		: 'stream'
						} ,
						{
							success: function( obj ){

								if( obj.message != '' )
								{
									$.dialog({
										title: $.language( 'COM_EASYSOCIAL_SUBSCRIPTION_DIALOG_SUBSCRIBE' ),
										content: obj.message,
										buttons: [
											{
												name: $.language( 'COM_EASYSOCIAL_SUBSCRIPTION_BUTTON_CANCEL' ),
												click: function(){
													$.dialog().close();
												}
											}
										]
									});

									return;
								}

								$.dialog({

									title: $.language( 'COM_EASYSOCIAL_SUBSCRIPTION_DIALOG_SUBSCRIBE' ),
									content: obj.htmlform,
									buttons: [
										{
											name : $.language( 'COM_EASYSOCIAL_SUBSCRIPTION_BUTTON_SUBMIT' ),
											click : function(){

												var fullname 	= $('#esfullname').val() ;
												var email 		= $('#email').val();

												EasySocial.ajax( 'site:/controllers/subscriptions/add' ,
													{
														'contentId' 		: self.element.data('id'),
														'contentType'		: 'stream',
														'esfullname'		: fullname,
														'email'				: email
													} ,
													{
														success: function( obj ){
															$.dialog({
																title: $.language( 'COM_EASYSOCIAL_SUBSCRIPTION_DIALOG_SUBSCRIBE' ),
																content: obj.message,
																buttons: [
																	{
																		name: $.language( 'COM_EASYSOCIAL_SUBSCRIPTION_BUTTON_OK' ),
																		click: function(){
																			self.followItem().removeClass( 'followItem' );
																			$.dialog().close();
																		}
																	}

																]
															});
														},
														fail: function(){

														}
													});

											}
										},
										{
											name : $.language( 'COM_EASYSOCIAL_SUBSCRIPTION_BUTTON_CANCEL' ),
											click : function(){
												$.dialog().close();
											}
										}
									]
								});
							},
							fail: function(){

							}
						});

				},

				"{hideItem} click" : function(){
					EasySocial.ajax( 'site:/controllers/stream/hide' ,
						{
							'id' 		: self.element.data('id')
						} ,
						{
							success: function( obj )
							{
								var content = '<div>' + obj.message + '</div>';

								self.streamData().hide();
								self.element.append(content);
							},
							fail: function( obj )
							{
								$.dialog({
									title: $.language( 'COM_EASYSOCIAL_STREAM_DIALOG_FEED' ),
									content: obj.message,
									buttons: [
										{
											name: $.language( 'COM_EASYSOCIAL_STREAM_BUTTON_CLOSE' ),
											click: function(){
												$.dialog().close();
											}
										}
									]
								});
							}
						});

				},


				"{unhideItem} click" : function(){
					EasySocial.ajax( 'site:/controllers/stream/unhide' ,
						{
							'id' 		: self.element.data('id')
						} ,
						{
							success: function( obj )
							{
								self.streamData().show();
								self.element.children().last().remove();

							},
							fail: function( obj )
							{
								$.dialog({
									title: $.language( 'COM_EASYSOCIAL_STREAM_DIALOG_FEED' ),
									content: obj.message,
									buttons: [
										{
											name: $.language( 'COM_EASYSOCIAL_STREAM_BUTTON_CLOSE' ),
											click: function(){
												$.dialog().close();
											}
										}
									]
								});
							}
						});
				},

				"{commentLink} click" : function(){
					self.commentInput().focus();

				}

			} }
		);


		module.resolve();
	});
});

EasySocial.module( 'tab' , function($) {

var module = this;

EasySocial.Controller(
	'Tab',
	{
		// A list of selectors we define
		// and expect template makers to follow.
		defaultOptions:
		{
			view			:{

			},
			"{tabs}"		: '',
			'{tabsContent}'	: '',
			'{defaultActive}': ''
		}
	},
	function(self){

		return {

			init: function()
			{
				console.log( 'Tabs loaded' );

				// @task: If defaultActive exists, we make this element with the active class.
				self.defaultActive().click();

			},

			'{tabs} click' : function( element ){

				// If the element has class of inactive, we shouldn't do anything here.
				if( $( element ).hasClass( 'inactive' ) )
				{
					return false;
				}
				
				// Remove active tab.
				self.tabs( '.active' ).removeClass( 'active' );

				// @task: Add active class to itself.
				$( element ).addClass( 'active' );
				
				// @task: Hide all contents
				self.tabsContent().hide();

				// @task: Find the current element's id.
				var activeContent		= '.tab-' + $( element ).attr( 'id' );

				// @task: Show active content
				self.tabsContent( activeContent ).show();
			}
		
		}
	}
);

module.resolve();

});

EasySocial.module( 'toggle' , function($) {

var module = this;

EasySocial.Controller(
	'Toggle',
	{
		// A list of selectors we define
		// and expect template makers to follow.
		defaultOptions:
		{
			view			:{

			},
			'{selector}'	: ""
		}
	},
	function(self){

		return {

			init: function()
			{
				console.log( 'Toggle loaded' );
			},

			'{selector} click' : function( element ){
				$( element ).next().toggle();
				$( element ).toggleClass('this-closed');
			}
		
		}
	}
);

module.resolve();

});

EasySocial.module( 'uploader/item' , function($){

	var module 	= this;

	EasySocial.require()
	.view( 'site/uploader/preview' )
	.done( function($){

		EasySocial.Controller(
			'Uploader.Item',
			{
				defaults:
				{
					"{uploadItem}" : ".uploadItem",
					"{uploadItemPreview}" : ".uploadItem.preview a.itemLink",

					// Actions
					"{itemLink}"		: '.itemLink',
					"{itemDelete}"		: '.itemDelete',

					view: {

						preview : 'site/uploader/preview'

					}
				}
			},
			function( self ){ return {

				init: function(){

				},

				"{itemDelete} click": function( el ){

					var id 		= $( el ).data( 'id' );

					EasySocial.ajax( 'site:/controllers/uploader/delete' , {
						'id'	: id
					}, function(){

						// Remove the item from the list
						$( el ).parents( 'li.uploadItem' ).remove();
					})
				},

				"{uploadItemPreview} click" : function( el ){

					var uri 	= $( el ).data( 'uri' ),
						title 	= $( el ).data( 'title' );

					$.dialog({
						title: title,
						content: $.Image.get(uri)
					});





					// $.dialog({
					// 	'title'		: title,
					// 	'content'	: content,
					// 	afterShow	: function(){

					// 		$.dialog().update();

					// 	}
					// });
				}

			} }
		);
	});

	module.resolve();
});


EasySocial.module('utilities/alias', function($) {

	var module = this;

	EasySocial.Controller(
		'Utilities.Alias',
		{
			defaultOptions:
			{
				// Should be overriden by the caller.
				"{target}"	: "",
				"{source}"	: ""
			}
		},function( self ){ 
			return {

				init: function()
				{
				},

				convertToPermalink: function( title )
				{
					return title.replace(/\s/g, '-').replace(/[^\w/-]/g, '').toLowerCase();
				},

				"{source} keyup" : function()
				{
					// Update the target when the source is change.
					self.target().val( self.convertToPermalink( self.source().val() ) );
				},

				"{target} keyup" : function()
				{
					self.target().val( self.convertToPermalink( self.target().val() ) );
				}
			}
		});

	module.resolve();
});
});
