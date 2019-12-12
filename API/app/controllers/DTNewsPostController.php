<?php

class DTNewsPostController extends \BaseDatatableController {
	
	// Map input columns to database table fields, does not observe 'in' prefix for the key
	// protected static $inputColumnsMapping = [<<input_columns>>];
	
	
	public function __construct() {
		$this->modelName = 'NewsPost';
		$this->initColumns = ['F_FLAGGED' => 'F_FLAGGED', 'MK' => 'ID', 'NEWSTITLE' => 'News Title', 'POSTEDAT' => 'Posted At'];
		$this->indexView = 'dtnewsposts.index'; 
		parent::__construct();
	}
	
	// Override load datatable
	public function index() {
		if (Input::get('archived') == 'true')
			return self::indexArchive();
		else if (Input::get('onhold') == 'true')
			return self::indexOnHold();
		else if (Input::has('fkuserid')) {
			return self::indexFkUserId(Input::get('fkuserid'));
		} else {
			// default view
			//return parent::index();
			$fetched = $this->modelInstance->fetchJson($this->initColumns, false, 'ON_HOLD = 0');
			$success = json_decode($fetched, true)['success'];
			
			if ($success) return View::make($this->indexView)->with(['json' => $fetched]);
			else return '<strong>Requested database fields does not exist.</strong>';
		}	
	}
	
	
	public function indexArchive()
	{
	
		
		$fetched = $this->modelInstance->fetchJson($this->initColumns, true);
		$success = json_decode($fetched, true)['success'];
		
		if ($success) return View::make($this->indexView)->with(['json' => $fetched]);
		else return '<strong>Requested database fields does not exist.</strong>';
	}
	
	public function indexOnHold()
	{
	
		
		$fetched = $this->modelInstance->fetchJson($this->initColumns, false, 'ON_HOLD = 1');
		$success = json_decode($fetched, true)['success'];
		
		if ($success) return View::make($this->indexView)->with(['json' => $fetched]);
		else return '<strong>Requested database fields does not exist.</strong>';
	}
	
	// Filter with userId
	public function indexFkUserId($userId)
	{
		$fetched = $this->modelInstance->fetchJson($this->initColumns, false, 'REGISTEREDUSER_MK = '.$userId);
		$success = json_decode($fetched, true)['success'];
		
		if ($success) return View::make($this->indexView)->with(['json' => $fetched]);
		else return '<strong>Requested database fields does not exist.</strong>';
	}
		
	public function bulk()
	{
		//$temp = static::$modelName;
		$temp = $this->modelName;
		
		// reroute basic bulk reuqest to appropriate model function
		$affected_mk = Input::get('affectedmk');
		
		
		switch (Input::get('action')) {
			case 'isspam':
				$this->modelInstance->deleteForever(explode(',', $affected_mk));
				break;
			case 'notspam':
				$this->modelInstance->unhold(explode(',', $affected_mk));
				break;
			default:
				parent::bulk();
		}
	}


	
}

?>
