<?
class Parser extends Main{

    public $allowed = array();
    public $text;
    public $l = "{";
    public $r = "}";

    /**
     * Constructor
     *
     * @access public
     */

    function SetAllowed($aAllowed = array()){
        if (!is_array($aAllowed))
            return $this->CriticalError("Allowed list isn't array");
        $this->allowed = $aAllowed;
    }

    /**
     * Parse text
     *
     * @param array $aData
     * @return string
     * @access public
     */
    function Parse($Text,$aData=array()){}
    function _CheckData($aData){return true;}

    /**
     * Check Text
     *
     * @param string text
     *
     * @access public
     */
    function CheckText($Text){return true;}
}
class StrictParser extends Parser {

    /**
     * Parse text, insert variable and return parsed text
     *
     * @param array $aData
     * @access public
     */
    function Parse($Text,$aData){
        $result = $this->_CheckData($aData);
        if ($result === null)
            return $this->Error("Can't parse. Incorrect incomming data");

        $parsed_text = $Text;
        foreach ($this->allowed as $name) {
            if (!empty($name)) $parsed_text = preg_replace("/{$this->l}$name{$this->r}/",$aData[$name],$parsed_text,-1);
        }
        return $parsed_text;
    }

    /**
     * Check Data
     *
     * @param array $aData
     * @access public
     */

     function _CheckData($aData){
        if (!is_array($aData))
            return $this->CriticalError("Incoming data isn't array");
        foreach ($aData as $key =>$value) {
            if (!in_array($key,$this->allowed))
                return $this->CriticalError("Incorrect variable [$key] in incomming data. See allowed list [".join(", ",$this->allowed)."]");
        }
        foreach ($this->allowed as $key) {
            if (!array_key_exists($key,$aData))
                return $this->CriticalError("Incomming data isn't full. Variable [$key] is missing in incomming data.");
        }
        return true;
    }

    /**
     * Check Text
     *
     * @param string text
     * @access public
     */

     function CheckText($text){
        preg_match_all("/$this->l(\w+?)$this->r/",$text,$aRes);
        $aVariables = $aRes[1];
        foreach ($aVariables as $key) {
            if (!in_array($key,$this->allowed))
                return $this->Error("Incorrect variable [$key] was found. See allowed list [".join(", ",$this->allowed)."]");
        }
        return true;
    }
}
class EvalParser extends Parser {

    public $l = '$';
    public $r = "";

    /**
     * Parse text, insert variable and return parsed text
     *
     * @param array $aData
     * @access public
     */
    function Parse($Text){
        $reg = "/(\\{$this->l}(\w[a-zA-Z0-9_]*){$this->r})/e";
        $parsed_text = preg_replace($reg,"\$GLOBALS['$2']",$Text,-1);
        return $parsed_text;
    }

}

?>