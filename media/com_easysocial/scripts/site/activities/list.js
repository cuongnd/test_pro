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