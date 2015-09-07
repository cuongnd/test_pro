
<?php
/**
 * @package 	Bookpro
 * @author 		Nguyen Dinh Cuong
 * @link 		http://ibookingonline.com
 * @copyright 	Copyright (C) 2011 - 2012 Nguyen Dinh Cuong
 * @license 	GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @version 	$Id: default.php 82 2012-08-16 15:07:10Z quannv $
 * */


defined('_JEXEC') or die('Restricted access');
$db = JFactory::getDbo();
$query = $db->getQuery(true);
$query->select('messages.*');
$query->from('#__bookpro_messages AS messages');

$query->select('user.name AS username');
$query->join('LEFT', '#__users AS user ON messages.cid_from = user.id ');

$query->select('CONCAT(c.firstname," ",c.lastname) AS ufirstname');
$query->join('LEFT', '#__bookpro_customer AS c ON messages.cid_from = c.user');

$query->where('messages.cid_from = c.user');

$query->order('created DESC');
$db->setQuery($query, 0, 5);
$items = $db->loadObjectList();
?>
<fieldset>
    <legend>
        <?php echo JText::_('COM_BOOKPRO_MESSAGES'); ?>
    </legend>
</fieldset>

        <table class="table-striped table" >
            <thead>
                <tr>

                    <th width="25%">
                        <?php echo JText::_('COM_BOOKPRO_MESSAGE_SUBJECT'); ?>
                    </th>
                    <th width="45%">
                        <?php echo JText::_('COM_BOOKPRO_MESSAGE'); ?>
                    </th>
                    <th width="20%">
                        <?php echo JText::_('From'); ?>
                    </th>
                    <th width="10%">
                        <?php echo JText::_('COM_BOOKPRO_MESSAGE_REPLY'); ?>
                    </th>

                </tr>
            </thead>

            <tbody>
                <?php
                for ($i = 0; $i < count($items); $i++) {
                    $subject = $items[$i];
                    //$link = JRoute::_(ARoute::edit(CONTROLLER_MESSAGE, $subject->id));
                    ?>
                    <tr>

                        <td>
                            <a href="<?php echo JRoute::_(ARoute::view('message', null, null, array('parent_id' => $subject->id, 'cid_from' => $subject->cid_from, 'layout' => 'edit'))); ?>" class=""><?php echo $subject->subject; ?></a><br/>

                            <?php
                            echo JHtml::_('date',$subject->created,'d-m H:i');
                            ?>
                        </td>


                        <td>
                            <?php   echo JHtmlString::truncateComplex($subject->message,50); ?>
                        </td>
                        <td>
                            <?php
                            echo $subject->ufirstname;
                            ?>
                        </td>
                        <td>
                            <a href="<?php echo JRoute::_(ARoute::view('message', null, null, array('parent_id' => $subject->id, 'cid_from' => $subject->cid_from, 'layout' => 'edit'))); ?>" class="btn btn-success">Reply</a>
                        </td>


                    </tr>
    <?php
}
?>
            </tbody>
        </table>


