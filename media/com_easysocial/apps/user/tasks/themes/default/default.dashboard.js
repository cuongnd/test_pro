
(function($){


	EasySocial.require()
	.view( 'apps/user/tasks/default.form' )
	.done( function($){

		EasySocial.Controller(
			'Apps.Tasks',
			{
				defaultOptions:
				{
					"{create}"	: "[data-tasksApp-create]",
					"{lists}"	: "[data-tasksApp-lists]",
					"{item}"	: "[data-tasksApp-item]",
					"{filter}"	: "[data-tasksApp-filter]",
					"{forms}"	: "[data-tasksApp-form]",

					view:
					{
						form : "apps/user/tasks/default.form"
					}
				}
			},
			function(self)
			{

				return {

					init : function()
					{
						// Implement each list item.
						self.item().implement( EasySocial.Controller.Apps.Tasks.Item );
					},

					"{create} click" : function()
					{
						self.view.form({})
							.implement( 'EasySocial.Controller.Apps.Tasks.Form' ,
							{
								"{parent}"	: self
							})
							.prependTo( self.lists() );
					},

					"{filter} click" : function( el , event )
					{
						var type 	= $( el ).data( 'filter' );

						// Remove all active classes on filters.
						self.filter().removeClass( 'active' );

						// Add active class on itself.
						$( el ).addClass( 'active' );

						// Remove all pending forms.
						self.forms().remove();

						// Hide all items
						self.item().hide();

						// Show only specific types.
						self.item( '.' + type ).show();
					}
				}
		});

		EasySocial.Controller(
			'Apps.Tasks.Item',
			{
				defaultOptions:
				{
					"{checkbox}" : "[data-taskItem-checkbox]",
					"{remove}"	 : "[data-taskItem-remove]",

					view:
					{
						form : "apps:/user/tasks/default.form"
					}
				}
			},
			function(self)
			{

				return {

					init : function()
					{
						self.options.id 	= self.element.data( 'id' );
					},

					"{checkbox} change" : function()
					{
						if( self.checkbox().is(':checked' ) )
						{
							self.element.removeClass( 'unresolved' ).addClass( 'resolved' );

							EasySocial.ajax( 'apps/user/tasks/controllers/tasks/resolve',
							{
								"id"	: self.options.id
							})
							.done(function(){

							})
							.fail(function(){

							});


							return true;
						}

						self.element.removeClass( 'resolved' ).addClass( 'unresolved' );

						EasySocial.ajax( 'apps/user/tasks/controllers/tasks/unresolve',
						{
							"id"	: self.options.id
						})
						.done(function(){



						})
						.fail(function(){

						});

					},

					"{remove} click" : function()
					{
						EasySocial.ajax( 'apps/user/tasks/controllers/tasks/remove' ,
						{
							"id"	: self.options.id
						})
						.done( function(){
							// Remove element from list.
							self.element.remove();
						})
						.fail( function(){
							console.log( 'failed' );
						});
					}
				}
		});

		EasySocial.Controller(
			'Apps.Tasks.Form',
			{
				defaultOptions:
				{
					"{title}"	: "[data-tasksForm-title]",
					"{cancel}"	: "[data-tasksForm-cancel]",
					"{save}"	: "[data-tasksForm-save]"
				}
			},
			function(self)
			{

				return {

					"{save} click" : function()
					{
						EasySocial.ajax( 'apps/user/tasks/controllers/tasks/save' ,
						{
							"title"	: self.title().val()
						})
						.done(function( item ){

							// Remove this form.
							// self.element.remove();

							// Append the item.
							self.element.replaceWith( item );
						})
						.fail( function( response ){
							console.log( response );
						});

					},

					"{title} keyup" : function( el , event )
					{
						// Enter key
						if(event.keyCode == 13)
						{
							self.save().click();
						}

						// Escape key
						if( event.keyCode == 27 )
						{
							self.cancel().click();
						}
					},

					"{cancel} click" : function()
					{
						// Remove element from the list.
						self.element.remove();
					}
				}
		});

		// Implement the controller.
		$( '[data-tasksApp]' ).implement( EasySocial.Controller.Apps.Tasks );

	});
})();
