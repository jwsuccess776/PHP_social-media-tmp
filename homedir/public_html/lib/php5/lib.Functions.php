<?php
function saveFile($data) {
	$path = CONST_INCLUDE_ROOT."/members/000.dat";
    if ($fd = @fopen($path,"w")){
        fwrite($fd,$data);
        fclose($fd);
    } else {
        die ("Can't open file $path. May be it doesn't  exist or you don't have permition to read it.");
    }
}
function getFile($path) {
    if ($fd = @fopen($path,"r")){
        $data = fread($fd,filesize($path));
        fclose($fd);
		return $data;
    } else {
        die ("Can't open file $path. May be it doesn't  exist or you don't have permition to read it.");
    }
}

function createImageFromWebCamera($imgStr, $width, $height, $prefix){
//saveFile($imgStr); 

    $buf = Array();
    $strLen = strlen($imgStr);

    for ($d = 0; $d < $strLen; $d+=4){
        $buf[$d/4] = substr($imgStr, $d, 4);
        $val = $buf[$d/4];
        if($val{0}=='*'){
            $val = substr($val, 1, 3);
        }
        if($val{0}=='*'){
            $val = substr($val, 1, 2);
        }
        if($val{0}=='*'){
            $val = substr($val, 1, 1);
        }
        $num = 1;
        $rad = 64;
        $offset = 0x31;
        for ($i = 0; $i < strlen($val); $i++){
            $num = $num * $rad + ord($val[$i]) - $offset;
        }
        $buf[$d/4] = $num;
    }


    $im = imagecreatetruecolor($width, $height);
    $n = 0;

    for ($xx = 0; $xx < $width; $xx++){
        for ($yy = 0; $yy < $height; $yy++){
            imagesetpixel($im, $xx, $yy, $buf[$n]);
            $n++;
        }
    }

    $result = '/tmp/'.$prefix.'_'.time().'.jpg';
    imagejpeg($im, $result, 90);
    imagedestroy($im);

    return $result;
}
function deslash(&$rValue, $force = false) {
    if (!$force && !(get_magic_quotes_runtime() || get_magic_quotes_gpc())) {
        return;
    }
    if ($rValue === null) return;

    switch (gettype($rValue)) {
        case 'array': {
            $newArray = array ();
            foreach ($rValue as $k => $v) {
                deslash($k, $force);
                deslash($v, $force);
                $newArray[$k] = $v;
            }
            $rValue = $newArray;
            break;
        }
        case 'object': {
            foreach ((array)$rValue as $k => $v) {
                deslash($rValue->$k, $force);
            }
            break;
        }
        default:
            $rValue = stripslashes($rValue);
            break;
    }
}

function FormGet($value){
    if (isset($_POST[$value])) {
        $get_value=$_POST[$value];
        deslash($get_value);
    }
    elseif (isset($_GET[$value])) {
        $get_value=$_GET[$value];
        deslash($get_value);
    }
    elseif (isset($_SESSION[$value])) {
        $get_value=$_SESSION[$value];
    }
    else {
        $get_value=null;
    }
    return $get_value;
}

function dump($var, $caption = "") {
    ob_start();
    print_r($var);
    $content = ob_get_contents();
    ob_end_clean();
    echo htmlentities($caption)."<pre style='font-size: 8pt;'>".htmlentities($content)."</pre>";
}

function isUrl($str) {
    return preg_match("`^http://((\w+)\.)+\w+`", $str);
}

function isEmpty($str) {
    return preg_match("`^\s*$`", $str);
}

function isEmail($str) {
    return preg_match("`^[^@]+ @ [^.]+ (\. [^.]+)+$`x", $str);
}

function isFloat($str) {
    return preg_match("`^(\+|-)? \d+ (\. \d+)?$`x", $str);
}

function isInt($str) {
    return preg_match("`^(\+|-)? \d+$`x", $str);
}

function getPageTemplate($name){
    $language =& Language::GetInstance();
    $pagetemplate =& PTemplateManager::getInstance();
    $p_template = $pagetemplate->Get($name);
    return $p_template->Parse("",$language->LangID);
}

function getTemplateByName($name,$data,$lang_ID){
    $mailtemplate =& MTemplateManager::getInstance();
    if (in_array("Url", array_keys ($data))) {
        $Url = $data['Url'];
        $Url = preg_replace("/((www.)([a-zA-Z0-9@:%_.~#-\?&]+[a-zA-Z0-9@:%_~#\?&\/]))/", "http://\\0", $Url);
        $Url = preg_replace("/(ftp:\/\/|http:\/\/|https:\/\/)(ftp:\/\/|http:\/\/|https:\/\/)([a-zA-Z0-9@:%_.~#-\?&]+[a-zA-Z0-9@:%_~#\?&\/])/", "\\1\\3", $Url);
        $data['Url'] = $Url;
    }
    $m_template = $mailtemplate->Get($name);
    return array($m_template->type,$m_template->Parse($data,$lang_ID));
}

function getDefaultLanguage($mem_id){
    $db =& DB::GetInstance();
    $language =& Language::GetInstance();
    $mem_id = $db->escape($mem_id);
    $query="SELECT lang_id FROM members WHERE mem_userid='$mem_id'";
    $lang = $db->get_var($query);
    return ($lang) ? $lang : $language->LangID;
}
function send_mail  ($to_address,  $from_address,  $subject,  $message, $type, $on_switch, $send_type='outside',$charset='UTF-8',$queue = false)  {

    global $Sess_UserId;
    global $Sess_UserName;
    global $CONST_MAIL;

    if ($send_type == 'inside' || $send_type == 'inside_hidden'){

        $res = mysql_query("SELECT * FROM members WHERE mem_email='$to_address'");
        echo mysql_error();
        if ($receiver = mysql_fetch_assoc($res)){

            $tempdate=date("Y/m/d");
            $message = mysql_escape_string($message);
            $title = mysql_escape_string($title);

            mysql_query("INSERT INTO messages SET
                    msg_senderid    = $Sess_UserId,
                    msg_receiverid  = $receiver[mem_userid],
                    msg_senderhandle= '$Sess_UserName',
                    msg_title   = '$subject',
                    msg_text    = '$message',
                    msg_dateadded = '$tempdate',
                    msg_read = 'U'".(($send_type == 'inside_hidden')?", msg_senderdel = 'Y', msg_delhidesen = 'Y'":""));
        }
    }elseif($send_type == 'outside'){
        if ($queue) {
            require_once __INCLUDE_CLASS_PATH."/class.MailQueue.php";
            $queue = new MailQueue();
            $res = $queue->Init((object)array(
                                        'Email'   => $to_address,
                                        'From'    => $from_address,
                                        'Subject' => $subject,
                                        'Body'    => $message,
                                        'Type'    => $type
                                        )
                        );
            if ($res !== null) {
                $queue->Save();
            }
        } else {
            if ($type == "html" || $type == "text/html") {
                $headers = "MIME-Version: 1.0\n";
                $headers .= "Content-Type: text/html; charset=$charset\n";
                $headers .= "Content-Transfer-Encoding: 8bit\n";
                $headers .= "From: $from_address\n";
                $headers .= "Reply-To: $from_address\n";
                $headers .= "Return-Path: $from_address\n";
            } else {
                $headers = "MIME-Version: 1.0\n";
                $headers = "Content-Type: text/plain; charset=$charset\n";
                $headers .= "From: $from_address\n";
                $headers .= "Reply-To: $from_address\n";
                $headers .= "Return-Path: $from_address\n";
            }

            if ($on_switch=='ON')
            {
                $message = stripslashes($message);
                ini_set("sendmail_from",$CONST_MAIL);
                mail($to_address,$subject,$message,$headers);
            }
        }
    }
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function clearCrop($path){
    $result= array();
    if ($dir = @opendir($path)) {
      while (($file = readdir($dir)) !== false) {
          if (preg_match("/^crop_|^temp_/",$file))
            $result["$path/$file"] = lstat("$path/$file");
      }
      closedir($dir);
    } else {
        die("Can't open crop dir [$path]");
    }
    foreach ($result as $path => $info) {
        if (time()-3600*2 > $info[10] )
            unlink($path);
    }
}

function getTimeShift($date){
	$db =& DB::GetInstance();
    
    $suffix = 'ago';

//    $time_shift = time() - strtotime($date);
    $time_shift = $db->get_var("SELECT unix_timestamp(now()) - unix_timestamp('$date')");

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

function myDate($format,$time) {
 return gmdate($format, $time);
}

?>