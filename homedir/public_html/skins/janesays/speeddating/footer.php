</div>
          <div class="clearBoth"></div>
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
