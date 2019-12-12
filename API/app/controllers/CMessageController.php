<?php

class CMessageController extends \BaseClientController {
	
	// Map input columns to database table fields, does not observe 'in' prefix for the key
	// protected static $inputColumnsMapping = [<<input_columns>>];
	
	public function __construct() {
		$this->modelName = 'PrivateMessage';
		parent::__construct();
	}
	
	public function index() {
		
	}
	
	public function store() {
		
	}
	
	public function update($id) {
		
	}
	
	public function viewPmInbox($userid) {
		
		try {
			// Check if user exist
			$target = DB::select('select count(*) as count from f_registereduser WHERE MK = ?', array($userid)); 
			if ($target[0]->count == 0) {
				throw new Exception('User with that username does not exists!');
			}
		
			$senderMK = 0;
			// Get messages
			$finalData = array();
			$data = PrivateMessage::join('f_registereduser', 'f_privatemessage.REGISTEREDUSER_MK_TO', '=', 'f_registereduser.MK')->select(DB::raw('REGISTEREDUSER_MK_FROM , MAX(SENTAT) as sent'))->where('REGISTEREDUSER_MK_TO', '=', $userid)->groupBy('REGISTEREDUSER_MK_FROM')->orderBy('sentat', 'desc')->get()->toArray();
			
			//dd($finalData);
			// Add PM content
			foreach ($data as $subArr) {
				$pmContent = PrivateMessage::select(DB::raw('PMCONTENT'))->where('REGISTEREDUSER_MK_FROM', '=', $subArr['REGISTEREDUSER_MK_FROM'])->where('SENTAT', '=', $subArr['sent'])->where('REGISTEREDUSER_MK_TO', '=', $userid)->get()->toArray();
				$pmContent = $pmContent[0]['PMCONTENT'];
				$subArr['lastmsg'] = $pmContent;
				//dd($subArr);
				//array_push($finalData, $subArr);
				
				
				// Add sender username
				$senderMK = $subArr['REGISTEREDUSER_MK_FROM'];
				$data2 = RegisteredUser::select(DB::raw('USERNAME, PROFILEPIC'))->where('MK', '=', $senderMK)->get()->toArray();
				$subArr['USERNAME'] = $data2[0]['USERNAME'];
				$subArr['PROFILEPIC'] = $data2[0]['PROFILEPIC'];
				array_push($finalData, $subArr);
			}
			
			
			
			
			//dd($finalData);
			return \Response::json(array('success' => true, 'data' => $finalData),200);
		} catch (\Exception $e) {
			
			Log::error('Something is really going wrong.');
			Log::error($e);
			return \Response::json(array('success' => false),500);
		}
		
	}
	
	
	public function viewPm($userid, $withuserid) {
		
		try {
			// Check if user exist
			$target = DB::select('select count(*) as count from f_registereduser WHERE MK = ?', array($userid)); 
			if ($target[0]->count == 0) {
				throw new Exception('User with that username does not exists!');
			}
			// Check if user exist
			$target = DB::select('select count(*) as count from f_registereduser WHERE MK = ?', array($withuserid));
			if ($target[0]->count == 0) {
				throw new Exception('User with that username does not exists!');
			}
			
			// Get messages
			$finalData = PrivateMessage::join('f_registereduser', 'f_privatemessage.REGISTEREDUSER_MK_TO', '=', 'f_registereduser.MK')->select(DB::raw('REGISTEREDUSER_MK_FROM , USERNAME as USERNAME_FROM, SENTAT as SENT, PMCONTENT, PROFILEPIC'))->where('REGISTEREDUSER_MK_TO', '=', $userid)->where('REGISTEREDUSER_MK_FROM', '=', $withuserid)->orderBy('SENT', 'ASC')->get()->toArray();
			return \Response::json(array('success' => true, 'data' => $finalData),200);
		} catch (\Exception $e) {
			
			Log::error('Something is really going wrong.');
			Log::error($e);
			return \Response::json(array('success' => false),500);
		}
		
	}
	
	public function sendPm($userid, $withuserid) {
		
		try {
			// Check if user exist
			$target = DB::select('select count(*) as count from f_registereduser WHERE MK = ?', array($userid)); 
			if ($target[0]->count == 0) {
				throw new Exception('User with that username does not exists!');
			}
			// Check if user exist
			$target = DB::select('select count(*) as count from f_registereduser WHERE MK = ?', array($withuserid));
			if ($target[0]->count == 0) {
				throw new Exception('User with that username does not exists!');
			}
			
			
			DB::beginTransaction();
			// Create and save comment
			$obj = new PrivateMessage;
			$obj->F_CREATE_KEY = parent::getMomentString('YmdHisu').'/'.parent::getIP();
			$obj->REGISTEREDUSER_MK_FROM = $userid;
			$obj->REGISTEREDUSER_MK_TO = $withuserid;
			$obj->PMCONTENT = Input::get('message');
			
			if (Input::has('newsmk')) {
				$obj->NEWSPOST_MK = Input::get('newsmk');
				// Check if news mk exist
				$target = DB::select('select count(*) as count from f_newspost WHERE MK = ?', array($obj->NEWSPOST_MK));
				if ($target[0]->count == 0) {
					throw new Exception('NEWS with that MK does not exist!');
				}
			}
			
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
