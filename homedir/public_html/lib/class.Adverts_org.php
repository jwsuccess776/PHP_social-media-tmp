<?

include_once __INCLUDE_CLASS_PATH."/class.Main.php";
include_once CONST_INCLUDE_ROOT."/functions.php";
class Adverts extends Main {
    var $SexImage = array();
    /**
     * Constructor
     *
     * @access public
     */
    function Adverts($id = 0){
        $skin =& Skin::GetInstance();
        $db =& db::getInstance();

        $this->time = $db->get_var("SELECT unix_timestamp(now())");

        $this->SexImage = array(
                        "M" => $skin->ImagePath.'genericm.jpg',
                        "F" => $skin->ImagePath.'genericf.jpg',
                        "C" => $skin->ImagePath.'genericc.jpg',
                    );
        if (isInt($id)) $this->InitById($id);
        
    }
    /**
     * Initialisation of object
     *
     * @param int $data
     * @access public
     */
    function InitByObject($data){
        $option_manager =& OptionManager::GetInstance();
        foreach ((array)$data as $key => $value) $this->{$key} = $value;
        include_once __INCLUDE_CLASS_PATH."/class.Picture.php";
        $picture = new Picture();
        $this->Image = $picture->GetDefault($this->adv_userid);
        if ($this->Image) {
            $this->image_info['small'] = $this->Image->getInfo('small');
            $this->image_info['medium'] = $this->Image->getInfo('medium');
        } else {
            $this->image_info['small'] = (object)array("Path" => $this->SexImage[$this->adv_sex],
                                                        "w" => CONST_THUMBS_SMALL_W,
                                                        "h" => CONST_THUMBS_SMALL_H
                                                        );
            $this->image_info['medium'] = (object)array("Path" => $this->SexImage[$this->adv_sex],
                                                        "w" => CONST_THUMBS_MEDIUM_W,
                                                        "h" => CONST_THUMBS_MEDIUM_H
                                                        );
        }


        $this->adv_comment=stripslashes($this->adv_comment);
        $this->adv_comment_full=nl2br($this->adv_comment);
        $this->adv_comment=substr($this->adv_comment,0,120);
        $this->adv_comment=str_replace("\n"," ",$this->adv_comment);
        $this->adv_comment=str_replace("\t"," ",$this->adv_comment);
        /**
         * geo data prepearing
         */
        $GEOGRAPHY_JAVASCRIPT = $option_manager->GetValue('geography_javascript');
        $this->adv_location = (!$GEOGRAPHY_JAVASCRIPT) ? (($this->adv_location) ? $this->adv_location : GENERAL_NOT_STATE) : $this->gct_name;
        $copy = $this;
        $copy->gct_cityid = 0;
        $this->adv_region = arrange_location($copy);
        $l_arr = array();
        $l_arr[] = ($GEOGRAPHY_JAVASCRIPT) ? $this->gct_name : $this->adv_location;
        if ($this->gst_name) $l_arr[] = $this->gst_name;
        $l_arr[] = $this->gcn_name;
        $this->full_address = join(', ',$l_arr);
        /**
         * Online/offline status
         */
        if (isset($this->session_active) && $this->session_active >=  $this->time - ONLINE_TIMEOUT_PERIOD*60) {
            $this->online="<span class='online'>".PRGSEARCH_ONLINE."</span>";
            $this->isOnline = true;
        } else {
            $this->online="<span class='offline'>".PRGSEARCH_OFFLINE."</span>";
            $this->isOnline = false;
        }
        /**
         * Standart/premium status
         */

        if ($this->adv_expiredate >= date("Y-m-d")) {
                $this->statustext="<span class='premium'>".STATUS_P."</span>";
        } else {
                $this->statustext="<span class='standard'>".STATUS_S."</span>";
        }
        $this->adv_title=stripslashes($this->adv_title);
        $this->lastvisit = date($option_manager->GetValue('format_date_short'),strtotime($this->mem_lastvisit));
        return true;
    }
    /**
     * Initialisation of object
     *
     * @param int $id
     * @access public
     */
    function InitById($id){
        $db =& db::getInstance();
        $id = $this->_PrepareData($id);
        $query = "
            SELECT
                *,
                (YEAR(CURDATE())-YEAR(adv_dob)) - (RIGHT(CURDATE(),5) < RIGHT(adv_dob,5)) AS age, mem_lastvisit,unix_timestamp(mem_timeout) AS session_active
            FROM adverts
            LEFT JOIN members ON (adv_userid=mem_userid)
            LEFT JOIN geo_country ON (adv_countryid = gcn_countryid)
            LEFT JOIN geo_city ON (adv_cityid = gct_cityid)
            LEFT JOIN geo_state ON (adv_stateid = gst_stateid)
            WHERE adv_userid = '$id'";
        $row = $db->get_row($query);
        $this->InitByObject($row);
        return true;
    }
    /**
     * Get images for profile
     *
     * @param mixed $type
     *
     * @access public
     */
    function SetImage($type){
        $this->adv_picture = $this->image_info[$type];
        return true;
    }
}
?>