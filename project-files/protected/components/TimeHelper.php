<?php
class TimeHelper extends CComponent {

    public function getReadableTimeSince($timestamp)
    {
        //@TODO: handle plurals... sigh
        $secondsSince = time() - $timestamp;
        $minutesSince = intval($secondsSince/60); 
        $hoursSince = intval($minutesSince/60);
        $daysSince = intval($hoursSince/24);
        $monthsSince = intval($daysSince/30);
                
        if($secondsSince <= 60)
        {
            return "less than a minute ago...";
        }
        elseif($minutesSince <=60)
        {
            if($minutesSince == 1)
            {
                return "about a minute ago...";
            }
            return $minutesSince." minutes ago...";
        }
        elseif($hoursSince <= 24)
        {
            if($hoursSince == 1)
            {
                return "an hour ago...";
            }
            return $hoursSince." hours ago...";
        }
        elseif($daysSince <= 30)
        {
            if($daysSince == 1)
            {
                return "a day ago...";
            }
            return "about ".$daysSince." days ago...";
        }
        elseif($monthsSince <= 11)
        {
            if($monthsSince == 1)
            {
                return "about a month ago...";
            }
            return "about ".$monthsSince." months ago...";
        }
        return " a long time ago...";
    }
    
    /**
     * Calculates the number of seconds in a given time interval.
     * @param string $interval Human readable interval, eg. 'day', 'year'.
     * @param int $periods The number of intervals to compute.
     * @return type 
     */
    public function getSeconds($intervalType, $periods = 1) {
        $seconds = 0;
        
        if ($intervalType == 'year')
            $seconds = 60*60*24*365;
        elseif ($intervalType == 'month') // defined as being four weeks long
            $seconds = 60*60*24*7*4;
        elseif ($intervalType == 'week')
            $seconds = 60*60*24*7;
        if ($intervalType == 'day')
            $seconds = 60*60*24;
        elseif ($intervalType == 'hour')
            $seconds = 60*60;
        elseif ($intervalType == 'minute')
            $seconds = 60;
        elseif ($intervalType == 'decaminute')
            $seconds = 10;
        
        return $periods * $seconds;
    }
    
}

?>
