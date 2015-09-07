<?php
/**
 * @package        JLinked
 * @copyright (C) 2011-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die(__FILE__);

jimport('sourcecoast.utilities');

define('SC_TYPE_ALL', '0');
define('SC_TYPE_INCLUDE', '1');
define('SC_TYPE_EXCLUDE', '2');
define('SC_NO', '0');
define('SC_YES', '1');
define('SC_VIEW_NONE', "0");
define('SC_VIEW_TOP', "1");
define('SC_VIEW_BOTTOM', "2");
define('SC_VIEW_BOTH', "3");

define('SC_INTRO_CHARS', '1');
define('SC_INTRO_WORDS', '2');

class SCArticleContent
{
    static function isArticleView($view)
    {
        return ($view == 'article' || $view == 'item');
    }

    static function getSocialItemViewPosition($article, $view, $showInArticleView, $showInFrontpageView, $showInCategoryView, $showInSectionView)
    {
        $returnValue = "0";
        if ($view == 'article' && $article->id != null)
            $returnValue = $showInArticleView;
        else if ($view == 'frontpage' || $view == 'featured')
            $returnValue = $showInFrontpageView;
        else if ($view == 'category' && $article->catid != null)
            $returnValue = $showInCategoryView;

        return $returnValue;
    }

    static function getSocialK2ItemViewPosition($article, $view, $layout, $task, $showInItemView, $showInTagView, $showInCategoryView, $showInUserpageView, $showInLatestView)
    {
        $returnValue = "0";
        if ($view == 'item' && $article->id != null)
            $returnValue = $showInItemView;
        else if ($view == 'itemlist')
        {
            if (SCArticleContent::_isK2Layout($layout, $task, 'category')
                    || SCArticleContent::_isK2Layout($layout, $task, 'search')
                    || SCArticleContent::_isK2Layout($layout, $task, 'date')
            )
                $returnValue = $showInCategoryView;
            else if (SCArticleContent::_isK2Layout($layout, $task, 'generic') || SCArticleContent::_isK2Layout($layout, $task, 'tag'))
                $returnValue = $showInTagView;
            else if (SCArticleContent::_isK2Layout($layout, $task, 'user') && JRequest::getInt('id', 0))
                $returnValue = $showInUserpageView;
        } else if ($view == 'latest')
            $returnValue = $showInLatestView;
        return $returnValue;
    }

    private static function _isK2Layout($layout, $task, $targetLayout)
    {
        return ($layout == $targetLayout || $task == $targetLayout);
    }

    static function showSocialItemInArticle($article, $articleIncludeIds, $articleExcludeIds, $catIncludeType, $catIds, $sectIncludeType, $sectIds)
    {
        //Show in Article
        $includeArticles = explode(",", $articleIncludeIds);
        $excludeArticles = explode(",", $articleExcludeIds);

        //Specific Article is included or excluded, then show or don't show it.
        if ($includeArticles != null && in_array($article->id, $includeArticles))
            return true;
        else if ($excludeArticles != null && in_array($article->id, $excludeArticles))
            return false;

        //Show in Category
        $categories = unserialize($catIds);
        $inCategoryArray = $categories != null && in_array($article->catid, $categories);

        if ($catIncludeType == SC_TYPE_INCLUDE)
        {
            if ($inCategoryArray)
                return true;
            else
                return false;
        } else if ($catIncludeType == SC_TYPE_EXCLUDE)
        {
            if ($inCategoryArray)
                return false;
            else
                return true;
        }

        return true;
    }

    static function showSocialItemInK2Item($article, $articleIncludeIds, $articleExcludeIds, $catIncludeType, $catIds)
    {
        //Show in Article
        $includeArticles = explode(",", $articleIncludeIds);
        $excludeArticles = explode(",", $articleExcludeIds);

        //Specific Article is included or excluded, then show or don't show it.
        if ($includeArticles != null && in_array($article->id, $includeArticles))
            return true;
        else if ($excludeArticles != null && in_array($article->id, $excludeArticles))
            return false;

        //Show in Category
        $categories = unserialize($catIds);
        $inCategoryArray = $categories != null && in_array($article->catid, $categories);

        if ($catIncludeType == SC_TYPE_INCLUDE)
        {
            if ($inCategoryArray)
                return true;
            else
                return false;
        } else if ($catIncludeType == SC_TYPE_EXCLUDE)
        {
            if ($inCategoryArray)
                return false;
            else
                return true;
        }

        return true;

    }

    static function getCurrentURL($article, $isJoomla)
    {
        if ($isJoomla)
            return SCArticleContent::_getCurrentArticleURL($article);
        else
            return SCArticleContent::_getCurrentItemURL($article);
    }

    private static function _getCurrentArticleURL($article)
    {
        require_once(JPATH_SITE . '/components/com_content/helpers/route.php');

        if (isset($article->catslug) && isset($article->slug))
            $url = ContentHelperRoute::getArticleRoute($article->slug, $article->catslug);
        else if (isset($article->catid) && isset($article->slug))
            $url = ContentHelperRoute::getArticleRoute($article->slug, $article->catid);
        else if (isset($article->slug))
            $url = ContentHelperRoute::getArticleRoute($article->slug);
        else
            $url = 'index.php?option=com_content&view=article&id=' . $article->id;

        $url = SCArticleContent::_getCompleteURL($url);
        return $url;
    }

    private static function _getCurrentItemURL($article)
    {
        require_once(JPATH_SITE . '/components/com_k2/helpers/route.php');
        $url = K2HelperRoute::getItemRoute($article->id . ":" . urlencode($article->alias), $article->catid);
        $url = SCArticleContent::_getCompleteURL($url);
        return $url;
    }

    private static function _getCompleteURL($url)
    {
        $url = JRoute::_($url, true);
        $jUri = JURI::getInstance();
        $url = rtrim($jUri->toString(array('scheme', 'host', 'port')), '/') . $url;
        return $url;
    }

    private static function _prependToIntrotext(& $article, $fbText)
    {
        if (isset($article->text))
            $article->text = $fbText . $article->text;
        if (isset($article->introtext))
            $article->introtext = $fbText . $article->introtext;
    }

    private static function _prependToFulltext(& $article, $fbText)
    {
        if (isset($article->text))
            SCArticleContent::_prependAfterSplitter($article->text, $fbText);
        if (isset($article->fulltext))
            SCArticleContent::_prependAfterSplitter($article->fulltext, $fbText);
    }

    private static function _appendToIntrotext(& $article, $fbText)
    {
        if (isset($article->text))
            $article->text = $article->text . $fbText;
        else if (isset($article->introtext))
            $article->introtext = $article->introtext . $fbText;
    }

    private static function _appendToFulltext(& $article, $fbText)
    {
        if (isset($article->text))
            $article->text = $article->text . $fbText;
        else if (isset($article->fulltext))
            $article->fulltext = $article->fulltext . $fbText;
    }

    private static function _prependAfterSplitter(& $text, $fbText)
    {
        $articleText = str_replace('{K2Splitter}', '', $text, $count);
        $text = $fbText . $articleText;
        if ($count)
            $text = '{K2Splitter}' . $text;
    }

    private static function _appendBeforeSplitter(& $text, $fbText)
    {
        $articleText = str_replace('{K2Splitter}', '', $text, $count);
        $text = $articleText . $fbText;
        if ($count)
            $text .= '{K2Splitter}';
    }

    static function addClassToFBText($fbText, $className)
    {
        $newFbText = str_replace('scsocialbuttons', 'scsocialbuttons ' . $className, $fbText);
        return $newFbText;
    }

    static function addTextToArticle(& $article, $fbText, $showTextPosition)
    {
        $hasFullText = isset($article->fulltext) && $article->fulltext != "";

        $introtextStartsWithSplitter = isset($article->introtext) && strpos($article->introtext, '{K2Splitter}') === 0;
        $textStartsWithSplitter = isset($article->text) && strpos($article->text, '{K2Splitter}') === 0;

        $hasIntroText = isset($article->introtext) && $article->introtext != "";
        if ($textStartsWithSplitter || $introtextStartsWithSplitter)
            $hasIntroText = false;

        $topText = SCArticleContent::addClassToFBText($fbText, "top");
        $bottomText = SCArticleContent::addClassToFBText($fbText, "bottom");

        if ($showTextPosition == SC_VIEW_TOP)
        {
            if (!$hasIntroText && $hasFullText)
            {
                if (isset($article->text))
                    SCArticleContent::_prependAfterSplitter($article->text, $topText);
                if (isset($article->fulltext))
                    SCArticleContent::_prependAfterSplitter($article->fulltext, $topText);
            } else
            {
                SCArticleContent::_prependToIntrotext($article, $topText);
            }
        } else if ($showTextPosition == SC_VIEW_BOTH)
        {
            //If introtext is present, we have to be careful of where to put the bottom item, because of K2Splitter
            if ($hasIntroText)
            {
                if ($hasFullText)
                {
                    //If fulltext is present, it means there's already something after fulltext, so safe to
                    //just add at the bottom of text.
                    SCArticleContent::_prependToIntrotext($article, $topText);
                    SCArticleContent::_appendToFulltext($article, $bottomText);
                } else
                {
                    //If full text is not present, then we must add the bottom portion before K2Splitter
                    SCArticleContent::_prependToIntrotext($article, $topText);

                    if (isset($article->text))
                        SCArticleContent::_appendBeforeSplitter($article->text, $bottomText);
                    if (isset($article->introtext))
                        SCArticleContent::_appendBeforeSplitter($article->introtext, $bottomText);
                }
            } else if ($hasFullText)
            {
                //If fulltext is present, 1it means there's already something after fulltext, so safe to
                //just add at the bottom of text.
                SCArticleContent::_prependToFulltext($article, $topText);
                SCArticleContent::_appendToFulltext($article, $bottomText);
            }
        } else if ($showTextPosition == SC_VIEW_BOTTOM)
        {
            if ($hasFullText)
            {
                //If fulltext is present, it means there's already something after fulltext, so safe to
                //just add at the bottom of text.
                SCArticleContent::_appendToFulltext($article, $bottomText);
            } else if ($hasIntroText)
            {
                //If full text is not present, then we must add the bottom portion before K2Splitter
                if (isset($article->text))
                    SCArticleContent::_appendBeforeSplitter($article->text, $bottomText);
                if (isset($article->introtext))
                    SCArticleContent::_appendBeforeSplitter($article->introtext, $bottomText);
            }
        }
    }

    static function getFirstCategoryText($category, $numCharacters = 100, $socialGraphFirstText = '1')
    {
        $categoryText = '';
        if (isset($category->description))
            $categoryText = SCArticleContent::getSelectedText($category->description, $socialGraphFirstText, $numCharacters);
        return $categoryText;
    }

    static function getFirstArticleText($article, $numCharacters = 100, $socialGraphFirstText = '1')
    {
        $articleText = '';
        if (isset($article->introtext) && trim(strip_tags($article->introtext)) != "")
        {
            $articleText = $article->introtext;
        } else if (isset($article->text) && trim(strip_tags($article->text)) != "")
        {
            $articleText = $article->text;
        } else if (isset($article->fulltext) && trim(strip_tags($article->fulltext)) != "")
        {
            $articleText = $article->fulltext;
        }

        $articleText = SCArticleContent::getSelectedText($articleText, $socialGraphFirstText, $numCharacters);

        return $articleText;
    }

    static function getSelectedText($contentText, $socialGraphFirstText, $numCharacters)
    {
        $articleText = strip_tags($contentText);
        $articleText = preg_replace('/\s+/', ' ', $articleText);
        $articleText = str_replace('{K2Splitter}', '', $articleText);
        SCSocialUtilities::stripSystemTags($articleText, 'JFBC');
        SCSocialUtilities::stripSystemTags($articleText, 'JLinked');
        SCSocialUtilities::stripSystemTags($articleText, 'SCOpenGraph');
        SCSocialUtilities::stripSystemTags($articleText, 'SCTwitter');
        SCSocialUtilities::stripSystemTags($articleText, 'SCGoogle');
        SCSocialUtilities::stripSystemTags($articleText, 'SCPinterest');
        SCSocialUtilities::stripSystemTags($articleText, 'loadposition');
        $articleText = SCStringUtilities::trimNBSP($articleText);

        $addEllipsis = false;
        if ($socialGraphFirstText == '1')
        {
            $addEllipsis = strlen($articleText) > $numCharacters;

            if (function_exists('mb_substr'))
                $articleText = mb_substr($articleText, 0, $numCharacters, 'UTF-8');
            else
                $articleText = substr($articleText, 0, $numCharacters);
        } else if ($socialGraphFirstText == '2')
        {
            if (function_exists('mb_split'))
               $parts = mb_split('\s+', $articleText);
            else
               $parts = preg_split('\s+', $articleText);
            $selParts = array_slice($parts, 0, $numCharacters);
            $articleText = implode(" ", $selParts);
            $addEllipsis = count($parts) > $numCharacters;
        } else
            $articleText = '';

        if ($addEllipsis)
            $articleText .= '...';

        return $articleText;
    }

    static function getArticleIntroImage($article)
    {
        $reg = new JRegistry();
        if (!empty($article->images))
            $reg->loadArray(json_decode($article->images));
        $fullImagePath = $reg->get('image_intro');
        $fullImagePath = SCArticleContent::_getImageLink($fullImagePath);
        return $fullImagePath;
    }

    static function getArticleFullImage($article)
    {
        $reg = new JRegistry();
        if (!empty($article->images))
            $reg->loadArray(json_decode($article->images));
        $fullImagePath = $reg->get('image_fulltext');
        $fullImagePath = SCArticleContent::_getImageLink($fullImagePath);
        return $fullImagePath;
    }

    static function getImageFromCategory($article)
    {
        $image = NULL;
        $fullImagePath = '';

        if (isset($article->catid))
        {
            $fullImagePath = SCArticleContent::getCategoryImage($article->catid);
        }
        return $fullImagePath;
    }

    static function getCategoryImage($catid)
    {
        $content = JCategories::getInstance('content');
        $category = $content->get($catid);
        $fullImagePath = '';
        if ($category)
        {
            $image = $category->getParams()->get('image');
            $fullImagePath = SCArticleContent::_getImageLink($image);
        }

        return $fullImagePath;
    }

    static function getFirstImage($article)
    {
        if (isset($article->text))
            $articleText = $article->text;
        else
            $articleText = $article->introtext . $article->fulltext;

        $fullImagePath = SCArticleContent::getFirstImageFromText($articleText);
        return $fullImagePath;
    }

    static function getFirstImageFromText($text)
    {
        $fullImagePath = '';
        if (preg_match_all('/<img [^>]*src=["|\']([^"|\']+)/i', $text, $matches))
        {
            $fullImagePath = SCArticleContent::_getImageLink($matches[1][0]);
        }
        return $fullImagePath;
    }

    private static function _getImageLink($path)
    {
        if ($path)
        {
            $juri = JURI::getInstance();
            $basePath = str_replace(array($juri->getScheme() . "://", $juri->getHost()), "", $juri->base());

            if (strpos($path, $basePath) === 0)
            {
                $path = substr($path, strlen($basePath));
                $path = $juri->base() . $path;
            } else if (strpos($path, "http") !== 0)
                $path = $juri->base() . $path;
        }
        return $path;
    }

    static function getK2MainImage($article)
    {
        $imageName = 'media/k2/items/cache/' . md5('Image' . $article->id) . '_XL.jpg';

        jimport('joomla.filesystem.file');
        if (JFile::exists(JPATH_SITE . '/' . $imageName))
            return JURI::base() . $imageName;
        else
            return '';
    }

    static function getK2CategoryImage($catid)
    {
        $category = SCArticleContent::getK2Category($catid);
        $image = $category->image;
        $imageName = 'media/k2/categories/' . $image;

        jimport('joomla.filesystem.file');
        if (JFile::exists(JPATH_SITE . '/' . $imageName))
            return JURI::base() . $imageName;
        else
            return '';
    }

    static function getCurrentK2CategoryId()
    {
        $catid = JRequest::getInt('id');
        return $catid;
    }

    static function getK2Category($catid)
    {
        JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_k2/tables');
        $category = JTable::getInstance('K2Category', 'Table');
        $category->load($catid);
        return $category;
    }

    static function getEasyBlogMainImage($article)
    {
        $url = '';

        if (isset($article->image))
        {
            $image = json_decode($article->image);
            if (isset($image->url))
            {
                $filePath = str_replace(JURI::root(), '', $image->url);

                jimport('joomla.filesystem.file');
                if (JFile::exists($filePath))
                    $url = $image->url;
            }
        }

        return $url;
    }

    static function getEasyBlogCategoryImage($catid)
    {
        $url = '';
        $category = SCArticleContent::getEasyBlogCategory($catid);

        if (isset($category->avatar))
        {
            $image = $category->avatar;
            $imageName = 'images/easyblog_cavatar/' . $image;

            jimport('joomla.filesystem.file');
            if (JFile::exists($imageName))
                $url = JURI::base() . $imageName;
        }
        return $url;
    }

    static function getCurrentEasyBlogCategoryId()
    {
        $catid = JRequest::getInt('id');
        return $catid;
    }

    static function getEasyBlogCategory($catid)
    {
        JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_easyblog/tables');
        $category = JTable::getInstance('Category', 'EasyBlogTable');
        $category = null;
        if ($category) // Some users have reported this not coming back. Haven't determined why, but this check should fix.
        $category->load($catid);
        return $category;
    }

    static function getBestArticleImage($article)
    {
        $image = SCArticleContent::getArticleFullImage($article);
        if ($image == '')
        {
            $image = SCArticleContent::getArticleIntroImage($article);
        }
        if ($image == '')
        {
            $image = SCArticleContent::getFirstImage($article);
        }
        if ($image == '')
        {
            $image = SCArticleContent::getImageFromCategory($article);
        }
        return $image;
    }

    static function getBestK2ItemImage($item)
    {
        $image = SCArticleContent::getK2MainImage($item);
        if ($image == '')
        {
            $image = SCArticleContent::getFirstImage($item);
        }
        if ($image == '')
        {
            $image = SCArticleContent::getK2CategoryImage($item->catid);
        }
        return $image;
    }
}