<script language="javascript"> var __FULL_PATH = "<?=$CONST_LINK_ROOT?>";</script>
<?php
// prototype.js creating issue in admin index.php graph so not loading this file for admin section
if (!stristr(getcwd(),'/admin')) { ?>
<script type="text/javascript" src="<?=$CONST_LINK_ROOT?>/lightbox/js/prototype.js"></script>
<?php } ?>
<script type="text/javascript" src="<?=$CONST_LINK_ROOT?>/lightbox/js/scriptaculous.js?load=effects,builder"></script>

<script type="text/javascript" src="<?=$CONST_LINK_ROOT?>/lightbox/js/lightbox.js"></script>

<script type="text/javascript" src="<?=$CONST_LINK_ROOT?>/ajax_form.js.php"></script>

<link rel="stylesheet" href="<?=$CONST_LINK_ROOT?>/lightbox/css/lightbox.css" type="text/css" media="screen" />

<link rel="stylesheet" type="text/css" href="<?=$CONST_LINK_ROOT?>/sifr3/css/sifr.css">

<script src="<?=$CONST_LINK_ROOT?>/sifr3/js/sifr.js" type="text/javascript"></script>

<script src="<?=$CONST_LINK_ROOT?>/sifr3/js/sifr-config.js" type="text/javascript"></script>

<script src="https://static.opentok.com/v2/js/opentok.js" charset="utf-8"></script>

<?php 

	if($Sess_UserId) {
			?>
			<!-- <script language="javascript">
				setInterval(function(){
				   window.location.reload(1);
				}, 20000);
			</script> -->
			<div class="open-button form-popup">
			<?php 
			$chat_receiver = "SELECT * FROM members_opentok_chat WHERE read_st='0' AND to_uid='".$Sess_UserId."'";
			$result=mysqli_query($globalMysqlConn, $chat_receiver) or die(mysqli_error($globalMysqlConn));
			if (mysqli_num_rows($result) > 0) {
			    // output data of each row
			    while($row = mysqli_fetch_assoc($result)) { 
			        $id = $row["id"];
			        $data = $row["uid"];
			        $session_id = $row["session_id"];
			        $username = $row["username"];
			     ?>
					<div class="form-container" id="myForm<?php echo $data; ?>">
					    <h1><?php echo $username; ?> IM Video Chat</h1>
					    <button class="btn"onclick="openChat()">Yes</button>
					    <button type="button" class="btn cancel" onclick="location.href='?delete=delte&id=<?php echo $session_id; ?>'">No</button>
					</div>
					<script type="text/javascript">
						function  openChat() {
					 		window.open("<?php echo $CONST_LINK_ROOT ?>/chat/web/?receive_id=<?php echo $data ?>", "_blank", "width=900,height=505,toolbar=0,directories=0,menubar=0,status=0,location=0,scrollbars=0,resizable=yes");
						 	document.getElementById("myForm<?php echo $data; ?>").style.display = "none";
						 }
						function closeForm() {
							document.getElementById("myForm<?php echo $data; ?>").style.display = "none";
						}
						</script>
					
			     <?php
			    }
			}
		?>
		</div>
	<?php  } ?>


<?php if (stristr(getcwd(),'/admin')) { ?>

<script src="http://cdn.ckeditor.com/4.11.3/standard/ckeditor.js"></script>

<script type="text/javascript" src="fusion/FusionCharts.js"></script>

<?php }  ?>  
<?php 
if($_GET){
    if(isset($_GET['delete'])){
    	echo "<script>closeForm();</script>";
    	$delete1 = "DELETE FROM `members_opentok_chat` WHERE session_id = '$session_id'";
        $result = mysqli_query($globalMysqlConn,$delete1) or die(mysqli_error());

        $delete2 = "DELETE FROM `members_videochat` WHERE session_id = '$session_id'";
        $result = mysqli_query($globalMysqlConn,$delete2) or die(mysqli_error());
        
        echo "<script>window.location = document.referrer;</script>"; 
    }
}
?>
<style type="text/css">
	
	/* Button used to open the contact form - fixed at the bottom of the page */
.open-button {
  /* background-color: #555; */
  color: white;
  /* padding: 16px 20px; */
  /* border: none; */
  cursor: pointer;
  opacity: 0.8;
  position: fixed;
  bottom: 23px;
  left: 28px;
  /* width: 280px; */
  z-index: 99999;
}


/* Add styles to the form container */
.form-container {
  /* width: 210px; */
  padding: 10px;
  background-color: white;
  margin-top: 10px;
}

/* Set a style for the submit/login button */
.form-container .btn {
  background-color: #4CAF50;
  color: white;
  padding: 13px 18px;
  border: none;
  cursor: pointer;
  /* width: 100%; */
  margin-bottom:10px;
  opacity: 0.8;
}

/* Add a red background color to the cancel button */
.form-container .cancel {
  background-color: red;
}

/* Add some hover effects to buttons */
.form-container .btn:hover, .open-button:hover {
  opacity: 1;
}
.form-container h1 {
    font-size: 18px;
}

.form-container a.btn {
    font-size: 14px;
}
</style>
