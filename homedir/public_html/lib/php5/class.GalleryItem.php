<?
include_once __INCLUDE_CLASS_PATH."/class.Main.php";

class GalleryItem extends Main {

    public $Gallery_ID;
    public $GalleryItem_ID;
    public $Description;
    public $Approved;
    public $Type;
    public $StatusList = array ("Approved" => GALLERY_APPROVED, "Pending" => GALLERY_PENDING, "Rejected" => GALLERY_REJECTED,);
    public $File;

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
                                SELECT 	GalleryItem_ID,
                                         Gallery_ID,
                                        Description,
                                        Type,
                                        Approved
                                FROM galleryitem
                                WHERE GalleryItem_ID='$eID'");
            if (!$row) return $this->Error('Unknown item');
         } else {
            $result = $this->_CheckValue($data);
            if ($result === null)
                return $this->Error("Incorrect data");
            $row = $data;
        }

        if ($row->Type == 'Image') {
            include_once __INCLUDE_CLASS_PATH."/class.ImageFile.php";
            $this->File = new ImageFile();
        } elseif ($row->Type == 'Video')  {
            include_once __INCLUDE_CLASS_PATH."/class.VideoFile.php";
            $this->File = new VideoFile();
        } else {
            return $this->Error("Incorrect file format.Please upload image or video file");
        }

        foreach ($row as $key => $data)
            $this->{$key} = $data;

        if ($row->filepath){
            $result = $this->File->setFile($row->filepath,$row->SubType);
            if ($result === null) return $this->Error($this->File->error);
        }

        if ($this->GalleryItem_ID) {
            $result = $this->File->Init($this->GalleryItem_ID,'gallery');
            if ($result === null) return $this->Error($this->File->error);
        }
        return true;
    }

    /**
     * Save option data
     *
     * @param numeric $value
     * @access public
     */
    function Save(){
        $option_manager =& OptionManager::GetInstance();

        $eDescription = $this->_PrepareData($this->Description);
        $eGallery_ID = $this->_PrepareData($this->Gallery_ID);
        $eType = $this->_PrepareData($this->Type);


        $db = & db::getInstance();

        if ($this->GalleryItem_ID) {
            $query="UPDATE galleryitem SET
                        Type	= '$eType',
                        Description	= '$eDescription'
                    WHERE GalleryItem_ID = '$this->GalleryItem_ID'
                    ";
        } else {
            if ($this->File->tempPath == '') return $this->Error("Please input file for uploading");

            $query="INSERT INTO galleryitem SET
                        Description	= '$eDescription',
                        Type	= '$eType',
                        Gallery_ID = '$eGallery_ID'
                    ";
        }
        $db->query($query);
        $this->GalleryItem_ID = ($this->GalleryItem_ID) ? $this->GalleryItem_ID : $db->insert_id;

        $this->File->Init($this->GalleryItem_ID,'gallery');
        $result = $this->File->Save();
        if ($result === null) return $this->Error($this->File->error);

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
        return true;
    }

    /**
     * Approve
     *
     * @param string status
     * @param string reject reason
     *
     * @access public
     */

    function Approve($status,$reason=''){
        include_once __INCLUDE_CLASS_PATH."/class.Gallery.php";

        $db = & db::getInstance();
        $eStatus = $this->_PrepareData($status);
        $db->query("UPDATE galleryitem
                    SET Approved='$eStatus'
                    WHERE GalleryItem_ID  = '$this->GalleryItem_ID'");
/*
        $gallery = new Gallery();
        $gallery->Init($this->Gallery_ID);

        $member = $db->get_row("SELECT * FROM members WHERE mem_userid = '$gallery->mem_id'");

        $data['ReceiverName']    = $member->mem_username;
        $data['CompanyName']     = $option_manager->GetValue('company');
        $data['Url']             = $option_manager->GetValue('url');
        $data['SupportEmail']    = $option_manager->GetValue('suppmail');

        if ($status == 'Rejected') {
            $data['Reason']          = $reason;
            list($type,$message) = getTemplateByName("Gallery_Reject_Mail",$data,getDefaultLanguage($member->mem_userid));
            send_mail ($member->mem_email, $option_manager->GetValue('mail'), $option_manager->GetValue('company')." ".GALLERY_UNAPR, $message,$type,"ON");

        } elseif ($status == 'Approved') {
            list($type,$message) = getTemplateByName("Gallery_Approve_Mail",$data,getDefaultLanguage($member->mem_userid));
            send_mail ($member->mem_email, $option_manager->GetValue('mail'), $option_manager->GetValue('company')." ".GALLERY_UNAPR, $message,$type,"ON");
            $data['Reason']          = $reason;
        }
*/
        return true;
    }

    /**
     * Check value
     *
     * @access private
     */

    function Delete(){
        $db = & db::getInstance();
        $query="DELETE FROM galleryitem WHERE GalleryItem_ID = '$this->GalleryItem_ID'";
        $db->query($query);

        $this->File->Delete();
        return true;
    }

}

?>