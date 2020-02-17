<?php
require_once __INCLUDE_CLASS_PATH."/class.Main.php";
require_once __INCLUDE_CLASS_PATH."/class.GroupLogo.php";
require_once __INCLUDE_CLASS_PATH."/class.GroupPost.php";
require_once __INCLUDE_CLASS_PATH."/class.Adverts.php";

class Group extends Main {
    public $id;
    public $owner;
    public $category;
    public $name;
    public $description_short;
    public $description;
    public $url_name;
    public $image;
    public $country;
    public $state;
    public $city;
    public $is_open;
    public $is_public;
    public $images_allowed;
    public $topics_autoapprove;
    public $status;

    function Group($id = 0) {
        $this->is_open = $this->is_public = $this->images_allowed = true;
        if (intval($id)) {
            $this->id = intval($id);
            $this->_retrieve();
        }
    }

    function initByArray($data) {
        $db = & db::getInstance();

        if (is_array($data)) {
            if (!empty($data['id']) && intval($data['id']))
                $this->id = intval($data['id']);

            if (!empty($data['owner']))
                $this->owner = intval($data['owner']);

            if (empty($data['category']) || !intval($data['category'])) {
                if ($_SESSION['Sess_UserType'] != 'A' &&
                    empty($this->category))
                    $this->Error(GRP_ERR_CATEGORY_UNDEFINED);
            } else
                $this->category = intval($data['category']);

            if (empty($data['name']))
                $this->Error(GRP_ERR_NAME_EMPTY);
            else
                $this->name = $data['name'];

            if (!empty($data['description_short']))
                $this->description_short = $data['description_short'];

            if (!empty($data['description']))
                $this->description = $data['description'];

            if (!$this->_isValidUrlName($data['url_name']))
                $this->Error(GRP_ERR_BAD_URLNAME);
            elseif ($db->count('groups', "url_name = '".$this->_prepareData($data['url_name'])."' && id!='{$this->id}'"))
                $this->Error(GRP_ERR_URLNAME_TAKEN);
            else
                $this->url_name = $data['url_name'];

            if (isset($data['country'])) {
                $this->country = $data['country'];
            } else {
                $this->country = 0;
            }

            if (isset($data['state'])) {
                $this->state = $data['state'];
            } else {
                $this->state = 0;
            }

            if (isset($data['city'])) {
                $this->city = $data['city'];
            } else {
                $this->city = 0;
            }

            $this->is_open = empty($data['is_open']) ? 0 : 1;
            $this->is_public = empty($data['is_public']) ? 0 : 1;
            $this->images_allowed = empty($data['images_allowed']) ? 0 : 1;
            $this->topics_autoapprove = empty($data['topics_autoapprove']) ? 0 : 1;

            $this->status = intval($data['status']);
        }
        if (count($this->error))
            return null;
        else
            return true;
    }

    function uploadImage($imageName) {
        if (!empty($_FILES[$imageName]) && !empty($_FILES[$imageName]['name'])) {
            if ($_FILES[$imageName]['error'])
                return $this->Error(GRP_ERR_IMAGE_UPLOAD_ERROR);

            $image = new GroupLogo($this->id);
            if ($image->setFile($_FILES[$imageName]['tmp_name']) === null) {
                $this->error += $image->error;
                return null;
            } else
                $this->image = $image;
        }
        return true;
    }

    static function autoApproveGroup() {
        $option_manager =& OptionManager::GetInstance();
        return $option_manager->getValue('groups_autoapprove')
               ? 1  // approved status
               : 0; // new status
    }

    function autoApproveJoin($id) {
        switch (true) {
            case $this->isRejected($id):
                return 2;
            case $this->isMember($id):
            case $this->is_open:
                return 1;
            default:
                return 0;
        }
    }

    function getLocationString() {
        $locationString = '';
        $db = & db::getInstance();
        if ($this->country) {
            $locationString = $db->get_var("SELECT gcn_name FROM geo_country WHERE gcn_countryid = '".$this->_prepareData($this->country)."'");
            if ($this->state) {
                $locationString .= ', '.$db->get_var("SELECT gst_name
                                                        FROM geo_state
                                                       WHERE gst_countryid = '".$this->_prepareData($this->country)."' &&
                                                             gst_stateid = '".$this->_prepareData($this->state)."'");
            }
            if ($this->city) {
                $locationString .= ', '.$db->get_var("SELECT gct_name
                                                        FROM geo_city
                                                       WHERE gct_countryid = '".$this->_prepareData($this->country)."' &&
                                                             gct_stateid = '".$this->_prepareData($this->state)."' &&
                                                             gct_cityid = '".$this->_prepareData($this->city)."'");
            }
        }
        return $locationString;
    }

    function getMembersCount($visibleOnly = true, $visibleStatus = 1) {
        if ($visibleOnly)
            $visibleExpr = "status = $visibleStatus";
        else
            $visibleExpr = 1;
        $db = & db::getInstance();
        return $db->get_var("SELECT count(memberid)
                               FROM member2group
                         INNER JOIN members
                                 ON memberid = mem_userid
                              WHERE groupid = '".$this->_prepareData($this->id)."' &&
                                    memberid != '$this->owner' &&
                                    $visibleExpr
                                ");
    }

    function isMember($userid) {
        $db = & db::getInstance();
        if ($this->owner == $userid ||
            $db->count('member2group', "memberid = '".$this->_prepareData($userid)."' && groupid = '$this->id' && status = 1"))
            return true;
        else
            return false;
    }

    function isRejected($userid) {
        $db = & db::getInstance();
        if ($db->count('member2group', "memberid = '".$this->_prepareData($userid)."' && groupid = '$this->id' && status = 2"))
            return true;
        else
            return false;
    }

    function getSomeMembers($count = 10) {
        $members = $this->findMembers("status = 1 && memberid != '$this->owner'", 9);
        $members = array_merge(array(new Adverts($this->owner)), $members); // add group owner
        return $members;
    }

    function getMembers(&$pager, $visibleOnly=true, $visibleStatus = 1) {
        if ($visibleOnly)
            $visibleExpr = "status = $visibleStatus";
        else
            $visibleExpr = 1;
        $limit = $pager->getLimit($this->getMembersCount($visibleOnly, $visibleStatus));
        $db = & db::getInstance();
        $ids = $db->get_col("SELECT memberid
                               FROM member2group
                         INNER JOIN members
                                 ON memberid = mem_userid
                              WHERE groupid = '".$this->_prepareData($this->id)."' &&
                                    $visibleExpr
                           ORDER BY mem_username
                                    $limit");
        $result = array();
        if (is_array($ids))
            foreach ($ids as $id)
                $result[] = new Adverts($id);
        return $result;
    }

    function findMembers($search = "1", $count = 0) {
        $db = & db::getInstance();
        $limit = $count > 0 ? "LIMIT 0, $count" : "";
        $ids = $db->get_col("SELECT DISTINCT memberid 
                             FROM member2group 
                             INNER JOIN members
                                     ON memberid = mem_userid
                             WHERE groupid = '".$this->_prepareData($this->id)."' && $search $limit");

        return $this->returnResult($ids, $count, 'Adverts');
    }

    function addMember($id) {
        $db = & db::getInstance();
        $status = $this->autoApproveJoin($id);
        $db->query("INSERT IGNORE INTO member2group
                                   SET memberid = '".$this->_prepareData($id)."',
                                       groupid = '".$this->_prepareData($this->id)."',
                                       status = '$status'");
        if ($status == 0) {
            // send notification to owner
        }
        return $status;
    }

    function removeMember($id) {
        $db = & db::getInstance();
        if ($id != $this->owner)
        $db->query("DELETE FROM member2group
                          WHERE memberid = '".$this->_prepareData($id)."' &&
                                groupid = '".$this->_prepareData($this->id)."'");
        return $status;
    }

    function setMemberStatus($id, $status) {
        $db = & db::getInstance();
        $db->query("UPDATE member2group
                       SET status = '".$this->_prepareData($status)."'
                     WHERE groupid = '".$this->_prepareData($this->id)."' &&
                           memberid = '".$this->_prepareData($id)."'");
    }

    function getTopics(&$pager, $visibleOnly = true) {
        if ($visibleOnly)
            $visibleExpr = "p1.status = 1";
        else
            $visibleExpr = 1;
        $db = & db::getInstance();
        $limit = $pager->getLimit($this->getTopicsCount($visibleOnly));
        $ids = $db->get_col("SELECT p1.id, MAX(IF(p2.created IS NULL, p1.created ,p2.created)) AS updated
                               FROM group_posts p1
                          LEFT JOIN group_posts p2
                                 ON p2.topic = p1.id
                         INNER JOIN members
                                 ON mem_userid = p1.author
                              WHERE p1.groupid = '".$this->_prepareData($this->id)."' &&
                                    p1.topic = 0 &&
                                    $visibleExpr
                           GROUP BY p1.id
                           ORDER BY updated DESC
                                    $limit");
        $result = array();
        if (is_array($ids))
            foreach ($ids as $id)
                $result[] = new GroupPost($id);
        return $result;
    }

    function getTopicsCount($visibleOnly = true, $visibleStatus = 1) {
        if ($visibleOnly)
            $visibleExpr = "status = $visibleStatus";
        else
            $visibleExpr = 1;
        $db = & db::getInstance();
        $result = $db->get_var("SELECT COUNT(*) AS num 
                                  FROM group_posts 
                            INNER JOIN members
                                    ON mem_userid = author
                                 WHERE groupid = '".$this->_prepareData($this->id)."' && 
                                       topic = 0 && 
                                       $visibleExpr");
        return $result;
    }

    static function count($search = "1") {
        $db = & db::getInstance();
        return $db->count('groups', $search);
    }

    static function find($search = "1", $count = 0) {
        $db = & db::getInstance();
        $limit = $count > 0 ? "LIMIT $count" : "";
        $ids = $db->get_col("SELECT DISTINCT id FROM groups WHERE $search ORDER BY name $limit");
        return Group::returnResult($ids, $count, 'Group');
    }

    static function findByMember($id, $count = 0, $visibleOnly = true) {
        if ($visibleOnly)
            $visibleExpr = "g.status = 1";
        else
            $visibleExpr = 1;
        $limit = $count > 0 ? "LIMIT 0, $count" : "";
        $db = & db::getInstance();
        $ids = $db->get_col("SELECT g.id
                               FROM groups g
                          LEFT JOIN member2group m2g
                                 ON m2g.groupid = g.id
                              WHERE g.owner = '".Main::_StaticPrepareData($id)."'  ||
                                    (m2g.memberid = '".Main::_StaticPrepareData($id)."' &&
                                     m2g.status = 1 &&
                                     $visibleExpr)
                                    $limit");
        return Group::returnResult($ids, $count, 'Group');
    }

    static function findByName($name) {
        $db = & db::getInstance();
        $group = 0;
        if ($db->count('groups', "url_name = '".Main::_StaticPrepareData($name)."' && status = 1"))
            $group = Group::find("url_name = '".Main::_StaticPrepareData($name)."' && status = 1", 1);
        return $group;
    }

    static function returnResult($ids, $count, $class) {
        if ($count == 1) {
            return count($ids) ? new $class($ids[0]) : '';
        } else {
            $result = array();
            if (is_array($ids))
                foreach ($ids as $id)
                    $result[] = new $class($id);
            return $result;
        }
    }

    function save() {
        if ($this->_isValid()) {
            $db = & db::getInstance();
            if ($this->id) { // update existing record
                $db->query("UPDATE groups
                               SET owner = '$this->owner',
                                   category = '$this->category',
                                   name = '".$this->_prepareData($this->name)."',
                                   description = '".$this->_prepareData($this->description)."',
                                   description_short = '".$this->_prepareData($this->description_short)."',
                                   url_name = '$this->url_name',
                                   country = '".$this->_prepareData($this->country)."',
                                   state = '".$this->_prepareData($this->state)."',
                                   city = '".$this->_prepareData($this->city)."',
                                   is_open = '$this->is_open',
                                   is_public = '$this->is_public',
                                   images_allowed = '$this->images_allowed',
                                   topics_autoapprove = '$this->topics_autoapprove',
                                   status = '$this->status'
                             WHERE id = '".$this->_prepareData($this->id)."'");
            } else { // create new record
                $db->query("INSERT INTO groups
                                    SET owner = '$this->owner',
                                        category = '$this->category',
                                        name = '".$this->_prepareData($this->name)."',
                                        description = '".$this->_prepareData($this->description)."',
                                        description_short = '".$this->_prepareData($this->description_short)."',
                                        url_name = '$this->url_name',
                                        country = '".$this->_prepareData($this->country)."',
                                        state = '".$this->_prepareData($this->state)."',
                                        city = '".$this->_prepareData($this->city)."',
                                        is_open = '$this->is_open',
                                        is_public = '$this->is_public',
                                        images_allowed = '$this->images_allowed',
                                        topics_autoapprove = '$this->topics_autoapprove',
                                        status = '$this->status'");
                $this->id = $db->insert_id;
                if ($this->image)
                    $this->image->setGroup($this->id);
            }
            $this->_retrieve();
        }
    }

    function delete() {
        $db = & db::getInstance();
        $db->query("DELETE FROM groups WHERE id = '".$this->_prepareData($this->id)."'");
    }

    function _isValidUrlName($url_name) {
        $valid = true;
        switch (true) {
            case empty($url_name):
            case !preg_match("'^[0-9a-z_]+$'i", $url_name):
                $valid = false;
        }
        return $valid;
    }

    function _isValid() {
        $valid = true;
        switch (true) {
            case !intval($this->owner):
            case empty($this->name):
            case empty($this->url_name):
            case !$this->_isValidUrlName($this->url_name):
                $valid = false;
        }
        return $valid;
    }
    function _retrieve() {
        if ($this->id) { // load group data from DB
            $db = & db::getInstance();
            $data = $db->get_row("SELECT * FROM groups WHERE id = '".$this->_PrepareData($this->id)."' LIMIT 1", ARRAY_A);
            $this->initByArray($data);

            $this->image = new GroupLogo($this->id);
        }
    }
    static function clearDrafts() {
        $db = & db::getInstance();
        $ids = $db->get_col("SELECT id FROM group_posts WHERE status = 3 && created < NOW() - INTERVAL 1 DAY");
        if (is_array($ids))
            foreach ($ids as $id) {
                $draft = new GroupPost($id, false); // loose integrity check
                $draft->delete();
            }
    }
}
?>