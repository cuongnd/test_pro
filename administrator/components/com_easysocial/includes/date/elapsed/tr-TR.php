 <?php
 /** 
 * @package %PACKAGE%
 * @subpackage %FIELD.SUBPACKAGE%
 * @license GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

class SocialDateElapsed_tr_TR extends SocialDateElapsed
{
	public $suffixAgo = "önce";

	public $suffixFromNow = "";

	public function seconds() {
		return "1 dakikadan";
	}

	public function minute() {
		return "1 dakika";
	}

	public function minutes() {
		return "%d dakika";
	}

	public function hour() {
		return "1 saat";
	}

	public function hours() {
		return "%d saat";
	}

	public function day() {
		return "1 gün";
	}

	public function days() {
		return "%d gün";
	}

	public function month() {
		return "1 ay";
	}

	public function months() {
		return "%d ay";
	}

	public function year() {
		return "1 yıl";
	}

	public function years() {
		return "%d yıl";
	}

}