<?php
include_once __INCLUDE_CLASS_PATH."/class.Main.php";

/**
 * Implements spam words dictionary and spam checking functionality
 *
 */
class SpamChecker extends Main {
    var $SpamWord_ID;
    var $SpamWord;

    /**
     * Initialisation of object by ID
     *
     * @access public
     * @param int $data
     * @return void
     */
    function InitById ( $eID ){
        $db = & db::getInstance ();
        $eID = $this -> _PrepareData ( $eID );
        $row = $db -> get_row("
                            SELECT 	*
                            FROM spamwords
                            WHERE SpamWord_ID='$eID'");
        if (!$row) {
            $result = $this->CriticalError("Can't find spam-word for ID [$data]");
        } else {
            foreach ($row as $key => $data) {
                $this->$key = $data;
            }
            $result = true;
        }
        return $result;
    }

    /**
     * Prepare data before save it
     *
     * @param numeric $data
     *
     * @access private
     * @return void
     */
    function _PrepareData($data){
        $db = & db::getInstance();
        return $db->escape($data);
    }

    /**
     * Initialisation of object ofObject
     *
     * @access public
     * @param object $data
     * @return void
     */
    function InitByObject ( $data ) {
        foreach ($data as $key => $value) {
            $this->{$key} = $value;
        }
        return true;
    }

    /**
     * Initialisation of object for saving
     *
     * @access public
     * @param array $data
     * @return void
     */
    function InitForSave ( $data ) {
        $result = $this->_CheckValue($data);
        if ($result === null) {
            $result = $this->Error( 'Incorrect data' );
        } else {
            $this -> InitByObject ( $data );
            $result = true;
        }
        return $result;
    }

    /**
     * Check value
     *
     * @access private
     * @return void
     */
    function _CheckValue($value){
        if ( (! isset ($value->SpamWord)) || ($value->SpamWord == '') ) {
            $result = $this->Error ( 'No spamword' );
        } else {
            $result = true;
        }
        return $result;
    }

    /**
     * returns amount of spamwords in database
     *
     * @access public
     * @return int
     */
    function getObjectsCount () {
        $db = & db::getInstance();
        $result = $db->get_var('SELECT count(*) AS cnt FROM spamwords');
        return $result;
    }

    /**
     * Return list of options
     *
     * @param object $pager
     * @return array
     * @access public
     */
    function getList (&$pager) {
        $db = & db::getInstance();
        if ( $pager !== null )  {
            $count = $this -> getObjectsCount ();
            $limit = $pager -> GetLimit($count);
        } else {
            $limit = '';
        }
        $sqlStr = 'SELECT * FROM spamwords '.$limit;
        $result = $db->get_results( $sqlStr );
        return $result;
    }

    /**
     * performs check of provided text for spam words
     * returns amount of spam-words found
     *
     * @access public
     * @param string
     * @return array
     */
    function checkText ( $text , $caseSensitive = false ) {
        $result = array();
        $db = & db::getInstance();
        $sqlStr = 'SELECT * FROM spamwords';
        $spamWordList = $db->get_results( $sqlStr );
        if ( $caseSensitive ) {
            $checkFunc = 'strstr';
        } else {
            $checkFunc = 'stristr';
        }
        $count = 0;
        foreach( $spamWordList as $spamRow ) {
            if ($spamRow->SpamWord != '' && $checkFunc ( $text , $spamRow->SpamWord ) ) {
                $result[] = $spamRow->SpamWord;
            }
        }
        return $result;
    }

    /**
     * removes spamword from database
     *
     * @access public
     * @param int
     * @return void
     */
    function Delete ( $SpamWord_ID ) {
        $db = & db::getInstance();
        $SpamWord_ID = $this -> _PrepareData ( $SpamWord_ID );
        $sqlStr = 'DELETE FROM spamwords WHERE SpamWord_ID='.$SpamWord_ID;
        $db -> query( $sqlStr );
        return true;
    }

    /**
     * saves current item into database
     *
     * @access public
     * @return bool
     */
    function Save () {
        $db = & db::getInstance();
        $spamWord = $this -> _PrepareData ( $this -> SpamWord );
        if ( $this -> SpamWord_ID ) {
            $sId = $this -> _PrepareData ( $this -> SpamWord_ID );
            $sqlStr = 'UPDATE spamwords SET SpamWord="'.$spamWord.'" WHERE SpamWord_ID='.$sId;
        } else {
            $sqlStr = 'INSERT INTO spamwords SET SpamWord="'.$spamWord.'"';
        }
        $db -> query ( $sqlStr );
        return true;
    }
}


?>