 <?php
 /** 
 * @package %PACKAGE%
 * @subpackage %FIELD.SUBPACKAGE%
 * @license GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

class SocialDateElapsed_cy_GB extends SocialDateElapsed
{
	public $prefixAgo = "";

	public $prefixFromNow = "";

	public $suffixAgo = "yn ôl";

	public $suffixFromNow = "o hyn";

	public function seconds() {
		return "llai na munud";
	}

	public function minute() {
		return "am funud";
	}

	public function minutes() {
		return "%d munud";
	}

	public function hour() {
		return "tua awr";
	}

	public function hours() {
		return "am %d awr";
	}

	public function day() {
		return "y dydd";
	}

	public function days() {
		return "%d diwrnod";
	}

	public function month() {
		return "tua mis";
	}

	public function months() {
		return "%d mis";
	}

	public function year() {
		return "am y flwyddyn";
	}

	public function years() {
		return "%d blynedd";
	}

	public function wordSeparator() {
		return " ";
	}

}