// replace these values with those generated in your TokBox Account
var apiKey,
    session,
    sessionId,
    token,
    response,
    connectionCount;

var apiKey = "45840112";
var sessionId = "1_MX40NTg0MDExMn5-MTUwNTQ4MzI5NTM0OH5IcGxzR2xQWG5RWjVaVmdOQndzaDlmNEV-fg";
var token = "T1==cGFydG5lcl9pZD00NTg0MDExMiZzaWc9MTdhOGNhODExMWNiYTA1ZDNhMGZjMzMwNDhlMGUzM2QwNDk1NzNmMDpzZXNzaW9uX2lkPTFfTVg0ME5UZzBNREV4TW41LU1UVXdOVFE0TXpJNU5UTTBPSDVJY0d4elIyeFFXRzVSV2pWYVZtZE9RbmR6YURsbU5FVi1mZyZjcmVhdGVfdGltZT0xNTA1NDgzMzIzJm5vbmNlPTAuMTI1NTk1NTYxNzkxNjIwMjQmcm9sZT1wdWJsaXNoZXImZXhwaXJlX3RpbWU9MTUwODA3NTMyMiZpbml0aWFsX2xheW91dF9jbGFzc19saXN0PQ==";
	
// (optional) add server code here
initializeSession();


// Handling all of our errors here by alerting them
function handleError(error) {
  if (error) {
    alert(error.message);
  }
}

function initializeSession() {
  session = OT.initSession(apiKey, sessionId);
  session.on("connectionCreated", function(event) {
     connectionCount++;
     document.getElementById("msg_submit").click();
  });
  // Subscribe to a newly created stream

  // Create a publisher
  var publisher = OT.initPublisher('publisher', {
    insertMode: 'append',
    width: '100%',
    height: '100%'
  }, handleError);

  // Connect to the session
  session.connect(token, function(error) {
    // If the connection is successful, publish to the session
    if (error) {
      handleError(error);
    } else {
      session.publish(publisher, handleError);
    }
  });
	  session.on('streamCreated', function(event) {
	  session.subscribe(event.stream, 'subscriber', {
	    insertMode: 'append',
	    width: '100%',
	    height: '100%'
	  }, handleError);
	});
}
//alert(session);
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

    if(event.data != ""){
    msg.innerHTML = event.data;
    msg.className = event.from.connectionId === session.connection.connectionId ? 'mine' : 'theirs';
    msgHistory.appendChild(msg);
    msg.scrollIntoView();
    event.data = '';
    }

    });
});

