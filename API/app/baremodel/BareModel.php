<?php /*
<<internal_table_name>>
*/ ?>
<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class <<model_class_name>> extends BaseModel  implements UserInterface, RemindableInterface{

	use UserTrait, RemindableTrait;
	
	protected $fillable = ['<<cs_fillable_fields>>'];

	public function __construct() {
	
		parent::__construct();
		$this->table =  self::PREFIX.'<<prefixless_table_name>>';
	}

	
}

?>
