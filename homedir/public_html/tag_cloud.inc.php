<?php
include_once __INCLUDE_CLASS_PATH."/class.Tag.php";
include_once __INCLUDE_CLASS_PATH."/class.Tagging.php";
$TaggingLink=new Tagging('blog');
list($scale,$tags) =  $TaggingLink->getCloud(25,6);
//list($scale,$tags) = Tagging::getCloud(25,6);
$tag = new Tag();
foreach ((array)$tags AS $id => $rating){
    $tag->initByTag($id);
    foreach ($scale as $class => $border){
        if ($rating <= $border){
            $tag_class = $class;
            break;
        }
   }
?>
   <span ><a class="tag<?=$tag_class?>" href="<?=$CONST_LINK_ROOT?>/tagging.php?tag_id=<?=$tag->id?>"><?=$tag->tag?></a></span>
<?php
}?>
