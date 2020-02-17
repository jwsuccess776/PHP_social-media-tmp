<?php
class Pager extends Main{

    var $_listsize = 10 ;
    var $PAGE;
    var $PAGESIZE;
    var $TOTAL;
    var $FIRSTPAGE;
    var $PREVPAGE;
    var $NEXTPAGE;
    var $LASTPAGE;
    var $FIRSTPOS;
    var $ENDPOS;
    var $BASICURL;
    var $LIST = array() ;

    /**
     * Constructor
     *
     * @access public
     */
    function __construct($page,$pagesize=0){

        $this->PAGE = $page;
        $this->PAGESIZE = $pagesize;
        if ($this->PAGE<1) $this->PAGE = 1;
        if ($this->PAGESIZE<1) {
                $OptionManagerLink=new OptionManager();
                $option_manager=$OptionManagerLink->GetInstance();
          //  $option_manager = &OptionManager::GetInstance();
            $this->PAGESIZE = $option_manager->GetValue('page_size');
        }
    }

    /**
     * Make analize and return Limit for query
     *
     * @param int $count
     * @access public
     */

    function GetLimit($count){
        $this->TOTAL = $count;
        if ($this->TOTAL<1) {
            $this->TOTAL = 0;
            $this->PAGE=1;
            return " LIMIT 0,0";
        }
        $this->_Init();
        $this->_GetList();
        return " LIMIT $this->STARTPOS,$this->PAGESIZE";
    }

    /**
     * Set url for link
     *
     * @param string $url
     * @access public
     */

    function SetUrl($url){
        $this->BASICURL = (preg_match("/\?/",$url)) ? "$url&" : "$url?";
    }

    /**
     * Calculate page params
     *
     * @access private
     */

     function _Init(){
        $this->LASTPAGE = ceil($this->TOTAL/$this->PAGESIZE);
        $this->PAGE = ($this->LASTPAGE < $this->PAGE) ? $this->LASTPAGE : $this->PAGE;
        $this->FIRSTPAGE = 1;
        $this->PREVPAGE = ($this->PAGE >1) ? $this->PAGE-1 : 1;
        $this->NEXTPAGE = ($this->PAGE < $this->LASTPAGE) ? $this->PAGE+1 : $this->LASTPAGE;

        $this->STARTPOS = $this->PAGE*$this->PAGESIZE - $this->PAGESIZE;

        if ($this->STARTPOS+$this->PAGESIZE > $this->TOTAL) {
            $this->ENDPOS = $this->TOTAL;
        } else {
            $this->ENDPOS = $this->STARTPOS + $this->PAGESIZE;
        }
        $this->FIRSTPOS=($this->TOTAL) ? $this->STARTPOS+1 : 0;
        $this->TOTAL = $this->TOTAL;
    }

    /**
     * Create list of pages
     *
     * @access private
     */

    function _GetList(){

        if ($this->LASTPAGE <= $this->_listsize) {
            $start   = 1;
            $stop    = $this->LASTPAGE;
        } else {
            $shift    = floor($this->_listsize/2);
            $start    = $this->PAGE - $shift;
            $stop     = $start + $this->_listsize -1 ;
            if ($start<=0) {
                $start = 1;
                $stop = $this->_listsize;
            }
            if ($stop>=$this->LASTPAGE) {
                $start = $this->LASTPAGE - $this->_listsize +1 ;
                $stop = $this->LASTPAGE;
            }
        }
        for ($i=$start; $i<=$stop; $i++)
            $this->LIST[] = $i;
    }
}
?>