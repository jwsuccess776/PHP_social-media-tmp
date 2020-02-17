<?php include('../../db_connect.php'); ?>
<?php include('../../session_handler.inc'); ?>
<html>
	<head>
  		<title>Video Chat</title>
		<style>

			html , body { width: 100% !important; height:100% !important; margin:0px; }
  			.OT_publisher{ z-index:2; right :10px; bottom:10px;  margin-right:26% !important; height: 150px !important;width: 175px !important; position: absolute !important;}
			.featherlight .featherlight-close {visibility: hidden;}
  			.OT_subscriber{ width:100% !important; height:100% !important; float:left;position: absolute !important;top:0px;}

			#OT_chat,#OT_chat_tests{ width: 100%; background: rgba(0, 0, 0, 0.12); height: 100%; }
  			#OT_chat{ bottom: 0; }
  			#OT_chat textarea,#OT_chat_tests textarea{vertical-align: bottom; position: relative; bottom: 0;}
  			#OT_chat_tests form{margin: 0; float: left;width: 100%;}
  			#text-field{ width: 100%; position: absolute; vertical-align: bottom; bottom: 0px; }
		  	.chat-footer{ margin: auto; padding: 0px 5%; float: none; width: 90%; text-align: center; }
		  	.chat-footer li{ list-style: none; float: left; text-align: center; }
		  	.chat-footer li input{ width: 100%; padding: 6px; font-size: 12px; }
		  	.chat-footer li img{ margin: 5px 0px 0px 0px; }
		  	
		  	/*.chat-footer li:nth-child(1){ width: 70%; }
			.chat-footer li:nth-child(2),.chat-footer li:nth-child(3){ width: 15%; }​
		  	.chat-footer li:nth-child(2) img, .chat-footer li:nth-child(3) img{ margin: 5px 0px 0px 0px; }*/
		  	#textchat{float: left; width: 25%; position: relative;}
		  	#videos{float: left; width: 75%; position: relative; height: 100%;}
  			#OT_chat .view,#OT_chat_tests .view{ width: 92%; background-color: #FFFFFF; margin: 4%; position: absolute; bottom: 45px; top: 18px; overflow: auto;}
 			#OT_chat h3,#OT_chat_tests h3{ width: 90%; text-align: left; margin: 0px;  padding: 5px;}
			#OT_chat .view .msg .provider,#OT_chat_tests .view .msg .provider{ font-size: 10px; margin-bottom: 4px; float: left; width: 100%; border-bottom: 1px solid #c7c7c7; padding-bottom: 3px; color: #676767; }
			#OT_chat .view .msg .time,#OT_chat_tests .view .msg .time{ margin-top: 10px; padding-top: 2px; border-top: 1px solid #c7c7c7; color: #676767; font-size: 10px; text-align: right; }
  			#OT_chat .view .msg,#OT_chat_tests .view .msg{ width: 90%; border: 1px solid #ccc; padding: 5px 3% 5px 3%; margin: 2%; border-radius: 5px; background: #eee; font-family: sans-serif; color: #6d6d6d; font-size: 12px; }
  			#OT_chat .view .msg_date,#OT_chat_tests .view .msg_date{ margin: 5px 0px; padding: 3px 0px; text-align: center; background: #ddd; border-radius: 5px; font-family: sans-serif; font-size: 14px; }
  			#OT_chat button, #OT_chat_tests button{ position: absolute; bottom: 13px; right: 14px; border: 1px solid #ccc; border-radius: 3px; color: #656565;}
  			#oneway, #disconnect, #twoway, #close{left:20px; left: 20px; padding: 10px; background: #00bcd4; color:#fff; border-radius: 4px; z-index: 10;  font-size: 13px; line-height: 21px;  text-align: center; vertical-align: middle; border: none; cursor: pointer;}
		  	#connect{bottom: 10px;}
		  	#disconnect{bottom: 10px;}
		  	#reconnect{bottom:60px;}
		  	#OT_archieves{float: left; text-align: center; background: rgba(0, 0, 0, 0.12); min-width: 187px; position: absolute;}
  			#OT_archieves h3{ font-family: sans-serif; font-weight: normal;margin: 10px 0px 2px 0px; margin: 5px 0px 5px 0px; cursor: pointer;}
  			#OT_archieves ul{float: left; width: 96%; margin: 0; padding: 0 2%;}
  			#OT_archieves ul li{list-style: none; margin: 5px 0px; background: #FFF; padding: 5px 0px;}
			#OT_archieves ul li a{text-decoration: none; background: #fff;z-index: 10}
			.mobile-link{display:none;}
			.emoji_box { font-size: 25px; margin: 0% 3% 17% 3%; background: rgb(255, 255, 255); width: 94%; height: 125px; padding: 0px; overflow: scroll;}
 			.emoji_box li {list-style:none;display: inline-flex;}
			.emoji_box li a {text-decoration: none;}
   			
   			p.me { float: right; margin: 5px 8px; padding: 6px 13px; background-color: rgba(171, 247, 174, 0.5); border-radius: 30px; clear: both; }
	        p.their { margin: 5px 8px; padding: 6px 13px; background-color: rgba(81, 243, 87, 0.5); border-radius: 30px; float: left; clear: both; }
   			@media (max-width: 767px){
	      		html, body{
	      			position: relative;
	      		}
	      		#videos{float: left; width: 100%; position: absolute; height: 100%;}
	      		#textchat{ float: left; width: 100%; position: absolute; bottom: 0px; z-index: 3; max-height: 200px; height: 100%;}
	      		.OT_subscriber{ width:99% !important;; }
  				/*#OT_chat, #OT_chat_tests{width:100%; float:left;}*/
  				#OT_archieves{position:relative; width:100%; margin-bottom:10px; text-align:left;}
 				#OT_archieves h3{margin-left:10px;}
 				#OT_archieves{display:none; }
  				.mobile-menu{display:block;}
				.showinmobile{display:block;}
				#OT_chat, #OT_chat_tests{display:block; background: transparent;}
				.mobile-link{display:block;}
				
				.mobile-link{padding:0px; margin:0px; list-style:none; float:left; width:100%; border-bottom: 1px solid #ccc;}
				.mobile-link li{ padding: 10px 0;  float: left;  width: 50%;  text-align: center;  background: #efefef; cursor:pointer;  font-family: sans-serif; font-size:14px; }
				.mobile-link li.presriptions{border-right:1px solid #ccc; width:49.7%;}
				.OT_publisher{ width: 60px !important; height: 60px !important; margin-right: 2% !important; bottom: 205px;}

				#OT_chat_tests h3{ display: none; }
				#OT_chat_tests .view{
				    width: 96%;
				    margin: 2%;
				    bottom: 30px;
				    top: 0px;
				    background: transparent;
				}
				/*.chat-footer li:first-child{ width: 80%; }
				.chat-footer li:nth-child(2), .chat-footer li:nth-child(3) { width: 10%; }​*/

			}
			@media (max-width: 480px){
				.mobile-link li{width:49.7%;}
     		}
		</style>
	</head>
  	<body>
  
    	<script src="https://static.opentok.com/v2/js/opentok.js" charset="utf-8"></script>
   		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
   		 <div id="videos">
   		 	<p>&nbsp;</p>
	        <div id="subscriber"></div>
	        <div id="publisher"></div>
	    </div>
		<div id="textchat" class="mobile-menu">
			<div class="showinmobile">
				<div class="text-chat-box">
					<div id="OT_chat_tests">
						<h3>Chat</h3>
						<div class="view" id="history"></div>
						<img id="loader_img" src = "../img/loading.gif" style="display:none;width: 25%;margin-left: 34%;max-width: 50px;position: relative;">
   						
   						<div id="text-field">
         
         					<form name="chat_form" id="target" method="post" enctype="multipart/form-data">
								<input type="hidden" name="user_id">
								<ul class="chat-footer">
									<li style="width: 80%;"> 
										<input type="text" placeholder="Input your text here" id="msgTxt">
									</li>
									<li style="width: 10%;">
										<img src="../img/emo.png" class="check_emoji">
									</li>
									<li style="width: 10%;">
										<label for="input-chat-file">
											<img src="../img/camera.png" class="input-chat-file" />
										</label>
									</li>
								</ul>
         						<!-- <button id="disconnect"></button> -->
								<input type="file" id="input-chat-file" name="fileupload" style="display: none;">
								<input type="submit" id="msg_submit" name="" style="visibility: hidden;">
							
							</form>

					    </div>

		    		 	<ul class="emoji_box" style="position: absolute;bottom: 0;margin-top: 2px;display: none;">
							
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F601</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F602</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F603</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F604</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F605</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F606</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F609</a></li>

							<li><a class="emoji_img" href="javascript:void(0)">&#x1f60a</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1f60b</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1f60c</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F60D</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F60F</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F612</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F613</a></li>

							<li><a class="emoji_img" href="javascript:void(0)">&#x1F614</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F616</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F618</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F61A</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F61C</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F61D</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F61E</a></li>

							<li><a class="emoji_img" href="javascript:void(0)">&#x1F620</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F621</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F622</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F623</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F624</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F625</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F628</a></li>

							<li><a class="emoji_img" href="javascript:void(0)">&#x1F629</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F62A</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F62B</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F62D</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F630</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F631</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F632</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F633</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F635</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F637</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F638</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F639</a></li>

							<li><a class="emoji_img" href="javascript:void(0)">&#x1F63A</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F63B</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F63C</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F63D</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F63E</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F63F</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F640</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F645</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F646</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F647</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F648</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F649</a></li>

							<li><a class="emoji_img" href="javascript:void(0)">&#x1F64A</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F64B</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F64C</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F64D</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F64E</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F64F</a></li>

							
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F640</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F645</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F646</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F647</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F648</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F649</a></li>

							<li><a class="emoji_img" href="javascript:void(0)">&#x2702</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x2705</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x2708</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x2709</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x270A</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x270B</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x270C</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x270F</a></li>

							<li><a class="emoji_img" href="javascript:void(0)">&#x2712</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x2714</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x2716</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x2728</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x2733</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x2734</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x2744</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x2747</a></li>

							<li><a class="emoji_img" href="javascript:void(0)">&#x274C</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x274E</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x2753</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x2754</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x2755</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x2757</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x2764</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x2795</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x2796</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x2797</a></li>

							<li><a class="emoji_img" href="javascript:void(0)">&#x27A1</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x27B0</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F680</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F683</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F684</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F685</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F687</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F689</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F68C</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F68F</a></li>

							<li><a class="emoji_img" href="javascript:void(0)">&#x1F691</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F692</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F693</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F695</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F697</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F699</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F69A</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F6A2</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F6A4</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F6A5</a></li>

							<li><a class="emoji_img" href="javascript:void(0)">&#x1F6A7</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F6A8</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F6A9</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F6AA</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F6AB</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F6AC</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F6AD</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F6B2</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F6B6</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F6B9</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F6BA</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F6BB</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F6BC</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F6BD</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F6BE</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F6C0</a></li>

							<li><a class="emoji_img" href="javascript:void(0)">&#x24c2</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F170</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F171</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F17E</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F18E</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F191</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F192</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F193</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F194</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F195</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F196</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F197</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F198</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F199</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F19A</a></li>

							<li><a class="emoji_img" href="javascript:void(0)">&#x1F567</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F566</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F565</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F564</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F563</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F562</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F561</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F560</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F55F</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F55E</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F55D</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F55C</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F52D</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F52C</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F515</a></li>

							<li><a class="emoji_img" href="javascript:void(0)">&#x1F509</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F507</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F506</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F505</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F504</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F502</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F501</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F500</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F4F5</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F4EF</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F4ED</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F4EC</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F4BC</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F4B6</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F4AD</a></li>

							<li><a class="emoji_img" href="javascript:void(0)">&#x1F46D</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F46C</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F465</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F42A</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F416</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F415</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F413</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F410</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F40F</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F40B</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F40A</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F409</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F408</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F407</a></li>
							<li><a class="emoji_img" href="javascript:void(0)">&#x1F406</a></li>
						</ul>   
					</div>
				</div>
			</div>
 		</div>
		<?php require_once ('js/app.php'); ?>  	
	</body>
</html>