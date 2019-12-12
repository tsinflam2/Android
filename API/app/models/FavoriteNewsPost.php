<?php /*
f_favoritenewspost
*/ ?>
<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class FavoriteNewsPost extends BaseModel  implements UserInterface, RemindableInterface{

	use UserTrait, RemindableTrait;
	
	protected $fillable = ['MK', 'F_CREATE_KEY', 'F_CREATED', 'F_UPDATED', 'F_ARCHIVED', 'F_RESTORED', 'F_CREATED_BY', 'F_UPDATED_BY', 'F_ARCHIVED_BY', 'F_RESTORED_BY', 'F_UPDATE_LOCKED', 'F_ARCHIVE_LOCKED', 'F_RESTORE_LOCKED', 'F_FLAGGED', 'F_NOTES', 'REGISTEREDUSER_MK', 'NEWSPOST_MK', 'FAVORITEDAT'];

	public function __construct() {
	
		parent::__construct();
		$this->table =  self::PREFIX.'favoritenewspost';
	}

	
}

?>
