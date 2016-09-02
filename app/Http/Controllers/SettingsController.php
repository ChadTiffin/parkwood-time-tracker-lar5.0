<?php

class SettingsController extends Controller {

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

	public function showSettings()
	{

		//$data['settings'] = DB::table("setting_values")
			//->join("settings","settings.id","=","setting_values.setting_id")->where('user_id','=',Auth::user()->id)->get();
		$user_id = Auth::user()->id;

		$q = "SELECT setting_values.id as id, value, setting_name, label, type, options, description FROM settings
		RIGHT JOIN setting_values ON setting_values.setting_id=settings.id
		WHERE setting_values.user_id = '$user_id'";
		$data['settings'] = DB::select($q);

		$data['header_data'] = $this->compileHeaderData();

		return View::make('pages.settings',$data);
	}

///////////////
// REQUESTS
///////////////

	public function saveSettings()
	{
		$settings = Request::all();

		foreach ($settings as $key => $setting) {

			if ($key != "_token") {
				//file_put_contents("test.txt", $key."\n\n",FILE_APPEND);

				$fetched_setting = SettingValue::find($key);

				$fetched_setting->value = $setting;
				$fetched_setting->save();
			}
		}
	}

	public function changePassword()
	{
		$user = Auth::user();

		$user->password = Hash::make(Request::get("new-psw"));

		$user->save();
	}

}
