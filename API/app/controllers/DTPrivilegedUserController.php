<?php

class DTPrivilegedUserController extends \BaseDatatableController {
	
	// Map input columns to database table fields, does not observe 'in' prefix for the key
	// protected static $inputColumnsMapping = [<<input_columns>>];
	
	
	public function __construct() {
		$this->modelName = 'PrivilegedUser';
		$this->initColumns = ['F_FLAGGED' => 'F_FLAGGED', 'MK' => 'ID', 'FIRSTNAME' => 'First Name', 'EMAIL' => 'Email'];
		$this->indexView = 'DTPrivilegedUserController.index'; 
		parent::__construct();
	}
	
	
	public function indexArchive()
	{
		$fetched = $this->modelInstance->fetchJson($this->initColumns, true);
		$success = json_decode($fetched, true)['success'];
		if ($success) return View::make($this->indexView)->with(['json' => $fetched]);
		else return '<strong>Requested database fields does not exist.</strong>';
	}
		
	
}

?>
