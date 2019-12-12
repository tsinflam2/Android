<?php

abstract class BaseDatatableController extends \BaseController {

	protected  $modelName;
	protected  $indexView;
	protected  $initColumns;
	
	//const  HASH_FIELD_NAME = 'HASH';
	
	protected $modelInstance; // change back to private if error
	
	public function __construct() {
		//get_class();
		
		
		//die($this->modelName .'dx');
		//$this->modelInstance = new static::$modelName;
		$this->modelInstance = new $this->modelName;
		
	}
	
	
	
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	 
	 
	public function index()
	{
		
		/*
		if (!Session::has('loggedInUser'))
		{
			return Redirect::to('./area51');
			die();
		}
		*/
		//$temp = static::$modelName;
		//$tempClass = new static::$modelName;
		
		//$mi = $this->modelInstance;
		
		$fetched = $this->modelInstance->fetchJson($this->initColumns);
		//$fetched = $this->modelInstance->fetchJson($this->initColumns, false, 'MK = 1');
		//$fetched = $this->modelInstance->where('MK', '=', '1')->get()->toJson();//fetchJson($this->initColumns);
		//dd($fetched);
		$success = json_decode($fetched, true)['success'];
		if ($success) return View::make($this->indexView)->with(['json' => $fetched]);
		else return '<strong>Requested database fields does not exist.</strong>';
		
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	// Save new record
	public function store()
	{
	
	//(new static::($this->modelName))->insert(self::processComplexInput());
	//DB::table('users')->crea(self::processComplexInput());
	//die(print_r(self::processComplexInput()));
		$data_array = self::processComplexInput();
		$data_array['F_CREATE_KEY'] = parent::getMomentString('YmdHisu').'/'.parent::getIP();
		
		$this->modelInstance->insert($data_array);
		return self::getDatatableScript();
		
	}
	
	
	// Update specific record
	public function update($id) {
		$this->modelInstance->where('MK', '=', $id)->update(self::processComplexInput());
		
		return self::getDatatableScript();
	
	}
	
	


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		die($this->modelInstance->where('MK', '=', $id)->get()->toJson());
	}


	/** NO USE????
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//dd(Input::get('_token'));
		//if (Session::token() != Input::get('_token')) dd('Token mismatch!');
		
		$fetched = $this->modelInstance->where('MK', '=', 4)->get()->toJson();
		//$fetched = $this->modelInstance->where('MK', '=', Input::get('query_mk'))->get()->toJson();
		return $fetched;
		//$success = json_decode($fetched, true)['success'];
		//return View::make(static::$indexView)->with(['json' => $fetched]);
		//else return '<strong>Requested database fields does not exist.</strong>';
		
		
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	 /*
	public function update($id)
	{
		DB::transaction(function() {
			parse_str(urldecode(Input::get('serialized')), $result);
				foreach ($result as $mk => $record) {
					$update_array = [];
					foreach ($record as $internal => $value) {
						$update_array[$internal] = $value;
					}
					$this->modelInstance->where('MK', '=', $mk)->update($update_array);
				}
		});
		return 'updated!!'.$id.'<br />';
		
	}
	*/

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}
	
	/*public function populateDatatable()
	{
	
		
		die();
		return $this->modelInstance->fetchJson($this->initColumns, false);
	
	}
	*/
	
	public function bulk()
	{
		//$temp = static::$modelName;
		$temp = $this->modelName;
		
		// reroute basic bulk reuqest to appropriate model function
		$affected_mk = Input::get('affectedmk');
		
		
		switch (Input::get('action')) {
			case 'archive':
				$this->modelInstance->archive(explode(',', $affected_mk));
				break;
			case 'restore':
				$this->modelInstance->restoreArchived(explode(',', $affected_mk));
				break;
			case 'delete':
				$this->modelInstance->deleteForever(explode(',', $affected_mk));
				break;
			case 'flag':
				$this->modelInstance->flag(explode(',', $affected_mk));
				break;
			case 'unflag':
				$this->modelInstance->unflag(explode(',', $affected_mk));
				break;
		}
	}
	
	public function processComplexInput()
	{
		$final_data_array = array();
	
		// OLD STUFF
		/*
		$pw;
		foreach (static::$inputColumnsMapping as $inputColumn => $fieldName) {
		
			// Requires special treatment
			if (substr($fieldName, 0, 10) === 'FRAMEWORK_') {
				switch (substr($fieldName, 10)) {
					case 'COMPLEX':	dd($inputColumn); break;
					case 'PASSWORD': 
						$pw = Input::get('in'.$inputColumn); break;
					case 'PASSWORD_CONFIRM': 
						if ($pw === Input::get('in'.$inputColumn)) { $final_data_array[self::HASH_FIELD_NAME] = Hash::make($pw);} else { return false;}
						break;
					default: dd('Invalid director at '.substr($fieldName, 10));
				}
				
			} else {
				// Map input columns to table fields directly
				$final_data_array[$fieldName] = Input::get('in'.$inputColumn);
			}
			
			
				
		}
		*/
		
	
		$input_data_array = Input::all();
		foreach ($input_data_array as $key => $value) {
			if (preg_match("#^in(.*)$#i", $key) && $value != '') {
				// add item to array if legit input received
				$final_data_array[substr($key, 2)] = $value;
			} else {
			
			}
		}
		
		// Password field handling
		if ((Input::has('secretConfirmPassword') && Input::has('secretPassword')) && (Input::get('secretPassword') == Input::get('secretConfirmPassword')))
		{
			$final_data_array['HASH'] = Hash::make(Input::get('secretPassword'));
		}
		
		// Token handling
		$final_data_array['token'] = $input_data_array['_token'];
		unset($final_data_array['token']); // temply unset, future dev cont.
		
		// Get all data from normal input fields(i.e. starts with 'in' prefix)
		
		
		//$final_data_array['F_CREATED'] = DB::raw('UTC_TIMESTAMP()');
		//$final_data_array['F_UPDATED'] = DB::raw('UTC_TIMESTAMP()');
		
		//dd("<script>alert('".var_dump($final_data_array)."');</script>");
		return $final_data_array;
	}

	public function getDatatableScript() {
	//dd('<script>alert("hi");</script>');
		//dd(static::DATATABLE_URI);
		//return  '<script>window.location.replace("http://localhost/fyp/laravel/public'. static::DATATABLE_URI. '");</script>';
		
 

		return  '<script>window.location.replace(document.location.href);</script>';
		//return  '<script>location.reload(true);</script>';
	}
	
	

}
