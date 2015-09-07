 <?php
 /**
 * @package %PACKAGE%
 * @subpackage %FIELD.SUBPACKAGE%
 * @license GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

class SocialDateElapsed_hr_HR extends SocialDateElapsed
{
	public $prefixAgo = "prije";

	public $prefixFromNow = "za";

	public $suffixAgo = "";

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

	public function second() {
		return "sekundu";
	}

	public function seconds($value) {
		return $this->numpf($value, "%d sekundu", "%d sekunde", "%d sekundi");
	}

	public function minute() {
		return "oko minutu";
	}

	public function minutes($value) {
		return $this->numpf($value, "%d minutu", "%d minute", "%d minuta");
	}

	public function hour() {
		return "oko jedan sat";
	}

	public function hours($value) {
		return $this->numpf($value, "%d sat", "%d sata", "%d sati");
	}

	public function day() {
		return "jedan dan";
	}

	public function days($value) {
		return $this->numpf($value, "%d dan", "%d dana", "%d dana");
	}

	public function month() {
		return "mjesec dana";
	}

	public function months($value) {
		return $this->numpf($value, "%d mjesec", "%d mjeseca", "%d mjeseci");
	}

	public function year() {
		return "prije godinu dana";
	}

	public function years() {
		return $this->numpf($value, "%d godinu", "%d godine", "%d godina");
	}
}
