<?
require_once(__INCLUDE_CLASS_PATH."/class.Main.php");
require_once(__INCLUDE_CLASS_PATH."/class.SingeltonStorage.php");

class ratingManager extends Main{
       var $ratings = array (
                    "video" => array("table" =>"rating_video", "scale" => 5));
    /**
     * Return configuration array
     *
     * @return array
     * @access public
     */

    function getData(){
        return array (
                    "video" => array("table" =>"rating_video", "scale" => 5),
                );
    }


    /**
     * Return rating object
     *
     * @param string $name
     * @param int $user
     * @param mixed $id
     * @return object
     * @access public
     */
    function Get($type,  $user, $id = 0){
        $db = & db::getInstance();
        $this->ratings = ratingManager::getData();
        switch ($type) {
            case 'video'      : $result = new Rating($this->ratings[$type]['table'], $this->ratings[$type]['scale']);break;
            default:
                    return $this->CriticalError("Unknown rating [$type]");
        }
        if (!$user) return $this->CriticalError("Empty userid");
        $result->Init($id, $user);
        return $result;
    }

    /**
     * Return rating average
     *
     * @param int $id
     * @return int
     * @access public
     */
    function getResult($type, $id){
        $this->ratings = ratingManager::getData();
        $db = & db::getInstance();
        if (!$id) return $this->CriticalError("Empty id");
        $row =  $db->get_row("SELECT ifnull(avg(value), 0) AS rating ,
                                       ifnull(count(*), 0) AS voted
                              FROM ".$this->ratings[$type]['table']." 
                              WHERE id = '".$db->escape($id)."'");
        return (object)array_merge(array(
                            "scale"     => $this->ratings[$type]['scale'],
                            "rating"   => 0,
                            "voted"     => 0
                            ), (array) $row);
    }

    /**
     * Get rating scale
     *
     * @return int
     * @access public
     */

    function getScale($type) {
        $this->ratings = ratingManager::getData();
        return $this->ratings[$type]['scale'];
    }

    /**
     * Clear rating for entity
     *
     * @access public
     */
    function Delete($type, $ent_id){
        $db = & db::getInstance();
        $this->ratings = ratingManager::getData();
        $db->query("DELETE FROM ".$this->ratings[$type]['table']." WHERE id = '$ent_id'");
    }

}

class Rating extends Main{


    var $table = "";
    var $scale = 0;
    var $value;
    var $id;
    var $userid;

    /**
     * Init object 
     *
     * @access public
     */

    function Rating($table,$scale){
        $this->table = $table;
        $this->scale = $scale;
    }

    /**
     * Fill object
     *
     * @param string $id
     * @param string $user
     * @return object
     * @access public
     */

    function Init($id, $userid){
        $db = & db::getInstance();
        if ( is_array($id) ) return $this->initByRow((object)$id);
        if ( is_object($id) ) return $this->initByRow($id);

        $data = $db->get_row("SELECT * FROM {$this->table} WHERE id='$id' AND userid = '$userid'");

        if ($data) {
            return $this->initByRow($data);
        } else { 
            return $this->initByRow((object)array("id"=>$id,"userid"=>$userid));
        }
    }

    /**
     * Fill object
     *
     * @param mixed $id
     * @param string $user
     * @return object
     * @access public
     */

    function initByRow($row){
        foreach ($row as $name => $value){
            $this->{$name} = $value;
        }
        return $this;
    }


    /**
     * Save object data to DB
     *
     * @access public
     */

    function save(){
        $db = & db::getInstance();
        $res = $this->check();
        if ($res === null) {
            return $this->Error("Can't save rating");
        }
        $db->query("
            REPLACE {$this->table} SET
                id      = ".$db->escape($this->id).",
                userid  = ".$db->escape($this->userid).",
                value   = ".$db->escape($this->value)."
        ");
        
        return true;
    }

    /**
     * Check data 
     *
     * @return boolean
     * @access public
     */
    function check(){
        if ($this->value > $this->scale) return $this->error("Rating value [$this->value] is too big. Max value is [$this->scale]");
        if (!$this->id) return $this->criticalError("Empty id");
        if (!$this->userid) return $this->error("Empty userid");
        return true;
    }
}

?>