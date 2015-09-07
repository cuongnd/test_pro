 <?php
 /**
 * @package %PACKAGE%
 * @subpackage %FIELD.SUBPACKAGE%
 * @license GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

class SocialDateElapsed_ru_RU extends SocialDateElapsed
{
	public $prefixAgo = "";

	public $prefixFromNow = "через";

	public $suffixAgo = "назад";

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
		return "меньше минуты";
	}

	public function minute() {
		return "минуту";
	}

	public function minutes($value) {
		return $this->numpf($value, "%d минута", "%d минуты", "%d минут");
	}

	public function hour() {
		return "час";
	}

	public function hours($value) {
		return $this->numpf($value, "%d час", "%d часа", "%d часов");
	}

	public function day() {
		return "день";
	}

	public function days($value) {
		return $this->numpf($value, "%d день", "%d дня", "%d дней");
	}

	public function month() {
		return "месяц";
	}

	public function months($value) {
		return $this->numpf($value, "%d месяц", "%d месяца", "%d месяцев");
	}

	public function year() {
		return "год";
	}

	public function years() {
		return $this->numpf($value, "%d год", "%d года", "%d лет");
	}
}
