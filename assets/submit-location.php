<?php

	# set post
	$streetAd = $_POST['streetAd'];
	$city = $_POST['city'];
	$state = $_POST['state'];
	$zip = $_POST['zip'];


	# get rid of spaces and add +
	$streetAd = replaceSpaces($streetAd);
	$city = replaceSpaces($city);
	$state = replaceSpaces($state);
	$zip = replaceSpaces($zip);

	# set API key and base url
	$key = 'AIzaSyDzB-XZC1m9cIL2XP4iT3Awfp64Iu9V0CM';
	$base_url = 'https://maps.googleapis.com/maps/api/geocode/json?address=';

	# http request url
	$url = $base_url . $streetAd . ',' . $city . ',' . $state . $zip . '&key=' . $key;

	# send http request
	$coordinates = @file_get_contents($url);

	# return json to js script
	echo $coordinates;


	# gets rid of spaces and adds +
	function replaceSpaces($expression) {
		$new = '';
		$tmp = explode(' ', $expression);
		foreach ($tmp as $word) {
			$new .= '+' . $word;
		}
		return $new;
	}
?>
