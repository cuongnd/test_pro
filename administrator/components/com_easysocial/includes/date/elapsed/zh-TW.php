 <?php
 /** 
 * @package %PACKAGE%
 * @subpackage %FIELD.SUBPACKAGE%
 * @license GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

class SocialDateElapsed_zh_TW extends SocialDateElapsed
{
	public $prefixAgo = "";

	public $prefixFromNow = "從現在開始";

	public $suffixAgo = "之前";

	public $suffixFromNow = "";

	public function seconds() {
		return "不到 1 分鐘";
	}

	public function minute() {
		return "大約 1 分鐘";
	}

	public function minutes() {
		return "%d 分鐘";
	}

	public function hour() {
		return "大約 1 小時";
	}

	public function hours() {
		return "大約 %d 小時";
	}

	public function day() {
		return "1 天";
	}

	public function days() {
		return "%d 天";
	}

	public function month() {
		return "大約 1 個月";
	}

	public function months() {
		return "%d 月";
	}

	public function year() {
		return "大約 1 年";
	}

	public function years() {
		return "%d 年";
	}

	public function wordSeparator() {
		return "";
	}

}