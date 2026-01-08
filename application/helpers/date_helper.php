<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * AdminCMS
 * @package   AdminCMS
 * @author    Yunus Shaikh {contributed}
 * @since     Version 1.0
 */
/**
 * Date helper functions.
 *
 * Includes additional date-related functions helpful in AdminCMS development.
 *
 */
if ( ! function_exists('dateDiff'))
{
	
	
	function dateDiff($first, $second=NULL, $unit = 'full')
	{
		if (is_null($second)) $second = date('Y-m-d H:i:s');
		$first = (int)strtotime($first);
		$second = (int)strtotime($second);
		$subTime = $second - $first;
		if($first <=0)
		{
		  echo 'First parameter is wrong';
		  return false; 	
		}
		if($second <=0)
		{
		  echo 'second parameter is wrong';
		  return false; 	
		}
		if ($unit == 'y') return (int) $subTime/(60*60*24*365).'  Years';
		if ($unit == 'M') return (int) ($subTime/(60*60*24*365)/30).'  Years';
		if ($unit == 'd') return (int) ($subTime/(60*60*24)).'  Days';
		if ($unit == 'h') return (int) ($subTime/(60*60)).' Hours';
		if ($unit == 'm') return (int) ($subTime/60).' Minits';
		if ($unit == 's') return (int) ($subTime).' Seconds';
		if ($unit == 'w') return (int) ($subTime/60*60*24*7).' Weeks';
		if ($unit == 'full') return intval($subTime/(60*60*24*365)).'  Years , '.intval($subTime/(60*60*24*7)).'  Weeks ,'.intval($subTime/(60*60*24)).'  Days , '.intval($subTime/(60*60)).' Hours , '.intval($subTime/60).' Minits and '.intval($subTime).' Seconds ';
		
	}
}

if ( ! function_exists('calculateAge'))
{
	function calculateAge($input,$givenDate=NULL,$flag=0)
	{
		$inputDate = str_replace('/','-',$input);
		$dob = date('d-m-Y',strtotime($inputDate));
		$dob = strtotime($dob);
		
		$givenDate = str_replace('/','-',$givenDate);
		$givenDate = date('d-m-Y',strtotime($givenDate));
		/*echo time()."<br>";
		echo strtotime('12-05-2016');exit;*/
		if($givenDate)
		{
			$current_time = strtotime($givenDate);
		}
			
		else
			$current_time = time();
		
		$age_years = date('Y',$current_time) - date('Y',$dob);
		$age_months = date('m',$current_time) - date('m',$dob);
		$age_days = date('d',$current_time) - date('d',$dob);
		
		if ($age_days<0) {
			$days_in_month = date('t',$current_time);
			$age_months--;
			$age_days= $days_in_month+$age_days;
		}
		
		if ($age_months<0) {
			$age_years--;
			$age_months = 12+$age_months;
		}
		if($flag)
			echo $age_years.".".$age_months;
		else
			echo "$age_years Yrs $age_months M";
	}
}

if (! function_exists('relativeDate')) {
    /**
     * Return a string representing how long ago a given UNIX timestamp was,
     * e.g. "moments ago", "2 weeks ago", etc.
     *
     *Auther: Yunus Shaikh
     * @param int $timestamp A UNIX timestamp.
     *
     * @return string A human-readable amount of time 'ago'.
     */
    function relativeDate($timestamp)
    {
        if ($timestamp != '' && ! is_int($timestamp)) {
            $timestamp = strtotime($timestamp);
        }

        if (! is_int($timestamp)) {
            return "You have passed invalid date";
        }

        $difference = time() - $timestamp;
        $periods = array('moment', 'min', 'hour', 'day', 'week', 'month', 'year', 'decade');
        $lengths = array('60', '60', '24', '7', '4.35', '12', '10', '10');

        if ($difference >= 0) {
            // This was in the past
            $ending = "ago";
        } else {
            // This is in the future
            $difference = -$difference;
            $ending = "to go";
        }
      //x /= y 	  :   x = x / y
        for ($j = 0; $difference >= $lengths[$j]; $j++) {
            $difference /= $lengths[$j]; 
        }

        $difference = round($difference);
        if ($difference != 1) {
            $periods[$j] .= "s";
        }

        if ($difference < 60 && $j == 0) {
            return "{$periods[$j]} {$ending}";
        }

        return "{$difference} {$periods[$j]} {$ending}";
    }
}

// date formatting for mysql dates
if (! function_exists('humanDate')) {
function humanDate($date, $fmt = '', $timezone = '', $seconds = FALSE)
	{
	if ($timezone === '')
	{
		$timezone = 'Asia/Kolkata';
	}
	if (!$fmt)
	{
		 $fmt = 'jS M Y';
	}
	if ($seconds)
	{
		$fmt .= ', H:i';
	}
	if ($date && $date > 0)
	{
		$timestamp = strtotime($date);
	
		return date($fmt, $timestamp);
	}
	else
	{ 
		return false;
	}
	}
}
// Date manipulation --------------------
if (! function_exists('dmyDate')) {
function dmyDate($date) 
{
	return strftime("%m-%d-%Y", strtotime($date)); // input:  2009-12-01 19:31:38   --> output 01-12-2009
}}
if (! function_exists('amyhmsDate')) {
function amyhmsDate($date) 
{
	return strftime("%d-%m-%Y %H:%M", strtotime($date)); // output 01-12-2009 19:31
//	return strftime("%A, %d-%m-%Y %I:%M %p", strtotime($date)); // output Tuesday, 01-12-2009 9:31 AM
}}
if (! function_exists('intDate')) {
function intDate($date) 
{
	return date("Y-m-d",$date); // input:  548751255  --> output 01-12-2009
}}

/**
 * MySQL datetime from a DD.MM.YYYY string
 * @param	String	French formatted datetime
 * @param	String	MySQL formatted datetime
 */
if ( ! function_exists('mysqlDate'))
{
	function mysqlDate($inputDate, $inputFormat='dd.mm.yyyy')
	{
		if ($inputDate !='')
		{
			$date = $time = '';
			if (strlen($inputDate) > 10)
			{
				list($date, $time) = explode(' ', $inputDate);
			}
			else
			{
				$date = $inputDate;
				$time = '00:00:00';
			}

			if ($inputFormat == '%d.%m.%Y')
			{
				list($day, $month, $year) = preg_split("/[\/.-]/", $date);
			}
			else if ($inputFormat == '%Y.%m.%d')
			{
				list($year, $month, $day) = preg_split("/[\/.-]/", $date);
			}
			else
				return date('Y-m-d H:i:s', strtotime($inputDate));

			return "$year-$month-$day $time";
		}
	}
}

