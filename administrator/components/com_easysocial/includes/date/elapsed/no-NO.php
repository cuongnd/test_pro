 <?php
 /** 
 * @package %PACKAGE%
 * @subpackage %FIELD.SUBPACKAGE%
 * @license GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

class SocialDateElapsed_no_NO extends SocialDateElapsed
{
	public $prefixAgo = "for";

	public $prefixFromNow = "om";

	public $suffixAgo = "siden";

	public $suffixFromNow = "";

	public function seconds() {
		return "mindre enn et minutt";
	}

	public function minute() {
		return "ca. et minutt";
	}

	public function minutes() {
		return "%d minutter";
	}

	public function hour() {
		return "ca. en time";
	}

	public function hours() {
		return "ca. %d timer";
	}

	public function day() {
		return "en dag";
	}

	public function days() {
		return "%d dager";
	}

	public function month() {
		return "ca. en måned";
	}

	public function months() {
		return "%d måneder";
	}

	public function year() {
		return "ca. et år";
	}

	public function years() {
		return "%d år";
	}

}