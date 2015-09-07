 <?php
 /** 
 * @package %PACKAGE%
 * @subpackage %FIELD.SUBPACKAGE%
 * @license GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

class SocialDateElapsed_ro_RO extends SocialDateElapsed
{
	public $prefixAgo = "acum";

	public $prefixFromNow = "in timp de";

	public $suffixAgo = "";

	public $suffixFromNow = "";

	public function seconds() {
		return "mai putin de un minut";
	}

	public function minute() {
		return "un minut";
	}

	public function minutes() {
		return "%d minute";
	}

	public function hour() {
		return "o ora";
	}

	public function hours() {
		return "%d ore";
	}

	public function day() {
		return "o zi";
	}

	public function days() {
		return "%d zile";
	}

	public function month() {
		return "o luna";
	}

	public function months() {
		return "%d luni";
	}

	public function year() {
		return "un an";
	}

	public function years() {
		return "%d ani";
	}

}