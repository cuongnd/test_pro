 <?php
 /** 
 * @package %PACKAGE%
 * @subpackage %FIELD.SUBPACKAGE%
 * @license GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

class SocialDateElapsed_de_DE extends SocialDateElapsed
{
	public $prefixAgo = "vor";

	public $prefixFromNow = "in";

	public $suffixAgo = "";

	public $suffixFromNow = "";

	public function seconds() {
		return "wenigen Sekunden";
	}

	public function minute() {
		return "etwa einer Minute";
	}

	public function minutes() {
		return "%d Minuten";
	}

	public function hour() {
		return "etwa einer Stunde";
	}

	public function hours() {
		return "%d Stunden";
	}

	public function day() {
		return "etwa einem Tag";
	}

	public function days() {
		return "%d Tagen";
	}

	public function month() {
		return "etwa einem Monat";
	}

	public function months() {
		return "%d Monaten";
	}

	public function year() {
		return "etwa einem Jahr";
	}

	public function years() {
		return "%d Jahren";
	}

}