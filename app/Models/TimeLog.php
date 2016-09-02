<?php

class TimeLog extends Eloquent {
	public $timestamps = false;

	/**
	* Return a filtered list of time logs
	* @return array 
	*
	*
	*
	*/
	public static function getFilteredLogs($userId, $dateStart,$dateEnd,$minShift,$maxShift)
	{
		$user_match = "user_id != ? AND";
		if ($userId != "all") {
			$user_match = "user_id = ? AND";
		}
		else {
			$userId = 0;
		}

		$q = "SELECT time_logs.id as id, first_name, last_name, clocked_in, clocked_out, ABS(TIMESTAMPDIFF(MINUTE,clocked_in,clocked_out)) as shift_total FROM time_logs
		RIGHT JOIN users ON users.id=time_logs.user_id 
		WHERE
			$user_match
			DATE(clocked_in) >= ? AND
			DATE(clocked_in) <= ? AND
			(ABS(TIMESTAMPDIFF(MINUTE,clocked_in,clocked_out))/60 >= ? AND
			ABS(TIMESTAMPDIFF(MINUTE,clocked_in,clocked_out))/60 <= ?)
			ORDER BY clocked_in ASC";

		$logs = DB::select($q,array($userId, $dateStart,$dateEnd,$minShift,$maxShift));

		$query_total = 0;
		foreach ($logs as $log) {
			$query_total += $log->shift_total;
		}

		return array("total" => $query_total, "results" => $logs);
	}
}