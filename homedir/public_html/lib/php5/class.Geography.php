<?php
include_once __INCLUDE_CLASS_PATH."/class.Main.php";

class Geography extends Main {

    static function getCountryByID($id) {
        $db =& db::getInstance();
        $result = $db->get_row("SELECT * FROM geo_country WHERE gcn_countryid > 0 AND gcn_name <> 'Unspecified' AND gcn_countryid = '".Main::_PrepareData($id)."'");
        return $result;
    }

    static function getStateByID($id) {
        $db =& db::getInstance();
        $result = $db->get_row("SELECT * FROM geo_state WHERE gst_stateid > 0 AND gst_name <> 'Unspecified' AND gst_stateid = '".Main::_PrepareData($id)."'");
        return $result;
    }

    static function getCityByID($id) {
        $db =& db::getInstance();
        $result = $db->get_row("SELECT * FROM geo_city WHERE gct_cityid > 0 AND gct_name <> 'Unspecified' AND gct_cityid = '".Main::_PrepareData($id)."'");
        return $result;
    }

    static function getCountriesList() {
        $db =& db::getInstance();
        $result = $db->get_results("SELECT * FROM geo_country
                                     WHERE gcn_countryid > 0
                                       AND gcn_name <> 'Unspecified'
                                       AND gcn_status = 1
                                  ORDER BY gcn_order, gcn_name");
        return $result;
    }

    static function getStatesList($countryID) {
        $db =& db::getInstance();
        $result = $db->get_results("SELECT * FROM geo_state
                                     WHERE gst_stateid > 0
                                       AND gst_name <> 'Unspecified'
                                       AND gst_countryid = '".Main::_PrepareData($countryID)."'
                                  ORDER BY gst_name");
        return $result;
    }

    static function getCitiesList($countryID,$stateID) {
        $db =& db::getInstance();
        $result = $db->get_results("SELECT * FROM geo_city
                                     WHERE gct_cityid > 0
                                       AND gct_name <> 'Unspecified'
                                       AND gct_countryid = '".Main::_PrepareData($countryID)."'
                                       AND gct_stateid = '".Main::_PrepareData($stateID)."'
                                  ORDER BY gct_name");
        return $result;
    }

    static function isStateInCountry($stateID,$countryID) {
        $db =& db::getInstance();
        $result = $db->get_row("SELECT * FROM geo_state
                                     WHERE gst_stateid = '".Main::_PrepareData($stateID)."'
                                       AND gst_countryid = '".Main::_PrepareData($countryID)."'");
        if ($result) return true;
        return false;
    }

}
?>