var apiKey,
    session,
    sessionId,
    token,
    response;

$(document).ready(function() {
  // Make an Ajax request to get the OpenTok API key, session ID, and token from the server
  $.get(SAMPLE_SERVER_BASE_URL + '/session.php', function(resp) {
    
    var res = $.parseJSON(resp);
    
    apiKey = res.apiKey;
    sessionId = res.sessionId;
    token = res.token;

    initializeSession();
  });
  //initializeSession();
});

function initializeSession() {

  session = OT.initSession(apiKey, sessionId);

  var connectionCount = 0;
  session.on("connectionCreated", function(event) {
   connectionCount++;
   if(connectionCount == 2) {
   var counter = 0;
    var myInterval = setInterval(function () {
      //alert(counter);
      var user_type = '';

      $.post( "<?=CONST_LINK_ROOT?>/chat/web/send_msg.php", { time: counter, key: 'addTime',session_id: sessionId,uType: user_type },  function( data ) {
            });
      ++counter;
    }, 5800);
    
   
   }
   console.log(event);
   //displayConnectionCount();
  });
  session.on("connectionDestroyed", function(event) {
     connectionCount--;
    console.log(event);
     //displayConnectionCount();
  });

  // Subscribe to a newly created stream
  session.on('streamCreated', function(event) {
   subscriber = session.subscribe(event.stream, 'subscriber', {
      insertMode: 'append',
      width: '100%',
      height: '100%',
    });
    subscriber.subscribeToVideo(true); // video off
  });

 
  // Connect to the session
  session.connect(token, function(error) {
    // If the connection is successful, initialize a publisher and publish to the session
    if (!error) {
      var publisher = OT.initPublisher();
      publisher.publishAudio(false);
      publisher.publishVideo(true);
      session.publish(publisher);
    } else {
      console.log('There was an error connecting to the session: ', error.code, error.message);
    }
  });

  session.on('sessionDisconnected', function(event) {
    alert('You were disconnected from the session.', event.reason);
  });
}

// Text chat
var form = document.querySelector('form');
var msgTxt = document.querySelector('#msgTxt');

// Send a signal once the user enters data in the form
form.addEventListener('submit', function(event) {
  event.preventDefault();
  session.signal({
      type: 'chat',
      data: msgTxt.value
    }, function(error) {
      if (error) {
      console.log("signal error ("+ error.name+ "): " + error.message);
      } else {
        console.log("signal sent.");
        msgTxt.value = '';
      }
    });


    session.on('signal:chat', function(event) {
    var msg = document.createElement('p');
    var msgHistory = document.getElementById('history');

    msg.innerHTML = event.data;
    msg.className = event.from.connectionId === session.connection.connectionId ? 'mine' : 'theirs';
    msgHistory.appendChild(msg);
    msg.scrollIntoView();
    event.data = '';

    });
});

