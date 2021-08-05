<?php namespace gesecole;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

/**
 * gesecole\User
 *
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property string $role
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\User whereRole($value)
 * @property string $fullname
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\User whereFullname($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\User whereUpdatedAt($value)
 * @property int $active
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\User whereActive($value)
 * @property int|null $id_personnel
 * @method static \Illuminate\Database\Eloquent\Builder|\gesecole\User whereIdPersonnel($value)
 */
class User extends Model implements AuthenticatableContract, CanResetPasswordContract {

	use Authenticatable, CanResetPassword;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['name', 'fullname', 'password','role','active','id_personnel'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

	public function canDoAction($actionAgainst) {

        if($this->isAdmin()) {
            return true;
        }
        $a = Action::where('action_name', 'like', $actionAgainst.'%')
            ->where('id_user', '=', $this->id)->first();
        if($a != null) {
            return true;
        }
	    return false;
    }

    public function isAdmin() {

        $a = Action::whereRaw('(action_name = ? OR action_name = ?) AND id_user = ?',[Action::ACTION_ALL, Action::ACTION_ALMOST_ALL, $this->id])->first();
        if($a != null) {
            return true;
        }

        return false;
    }

    public function isCreator() {
	    return $this->hasSpecificAction(Action::ACTION_ALL);
    }
    public function isTeacher() {
	    return $this->hasSpecificAction(Action::ACTION_TEACHER);
    }



    public function hasSpecificAction($actionAgainst) {
	    $a = Action::where('action_name', '=', $actionAgainst)
            ->where('id_user', '=', $this->id)->first();
        if($a != null) {
            return true;
        }
        return false;
    }

    public function actions() {
        return  $this->hasMany(Action::class,'id_user');

    }
    public function roles()
    {
        return  $this->hasMany(Role::class,'id_user');
    }

}
