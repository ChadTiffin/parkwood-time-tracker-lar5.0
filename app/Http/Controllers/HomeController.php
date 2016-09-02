<?php

class HomeController extends Controller {

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

	public function showLogin()
	{

		$data['header_data'] = $this->compileHeaderData();

		return View::make('login',$data);
	}

	public function logout()
	{
		
		$data['header_data'] = $this->compileHeaderData();
		Auth::logout();
		return View::make('login',$data);
	}

	public function showSummary()
	{

		$logged_in_user_id = Auth::user()->id;

		$q_base = "SELECT SUM(ABS(TIMESTAMPDIFF(MINUTE, clocked_out, clocked_in))) as sum FROM time_logs WHERE user_id='$logged_in_user_id' AND ";

		/////////////////////////////
		// SUMMARY FOR THIS WEEK
		////////////////////////////////

		//find total hours for each day of week
		$last_sunday = date("Y-m-d",strtotime('-2 weeks sunday'));

		//loop through next 14 days and totalize times for each day
		$daily_totals = array();
		$this_week_total = 0;
		$last_week_total = 0;

		for ($i=0; $i < 14; $i++) { 
			$current_iterated_day = new DateTime($last_sunday);
			$current_iterated_day->add(new DateInterval("P".$i."D"));

			$q = "$q_base DATE(clocked_in) = ?";
			$days_logs = DB::select($q,array($current_iterated_day->format("Y-m-d")));

			$daily_total = round($days_logs[0]->sum/60,1);

			if ($i <= 6) {
				$last_week_total += $daily_total;
			}
			else {
				$this_week_total += $daily_total;
			}

			if ($daily_total == 0) {
				$daily_total = "-";
			}
			else {
				$daily_total .= " hrs";
			}

			$daily_totals[] = $daily_total;

		}

		$data['daily_totals'] = $daily_totals;
		$data['last_week_total'] = $last_week_total;
		$data['this_week_total'] = $this_week_total;

		/////////////////////////////
		// MONTHLY SUMMARY
		////////////////////////////////

		$current_year = date("Y");
		$monthly_totals = array();
		$monthly_total = 0;
		$year_total = 0;

		for ($i=0; $i < 12; $i++) { 
			$current_iterated_month = new DateTime("$current_year-01-01");
			$current_iterated_month->add(new DateInterval("P".$i."M"));

			$q = "$q_base MONTH(clocked_in) = ?";
			$months_logs = DB::select($q,array($current_iterated_month->format("m")));

			$monthly_total = round($months_logs[0]->sum/60,1);

			if ($monthly_total == 0) {
				$monthly_total = "-";
			}
			else {
				$monthly_total .= " hrs";
			}

			$monthly_totals[] = $monthly_total;
			$year_total += $monthly_total;
		}

		$data['monthly_totals'] = $monthly_totals;
		$data['year_total'] = $year_total;

		/////////////////////////////
		// TODAY'S LOGS
		/////////////////////////////

		$q = "SELECT clocked_in, clocked_out, ABS(TIMESTAMPDIFF(MINUTE,clocked_in,clocked_out)) as shift_total FROM time_logs WHERE DATE(NOW()) = DATE(clocked_in) AND user_id='$logged_in_user_id'";
		$todays_logs = DB::select($q);

		$data['today_logs'] = $todays_logs;
		$data['yr'] = date("Y");

		$data['header_data'] = $this->compileHeaderData();
 
		return View::make('pages.summary',$data);
	}

	public function showLogs($userId, $dateStart = "2000-01-01", $dateEnd = "2100-01-01", $minShift = 0, $maxShift = 9999)
	{

		$result = TimeLog::getFilteredLogs($userId, $dateStart,$dateEnd,$minShift,$maxShift);

		$data['header_data'] = $this->compileHeaderData();

		$data['logs'] = $result['results'];
		$data['users'] = User::all();

		$data['query_total'] = round($result['total']/60,2);

		return View::make('pages.log_list',$data);
	}

//////////////////////////
// AJAX REQUESTS
//////////////////////////

	public function punchClock()
	{

		//check if there is an open log
		$time_log = $this->clockedInTime();
		$user_id = Auth::user()->id;

		$current_time = date("Y-m-d H:i:s");

		if (!$time_log) {
			//Punch user IN

			$time_log = new TimeLog;
			$time_log->clocked_in = $current_time;
			$time_log->user_id = $user_id;

			$time_log->save();

			echo format_datetime($current_time,"time");
		}
		else {
			//Punch user out

			$time_log = TimeLog::where("clocked_out","=", null)->where("user_id","=",$user_id)->take(1)->get();
			$id = $time_log[0]->id;

			$time_log = TimeLog::find($id);

			$time_log->clocked_out = $current_time;
			$time_log->save();

			echo "clocked out";
		}

		
	}

	public function msgSlack()
	{
		$clock_direction = Request::get("direction");

		$slack_api = "https://hooks.slack.com/services/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";

		$first_name = Auth::user()->first_name;

		$message = "";
		if ($clock_direction == "in") {
			$message = "$first_name has clocked in.";
		}
		else {
			$message = "$first_name has clocked out.";
		}

		$payload['text'] = $message;
		$msg['payload'] = json_encode($payload);
		httpPost($slack_api,$msg);

	}

	public function editLog()
	{
		$cl_in = Request::get("edit-clocked-in");
		$cl_out = Request::get("edit-clocked-out");
		$log_id = Request::get("id");

		$time_log = TimeLog::find($log_id);

		if ($cl_out == "") {
			$cl_out = null;
		}

		$time_log->clocked_in = $cl_in;
		$time_log->clocked_out = $cl_out;

		if ($cl_out == null) {
			$cl_out = "[in progress]";
			$total_hrs = "...";
		}
		else {
			$total_hrs = round((strtotime($time_log->clocked_out) - strtotime($time_log->clocked_in))/60/60,2);
			$cl_out = format_datetime($cl_out,'time');
		}

		$time_log->save();

		$log = array(
			'date' => format_datetime($time_log->clocked_in),
			'clocked_in' => format_datetime($cl_in,"time"), 
			'clocked_out' => $cl_out,
			"total" => $total_hrs
		);

		echo json_encode($log);
	}

	public function deleteLog()
	{
		$log_id = Request::get('id');

		$log = TimeLog::find($log_id);
		$log->delete();
	}

	public function sendEmailReport()
	{
		$fields = Request::all();

		$timeLogObj = new TimeLog();
		$result = $timeLogObj->getFilteredLogs(Auth::user()->id,$fields['from-date'],$fields['to-date'],0,9999);

		$total_hrs = $result['total'];

		$date_range_text = date("F j",strtotime($fields['from-date']))." to ".date("F j",strtotime($fields['to-date']));

		$body = "<p>Hi,</p>  
			<p>My total hours from $date_range_text is ".round($total_hrs/60,1)." hrs.</p>
			<p>Cheers,</p>
			<p>".Auth::user()->first_name."</p>";

		if ($fields['report-type'] == "hrs only") {
			
		}
		elseif ($fields['report-type'] == "full") {

		}

		Mail::send("emails.plaintext",array("msg" => $body),function($message){

			$payroll_email_setting = Setting::getSetting("payroll_email");
			$email_subject = Setting::getSetting("report_subject");

			$message->to($payroll_email_setting->value)
				->from(Auth::user()->email)
				->subject($email_subject->value);
		});		

	}

	public function processLogin()
	{
		$email = Request::get("email");
		$password = Request::get("password");

		if (Auth::attempt(array('email' => $email, "password" => $password))) {
			return "true";
		}
		else {
			return "false";
		}
	}

}
