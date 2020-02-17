<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<?php

# Check if mobile version exists

$filename = $CONST_INCLUDE_ROOT.'/mobile/config.php';

if (file_exists($filename)) {

	include_once $filename;

	$useragent=$_SERVER['HTTP_USER_AGENT'];

	if(preg_match('/android.+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|e\-|e\/|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(di|rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|xda(\-|2|g)|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))

	header('Location: '.$CONST_LINK_MOB_ROOT);

}

unset($_SESSION);

#  cookie for login



if ($HTTP_GET_VARS['clear']){

               setcookie ("txtHandle_c", $txtHandle,time()-3600);

               setcookie ("txtPassword_c", $txtHandle,time()-3600);

        $HTTP_COOKIE_VARS['txtHandle_c'] = '';

        $HTTP_COOKIE_VARS['txtPassword_c'] = '';

}



# set cookie for affiliate



if ($referid=formGet('referid')) {

        setcookie("referrer","$referid",0);

        $query="SELECT aff_clickthru FROM affiliates WHERE aff_userid=$referid";

        if ($sql_array = $db->get_row($query)) {

                $query="update affiliates set aff_clickthru = aff_clickthru+1 where aff_userid=$referid";

                $db->query($query);

        }

}



$country_res = $db->get_results("SELECT * FROM geo_country WHERE gcn_countryid!=0 ORDER BY gcn_order,gcn_name");

include_once __INCLUDE_CLASS_PATH."/class.Adverts.php";

$adv = new Adverts();

?>

<html xmlns="http://www.w3.org/1999/xhtml">

<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<title>

<?=$CONST_COMPANY?>

</title>

<meta name="Keywords" content="dating, online dating, internet dating, romance, relationships, marriage, free personals, online chat, chat, chat room, single men, dating agency" />

<meta name="Description" content="Looking for new ways to find a date?  Try a Keyword Search to find people with similar interests or a Custom Search to describe exactly what you want in a dream date." />

<meta name="ROBOTS" content="INDEX, FOLLOW" />

<meta name="language" content="en" />

<link rel='stylesheet' type='text/css' href='<?=$CONST_LINK_ROOT.$skin->Path?>template.css' />

<link rel='stylesheet' type='text/css' href='<?=$CONST_LINK_ROOT.$skin->Path?>core.css' />

<link rel="stylesheet" href="<?=$CONST_LINK_ROOT?>/lightbox/css/lightbox.css" type="text/css" media="screen" />

<script language="JavaScript" src="<?=$CONST_LINK_ROOT?>/jscript_lib.js.php" type="text/javascript"></script>

<script type="text/javascript" src="<?=$CONST_LINK_ROOT?>/ajax_form.js.php"></script>

<link rel="stylesheet" type="text/css" href="<?=$CONST_LINK_ROOT?>/sifr3/css/sifr.css">

<script src="<?=$CONST_LINK_ROOT?>/sifr3/js/sifr.js" type="text/javascript"></script>

<script src="<?=$CONST_LINK_ROOT?>/sifr3/js/sifr-config.js" type="text/javascript"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.0/css/bootstrap.min.css"></link>
</head>

<body>

<? include CONST_INCLUDE_ROOT."/csslogin.php"?>

<div id="wrapper">

  <!-- Start Header -->
<div class="container">
    <div class="row">
      <div class="grid_12">
        <div class="header_links color3">
          <a href="register.php">Register</a><a href="login.php">Sign in</a>
        </div>
      </div>
    </div>
  </div>
  <div id="header">
      <div id="wrapperin">
      
    <div id="header_left">

      <?php if (isset($_SESSION['Sess_UserId'])){	?>

      <a href="<?=$CONST_LINK_ROOT?>/home.php"><img src="<?=$CONST_IMAGE_ROOT?>logo.png" alt="<?=$CONST_COMPANY?>" width="350" height="95" border="0" /></a>

      <?php } else { ?>

      <a href="<?=$CONST_LINK_ROOT?>/index.php"><img src="<?=$CONST_IMAGE_ROOT?>logo.png" alt="<?=$CONST_COMPANY?>" width="350" height="95" border="0" /></a>

      <?php }	?>

    </div>

    <div id="header_right">

      <?php if (isset($_SESSION['Sess_UserId'])){	?>

      <a href="<?=$CONST_LINK_ROOT?>/home.php">

      <?=$MENU_HOME?>

      </a> | <a href="<?=$CONST_LINK_ROOT?>/get_premium.php">

      <?=$MENU_UPGRADE?>

      </a> | <a href="<?=$CONST_LINK_ROOT?>/logoff.php">

      <?=$MENU_LOGOUT?>

      </a> |

      <?php } else { ?>

      <a class="home-nav-current home-nav" href="<?=$CONST_LINK_ROOT?>/index.php">

      <?=$MENU_HOME?>

      </a>  <a  class="home-nav" href="<?=$CONST_LINK_ROOT?>/register.php">

      <?=$MENU_REGISTER?>

      </a>  <a class="home-nav"  href="#" onClick="openbox('', 1,'box')">

      <?=$MENU_LOGIN?>

      </a> 

      <?php }	?>

      <a class="home-nav" href="<?=$CONST_LINK_ROOT?>/about.php">

      <?=$MENU_ABOUT?>

      </a>  <a class="home-nav" href="<?=$CONST_LINK_ROOT?>/news_list.php">

      <?=$MENU_NEWS?>

      </a>  <a class="home-nav" href="<?=$CONST_LINK_ROOT?>/stories_list.php">

      <?=$MENU_STORIES?>

      </a>  <a class="home-nav" href="<?=$CONST_LINK_ROOT?>/help.php">

      <?=$MENU_HELP?>

      </a> </div>
</div>
  </div>

  <!-- End Header - Start body -->

  <div id="shadow_m">

    <div id="shadow_t">

      <div id="shadow_b">

        <!--  Start Nav -->

        <!--<div id="nav">

          <?php

        require_once __INCLUDE_CLASS_PATH."/class.MenuManager.php";

        $menu = new MenuManager('home');

        $menu->outputMenu();


      ?>

        </div>-->

        <!-- End Nav - Start Content - Start Upper Content -->

        <div id="content" class="home_content">
            <div id="wrapperin">
          <div id="home_content_top">

            <!--<div id="home_slogan">

              <div id="home_join"><a href="<?=$CONST_LINK_ROOT?>/register.php"><img src="<?=$CONST_IMAGE_ROOT?>join.gif" alt="<?=$MENU_REGISTER?>" width="190" height="50" border="0" /></a> </div>

              <span class="home_note1">

              <?=INDEX_NOTE1?>

              </span><br />

              <span class="home_note2">

              <?=INDEX_NOTE2?>

              </span> </div>-->
            <div class="row">
            <div class="col-md-8 content-left" ></div>

            <div class="col-md-4">
                <div id="home_search" >
                <div  class="home_searchtitle"><?php echo "Find Your Match!";?>
                </div>
                      <form action="<?=$CONST_LINK_ROOT?>/prgminisearch.php" method="post" name="dating service" id="dating service">

                <table cellpadding="3" style="width:100%;">

              
                  

                  <tr>

                    <td align="right" class="home_searchtxt"><?=HOME_IAM?></td>

                    <td><select name="lstDatingFrom" size="1" class="inputs">

                        <option value="F">

                        <?=GENDER_W?>

                        </option>

                        <option value="M" selected="selected">

                        <?=GENDER_M?>

                        </option>

                      </select></td>

                  </tr>

                  <tr>

                    <td align="right" class="home_searchtxt"><?=HOME_SEEKING?></td>

                    <td><select name="lstDatingTo" size="1" class="inputs">

                        <option value="M">

                        <?=GENDER_M?>

                        </option>

                        <option value="F" selected="selected">

                        <?=GENDER_W?>

                        </option>

                      </select></td>

                  </tr>

                  <tr>

                    <td align="right" class="home_searchtxt"><?=HOME_LOCATED?></td>

                    <td ><select name="lstDatingCountry" class="inputf">

                        <option selected="selected" value="0">

                        <?=SEARCH_ALLCOUNTRIES?>

                        </option>

                        <?php foreach ($country_res as $country_row){ ?>

                        <option value="<?=$country_row->gcn_countryid?>">

                        <?=$country_row->gcn_name?></option>

                        <?php } ?>

                      </select></td>

                  </tr>

                  <tr>

                    

                    <td colspan="2">
                        <input value="<?=HOME_SEARCH_TITLE?>" type="submit" class="button" alt="<?=HOME_SEARCH_TITLE?>" name="search"/>
                    </td>
                     

                  </tr>

               

              </table>
                           </form>
</div>
            </div>
</div>
          </div></div>
        </div>
 <div id="content-bottom">
            <div id="wrapperin">
          <!-- End Uppper Content - Start Lower Content -->

          <div id="home_content_lower">

            <div id="home_left">
                
                 
                    <div class="home_content_top_header">Find the ideal plus sized woman of your dreams!</div>
                    <div class="home_content_body">
                        Here is the textHere is the text Here is the text Here is the text Here is the text<br> 
                Here is the text'sHere is the text's Here is the text Here is the text Here is the text Here is the text
                    </div>
                    
              

              <div class="home_content_header"><?=GENERAL_FEATURED_MEMBERS?></div>

              <div class="home_content_body">

<?php



        $query="SELECT pic_userid, adv_username, adv_sex, adv_countryid, adv_userid, gcn_name as adv_country, (YEAR(CURDATE())-YEAR(adv_dob)) - (RIGHT(CURDATE(),5) < RIGHT(adv_dob,5)) AS age FROM pictures

                LEFT JOIN adverts ON (pic_userid = adv_userid)

				LEFT JOIN geo_country ON adv_countryid=gcn_countryid

				LEFT JOIN members ON (pic_userid = mem_userid)

                WHERE RAND()<(SELECT ((2/COUNT(*))*10) FROM pictures) AND pic_private = 'N' AND pic_default='Y' AND adv_sex='F' AND adv_approved=1 AND mem_featured=0 AND mem_suspend='N' 

                LIMIT 2";



        $res=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());

        if ($row = mysqli_fetch_object($res)){

                $adv->InitByObject($row);

                $adv->SetImage('medium');

                $sql_picture = $adv;

                $female1_picture = $sql_picture->adv_picture->Path;

                $female1_name=$sql_picture->adv_username;

                $female1_age=$sql_picture->age;

                $female1_country=$sql_picture->adv_country;

                $female1_id=$sql_picture->adv_userid;

        }



        if ($row = mysqli_fetch_object($res)){

                $adv->InitByObject($row);

                $adv->SetImage('medium');

                $sql_picture = $adv;

                $female2_picture = $sql_picture->adv_picture->Path;

                $female2_name=$sql_picture->adv_username;

                $female2_age=$sql_picture->age;

                $female2_country=$sql_picture->adv_country;

                $female2_id=$sql_picture->adv_userid;

        }



        $query="SELECT pic_userid, adv_username, adv_sex, adv_countryid, adv_userid, gcn_name as adv_country, (YEAR(CURDATE())-YEAR(adv_dob)) - (RIGHT(CURDATE(),5) < RIGHT(adv_dob,5)) AS age FROM pictures

                LEFT JOIN adverts ON (pic_userid = adv_userid)

				LEFT JOIN geo_country ON adv_countryid=gcn_countryid

				LEFT JOIN members ON (pic_userid = mem_userid)

                WHERE RAND()<(SELECT ((2/COUNT(*))*10) FROM pictures) AND pic_private = 'N' AND pic_default='Y' AND adv_sex='M' AND adv_approved=1 AND mem_featured=0 AND mem_suspend='N' 

                LIMIT 2";



        $res=mysqli_query($globalMysqlConn,$query) or die(mysqli_error());



        if ($row = mysqli_fetch_object($res)){

                $adv->InitByObject($row);

                $adv->SetImage('medium');

                $sql_picture = $adv;

                $male1_picture = $sql_picture->adv_picture->Path;

                $male1_name=$sql_picture->adv_username;

                $male1_age=$sql_picture->age;

                $male1_country=$sql_picture->adv_country;

                $male1_id=$sql_picture->adv_userid;

        }

        

        if ($row = mysqli_fetch_object($res)){

                $adv->InitByObject($row);

                $adv->SetImage('medium');

                $sql_picture = $adv;

                $male2_picture = $sql_picture->adv_picture->Path;

                $male2_name=$sql_picture->adv_username;

                $male2_age=$sql_picture->age;

                $male2_country=$sql_picture->adv_country;

                $male2_id=$sql_picture->adv_userid;

        }

        // mysqli_close( $link );



        ?>

                <table id="home_profile">

                  <tr>

                    <td valign="top"><?php if (!empty($female1_id)) { ?>

                      <a href="<?=$CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$female1_id?>" class="profile_image"><span><img src="<?=$CONST_LINK_ROOT?><?=$female1_picture?>"  class="imagehome" alt="" border="0" /></span></a>

                      <div class="home_profile_1"><a href="<?=$CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$female1_id?>">

                        <?=$female1_name?>

                        ,

                        <?=$female1_age?>

                        

                        

                        </a></div>
                      <div><?=$female1_country?></div>

                      <?php } ?></td>

                    <td valign="top"><?php if (!empty($female2_id)) { ?>

                      <a href="<?=$CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$female2_id?>" class="profile_image">
                          <span><img src="<?=$CONST_LINK_ROOT?><?=$female2_picture?>" alt="" border="0" /></span>
                      </a>

                      <div class="home_profile_1">
                          <a href="<?=$CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$female2_id?>">

                        <?=$female2_name?>

                        ,

                        <?=$female2_age?>

                       

                        </a></div>
                        <div>
                          

                        <?=$female2_country?>
                        </div>

                      <?php } ?></td>

                    <td valign="top"><?php if (!empty($male1_id)) { ?>

                      <a href="<?=$CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$male1_id?>" class="profile_image"><span><img src="<?=$CONST_LINK_ROOT?><?=$male1_picture?>" alt="" border="0" /></span></a>

                      <div class="home_profile_1"><a href="<?=$CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$male1_id?>">

                        <?=$male1_name?>

                        ,

                        <?=$male1_age?>

                       

                       

                        </a></div>
                      <div> <?=$male1_country?>
                          
                      </div>
                      <?php } ?></td>

                    <td valign="top"><?php if (!empty($male2_id)) { ?>

                      <a href="<?=$CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$male2_id?>" class="profile_image"><span><img src="<?=$CONST_LINK_ROOT?><?=$male2_picture?>" alt="" border="0" /></span></a>

                      <div class="home_profile_1"><a href="<?=$CONST_LINK_ROOT?>/prgretuser.php?userid=<?=$male2_id?>">

                        <?=$male2_name?>

                        ,

                        <?=$male2_age?>

                        

                        

                        </a></div>
                      <div>
                          <?=$male2_country?>
                      </div>

                      <?php } ?></td>

                  </tr>

                </table>

              </div>

            </div>

            

          </div>

          <div class="clearBoth"></div>
        </div>
        </div>

        <!-- End Lower Content - End Content - Start Footer -->

        <div id="footer">

          <div id="footer_inner">

            <div class="footer_cell col-xs-12 col-sm-4">

              <?php if (isset($_SESSION['Sess_UserId'])){	?>

              <a href="<?=$CONST_LINK_ROOT?>/get_premium.php">

              <?=HOME_UPGRADE?>

              </a>

              <?php } else { ?>

              <a href="<?=$CONST_LINK_ROOT?>/register.php">

              <?=$MENU_REGISTER?>

              </a>

              <?php }	?>

              <br />

              <a href="<?php echo $CONST_LINK_ROOT?>/myinfo.php">

              <?= HOME_SETTINGS?>

              </a><br />

              <a href="<?="$CONST_LINK_ROOT/prgpicadmin.php?mode=show"?>">

              <?= HOME_VIDEO?>

              </a><br />

              <a href="<?php echo $CONST_LINK_ROOT?>/prgamendad.php">

              <?= HOME_PROFILE?>

              </a><br />

              <a href="<?php echo $CONST_LINK_ROOT?>/prgamendreg.php"><?php echo MYINFO_LINK_REGISTRATION?></a><br />

              <a href="<?php echo $CONST_LINK_ROOT?>/eventscalendar.php">

              <?= HOME_CALENDAR?>

              </a><br />

              <a href="<?php echo $CONST_LINK_ROOT?>/whoson.php">

              <?= HOME_WHOSEON?>

              </a> </div>

            <div class="footer_cell"><a class='forumlinks' href="<?php echo $CONST_LINK_ROOT?>/invitefriend.php">

              <?= HOME_INVITE_FRIENDS?>

              </a><br />

              <a href="<?php echo $CONST_LINK_ROOT?>/interested_in_me.php">

              <?= HOME_INTERESTED_IN_ME?>

              </a><br />

              <a href="javascript:MDM_openWindow('<?php echo $CONST_LINK_ROOT?>/prgencounters.php','Encounters','scrollbars=yes, width=560,height=550')">

              <?= HOME_ENCOUNT?>

              </a><br />

              <a href="<?php echo $CONST_LINK_ROOT?>/my_interests.php">

              <?= HOME_MY_INTERESTS?>

              </a><br />

              <a href="<?php echo $CONST_LINK_ROOT?>/my_matches.php">

              <?= HOME_MYMATCH?>

              </a><br />

              <a href="<?php echo $CONST_GROUPS_LINK_ROOT?>/my_groups.php">

              <?= HOME_GROUPS?>

              </a><br />

              <a href="<?php echo $CONST_LINK_ROOT?>/prgmailblock.php"><?php echo MYINFO_LINK_MANAGE?></a> </div>

            <div class="footer_cell">

              <?php if (isset($_SESSION['Sess_UserId'])){	?>

              <a href="<?=$CONST_LINK_ROOT?>/logoff.php">

              <?=$MENU_LOGOUT?>

              </a>

              <?php } else { ?>

              <a href="<?=$CONST_LINK_ROOT?>/login.php">

              <?=$MENU_LOGIN?>

              </a>

              <?php }	?>

              <br />

              <a href="<?=$CONST_LINK_ROOT?>/disclaimer.php">

              <?=$MENU_DISCLAIMER?>

              </a> <br />

              <a href="<?=$CONST_LINK_ROOT?>/contact.php">

              <?=$MENU_CONTACT?>

              </a> <br />

              <a href="<?=$CONST_LINK_ROOT?>/scheme.php">

              <?=$MENU_AFFILIATES?>

              </a> <br />

              <a href="<?=$CONST_LINK_ROOT?>/privacy.php">

              <?=$MENU_PRIVACY?>

              </a><br />

              <br />

              <?php echo "Copyright &copy; ".date("Y")." ".$CONST_COMPANY; ?> </div>

            <div class="clearBoth"></div>

          </div>

        </div>

        <!-- End Footer -->

      </div>

    </div>

  </div>

  <!-- End Body -->

</div>

<div id="language">

  <?= $CONST_LINK_LANG_SWITCHER;?>

</div>

</body>

</html>

