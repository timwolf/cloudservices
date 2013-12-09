<?php
/*
 * By installing or using this file, you are confirming on behalf of the entity
 * subscribed to the SugarCRM Inc. product ("Company") that Company is bound by
 * the SugarCRM Inc. Master Subscription Agreement ("MSA"), which is viewable at:
 * http://www.sugarcrm.com/master-subscription-agreement
 *
 * If Company is not bound by the MSA, then by installing or using this file
 * you are agreeing unconditionally that Company will be bound by the MSA and
 * certifying that you have authority to bind Company accordingly.
 *
 * Copyright  2004-2013 SugarCRM Inc.  All rights reserved.
 */

$alphabet="ABCDEFGHIJKLMNOPQRSTUVWXYZ";
$hexcharacters="0123456789abcdef";
$month_names = array("January", "February", "March", "April", "May", "June", "July",
                     "August", "September", "October", "November", "December");
$day_names   = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");


function startsWith($source, $aString)
  {
    $src = $source;
    $str = $aString;
    $lensrc=strlen($src);
    $lenstr=strlen($str);
    if ($lenstr > $lensrc)
      return(FALSE);
    if ($str == $src)
      return(TRUE);
    if (substr($src,0,$lenstr) == $str)
      return(TRUE);
    return(FALSE);
  }


function endsWith($source, $aString)
  {
    $src = $source;
    $str = $aString;
    $lensrc=strlen($src);
    $lenstr=strlen($str);
    if ($lenstr > $lensrc)
      return(FALSE);
    if ($str == $src)
      return(TRUE);
    $pos = $lensrc - $lenstr;
    if (substr($src,$pos,$lenstr) == $str)
      return(TRUE);
    return(FALSE);
  }


function contains($source, $aString)
  {
	$src = strtolower($source);
	$str = strtolower($aString);
	$lensrc=strlen($src);
	$lenstr=strlen($str);
	if ($lenstr > $lensrc)
		return(FALSE);
	if ($str == $src)
		return(TRUE);
	if (is_integer(strpos($src, $str)))
		return(TRUE);
	return(FALSE);
  }


function padLeft($s, $len)
  {
    $slen=strlen($s);
    for ($i=$slen; $i<$len; $i++)
      $s=" ".$s;
    return $s;
  }

function padRight($s, $len)
  {
    $slen=strlen($s);
    for ($i=$slen; $i<$len; $i++)
      $s=$s." ";
    return $s;
  }

function isNumeric($token)
  {
    $len=strlen($token);
    if ($len==0) return(FALSE);
    for ($i=0; $i<$len; $i++)
      {
        $temp=substr($token,$i,1);
        if (!($temp >= "0" && $temp <= "9"))
          return(FALSE);
      }
    return(TRUE);
  }


function isNumber($token)
  {          // also allows +/- in first pos
    $len=strlen($token);
    if ($len==0) return(FALSE);
    $temp=substr($token,0,1);
    if ($temp == "-" || $temp == "+")
      $token=substr($token,1);
    return (isNumeric($token));
  }


function stripEOL($s)
  {
    $len=strlen($s);
    if ($len>0)
      {
        if (substr($s,$len-1,1) == "\r" ||
            (substr($s,$len-1,1) == "\n"))
          $s=substr($s,0,$len-1);
      }
    $len=strlen($s);
    if ($len>0)
      {
        if (substr($s,$len-1,1) == "\r" ||
            (substr($s,$len-1,1) == "\n"))
          $s=substr($s,0,$len-1);
      }
    $len=strlen($s);
    return($s);
  }


function strip_slashes($s)
  {
    while (is_integer( strpos($s,"\\") ))
      $s=stripslashes($s);
    return $s;
  }

function getDigits($token)
  {
    $s="";
    $len=strlen($token);
    if ($len==0) return($s);
    for ($i=0; $i<$len; $i++)
      {
        $temp=substr($token,$i,1);
        if ($temp >= "0" && $temp <= "9")
           $s .= $temp;
      }
    return($s);
  }


function getAlphabetic($token)
  {
    $s="";
    $len=strlen($token);
    if ($len==0) return($s);
    for ($i=0; $i<$len; $i++)
      {
        $temp=substr($token,$i,1);
        if (($temp >= "a" && $temp <= "z") ||
            ($temp >= "A" && $temp <= "Z") )
           $s .= $temp;
      }
    return($s);
  }


function getAlphanumeric($token)
  {
    $s="";
    $len=strlen($token);
    if ($len==0) return($s);
    for ($i=0; $i<$len; $i++)
      {
        $temp=substr($token,$i,1);
        if (($temp >= "a" && $temp <= "z") ||
            ($temp >= "A" && $temp <= "Z") ||
            ($temp >= "0" && $temp <= "9") )
           $s .= $temp;
      }
    return($s);
  }


function stripa($string, $chr=" ")
 {
   $result="";
   $len = strlen($string);
   for ($i=0; $i<$len; $i++)
     {
       $s = substr($string,$i,1);
       if ($s != $chr)
         {
           $result .= $s;
         }
     }
   return ($result);
 }


function stripl($string, $chr=" ")
 {
   $j=0;
   $len = strlen($string);
   for ($i=0; $i<$len; $i++)
     {
       $s = substr($string,$i,1);
       if ($s != $chr)
         {
           return substr($string,$i,($len-$j));
         }
       $j++;
       if ($j == $len)
         {
           return "";
         }
     }
 }


function stript($string, $chr=" ")
 {
   $len1 = strlen($string);
   $len2 = $len1;
   for ($i=$len2-1; $i>=0; $i--)
     {
       if (substr($string,$i,1) == $chr)
         $len2--;
       else
         break;
     }

   if ($len2 == 0)
       return "";
   if ($len2 != $len1)
       return substr($string,0,$len2);
   return $string;
 }


function striplt($string, $chr=" ")
 {
   $result = stripl($string, $chr);
   $result = stript($result, $chr);
   return ($result);
 }


function stripchr($string,$ch)
 {
   $result="";
   $len = strlen($string);
   for ($i=0; $i<$len; $i++)
     {
       $s = substr($string,$i,1);
       if ($s != $ch)
         {
           $result .= $s;
         }
     }
   return ($result);
 }

function asTrueFalse($b,$uppercase=FALSE)
  {
     if ($b) return("true");
     return("false");
  }

function asYesNo($b)
  {
     if ($b) return("yes");
     return("no");
  }


//--------------------------------------------------------------------
// DATE/TIME Fuctions
//--------------------------------------------------------------------

 /*****
    a=Wed                      %a - abbreviated weekday name according to the current locale
    A=Wednesday                %A - full weekday name according to the current locale
    b=Apr                      %b - abbreviated month name according to the current locale
    B=April                    %B - full month name according to the current locale
    c=04/19/00 21:28:06        %c - preferred date and time representation for the current locale
    d=19                       %d - day of the month as a decimal number (range 00 to 31)
    H=21                       %H - hour as a decimal number using a 24-hour clock (range 00 to 23)
    I=09                       %I - hour as a decimal number using a 12-hour clock (range 01 to 12)
    j=110                      %j - day of the year as a decimal number (range 001 to 366)
    m=04                       %m - month as a decimal number (range 1 to 12)
    M=28                       %M - minute as a decimal number
    p=PM                       %p - either `am' or `pm' according to the given time value, or the corresponding strings for the current locale
    S=06                       %S - second as a decimal number
    U=16                       %U - week number of the current year as a decimal number, starting with the first Sunday as the first day of the first week
    W=16                       %W - week number of the current year as a decimal number, starting with the first Monday as the first day of the first week
    w=3                        %w - day of the week as a decimal, Sunday being 0
    x=04/19/00                 %x - preferred date representation for the current locale without the time
    X=21:28:06                 %X - preferred time representation for the current locale without the date
    y=00                       %y - year as a decimal number without a century (range 00 to 99)
    Y=2000                     %Y - year as a decimal number including the century
    Z=Eastern Daylight Time    %Z - time zone or name or abbreviation
  *****/


function gmtime($tm=0)
  {
    if ($tm==0)
      return time() - (int) date('Z');
    return $tm - (int) date('Z');
  }


function timestampFromDateArray($dateArray)
  {
    $timestamp = mktime(
              0,
              0,
              0,
              $dateArray["month"],
              $dateArray["day"],
              $dateArray["year"] );
    return $timestamp;
  }

function timestampFromDateTimeArray($datetimeArray)
  {
    $timestamp = mktime(
              $datetimeArray["hour"],
              $datetimeArray["minute"],
              $datetimeArray["second"],
              $datetimeArray["month"],
              $datetimeArray["day"],
              $datetimeArray["year"] );
    return $timestamp;
  }


function timestampFromDateString($dateAsString)
  {
    $dateArray = dateToArray($dateAsString);
    $timestamp = timestampFromDateArray($dateArray);
    return $timestamp;
  }

function getDateToday()
  {
    $tm=time();
    $mm=strftime("%m",$tm);
    $dd=strftime("%d",$tm);
    $yy=strftime("%Y",$tm);
    $dateToday = sprintf("%04d-%02d-%02d",$yy,$mm,$dd);
    return($dateToday);
  }


function getTimeNow($incsec=false)
  {
    $tm=time();
    $hr=strftime("%H",$tm);
    $mn=strftime("%M",$tm);
    if ($incsec)
      {
        $sc=strftime("%S",$tm);
        $timeNow  = sprintf("%02d:%02d:%02d",$hr,$mn,$sc);
      }
    else
       $timeNow   = sprintf("%02d:%02d",$hr,$mn);
    return($timeNow);
  }

function getMonthEndDateAsArray($dateArray=FALSE)
  {
    if (is_array($dateArray))
      $dt = $dateArray;
    else
      $dt = getDateTodayAsArray();
    $calData=calendar($dt);
    $dim=$calData["DaysInMonth"];
    $dt["day"]=$dim;
    return($dt);
  }

function getPreviousMonthEndDateAsArray()
  {
    $today = getDateTodayAsArray();
    $prevMonth=calStepMonths(-1,$today);
    return(getMonthEndDateAsArray($prevMonth));
  }


function getDateTime($incsec=false)
  {
    $tm=time();
    $mm=strftime("%m",$tm);
    $dd=strftime("%d",$tm);
    $yy=strftime("%Y",$tm);
    $hr=strftime("%H",$tm);
    $mn=strftime("%M",$tm);
    if ($incsec) {
       $sc=strftime("%S",$tm);
       $dateTime = sprintf("%04d-%02d-%02d %02d:%02d:%02d",$yy,$mm,$dd,$hr,$mn,$sc);
    } else {
       $dateTime = sprintf("%04d-%02d-%02d %02d:%02d",$yy,$mm,$dd,$hr,$mn);
	}
    return($dateTime);
  }

function formatDate($tm)
  {
    if ($tm==0) return "null";
    $mm=strftime("%m",$tm);
    $dd=strftime("%d",$tm);
    $yy=strftime("%Y",$tm);
    $date = sprintf("%04d-%02d-%02d",$yy,$mm,$dd);
    return($date);
  }


function formatTime($tm, $incsec=false)
  {
    if ($tm==0) return "null";
    $hr=strftime("%H",$tm);
    $mn=strftime("%M",$tm);
    if ($incsec)
      {
        $sc=strftime("%S",$tm);
        $time = sprintf("%02d:%02d:%02d",$hr,$mn,$sc);
      }
    else
        $time = sprintf("%02d:%02d",$hr,$mn);
    return($time);
  }


function formatDateTime($tm, $incsec=false)
  {
    if ($tm==0) return "null";
    $mm=strftime("%m",$tm);
    $dd=strftime("%d",$tm);
    $yy=strftime("%Y",$tm);
    $hr=strftime("%H",$tm);
    $mn=strftime("%M",$tm);

    if ($incsec)
      {
        $sc=strftime("%S",$tm);
        $dateTime = sprintf("%04d-%02d-%02d %02d:%02d:%02d",$yy,$mm,$dd,$hr,$mn,$sc);
      }
    else
        $dateTime = sprintf("%04d-%02d-%02d %02d:%02d",$yy,$mm,$dd,$hr,$mn);
    return($dateTime);
  }


function getmicroseconds($asFloat=FALSE)
  {
    $t=gettimeofday();
    $t2=substr((sprintf("%06d", $t['usec'])."000000"),0,6);
    $ms = (double) ($t['sec'].$t2);
    if ($asFloat)
       return $ms;
    return sprintf("%1.0f",$ms);
  }

function micFormatDate($ms)
  {
    $tm=(int) ((double) $ms / (double) 1000000);
    return(formatDate($tm));
  }

function micFormatTime($ms, $incsec=false)
  {
    $tm = ((double) $ms / (double) 1000000);
    return(formatTime(toInteger($tm), $incsec));
  }

function micFormatDateTime($ms, $incsec=false)
  {
    $tm = ((double) $ms / (double) 1000000);
    return(formatDateTime(toInteger($tm), $incsec));
  }



function getmilliseconds($asFloat=FALSE)
  {
    $t=gettimeofday();
    $t2=substr((sprintf("%06d", $t['usec'])."000"),0,3);
    $ms = (double) ($t['sec'].$t2);
    if ($asFloat)
       return $ms;
    return sprintf("%1.0f",$ms);
  }


function toInteger($bint)
  {
    return sprintf("%1.0f",(double) $bint);
  }


function getDateTodayAsArray()
  {
    $tm=time();
    $mm=strftime("%m",$tm);
    $dd=strftime("%d",$tm);
    $yy=strftime("%Y",$tm);
    $dateArray["month"] = (int) $mm;
    $dateArray["day"]   = (int) $dd;
    $dateArray["year"]  = (int) $yy;
    return $dateArray;
  }


function getTimeNowAsArray()
  {
    $tm=time();
    $hr=strftime("%H",$tm);
    $mn=strftime("%M",$tm);
    $sc=strftime("%S",$tm);
    $timeArray["hour"]   = (int) $hr;
    $timeArray["minute"] = (int) $mn;
    $timeArray["second"] = (int) $sc;
    return $timeArray;
  }


function getDateTimeAsArray()
  {
     return(getDateTimeFromSecondsAsArray(time()));
  }


function getDateTimeFromSecondsAsArray($tm)
  {
    $mm=strftime("%m",$tm);
    $dd=strftime("%d",$tm);
    $yy=strftime("%Y",$tm);
    $hr=strftime("%H",$tm);
    $mn=strftime("%M",$tm);
    $sc=strftime("%S",$tm);
    $dtmArray["month"]  = (int) $mm;
    $dtmArray["day"]    = (int) $dd;
    $dtmArray["year"]   = (int) $yy;
    $dtmArray["hour"]   = (int) $hr;
    $dtmArray["minute"] = (int) $mn;
    $dtmArray["second"] = (int) $sc;
    return $dtmArray;
  }


function dateArrayToString($dateArray)
  {
    $dt  = sprintf("%04d-%02d-%02d",
           $dateArray["year"], $dateArray["month"], $dateArray["day"]);
    return $dt;
  }


function timeArrayToString($timeArray)
  {
    $dt = sprintf("%02d:%02d:%02d",
           $timeArray["hour"], $timeArray["minute"], $timeArray["second"]);
    return $dt;
  }


function dateTimeArrayToString($dtmArray)
  {
    $dtm = sprintf("%04d-%02d-%02d %02d:%02d:%02d",
           $dtmArray["year"], $dtmArray["month"], $dtmArray["day"],
           $dtmArray["hour"], $dtmArray["minute"], $dtmArray["second"]);
    return $dtm;
  }


function getDateTimeAsSeconds()
  {
    return(time());
  }


function dateToArray($dateAsString)
  {
    list($yy,$mm,$dd) = split("-", $dateAsString);
    $dateArray["month"]  = (int)$mm;
    $dateArray["day"]    = (int)$dd;
    $dateArray["year"]   = (int)$yy;
    return $dateArray;
  }


function timeToArray($timeAsString)
  {
    list($hr,$mn,$sc) = split(":", $timeAsString);
    $timeArray["hour"]    = (int)$hr;
    $timeArray["minute"]  = (int)$mn;
    $timeArray["second"]  = (int)$sc;
    return $timeArray;
  }


function dateTimeToArray($dtmAsString)
  {
    list($dt, $tm) = split(" ", $dtmAsString);
    $da = dateToArray($dt);
    $ta = timeToArray($tm);
    $dtmArray["month"]  = $da["month"];
    $dtmArray["day"]    = $da["day"];
    $dtmArray["year"]   = $da["year"];
    $dtmArray["hour"]   = $ta["hour"];
    $dtmArray["minute"] = $ta["minute"];
    $dtmArray["second"] = $ta["second"];
    return $dtmArray;
  }


//--------------------------------------------------------------------
// CALENDAR  Fuctions
//--------------------------------------------------------------------

function getMonthName($mm)
 {
   global $month_names;
   if ($mm >= 1 && ($mm <= 12))
     {
       return $month_names[$mm-1];
     }
   return "";
 }


function getDayName($dd)
 {
   global $day_names;
   if ($dd >= 0 && ($dd <= 6))
     {
       return $day_names[$dd];
     }
   return "";
 }


function getAbbrevMonthName($mm)
 {
   global $month_names;
   if ($mm >= 1 && ($mm <= 12))
     {
       return substr($month_names[$mm-1],0,3);
     }
   return "";
 }


function getAbbrevDayName($dd)
 {
   global $day_names;
   if ($dd >= 0 && ($dd <= 6))
     {
       return substr($day_names[$dd],0,3);
     }
   return "";
 }


//----------------------------------------------
// Subroutine: isLeapYear
//
// answer TRUE/FALSE whether supplied year is a
// leap year.
//
// Args   :  int  Year
// Returns:  TRUE id leapyear  otherwise FALSE
//----------------------------------------------
function isLeapYear($yy)
 {
   if (($yy % 400) == 0)
    { return TRUE; }   //-- IS a leapyear
   if (($yy % 100) == 0)
    { return FALSE; }  //-- IS NOT a leapyear
   if (($yy % 4) == 0)
    { return TRUE; }   //-- IS a leapyear
   return FALSE;       //-- Otherwise IS NOT a leapyear
 }
?>
