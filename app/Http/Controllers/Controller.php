<?php

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

class Controller extends BaseController
{
    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

/**
	 * Checks if user is clocked in or not. If clocked in, return clock-in time, else return false
	 *
	 * @return datetime/boolean
	 */
	public function clockedInTime()
	{
		if (Auth::check()) {
			$open_logs = TimeLog::where("clocked_out","=", null)->where("user_id","=",Auth::user()->id)->take(1)->get();

			if (count($open_logs) != 0) {
				return $open_logs[0]->clocked_in;
			}
			else {
				return false;
			}
		}
		else {
			return false;
		}
	}

	/**
	* Set all data common to all views
	* @return array
	*
	*
	*/
	public function compileHeaderData()
	{
		$clock_time = $this->clockedInTime();

		if (!$clock_time) {
			$data['clock_direction'] = "IN";
			$data['status'] = "Clocked out.";
			$data['clock_btn_type'] = "btn-success";
		}
		else {
			
			$data['clock_direction'] = "OUT";
			$data['status'] = "Clocked in at ".date("h:i A",strtotime($clock_time));
			$data['clock_btn_type'] = "btn-warning";
		}

		return $data;
	}
}
