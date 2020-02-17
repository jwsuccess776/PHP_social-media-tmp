<?
include_once __INCLUDE_CLASS_PATH."/class.Parser.php";
include_once __INCLUDE_CLASS_PATH."/class.Item.php";

class Template extends Item {

    public $config = array();
    public $comments;

    /**
     * Parce template
     *
     * @param array $aData
     * @param string $LangID
     * @return string
     * @access public
     */
    function Parse($aData,$LangID){
        $result = $this->parser->Parse($this->value[$LangID],$aData);
        if ($result === null)
            return $this->Error("Can't parse template [$this->name].");
        return $result;
    }

    /**
     * Prepare data before save it
     *
     * @param mixed $data
     *
     * @access private
     */
    function _PrepareData($data){
        $db = & db::getInstance();
        return $db->escape($data);
    }

    /**
     * Extract options config for current option type
     *
     * @access private
     */

    function _ExtractData(){
        $aResult = array();
        foreach (explode(";",trim($this->config)) as $var)
            if ($var != "") $aResult[] = $var;
        $this->config = $aResult;
        return true;
    }

    /**
     * Check template body
     *
     * @param string $Value
     * @access private
     */

    function _CheckValue($Value){
        $result = $this->parser->CheckText($Value);
        if ($result === null)
            return $this->Error($this->parser->error);
        return true;
    }


}

class MTemplate extends Template {

    public $type;
    public $value=array();

    /**
     * Constructor
     *
     * @param string $name
     * @access public
     */
    function MTemplate($name,&$parser){
        $db = & db::getInstance();
        $eName = $db->escape($name);
        $row = $db->get_row("SELECT 	*
                            FROM mailtemplate
                            WHERE Name='$eName'");
        if (!$row) return $this->CriticalError("Can't find mail template $name");
        $this->MailTemplate_ID = $row->MailTemplate_ID;
        $this->type = $row->Type;
        $this->name = $row->Name;
        $this->config = $row->Params;
        $this->comments = (defined($row->Description)) ? constant($row->Description) : $row->Description;
        $this->parser =& $parser;

        $aBody = $db->get_results("
                            SELECT 	*
                            FROM lang_mailtemplate
                            WHERE MailTemplate_ID='$this->MailTemplate_ID'");
        foreach ($aBody as $row) $this->value[$row->lang_id] = $row->Body ;
        $this->_ExtractData();
        $this->parser->SetAllowed($this->config);
    }

    /**
     * Save option data
     *
     * @param string $Body
     * @param string $Type
     * @param string $LangID
     *
     * @access public
     */
    function Save($Body,$Type,$LangID){
        $result = $this->_CheckValue($Body);
        if ($result === null)
            return $this->Error("Can't save template [$this->comments]");
        $eBody = $this->_PrepareData($Body);
        $eType = $this->_PrepareData($Type);
        $LangID = $this->_PrepareData($LangID);
        $db = & db::getInstance();
        $db->query("
            UPDATE mailtemplate SET
                Type = '$eType'
            WHERE
                Name = '$this->name'
        ");
        $db->query("
            REPLACE lang_mailtemplate SET
                Body = '$eBody',
                MailTemplate_ID  = '$this->MailTemplate_ID',
                lang_id = '$LangID'
        ");
        $this->value[$LangID] = $Body;
        $this->type = $Type;
        $m =& MTemplateManager::getInstance();
        $m->Clear($this->name);
        return true;
    }
}
class PTemplate extends Template {

    public $type;
    public $value=array();

    /**
     * Constructor
     *
     * @param string $name
     * @access public
     */
    function PTemplate($name,&$parser){
        $db = & db::getInstance();
        $eName = $db->escape($name);
        $row = $db->get_row("SELECT 	*
                            FROM pagetemplate
                            WHERE Name='$eName'");
        if (!$row) return $this->CriticalError("Can't find page template $name");
        $this->PageTemplate_ID = $row->PageTemplate_ID;
        $this->name = $row->Name;
        $this->config = $row->Params;
        $this->comments = (defined($row->Description)) ? constant($row->Description) : $row->Description;
        $this->parser =& $parser;

        $aBody = $db->get_results("
                            SELECT 	*
                            FROM lang_pagetemplate
                            WHERE PageTemplate_ID='$this->PageTemplate_ID'");
        foreach ($aBody as $row) $this->value[$row->lang_id] = $row->Body ;
        $this->_ExtractData();
    }

    /**
     * Save option data
     *
     * @param string $Body
     * @param string $Type
     * @param string $LangID
     *
     * @access public
     */
    function Save($Body,$LangID){
        $result = $this->_CheckValue($Body);
        if ($result === null)
            return $this->Error("Can't save template [$this->comments]");
        $eBody = $this->_PrepareData($Body);
        $LangID = $this->_PrepareData($LangID);
        $db = & db::getInstance();
        $db->query("
            REPLACE lang_pagetemplate SET
                Body = '$eBody',
                PageTemplate_ID  = '$this->PageTemplate_ID',
                lang_id = '$LangID'
        ");
        $this->value[$LangID] = $Body;
        $m =& PTemplateManager::getInstance();
        $m->Clear($this->name);
        return true;
    }
}


?>