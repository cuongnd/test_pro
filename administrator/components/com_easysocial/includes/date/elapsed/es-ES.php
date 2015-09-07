 <?php
 /** 
 * @package %PACKAGE%
 * @subpackage %FIELD.SUBPACKAGE%
 * @license GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

class SocialDateElapsed_es_ES extends SocialDateElapsed
{
	public $prefixAgo = "hace";

	public $prefixFromNow = "dentro de";

	public $suffixAgo = "";

	public $suffixFromNow = "";

	public function seconds() {
		return "menos de un minuto";
	}

	public function minute() {
		return "un minuto";
	}

	public function minutes() {
		return "unos %d minutos";
	}

	public function hour() {
		return "una hora";
	}

	public function hours() {
		return "%d horas";
	}

	public function day() {
		return "un día";
	}

	public function days() {
		return "%d días";
	}

	public function month() {
		return "un mes";
	}

	public function months() {
		return "%d meses";
	}

	public function year() {
		return "un año";
	}

	public function years() {
		return "%d años";
	}

}