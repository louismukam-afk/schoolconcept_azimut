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
	protected $fillable = ['name', 'email', 'password','role'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['password', 'remember_token'];

}
