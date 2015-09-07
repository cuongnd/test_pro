 <?php
 /** 
 * @package %PACKAGE%
 * @subpackage %FIELD.SUBPACKAGE%
 * @license GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

class SocialDateElapsed_pt_BR extends SocialDateElapsed
{
	public $suffixAgo = "atrás";

	public $suffixFromNow = "nesse momento";

	public function seconds() {
		return "alguns segundos";
	}

	public function minute() {
		return "há um minuto";
	}

	public function minutes() {
		return "há %d minutos";
	}

	public function hour() {
		return "há uma hora";
	}

	public function hours() {
		return "há %d horas";
	}

	public function day() {
		return "há um dia";
	}

	public function days() {
		return "há %d dias";
	}

	public function month() {
		return "há um mês";
	}

	public function months() {
		return "há %d meses";
	}

	public function year() {
		return "há um ano";
	}

	public function years() {
		return "há %d anos";
	}

}