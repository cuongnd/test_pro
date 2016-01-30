<?php
/**
 * @company		:	BriTech Solutions
 * @created by	:	JoomBri Team
 * @contact		:	www.joombri.in, support@joombri.in
 * @created on	:	16 March 2012
 * @file name	:	models/guest.php
 * @copyright   :	Copyright (C) 2012. All rights reserved.
 * @license     :	GNU General Public License version 2 or later
 * @author      :	Faisel
 * @description	: 	Entry point for the component (jblance)
 */
 defined('_JEXEC') or die('Restricted access');
 
 jimport('joomla.application.component.model');
 
 class JblanceModelMembership extends JModelLegacy {

	 public static  $plan_history=null;
 	//17.Subscribe to a Plan
	function getPlanAdd(){
		$app =& JFactory::getApplication();
		$db	= & JFactory::getDBO();
		$user 	=& JFactory::getUser();
		
		//get the user group id from the session
		$session =& JFactory::getSession();
		$ugid = $session->get('ugid', 0, 'register');
		
		if(empty($ugid)){
			$jbuser = JblanceHelper::get('helper.user');
			$ugroup = $jbuser->getUserGroupInfo($user->id, null);
			$ugid = $ugroup->id;
			
			//if user group is empty, the user is not subscribed to any JoomBri group. So direct him to usergroup selection page
			if(empty($ugid)){
				$msg = JText::_('COM_JBLANCE_PLEASE_SELECT_USERGROUP_BEFORE_CONTINUE');
				$return	= JRoute::_('index.php?option=com_jblance&view=guest&layout=showfront', false);
				$app->redirect($return, $msg);
			}
		}
    
	  	$query = "SELECT p.id AS planid, SUM(s.access_count) plan_count FROM #__jblance_plan p ".
			     "LEFT JOIN #__jblance_plan_subscr s ON s.plan_id = p.id ".
			     "WHERE s.approved=1 AND s.user_id = $user->id ".
			   	 "GROUP BY p.id";
	    $db->setQuery($query);
	   	$plans = $db->loadObjectList('planid');
	    
	    $query = "SELECT p.* FROM #__jblance_plan AS p
			    WHERE p.ug_id=".$db->quote($ugid)." AND p.published = 1 and p.invisible = 0 
			    ORDER BY p.ordering ASC";
	    $db->setQuery($query);
	    $rows = $db->loadObjectList();
	    
		$return[0] = $rows;
		$return[1] = $plans;
		return $return;
	}
	
	//16.History of plans Subscribed to
	function getPlanHistory(){
		$app 	=& JFactory::getApplication();
		$user 	= JFactory::getUser();
		$db		=& JFactory::getDBO();
		$subid 	=& $app->input->get('subid', 0, 'int');
	
		$query = "SELECT s.*,p.name,p.id plan_id,(TO_DAYS(s.date_expire) - TO_DAYS(NOW())) AS daysleft ".
				 "FROM #__jblance_plan_subscr AS s ".
				 "LEFT JOIN #__jblance_plan AS p ON p.id = s.plan_id ".
				 "WHERE s.user_id = $user->id AND p.published = 1 ".
				 "ORDER BY s.id DESC";
		$db->setQuery($query);


		$cache = JFactory::getCache('_jblance', 'callback');
		try
		{
			$rows  = $cache->get(array($db, 'loadObjectList'), array(), null, false);

			/**
			 * Verify $components is an array, some cache handlers return an object even though
			 * the original was a single object array.
			 */
			if (!is_array($rows))
			{
				static::$plan_history = $rows;
			}
		}
		catch (RuntimeException $e)
		{
			// Fatal error.
			JLog::add(JText::sprintf('JLIB_APPLICATION_ERROR_PROJECT_NOT_LOADING', '', $e->getMessage()), JLog::WARNING, 'jerror');

			return false;
		}


		$finish = '';
		if($subid > 0){
		$query = "SELECT finish_msg FROM #__jblance_plan WHERE id = ".$subid;
		$db->setQuery($query);
		$finish = $db->loadResult();
	}
		$return[0] = static::$plan_history;
		$return[1] = $finish;
	
		return $return;
	}
	
	//20.Subscription Checkout
	function getPlanCheckout(){
		$app  = JFactory::getApplication();
		//get the subscription id from the session
		$session =& JFactory::getSession();
		$id = $session->get('id', 0, 'upgsubscr');
	
		//the subscr id is not set in session, get from the 'GET' request
		if(($id == 0))
			$id = $app->input->get('id', 0, 'int');
	
		$subscr	=& JTable::getInstance('plansubscr', 'Table');
		$subscr->load($id);
	
		$plan	=& JTable::getInstance('plan', 'Table');
		$plan->load($subscr->plan_id);
	
		$return[0] = $subscr;
		$return[1] = $plan;
	
		return $return;
	}
	
	//20.Deposit Checkout
	function getDepositCheckout(){
		$app = JFactory::getApplication();
		$id  = $app->input->get('id', 0, 'int');
		
		$deposit	=& JTable::getInstance('deposit', 'Table');
		$deposit->load($id);	
		
		$return[0] = $deposit;
		return $return;
	}
	
	//19.Plan Bank Transfer
	function getPlanBankTransfer(){
	
		$app = JFactory::getApplication();
		$id  = $app->input->get('id', 0, 'int');
		$subscr	=& JTable::getInstance('plansubscr', 'Table');
		$subscr->load($id);
	
		$plan	=& JTable::getInstance('plan', 'Table');
		$plan->load($subscr->plan_id);
	
		$payconfig = JblanceHelper::getPaymodeInfo('banktransfer');
	
		$return[0] = $subscr;
		$return[1] = $plan;
		$return[2] = $payconfig;
	
		return $return;
	}
	//19.Bank Transfer
	function getDepositBankTransfer(){
	
		$app = JFactory::getApplication();
		$id  = $app->input->get('id', 0, 'int');
		$deposit	=& JTable::getInstance('deposit', 'Table');
		$deposit->load($id);
	
		$payconfig = JblanceHelper::getPaymodeInfo('banktransfer');
	
		$return[0] = $deposit;
		$return[1] = $payconfig;
	
		return $return;
	}
	
	//11.Show Balance
	function getTransaction(){
		$app =& JFactory::getApplication();
		$user	=& JFactory::getUser();
		$db		=& JFactory::getDBO();
	
		$limit		= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart	= $app->input->get('limitstart', 0, 'int');
	
		$query = "SELECT MAX(id) FROM #__jblance_transaction WHERE user_id = ".$db->quote($user->id);
		$db->setQuery( $query );
		$id_max = $db->loadResult();
	
		$last_trans =& JTable::getInstance('transaction', 'Table');
		$last_trans->load($id_max);
		
		
		$query = "SELECT * FROM #__jblance_transaction ".
				 "WHERE user_id =".$db->quote($user->id)." ORDER BY date_trans DESC";
		$db->setQuery($query);
		$db->execute();
		$total = $db->getNumRows();
		
		jimport('joomla.html.pagination');
		$pageNav = new JPagination($total, $limitstart, $limit);
		
		$db->setQuery($query, $pageNav->limitstart, $pageNav->limit);
		$rows = $db->loadObjectList();
		
		$total_amt = JblanceHelper::getTotalFund($user->id);
		
		$return[0] = $rows;
		$return[1] = $pageNav;
		$return[2] = $last_trans;
		$return[3] = $total_amt;
		
		return $return;
	}
	
	function getWithdrawFund(){
		
		$app  = JFactory::getApplication();
		$db =& JFactory::getDBO();
		$gateway = $app->input->get('gateway', '', 'string');
		
		$query = "SELECT * FROM #__jblance_paymode ".
				 "WHERE published=1 AND withdraw=1 ".
				 "ORDER BY ordering";
		$db->setQuery($query);
		$paymodes = $db->loadObjectList();
		
		$return[0] = $paymodes;
		return $return;
	}
	
	function getEscrow(){
		$db =& JFactory::getDBO();
		$user	=& JFactory::getUser();
		
		$query = "SELECT id AS value, project_title AS text FROM #__jblance_project ".
				 "WHERE publisher_userid=$user->id AND status=".$db->quote('COM_JBLANCE_CLOSED')." AND paid_status<>".$db->quote('COM_JBLANCE_PYMT_COMPLETE');
		$db->setQuery($query);
		$projects = $db->loadObjectList();
		
		if(empty($projects)){
			$lists = JText::_('COM_JBLANCE_NO_PROJECTS_WITH_CHOSEN_WINNERS');
		}
		else {
			$types[] = JHTML::_('select.option', '', '- '.JText::_('COM_JBLANCE_PLEASE_SELECT').' -');
			foreach($projects as $item){
				$types[] = JHTML::_('select.option', $item->value, $item->text);
			}
			$lists 	= JHTML::_('select.genericlist', $types, 'project_id', "class='inputbox required' size='5' onclick='fillProjectInfo();'", 'value', 'text', '');
		}

		$return[0] = $lists;
		return $return;
	}
	
	function getManagepay(){
		$app 	=& JFactory::getApplication();
		$db 	=& JFactory::getDBO();
		$user 	=& JFactory::getUser();
		jimport('joomla.html.pagination');
		
		$limit		= $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart	= $app->input->get('limitstart', 0, 'int');
		
		$query = "SELECT e.*,p.project_title FROM #__jblance_escrow e ".
				 "LEFT JOIN #__jblance_project p ON p.id=e.project_id ".
				// "WHERE from_id= ".$user->id." AND e.status='' ".
				 "WHERE from_id= ".$user->id." ".
				 "ORDER BY id DESC";
		$db->setQuery($query);
		$escrow_out = $db->loadObjectList();
		
		$query = "SELECT e.*,p.project_title FROM #__jblance_escrow e ".
				 "LEFT JOIN #__jblance_project p ON p.id=e.project_id ".
				 //"WHERE to_id= ".$user->id." AND e.status=".$db->quote('COM_JBLANCE_RELEASED')." ".
				 "WHERE to_id= ".$user->id." ".
				 "ORDER BY id DESC";
		$db->setQuery($query);
		$escrow_in = $db->loadObjectList();
		
		$query = "SELECT * FROM #__jblance_withdraw WHERE user_id= ".$user->id." ORDER BY approved";
		$db->setQuery($query);
		$db->execute();
		$total = $db->getNumRows();
		$pageNavWithdraw = new JPagination($total, $limitstart, $limit);
		$db->setQuery($query, $pageNavWithdraw->limitstart, $pageNavWithdraw->limit);
		$withdraws = $db->loadObjectList();
		
		$query = "SELECT * FROM #__jblance_deposit WHERE user_id= ".$user->id." ORDER BY approved";
		$db->setQuery($query);
		$db->execute();
		$total = $db->getNumRows();
		$pageNavDeposit = new JPagination($total, $limitstart, $limit);
		$db->setQuery($query, $pageNavDeposit->limitstart, $pageNavDeposit->limit);
		$deposits = $db->loadObjectList();
		
		$return[0] = $escrow_out;
		$return[1] = $escrow_in;
		$return[2] = $withdraws;
		$return[3] = $deposits;
		$return[4] = $pageNavWithdraw;
		$return[5] = $pageNavDeposit;
		return $return;
	}
	
	function getInvoice(){
		$app 	=& JFactory::getApplication();
		$user	=& JFactory::getUser();
		$id 	= $app->input->get('id', 0, 'int');
		$type 	= $app->input->get('type', '', 'string');
		$db 	=& JFactory::getDBO();
		
		if($type == 'plan'){
			$query = "SELECT ps.*,ps.date_buy AS invoiceDate,p.name AS planname,u.email,u.name,ju.biz_name FROM #__jblance_plan_subscr ps ".
					 "LEFT JOIN #__users u ON ps.user_id=u.id ".
					 "LEFT JOIN #__jblance_plan p ON p.id=ps.plan_id ".
					 "LEFT JOIN #__jblance_user ju ON ju.user_id=ps.user_id ".
					 "WHERE ps.id=".$db->quote($id)." AND ps.user_id=".$db->quote($user->id);
		}
		elseif($type == 'deposit'){
			$query = "SELECT d.*,d.date_deposit AS invoiceDate,u.email,u.name,ju.biz_name FROM #__jblance_deposit d ".
					 "LEFT JOIN #__users u ON d.user_id=u.id ".
					 "LEFT JOIN #__jblance_user ju ON ju.user_id=d.user_id ".
					 "WHERE d.id=".$db->quote($id)." AND d.user_id=".$db->quote($user->id);
		}
		elseif($type == 'withdraw'){
			$query = "SELECT w.*,w.date_withdraw AS invoiceDate,u.email,u.name,ju.biz_name FROM #__jblance_withdraw w ".
					 "LEFT JOIN #__users u ON w.user_id=u.id ".
					 "LEFT JOIN #__jblance_user ju ON ju.user_id=w.user_id ".
					 "WHERE w.id=".$db->quote($id)." AND w.user_id=".$db->quote($user->id);
		}
		
		$db->setQuery($query);//echo $query;
		$row = $db->loadObject();
		
		$return[0] = $row;
		//$return[1] = $billingAddress;
		return $return;
	}
	
	function getPlanDetail(){
		$app =& JFactory::getApplication();
		$db	 =& JFactory::getDBO();
		$id  = $app->input->get('id', 0, 'int');
		
		$query = "SELECT s.*,p.id planid,p.name FROM #__jblance_plan_subscr s ".
				 "JOIN #__jblance_plan p ON p.id=s.plan_id ".
				 "WHERE s.id=".$id;
		$db->setQuery($query);
		$row = $db->loadObject();
		
		$return[0] = $row;
		return $return;
	}
	
	function getThankPayment(){
		$app  = JFactory::getApplication();
		$oid  = $app->input->get('oid', 0, 'int');
		$buy  = $app->input->get('buy', '', 'string');	//either buy deposit or plan
		$db	  =& JFactory::getDBO();
		
		$obj = new stdClass();
		
		if($buy == 'plan'){
			$row	=& JTable::getInstance('plansubscr', 'Table');
			$row->load($oid);
			
			$query = "SELECT * FROM #__jblance_plan WHERE id = ".$row->plan_id;
			$db->setQuery($query);
			$plan = $db->loadObject();
			
			$obj->itemName 	= JText::_('COM_JBLANCE_BUY_SUBSCR').' - '.$plan->name;
			$obj->invoiceNo = $row->invoiceNo;
			$obj->status 	= $row->approved;
			$obj->amount 	= (float)($row->price + $row->price * ($row->tax_percent/100));
			$obj->gateway 	= JblanceHelper::getPaymodeInfo($row->gateway)->gateway_name;
			$obj->lnk_continue 	= JRoute::_('index.php?option=com_jblance&view=membership&layout=planhistory', false);
			$obj->lnk_invoice 	= JRoute::_('index.php?option=com_jblance&view=membership&layout=invoice&id='.$row->id.'&tmpl=component&print=1&type=plan');
		}
		elseif($buy == 'deposit'){
			$row 	=& JTable::getInstance('deposit', 'Table');
			$row->load($oid);
			
			$obj->itemName 	=  JText::_('COM_JBLANCE_DEPOSIT_FUNDS');
			$obj->invoiceNo = $row->invoiceNo;
			$obj->status 	= $row->approved;
			$obj->amount 	= $row->total;
			$obj->gateway 	= JblanceHelper::getPaymodeInfo($row->gateway)->gateway_name;
			$obj->lnk_continue 	= JRoute::_('index.php?option=com_jblance&view=membership&layout=managepay', false);
			$obj->lnk_invoice 	= JRoute::_('index.php?option=com_jblance&view=membership&layout=invoice&id='.$row->id.'&tmpl=component&print=1&type=deposit');
		}
		
		$return[0] = $obj;
		return $return;
	}
	
	/* Misc Functions */
	
	//13.getSelectPaymode
	function getSelectPaymode($var, $default, $disabled){
		$app =& JFactory::getApplication();
		$db	= & JFactory::getDBO();
	
		$option = '';
		if($disabled == 1)
			$option = 'disabled';
	
		//make selection salutation
		$query = "SELECT gwcode AS value, gateway_name AS text FROM #__jblance_paymode ".
				 "WHERE published=1 ".
				 "ORDER BY ordering";
		$db->setQuery($query);
		$paymodes = $db->loadObjectList();
	
		foreach($paymodes as $item){
			$types[] = JHTML::_('select.option', $item->value, JText::_($item->text));
		}
	
		$lists 	= JHTML::_('select.genericlist', $types, $var, 'class="inputbox" size="1" style="width:200px;"  '.$option.'', 'value', 'text', $default);
		return $lists;
	}
	
	function buildPlanInfo($planId){
		
		$config =& JblanceHelper::getConfig();
		$currencysym = $config->currencySymbol;
	
		//initialize variables
		$jbuser = JblanceHelper::get('helper.user');		// create an instance of the class userHelper
		$planInfo = $jbuser->getPlanInfo($planId);
	
		//get the plan details for the plan id passed
		$ugid = $planInfo->ug_id;
	
		//get the user group of the plan and thereby, get the usergroup info.
		$ugInfo = $jbuser->getUserGroupInfo(null, $ugid);
	
		$i = 0;
		$infos = '';
		//get the keys and values for the allowed functions.
		if($ugInfo->allowBidProjects){
			$infos[$i] = new stdClass();
			$infos[$i]->key = JText::_('COM_JBLANCE_PROJECT_COMMISSION');
			//$infos[$i]->value = "<span class=hasTip title=\'".JText::_('COM_JBLANCE_WHICHEVER_HIGHER')."\'>".JblanceHelper::formatCurrency($planInfo->flFeeAmtPerProject, $currencysym).' '.JText::_('COM_JBLANCE_OR').' '.$planInfo->flFeePercentPerProject.'%'."</span>";
			$infos[$i]->value = JblanceHelper::formatCurrency($planInfo->flFeeAmtPerProject, $currencysym).' '.JText::_('COM_JBLANCE_OR').' '.$planInfo->flFeePercentPerProject.'%';
			$i++;
			
			$infos[$i] = new stdClass();
			$infos[$i]->key = JText::_('COM_JBLANCE_CHARGE_PER_BID');
			$infos[$i]->value = ($planInfo->flChargePerBid == 0) ? JText::_('COM_JBLANCE_FREE') : JblanceHelper::formatCurrency($planInfo->flChargePerBid, $currencysym);
			$i++;
			$infos[$i] = new stdClass();
			$infos[$i]->key = JText::_('COM_JBLANCE_NUM_PORTFOLIOS_ALLOWED');
			$infos[$i]->value = ($planInfo->portfolioCount == 0) ? "<img src=".JURI::root()."components/com_jblance/images/s0.png width=12 alt=No />" : $planInfo->portfolioCount;
			$i++;
	
		}
		if($ugInfo->allowPostProjects){
			$infos[$i] = new stdClass();
			$infos[$i]->key = JText::_('COM_JBLANCE_PROJECT_COMMISSION');
			//$infos[$i]->value = "<span class=hasTip title='".JText::_('COM_JBLANCE_WHICHEVER_HIGHER')."'>".JblanceHelper::formatCurrency($planInfo->buyFeeAmtPerProject, $currencysym).' '.JText::_('COM_JBLANCE_OR').' '.$planInfo->buyFeeAmtPerProject.'%'."</span>";
			$infos[$i]->value = JblanceHelper::formatCurrency($planInfo->buyFeeAmtPerProject, $currencysym).' '.JText::_('COM_JBLANCE_OR').' '.$planInfo->buyFeeAmtPerProject.'%';
			$i++;
	
			$infos[$i] = new stdClass();
			$infos[$i]->key = JText::_('COM_JBLANCE_CHARGE_PER_PROJECT');
			$infos[$i]->value = ($planInfo->buyChargePerProject == 0) ? JText::_('COM_JBLANCE_FREE') : JblanceHelper::formatCurrency($planInfo->buyChargePerProject, $currencysym);
			$i++;
			
			$infos[$i] = new stdClass();
			$infos[$i]->key = JText::_('COM_JBLANCE_FEATURED_PROJECT');
			$infos[$i]->value = ($planInfo->buyFeePerFeaturedProject == 0) ? JText::_('COM_JBLANCE_FREE') : JblanceHelper::formatCurrency($planInfo->buyFeePerFeaturedProject, $currencysym);
			$i++;
			
			$infos[$i] = new stdClass();
			$infos[$i]->key = JText::_('COM_JBLANCE_URGENT_PROJECT');
			$infos[$i]->value = ($planInfo->buyFeePerUrgentProject == 0) ? JText::_('COM_JBLANCE_FREE') : JblanceHelper::formatCurrency($planInfo->buyFeePerUrgentProject, $currencysym);
			$i++;
			
			$infos[$i] = new stdClass();
			$infos[$i]->key = JText::_('COM_JBLANCE_PRIVATE_PROJECT');
			$infos[$i]->value = ($planInfo->buyFeePerPrivateProject == 0) ? JText::_('COM_JBLANCE_FREE') : JblanceHelper::formatCurrency($planInfo->buyFeePerPrivateProject, $currencysym);
			$i++;
			
			$infos[$i] = new stdClass();
			$infos[$i]->key = JText::_('COM_JBLANCE_SEALED_PROJECT');
			$infos[$i]->value = ($planInfo->buyFeePerSealedProject == 0) ? JText::_('COM_JBLANCE_FREE') : JblanceHelper::formatCurrency($planInfo->buyFeePerSealedProject, $currencysym);
			$i++;
			
			$infos[$i] = new stdClass();
			$infos[$i]->key = JText::_('COM_JBLANCE_NDA_PROJECT');
			$infos[$i]->value = ($planInfo->buyFeePerNDAProject == 0) ? JText::_('COM_JBLANCE_FREE') : JblanceHelper::formatCurrency($planInfo->buyFeePerNDAProject, $currencysym);
			$i++;
		}
	
		return $infos;
	}
 	
	function countManagePayPending($type){
		$db 	=& JFactory::getDBO();
		$user	=& JFactory::getUser();
		
		if($type == 'deposit')
			$query = "SELECT COUNT(*) FROM #__jblance_deposit WHERE user_id= ".$user->id." AND approved=0";
		elseif($type == 'withdraw')
			$query = "SELECT COUNT(*) FROM #__jblance_withdraw WHERE user_id= ".$user->id." AND approved=0";
		elseif($type == 'escrowout')
			$query = "SELECT COUNT(*) FROM #__jblance_escrow WHERE from_id= ".$user->id." AND status=''";
		elseif($type == 'escrowin')
			$query = "SELECT COUNT(*) FROM #__jblance_escrow WHERE to_id= ".$user->id." AND status='COM_JBLANCE_RELEASED'";
		
		$db->setQuery($query);
		$total 	= $db->loadResult();
		return $total;
	}
 }