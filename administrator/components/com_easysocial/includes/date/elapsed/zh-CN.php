 <?php
 /** 
 * @package %PACKAGE%
 * @subpackage %FIELD.SUBPACKAGE%
 * @license GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

class SocialDateElapsed_zh_CN extends SocialDateElapsed
{
	public $prefixAgo = "";

	public $prefixFromNow = "从现在开始";

	public $suffixAgo = "之前";

	public $suffixFromNow = "";

	public function seconds() {
		return "不到 1 分钟";
	}

	public function minute() {
		return "大约 1 分钟";
	}

	public function minutes() {
		return "%d 分钟";
	}

	public function hour() {
		return "大约 1 小时";
	}

	public function hours() {
		return "大约 %d 小时";
	}

	public function day() {
		return "1 天";
	}

	public function days() {
		return "%d 天";
	}

	public function month() {
		return "大约 1 个月";
	}

	public function months() {
		return "%d 月";
	}

	public function year() {
		return "大约 1 年";
	}

	public function years() {
		return "%d 年";
	}

	public function wordSeparator() {
		return "";
	}

}