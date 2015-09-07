 <?php
 /**
 * @package %PACKAGE%
 * @subpackage %FIELD.SUBPACKAGE%
 * @license GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

class SocialDateElapsed_pl_PL extends SocialDateElapsed
{
	public $prefixAgo = "";

	public $prefixFromNow = "za";

	public $suffixAgo = "temu";

	public $suffixFromNow = "";

	public function numpf($n, $s, $t) {

		$n10 = $n % 10;

		if ( ($n10 > 1) && ($n10 < 5) && (($n < 20 || $n < 10)) ) {
			return $s;
		} else {
			return $t;
		}
	}

	public function seconds($value) {
		return "mniej niż minutę";
	}

	public function minute() {
		return "minutę";
	}

	public function minutes($value) {
		return $this->numpf($value, "%d minuty", "%d minut");
	}

	public function hour() {
		return "godzinę";
	}

	public function hours($value) {
		return $this->numpf($value, "%d godziny", "%d godzin");
	}

	public function day() {
		return "dzień";
	}

	public function days($value) {
		return "%d dni";
	}

	public function month() {
		return "miesiąc";
	}

	public function months($value) {
		return $this->numpf($value, "%d miesiące", "%d miesięcy");
	}

	public function year() {
		return "rok";
	}

	public function years() {
		return $this->numpf($value, "%d lata", "%d lat");
	}
}
