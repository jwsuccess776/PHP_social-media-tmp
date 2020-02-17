<?php
include_once __INCLUDE_CLASS_PATH."/class.Main.php";

class Geography extends Main {

    function getCountryByID($id) {
        $db =& db::getInstance();
         $MainLink=new Main();
        $Id= $MainLink->_PrepareData($id);
        
        
        $result = $db->get_row("SELECT * FROM geo_country WHERE gcn_countryid > 0 "
                . "AND gcn_name <> 'Unspecified' AND gcn_countryid = '".$Id."'");
        return $result;
    }

    function getStateByID($id) {
        $db =& db::getInstance();
         $MainLink=new Main();
        $Id= $MainLink->_PrepareData($id);
        $result = $db->get_row("SELECT * FROM geo_state WHERE gst_stateid > 0 "
                . "AND gst_name <> 'Unspecified' AND gst_stateid = '".$Id."'");
        return $result;
    }

    function getCityByID($id) {
        $db =& db::getInstance();
         $MainLink=new Main();
        $Id= $MainLink->_PrepareData($id);
        $result = $db->get_row("SELECT * FROM geo_city WHERE gct_cityid > 0 "
                . "AND gct_name <> 'Unspecified' AND gct_cityid = '".$Id."'");
        return $result;
    }

    public static function getCountriesList() {
        $db =& db::getInstance();
        $result = $db->get_results("SELECT * FROM geo_country
                                     WHERE gcn_countryid > 0
                                       AND gcn_name <> 'Unspecified'
                                       AND gcn_status = 1
                                  ORDER BY gcn_order, gcn_name");
        return $result;
    }

    public static function getStatesList($countryID) {
        $db =& db::getInstance();
        
        $MainLink=new Main();
        $conId= $MainLink->_PrepareData($countryID);
        
        $result = $db->get_results("SELECT * FROM geo_state
                                     WHERE gst_stateid > 0
                                       AND gst_name <> 'Unspecified'
                                       AND gst_countryid = '".$conId."'
                                  ORDER BY gst_name");
        return $result;
    }

    public static function getCitiesList($countryID,$stateID) {
        $db =& db::getInstance();
        $MainLink=new Main();
        $conId= $MainLink->_PrepareData($countryID);
        $stateId=$MainLink->_PrepareData($stateID);
        $result = $db->get_results("SELECT * FROM geo_city
                                     WHERE gct_cityid > 0
                                       AND gct_name <> 'Unspecified'
                                       AND gct_countryid = '".$conId."'
                                       AND gct_stateid = '".$stateId."'
                                  ORDER BY gct_name");
        return $result;
    }

    function isStateInCountry($stateID,$countryID) {
        $db =& db::getInstance();
          $MainLink=new Main();
        $conId= $MainLink->_PrepareData($countryID);
        $stateId=$MainLink->_PrepareData($stateID);
        $result = $db->get_row("SELECT * FROM geo_state
                                     WHERE gst_stateid = '".$stateId."'
                                       AND gst_countryid = '".$conId."'");
        if ($result) return true;
        return false;
    }

}
?>