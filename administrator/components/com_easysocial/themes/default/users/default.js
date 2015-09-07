
EasySocial
.require()
.script( 'admin/users/users' , 'admin/grid/grid' )
.library( 'dialog' )
.done(function($){

	$( '[data-table-grid]' ).implement( EasySocial.Controller.Grid );

	<?php if( $this->tmpl != 'component' ){ ?>

	$( '[data-activate-user]' ).on( 'click' , function()
	{
		$(this).parents( '[data-user-item]' ).find( '[data-table-grid-id]' ).prop( 'checked' , 'checked' );

		// Submit the form.
		$.Joomla( 'submitform' , ['activate'] );
	});

	$.Joomla( 'submitbutton' , function( task )
	{
		var selected 	= new Array;

		if( task == 'add' )
		{
			EasySocial.dialog(
			{
				content 	: EasySocial.ajax( 'admin/views/users/newUserForm' ),
				bindings	:
				{
					"{continueButton} click" : function()
					{
						var selectedProfile 	= this.profile().val();
						
						window.location.href 	= "index.php?option=com_easysocial&view=users&layout=form&profileId=" + selectedProfile;
					}
				}
			});

			return false;
		}

		$( '[data-table-grid]' ).find( 'input[name=cid\\[\\]]:checked' ).each( function( i , el  ){
			selected.push( $( el ).val() );
		});

		if( task == 'switchProfile' )
		{
			EasySocial.dialog(
			{
				content 	: EasySocial.ajax( 'admin/views/users/switchProfileForm' , { "ids" : selected }),
				bindings	:
				{
					"{submitButton} click" : function()
					{
						this.form().submit();
					}
				}
			});

			return false;
		}

		if( task == 'assign' )
		{
			EasySocial.dialog(
			{
				content 	: EasySocial.ajax( 'admin/views/users/assign' , { "ids" : selected } ),
				bindings	:
				{
					"{assignButton} click" : function()
					{
						this.assignForm().submit();
					}
				}
			});

			return false;
		}

		if( task == 'assignPoints' )
		{
			// Ask if the admin wants to assign a custom message for this badge
			EasySocial.dialog(
			{
				content	: EasySocial.ajax( 'admin/views/users/assignPoints' , 
				{
					uid : selected
				}),
				bindings:
				{
					"{doneButton} click" : function()
					{
						this.form().submit();
					} 
				}
			});

			return false;
		}

		if( task == 'assignBadge' )
		{

			window.assignBadge	= function( obj , uids )
			{

				// Ask if the admin wants to assign a custom message for this badge
				EasySocial.dialog(
				{
					content	: EasySocial.ajax( 'admin/views/users/assignBadgeMessage' , 
					{
						id 	: obj.id,
						uid : uids
					}),
					bindings:
					{
						"{doneButton} click" : function()
						{
							this.assignForm().submit();
						} 
					}
				});
			}

			EasySocial.dialog(
			{
				content 	: EasySocial.ajax( 'admin/views/users/assignBadge' , 
				{
					ids			: selected
				})
			});

			return false;
		}

		if( task == 'remove' )
		{
			EasySocial.dialog( 
			{
				content 	: EasySocial.ajax( 'admin/views/users/confirmDelete' , { "id" : selected })
			});

			return false;
		}

		if( task == 'add' )
		{
			window.location 	= '<?php echo JURI::root();?>administrator/index.php?option=com_easysocial&view=users&layout=form';
			return false;
		}

		// Submit the form.
		$.Joomla( 'submitform' , [task] );
	});

	// $( '#filterGroup, #filterLogin, #filterState' ).bind( 'change' , function(){

	// 	$( '#userForm' ).submit();

	// });
	<?php } else { ?>
		
		$( '[data-user-insert]' ).on('click', function( event )
		{
			event.preventDefault();

			// Supply all the necessary info to the caller
			var id 		= $( this ).data( 'id' ),
				avatar 	= $( this ).data( 'avatar' ),
				title	= $( this ).data( 'title' ),
				alias	= $(this).data( 'alias' );

				obj 	= {
							"id"	: id,
							"title"	: title,
							"avatar" : avatar,
							"alias"	: alias
						  };

			window.parent["<?php echo JRequest::getCmd( 'jscallback' );?>" ]( obj );
		});
		
	<?php } ?>
});