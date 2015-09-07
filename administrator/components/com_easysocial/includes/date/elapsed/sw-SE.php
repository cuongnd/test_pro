 <?php
 /** 
 * @package %PACKAGE%
 * @subpackage %FIELD.SUBPACKAGE%
 * @license GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

class SocialDateElapsed_sw_SE extends SocialDateElapsed
{
	public $prefixAgo = "för";

	public $prefixFromNow = "om";

	public $suffixAgo = "sedan";

	public $suffixFromNow = "";

	public function seconds() {
		return "mindre än en minut";
	}

	public function minute() {
		return "ungefär en minut";
	}

	public function minutes() {
		return "%d minuter";
	}

	public function hour() {
		return "ungefär en timme";
	}

	public function hours() {
		return "ungefär %d timmar";
	}

	public function day() {
		return "en dag";
	}

	public function days() {
		return "%d dagar";
	}

	public function month() {
		return "ungefär en månad";
	}

	public function months() {
		return "%d månader";
	}

	public function year() {
		return "ungefär ett år";
	}

	public function years() {
		return "%d år";
	}

}