<?php

class CNewsImageController extends \BaseClientController {
	
	// Map input columns to database table fields, does not observe 'in' prefix for the key
	// protected static $inputColumnsMapping = [<<input_columns>>];
	
	public function __construct() {
		$this->modelName = 'NewsImage';
		parent::__construct();
	}
	
	// Override load datatable
	public function index() {
	
		/* Get newspost author info
		$request = Request::create('/cgeneralusers', 'GET', array());
		$response = Route::dispatch($request)->getContent();
		*/
		
		// Get news post info	
		// Sorting (with default option)
		$sort = Input::get('sort', '-addedat');
		// Get certain fields (with default option)
		$fields = Input::get('fields', 'filename');
		
		$fromRecord = Input::get('fromRecord','0');
		$recordCount = Input::get('recordCount','10');	
		
		return parent::requestData($fields, $sort, null, $fromRecord, $recordCount);
	}
	
	// Save new record
	public function store() {
		//Input::flash();
		dd(Request::all());
	dd(Input::all());
		$obj = new $this->modelName;

		$obj->F_CREATE_KEY = parent::getMomentString('YmdHisu').'/'.parent::getIP();

		$obj->FILENAME = Input::get('filename');
		$obj->REGISTEREDUSER_MK = Input::get('registereduser_mk');
		$obj->NEWSPOST_MK = Input::get('newspost_mk');
		
		
		$obj->save();
		
	}
		
	// Update specific record
	public function update($id) {
		// Not allowed
		return \Response::json(array('success' => false),405);
	}
		
	
}

?>
