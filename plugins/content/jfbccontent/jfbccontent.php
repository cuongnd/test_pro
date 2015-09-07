<?php
/**
 * @package        JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');
jimport('sourcecoast.utilities');
jimport('sourcecoast.articleContent');

class plgContentJFBCContent extends JPlugin
{
    function onContentPrepare($context, &$row, &$params, $page = 0)
    {
        $plugin = JPluginHelper::getPlugin('content', 'emailcloak');
        if (is_object($plugin))
        {
            if (is_object($row))
            {
                if (stripos($row->text, '{JLinkedApply') !== false)
                    $row->text .= "{emailcloak=off}";
            }
            else
            {
                if (stripos($row, '{JLinkedApply') !== false)
                    $row .= "{emailcloak=off}";
            }
        }
    }

    function onContentBeforeDisplay($context, &$article, &$params, $limitstart = 0)
    {
        $app = JFactory::getApplication();
        if ($app->isAdmin())
            return;

        if (strpos($context, 'com_k2') !== 0)
        {
            // Check to only see if we're inside com_content, not tags (or anywhere else)
            if (strpos($context, 'com_content') !== 0)
                return;
            // Make sure we're showing the article from the component, not from a module
            if (!$params || (get_class($params) == 'JRegistry' && !$params->exists('article_layout')))
                return;
        }

        //Get Social RenderKey
        $jfbcLibrary = JFBCFactory::provider('facebook');
        $renderKey = $jfbcLibrary->getSocialTagRenderKey();
        if ($renderKey)
            $renderKeyString = " key=" . $renderKey;
        else
            $renderKeyString = "";

        $configModel = $jfbcLibrary->getConfigModel();

        $view = JRequest::getVar('view');
        $layout = JRequest::getVar('layout');
        $task = JRequest::getVar('task');
        $isArticleView = SCArticleContent::isArticleView($view);

        if ($view == 'item' || $view == 'itemlist' || $view == 'latest') //K2
        {
            $showK2Comments = SCArticleContent::showSocialItemInK2Item($article,
                $configModel->getSetting('social_k2_comment_item_include_ids'),
                $configModel->getSetting('social_k2_comment_item_exclude_ids'),
                $configModel->getSetting('social_k2_comment_cat_include_type'),
                $configModel->getSetting('social_k2_comment_cat_ids'));

            $showK2Like = SCArticleContent::showSocialItemInK2Item($article,
                $configModel->getSetting('social_k2_like_item_include_ids'),
                $configModel->getSetting('social_k2_like_item_exclude_ids'),
                $configModel->getSetting('social_k2_like_cat_include_type'),
                $configModel->getSetting('social_k2_like_cat_ids'));

            $showK2CommentsInViewPosition = SCArticleContent::getSocialK2ItemViewPosition($article, $view, $layout, $task,
                $configModel->getSetting('social_k2_comment_item_view'),
                $configModel->getSetting('social_k2_comment_tag_view'),
                $configModel->getSetting('social_k2_comment_category_view'),
                $configModel->getSetting('social_k2_comment_userpage_view'),
                $configModel->getSetting('social_k2_comment_latest_view')
            );

            $showK2LikeInViewPosition = SCArticleContent::getSocialK2ItemViewPosition($article, $view, $layout, $task,
                $configModel->getSetting('social_k2_like_item_view'),
                $configModel->getSetting('social_k2_like_tag_view'),
                $configModel->getSetting('social_k2_like_category_view'),
                $configModel->getSetting('social_k2_like_userpage_view'),
                $configModel->getSetting('social_k2_like_latest_view')
            );
            if ($showK2Like == true && $showK2LikeInViewPosition != SC_VIEW_NONE)
            {
                if ($isArticleView) //Item View
                    $likeText = $this->_getK2ItemLike($article, $configModel, $renderKeyString);
                else //Blog View
                    $likeText = $this->_getK2BlogLike($article, $configModel, $renderKeyString);

                SCArticleContent::addTextToArticle($article, $likeText, $showK2LikeInViewPosition);
            }
            if ($showK2Comments == true && $showK2CommentsInViewPosition != SC_VIEW_NONE)
            {
                if ($isArticleView) //Item Text
                    $commentText = $this->_getK2ItemComments($article, $configModel, $renderKeyString);
                else
                    $commentText = $this->_getK2BlogComments($article, $configModel, $renderKeyString);

                SCArticleContent::addTextToArticle($article, $commentText, $showK2CommentsInViewPosition);
            }
        } else
        {
            $showComments = SCArticleContent::showSocialItemInArticle($article,
                $configModel->getSetting('social_comment_article_include_ids'),
                $configModel->getSetting('social_comment_article_exclude_ids'),
                $configModel->getSetting('social_comment_cat_include_type'),
                $configModel->getSetting('social_comment_cat_ids'),
                $configModel->getSetting('social_comment_sect_include_type'),
                $configModel->getSetting('social_comment_sect_ids'));

            $showLike = SCArticleContent::showSocialItemInArticle($article,
                $configModel->getSetting('social_like_article_include_ids'),
                $configModel->getSetting('social_like_article_exclude_ids'),
                $configModel->getSetting('social_like_cat_include_type'),
                $configModel->getSetting('social_like_cat_ids'),
                $configModel->getSetting('social_like_sect_include_type'),
                $configModel->getSetting('social_like_sect_ids'));

            $showCommentsInViewPosition = SCArticleContent::getSocialItemViewPosition($article, $view,
                $configModel->getSetting('social_comment_article_view'),
                $configModel->getSetting('social_comment_frontpage_view'),
                $configModel->getSetting('social_comment_category_view'),
                $configModel->getSetting('social_comment_section_view'));

            $showLikeInViewPosition = SCArticleContent::getSocialItemViewPosition($article, $view,
                $configModel->getSetting('social_like_article_view'),
                $configModel->getSetting('social_like_frontpage_view'),
                $configModel->getSetting('social_like_category_view'),
                $configModel->getSetting('social_like_section_view'));

            if ($showLike == true && $showLikeInViewPosition != SC_VIEW_NONE)
            {
                if ($isArticleView) //Article Text
                    $likeText = $this->_getJoomlaArticleLike($article, $configModel, $renderKeyString);
                else //Blog Text
                    $likeText = $this->_getJoomlaBlogLike($article, $configModel, $renderKeyString);

                SCArticleContent::addTextToArticle($article, $likeText, $showLikeInViewPosition);
            }
            if ($showComments == true && $showCommentsInViewPosition != SC_VIEW_NONE)
            {
                if ($isArticleView) //Article Text
                    $commentText = $this->_getJoomlaArticleComments($article, $configModel, $renderKeyString);
                else //Blog Text
                    $commentText = $this->_getJoomlaBlogComments($article, $configModel, $renderKeyString);

                SCArticleContent::addTextToArticle($article, $commentText, $showCommentsInViewPosition);
            }
        }
    }

    function _getJoomlaArticleLike($article, $configModel, $renderKeyString)
    {
        $buttonStyle = $configModel->getSetting('social_article_like_layout_style');
        $showFaces = $configModel->getSetting('social_article_like_show_faces');
        $showShareButton = $configModel->getSetting('social_article_like_show_send_button');
        $width = $configModel->getSetting('social_article_like_width');
        $verbToDisplay = $configModel->getSetting('social_article_like_verb_to_display');
        $font = $configModel->getSetting('social_article_like_font');
        $colorScheme = $configModel->getSetting('social_article_like_color_scheme');
        $showLinkedIn = $configModel->getSetting('social_article_like_show_linkedin');
        $showTwitter = $configModel->getSetting('social_article_like_show_twitter');
        $showGooglePlus = $configModel->getSetting('social_article_like_show_googleplus');
        $showPinterest = $configModel->getSetting('social_article_like_show_pinterest');
        $pinImage = SCArticleContent::getBestArticleImage($article);
        $pinText = SCArticleContent::getFirstArticleText($article, 20, SC_INTRO_WORDS);

        $likeText = $this->_getLikeButton($article, $buttonStyle, $showFaces, $showShareButton, $showLinkedIn, $showTwitter, $showGooglePlus, $showPinterest, $width, $verbToDisplay, $font, $colorScheme, $pinImage, $pinText, $renderKeyString, true);
        return $likeText;
    }

    function _getJoomlaBlogLike($article, $configModel, $renderKeyString)
    {
        $buttonStyle = $configModel->getSetting('social_blog_like_layout_style');
        $showFaces = $configModel->getSetting('social_blog_like_show_faces');
        $showShareButton = $configModel->getSetting('social_blog_like_show_send_button');
        $width = $configModel->getSetting('social_blog_like_width');
        $verbToDisplay = $configModel->getSetting('social_blog_like_verb_to_display');
        $font = $configModel->getSetting('social_blog_like_font');
        $colorScheme = $configModel->getSetting('social_blog_like_color_scheme');
        $showLinkedIn = $configModel->getSetting('social_blog_like_show_linkedin');
        $showTwitter = $configModel->getSetting('social_blog_like_show_twitter');
        $showGooglePlus = $configModel->getSetting('social_blog_like_show_googleplus');
        $showPinterest = $configModel->getSetting('social_blog_like_show_pinterest');
        $pinImage = SCArticleContent::getBestArticleImage($article);
        $pinText = SCArticleContent::getFirstArticleText($article, 20, SC_INTRO_WORDS);

        $likeText = $this->_getLikeButton($article, $buttonStyle, $showFaces, $showShareButton, $showLinkedIn, $showTwitter, $showGooglePlus, $showPinterest, $width, $verbToDisplay, $font, $colorScheme, $pinImage, $pinText, $renderKeyString, true);
        return $likeText;
    }

    function _getK2ItemLike($article, $configModel, $renderKeyString)
    {
        $buttonStyle = $configModel->getSetting('social_k2_item_like_layout_style');
        $showFaces = $configModel->getSetting('social_k2_item_like_show_faces');
        $showShareButton = $configModel->getSetting('social_k2_item_like_show_send_button');
        $width = $configModel->getSetting('social_k2_item_like_width');
        $verbToDisplay = $configModel->getSetting('social_k2_item_like_verb_to_display');
        $font = $configModel->getSetting('social_k2_item_like_font');
        $colorScheme = $configModel->getSetting('social_k2_item_like_color_scheme');
        $showLinkedIn = $configModel->getSetting('social_k2_item_like_show_linkedin');
        $showTwitter = $configModel->getSetting('social_k2_item_like_show_twitter');
        $showGooglePlus = $configModel->getSetting('social_k2_item_like_show_googleplus');
        $showPinterest = $configModel->getSetting('social_k2_item_like_show_pinterest');
        $pinImage = SCArticleContent::getBestK2ItemImage($article);
        $pinText = SCArticleContent::getFirstArticleText($article, 20, SC_INTRO_WORDS);

        $likeText = $this->_getLikeButton($article, $buttonStyle, $showFaces, $showShareButton, $showLinkedIn, $showTwitter, $showGooglePlus, $showPinterest, $width, $verbToDisplay, $font, $colorScheme, $pinImage, $pinText, $renderKeyString, false);
        return $likeText;
    }

    function _getK2BlogLike($article, $configModel, $renderKeyString)
    {
        $buttonStyle = $configModel->getSetting('social_k2_blog_like_layout_style');
        $showFaces = $configModel->getSetting('social_k2_blog_like_show_faces');
        $showShareButton = $configModel->getSetting('social_k2_blog_like_show_send_button');
        $width = $configModel->getSetting('social_k2_blog_like_width');
        $verbToDisplay = $configModel->getSetting('social_k2_blog_like_verb_to_display');
        $font = $configModel->getSetting('social_k2_blog_like_font');
        $colorScheme = $configModel->getSetting('social_k2_blog_like_color_scheme');
        $showLinkedIn = $configModel->getSetting('social_k2_blog_like_show_linkedin');
        $showTwitter = $configModel->getSetting('social_k2_blog_like_show_twitter');
        $showGooglePlus = $configModel->getSetting('social_k2_blog_like_show_googleplus');
        $showPinterest = $configModel->getSetting('social_k2_blog_like_show_pinterest');
        $pinImage = SCArticleContent::getBestK2ItemImage($article);
        $pinText = SCArticleContent::getFirstArticleText($article, 20, SC_INTRO_WORDS);

        $likeText = $this->_getLikeButton($article, $buttonStyle, $showFaces, $showShareButton, $showLinkedIn, $showTwitter, $showGooglePlus, $showPinterest, $width, $verbToDisplay, $font, $colorScheme, $pinImage, $pinText, $renderKeyString, false);
        return $likeText;
    }

    function _getLikeButton($article, $buttonStyle, $showFaces, $showShareButton, $showLinkedInButton, $showTwitterButton, $showGooglePlusButton, $showPinterestButton, $width, $verbToDisplay, $font, $colorScheme, $pinImage, $pinText, $renderKeyString, $isJoomla)
    {
        $url = SCArticleContent::getCurrentURL($article, $isJoomla);

        //Only set width for standard layout, not box_count or button_count
        if ($buttonStyle == 'standard')
            $widthField = ' width=' . $width;
        else
            $widthField = '';

        $likeText = '{JFBCLike layout=' . $buttonStyle . ' show_faces=' . $showFaces . ' share=' . $showShareButton
                . $widthField . ' action=' . $verbToDisplay . ' font=' . $font
                . ' colorscheme=' . $colorScheme . ' href=' . $url . $renderKeyString . '}';

        $buttonText = '<div style="position: relative; top:0px; left:0px; z-index: 99;" class="scsocialbuttons '.$buttonStyle.'">';
        if ($showLinkedInButton || $showTwitterButton || $showGooglePlusButton)
        {
            $extraButtonText = SCSocialUtilities::getExtraShareButtons($url, $buttonStyle, false, false, $showTwitterButton, $showGooglePlusButton, $renderKeyString, $showLinkedInButton, '50', $showPinterestButton, $pinImage, $pinText);
            $buttonText .= $extraButtonText;

        }
        $buttonText .= $likeText;
        $buttonText .= '</div><div style="clear:left"></div>';
        $likeText = $buttonText;

        return $likeText;
    }

    function _getJoomlaArticleComments($article, $configModel, $renderKeyString)
    {
        $width = $configModel->getSetting('social_article_comment_width');
        $numposts = $configModel->getSetting('social_article_comment_max_num');
        $colorscheme = $configModel->getSetting('social_article_comment_color_scheme');
        $orderBy = $configModel->getSetting('social_article_comment_order_by');

        $commentText = $this->_getComments($article, $width, $numposts, $colorscheme, $orderBy, $renderKeyString, true);
        return $commentText;
    }

    function _getJoomlaBlogComments($article, $configModel, $renderKeyString)
    {
        $width = $configModel->getSetting('social_blog_comment_width');
        $numposts = $configModel->getSetting('social_blog_comment_max_num');
        $colorscheme = $configModel->getSetting('social_blog_comment_color_scheme');
        $orderBy = $configModel->getSetting('social_blog_comment_order_by');

        $commentText = $this->_getComments($article, $width, $numposts, $colorscheme, $orderBy, $renderKeyString, true);
        return $commentText;
    }

    function _getK2ItemComments($article, $configModel, $renderKeyString)
    {
        $width = $configModel->getSetting('social_k2_item_comment_width');
        $numposts = $configModel->getSetting('social_k2_item_comment_max_num');
        $colorscheme = $configModel->getSetting('social_k2_item_comment_color_scheme');
        $orderBy = $configModel->getSetting('social_k2_item_comment_order_by');

        $commentText = $this->_getComments($article, $width, $numposts, $colorscheme, $orderBy, $renderKeyString, false);
        return $commentText;
    }

    function _getK2BlogComments($article, $configModel, $renderKeyString)
    {
        $width = $configModel->getSetting('social_k2_blog_comment_width');
        $numposts = $configModel->getSetting('social_k2_blog_comment_max_num');
        $colorscheme = $configModel->getSetting('social_k2_blog_comment_color_scheme');
        $orderBy = $configModel->getSetting('social_k2_blog_comment_order_by');

        $commentText = $this->_getComments($article, $width, $numposts, $colorscheme, $orderBy, $renderKeyString, false);
        return $commentText;
    }

    function _getComments($article, $width, $numposts, $colorscheme, $orderBy, $renderKeyString, $isJoomla)
    {
        $href = SCArticleContent::getCurrentURL($article, $isJoomla);

        if (!$numposts || $numposts == '0')
        {
            $commentText = '{JFBCCommentsCount href=' . $href . $renderKeyString . '}';
        } else
        {
            $commentText = '{JFBCComments href=' . $href . ' width=' . $width . ' num_posts=' . $numposts
                    . ' colorscheme=' . $colorscheme . ' order_by=' . $orderBy . $renderKeyString . '}';
        }

        $buttonText = '<div style="z-index: 98;" class="scsocialcomments">' . $commentText . '</div>';
        return $buttonText;
    }
}
