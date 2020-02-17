<?php

include('db_connect.php');

include('session_handler.inc');

include('error.php');

require_once __INCLUDE_CLASS_PATH."/class.Avatar.php";



if ($id = formGet('id')) {

    $avatar = new Avatar($id);

    //$redirectURL = CONST_LINK_ROOT."/crop.php?thePic=$avatar->relativePath";
    $redirectURL = CONST_LINK_ROOT."/process.php?file=$avatar->relativePath";

?>

<html><body><script language="javascript">

window.opener.location = '<?=$redirectURL?>';

window.close()

</script></body></html>

<?php

}


$at = new Avatar(null);
$avatars = $at->findAll();



$area = 'popup';

?>

<?=$skin->ShowHeader($area)?>



<div id="avatar_list">

<?php foreach ($avatars as $avatar) { ?>

<a href="addavatar.php?id=<?=$avatar->id?>"><img src="<?=$avatar->thumbURL?>" border="0"></a>

<?php } ?>

</div>

<?=$skin->ShowFooter($area)?>

