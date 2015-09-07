 <?php
 /** 
 * @package %PACKAGE%
 * @subpackage %FIELD.SUBPACKAGE%
 * @license GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

class SocialDateElapsed_th_TH extends SocialDateElapsed
{
	public $prefixAgo = "";

	public $prefixFromNow = "";

	public $suffixAgo = "ที่แล้ว";

	public $suffixFromNow = "จากตอนนี้";

	public function seconds() {
		return "น้อยกว่าหนึ่งนาที";
	}

	public function minute() {
		return "ประมาณหนึ่งนาที";
	}

	public function minutes() {
		return "%d นาที";
	}

	public function hour() {
		return "ประมาณหนึ่งชั่วโมง";
	}

	public function hours() {
		return "ประมาณ %d ชั่วโมง";
	}

	public function day() {
		return "หนึ่งวัน";
	}

	public function days() {
		return "%d วัน";
	}

	public function month() {
		return "ประมาณหนึ่งเดือน";
	}

	public function months() {
		return "%d เดือน";
	}

	public function year() {
		return "ประมาณหนึ่งปี";
	}

	public function years() {
		return "%d ปี";
	}

	public function wordSeparator() {
		return "";
	}

}