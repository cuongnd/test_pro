 <?php
 /** 
 * @package %PACKAGE%
 * @subpackage %FIELD.SUBPACKAGE%
 * @license GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

class SocialDateElapsed_hu_HU extends SocialDateElapsed
{
	public $prefixAgo = "";

	public $prefixFromNow = "";

	public $suffixAgo = "";

	public $suffixFromNow = "";

	public function seconds() {
		return "kevesebb mint egy perce";
	}

	public function minute() {
		return "körülbelül egy perce";
	}

	public function minutes() {
		return "%d perce";
	}

	public function hour() {
		return "körülbelül egy órája";
	}

	public function hours() {
		return "körülbelül %d órája";
	}

	public function day() {
		return "körülbelül egy napja";
	}

	public function days() {
		return "%d napja";
	}

	public function month() {
		return "körülbelül egy hónapja";
	}

	public function months() {
		return "%d hónapja";
	}

	public function year() {
		return "körülbelül egy éve";
	}

	public function years() {
		return "%d éve";
	}

}