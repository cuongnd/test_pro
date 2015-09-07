<?php
/**
 * MaQma Helpdesk Component
 * www.imaqma.com
 *
 * @package   MaQma_Helpdesk
 * @copyright (C) 2006-2012 Components Lab, Lda.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 *
 */

defined('_JEXEC') or die('Direct Access to this location is not allowed.');

// Include helpers
require_once(JPATH_SITE . '/components/com_maqmahelpdesk/helpers/feed.php');

$info = null;
$rss = null;

$showFeed = false;
$database = JFactory::getDBO();
$mainframe = JFactory::getApplication();
$nullDate = $database->getNullDate();
$supportConfig = HelpdeskUtility::GetConfig();

$now = date("d-m-Y H:i:s");
$iso = 'utf-8';

// parameter intilization
$info['date'] = date('r');
$info['year'] = date('Y');
$info['encoding'] = $iso;
$info['link'] = htmlspecialchars(JURI::root());
$info['cache'] = 1;
$info['cache_time'] = 3600;
$info['count'] = 10;
$info['orderby'] = '';
$info['title'] = JText::_('wk_kb');
$info['description'] = JText::_('wk_kb');
$info['image'] = JURI::root() . 'media/com_maqmahelpdesk/images/themes/' . $supportConfig->theme_icon . '/48px/kb.png';
$info['image_alt'] = 'Powered by MaQma Helpdesk';
$info['limit_text'] = 0;
$info['text_length'] = 20;
$info['feed'] = JRequest::getVar('feed', 'RSS2.0', 'GET', 'string');
$info['live_bookmark'] = '';
$info['bookmark_file'] = '';

// set filename for live bookmarks feed
if (!$showFeed & $info['live_bookmark']) {
	if ($info['bookmark_file']) {
		// custom bookmark filename
		$filename = $info['bookmark_file'];
	} else {
		// standard bookmark filename
		$filename = $info['live_bookmark'];
	}
} else {
	// set filename for rss feeds
	$info['file'] = JString::strtolower(str_replace('.', '', $info['feed']));
}
$filename = JPATH_SITE . '/components/com_maqmahelpdesk/feed-kb';

// load feed creator class
$rss = new UniversalFeedCreator();
// load image creator class
$image = new FeedImage();

$rss->title = $info['title'];
$rss->description = $info['description'];
$rss->link = $info['link'];
$rss->syndicationURL = $info['link'];
$rss->cssStyleSheet = NULL;
$rss->encoding = $info['encoding'];

if ($info['image']) {
	$image->url = $info['image'];
	$image->link = $info['link'];
	$image->title = $info['image_alt'];
	$image->description = $info['description'];
	// loads image info into rss array
	$rss->image = $image;
}

// Get the itemid of the component
$sql = "SELECT `id`
    FROM `#__menu`
    WHERE `link` LIKE 'index.php?option=com_maqmahelpdesk%'
    AND `published`=1";
$database->setQuery($sql);
$mgmitemid = $database->loadResult();

// query of frontpage content items
$is_support = 0;
$query = "SELECT k.id, k.kbcode, k.kbtitle as title, u.name, k.date_created, k.date_updated, k.content as introtext
    FROM #__support_kb as k, #__users as u
    WHERE k.publish='1' AND u.id=k.id_user
    ORDER BY k.date_updated DESC";
$database->setQuery($query, 0, $info['count']);
$rows = $database->loadObjectList();

foreach ($rows as $row) {
	// title for particular item
	$item_title = htmlspecialchars($row->title);
	$item_title = html_entity_decode($item_title);

	$item_link = 'index.php?option=com_maqmahelpdesk&Itemid=' . $mgmitemid . '&id_workgroup=&task=kb_view&id=' . $row->id;

	// removes all formating from the intro text for the description text
	$item_description = $row->introtext;
	$item_description = JFilterOutput::cleanText($item_description);
	$item_description = html_entity_decode($item_description);
	if ($info['limit_text']) {
		if ($info['text_length']) {
			// limits description text to x words
			$item_description_array = split(' ', $item_description);
			$count = count($item_description_array);
			if ($count > $info['text_length']) {
				$item_description = '';
				for ($a = 0; $a < $info['text_length']; $a++) {
					$item_description .= $item_description_array[$a] . ' ';
				}
				$item_description = trim($item_description);
				$item_description .= '...';
			}
		} else {
			// do not include description when text_length = 0
			$item_description = NULL;
		}
	}

	// load individual item creator class
	$item = new FeedItem();
	// item info
	$item->title = $item_title;
	$item->link = $item_link;
	$item->description = $item_description;
	$item->source = $info['link'];
	echo "";
	$item->date = date('r', mktime(0, 0, 0, JString::substr($row->date_updated, 5, 2), JString::substr($row->date_updated, 8, 2), JString::substr($row->date_updated, 0, 4)));
	$item->category = '';
	// loads item info into rss array
	$rss->addItem($item);
}

// save feed file
echo $rss->saveFeed($info['feed'], $filename);
