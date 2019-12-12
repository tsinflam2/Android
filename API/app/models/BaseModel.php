<?php
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class BaseModel extends Eloquent {
	use SoftDeletingTrait;

    const CREATED_AT = 'F_CREATED';
    const UPDATED_AT = 'F_UPDATED';
    const DELETED_AT = 'F_ARCHIVED';
    
    const PREFIX = 'f_';
  
    
    protected $primaryKey = 'MK';
    
    protected $dates = ['deleted_at'];
    
	protected $fillable = ['F_CREATE_KEY', 'F_CREATED_BY', 'F_UPDATED_BY'];



	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');
   
   
   
	// adjust boot function
    public static function boot()
    {
        // run parent
        parent::boot();

        static::updated(function($model)
        {
            		
			if ($model->F_ARCHIVED == null ) {
				// if action was to restore record	
				$model->F_RESTORED = new DateTime;
				$model->save();
			} else {
				// action was to archive record
				$model->F_RESTORED = 0;
				$model->save();			
			}
        });
    }
   
	public static function getAllTableNames()
    {
        switch (DB::connection()->getConfig('driver')) {
            

            case 'mysql':
                $query = "select `TABLE_NAME` from information_schema.tables WHERE TABLE_SCHEMA = 'framework'";
                $column_name = 'TABLE_NAME';
                $reverse = false;
                break;

          

            default: 
                $error = 'Database driver not supported: '.DB::connection()->getConfig('driver');
                throw new Exception($error);
                break;
        }

        $names = array();

        foreach(DB::select($query) as $name)
        {
            //$names[$name->$column_name] = $name->$column_name; // setting the table name as key too
			array_push($names,$name->$column_name);
        }

        if($reverse)
        {
            $names = array_reverse($names);
        }

        return $names;
    }
   
    public static function getAllColumnsNames($table_name)
    {
        switch (DB::connection()->getConfig('driver')) {
            case 'pgsql':
                $query = "SELECT column_name FROM information_schema.columns WHERE table_name = '".$this->getTable()."'";
                $column_name = 'column_name';
                $reverse = true;
                break;

            case 'mysql':
                $query = 'SHOW COLUMNS FROM '.$table_name;
                $column_name = 'Field';
                $reverse = false;
                break;

            case 'sqlsrv':
                $parts = explode('.', $this->getTable());
                $num = (count($parts) - 1);
                $table = $parts[$num];
                $query = "SELECT column_name FROM ".DB::connection()->getConfig('database').".INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = N'".$table."'";
                $column_name = 'column_name';
                $reverse = false;
                break;

            default: 
                $error = 'Database driver not supported: '.DB::connection()->getConfig('driver');
                throw new Exception($error);
                break;
        }

        $columns = array();

        foreach(DB::select($query) as $column)
        {
            $columns[$column->$column_name] = $column->$column_name; // setting the column name as key too
        }

        if($reverse)
        {
            $columns = array_reverse($columns);
        }

        return $columns;
    }
    
    public static function checkColumnsExist($table, $column_names_arr) {
        $cols_existing = self::getAllColumnsNames($table);
        foreach ($column_names_arr as $check_col) {
            if (!in_array($check_col, $cols_existing))
                return false;
        }
        return true;
    }
    
    public function getTableName() {
        return $this->table;
    }
    
	// 2nd param - boolean: only show soft deleted records
	// 3rd param - string: where clause
    public function fetchJson($cols_arr, $showDeleted = false, $whereRaw = null) {
		//$cols_arr = array('F_FLAGGED' => 'F_FLAGGED', 'MK' => 'ID', 'USERNAME' => 'Username', 'EMAIL' => 'Email', 'F_UPDATED' => 'Last Updated', 'F_CREATED' => 'Created');
		//$obj = new User();
		
		//$table_name_in_question = $obj->getTableName();
		$table_name_in_question = $this->getTableName();
		$cols_internal_arr = array();
		$cols_friendly_arr = array();
		
		foreach ($cols_arr as $internal_name => $friendly_name) {
			array_push($cols_internal_arr, $internal_name);
			array_push($cols_friendly_arr, $friendly_name);
		}
		
		// if requested columns exist
		if (self::checkColumnsExist($table_name_in_question, $cols_internal_arr)) {
			// hand out data to view
			//dd(array_values($cols_internal_arr));
			//dd(User::all());
			
			//User::find(4)->delete();
			//User::all();
			//$data = self::take(100)->get(array_values($cols_internal_arr))->toJson();
			if ($showDeleted)
				$data = self::onlyTrashed()->select(array_values($cols_internal_arr))->get()->toJson();
			else if ($whereRaw)
				$data = self::select(array_values($cols_internal_arr))->whereRaw($whereRaw)->get()->toJson();
			else
				$data = self::all(array_values($cols_internal_arr))->toJson();
			
			//$data = User::find(1);
			
			//var_dump($data);\
			return json_encode(['cols_friendly'=> $cols_friendly_arr, 'cols_internal' => $cols_internal_arr, 'data' => $data, 'success' => true]);
			
			//var_dump($json);
			
			//return View::make('users.index')->with(['cols_friendly'=> $cols_friendly_arr, 'cols_internal' => $cols_internal_arr, 'data' => $data]);
			
			
		} else return json_encode(['success' => false]);
	}
	
	
	public function archive($mk_arr) {
		foreach ($mk_arr as $mk) {
			self::find($mk)->delete();
		}
	}
	
	
	public function restoreArchived($mk_arr) {
		foreach ($mk_arr as $mk) {
			self::withTrashed()->find($mk)->restore();
		}
	}
	
	public function deleteForever($mk_arr) {
		foreach ($mk_arr as $mk) {
			self::withTrashed()->find($mk)->forceDelete();
		}
	}
	
	public function flag($mk_arr) {
		foreach ($mk_arr as $mk) {
			$user = self::find($mk);
			$user->F_FLAGGED = 1;
			$user->save();
		}
	}
	
	public function unflag($mk_arr) {
		foreach ($mk_arr as $mk) {
			$user = self::find($mk);
			$user->F_FLAGGED = 0;
			$user->save();
		}
	}
    
    
}