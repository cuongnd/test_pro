 <?php
 /**
 * @package %PACKAGE%
 * @subpackage %FIELD.SUBPACKAGE%
 * @license GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

class SocialDateElapsed_ar_AA extends SocialDateElapsed
{
	public $prefixAgo = "منذ";

	public $prefixFromNow = "يتبقى";

	public $suffixAgo = "";

	public $suffixFromNow = "";

	public function numpf($num, $w, $x, $y, $z) {

		if ($num==0) {
			return $w;
		} elseif ($num == 2) {
			return $x;
		} elseif ($num >= 3 && $num <= 10) {
			return $y;
		} else {
			return $z;
		}
	}

	public function seconds($value) {
		return $this->numpf($value, "لحظات", "ثانيتين", "%d ثواني", "%d ثانيه");
	}

	public function minute() {
		return "دقيقة";
	}

	public function minutes($value) {
		return $this->numpf($value, "", "دقيقتين", "%d دقائق", "%d دقيقة");
	}

	public function hour() {
		return "ساعة";
	}

	public function hours($value) {
		return $this->numpf($value, "", "ساعتين", "%d ساعات", "%d ساعة");
	}

	public function day() {
		return "يوم";
	}

	public function days($value) {
		return $this->numpf($value, "", "يومين", "%d أيام", "%d يوم");
	}

	public function month() {
		return "شهر";
	}

	public function months($value) {
		return $this->numpf($value, "", "شهرين", "%d أشهر", "%d شهر");
	}

	public function year() {
		return "سنه";
	}

	public function years() {
		return "%d blynedd";
	}
}
