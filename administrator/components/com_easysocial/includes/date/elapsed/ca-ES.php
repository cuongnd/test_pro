 <?php
 /** 
 * @package %PACKAGE%
 * @subpackage %FIELD.SUBPACKAGE%
 * @license GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

class SocialDateElapsed_ca_ES extends SocialDateElapsed
{
	public $prefixAgo = "fa";

	public $prefixFromNow = "d'aqui a";

	public $suffixAgo = "";

	public $suffixFromNow = "";

	public function seconds() {
		return "menys d'1 minut";
	}

	public function minute() {
		return "1 minut";
	}

	public function minutes() {
		return "uns %d minuts";
	}

	public function hour() {
		return "1 hora";
	}

	public function hours() {
		return "unes %d hores";
	}

	public function day() {
		return "1 dia";
	}

	public function days() {
		return "%d dies";
	}

	public function month() {
		return "aproximadament un mes";
	}

	public function months() {
		return "%d mesos";
	}

	public function year() {
		return "aproximadament un any";
	}

	public function years() {
		return "%d anys";
	}

}