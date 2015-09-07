 <?php
 /** 
 * @package %PACKAGE%
 * @subpackage %FIELD.SUBPACKAGE%
 * @license GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

class SocialDateElapsed_mk_MK extends SocialDateElapsed
{
	public $prefixAgo = "пред";

	public $prefixFromNow = "за";

	public $suffixAgo = "";

	public $suffixFromNow = "";

	public function seconds() {
		return "%d секунди";
	}

	public function minute() {
		return "%d минута";
	}

	public function minutes() {
		return "%d минути";
	}

	public function hour() {
		return "%d час";
	}

	public function hours() {
		return "%d часа";
	}

	public function day() {
		return "%d ден";
	}

	public function days() {
		return "%d денови";
	}

	public function month() {
		return "%d месец";
	}

	public function months() {
		return "%d месеци";
	}

	public function year() {
		return "%d година";
	}

	public function years() {
		return "%d години";
	}

}