EasySocial.require()
	.script("site/profile/avatar")
	.done(function($){
		$("[data-profile-avatar]")
			.addController(
				"EasySocial.Controller.Profile.Avatar",
				{

				}
			);
	});