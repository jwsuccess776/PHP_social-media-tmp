<?
include('db_connect.php');
require_once ( __INCLUDE_CLASS_PATH . '/class.Video.php' );

if ($video_id = formGet('video_id')) {
    $video = new Video();
    $video->initByID($video_id);
    $video->addView($Sess_UserId); 
    $fp = fopen(CONST_INCLUDE_ROOT.$video->File->Path, 'rb');

    // send the right headers
    header("Content-Type: application/octet-stream");
    header("Content-Length: " . filesize(CONST_INCLUDE_ROOT.$video->File->Path));

    fpassthru($fp);
    exit;
}
?>