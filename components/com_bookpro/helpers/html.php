<?php


/**
 * Support for generating html code
 *
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: html.php 82 2012-08-16 15:07:10Z quannv $
 **/
defined('_JEXEC') or die('Restricted access');

class AHtmlFrontEnd
{

	/**
	 * Get calendar html with cleaning date.
	 *
	 * @param string $date       date in default MySQL format
	 * @param string $fieldName  html field name
	 * @param string $fieldId    html field id
	 * @param string $formatDate format to date humanreadable displaying
	 * @param string $formatCal  interval format for js calendar, there is a different in Joomla 1.6.x.
	 * For displaying is used date format (e.q. Y-m-d).
	 * Calendar use strftime format (e.q. %Y-%m-%d).
	 * @param string $customParams custom HTML field params (e.q. class="inputbox")
	 * @return string HTML code
	 */
	static function getCalendar($date, $name, $id, $formatDate, $format, $attribs = '', $addTime = true, $offset = true)
	{
		static $done;

		if ($done === null)
			$done = array();

		JHtml::_('behavior.calendar');
		JHtml::_('behavior.tooltip');

		// Only display the triggers once for each control.
		if (! in_array($id, $done)) {

			$done[] = $id;

			$id = htmlspecialchars($id, ENT_QUOTES, 'UTF-8');

			// field where calendar writes date value
			$setting = array('inputField: "' . $id . '"');
			// field where calendar displays formated date value
			$setting[] = 'displayArea: "' . $id . '_da"';
			// date format for input field
			$setting[] = 'ifFormat: "%Y-%m-%d %H:%M:%S"';
			// date format for display area
			$setting[] = 'daFormat: "' . htmlspecialchars($format, ENT_QUOTES, 'UTF-8') . '"';
			// button to trige calendar
			$setting[] = 'button: "' . $id . '_img"';
			// text align
			$setting[] = 'align: "Tl"';
			$setting[] = 'singleClick: true';
			if ($addTime)
				// show time picker
				$setting[] = 'showsTime: true';

			$setting[] = 'firstDay: ' . JFactory::getLanguage()->getFirstDay();

			$document = &JFactory::getDocument();
			/* @var $document JDocumentHTML */
			$document->addScriptDeclaration('window.addEvent(\'domready\', function() { Calendar.setup({ ' . implode(', ', $setting) . ' });});');
		}

		$code = '<span id="' . $id . '_da" class="calendar_da">' . htmlspecialchars($offset ? AHtmlFrontEnd::date($date, $formatDate) : AHtmlFrontEnd::date($date, $formatDate, 0), ENT_COMPAT, 'UTF-8') . '</span>';
		$code .= '<input type="hidden" id="' . $id . '" name="' . htmlspecialchars($name, ENT_QUOTES, 'UTF-8') . '" value="' . htmlspecialchars($offset ? AHtmlFrontEnd::date($date, ADATE_FORMAT_MYSQL_DATETIME) : AHtmlFrontEnd::date($date, ADATE_FORMAT_MYSQL_DATETIME, 0), ENT_COMPAT, 'UTF-8') . '" />';
		$code .= '<img class="calendar" src="' . IMAGES . 'icon-16-calendar.png" alt="calendar" id="' . $id . '_img" title="' . htmlspecialchars(JText::_('SET DATE'), ENT_QUOTES, 'UTF-8') . '"/>';
		$code .= '<img class="calendar_era" src="' . IMAGES . 'icon-16-calendar-erase.png" alt="erase" id="' . $id . '_era" title="' . htmlspecialchars(JText::_('ERASE DATE'), ENT_QUOTES, 'UTF-8') . '" onclick="ACommon.resetCalendar(\'' . $id . '\')" />';
		return $code;
	}
	function getCustomcalendar($value, $name, $id, $format, $attribs = null)
	{
		JHTML::_('behavior.calendar');

		if (is_array($attribs))
			$attribs = JArrayHelper::toString($attribs);

		$js = 'Calendar.setup({' . PHP_EOL;
		$js .= '  inputField     :    \'' . $id . '\',' . PHP_EOL;
		$js .= '  ifFormat       :    \'' . $format . '\',' . PHP_EOL;
		$js .= '  button         :    \'' . ($button = $id . '_button') . '\',' . PHP_EOL;
		$js .= '  align          :    \'Tl\',' . PHP_EOL;
		$js .= '  singleClick    :    true,' . PHP_EOL;
		$js .= '  disableFunc    :    disallowDate,' . PHP_EOL;
		$js .= '  onSelect       :    onSelectDate' . PHP_EOL;
		$js .= '});' . PHP_EOL;

		ADocument::addDomreadyEvent($js);

		$code = '<input type="hidden" name="' . $name . '" id="' . $id . '" value="' . htmlspecialchars($value, ENT_COMPAT, 'UTF-8') . '" ' . $attribs . ' />';
		$code .= '<a class="calendarButton" id="' . $button . '">' . JText::_('Select date') . '</a>';

		return $code;
	}

	/**
	 * Get time picker gui selector.
	 *
	 * @param string $value
	 * @param string $field
	 * @return string HTML
	 */
	function getTimePicker($value, $field, $withTzOffset = true, $params = '')
	{
		static $id;
		if (is_null($id)) {
			$id = 1;
		} else {
			$id ++;
		}
		$picker = 'timePicker' . $id;
		$toggler = 'timePickerToggler' . $id;
		$holder = 'timePickerHolder' . $id;
		if ($withTzOffset) {
			$time = AHtmlFrontEnd::date($value, ATIME_FORMAT_SHORT);
		} else {
			$time = AHtmlFrontEnd::date($value, ATIME_FORMAT_SHORT, 0);
		}
		if ($withTzOffset) {
			$hour = (int) AHtmlFrontEnd::date($value, 'H');
		} else {
			$hour = (int) AHtmlFrontEnd::date($value, 'H', 0);
		}
		if ($withTzOffset) {
			$minute = (int) AHtmlFrontEnd::date($value,  'i');
		} else {
			$minute = (int) AHtmlFrontEnd::date($value,  'i', 0);
		}
		$code = '<input type="text" name="' . $field . '" value="' . $time . '" id="' . $picker . '" size="5" ' . $params . '/>';
		$code .= '<img src="' . IMAGES . 'icon-16-clock.png" id="' . $toggler . '" alt="' . JText::_('Open time picker') . '" class="clock"/>';
		$code .= '<div id="' . $holder . '" class="time_picker_div"></div>';
		$document = &JFactory::getDocument();
		$document->addScriptDeclaration("
				window.addEvent('domready',
				function() {
				timePickers.push(
				new TimePicker('$holder', '$picker', '$toggler',
				{
				format24: true,
				imagesPath:\"" . TIME_PICKER_IMAGES . "\",
				startTime: {
				hour: $hour,
				minute: $minute
	}
	}
		)
		)
	}
		)
				");
		return $code;
	}

	/**
	 * Filter no real date data like 0000-00-00 or 0000-00-00 00:00:00 or null value or empty string.
	 *
	 * @param string $date date to clean
	 * @return string real date/empty string
	 */
	function cleanDate($date)
	{
		switch (($date = JString::trim($date))) {
			case '0000-00-00':
			case '0000-00-00 00:00:00':
			case '00:00:00':
			case '':
			case null:
			case NULL:
				return '';
			default:
				return $date;
		}
	}

	/**
	 * Get formated date in locale, GMT0 or custom localization.
	 *
	 * @param string $date   date in format to work with PHP strftime (Joomla 1.5.x) or date (Joomla 1.6.x) method.
	 * @param string $format string format for strftime/date (see above).
	 * @param mixed  $offset time zone offset. 0/null/value - GMT0/offset from Joomla global config/custom offset
	 * @return string formated date
	 */
	static function date($date, $format, $offset = null)
	{

		if ($offset === 0)
			$offset = 'GMT0';
		if ($offset === null) {
			$mainframe = &JFactory::getApplication();
			/* @var $mainframe JApplication */
			$offset = $mainframe->getCfg('offset');
		}

		switch (($date = AHtmlFrontEnd::cleanDate($date))) {
			case '':
				return '';
			default:
				return JHtml::date($date, $format, $offset);
		}
	}

	function getFilterGroupList(){

	}
	/**
	 * Get dropdown list by added data
	 *
	 * @param string $field name
	 * @param string $noSelectText default value label
	 * @param array $items dropdown items
	 * @param int $selected current item
	 * @param boolean $autoSubmit autosubmit form on change dropdown list true/false
	 * @param string $customParams custom dropdown params like style or class params
	 * @param string name of param of items which may be used like value param in select box
	 * @param
	 * @return string HTML code
	 */
	static function  getFilterSelect($field, $noSelectText, $items, $selected, $autoSubmit = false, $customParams = '', $valueLabel = 'value', $textLabel = 'text')
	{
		$first = new stdClass();
		$first->$valueLabel = 0;
		$first->$textLabel = '- ' . JText::_($noSelectText) . ' -';
		array_unshift($items, $first);
		$customParams = array(trim($customParams));
		if ($autoSubmit) {
			$customParams[] = 'onchange="this.form.submit()"';
		}

		$customParams = implode(' ', $customParams);

		return JHTML::_('select.genericlist', $items, $field, $customParams, $valueLabel, $textLabel, $selected);
	}

	/**
	 * Get control panel button.
	 *
	 * @param string $link URL on page
	 * @param string $image button image
	 * @param string $text button label
	 * @return string HTML code
	 */
	static function getCPanelButton($link, $image, $text, $localImage = false, $params = array())
	{
		static $mainframe, $lang, $template;
		if (is_null($mainframe)) {
			$mainframe = &JFactory::getApplication();
			/* @var $mainframe JAdministrator */
			$lang = &JFactory::getLanguage();
			/* @var $lang JLanguage */
			$template = $mainframe->getTemplate();
		}
		$hparams = '';
		foreach ($params as $param => $value) {
			$hparams .= htmlspecialchars($param) . '="' . htmlspecialchars($value) . '" ';
		}
		$code = '<div class="icon">' . PHP_EOL;
		$code .= '	<a href="' . $link . '" title="' . $text . '" ' . $hparams . '>' . PHP_EOL;
		$path = ($localImage ? IMAGES : 'templates/' . $template . '/images/header/') . 'icon-48-' . $image . '.png';
		$code .= '<img src="' . $path . '" alt="' . JText::_($text) . '" />';
		$code .= '		<span>' . $text . '</span>' . PHP_EOL;
		$code .= '	</a>' . PHP_EOL;
		$code .= '</div>' . PHP_EOL;
		return $code;
	}

	/**
	 * Get control panel button to open standard Joomla! configuration page in lightbox.
	 *
	 * @return string HTML code
	 */
	static function getCPanelConfigButton()
	{
		$params = array('class' => 'modal' , 'rel' => '{handler: \'iframe\', size: {x: 800, y: 600}}');
		return AHtmlFrontEnd::getCPanelButton(ARoute::config(), 'config', JText::_('Configuration'), false, $params);
	}

	/**
	 * Get state item icon with tooltip label
	 *
	 * @param stdClass $row item
	 * @param int $i order number in lost
	 * @return string HTML code
	 */
	function state(&$row, $i, $active = true)
	{
		$mainframe = &JFactory::getApplication();
		/* @var $mainframe JApplication */
		$tzoffset = $mainframe->getCfg('offset');
		$now = &JFactory::getDate();
		/* @var $now JDate */
		$nowUTS = $now->toUnix();
		$template = &$mainframe->getTemplate();
		$nullDate = AModel::getNullDate();
		$publishUp = &JFactory::getDate($row->publish_up, $tzoffset);
		/* @var $publishUp JDate */
		$publishDown = &JFactory::getDate($row->publish_down, $tzoffset);
		/* @var $publishDown JDate */
		$publishUpUTS = $publishUp->toUnix();
		$publishDownUTS = $publishDown->toUnix();
		$submit = $row->state > - 1;
		switch ($row->state) {
			case 0:
				$className = 'aIconUnpublish';
				$alt = 'Unpublished';
				break;
			case 1:
				if ($nowUTS <= $publishUpUTS) {
					$className = 'aIconPending';
					$alt = 'Pending';
				} elseif ($nowUTS <= $publishDownUTS || $row->publish_down == $nullDate) {
					$className = 'aIconPublished';
					$alt = 'Published';
				} elseif ($nowUTS > $publishDownUTS) {
					$className = 'aIconExpired';
					$alt = 'Expired';
				}
				break;
			case - 1:
				$className = 'aIconArchived';
				$alt = 'Archived';
				break;
			case - 2:
				$className = 'aIconTrash';
				$alt = 'Trashed';
				break;
		}
		$times = '';
		$alt = htmlspecialchars(JText::_($alt), ENT_QUOTES);
		if (isset($row->publish_up) && $submit) {
			if ($row->publish_up == $nullDate)
				$times .= htmlspecialchars(JText::_('Object publish up infinity'), ENT_QUOTES);
			else
				$times .= htmlspecialchars(JText::sprintf('Object publish up', AHtmlFrontEnd::date($row->publish_up, ADATE_FORMAT_LONG)), ENT_QUOTES);
		}
		if (isset($row->publish_down) && $submit) {
			if ($row->publish_down == $nullDate)
				$times .= '<br/>' . htmlspecialchars(JText::_('Object publish down infinity'), ENT_QUOTES);
			else
				$times .= '<br/>' . htmlspecialchars(JText::sprintf('Object publish down', AHtmlFrontEnd::date($row->publish_down, ADATE_FORMAT_LONG)), ENT_QUOTES);
		}
		if ($submit) {
			$code = '<span class="editlinktip hasTip" title="' . htmlspecialchars(JText::_('Publish Information', ENT_QUOTES) . '::' . $times) . '">';
			if ($active) {
				$code .= '<a href="javascript:void(0);" onclick="return listItemTask(\'cb' . $i . '\',\'' . ($row->state ? 'unpublish' : 'publish') . '\')" title="">';
				$code .= '<span class="aIcon ' . $className . ' aIconPointer">&nbsp;</span>';
				$code .= '</a>';
			} else
				$code .= '<span class="aIcon ' . $className . '" title="' . $alt . '">&nbsp;</span>';
			$code .= '</span>';
			return $code;
		}
		return '<span class="aIcon ' . $className . '" title="' . $alt . '">&nbsp;</span>';
	}
	function feature(&$row, $i, $active = true){
		$submit = $row->featured > - 1;
		switch ($row->featured) {
			case 0:
				$className = 'aIconUnpublish';
				$alt = 'Unpublished';
				break;
			case 1:
				$className = 'aIconPublished';
				$alt = 'Published';
		}
		if ($submit) {
			$code = '<span class="editlinktip hasTip" title="' . htmlspecialchars(JText::_('Publish Information', ENT_QUOTES) . '::' . $times) . '">';
			if ($active) {
				$code .= '<a href="javascript:void(0);" onclick="return listItemTask(\'cb' . $i . '\',\'' . ($row->featured ? 'unfeature' : 'feature') . '\')" title="">';
				$code .= '<span class="aIcon ' . $className . ' aIconPointer">&nbsp;</span>';
				$code .= '</a>';
			} else
				$code .= '<span class="aIcon ' . $className . '" title="' . $alt . '">&nbsp;</span>';
			$code .= '</span>';
			return $code;
		}
		return '<span class="aIcon ' . $className . '" title="' . $alt . '">&nbsp;</span>';
	}

	function noActiveAccess(&$row, $i, $archived = NULL)
	{
		if (! $row->access) {
			$color = 'green';
		} else if ($row->access == 1) {
			$color = 'red';
		} else {
			$color = 'black';
		}
		$groupname = JText::_($row->groupname);
		if ($archived == - 1) {
			$href = $groupname;
		} else {
			$href = '<span style="color: ' . $color . ';">' . $groupname . '</span>';
		}
		return $href;
	}

	/**
	 * Smart state indicator. Only active or trashed icon without clickable icon.
	 *
	 * @param stdClass $row
	 * @return string HTML code
	 */
	function enabled(&$row)
	{
		switch ($row->state) {
			case CUSTOMER_STATE_PUBLISHED:
				switch ($row->block) {
					case CUSTOMER_USER_STATE_ENABLED:
						$className = 'aIconTick';
						$title = 'Active';
						break;
					case CUSTOMER_USER_STATE_BLOCK:
						$className = 'aIconUnpublish';
						$title = 'Block';
						break;
				}
				break;
			case CUSTOMER_STATE_UNPUBLISHED:
				$className = 'aIconTrash';
				$title = 'Trashed';
				break;
		}
		return AHtmlFrontEnd::stateTool($title, '', $className);
	}

	function stateTool($title, $text, $className, $i = null, $nextHop = null, $isChecked = false)
	{
		if ($isChecked) {
			$title = JText::_('Item is checked');
		} else {
			$title = JText::_($title);
			if (! is_null($i) && ! is_null($nextHop)) {
				$title .= '::' . JText::_($text);
			}
		}

		$code = '<span class="editlinktip hasTip aIcon ' . $className . '" title="' . $title . '"';
		if (! is_null($i) && ! is_null($nextHop) && ! $isChecked) {
			$code .= ' onclick="listItemTask(\'cb' . $i . '\',\'' . $nextHop . '\')" style="cursor: pointer" ';
		}
		$code .= '>&nbsp;</span>';
		return $code;
	}

	function importIcons()
	{
		AImporter::cssIcon('tick', 'icon-16-tick.png');
		AImporter::cssIcon('unpublish', 'icon-16-storno.png');
		AImporter::cssIcon('trash', 'icon-16-trash.png');
		AImporter::cssIcon('pending', 'icon-16-pending.png');
		AImporter::cssIcon('published', 'icon-16-publish.png');
		AImporter::cssIcon('expired', 'icon-16-unpublish.png');
		AImporter::cssIcon('archived', 'icon-16-disabled.png');
		AImporter::cssIcon('edit', 'icon-16-edit.png');
		AImporter::cssIcon('info', 'icon-16-info.png');
		AImporter::cssIcon('default', 'icon-16-default.png');
		AImporter::cssIcon('email', 'icon-16-email.png');
		AImporter::cssIcon('toolProfile', 'icon-32-card.png');
		AImporter::cssIcon('toolEdit', 'icon-32-edit.png');
		AImporter::cssIcon('toolReservations', 'icon-32-edittime.png');
		AImporter::cssIcon('toolSave', 'icon-32-save.png');
		AImporter::cssIcon('toolCancel', 'icon-32-cancel.png');
		AImporter::cssIcon('toolApply', 'icon-32-apply.png');
		AImporter::cssIcon('toolTrash', 'icon-32-delete.png');
		AImporter::cssIcon('toolRestore', 'icon-32-restore.png');
		AImporter::cssIcon('toolBack', 'icon-32-back.png');
		AImporter::cssIcon('toolPublish', 'icon-32-publish.png');
		AImporter::cssIcon('toolUnpublish', 'icon-32-unpublish.png');
		AImporter::cssIcon('toolPending', 'icon-32-query.png');
		AImporter::cssIcon('toolAdd', 'icon-32-add.png');
		AImporter::cssIcon('toolDelete', 'icon-32-trash.png');
		AImporter::cssIcon('buy', 'icon-48-buy.png');
	}

	/**
	 * Render multiple list filter by added name, options and select values
	 *
	 * @param string $name filter name, use for name and id param
	 * @param string $options usable options
	 * @param string $select select filter values from request
	 * @return string HTML code
	 */
	function renderMultipleFilter($name, $options, $select)
	{
		$code = '<select name="' . $name . '[]" id="' . $name . '" size="3" multiple="multiple" onchange="this.form.submit()" class="inputbox">';
		foreach ($options as $value => $label) {
			$code .= '<option value="' . htmlspecialchars($value) . '"' . (in_array($value, $select) ? ' selected="selected" ' : '') . '>' . JText::_($label) . '</option>';
		}
		$code .= '</select>';
		return $code;
	}

	/**
	 * Get order tools for tree items list.
	 *
	 * @param array $items ordered items
	 * @param int $currentIndex index of current item in list
	 * @param JPagination $pagination standard Joomla! pagination object to create order arrows
	 * @param boolean $turnOnOrdering turn ordering on/off - true/false
	 * @param int $itemsCount total list items count
	 * @return string HTML code
	 */
	function orderTree(&$items, $currentIndex, &$pagination, $turnOnOrdering, $itemsCount)
	{
		$currentItem = &$items[$currentIndex];
        $currentItemParent = $currentItem->parent;
        $inBranchWithPreview = false;
        for ($i = $currentIndex - 1; $i >= 0; $i --) {
            if ($currentItemParent == $items[$i]->parent) {
                $inBranchWithPreview = true;
                break;
            }
        }
        $inBranchWithNext = false;
        for ($i = $currentIndex + 1; $i < $itemsCount; $i ++) {
            if ($currentItemParent == $items[$i]->parent) {
                $inBranchWithNext = true;
                break;
            }
        }
       // $code = '<span>' . $pagination->orderUpIcon($currentIndex, $inBranchWithPreview, 'orderup', 'Move Up', $turnOnOrdering) . '</span>';
        //$code .= '<span>' . $pagination->orderDownIcon($currentIndex, $itemsCount, $inBranchWithNext, 'orderdown', 'Move Down', $turnOnOrdering) . '</span>';
        $code .= '<input type="text" name="order[]" size="1" value="' . $currentItem->ordering . '" ' . ($turnOnOrdering ? '' : 'disabled="disabled"') . ' class="input-mini" style="text-align: center" />';
        return $code;
	}
	/**
	 * Generates a HTML check box or boxes
	 * @param array An array of objects
	 * @param string The value of the HTML name attribute
	 * @param string Additional HTML attributes for the <select> tag
	 * @param mixed The key that is selected. Can be array of keys or just one key
	 * @param string The name of the object variable for the option value
	 * @param string The name of the object variable for the option text
	 * @returns string HTML for the select list
	 */
	static function checkBoxList( &$arr, $tag_name, $tag_attribs, $selected=null, $key='value', $text='text' ) {
		reset( $arr );
		$html = "";
		for ($i=0, $n=count( $arr ); $i < $n; $i++ ) {
			$k = $arr[$i]->$key;
			$t = $arr[$i]->$text;
			$id = @$arr[$i]->id;

			$extra = '';
			$extra .= $id ? " id=\"" . $arr[$i]->id . "\"" : '';
			if (is_array( $selected )) {
				foreach ($selected as $obj) {
					$k2 = $obj;
					if ($k == $k2) {
						$extra .= " checked=\"checked\" ";
						break;
					}
				}
			} else {
				$extra .= ($k == $selected ? " checked " : '');
			}

			$html .= "\n\t<label class='checkbox inline'><input type=\"checkbox\" name=\"$tag_name\" value=\"".$k."\"$extra $tag_attribs />" . $t;
			$html .= "\n</label>";
		}

		return $html;


	}
	static function bootrapCheckBoxList( &$arr, $tag_name, $tag_attribs, $selected=null, $key='value', $text='text' ) {
		reset( $arr );

		$html[] ='<fieldset class="checkboxes" >' ;
		$html[] = '<ul id="triple">';
		for ($i=0, $n=count( $arr ); $i < $n; $i++ ) {
			$k = $arr[$i]->$key;
			$t = $arr[$i]->$text;
			$id = @$arr[$i]->id;
			$extra = '';
			$extra .= $id ? " id=\"" . $arr[$i]->id . "\"" : '';
			if (is_array( $selected )) {
				foreach ($selected as $obj) {
					$k2 = $obj;
					if ($k == $k2) {
						$extra .= " checked=\"checked\" ";
						break;
					}
				}
			} else {
				$extra .= ($k == $selected ? " checked " : '');
			}
			$attrId=$tag_name.$i;
			$html[] = "<li>"."<label for=\"$attrId\" class='checkbox'>";
			$html[] = "<span class='title-checkbox'>".$t ."</span>";
			$html[] = "<span class='input-checkbox'>"."\n\t<input type=\"checkbox\" id=\"$attrId\" name=\"$tag_name\" value=\"".$k."\"$extra $tag_attribs />"."</span>";
			$html[] = "</label>";

			$html[] = '</li>';
		}
		$html[]= "\n";
		$html[] = '</ul>';
		$html[] = '</fieldset>';
		return implode($html);
	}
	static function bootrapCheckBox( &$arr, $tag_name, $tag_attribs, $selected=null, $key='value', $text='text' ) {
		reset( $arr );
		$html[] ='<fieldset class="checkboxes" >' ;
		$html[] = '<ul id="triple">';
		for ($i=0, $n=count( $arr ); $i < $n; $i++ ) {
			$k = $arr[$i]->$key;
			$t = $arr[$i]->$text;
			$id = @$arr[$i]->id;
			$extra = '';
			$extra .= $id ? " id=\"" . $arr[$i]->id . "\"" : '';
			if (is_array( $selected )) {
				foreach ($selected as $obj) {
					$k2 = $obj;
					if ($k == $k2) {
						$extra .= " checked=\"checked\" ";
						break;
					}
				}
			} else {
				$extra .= ($k == $selected ? " checked " : '');
			}
			$attrId=$tag_name.$i;
			$html[] = "<li>"."<label for=\"$attrId\" class='checkbox'>";
			$html[] = "<span class='title-checkbox'>".$t ."</span>"."</br>";
			$html[] = "<span class='input-checkbox'>"."\n\t<input type=\"checkbox\" id=\"$attrId\" name=\"$tag_name\" value=\"".$k."\"$extra $tag_attribs />"."</span>";
			$html[] = "</label>";
			$html[] = '</li>';
		}
		$html[]= "\n";
		$html[] = '</ul>';
		$html[] = '</fieldset>';
		return implode($html);
	}


	/**
	 * Get checkbox HTML
	 *
	 * @param int $value if 1 checkbox is checked
	 * @param string $field name, use for name and id param
	 * @return string HTML
	 */
	function getCheckbox($value, $field, $extraValue = null, $autoSubmit = false)
	{
		$code = '<input type="checkbox" class="inputCheckbox" name="' . $field . '" id="' . $field . '" value="' . (is_null($extraValue) ? 1 : $extraValue) . '" ' . ($value !== false ? 'checked="checked"' : '');
		$code .= ($autoSubmit ? ' onclick="document.adminForm.submit()" ' : '') . '/>' . PHP_EOL;
		return $code;
	}

	function getFilterCheckbox($field, $value, $extraValue, $image, $templateImage = false, $text = null, $color = null)
	{
		$code = '<span class="cfilter" title="' . htmlspecialchars($text, ENT_QUOTES, ENCODING) . '">' . PHP_EOL;
		$code .= AHtmlFrontEnd::getCheckbox($value, $field, $extraValue, true);
		if ($image) {
			$code .= '<img src="' . IMAGES . 'icon-16-' . $image . '.png" alt="" onclick="$(\'' . $field . '\').checked=!$(\'' . $field . '\').checked;document.adminForm.submit();" style="cursor: pointer;" />';
		} else {
			$code .= '<label for="' . $field . '" class="text" style="color: ' . $color . '">' . JText::_($text) . '</label>';
		}
		$code .= '</span>' . PHP_EOL;
		return $code;
	}

	/**
	 * Set page title by JToolBarHelper object like "OBJECT_TITLE:[task]",
	 * where task take from request and OBJECT_TITLE and icon is given by function parameter.
	 *
	 * @param string $title object title
	 * @param string $icon image name
	 */
	function title($title, $icon, $ctitle = 'Bookpro')
	{
		JToolBarHelper::title($ctitle . ': ' . JText::_($title) /*. ' <small><small>[ ' . ucfirst(JText::_(JRequest::getString('task'))) . ' ]</small></small>'*/, $icon);
	}

	function getReadmore($text, $length = null)
	{
		$text = strip_tags($text);
		$text = JString::trim($text);
		if ($length) {
			$text = JString::substr($text, 0, $length + 1);
			$last = JString::strrpos($text, ' ');
			if ($last) {
				$text = JString::substr($text, 0, $last);
				$run = true;
				while ($run) {
					$slength = JString::strlen($text);
					if ($slength == 0) {
						break;
					}
					$last = JString::substr($text, $slength - 1, 1);
					switch ($last) {
						case '.':
						case ',':
						case '_':
						case '-':
							$text = JString::substr($text, 0, $slength - 1);
							break;
						default:
							$run = false;
							break;
					}
				}
				$text .= ' ...';
			}
		}
		return $text;
	}

	/**
	 * Make custom HTML tooltip.
	 *
	 * @param string $header Header text displayed with icon
	 * @param string $text Text displayed after open tooltip or on mouse icon over
	 * @return string HTML code
	 */
	function info($header, $text)
	{
		$header = JString::trim(JText::_($header));
		$text = JString::trim(JText::_($text));

		if ($header && $text)
			$title = htmlspecialchars($header, ENT_QUOTES) . '::' . htmlspecialchars($text, ENT_QUOTES);
		else
			$title = htmlspecialchars($header . $text);

		$html = '<div class="topInfo editlinktip hasTip" title="' . $title . '" onclick="ACommon.info(this)">' . PHP_EOL;
		$html .= '  <span>' . $header . '</span>' . PHP_EOL;
		$html .= '  <p style="display: none">' . $text . '</p>' . PHP_EOL;
		$html .= '  <div class="clr"></div>' . PHP_EOL;
		$html .= '</div>' . PHP_EOL;

		return $html;
	}

	/**
	 * Get months select for quick navigator.
	 *
	 * @param string $name name of HTML select box
	 * @param int $selectedMonth selected month from user request
	 * @param int $selectedYear selected year from user request
	 * @param int $month current month
	 * @param int $year current year
	 * @param int $deep set calendar available deepth
	 * @param string $attribs custom HTML tag params
	 * @return string HTML
	 */
	function getMonthsSelect($name, $selectedMonth, $selectedYear, $month, $year, $deep, $attribs = '')
	{
		$months = array(1 => JText::_('January') , 2 => JText::_('February') , 3 => JText::_('March') , 4 => JText::_('April') , 5 => JText::_('May') , 6 => JText::_('June') , 7 => JText::_('July') , 8 => JText::_('August') , 9 => JText::_('September') , 10 => JText::_('October') , 11 => JText::_('November') , 12 => JText::_('December'));

		$stop = $month + $deep;
		for ($i = $month; $i < $stop; $i ++)
			$arr[] = JHTML::_('select.option', ($key = (! ($k = $i % 12) ? 12 : $k)) . ',' . ($y = (floor(($i - 1) / 12) + $year)), ($months[$key] . '/' . $y));

		return JHTML::_('select.genericlist', $arr, $name, $attribs, 'value', 'text', $selectedMonth . ',' . $selectedYear);
	}

	/**
	 * Get week select for quick navigator.
	 *
	 * @param string $name name of HTML select box
	 * @param int $selectedWeek selected week from user request
	 * @param int $selectedYear selected year from user request
	 * @param int $week current week
	 * @param int $year current year
	 * @param int $deep set calendar available deepth
	 * @param string $attribs custom HTML tag params
	 * @return string HTML
	 */
	function getWeekSelect($name, $selectedWeek, $selectedYear, $week, $year, $deep, $attribs)
	{
		$stop = $week + $deep;
		for ($i = $week; $i < $stop; $i ++)
			$arr[] = JHTML::_('select.option', ($key = (! ($k = $i % 54) ? 54 : $k)) . ',' . ($y = (floor(($i - $week) / 54) + $year)), ($key . '/' . $y));

		return JHTML::_('select.genericlist', $arr, $name, $attribs, 'value', 'text', $selectedWeek . ',' . $selectedYear);
	}

	/**
	 * Set calendar deeoth limit for using in javascript.
	 *
	 * @param int $deep set calendar available deepth
	 */
	function setCalendarLimit($deep)
	{
		ADocument::addDomreadyEvent('Calendars.dateBegin = ' . date('Ymd', strtotime(date('Y-m-d'))) . ';' . PHP_EOL . 'Calendars.dateEnd = ' . date('Ymd', strtotime('+' . $deep . ' days')) . ';');
	}

	/**
	 * Convert absolute path to real path from Joomla installation root.
	 *
	 * @param string $abs
	 * @return string
	 */
	function abs2real($abs)
	{
		return JURI::root() . JPath::clean(str_replace(JPATH_ROOT . DS, '', $abs));
	}

	/**
	 * Display label with compulsory sign and set javascript property with information about field is compulsory.
	 *
	 * @param JDocument $document
	 * @param AConfig $config
	 * @param string $field
	 * @param string $label
	 * @return string
	 */
	function displayLabel(&$document, &$config, $configField, $field, $label)
	{
		static $id;
		if (is_null($id))
			$id = 0;
		if (($isCompulsory = $config->$configField == RS_COMPULSORY))
			$document->addScriptDeclaration('rfields[' . $id ++ . '] = {name: "' . $field . '", msg: "' . JText::_('Add ' . $label, true) . '"}' . PHP_EOL);
		return '<label for="' . $field . '"' . ($isCompulsory = $config->$configField == RS_COMPULSORY ? ' class="compulsory"' : '') . '>' . JText::_($label) . ': </label>';
	}

	/**
	 * Get payment method select dialog
	 *
	 * @param array $payments
	 * @param TableReservation $reservation
	 */
	function getPaymentMethodSelect(&$payments, &$reservation)
	{
		$options[] = JHTML::_('select.option', 0, JText::_('- unselect -'), 'alias', 'title');
		$options = array_merge($options, $this->payments);
		$code = JHTML::_('select.genericlist', $options, 'payment_method_id', 'onchange="var p = this.form.payment_method_name; if(this.value == \'0\') p.value = \'\'; else p.value = this.options[this.selectedIndex].innerHTML;"', 'alias', 'title', $reservation->payment_method_id);
		$code .= '<input type="hidden" name="payment_method_name" id="payment_method_name" value="' . $reservation->payment_method_name . '" />';
		return $code;
	}

	/**
	 * Return all modules on given template position.
	 *
	 * @param string $positions positions names
	 * @return string HTML code of rendered modules
	 */
	function renderModules($positions)
	{
		$document = &JFactory::getDocument();
		/* @var $document JDocument */
		$renderer = &$document->loadRenderer('module');
		/* @var $renderer JDocumentRendererModule */
		$code = '';
		foreach (func_get_args() as $position)
			foreach (JModuleHelper::getModules($position) as $module)
			$code .= $renderer->render($module);
		return $code;
	}

	/**
	 * Render Joomla toolbar box in standard template format.
	 *
	 * @return string HTML code of complete toolbar box
	 */
	function renderToolbarBox()
	{
		$code = '<div id="toolbar-box">';
		$code .= '<div class="t"><div class="t"><div class="t"></div></div></div>';
		$code .= '<div class="m">' . AHtmlFrontEnd::renderModules('toolbar', 'title') . '<div class="clr"></div></div>';
		$code .= '<div class="b"><div class="b"><div class="b"></div></div></div>';
		$code .= '</div><div class="clr"></div>';
		return $code;
	}

	/**
	 * Display reservation interval.
	 *
	 * @param TableReservation $reservation
	 */
	function interval(&$reservation, $offset = null)
	{
		if ($reservation->rtype == RESERVATION_TYPE_DAILY) {
			if (AHtmlFrontEnd::date($reservation->from, ADATE_FORMAT_MYSQL_TIME, $offset) == '00:00:00' && AHtmlFrontEnd::date($t = $reservation->to, ADATE_FORMAT_MYSQL_TIME, $offset) == '23:59:00') {
				if (AHtmlFrontEnd::date($reservation->from, ADATE_FORMAT_NORMAL, $offset) == AHtmlFrontEnd::date($reservation->to, ADATE_FORMAT_NORMAL, $offset))
					return JText::sprintf('Interval date', AHtmlFrontEnd::date($reservation->from, ADATE_FORMAT_NORMAL, $offset));
				else
					return JText::sprintf('Interval from to', AHtmlFrontEnd::date($reservation->from, ADATE_FORMAT_NORMAL, $offset), AHtmlFrontEnd::date($reservation->to, ADATE_FORMAT_NORMAL, $offset));
			} else
				return JText::sprintf('Interval from to time up down', AHtmlFrontEnd::date($reservation->from, ADATE_FORMAT_NORMAL, $offset), AHtmlFrontEnd::date($reservation->from, ATIME_FORMAT_SHORT, $offset), AHtmlFrontEnd::date($reservation->to, ADATE_FORMAT_NORMAL, $offset), AHtmlFrontEnd::date($reservation->to, ATIME_FORMAT_SHORT, $offset));
		}
		return JText::sprintf('Interval date time up down', AHtmlFrontEnd::date($reservation->from, ADATE_FORMAT_NORMAL, $offset), AHtmlFrontEnd::date($reservation->from, ATIME_FORMAT_SHORT, $offset), AHtmlFrontEnd::date($reservation->to, ATIME_FORMAT_SHORT, $offset));
	}

	/**
	 * Convert format string for strftime method to date method.
	 *
	 * @param  string format string for strftime
	 * @return string format string for date
	 */
	static function  strftime2date($format)
	{
		return str_replace(array('e' , 'M' , 'C' , '%' , 'b' , 'a'), array('j' , 'i' , 's' , '' , 'M' , 'D'), $format);
	}

	/**
	 * Creates a tooltip with an image as button.
	 *
	 * @param	string	$tooltip The tip string
	 * @param	string	$title The title of the tooltip
	 * @param	string	$image The image for the tip, if no text is provided
	 * @param	string	$text The text for the tip
	 * @param	string	$href An URL that will be used to create the link
	 * @return	string  HTML code
	 */
	function tooltip($tooltip = '', $title = '', $image = 'tooltip.png', $text = '', $href = '')
	{
		$tooltip = addslashes(htmlspecialchars(JString::trim($tooltip), ENT_QUOTES, 'UTF-8'));
		$title = addslashes(htmlspecialchars(JString::trim($title), ENT_QUOTES, 'UTF-8'));
		$text = ($text = JString::trim($text)) ? JText::_($text, true) : '<img src="' . IMAGES . $image . '" border="0" alt="' . addslashes(htmlspecialchars(JText::_('Tooltip'), ENT_QUOTES, 'UTF-8')) . '"/>';
		$title = $title . (($title && $tooltip) ? '::' : '') . $tooltip;
		if ($href)
			return '<span class="editlinktip hasTip" title="' . $title . '"><a href="' . JRoute::_($href) . '" title="">' . $text . '</a></span>';
		else
			return '<span class="editlinktip hasTip" title="' . $title . '">' . $text . '</span>';
	}

	/**
	 * Set webpage metadata. Title, keywords and description.
	 *
	 * @param stdClass $object object containing parameters title,keywords and description
	 * @return void
	 */
	function setMetaData(&$object)
	{
		$document = &JFactory::getDocument();
		/* @var $document JDocument */
		$mainframe = &JFactory::getApplication();
		/* @var $mainframe JApplication */
		$document->setTitle($object->title . ' - ' . $mainframe->getCfg('sitename'));
		if (($keywords = JString::trim($object->keywords)))
			$document->setMetaData('keywords', $keywords);
		if (($description = JString::trim($object->description)))
			$document->setDescription($description);
	}
	function getSupportedTimeFormats() {

		// Describe the formats.
		$strftimeFormats = array(
				'A' => 'A full textual representation of the day',
				'B' => 'Full month name, based on the locale',
				'C' => 'Two digit representation of the century (year divided by 100, truncated to an integer)',
				'D' => 'Same as "%m/%d/%y"',
				'E' => '',
				'F' => 'Same as "%Y-%m-%d"',
				'G' => 'The full four-digit version of %g',
				'H' => 'Two digit representation of the hour in 24-hour format',
				'I' => 'Two digit representation of the hour in 12-hour format',
				'J' => '',
				'K' => '',
				'L' => '',
				'M' => 'Two digit representation of the minute',
				'N' => '',
				'O' => '',
				'P' => 'lower-case "am" or "pm" based on the given time',
				'Q' => '',
				'R' => 'Same as "%H:%M"',
				'S' => 'Two digit representation of the second',
				'T' => 'Same as "%H:%M:%S"',
				'U' => 'Week number of the given year, starting with the first Sunday as the first week',
				'V' => 'ISO-8601:1988 week number of the given year, starting with the first week of the year with at least 4 weekdays, with Monday being the start of the week',
				'W' => 'A numeric representation of the week of the year, starting with the first Monday as the first week',
				'X' => 'Preferred time representation based on locale, without the date',
				'Y' => 'Four digit representation for the year',
				'Z' => 'The time zone offset/abbreviation option NOT given by %z (depends on operating system)',
				'a' => 'An abbreviated textual representation of the day',
				'b' => 'Abbreviated month name, based on the locale',
				'c' => 'Preferred date and time stamp based on local',
				'd' => 'Two-digit day of the month (with leading zeros)',
				'e' => 'Day of the month, with a space preceding single digits',
				'f' => '',
				'g' => 'Two digit representation of the year going by ISO-8601:1988 standards (see %V)',
				'h' => 'Abbreviated month name, based on the locale (an alias of %b)',
				'i' => '',
				'j' => 'Day of the year, 3 digits with leading zeros',
				'k' => '',
				'l' => 'Hour in 12-hour format, with a space preceeding single digits',
				'm' => 'Two digit representation of the month',
				'n' => 'A newline character ("\n")',
				'o' => '',
				'p' => 'UPPER-CASE "AM" or "PM" based on the given time',
				'q' => '',
				'r' => 'Same as "%I:%M:%S %p"',
				's' => 'Unix Epoch Time timestamp',
				't' => 'A Tab character ("\t")',
				'u' => 'ISO-8601 numeric representation of the day of the week',
				'v' => '',
				'w' => 'Numeric representation of the day of the week',
				'x' => 'Preferred date representation based on locale, without the time',
				'y' => 'Two digit representation of the year',
				'z' => 'Either the time zone offset from UTC or the abbreviation (depends on operating system)',
				'%' => 'A literal percentage character ("%")',
		);

		// Results.
		$strftimeValues = array();

		// Evaluate the formats whilst suppressing any errors.
		foreach($strftimeFormats as $format => $description){
			if (False !== ($value = @strftime("%{$format}"))){
				$strftimeValues[$format] = $value;
			}
		}

		// Find the longest value.
		$maxValueLength = 2 + max(array_map('strlen', $strftimeValues));

		$return = '';

		// Report known formats.
		foreach($strftimeValues as $format => $value){
			$return.= "Known format   : '{$format}' = ". str_pad("'{$value}'", $maxValueLength). " ( {$strftimeFormats[$format]} )<br>\n";
		}

		// Report unknown formats.
		foreach(array_diff_key($strftimeFormats, $strftimeValues) as $format => $description){
			$return.= "Unknown format : '{$format}'   ". str_pad(' ', $maxValueLength). ($description ? " ( {$description} )" : ''). "<br>\n";
		}

		return $return;
	}
	static function displayMap($obj_id){
		$link=JURI::base()."index.php?option=com_bookpro&task=displaymap&tmpl=component&dest_id=".$obj_id;
		$modallink=JHtml::link($link, JText::_("COM_BOOKPRO_VIEW_MAP"),array('class'=>'modal','rel'=>"{handler: 'iframe', size: {x: 600, y: 530}}"));
		return $modallink;
	}

}

?>