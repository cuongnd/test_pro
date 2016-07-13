<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Form
 *
 * @copyright   Copyright (C) 2005 - 2014 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die(__FILE__);

JFormHelper::loadFieldClass('text');

/**
 * Form Field class for the Joomla Platform.
 * Provides and input field for e-mail addresses
 *
 * @package     Joomla.Platform
 * @subpackage  Form
 * @link        http://www.w3.org/TR/html-markup/input.email.html#input.email
 * @see         JFormRuleEmail
 * @since       11.1
 */
class JFormFieldparentalbum extends JFormField
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  11.1
     */
    protected $type = 'parentalbum';

    /**
     * Method to get the field input markup for e-mail addresses.
     *
     * @return  string  The field input markup.
     *
     * @since   11.1
     */
    protected function getInput()
    {
        $db = JFactory::getDbo();
        $user = JFactory::getUser();
        $website=JFactory::getWebsite();

        $query = $db->getQuery(true);
        $query->clear()
            ->select('root_album_id_website_id.id')
            ->from('#__phatthanhnghean_root_album_id_website_id AS root_album_id_website_id ')
            ->where('website_id='.(int)$website->website_id)
        ;
        $a_list_root_album=$db->setQuery($query)->loadColumn();
        if(count($a_list_root_album)==0)
        {
            require_once JPATH_ROOT.'/components/website/website_phatthanhnghean/com_phatthanhnghean/tables/album.php';
            $table_album=JTable::getInstance("album","JTable");
            $table_album->id=0;
            $table_album->album_name="root";
            $table_album->alias="root";
            $table_album->website_id=$website->website_id;
            $ok=$table_album->store();
            if(!$ok)
            {
                throw  new  Exception($table_album->getError());
            }
            $insert_id=$table_album->id;
            require_once JPATH_ROOT.'/components/website/website_phatthanhnghean/com_phatthanhnghean/tables/album.php';
            $table_website_root_album=JTable::getInstance("websiterootalbum","JTable");

            $table_website_root_album->id=0;
            $table_website_root_album->album_id=$insert_id;
            $table_website_root_album->website_id=$website->website_id;
            $ok=$table_website_root_album->store();
            if(!$ok)
            {
                throw  new  Exception($table_website_root_album->getError());
            }

        }


        $query = $db->getQuery(true);
        $query->clear()
            ->select('albums.id,albums.parent_id,albums.album_name as text,albums.website_id')
            ->from('#__phatthanhnghean_albums AS albums')
        ;
        $list_all_album=$db->setQuery($query)->loadObjectList();
        $children_album = array();
        foreach ($list_all_album as $v) {
            $pt = $v->parent_id;
            $pt = ($pt == '' || $pt == $v->id) ? 'list_root' : $pt;
            $list = @$children_album[$pt] ? $children_album[$pt] : array();
            array_push($list, $v);
            $children_album[$pt] = $list;
        }
        $list_root_album = $children_album['list_root'];
        $list_album=array();
        unset($children_album['list_root']);

        $get_list_album=function($function_call_back, $album_id=0, $current_album_id, &$list_album, $children_album, $level=1, $max_level=999){
            $level1=$level+1;
            foreach($children_album[$album_id] as $album)
            {

                $album_id_1=$album->id;
                if($album_id_1==$current_album_id)
                {
                    continue;
                }
                $album->text=str_repeat('---',$level).$album->text;
                $list_album[]=$album;

                $function_call_back($function_call_back,$album_id_1, $current_album_id,$list_album,$children_album,$level1,$max_level);
            }
        };
        $current_album_id=$this->form->getValue('id');
        foreach($list_root_album as $root_album)
        {
            if($root_album->website_id==$website->website_id)
            {
                if($current_album_id!=$root_album->id) {
                    $list_album[] = $root_album;
                    $get_list_album($get_list_album, $root_album->id, $current_album_id, $list_album, $children_album);
                }
            }

        }
        $doc=JFactory::getDocument();
        $doc->addLessStyleSheet(JUri::root().'media/system/js/select2-4.0.0/dist/css/select2.css');
        $doc->addScript(JUri::root().'/media/system/js/select2-4.0.0/dist/js/select2.full.js');
        $doc->addScript(JUri::root().'/components/website/website_phatthanhnghean/com_phatthanhnghean/models/fields/jquery.parentalbum.js');
        $script_id = "script_field_parent_product_category_" . $this->id;
        ob_start();
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#<?php echo $this->id; ?>').field_parentalbum({
                    list_product_category:<?php echo json_encode($list_album)?>,
                    field:{
                        name:"<?php echo $this->name ?>"
                    }
                });


            });
        </script>
        <?php
        $script = ob_get_clean();
        $script = JUtility::remove_string_javascript($script);
        $doc->addScriptDeclaration($script, "text/javascript", $script_id);
        ob_start();
        ?>
        <div id="<?php echo $this->id ?>" class="<?php echo $this->id ?>">
            <input class="form-control input-xxlarge input-large-text" type="text" value="<?php echo $this->value ?>" name="<?php echo $this->name ?>" >
        </div>
        <?php
        $html=ob_get_clean();
        return $html;
    }
}
