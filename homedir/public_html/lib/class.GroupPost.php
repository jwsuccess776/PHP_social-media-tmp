<?php
require_once __INCLUDE_CLASS_PATH."/class.Main.php";
require_once __INCLUDE_CLASS_PATH."/class.Group.php";
require_once __INCLUDE_CLASS_PATH."/class.GroupImage.php";

/*
Note on post status:
0 - new/unapproved
1 - approved
2 - rejected
3 - draft
*/

class GroupPost extends Main {
    var $id;
    var $groupid;
    var $topic;
    var $subject;
    var $text;
    var $author;
    var $created;
    var $status;

    var $images = array();
    var $imagesLimit = 3;

    var $strictIntegrityCheck;
    var $integrityFlaws = array();

    function __construct($id = 0, $strictIntergityCheck = true) {
        $this->strictIntegrityCheck = (boolean)$strictIntergityCheck;
        if (intval($id)) {
            $this->id = intval($id);
            $this->_retrieve();
        }
    }

    function initByArray($data) {
        if (is_array($data)) {
            $db =& db::getInstance();
            if (!empty($data['id']) && intval($data['id']))
                $this->id = intval($data['id']);

            if (!empty($data['groupid']) && intval($data['groupid'])) {
                if ($db->count('groups', "id = '".$this->_prepareData($data['groupid'])."'"))
                    $this->groupid = intval($data['groupid']);
                else
                    $this->_integrityError('groupid');
            } elseif (!$this->groupid) // group ID wasn't prepopulated
                $this->_integrityError('groupid');                

            if (!empty($data['topic']))
                $this->topic = intval($data['topic']);

            if (!empty($data['author']) && intval($data['author'])) {
                $this->author = intval($data['author']);
            } elseif (!$this->author) // author wasn't prepopulated
                $this->_integrityError('author');

            if (!empty($data['subject']))
                $this->subject = $this->_prepareText($data['subject']);
            else
                return $this->Error(GRP_ERR_SUBJECT_EMPTY);

            if (!empty($data['text']))
                $this->text = $data['text'];
            else
                return $this->Error(GRP_ERR_POST_EMPTY);

            if (!empty($data['created']) && intval($data['created']))
                $this->created = intval($data['created']);

            $this->status = intval($data['status']);
        }
        if (count($this->error)) 
            return null;
        else
            return true;
    }

    function initDraftByArray($data) {
        if (is_array($data)) {
            $db =& db::getInstance();
            if (!empty($data['id']) && intval($data['id']))
                $this->id = intval($data['id']);
            else 
                $this->_integrityError('id');

            if (!empty($data['groupid']) && intval($data['groupid'])) {
                if ($db->count('groups', "id = '".$this->_prepareData($data['groupid'])."'"))
                    $this->groupid = intval($data['groupid']);
                else
                    $this->_integrityError('groupid');
            } else
                $this->_integrityError('groupid');
                
            if (!empty($data['topic']))
                $this->topic = intval($data['topic']);

            if (!empty($data['author']) && intval($data['author'])) {
                $this->author = intval($data['author']);
            } elseif (!$this->author) // author wasn't prepopulated
                $this->_integrityError('author');

            if (!empty($data['subject']))
                $this->subject = $this->_prepareText($data['subject']);

            if (!empty($data['text']))
                $this->text = $data['text'];

            $this->status = 3;
        }
        return true;
    }

    function initImages() {
        for ($st = 0; $st < $this->imagesLimit; $st++) {
            $image = new GroupImage($this->id, $st);
            if ($image->uploaded)
                $this->images[] = $image;
        }
    }

    function addImage($imageName) {
        if (count($this->images) >= $this->imagesLimit)
            return $this->Error(GRP_ERR_IMAGE_UPLOAD_LIMIT);

        if (!empty($_FILES[$imageName]) && !empty($_FILES[$imageName]['name'])) {
            if ($_FILES[$imageName]['error'])
                return $this->Error(GRP_ERR_IMAGE_UPLOAD_ERROR);

            $image = new GroupImage($this->id, count($this->images));
            if ($image->setFile($_FILES[$imageName]['tmp_name']) === null) {
                $this->error = array_merge($this->error, $image->error);
                return null;
            } else {
                $this->images[] = $image;
            } 
            return true;
        } else
            return $this->Error(GRP_ERR_IMAGE_UPLOAD_EMPTY);
    }

    function getPostsCount($visibleOnly = true) {
        if ($visibleOnly) 
            $visibleExpr = "status = 1";
        else
            $visibleExpr = 1;
        $db =& db::getInstance();
        return $db->count('group_posts', "topic = '".$this->_prepareData($this->id)."' && $visibleExpr") + 1;
    }

    function getLastPost() {
        $db =& db::getInstance();
        $id = $db->get_var("SELECT id FROM group_posts WHERE topic = '".$this->_prepareData($this->id)."' ORDER BY created DESC LIMIT 1");
        if ($id)
            $post = new GroupPost($id);
        else
            $post = $this;
        return $post;
    }

    function getPosts(&$pager, $visibleOnly = true) {
        if ($visibleOnly) 
            $visibleExpr = "status = 1";
        else
            $visibleExpr = 1;
        $db = & db::getInstance();
        $limit = $pager->getLimit($this->getPostsCount($visibleOnly));
        $ids = $db->get_col("SELECT id 
                               FROM group_posts
                              WHERE topic = '".$this->_prepareData($this->id)."' &&
                                    $visibleExpr
                           ORDER BY created ASC
                                    $limit");
        $result = array($this);
        if (is_array($ids))
            foreach ($ids as $id)
                $result[] = new GroupPost($id);
        return $result;
    }

    function autoApprove() {
        $group = new Group($this->groupid);
        return ($group->topics_autoapprove || $group->owner == $this->author || $this->topic)
               ? 1
               : 0;
    }

    function save() {
        if ($this->_isValid()) {
            $db = & db::getInstance();
            if ($this->id) { // update
                $db->query("UPDATE group_posts
                               SET groupid = '".$this->_prepareData($this->groupid)."',
                                   topic = '".$this->_prepareData($this->topic)."',
                                   author = '".$this->_prepareData($this->author)."',
                                   subject = '".$this->_prepareData($this->subject)."',
                                   text = '".$this->_prepareData($this->text)."',
                                   created = IF(status = 3, NOW(), created),
                                   status = '".$this->_prepareData($this->status)."'
                             WHERE id = '".$this->_prepareData($this->id)."'");
            } else {
                $db->query("INSERT INTO group_posts
                                    SET groupid = '".$this->_prepareData($this->groupid)."',
                                        topic = '".$this->_prepareData($this->topic)."',
                                        author = '".$this->_prepareData($this->author)."',
                                        subject = '".$this->_prepareData($this->subject)."',
                                        text = '".$this->_prepareData($this->text)."',
                                        created = NOW(),
                                        status = '".$this->_prepareData($this->status)."'");
                $this->id = $db->insert_id;
            }
            $this->_retrieve();
        }
    }

    function build() {
        if ($this->_isValidForBuild()) {
            $db = & db::getInstance();
            $db->query("INSERT INTO group_posts
                                SET groupid = '".$this->_prepareData($this->groupid)."',
                                    topic = '".$this->_prepareData($this->topic)."',
                                    author = '".$this->_prepareData($this->author)."',
                                    created = NOW(),
                                    status = 3");
            $this->id = $db->insert_id;
            return true;
        } else
            return false;
    }

    function delete() {
        $db = & db::getInstance();
        $ids = $db->get_col("SELECT id FROM group_posts WHERE topic = '".$this->_prepareData($this->id)."'");
        if (is_array($ids))
            foreach ($ids as $id) {
                $post = new GroupPost($id);
                $post->delete();
            }
        foreach ($this->images as $image) {
            $image->delete();
        }
        $db->query("DELETE FROM group_posts WHERE id = '".$this->_prepareData($this->id)."'");
    }

    function _isValid() {
        $valid = true;
        switch (true) {
            case !intval($this->groupid):
            case !intval($this->author):
            case empty($this->subject):
            case empty($this->text):
                $valid = false;
        }
        return $valid;
    }

    function _isValidForBuild() {
        $valid = true;
        switch (true) {
            case !intval($this->groupid):
            case !intval($this->author):
                $valid = false;
        }
        return $valid;
    }

    function _prepareText($text) {
        return strip_tags($text, '<a><b><i><u>');
    }

    function _retrieve() {
        if ($this->id) { // load topic data from DB
            $db = & db::getInstance();
            $data = $db->get_row("SELECT id, topic, groupid, author, subject, text, UNIX_TIMESTAMP(created) AS created, status
                                    FROM group_posts WHERE id = '".$this->_PrepareData($this->id)."' LIMIT 1", ARRAY_A);
            if ($data['status'] == 3) // draft
                $this->initDraftByArray($data);
            else
                $this->initByArray($data);
            $this->initImages();
        }
    }
    function _integrityError($fieldName) {
        if ($this->strictIntegrityCheck)
            $this->CriticalError("Integrity fault: $fieldName missing");
        else
            $this->integrityFlaws[] = $fieldName;
    }
}
?>