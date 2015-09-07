 <?php
 /** 
 * @package %PACKAGE%
 * @subpackage %FIELD.SUBPACKAGE%
 * @license GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

class SocialDateElapsed_fa_IR extends SocialDateElapsed
{
	public $prefixAgo = "";

	public $prefixFromNow = "";

	public $suffixAgo = "پیش";

	public $suffixFromNow = "از حال";

	public function seconds() {
		return "کمتر از یک دقیقه";
	}

	public function minute() {
		return "حدود یک دقیقه";
	}

	public function minutes() {
		return "%d دقیقه";
	}

	public function hour() {
		return "حدود یک ساعت";
	}

	public function hours() {
		return "حدود %d ساعت";
	}

	public function day() {
		return "یک روز";
	}

	public function days() {
		return "%d روز";
	}

	public function month() {
		return "حدود یک ماه";
	}

	public function months() {
		return "%d ماه";
	}

	public function year() {
		return "حدود یک سال";
	}

	public function years() {
		return "%d سال";
	}

	public function wordSeparator() {
		return " ";
	}

}