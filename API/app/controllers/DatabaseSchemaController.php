<?php

class DatabaseSchemaController extends \BaseController {


	public function getTableNames()
	{
		return BaseModel::getAllTableNames();
		
	}
	
	public function getTableColumnNames($tableName)
	{
		return BaseModel::getAllColumnsNames($tableName);
	}

}
