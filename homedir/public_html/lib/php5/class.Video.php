<?
include_once __INCLUDE_CLASS_PATH."/class.RatedItem.php";
include_once __INCLUDE_CLASS_PATH."/class.Tagging.php";

class Video extends ratedItem {

    public $vid_id;
    public $vid_userid;
    public $vid_private;
    public $vid_title;
    public $vid_view;
    public $vid_date;
    public $vid_description;
    public $vid_status;
    public $File;
    public $Frame;
    public $filename;
    public $filepath;
    public $allowed_status = array('new', 'rejected', 'converted');
    public $rating_name = 'video';
    public $vid_views;
    /**
     * Constructor
     *
     * @access public
     */

    function Video(){
        $language =& Language::GetInstance();
        $skin =& Skin::GetInstance();
        $lang =& Language::GetInstance();
        $this->frameImage = array(
                        "rejected" => $skin->ImagePath.$lang->LangID.'/rejected_video.jpg',
                        "new" => $skin->ImagePath.$lang->LangID.'/new_video.jpg',
                        "private" => $skin->ImagePath.$lang->LangID.'/private_video.jpg',
                        "converted" => $skin->ImagePath.$lang->LangID.'/converted_video.jpg',
                                );
        $this->tagging = new Tagging('video');
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
                                FROM videos
                                WHERE vid_id='$eID'");
        if (!$row) return $this->Error("There is no video with this ID [$data]");

        return $this->InitByObject($row);
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

        include_once __INCLUDE_CLASS_PATH."/class.VideoFile.php";
        $this->File = new VideoFile();
        $this->File->Init($this->vid_id,'member',$this->vid_video);

        if ($this->vid_status == 'converted' && $this->vid_video == 'cvid') {
            include_once __INCLUDE_CLASS_PATH."/class.ImageFile.php";
            $this->Frame = new dynamicImageFile();
            $this->Frame->Init($this->vid_id, 'video', 'jpg');
        }

        $this->rating = new RatedItem('video',$this->vid_id);
        $this->rating->rate_url = CONST_LINK_ROOT."/rating/rate_video.php?vid_id={$this->vid_id}&vote=";
        return $this;
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

        if ($this->filepath || !$this->vid_id) {
            include_once __INCLUDE_CLASS_PATH."/class.VideoFile.php";
            $this->File = new VideoFile();
            $result = $this->File->setFile($this->filepath, $this->File->getExtFromPath($this->filename));
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

        $ePrivate       = $this->_PrepareData($this->vid_private);
        $eUser          = $this->_PrepareData($this->vid_userid);
        $eTitle         = $this->_PrepareData($this->vid_title);
        $eDescription   = $this->_PrepareData($this->vid_description);
        $eStatus        = $this->_PrepareData($this->vid_status);

        $db = & db::getInstance();
        
        $fileExt = ($this->filename) ? $this->File->getExtFromPath($this->filename) : $this->vid_video;

        if ($this->vid_id) {
            $query="UPDATE videos SET
                        vid_private = '$ePrivate',
                        vid_userid  = '$eUser',
                        vid_title   = '$eTitle',
                        vid_description = '$eDescription',
                        vid_status  = '$eStatus',
                        vid_video = '$fileExt'
                    WHERE vid_id = '$this->vid_id'
                    ";
        } else {
            $query="INSERT INTO videos SET
                        vid_private = '$ePrivate',
                        vid_userid  = '$eUser',
                        vid_title   = '$eTitle',
                        vid_description = '$eDescription',
                        vid_status  = '$eStatus',
                        vid_date    = now(),
                        vid_video = '$fileExt'
                    ";
        }
        $db->query($query);
        $this->vid_id = ($this->vid_id) ? $this->vid_id : $db->insert_id;
        if (is_array($this->tags) && count($this->tags)) $this->tagging->set($this->vid_id, $this->tags);

        if ($this->filename) {
            $this->File->Init($this->vid_id,'member',$fileExt);
            $result = $this->File->Save();
            if ($result === null) return $this->Error($this->File->error);
        }
        if ($fileExt != $this->vid_video && $this->vid_video != '') unlink(preg_replace("/\.$fileExt$/", ".$this->vid_video", $this->File->_Path));

        return true;
    }

    /**
     * Check value
     *
     * @access private
     */

    function _CheckValue($data){
        if ($data['vid_status'] == '') return $this->CriticalError("Incorrect status [$data[vid_status]] . Allowed are ".join(', ', $this->allowed_status));
//        if ($data['filepath'] == '') return $this->Error("File is empty. Please input file for uploading");
        if ($data['vid_title'] == '') return $this->Error(VIDEO_TITLE_ERROR);
        if ($data['vid_description'] == '') return $this->Error(VIDEO_DESC_ERROR);
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
     * Return info about the frame
     *
     * @param int $type
     *
     * @return array
     *
     * @access public
     */

    function getFrameInfo($size='',$allow_private = true)   {
        if ($allow_private) {
            if ($this->Frame) {
                return $this->Frame->getInfo($size);
            } else {
                return (object)array("Path" => $this->frameImage[$this->vid_status],
                              "w" => @constant("CONST_THUMBS_".strtoupper($size)."_W"),
                              "h" => @constant("CONST_THUMBS_".strtoupper($size)."_H")
                             );
            }
        } else {
            return (object)array("Path" => $this->frameImage['private'],
                          "w" => @constant("CONST_THUMBS_".strtoupper($size)."_W"),
                          "h" => @constant("CONST_THUMBS_".strtoupper($size)."_H")
                         );
        }
    }

    /**
     * Delete video
     *
     * @param int $user_id
     *
     * @access private
     */

    function Delete($user_id, $admin = false){
        $db     = & db::getInstance();
        $eUser  = $this->_PrepareData($user_id);
        $owner_filter = ($admin) ? "" : " AND vid_userid = '$eUser'";

        if ($row = $db->get_row("SELECT * FROM videos WHERE vid_id = '$this->vid_id' $owner_filter")){
            $query="DELETE FROM videos WHERE vid_id = '$this->vid_id'";
            $db->query($query);
            $this->File->Delete();
            if ($this->vid_status == 'converted' && $this->vid_video == '') {
                $this->Frame->Delete();
            
            }
            $this->tagging->delete($this->vid_id);
            $this->rating->delete($this->vid_id);

            include_once __INCLUDE_CLASS_PATH."/class.CommentManager.php";
            $comment_manager = new CommentManager('video', $this->vid_id);
            foreach ($comment_manager->getList(new StdClass) as $comment)
                $comment->delete();
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
        $db     = & db::getInstance();
        $eUser  = $this->_PrepareData($user_id);

        $query="UPDATE videos SET vid_private='Y' WHERE vid_id = '$this->vid_id' AND vid_userid = '$eUser'";
        $db->query($query);

        return true;
    }

    /**
     * Reject video
     *
     * @access public
     */

    function reject(){
        $db     = & db::getInstance();

        $query="UPDATE videos SET vid_status='rejected' WHERE vid_id = '$this->vid_id'";
        $db->query($query);

        return true;
    }

    /**
     * Approve video
     *
     * @access public
     */

    function approve(){
        $db     = & db::getInstance();

        $query="UPDATE videos SET vid_status='converted' WHERE vid_id = '$this->vid_id'";
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
        $db     = & db::getInstance();
        $eUser  = $this->_PrepareData($user_id);

        $query="UPDATE videos SET vid_private='N' WHERE vid_userid = '$eUser'";
        $db->query($query);

        return true;
    }

    /**
     * Return list of videos
     *
     * @return array
     * @access public
     */
    function GetListByMember($mem_id, $status = ''){
        $aResult = array();
        $db = & db::getInstance();
        $eMem_id = $this->_PrepareData($mem_id);
        $status = $this->_PrepareData($status);
        $status_query = ($status) ? " AND vid_status = '$status' " : "";
        $aPicture = $db->get_results("
                                SELECT  *
                                FROM videos
                                WHERE vid_userid = '$eMem_id' $status_query
                                ORDER BY vid_id
        ");
        foreach ($aPicture as $row){
            $t = new Video();
            $t->InitByObject($row);
            $aResult[] = $t;
        }
        return $aResult;
    }

    /**
     * Return list of videos for conversion
     *
     * @return array
     * @access public
     */
    function getListByStatus($status = 'converted', $mode = 'list'){
        $aResult = array();
        $db = & db::getInstance();
        $status = $this->_PrepareData($status);
        $aPicture = $db->get_results("
                                SELECT  *
                                FROM videos
                                WHERE vid_status = '$status'
                                ORDER BY vid_id DESC
        ");
        if ($mode != 'list') return count($aPicture);
        foreach ($aPicture as $row){
            $t = new Video();
            $t->InitByObject($row);
            $aResult[] = $t;
        }
        return $aResult;
    }

    /**
     * Convert video file to FLV
     *
     * @return array
     * @access public
     */
    function convert(){
        $db = & db::getInstance();

        $extension = "ffmpeg";
        $extension_soname = $extension . "." . PHP_SHLIB_SUFFIX;
        $extension_fullname = $extension_soname;

        //dl($extension_soname) or $this->criticalError("Can't load extension $extension_fullname\n");

        $FileName = $this->File->_Path;
        $flvName =  preg_replace("/\.$this->vid_video$/", '.cvid', $this->File->_Path);
        $path_parts = pathinfo($this->File->_Path);

        $thumbname = "/tmp/".preg_replace("/\.$this->vid_video$/",".jpg", $path_parts['basename']);

        $ffmpeg             = "/usr/bin/ffmpeg";
        $FileName           = escapeshellcmd($FileName);
        $ffmpegObj          = new ffmpeg_movie($FileName);
        $srcWidth           = floor($ffmpegObj->getFrameWidth()/2)*2;
        $srcHeight          = floor($ffmpegObj->getFrameHeight()/2)*2;
        $srcFPS             = $ffmpegObj->getFrameRate();
        $frame_count        = floor($ffmpegObj->getFrameCount()/2);
        $frameSnap          = $ffmpegObj->getFrame($frame_count ? $frame_count : 1);
        if (!is_object($frameSnap)) return $this->Error("Can't get frame. May be video file is corrupted.");
         $snapWidth          = $frameSnap->getWidth();
        $snapHeight         = $frameSnap->getHeight();
        $ar = ($ffmpegObj->hasAudio()) ? "  -ar 44100 " : "-an";
        $imageSnap          = $frameSnap->toGDImage();
        $thumb = imagecreatetruecolor(480, 384);
        imagecopyresampled($thumb, $imageSnap, 0, 0, 0, 0, 480, 384, $snapWidth, $snapHeight);
        imagejpeg($thumb, $thumbname, 100);

        $cmd = $ffmpeg . " -i " . $FileName . " -y -f flv -deinterlace $ar -nr 500 -s 320x240 -aspect 4:3 -r ".$srcFPS." -b 400000 -me_range ".$srcFPS." -i_qfactor 0.91 -g 500 " . $flvName;
        echo $cmd."<br>";
        $handle = popen($cmd .' 2>&1', 'r');
        while (!feof($handle)) {
          echo nl2br(fread($handle, 100));
          flush();
        }
        pclose($handle);

        imagedestroy($imageSnap);
        unset($frameSnap);
        unset($ffmpegObj);
        
        if (file_exists($flvName) && filesize($flvName) != 0) {
            include_once __INCLUDE_CLASS_PATH."/class.ImageFile.php";
            $this->Frame = new dynamicImageFile();
            $result = $this->Frame->setFile($thumbname);
            if ($result === null) {
                unlink($thumbname);
                return $this->Error($this->Frame->error);
            }

            $this->Frame->Init($this->vid_id, 'video', 'jpg');
            $result = $this->Frame->Save();
            if ($result === null) {
                unlink($thumbname);
                return $this->Error($this->Frame->error);
            }
            $db->query("UPDATE videos 
                        SET vid_status='converted',
                            vid_video='cvid'
                        WHERE vid_id = '$this->vid_id'");
            unlink($FileName);
            return true;
        }
    }

    /**
     * How long video was added 
     * @return array 
     * @access public
     */

    function getTimeShift(){
        
    	$db =& DB::GetInstance();
        
        $suffix = 'ago';

        $time_shift = $db->get_var("SELECT unix_timestamp(now()) - unix_timestamp('$this->vid_date')");
        
        if ($time_shift < 60)
            return $time_shift." second(s) ".$suffix;
        if ($time_shift < 60*60)
            return floor($time_shift/60)." minute(s) ".$suffix;
        if ($time_shift < 60*60*24)
            return floor($time_shift/(60*60))." hour(s) ".$suffix;
        if ($time_shift < 60*60*24*7)
            return floor($time_shift/(60*60*24))." day(s) ".$suffix;
        if ($time_shift < 60*60*24*30)
            return floor($time_shift/(60*60*24*7))." week(s) ".$suffix;
        return floor($time_shift/(60*60*24*30))." month(s) ".$suffix;
        
    }

    /**
     * Vote
     *
     * @return int
     * @access public
     */

    function vote($userid, $value) {
        $db = & db::getInstance();
        $res = $this->rating->vote($userid, $value);
        if ($res === NULL) {
            return $this->error("Can't vote");
        }
        $rating = $this->rating->getRating();
        $db->query("UPDATE videos SET vid_rating = {$rating->rating} WHERE vid_id = $this->vid_id");
        return true;
    }

    /**
     * Get tags list
     * @return array 
     * @access public
     */

    function getTags($mode = 'array'){
        return ($this->vid_id) ? $this->tagging->getTagsList($this->vid_id, $mode) : "";
    }

    /**     * Get list of videos for currect tag
     * @param int $tag_id 
     * @param object $pager 
     * @return array 
     * @access public
     */

    function getList(&$pager,$tag_id = '',$order = '', $userid = ''){
        if ($order == 'new')  {           $order_ext = " vid_date DESC ";
        } elseif ($order == 'rated') {
            $order_ext = " vid_rating DESC ";
        } elseif ($order == 'view') {
            $order_ext = " vid_views DESC ";
        } elseif ($order == '') {
            $order_ext = " vid_views DESC ";
        } else {
            $this->CriticalError("Incorrect order request");
        }

        if ($userid) {            $db = & db::getInstance();
            $username = $db->escape($username);
            $count = $db->get_var("
                             SELECT count(*)
                             FROM videos
                             INNER JOIN members ON mem_userid=vid_userid
                             WHERE vid_status = 'converted' AND mem_userid = '$userid'
                                   ");

            $list = $db->get_results("
                             SELECT  *
                             FROM videos
                             INNER JOIN members ON mem_userid=vid_userid
                             WHERE vid_status = 'converted' AND mem_userid = '$userid' 
			     ORDER BY $order_ext ".
                             $pager->GetLimit($count)
                                   );
        
        } else {
            if ($tag_id) {
                $list = $this->tagging->getEntList($tag_id, 'videos', 'vid_id', $pager, "vid_status='converted'", $order_ext);
            } else {
                
                $db = & db::getInstance();
                $count = $db->get_var("
                                 SELECT count(*)
                                 FROM videos
                                 WHERE vid_status = 'converted'
                                       ");

                $list = $db->get_results("
                                 SELECT  *
                                 FROM videos
                                 WHERE vid_status = 'converted'
                                 ORDER BY $order_ext".
                                 $pager->GetLimit($count)
                                       );
            }  
        }
            
        foreach ($list as $row){
            $t = new Video();
            $aResult[] = $t->InitByObject($row);
        }
        return $aResult;
    }


    function getListByName(&$pager, $username = ''){

        $db = & db::getInstance();
        $username = $db->escape($username);
        $count = $db->get_var("
                         SELECT count(*)
                         FROM videos
                         INNER JOIN members ON mem_userid=vid_userid
                         WHERE vid_status = 'converted' AND mem_username LIKE '%$username%'
                               ");

        $list = $db->get_results("
                         SELECT  *
                         FROM videos
                         INNER JOIN members ON mem_userid=vid_userid
                         WHERE vid_status = 'converted' AND mem_username LIKE '%$username%'".
                         $pager->GetLimit($count)
                               );
        foreach ($list as $row){
            $t = new Video();
            $aResult[] = $t->InitByObject($row);
        }
        return $aResult;
    }

    /**

     * Get list of videos for close to currect
     * @return array 
     * @access public
     */

    function getRelativeList($limit = 5){
        $aResult = array();
        $list = $this->tagging->getRelativsEntList($this->vid_id, 'videos', 'vid_id', "vid_status='converted'", 'vid_rating', $limit);
        foreach ($list as $row){
            $t = new Video();
            $aResult[] = $t->InitById($row->ent_id);
        }
        return $aResult;
    }

    /**
     * Clear private status from all records
     *
     * @param int $user_id
     * @access public
     */

    function addView($user_id){
        $db     = & db::getInstance();
        $eUser  = $this->_PrepareData($user_id);

        $query="UPDATE videos SET vid_views = vid_views + 1 WHERE vid_id = '$this->vid_id'";
        $db->query($query);

        return true;
    }


}
?>