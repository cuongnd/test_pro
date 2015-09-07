 <?php
 /** 
 * @package %PACKAGE%
 * @subpackage %FIELD.SUBPACKAGE%
 * @license GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

class SocialDateElapsed_id_ID extends SocialDateElapsed
{
	public $prefixAgo = "";

	public $prefixFromNow = "";

	public $suffixAgo = "yang lalu";

	public $suffixFromNow = "dari sekarang";

	public function seconds() {
		return "kurang dari semenit";
	}

	public function minute() {
		return "sekitar satu menit";
	}

	public function minutes() {
		return "%d menit";
	}

	public function hour() {
		return "sekitar sejam";
	}

	public function hours() {
		return "sekitar %d jam";
	}

	public function day() {
		return "sehari";
	}

	public function days() {
		return "%d hari";
	}

	public function month() {
		return "sekitar sebulan";
	}

	public function months() {
		return "%d tahun";
	}

	public function year() {
		return "sekitar setahun";
	}

	public function years() {
		return "%d tahun";
	}

}