<?

function show_comments($type, $id){

    include CONST_INCLUDE_ROOT."/comment/comment.js.php";

?>
 <div id="comments"></div>
<div class="resulthead">
  <?=ADD_COMMENT?>
</div>

<div class="vidshow_resultbody">
  <form>
    <textarea id="new_comment" ROWS=5 COLS=85 style="width:100%""></textarea>
    <br />
    <input type=button class=button value="<?=ADD_COMMENT?>" onClick="addComment('ent_type=<?=$type?>&ent_id=<?=$id?>', 'new_comment')">
  </form>
</div>
<script language="javascript">

        displayProgress('comments');

        getComments('ent_type=<?=$type?>&ent_id=<?=$id?>', 'comments');

    </script>
<?

}



function checkEntOwner($type, $id) {

    $db =& db::getInstance();

    switch ($type) {

        case 'video':

            include_once __INCLUDE_CLASS_PATH."/class.Video.php";

            $video = new Video();

            $video->initById($id);

            return ($video->vid_userid == $_SESSION['Sess_UserId']) ? true : false;

            break;

        case 'blog' :

            $owner = $db->get_var("SELECT blg_userid FROM blogs WHERE blg_id = '$id'");

            return ($owner == $_SESSION['Sess_UserId']) ? true : false;

        break;

        default:

            die ("Incorret entity type [$type]");

    }



}

?>
