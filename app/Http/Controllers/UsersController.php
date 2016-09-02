<?php

class UsersController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

///////////////////
// 'VIEW' LOADERS
///////////////////

	public function showUsers()
	{

		$users = User::all();

		$new_users = [];
		foreach ($users as $user) {
			$open_logs = TimeLog::where("clocked_out","=", null)->where("user_id","=",$user->id)->take(1)->get();

			if (count($open_logs) != 0) {
				$status = "<div class='text-success'>Clocked in at ". $open_logs[0]->clocked_in."</div>";
			}
			else {
				$status = "<span class='text-warning'>Clocked Out</span>";
			}

			$user['status'] = $status;

			$new_users[] = $user;
		}

		$data['users'] = json_decode(json_encode($new_users));

		$data['header_data'] = $this->compileHeaderData();

		return View::make('pages.users',$data);
	}

	public function editUser()
	{
		$input = Request::all();

		$edit_type = "edit";
		if ($input['id'] != "") {
			$user = User::find($input['id']);
		}
		else {
			//create new user

			$user = new User;

			$user->password = Hash::make("password");

			$edit_type = "new";
		}

		$user->first_name = $input['edit-first-name'];
		$user->last_name = $input['edit-last-name'];
		$user->email = $input['edit-email'];

		$user->save();

		//create user settings records if new user
		if ($input['id'] == "") {
			$setting = new SettingValue;

			$setting->value = "";
			$setting->setting_id = "1";
			$setting->user_id = $user->id;

			$setting->save();

			$setting = new SettingValue;

			$setting->value = "";
			$setting->setting_id = "2";
			$setting->user_id = $user->id;

			$setting->save();
		}

		return $edit_type;
	}

}
