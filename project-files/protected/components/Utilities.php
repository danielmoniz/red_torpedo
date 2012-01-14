<?php

/**
 * This component is used for minor utility functions that we might need.
 */
class Utilities extends CComponent
{
    /**
     * Returns an array of random integers of a specified min, max, and size.
     * NOTE: This might generate numbers for which there are no database
     * entries in the relevant DB table (if using this to help query the DB).
     * This can happen in the case of an auto incrementing primary key that has
     * deleted entries.
     * @param int $min The smallest desired integer.
     * @param int $max The largest desired integer.
     * @param int $size The size of the array to be returned (the # of #'s).
     * @return Array An array of integers.
     */
    public function getRandomArray($min = 1, $max = 5, $size = 5)
    {
        $randomArray = array();
        while (count($randomArray) < $size ) {
            $randomNumber = mt_rand($min, $max);
            if ( !in_array($randomNumber,$randomArray) ) {
                $randomArray[] = $randomNumber;
            }
        }
        
        return $randomArray;
    }

    public function query($sql, $paramArray = array(), $queryType = 'execute')
    {
        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $command->bindValues($paramArray);

        $queryType = strtolower($queryType);
        if ($queryType == 'column')
            return $command->queryColumn();
        elseif ($queryType == 'row')
            return $command->queryRow();
        elseif ($queryType == 'scalar')
            return $command->queryScalar();
        elseif ($queryType == 'execute')
            return $command->execute();
        elseif ($queryType == 'all')
            return $command->queryAll();
        else
            return 'Unknown query type!';
    }
    
    /**
     * Return the total number of non-limited, non-offset rows from the 
     * latest executed query.
     * @return int A count of rows.
     */
    public function getTotalEntries()
    {
        $sql = "SELECT FOUND_ROWS()";
        
        return Utilities::query($sql, array(), 'scalar');
    }

    public function filterList($list, $filterText)
    {
//        var_dump($list); exit;
        $stringLength = strlen($filterText);
        foreach ($list as $key=>$element) {
            if ($filterText != substr($element, 0, $stringLength))
                unset($list[$key]);
        }
        return $list; exit;
    }
    
    /**
     * A simple function to get a success return given a success variable, 
     * possible error text, and possible data to return.
     * @param boolean $success Represents the success of the task.
     * @param string $errorText Error text to return, if any.
     * @param mixed $data The data that succeeded or failed.
     * @return array An array describing task success or failure.
     */
    public function getSuccessReturn($success, $errorText = '', $data = '') {
        $returnArray = array("success"=>$success);
        // add error text if failure
        if ($success == false && !empty($errorText))
            $returnArray['error'] = $errorText;
        // add return data if success
//        if ($success == true && !empty($data))
        if ($success == true)
            $returnArray['returnData'] = $data;
        return $returnArray;
    }
    
    /**
     * Simply a JSON wrapper for getSuccessReturn
     * For params, see self::getSuccessReturn().
     * @return JSON A JSON encoded array describing task success or failure.
     */
    public function getFullSuccessReturn($success, $errorText = '', $data = '') {
        $returnArray = self::getSuccessReturn($success, $errorText, $data);
        return json_encode($returnArray);
    }
    
    public function getUrlStart() {
        if (isset($_SERVER['HTTPS']))
            return 'https://';
        else
            return 'http://';
    }
    
    /**
     * Gets an HTML/JavaScript safe version of the inputText. Prevents 
     * frint-end injection, eg. javascript injection.
     * @param string $inputHtml The HTML/JavaScript to be converted.
     * @return string The converted (safe) output string.
     */
    public function cleanHtml($inputHtml) {
        return htmlentities($inputHtml);
    }
}

?>
