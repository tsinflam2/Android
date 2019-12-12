<?php

class DTRegisteredUserController extends \BaseDatatableController {
	
	// Map input columns to database table fields, does not observe 'in' prefix for the key
	// protected static $inputColumnsMapping = [<<input_columns>>];
	
	
	public function __construct() {
		$this->modelName = 'RegisteredUser';
		$this->initColumns = ['F_FLAGGED' => 'F_FLAGGED', 'MK' => 'ID', 'USERNAME' => 'Username', 'F_CREATED' => 'Registered'];
		$this->indexView = 'dtusers.index'; 
		parent::__construct();
	}
	
	// Override load datatable
	public function index() {
		if (Input::get('archived') == 'true')
			return self::indexArchive();
		else if (Input::get('suspended') == 'true')
			return self::indexSuspended();
		else {
			// default view
			return parent::index();
		}	
	}
	
	public function indexArchive()
	{
		$fetched = $this->modelInstance->fetchJson($this->initColumns, true);
		$success = json_decode($fetched, true)['success'];
		if ($success) return View::make($this->indexView)->with(['json' => $fetched]);
		else return '<strong>Requested database fields does not exist.</strong>';
	}
	
	public function indexSuspended()
	{
	
		
		$fetched = $this->modelInstance->fetchJson($this->initColumns, false, 'SUSPENDEDAT IS NOT NULL');
		$success = json_decode($fetched, true)['success'];
		
		if ($success) return View::make($this->indexView)->with(['json' => $fetched]);
		else return '<strong>Requested database fields does not exist.</strong>';
	}
	
	
		
	public function query()
	{
		$draw = Input::get('draw');
		
		
		
		$orderBy = Input::get('order')[0]['dir'];
		$orderByColumn = Input::get('order')[0]['column'];
		//$temp = json_decode($temp)['dir'];
		
		//$ar1 = array(array(null, null,  'XD', 'HD'));
		
		$orderByColumnName = Input::get('columns')[$orderByColumn]['name'];
		
		
		
		$fetched = $this->modelInstance->fetchJson($this->initColumns);
		$success = json_decode($fetched, true)['success'];
		
		$ajaxData = json_decode($fetched, true)['data'];
		//dd($ajaxData);
		
		$ajaxData = json_decode($ajaxData, true);
		
		$processedData = array();
		foreach ($ajaxData as $value)
			array_push($processedData, $value);
		//dd($processedData);
		
		$finalData = array();
		foreach ($processedData as $subArray) {
			$rowData = array('null'); // first column is null, reserve for check box
			foreach ($subArray as $key => $value) {
				array_push($rowData, $value);
			}
			array_push($finalData, $rowData);
		}
		//dd($finalData);
		
		
		
		
		
		
		
		$arr = array('draw'=> $draw, 'recordsTotal' => 1, 'recordsFiltered' => 1, 'data' => $finalData, 'col' => $orderByColumnName);
		return json_encode($arr);
	}
	
	/*
	public function index()
	{
		
		
		
		if ($success) return View::make($this->indexView)->with(['json' => $fetched]);
		else return '<strong>Requested database fields does not exist.</strong>';
		
	}
	*/
	
}

?>
