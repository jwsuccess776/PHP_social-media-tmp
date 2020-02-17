<?php
        $pics=$db->get_var("SELECT * FROM hotlist WHERE hot_userid = $userid AND hot_advid = $Sess_UserId AND hot_private='Y'");
        //$show_private=mysql_num_rows($pics);
         $show_private=$pics;
        include_once __INCLUDE_CLASS_PATH."/class.Picture.php";
        $picture = new Picture();
        $aPicture=$picture->GetListByMember($userid, 'showall');
        $no_of_pics = count($aPicture);
        if ($no_of_pics > 0) {
            $pic_no=0;
            foreach ($aPicture as $sql_pic_array) {
                $pic_no++;
                if($show_private || $sql_pic_array->pic_private != 'Y')
                {
                    $medium = $sql_pic_array->GetInfo('medium');
                    $full = $sql_pic_array->GetInfo('');
                    $url_src .= ",\"$CONST_LINK_ROOT$full->Path\"";
                    $img_src .= ",'$CONST_LINK_ROOT$medium->Path'";
                    $dimensions = "width=$medium->w";
                }
                else
                {
                    $img_src .= ",'$CONST_LINK_ROOT$sql_pic_array->private_file'";
                    $url_src .= ",\"#\"";
                    $dimensions = "width=".CONST_THUMBS_MEDIUM_W;
                }
            }
            $loading_img_url = "$CONST_IMAGE_ROOT"."$CONST_IMAGE_LANG/photo_loading.gif";
            ?>
            <script language=javascript>
                function show_image(name,name1,cur_image_num){
                    url = eval(name + '_img_src[' + cur_image_num + ']');
                    url1 = eval(name + '_url_src[' + cur_image_num + ']');
                    img = document.getElementById(name);
                    img.src = loading_img_url;
                    link_url = document.getElementById(name1);
                    link_url.href = url1;
                    window.setTimeout('document.getElementById(\'' + name + '\').src = \'' + url + '\'', 1);
                }
                mainpicture_img_src = new Array(0<?=$img_src?>);
                mainpicture_url_src = new Array(0<?=$url_src?>);
                loading_img_url = '<?=$loading_img_url?>';
                imgLoading = new Image();
                imgLoading.src = loading_img_url;
            </script> 
                    <table width="100%" height="" border="0" cellpadding="2" cellspacing="0">
                        <tr>
                          <td valign="middle" > <table  border="0" cellspacing="0" cellpadding="0">
                        <tr>
                            <td class="imageframe"><a rel='lightbox' id="bigpicture" href="<?=$sql_pic_array->pic_picture?>"><img id=mainpicture name='pic' border=0 src='<?=$loading_img_url?>?<?=time() ?>' <?=$dimensions?>></a></td>
                        </tr>
                    </table>
                </td>
                </tr>
                <tr>
                   <td height="20" align="center" valign="bottom" >
                        <?php for ($i = 1; $i<= $no_of_pics; $i++) { ?>
                        <a href="#" onClick="show_image('mainpicture','bigpicture',<?=$i?>)">
                            <img src="<?=$CONST_IMAGE_ROOT?>/<?=$CONST_IMAGE_LANG?>/picture.gif" border=0></a>&nbsp;
                        <?php } ?>
<?php
    if ($CONST_VIDEOS == 'Y') {
        include_once __INCLUDE_CLASS_PATH."/class.Video.php";
        $video = new Video();
        $aVideo=$video->GetListByMember($userid, 'converted');
        if (count($aVideo) > 0) {
?>
	    <a href="<?=CONST_LINK_ROOT?>/video_list.php?userid=<?=$userid?>" ><img border='0' src="<?=$CONST_IMAGE_ROOT?>/<?=$CONST_IMAGE_LANG?>/video.gif"></a>
<?php
        }
    }
?>
<?php
    if ($CONST_AUDIOS == 'Y') {
        include_once __INCLUDE_CLASS_PATH."/class.Audio.php";
        $audio = new Audio();
        $aAudios=$audio->GetListByMember($userid);
        if (count($aAudios) > 0) {
            $sql_audios = array_shift($aAudios);
            $aud_info = $sql_audios->File->getInfo('medium');
            if ($sql_audios->aud_private == 'N' || $show_private) {
               print("<a href=\"javascript:MDM_openWindow('$CONST_LINK_ROOT$aud_info->Path','','width=400,height=80,toolbar=no,titlebar=no,location=no,directories=no,status=no,menubar=no,copyhistory=no')\"><img src='$CONST_LINK_ROOT$sql_audios->title_file' border=0></a>");
            } else {
               print("<img src='$CONST_LINK_ROOT$sql_audios->private_file' border=0>");
            }
        }
    }
?>

                   </td>
                    </tr>
                  </table>
                  <script language=javascript>
                cur_image_num = 1;
                show_image('mainpicture','bigpicture',cur_image_num);
            </script>
                  <?php } else { ?>
                  <table  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td class="imageframe"><img id=mainpicture name='pic' border=0 src='<?=$CONST_LINK_ROOT?><?=$sql_array->adv_picture->Path?>'></td>
                    </tr>
                  </table>
                  <?php } ?>

