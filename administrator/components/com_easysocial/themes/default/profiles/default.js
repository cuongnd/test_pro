EasySocial.require()
.script( 'admin/profiles/profiles' , 'admin/grid/grid' )
.done(function($){

	// Implement grid item.
	$( '[data-table-grid]' ).implement( EasySocial.Controller.Grid );

	var profileList = $( '[data-profiles]' ).addController( EasySocial.Controller.Profiles );

	<?php if( !$callback ){ ?>
	$.Joomla("submitbutton", function(task)
	{
		if( task == 'form' )
		{
			window.location 	= 'index.php?option=com_easysocial&view=profiles&layout=form';

			return;
		}

		if( task == 'delete' )
		{

			EasySocial.dialog(
			{
				content 	: EasySocial.ajax( 'admin/views/profiles/confirmDelete' , {} ),
				bindings 	:
				{
					"{deleteButton} click" : function()
					{
						$.Joomla( 'submitform' , ['delete' ] );
					}
				}
			});

			return false;
		}

		$.Joomla("submitform", [task]);

	});

	<?php } else { ?>
		
		$( '[data-profile-insert]' ).on('click', function( event )
		{
			event.preventDefault();

			var id 	= $( this ).data( 'id' );

			window.parent["<?php echo JRequest::getCmd( 'callback' );?>" ]( id );
		});

	<?php } ?>

});