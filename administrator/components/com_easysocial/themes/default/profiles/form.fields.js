EasySocial.require()
.script( 'admin/profiles/fields' )
.done(function($){

	$('.profileFieldForm').implement(
		'EasySocial.Controller.Fields'
	);

});
