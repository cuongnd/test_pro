 <?php
 /** 
 * @package %PACKAGE%
 * @subpackage %FIELD.SUBPACKAGE%
 * @license GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

class SocialDateElapsed_uz_UZ extends SocialDateElapsed
{
	public $prefixAgo = "";

	public $prefixFromNow = "keyin";

	public $suffixAgo = "avval";

	public $suffixFromNow = "";

	public function seconds() {
		return "bir necha soniya";
	}

	public function minute() {
		return "1 daqiqa";
	}

	public function minutes() {
		return "function (value) { return "%d daqiqa" }";
	}

	public function hour() {
		return "1 soat";
	}

	public function hours() {
		return "function (value) { return "%d soat" }";
	}

	public function day() {
		return "1 kun";
	}

	public function days() {
		return "function (value) { return "%d kun" }";
	}

	public function month() {
		return "1 oy";
	}

	public function months() {
		return "function (value) { return "%d oy" }";
	}

	public function year() {
		return "1 yil";
	}

	public function years() {
		return "function (value) { return "%d yil" }";
	}

	public function wordSeparator() {
		return " ";
	}

}