<?php
include_once __INCLUDE_CLASS_PATH."/class.Main.php";

class Microtime extends Main{

    var $start_time;
    var $prev_time;
    var $results = array();

    /**
     * Constructor
     *
     * @access public
     */

	function  & getInstance(){
        $ao = new SingeltonStorage;
		$m = & $ao->get("instance", "Microtime", null);

		if ($m === null){
		    $m = new Microtime('12345678');
		}
		return $m;
	}

     function Microtime1($secret_key=''){
        if ($secret_key != 12345678)
            return $this->CriticalError("You try to use private constructor pls use [getInstance] function to get object Microtime<br>
            							<b>Example: \$Micro = & Microtime::getInstance();</b>");
        $this->start_time = $this->prev_time = $this->getmicrotime();
     }

    /**
     * Add timer info to storage
     *
     * @param $label label for time
     * @access public
     */

	function dumpTime($label) {
	    $this->results[] = "$label -> [".($this->getmicrotime()-$this->start_time)."]-[".round($this->getmicrotime()-$this->prev_time,5)."]";
        $this->prev_time = $this->getmicrotime();
	}

    /**
     * Get time interval
     *
     * @access public
     */

    function getmicrotime(){
        list($usec, $sec) = explode(" ",microtime());
        return ((float)$usec + (float)$sec);
    }
}
?>