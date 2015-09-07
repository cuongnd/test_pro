EasySocial.module('site/photos/photos', function($){

	var module = this;

	EasySocial.require()
		.script("photos")
		.done(function(){

			EasySocial.photos = $("body").addController("EasySocial.Controller.Photos");

			module.resolve();
		});
});
