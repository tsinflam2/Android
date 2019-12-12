<?php

class ControllerManagementController extends \BaseController {

	// Create a controller file, returns 1 if success
	public function createController($controllerName)
	{
		$controllerName = 'DT'.$controllerName;
		
		$file = '../app/barecontroller/BareDatatableController.php';
		$newfile = '../app/controllers/'.$controllerName.'.php';
		
		if (!copy($file, $newfile)) {
			return 0;
		}
		
		$internalFieldName = Input::get("internalFieldName");
		$friendlyFieldName = Input::get("friendlyFieldName");
		$internalFieldNameArr = explode(",",$internalFieldName);
		$friendlyFieldNameArr = explode(",",$friendlyFieldName);
		
		$init_columns = '';
		$pos = 0;
		foreach ($internalFieldNameArr as $internalField) {
			$init_columns .= "'".$internalField ."' => '".$friendlyFieldNameArr[$pos++]."', ";
		}
		
		// Write new file
		$changedFile = file_get_contents($newfile);
		$changedFile = str_replace("<<controller_class_name>>", $controllerName,$changedFile);
		$changedFile = str_replace("<<route_index>>", Input::get("namespace").'.index',$changedFile);
		$changedFile = str_replace("<<model_name>>", Input::get("modelName"),$changedFile);
		$changedFile = str_replace("<<init_columns>>", rtrim($init_columns, ", "),$changedFile);
		
		// For viewing archived records only
		$archivedIndex = "
	public function indexArchive()
	{
		\$fetched = \$this->modelInstance->fetchJson(\$this->initColumns, true);
		\$success = json_decode(\$fetched, true)['success'];
		if (\$success) return View::make(\$this->indexView)->with(['json' => \$fetched]);
		else return '<strong>Requested database fields does not exist.</strong>';
	}
		";
		$changedFile = str_replace("<<archived_index>>", $archivedIndex,$changedFile);
		
		
		
		
		file_put_contents($newfile, $changedFile);
		
		return 1;
		
		
	}

}

?>