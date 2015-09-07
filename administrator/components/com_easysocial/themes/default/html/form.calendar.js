EasySocial.require()
.library('ui/timepicker')
.done(function($)
{
	// $( '[data-badge-created]' ).datetimepicker(
	// {
	// 	timeFormat		: "HH:mm:ss",
	// 	dateFormat		: "yy-mm-dd",
	$( '[data-form-calendar-<?php echo $uuid;?>]' ).datetimepicker(
	{
		changeMonth	 	: true,
		changeYear 		: true,
		timeFormat		: "HH:mm:ss",
		dateFormat		: "yy-mm-dd",
		onSelect 		: function( value )
		{
		}
	});
});