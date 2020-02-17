<?
require_once(__INCLUDE_CLASS_PATH."/class.Main.php");

class ratedItem extends Main{
    var $rating_name = '';
    var $rate_url = '';


    function ratedItem($name, $id) {
        if (!$name) return $this->criticalError("Empty rating name");
        if (!$id) return $this->criticalError("Empty rated item id");
        $this->rating_name = $name;
        $this->_id = $id;
    }

    /**
     * Return vote result
     *
     * @return object
     * @access public
     */

    function getRating() {
        include_once __INCLUDE_CLASS_PATH."/class.RatingManager.php";
        $rating = RatingManager::getResult($this->rating_name, $this->_id);
        return $rating;
    }

    /**
     * Check if this video was voted by member
     *
     * @param int $userid
     * @return object
     * @access public
     */

    function voted($userid) {
        include_once __INCLUDE_CLASS_PATH."/class.RatingManager.php";
        if (!isset($userid)) return $this->criticalError("Empty userid");
        $rating = RatingManager::get($this->rating_name, $userid, $this->_id);
        return ($rating->value > 0) ? true : false;
    }

    /**
     * Get rating scale
     *
     * @return int
     * @access public
     */

    function getScale() {
        include_once __INCLUDE_CLASS_PATH."/class.RatingManager.php";
        return RatingManager::getScale($this->rating_name);
    }

    /**
     * Get rating scale
     *
     * @return int
     * @access public
     */

    function vote($userid, $value) {
        include_once __INCLUDE_CLASS_PATH."/class.RatingManager.php";
        $rating = RatingManager::get($this->rating_name, $userid, $this->_id);
        $rating->value = $value;
        $res = $rating->save();
        if ($res === NULL) {
            return $this->error($rating->error);
        }
        return true;
    }

    /**
     * Clear rating for entity
     *
     * @access public
     */
    function Delete(){
        include_once __INCLUDE_CLASS_PATH."/class.RatingManager.php";
        $rating = RatingManager::delete($this->rating_name, $this->_id);
    }


}

?>