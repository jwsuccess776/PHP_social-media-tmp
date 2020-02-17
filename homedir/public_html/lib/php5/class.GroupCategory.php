<?php
require_once __INCLUDE_CLASS_PATH."/class.Main.php";

class GroupCategory extends Main {
    public $id = 0;
    public $name = '';
    public $parent = 0;
    public $visible = true;

    function GroupCategory($id = 0) {
        if (intval($id)) {
            $this->id = intval($id);
            $this->_retrieve();
        }
    }

    function initByObject($obj) {
        $this->id = intval($obj->id);
        $this->name = $obj->name;
        $this->parent = intval($obj->parent);
        $this->visible = $obj->visible;
    }

    function getChilds($visibleOnly = true) {
        if ($visibleOnly)
            $visibleExpr = "visible = 1";
        else
            $visibleExpr = 1;
        $db = & db::getInstance();
        $ids = $db->get_col("SELECT id
                               FROM group_categories
                              WHERE parent = '".$this->_PrepareData($this->id)."' &&
                                    $visibleExpr
                           ORDER BY name");
        $childs = array();
        if (is_array($ids))
            foreach ($ids as $id) {
                $childs[] = new GroupCategory($id);
            }
        return $childs;
    }

    function childExists($name) {
        $db = & db::getInstance();
        return $db->count('group_categories', "parent = '{$this->id}' && name = '".$this->_PrepareData($name)."'")
            ? true
            : false;
    }

    function hasChilds() {
        $db = & db::getInstance();
        return $db->count('group_categories', "parent = '{$this->id}'")
            ? true
            : false;
    }

    function isChild($id) {
        $db = & db::getInstance();
        return $db->count('group_categories', "id = '".$this->_prepareData($id)."' && parent = '{$this->id}'")
            ? true
            : false;
    }

    function addChild($name) {
        $child = new GroupCategory();
        $child->parent = $this->id;
        $child->name = $name;
        $child->save();
    }

    function deleteChild($id) {
        if ($this->isChild($id)) {
            $child = new GroupCategory($id);
            $child->delete();
        }
    }

    function hasGroups() {
        $db = & db::getInstance();
        if ($db->count('groups', "category = '{$this->id}'"))
            return true;
        $childs = $this->getChilds();
        foreach ($childs as $child)
            if ($child->hasGroups())
                return true;
        return false;
    }

    function getGroups(&$pager, $visibleOnly = true) {
        if ($visibleOnly)
            $visibleExpr = "status = 1";
        else
            $visibleExpr = 1;
        $limit = $pager->getLimit($this->getGroupsCount($visibleOnly));
        $db = & db::getInstance();
        $ids = $db->get_col("SELECT id
                               FROM groups
                              WHERE category = '".intval($this->id)."' &&
                                    $visibleExpr
                           ORDER BY status, name
                                    $limit");
        $groups = array();
        if (is_array($ids))
            foreach ($ids as $id) {
                $groups[] = new Group($id);
            }
        return $groups;
    }

    function getGroupsCount($visibleOnly = true) {
        if ($visibleOnly)
            $visibleExpr = "status = 1";
        else
            $visibleExpr = 1;
        $db = & db::getInstance();
        return $db->count('groups', "category = '".intval($this->id)."' && $visibleExpr");
    }

    function getPath($linkTo = '') {
        if (!$this->id) return; // no name for root
        $path = $this->name;
        if ($linkTo)
            $path = '<a href="'.$linkTo.$this->id.'">'.$path.'</a>';
        $parent = new GroupCategory($this->parent);
        $path = $parent->getPath($linkTo).' &gt; '.$path;
        return $path;
    }

    function save() {
        if ($this->_isValid()) {
            $db = & db::getInstance();
            if ($this->id) { // updating existing record
                $db->query("UPDATE group_categories
                               SET name = '".$this->_prepareData($this->name)."',
                                   parent = '{$this->parent}',
                                   visible = '{$this->visible}'
                             WHERE id = '{$this->id}'");
            } else { // creating new record
                $db->query("INSERT INTO group_categories
                                    SET name = '".$this->_prepareData($this->name)."',
                                        parent = '{$this->parent}',
                                        visible = '{$this->visible}'");
                $this->id = $db->insert_id;
            }
            $this->_retrieve();
        }
    }

    function okToDelete() {
        if ($this->hasGroups())
            return $this->error(sprintf(GRP_ERR_HAS_GROUPS, $this->name));
        return true;
    }

    function delete() {
        if ($this->id) {
            if ($this->hasGroups())
                return $this->Error(sprintf(GRP_ERR_HAS_GROUPS, $this->name));
            $db = & db::getInstance();
            $db->query("DELETE FROM group_categories WHERE id = '{$this->id}'");
        }
        return true;
    }

    function deleteBranch() {
        if ($this->id) {
            $childs = $this->getChilds();
            foreach ($childs as $child)
                $child->delete();
            $groups = $this->getGroups();
            foreach ($groups as $group)
                $group->delete();
            $db = & db::getInstance();
            $db->query("DELETE FROM group_categories WHERE id = '{$this->id}'");
        }
    }

    function _retrieve() {
        if ($this->id) { // load category data from DB
            $db = & db::getInstance();
            $data = $db->get_row("SELECT id, name, parent, visible FROM group_categories WHERE id = '".$this->_PrepareData($this->id)."' LIMIT 1");
            $this->initByObject($data);
        }
    }

    function _isValid() {
        $valid = true;
        if (empty($this->name)) $valid = false;
        return $valid;
    }
}
?>