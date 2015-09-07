 <?php
 /**
 * @package %PACKAGE%
 * @subpackage %FIELD.SUBPACKAGE%
 * @license GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

class SocialDateElapsed_uk_UA extends SocialDateElapsed
{
	public $prefixAgo = "";

	public $prefixFromNow = "через";

	public $suffixAgo = "тому";

	public $suffixFromNow = "";

	public function numpf($n, $f, $s, $t) {

		$n10 = $n % 10;

		if ($n10 == 1 && ($n == 1 || $n > 20)) {
			return $f;
		} else if ($n10 > 1 && $n10 < 5 && ($n < 20 || $n < 10)) {
			return $s;
		} else {
			return $t;
		}
	}

	public function seconds($value) {
		return "менше хвилини";
	}

	public function minute() {
		return "хвилина";
	}

	public function minutes($value) {
		return $this->numpf($value, "%d хвилина", "%d хвилини", "%d хвилин");
	}

	public function hour() {
		return "година";
	}

	public function hours($value) {
		return $this->numpf($value, "%d година", "%d години", "%d годин");
	}

	public function day() {
		return "день";
	}

	public function days($value) {
		return $this->numpf($value, "%d день", "%d дні", "%d днів");
	}

	public function month() {
		return "місяць";
	}

	public function months($value) {
		return $this->numpf($value, "%d місяць", "%d місяці", "%d місяців");
	}

	public function year() {
		return "рік";
	}

	public function years() {
		return $this->numpf($value, "%d рік", "%d роки", "%d років");
	}
}
