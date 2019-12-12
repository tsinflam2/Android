<?php

abstract class BaseClientController extends \BaseController {

	protected $modelName;
	
	protected $modelInstance; // change back to private if error
	
	public function __construct() {
		$this->modelInstance = new $this->modelName;
	}
	
	public function requestData($fields, $sort, $whereRaw, $fromRecordPos, $recordCount, $returnJsonResponse = true) {
		
		

		$fetched = $this->modelInstance->select(DB::raw($fields));
		
		if ($whereRaw != null) 
			$fetched = $fetched->whereRaw($whereRaw);
		
		try {
			// Order by
			if ($sort != null) {
				$sortByCriteria = explode(",",$sort);
				foreach ($sortByCriteria as $criteria) {
					if (substr($criteria, 0, 1) == '-')
						$fetched = $fetched->orderBy(substr($criteria, 1), 'desc');
					else
						$fetched = $fetched->orderBy($criteria, 'asc');
				}
			}
			
			// Limit
			$fetched = $fetched->skip($fromRecordPos)->take($recordCount)->get()->toArray();
			//$fetched = json_decode($fetched, true);

			$finalArray = array();
			foreach ($fetched as $row) {
				// Rebuild array to keep double as double instead of string
				$rowArray = array();
				foreach ($row as $k => $v) {
					//echo $k.' is '.$v.'<br />';
					if (is_string($v) && is_numeric(str_replace('.', '', $v))) {
						// Keep double as double instead of String
						$rowArray[$k] = floatval($v);
					} else
						$rowArray[$k] = $v;
				}
				array_push($finalArray, $rowArray);	
			}
			//dd(count($finalArray));
			
			if (count($finalArray) == 0) throw new \Exception;

			if ($returnJsonResponse)
				return \Response::json(array('success' => true, 'data' => $finalArray),200);
			else
				return array('success' => true, 'data' => $finalArray);
		
		} catch (\Exception $e) {
			// No record returned or other problems found
			return \Response::json(array('success' => false),500);
		}
			
		
	}
	
	// Get set of record
	public abstract function index();
	
	// Get one record
	public function show($id) {
	
		// Get certain fields, default is all fields
		$fields = Input::get('fields', '*');
		return self::requestData($fields, null, 'MK = '.$id , 0, 1);
		
	}
	
	// Save new record
	public abstract function store();
		
	// Update specific record
	public abstract function update($id);
	
	// Soft delete a record
	public function destroy($id){
		
		$this->modelInstance->find($id)->delete();
	}
	
	// Reserved
	public function create() {}
	public function edit($id) {}

	
}
