<?php
/**
 * @package		JFBConnect
 * @copyright (C) 2009-2013 by Source Coast - All rights reserved
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

defined('_JEXEC') or die('Restricted access');

if ($userIntro != '') {
    echo '<div class="jfbcsocialshare_desc">'.$userIntro."</div>";
}
?>
<div style="<?php echo $groupStyles;?> z-index: 99; overflow: auto;display:none" class="jfbcsocialshare scsocialbuttons <?php echo $layout. ' '.$orientation;?>">
    <?php
    if($linkedinEnable)
    {
        echo '{JLinkedShare' . $href . ' layout='. $layout . ' show_zero=' . $linkedinShowZero . $renderKeyString . '}';
    }

    if($twitterEnable)
    {
        echo '{SCTwitterShare' . $href . ' layout='. $layout . $renderKeyString . '}';
    }

    if($googleEnable)
    {
        echo '{SCGooglePlusOne' . $href . ' layout='. $layout . ' width='. $googleWidth . $renderKeyString . '}';
    }

    if ($facebookEnable)
    {
        if(!$facebookShareEnable)
        {
            //Use Like IFrame for height and width to not resize dynamically/cause flashing.
            //However, Share button will not display in this mode
            echo '<div class="jfbclike"><iframe src="//www.facebook.com/plugins/like.php?href='.$url.
            '&amp;width='.$facebookWidth.
            '&amp;height='.$facebookHeight.
            '&amp;colorscheme='.$facebookColorScheme.
            '&amp;layout='.$layout.
            '&amp;action='.$facebookVerb.
            '&amp;show_faces='.$facebookShowFacesValue.
            '&amp;kid_directed_site='.$facebookKidDirectedSiteValue.
            '&amp;appId='.$fbAppId.'" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:'.$facebookWidth.'; height:'.$facebookHeight.';" allowTransparency="true"></iframe></div>';
        }
        else {
            echo '{JFBCLike'.$href.
                ' layout='.$layout.
                ' show_faces='.$facebookShowFacesValue.
                ' share='.$facebookShowShareButtonValue.
                ' width='.$facebookWidth.
                ' height='.$facebookHeight.
                ' action='.$facebookVerb.
                ' colorScheme='.$facebookColorScheme.
                ' ref='.$facebookRef.
                $renderKeyString.
                '}';
        }
    }
    ?>
</div><div style="clear:left"></div>
<?php
require(JPATH_ROOT.'/components/com_jfbconnect/assets/poweredBy.php');
?>