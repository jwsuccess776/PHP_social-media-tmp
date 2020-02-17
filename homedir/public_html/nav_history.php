<?php

/*****************************************************

* © copyright 1999 - 2020 iDateMedia, LLC.

*

* All materials and software are copyrighted by iDateMedia, LLC.

* under British, US and International copyright law. All rights reserved.

* No part of this code may be reproduced, sold, distributed

* or otherwise used in whole or in part without prior written permission.

*

*****************************************************/

######################################################################

#

# Name:                 nav_history.php

#

# Description:  Home page destination for traffic sent by affiliates

#

# Version:               7.2

#

######################################################################

// Following script provides redirection functionality.

// Restore the previous request when it is needed.

if(!is_array($_SESSION['HISTORY_PAGE']))

{

	$_SESSION['HISTORY_GET_VARS'] = array(0,0,0);

	$_SESSION['HISTORY_POST_VARS'] = array(0,0,0);

	$_SESSION['HISTORY_PAGE'] = array(0,0,0);

}

elseif($_GET['restore_request']

       && ($_SESSION['HISTORY_PAGE'][0] == $_SERVER['PHP_SELF']

           || $_SESSION['HISTORY_PAGE'][1] == $_SERVER['PHP_SELF'])

      )

{

	if($_SESSION['HISTORY_LAST_RESTORE_ID'] != $_GET['restore_request'])

	{

		array_shift($_SESSION['HISTORY_GET_VARS']);

		array_push($_SESSION['HISTORY_GET_VARS'], 0);

		array_shift($_SESSION['HISTORY_POST_VARS']);

		array_push($_SESSION['HISTORY_POST_VARS'], 0);

		array_shift($_SESSION['HISTORY_PAGE']);

		array_push($_SESSION['HISTORY_PAGE'], 0);

	}

	$_SESSION['HISTORY_LAST_RESTORE_ID'] = $_GET['restore_request'];

	$_GET = $_GET = $_SESSION['HISTORY_GET_VARS'][0];

	$_POST = $_POST = $_SESSION['HISTORY_POST_VARS'][0];

}

//echo "<pre>"; var_dump($_SESSION['HISTORY_GET_VARS']); echo "</pre>";



// Saves current request to the session variables.

// Use it to guarantee correcnt work of get_prev_page_url() function.

// Call this function on the page where get_prev_page_url() is used

// and when you want to save page to history.

// Don't call this function when it is data updating action

// (e.g. saving or removing data from the database)

function save_request(){



	if($_SESSION['HISTORY_GET_VARS'][0] != $_GET

        || $_SESSION['HISTORY_POST_VARS'][0] != $_POST

        || $_SESSION['HISTORY_PAGE'][0] != $_SERVER['PHP_SELF'])

	{

		array_unshift($_SESSION['HISTORY_GET_VARS'], $_GET);

		array_pop($_SESSION['HISTORY_GET_VARS']);





		array_unshift($_SESSION['HISTORY_POST_VARS'], $_POST);

		array_pop($_SESSION['HISTORY_POST_VARS']);

		array_unshift($_SESSION['HISTORY_PAGE'], $_SERVER['PHP_SELF']);

		array_pop($_SESSION['HISTORY_PAGE']);

	}

}



// Gets previously saved request url with restore parameter.

function get_prev_page_url(){

	if($_SESSION['HISTORY_PAGE'][1]){

		srand((double) microtime() * 1000000);

		return "http://".$_SERVER['HTTP_HOST'].$_SESSION['HISTORY_PAGE'][1]."?restore_request=".rand(1,1000000000);

	}else

		return '';

}



// Gets name of previously saved page.

function get_prev_page_name(){

	if($_SESSION['HISTORY_PAGE'][1]){

		$const_name = substr($_SESSION['HISTORY_PAGE'][1], strrpos($_SESSION['HISTORY_PAGE'][1], '/') + 1);

		$const_name = 'PAGE_NAME_'.strtoupper(substr($const_name, 0, -4));

		$page_name = eval("echo \$const_name;");

		if($const_name == 'PAGE_NAME_PRGRETUSER')

		{

			global $link;

			$sql_query = "SELECT mem_username FROM members WHERE mem_userid = ".$_SESSION['HISTORY_GET_VARS'][1]['userid'];

			$sql_result = mysqli_query($globalMysqlConn, $sql_query) or die(mysqli_error());

			$page_name = sprintf($page_name, mysqli_num_rows($sql_result));

		}

		if($page_name == $const_name)

			return '';

		else

			return $page_name;

	}else

		return '';

}



// Gets name of link to previously saved page.

function get_back_link_name(){

	return ($page_name = get_prev_page_name()) == '' ? BUTTON_BACK : BUTTON_BACK_TO.' '.$page_name;

}



//

function can_navigate_back(){

	return $_SESSION['HISTORY_PAGE'][1] ? true : false;

}

?>