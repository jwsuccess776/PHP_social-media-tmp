<?php
//session_start();
$config = "../../db_connect.php";
include_once('../../validation_functions.php');
require_once ($config);
global $DB;

$user_id =sanitizeData($_REQUEST['id'], 'xss_clean');  
$target_dir = "upload/";
$uniq_image_id = time();
$extension = explode(".", basename($_FILES["fileupload"]["name"]));
$ext = end($extension);

$newfilename= $user_id.'_'.$uniq_image_id .".".$ext;

$target_file = $target_dir . $newfilename ;

$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    $check = getimagesize($_FILES["fileupload"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }
}
// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}

// Allow certain file formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" && $imageFileType != "JPG" && $imageFileType != "PNG" && $imageFileType != "JPEG" ) {
    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
    $uploadOk = 0;
}
// Check if $uploadOk is set to 0 by an error
if ($uploadOk == 0) {
    echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
} else {
    if (move_uploaded_file($_FILES["fileupload"]["tmp_name"], $target_file)) {
        //$getAlbum = $DB->Row("SELECT `aid` FROM album WHERE uid='".$user_id."'");
       
        //$DB->Insert("INSERT INTO `chat_photos` (`id`,`aid`,`user`,`uid`,`date`,`title`,`description`,`bigimage`,`width`,`height`,`filesize`,`views`,`medwidth`,`medheight`,`medsize`,`approved`,`rating`,`default`,`featured`,`type`,`rating_votes`,`adult_content`) VALUES('','0','".$_SESSION['to_id']."','".$user_id."','".date('Y-m-d')."','Chat Photo','Chat Photo','inc/templates/v22_design/chat_system/web/upload/".$newfilename."','0','0','0','0','0','0','0','yes','0.00','no','no','photo','0','no')");
        echo $newfilename;
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>