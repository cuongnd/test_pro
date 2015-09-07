EasySocial.require()
	.script("story/photos")
	.done(function($){

		var plugin =
			story.addPlugin("photos", {
				uploader: {
					settings: {
						url: "<?php echo FRoute::raw( 'index.php?option=com_easysocial&controller=photos&task=uploadStory&format=json&tmpl=component&' . Foundry::token() . '=1' ); ?>"
					}
				}
			});
	});
