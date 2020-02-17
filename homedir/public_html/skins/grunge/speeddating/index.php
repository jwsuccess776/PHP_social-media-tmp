<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />

<title>

<?=$CONST_COMPANY?>

</title>

<meta name="Keywords" content="dating, online dating, internet dating, russian dating, uk dating,

london dating, romance, relationships, marriage, free personals, online chat, chat, chat room, single men,

single women, service, personal ads, photo profile,  matchmaking, meet people, free, online,  single, christian dating,

jewish dating, asian dating, women, dating site, personals, matchmaker, couples,

swinging, gay dating, lesbian dating, gay, lesbian, relationship,  on-line, marriage, women,

chat, romance, interracial dating,  adult dating,  love, introduction, swinging, couples,  personals,

relationship, personal, ladies, dating agency" />

<meta name="Description" content="Online dating free dating gay dating site with chat room and adult dating. Looking for new ways to find a date?  Try a Keyword Search to find people with similar interests or a Custom Search

to describe exactly what you want in a dream date." />

<meta name="ROBOTS" content="INDEX, FOLLOW" />

<meta name="language" content="en" />

<link rel='stylesheet' type='text/css' href='<?=$CONST_LINK_ROOT.$skin->Path?>template.css' />

<link rel='stylesheet' type='text/css' href='<?=$CONST_LINK_ROOT.$skin->Path?>core.css' />

<LINK rel='stylesheet' type='text/css' href="<?=$CONST_LINK_ROOT.$skin->Path?>speeddating/speeddating.css" >

<script language="JavaScript" src="<?=$CONST_LINK_ROOT?>/jscript_lib.js.php" type="text/javascript"></script>

<script language="javascript" src="<?=$CONST_LINK_ROOT?>/speeddating/geography.js" type="text/javascript"></script>

</head>

<body>

<div id="wrapper">

  <div id="wrapper_inner">

    <div id="wrapper_content">

      <!-- Start Header -->

      <div id="header">

        <div id="header_left">

          <?php if (isset($_SESSION['Sess_UserId'])){	?>

          <a href="<?=$CONST_LINK_ROOT?>/home.php"><img src="<?=$CONST_IMAGE_ROOT?>logo.gif" alt="<?=$CONST_COMPANY?>" width="560" height="100" border="0" /></a>

          <?php } else { ?>

          <a href="<?=$CONST_LINK_ROOT?>/index.php"><img src="<?=$CONST_IMAGE_ROOT?>logo.gif" alt="<?=$CONST_COMPANY?>" width="560" height="100" border="0" /></a>

          <?php }	?>

        </div>

        <div id="header_right">

          <?php if (isset($_SESSION['Sess_UserId'])){	?>

          <a href="<?=$CONST_LINK_ROOT?>/home.php">

          <?=$MENU_HOME?>

          </a> | <a href="<?=$CONST_LINK_ROOT?>/speeddating/register.php" ><?=SD_REGISTER?></a>

          <? } else { ?>

          <a href="<?=$CONST_LINK_ROOT?>/index.php">

          <?=$MENU_HOME?>

          </a>

          <? } ?>

          |

          <?=$generatedLogin?>

    | <a href="<?=$CONST_LINK_ROOT?>/speeddating/aboutus.php"><?=$MENU_ABOUT?></a>  | <a href="<?=$CONST_LINK_ROOT?>/speeddating/help.php"  ><?=$MENU_HELP?></a> </div>

      </div>

      <!-- End Header - Start Nav -->

  <div id="nav"> <a href="<?=$CONST_LINK_ROOT?>/speeddating/index.php"  ><?=SD_HOME?></a><a href="event_list.php"><?=ADM_EVENTS?></a><a href="<?=$CONST_LINK_ROOT?>/speeddating/stories_list.php"><?=$MENU_STORIES?></a><a href="<?=$CONST_LINK_ROOT?>/speeddating/home.php"><?=SD_PERSONAL_PAGE?></a><a href="<?=$CONST_LINK_ROOT?>/speeddating/tipafriend.php" ><?=SD_TIP_SECTION_NAME?></a></div>

      <!-- End Nav - Start Content -->

       <div id="content">

        <div id="content_inner">



          <div id="home_content_top">

          <div id="home_slogan"> <span class="home_note1">

            <?=INDEX_NOTE1?>

            </span><br />

            <span class="home_note2">

            <?=INDEX_NOTE2?>

            </span> </div>

          <div id="home_search">

            <table>

              <form action="event_list.php" method="post" name="search_event" id="search_event">

                <tr>

                  <td colspan="2" class="home_searchtitle"><?=$MENU_SEARCH?></td>

                </tr>

                <tr >

                  <td align="right" class="home_searchtxt"><?=GENERAL_COUNTRY?></td>

                  <td align="left"><select name="search_country" id="select" size="1" class="input" style="width:180px;" tabindex="1" onchange="onCountryListChange('search_event', 'search_country', 'search_state', 'search_city');">

                      <option value="0" selected="selected">-

                      <?=SEARCH_COUNTRY?>

                      -</option>

                      <option value=""></option>

                    </select>

                  </td>

                </tr>

                <tr >

                  <td align="right" class="home_searchtxt"><?=GENERAL_STATE?></td>

                  <td align="left"><select name="search_state" id="select2" size="1" class="input" style="width:180px;" tabindex="1" onchange="onStateListChange('search_event', 'search_country', 'search_state', 'search_city');">

                      <option value="0" selected="selected">-

                      <?=SEARCH_STATE?>

                      -</option>

                    </select></td>

                </tr>

                <tr >

                  <td align="right" class="home_searchtxt"><?=GENERAL_CITY?></td>

                  <td align="left"><select name="search_city" size="1" id="select3" class="input" style="width:180px;" tabindex="1" onchange="onCityListChange('search_event', 'search_city');">

                      <option value="0" selected="selected">-

                      <?=SEARCH_CITY?>

                      -</option>

                    </select>

                  </td>

                </tr>

                <tr >

                  <td align="right" class="home_searchtxt"><?=SD_INDEX_AGES?></td>

                  <td align="left"><select name="search_age" id="select4" size="1" class="input" style="width:180px;" tabindex="1">

                      <option value="" selected="selected">-

                      <?=SEARCH_AGES?>

                      -</option>

                      <option value="20/30">20-30</option>

                      <option value="25/35">25-35</option>

                      <option value="30/40">30-40</option>

                      <option value="35/45">35-45</option>

                      <option value="40/50">40-50</option>

                      <option value="45/55">45-55</option>

                      <option value="50/60">50-60</option>

                      <option value="55/65">55-65</option>

                    </select></td>

                </tr>

                <tr >

                  <td align="right" class="home_searchtxt"><?=SEX?></td>

                  <td align="left"><select name="search_group" id="select5" size="1" class="input" style="width:180px;" tabindex="1">

                      <option value="" selected="selected">-

                      <?=SEARCH_ANY?>

                      -</option>

                      <option value="M/F">

                      <?=SD_GENDER_GROUP1?>

                      </option>

                      <option value="M/M">

                      <?=SD_GENDER_GROUP2?>

                      </option>

                      <option value="F/F">

                      <?=SD_GENDER_GROUP3?>

                      </option>

                    </select></td>

                </tr>

                <tr >

                  <td align="right" class="home_searchtxt">&nbsp;</td>

                  <td align="left"><input type="image" src="<?=$CONST_IMAGE_ROOT?>search.gif" alt="<?php echo $HOME_SEARCH_TITLE ?>" name="search" /></td>

                </tr>

              </form>

              <script language="JavaScript" type="text/javascript">



                                initialize('search_event', 'search_country', 'search_state', 'search_city');



                            </script>

            </table>

          </div>

        </div>

        <div id="home_content_bot_sd">

          <div id="home_left_sd">

            <table width="100%" cellpadding="5">

              <tr>

                <td width="50%" valign="top"><div class="home_content_header" style="padding-left:0;"><?=SD_SPECIAL?></div>

                  

                    <?=$generatedEvents;?>

                 </td>

                <td width="50%" valign="top"><div class="home_content_header" style="padding-left:0;"><?=$MENU_STORIES?></div>

                

                    <?=$generatedStories;?>

                  </td>

              </tr>

            </table>

          </div>

          <div id="home_right_sd">

            <table align="center" cellpadding="5">

              <form action="prgsubscribe_mail.php">

                <tr>

                  <td colspan="2" align="left"  class="home_content_header" style="padding-left:0;"><?=SD_INFORMED?></td>

                </tr>

                <tr>

                  <td align="left" ><?=SD_TELLAFRIEND_EMAIL?></td>

                  <td align="left"><input name='email' type="text" class="input" style="width:160px;" value='' /></td>

                </tr>

                <tr>

                  <td align="left" ><?=ADM_EVENT_TICKETS_GENDER?></td>

                  <td align="left" ><?=show_gender('','radio','sex')?>

                  </td>

                </tr>

                <tr>

                  <td colspan="2" align="left" nowrap="nowrap"><input type="submit" class="button" name="subscribe_mail" value="<?=SD_INDEX_SUB?>" />

                    <input type="submit" class="button" name="unsubscribe_mail" value="<?=SD_INDEX_UNSUB?>" />

                  </td>

                </tr>

              </form>

            </table>

          </div>

          <div class="clearBoth"></div>

        </div>

        <div style="margin:0 20px;">

          <table width="100%">

            <tr>

              <td class="hometext"><table width="100%"  border="0" cellspacing="o" cellpadding="3">

                  <?php



        $sql_query = "  SELECT *,COUNT(if(sdt_gender='Gender1',1,null)) G1_qty, COUNT(if(sdt_gender='Gender2',1,null)) G2_qty,st.sdt_eventid sdt_eventid



                        FROM sd_events se



                            LEFT JOIN sd_tickets st



                                ON (se.sde_eventid = st.sdt_eventid)



                            INNER JOIN sd_venues sv



                                ON (se.sde_venueid  = sv.vnu_venueid )



                            LEFT JOIN geo_city gs



                                ON (sv.vnu_cityid   = gs.gct_cityid )



                        WHERE se.sde_date > now()



                            AND (se.sde_is_special = 'no' OR se.sde_is_special = 'yes')



                        GROUP BY se.sde_eventid



                        ORDER BY se.sde_date ASC



                        LIMIT 0, 10";



        $sql_result = mysqli_query($globalMysqlConn,$sql_query);



        $event_qty = mysqli_num_rows($sql_result) < 10 ? mysqli_num_rows($sql_result) : 10;







        if ($event_qty > 0) {



            ?>

                  <tr>

                    <td colspan="6"><div class="home_content_header" style="padding-left:0;"><?=SD_NEXT?> 

                        <?=$event_qty;?>

                        <?=SD_EVENTS?></div></td>

                  </tr>

                  <form action="<?php echo $CONST_LINK_ROOT?>/speeddating/events.php" method="post" name="frmPremFunc" id="frmPremFunc">

                    <tr align="left" class="tdtoprow">

                      <td  class="tdtoprow"  ><strong>

                        <?=GENERAL_LOCATION?>

                        </strong></td>

                      <td  class="tdtoprow" ><strong>

                        <?=SD_EVENTS_NAME?>

                        </strong></td>

                      <td  class="tdtoprow"><strong>

                        <?=SD_EVENTS_DATE?>

                        </strong></td>

                      <td  class="tdtoprow"><strong>

                        <?=SD_INDEX_AGES?>

                        </strong></td>

                      <td align="center" class="tdtoprow"><strong>

                        <?=SD_EVENT_LIST_AVAIBLE?>

                        </strong></td>

                      <td class="tdtoprow"><strong>

                        <!--<?=SD_EVENT_LIST_SPECIAL?>-->

                        </strong></td>

                    </tr>

                    <?php



               while($event = mysqli_fetch_object($sql_result)) {



                            $zebra = ($zebra == "tdodd") ? 'tdeven' : 'tdodd';



                ?>

                    <tr class="link <?=$zebra?>" style="cursor:pointer"; onclick="location.href='<?=$CONST_LINK_ROOT?>/speeddating/event_info.php?sde_eventid=<?=$event->sde_eventid?>&amp;back=index';">

                      <td  ><?=htmlspecialchars($event->gct_name)?>

                      </td>

                      <td ><?=htmlspecialchars($event->sde_name)?>

                      </td>

                      <td ><?=date($CONST_FORMAT_DATE_SHORT, strtotime($event->sde_date))?>

                      </td>

                      <td nowrap="nowrap"  ><?php echo $event->sde_age_from."-".$event->sde_age_to; ?> </td>

                      <td align="center"><?php



                                    $available_gender1 = $event->sde_gender1_places - $event->G1_qty;



                                    $available_gender2 = $event->sde_gender2_places - $event->G2_qty;



                                    if ($event->sde_gender1 == $event->sde_gender2) {



                                        if ($available_gender1 > 0 || $available_gender2 > 0) {



                                            echo constant("SD_INDEX_GENDER_".$event->sde_gender1);



                                        } else {



                                            echo SD_EVENT_LIST_SOLD;



                                        }



                                    } else {



                                        if ($available_gender1 > 0 && $available_gender2 > 0) {



                                            echo SD_INDEX_GENDER_B;



                                        } elseif ($available_gender1 <= 0 && $available_gender2 > 0) {



                                            echo constant("SD_INDEX_GENDER_".$event->sde_gender2);



                                        } elseif ($available_gender1 > 0 && $available_gender2 <= 0) {



                                            echo constant("SD_INDEX_GENDER_".$event->sde_gender1);



                                        } else {



                                            echo SD_EVENT_LIST_SOLD;



                                        }



                                    }



                                    ?>

                      </td>

                      <td ><?php if (empty($event->sde_special)) { 

							echo "&quot;&nbsp;&quot;"; 

						} else { 

							echo htmlspecialchars($event->sde_special);

                        }  

						?></td>

                      <?php } ?>

                    </tr>

                  </form>

                  <tr align="right">

                    <td colspan="6" class="tdfoot"><a href="event_list.php">

                      <?=SD_INDEX_FULL?>

                      </a></td>

                  </tr>

                  <? } else { ?>

                  <tr>

                    <td align="center" colspan="6"><?=SD_INDEX_NO?>

                    </td>

                  </tr>

                  <? } ?>

                </table></td>

            </tr>

          </table>

        </div>

      </div>

	  </div>

      <!-- End Content - Start Footer -->

      <div id="footer">

        <div id="footer_inner">

          <div class="footer_cell">

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

              <a href="<?=$CONST_LINK_ROOT?>/prgpicadmin.php?mode=show">

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

            <a href="<?php echo $CONST_LINK_ROOT?>/prgmailblock.php"><?php echo MYINFO_LINK_MANAGE?></a></div>

          <div class="footer_cell footer_plus">

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

<div id="language">

  <?= $CONST_LINK_LANG_SWITCHER;?>

</div>

</body>

</html>

