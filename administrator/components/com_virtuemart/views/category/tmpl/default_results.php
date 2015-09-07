<?php
/**
*
* Lists all the categories in the shop
*
* @package	VirtueMart
* @subpackage Category
* @author RickG, jseros, RolandD, Max Milbers
* @link http://www.virtuemart.net
* @copyright Copyright (c) 2004 - 2010 VirtueMart Team. All rights reserved.
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
* VirtueMart is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* @version $Id: default.php 6477 2012-09-24 14:33:54Z Milbo $
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die('Restricted access');

if (!class_exists ('shopFunctionsF'))
	require(JPATH_VM_SITE . DS . 'helpers' . DS . 'shopfunctionsf.php');
if (JRequest::getCmd('tmpl') =='component' ) $front = '&tmpl=component';
else $front = '';
?>
	<div id="resultscounter"><?php echo $this->pagination->getResultsCounter(); ?></div>
	<table class="table table-striped">
		<thead>
		<tr>

			<th width="20">
				<input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this);" />
			</th>
			<th align="left" width="25%">
				<?php echo $this->sort('category_name') ?>
			</th>
			<th align="left" class="autosize">
				<?php echo $this->sort('category_description', 'COM_VIRTUEMART_DESCRIPTION') ; ?>
			</th>
			<th align="left" class="autosize">
				<?php echo JText::_('COM_VIRTUEMART_PRODUCT_S'); ?>
			</th>

			<th align="left" width="13%">
				<?php echo $this->sort( 'c.ordering' , 'COM_VIRTUEMART_ORDERING') ?>
				<?php echo JHTML::_('grid.order', $this->categories, 'filesave.png', 'saveOrder' ); ?>
			</th>
			<th align="center" width="20" class="autosize">
				<?php echo $this->sort('c.published' , 'COM_VIRTUEMART_PUBLISHED') ?>
			</th>
			<?php if(Vmconfig::get('multix','none')!=='none' and $this->perms->check('admin') ){ ?>
            <th width="20" class="autosize">

				<?php echo $this->sort( 'cx.category_shared' , 'COM_VIRTUEMART_SHARED') ?>
            </th width="20">
			<?php } ?>

			<th><?php echo $this->sort('virtuemart_category_id', 'COM_VIRTUEMART_ID')  ?></th>

		</tr>
		</thead>
		<tbody>
		<?php
		$k = 0;
		$repeat = 0;

 		$nrows = count( $this->categories );

		if( $this->pagination->limit < $nrows ){
			if( ($this->pagination->limitstart + $this->pagination->limit) < $nrows ) {
				$nrows = $this->pagination->limitstart + $this->pagination->limit;
			}
		}

// 		for ($i = $this->pagination->limitstart; $i < $nrows; $i++) {

		foreach($this->categories as $i=>$cat){

// 			if( !isset($this->rowList[$i])) $this->rowList[$i] = $i;
// 			if( !isset($this->depthList[$i])) $this->depthList[$i] = 0;

// 			$row = $this->categories[$this->rowList[$i]];
			$canDo = $this->canChange($cat->created_by);
			$checked = JHTML::_('grid.id', $i, $cat->virtuemart_category_id);
			$published = $this->toggle( $cat->published, $i, 'published',$canDo);

			//$showProductsLink = JRoute::_('index.php?option=com_virtuemart&view=product&virtuemart_category_id=' . $cat->virtuemart_category_id.$front);
			$shared = $this->toggle($cat->shared, $i, 'toggle.shared',$canDo);

			$px = 0;
			if(!isset($cat->level)){
				if($cat->category_parent_id){
					$cat->level = 1;
				} else {
					$cat->level = 0;
				}

			}
			$repeat = $cat->level;

			if($repeat > 1){
				$px = ($repeat - 2)*12;
				$categoryLevel = "<sup>&#x221f;</sup>";
			} else $categoryLevel ="";
		?>
			<tr >

				<td><?php echo $checked;?></td>
				<td align="left">
					<span class="categoryLevel" style="padding-left:<?php echo $px ?>px"><?php echo $categoryLevel;?></span>
					<?php echo $this->editLink($cat->virtuemart_category_id, $cat->treename, 'virtuemart_category_id')?>
				</td>
				<td class="autosize">

					<?php

					echo shopFunctionsF::limitStringByWord(JFilterOutput::cleanText($cat->category_description),50); ?>
				</td>
				<td>
					
					<a class="btn btn-mini btn-info" href="<?php echo $showProductsLink; ?>">
						<div><?php echo  $this->catmodel->countProducts($cat->virtuemart_category_id);//ShopFunctions::countProductsByCategory($row->virtuemart_category_id);?>
					</div>
						<?php echo JText::_('COM_VIRTUEMART_SHOW');?></a>
				</td>
				<td align="center" class="order">
					<span><?php 
					
					
					$cond = (($cat->category_parent_id == 0 || $cat->category_parent_id == @$this->categories[$i - 1]->category_parent_id));
					$cond2= ($cat->category_parent_id == 0 || $cat->category_parent_id == @$this->categories[$i + 1]->category_parent_id);
					echo $this->pagination->orderUpIcon( $i, true, 'orderUp', JText::_('COM_VIRTUEMART_MOVE_UP'), $cond); ?></span>
					<span><?php echo $this->pagination->orderDownIcon( $i, $nrows, true, 'orderDown', JText::_('COM_VIRTUEMART_MOVE_DOWN'), $cond2); ?></span>
					<input class="ordering input-mini" type="text" name="order[<?php echo $i?>]" id="order[<?php echo $i?>]" size="5" value="<?php echo $cat->ordering; ?>" style="text-align: center" />
				</td>
				<td align="center">
					<?php echo $published;?>
				</td>
				<?php
				if( Vmconfig::get('multix','none')!='none' && $this->perms->check('admin') ) {
					?><td align="center">
						<?php echo $shared; ?>
                    </td>
					<?php
				}
				?>
				<td><?php echo $cat->virtuemart_category_id; // echo $product->vendor_name; ?></td>
			</tr>
		<?php
			$k = 1 - $k;
		}
		?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="9">
					<?php echo $this->pagination->getListFooter(); ?>
				</td>
			</tr>
		</tfoot>
	</table>

	<?php echo $this->addStandardHiddenToForm($this->_name,$this->task);  ?>
