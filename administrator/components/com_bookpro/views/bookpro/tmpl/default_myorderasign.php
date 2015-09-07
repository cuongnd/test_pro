<fieldset>
                    <legend>
                        <?php echo JText::_('My order asign'); ?> <div class="pull-right">
                            <a href="<?php echo Juri::base() ?>index.php?option=com_bookpro&view=orders"> View all </a>
                        </div>
                    </legend>

                    <form action="index.php" method="post" name="adminForm" id="adminForm">



                        <table class="table table-striped ">
                            <thead>
                                <tr>


                                    <th><?php echo JText::_("COM_BOOKPRO_CUSTOMER"); ?>	</th>
                                    <th><?php echo JText::_("COM_BOOKPRO_ORDER_NUMBER"); ?></th>
                                    <th><?php echo JText::_("COM_BOOKPRO_ORDER_TOTAL"); ?></th>
                                    <th><?php echo JText::_("COM_BOOKPRO_ORDER_PAY_STATUS"); ?></th>
                                    <th><?php echo JText::_("Actions"); ?></th>

                                </tr>
                            </thead>

                            <tbody>
                                <?php
                                $itemsCount = count($this->myordersign);
                                if ($itemsCount == 0) { ?>
                                    <tr>
                                        <td colspan="13" class="emptyListInfo"><?php echo JText::_('No booking today.'); ?></td>
                                    </tr>
                                <?php } ?>
                                <?php for ($i = 0; $i < $itemsCount; $i++) { ?>
                                    <?php $subject = &$this->myordersign[$i]; ?>

                                    <tr class="row<?php echo $i % 2; ?>">
                                        <td><a href="<?php echo JRoute::_(ARoute::edit(CONTROLLER_CUSTOMER, $subject->user_id)); ?>"><?php echo $subject->ufirstname; ?></a>
                                            <br>
                                            <?php echo JHtml::_('date', $subject->created, 'd-m H:i') ?>
                                        </td>

                                        <td><a href="<?php echo JRoute::_(ARoute::detail(CONTROLLER_ORDER, $subject->id)); ?>"><?php echo $subject->order_number; ?></a></td>
                                        <td><?php echo CurrencyHelper::formatprice($subject->total) ?></td>
                                        <td><?php echo PayStatus::format($subject->pay_status); ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-small dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                                <ul class="dropdown-menu">
                                                    <li><a href="#">Add payment</a></li>
                                                    <li><a href="#">View detail</a></li>
                                                    <li><a href="#">Edit</a></li>
                                                </ul>
                                            </div>

                                        </td>

                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>

                        <input type="hidden" name="option" value="<?php echo OPTION; ?>" />
                        <input	type="hidden" name="task" value="<?php echo JRequest::getCmd('task'); ?>" />
                        <input type="hidden" name="reset"	value="0" />
                        <input type="hidden" name="cid[]"	value="" />
                        <input type="hidden" name="boxchecked" value="0" />
                        <input	type="hidden" name="filter_order" value="<?php echo $order; ?>" />
                        <input	type="hidden" name="filter_order_Dir" value="<?php echo $orderDir; ?>" />
                        <input type="hidden" name="controller"	value="<?php echo CONTROLLER_ORDER; ?>" />
                        <?php echo JHTML::_('form.token'); ?>
                    </form>
                </fieldset>