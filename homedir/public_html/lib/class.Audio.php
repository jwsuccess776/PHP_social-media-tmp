<?
include_once __INCLUDE_CLASS_PATH."/class.Main.php";

class Audio extends Main {

    var $aud_id;
    var $aud_userid;
    var $aud_private;
    var $File;
    var $title_file;
    var $private_file;

    /**
     * Constructor
     *
     * @access public
     */

    function __construct(){
        $language =& Language::GetInstance();
        $skin =& Skin::GetInstance();
        $this->title_file = $skin->ImagePath."$language->LangID/playaudio.gif";
        $this->private_file = $skin->ImagePath."$language->LangID/privateaudio.gif";
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
                                SELECT 	*
                                FROM audios
                                WHERE aud_id='$eID'");
        if (!$row) return $this->Error('');

        foreach ($row as $key => $data) $this->{$key} = $data;

        include_once __INCLUDE_CLASS_PATH."/class.AudioFile.php";
        $this->File = new AudioFile();
        $this->File->Init($this->aud_id,'member',$this->aud_audio);
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

        include_once __INCLUDE_CLASS_PATH."/class.AudioFile.php";
        $this->File = new AudioFile();
        $this->File->Init($this->aud_id,'member',$this->aud_audio);

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

        include_once __INCLUDE_CLASS_PATH."/class.AudioFile.php";
        $this->File = new AudioFile();
        $result = $this->File->setFile($this->filepath, $this->File->getExtFromPath($this->filename));
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

        $ePrivate    = $this->_PrepareData($this->aud_private);
        $eUser       = $this->_PrepareData($this->aud_userid);

        $db = & db::getInstance();
        $fileExt = $this->File->getExtFromPath($this->filename);

        if ($this->pic_id) {
            $query="UPDATE audios SET
                        aud_private	= '$ePrivate',
                        aud_audio = '$fileExt',
                        aud_userid	= '$eUser'
                    WHERE aud_id = '$this->aud_id'
                    ";
        } else {
            $query="INSERT INTO audios SET
                        aud_private	= '$ePrivate',
                        aud_audio = '$fileExt',
                        aud_userid	= '$eUser'
                    ";
        }
        $db->query($query);
        $this->aud_id = ($this->aud_id) ? $this->aud_id : $db->insert_id;

        $this->File->Init($this->aud_id,'member',$fileExt);
        $result = $this->File->Save();
        if ($result === null) return $this->Error($this->File->error);

        return true;
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
     * Delete video
     *
     * @param int $user_id
     *
     * @access private
     */

    function Delete($user_id){
        $db = & db::getInstance();
        $eUser       = $this->_PrepareData($user_id);

        $query="DELETE FROM audios WHERE aud_id = '$this->aud_id' AND aud_userid = '$eUser'";
        if ($db->query($query)) {
            $this->File->Delete();
        }
        return true;
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

        $query="UPDATE audios SET aud_private='Y' WHERE aud_id = '$this->aud_id' AND aud_userid = '$eUser'";
        $db->query($query);

        return true;
    }

    /**
     * Clear private status from all records
     *
     * @param int $user_id

     * @access public
     */

    function ClearPrivate($user_id){
        $db = & db::getInstance();
        $eUser       = $this->_PrepareData($user_id);

        $query="UPDATE audios SET aud_private='N' WHERE aud_userid = '$eUser'";
        $db->query($query);

        return true;
    }

    /**
     * Return list of audios
     *
     * @return array
     * @access public
     */
    function GetListByMember($mem_id){
        $aResult = array();
        $db = & db::getInstance();
        $eMem_id = $this->_PrepareData($mem_id);
        $aAudio = $db->get_results("
                                SELECT 	*
                                FROM audios
                                WHERE aud_userid = '$eMem_id'
                                ORDER BY aud_id
        ");
        foreach ($aAudio as $row){
            $t = new Audio();
            $t->InitByObject($row);
            $aResult[] = $t;
        }
        return $aResult;
    }



}

?>