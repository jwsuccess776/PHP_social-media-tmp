<div class="search_result">

  <div class="resulthead">

    <?php if ($SCRIPT_NAME == 'my_interests.php' || $SCRIPT_NAME == 'interested_in_me.php'){ ?>

    <div style="float:right;width:40px;">

      <input type='checkbox' name=chkDelete[] value=<?=$sql_array->adv_userid?>>

      <?=BUTTON_REMOVE?>

    </div>

    <?php } ?>

   

	<a href='<?=$CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$sql_array->adv_userid?>'>

    <?=$sql_array->adv_username?>

    </a> 
      <span class="resultstatus"><?php if($sql_array->online=="Online")
      {  ?>
          <img src="<?php echo $CONST_LINK_ROOT ?>/imageslightbulb-on.png"/>
      <?php }
      else {
          ?>
          <img src="<?=$CONST_LINK_ROOT?>/images/lightbulb-off.png"/>
      <?php 
      }?>
           </span>
  </div>

  <div class="resultbody">

     
      <div class="resultimage" valign="top" >
            <a href="<?=$CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$sql_array->adv_userid?>" class="searchimageframe">
                <img border='0' src='<?=$CONST_LINK_ROOT?><?=$sql_array->adv_picture->Path?>?<?=time()?>' width=<?=$sql_array->adv_picture->w?>></a>
        </div>
      
      <div><a><?php echo $sql_array->age." ".SEARCH_AGELOCALITY;?> 
             <?php if($sql_array->mem_sex=="F") $sex= GENDER_W;
             else if($sql_array->mem_sex=="M") $sex= GENDER_M;
             else $sex= GENDER_C; 
             echo $sex.' '. strtolower(GENERAL_FROM);
             ?></a>
            </div>
    
          <div><a><?=$sql_array->gcn_name?></a></div>
     
           
 

        <div><?php if (isset($Sess_UserId)) { ?>

            <?php include("skype.inc.php"); ?>

          <a href='#' onclick="window.open('<?=$CONST_LINK_ROOT?>/add2hotlist.php?userid=<?=$sql_array->adv_userid?>&amp;handle=<?=$sql_array->adv_username?>','','toolbar=no,menubar=no,height=150,width=200,left='+(screen.width/2-100)+',top='+(screen.height/2-75)+'');return false;" title="<?=PRGRETUSER_TEXT7?>"><img border='0' src='<?=$CONST_IMAGE_ROOT?><?=$CONST_IMAGE_LANG?>/add2hotlist.gif' align="absmiddle" /></a>&nbsp;

    	  <?php include "sms.inc.php"?>

          <?php if (!$USERPLANE_IM && !$option_manager->GetValue('userplane_im_free')) {?>

          <a href='#' onclick="window.open('<?=$CONST_LINK_ROOT?>/add2im.php?userid=<?=$sql_array->adv_userid?>&amp;handle=<?=$sql_array->adv_username?>','','toolbar=no,menubar=no,height=150,width=200,left='+(screen.width/2-100)+',top='+(screen.height/2-75)+'');return false;" title="<?=PRGRETUSER_TEXT6?>"><img border='0' src='<?=$CONST_IMAGE_ROOT?><?=$CONST_IMAGE_LANG?>/addimfriend.gif' align="absmiddle" /></a>&nbsp;

          <?php } ?>

          <?php

      $isblocked=$db->get_var("SELECT count(*) FROM blockmail WHERE blk_receiverid = $sql_array->adv_userid AND blk_senderid = $Sess_UserId",$link);

                if ($isblocked > 0)

                   $sendmail_link = "javascript: alert('".str_replace("'", "\\'", PRGRETUSER_TEXT3)."')";

                else

                   $sendmail_link = "$CONST_LINK_ROOT/sendmail.php?userid=$sql_array->adv_userid&handle=$sql_array->adv_username";

     ?>

          <a href="<?=$sendmail_link?>" title="<?=PRGRETUSER_TEXT5?>"><img border='0' src='<?=$CONST_IMAGE_ROOT?><?=$CONST_IMAGE_LANG?>/mailme.gif' align="absmiddle" /></a>&nbsp;

          <?php

                $advQuery=mysqli_query($globalMysqlConn, "SELECT * FROM adverts where adv_userid=$Sess_UserId AND adv_approved=1");

                $advNote=mysqli_num_rows($advQuery);

                if ($CONST_FLIRT=='Y' && $advNote > 0) {

                   $NoteQuery=mysqli_query($globalMysqlConn, "SELECT * FROM notifications where ntf_senderid=$Sess_UserId and ntf_receiverid=$sql_array->adv_userid");

                   $hadNote=mysqli_num_rows($NoteQuery);

                        if ($hadNote > 0) {

                          print("<a href='#' title='".PRGRETUSER_FLIRTED."'><img src='$CONST_IMAGE_ROOT"."$CONST_IMAGE_LANG/sent4free.gif' align=\"absmiddle\" border='0'></a>");

                        } else {

                           print("<a href='$CONST_LINK_ROOT/prgsendflirt.php?userid=$sql_array->adv_userid&handle=$sql_array->adv_username' title='".PRGRETUSER_TEXT4."'><img border='0' src='$CONST_IMAGE_ROOT"."$CONST_IMAGE_LANG/flirt4free.gif' align=\"absmiddle\"></a>");

                        }

                } elseif ($CONST_FLIRT=='Y' && $advNote < 1) {

                                print("<a href='#'title='".PRGRETUSER_TEXT1."'><img src='$CONST_IMAGE_ROOT"."$CONST_IMAGE_LANG/block4free.gif' align=\"absmiddle\" border='0'></a>");

                }

            ?>

          <a href="<?php echo $CONST_LINK_ROOT?>/tipafriend.php?handle=<?=$sql_array->adv_username?>" title='<?=PRGRETUSER_TEXT2?>'><img border='0' src='<?=$CONST_IMAGE_ROOT?><?= $CONST_IMAGE_LANG ?>/tipfriend.gif' align="absmiddle" /></a>

          <?include "online_me.inc.php"?>

          <?php } else {?>

          <a href='<?=$CONST_LINK_ROOT?>/login.php' title='<?=PRGRETUSER_TEXT7?>'><img border='0' src='<?=$CONST_IMAGE_ROOT?><?=$CONST_IMAGE_LANG?>/add2hotlist.gif' /></a>&nbsp;

          <?php if (!$USERPLANE_IM && !$option_manager->GetValue('userplane_im_free')) {?>

          <a href='<?=$CONST_LINK_ROOT?>/login.php' title='<?=PRGRETUSER_TEXT6?>'><img border='0' src='<?=$CONST_IMAGE_ROOT?><?=$CONST_IMAGE_LANG?>/addimfriend.gif' /></a>&nbsp;

          <?php }?>

          <a href='<?=$CONST_LINK_ROOT?>/login.php' title='<?=PRGRETUSER_TEXT5?>'><img border='0' src='<?=$CONST_IMAGE_ROOT?><?=$CONST_IMAGE_LANG?>/mailme.gif' /></a>&nbsp; <a href='<?=$CONST_LINK_ROOT?>/login.php' title='<?=PRGRETUSER_TEXT4?>'><img border='0' src='<?=$CONST_IMAGE_ROOT?><?=$CONST_IMAGE_LANG?>/flirt4free.gif' /></a>&nbsp; <a href='<?=$CONST_LINK_ROOT?>/login.php' title='<?=PRGRETUSER_TEXT2?>'><img border='0' src='<?=$CONST_IMAGE_ROOT?><?=$CONST_IMAGE_LANG?>/tipfriend.gif' /></a>

          <?php } ?>

        </div>

       

  </div>

</div>

