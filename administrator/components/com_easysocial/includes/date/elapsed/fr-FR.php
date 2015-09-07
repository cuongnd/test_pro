 <?php
 /** 
 * @package %PACKAGE%
 * @subpackage %FIELD.SUBPACKAGE%
 * @license GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

class SocialDateElapsed_fr_FR extends SocialDateElapsed
{
	public $prefixAgo = "il y a";

	public $prefixFromNow = "d'ici";

	public function seconds() {
		return "moins d'une minute";
	}

	public function minute() {
		return "environ une minute";
	}

	public function minutes() {
		return "environ %d minutes";
	}

	public function hour() {
		return "environ une heure";
	}

	public function hours() {
		return "environ %d heures";
	}

	public function day() {
		return "environ un jour";
	}

	public function days() {
		return "environ %d jours";
	}

	public function month() {
		return "environ un mois";
	}

	public function months() {
		return "environ %d mois";
	}

	public function year() {
		return "un an";
	}

	public function years() {
		return "%d ans";
	}

}