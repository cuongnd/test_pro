 <?php
 /** 
 * @package %PACKAGE%
 * @subpackage %FIELD.SUBPACKAGE%
 * @license GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

class SocialDateElapsed_pt_PT extends SocialDateElapsed
{
	public $suffixAgo = "atrás";

	public $suffixFromNow = "a partir de agora";

	public function seconds() {
		return "menos de um minuto";
	}

	public function minute() {
		return "cerca de um minuto";
	}

	public function minutes() {
		return "%d minutos";
	}

	public function hour() {
		return "cerca de uma hora";
	}

	public function hours() {
		return "cerca de %d horas";
	}

	public function day() {
		return "um dia";
	}

	public function days() {
		return "%d dias";
	}

	public function month() {
		return "cerca de um mês";
	}

	public function months() {
		return "%d meses";
	}

	public function year() {
		return "cerca de um ano";
	}

	public function years() {
		return "%d anos";
	}

}