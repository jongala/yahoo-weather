<?php
/*
 * PHP Yahoo weather utility for the command line.
 * By Jonathan Gala : jon@jongala.com | github.com/jongala
 * License: WTFPL: http://sam.zoy.org/wtfpl/
 */

$help = <<<EOS
Simple Yahoo Weather utility.

Usage:
$ php yahoo_weather.php [-cfCF] [zip]

Flags:
    -c, --conditions          Displays only the current conditions
    -f, --forecast            Displays only the forecast
    -C                        Use Celsius for temperatures
    -F                        Use Farenheit for temperatures (default)

Example:
$ php yahoo_weather.php 11201
> Cloudy, 70˚F
  Wed: Showers Early, H 74˚ | L 63˚
  Thu: Partly Cloudy, H 75˚ | L 61˚


EOS;

// Yahoo API url
$url="http://weather.yahooapis.com/forecastrss?p=%s&u=%s";

// Args
$conditions_only = false;
$forecast_only = false;
$units = 'f';
$zip = '';

// Process args
foreach($argv as $arg) {
	if ($arg == '--help') {
		print $help;
		exit;
	}
	if ($arg == '--forecast' || $arg == '-f') {
		$forecast_only = true;
	}
	if ($arg == '--conditions' || $arg == '-c') {
		$conditions_only = true;
	}
	if ($arg == '-C') {
		$units = 'c';
	}
	if (preg_match('/\d{5}/', $arg)) {
		$zip = $arg;
	}
}

if( !strlen($zip) ) {
	print "ERROR: You must supply a 5-digit zip code.\n";
	print "------------------------------------------\n\n";
	print $help;
	exit;
}

$units_label = ($units == 'f')? 'F':'C';

$url = sprintf($url, $zip, $units);

$wx = simplexml_load_file($url);

$c_text = $wx->xpath('//yweather:condition/@text');
$c_temp = $wx->xpath('//yweather:condition/@temp');
$condition_string = sprintf("%s, %s°%s\n", $c_text[0], $c_temp[0], $units_label);

$forecasts = $wx->xpath('//yweather:forecast');
$forecast_pattern = "%s: %s, H %s° | L %s° \n";
$forecast_string = '';

foreach($forecasts as $forecast) {
	$f_data = $forecast->attributes();
	
	$f_day = $f_data['day'];
	$f_text = $f_data['text'];
	$f_high = $f_data['high'];
	$f_low =  $f_data['low'];

	$forecast_string .= sprintf($forecast_pattern, $f_day, $f_text, $f_high, $f_low);
}

if(!$forecast_only) print $condition_string;
if(!$conditions_only) print $forecast_string;

?>