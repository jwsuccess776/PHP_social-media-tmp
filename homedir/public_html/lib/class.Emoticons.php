<?
include_once __INCLUDE_CLASS_PATH."/class.Main.php";

class Emoticons extends Main{

    var $emotions  = array(
                        ":smile:"	=>	"smile.gif",
                        ":laugh:"	=>	"laugh.gif",
                        ":tongue:"	=>	"tongue.gif",
                        ":suprise:"	=>	"surprise.gif",
                        ":wink:"	=>	"wink.gif",
                        ":angry:"	=>	"angry.gif",
                        ":queer:"	=>	"queer.gif",
                        ":cool:"	=>	"cool.gif",
                        ":shock:"	=>	"shock.gif",
                        ":bashful:"	=>	"bashful.gif",
                        ":sad:"	    =>	"sad.gif",
                        ":cry:"	    =>	"cry.gif",
                    );
    var $display_width = 6;

    /**
     * Constructor
     *
     * @access public
     */
    function __construct(){
        $this->emotions = array_map(array('Emoticons','MakePath'),$this->emotions);
    }

    /**
     * Make parsing
     *
     * @param string $text
     * @return string
     *
     * @access public
     */
    function Parse($text){
        return str_replace(array_keys($this->emotions), array_values($this->emotions), $text);
    }

    /**
     * Make path to emotions files
     *
     * @param array $emotion
     * @static
     * @return array
     *
     * @access public
     */
    function MakePath($emotion){
        return "<img src=\"".CONST_EMOTIONS_PATH."$emotion\" width='19' height='19' border=0>";
    }

     /**
     * Display emoticons area
     *
     * @param string $area_id
     * @return text
     *
     * @access public
     */
    function DisplayIcons($area_id){
        $result = '<table width=100% border="0" cellspacing="4" cellpadding="3"><tr>';
        $i=0;
        foreach ($this->emotions as $key => $value){
            $result .= "<td width=25 align=\"center\" valign=\"middle\"><a href=\"javascript:emoticon('$area_id','$key')\">$value</a></td>";
            if (++$i%$this->display_width == 0) $result .= "</tr><tr>";
        }
        $result .= '</tr></table>';
        return $result;
    }
}
?>