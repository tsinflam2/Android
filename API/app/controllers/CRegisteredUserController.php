<?php

class CRegisteredUserController extends \BaseClientController {
	
	// Map input columns to database table fields, does not observe 'in' prefix for the key
	// protected static $inputColumnsMapping = [<<input_columns>>];
	
	public function __construct() {
		$this->modelName = 'RegisteredUser';
		parent::__construct();
	}
	
	// Override load datatable
	public function index() {
		
		
		
		// Sorting (with default option)
		$sort = Input::get('sort', '-F_CREATED');
		// Get certain fields (with default option)
		$fields = Input::get('fields', 'username,F_CREATED');
		
		$fromRecord = Input::get('fromRecord','0');
		$recordCount = Input::get('recordCount','10');	
		
		return parent::requestData($fields, $sort, null, $fromRecord, $recordCount);
	}
	
	// Get one record
	public function show($id) {
	
		// Get certain fields, default is all fields
		$fields = Input::get('fields', 'MK,username, email, firstname, lastname, trustcredits, facebookid, youtube, suspendedat, profilepic');
		return self::requestData($fields, null, 'MK = '.$id , 0, 1);
		
	}
	
	// Save new record
	public function store() {
	
		try {
			// Check if user exist
			$target = DB::select('select count(*) as count from f_registereduser WHERE USERNAME = ?', array(Input::get('username')));
			if ($target[0]->count != 0) {
				throw new Exception('User with that username already exists!');
			}
			
			$target = DB::select('select count(*) as count from f_registereduser WHERE EMAIL = ?', array(Input::get('email')));
			if ($target[0]->count != 0) {
				throw new Exception('User with that email already exists!');
			}
			
			// Register user
			DB::beginTransaction();
			$obj = new $this->modelName;
			$obj->F_CREATE_KEY = parent::getMomentString('YmdHisu').'/'.parent::getIP();
			$obj->USERNAME = Input::get('username');
			$obj->HASH = Hash::make(Input::get('password'));
			$obj->EMAIL = Input::get('email');
			$obj->FIRSTNAME = Input::get('firstname');
			$obj->LASTNAME = Input::get('lastname');
			$obj->save();
			DB::commit(); 
			
			return \Response::json(array('success' => true),200);
		} catch (\Exception $e) {
			DB::rollback();
			Log::error('Something is really going wrong.');
			Log::error($e);
			return \Response::json(array('success' => false),500);
		}
		
		
		
		
		
	}
		
	// Update specific record
	public function update($id) {
		// Not allowed
		return \Response::json(array('success' => false),405);
	}
	
	public function viewFavourites($id) {
		
		try {
			// Check if user exist
			$target = DB::select('select count(*) as count from f_registereduser WHERE MK = ?', array($id));
			if ($target[0]->count == 0) {
				throw new Exception('User does not exist');
			}
			
			// Get favorites
			$finalData = RegisteredUser::join('f_favoritenewspost', 'f_registereduser.MK', '=', 'f_favoritenewspost.REGISTEREDUSER_MK')->join('f_newspost', 'f_favoritenewspost.NEWSPOST_MK', '=', 'f_newspost.MK')->select(DB::raw('f_favoritenewspost.newspost_mk, newstitle,newsdescription,latitude,longitude,postedat,viewcount,favoritedat'))->where('f_registereduser.MK','=', $id)->orderBy('FAVORITEDAT', 'DESC')->get()->toArray();
			return \Response::json(array('success' => true, 'data' => $finalData),200);
		} catch (\Exception $e) {
			
			Log::error('Something is really going wrong.');
			Log::error($e);
			return \Response::json(array('success' => false),500);
		}
		
	}
	
	public function viewSubscriptions($id) {
		
		try {
			// Check if user exist
			$target = DB::select('select count(*) as count from f_registereduser WHERE MK = ?', array($id));
			if ($target[0]->count == 0) {
				throw new Exception('User does not exist');
			}
			
			// Get subscriptions
			$finalData = Subscription::join('f_registereduser', 'f_subscription.REGISTEREDUSER_MK_TO', '=', 'f_registereduser.MK')->select(DB::raw('f_registereduser.MK, f_registereduser.USERNAME, f_registereduser.PROFILEPIC'))->where('f_subscription.REGISTEREDUSER_MK_BY','=', $id)->get()->toArray();
						
			return \Response::json(array('success' => true, 'data' => $finalData),200);
		} catch (\Exception $e) {
			
			Log::error('Something is really going wrong.');
			Log::error($e);
			return \Response::json(array('success' => false),500);
		}
		
	}
		
	public function addSubscription($id, $withid) {
		
		try {
			// Check if user exist
			$target = DB::select('select count(*) as count from f_registereduser WHERE MK = ? OR MK = ?', array($id, $withid));
			if ($target[0]->count != 2) {
				throw new Exception('Either from_user or to_user in subscribe event does not exist');
			}
			
			// Check if subscription event already exist
			$target = DB::select('select count(*) as count from f_subscription WHERE REGISTEREDUSER_MK_BY = ? AND REGISTEREDUSER_MK_TO = ?', array($id, $withid));
			if ($target[0]->count == 1) {
				throw new Exception('Already subscribed');
			}
			
			// Subscribe
			DB::beginTransaction();
			$model = new Subscription;
			$model->F_CREATE_KEY = parent::getMomentString('YmdHisu').'/'.parent::getIP();
			$model->REGISTEREDUSER_MK_BY = $id;
			$model->REGISTEREDUSER_MK_TO = $withid;
			$model->save();
			DB::commit(); 
			
			return \Response::json(array('success' => true),200);
		} catch (\Exception $e) {
			DB::rollback();
			Log::error('Something is really going wrong.');
			Log::error($e);
			return \Response::json(array('success' => false),500);
		}
		
		
	}
	
	public function removeSubscription($id, $withid) {
		
		try {
			// Check if subscription event already exist
			$target = DB::select('select count(*) as count from f_subscription WHERE REGISTEREDUSER_MK_BY = ? AND REGISTEREDUSER_MK_TO = ?', array($id, $withid));
			if ($target[0]->count == 0) {
				throw new Exception('Subscription does not exist.');
			}
			
			// Un-subscribe
			DB::beginTransaction();
			$deleteModel = new Subscription;
			$deleteModel = $deleteModel::withTrashed()->whereRaw('REGISTEREDUSER_MK_BY = ? and REGISTEREDUSER_MK_TO = ?', array($id, $withid));
			$deleteModel->forceDelete();
			DB::commit(); 
			
			return \Response::json(array('success' => true),200);
		} catch (\Exception $e) {
			DB::rollback();
			Log::error('Something is really going wrong.');
			Log::error($e);
			return \Response::json(array('success' => false),500);
		}
		
	}
	
}

?>
