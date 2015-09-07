<label ><strong><?php echo JText::_('COM_BOOKPRO_TOUR_PACKAGES'); ?></strong> </label>
					<?php echo $this->tourpackages; ?>
				    
					<label ><strong><?php echo JText::_('COM_BOOKPRO_START_DATE_'); ?></strong> </label> 
                    <?php echo JHtml::calendar('', 'startdate', 'startdate','%Y-%m-%d','readonly="readonly"') ?>
             
                    <label ><strong><?php echo JText::_('COM_BOOKPRO_END_DATE_'); ?></strong> </label>
                   <?php echo JHtml::calendar('', 'enddate', 'enddate','%Y-%m-%d','readonly="readonly"') ?>
                   
                    <label ><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_ADULT'); ?></strong> </label>
                    <input class="text_area" type="text" name="adult" id="adult" size="60" maxlength="255" /> 
                   
                    <label ><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_TEEN'); ?></strong> </label>
                    <input class="text_area" type="text" name="teen" id="teen" size="60" maxlength="255" />
                    
                    <label ><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_CHILD1'); ?></strong> </label>
                    <input class="text_area" type="text" name="child1" id="child1" size="60" maxlength="255" value="" /> 
                   
                    <label ><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_CHILD2'); ?></strong> </label>
                    <input class="text_area" type="text" name="child2" id="child2" size="60" maxlength="255" value="" /> 
                   
                    <label ><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_EXTRA_BED'); ?></strong> </label>
                    <input class="text_area" type="text" name="extra_bed" id="extra_bed" size="60" maxlength="255" value="" />   
                    
                    <label ><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_PRENIGHT'); ?></strong> </label>
                    <input class="text_area" type="text" name="prenight" id="prenight" size="60" maxlength="255" value="" />   
                    
                    <label ><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_PRETRANSFER'); ?></strong> </label>
                    <input class="text_area" type="text" name="pretransfer" id="pretransfer" size="60" maxlength="255" value="" />   
                    
                    <label ><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_POST_TRANSFER'); ?></strong> </label>
                    <input class="text_area" type="text" name="posttransfer" id="posttransfer" size="60" maxlength="255" value="" />  
                    
                    <label ><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_FRENIGHT_SGL'); ?></strong> </label>
                    <input class="text_area" type="text" name="prenight_sgl" id="prenight_sgl" size="60" maxlength="255" value="" /> 
                    
                    <label ><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_FRENIGHT_TWN'); ?></strong> </label>
                    <input class="text_area" type="text" name="prenight_twn" id="prenight_twn" size="60" maxlength="255" value="" /> 
                    
                    <label ><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_FRENIGHT_TPL'); ?></strong> </label>
                    <input class="text_area" type="text" name="prenight_tpl" id="prenight_tpl" size="60" maxlength="255" value="" />  
                    
                    <label ><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_POST_NIGHT_SGL'); ?></strong> </label>
                    <input class="text_area" type="text" name="postnight_sgl" id="postnight_sgl" size="60" maxlength="255" value="" /> 
                    
                    <label ><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_POST_NIGHT_TWN'); ?></strong> </label>
                    <input class="text_area" type="text" name="postnight_twn" id="postnight_twn" size="60" maxlength="255" value="" />
                    
                    <label ><strong><?php echo JText::_('COM_BOOKPRO_PACKAGE_RATE_POST_NIGHT_TPL'); ?></strong> </label>
                    <input class="text_area" type="text" name="postnight_tpl" id="postnight_tpl" size="60" maxlength="255" value="" />   