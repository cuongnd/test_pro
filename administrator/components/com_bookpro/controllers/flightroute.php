<?php
class BookproControllerFlightroute extends JControllerForm{
	protected function postSaveHook(JModelLegacy $model, $validData = array())
	{
		$task = $this->getTask();
	
		if ($task == 'save')
		{
			$this->setRedirect(JRoute::_('index.php?option=com_bookpro&view=flightroutes', false));
		}
	}
}