<?
include "../../../db_connect.php";
?>

td.speedhead  { 
font-weight: 600;
font-size: 13px;
color: #ffffff;
padding: 5px 10px;
border-bottom: 1px solid #ffffff;
border-top: 1px solid #ffffff;
text-transform: uppercase;
background-color: #000066;
}

td.speedlink  { 
font-size: 11px;
color: #ffffff;
padding: 10px;
}

td.speedlink a  { 
padding: 3px;
color: #ffffff;
text-decoration: none;
font-weight: 600;
border-bottom: 1px solid #ffffff;
}

td.speedlink a:hover { 
color: #ffffff;
text-decoration: none;
font-weight: 600;
background-color: #6666cc;
}

td.profile  td{ 
color: #ffffff;
}

.link {
    cursor: pointer;
    behavior:url('<?=$CONST_LINK_ROOT.$skin->Path?>/speeddating/link.htc');
}
/* Only for Mozilla/NN6*/
tr.link:hover,td.link:hover{
    background-color: #CCCCCC;
    color: #FFFFFF;
    cursor: pointer;
}
