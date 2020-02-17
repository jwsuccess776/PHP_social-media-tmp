<?php
include_once __INCLUDE_CLASS_PATH."/class.Main.php";

class Picture extends Main {

    var $pic_id;
    var $pic_userid;
    var $pic_private;
    var $pic_default;
    var $pic_approved;
    var $File;
    var $private_file;

    /**
     * Constructor
     *
     * @access public
     */

    function __construct(){
        $LanguageLink=new Language();
       $language= $LanguageLink->GetInstance();
        //$language =& Language::GetInstance();
       $SkinLink=new Skin();
       $skin =$SkinLink->GetInstance();
        //$skin =& Skin::GetInstance();
        $this->private_file = $skin->ImagePath."$language->LangID/private.gif";
    }

    /**
     * Initialisation of object by ID
     *
     * @param int $data
     * @access public
     */
    function InitById($data){
        $db = & db::getInstance();

        $eID = $this->_PrepareData($data);
        $row = $db->get_row("
                            SELECT  *
                            FROM pictures
                            WHERE pic_id='$eID'");
        if (!$row) return $this->Error('');

        foreach ($row as $key => $data) $this->{$key} = $data;

        include_once __INCLUDE_CLASS_PATH."/class.ImageFile.php";
        $this->File = new ImageFile();
        $this->File->Init($this->pic_id,'member');
        return true;
    }

    /**
     * Initialisation of object ofObject
     *
     * @param object $data
     * @access public
     */
    function InitByObject($data){
        foreach ($data as $key => $value)
            $this->{$key} = $value;

        include_once __INCLUDE_CLASS_PATH."/class.ImageFile.php";
        $this->File = new ImageFile();
        $this->File->Init($this->pic_id,'member');

        return true;
    }

    /**
     * Initialisation of object for saving
     *
     * @param array $data
     * @access public
     */
    function InitForSave($data){
        $result = $this->_CheckValue($data);
        if ($result === null)
            return $this->Error("Incorrect data");

        foreach ($data as $key => $value) $this->{$key} = $value;

        include_once __INCLUDE_CLASS_PATH."/class.ImageFile.php";
        $this->File = new ImageFile();
        $result = $this->File->setFile($this->filepath);
        if ($result === null) return $this->Error($this->File->error);

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

        $ePrivate    = $this->_PrepareData($this->pic_private);
        $eDefault    = $this->_PrepareData($this->pic_default);
        $eUser       = $this->_PrepareData($this->pic_userid);
        $eApproved    = $this->_PrepareData($this->pic_approved);

        $db = & db::getInstance();

        if ($this->pic_id) {
            $query="UPDATE pictures SET
                        pic_private = '$ePrivate',
                        pic_default = '$eDefault',
                        pic_userid  = '$eUser',
                        pic_approved  = '$eApproved'
                    WHERE pic_id = '$this->pic_id'
                    ";
            $db->query($query);
        } else {
            $eDefault = ($db->get_var("SELECT count(*) FROM pictures WHERE pic_userid='$eUser'")) ? 'N': 'Y';

            $query="INSERT INTO pictures SET
                        pic_private = '$ePrivate',
                        pic_default = '$eDefault',
                        pic_approved  = '$eApproved',
                        pic_userid  = '$eUser'
                    ";
            $db->query($query);
            $this->pic_id = $db->insert_id;
        }
        $this->File->Init($this->pic_id,'member');
        $result = $this->File->Save();
        if ($result === null) return $this->Error($this->File->error);

        return $this->pic_id;
    }

    /**
     * Check value
     *
     * @access private
     */

    function _CheckValue($data){
        if ($data['filepath'] == '') return $this->Error("File is empty. Please input file for uploading");
        return true;
    }

    /**
     * Return info about the file
     *
     * @param int $type
     *
     * @return array
     *
     * @access public
     */

    function getInfo($type='')   {
        return $this->File->getInfo($type);
    }

    /**
     * Delete picture
     *
     * @param int $user_id
     *
     * @access private
     */

    function Delete($user_id){
        $set_default = true;
        $db = & db::getInstance();
        $eUser       = $this->_PrepareData($user_id);

        $aPublic = $this->GetListByMember($eUser, 'public');
        $aPrivate = $this->GetListByMember($eUser, 'private');

        if ($this->pic_default == 'Y' && count($aPublic) == 1 && count($aPrivate) > 0) {
            return $this->error("You can't delete default image. You should have one not private picture");
        }

        $query="DELETE FROM pictures WHERE pic_id = '$this->pic_id' AND pic_userid = '$eUser'";
        if ($db->query($query)) {
            $this->File->Delete();
        }

        $aPictures = $this->GetListByMember($eUser);
        $db->query("UPDATE pictures SET pic_default='Y' WHERE pic_id = '{$aPictures[0]->pic_id}' AND pic_userid = '$eUser'");

        return true;
    }

    /**
     * Set picture as default
     *
     * @param int $user_id

     * @access public
     */

    function SetDefault($user_id){
        $db = & db::getInstance();
        $eUser = $this->_PrepareData($user_id);

        if ($this->pic_private == 'Y')
            return $this->Error("You can't set private image as default");

        $query="UPDATE pictures SET pic_default='N' WHERE pic_userid = '$eUser'";
        $db->query($query);

        $query="UPDATE pictures SET pic_default='Y' WHERE pic_id = '$this->pic_id' AND pic_userid = '$eUser'";

        $db->query($query);

        return true;
    }

    /**
     * Get default picture
     *
     * @access public
     */

    function GetDefault($user_id){
        $db = & db::getInstance();

        $eUser       = $this->_PrepareData($user_id);
        $row = $db->get_row("
                            SELECT  *
                            FROM pictures
                            WHERE pic_userid='$eUser'
							AND pic_approved = 1
                            AND pic_default = 'Y'");
        if (!$row) return "";

        $t = new Picture();
        $t->InitByObject($row);
        return $t;
    }

    /**
     * Set private
     *
     * @param int $user_id

     * @access public
     */

    function SetPrivate($user_id){
        $db = & db::getInstance();
        $eUser       = $this->_PrepareData($user_id);

        $query="UPDATE pictures SET pic_private='Y' WHERE pic_id = '$this->pic_id' AND pic_userid = '$eUser'";
        $db->query($query);

        return true;
    }

    /**
     * Set private
     *
     * @param int $user_id

     * @access public
     */

    function ClearPrivate($user_id){
        $db = & db::getInstance();
        $eUser       = $this->_PrepareData($user_id);

        $query="UPDATE pictures SET pic_private='N' WHERE pic_userid = '$eUser'";
        $db->query($query);

        return true;
    }

    /**
     * Return list of pictures
     *
     * @return array
     * @access public
     */
    function GetListByMember($mem_id, $status = 'all'){
        $aResult = array();

        if ($status == 'private'){
            $query_filter = " AND pic_private = 'Y' AND pic_approved = 1";
        } elseif ($status == 'public') {
            $query_filter = " AND pic_private = 'N'  AND pic_approved = 1";
        } elseif ($status == 'showall') {
            $query_filter = " AND pic_approved = 1";
        } elseif ($status == 'approve') {
            $query_filter = " AND pic_approved = 0";
        } elseif ($status == 'all') {
            $query_filter = " ";
        } else {
            $this->criticalError("Invalid picture status [$status]");
        }

        $db = & db::getInstance();
        $eMem_id = $this->_PrepareData($mem_id);
        $aPicture = $db->get_results("
                                SELECT  *
                                FROM pictures
                                WHERE pic_userid = '$eMem_id'
                                $query_filter
                                ORDER BY pic_default,pic_id
        ");
        foreach ($aPicture as $row){
            $t = new Picture();
            $t->InitByObject($row);
            $aResult[] = $t;
        }
        return $aResult;
    }



}

?>