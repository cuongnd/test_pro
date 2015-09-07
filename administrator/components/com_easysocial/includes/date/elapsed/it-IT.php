 <?php
 /** 
 * @package %PACKAGE%
 * @subpackage %FIELD.SUBPACKAGE%
 * @license GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

class SocialDateElapsed_it_IT extends SocialDateElapsed
{
	public $suffixAgo = "fa";

	public $suffixFromNow = "da ora";

	public function seconds() {
		return "meno di un minuto";
	}

	public function minute() {
		return "circa un minuto";
	}

	public function minutes() {
		return "%d minuti";
	}

	public function hour() {
		return "circa un'ora";
	}

	public function hours() {
		return "circa %d ore";
	}

	public function day() {
		return "un giorno";
	}

	public function days() {
		return "%d giorni";
	}

	public function month() {
		return "circa un mese";
	}

	public function months() {
		return "%d mesi";
	}

	public function year() {
		return "circa un anno";
	}

	public function years() {
		return "%d anni";
	}

}