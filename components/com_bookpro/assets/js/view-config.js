
var ViewConfig = {

	/**
	 * Valid form before submit. Standard in Joomla! administration.
	 * 
	 * @param pressbutton
	 *            button selected in toolbar
	 */
	submitbutton : function(pressbutton) {
		switch (pressbutton) {
		case 'apply':
			// save last select bookmark into cookies
			ACommon.saveBookmark();
			break;
		}
		submitform(pressbutton);
	},

	/**
	 * Some options have child options. This child options are selectable only
	 * if masters options are switch on. This function disable child options if
	 * masters are switch off. This function set events for masters - if masters
	 * go to switch on then child options are enabled.
	 * 
	 * @param boolean
	 *            setEvents if true then function set events for masters - run
	 *            only once during initialise
	 */
	setEvents : function(setEvents) {

		// bookmark main
		this.setDisabled('params_date_long', 'params_date_type0');
		this.setDisabled('params_date_normal', 'params_date_type0');
		this.setDisabled('params_date_day', 'params_date_type0');
		this.setDisabled('params_date_day_short', 'params_date_type0');
		//this.setDisabled('params_time', 'params_date_type0');

		/* bookmark objects

		// thumbnails
		this.setDisabled('params_display_thumbs_subjects_list_width',
				'params_display_thumbs_subjects_list0');
		this.setDisabled('params_display_thumbs_subjects_list_height',
				'params_display_thumbs_subjects_list0');

		// introtext
		this.setDisabled('params_display_readmore_subjects_list_length',
				'params_display_readmore_subjects_list0');

		// pagination
		this.setDisabled('params_subjects_pagination_start',
				'params_subjects_pagination0');

		// monthly calendars
		this.setDisabled('params_subjects_calendar_skin',
				'params_subjects_calendar0');
		this.setDisabled('params_subjects_calendar_start',
				'params_subjects_calendar0');
		this.setDisabled('params_subjects_calendar_deep',
				'params_subjects_calendar0');
		// weekly calendars
		this.setDisabled('params_subjects_week_deep', 'params_subjects_week0');

		/* bookmark object 

		// main image
		this.setDisabled('params_display_thumbs_subject_detail_width',
				'params_display_image_subject_detail0');
		this.setDisabled('params_display_thumbs_subject_detail_height',
				'params_display_image_subject_detail0');
		// images gallery
		this.setDisabled('params_display_gallery_thumbs_subject_detail_width',
				'params_display_gallery_subject_detail0');
		this.setDisabled('params_display_gallery_thumbs_subject_detail_height',
				'params_display_gallery_subject_detail0');
		this.setDisabled('params_display_gallery_preview_subject_detail_width',
				'params_display_gallery_subject_detail0');
		this.setDisabled('params_display_gallery_preview_subject_detail_height',
				'params_display_gallery_subject_detail0');

		 bookmark prices 
		this.setDisabled('params_thousand_separator_char',
				'params_thousand_separator0');
        */
		// set events for all masters
		if (setEvents) {

			//Array.each($('adminForm').getElements('*[class^=masterChild]'),
					//function(child, index) {
						//child.getParent().getParent().addClass('masterChild');
						//child.removeClass('masterChild');
					//});

			//var masters = new Array('params_date_type0', 'params_date_type1',
					//'params_display_image_subject_detail0',
					//'params_display_image_subject_detail1',
					//'params_display_gallery_subject_detail0',
					//'params_display_gallery_subject_detail1');
			/*for ( var i = 0; i < masters.length; i++)
			{
				try{
					$(masters[i]).addEvent('click', function() {
						ViewConfig.setEvents(false);
					});
				}catch(e){console.error( masters[i], " id probably don't exists");}
			}*/
		}
	},

	/**
	 * Disable child option if master is switch off.
	 * 
	 * @param string
	 *            child ID of child option
	 * @param string
	 *            master ID of master option
	 */
	setDisabled : function(child, master) {
		//$(child).setProperty('disabled', $(master).checked);
	}
}

try {
	/**
	 * Joomla! 1.6.x
	 */
	Joomla.submitbutton = function(pressbutton) {
		return ViewConfig.submitbutton(pressbutton);
	}
} catch (e) {
	/**
	 * Joomla! 1.5.x
	 */
	function submitbutton(pressbutton) {
		return ViewConfig.submitbutton(pressbutton);
	}
}