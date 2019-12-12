<?php

class ModelManagementController extends \BaseController {

	// Create a data model file, returns 1 if success
	public function createModel($modelName)
	{
	
		$tableName = Input::get("table");
	
		$file = '../app/baremodel/BareModel.php';
		$newfile = '../app/models/'.$modelName.'.php';

		
		
		
		if (!copy($file, $newfile)) {
			return 0;
		}
		
		// Get all fields in table
		$cols = App::make('DatabaseSchemaController')->getTableColumnNames($tableName);
		
		
		// Write new file
		$changedFile = file_get_contents($newfile);
		$changedFile = str_replace("<<internal_table_name>>", $tableName,$changedFile);
		$changedFile = str_replace("<<model_class_name>>", $modelName,$changedFile);
		$changedFile = str_replace("<<cs_fillable_fields>>", implode("', '", $cols),$changedFile);
		$changedFile = str_replace("<<prefixless_table_name>>", substr($tableName, 2),$changedFile);
		file_put_contents($newfile, $changedFile);
		
		return 1;
		
	}

}
