 <?php
 /** 
 * @package %PACKAGE%
 * @subpackage %FIELD.SUBPACKAGE%
 * @license GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

class SocialDateElapsed_el_GR extends SocialDateElapsed
{
	public $prefixAgo = "πριν";

	public $prefixFromNow = "σε";

	public $suffixAgo = "";

	public $suffixFromNow = "";

	public function seconds() {
		return "λιγότερο από ένα λεπτό";
	}

	public function minute() {
		return "περίπου ένα λεπτό";
	}

	public function minutes() {
		return "%d λεπτά";
	}

	public function hour() {
		return "περίπου μία ώρα";
	}

	public function hours() {
		return "περίπου %d ώρες";
	}

	public function day() {
		return "μία μέρα";
	}

	public function days() {
		return "%d μέρες";
	}

	public function month() {
		return "περίπου ένα μήνα";
	}

	public function months() {
		return "%d μήνες";
	}

	public function year() {
		return "περίπου ένα χρόνο";
	}

	public function years() {
		return "%d χρόνια";
	}

}