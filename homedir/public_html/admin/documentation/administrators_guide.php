<?
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
# Name:         administrators_guide.php
#
# Description:  Company information page ('About Us')
#
# Version:      7.3
#
######################################################################
include('../../db_connect.php');
include('../../session_handler.inc');
?>
<html>

<head>
<meta http-equiv="Content-Language" content="en-gb">
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta name="GENERATOR" content="Microsoft FrontPage 5.0">
<meta name="ProgId" content="FrontPage.Editor.Document">
<LINK REL='StyleSheet' type='text/css' href='<?php echo $CONST_LINK_ROOT ?>/singles.css'>
<title><?php echo DOC_ADM_GUIDE_TITLE?></title>
</head>

<body >
  <table border="0" cellpadding="5" cellspacing="0" width="700" class="poptable">
    <tr>
      <td width="100%">
        <span class="pageheader">Administrators Guide</span>
        <hr>
    <B>        System Administration v2.1

        <p>The system administration panel is accessed via the member status

        graphic present in each page. If a user is of type 'administrator'

        (mem_type='A'), this graphic becomes enabled as a link to the

        administration main menu (admin/index.php). Once in the menu screen, the

        administrator is presented with a selection of links.&nbsp;</p>

        <p>These are as follows:</p></B>
        <hr>
          <table border="0" cellpadding="5" cellspacing="0" width="80%">
            <tr>
              <td><strong>Reports</strong></td>
              <td><strong>Settings</strong></td>
              <td><strong>Tools</strong></td>
            </tr>
            <tr>
              <td width="33%"><a href="#AdminGuide">Administration Guide</a></td>
              <td width="33%"><a href="#SetParameters">Set Parameters</a></td>
              <td width="34%"><a href="#AuthoriseAds">Authorise Profiles</a></td>
            </tr>
            <tr>
              <td width="33%"><a href="#CupidReport"><span lang="sv">Cupid</span> Report</a></td>
              <td width="33%"><a href="#Templates">Templates</a></td>
              <td width="34%"><a href="#MemberAdministration">Member Administration</a></td>
            </tr>
            <tr>
              <td width="33%"><a href="#BrowseAds">Browse Profiles</a></td>
              <td width="33%"><a href="#Languages">Languages</a></td>
              <td width="34%"><a href="#Send Mail">Send Mail</a></td>
            </tr>
            <tr>
              <td width="33%"><a href="#Demographics">Demographics</a></td>
              <td width="33%"><a href="#PremiumFunctions">Premium Functions</a></td>
              <td width="34%"><a href="#EmailExtract">Email Extract</a></td>
            </tr>
            <tr>
              <td><a href="#MemberPayments">Member Payments</a></td>
              <td><a href="#Options">Option Administration</a></td>
              <td><a href="#NewsOpt-outs">Newsletter Opt-outs</a></td>
            </tr>
            <tr>
              <td><a href="#UnconfirmedUsers">Unconfirmed Users</a> </td>
              <td><a href="#PaymentSystems">Payment Systems</a></td>
              <td><a href="#MemberAdministration">Inactive Members</a></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><a href="#PaymentServices">Payment Services</a></td>
              <td><a href="#DataExport">Database Backup</a></td>
            </tr>
            <tr>
              <td>&nbsp;</td>
              <td><a href="#Geography">Geography</a></td>
              <td><a href="#Stories">Stories</a></td>
            </tr>
            <tr>
              <td height="10">&nbsp;</td>
              <td><a href="#MailTemplates">Mail Templates</a></td>
              <td><a href="#News">News</a></td>
            </tr>
            <tr>
              <td height="10">&nbsp;</td>
              <td>&nbsp;</td>
              <td><a href="#Flirt">Flirt</a></td>
            </tr>
            <tr>
              <td height="10">&nbsp;</td>
              <td>&nbsp;</td>
              <td><a href="#Optimizedatabase">Optimize database</a> </td>
            </tr>
            <tr>
              <td height="10">&nbsp;</td>
              <td>&nbsp;</td>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td height="10"><strong>Events</strong></td>
              <td><strong>Affiliates (if installed) </strong></td>
              <td><strong>Speed Dating (if installed)</strong></td>
            </tr>
            <tr>
              <td height="10"><a href="#AddEvents">Add Events</a></td>
              <td><a href="#AuthoriseAffiliates">Authorise Affiliates</a></td>
              <td><a href="#ManageEvents">Manage Events</a></td>
            </tr>
            <tr>
              <td height="10"><a href="#ApproveEvents">Approve Events</a></td>
              <td><a href="#AffiliatePayments">Affiliate Payments</a></td>
              <td><a href="#ManageVenues">Manage Venues</a></td>
            </tr>
            <tr>
              <td height="10"><a href="#ApproveReviews">Approve Reviews</a></td>
              <td><a href="#AffiliateBanners">Affiliate Banners</a></td>
              <td><a href="#SDStories">Stories</a></td>
            </tr>
            <tr>
              <td height="10">&nbsp;</td>
              <td><a href="#AffiliatesAdministration">Affiliates Administration</a></td>
              <td><a href="#SDReports">Reports</a></td>
            </tr>
            <tr>
              <td height="10">&nbsp;</td>
              <td><a href="#AffiliatesPerformance">Affiliates Performance</a></td>
              <td><a href="#WaitingList">Waiting List</a></td>
            </tr>
          </table>
        </div>
        <hr>
        <p><strong>Reports Section </strong></p>
        <hr>
        <p><a name="AdminGuide"><span>Administration Guide </span></a> </p>
        <p>This guide.</p>
        <hr>
        <a name="CupidReport" id="CupidReport"><span>Cupid Report</span></a>
        <p>The cupid report is an HTML based e-mail that is sent to
        all members that subscribe to it via the search screen. Each member's
        saved search criteria is extracted and the profiles table is analyzed for
        all records that match the given search criteria where those profiles
        have been created or updated since the last cupid run.</p>
        <p>The mail contains a list of profiles, including photos where
        applicable, and a link to the member's profile. </p>
        <p>The member can unsubscribe by clicking a link at the bottom of the
          e-mail. If the user would like to change their search profile, they can
          do this from the search screen.</p>
        <hr>
        <a name="BrowseAds">Browse Profiles</a>
        <p>This is a simple profile browser. It displays the text, username and password of profiles in descending order based on the date the profile was created. It can be useful if you see a member that has written an profile that you consider offensive to the point of wanting to remove them from the system. You can browse the profiles and get the username and password, log in as that person and delete them.</p>
        <hr>
        <a name="Demographics">Demographics</a>
        <p>This is a report that provides you with a graph of member numbers so that you can quickly see how the site is growing and what your demographics are. The top table shows the profile distribution. The table is read by row (not column).&nbsp;</p>
        <p>For example, reading along the top line, the first number is female profiles seeking females. The second number in the top line is female profiles seeking males and so on...</p>
        <p>The figures underneath this table show the total number of members registered and by gender; also the total number of profiles and by gender.</p>
        <hr>
        <a name="MemberPayments">Member Payments</a>
        <p>This is a report that provides you with details of all payments made by customers within the selected time period. Simply select the year and month of interest and click &quot;Get Report&quot;. </p>
        <p>You can aslo export the report in CSV format for MS Excel and other packages that support CSV format by clicking the <em>Get CSV report</em> link at the bottom of the screen. </p>
        <hr>
        <a name="UnconfirmedUsers">Unconfirmed Users </a>
        <p>If the email confirmation is enabled, this report will display all those users that have registered but not clicked the confirmation link in their registration emails, therefore not capable of logging in to the system.. </p>
        <hr>
        <p><strong>Settings Section </strong></p>
        <hr>
        <a name="SetParameters">Set Parameters</a>
        <p>The following is a description of the input fields within the Set Parameters page.</p>
        <p><b>Authorise Profiles</b> - by clicking this box you enable automatic authorization of profiles. This means that all profiles will automatically appear in the system without requiring administrator approval first. It is not recommended that this is used.&nbsp;</p>
        <p><b>Trial Premium</b> - This is the setting that determines quantity of free-of-charge PREMIUM membership days which user receives at registration.</p>
        <p><b>Price Index</b> - the price index is the central area for setting the prices of the premium membership plans. Changes to the prices here will be propagated to the affiliate scheme page (accessed from the home page to describe the affiliate scheme). It also is used to calculate affiliate earnings and is displayed in the join page. This is the single hub for price setting. N.B The prices here are expressed for the whole period and not for their monthly equivalents prices. </p>
        <p><b>Max Picture Size</b> - This is the setting that determines how large you allow individual pictures to be when members upload them to their profiles.  Enter the figure in bytes (so 5k = 5000 bytes).</p>
        <p><strong>Max Video Size</strong> - This setting limits the size of any video uploaded to the profile.  Enter as above. </p>
        <p><strong>Max Audio Size</strong> - This setting limits the size of any audio file uploaded to the profile.  Enter as above.</p>
        <p><strong>Initial referal percent</strong> - This setting relates to the payment calculated for affiliates for each initial sale to a unique customer.</p>
        <p><strong>Subsequent percent</strong> - This setting determines what percentage of a payment is paid to an affiliate for each subsequent purchase made by a customer after their initial payment.  </p>
        <hr>
        <a name="Templates">Templates</a>
        <p>Templates are one of the fundamental components of the system. For more information about templates and how they work, please contact the vendor. </p>
        <p>If you accidentally make a change to a template, <strong>do not click update</strong>, simply click CTRL Z to undo or select another template from the list (or move to another page), your template will not be saved.</p>
        <hr>
        <a name="Languages">Languages</a>
        <p>The Languages section will allow you to enable different languages on the system. To implement a new language you must first provide the translation files. Please contact your vendor for further information.</p>
        <hr>
        <a name="PremiumFunctions">Premium Functions</a>
        <p>The premium functions section is design ed to allow the administrator the flexibility as to which functions on the site will be designated as open to all members and which will be deemed premium functions (i.e. the user must have paid a subscription to the site in order to access them).</p>
        <p>To make a feature premium you must click the checkbox to make it active. Depending on the feature you can use one of the three methods to inform your user that they need premium status to access that feature. </p>
        <p>To add a new feature, simply place the url to the page in the URI box and give the feature a name and click 'add'. you may need to experiment with which error message type is best.</p>
        <hr>
        <a name="Options">Option Admin</a>
        <p>The Option Administration page allows the administrator to add, edit and delete options from the main 10 selection boxes displayed in the profile and the search screen. These are for example: Seeking, Ethnicity, Marital Status, Body Type, Religion, Employment, Education, Smoker, Children and Income.</p>
        <p>Selecting the option type using the list box at the top of this page will display all the values that are currently set for that options type. Beside each value is a link to 'edit' or 'delete'. At the bottom of the list are 2 textboxes for adding new values.</p>
        <p>To edit a value, click the edit link next to the value you wish to update. This will display the value and the order for you to make your changes. Once changed, click save and the new value will be updated and you will return to the list.</p>
        <p>To delete a value, click the delete link next to the value you wish to remove. This will remove the value and return the list.</p>
        <p>To add a new value, enter the text and the order in the boxes at the end of the list and click 'add'. You value will be added to the list.</p>
        <p>Where you have an additional language enabled, you will see an additional box for each language translation.</p>
        <p>Note: Order refers to the position that the value has when displayed in the list boxes. If a 'Not stated' value is present, this should always be the first in the list order (i.e. 1)</p>
        <p>WARNING: You should not edit or delete values once the system is up and running or you may leave 'orphaned' values in the members profile so that their information is incorrectly displayed. You can add values or edit the order of a value without causing any issues at any time.</p>
        <hr>
        <a name="PaymentSystems">Payment Systems</a>
        <p>This page displays the various payments systems that are available as pre-built gateways in the system. To enable a gateway you need to edit it and add the information that is required for each gateway that you wish to use and then click the link from inactive to active to make it available to users.</p>
        <p>Please contact your vendor should you need assistance with setting up your payments. </p>
        <hr>
        <a name="PaymentServices">Payment Services</a>
        <p>Some payments processors allow for subscriptions (payments that are taken automatically at various predefined periods) You can set the systems to recurring using the links available in this screen and this will normally provide you with additional fields to fill in the Payment Systems section.</p>
        <p>Please contact your vendor should you need assistance with setting up your payments. </p>
        <hr>
        <a name="Geography">Geography</a>
        <p> This feature allows you to edit, edit and remove geographical regions, towns and cities.</p>
        <hr>
        <a name="MailTemplates">Mail Templates</a>
        <p> The majority of the emails that are sent by the system can be edited from here using either straight text or HTML. A variety of system variables have been provided to give access to mail specific data. </p>
        <hr>
        <p><strong>Tools Section </strong></p>
        <hr>
        <a name="AuthoriseAds">Authorise Adverts</a>
        <p>Each time a member creates or updates their profile, a flag is set in
        the database. This flag status can be one of 3 possible values:</p>
        <p>0 = Authorization Required; 1 = Authorized; 2 = Rejected</p>
        <p>Authorise ads will step through all profiles with a status of 0 and
        display the profile to the administrator who can then set the status to
        either 1 or 2. If the administrator decides to reject the profile, they
        can also write a brief description of why the profile has been rejected
        (i.e. Inappropriate photo).</p>
        <p>Authorized profile will immediately become available in the search.
        Rejected profiles must be updated before the status is set back to 0,
        they will then be picked up for authorization.</p>
        <p><u>Important!</u> You can edit both the title and the message of the
        profile before approving. This is useful to remove contact details and
        then authorize the profile without having to reject it.</p>
        <dl>
          <dd>
            <div align="left"><i><b>Note:</b> The initial status value is extracted from the
              parameter table. Therefore if automatic authorization is set ON, the
              status will always be set to 1.&nbsp; </i></div>
          </dd>
        </dl>
        <hr>
        <a name="MemberAdministration">Member Administration</a>
        <p>Member administration can perform several functions. To select a member, enter the username in the search box (or part or the username such as &quot;man&quot; will return &quot;manager&quot;, &quot;manaman&quot;, &quot;manatoo&quot; and so on). When the results are displayed, click the username for the user you wish to access and then click get member.</p>
        <p>Once you have the members details loaded you can alter some of these key fields. For example to make a member premium, enter an expire date in advance of the current date in the expire date field and click update now, that user is then a premium member until that date is reached. </p>
        <p>You can also delete member adverts or members from this screen using the buttons at the bottom. Use the with caution though. </p>
        <hr>
        <a name="SendMail">Send Mail</a>
        <p>Send mail is used to send mails to members. Mails can be sent in
        either text or in HTML format. HTML format mails always include removal
        instructions at the bottom of the e-mail.</p>
        <p>The following explains the entry fields found on this page:</p>
        <p><b>Email to </b>- if you wish to send mail to a single person (for
        example to test an HTML mail by sending it to yourself), enter the
        e-mail address in this box. ALL other mail address selection criteria
        are ignored if an address is entered here (i.e. <i>All Users, From File,
        Gender</i>).</p>
        <p><b>All Users </b>- by ticking this field, e-mails will also be sent
        to members that have previously unsubscribe. The reason for this is that
        you may have an important system message or an offer that you wish to
        send to every user rather than just those subscribed to the newsletter.
        Use sparingly.</p>
        <p><b>From File</b> - rather than selecting members from the database,
        you can supply a file of e-mail addresses. The file should be in text
        format with each address terminated by a newline character. Ticking this
        box will cause the <i>All Users</i> and <i>Gender</i> fields to be
        ignored but will not override the precedence of <i>Email To</i>.</p>
        <p><b>Introduction</b> - ticking this field will insert the text
        &quot;Dear &lt;username&gt;&quot; as the first line of each mail where
        username is the members Handle.</p>
        <p><b>HTML</b> - clicking this tells the program that the message
        contains HTML. If you include HTML and do not click this button then the
        e-mail will not be readable.</p>
        <p><b>Gender</b> - if you are sending e-mails to addresses in the
        database, you can target your mails to a specific gender. You may also
        want to use this to spread out the mail campaign.&nbsp;&nbsp;</p>
        <p><strong>Status</strong> - filters the recipients by their status as Premium, Standard or Inactive (members that have not logged on to the site within the last 90 days) </p>
        <p><strong>Send Type</strong> - determines if the mail should be sent to the internal or external member mail box. </p>
        <p><b>Subject</b> - enter the subject line of the mail.<b>Message</b> - enter the message in either text or HTML format.</p>
        <p><b>From</b> - enter the mail address you wish to appear as the from
        address when the user receives the mail.</p>
        <dl>
          <dd><i><b>Note:</b>  There is an automatic delay between each mail of
            2 seconds and a 3 minute sleep between every 500 mails sent. This is
            to prevent flooding of the server. You will need to agree with you
            hosting company what settings are appropriate. </i></dd>
        </dl>
        <hr>
        <a name="EmailExtract">Email Extract</a>
        <p>The email extract routine allows the administrator to extract email addresses for members that joined between two selected dates to a CSV file. </p>
        <hr>
        <a name="NewsOpt-outs">Newsletter OptOut</a>
        <p>This is a useful tool for removing members from the newsletter and
        also removing bounced mails. Members often do not click the unsubscribe
        button in e-mails they receive from the system. Instead they reply to
        the mail with REMOVE in the subject. If this happens you can
        cut-and-paste the address into this screen and the member will be
        removed.</p>
        <p>This screen also provides for bulk removals. This is useful if you
        can get a list of bounced mail addresses from you hosting company. The
        file should be in text format with each address terminated by a newline
        character.</p>
        <hr>
        <a name="InactiveMembers">Inactive Members</a>
        <p>From time to time it may be necessary to remove members that have been inactive for a long period of time. This screen allows you to display members that have been inactive (i.e. not logged on) for longer than the number of months specified (i.e. select 30 will show members that have not logged on in more than 30 months). To delete these members select the required number of months from the listbox and let the list refresh, then if satisfied that this is correct, click delete. </p>
        <hr>
        <a name="DataExport">Database Backup</a>
        <p>The data export page provides a means to extract the database structure and data from the system
        to either keep as a backup of the table or to import in your local
        database. The resulting files can be compressed into zip format for your convenience. </p>
        <p>To import the data back into the system you will need access to an interface program such as phpmyadmin or MySQL-Front.</p>
        <p>It is always recommended that you take regular backups or ensure that your hosting company does so on your behalf. </p>
        <hr>
        <a name="#Stories">Stories</a>
        <p> This feature allows you to add, edit or delete stories. This is a useful feature if you want to add information about letters received, marriages between members and so on. </p>
        <hr>
        <a name="#News">News</a>
        <p>Similar to stories but more news related items about what is happening on the site. </p>
        <hr>
        <a name="#Flirt">Flirt</a>
        <p>Within the member portion of the site is the option to send preset flirtatious messages to other members. This admin feature allows you to add, edit and delete the messages that are available in the dropdown for flirts. </p>
        <hr>
        <a name="#Optimizedatabase">Optimize database</a>
        <p>Database optimization uses a standard MySQL feature to reclaim space previously taken up by deleted records and to defragment your database. It can help speed up database responses. </p>
        <hr>
        <p><strong>Events Section </strong></p>
        <hr>
        <p><a name="#AddEvents">Add Events</a> </p>
        <p>This feature allows you to add events for the events calendar. It is seperate and should not be confused with the speedddating software. Add your event here and it will be displayed in the events calender available in the members home page. </p>
        <hr>
        <p><a name="#ApproveEvents">Approve Events</a> </p>
        <p>Use the Approve Events link to approve or delete events from the events calender.</p>
        <hr>
        <p><a name="#ApproveReviews">Approve Reviews</a> </p>
        <p>Members can add reviews of particular events. These reviews can be viewed and approved or rejected from this link. If there are no outstanding reviews you will be taken directly to the list of events. </p>
        <hr>
        <p><strong>Affiliates Section </strong></p>
        <hr>
        <p><a name="#AuthoriseAffiliates">Authorise Affiliates</a> </p>
        <p>This screen allows you to review applications from potential affiliates that wish to display your banner or links on their website in exchange for sharing in any revenue. Approve or reject the applications and an email is sent to the affiliate informting them of your decision. If you approve, they are sent login details and links to the affiliate user site.</p>
        <hr>
        <p><a name="#AffiliatePayments">Affiliate Payments</a> </p>
        <p>This is a report displaying who should be paid for a particular month and the amount to pay. The amounts are calculated based upon the figures entered into the Set Parameters page and current at the time the member payment was received. There is a delay calculated on each payment due date to help protect against chargebacks. </p>
        <hr>
        <p><a name="#AffiliateBanners">Affiliate Banners</a> </p>
        <p>In this screen you can add or delete the banners that you wish to make available to the affiliates. You can even add a date range when these banners can be used, after which they will become unavailable to the affiliates. Banners uploaded here are displayed in the &quot;get code&quot; section of the affiliate software. </p>
        <hr>
        <p><a name="#AffiliatesAdministration">Affiliates Administration</a></p>
        <p>This is a report displaying who should be paid for a particular month and the amount to pay. The amounts are calculated based upon the figures entered into the Set Parameters page and current at the time the member payment was received.</p>
        <hr>
        <p><a name="#AffiliatesPerformance">Affiliates Performance</a></p>
        <p>This is a report displaying who should be paid for a particular month and the amount to pay. The amounts are calculated based upon the figures entered into the Set Parameters page and current at the time the member payment was received.</p>
        <hr>
        <p><strong>Speed Dating Section </strong></p>
        <hr>
        <p><a name="#ManageEvents">Manage Events</a></p>
        <p>Use this link to add, edit and delete your speed dating events. It is only available if you have the speed dating module installed. When creating an event you can select the date, time and venue along with ticket price and the genders of those that can attend and the numbers.There is an additional box that when ticked will flag the event as a special (and will be displayed in that area). </p>
        <hr>
        <p><a name="#ManageVenues">Manage Venues</a></p>
        <p>Add, edit and delete the venues where speed dating events will be held. You must create the venue before you can create the event but once created, you can associate many events to that one venue.</p>
        <hr>
        <p><a name="#SDStories">Stories</a></p>
        <p>Similar to the dating software stories section, this page allows you to add stories and associated images for the speed dating stories page..</p>
        <hr>
        <p><a name="#SDReports">Reports</a></p>
        <p>The report will display all the members associated with a particular event. This is so you can have a list of attendees handy at the venue and to make name badges and so on..</p>
        <hr>
        <p><a name="#WaitingList">Waiting List</a></p>
        <p>When an event is fully booked, members have the option to add themselves to a waiting list for the event should another member drop out. This report will display the waiting list for a particular event..</p>
        <hr>
        <p>iDateMedia, LLC. © 1999 - 2005</p>
      </td>
    </tr>
  </table>
</div>

</body>

</html>