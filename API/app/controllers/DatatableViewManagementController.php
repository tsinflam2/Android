<?php

class DatatableViewManagementController extends \BaseController {

	// Create a data model file, returns 1 if success
	public function createDatatableView($datatableViewFolderName)
	{
		if (!file_exists('../app/views/'.$datatableViewFolderName)) {
			mkdir('../app/views/'.$datatableViewFolderName, 0777, true);
		}	
	
		$file = '../app/views/bareview/index.blade.php';
		$newfile = '../app/views/'.$datatableViewFolderName.'/index.blade.php';

		if (!copy($file, $newfile)) {
			return 0;
		}
		
		
		
		
		// Write new file
		$changedFile = file_get_contents($newfile);
		$changedFile = str_replace("<<namespace>>", $datatableViewFolderName,$changedFile);
		$changedFile = str_replace("<<edit_title>>", Input::get('edittitle'),$changedFile);
		$changedFile = str_replace("<<add_title>>", Input::get('addtitle') ,$changedFile);
		$changedFile = str_replace("<<add_box>>", Input::get('addbox') ,$changedFile);
		$changedFile = str_replace("<<edit_box>>", Input::get('editbox') ,$changedFile);
		$changedFile = str_replace("<<table_title>>", Input::get('tabletitle') ,$changedFile);
		
		
		
		file_put_contents($newfile, $changedFile);
		

		
		return 1;
		
	}

}
