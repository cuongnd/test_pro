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
