<?php

class CFriendController extends \BaseClientController {
	
	public function __construct() {
		$this->modelName = 'Friend';
		parent::__construct();
	}
	
	public function index() {
		
	}
	
	public function store() {
		
	}
	
	public function update($id) {
		
	}
	
	public function viewFriends($userid) {
		
		try {
			// Check if user exist
			$target = DB::select('select count(*) as count from f_registereduser WHERE MK = ?', array($userid)); 
			if ($target[0]->count == 0) {
				throw new Exception('User with that id does not exists!');
			}
		
			// Get messages
			$model = new Friend;
			$data = $model::join('f_registereduser', 'f_friend.REGISTEREDUSER_MK', '=', 'f_registereduser.MK')->select(DB::raw('REGISTEREDUSER_MK_FRIEND'))->where('REGISTEREDUSER_MK', '=', $userid)->get()->toArray();
			//dd($finalData);
			$finalData = array();
			
			foreach ($data as $subArr) {
				$userName = RegisteredUser::select(DB::raw('USERNAME'))->where('MK', '=', $subArr['REGISTEREDUSER_MK_FRIEND'])->get()->toArray();
				$userName = $userName[0]['USERNAME'];
				$subArr["USERNAME"] = $userName;
				array_push($finalData, $subArr);
			}
			
			return \Response::json(array('success' => true, 'data' => $finalData),200);
		} catch (\Exception $e) {
			
			Log::error('Something is really going wrong.');
			Log::error($e);
			return \Response::json(array('success' => false),500);
		}
		
		//return 'fk off';
		
	}
	

	public function removeFriend($userid, $frdid) {
		
		try {
			
			// Check if user exist
			$target = DB::select('select count(*) as count from f_registereduser WHERE MK = ?', array($userid)); 
			if ($target[0]->count == 0) {
				throw new Exception('user_id  does not exists!');
			}
			// Check if user exist
			$target = DB::select('select count(*) as count from f_registereduser WHERE MK = ?', array($frdid));
			if ($target[0]->count == 0) {
				throw new Exception('friend_id  does not exists!');
			}
			
			// Get friend
			Friend::where('REGISTEREDUSER_MK' ,'=' ,$userid)->where('REGISTEREDUSER_MK_FRIEND' ,'=' ,$frdid)->delete();
			
			return \Response::json(array('success' => true),200);
		} catch (\Exception $e) {
			
			Log::error('Something is really going wrong.');
			Log::error($e);
			
			return \Response::json(array('success' => false),500);
		}
		
	}

	
	public function addFriend($userid, $frdid) {
		
		try {
			
			// Check if user exist
			$target = DB::select('select count(*) as count from f_registereduser WHERE MK = ?', array($userid)); 
			if ($target[0]->count == 0) {
				throw new Exception('user_id  does not exists!');
			}
			// Check if user exist
			$target = DB::select('select count(*) as count from f_registereduser WHERE MK = ?', array($frdid));
			if ($target[0]->count == 0) {
				throw new Exception('friend_id  does not exists!');
			}
			
			
			DB::beginTransaction();
			// Create and save friend
			$obj = new Friend;
			$obj->F_CREATE_KEY = parent::getMomentString('YmdHisu').'/'.parent::getIP();
			$obj->REGISTEREDUSER_MK = $userid;
			$obj->REGISTEREDUSER_MK_FRIEND = $frdid;
			
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
	
}

?>
