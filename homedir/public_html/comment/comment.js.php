<script language="javascript">
var comment_conteiner = '';
function getComments(url, id){
    if (id) comment_conteiner = id;
    now = new Date;
    new Ajax.Request(
        '<?=CONST_LINK_ROOT?>/comment/list.php?'+url+'&'+now.getTime(),
        {
            method: 'get',
            onComplete: function (transport) {
                if (transport.responseText != '') {
                    try {
                        var response = transport.responseText;
                       try{
                              eval("ajaxRes = " + response + ";");
                          }
                          catch(e) {
                              a=1;
                          }
                    } catch(E) {
                        a=1;
                    }
                displayComment(comment_conteiner, ajaxRes.list);
                }
            }
        }
    );
}

function addComment(url,id){
    new Ajax.Request(
        '<?=CONST_LINK_ROOT?>/comment/add.php?'+url,
        {
            method: 'post',
            postBody: 'text='+document.getElementById(id).value,
            onComplete: function (transport) {
                if (transport.responseText != '') {
                    try {
                        var response = transport.responseText;
                       try{
                              eval("ajaxRes = " + response + ";");
                          }
                          catch(e) { a=1;}
                    } catch(E) { a=1;}
                    if (ajaxRes.error == 'YES') {
                        alert(ajaxRes.text);
                    } else {
                        document.getElementById(id).value = '';
                    }
                    if (comment_conteiner != '') getComments(url);
                }
            }
        }
    );
    if (comment_conteiner != '') displayProgress(comment_conteiner);
}

function deleteComment(url,id){
    new Ajax.Request(
        '<?=CONST_LINK_ROOT?>/comment/delete.php?'+url,
        {
            method: 'post',
            postBody: 'id='+id,
            onComplete: function (transport) {
                if (transport.responseText != '') {
                    try {
                        var response = transport.responseText;
                       try{
                              eval("ajaxRes = " + response + ";");
                          }
                          catch(e) {
                              a=1;
                          }
                    } catch(E) {
                        a=1;
                    }
                    if (ajaxRes.error == 'YES') {
                        alert(ajaxRes.text);
                    } 
                    if (comment_conteiner != '') getComments(url);
                }
            }
        }
    );
    if (comment_conteiner != '') displayProgress(comment_conteiner);
}


function displayComment(id, list){
    document.getElementById(id).innerHTML = list;
}

function displayProgress(id){
    document.getElementById(id).innerHTML = '<img src="<?=CONST_IMAGE_ROOT?>ajax-loader.gif" align="absmiddle" border=0>';
}
</script>