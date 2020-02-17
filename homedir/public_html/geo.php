<?php
/*****************************************************
* © copyright 1999 - 2020 iDateMedia, LLC.
*
* All materials and software are copyrighted by iDateMedia, LLC.
* under British, US and International copyright law. All rights reserved.
* No part of this code may be reproduced, sold, distributed
* or otherwise used in whole or in part without prior written permission.
*
****************************************************/

######################################################################
#
# Name:         goe.php
#
# Description:  Displays the profile input page (after advert)
#
# Version:      7.2
#
######################################################################

include('db_connect.php');
set_time_limit(6000);
$countries = array('US' => 204, 'US:Alabama' => 204, 'US:Alaska' => 204, 'US:Arizona' => 204, 'US:Arkansas' => 204, 'US:California' => 204, 'US:Colorado' => 204, 'US:Connecticut' => 204, 'US:Delaware' => 204, 'US:Florida' => 204, 'US:Georgia' => 204, 'US:Hawaii' => 204, 'US:Idaho' => 204, 'US:Illinois' => 204, 'US:Indiana' => 204, 'US:Iowa' => 204, 'US:Kansas' => 204, 'US:Kentucky' => 204, 'US:Louisiana' => 204, 'US:Maine' => 204, 'US:Maryland' => 204, 'US:Massachusetts' => 204, 'US:Michigan' => 204, 'US:Minnesota' => 204, 'US:Mississippi' => 204, 'US:Missouri' => 204, 'US:Montana' => 204, 'US:N.Carolina' => 204, 'US:N.Mexico' => 204, 'US:Nebraska' => 204, 'US:Nevada' => 204, 'US:New Hampshire' => 204, 'US:New Jersey' => 204, 'US:New York' => 204, 'US:North Dakota' => 204, 'US:Ohio' => 204, 'US:Oklahoma' => 204, 'US:Oregon' => 204, 'US:Pennsylvania' => 204, 'US:Rhode Island' => 204, 'US:S.Carolina' => 204, 'US:South Dakota' => 204, 'US:Tennessee' => 204, 'US:Texas' => 204, 'US:Utah' => 204, 'US:Vermont' => 204, 'US:Virginia' => 204, 'US:Washington' => 204, 'US:Washington DC' => 204, 'US:West Virginia' => 204, 'US:Wisconsin' => 204, 'US:Wyoming' => 204, 'CA' => 37, 'CA:Alberta' => 37, 'CA:British Columbia' => 37, 'CA:Manitoba' => 37, 'CA:New Brunswick' => 37, 'CA:Newfoundland' => 37, 'CA:North West Territories' => 37, 'CA:Nova Scotia' => 37, 'CA:Ontario' => 37, 'CA:Prince Edward Island' => 37, 'CA:Quebec' => 37, 'CA:Saskatchewan' => 37, 'CA:Yukon' => 37, 'EU:Austria' => 14, 'EU:Belgium' => 21, 'EU:Cyprus' => 51, 'EU:Denmark' => 53, 'EU:Finland' => 68, 'EU:France' => 71, 'EU:Germany' => 76, 'EU:Gibraltar' => 78, 'EU:Greece' => 79, 'EU:Iceland' => 93, 'EU:Ireland' => 98, 'EU:Italy' => 100, 'EU:Luxembourg' => 119, 'EU:Malta' => 127, 'EU:Netherlands' => 142, 'EU:Norway' => 151, 'EU:Portugal' => 161, 'EU:Spain' => 179, 'EU:Sweden' => 185, 'EU:Switzerland' => 186, 'EU:Turkey' => 196, 'EU:United Kingdom' => 203, 'EU:FMR. Yugoslavia' => 170, 'EE:Albania' => 2, 'EE:Armenia' => 10, 'EE:Bosnia' => 27, 'EE:Bulgaria' => 32, 'EE:Croatia' => 49, 'EE:Czech Republic' => 52, 'EE:Estonia' => 63, 'EE:Hungary' => 92, 'EE:Kazakhstan' => 105, 'EE:Latvia' => 113, 'EE:Lithuania' => 118, 'EE:Poland' => 160, 'EE:Romania' => 164, 'EE:Russia' => 165, 'EE:Slovakia' => 174, 'EE:Slovenia' => 175, 'EE:Ukraine' => 201, 'AU' => 13, 'AU:Fiji' => 67, 'AU:New South Wales' => 13, 'AU:New Zealand' => 145, 'AU:Northern Territory' => 13, 'AU:Queensland' => 13, 'AU:South Australia' => 13, 'AU:Tasmania' => 13, 'AU:Victoria' => 13, 'AU:Western Australia' => 13, 'AS:Bangladesh' => 18, 'AS:Brunei Darussalam' => 31, 'AS:Cambodia' => 36, 'AS:China' => 43, 'AS:Hong Kong' => 91, 'AS:India' => 94, 'AS:Indonesia' => 95, 'AS:Japan' => 103, 'AS:Malaysia' => 124, 'AS:Maldives' => 125, 'AS:Nepal' => 140, 'AS:North Korea' => 109, 'AS:Pakistan' => 153, 'AS:Philippines' => 159, 'AS:Singapore' => 173, 'AS:South Korea' => 108, 'AS:Sri Lanka' => 180, 'AS:Taiwan' => 188, 'AS:Thailand' => 191, 'ME:Bahrain' => 17, 'ME:Egypt' => 59, 'ME:Iran' => 96, 'ME:Iraq' => 97, 'ME:Israel' => 99, 'ME:Jordan' => 104, 'ME:Kuwait' => 110, 'ME:Lebanon' => 114, 'ME:Oman' => 152, 'ME:Qatar' => 162, 'ME:Saudi Arabia' => 169, 'ME:Syria' => 187, 'ME:U.A.E' => 202, 'AF:Algeria' => 3, 'AF:Angola' => 5, 'AF:Ethiopia' => 64, 'AF:Ghana' => 77, 'AF:Kenya' => 106, 'AF:Liberia' => 116, 'AF:Libya' => 117, 'AF:Morocco' => 136, 'AF:Mozambique' => 137, 'AF:Namibia' => 138, 'AF:Nigeria' => 148, 'AF:South Africa' => 178, 'AF:Tanzania' => 190, 'AF:Tunisia' => 195, 'AF:Uganda' => 200, 'AF:Zambia' => 211, 'AF:Zimbabwe' => 212, 'SA:Argentina' => 9, 'SA:Brazil' => 29, 'SA:Chile' => 42, 'SA:Columbia' => 44, 'SA:Paraguay' => 157, 'SA:Peru' => 158, 'SA:Uruguay' => 214, 'SA:Venezuela' => 206, 'CE:Costa Rica' => 48, 'CE:Cuba' => 50, 'CE:Dominica' => 56, 'CE:Ecuador' => 58, 'CE:Haiti' => 89, 'CE:Honduras' => 90, 'CE:Panama' => 155, 'CE:Mexico' => 131, 'WI:Antigua' => 8, 'WI:Aruba' => 11, 'WI:Bahamas' => 16, 'WI:Barbados' => 19, 'WI:Bermuda' => 24, 'WI:Cayman Islands' => 39, 'WI:Jamaica' => 102, 'WI:Puerto Rico' => 213, 'WI:St Vincent' => 181, 'WI:Tobago' => 194, 'WI:Trinidad' => 194);
$states = array('US' => 0, 'US:Alabama' => 1, 'US:Alaska' => 2, 'US:Arizona' => 3, 'US:Arkansas' => 4, 'US:California' => 5, 'US:Colorado' => 6, 'US:Connecticut' => 7, 'US:Delaware' => 8, 'US:Florida' => 9, 'US:Georgia' => 10, 'US:Hawaii' => 11, 'US:Idaho' => 12, 'US:Illinois' => 13, 'US:Indiana' => 14, 'US:Iowa' => 15, 'US:Kansas' => 16, 'US:Kentucky' => 17, 'US:Louisiana' => 18, 'US:Maine' => 19, 'US:Maryland' => 20, 'US:Massachusetts' => 21, 'US:Michigan' => 22, 'US:Minnesota' => 23, 'US:Mississippi' => 24, 'US:Missouri' => 25, 'US:Montana' => 26, 'US:N.Carolina' => 33, 'US:N.Mexico' => 31, 'US:Nebraska' => 27, 'US:Nevada' => 28, 'US:New Hampshire' => 29, 'US:New Jersey' => 30, 'US:New York' => 32, 'US:North Dakota' => 34, 'US:Ohio' => 35, 'US:Oklahoma' => 36, 'US:Oregon' => 37, 'US:Pennsylvania' => 38, 'US:Rhode Island' => 39, 'US:S.Carolina' => 40, 'US:South Dakota' => 41, 'US:Tennessee' => 42, 'US:Texas' => 43, 'US:Utah' => 44, 'US:Vermont' => 45, 'US:Virginia' => 46, 'US:Washington' => 47, 'US:Washington DC' => 47, 'US:West Virginia' => 48, 'US:Wisconsin' => 49, 'US:Wyoming' => 50, 'CA' => 0, 'CA:Alberta' => 52, 'CA:British Columbia' => 53, 'CA:Manitoba' => 54, 'CA:New Brunswick' => 55, 'CA:Newfoundland' => 56, 'CA:North West Territories' => 57, 'CA:Nova Scotia' => 58, 'CA:Ontario' => 60, 'CA:Prince Edward Island' => 61, 'CA:Quebec' => 62, 'CA:Saskatchewan' => 63, 'CA:Yukon' => 64, 'EU:Austria' => 0, 'EU:Belgium' => 0, 'EU:Cyprus' => 0, 'EU:Denmark' => 0, 'EU:Finland' => 0, 'EU:France' => 0, 'EU:Germany' => 0, 'EU:Gibraltar' => 0, 'EU:Greece' => 0, 'EU:Iceland' => 0, 'EU:Ireland' => 0, 'EU:Italy' => 0, 'EU:Luxembourg' => 0, 'EU:Malta' => 0, 'EU:Netherlands' => 0, 'EU:Norway' => 0, 'EU:Portugal' => 0, 'EU:Spain' => 0, 'EU:Sweden' => 0, 'EU:Switzerland' => 0, 'EU:Turkey' => 0, 'EU:United Kingdom' => 0, 'EU:FMR. Yugoslavia' => 0, 'EE:Albania' => 0, 'EE:Armenia' => 0, 'EE:Bosnia' => 0, 'EE:Bulgaria' => 0, 'EE:Croatia' => 0, 'EE:Czech Republic' => 0, 'EE:Estonia' => 0, 'EE:Hungary' => 0, 'EE:Kazakhstan' => 0, 'EE:Latvia' => 0, 'EE:Lithuania' => 0, 'EE:Poland' => 0, 'EE:Romania' => 0, 'EE:Russia' => 0, 'EE:Slovakia' => 0, 'EE:Slovenia' => 0, 'EE:Ukraine' => 0, 'AU' => 0, 'AU:Fiji' => 0, 'AU:New South Wales' => 70, 'AU:New Zealand' => 0, 'AU:Northern Territory' => 71, 'AU:Queensland' => 72, 'AU:South Australia' => 73, 'AU:Tasmania' => 74, 'AU:Victoria' => 75, 'AU:Western Australia' => 76, 'AS:Bangladesh' => 0, 'AS:Brunei Darussalam' => 0, 'AS:Cambodia' => 0, 'AS:China' => 0, 'AS:Hong Kong' => 0, 'AS:India' => 0, 'AS:Indonesia' => 0, 'AS:Japan' => 0, 'AS:Malaysia' => 0, 'AS:Maldives' => 0, 'AS:Nepal' => 0, 'AS:North Korea' => 0, 'AS:Pakistan' => 0, 'AS:Philippines' => 0, 'AS:Singapore' => 0, 'AS:South Korea' => 0, 'AS:Sri Lanka' => 0, 'AS:Taiwan' => 0, 'AS:Thailand' => 0, 'ME:Bahrain' => 0, 'ME:Egypt' => 0, 'ME:Iran' => 0, 'ME:Iraq' => 0, 'ME:Israel' => 0, 'ME:Jordan' => 0, 'ME:Kuwait' => 0, 'ME:Lebanon' => 0, 'ME:Oman' => 0, 'ME:Qatar' => 0, 'ME:Saudi Arabia' => 0, 'ME:Syria' => 0, 'ME:U.A.E' => 0, 'AF:Algeria' => 0, 'AF:Angola' => 0, 'AF:Ethiopia' => 0, 'AF:Ghana' => 0, 'AF:Kenya' => 0, 'AF:Liberia' => 0, 'AF:Libya' => 0, 'AF:Morocco' => 0, 'AF:Mozambique' => 0, 'AF:Namibia' => 0, 'AF:Nigeria' => 0, 'AF:South Africa' => 0, 'AF:Tanzania' => 0, 'AF:Tunisia' => 0, 'AF:Uganda' => 0, 'AF:Zambia' => 0, 'AF:Zimbabwe' => 0, 'SA:Argentina' => 0, 'SA:Brazil' => 0, 'SA:Chile' => 0, 'SA:Columbia' => 0, 'SA:Paraguay' => 0, 'SA:Peru' => 0, 'SA:Uruguay' => 0, 'SA:Venezuela' => 0, 'CE:Costa Rica' => 0, 'CE:Cuba' => 0, 'CE:Dominica' => 0, 'CE:Ecuador' => 0, 'CE:Haiti' => 0, 'CE:Honduras' => 0, 'CE:Panama' => 0, 'CE:Mexico' => 0, 'WI:Antigua' => 0, 'WI:Aruba' => 0, 'WI:Bahamas' => 0, 'WI:Barbados' => 0, 'WI:Bermuda' => 0, 'WI:Cayman Islands' => 0, 'WI:Jamaica' => 0, 'WI:Puerto Rico' => 0, 'WI:St Vincent' => 0, 'WI:Tobago' => 0, 'WI:Trinidad' => 0);

if($_POST[action] == 'adverts')
{
    #mysql_query("ALTER TABLE `adverts` ADD `adv_countryid` INT DEFAULT '0' NOT NULL AFTER `adv_country` , ADD `adv_stateid` INT DEFAULT '0' NOT NULL AFTER `adv_countryid` , ADD `adv_cityid` INT DEFAULT '0' NOT NULL AFTER `adv_stateid`") or die(mysql_error());

    while(list($code, $countryid) = each($countries))
    {
        $stateid = $states[$code];
        $sql_query = "UPDATE adverts SET adv_countryid = $countryid, adv_stateid = $stateid WHERE adv_country = '$code'";
        mysql_query($sql_query) or die(mysql_error());
    }
    
    $sql_query = "SELECT * FROM adverts";
    $sql_result = mysql_query($sql_query) or die(mysql_error());
    $numrows = mysql_num_rows($sql_result);
    $i = 0;
    while($advert = mysql_fetch_object($sql_result))
    {
        $i++;
        echo "$i - $numrows<br>"; flush();
        if($advert->adv_location)
        {
            $sql_query = "SELECT * FROM geo_city WHERE LCASE(TRIM(gct_name)) REGEXP CONCAT('^',LCASE(TRIM('".mysql_escape_string($advert->adv_location)."\\\\*?')),'$') AND gct_countryid = $advert->adv_countryid AND ($advert->adv_stateid = 0 OR gct_stateid = $advert->adv_stateid)";
            $sql_result_city = mysql_query($sql_query, $link) or die($sql_query.'<br>'.mysql_error());
            if(mysql_num_rows($sql_result_city) == 1)
            {
                $city = mysql_fetch_object($sql_result_city);
                $sql_query = "UPDATE adverts SET adv_cityid = $city->gct_cityid WHERE adv_userid = $advert->adv_userid";
                mysql_query($sql_query) or die(mysql_error());
                if($advert->adv_stateid == 0 && $city->gct_stateid)
                {
                    $sql_query = "UPDATE adverts SET adv_stateid = $city->gct_stateid WHERE adv_userid = $advert->adv_userid";
                    mysql_query($sql_query) or die(mysql_error());
                }
            }
        }
    }
    echo 'Done<br>';
}
elseif($_POST[action] == 'sarray')
{
    $sql_query = "UPDATE sarray SET sar_type = 'lstCountryT' WHERE sar_type = 'lstCountry'";
    $sql_result = mysql_query($sql_query, $link) or die(mysql_error());
    $sql_query = "SELECT * FROM sarray WHERE sar_type = 'lstCountryT'";
    $sql_result = mysql_query($sql_query, $link) or die(mysql_error());
    $numrows = mysql_num_rows($sql_result);
    $i = 0;
    while($record = mysql_fetch_object($sql_result))
    {
        $i++;
        echo "$i - $numrows<br>"; flush();
        if(array_key_exists($record->sar_value, $countries) && $countries[$record->sar_value] != 0)
        {
            $sql_query = "INSERT INTO sarray (sar_userid, sar_type, sar_value) VALUES ($record->sar_userid, 'lstCountry', ".$countries[$record->sar_value].")";
            mysql_query($sql_query, $link) or die(mysql_error());
        }
        if(array_key_exists($record->sar_value, $states) && $states[$record->sar_value] != 0)
        {
            $sql_query = "INSERT INTO sarray (sar_userid, sar_type, sar_value) VALUES ($record->sar_userid, 'lstState', ".$states[$record->sar_value].")";
            mysql_query($sql_query, $link) or die(mysql_error());
        }
    }
    $sql_query = "DELETE FROM sarray WHERE sar_type = 'lstCountryT'";
    //$sql_result = mysql_query($sql_query, $link) or die(mysql_error());
    echo 'Done<br>';
}
?>
<form method="post">
    <input type="hidden" name="action" value="adverts">
    <input type="submit" value="update adverts" class="button">
</form>
<form method="post">
    <input type="hidden" name="action" value="sarray">
    <input type="submit" value="update sarray" class="button">
</form>
