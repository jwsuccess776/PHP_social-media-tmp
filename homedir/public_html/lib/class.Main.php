<?php

class Main {
    var $error = array();
    /**
     * Interupt execution with trigger error
     *
     * @param string $error
     * @access private
     */

    function CriticalError($error){
        trigger_error($error, E_USER_ERROR);
        return null;
    }

    /**
     * Set data to pull of errors
     *
     * @param string $error
     * @return null
     * @access private
     */
    function Error($error){
        if (is_array($error)){
            $this->error = array_merge($error,$this->error);
        } else {
            array_unshift($this->error,$error);
        }
        return null;
    }

    /**
     * Prepare data before save it
     *
     * @param numeric $data
     *
     * @access private
     */
    function _PrepareData($data){
        $db = & db::getInstance();
        return $db->escape($data);
    }

    /**     * Extract data
     *
     * @param object $data
     *
     * @access private
     */

    function _extract($data){        foreach ($data as $key => $value)
            $this->{$key} = $value;
    }

}

?>