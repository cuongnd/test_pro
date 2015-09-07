 <?php
 /** 
 * @package %PACKAGE%
 * @subpackage %FIELD.SUBPACKAGE%
 * @license GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

class SocialDateElapsed_ja_JP extends SocialDateElapsed
{
	public $prefixAgo = "";

	public $prefixFromNow = "今から";

	public $suffixAgo = "前";

	public $suffixFromNow = "後";

	public function seconds() {
		return "ほんの数秒";
	}

	public function minute() {
		return "約一分";
	}

	public function minutes() {
		return "%d 分";
	}

	public function hour() {
		return "大体一時間";
	}

	public function hours() {
		return "大体 %d 時間位";
	}

	public function day() {
		return "一日";
	}

	public function days() {
		return "%d 日ほど";
	}

	public function month() {
		return "大体一ヶ月";
	}

	public function months() {
		return "%d ヶ月ほど";
	}

	public function year() {
		return "1年前";
	}

	public function years() {
		return "%d 年";
	}

	public function wordSeparator() {
		return "";
	}

}