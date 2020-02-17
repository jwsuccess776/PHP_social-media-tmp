<?php



# Profiles needing approval

$newAdverts = $db->get_var("SELECT COUNT(adv_approved) FROM adverts LEFT JOIN members ON (adv_userid=mem_userid) WHERE adv_approved = 0 AND mem_confirm = 1");



#Videos needing approval / conversion

$video = new Video();

$newVideo = $video->getListByStatus('new','count');



# New Groups to approve
$group = new Group;
$newGroups = $group->count('status = 0');



# Mails waiting in queue

$newMails = $db->get_var("SELECT COUNT(MailQueue_ID) FROM mail_queue");



# Pictures to be approved

$newPictures = $db->get_var("SELECT COUNT(pic_userid) FROM pictures WHERE pic_approved=0");



# Blogs to be approved

$newBlogs = $db->get_var("SELECT COUNT(blg_approved) FROM blogs WHERE blg_approved = 'N'");



# Forums to be approved

$newForums = $db->get_var("SELECT COUNT(post_approved) FROM bb_posts WHERE post_approved = '0'");



# Gallery to be approved

$gallery= new Gallery();

$newGallery = $gallery->CountPendingItem();



# Unconfirmed Users

$totalconf = $db->get_var("SELECT COUNT(adv_approved) FROM adverts LEFT JOIN members ON (adv_userid=mem_userid) WHERE mem_confirm = 0");



if ($newAdverts) print("<a style='float:left; margin: 5px 10px 5px 10px;' href='$CONST_LINK_ROOT/admin/prgauthads.php?mode=start'>".APPROVE_ADS_SECTION_NAME." ($newAdverts)</a>&nbsp;");

if ($newPictures) print("<a style='float:left; margin: 5px 10px 5px 10px;' href='$CONST_LINK_ROOT/admin/prgauthpics.php?mode=start'>".DB_OPTION_AUTHORISEPIC_LABEL." ($newPictures)</a>&nbsp;");

if ($newBlogs) print("<a style='float:left; margin: 5px 10px 5px 10px;' href='$CONST_LINK_ROOT/admin/prgauthblogs.php?mode=start'>".ADM_BLOGS_APPROVE_SECTION_NAME." ($newBlogs)</a>&nbsp;");

if ($newForums) print("<a style='float:left; margin: 5px 10px 5px 10px;' href='$CONST_LINK_ROOT/admin/prgauthforum.php?mode=start'>".ADM_FORUM_APPROVE_SECTION_NAME." ($newForums)</a>&nbsp;");

if ($newGallery) print("<a style='float:left; margin: 5px 10px 5px 10px;' href='$CONST_LINK_ROOT/admin/prgauthgallery.php'>".APPROVE_GALLERY." ($newGallery)</a>&nbsp;");

if ($newVideo) print("<a style='float:left; margin: 5px 10px 5px 10px;' href='$CONST_LINK_ROOT/admin/prgauthvideo.php?mode=start'>".ADM_CONVERT_VIDEO." ($newVideo)</a>&nbsp;");

if ($newGroups) print("<a style='float:left; margin: 5px 10px 5px 10px;' href='$CONST_LINK_ROOT/admin/adm_groups_approve.php'>".ADM_GROUPS_APPROVE_SECTION_NAME." ($newGroups)</a>&nbsp;");

if ($newMails) print("<a style='float:left; margin: 5px 10px 5px 10px;' href='$CONST_LINK_ROOT/admin/adm_process_queue.php?mode=start'>".ADM_PROCESS_MAIL." ($newMails)</a>&nbsp;");

if ($totalconf) print("<a style='float:left; margin: 5px 10px 5px 10px;' href='$CONST_LINK_ROOT/admin/unconfirmed.php'>".UNCONFIRMED_SECTION_NAME." ($totalconf)</a>&nbsp;");



?>