 <?php
 /** 
 * @package %PACKAGE%
 * @subpackage %FIELD.SUBPACKAGE%
 * @license GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

class SocialDateElapsed_fi_FI extends SocialDateElapsed
{
	public $prefixAgo = "";

	public $prefixFromNow = "";

	public $suffixAgo = "sitten";

	public $suffixFromNow = "tulevaisuudessa";

	public function seconds() {
		return "alle minuutti";
	}

	public function minute() {
		return "minuutti";
	}

	public function minutes() {
		return "%d minuuttia";
	}

	public function hour() {
		return "tunti";
	}

	public function hours() {
		return "%d tuntia";
	}

	public function day() {
		return "päivä";
	}

	public function days() {
		return "%d päivää";
	}

	public function month() {
		return "kuukausi";
	}

	public function months() {
		return "%d kuukautta";
	}

	public function year() {
		return "vuosi";
	}

	public function years() {
		return "%d vuotta";
	}

}