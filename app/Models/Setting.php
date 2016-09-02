<?php

class Setting extends Eloquent {
	
	public static function getSetting($setting_name)
	{
		//return Setting::where("setting_name", "=", $setting_name)->where("user_id","=",Auth::user()->id)->firstOrFail();

		//return SettingValue::where("setting_name", "=", $setting_name)->where("user_id","=",Auth::user()->id)->firstOrFail();

		$q = "SELECT setting_name, label, type, options, description, value, settings.id as id FROM settings 
		RIGHT JOIN setting_values ON setting_values.setting_id=settings.id
		WHERE setting_name=? AND user_id=? LIMIT 1";

		$result =  DB::select($q,array($setting_name,Auth::user()->id));

		if (count($result) > 0) {
			return $result[0];
		}
		else {
			return false;
		}
	}
}