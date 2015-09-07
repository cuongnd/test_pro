<?php
class BookproControllerFlightroutes extends JControllerAdmin{
	public function getModel($name = 'flightroute', $prefix = 'BookproModel', $config = array('ignore_request' => true)) {
		$model = parent::getModel($name, $prefix, $config);
		return $model;
	}
}