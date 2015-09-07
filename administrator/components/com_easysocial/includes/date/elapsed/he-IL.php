 <?php
 /** 
 * @package %PACKAGE%
 * @subpackage %FIELD.SUBPACKAGE%
 * @license GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

class SocialDateElapsed_he_IL extends SocialDateElapsed
{
	public $prefixAgo = "לפני";

	public $prefixFromNow = "מעכשיו";

	public $suffixAgo = "";

	public $suffixFromNow = "";

	public function seconds() {
		return "פחות מדקה";
	}

	public function minute() {
		return "דקה";
	}

	public function minutes() {
		return "%d דקות";
	}

	public function hour() {
		return "שעה";
	}

	public function hours() {
		return "%d שעות";
	}

	public function day() {
		return "יום";
	}

	public function days() {
		return "%d ימים";
	}

	public function month() {
		return "חודש";
	}

	public function months() {
		return "%d חודשים";
	}

	public function year() {
		return "שנה";
	}

	public function years() {
		return "%d שנים";
	}

}