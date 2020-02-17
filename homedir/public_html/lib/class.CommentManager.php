<?php
require_once(__INCLUDE_CLASS_PATH."/class.Main.php");
require_once(__INCLUDE_CLASS_PATH."/class.Comment.php");

class CommentManager extends Main{

    var $object_id;
    var $object_type;
    
    function __construct($object_type, $object_id = 0) {
        $this->object_type = $object_type;
        $this->object_id = $object_id;
    }
    
    function initialized() {
        switch (true) {
            case empty($this->object_id):
            case empty($this->object_type):
                return false;
            default:
                return true;    
        }
    }
    
    function add($text, $user_id) {
        if ($this->initialized()) {
            $comment = new Comment();
            $comment->ent_id = $this->object_id;
            $comment->type   = $this->object_type;
            $comment->text =   $text;
            $comment->user_id = $user_id;
            $comment->approve = 1;
            $res = $comment->save();
            if ($res === null)  {
                $this->error($comment->error); 
                return $this->error(ADD_COMMENT_ERROR); 
            } 
            return true;
        }
        return false;    
    }

    function get($id) {
        if ($this->initialized()) {
            $comment = new Comment();
            return $comment->initById($id);
        }
    }
    
    function count() {
        $result = 0;
        if ($this->initialized()) {
            $db = & db::getInstance();
            $result = $db->get_var("
                                    SELECT count(*) 
                                    FROM comments 
                                    WHERE ent_id = '$this->object_id' 
                                    AND type= '$this->object_type'");
        }
        return $result;
    }
    
    function getList(&$pager) {
        $result = array();
        $limit = '';
        if ($this->initialized()) {
            $db = & db::getInstance();
            if (get_class($pager) == 'pager') {
                if (!is_object($pager)) return $this->criticalError("Incorrect paging object");
                $count = $db->get_var("
                                        SELECT count(*)
                                        FROM comments 
                                        WHERE ent_id = '$this->object_id' 
                                        AND type= '$this->object_type'
                                      ");
                $limit = $pager->getLimit($count);    
            } 
            
            $list = $db->get_results("
                                    SELECT * 
                                    FROM comments 
                                    WHERE ent_id = '$this->object_id' 
                                    AND type= '$this->object_type'
                                    ORDER BY date ASC".
                                    $limit
                                    );
            foreach ($list as $obj) {
                $comment = new Comment();
                $result[] = $comment->initByObj($obj);
            }
        }
        return $result;
    }
}
?>