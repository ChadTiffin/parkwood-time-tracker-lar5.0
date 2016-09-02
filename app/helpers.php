<?php

function format_datetime($date,$format = "date")
{
	if (is_numeric($date)) {
		$timestamp = $date;
	}
	else {
		$timestamp = strtotime($date);
	}

	if ($format == "time") {
		return date("H:i A", $timestamp);
	}
	else {
		return date("M j, Y l", $timestamp);
	}
	
}

function httpPost($url, $data)
{
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}