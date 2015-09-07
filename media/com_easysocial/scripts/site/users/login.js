EasySocial.module('site/users/login', function($){

	var module = this;

	EasySocial.require()
		.library('dialog')
		.done(function(){

			EasySocial.login = function() {
				EasySocial.dialog({
					'content': EasySocial.ajax( 'site/views/login/form' , {})
				});
			}

			module.resolve();
		});
});