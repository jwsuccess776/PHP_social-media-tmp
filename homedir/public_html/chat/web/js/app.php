<?php
$appendUrl = "";
if(isset($_GET['receive_id'])){
  $appendUrl = "?receive_id=".$_REQUEST['receive_id'];
}
else if(isset($_GET['to_uid'])){
  $appendUrl = "?to_uid=".$_REQUEST['to_uid'];
}
?>
<script type="text/javascript">

var SAMPLE_SERVER_BASE_URL = '<?=CONST_LINK_ROOT?>/chat/web';
//alert(SAMPLE_SERVER_BASE_URL);
var apiKey,
    session,
    sessionId,
    token,
    response;
$(document).ready(function() {
   var loginSession = '<?php echo $Sess_UserId; ?>';
  // Make an Ajax request to get the OpenTok API key, session ID, and token from the server
  $.get(SAMPLE_SERVER_BASE_URL + '/session.php<?=$appendUrl?>', function(resp) {
    console.log(resp);
    var res = $.parseJSON(resp);
    
    apiKey = res.apiKey;
    sessionId = res.sessionId;
    token = res.token;
    initializeSession();
  });
  
});

function initializeSession() {
    session = OT.initSession(apiKey, sessionId);

    var connectionCount = 0;
    var counter = 1;
    var user_type = '<?php echo $Sess_UserType; ?>';
    var userId = '<?php echo $Sess_UserId; ?>';

  session.on("connectionCreated", function(event) {
    
    connectionCount++;
    $('#msg_submit').click();    
    $('#disconnect').css('display','block');

  });
  
  session.on("connectionDestroyed", function(event) {
    $('#disconnect').css('display','none');
    connectionCount--;
    $.post( "<?=CONST_LINK_ROOT?>/chat/web/send_msg.php", { key:'addTime', time: counter, uid: userId,session_id: sessionId,uType: user_type }, 
     function( data ) {
           alert('Chat has ended', event.reason);
           $('#close').css('display','block');
           $('.featherlight-close-icon').css('visibility','visible');
      });
  });

   
  // Subscribe to a newly created stream
  session.on('streamCreated', function(event) {
    subscriber = session.subscribe(event.stream, 'subscriber', {
      insertMode: 'append',
      width: '100%',
      height: '100%',
    });
  
      //subscriber.subscribeToAudio(true); // audio off
      //subscriber.setStyle({buttonDisplayMode: "off"}); // button hide
 
  });


  // Connect to the session
  session.connect(token, function(error) {

    // If the connection is successful, initialize a publisher and publish to the session
    if (!error) {
      var publisher = OT.initPublisher();
      //publisher.publishAudio(false);
      //publisher.setStyle({buttonDisplayMode: "off"});
      session.publish(publisher);
    } else {
      console.log('There was an error connecting to the session: ', error.code, error.message);
    }
  });

  session.on('sessionDisconnected', function(event) {
    localStorage.removeItem('chatWindowOpen');
    localStorage.removeItem('chatUrl');
    $('#disconnect').css('display','none');

    $.post( "<?=CONST_LINK_ROOT?>/chat/web/send_msg.php", { key:'addTime', time: counter, uid: userId,session_id: sessionId,uType: user_type }, 
     function( data ) {
           alert('Your Chat session has ended', event.reason);
           $('#close').css('display','block');
           $('.featherlight-close-icon').css('visibility','visible');
      });
    
  });
}
// Text chat
var form = document.querySelector('form');
var msgTxt = document.querySelector('#msgTxt');
var userId = '<?php echo $Sess_UserId; ?>';
var userName = '<?php echo $Sess_UserName; ?>';
var user_type = '<?php echo $Sess_UserType; ?>';
//console.log(userId);
// Send a signal once the user enters data in the form
form.addEventListener('submit', function(event) {
  event.preventDefault();
  session.signal({
      type: 'chat',
      //data: { msg:msgTxt.value , uid: userId, uName:userName, uType:user_type }
      data: msgTxt.value
    }, function(error) {
      if (error) {
      console.log("signal error ("+ error.name+ "): " + error.message);
      } else {
        
        console.log("signal sent (" + userId + "," + userName + ")");
        msgTxt.value = '';
      }
    });

    

    session.on('signal:chat', function(event) {
        var str = event.data;
        console.log(event);
        var imgExists = str.indexOf('<div class="chat_image"><img id="');

        if (imgExists > -1) {
            var i = imgExists + 33;
                id  = str.substr(i);
                id = id.substr(0, id.indexOf('"'));
                id_length = id.length;
                
                var j = imgExists + 40 + id_length;
                img_url = str.substr(j);
                img_url = img_url.substr(0, img_url.indexOf('"'));
        }
        
      var user_type = '';
      var substring = "div";
      var identify_img = event.data.includes(substring);

      var msg = document.createElement('p');
      var msgHistory = document.getElementById('history');
      msg.className = event.from.connectionId === session.connection.connectionId ? 'me' : 'their';
      
      if(event.data != ""){
    
        var messageData = event.data;
        /*$.post( "<?=CONST_LINK_ROOT?>/chat/web/send_msg.php", {key:'messageCheck',msg_text:messageData, time: counter, uid: userId, session_id: sessionId }*/
        $.post( "<?=CONST_LINK_ROOT?>/chat/web/send_msg.php", {key:'messageCheck',msg_text:messageData}, 
             function( messageCheckData ) {
                if(messageCheckData == 0 ) {
                    msg.innerHTML = "This is an inappropriate message";
                    return false;
                } else {
                  //alert(messageData);
                  msg.innerHTML = messageData;
                }
          });
        
        
        //msg.innerHTML = event.data;  
       
        msgHistory.appendChild(msg);
        $('#history').animate({scrollTop:$(document).height()}, 'slow');
        event.data = '';
      
      }
      });
});


function show_image(id,img_data) {

  var user_type = '';
  var userId = '<?php echo $Sess_UserId; ?>';

    var response = confirm("Charges apply to see image.");
    if(response) {
      $.post( "<?=CONST_LINK_ROOT?>/chat/web/send_msg.php", { key:'unlockImage', uid: userId,session_id: sessionId,uType: user_type,imgId : id }, 
       function( data ) {
          if(data == 0 ) {
              alert("You have no sufficient Tokens, Please Purchase");
          } else {
              //var img_name = data.split(".");
              var img_id = id; 
              var imageurl = img_data;
              jQuery('#'+img_id).attr('src' , imageurl);          
          }
        });
        
    }
          
}

$('.check_emoji').click(function() {
  $('.emoji_box').slideToggle('slow');
});

$('.emoji_img').click(function() {
  var emoji_val = $(this).text(); 
  var msgValue = $('#msgTxt').val();
  var newMsg = msgValue+' '+emoji_val;
  $('#msgTxt').val(newMsg);
});

$('#input-chat-file').change(function() {
           
    var userId = <?php echo $Sess_UserId; ?>;
    var user_type = '';
      
    $('#loader_img').css('display','block');
      $.ajax({
        url: '<?=CONST_LINK_ROOT?>/chat/web/ajax.php?id='+userId,
        type: "POST",
        processData : false,
        contentType : false,
        data: new FormData($('#target')[0]),
        success: function(data) {

          $('#loader_img').css('display','none');
          $('#input-chat-file').val('');
          var image_name = data.split(".");

          img_data = '<div class="chat_image"><img id="'+image_name[0]+'" src="<?=CONST_LINK_ROOT?>/chat/web/upload/'+data+'" style="width: 100%"></div>';

          session.signal({
            type: 'chat',
            data: img_data
          }, function(error) {
            if (error) {
              console.log("signal error ("+ error.name+ "): " + error.message);
            } else {
              console.log("Image sent.");
              msgTxt.value = '';
            }
          });
        },
        error: function(){
          alert('error');
        }
      });
      
    });
</script>