EasySocial.module('site/sidebar/sidebar', function($) {
	var module = this;

	$(function() {
		var toggle = $('[data-sidebar-toggle]');

		toggle.on('click', function() {
			var sidebar = $('[data-sidebar]');

			sidebar.toggleClass('sidebar-open');

			sidebar.trigger('sidebarToggle');
		});
	});

	module.resolve();
});
