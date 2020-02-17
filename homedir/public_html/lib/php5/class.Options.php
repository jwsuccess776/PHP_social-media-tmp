<?
include_once __INCLUDE_CLASS_PATH."/class.Item.php";

class Option extends Item {

    public $type;
    public $label;
    public $config;
    public $group;
    public $comments;

    /**
     * Initialisation object
     *
     * @param mixed $data
     * @access public
     */
    function Init($data){
        if (!is_object($data)){
            $db = & db::getInstance();
            $eName = $db->escape($data);
            $row = $db->get_row("
                                SELECT 	A.Name,
                                        A.Value,
                                        A.Label,
                                        A.Config,
                                        A.Type,
                                        A.Comments,
                                        B.Name AS GroupName,
                                        B.Title AS GroupTitle
                                FROM options A
                                    INNER JOIN optionsgroup B
                                    ON (A.OptionsGroup_ID = B.OptionsGroup_ID)
                                WHERE A.Name='$eName'");
            if (!$row) return $this->CriticalError("Can't find option $data");
        } else {
            $row = $data;
        }
        $this->type = $row->Type;
        $this->label = (defined($row->Label)) ? constant($row->Label) : $row->Label;
        $this->name = $row->Name;
        $this->value = $row->Value;
        $this->config = $row->Config;
        $this->group = $row->Group;
        $this->comments = (defined($row->Comments)) ? constant($row->Comments) : $row->Comments;
        $this->_ExtractData();
    }

    /**
     * Save option data
     *
     * @param numeric $value
     * @access public
     */
    function Save($value){
        $result = $this->_CheckValue($value);
        if ($result === null)
            return $this->Error("Can't save option $this->label");
        $pValue = $this->_PrepareData($value);
        $db = & db::getInstance();
        $db->query("
            UPDATE options SET
                Value = '$pValue'
            WHERE
                Name = '$this->name'
        ");
        $this->value = $value;
        $m =& OptionManager::getInstance();
        $m->Clear($this->name);
        return true;
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

    /**
     * Extract options config for current option type
     *
     * @access private
     */

    function _ExtractData(){}
    function _CheckValue(){}
}

class NumericOption extends Option{

    public $limit;

    /**
     * Extract options config for current option type
     *
     * @access private
     */
    function _ExtractData(){
        $temp = eval($this->config);
        if (!is_array($temp))
            return $this->CriticalError("Incorrect config for option $this->name:<br>$this->config");
        $this->limit = $temp;
    }

    /**
     * Check value
     *
     * @access private
     */

    function _CheckValue($value){
        if (!isInt($value) && !isFloat($value))
            return $this->Error("Incorrect value [$value]. Only numeric value allowed");
        if ($value < $this->limit['min'])
            return $this->Error("Incorrect value [$value]. Min allowed value is {$this->limit['min']}");
        if ($value > $this->limit['max'])
            return $this->Error("Incorrect value [$value]. Max allowed value is {$this->limit['max']}");
        return true;
    }
}

class StringOption  extends Option {

    /**
     * Check value
     *
     * @access private
     */

    function _CheckValue($value){
        return true;
    }

}

class ListOption  extends Option{

    /**
     * List of allowed values for this option
     *
     * Exmple
     *
     * $list = array(
     * 	"value of option" => "label",
     * 	"value of option" => "label",
     * 	"value of option" => "label",
     * );
     */
    public $list;

    /**
     * Extract options config for current option type
     *
     * @access private
     */
    function _ExtractData(){
        $temp = eval($this->config);
        if (!is_array($temp))
            return $this->CriticalError("Incorrect config for option $this->name:<br>$this->config");
        $this->list = $temp;
    }

    /**
     * Check value
     *
     * @access private
     */

    function _CheckValue($value){
        if (!array_key_exists($value,$this->list))
            return $this->Error("Incorrect value [$value]. Allowed values are [".join("][",array_keys($this->list))."]");
        return true;
    }
}

class EmailOption  extends Option{

    /**
     * Check value
     *
     * @access private
     */

    function _CheckValue($value){
        if (!isEmail($value))
            return $this->Error("Incorrect value [$value]. It is not correct email");
        return true;
    }
}
class UrlOption  extends Option{

    /**
     * Check value
     *
     * @access private
     */

    function _CheckValue($value){
        if (!isUrl($value))
            return $this->Error("Incorrect value [$value]. It is not correct url");
        return true;
    }
}
class DateFormatOption  extends Option{

    /**
     * Extract options config for current option type
     * search and replace patern @~format~@ to date in current format
     * @access private
     */

    function _ExtractData(){
        $this->comments = preg_replace("/@~format~@/",date($this->value),$this->comments);
        return true;
    }

    /**
     * Check value
     *
     * @access private
     */

    function _CheckValue($value){
        if (0)
            return $this->Error("Incorrect value [$value]. It is not correct date format");
        return true;
    }
}
class TimeFormatOption  extends Option{

    /**
     * Extract options config for current option type
     * search and replace patern @~format~@ to time in current format
     * @access private
     */

    function _ExtractData(){
        $this->comments = preg_replace("/@~format~@/",date($this->value),$this->comments);
        return true;
    }

    /**
     * Check value
     *
     * @access private
     */

    function _CheckValue($value){
        if (0)
            return $this->Error("Incorrect value [$value]. It is not correct time format");
        return true;
    }
}

class SkinOption  extends Option{

    public $list;

    /**
     * Extract options config for current option type
     *
     * @access private
     */
    function _ExtractData(){
        $skin = &Skin::GetInstance();
        foreach ($skin->SkinsList() as $name => $path ) $this->list[$name] = $name;
    }

    /**
     * Check value
     *
     * @access private
     */

    function _CheckValue($value){
        if (!array_key_exists($value,$this->list))
            return $this->Error("Incorrect value [$value]. Allowed values are [".join("][",array_keys($this->list))."]");
        return true;
    }
}

class LangOption  extends Option{

    public $list;

    /**
     * Extract options config for current option type
     *
     * @access private
     */
    function _ExtractData(){
        $language = &Language::GetInstance();
        foreach ($language->GetActiveList() as $row ) {
            $this->list[$row->LangID] = $row->Name;
        }
    }

    /**
     * Check value
     *
     * @access private
     */

    function _CheckValue($value){
        if (!array_key_exists($value,$this->list))
            return $this->Error("Incorrect value [$value]. Allowed values are [".join("][",array_keys($this->list))."]");
        return true;
    }
}

?>