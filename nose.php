<?php
/**
 * Class for retrieving data from The PureChoice Nose Monitor
 * Retrieves all data from monitor and converts to json data.
 * Also checks the values for set thresholds and alerts via email
 * when a threshold is reached
 * 
 * @author f13nd
 *
 */
Class NoseData {
    private $url = "http://ip-of-nose-monitor/data.txt";
    
    public function __construct() {
        return $this->getData();
    }
    /**
     * Makes request to the nose monitor for current readings
     * explodes the returned data into an array for processing
     * 
     * @return JSON Array
     */
    private function getData() {
        $res = file_get_contents($this->url);
        /** Sometimes the nose request returns an empty string.
         *  If we get an empty string, lets try again until we get data.
         */
        if($res == ""){
            while($res == ""){
                // Looks like we got nothing back so lets try till we do
                $res = file_get_contents($this->url);
                if ($res == ""){
                    //still nothing
                    continue;
                }
                else {
                    // Now we have data. lets move on.
                    // explode the results on & into an array
                    $res = explode("&", $res);
                }
            }
        } 
        else {
            // Looks like we initially did data so lets process it.
            // explode the results on & into an array
            $res = explode("&", $res);
        }
        // Declare array for storing what we find
        $data = array();
        
        // looop through results and assign to the data array
        foreach($res as $key=>$val){
            /** Our first array was separated based on '&'.
             *  each value is now in the form name=value.
             *  We need to explode those by '=' 
             *  and assign them to the data array
            **/
            $d = explode("=", $val);
            $data[] = array($d[0] => $d[1]);
        }
        // Set content-type to application/json
        header('Content-Type: application/json');
        exit(json_encode($this->processData($data)));
    }
    /**
     * Process data from Nose Monitor
     * Calls getFields to Only grab the data we care about such as
     * temperature, humidity, and CO2 levels and drop the rest
     * Calls checkThresholds to validate there is no need for alarm
     * 
     * @param array $data
     * @return array $res
     */
    private function processData(Array $data) {
        // declare an array for our results
        $res = array();
        // Loop through data array
        foreach($data as $idx=>$val) {
            foreach($val as $i=>$v) {
                // Call getFields() to get only the data we want
                $checkField = $this->getField($i);
                // If its a field we are collecting, getField() will return true
                if($checkField['result'] === true) {
                    // Call checkThresholds() to test if we are OK
                    $this->checkThresholds($checkField['name'], $v);
                    // Assign the data to the $res array
                    $res[] = array($checkField['name'] => $v);
                }
                else {
                    // checkField was false... Continue on.
                    continue;
                }
            }
        }
        // Return what we found
        return $res;
    }
    
    /**
     * Check to see if the $filed passed is one we are collecting.
     * returns array('result' => true) when desired field is captured
     * and modifys the name to a more desirable human friendly one.
     * 
     * @param String $field
     * @return Array [name, result]
     */
    private function getField($field){
        switch($field) {
            case 'senstype1':
                $name = "Tempurature Level";
                return array('name' => $name, 'result' => true);
            break;
            case 'senstype2':
                $name = "Humidity Level";
                return array('name' => $name, 'result' => true);
            break;
            case 'senstype3':
                $name = "CO2 Level";
                return array('name' => $name, 'result' => true);
            break;
            default:
                return false;
            break;
        }
    }
    
    /**
     * Checks the thresholds for each value and sends an alert via email
     * when thresholds are reached.
     * 
     * @param String $name
     * @param Interger $value
     * @return sendAlert()
     */
    private function checkThresholds($name, $value){
        switch($name) {
            case 'Tempurature Level':
                // if temperature is above 90 degrees or below 70 send an alert
                if((intval($value) > 90) || (intval($value) < 70)){
                    $message = "Wanring The Current $name is: $value Deg F";
                    return $this->sendAlert($name, $message);
                }
            break;
            case 'Humidity Level':
                // if humidity is above 90 degrees or below 70 send an alert
                if((intval($value) > 90) || (intval($value) < 50)) {
                    $message = "Wanring The Current $name is: $value %";
                    return $this->sendAlert($name, $message);
                }
            break;
            case 'CO2 Level':
                // if CO2 level is above 1000ppm or below 550ppm send an alert
                if((intval($value) > 1000) || (intval($value) < 550)) {
                    $message = "Wanring The Current $name is: $value PPM";
                    return $this->sendAlert($name, $message);
                }
            break;
            default:
                return true;
        }
    }
    
    /**
     * Send email alert to static address $to
     * 
     * @param String $type
     * @param String $message
     * @return mail()
     */
    private function sendAlert($type, $message){
        $to      = 'some@email.com';
        $subject = "Alert: $type";
        return mail($to, $subject, $message);
    }
}

// Initialize NoseData()
new NoseData();
