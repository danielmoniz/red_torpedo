<?php

/**
 * This class is used for gathering data of various kinds about users and their 
 * usage of the site.
 */
class MetricsGathering {
    
    /**
     * Return usernames for all users currently online (ie. all users with 
     * unexpired sessions).
     * @return Array A list of usernames.
     */
    public function getUsernamesOnline() {
        $sql = "SELECT SQL_CALC_FOUND_ROWS 
                user_id FROM yii_session
            WHERE expire >= UNIX_TIMESTAMP()
            AND user_id IS NOT NULL";
        $userIds = Utilities::query($sql, array(), 'column');
        
        $usernames = User::getUserAttributeFromUserIds($userIds);
        return $usernames;
    }
    
    /**
     * Gets data from the session DB table.
     * @return Array A list of rows of data from the session DB table.
     */
    public function getSessionData() {
        $sql = "SELECT * FROM yii_session
            WHERE expire >= UNIX_TIMESTAMP()
            AND data IS NOT NULL";
        return Utilities::query($sql, array(), 'all');
    }
    
    /**
     * Get records of all recent signups, and the total number.
     * @param string $interval Find signups within this interval.
     */
    public function getRecentSignups($interval = 'day') {
        $sql = "SELECT SQL_CALC_FOUND_ROWS 
                username, registration_timestamp
            FROM user_main 
            WHERE registration_timestamp >= UNIX_TIMESTAMP() - :interval
            ORDER BY registration_timestamp DESC";
        $paramArray = array(':interval'=>TimeHelper::getSeconds($interval));
        $recentSignups = Utilities::query($sql, $paramArray, 'all');
        
        $recentSignupValues = array();
        foreach ($recentSignups as $key=>$signupInfo) {
            $value = $signupInfo['username'] . ": "
                    . date("g:ia", $signupInfo['registration_timestamp']);
            $recentSignupValues[$key] = $value;
        }
        
        $numRecentSignups = Utilities::getTotalEntries();
        
        return array($recentSignupValues, $numRecentSignups);
    }
    
    /**
     * Get information about the least active users on the site.
     * @return Array A list of strings containing info about users.
     */
    public function getLeastActiveUserInfo() {
        $sql = "SELECT username, last_login_timestamp FROM user_main
            ORDER BY last_login_timestamp ASC
            LIMIT 5";
        $userInfo = Utilities::query($sql, array(), 'all');
        
        // build info to return
        $userStrings = array();
        foreach ($userInfo as $user) {
            $lastLogin = $user['last_login_timestamp'];
            $userStrings[] = $user['username'] . ": " 
                    . date("M. jS, g:ia, Y", $lastLogin);
        }
        
        return $userStrings;
    }
    
}


?>
