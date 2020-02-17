<?php
include_once __INCLUDE_CLASS_PATH."/class.Main.php";
include_once __INCLUDE_CLASS_PATH."/class.Pager.php";
include_once CONST_INCLUDE_ROOT."/functions.php";

class Banner extends Main {
    public $id;

    function Banner($id = 0) {
        if ($id) $this->id = $id;
    }

    static function getByID($id) {
        $db =& db::getInstance();
        $result = $db->get_row("SELECT * FROM bannercodes WHERE banner_id = '".Main::_PrepareData($id)."'");
        return $result;
    }

    static function getList($size, $order = 'bc.size, bannerName', $page = 0) {
        $db =& db::getInstance();
        $pager = new Pager($page);
        $sizeWhereExpr = $size ? "bc.size = '".Main::_PrepareData($size)."'" : 1;
        $num = $db->get_var("SELECT COUNT(bc.banner_id) FROM bannercodes bc WHERE $sizeWhereExpr");
        $result = $db->get_results("SELECT banner_id,
                                           bc.size,
                                           bcf.label AS bannerFormat,
                                           bc.label AS bannerName,
                                           is_active
                                      FROM bannercodes bc
                                 LEFT JOIN bannercodeformats bcf
                                        ON bc.size = bcf.size
                                     WHERE $sizeWhereExpr
                                  ORDER BY ".Main::_PrepareData($order)."
                                           ".$pager->GetLimit($num));
        return $result;
    }

    static function getSizeOptions() {
        $db =& db::getInstance();
        $tmp = $db->get_results("SELECT * FROM bannercodeformats ORDER BY label");
        $result = array();
        foreach ($tmp as $format)
            $result[$format->size] = $format->label;
        return $result;
    }

    function save($size, $label, $code, $hits, $active) {
        $db =& db::getInstance();
        if (!$size) return $this->Error('Undefined banner format');
        if (!$label) return $this->Error('Empty banner name');
        if (!$code) return $this->Error('Empty banner name');
        if (!$hits) $hits = -1;
        if ($active) $active = 1;
        if (!$this->id) {
            $db->query("INSERT INTO bannercodes SET size = ''");
            $this->id = $db->insert_id;
        }
        $db->query("UPDATE bannercodes 
                       SET size = '".$this->_PrepareData($size)."',
                           label = '".$this->_PrepareData($label)."',
                           code = '".$this->_PrepareData($code)."',
                           hits = '".$this->_PrepareData($hits)."',
                           is_active = '$active'
                     WHERE banner_id = '".$this->_PrepareData($this->id)."'");
        return true;
    }

    static function displayBanner($size, $print = true, $count = true) {
        $db =& db::getInstance();
        $ids = $db->get_col("SELECT banner_id 
                               FROM bannercodes
                              WHERE size = '$size' &&
                                    is_active = 1 &&
                                    hits != 0");
        if (!count($ids)) return null; // no banners to display
        $id = $ids[rand(1, count($ids))-1];

        $code = $db->get_var("SELECT code FROM bannercodes WHERE banner_id = '$id'");
        if ($print) echo $code;
        if ($count) {
            $db->query("UPDATE bannercodes SET hits = hits - 1 WHERE banner_id = '$id' && hits > 1");
            $db->query("INSERT IGNORE INTO bannerstats SET banner_id = '$id', statdate = date_format(NOW(),'%Y-%m-%d')");
            $db->query("UPDATE bannerstats SET hits = hits + 1 WHERE banner_id = '$id' AND statdate= date_format(NOW(),'%Y-%m-%d')");
        }
        return $code;
    }

    function report($year, $month) {
        $db =& db::getInstance();
        $stamp = $year.str_pad($month, 2, '0', STR_PAD_LEFT);
        $result = $db->get_results("SELECT bc.banner_id,DATE_FORMAT(bs.statdate, '%d %M %Y') AS statdatePrint,
                                           bc.label AS bannerName,
                                           bcf.label AS bannerSize,
                                           SUM(bs.hits) AS hits
                                      FROM bannerstats bs
                                INNER JOIN bannercodes bc
                                        ON bc.banner_id = bs.banner_id
                                INNER JOIN bannercodeformats bcf
                                        ON bcf.size = bc.size
                                     WHERE EXTRACT(YEAR_MONTH FROM bs.statdate) = '$stamp'
                                  GROUP BY bc.banner_id,statdate");
        return $result;
    }
}
?>