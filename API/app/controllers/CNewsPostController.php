<?php

class CNewsPostController extends \BaseClientController {
	
	// Map input columns to database table fields, does not observe 'in' prefix for the key
	// protected static $inputColumnsMapping = [<<input_columns>>];
	
	public function __construct() {
		$this->modelName = 'NewsPost';
		parent::__construct();
	}
	
	// Override load datatable
	public function index() {
	
		// Get news post info	
		
		if (Input::get('sort') == '-proximity') {
			// Sort by proximity
			
			$sort = '-proximity';
			// Get certain fields (with default option)
			$fields = Input::get('fields', 'mk, newstitle,newsdescription,latitude,longitude,postedat,viewcount, registereduser_mk');
			// Limits
			$fromRecord = Input::get('fromRecord','0');
			$recordCount = Input::get('recordCount','10');	
			
			$lat = Input::get('currentLat');
			$long = Input::get('currentLong');
			
			$searchNewsPostModel = new NewsPost;
			
			if (Input::has('keyword')) {
				$keyword = Input::get('keyword');
				$arrayData = $searchNewsPostModel::leftJoin('f_newspostcomment', 'f_newspost.mk', '=', 'f_newspostcomment.NEWSPOST_MK')->select(DB::raw("f_newspost.mk, newstitle,newsdescription,latitude,longitude,f_newspost.postedat,viewcount, f_newspost.registereduser_mk, COUNT(f_newspostcomment.MK) as commentsCount, (6371 * acos( cos( radians(" . $lat .") ) * cos( radians( `LATITUDE` ) ) * cos( radians( " . $long ." ) - radians(`LONGITUDE`) ) + sin( radians(" . $lat . ") ) * sin( radians(`LATITUDE`) ) )) AS distance"))->where('ON_HOLD','=', 0)->where('newstitle','LIKE', "%". $keyword ."%")->groupBy(DB::raw('f_newspost.mk, newstitle,newsdescription,latitude,longitude,f_newspost.postedat,viewcount, f_newspost.registereduser_mk, distance'))->orderBy('distance', 'asc')->skip($fromRecord)->take($recordCount)->get()->toArray();
			} else {
				$arrayData = $searchNewsPostModel::leftJoin('f_newspostcomment', 'f_newspost.mk', '=', 'f_newspostcomment.NEWSPOST_MK')->select(DB::raw("f_newspost.mk, newstitle,newsdescription,latitude,longitude,f_newspost.postedat,viewcount, f_newspost.registereduser_mk, COUNT(f_newspostcomment.MK) as commentsCount, (6371 * acos( cos( radians(" . $lat .") ) * cos( radians( `LATITUDE` ) ) * cos( radians( " . $long ." ) - radians(`LONGITUDE`) ) + sin( radians(" . $lat . ") ) * sin( radians(`LATITUDE`) ) )) AS distance"))->where('ON_HOLD','=', 0)->groupBy(DB::raw('f_newspost.mk, newstitle,newsdescription,latitude,longitude,f_newspost.postedat,viewcount, f_newspost.registereduser_mk, distance'))->orderBy('distance', 'asc')->skip($fromRecord)->take($recordCount)->get()->toArray();
			}
			
			
			//$subArrayData['favourited'] = $searchNewsPostData[0]['favourited'];
			//dd($arrayData);
			$arrayData = array('data' => $arrayData);
			//$arrayData = parent::requestData($fields, $sort, 'ON_HOLD = 0', $fromRecord, $recordCount, false);
			
		} else if (Input::has('keyword')) {
			// Search keyword, order by postedat desc default
			// Sorting (with default option)
			$sort = Input::get('sort', '-postedat');
			// Get certain fields (with default option)
			$fields = Input::get('fields', 'mk, newstitle,newsdescription,latitude,longitude,postedat,viewcount, registereduser_mk');
			// Limits
			$fromRecord = Input::get('fromRecord','0');
			$recordCount = Input::get('recordCount','10');	
			
			$lat = Input::get('currentLat');
			$long = Input::get('currentLong');
			
			$keyword = Input::get('keyword');
			
			$searchNewsPostModel = new NewsPost;
			$arrayData = $searchNewsPostModel::leftJoin('f_newspostcomment', 'f_newspost.mk', '=', 'f_newspostcomment.NEWSPOST_MK')->select(DB::raw("f_newspost.mk, newstitle,newsdescription,latitude,longitude,f_newspost.postedat,viewcount, f_newspost.registereduser_mk, COUNT(f_newspostcomment.MK) as commentsCount"))->where('ON_HOLD','=', 0)->where('newstitle','LIKE', "%". $keyword ."%")->groupBy(DB::raw('f_newspost.mk, newstitle,newsdescription,latitude,longitude,f_newspost.postedat,viewcount, f_newspost.registereduser_mk'))->orderBy('viewcount', 'desc')->skip($fromRecord)->take($recordCount)->get()->toArray();
			$arrayData = array('data' => $arrayData);
			
		} else if (Input::has('author')) {
			// Get post of this author
			// Sorting (with default option)
			$sort = Input::get('sort', '-postedat');
			// Get certain fields (with default option)
			$fields = Input::get('fields', 'mk, newstitle,newsdescription,latitude,longitude,postedat,viewcount, registereduser_mk');
			// Limits
			$fromRecord = Input::get('fromRecord','0');
			$recordCount = Input::get('recordCount','10');	
			
			// Author MK
			$author = Input::get('author');
			
			$searchNewsPostModel = new NewsPost;
			//$arrayData = $searchNewsPostModel::leftJoin('f_newspostcomment', 'f_newspost.mk', '=', 'f_newspostcomment.NEWSPOST_MK')->select(DB::raw("f_newspost.mk, newstitle,newsdescription,latitude,longitude,f_newspost.postedat,viewcount, f_newspost.registereduser_mk, COUNT(f_newspostcomment.MK) as commentsCount"))->where('ON_HOLD','=', 0)->groupBy(DB::raw('f_newspost.mk, newstitle,newsdescription,latitude,longitude,f_newspost.postedat,viewcount, f_newspost.registereduser_mk'))->orderBy('viewcount', 'desc')->skip($fromRecord)->take($recordCount)->get()->toArray();
			
			$arrayData = $searchNewsPostModel::leftJoin('f_newspostcomment', 'f_newspost.mk', '=', 'f_newspostcomment.NEWSPOST_MK')->select(DB::raw("f_newspost.mk, newstitle,newsdescription,latitude,longitude,f_newspost.postedat,viewcount, f_newspost.registereduser_mk, COUNT(f_newspostcomment.MK) as commentsCount"))->where('ON_HOLD','=', 0)->where('f_newspost.registereduser_mk','=', $author)->groupBy(DB::raw('f_newspost.mk, newstitle,newsdescription,latitude,longitude,f_newspost.postedat,viewcount, f_newspost.registereduser_mk'))->orderBy('postedat', 'desc')->skip($fromRecord)->take($recordCount)->get()->toArray();
			
			//->leftJoin('f_newsvideo', 'f_newspost.mk', '=', 'f_newsvideo.NEWSPOST_MK')
			
			$arrayData = array('data' => $arrayData);
			
		} else {
			// Sort by other column
			
			// Sorting (with default option)
			$sort = Input::get('sort', '-postedat');
			// Get certain fields (with default option)
			$fields = Input::get('fields', 'mk, newstitle,newsdescription,latitude,longitude,postedat,viewcount, registereduser_mk');
			// Limits
			$fromRecord = Input::get('fromRecord','0');
			$recordCount = Input::get('recordCount','10');	
			
			$searchNewsPostModel = new NewsPost;
			//$arrayData = $searchNewsPostModel::leftJoin('f_newspostcomment', 'f_newspost.mk', '=', 'f_newspostcomment.NEWSPOST_MK')->select(DB::raw("f_newspost.mk, newstitle,newsdescription,latitude,longitude,f_newspost.postedat,viewcount, f_newspost.registereduser_mk, COUNT(f_newspostcomment.MK) as commentsCount"))->where('ON_HOLD','=', 0)->groupBy(DB::raw('f_newspost.mk, newstitle,newsdescription,latitude,longitude,f_newspost.postedat,viewcount, f_newspost.registereduser_mk'))->orderBy('viewcount', 'desc')->skip($fromRecord)->take($recordCount)->get()->toArray();
			
			$arrayData = $searchNewsPostModel::leftJoin('f_newspostcomment', 'f_newspost.mk', '=', 'f_newspostcomment.NEWSPOST_MK')->select(DB::raw("f_newspost.mk, newstitle,newsdescription,latitude,longitude,f_newspost.postedat,viewcount, f_newspost.registereduser_mk, COUNT(f_newspostcomment.MK) as commentsCount"))->where('ON_HOLD','=', 0)->groupBy(DB::raw('f_newspost.mk, newstitle,newsdescription,latitude,longitude,f_newspost.postedat,viewcount, f_newspost.registereduser_mk'))->orderBy('viewcount', 'desc')->skip($fromRecord)->take($recordCount)->get()->toArray();
			
			//->leftJoin('f_newsvideo', 'f_newspost.mk', '=', 'f_newsvideo.NEWSPOST_MK')
			
			$arrayData = array('data' => $arrayData);
			
			//dd($arrayData);
			
			//dd($arrayData);	
			
			
			//$arrayData = parent::requestData($fields, $sort, 'ON_HOLD = 0', $fromRecord, $recordCount, false);
		}
		
		
		// Get youtube videos and images
		$currPos = 0;
		foreach ($arrayData['data'] as $subArr) {
			//dd($subArr['mk']);
			$videosArr = NewsPost::leftJoin('f_newsvideo', 'f_newspost.mk', '=', 'f_newsvideo.NEWSPOST_MK')->select(DB::raw("YOUTUBEVIDEOID as youtube"))->where('f_newsvideo.NEWSPOST_MK','=', $subArr['mk'])->get()->toArray();
			$arrayData['data'][$currPos]['videos'] = $videosArr;
			
			$photosArr = NewsPost::leftJoin('f_newsimage', 'f_newspost.mk', '=', 'f_newsimage.NEWSPOST_MK')->select(DB::raw("FILENAME as jpg"))->where('f_newsimage.NEWSPOST_MK','=', $subArr['mk'])->get()->toArray();
			$arrayData['data'][$currPos++]['photos'] = $photosArr;
			//dd($videosArr);				
		}
		
		// Get additional data
		$userMK = Input::get('registereduser_mk','0');
		
		$finalData = array('data' => array());
		//dd($arrayData['data']);
		
		$newspostsMKArr = array();
		
		foreach ($arrayData['data'] as $subArrayData) {
			
			// Author info(mk, username and profile pic)
			$searchUserModel = new RegisteredUser;
			$searchUserData = $searchUserModel::where('MK', '=', $subArrayData['registereduser_mk'])->select(array('username', 'profilepic'))->get()->toArray();
			$subArrayData['username'] = $searchUserData[0]['username'];
			$subArrayData['profilepic'] = $searchUserData[0]['profilepic'];
			
			// Total favourite count
			$searchNewsPostModel = new NewsPost;
			$searchNewsPostData = $searchNewsPostModel::join('f_favoritenewspost', 'f_newspost.mk', '=', 'f_favoritenewspost.NEWSPOST_MK')->select(DB::raw('COUNT(f_favoritenewspost.MK) as favourited'))->where('f_favoritenewspost.NEWSPOST_MK','=', $subArrayData['mk'])->get()->toArray();
			$subArrayData['favourited'] = $searchNewsPostData[0]['favourited'];
			
			// Whether user has favourited the news post
			$searchFavoriteModel = new FavoriteNewsPost;
			$searchFavoriteData = $searchFavoriteModel::select(DB::raw('COUNT(*) as hasfavourited'))->where('NEWSPOST_MK','=', $subArrayData['mk'])->where('REGISTEREDUSER_MK','=', $userMK)->get()->toArray();
			$subArrayData['hasfavourited'] = $searchFavoriteData[0]['hasfavourited'];
			
			// Remember MKs of returned news posts
			array_push($newspostsMKArr, $subArrayData['mk']);
			
			// Append sub array to final data
			array_push($finalData['data'], $subArrayData);
		}

		// Add viewcount to all returned news posts
		NewsPost::select(DB::raw('MK'))->whereIn('MK', $newspostsMKArr)->increment('VIEWCOUNT');
		
		//dd($finalData['data']);
		return \Response::json(array('success' => true, 'data' => $finalData['data']),200);
	}
	
	// Get one record
	public function show($id) {
	
		// Get certain fields, default is all fields
		$fields = Input::get('fields', 'mk, newstitle,newsdescription,latitude,longitude,postedat,viewcount, registereduser_mk');
		$arrayData = self::requestData($fields, null, 'MK = '.$id , 0, 1, false);
		
		// Get additional data
		$userMK = Input::get('registereduser_mk','0');
		$finalData = array('data' => array());
		
		foreach ($arrayData['data'] as $subArrayData) {
			// Author info(mk, username and profile pic)
			$searchUserModel = new RegisteredUser;
			$searchUserData = $searchUserModel::where('MK', '=', $subArrayData['registereduser_mk'])->select(array('username', 'profilepic'))->get()->toArray();
			$subArrayData['username'] = $searchUserData[0]['username'];
			$subArrayData['profilepic'] = $searchUserData[0]['profilepic'];
			
			// Total favourite count
			$searchNewsPostModel = new NewsPost;
			$searchNewsPostData = $searchNewsPostModel::join('f_favoritenewspost', 'f_newspost.mk', '=', 'f_favoritenewspost.NEWSPOST_MK')->select(DB::raw('COUNT(f_favoritenewspost.MK) as favourited'))->where('f_favoritenewspost.NEWSPOST_MK','=', $subArrayData['mk'])->get()->toArray();
			$subArrayData['favourited'] = $searchNewsPostData[0]['favourited'];
			
			// Whether user has favourited the news post
			$searchFavoriteModel = new FavoriteNewsPost;
			$searchFavoriteData = $searchFavoriteModel::select(DB::raw('COUNT(*) as hasfavourited'))->where('NEWSPOST_MK','=', $subArrayData['mk'])->where('REGISTEREDUSER_MK','=', $userMK)->get()->toArray();
			$subArrayData['hasfavourited'] = $searchFavoriteData[0]['hasfavourited'];
			
			// Remember MKs of returned news posts
			NewsPost::select(DB::raw('MK'))->whereIn('MK', array($subArrayData['mk']))->increment('VIEWCOUNT');
			
			// Append sub array to final data
			array_push($finalData['data'], $subArrayData);
		}
		
		// Get youtube videos and images
		$currPos = 0;
		foreach ($arrayData['data'] as $subArrayData) {
			//dd($subArr['mk']);
			$videosArr = NewsPost::leftJoin('f_newsvideo', 'f_newspost.mk', '=', 'f_newsvideo.NEWSPOST_MK')->select(DB::raw("YOUTUBEVIDEOID as youtube"))->where('f_newsvideo.NEWSPOST_MK','=', $subArrayData['mk'])->get()->toArray();
			//$arrayData['data']['videos'] = $videosArr;
			
			$photosArr = NewsPost::leftJoin('f_newsimage', 'f_newspost.mk', '=', 'f_newsimage.NEWSPOST_MK')->select(DB::raw("FILENAME as jpg"))->where('f_newsimage.NEWSPOST_MK','=', $subArrayData['mk'])->get()->toArray();
			
			$finalData['data'][0]['photos'] = $photosArr;
			$finalData['data'][0]['videos'] = $videosArr;
			
			//array_push($finalData['data'], $arrayData['photos']);
			//dd($videosArr);		
		
		}
		
		return \Response::json(array('success' => true, 'data' => $finalData['data']),200);
	}
	
	// Save new record
	public function store() {
	
		$Akismet = new Akismet('521e2a5c591e');
		/*
		$comment_data = array(
		    'user_ip'               => parent::getIP(),
		    'comment_type'          => 'comment',
		    'comment_content'       => Input::get('newsdescription')
		);
		*/
		$comment_data = array(
		    'blog'                  => 'http://mysitehere.com',
		    'user_ip'               => 'x.x.x.x',
		    'user_agent'            => $_SERVER['HTTP_USER_AGENT'],
		    'comment_type'          => 'comment',
		    //'comment_author'        => 'John Doe',
		    //'comment_author_email'  => 'john@example.com',
		    //'comment_author_url'    => 'http://example.com/johndoe',
		   
		    'comment_content'  => Input::get('newstitle').'. '.Input::get('newsdescription')
		);
	    $response = $Akismet->checkSpam($comment_data);
	    

		try {
			
			DB::beginTransaction();

			// Create and save model
			$obj = new $this->modelName;

			$obj->F_CREATE_KEY = parent::getMomentString('YmdHisu').'/'.parent::getIP();

			$obj->newstitle = Input::get('newstitle');
			
			$obj->longitude = Input::get('longitude');
			$obj->latitude = Input::get('latitude');
			$obj->registereduser_mk = Input::get('registereduser_mk');
			$obj->newscategory_mk = Input::get('newscategory_mk');
			$obj->newsdescription = Input::get('newsdescription');

			// If same latitude move a bit off
			$searchModel = new $this->modelName;
			$latitudeSameCount = $searchModel->where('latitude', '=', Input::get('latitude'))->count();
			$longitudeSameCount = $searchModel->where('longitude', '=', Input::get('longitude'))->count();
			if ($latitudeSameCount > 0)
				$obj->latitude = floatval($obj->latitude) + 0.0001;
			if ($longitudeSameCount > 0)
				$obj->longitude = floatval($obj->longitude) + 0.0001;


			if ($response["spam"] == true)
				$obj->ON_HOLD = 1;

			$obj->save();

			
			
			$path ="NONE";
			$finalFileName = "";
			// Image file handling
			$imgIdx = 1;
			
			// Video file handling
			$vidIdx = 1;
			
			while (true) {

				// Is image - handlie it
				if (Input::hasFile('image'.$imgIdx))
				{
					$threeRandomChar = substr( "abcdefghijklmnopqrstuvwxyz" ,mt_rand( 0 ,25 ) ,1 ) .substr( md5( time( ) ), 0 ,2 );
					$finalFileName = parent::getMomentString('YmdHisu').$threeRandomChar.'.jpg';

				    //Input::file('image'.$imgIdx)->move('/Applications/XAMPP/xamppfiles/htdocs/fyp/laravel/public/newspost_photos', $finalFileName);
					Input::file('image'.$imgIdx)->move('C:\xamppdev\htdocs\fyp\laravel\public\newspost_photos', $finalFileName);
				    $path = Input::file('image'.$imgIdx)->getRealPath();

				    Log::error('now '.'image'.$imgIdx);

				    ${'newsImageObj' . $imgIdx} = new NewsImage;
					${'newsImageObj' . $imgIdx}->FILENAME = $finalFileName;
					${'newsImageObj' . $imgIdx}->VIEWCOUNT = '0';
					${'newsImageObj' . $imgIdx}->NEWSPOST_MK = $obj->MK;
					${'newsImageObj' . $imgIdx}->REGISTEREDUSER_MK = '37';
					${'newsImageObj' . $imgIdx}->F_CREATE_KEY = parent::getMomentString('YmdHisu').'/'.parent::getIP();

					${'newsImageObj' . $imgIdx}->save();

					$imgIdx++;
					
				} else if (Input::has('youtube'.$vidIdx)) {
					
				    Log::error('now '.'youtube'.$vidIdx);

				    ${'newsVideoObj' . $vidIdx} = new NewsVideo;
					${'newsVideoObj' . $vidIdx}->YOUTUBEVIDEOID = Input::get('youtube'.$vidIdx);
					${'newsVideoObj' . $vidIdx}->NEWSPOST_MK = $obj->MK;
					${'newsVideoObj' . $vidIdx}->REGISTEREDUSER_MK = '37';
					${'newsVideoObj' . $vidIdx}->F_CREATE_KEY = parent::getMomentString('YmdHisu').'/'.parent::getIP();

					${'newsVideoObj' . $vidIdx}->save();

					$vidIdx++;
					
				} else {
					//throw new \Exception;
					break;
				}
				
				
				
			}
			DB::commit();
			
				    
			//return \Response::json(array('success' => true, 'path' => $path),200);
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
	
	
	
	public function addFavourite($id) {
		try {
			// Check if user already added that post as favourite before
			$alreadyFavorited = DB::select('select count(*) as count from f_FavoriteNewsPost WHERE registereduser_mk = ? AND NEWSPOST_MK = ?', array(Input::get('registereduser_mk'), $id));
			if ($alreadyFavorited[0]->count > 0) {
				throw new Exception('Favourite already exist: '.$alreadyFavorited[0]->count);
			}
			
			DB::beginTransaction();
			// Create and save model
			$obj = new FavoriteNewsPost;
			$obj->F_CREATE_KEY = parent::getMomentString('YmdHisu').'/'.parent::getIP();
			$obj->REGISTEREDUSER_MK = Input::get('registereduser_mk');
			$obj->NEWSPOST_MK = $id;
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
	
	public function removeFavourite($id) {
		try {
			// Create and save model
			$target = DB::select('select count(*) as count, MK from f_FavoriteNewsPost WHERE registereduser_mk = ? AND NEWSPOST_MK = ?', array(Input::get('registereduser_mk'), $id));
			
			if ($target[0]->count == 0) {
				throw new Exception('Favourite does not exist: '.$target[0]->count);
			}
			
			// Delete favourite
			DB::beginTransaction();
			$deleteModel = new FavoriteNewsPost;
			$deleteModel = $deleteModel::withTrashed()->find($target[0]->MK);
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
	
	public function reportInappropriate($id) {
		
		try {
			// Check if news post or user does not exist
			$targetPost = DB::select('select count(*) as count from f_newspost WHERE MK = ?', array($id));
			$targetUser = DB::select('select count(*) as count from f_registereduser WHERE MK = ?', array(Input::get('registereduser_mk')));
			
			if ($targetPost[0]->count == 0 || $targetUser[0]->count == 0) {
				throw new Exception('Either user or newspost to be reported does not exist');
			}
			
			// Report news post
			DB::beginTransaction();
			$flaggedNewsPost = new FlaggedNewsPost();
			$flaggedNewsPost->F_CREATE_KEY = parent::getMomentString('YmdHisu').'/'.parent::getIP();
			$flaggedNewsPost->NEWSPOST_MK = $id;
			$flaggedNewsPost->REGISTEREDUSER_MK = Input::get('registereduser_mk');
			$flaggedNewsPost->EXPLANATION = Input::get('explanation');
			$flaggedNewsPost->save();
			DB::commit(); 
			
			return \Response::json(array('success' => true),200);
		} catch (\Exception $e) {
			DB::rollback();
			Log::error('Something is really going wrong.');
			Log::error($e);
			return \Response::json(array('success' => false),500);
		}
		
	}
	
	public function viewComments($id) {
		
		try {
			// Check if news post exist
			$exists = DB::select('select count(*) as count from f_newspost WHERE MK = ?', array($id));
			if ($exists[0]->count == 0) {
				throw new Exception('News post does not exist: '.$exists[0]->count);
			}
			
			// Get comments
			$finalData = NewsPostComment::join('f_registereduser', 'f_newspostcomment.REGISTEREDUSER_MK', '=', 'f_registereduser.MK')->select(DB::raw('comment, postedat, registereduser_mk, username, profilepic'))->where('newspost_mk', '=', $id)->orderBy('postedat', 'ASC')->get()->toArray();
			return \Response::json(array('success' => true, 'data' => $finalData),200);
		} catch (\Exception $e) {
			
			Log::error('Something is really going wrong.');
			Log::error($e);
			return \Response::json(array('success' => false),500);
		}
		
	}
	
	public function addComment($id) {
		
		try {
			// Check if news post exist
			$exists = DB::select('select count(*) as count from f_newspost WHERE MK = ?', array($id));
			if ($exists[0]->count == 0) {
				throw new Exception('News post does not exist: '.$exists[0]->count);
			}
			
			DB::beginTransaction();
			// Create and save comment
			$obj = new NewsPostComment;
			$obj->F_CREATE_KEY = parent::getMomentString('YmdHisu').'/'.parent::getIP();
			$obj->REGISTEREDUSER_MK = Input::get('registereduser_mk');
			$obj->NEWSPOST_MK = $id;
			$obj->COMMENT = Input::get('comment');;
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
