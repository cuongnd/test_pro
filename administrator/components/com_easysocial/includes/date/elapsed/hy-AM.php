 <?php
 /** 
 * @package %PACKAGE%
 * @subpackage %FIELD.SUBPACKAGE%
 * @license GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

class SocialDateElapsed_hy_AM extends SocialDateElapsed
{
	public $prefixAgo = "";

	public $prefixFromNow = "";

	public $suffixAgo = "առաջ";

	public $suffixFromNow = "հետո";

	public function seconds() {
		return "վայրկյաններ";
	}

	public function minute() {
		return "մեկ րոպե";
	}

	public function minutes() {
		return "%d րոպե";
	}

	public function hour() {
		return "մեկ ժամ";
	}

	public function hours() {
		return "%d ժամ";
	}

	public function day() {
		return "մեկ օր";
	}

	public function days() {
		return "%d օր";
	}

	public function month() {
		return "մեկ ամիս";
	}

	public function months() {
		return "%d ամիս";
	}

	public function year() {
		return "մեկ տարի";
	}

	public function years() {
		return "%d տարի";
	}

}