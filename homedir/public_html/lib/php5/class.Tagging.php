<?
require_once(__INCLUDE_CLASS_PATH."/class.Main.php");
require_once(__INCLUDE_CLASS_PATH."/class.Tag.php");

class Tagging extends Main{

    var $ent_type;
    var $allowed_types = array (
                                "video",
                                "blog",
                               );


    /**
     * Create tagging object for pointed type of entity
     * @param string $type
     * @return int
     * @access public
     */

    function Tagging($type){
        if (in_array($type, $this->allowed_types)) {
            $this->ent_type = $type;
        } else {
            return $this->criticalError("Incorrect entity type [$type]. Allowed types are ".join(", ",$this->allowed_types));
        }
    }

    /**
     * Set tags for entity
     * @param int $ent_id
     * @param array $tags
     * @return boolean
     * @access public
     */

    function set($ent_id, $tags){
        if (!is_array($tags)) return $this->criticalError("Incorrect type for argument 1. It should be array");
        if (!$ent_id) return $this->criticalError("Incorrect entity ID [$ent_id]");

        $db = & db::getInstance();
        foreach ($tags as $value) {
            $tag = new Tag();
            $tag->initByTag ($value);
            if ($tag->id) {
                $valid_tags[] = $tag->id;
            }  else {
                return $this->error($tag->errors);
            }
        }

        $db->query("DELETE FROM tag_links
                    WHERE
                        ent_id = '".$db->escape($ent_id)."'
                    AND
                        ent_type = '".$db->escape($this->ent_type)."'");

        foreach ($valid_tags as $tag_id) {
            $db->query("REPLACE INTO tag_links SET
                            ent_id = '".$db->escape($ent_id)."',
                            tag_id = '$tag_id',
                            ent_type = '".$db->escape($this->ent_type)."'
                    ");
        }
        return true;
    }

    /**
     * Get tags list
     * @param int $ent_id
     * @return array
     * @access public
     */

    function getTagsList($ent_id, $mode ='array'){
        $tags = $result = array();
        if (!$ent_id) return $this->criticalError("Incorrect entity ID [$ent_id]");

        $db = & db::getInstance();
        $list = $db->get_results("  SELECT id, tag
                                    FROM tag_links
                                    INNER JOIN tag ON (tag_id = id)
                                    WHERE
                                        ent_id = '".$db->escape($ent_id)."'
                                    AND
                                        ent_type = '".$db->escape($this->ent_type)."'
                            ");
        foreach ($list as $obj) {
            $tag = new Tag();
            $result[] = $tag->initByObj($obj);
        }

        if ($mode  == 'array') {
            return $result;
        } else {
            foreach ($result as $tag) $tags[] = $tag->tag;
            return join(", ", $tags);
        }
    }

    /**
     * Get entities list
     * @param int $tag_id
     * @return array
     * @access public
     */

    function getEntList($tag_id, $table, $id, &$pager, $filter='', $order='', $extra_jion = '', $limit= ''){
        if (!$tag_id) return $this->criticalError("Incorrect tag ID [$ent_id]");
        if (!$table) return $this->criticalError("Incorrect table [$table]");
        if (!$id) return $this->criticalError("Incorrect ID for joined table [$id]");
//        if (!$limit) return $this->criticalError("Empty limit value [$limit]");
        $order = ($order) ? " ORDER BY $order " : "";
        $where = ($filter) ? " AND $filter " : '';

        $db = & db::getInstance();
        if (get_class($pager) == 'pager') {
            $count = $db->get_var(" SELECT count(*)
                                        FROM tag_links
                                        INNER JOIN $table ON (ent_id = $id)
                                        $extra_jion
                                        WHERE
                                            tag_id = '".$db->escape($tag_id)."'
                                        AND
                                            ent_type = '".$db->escape($this->ent_type)."'
                                        $where
                                ");

            $limit = $pager->GetLimit($count);
        } else {
            $limit = ($limit) ? "LIMIT $limit" : '';
        }
        $list = $db->get_results("  SELECT  *
                                    FROM tag_links
                                    INNER JOIN $table ON (ent_id = $id)
                                    $extra_jion
                                    WHERE
                                        tag_id = '".$db->escape($tag_id)."'
                                    AND
                                        ent_type = '".$db->escape($this->ent_type)."'
                                    $where
                                    $order
                                    $limit
                            ");
        return $list;
    }

    /**
     * Get entities list by current entity's tags list
     * @param int $ent_id
     * @return array
     * @access public
     */

    function getRelativsEntList($ent_id, $table, $id, $filter='', $order='', $limit=5){
        $db = & db::getInstance();

        if (!$ent_id) return $this->criticalError("Incorrect tag ID [$ent_id]");
        if (!$table) return $this->criticalError("Incorrect table [$table]");
        if (!$id) return $this->criticalError("Incorrect ID for joined table [$id]");
        if (!$limit) return $this->criticalError("Empty limit value [$limit]");

        $order_ext = " ORDER BY tags DESC ".(($order) ? " ,$order DESC " : "");
        $where = ($filter) ? " AND $filter " : '';

        foreach ((array) $this->getTagsList($ent_id) as $obj) {
            $tags[] = $obj->id;
        }

        if (count($tags) == 0) return array();

        $list = $db->get_results("  SELECT ent_id, count(*) AS tags, $order
                                    FROM tag_links
                                        INNER JOIN $table ON (ent_id = $id)
                                    WHERE
                                        tag_id IN ('".join("','",$tags)."')
                                    AND
                                        ent_id != $ent_id
                                    AND
                                        ent_type = '".$db->escape($this->ent_type)."'
                                    $where
                                    GROUP BY ent_id
                                    $order_ext
                                    LIMIT 0, $limit");
        return $list;
    }

    /**
     * Get tags ordered by count
     * @param int $limit
     * @param int $scale
     * @return array
     * @access public
     */

    function getCloud($limit = null, $scale = 2) {
        $db = & db::getInstance();
        $result = $scale_res =  array();

        $limit = empty($limit) ? '' : "LIMIT 0, $limit";
        $result = $db->get_hash("SELECT t.tag, count(tl.ent_id) AS total 
                                 FROM tag t LEFT JOIN tag_links tl ON t.id = tl.tag_id  AND tag != '' AND tag != ' '
                                 GROUP BY t.id 
                                 HAVING total != 0
                                 ORDER BY total DESC $limit");
        $total = array_sum((array)$result);
	if ($result) {
            $max = each($result);
            $max = $max['value'];
            $min = end($result);
            $step = ($max - $min) / $scale;

            $scale_res[1] = $min;

            for ($i = 2; $i<=$scale; $i++)
                $scale_res[$i] = $min + $step*$i;

            ksort($result);
	}
        return array($scale_res, $result);
    }


    /**
     * Break link between tag and entity
     *
     * @access public
     */
    function Delete($ent_id){
        $db = & db::getInstance();
        $db->query("DELETE FROM tag_links WHERE ent_id = '$ent_id' AND ent_type = '$this->ent_type'");
        return $result;
    }

}

?>