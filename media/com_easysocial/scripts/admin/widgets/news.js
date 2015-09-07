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
