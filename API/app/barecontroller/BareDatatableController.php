<?php

class <<controller_class_name>> extends \BaseDatatableController {
	
	// Map input columns to database table fields, does not observe 'in' prefix for the key
	// protected static $inputColumnsMapping = [<<input_columns>>];
	
	
	public function __construct() {
		$this->modelName = '<<model_name>>';
		$this->initColumns = ['F_FLAGGED' => 'F_FLAGGED', <<init_columns>>];
		$this->indexView = '<<route_index>>'; 
		parent::__construct();
	}
	
	<<archived_index>>
	
}

?>
