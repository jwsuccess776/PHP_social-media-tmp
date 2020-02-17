 <div id="mail">
  <ul id="mailnav">
    <li><a href="<?=$CONST_LINK_ROOT?>/prgpicadmin.php" <?if ($SCRIPT_NAME == 'prgpicadmin.php'){?>id='current'<?}?>>
      <?=PICADMIN_SECTION_NAME?>
      </a></li>
<?php if ( strtoupper ( $CONST_VIDEOS ) == 'Y' ) { ?>
    <li><a href="<?=$CONST_LINK_ROOT?>/prgvideoadmin.php" <?if ($SCRIPT_NAME == 'prgvideoadmin.php'){?>id='current'<?}?>>
      <?=VIDEOADMIN_SECTION_NAME?>
      </a></li>
<?php } ?>
<?php if ( strtoupper ( $CONST_AUDIOS ) == 'Y' ) { ?>
    <li><a href="<?=$CONST_LINK_ROOT?>/prgaudadmin.php" <?if ($SCRIPT_NAME == 'prgaudadmin.php'){?>id='current'<?}?>>
      <?=AUDADMIN_SECTION_NAME?>
      </a></li>
<?php } ?>
    <li><a href="<?=$CONST_GALLERY_LINK_ROOT?>/manage_gallery.php" <?if ($SCRIPT_NAME == 'manage_gallery.php'){?>id='current'<?}?>>
      <?=GALLERY_SECTION_NAME?>
      </a></li>
  </ul>
</div>
