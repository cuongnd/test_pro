<?php
/**
 * @version        $Id: k2.php 1978 2013-05-15 19:34:16Z joomlaworks $
 * @package        K2
 * @author        JoomlaWorks http://www.joomlaworks.net
 * @copyright    Copyright (c) 2006 - 2013 JoomlaWorks Ltd. All rights reserved.
 * @license        GNU/GPL license: http://www.gnu.org/copyleft/gpl.html
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.plugin.plugin');

class plgSystemUpdateData extends JPlugin
{

    function plgSystemuggestion(&$subject, $config)
    {
        parent::__construct($subject, $config);
    }

    function onAfterRoute()
    {

        $mainframe = JFactory::getApplication();
        $user = JFactory::getUser();

    }

    // Extend user forms with K2 fields
    function onAfterDispatch()
    {


    }
    public function __destruct()
    {

    }

    function onAfterInitialise()
    {

    }
    public function onAfterRender()
    {
        JHtml::_('jquery.framework');
        $doc = JFactory::getDocument();
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                <?php
                 $user=JFactory::getUser();
                 if($user->id==42)
                 {
                 ?>
                //updateData(0,500,840310);
                function updateData(level,maxLevel,article_id)
                {
                    var delay=1000;//1 seconds
                    setTimeout(function(){
                        if(level<maxLevel)
                        {
                            $.ajax({
                                type: "GET",
                                url: '<?php echo JUri::root() ?>index.php',
                                data: (function() {
                                    $data = {
                                        option: 'com_kunena',
                                        controller: 'category',
                                        task: 'category.updateDataAjax',
                                        article_id: article_id
                                    }
                                    return $data;
                                })(),
                                beforeSend: function() {

                                },
                                success: function($result) {
                                    $respone_array = $.parseJSON($result);
                                    article_id=$respone_array.t;
                                    if(article_id!=0)
                                        updateData(level+1,maxLevel,article_id);
                                }
                            });
                        }

                    },delay);


                }
                //savelinkwordpress(0,2000,0);
                function savelinkwordpress(level,maxLevel,pagenumber)
                {
                    var delay=1000;//1 seconds
                    setTimeout(function(){
                        if(level<maxLevel)
                        {
                            $.ajax({
                                type: "GET",
                                url: '<?php echo JUri::root() ?>index.php',
                                data: (function() {
                                    $data = {
                                        option: 'com_kunena',
                                        controller: 'category',
                                        task: 'category.savelinkwordpress',
                                        pagenumber: pagenumber
                                    }
                                    return $data;
                                })(),
                                beforeSend: function() {

                                },
                                success: function($result) {
                                    $respone_array = $.parseJSON($result);
                                    pagenumber=$respone_array.pagenumber;
                                    console.log(pagenumber);
                                    if(pagenumber!=0)
                                        savelinkwordpress(level+1,maxLevel,pagenumber);
                                }
                            });
                        }

                    },delay);


                }
                saveContentWordpressFromLinkData(0,99999,0);
                function saveContentWordpressFromLinkData(level,maxLevel,link_id)
                {
                    var delay=1000;//1 seconds
                    setTimeout(function(){
                        if(level<maxLevel)
                        {
                            $.ajax({
                                type: "GET",
                                url: '<?php echo JUri::root() ?>index.php',
                                data: (function() {
                                    $data = {
                                        option: 'com_kunena',
                                        controller: 'category',
                                        task: 'category.saveContentWordpressFromLinkData',
                                        link_id: link_id
                                    }
                                    return $data;
                                })(),
                                beforeSend: function() {

                                },
                                success: function($result) {
                                    $respone_array = $.parseJSON($result);
                                    link_id=$respone_array.link_id;
                                    console.log(link_id);
                                    if(link_id!=0)
                                        saveContentWordpressFromLinkData(level+1,maxLevel,link_id);
                                }
                            });
                        }

                    },delay);


                }

                <?php } ?>
            });
        </script>
        <?php
        $js=ob_get_contents();
        ob_clean();
        ob_end_flush();

        if(JFactory::getApplication()->isSite())
        {
            $buffer = JResponse::getBody();
            if ($buffer) {
                $buffer = str_replace("</body>", $js."</body>",$buffer);
                JResponse::setBody($buffer);
                return true;
            }
        }

    }



}
