<?php
    /**
    * @package     Joomla.Platform
    * @subpackage  Form
    *
    * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
    * @license     GNU General Public License version 2 or later; see LICENSE
    */

    defined('JPATH_PLATFORM') or die;
    jimport('joomla.filesystem.folder');
    jimport('joomla.filesystem.file');
    /**
    * Supports an HTML select list of image
    *
    * @package     Joomla.Platform
    * @subpackage  Form
    * @since       11.1
    */
    class JFormFieldImagelistBookpro extends JFormField
    {

        /**
        * The form field type.
        *
        * @var    string
        * @since  11.1
        */
        public $type = 'ImagelistBookpro';

        /**
        * Method to get the list of images field options.
        * Use the filter attribute to specify allowable file extensions.
        *
        * @return  array  The field option objects.
        *
        * @since   11.1
        */
        protected function getInput()
        {
            // Define the image file type filter.
            $filter = '\.png$|\.gif$|\.jpg$|\.bmp$|\.ico$|\.jpeg$|\.psd$|\.eps$';
            JHtml::_('behavior.modal');
            // Set the form field element attribute for file type filter.
            $this->element->addAttribute('filter', $filter);
            $filter = (string) $this->element['filter'];
            $exclude = (string) $this->element['exclude'];
            $stripExt = (string) $this->element['stripext'];
            $hideNone = (string) $this->element['hide_none'];
            $hideDefault = (string) $this->element['hide_default'];

            // Get the path in which to search for file options.
            $path = (string) $this->element['directory'];
            if (!is_dir($path))
            {
                $path = JPATH_ROOT . '/' . $path;
            }



            // Get a list of files in the search path with the given filter.
            $files = JFolder::files($path, $filter);
            $ulimage='';
            $ulimage.='<ul>';
            // Build the options list from the list of files.
            if (is_array($files))
            {
                foreach ($files as $file)
                {

                    // Check to see if the file is in the exclude mask.
                    if ($exclude)
                    {
                        if (preg_match(chr(1) . $exclude . chr(1), $file))
                        {
                            continue;
                        }
                    }

                    // If the extension is to be stripped, do it.
                    if ($stripExt)
                    {
                        $file = JFile::stripExt($file);
                    }
                    $ulimage.='<li><img data="'.$this->element['directory'].'/'.$file.'" src="'.JURI::root().$this->element['directory'].'/'.$file.'"/></li>';

                }
            }
            $ulimage.='</ul>';
            ob_start();
        ?>
        <style>
            .divDemoBody  {
                width: 60%;
                margin-left: auto;
                margin-right: auto;
                margin-top: 100px;
            }
            .divDemoBody p {
                font-size: 18px;
                line-height: 140%;
                padding-top: 12px;
            }
            .divDialogElements input {
                font-size: 18px;
                padding: 3px; 
                height: 32px; 
                width: 500px; 
            }
            .divButton {
                padding-top: 12px;
            }
            .divDialogElements ul
            {
                list-style: none;
            }
            .divDialogElements ul li
            {
                float: left;
                width: 50px;
            }
            .divDialogElements ul li img
            {
                cursor: pointer;
                padding: 5px;
            }
            .divDialogElements ul li img.choose
            {
                border-style: solid;
                border-width: 2px;
                border-radius: 4px;
                box-shadow: 0 1px 0 rgba(255, 255, 255, 0.2) inset, 0 1px 2px rgba(0, 0, 0, 0.1);
                border: 1px;
                padding: 5px;
            }
            img[name="view_image<?php echo $this->element['name']  ?>"]
            {
                border-style: solid;
                border-width: 2px;
                border-radius: 4px;
                box-shadow: 0 1px 0 rgba(255, 255, 255, 0.2) inset, 0 1px 2px rgba(0, 0, 0, 0.1);
                border: 1px;
                padding: 5px;
                min-height: 20px;
                min-width: 20px; 
            }
        </style>
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                $('#windowTitleDialog').bind('show', function () {
                    //document.getElementById ("xlInput").value = document.title;
                });
                $('.modal-footer .btn.cancel').click(function(){
                    $('#windowTitleDialog').modal('hide');
                });
                $('.modal-footer .btn.btn-primary.ok').click(function(){
                    $('#windowTitleDialog').modal('hide');
                    $('img[name="view_image<?php echo $this->element['name']  ?>"]').attr('src',$('.divDialogElements ul li img.choose').attr('src'));
                    $('input[name="jform[<?php echo $this->element['name']  ?>]"]').val($('.divDialogElements ul li img.choose').attr('data'));
                });
                $('.divDialogElements ul li img').click(function(){
                    $('.divDialogElements ul li img').each(function(index){
                        $(this).removeClass('choose'); 
                    });
                    $(this).addClass('choose');
                });

            });


        </script>

        <div id="windowTitleDialog" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="windowTitleLabel" aria-hidden="true">
            <div class="modal-header">
                <h3><?php echo JText::_('COM_BOOKPRO_SELECT_IMAGE') ?>.</h3>
            </div>
            <div class="modal-body">
                <div class="divDialogElements">
                    <?php echo $ulimage ?>
                </div>
            </div>
            <div class="modal-footer">
                <a href="#" class="btn cancel"><?php echo JText::_('COM_BOOKPRO_CANCAL') ?></a>
                <a href="#" class="btn btn-primary ok"><?php echo JText::_('COM_BOOKPRO_OK') ?></a>
            </div>
        </div>
        <input type="hidden" value="<?php echo $this->value ?>" id="<?php echo $this->element['name'] ?>" name="jform[<?php echo $this->element['name'] ?>]">
        <img src="<?php echo JURI::root().$this->value  ?>" name="view_image<?php echo $this->element['name'] ?>" id="view_image<?php echo $this->element['name'] ?>" >
        <a class="btn btn-mini" href="#windowTitleDialog" data-toggle="modal"><?php echo JText::_('COM_BOOKPRO_CHANGE_IMAGE') ?></a>
        <?php
            $html=ob_get_contents();
            ob_end_clean(); // get the callback function
            return $html;
        }
    }
