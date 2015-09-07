 <?php
 /** 
 * @package %PACKAGE%
 * @subpackage %FIELD.SUBPACKAGE%
 * @license GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

class SocialDateElapsed_cs_CZ extends SocialDateElapsed
{
	public $prefixAgo = "před";

	public $prefixFromNow = "";

	public $suffixAgo = "";

	public $suffixFromNow = "";

	public function seconds() {
		return "méně než minutou";
	}

	public function minute() {
		return "minutou";
	}

	public function minutes() {
		return "%d minutami";
	}

	public function hour() {
		return "hodinou";
	}

	public function hours() {
		return "%d hodinami";
	}

	public function day() {
		return "1 dnem";
	}

	public function days() {
		return "%d dny";
	}

	public function month() {
		return "1 měsícem";
	}

	public function months() {
		return "%d měsíci";
	}

	public function year() {
		return "1 rokem";
	}

	public function years() {
		return "%d roky";
	}

}