 <?php
 /** 
 * @package %PACKAGE%
 * @subpackage %FIELD.SUBPACKAGE%
 * @license GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

class SocialDateElapsed_nl_NL extends SocialDateElapsed
{
	public $prefixAgo = "";

	public $prefixFromNow = "";

	public $suffixAgo = "geleden";

	public $suffixFromNow = "van nu";

	public function seconds() {
		return "minder dan een minuut";
	}

	public function minute() {
		return "ongeveer een minuut";
	}

	public function minutes() {
		return "%d minuten";
	}

	public function hour() {
		return "ongeveer een uur";
	}

	public function hours() {
		return "ongeveer %d uur";
	}

	public function day() {
		return "een dag";
	}

	public function days() {
		return "%d dagen";
	}

	public function month() {
		return "ongeveer een maand";
	}

	public function months() {
		return "%d maanden";
	}

	public function year() {
		return "ongeveer een jaar";
	}

	public function years() {
		return "%d jaar";
	}

	public function wordSeparator() {
		return " ";
	}

}