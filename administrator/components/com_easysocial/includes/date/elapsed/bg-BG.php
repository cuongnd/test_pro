 <?php
 /** 
 * @package %PACKAGE%
 * @subpackage %FIELD.SUBPACKAGE%
 * @license GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

class SocialDateElapsed_bg_BG extends SocialDateElapsed
{
	public $prefixAgo = "преди";

	public $prefixFromNow = "след";

	public $suffixAgo = "";

	public $suffixFromNow = "";

	public function seconds() {
		return "по-малко от минута";
	}

	public function minute() {
		return "една минута";
	}

	public function minutes() {
		return "%d минути";
	}

	public function hour() {
		return "един час";
	}

	public function hours() {
		return "%d часа";
	}

	public function day() {
		return "един ден";
	}

	public function days() {
		return "%d дни";
	}

	public function month() {
		return "един месец";
	}

	public function months() {
		return "%d месеца";
	}

	public function year() {
		return "една година";
	}

	public function years() {
		return "%d години";
	}

}