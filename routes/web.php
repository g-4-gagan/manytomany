<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Models\Role;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/user{id}/create',function($id){
	$user = User::findOrFail($id);

	$role = new Role(['name'=>'Administrator']);

	// $role = Role::find(1);

	$user->roles()->save($role);

	return 'done';
});

Route::get('/user{id}/roles',function($id){
	$user = User::findOrFail($id);

	return $user->roles;
});

Route::get('/user{id}/updaterole',function($id){
	$user = User::findOrFail($id);

	if ($user->has('roles')) {
		foreach ($user->roles as $role) {
			if ($role->name == 'Administrator') {
				$role->name = 'subscriber';
				$role->save();
				return"done";
			}
		}
	}
});

Route::get('/user{id}/role{id2}/delete',function($id,$id2){
	$user = User::findOrFail($id);
    
	foreach ($user->roles as $role) {
		$role->whereId($id2)->delete();
		return 'done';
	}
});

Route::get('/user{id}/role{id2}/attach',function($id,$id2){
	$user = User::findOrFail($id);

	$user->roles()->attach($id2);

	return"done";
});

Route::get('/user{id}/role{id2}/detach',function($id,$id2){
	$user = User::findOrFail($id);

	$user->roles()->detach($id2);

	return"done";
});

Route::get('/sync',function(){
	$user = User::findOrFail(1);

	$user->roles()->sync([2,3]);
});
