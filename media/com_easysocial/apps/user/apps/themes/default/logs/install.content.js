
EasySocial.require()
.done(function($)
{
	$( '[data-install-app]' ).on( 'click' ,function()
	{
		var installButton 	= this;
		
		EasySocial.dialog({
			content: EasySocial.ajax('site/views/apps/getTnc' ),
			bindings:
			{
				'{cancelButton} click': function() {

					EasySocial.dialog().close();
				},

				'{installButton} click': function()
				{
					var agreed = EasySocial.dialog().element.find('[data-apps-install-agree]').is(':checked') || !self.options.requireTerms;

					if( !agreed )
					{
						this.termsError().show();
						return;
					}

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
						$( installButton ).hide();
					});
				}
			}
		});
	});


});