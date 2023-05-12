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
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/createuser/{user_name}/{email}/{password}', function ($user_name, $email, $password) {
    User::create(['name' => $user_name, 'email' => $email, 'password' => $password]);
    return redirect('/readusers');
});
Route::get('/createrole/{user_id}/{role_name}', function ($user_id, $role_name) {
    User::findOrFail($user_id)->roles()->save(Role::create(['name' => $role_name]));
    return redirect('/readroles');
});
Route::get('/readusers', function () {
    foreach (User::all() as $user) {
        echo '<br>-------------------------------------';
        echo '<br>Name: ' . $user->name;
        echo '<br>Email: ' . $user->email;
        echo '<br>Roles: ';
        foreach ($user->roles as $role) {
            echo $role->name . ' [id ' . $role->id . ']; ';
        }
    };
});
Route::get('/readroles', function () {
    foreach (Role::all() as $role) {
        echo '<br>-------------------------------------';
        echo '<br>Role: ' . $role->name . ' [id ' . $role->id . ']';
        echo '<br>Users: ';
        foreach ($role->users as $user) {
            echo $user->name . '; ';
        }
    }
});
Route::get('/updateuser/{user_id}/{user_name}/{email}/{password}', function ($user_id, $user_name, $email, $password) {
    User::findOrFail($user_id)->update(['name' => $user_name, 'email' => $email, 'password' => $password]);
    return redirect('/readusers');
});
Route::get('/updaterole/{role_id}/{role_name}', function ($role_id, $role_name) {
    Role::findOrFail($role_id)->update(['name' => $role_name]);
    return redirect('/readroles');
});
Route::get('/updateuserrole/{user_id}/{user_role}', function ($user_id, $role_id) {
    $user = User::findOrFail($user_id);
    if (!$user->roles->where('id', $role_id)->first()) {
        $user->roles()->attach($role_id);
    } else $user->roles()->detach($role_id);
    return redirect('/readusers');
});
Route::get('/deleteuser/{user_id}', function ($user_id) {
    User::findOrFail($user_id)->delete();
    return redirect('/readusers');
});
Route::get('/deleterole/{role_id}', function ($role_id) {
    Role::findOrFail($role_id)->delete();
    return redirect('/readroles');
});