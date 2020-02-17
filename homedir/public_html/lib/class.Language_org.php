<?
class Language extends Main {

    var $LangID;
    var $Charset;
    var $FileName;
    var $Name;
    var $ImageName;
    var $Status;

    /**
     * Return static link to object
     *
     * @return static link
     * @access public
     */

    function & getInstance(){
        $language = & SingeltonStorage::get("instance", "Language", null);
        if ($language === null){
            $language = new Language;
        }
        return $language;
    }

    /**
     * Initialise language, set LandID,Chaset,File
     *
	 * @param string $LangID
     * @return static link
     * @access public
     */

    function Init($LangID){
        $db = & db::getInstance();
        $options = & OptionManager::getInstance();
        $eLangID = $db->escape($LangID);
        $row = $db->get_row("
        					SELECT *
							FROM langfile
        					WHERE lang_id='$eLangID' AND lang_active=1");
        if (!$row) {
            $LangID = $db->escape($options->GetValue('default_language'));
	        $row = $db->get_row("
	        					SELECT *
								FROM langfile
	        					WHERE lang_id='$LangID' AND lang_active=1");
            if (!$row)
                return $this->CriticalError("Default language [$langID] isn't active");

        }
        $this->LangID = $row->lang_id;
        $this->Charset = $row->lang_charset;
        $this->FileName = $row->lang_filename;
        $this->Status = $row->lang_active;
        $this->Name = $row->lang_name;
        return true;
    }

    /**
     * Return list of Languages with object fields
     *
     * @return array List of objects
     * @access public
     */
    function Convert($aList){
        $aLanguages = array();
        foreach($aList as $row)
        $aLanguages[] = (object) array(
        					"Status"=>$row->lang_active,
        					"Name"=>$row->lang_name,
        					"LangID"=>$row->lang_id,
        					"ImageName"=>$row->lang_imagename,
        					"FileName"=>$row->lang_filename,
        					"Charset"=>$row->lang_charset,
                            );
        return $aLanguages;
	}

    /**
     * Grep record list and check if languages files exists for languages
     *
     * @param array  List of objects
     * @return array List of objects
     * @access public
     */
    function checkEnable($row){
        if (!is_object($row)) {
            $db = & db::getInstance();
            $row = $db->escape($row);
            $row = $db->get_row("SELECT * FROM langfile WHERE lang_id = '$row'");
        }
       	if (!file_exists(CONST_INCLUDE_ROOT."/languages/{$row->lang_filename}.php")) return null;
       	if (!file_exists(CONST_INCLUDE_ROOT."/languages/{$row->lang_filename}_js.php")) return null;
       	if (!file_exists(CONST_INCLUDE_ROOT."/languages/{$row->lang_filename}_admin.php")) return null;
        return true;
	}

    /**
     * Return list of Languages
     *
     * @return array List of objects
     * @access public
     */
    function GetList(){
        $db = & db::getInstance();
        $aLanguages = $db->get_results("SELECT * FROM langfile");
        return $this->Convert($aLanguages);
	}

    /**
     * Return list of active languages
     *
     * @return array List of objects
     * @param string lang_id
     * @access public
     */
    function GetActiveList($lang_id = ''){
        $db = & db::getInstance();
        $lang_id = $db->escape($lang_id);
        $lang_query = ($lang_id) ? " AND lang_id = '$lang_id'" : "";
        $aLanguages = $db->get_results("SELECT * FROM langfile WHERE lang_active=1 $lang_query");
        return $this->Convert($aLanguages);
	}

    /**
     * Activate language
     *
     * @param string $LangID
     * @access public
     */
    function Activate($LangID){
        $db = & db::getInstance();
        $eLangID = $db->escape($LangID);
        if (!$this->checkEnable($LangID))
            return $this->error("You don't have correct languages files for this language [$LangID]");
        $db->query("UPDATE langfile SET lang_active=1 WHERE lang_id='$eLangID'");
        return true;
	}

    /**
     * Deactivate language
	 *
     * @param string $LangID
     * @access public
     */
    function Deactivate($LangID){
        $db = & db::getInstance();
        $eLangID = $db->escape($LangID);
        $db->query("UPDATE langfile SET lang_active=0 WHERE lang_id='$eLangID'");
        if ($db->rows_affected != 1)
                return $this->CriticalError("Incorrect language [$langId] for deactivating");
        return true;
	}

    /**
     * Deactivate all languages
     *
     * @access public
     */
    function DeactivateAll(){
        $db = & db::getInstance();
        $db->query("UPDATE langfile SET lang_active=0 WHERE 1");
        return true;
	}

}


?>