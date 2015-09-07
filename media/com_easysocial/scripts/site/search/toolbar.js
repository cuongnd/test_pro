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