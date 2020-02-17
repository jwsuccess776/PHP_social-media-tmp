<?
include_once __INCLUDE_CLASS_PATH."/class.Main.php";

class Gallery extends Main {

    public $Gallery_ID;
    public $Name;
    public $Description;
    public $Level;
    public $mem_id;
    public $LimitList = array ("Private" => GALLERY_PRIVATE, "Hotlist" => GALLERY_HOTLIST, "Public" => GALLERY_PUBLIC,);
    public $ImagePerLine = 3;

    /**
     * Initialisation of object
     *
     * @param mixed $data
     * @access public
     */
    function Init($data){
        if (!is_object($data)){
            $db = & db::getInstance();
            $eID = $this->_PrepareData($data);
            $row = $db->get_row("
                                SELECT 	*
                                FROM gallery
                                WHERE Gallery_ID='$eID'");
            if (!$row) return $this->CriticalError("Can't find gallery for ID [$data]");
        } else {
            $result = $this->_CheckValue($data);
            if ($result === null)
                return $this->Error("Incorrect gallery data");
            $row = $data;
        }
        foreach ($row as $key => $data)
            $this->{$key} = $data;
        return true;
    }

    /**
     * Save option data
     *
     * @param numeric $value
     * @access public
     */
    function Save(){

        $eName         = $this->_PrepareData($this->Name);
        $eDescription  = $this->_PrepareData($this->Description);
        $eLevel        = $this->_PrepareData($this->Level);
        $eMem_id       = $this->_PrepareData($this->mem_id);

        $db = & db::getInstance();
        $lang =& Language::getInstance();
        if ($this->Gallery_ID) {
            $query="UPDATE gallery SET
                        Name		= '$eName',
                        Description	= '$eDescription',
                        Level		= '$eLevel',
                        LangID		= '$lang->LangID'
                    WHERE Gallery_ID = '$this->Gallery_ID'
                    ";
        } else {
            $query="INSERT INTO gallery SET
                        Name		= '$eName',
                        Description	= '$eDescription',
                        Level		= '$eLevel',
                        LangID		= '$lang->LangID',
                        mem_id		= '$eMem_id'
                    ";
        }

        $db->query($query);
        $this->Gallery_ID = ($this->Gallery_ID) ? $this->Gallery_ID : $db->insert_id;
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
     * Check value
     *
     * @access private
     */

    function _CheckValue($value){
        if ($value->Name == '')    return $this->Error("Name is empty");
        return true;
    }

    /**
     * Check value
     *
     * @access private
     */

    function Delete($mem_id){
        include_once __INCLUDE_CLASS_PATH."/class.GalleryItem.php";
        $db = & db::getInstance();
        $eMem_id = $this->_PrepareData($mem_id);
        $query="DELETE FROM gallery
                WHERE Gallery_ID = '$this->Gallery_ID'
                AND mem_id = '$eMem_id'";
        $db->query($query);

        $aItems = $this->GetItemList($this->Gallery_ID);
        $t = new GalleryItem();
        foreach ($aItems as $item) {
            $t->Init($item);
            $t->Delete();
        }
        return true;
    }

    /**
     * Return list of Galleries
     *
     * @return array
     * @access public
     */
    function GetListByMember($mem_id,$level=array()){
        $db = & db::getInstance();
        $lang =& Language::getInstance();
        $eMem_id = $this->_PrepareData($mem_id);
        if (count($level) >0){
            $eLevel = array_map(array('Gallery','_PrepareData'),$level);
            $query_level = " AND Level in ('".join("','",$eLevel)."')";
        }

       if ($eMem_id == $_SESSION['Sess_UserId'] ) {
	   					$aGallery = $db->get_results("
                                SELECT  *
                                FROM gallery 
                                WHERE mem_id = '$eMem_id'
                                $query_level
                                ORDER BY Name
        ");
        } else {
			$aGallery = $db->get_results("
                                SELECT  *, a.Gallery_ID
                                FROM gallery m
								LEFT JOIN galleryitem a ON (a.Gallery_ID = m.Gallery_ID)
                                WHERE m.mem_id = '$eMem_id'
								AND (m.Level != 'Private')
                                $query_level
                                GROUP BY m.Gallery_ID HAVING count(a.Gallery_ID) > 0
								ORDER BY Name
        ");
		}
        $aResult = array ();
        foreach ($aGallery as $row){
            $t = new Gallery();
            $t->Init($row);
            $aResult[] = $t;
        }
        return $aResult;
    }

    /**
     * Return list of Gallery Items
     *
     * @return array
     * @access public
     */
    function GetItemList($Gallery_ID){
        $db = & db::getInstance();
        $eGallery_ID = $this->_PrepareData($Gallery_ID);

        $aItems = $db->get_results("
                                SELECT 	*
                                FROM galleryitem
                                WHERE Gallery_ID = '$eGallery_ID'
                                ORDER BY GalleryItem_ID
        ");

        return $aItems;
    }

    /**
     * Return list of approved Gallery Items
     *
     * @return array
     * @access public
     */
    function GetApprovedItemList($Gallery_ID){
        $db = & db::getInstance();
        $eGallery_ID = $this->_PrepareData($Gallery_ID);

        $aItems = $db->get_results("
                                SELECT 	*
                                FROM galleryitem
                                WHERE Gallery_ID = '$eGallery_ID'
                                AND Approved = 'Approved'
        ");

        return $aItems;
    }

    /**
     * Return list of pending Gallery Items
     *
     * @return array
     * @access public
     */
    function GetPendingItemList($Gallery_ID){
        $db = & db::getInstance();
        $eGallery_ID = $this->_PrepareData($Gallery_ID);

        $aItems = $db->get_results("
                                SELECT 	*
                                FROM galleryitem
                                WHERE Approved = 'Pending'
                                AND Gallery_ID = '$eGallery_ID'
        ");

        return $aItems;
    }

    /**
     * Get list of pending items for one member
     *
     * @return array
     *
     * @access public
     */
    function GetPendingGallery($mem_id){
        $db = & db::getInstance();
        $eMem_id = $this->_PrepareData($mem_id);

        $aItems = $db->get_results("
                                SELECT 	*
                                FROM gallery g
                                    INNER JOIN galleryitem i
                                    ON (g.Gallery_ID = i.Gallery_ID)
                                    INNER JOIN members m
                                    ON (m.mem_userid = g.mem_id)
                                WHERE Approved = 'Pending'
                                AND mem_id = '$eMem_id'
                                GROUP BY g.Gallery_ID;
        ");
        return $aItems;
    }

    /**
     * Get one member with pending items
     *
     * @return array
     *
     * @access public
     */
    function GetMemberForApprove(){
        $db = & db::getInstance();

        $member = $db->get_var("
                                SELECT 	mem_id
                                FROM gallery g
                                    INNER JOIN galleryitem i
                                    ON (g.Gallery_ID = i.Gallery_ID)
                                    INNER JOIN members m
                                    ON (m.mem_userid = g.mem_id)
                                WHERE Approved = 'Pending'
                                GROUP BY mem_id
                                LIMIT 0,1;
        ");
        return $member;
    }

    /**
     * Count pending items
     *
     * @return intrger
     *
     * @access public
     */
    function CountPendingItem(){
        $db = & db::getInstance();

        return $db->get_var("
                                SELECT 	count(*)
                                FROM gallery g
                                    INNER JOIN galleryitem i
                                    ON (g.Gallery_ID = i.Gallery_ID)
                                    INNER JOIN members m
                                    ON (m.mem_userid = g.mem_id)
                                WHERE Approved = 'Pending'
        ");
    }

}

?>