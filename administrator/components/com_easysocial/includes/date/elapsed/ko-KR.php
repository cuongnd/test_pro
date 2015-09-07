 <?php
 /** 
 * @package %PACKAGE%
 * @subpackage %FIELD.SUBPACKAGE%
 * @license GNU/GPL, see LICENSE.php
 */

defined('_JEXEC') or die('Restricted access');

class SocialDateElapsed_ko_KR extends SocialDateElapsed
{
	public $suffixAgo = "전";

	public $suffixFromNow = "후";

	public function seconds() {
		return "1분 이내";
	}

	public function minute() {
		return "1분";
	}

	public function minutes() {
		return "%d분";
	}

	public function hour() {
		return "1시간";
	}

	public function hours() {
		return "%d시간";
	}

	public function day() {
		return "하루";
	}

	public function days() {
		return "%d일";
	}

	public function month() {
		return "한 달";
	}

	public function months() {
		return "%d달";
	}

	public function year() {
		return "1년";
	}

	public function years() {
		return "%d년";
	}

	public function wordSeparator() {
		return " ";
	}

}