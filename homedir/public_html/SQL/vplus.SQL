-- phpMyAdmin SQL Dump
-- version 2.11.9.1
-- http://www.phpmyadmin.net
--
-- Värd: localhost
-- Skapad: 25 november 2008 kl 01:05
-- Serverversion: 4.1.22
-- PHP-version: 5.2.6

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

-- --------------------------------------------------------

--
-- Struktur för tabell `adverts`
--

CREATE TABLE IF NOT EXISTS `adverts` (
  `adv_userid` int(11) NOT NULL default '0',
  `adv_username` varchar(30) NOT NULL default '',
  `adv_smoker` varchar(250) default NULL,
  `adv_children` varchar(250) default NULL,
  `adv_comment` text,
  `adv_location` varchar(250) default NULL,
  `adv_countryid` int(11) NOT NULL default '0',
  `adv_stateid` int(11) NOT NULL default '0',
  `adv_cityid` int(11) NOT NULL default '0',
  `adv_height` varchar(15) default NULL,
  `adv_marital` varchar(250) default NULL,
  `adv_bodytype` varchar(250) default NULL,
  `adv_ethnicity` varchar(250) default NULL,
  `adv_religion` varchar(250) default NULL,
  `adv_sex` char(1) default NULL,
  `adv_seekmen` char(1) default NULL,
  `adv_seekwmn` char(1) default NULL,
  `adv_seekcpl` char(1) default NULL,
  `adv_seeking` varchar(250) default NULL,
  `adv_picture` varchar(75) default NULL,
  `adv_dob` date default NULL,
  `adv_approved` int(11) default NULL,
  `adv_agency` varchar(7) default NULL,
  `adv_createdate` datetime default NULL,
  `adv_title` varchar(30) default NULL,
  `adv_education` varchar(250) default NULL,
  `adv_income` varchar(250) default NULL,
  `adv_profession` varchar(250) default NULL,
  `adv_expiredate` date default NULL,
  `adv_views` int(3) default '0',
  `adv_zipcode` varchar(5) default NULL,
  `adv_paused` enum('Y','N') NOT NULL default 'N',
  `adv_rejectreason` varchar(255) NOT NULL default '',
  `adv_eyecolor` varchar(250) NOT NULL default '',
  `adv_haircolor` varchar(250) NOT NULL default '',
  `adv_drink` varchar(250) NOT NULL default '',
  `adv_ip` varchar(255) NOT NULL default '',
  `block_time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`adv_userid`)
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `adverts`
--

INSERT INTO `adverts` (`adv_userid`, `adv_username`, `adv_smoker`, `adv_children`, `adv_comment`, `adv_location`, `adv_countryid`, `adv_stateid`, `adv_cityid`, `adv_height`, `adv_marital`, `adv_bodytype`, `adv_ethnicity`, `adv_religion`, `adv_sex`, `adv_seekmen`, `adv_seekwmn`, `adv_seekcpl`, `adv_seeking`, `adv_picture`, `adv_dob`, `adv_approved`, `adv_agency`, `adv_createdate`, `adv_title`, `adv_education`, `adv_income`, `adv_profession`, `adv_expiredate`, `adv_views`, `adv_zipcode`, `adv_paused`, `adv_rejectreason`, `adv_eyecolor`, `adv_haircolor`, `adv_drink`, `adv_ip`, `block_time`) VALUES
(14651, 'manager', 'Light', 'No', 'Just seeing what the site looks like  but if I find a worth while man, I might be up to taking things further so give me a call \r\nsusie-sue@hotmail.co.uk\r\nTHIS IS A SYSTEM TEST !!!!\r\nIT IS NOT A REAL PROFILE, \r\nPICTURE OR EMAIL ADDRESS. ', '', 203, 65, 2881, '141', 'Single', 'Average', 'Caucasian', 'None', 'M', 'Y', 'N', 'Y', 'Casual Encounters', '', '1966-01-29', 1, NULL, '2008-11-06 11:18:38', 'Girl - Looking for fun', 'Some College', '00 - 20k', 'Not stated', '2017-08-31', 0, '', 'N', '', 'Green', 'Blonde', 'Not stated', '88.64.136.219', '0000-00-00 00:00:00');
-- --------------------------------------------------------

--
-- Struktur för tabell `affiliates`
--

CREATE TABLE IF NOT EXISTS `affiliates` (
  `aff_userid` int(11) NOT NULL auto_increment,
  `aff_username` varchar(25) default NULL,
  `aff_password` varchar(10) NOT NULL default '',
  `aff_surname` varchar(25) default NULL,
  `aff_forename` varchar(25) default NULL,
  `aff_email` varchar(75) default NULL,
  `aff_address` varchar(50) default NULL,
  `aff_street` varchar(50) default NULL,
  `aff_town` varchar(50) default NULL,
  `aff_state` varchar(50) default NULL,
  `aff_zipcode` varchar(10) default NULL,
  `aff_country` varchar(50) default NULL,
  `aff_joindate` date default NULL,
  `aff_business` varchar(50) default NULL,
  `aff_payname` varchar(50) default NULL,
  `aff_website` varchar(100) default NULL,
  `aff_fax` varchar(25) default NULL,
  `aff_telephone` varchar(25) default NULL,
  `aff_approved` tinyint(4) default NULL,
  `aff_clickthru` int(11) default NULL,
  PRIMARY KEY  (`aff_userid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10284 ;

--
-- Data i tabell `affiliates`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `audios`
--

CREATE TABLE IF NOT EXISTS `audios` (
  `aud_id` int(11) NOT NULL auto_increment,
  `aud_userid` int(11) NOT NULL default '0',
  `aud_audio` varchar(75) NOT NULL default '',
  `aud_private` enum('Y','N') NOT NULL default 'N',
  PRIMARY KEY  (`aud_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Data i tabell `audios`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `avatars`
--

CREATE TABLE IF NOT EXISTS `avatars` (
  `avatar_id` int(11) NOT NULL auto_increment,
  `pic_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`avatar_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

--
-- Data i tabell `avatars`
--

-- --------------------------------------------------------

--
-- Struktur för tabell `bannercodeformats`
--

CREATE TABLE IF NOT EXISTS `bannercodeformats` (
  `size` varchar(20) NOT NULL default '',
  `label` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`size`)
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `bannercodeformats`
--

INSERT INTO `bannercodeformats` (`size`, `label`) VALUES
('468x60', '468x60'),
('120x60', '120x60'),
('100x100', '100x100'),
('120x90', '120x90'),
('728x90', '728x90'),
('300x600', '300x600');

-- --------------------------------------------------------

--
-- Struktur för tabell `bannercodes`
--

CREATE TABLE IF NOT EXISTS `bannercodes` (
  `banner_id` int(10) unsigned NOT NULL auto_increment,
  `size` varchar(20) NOT NULL default '',
  `label` varchar(50) NOT NULL default '',
  `code` text NOT NULL,
  `hits` int(11) NOT NULL default '-1',
  `is_active` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`banner_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Data i tabell `bannercodes`
--

-- --------------------------------------------------------

--
-- Struktur för tabell `banners`
--

CREATE TABLE IF NOT EXISTS `banners` (
  `ban_id` int(11) NOT NULL auto_increment,
  `ban_text` text NOT NULL,
  `ban_picture` varchar(255) NOT NULL default '',
  `ban_start` date default NULL,
  `ban_end` date default NULL,
  PRIMARY KEY  (`ban_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=20 ;

--
-- Data i tabell `banners`
--

-- --------------------------------------------------------

--
-- Struktur för tabell `bannerstats`
--

CREATE TABLE IF NOT EXISTS `bannerstats` (
  `banner_id` int(10) unsigned NOT NULL default '0',
  `statdate` date NOT NULL default '0000-00-00',
  `hits` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`banner_id`,`statdate`)
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `bannerstats`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `bb_forum`
--

CREATE TABLE IF NOT EXISTS `bb_forum` (
  `forum_id` mediumint(8) unsigned NOT NULL auto_increment,
  `forum_title` char(250) NOT NULL default '',
  `forum_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `forum_status` tinyint(3) NOT NULL default '0',
  PRIMARY KEY  (`forum_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Data i tabell `bb_forum`
--

-- --------------------------------------------------------

--
-- Struktur för tabell `bb_posts`
--

CREATE TABLE IF NOT EXISTS `bb_posts` (
  `post_id` mediumint(8) unsigned NOT NULL auto_increment,
  `topic_id` mediumint(8) unsigned NOT NULL default '0',
  `poster_id` mediumint(8) NOT NULL default '0',
  `post_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `post_approved` tinyint(4) NOT NULL default '0',
  `post_text` text NOT NULL,
  `subtopic_id` int(11) NOT NULL default '0',
  `post_ext` varchar(4) NOT NULL default '',
  PRIMARY KEY  (`post_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Data i tabell `bb_posts`
--

-- --------------------------------------------------------

--
-- Struktur för tabell `bb_subtopics`
--

CREATE TABLE IF NOT EXISTS `bb_subtopics` (
  `subtopic_id` mediumint(8) unsigned NOT NULL auto_increment,
  `subtopic_title` varchar(250) NOT NULL default '',
  `subtopic_status` tinyint(3) NOT NULL default '0',
  `forum_id` int(11) NOT NULL default '0',
  `topic_id` int(11) NOT NULL default '0',
  `subtopic_time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`subtopic_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Data i tabell `bb_subtopics`
--

-- --------------------------------------------------------

--
-- Struktur för tabell `bb_topics`
--

CREATE TABLE IF NOT EXISTS `bb_topics` (
  `topic_id` mediumint(8) unsigned NOT NULL auto_increment,
  `topic_title` varchar(250) NOT NULL default '',
  `topic_desc` varchar(250) default NULL,
  `topic_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `topic_status` tinyint(3) NOT NULL default '0',
  `forum_id` int(11) NOT NULL default '0',
  PRIMARY KEY  (`topic_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Data i tabell `bb_topics`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `blockmail`
--

CREATE TABLE IF NOT EXISTS `blockmail` (
  `blk_receiverid` int(11) NOT NULL default '0',
  `blk_senderid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`blk_receiverid`,`blk_senderid`)
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `blockmail`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `blogs`
--

CREATE TABLE IF NOT EXISTS `blogs` (
  `blg_id` int(11) NOT NULL auto_increment,
  `blg_userid` int(11) NOT NULL default '0',
  `blg_datetime` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `blg_message` text NOT NULL,
  `blg_approved` enum('Y','N','R') NOT NULL default 'N',
  `blg_private` enum('Y','N') NOT NULL default 'Y',
  PRIMARY KEY  (`blg_id`),
  KEY `blg_userid` (`blg_userid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=40 ;

--
-- Data i tabell `blogs`
--

-- --------------------------------------------------------

--
-- Struktur för tabell `clubs`
--

CREATE TABLE IF NOT EXISTS `clubs` (
  `cl_clubid` int(11) NOT NULL auto_increment,
  `cl_userid` int(11) NOT NULL default '0',
  `cl_clubname` varchar(50) NOT NULL default '',
  `cl_address` text,
  `cl_city` varchar(45) NOT NULL default '',
  `cl_country` varchar(45) NOT NULL default '',
  `cl_phone` varchar(25) default NULL,
  `cl_website` varchar(50) default NULL,
  `cl_description` text,
  `cl_picture` varchar(75) default NULL,
  `cl_approved` int(11) NOT NULL default '0',
  PRIMARY KEY  (`cl_clubid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Data i tabell `clubs`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL auto_increment,
  `type` varchar(255) NOT NULL default '',
  `user_id` int(11) NOT NULL default '0',
  `ent_id` int(11) NOT NULL default '0',
  `date` datetime NOT NULL default '0000-00-00 00:00:00',
  `text` text NOT NULL,
  `approve` set('0','1') NOT NULL default '1',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=19 ;

--
-- Data i tabell `comments`
--

-- --------------------------------------------------------

--
-- Struktur för tabell `conversations`
--

CREATE TABLE IF NOT EXISTS `conversations` (
  `cid` varchar(25) NOT NULL default '',
  `uid` varchar(25) NOT NULL default '',
  `msgstartdt` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `msgdttime` timestamp NOT NULL default '0000-00-00 00:00:00',
  `msgtext` blob NOT NULL
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `conversations`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `db_sessions`
--

CREATE TABLE IF NOT EXISTS `db_sessions` (
  `session_id` varchar(32) NOT NULL default '',
  `session_created` int(11) NOT NULL default '0',
  `session_active` int(11) NOT NULL default '0',
  `session_counter` int(11) NOT NULL default '0',
  `session_remote_address` varchar(128) NOT NULL default '',
  `session_data` longtext NOT NULL,
  `session_userid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`session_id`)
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `db_sessions`
--

-- --------------------------------------------------------

--
-- Struktur för tabell `dir_articles`
--

CREATE TABLE IF NOT EXISTS `dir_articles` (
  `articles_id` int(11) NOT NULL auto_increment,
  `title` varchar(255) NOT NULL default '',
  `body` mediumtext NOT NULL,
  `enterdate` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`articles_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Data i tabell `dir_articles`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `dir_banners`
--

CREATE TABLE IF NOT EXISTS `dir_banners` (
  `ban_id` int(11) NOT NULL auto_increment,
  `ban_text` text NOT NULL,
  `ban_start` date default NULL,
  `ban_end` date default NULL,
  `ban_sponsor` enum('Y','N') NOT NULL default 'N',
  PRIMARY KEY  (`ban_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=90 ;

--
-- Data i tabell `dir_banners`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `dir_b_categories`
--

CREATE TABLE IF NOT EXISTS `dir_b_categories` (
  `ban_id` int(11) NOT NULL default '0',
  `ban_category` int(11) NOT NULL default '0',
  UNIQUE KEY `ban_category` (`ban_category`,`ban_id`)
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `dir_b_categories`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `dir_categories`
--

CREATE TABLE IF NOT EXISTS `dir_categories` (
  `cat_id` int(11) NOT NULL auto_increment,
  `cat_parent` varchar(120) NOT NULL default '',
  `cat_child` varchar(120) NOT NULL default '',
  PRIMARY KEY  (`cat_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=134 ;

--
-- Data i tabell `dir_categories`
--

INSERT INTO `dir_categories` (`cat_id`, `cat_parent`, `cat_child`) VALUES
(1, 'Generic Category', 'Test Sub-category 1'),
(2, 'Generic Category', 'Test Sub-category 2'),
(3, 'Generic Category', 'Test Sub-category 3');

-- --------------------------------------------------------

--
-- Struktur för tabell `dir_mail_list`
--

CREATE TABLE IF NOT EXISTS `dir_mail_list` (
  `mail_address` varchar(75) NOT NULL default '',
  `time_added` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `auth_string` varchar(12) NOT NULL default '',
  `authorised` enum('Y','N') NOT NULL default 'Y',
  PRIMARY KEY  (`mail_address`),
  KEY `mail_address` (`mail_address`)
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `dir_mail_list`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `dir_searchlogs`
--

CREATE TABLE IF NOT EXISTS `dir_searchlogs` (
  `src_id` int(11) unsigned NOT NULL auto_increment,
  `src_string` varchar(250) default NULL,
  PRIMARY KEY  (`src_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2123 ;

--
-- Data i tabell `dir_searchlogs`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `dir_site_list`
--

CREATE TABLE IF NOT EXISTS `dir_site_list` (
  `site_id` int(11) NOT NULL auto_increment,
  `site_name` varchar(120) NOT NULL default '',
  `site_description` text NOT NULL,
  `site_email` varchar(120) NOT NULL default '',
  `site_url` varchar(120) NOT NULL default '',
  `site_linkback` varchar(250) NOT NULL default '',
  `site_category` varchar(120) NOT NULL default '',
  `site_live` enum('Y','N') NOT NULL default 'N',
  `site_sponsor` enum('Y','N') NOT NULL default 'N',
  `cat_id` int(11) NOT NULL default '0',
  `clicks_counter` int(11) unsigned NOT NULL default '0',
  PRIMARY KEY  (`site_id`),
  FULLTEXT KEY `site_name` (`site_name`,`site_description`,`site_category`,`site_url`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2057 ;

--
-- Data i tabell `dir_site_list`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `dir_template`
--

CREATE TABLE IF NOT EXISTS `dir_template` (
  `template_id` int(11) NOT NULL auto_increment,
  `template_name` varchar(255) NOT NULL default '',
  `template_value` mediumtext NOT NULL,
  `template_variables` mediumtext NOT NULL,
  `template_type` enum('text','html') NOT NULL default 'text',
  `template_title` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`template_id`),
  UNIQUE KEY `config_name` (`template_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Data i tabell `dir_template`
--

INSERT INTO `dir_template` (`template_id`, `template_name`, `template_value`, `template_variables`, `template_type`, `template_title`) VALUES
(1, 'Add_Url_Email', 'Dear Webmaster\r\nYour request to be added to the directory {CONST_LINK_SITE} has been received.\r\nThe following data has been submitted for review:\r\nSite Name: {sitename}\r\nCategory: {category}\r\nDescription: {description}\r\nEmail: {email}\r\nURL: {url}\r\nLink URL: {linkurl}\r\n\r\nIf you have not already placed a link back, please use the following code placed not further than one click from your homepage:\r\n\r\n<p align=''center''><font face=''Verdana'' size=''1''><a href=''{CONST_LINK_ROOT}''><img ALT=''Click here'' border=''0'' src=''{CONST_LINK_ROOT}/images/ondating_1.gif'' width=''120'' height=''60''><br>{CONST_LINK_SITE}</a></font></p>\r\n\r\n\r\nIf any of this information is incorrect then please respond to this email with the corrections. Your site will be reviewed for inclusion with a couple of days.\r\n\r\n\r\nAdministrator', 'CONST_LINK_SITE;sitename;category;description;email;url;linkurl;CONST_LINK_ROOT;', 'text', 'Link Request Received'),
(3, 'Add_Url_Accept_Email', '<p><font face=''Verdana'' size=''2''>Dear webmaster<br><br>\r\n                            Your link {txtUrl} has been added to {CONST_LINK_ROOT} in the <b> {main_cat} </b> category and cross-referenced under <b>{sub_cat}</b>. \r\n                            You have also been included in the search engine. \r\n                            Links are checked once a month so please do not remove the link to our site or you may be removed without notice.<br><br>\r\n                            Thank you for advertising with us, we appreciate it and we wish you all the best with your site.<br><br>\r\n                            Regards<br><br>\r\n                            Administrator</font></p>', 'txtUrl;main_cat;CONST_LINK_ROOT;sub_cat;CONST_LINK_SITE;sitename;category;description;email;url;linkurl;', 'html', 'Link Request Approved'),
(4, 'Add_Url_Reject_Email', '<font face=''Verdana'' size=''2''>Dear webmaster<br><br>\r\n                    Unfortunately we are unable to add your link to our directory/search engine for the following reason(s):<p><i>{lstReason}</i></p>\r\n                    <p><i>{txtReason}</i></p>\r\n                    Please resubmit your site when it meets the criteria and we will be happy to include you in the directory and search engine.<br><br>\r\n                    Regards<br><br>\r\n                    Administrator</font>', 'txtReason;lstReason;CONST_LINK_SITE;sitename;category;description;email;url;linkurl;CONST_LINK_ROOT;', 'html', 'Link Request Rejected');

-- --------------------------------------------------------

--
-- Struktur för tabell `dir_voting`
--

CREATE TABLE IF NOT EXISTS `dir_voting` (
  `siteid` int(11) NOT NULL default '0',
  `votes` int(11) default '0',
  `value` int(11) default '0',
  `average` decimal(3,2) unsigned default '0.00',
  PRIMARY KEY  (`siteid`)
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `dir_voting`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `dir_voting_history`
--

CREATE TABLE IF NOT EXISTS `dir_voting_history` (
  `site_id` int(11) NOT NULL default '0',
  `IP` varchar(255) NOT NULL default '',
  `lastvote` datetime default NULL,
  PRIMARY KEY  (`site_id`,`IP`)
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `dir_voting_history`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `encounters`
--

CREATE TABLE IF NOT EXISTS `encounters` (
  `enc_recno` int(11) unsigned NOT NULL auto_increment,
  `enc_userid` int(11) unsigned NOT NULL default '0',
  `enc_viewerid` int(11) unsigned NOT NULL default '0',
  `enc_viewdate` date default '0000-00-00',
  PRIMARY KEY  (`enc_recno`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=518 ;

--
-- Data i tabell `encounters`
--

-- --------------------------------------------------------

--
-- Struktur för tabell `events`
--

CREATE TABLE IF NOT EXISTS `events` (
  `ev_eventid` int(11) NOT NULL auto_increment,
  `ev_userid` int(11) NOT NULL default '0',
  `ev_eventname` varchar(50) NOT NULL default '',
  `ev_address` varchar(60) default NULL,
  `ev_city` varchar(45) default NULL,
  `ev_state` varchar(250) NOT NULL default '',
  `ev_country` varchar(45) default NULL,
  `ev_schedule` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `ev_phone` varchar(25) default NULL,
  `ev_website` varchar(50) default NULL,
  `ev_description` text,
  `ev_picture` varchar(75) default NULL,
  `ev_approved` int(11) NOT NULL default '0',
  PRIMARY KEY  (`ev_eventid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Data i tabell `events`
--

-- --------------------------------------------------------

--
-- Struktur för tabell `flirt`
--

CREATE TABLE IF NOT EXISTS `flirt` (
  `Flirt_ID` int(11) NOT NULL auto_increment,
  PRIMARY KEY  (`Flirt_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Data i tabell `flirt`
--

INSERT INTO `flirt` (`Flirt_ID`) VALUES
(13),
(14),
(15),
(16),
(17),
(18),
(19),
(20);

-- --------------------------------------------------------

--
-- Struktur för tabell `gallery`
--

CREATE TABLE IF NOT EXISTS `gallery` (
  `Gallery_ID` int(11) NOT NULL auto_increment,
  `LangID` char(3) NOT NULL default '',
  `Name` varchar(250) NOT NULL default '',
  `Description` mediumtext NOT NULL,
  `mem_id` int(11) NOT NULL default '0',
  `Level` enum('Private','Hotlist','Public') NOT NULL default 'Private',
  PRIMARY KEY  (`Gallery_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Data i tabell `gallery`
--

-- --------------------------------------------------------

--
-- Struktur för tabell `galleryitem`
--

CREATE TABLE IF NOT EXISTS `galleryitem` (
  `GalleryItem_ID` int(11) NOT NULL auto_increment,
  `Gallery_ID` int(11) NOT NULL default '0',
  `Description` varchar(255) NOT NULL default '',
  `Type` enum('Image','Video') NOT NULL default 'Image',
  `Approved` enum('Pending','Approved','Rejected') NOT NULL default 'Pending',
  PRIMARY KEY  (`GalleryItem_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Data i tabell `galleryitem`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `owner` int(10) unsigned NOT NULL default '0',
  `category` int(10) unsigned NOT NULL default '0',
  `name` varchar(50) NOT NULL default '',
  `description` varchar(255) NOT NULL default '',
  `description_short` varchar(100) NOT NULL default '',
  `url_name` varchar(50) NOT NULL default '',
  `country` int(10) unsigned NOT NULL default '0',
  `state` int(10) unsigned NOT NULL default '0',
  `city` varchar(50) NOT NULL default '0',
  `is_open` tinyint(1) unsigned NOT NULL default '1',
  `is_public` tinyint(1) unsigned NOT NULL default '1',
  `images_allowed` tinyint(1) unsigned NOT NULL default '1',
  `topics_autoapprove` tinyint(1) unsigned NOT NULL default '1',
  `status` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `category` (`category`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Data i tabell `groups`
--

-- --------------------------------------------------------

--
-- Struktur för tabell `group_categories`
--

CREATE TABLE IF NOT EXISTS `group_categories` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(50) NOT NULL default '',
  `parent` int(10) unsigned NOT NULL default '0',
  `visible` tinyint(1) unsigned NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `parent` (`parent`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Data i tabell `group_categories`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `group_posts`
--

CREATE TABLE IF NOT EXISTS `group_posts` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `groupid` int(10) unsigned NOT NULL default '0',
  `topic` int(10) unsigned NOT NULL default '0',
  `author` int(10) unsigned NOT NULL default '0',
  `subject` varchar(255) NOT NULL default '',
  `text` text NOT NULL,
  `created` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `status` tinyint(1) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `topic` (`topic`),
  KEY `groupid` (`groupid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Data i tabell `group_posts`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `hotlist`
--

CREATE TABLE IF NOT EXISTS `hotlist` (
  `hot_id` int(11) NOT NULL auto_increment,
  `hot_userid` int(11) NOT NULL default '0',
  `hot_advid` int(11) NOT NULL default '0',
  `hot_screenname` varchar(30) default NULL,
  `hot_dateadded` date default NULL,
  `hot_title` varchar(30) default NULL,
  `hot_private` enum('Y','N') NOT NULL default 'N',
  PRIMARY KEY  (`hot_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2255 ;

--
-- Data i tabell `hotlist`
--

-- --------------------------------------------------------

--
-- Struktur för tabell `iwannatalk`
--

CREATE TABLE IF NOT EXISTS `iwannatalk` (
  `uid` varchar(25) NOT NULL default '',
  `cid` varchar(25) NOT NULL default '',
  `status` char(1) NOT NULL default 'F',
  `tstamp` varchar(15) NOT NULL default '0000000000'
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `iwannatalk`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `langfile`
--

CREATE TABLE IF NOT EXISTS `langfile` (
  `lang_id` char(3) NOT NULL default '',
  `lang_name` varchar(40) default NULL,
  `lang_filename` varchar(50) default NULL,
  `lang_imagename` varchar(50) NOT NULL default '',
  `lang_active` char(1) NOT NULL default '0',
  `lang_charset` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`lang_id`)
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `langfile`
--

INSERT INTO `langfile` (`lang_id`, `lang_name`, `lang_filename`, `lang_imagename`, `lang_active`, `lang_charset`) VALUES
('EN', 'English', 'english', 'gb.png', '1', 'windows-1252');


-- --------------------------------------------------------

--
-- Struktur för tabell `lang_flirt`
--

CREATE TABLE IF NOT EXISTS `lang_flirt` (
  `Flirt_ID` int(11) NOT NULL default '0',
  `Text` varchar(60) NOT NULL default '',
  `lang_id` char(2) NOT NULL default '',
  PRIMARY KEY  (`Flirt_ID`,`lang_id`)
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `lang_flirt`
--

INSERT INTO `lang_flirt` (`Flirt_ID`, `Text`, `lang_id`) VALUES
(15, 'A virtual rose just for you', 'EN'),
(14, 'You caught my eye', 'EN'),
(13, 'You stole my heart', 'EN'),
(16, 'You are cute, can we chat sometime?', 'EN'),
(17, 'You had me at hello...', 'EN'),
(18, 'What a great profile! How about mine?', 'EN'),
(19, 'You were made for me...', 'EN'),
(20, 'Hey don''t hide, upload your photo!', 'EN');

-- --------------------------------------------------------

--
-- Struktur för tabell `lang_mailtemplate`
--

CREATE TABLE IF NOT EXISTS `lang_mailtemplate` (
  `lang_id` char(3) NOT NULL default '',
  `MailTemplate_ID` int(11) NOT NULL default '0',
  `Body` mediumtext NOT NULL,
  PRIMARY KEY  (`lang_id`,`MailTemplate_ID`)
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `lang_mailtemplate`
--

INSERT INTO `lang_mailtemplate` (`lang_id`, `MailTemplate_ID`, `Body`) VALUES
('EN', 1, '<table cellspacing="0" cellpadding="0" width="450" border="0">\r\n    <tbody>\r\n        <tr>\r\n            <td align="center">\r\n            <p><img align="left" border="0" alt="" src="{ImagePath}/flirt4free.gif" /></p>\r\n            <p align="left"><font face="Verdana" size="2">Dear {ReceiverName} <br />\r\n            <br />\r\n            After seeing your profile at {CompanyName}, <b>{SenderName}</b> has expressed an interest in getting to know you a little better and send you flirt <b>{FlirtMessage}</b>, but before purchasing a membership, they would like to know whether you are interested in them.</font></p>\r\n            <p align="left"><font face="Verdana" size="2"><i>Flirts are a service for members to express an interest in one another before making a more in-depth response.</i> </font></p>\r\n            <p align="left"><font face="Verdana" size="2">Responding could not be simpler! <a href="{ProfileLink}"><b>Click here</b></a><a> to view <b>{SenderName}''s</b> profile and if interested, send a flirt back by clicking the flirt link at the top of the profile page.</a> </font></p>\r\n            <img align="left" border="0" alt="" src="{ImagePath}/flirt4free.gif" /></td>\r\n        </tr>\r\n    </tbody>\r\n</table>\r\n<div>&nbsp;</div>'),
('EN', 27, 'Dear {ReceiverName},\r\n\r\nYou have received a message from {SenderName} in your {CompanyName} mailbox. To read your e-mail, log onto {Url} and click the ''My E-mail'' button on the menu.\r\n\r\nIf you have any questions or comments please send them to {SupportEmail}\r\n\r\nAdministrator'),
('EN', 2, ' Dear {ReceiverName}<br>\r\n        After seeing your profile <b>{SenderName}</b> has expressed an interest in\r\n        getting to know you a little better and send you flirt <b>{FlirtMessage}</b>, but before purchasing a membership, they would like to know whether you are interested in them.\\n\r\n        Flirts are a service for  members to express an interest in one another before making a more in-depth response.\\n\r\n        Responding could not be simpler! View <b>{SenderName}''s</b> profile and if interested, send a flirt back by clicking the flirt link at the top of the profile page.'),
('EN', 28, '<div align=''left''>\r\n<table border=''0'' cellpadding=''0'' cellspacing=''0'' width=''80%''>\r\n<tr >\r\n<td colspan=''2'' width=''80%''>\r\n<font size=''2'' face=''Verdana''>Dear {UserName},</font>\r\n     <p><font size=''2'' face=''Verdana''>Thank you for registering with <a href=''{Url}''>{CompanyName}</a>.\r\n        Your email address is strictly for our own internal use and is not sold,\r\n        distributed or accessible to any other organization. Your privacy is\r\n        important to us and all information is supplied in strictest confidence.\r\n        Your login details are as follows:</font></td></tr><tr><td colspan=''2'' width=''80%'' height=''20''></td>\r\n      </tr>\r\n<tr><td width=''20%''><font size=''2'' face=''Verdana''>Username:</font></td>\r\n<td width=''80%''><b>\r\n<font size=''2'' face=''Verdana''>{UserName}</font></b></td>\r\n</tr>\r\n<tr><td width=''20%''><font size=''2'' face=''Verdana''>Password:</font></td>\r\n<td width=''80%''>\r\n<b><font size=''2'' face=''Verdana''>{Password}</font></b></td>\r\n</tr>\r\n\r\n<tr><td colspan=''2'' width=''80%'' height=''20''></td></tr>\r\n<tr><td colspan=''2'' width=''80%''><font face=''Verdana'' size=''2''>Please click the link below to validate your email address. You MUST do this before you are able to login to the site.</font>\r\n<p><font face=''Verdana'' size=''2''>\r\n<a href=''{ConfirmUrl}''>{ConfirmUrl}</a></font></p>\r\n\r\n<tr><td colspan=''2'' width=''80%'' height=''20''></td></tr>\r\n<tr><td colspan=''2'' width=''80%''><font face=''Verdana'' size=''2''>Please enjoy the site and use it responsibly. At the moment you do not have a personal profile, to make the most of your membership we recommend that you place your profile as soon as possible.   For maximum exposure then don''t forget to add your photo!</font>\r\n<p><font face=''Verdana'' size=''2''>If you have any questions or comments please send them to\r\n<a href=''mailto:{SupportEmail}''>{SupportEmail}</a></font></p>\r\n</td></tr>\r\n<tr><td colspan=''2'' width=''80%'' height=''60'' valign=''middle'' align=''left''>\r\n<font face=''Verdana'' size=''2''><i>The {CompanyName} Team</i></font>\r\n</td>\r\n</tr>\r\n<tr><td colspan=''2'' width=''80%''><font face=''Verdana'' size=''1''>Please note: We\r\n        operate an anti-Spam service and our messaging service is protected by\r\n        software to prevent the dissemination of Spam. Please do not use our\r\n        service to distribute chain-letters or any other information unrelated\r\n        to the purpose of this service. </font></td>\r\n     </tr></table></div>'),
('EN', 29, '<div align=''left''>\r\n<table border=''0'' cellpadding=''0'' cellspacing=''0'' width=''80%''>\r\n<tr >\r\n<td colspan=''2'' width=''80%''>\r\n<font size=''2'' face=''Verdana''>Dear {UserName},</font>\r\n       <p><font size=''2'' face=''Verdana''>Thank you for registering with <a href=''{Url}''>{CompanyName}</a>.\r\n       Your email address is strictly for our own internal use and is not sold,\r\n       distributed or accessible to any other organization. Your privacy is\r\n       important to us and all information is supplied in strictest confidence.\r\n       Your login details are as follows:</font></td></tr><tr><td colspan=''2'' width=''80%'' height=''20''></td>\r\n       </tr>\r\n<tr><td width=''20%''><font size=''2'' face=''Verdana''>Username:</font></td>\r\n<td width=''80%''><b>\r\n<font size=''2'' face=''Verdana''>{UserName}</font></b></td>\r\n</tr>\r\n<tr><td width=''20%''><font size=''2'' face=''Verdana''>Password:</font></td>\r\n<td width=''80%''>\r\n<b><font size=''2'' face=''Verdana''>{Password}</font></b></td>\r\n</tr>\r\n\r\n<tr><td colspan=''2'' width=''80%'' height=''20''></td></tr>\r\n<tr><td colspan=''2'' width=''80%''><font face=''Verdana'' size=''2''>Please enjoy the site and use it responsibly. At the moment you do not have a personal profile, to make the most of your membership we recommend that you place your profile as soon as possible.   For maximum exposure then don''t forget to add your photo!</font>\r\n<p><font face=''Verdana'' size=''2''>If you have any questions or comments please send them to\r\n<a href=''mailto:{SupportEmail}''>{SupportEmail}</a></font></p>\r\n</td></tr>\r\n<tr><td colspan=''2'' width=''80%'' height=''60'' valign=''middle'' align=''left''>\r\n<font face=''Verdana'' size=''2''><i>The {CompanyName} Team</i></font>\r\n</td>\r\n</tr>\r\n<tr><td colspan=''2'' width=''80%''><font face=''Verdana'' size=''1''>Please note: We\r\n       operate an anti-Spam service and our messaging service is protected by\r\n       software to prevent the dissemination of Spam. Please do not use our\r\n       service to distribute chain-letters or any other information unrelated\r\n       to the purpose of this service. </font></td>\r\n       </tr></table></div> '),
('EN', 30, 'Dear {ReceiverName},\r\nYour profile at {CompanyName} ({Url}) has been approved and is now active.\r\nRemember: to get the best response from your profile include a photograph. Profiles with photographs attract 5 - 10 times as many views as those that do not.\r\nIf you have any questions or comments please send them to {SupportEmail}\r\n\r\nAdministrator'),
('EN', 31, 'Dear {ReceiverName},\r\nUnfortunately we are unable to approve your profile at {CompanyName} ({Url}) due to the following reason(s):\r\n{Reason}\r\n\r\nPlease update the profile to correct the above we will be happy to review it again. If you have any questions or comments please send them to {SupportEmail}\r\n\r\nAdministrator'),
('EN', 32, 'Dear {UserName},\r\nYour booking of an event at {CompanyName} ({Url}) is accepted.\r\nThe event "{EventName}" will take place {EventDate} at {EventTime} in the address: \r\n{EventAddress}\r\nIf you have any questions or comments please send them to {SupportEmail}\r\nAdministrator'),
('EN', 33, 'Dear {ReceiverName},<br>\r\nyou ticket # {TicketNumber} to event\r\n"{EventName}" was canceled by {SenderName}'),
('EN', 3, 'Hi, your friend says:<br><br>\r\n\r\n{Message}<br>\r\nSee at this link: {ProfileUrl}<br><br>\r\n\r\nYou have received this e-mail because your friend thinks that you may be interested in checking out one of our members. Go to {SiteUrl} and register. It''s completely FREE (a limited time offer). <br><br>\r\n\r\nRegards,<br>\r\n{CompanyName} Team'),
('EN', 4, 'Hi, your friend says:<br><br>\r\n\r\n{Message}<br><br>\r\n\r\nYou have received this e-mail because your friend thinks that you may be interested in checking out one of our members. Go to {SiteUrl} and register. It''s completely FREE (a limited time offer). <br><br>\r\n\r\nRegards,<br>\r\n{CompanyName} Team'),
('EN', 5, 'Hi, your friend says:<br><br>\r\n\r\n{Message}<br><br>\r\n\r\nYou have received this e-mail because your friend thinks that you may be interested in checking out one of our members. Go to {SiteUrl} and register. It''s completely FREE (a limited time offer). <br><br>\r\n\r\nRegards,<br>\r\n{CompanyName} Team'),
('EN', 34, 'Dear {ReceiverName},\r\n\r\nYour blog at {CompanyName} ({Url}) has been approved and is now active.\r\n\r\nIf you have any questions or comments please send them to {SupportEmail}\r\n\r\nAdministrator'),
('EN', 35, 'Dear {ReceiverName},\r\n\r\nUnfortunately we are unable to approve your blog at {CompanyName} ({Url}) due to the following reason(s):\r\n{Reason}\r\n\r\nPlease update the blog to correct the above we will be happy to review it again. If you have any questions or comments please send them to {SupportEmail}\r\n\r\nAdministrator'),
('EN', 36, 'Dear {ReceiverName},\r\n\r\nYour post at {CompanyName} ({Url}) has been approved and is now active.\r\n\r\nIf you have any questions or comments please send them to {SupportEmail}\r\n\r\nAdministrator'),
('EN', 37, 'Dear {ReceiverName},\r\n\r\nUnfortunately we are unable to approve your post at {CompanyName} ({Url}) due to the following reason(s):\r\n{Reason}\r\n\r\nPlease update the post to correct the above we will be happy to review it again. If you have any questions or comments please send them to {SupportEmail}\r\n\r\nAdministrator'),
('EN', 38, 'Dear {ReceiverName},\r\n{SenderName} want to be you friend \r\nGo to site {ApproveLink} to approve or reject his request'),
('EN', 43, 'Hello, {MemberName}\r\n\r\nYour membership in "{GroupName}" group was approved.\r\n\r\nHave fun!'),
('EN', 44, 'Hello, {MemberName}\r\n\r\nYour membership in "{GroupName}" group was rejected.\r\n\r\nHave fun!'),
('EN', 42, 'Hello, {AuthorName}\r\n\r\nYour topic "{Topic}" in group "{GroupName}" was rejected by group owner.\r\nReason: {Reason}'),
('EN', 41, 'Hi {AuthorName},\r\nyour {Topic} for  {GroupName} was approved\r\n'),
('EN', 40, 'Hello, {OwnerName}\r\n\r\nYour newly created group "{GroupName}" was rejected by admin.\r\nReason: {Reason}'),
('EN', 39, 'Hello, {OwnerName}\r\n\r\nYour newly created group "{GroupName}" was approved by admin.\r\n\r\nHave fun!'),
('EN', 50, 'Dear Member,<br>\r\nAs requested, here are your {Url} login details.<br><br>\r\nYour Username is: {Login}<br>\r\nYour Password is: {Password}<br><br>\r\nIf you have any questions or comments please send them to {SupportEmail}<br><br>\r\nAdministrator'),
('EN', 51, 'Dear Member,<br><br>\r\nAs requested, here are your {Url}  login details.<br><br>\r\nYour Username is: {Login}<br>\r\nYour Password is: {Password}<br><br>\r\nPlease click the link below to validate your email address. You MUST do this before you are able to login to the site.<br><br>\r\n<a href="{ConfirmLink}">{ConfirmLink}</a><br><br>\r\nIf you have any questions or comments please send them to {SupportEmail}<br><br>\r\nAdministrator'),
('EN', 59, '<p>{ReceiverName}, </p>\r\n<p>{CompanyName}, </p>\r\n<p>{Url}, </p>\r\n<p>{SupportEmail}</p>');

-- --------------------------------------------------------

--
-- Struktur för tabell `lang_pagetemplate`
--

CREATE TABLE IF NOT EXISTS `lang_pagetemplate` (
  `lang_id` char(3) NOT NULL default '',
  `PageTemplate_ID` int(11) NOT NULL default '0',
  `Body` mediumtext NOT NULL,
  PRIMARY KEY  (`lang_id`,`PageTemplate_ID`)
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `lang_pagetemplate`
--

INSERT INTO `lang_pagetemplate` (`lang_id`, `PageTemplate_ID`, `Body`) VALUES
('EN', 1, '<p ALIGN="CENTER"><b>$CONST_COMPANY\r\n            MEMBER TERMS AND CONDITIONS</b></p>\r\n            <p ALIGN="left"></b>(YOU MUST BE AT LEAST 18 YEARS\r\n            OLD TO REGISTER ON OR USE THE $CONST_COMPANY WEBSITE. ACCESS OR USE\r\n            OF THIS SITE OR ITS CONTENTS BY ANY PERSON UNDER THE AGE OF 18 IS\r\n            STRICTLY PROHIBITED.)<br>\r\n            <br>\r\n            Please read this Member Terms and Conditions Agreement (the\r\n            "Agreement") carefully. The Agreement sets out the terms\r\n            under which $CONST_COMPANY services (the "Services") are\r\n            provided to you by us ($CONST_COMPANY, whose registered office is at $CONST_ADDR1, $CONST_ADDR2, $CONST_ADDR3. \r\n       $CONST_ADDR4) through the $CONST_URL website (the "Site"). By registering with\r\n            $CONST_COMPANY, you become a member of the Service (a\r\n            "Member"), and you agree to be bound by the terms and\r\n            conditions of this Agreement (the "Terms") for as long as\r\n            you continue to be a Member. If you do not agree to the Terms,\r\n            please do not register for the $CONST_COMPANY Service or otherwise\r\n            access or use this Site.<br>\r\n            <br>\r\n            We reserve the right at any time to:<br>\r\n            <br>\r\n            ? Change the terms and conditions of this Agreement;<br>\r\n            <br>\r\n            ? Change the Site, including eliminating or discontinuing any\r\n            content on or feature of the Site; or<br>\r\n            <br>\r\n            ? Change any fees or charges for using the Site.<br>\r\n            <br>\r\n            Any changes we make will be effective immediately upon notice, which\r\n            we may provide by means including, without limitation, posting on\r\n            the Site or email. Your use of the Site after such notice will be\r\n            deemed acceptance of the changes. Be sure to review this Agreement\r\n            periodically to ensure familiarity with the most current version.\r\n            Upon our request, you agree to sign a non-electronic version of this\r\n            Agreement.<br>\r\n            <br>\r\n            REGISTRATION. To become a Member you must register with us. When and\r\n            if you register to become a Member, you agree to provide accurate,\r\n            current and complete information about yourself as prompted by our\r\n            registration form ("Registration Data"), and to maintain\r\n            and update your information to keep it accurate, current and\r\n            complete. You agree that we may rely on your Registration Data as\r\n            accurate, current and complete. You acknowledge that if your\r\n            Registration Data is untrue, inaccurate, not current or incomplete\r\n            in any respect, we reserve the right to terminate this Agreement and\r\n            your use of the Site.<br>\r\n            <br>\r\n            By becoming a Member, you represent and warrant that you are at\r\n            least 18 years old, which is the minimum required age to become a\r\n            Member. However, we cannot guarantee that each Member is at least\r\n            the required minimum age, neither do we accept responsibility or\r\n            liability for any content, communication or other use or access of\r\n            the Site by persons under the age of 18 in violation of these Terms.<br>\r\n            <br>\r\n            Either you or $CONST_COMPANY may terminate your membership at any\r\n            time, for any reason, effective upon sending written notice to the\r\n            other party. All membership fees paid are non-refundable. We reserve\r\n            the right to immediately suspend or terminate your access to the\r\n            Service, without notice, upon any breach of this Agreement by you\r\n            that is brought to our attention.<br>\r\n            <br>\r\n            Your membership in the Service is for your sole, personal use. You\r\n            may not authorise others to use your membership, and you may not\r\n            assign or otherwise transfer your account to any other person or\r\n            entity.<br>\r\n            <br>\r\n            USERNAME AND PASSWORD. As part of the registration process, you will\r\n            be asked to select a username. We may refuse to grant any username\r\n            that impersonates someone else, is or may be illegal, is or may be\r\n            protected by trademark or other proprietary rights law, is vulgar or\r\n            otherwise offensive, or may cause confusion, as we determine in our\r\n            sole discretion. You are responsible for the confidentiality and use\r\n            of your username and password and agree not to transfer or resell\r\n            your use of or access to the Site to any third party. YOU ARE SOLELY\r\n            RESPONSIBLE FOR MAINTAINING THE CONFIDENTIALITY OF YOUR USERNAME AND\r\n            PASSWORD AND FOR ANY AND ALL ACTIVITIES THAT ARE CONDUCTED THROUGH\r\n            YOUR ACCOUNT.<br>\r\n            <br>\r\n            ONLINE CONDUCT. As a Member, you agree that:<br>\r\n            <br>\r\n            You are solely responsible for the content or information you\r\n            publish or display (hereinafter, "post") on the Service,\r\n            or transmit to other Members.<br>\r\n            <br>\r\n            You will not post on the Service, or transmit to other Members or\r\n            employees, any defamatory, inaccurate, abusive, obscene, profane,\r\n            offensive sexually oriented, threatening, harassing, racially\r\n            offensive, or illegal material, or any material that infringes or\r\n            violates another party''s rights (including, but not limited to,\r\n            intellectual property rights, and rights of privacy and publicity).\r\n            Please notify us by writing to $CONST_COMPANY, $CONST_ADDR1,\r\n            $CONST_ADDR2, $CONST_ADDR3. $CONST_ADDR4 or sending an email to\r\n            $CONST_MAIL of any material contained in the Service that\r\n            you believe infringes these rules.<br>\r\n            <br>\r\n            You will use the Service in a manner consistent with any and all\r\n            applicable laws and regulations. You will not include in your\r\n            profile any telephone numbers, street addresses, last names, URLs,\r\n            e-mail addresses, offensive anatomical or sexual references, or\r\n            offensive sexually suggestive or connotative language, or any\r\n            confidential information of any third person, and you will not post\r\n            any photographs or other images containing nudity or such personal\r\n            information. $CONST_COMPANY reserves the right, but has no\r\n            obligation, to reject any profile or photograph or image that does\r\n            not comply with these prohibitions.<br>\r\n            <br>\r\n            You will not impersonate any person or entity.<br>\r\n            <br>\r\n            You will not "stalk" or otherwise harass any person.<br>\r\n            <br>\r\n            You will not engage in advertising to, or solicitation of, other\r\n            Members to buy or sell any products or services through the Service.\r\n            You will not transmit any chain letters, spam or junk email to other\r\n            Members.<br>\r\n            <br>\r\n            You will not express or imply that any statements you make are\r\n            endorsed by us, without our specific prior written consent.<br>\r\n            <br>\r\n            You will not harvest or collect personal information about other\r\n            Members whether or not for commercial purposes, without their\r\n            express consent.<br>\r\n            <br>\r\n            You will not use any robot, spider, site search/retrieval\r\n            application, or other manual or automatic device or process to\r\n            retrieve, index, "data mine", or in any way reproduce or\r\n            circumvent the navigational structure or presentation of the Site or\r\n            its contents.<br>\r\n            <br>\r\n            You will not post, distribute or reproduce in any way any\r\n            copyrighted material, trademarks, or other proprietary information\r\n            without obtaining the prior consent of the owner of such proprietary\r\n            rights.<br>\r\n            <br>\r\n            You will not remove any copyright, trademark or other proprietary\r\n            rights notices contained in the Site.<br>\r\n            <br>\r\n            You will not interfere with or disrupt the Services or the Site or\r\n            the servers or networks connected to the Services or the Site.<br>\r\n            <br>\r\n            You will not post, e-mail or otherwise transmit any material that\r\n            contains software viruses or any other computer code, files or\r\n            programs designed to interrupt, destroy or limit the functionality\r\n            of any computer software or hardware or telecommunications\r\n            equipment. Please notify us by writing to $CONST_COMPANY, $CONST_ADDR1, $CONST_ADDR2, $CONST_ADDR3. \r\n     $CONST_ADDR4 or sending an\r\n            e-mail to $CONST_MAIL of any material contained in the\r\n            Service that you believe infringes these rules.<br>\r\n            <br>\r\n            You will not forge headers or otherwise manipulate identifiers in\r\n            order to disguise the origin of any information transmitted through\r\n            the Service.<br>\r\n            <br>\r\n            You will not "frame" or "mirror" any part of the\r\n            Site, without our prior written authorisation. You also shall not\r\n            use metatags or code or other devices containing any reference to\r\n            $CONST_URL or the Service or the Site in order to direct any\r\n            person to any other website for any purpose.<br>\r\n            <br>\r\n            You will not modify, adapt, sub-license, translate, sell, reverse\r\n            engineer, decipher, de-compile or otherwise disassemble any portion\r\n            of the Site or any software used on or for the Site or cause others\r\n            to do so.<br>\r\n            <br>\r\n            You are solely responsible for your interactions with other Members.\r\n            $CONST_COMPANY reserves the right, but has no obligation, to\r\n            monitor disputes between you and other Members.<br>\r\n            <br>\r\n            INDEMNITY BY MEMBER. You agree to indemnify, defend and hold us, our\r\n            officers, directors, employees, agents and representatives harmless\r\n            from and against any and all claims, damages, losses, liabilities,\r\n            costs (including reasonable attorneys'' fees) or other expenses that\r\n            arise directly or indirectly out of or from (i) your breach of this\r\n            Agreement; (ii) any allegation that any materials that you submit to\r\n            us or transmit to the Site infringe or otherwise violate the\r\n            copyright, trademark, trade secret or other intellectual property or\r\n            other rights of any third party; and/or (iii) your activities in\r\n            connection with the Site. This indemnity shall be applicable without\r\n            regard to the negligence of any party, including any indemnified\r\n            person.<br>\r\n            <br>\r\n            ONLINE CONTENT. Opinions, advice, statements, offers or other\r\n            information or content made available through the Service are those\r\n            of their respective Member-author and not of $CONST_COMPANY, and\r\n            should not necessarily be relied upon. Such Members are solely\r\n            responsible for such content. $CONST_COMPANY does not guarantee the\r\n            accuracy, completeness or usefulness of any information on the\r\n            Service and neither adopts nor endorses nor is responsible for the\r\n            accuracy or reliability of any opinion, advice or statement made.\r\n            Under no circumstances will $CONST_COMPANY be responsible for any\r\n            loss or damage resulting from anyone''s reliance on information or\r\n            other content posted on the Service or transmitted to Members.<br>\r\n            <br>\r\n            The Site may contain links to other Internet websites or resources.\r\n            We neither control nor endorse such other websites, nor have we\r\n            reviewed any content that appears on such other websites. You\r\n            acknowledge and agree that we shall not be held responsible for the\r\n            legality, accuracy or inappropriate nature of any content,\r\n            advertising, products, services or information located on or through\r\n            any other websites or for the operation or policies of any such\r\n            websites, nor for any loss or damages caused or alleged to have been\r\n            caused by the use of or reliance on any such websites or such\r\n            content or other information.<br>\r\n            <br>\r\n            WARNING - PLEASE READ: It is possible that other Members or users\r\n            (including unauthorised users, or "hackers") may post or\r\n            transmit offensive or obscene materials on the Service and that you\r\n            may be involuntarily exposed to such offensive and obscene\r\n            materials. It also is possible for others to obtain personal\r\n            information about you due to your use of the Service, and that the\r\n            recipient may use such information to harass or injure you.\r\n            $CONST_COMPANY is not responsible for the use of any personal\r\n            information that you may choose to disclose on the Service. Please\r\n            carefully select the type of information that you post on the\r\n            Service or release to others.<br>\r\n            <br>\r\n            $CONST_COMPANY reserves the right, but has no obligation, to\r\n            monitor the materials posted in any area of the Service. $CONST_COMPANY shall have the right but not the obligation to remove any such\r\n            material that violates, or is alleged to violate, the law or this\r\n            Agreement. Notwithstanding this right of $CONST_COMPANY, you remain\r\n            solely responsible for the content of the materials you post in any\r\n            area of the Service and your private email messages. E-mails sent\r\n            between you and other Members that are not readily accessible to the\r\n            general public will be treated as private by $CONST_COMPANY to the\r\n            extent required by applicable law and pursuant to the commercially\r\n            reasonable efforts of $CONST_COMPANY.<br>\r\n            <br>\r\n            We do not exclude any liability for death or personal injury due to\r\n            our negligence.<br>\r\n            <br>\r\n            PROPRIETARY RIGHTS. $CONST_COMPANY owns and retains other\r\n            proprietary rights in the Services and the Site. The Services and\r\n            the Site contain the copyrighted material, trademarks and other\r\n            proprietary information and software of $CONST_COMPANY and its\r\n            licensers. In addition, other Members may post copyrighted\r\n            information, which has copyright protection whether or not it is\r\n            identified as copyrighted. Except for that information which is in\r\n            the public domain or for which you have been given permission, you\r\n            will not copy, modify, publish, transmit, distribute, perform,\r\n            display or sell any such proprietary information. By posting\r\n            information or content to any area of $CONST_URL, you\r\n            automatically grant, and you represent and warrant that you have the\r\n            right to grant, to $CONST_COMPANY and other Members an irrevocable,\r\n            perpetual, non-exclusive, fully-paid, worldwide license to use,\r\n            copy, perform, display and distribute such information and content\r\n            and to prepare derivative works of, or incorporate into other works,\r\n            such information and content, and to grant and authorise\r\n            sub-licenses of the foregoing.<br>\r\n            <br>\r\n            INFORMATION SUPPLIED BY MEMBER. We will respect your personal\r\n            information and undertake to comply with all applicable data\r\n            protection legislation in the UK and other jurisdictions in which we\r\n            offer the Service, subject to the Privacy Policy of $CONST_COMPANY.<br>\r\n            <br>\r\n            The personal information (including sensitive personal information)\r\n            you provide to us will be stored on computer. You consent to us\r\n            using this information to build up a profile of interests,\r\n            preferences and browsing patterns and to allow you to participate in\r\n            the $CONST_URL Service. All Members also agree to uphold and\r\n            maintain our Privacy Policy and to the terms and conditions thereof.\r\n            You may read a full description of our Privacy Policy at $CONST_URL/privacy.php.<br>\r\n            <br>\r\n            DISCLAIMER OF WARRANTY. $CONST_COMPANY PROVIDES THE SERVICE AND THE\r\n            SITE ON AN "AS IS" BASIS AND GRANTS NO WARRANTIES OF ANY\r\n            KIND, EXPRESS, IMPLIED OR STATUTORY, IN ANY COMMUNICATION WITH\r\n            $CONST_COMPANY OR ITS REPRESENTATIVES, OR OTHERWISE WITH RESPECT TO\r\n            THE SERVICE OR THE SITE. $CONST_COMPANY SPECIFICALLY DISCLAIMS ANY\r\n            IMPLIED WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR\r\n            PURPOSE OR NONINFRINGEMENT. $CONST_COMPANY DOES NOT WARRANT THAT\r\n            YOUR USE OF THE SERVICE OR THE SITE WILL BE SECURE, UNINTERRUPTED,\r\n            ALWAYS AVAILABLE OR ERROR-FREE, OR WILL MEET YOUR REQUIREMENTS, OR\r\n            THAT ANY DEFECTS IN THE SERVICE OR THE SITE WILL BE CORRECTED.\r\n            $CONST_COMPANY DISCLAIMS LIABILITY FOR, AND NO WARRANTY IS MADE\r\n            WITH RESPECT TO, TELEPHONE OR OTHER SERVICE, INCLUDING COVERAGE,\r\n            RANGE, OR ANY INTERRUPTION IN TELEPHONE OR OTHER SERVICE.<br>\r\n            <br>\r\n            LIMITATION OF LIABILITY. IN NO EVENT WILL $CONST_COMPANY BE LIABLE\r\n            (i) TO YOU FOR ANY INCIDENTAL, CONSEQUENTIAL, OR INDIRECT DAMAGES\r\n            (INCLUDING, BUT NOT LIMITED TO, DAMAGES FOR LOSS OF DATA, LOSS OF\r\n            PROGRAMS, COST OF PROCUREMENT OF SUBSTITUTE SERVICES OR SERVICE\r\n            INTERRUPTIONS) ARISING OUT OF THE USE OF OR INABILITY TO USE THE\r\n            SERVICE OR THE SITE, EVEN IF $CONST_COMPANY OR ITS AGENTS OR\r\n            REPRESENTATIVES KNOW OR HAVE BEEN ADVISED OF THE POSSIBILITY OF SUCH\r\n            DAMAGES, OR (ii) TO ANY PERSON OTHER THAN YOU. NOTWITHSTANDING ANY\r\n            CONTRARY PROVISION HEREIN BUT SUBJECT TO THE FOREGOING LIMITATION ON\r\n            LIABILITY, IN ALL EVENTS OUR LIABILITY TO YOU FOR ANY\r\n            CAUSE WHATSOEVER, AND REGARDLESS OF THE FORM OF THE ACTION, WILL AT\r\n            ALL TIMES BE LIMITED TO THE AMOUNT PAID, IF ANY, BY YOU TO\r\n            $CONST_COMPANY FOR THE SERVICE DURING THE TERM OF MEMBERSHIP.<br>\r\n            <br>\r\n            In addition, $CONST_COMPANY disclaims all liability, regardless of\r\n            the form of action, for the acts or omissions of other Members or\r\n            users (including unauthorised users, or "hackers") of the\r\n            Service.<br>\r\n            <br>\r\n            Certain jurisdictions limit the applicability of warranty\r\n            disclaimers and limitations of liability so the above disclaimers of\r\n            warranty and limitations of liability may not apply to you.<br>\r\n            <br>\r\n            We do not exclude any liability for death or personal injury due to\r\n            our negligence.<br>\r\n            <br>\r\n            COPYRIGHT POLICY. We respect the intellectual property of others,\r\n            and we ask our Members to do the same. If you believe that a copy of\r\n            your work is available on the Service without your consent or that a\r\n            copyright infringement has otherwise occurred, please supply us with\r\n            the following information:<br>\r\n            <br>\r\n            ? A description of the copyrighted work that you claim has been\r\n            infringed;<br>\r\n            <br>\r\n            ? Details of where the infringing material is located on the Site;<br>\r\n            <br>\r\n            ? Your address, telephone number and e-mail address;<br>\r\n            <br>\r\n            ? A statement by you that you believe in good faith that the use of\r\n            the work on the Site is not authorised by the copyright owner or any\r\n            person entitled to act on their behalf or by law; and<br>\r\n            <br>\r\n            ? An affidavit executed by you that the information you provide\r\n            concerning the copyright infringed is accurate and that you are the\r\n            copyright owner authorised to act on their behalf.<br>\r\n            <br>\r\n            Please deliver this information to: $CONST_COMPANY. $CONST_ADDR1,\r\n            $CONST_ADDR2, $CONST_ADDR3. $CONST_ADDR4.<br>\r\n            <br>\r\n            COMPLAINTS. To resolve a complaint regarding the Service or the\r\n            Site, you should contact $CONST_COMPANY Customer Support at\r\n            $CONST_SUPPMAIL<br>\r\n            <br>\r\n            GENERAL PROVISIONS. This Agreement with you, set out in these Member\r\n            Terms and Conditions, is governed by English law and you and we\r\n            submit to the non-exclusive jurisdiction of the English Courts in\r\n            the event of any dispute regarding these Terms or the Service or the\r\n            Site or any matter related thereto. This Agreement shall not be\r\n            governed by the United Nations Convention on Contracts for the\r\n            International Sale of Goods, the application of which is hereby\r\n            expressly excluded.<br>\r\n            <br>\r\n            This Agreement, accepted by you upon registering for the Service,\r\n            contains the entire agreement between you and $CONST_COMPANY\r\n            regarding the Service and the Site, including the $CONST_COMPANY\r\n            Privacy Policy. In the event of any conflict between the Privacy\r\n            Policy and this Agreement, this Agreement shall control. Unless\r\n            otherwise explicitly stated, the Terms will survive termination of\r\n            your membership to the Service. This agreement shall terminate\r\n            immediately and without notice if you breach any of the Terms,\r\n            subject to the survival of all rights and reservations of\r\n            $CONST_COMPANY. If any provision of this Agreement is held invalid,\r\n            the remainder of this Agreement shall continue in full force and\r\n            effect.<br>\r\n            <br>\r\n            The Member certifies that the Member has read and agrees to be bound\r\n            by these Member Terms and Conditions.<br>\r\n            <br>\r\n            Refunds for paid membership are given at\r\n            the discretion of $CONST_COMPANY. Should you have any questions regarding your\r\n            payment, please direct them in the first instance to the site administrator (details\r\n            on the contact page).'),
('EN', 8, '<p>$CONST_COMPANY was formed in 1999 in             response to the increasing demand for Internet based personals. The             Internet as a medium lends itself well to this service due to it''s             wide availability and the degree of anonymity for it''s users.</p>\r\n<p>$CONST_COMPANY aims to meet the need             for a site that has higher standards in terms of the content. It is             our aim to avoid the purely sexual profiles by both professional and             personal advertisers without being judgmental about a persons             lifestyle. We hope that this will increase the chances of you             contacting genuine advertisers rather than chasing advertisers who             are only chasing your money!</p>\r\n<p>We believe that this site provides             a fresh and engaging forum for our users and we are committed to             offering the best value for money for the service. The original site             was a free site financed through banner advertising, but the             collapse in the value of this market and the fact that we could not             develop the site to target a larger audience (due to the increasing             cost of advertising) means that we have have been forced to switch             to a fee based service.</p>\r\n<p>We hope that you will enjoy this             site. Should you have any questions or comments then please feel             free to contact us at <a href="mailto:$CONST_MAIL">$CONST_MAIL</a></p>'),
('EN', 3, '$CONST_COMPANY has created this privacy statement in order to\r\n            demonstrate our firm commitment to your privacy. The following discloses\r\n            the information gathering and dissemination practices for this Web\r\n            site: $CONST_URL<br>\r\n            <br>\r\n            <b>\r\n            Information Automatically Logged</b><br>\r\n            <br>\r\n            We use your IP address to gather broad demographic information. IP\r\n            address are generally dynamically allocated (different every time\r\n            you log on). We use this information to get a general feeling of how\r\n            many people visit the site and from where they are referred.&nbsp;This\r\n            is done by our web host using standard logging technology.<br>\r\n            <br>\r\n            <b>\r\n            Cookies<br>\r\n            </b>\r\n            <br>\r\n            Our Site uses cookies to save your password so you don''t have to\r\n            re-enter it each time you visit a page on the site. The cookie is\r\n            session based (sits in the header information sent to the browser) and is destroyed after you close your browser. The\r\n            cookie neither reads nor writes anything to or from your computer\r\n            but cookies must be enabled to use the site as a member.&nbsp;<br>\r\n            <br>\r\n            <b>\r\n            Registration Forms</b><br>\r\n            <br>\r\n            Our site''s registration form requires members to give us contact\r\n            information (like their name and email address).</p>\r\n            <p>\r\n            Contact information from the registration forms is used to create a\r\n            unique record to identify the user to the system and for sending\r\n            information to the member but cannot and is not used for any other\r\n            purpose.<br>\r\n            <br>\r\n            Members may opt-out of receiving future mailings; see the\r\n            choice/opt-out section below.<br>\r\n            <br>\r\n            Demographic and profile data is also collected at our site. This is\r\n            used for your profiles and is made available to other members for the\r\n            purpose of their contacting you in the event that your profile\r\n            attracts their favorable attention.<br>\r\n            <br>\r\n            <b>\r\n            External Links<br>\r\n            </b>\r\n            <br>\r\n            This site contains links to other sites. $CONST_COMPANY is not\r\n            responsible for the privacy practices or the content of such Web\r\n            sites.<br>\r\n            <br>\r\n            <b>\r\n            Choice/Opt-Out</b><br>\r\n            <br>\r\n            Our site provides members the opportunity to opt-out of receiving\r\n            promotional/marketing information from us.<br>\r\n            <br>\r\n            Click the My Information button after logging in and amend/delete your registration.\r\n            Note: You may still receive important information regarding the\r\n            operation of the site from time to time even if you opt out of the\r\n            newsletter. This is a condition of being a member.<br>\r\n            <br>\r\n            <b>\r\n            Data Quality/Access<br>\r\n            </b>\r\n            <br>\r\n            This site gives members the following options for changing and\r\n            modifying information previously provided: click the\r\n            profile button after logging in and amend/delete your profile.<br>\r\n            <br>\r\n            <b>\r\n            Contacting the Web Site</b><br>\r\n            <br>\r\n            If you have any questions about this privacy statement, the\r\n            practices of\r\n            this site, or your dealings with this Web site, you can contact: \r\n        <a href="mailto:$CONST_AFFMAIL">$CONST_AFFMAIL</a>'),
('EN', 4, '<b>HELP!\r\n        What should I write in my profile?</b>\r\n        <p>So you''ve decided to try online personals. Well\r\n        you''ve come to the right place. Here are tips for writing attention\r\n        getting profiles.</p>\r\n        <p>Most online dating services ask standard\r\n        questions about yourself. It''s great to get the particulars of yourself\r\n        out of the way, but the key to a good personal ad is what you write in\r\n        the description &amp; headline fields. Think of your personal ad as an\r\n        advertisement for yourself. The goal is to draw people in with your\r\n        headline and keep the reader''s attention by writing an interesting\r\n        description of yourself.</p>\r\n        <p><b>Your Headline</b></p>\r\n        <p>The headline for your profile needs to be catchy\r\n        enough to get someone''s attention in just a few words. You can easily do\r\n        this by using keywords that best sum up your personality. If you''re\r\n        funny it should show in your headline. There''s no need to include\r\n        details about yourself such as your age since that information is listed\r\n        along with your headline. It''s also important <b>not</b> to say anything\r\n        that makes you sound desperate like in these examples:</p>\r\n        <p>Lonely Guy/Girl<br>\r\n        Sick of being single<br>\r\n        Desperate for love<br>\r\n        Tired of Looking</p>\r\n        <p>Remember, you''re essentially trying to sell\r\n        yourself and your positive qualities. So avoid negative statements in\r\n        your headline and have fun with it! It''s also a good idea to include\r\n        phrases like &quot;click here&quot; or ask a question like &quot;Are you\r\n        a movie fanatic?&quot; When you ask someone to do something or to answer\r\n        a question you''re more likely to get results.</p>\r\n        <p><b>Your Description</b></p>\r\n        <p>Now let''s talk about the Description for your\r\n        ad. This is your chance to say in your own words what you''re about. It''s\r\n        not enough to answer a bunch of generic questions. So GO FOR IT. Let\r\n        your personality show and don''t hold back. Keep in mind there are\r\n        hundreds of profiles just like yours that say the same things. So say\r\n        something to stand out from the crowd and make the reader curious to\r\n        find out more about you. Talk about things that are interesting to you\r\n        and stay away from clich&#1081;s. And please, please avoid making up things\r\n        about yourself. You''re only wasting your time and someone else''s by\r\n        being dishonest. I suggest writing enough to convey what you''re about\r\n        but not revealing everything about yourself initially. A good rule of\r\n        thumb is to keep your description within 200-300 words.</p>\r\n\r\n        <p>Good luck!!'),
('EN', 5, 'Affiliates Terms and Conditions\r\nThis agreement describes the entire terms and conditions for participation in the $CONST_COMPANY Affiliate Program.\r\nIn this agreement, the term "Participant" refers to you (the applicant), and "sponsoring Web site" refers to the Web site from which you will link the $CONST_COMPANY site. Wherever the agreement refers to "you" or "your", it means "the Participant"; "we" or "our" refers to the Net-Singes site. A "Registered User" is defined as a consumer who clicks the Logo Box (banner or text link) on your Web page, is connected to the $CONST_COMPANY site, completes the registration questionnaire. A "Subscribed User" is defined as a consumer who subscribes as a paying member, and is a premium member of our singles user database.\r\nPrivacy of the Associate Account: In order to join the Affiliate Program, you will need to provide certain information. All information will be held in strict confidence and will not be provided to any third parties except as required by law.\r\n$CONST_COMPANY Sites Referral Fee Percentage: You will earn 50% of gross revenue as a referral fee from new Premium Member subscriptions from members that have been referred via your affiliate link(s). Gross revenue only includes cash collected via valid credit card, cheque or money order or other means and does not include sales tax charges. In the event of the subscriber renewing his/her premium membership you will earn a further 25% for each renewal of the gross revenue of this transaction whilst your affiliate account is active.\r\nReferral Fee Payment: $CONST_COMPANY will send a referral fee cheque for the applicable referral fee (less any taxes required to be withheld under applicable law) to the Participant within approximately 15 days after the end of each calendar month if the total amount of the referral fees is equal to or greater than $30.00. If the amount owed you is less than $30.00 then your balance will be carried forward to the next month or until the total sum of your referrals is equal to or greater than $30.00 or upon the termination, by you or $CONST_COMPANY, in the program. Payments will be in GBP (&#1032;UK), payable at the rate of exchange applicable at the date of each transaction.\r\nRefunds and Charge-backs: All payments are subject to an 8 week lead time to insure against refunds and charge-backs. This is a stipulation of our payment processing company.\r\nSubscription Payment Processing: $CONST_COMPANY will be solely responsible for processing every subscription order placed by a customer. Subscription orders will be accepted via the $CONST_COMPANY online ordering process which utilises WorldPay payment servers. Payment processing, renewal payment processing, cancellations and refund processing, and related customer service are all the responsibility of $CONST_COMPANY.\r\nAll of the rules, operating procedures and policies of $CONST_COMPANY  regarding customer subscriptions will apply to all subscription orders we via our affiliates. $CONST_COMPANY  reserves the right to reject any subscription order that does not comply with our rules, operating procedures and policies.\r\nTracking of Subscription Sales: $CONST_COMPANY will be solely responsible for tracking subscription sales. Statements of subscription sales activity are provided to the Participant through the affiliate centre pages. To protect $CONST_COMPANY  customer privacy, the names or other personal information about specific customers will not be provided to the Participant.\r\nTime Limitations For Depositing Associate Cheques: Participants are required to cash or deposit any cheque from $CONST_COMPANY within ninety (90) days of the date the cheque was issued. $CONST_COMPANY is not required to re-issue payment for associate cheques older than ninety (90) days.\r\nLost, Stolen, Or Destroyed Cheques: It is the responsibility of the associate to request in writing a reissue of lost, stolen or destroyed cheques within the ninety (90) day period outlined above. The request must include as much information about the dates and earnings in question as possible, including the Affiliate ID of the account and to whom the cheque was issued. Email requests are sufficient. Reissued cheques are held to the same ninety (90) day expiration rules. $CONST_COMPANY may receive returned cheques because the mailing address provided by the associate is incorrect or insufficient. In these cases, we will contact the associate upon receipt of the returned cheque. It is the responsibility of the associate to respond and correct the information as necessary. After thirty (30) days without a reply from the associate regarding the issue, the cheque will become void and $CONST_COMPANY will not be required to re-issue payment and all payments will become null and void, and the associate account will be terminated.\r\nCopyrighted material: As the Participant, you are solely responsible for ensuring that your logo and preamble text obey all applicable copyright and other laws. You must have express permission to use another party''s copyrighted material. $CONST_COMPANY will not be responsible if you use another party''s copyrighted material in violation of the law.\r\n$CONST_COMPANY policy applies to all orders: Every customer who buys a subscription through this program is deemed to be a customer of $CONST_COMPANY. The Participant does not have the authority to make or accept any offer on behalf of $CONST_COMPANY. All $CONST_COMPANY policies regarding customer orders, including pricing and problem resolution, will apply to these customers. $CONST_COMPANY is not responsible for any representations made by the Participant which contradict our policies.\r\nPrices and availability: The price charged for subscriptions sold under this program will be determined by $CONST_COMPANY according to our own pricing policies. Prices may vary from time to time. $CONST_COMPANY  policies will always determine the price paid by the customer.\r\nSites that Do Not Qualify for the $CONST_COMPANY Affiliates Program include:\r\n? Sites that promote sexually explicit material with minors (under 18 years of age)\r\n? Sites that promote violence\r\n? Sites that promote discrimination based on race, sex, religion, national origin, physical disability, sexual orientation or age\r\n? Sites that promote illegal activities\r\nSpamming Policy: $CONST_COMPANY in no way participates in mass unsolicited emailing (i.e. spamming), and all Participants are expected to adhere to this policy as well. Violation of this policy will result in the termination of this contract and immediate dismissal from the $CONST_COMPANY Affiliate Program.\r\nWeb Site service interruption: $CONST_COMPANY  will make every effort to keep its Web site operational. However, certain technical difficulties may, from time to time, result in temporary service interruptions. The Participant agrees not to \r\n$CONST_COMPANY liable for any of the consequences of such interruptions.\r\nTerm of the Agreement: The Participant may receive referral fee payments on orders that are placed during the term of this agreement. This term will begin on the date that your application is approved by $CONST_COMPANY, and shall end when cancelled by either party as provided below. Referral Fees earned through the date of expiration or cancellation of this agreement will remain payable only if the subscriptions are not refunded. Payment of the final referral fee payment to the Participant may be withheld for a reasonable time in order to ensure that the correct amount is paid.\r\nThe agreement may be modified: $CONST_COMPANY reserves the right to change any of the terms and conditions in this agreement, at any time and at its sole discretion, by posting a new agreement on our Web site.\r\nWithout limiting the generality of the foregoing, referral fee schedules, and all other provisions of this agreement are subject to change without notice other than posting such information on our Web site.\r\nCancellation of this agreement: Either $CONST_COMPANY or the Participant, acting in their sole discretion, may choose to cancel this agreement at any time by written notice of cancellation to the other.\r\nWarranty Disclaimer: $CONST_COMPANY makes no warranties, representations or conditions with regard to the program or, except as expressly set forth $CONST_COMPANY then current sales policies, any subscriptions sold thereunder, whether express or implied, arising by law or otherwise, including without limitation any implied warranty of merchantability or fitness for a particular purpose or non-infringement or any implied warranty arising out of course of performance, course of dealing or usage of trade.\r\nLimitation of Damages: $CONST_COMPANY shall have no liability for any indirect, incidental, special or consequential damages or any loss of revenue or profits arising under or with respect to this agreement or the program, even if $CONST_COMPANY has been advised of the possibility of such damages. Further, $CONST_COMPANY aggregates liability arising under or with respect to this agreement or the program shall in no event exceed the total referral fees paid or payable by $CONST_COMPANY to Participant under this agreement.\r\nMiscellaneous: Participant and $CONST_COMPANY are independent contractors and nothing in this agreement is intended to or will create any form of partnership, joint venture, agency, franchise, sales representative or employment relationship between the parties. Participant shall not assign this Agreement, by operation of law or otherwise, without the prior written consent of $CONST_COMPANY\r\n. Subject to the foregoing restriction, this agreement is binding upon, insures to the benefit of and is enforceable by the parties and their respective successors and assigns.\r\nGoverning Law: This agreement shall be governed by the laws of the United Kingdom of Great Britain and Northern Ireland without reference to its choice of law principles.\r\n'),
('EN', 6, 'Welcome to $CONST_COMPANY, we\r\n            hope that you enjoy using the site and that you check back regularly\r\n            for updates. Below is a guide to help you use the system more\r\n            effectively.\r\n            <p><strong>Searching - </strong>There\r\n            are several criteria upon which you can search. These include the\r\n            advertisers age, country of residence, body type, sex and whether\r\n            the profile includes a photo. If you are a male seeking a female then\r\n            search for ''Women seeking Men''. If you wish to see only profiles with\r\n            photos the tick the ''Photo Ads Only'' box. You can quickly move through the regions\r\n            box by pressing the ''-'' (dash) on your keyboard.</p>\r\n            <p>If you wish to find an individual\r\n            member, you may type their username directly into the ''Username'' text\r\n            box and click search.</p>\r\n            <p><strong>Register - </strong>Basic registration\r\n            is FREE. You need to register in order to place a profile as your registered name is what identifies you in the\r\n            system and allows us to find your profile for you to edit through the\r\n            ''My Information'' option. <strong>Tip:</strong> Enter your e-mail address\r\n            carefully as your password is automatically sent to this address. If\r\n            you make a mistake, you will not receive your password.</p>\r\n            <p><strong>Login - </strong>As a\r\n            member, you may login either at the entry screen. <strong>Tip: </strong>If you forget your&nbsp;\r\n            username or password then you can have it resent by clicking on the ''resend\r\n            password'' link under the login box. Enter the e-mail address you registered\r\n            with and we will send you the details you require.</p>\r\n            <p><strong>Add Profile - </strong>Profiles\r\n            are FREE to place and are much more fun than simply registering. Our\r\n            studies found that if you place a profile you will be 5 more times likely to\r\n            receive a response than if you respond to someone else''s\r\n            profile. You\r\n            can change or delete your profile at any time through the ''My\r\n            Information''\r\n            option. <strong>Tip: </strong>Try to keep the size of your photo\r\n            smaller than 20k or you may have trouble uploading it (depending on your internet connection).\r\n            Use an image editor such as Paint Shop Pro to reduce the size of your image by\r\n            cutting out unnecessary ''white space'' and compressing the image.</p>\r\n            \r\n        <p><strong>My Information - </strong>This section lets you delete or edit \r\n          your profile or change your registration details (if for example you \r\n          change your main e-mail address). You can also de-register from here \r\n          and you will no longer receive e-mails from us telling you of updates.&nbsp;</p>\r\n        <p><strong>Chat - </strong>The chatroom is for non-trial members to communicate \r\n          on either a personal or community level Click the chat room button on \r\n          the left-hand menu and the chat room you will automatically be logged \r\n          into the chat room using your username. Simply enter your message in \r\n          the blue box and press send to display your message to other users. \r\n          If you prefer to communicate on a one-to-one basis, double-click the \r\n          username in the list on the right to display a private chat window that \r\n          can only be seen by you and the person you are communicating with.</p>\r\n        <p><strong>Instant Messenger - </strong>The Instant messenger gives you \r\n          a private chat facility for other members that are logged in to the \r\n          Instant Messenger. When launched, it will display a list of members \r\n          online. Select the member you wish to message by clicking their username. \r\n          This action will display a message box for you to enter a message. Click \r\n          an emoticon to enter it at any point in your message and press send \r\n          to message the user. Although you can communicate with several people \r\n          simultaneously, only the member you are messaging will see your message.</p>\r\n            <p><strong>If you feel that something\r\n            needs to be added to this help page let us know.</strong>\r\n');
INSERT INTO `lang_pagetemplate` (`lang_id`, `PageTemplate_ID`, `Body`) VALUES
('EN', 7, '<p><b><u><a href="$CONST_LINK_ROOT/affiliates/">Affiliate Login >></a></u></b></p>\n<p><img border="0" alt="" align="right" width="209" height="309" src="$CONST_IMAGE_ROOT/$CONST_IMAGE_LANG/money.jpg" />Thank you for showing interest in\r               our affiliate scheme. Below is information about our affiliate\r               scheme and how it works. If you have any\r               further questions, please mail us at <a href="mailto:$CONST_AFFMAIL">$CONST_AFFMAIL\r</a></p>\n<p><b>How does the scheme work?</b></p>\n<p>Once your application is accepted,\r               you will be sent a confirmation e-mail. This mail will include the\r               URL to the affiliate center plus your unique tracking number and\r \r               login/password. You can retrieve the code for banners or text\r               links from the affiliate center. Placing this code into a web page on your own site\r               will display a banner on the page. This banner\r               includes a unique tracking number (your affiliate number) that is sent to our site if a\r               user clicks on the banner.</p>\n<p>If a user free registers on our\r               site, your affiliate number is associated with the registration in our\r               database. Therefore there is a permanent record of who referred the user.</p>\n<p><b>How do I make money?</b></p>\n<p>Every free member you send to our\r               site and that subsequently goes on to become a paid subscriber\r               will earn you cash. Unlike many affiliate schemes there is no time\r               limit in which the user can subscribe and still earn you cash!!\r               And not only that but every time that user renews you will\r               continue to earn\r               MORE!</p>\n<p><b>How much money can I make?</b></p>\n<p>Well that is really up to you. Where\r               you place the banner or the link will affect the number of users\r               sent to our site and of course the more users you send the more\r               subscribers there are likely to be. Our rates are as follows:</p>\n<p>Initial Referral (i.e. the first time\r               the user subscribes to paid membership):</p>\n<table border="1" cellpadding="0" width="491" bordercolorlight="#C0C0C0">\n    <tbody>\n        <tr>\n            <td width="122"><b>Subscription</b></td>\n            <td width="122"><b>Price</b></td>\n            <td width="122"><b>Commission</b></td>\n            <td width="123"><b>You earn</b></td>\n        </tr>\n        <tr>\n            <td width="122">One Month</td>\n            <td width="122">$CONST_SYMBOL%s</td>\n            <td width="122">%s%%</td>\n            <td width="123">$CONST_SYMBOL%s</td>\n        </tr>\n        <tr>\n            <td width="122">Three Months</td>\n            <td width="122">$CONST_SYMBOL%s</td>\n            <td width="122">%s%%</td>\n            <td width="123">$CONST_SYMBOL%s</td>\n        </tr>\n        <tr>\n            <td width="122">Six Months</td>\n            <td width="122">$CONST_SYMBOL%s</td>\n            <td width="122">%s%%</td>\n            <td width="123">$CONST_SYMBOL%s</td>\n        </tr>\n        <tr>\n            <td width="122">Twelve Months</td>\n            <td width="122">$CONST_SYMBOL%s</td>\n            <td width="122">%s%%</td>\n            <td width="123">$CONST_SYMBOL%s</td>\n        </tr>\n    </tbody>\n</table>\n<p>Subsequent Referral (i.e. every\r               subscription paid by the same user after the first):</p>\n<table border="1" cellpadding="0" width="491" bordercolor="#C0C0C0">\n    <tbody>\n        <tr>\n            <td width="122"><b>Subscription</b></td>\n            <td width="122"><b>Price</b></td>\n            <td width="122"><b>Commission</b></td>\n            <td width="123"><b>You earn</b></td>\n        </tr>\n        <tr>\n            <td width="122">One Month</td>\n            <td width="122">$CONST_SYMBOL%s</td>\n            <td width="122">%s%%</td>\n            <td width="123">$CONST_SYMBOL%s</td>\n        </tr>\n        <tr>\n            <td width="122">Three Months</td>\n            <td width="122">$CONST_SYMBOL%s</td>\n            <td width="122">%s%%</td>\n            <td width="123">$CONST_SYMBOL%s</td>\n        </tr>\n        <tr>\n            <td width="122">Six Months</td>\n            <td width="122">$CONST_SYMBOL%s</td>\n            <td width="122">%s%%</td>\n            <td width="123">$CONST_SYMBOL%s</td>\n        </tr>\n        <tr>\n            <td width="122">Twelve Months</td>\n            <td width="122">$CONST_SYMBOL%s</td>\n            <td width="122">%s%%</td>\n            <td width="123">$CONST_SYMBOL%s</td>\n        </tr>\n    </tbody>\n</table>\n<p>Note: Commissions\r               are subject to price variations on the site due to special\r               promotions etc. Therefore if the price is reduced or increased for any reason,\r               then the commission will also reflect that change.</p>\n<p><b>How and when do I receive the\r               commission?</b></p>\n<p>Commissions will be paid monthly and you will receive all commission on payments that have been\r               cleared at least 8 weeks. We only pay after at least 8 weeks due to the\r               risk of charge-backs should the customer''s card have been stolen or\r               misused in some way.</p>\n<p>You will receive your commission\r               through the post by cheque in &#1032;''s (GBP) payable at the rate of\r               exchange applicable at the time of the transaction.\r               Affiliates are responsible for charges relating to currency\r               conversion that their bank may charge. Affiliates that show a\r               high referral rate and earn more than $500 per quarter may\r               negotiate to be paid by bank transfer in US Dollars.</p>\n<p>Note: There is a $30 minimum\r               required to be accrued before payment can be made. If the account\r               does not accrue this minimum during the month, the payment will be\r               held over until the following month and so on until the condition\r               is satisfied.</p>\n<p><a href="$CONST_LINK_ROOT/affiliates.php">Apply Now</a> | <a href="$CONST_LINK_ROOT/index.php">No Thanks</a></p>'),
('RU', 8, 'test ru ');

-- --------------------------------------------------------

--
-- Struktur för tabell `mailtemplate`
--

CREATE TABLE IF NOT EXISTS `mailtemplate` (
  `MailTemplate_ID` int(11) NOT NULL auto_increment,
  `Name` varchar(50) NOT NULL default '',
  `Description` varchar(255) NOT NULL default '',
  `Params` mediumtext NOT NULL,
  `Type` enum('text/html','text/plain') NOT NULL default 'text/html',
  PRIMARY KEY  (`MailTemplate_ID`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=61 ;

--
-- Data i tabell `mailtemplate`
--

INSERT INTO `mailtemplate` (`MailTemplate_ID`, `Name`, `Description`, `Params`, `Type`) VALUES
(1, 'Flirt_Mail', 'DB_MTEMPLATE_FLIRT_MAIL', 'ReceiverName;CompanyName;SenderName;FlirtMessage;ProfileLink;ImagePath;', 'text/html'),
(27, 'You_Have_Got_A_Mail', 'DB_MTEMPLATE_YOU_HAVE_GOT_A_MAIL', 'ReceiverName;SenderName;CompanyName;Url;SupportEmail;', 'text/plain'),
(2, 'Flirt_Message', 'DB_MTEMPLATE_FLIRT_MESSAGE', 'ReceiverName;CompanyName;SenderName;FlirtMessage;ProfileLink;ImagePath;', 'text/html'),
(28, 'Welcome_Mail_Activate', 'DB_MTEMPLATE_WELCOME_MAIL_ACTIVATE', 'UserName;Url;CompanyName;ConfirmUrl;SupportEmail;Password;', 'text/html'),
(29, 'Welcome_Mail', 'DB_MTEMPLATE_WELCOME_MAIL', 'UserName;Url;CompanyName;ConfirmUrl;SupportEmail;Password;', 'text/html'),
(30, 'Approve_Mail', 'DB_MTEMPLATE_APPROVE_MAIL', 'ReceiverName;CompanyName;Url;SupportEmail;', 'text/plain'),
(31, 'Reject_Mail', 'DB_MTEMPLATE_REJECT_MAIL', 'ReceiverName;CompanyName;Url;SupportEmail;Reason; ', 'text/plain'),
(32, 'Event_Booked_Email', 'DB_MTEMPLATE_EVENT_BOOKED_EMAIL', 'UserName;CompanyName;SupportEmail;EventName;EventPrice;EventDate;EventTime;EventAddress;Url;', 'text/plain'),
(33, 'SD_Cancel_Ticket', 'DB_MTEMPLATE_SD_CANCEL_TICKET', 'ReceiverName;TicketNumber;EventName;SenderName;', 'text/html'),
(3, 'Tip_A_Friend', 'DB_MTEMPLATE_TIP_A_FRIEND', 'ProfileUrl;CompanyName;SiteUrl;Message;', 'text/html'),
(4, 'Tell_A_Friend_Member', 'DB_MTEMPLATE_TELL_A_FRIEND_MEMBER', 'CompanyName;SiteUrl;Message;', 'text/html'),
(5, 'Tell_A_Friend_Guest', 'DB_MTEMPLATE_TELL_A_FRIEND_GUEST', 'CompanyName;SiteUrl;Message;', 'text/html'),
(34, 'Approve_Blog', 'DB_MTEMPLATE_APPROVE_BLOG', 'ReceiverName;CompanyName;Url;SupportEmail;', 'text/plain'),
(35, 'Reject_Blog', 'DB_MTEMPLATE_REJECT_BLOG', 'ReceiverName;CompanyName;Url;SupportEmail;Reason; ', 'text/plain'),
(36, 'Approve_Forum', 'DB_MTEMPLATE_APPROVE_FORUM', 'ReceiverName;CompanyName;Url;SupportEmail;', 'text/plain'),
(37, 'Reject_Forum', 'DB_MTEMPLATE_REJECT_FORUM', 'ReceiverName;CompanyName;Url;SupportEmail;Reason; ', 'text/plain'),
(38, 'Friendship_Request', 'DB_MTEMPLATE_FRIENDSHIP_REQUEST', 'ReceiverName;SenderName;ApproveLink;RejectLink', 'text/html'),
(39, 'Approve_Group', 'DB_MTEMPLATE_APPROVE_GROUP', 'OwnerName;GroupName', 'text/html'),
(40, 'Reject_Group', 'DB_MTEMPLATE_REJECT_GROUP', 'OwnerName;GroupName;Reason', 'text/html'),
(41, 'Approve_Group_Topic', 'DB_MTEMPLATE_APPROVE_GROUP_TOPIC', 'AuthorName;GroupName;Topic', 'text/html'),
(42, 'Reject_Group_Topic', 'DB_MTEMPLATE_REJECT_GROUP_TOPIC', 'AuthorName;GroupName;Topic;Reason', 'text/html'),
(43, 'Approve_Group_Membership', 'DB_MTEMPLATE_APPROVE_GROUP_MEMBERSHIP', 'MemberName;GroupName', 'text/html'),
(44, 'Reject_Group_Membership', 'DB_MTEMPLATE_REJECT_GROUP_MEMBERSHIP', 'MemberName;GroupName', 'text/html'),
(50, 'Login_Resend', 'DB_MTEMPLATE_RESEND', 'Login;Password;Url;SupportEmail;', 'text/html'),
(51, 'Confirm_Resend', 'DB_MTEMPLATE_CONFIRM_RESEND', 'Login;Password;Url;SupportEmail;ConfirmLink;', 'text/html'),
(59, 'Approve_Video', 'DB_MTEMPLATE_APPROVE_VIDEO', 'ReceiverName;CompanyName;Url;SupportEmail;', 'text/html'),
(60, 'Reject_Video', 'DB_MTEMPLATE_REJECT_VIDEO', 'ReceiverName;CompanyName;Url;SupportEmail;', 'text/plain');

-- --------------------------------------------------------

--
-- Struktur för tabell `mail_queue`
--

CREATE TABLE IF NOT EXISTS `mail_queue` (
  `MailQueue_ID` int(11) NOT NULL auto_increment,
  `Email` varchar(255) NOT NULL default '',
  `From` varchar(255) NOT NULL default '',
  `Subject` varchar(255) NOT NULL default '',
  `Body` mediumtext NOT NULL,
  `Type` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`MailQueue_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=87 ;

--
-- Data i tabell `mail_queue`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `mail_queue_stat`
--

CREATE TABLE IF NOT EXISTS `mail_queue_stat` (
  `MailQueueStat_ID` int(11) NOT NULL auto_increment,
  `Size` int(11) NOT NULL default '0',
  `startTime` datetime NOT NULL default '0000-00-00 00:00:00',
  `endTime` datetime NOT NULL default '0000-00-00 00:00:00',
  `Errors` mediumtext NOT NULL,
  PRIMARY KEY  (`MailQueueStat_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=416 ;

--
-- Data i tabell `mail_queue_stat`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `member2group`
--

CREATE TABLE IF NOT EXISTS `member2group` (
  `memberid` int(10) unsigned NOT NULL default '0',
  `groupid` int(10) unsigned NOT NULL default '0',
  `status` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`memberid`,`groupid`)
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `member2group`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `members`
--

CREATE TABLE IF NOT EXISTS `members` (
  `mem_userid` int(11) NOT NULL auto_increment,
  `mem_username` varchar(25) NOT NULL default '',
  `mem_password` varchar(10) NOT NULL default '',
  `mem_surname` varchar(25) default NULL,
  `mem_forename` varchar(25) default NULL,
  `mem_email` varchar(75) default NULL,
  `mem_newsletter` smallint(6) default '1',
  `mem_joindate` date default NULL,
  `mem_dob` date default NULL,
  `mem_expiredate` date default NULL,
  `mem_lastvisit` date default NULL,
  `mem_sex` char(1) default NULL,
  `mem_type` char(1) default NULL,
  `mem_emailtype` char(1) default NULL,
  `mem_update` smallint(6) default '1',
  `mem_referrer` int(11) default NULL,
  `mem_country` varchar(50) default NULL,
  `mem_online` enum('Y','N') NOT NULL default 'N',
  `mem_timeout` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `lang_id` char(3) NOT NULL default '',
  `mem_confirm` tinyint(3) unsigned NOT NULL default '0',
  `mem_ip` varchar(255) NOT NULL default '',
  `mem_suspend` enum('Y','N') NOT NULL default 'N',
  `mem_skype` varchar(255) default NULL,
  `mem_skypeset` varchar(10) default NULL,
  `mem_block_mail` int(1) default NULL,
  `mem_sms` tinyint(4) NOT NULL default '0',
  `mem_mobile` varchar(15) NOT NULL default '',
  `mem_carrier` int(11) NOT NULL default '0',
  PRIMARY KEY  (`mem_userid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14840 ;

--
-- Data i tabell `members`
--

INSERT INTO `members` (`mem_userid`, `mem_username`, `mem_password`, `mem_surname`, `mem_forename`, `mem_email`, `mem_newsletter`, `mem_joindate`, `mem_dob`, `mem_expiredate`, `mem_lastvisit`, `mem_sex`, `mem_type`, `mem_emailtype`, `mem_update`, `mem_referrer`, `mem_country`, `mem_online`, `mem_timeout`, `lang_id`, `mem_confirm`, `mem_ip`, `mem_suspend`, `mem_skype`, `mem_skypeset`, `mem_block_mail`, `mem_sms`, `mem_mobile`, `mem_carrier`) VALUES
(14651, 'manager', 'demo123', 'Fox', 'Dylan', 'contact@interactive-arts.biz', 1, '2006-07-15', '1966-01-29', '2017-08-31', '2008-11-25', 'M', 'A', 'H', 0, 0, NULL, 'N', '2008-11-25 00:41:50', 'EN', 1, '86.137.89.76', 'N', '', 'ALL', 0, 0, '', 0);
-- --------------------------------------------------------

--
-- Struktur för tabell `messages`
--

CREATE TABLE IF NOT EXISTS `messages` (
  `msg_id` int(11) NOT NULL auto_increment,
  `msg_senderid` int(11) NOT NULL default '0',
  `msg_receiverid` int(11) NOT NULL default '0',
  `msg_senderhandle` varchar(30) NOT NULL default '',
  `msg_title` varchar(60) default NULL,
  `msg_text` text,
  `msg_dateadded` datetime default NULL,
  `msg_read` char(1) default NULL,
  `msg_senderdel` enum('Y','N') NOT NULL default 'N',
  `msg_receiverdel` enum('Y','N') NOT NULL default 'N',
  `msg_delhiderec` enum('Y','N') NOT NULL default 'N',
  `msg_delhidesen` enum('Y','N') NOT NULL default 'N',
  `msg_replied` enum('Y','N') NOT NULL default 'N',
  `msg_flirt` enum('Y','N') NOT NULL default 'N',
  PRIMARY KEY  (`msg_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1842 ;

--
-- Data i tabell `messages`
--

-- --------------------------------------------------------

--
-- Struktur för tabell `mymatch`
--

CREATE TABLE IF NOT EXISTS `mymatch` (
  `mym_userid` int(11) NOT NULL default '0',
  `mym_gender` varchar(255) NOT NULL default '',
  `mym_smoker` varchar(15) default NULL,
  `mym_comment` text,
  `mym_minheight` varchar(15) default NULL,
  `mym_maxheight` varchar(15) default NULL,
  `mym_bodytype` varchar(15) default NULL,
  `mym_agemin` tinyint(4) default NULL,
  `mym_agemax` tinyint(4) default NULL,
  `mym_relationship` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`mym_userid`)
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `mymatch`
--

-- --------------------------------------------------------

--
-- Struktur för tabell `my_friends`
--

CREATE TABLE IF NOT EXISTS `my_friends` (
  `uid` varchar(25) NOT NULL default '',
  `friend_uid` varchar(25) NOT NULL default '',
  `status` char(1) NOT NULL default 'A',
  `is_deleted` char(1) NOT NULL default 'F'
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `my_friends`
--

-- --------------------------------------------------------

--
-- Struktur för tabell `networkinvite`
--

CREATE TABLE IF NOT EXISTS `networkinvite` (
  `User_id` int(11) NOT NULL default '0',
  `Email` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`User_id`,`Email`)
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `networkinvite`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `news_id` int(11) NOT NULL auto_increment,
  `title` varchar(250) default NULL,
  `body` mediumtext,
  PRIMARY KEY  (`news_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Data i tabell `news`
--

-- --------------------------------------------------------

--
-- Struktur för tabell `notifications`
--

CREATE TABLE IF NOT EXISTS `notifications` (
  `ntf_id` int(11) NOT NULL auto_increment,
  `ntf_senderid` int(11) NOT NULL default '0',
  `ntf_senderhandle` varchar(30) NOT NULL default '',
  `ntf_receiverid` int(11) NOT NULL default '0',
  `ntf_receiverhandle` varchar(30) NOT NULL default '',
  `ntf_dateadded` date default NULL,
  `ntf_response` char(1) default NULL,
  PRIMARY KEY  (`ntf_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;

--
-- Data i tabell `notifications`
--

-- --------------------------------------------------------

--
-- Struktur för tabell `notify_events`
--

CREATE TABLE IF NOT EXISTS `notify_events` (
  `id` int(11) NOT NULL auto_increment,
  `userid` int(11) NOT NULL default '0',
  `text` varchar(255) default NULL,
  `created` datetime default NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Data i tabell `notify_events`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `online`
--

CREATE TABLE IF NOT EXISTS `online` (
  `uid` varchar(25) NOT NULL default '',
  `tstamp` varchar(15) NOT NULL default '',
  PRIMARY KEY  (`uid`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COMMENT='This table shows who is on line for Flash IM';

--
-- Data i tabell `online`
--

-- --------------------------------------------------------

--
-- Struktur för tabell `options`
--

CREATE TABLE IF NOT EXISTS `options` (
  `Option_ID` int(11) NOT NULL auto_increment,
  `Name` varchar(255) NOT NULL default '',
  `Value` varchar(255) NOT NULL default '',
  `Label` varchar(255) NOT NULL default '',
  `Config` mediumtext NOT NULL,
  `OptionsGroup_ID` int(11) NOT NULL default '0',
  `Type` enum('string','numeric','list','email','url','dateformat','timeformat','skin','language') NOT NULL default 'string',
  `Comments` varchar(255) NOT NULL default '',
  `Order` int(11) NOT NULL default '0',
  PRIMARY KEY  (`Option_ID`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=225 ;

--
-- Data i tabell `options`
--

INSERT INTO `options` (`Option_ID`, `Name`, `Value`, `Label`, `Config`, `OptionsGroup_ID`, `Type`, `Comments`, `Order`) VALUES
(1, '1month', '11.95', 'DB_OPTION_1MONTH_LABEL', '$t = array(\r\n"min"=>0,\r\n"max"=>99999999\r\n);\r\nreturn $t;', 1, 'numeric', 'DB_OPTION_1MONTH', 0),
(2, '3month', '29.95', 'DB_OPTION_3MONTH_LABEL', '$t = array(\r\n"min"=>0,\r\n"max"=>99999999\r\n);\r\nreturn $t;', 1, 'numeric', 'DB_OPTION_3MONTH', 0),
(3, '6month', '49.95', 'DB_OPTION_6MONTH_LABEL', '$t = array(\r\n"min"=>0,\r\n"max"=>99999999\r\n);\r\nreturn $t;', 1, 'numeric', 'DB_OPTION_6MONTH', 0),
(4, '12month', '99.95', 'DB_OPTION_12MONTH_LABEL', '$t = array(\r\n"min"=>0,\r\n"max"=>99999999\r\n);\r\nreturn $t;', 1, 'numeric', 'DB_OPTION_12MONTH', 0),
(5, 'maxpicsize', '99999999', 'DB_OPTION_MAXPICSIZE_LABEL', '$t = array(\r\n"min"=>0,\r\n"max"=>99999999\r\n);\r\nreturn $t;', 2, 'numeric', 'DB_OPTION_MAXPICSIZE', 0),
(6, 'maxvidsize', '99999999', 'DB_OPTION_MAXVIDSIZE_LABEL', '$t = array(\r\n"min"=>0,\r\n"max"=>99999999\r\n);\r\nreturn $t;', 2, 'numeric', 'DB_OPTION_MAXVIDSIZE', 0),
(7, 'maxaudsize', '99999999', 'DB_OPTION_MAXAUDSIZE_LABEL', '$t = array(\r\n"min"=>0,\r\n"max"=>99999999\r\n);\r\nreturn $t;', 2, 'numeric', 'DB_OPTION_MAXAUDSIZE', 0),
(8, 'initialreferal', '50', 'DB_OPTION_INITIALREFERAL_LABEL', '$t = array(\r\n"min"=>0,\r\n"max"=>100\r\n);\r\nreturn $t;', 3, 'numeric', 'DB_OPTION_INITIALREFERAL', 0),
(9, 'subsequentreferal', '20', 'DB_OPTION_SUBSEQUENTREFERAL_LABEL', '$t = array(\r\n"min"=>0,\r\n"max"=>100\r\n);\r\nreturn $t;', 3, 'numeric', 'DB_OPTION_SUBSEQUENTREFERAL', 0),
(10, 'freetime', '-1', 'DB_OPTION_FREETIME_LABEL', '$t = array(\r\n"min"=>-1,\r\n"max"=>365\r\n);\r\nreturn $t;', 5, 'numeric', 'DB_OPTION_FREETIME', 5),
(11, 'authorisead', '1', 'DB_OPTION_AUTHORISEAD_LABEL', '$t = array(\r\n"0"=>"Off",\r\n"1"=>"On",\r\n);\r\nreturn $t;', 5, 'list', 'DB_OPTION_AUTHORISEAD', 1),
(25, 'company', 'DatingSoftware.biz', 'DB_OPTION_COMPANY_LABEL', '', 8, 'string', 'DB_OPTION_COMPANY', 0),
(13, 'free', '1', 'DB_OPTION_FREE_LABEL', '$t = array(\r\n"0"=>"Off",\r\n"1"=>"On",\r\n);\r\nreturn $t;', 7, 'list', 'DB_OPTION_FREE', 0),
(14, 'security_registration', '1', 'DB_OPTION_SECURITY_REGISTRATION_LABEL', '$t = array(\r\n"0"=>"Off",\r\n"1"=>"On",\r\n);\r\nreturn $t;', 7, 'list', 'DB_OPTION_SECURITY_REGISTRATION', 0),
(15, 'geography_javascript', '1', 'DB_OPTION_GEOGRAPHY_JAVASCRIPT_LABEL', '$t = array(\r\n"0"=>"Off",\r\n"1"=>"On",\r\n);\r\nreturn $t;', 7, 'list', 'DB_OPTION_GEOGRAPHY_JAVASCRIPT', 0),
(16, 'zipcodes', 'Y', 'DB_OPTION_ZIPCODES_LABEL', '$t = array(\r\n"N"=>"Off",\r\n"Y"=>"On",\r\n);\r\nreturn $t;', 7, 'list', 'DB_OPTION_ZIPCODES', 0),
(17, 'thumbs', 'Y', 'DB_OPTION_THUMBS_LABEL', '$t = array(\r\n"N"=>"Off",\r\n"Y"=>"On",\r\n);\r\nreturn $t;', 7, 'list', 'DB_OPTION_THUMBS', 0),
(18, 'videos', 'Y', 'DB_OPTION_VIDEOS_LABEL', '$t = array(\r\n"N"=>"Off",\r\n"Y"=>"On",\r\n);\r\nreturn $t;', 7, 'list', 'DB_OPTION_VIDEOS', 0),
(19, 'audios', 'Y', 'DB_OPTION_AUDIOS_LABEL', '$t = array(\r\n"N"=>"Off",\r\n"Y"=>"On",\r\n);\r\nreturn $t;', 7, 'list', 'DB_OPTION_AUDIOS', 0),
(20, 'flirt', 'Y', 'DB_OPTION_FLIRT_LABEL', '$t = array(\r\n"N"=>"Off",\r\n"Y"=>"On",\r\n);\r\nreturn $t;', 7, 'list', 'DB_OPTION_FLIRT', 0),
(21, 'rating', 'Y', 'DB_OPTION_RATING_LABEL', '$t = array(\r\n"N"=>"Off",\r\n"Y"=>"On",\r\n);\r\nreturn $t;', 7, 'list', 'DB_OPTION_RATING', 0),
(22, 'spam', 'Y', 'DB_OPTION_SPAM_LABEL', '$t = array(\r\n"N"=>"Off",\r\n"Y"=>"On",\r\n);\r\nreturn $t;', 13, 'list', 'DB_OPTION_SPAM', 0),
(23, 'email_confirm', 'Y', 'DB_OPTION_EMAIL_CONFIRM_LABEL', '$t = array(\r\n"N"=>"Off",\r\n"Y"=>"On",\r\n);\r\nreturn $t;', 7, 'list', 'DB_OPTION_EMAIL_CONFIRM', 0),
(24, 'avatars_gallery', 'Y', 'DB_OPTION_AVATARS_GALLERY_LABEL', '$t = array(\r\n"N"=>"Off",\r\n"Y"=>"On",\r\n);\r\nreturn $t;', 7, 'list', 'DB_OPTION_AVATARS_GALLERY', 0),
(26, 'affmail', 'contact@datingsoftware.biz', 'DB_OPTION_AFFMAIL_LABEL', '', 8, 'email', 'DB_OPTION_AFFMAIL', 0),
(27, 'addr1', 'Some ADDR1', 'DB_OPTION_ADDR1_LABEL', '', 8, 'string', 'DB_OPTION_ADDR1', 0),
(28, 'addr2', 'Some ADDR2', 'DB_OPTION_ADDR2_LABEL', '', 8, 'string', 'DB_OPTION_ADDR2', 0),
(29, 'addr3', 'Some ADDR3', 'DB_OPTION_ADDR3_LABEL', '', 8, 'string', 'DB_OPTION_ADDR3', 0),
(30, 'addr4', 'Some ADDR4', 'DB_OPTION_ADDR4_LABEL', '', 8, 'string', 'DB_OPTION_ADDR4', 0),
(31, 'mail', 'contact@datingsoftware.biz', 'DB_OPTION_MAIL_LABEL', '', 8, 'email', 'DB_OPTION_MAIL', 0),
(32, 'suppmail', 'contact@datingsoftware.biz', 'DB_OPTION_SUPPMAIL_LABEL', '', 8, 'email', 'DB_OPTION_SUPPMAIL', 0),
(33, 'url', 'www.datingsoftware.biz', 'DB_OPTION_URL_LABEL', '', 8, 'url', 'DB_OPTION_URL', 0),
(34, 'default_language', 'EN', 'DB_OPTION_DEFAULT_LANGUAGE_LABEL', '', 5, 'language', 'DB_OPTION_DEFAULT_LANGUAGE', 10),
(35, 'currency', 'USD', 'DB_OPTION_CURRENCY_LABEL', '', 5, 'string', 'DB_OPTION_CURRENCY', 10),
(36, 'currency_symbol', '$', 'DB_OPTION_CURRENCY_SYMBOL_LABEL', '', 5, 'string', 'DB_OPTION_CURRENCY_SYMBOL', 10),
(37, 'spam_tolerange', '4', 'DB_OPTION_SPAM_TOLERANGE_LABEL', '$t = array(\r\n"min"=>0,\r\n"max"=>100\r\n);\r\nreturn $t;', 13, 'numeric', 'DB_OPTION_SPAM_TOLERANGE', 0),
(38, 'flirtmail', 'contact@datingsoftware.biz', 'DB_OPTION_FLIRTMAIL_LABEL', '', 5, 'email', 'DB_OPTION_FLIRTMAIL', 10),
(39, 'image_count', '4', 'DB_OPTION_IMAGE_COUNT_LABEL', '$t = array(\n"min"=>0,\n"max"=>20\n);\nreturn $t;', 5, 'numeric', 'DB_OPTION_IMAGE_COUNT', 10),
(40, 'format_date_short', 'm/d/Y', 'DB_OPTION_FORMAT_DATE_SHORT_LABEL', '', 5, 'dateformat', 'DB_OPTION_FORMAT_DATE_SHORT', 10),
(41, 'format_time_short', 'H:i', 'DB_OPTION_FORMAT_TIME_SHORT_LABEL', '', 5, 'timeformat', 'DB_OPTION_FORMAT_TIME_SHORT', 10),
(42, 'userplane_chat', '0', 'DB_OPTION_USERPLANE_CHAT_LABEL', '$t = array("0"=>"Off",\n"1"=>"On",\n);\nreturn $t;', 9, 'list', 'DB_OPTION_USERPLANE_CHAT', 0),
(43, 'userplane_domain', 'mydomainqq', 'DB_OPTION_USERPLANE_DOMAIN_LABEL', '', 9, 'string', 'DB_OPTION_USERPLANE_DOMAIN', 0),
(44, 'userplane_domain_full', 'mydomain.net', 'DB_OPTION_USERPLANE_DOMAIN_FULL_LABEL', '', 9, 'string', 'DB_OPTION_USERPLANE_DOMAIN_FULL', 0),
(45, 'table_width', '100%', 'DB_OPTION_TABLE_WIDTH_LABEL', '', 10, 'string', 'DB_OPTION_TABLE_WIDTH', 0),
(46, 'table_cellpadding', '0', 'DB_OPTION_TABLE_CELLPADDING_LABEL', '$t = array(\r\n"min"=>0,\r\n"max"=>2000\r\n);\r\nreturn $t;', 10, 'numeric', 'DB_OPTION_TABLE_CELLPADDING', 0),
(47, 'table_cellspacing', '0', 'DB_OPTION_TABLE_CELLSPACING_LABEL', '$t = array(\r\n"min"=>0,\r\n"max"=>2000\r\n);\r\nreturn $t;', 10, 'numeric', 'DB_OPTION_TABLE_CELLSPACING', 0),
(48, 'memimage_height', '18', 'DB_OPTION_MEMIMAGE_HEIGHT_LABEL', '', 10, 'string', 'DB_OPTION_MEMIMAGE_HEIGHT', 0),
(49, 'memimage_width', '120', 'DB_OPTION_MEMIMAGE_WIDTH_LABEL', '', 10, 'string', 'DB_OPTION_MEMIMAGE_WIDTH', 0),
(50, 'subtable_cellspacing', '0', 'DB_OPTION_SUBTABLE_CELLSPACING_LABEL', '$t = array(\r\n"min"=>0,\r\n"max"=>2000\r\n);\r\nreturn $t;', 10, 'numeric', 'DB_OPTION_SUBTABLE_CELLSPACING', 0),
(51, 'subtable_cellpadding', '5', 'DB_OPTION_SUBTABLE_CELLPADDING_LABEL', '$t = array(\r\n"min"=>0,\r\n"max"=>2000\r\n);\r\nreturn $t;', 10, 'numeric', 'DB_OPTION_SUBTABLE_CELLPADDING', 0),
(52, 'table_align', 'left', 'DB_OPTION_TABLE_ALIGN_LABEL', '', 10, 'string', 'DB_OPTION_TABLE_ALIGN', 0),
(53, 'story_image_height', '120', 'DB_OPTION_STORY_IMAGE_HEIGHT_LABEL', '$t = array(\r\n"min"=>0,\r\n"max"=>2000\r\n);\r\nreturn $t;', 10, 'numeric', 'DB_OPTION_STORY_IMAGE_HEIGHT', 0),
(54, 'story_image_width', '160', 'DB_OPTION_STORY_IMAGE_WIDTH_LABEL', '$t = array(\r\n"min"=>0,\r\n"max"=>2000\r\n);\r\nreturn $t;', 10, 'numeric', 'DB_OPTION_STORY_IMAGE_WIDTH', 0),
(55, 'story_image_weight', '10', 'DB_OPTION_STORY_IMAGE_WEIGHT_LABEL', '$t = array(\r\n"min"=>0,\r\n"max"=>2000\r\n);\r\nreturn $t;', 10, 'numeric', 'DB_OPTION_STORY_IMAGE_WEIGHT', 0),
(56, 'comment_width', '300', 'DB_OPTION_COMMENT_WIDTH_LABEL', '$t = array(\r\n"min"=>0,\r\n"max"=>2000\r\n);\r\nreturn $t;', 10, 'numeric', 'DB_OPTION_COMMENT_WIDTH', 0),
(57, 'page_size', '20', 'DB_OPTION_PAGE_SIZE_LABEL', '$t = array(\r\n"min"=>0,\r\n"max"=>2000\r\n);\r\nreturn $t;', 5, 'numeric', 'DB_OPTION_PAGE_SIZE', 10),
(58, 'authorisead_forum', '0', 'DB_OPTION_AUTHORISEAD_FORUM_LABEL', '$t = array(\r\n"0"=>"Off",\r\n"1"=>"On",\r\n);\r\nreturn $t;', 5, 'list', 'DB_OPTION_AUTHORISEAD_FORUM', 4),
(59, 'trial_gender', 'B', 'DB_OPTION_TRIAL_GENDER_LABEL', '$t = array( "M"=>"Male", "F"=>"Female","B"=>"Both" ); return $t;', 5, 'list', 'DB_OPTION_TRIAL_GENDER', 6),
(60, 'skin', 'grunge', 'DB_OPTION_SKIN_LABEL', '', 5, 'skin', 'DB_OPTION_SKIN', 8),
(62, 'snetwork', '0', 'DB_OPTION_SNETWORK_LABEL', '$t = array(\r\n"0"=>"Off",\r\n"1"=>"On",\r\n);\r\nreturn $t;', 12, 'list', 'DB_OPTION_SNETWORK', 0),
(63, 'snetwork_depth', '6', 'DB_OPTION_SNETWORK_DEPTH_LABEL', '$t = array(\r\n"min"=>0,\r\n"max"=>20\r\n);\r\nreturn $t;', 12, 'numeric', 'DB_OPTION_SNETWORK_DEPTH', 0),
(64, 'authorisead_blog', '0', 'DB_OPTION_AUTHORISEAD_BLOG_LABEL', '$t = array( "0"=>"Off", "1"=>"On", ); return $t;', 5, 'list', 'DB_OPTION_AUTHORISEAD_BLOG', 3),
(65, 'userplane_im', '0', 'DB_OPTION_USERPLANE_IM_LABEL', '$t = array(\n"0"=>"Off",\n"1"=>"On",\n);\nreturn $t;', 9, 'list', 'DB_OPTION_USERPLANE_IM', 1),
(67, 'spam_limit', '4', 'DB_OPTION_SPAM_LIMIT_LABEL', '$t = array(\r\n"min"=>0,\r\n"max"=>99999999\r\n);\r\nreturn $t;', 13, 'string', 'DB_OPTION_SPAM_LIMIT', 1),
(68, 'groups_autoapprove', '0', 'DB_OPTION_GROUPS_AUTOAPPROVE_LABEL', '$t = array( "0"=>"Off", "1"=>"On" ); return $t;', 14, 'list', 'DB_OPTION_GROUPS_AUTOAPPROVE', 1),
(69, 'groups_image_width', '80', 'DB_OPTION_GROUPS_IMAGE_WIDTH_LABEL', '$t = array(\r\n"min"=>0,\r\n"max"=>2000\r\n);\r\nreturn $t;', 14, 'numeric', 'DB_OPTION_GROUPS_IMAGE_WIDTH', 2),
(70, 'groups_image_height', '80', 'DB_OPTION_GROUPS_IMAGE_HEIGHT_LABEL', '$t = array(\r\n"min"=>0,\r\n"max"=>2000\r\n);\r\nreturn $t;', 14, 'numeric', 'DB_OPTION_GROUPS_IMAGE_HEIGHT', 3),
(71, 'groups_manager', 'manager', 'DB_OPTION_GROUPS_MANAGER_LABEL', '$t = array(\r\n"min"=>0,\r\n"max"=>99999999\r\n);\r\nreturn $t;', 14, 'string', 'DB_OPTION_GROUPS_MANAGER', 4),
(76, 'skype', '1', 'DB_OPTION_SKYPE_LABEL', '$t = array( "0"=>"Off", "1"=>"On", ); return $t;', 15, 'list', 'DB_OPTION_SKYPE', 1),
(77, 'skype_premium', '0', 'DB_OPTION_SKYPE_PREMIUM_LABEL', '$t = array( "0"=>"All Users", "1"=>"Premium Users Only", ); return $t;', 15, 'list', 'DB_OPTION_SKYPE_PREMIUM', 2),
(78, 'blogs', '1', 'DB_OPTION_BLOGS_LABEL', '$t = array( "0"=>"Off", "1"=>"On", ); return $t;', 7, 'list', 'DB_OPTION_BLOGS', 0),
(79, 'groups', '1', 'DB_OPTION_GROUPS_LABEL', '$t = array( "0"=>"Off", "1"=>"On", ); return $t;', 7, 'list', 'DB_OPTION_GROUPS', 0),
(80, 'forums', '1', 'DB_OPTION_FORUMS_LABEL', '$t = array( "0"=>"Off", "1"=>"On", ); return $t;', 7, 'list', 'DB_OPTION_FORUMS', 0),
(81, 'geography_ajax', '1', 'DB_OPTION_GEOGRAPHY_AJAX_LABEL', '$t = array( "0"=>"Off", "1"=>"On", ); return $t;', 7, 'list', 'DB_OPTION_GEOGRAPHY_AJAX', 0),
(200, 'userplane_im_free', '1', 'DB_OPTION_USERPLANE_IM_LABEL', '$t = array(\r\n"0"=>"Off",\r\n"1"=>"On",\r\n);\r\nreturn $t;', 11, 'list', 'DB_OPTION_USERPLANE_IM', 1),
(201, 'userplane_domain_free', 'ipash', 'DB_OPTION_USERPLANE_DOMAIN_LABEL', '', 11, 'string', 'DB_OPTION_USERPLANE_DOMAIN', 2),
(202, 'userplane_domain_full_free', 'platinum.datingsoftware.biz', 'DB_OPTION_USERPLANE_DOMAIN_FULL_LABEL', '', 11, 'string', 'DB_OPTION_USERPLANE_DOMAIN_FULL', 3),
(203, 'userplane_leader_board_id', '4078', 'DB_OPTION_USERPLANE_LEADER_BOARD_ID_LABEL', '', 11, 'string', 'DB_OPTION_USERPLANE_LEADER_BOARD_ID', 3),
(204, 'userplane_text_zone_id', '193', 'DB_OPTION_USERPLANE_TEXT_ZONE_ID_LABEL', '', 11, 'string', 'DB_OPTION_USERPLANE_TEXT_ZONE_ID', 3),
(205, 'userplane_chat_free', '1', 'DB_OPTION_USERPLANE_CHAT_LABEL', '$t = array(\r\n"0"=>"Off",\r\n"1"=>"On",\r\n);\r\nreturn $t;', 11, 'list', 'DB_OPTION_USERPLANE_CHAT', 1),
(206, 'userplane_full_banner_id', '4079', 'DB_OPTION_USERPLANE_FULL_BANNER_ID_LABEL', '', 11, 'string', 'DB_OPTION_USERPLANE_FULL_BANNER_ID', 3),
(210, 'userplane_presence', '0', 'DB_OPTION_USERPLANE_PRESENCE_LABEL', '$t = array(\r\n"0"=>"Off",\r\n"1"=>"On",\r\n);\r\nreturn $t;', 9, 'list', 'DB_OPTION_USERPLANE_PRESENCE', 1),
(211, 'userplane_presence_id', 'mydomainqq', 'DB_OPTION_USERPLANE_PRESENCE_ID_LABEL', '', 9, 'string', 'DB_OPTION_USERPLANE_PRESENCE_ID', 2),
(212, 'userplane_presence_password', 'mydomain.net', 'DB_OPTION_USERPLANE_PRESENCE_PASSWORD_LABEL', '', 9, 'string', 'DB_OPTION_USERPLANE_PRESENCE_PASSWORD', 3),
(213, 'js_notify', '1', 'DB_OPTION_JS_NOTIFY_LABEL', '$t = array(\r\n"0"=>"Off",\r\n"1"=>"On",\r\n);\r\nreturn $t;', 7, 'list', 'DB_OPTION_JS_NOTIFY', 0),
(214, 'video_conversion', '1', 'DB_OPTION_VIDEO_CONVERSION_LABEL', '$t = array(\r\n"0"=>"Off",\r\n"1"=>"On",\r\n);\r\nreturn $t;', 7, 'list', 'DB_OPTION_VIDEO_CONVERSION', 0),
(224, 'sms', 'Y', 'DB_OPTION_SMS_LABEL', '$t = array(\r\n"N"=>"Off",\r\n"Y"=>"On",\r\n);\r\nreturn $t;', 7, 'list', 'DB_OPTION_SMS', 0),
(226, 'authorisepic', '1', 'DB_OPTION_AUTHORISEPIC_LABEL', '$t = array(\r\n"0"=>"Off",\r\n"1"=>"On",\r\n);\r\nreturn $t;', 5, 'list', 'DB_OPTION_AUTHORISEPIC', 2);

-- --------------------------------------------------------

--
-- Struktur för tabell `optionsgroup`
--

CREATE TABLE IF NOT EXISTS `optionsgroup` (
  `OptionsGroup_ID` int(11) NOT NULL auto_increment,
  `Name` varchar(255) NOT NULL default '',
  `Title` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`OptionsGroup_ID`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Data i tabell `optionsgroup`
--

INSERT INTO `optionsgroup` (`OptionsGroup_ID`, `Name`, `Title`) VALUES
(1, 'payment', 'DB_OPTGROUP_PAYMENT'),
(2, 'file', 'DB_OPTGROUP_FILE'),
(3, 'affiliates', 'DB_OPTGROUP_AFFILIATES'),
(7, 'modules', 'DB_OPTGROUP_MODULES'),
(5, 'general', 'DB_OPTGROUP_GENERAL'),
(8, 'contact', 'DB_OPTGROUP_CONTACT'),
(9, 'userplane', 'DB_OPTGROUP_USERPLANE'),
(10, 'layout', 'DB_OPTGROUP_LAYOUT'),
(11, 'userplane_free_chat', 'DB_OPTGROUP_USERPLANE_CHAT'),
(12, 'social_network', 'DB_OPTGROUP_SNETWORK'),
(13, 'spam', 'DB_OPTGROUP_SPAM'),
(14, 'groups', 'DB_OPTGROUP_GROUPS'),
(15, 'skype', 'DB_OPTGROUP_SKYPE');

-- --------------------------------------------------------

--
-- Struktur för tabell `pagetemplate`
--

CREATE TABLE IF NOT EXISTS `pagetemplate` (
  `PageTemplate_ID` int(11) NOT NULL auto_increment,
  `Name` varchar(50) NOT NULL default '',
  `Description` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`PageTemplate_ID`),
  UNIQUE KEY `Name` (`Name`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Data i tabell `pagetemplate`
--

INSERT INTO `pagetemplate` (`PageTemplate_ID`, `Name`, `Description`) VALUES
(1, 'aff_disclaimer_text', 'DB_PAGETEMPLATE_AFF_DISCLAIMER_TEXT'),
(3, 'aff_privacy_text', 'DB_PAGETEMPLATE_AFF_PRIVACY_TEXT'),
(4, 'help_advert2_text', 'DB_PAGETEMPLATE_HELP_ADVERT2_TEXT'),
(5, 'affiliates_text', 'DB_PAGETEMPLATE_AFFILIATES_TEXT'),
(6, 'help_text', 'DB_PAGETEMPLATE_HELP_TEXT'),
(7, 'scheme_text', 'DB_PAGETEMPLATE_SCHEME_TEXT'),
(8, 'about_content', 'DB_PAGETEMPLATE_ABOUT_CONTENT');

-- --------------------------------------------------------

--
-- Struktur för tabell `params`
--

CREATE TABLE IF NOT EXISTS `params` (
  `par_authorisead` int(11) NOT NULL default '0',
  `par_lastcupidrun` datetime default NULL,
  `par_1month` decimal(9,2) default NULL,
  `par_3month` decimal(9,2) default NULL,
  `par_6month` decimal(9,2) default NULL,
  `par_12month` decimal(9,2) default NULL,
  `par_rootpath` varchar(200) default NULL,
  `par_company` varchar(50) default NULL,
  `par_maxpicsize` int(11) NOT NULL default '50000',
  `par_maxvidsize` int(11) NOT NULL default '1000000',
  `par_maxaudsize` int(11) NOT NULL default '500000',
  `par_initialreferal` int(11) NOT NULL default '0',
  `par_subsequentreferal` int(11) NOT NULL default '0',
  `par_freetime` int(2) NOT NULL default '-1',
  `par_lastrun` int(11) NOT NULL default '0'
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `params`
--

INSERT INTO `params` (`par_authorisead`, `par_lastcupidrun`, `par_1month`, `par_3month`, `par_6month`, `par_12month`, `par_rootpath`, `par_company`, `par_maxpicsize`, `par_maxvidsize`, `par_maxaudsize`, `par_initialreferal`, `par_subsequentreferal`, `par_freetime`, `par_lastrun`) VALUES
(0, '2008-11-23 17:31:42', 10.00, 50.00, 80.00, 120.00, '', '', 500000, 1000000, 5000000, 30, 10, 2, 10);

-- --------------------------------------------------------

--
-- Struktur för tabell `payments`
--

CREATE TABLE IF NOT EXISTS `payments` (
  `pay_paymentid` int(11) NOT NULL auto_increment,
  `pay_transid` varchar(50) NOT NULL default '0',
  `pay_transstatus` varchar(50) NOT NULL default '',
  `pay_transtime` varchar(55) default '0',
  `pay_message` text,
  `pay_userid` int(11) NOT NULL default '0',
  `pay_date` datetime default NULL,
  `pay_name` varchar(40) default NULL,
  `pay_address` text,
  `pay_postcode` varchar(12) default NULL,
  `pay_country` char(2) default NULL,
  `pay_telephone` varchar(30) default NULL,
  `pay_email` varchar(80) default NULL,
  `pay_scountry` varchar(80) default NULL,
  `pay_samount` varchar(20) default NULL,
  `pay_service` enum('premium','sd_ticket') NOT NULL default 'premium',
  `pay_params` mediumtext NOT NULL,
  `pay_system` varchar(255) NOT NULL default '',
  `pay_username` varchar(25) default NULL,
  `pay_notify_log` mediumtext NOT NULL,
  PRIMARY KEY  (`pay_paymentid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=235 ;

--
-- Data i tabell `payments`
--

-- --------------------------------------------------------

--
-- Struktur för tabell `payment_params`
--

CREATE TABLE IF NOT EXISTS `payment_params` (
  `psp_id` int(11) NOT NULL auto_increment,
  `psp_name` varchar(255) default NULL,
  `psp_value` varchar(255) default NULL,
  `ps_id` int(11) NOT NULL default '0',
  `pay_service` enum('premium','sd_ticket') NOT NULL default 'premium',
  `psp_type` enum('onetime','recurring','common') NOT NULL default 'common',
  `psp_description` mediumtext,
  PRIMARY KEY  (`psp_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=86 ;

--
-- Data i tabell `payment_params`
--

INSERT INTO `payment_params` (`psp_id`, `psp_name`, `psp_value`, `ps_id`, `pay_service`, `psp_type`, `psp_description`) VALUES
(1, 'sid', '', 2, 'premium', 'common', 'Vendor Number'),
(2, 'business', 'tes2t@test.com', 1, 'premium', 'common', 'PayPal account'),
(27, 'allowedTypes', '', 3, 'premium', 'recurring', 'allowedTypes ; price'),
(4, 'clientAccnum', '', 3, 'premium', 'common', 'CCBill account'),
(5, 'clientSubacc', '', 3, 'premium', 'common', 'CCBill subaccount'),
(6, 'formName', '', 3, 'premium', 'common', 'clolors schema for signup form'),
(7, 'language', 'English', 3, 'premium', 'common', 'Lang 12'),
(8, 'allowedTypes', '', 3, 'premium', 'onetime', 'allowedTypes ; price'),
(14, 'allowedTypes', '', 3, 'premium', 'onetime', 'allowedTypes ; price'),
(10, 'ACCOUNT_1', '', 4, 'premium', 'onetime', 'Enter account number and price separated by semicolon'),
(11, 'ACCOUNT_2', '', 4, 'premium', 'onetime', 'Enter account number and price separated by semicolon'),
(12, 'ACCOUNT_3', '', 4, 'premium', 'onetime', 'Enter account number and price separated by semicolon'),
(13, 'ACCOUNT_4', '', 4, 'premium', 'onetime', 'Enter account number and price separated by semicolon'),
(15, 'sid', '', 2, 'sd_ticket', 'common', 'Vendor Number'),
(16, 'business', '', 1, 'sd_ticket', 'common', 'PayPal account'),
(26, 'allowedTypes', '', 3, 'premium', 'recurring', 'allowedTypes ; price'),
(18, 'allowedTypes', '', 3, 'premium', 'onetime', 'allowedTypes ; price'),
(19, 'allowedTypes', '', 3, 'premium', 'onetime', 'allowedTypes ; price'),
(20, 'storename', '', 5, 'premium', 'common', 'Your linkpoint store name'),
(21, 'storename', '', 5, 'sd_ticket', 'common', 'Your linkpoint store name'),
(22, 'currency_code', 'USD', 1, 'premium', 'common', 'Currency code'),
(23, 'currency_code', 'GBP', 1, 'sd_ticket', 'common', 'Currency code'),
(25, 'secret', '', 2, 'premium', 'common', 'Secret word'),
(28, 'allowedTypes', '', 3, 'premium', 'recurring', 'allowedTypes ; price'),
(29, 'allowedTypes', '', 3, 'premium', 'recurring', 'allowedTypes ; price'),
(41, 'currency', 'USD', 6, 'premium', 'common', 'Currency code'),
(30, 'ACCOUNT_5', '', 4, 'premium', 'recurring', 'Enter account number and price separated by semicolon'),
(31, 'ACCOUNT_6', '', 4, 'premium', 'recurring', 'Enter account number and price separated by semicolon'),
(32, 'ACCOUNT_7', '', 4, 'premium', 'recurring', 'Enter account number and price separated by semicolon '),
(33, 'ACCOUNT_8', '', 4, 'premium', 'recurring', 'Enter account number and price separated by semicolon '),
(35, 'demo', '', 2, 'premium', 'common', 'Demo mode'),
(40, 'instId', '', 6, 'premium', 'common', 'Account ID'),
(36, 'product_id_1', '', 2, 'premium', 'recurring', 'product_id and price separated by semicolon'),
(37, 'product_id_2', '', 2, 'premium', 'recurring', 'product_id and price separated by semicolon'),
(38, 'product_id_3', '', 2, 'premium', 'recurring', 'product_id and price separated by semicolon'),
(39, 'product_id_4', '', 2, 'premium', 'recurring', 'product_id and price separated by semicolon'),
(42, 'testMode', '100', 6, 'premium', 'common', 'Test mode code'),
(43, 'instId', '', 6, 'sd_ticket', 'onetime', 'Account ID'),
(44, 'currency', 'USD', 6, 'sd_ticket', 'onetime', 'Currency code'),
(45, 'testMode', '100', 6, 'sd_ticket', 'onetime', 'Test mode code'),
(46, 'configfile', '', 7, 'premium', 'common', 'Your linkpoint store name'),
(47, 'configfile', '', 7, 'sd_ticket', 'common', 'Your linkpoint store name'),
(48, 'keyfile', '/path to sertificate/sertificate.pem', 7, 'premium', 'common', NULL),
(49, 'keyfile', '/path to sertificate/sertificate.pem', 7, 'sd_ticket', 'common', NULL),
(50, 'result', 'GOOD', 7, 'premium', 'common', 'GOOD - test mode LIVE - real transaction'),
(51, 'result', 'GOOD', 7, 'sd_ticket', 'common', 'GOOD - test mode LIVE - real transaction'),
(56, 'LOGIN', '', 8, 'premium', 'common', 'VeriSign login'),
(57, 'LOGIN', '', 8, 'sd_ticket', 'common', 'Verisign login'),
(58, 'x_login', 'login', 9, 'premium', 'common', 'login'),
(59, 'x_tran_key', '0000', 9, 'premium', 'common', 'Transaction Key'),
(60, 'x_test_request', 'TRUE', 9, 'premium', 'common', 'Test Request'),
(78, 'Username', '', 10, 'premium', 'onetime', 'PayFlow username'),
(79, 'Password', '', 10, 'premium', 'onetime', 'PayFlow password'),
(80, 'Partner', 'VeriSign', 10, 'premium', 'onetime', 'PayFlow Partner name'),
(81, 'VendorName', '', 10, 'premium', 'onetime', 'PayFlow Vendor name'),
(82, 'Username', '', 10, 'sd_ticket', 'onetime', 'PayFlow username'),
(83, 'Password', '', 10, 'sd_ticket', 'onetime', 'PayFlow password'),
(84, 'Partner', 'VeriSign', 10, 'sd_ticket', 'onetime', 'PayFlow Partner name'),
(85, 'VendorName', '', 10, 'sd_ticket', 'onetime', 'PayFlow Vendor name'),
(86, 'pi_code', '', 11, 'premium', 'common', 'Enter product id and price separated by semicolon'),
(87, 'pi_code', '', 11, 'premium', 'common', 'Enter product id and price separated by semicolon'),
(88, 'pi_code', '', 11, 'premium', 'common', 'Enter product id and price separated by semicolon'),
(89, 'pi_code', '', 11, 'premium', 'common', 'Enter product id and price separated by semicolon'),
(90, 'co_code', '', 11, 'premium', 'common', 'Company code'),
(91, 'merchant_id', '', 12, 'premium', 'common', 'The merchant id required to identify your account. Your default\r\nmerchant id is the email address you use with your Nochex\r\naccount.'),
(92, 'test_transaction', '0', 12, 'premium', 'common', 'Setting this parameter to 100 sets the Payments page into a\r\ntest mode.'),
(93, 'key', '', 13, 'premium', 'common', 'Source Key generated by the Merchant Console at www.usaepay.com.'),
(94, 'testmode', '1', 13, 'premium', 'common', 'Use testmode. If set to <b>1</b> then the transaction will be simulated but not actually processed. (optional, defaults to <b>0</b>)'),
(95, 'merchantID', '', 14, 'premium', 'onetime', 'Google merchant ID'),
(96, 'merchantID', '', 14, 'sd_ticket', 'onetime', 'Google merchant ID'),
(97, 'merchant_key', '', 14, 'premium', 'onetime', 'Google merchant key'),
(98, 'merchant_key', '', 14, 'sd_ticket', 'onetime', 'Google merchant key'),
(99, 'sandbox_test', '1', 14, 'premium', 'onetime', 'Set to 1 for test mode. Set to 0 for production mode.'),
(109, 'sandbox_test', '1', 14, 'sd_ticket', 'onetime', 'Set to 1 for test mode. Set to 0 for production mode.'),
(100, 'GatewayID', '', 15, 'premium', 'common', 'Your assigned InternetSecure Gateway Gateway ID, provided to you in your "Welcome" email'),
(101, 'GatewayID', '', 15, 'sd_ticket', 'common', 'Your assigned InternetSecure Gateway Gateway ID, provided to you in your "Welcome" email'),
(102, 'language', 'EN', 15, 'premium', 'common', 'English (or EN), French (or FR), Spanish (SP), Portuguese (PT) or Japanese (JP)'),
(103, 'language', 'EN', 15, 'sd_ticket', 'common', 'English (or EN), French (or FR), Spanish (SP), Portuguese (PT) or Japanese (JP)'),
(104, 'merchant', '', 22, 'premium', 'onetime', 'Shop identification. The Merchant number appears in the e-mail received from DIBS during registration with DIBS.'),
(105, 'test', 'yes', 22, 'premium', 'onetime', 'This field is used when tests are being conducted on the shop (eg. test=yes). When this field is declared the transaction is not dispatched to the card issuer, but is instead handled by the DIBS test module. Should the test system be used at a later date, this will be activated at DIBS (contact support@dibs.dkThis email address is being protected from spam bots, you need Javascript enabled to view it for reactivating the test mode of your shop)'),
(106, 'currency', '208', 22, 'premium', 'onetime', 'Currency specification as indicated in ISO4217 where the Danish crown is no. 208. Also see our list of currencies . Make sure that your acquirer agreement supports all the currencies that you want to use.'),
(107, 'k1', '', 22, 'premium', 'onetime', NULL),
(108, 'k2', '', 22, 'premium', 'onetime', NULL),
(111, 'PAYGATE_ID', '10011013800', 16, 'premium', 'onetime', 'It is unique to each PayGate client and ensures that all payments\r\nreceived are credited directly to the merchants bank account. To test your system, use the PayGate ID:\r\n10011013800.'),
(110, 'KEY', 'secret', 16, 'premium', 'onetime', 'Secret word using for creating security hash "CHECKSUM". The\r\nKEY is only known by the merchant and PayGate (via the PayGate BackOffice) and should not be displayed on\r\nthe merchant’s web site.'),
(120, 'pi', '830467', 17, 'premium', 'onetime', 'Merchants Paystation ID that the\r\npayments will be made against'),
(121, 'tm', 'T', 17, 'premium', 'onetime', 'Single character ‘t’ or ‘T’<br>\r\nTest Mode [optional] - sets the\r\nPaystation server into Test Mode (for the\r\nsingle transaction only). It uses the\r\nmerchants TEST account on the VPS\r\nserver, and marks the transaction as a\r\nTest in the Paystation server. This allows\r\nthe merchant to run test transactions\r\nwithout incurring any costs or running\r\nlive card transactions.');

INSERT INTO `payment_params` VALUES (124, 'api_username', 'sdk-seller_api1.sdk.com', 18, 'premium', 'common', 'A PayPal-generated identifying account name and password that you use specifically for making\r\nAPI calls. You include your API username and password with every API call. The API username\r\nand password are different from your PayPal login username (email address) and password.');
INSERT INTO `payment_params` VALUES (125, 'api_password', '12345678', 18, 'premium', 'common', 'A PayPal-generated identifying account name and password that you use specifically for making\r\nAPI calls. You include your API username and password with every API call. The API username\r\nand password are different from your PayPal login username (email address) and password.');
INSERT INTO `payment_params` VALUES (126, 'signature', NULL, 18, 'premium', 'common', 'Your API signature, if you use one instead of an API\r\nCertificate.');
INSERT INTO `payment_params` VALUES (127, 'encrypted_api_certificate', 'sdk-seller_cert_key_pem.txt', 18, 'premium', 'common', 'If you are using API Certificates, The path to the API certificate in PEM format as\r\ndownloaded from PayPal');
INSERT INTO `payment_params` VALUES (128, 'environment', 'sandbox', 18, 'premium', 'common', 'The PayPal environment for processing API calls: live, sandbox or beta-sandbox.\r\nDepending on the feature you want to develop and\r\ntest(sandbox), you need a Personal, Business, or Premier\r\naccount(live).');
INSERT INTO `payment_params` VALUES (129, 'api_username', 'sdk-seller_api1.sdk.com', 18, 'sd_ticket', 'common', 'A PayPal-generated identifying account name and password that you use specifically for making\r\nAPI calls. You include your API username and password with every API call. The API username\r\nand password are different from your PayPal login username (email address) and password.');
INSERT INTO `payment_params` VALUES (130, 'api_password', '12345678', 18, 'sd_ticket', 'common', 'A PayPal-generated identifying account name and password that you use specifically for making\r\nAPI calls. You include your API username and password with every API call. The API username\r\nand password are different from your PayPal login username (email address) and password.');
INSERT INTO `payment_params` VALUES (131, 'signature', NULL, 18, 'sd_ticket', 'common', 'Your API signature, if you use one instead of an API\r\nCertificate.');
INSERT INTO `payment_params` VALUES (132, 'encrypted_api_certificate', 'sdk-seller_cert_key_pem.txt', 18, 'sd_ticket', 'common', 'If you are using API Certificates, The path to the API certificate in PEM format as\r\ndownloaded from PayPal');
INSERT INTO `payment_params` VALUES (133, 'environment', 'sandbox', 18, 'sd_ticket', 'common', 'The PayPal environment for processing API calls: live, sandbox or beta-sandbox.\r\nDepending on the feature you want to develop and\r\ntest(sandbox), you need a Personal, Business, or Premier\r\naccount(live).');
INSERT INTO `payment_params` VALUES (134, 'currency', 'USD', 18, 'premium', 'common', NULL);
INSERT INTO `payment_params` VALUES (135, 'currency', 'USD', 18, 'sd_ticket', 'common', NULL);
INSERT INTO `payment_params` VALUES (136, 'country', 'US', 18, 'premium', 'common', NULL);
INSERT INTO `payment_params` VALUES (137, 'country', 'US', 18, 'sd_ticket', 'common', NULL);

INSERT INTO `payment_params` VALUES (143, 'x_login', 'knoxville32', 23, 'premium', 'common', 'login');
INSERT INTO `payment_params` VALUES (144, 'x_tran_key', '', 23, 'premium', 'common', 'Transaction Key');
INSERT INTO `payment_params` VALUES (145, 'x_test_request', 'TRUE', 23, 'premium', 'common', 'Test Request');
INSERT INTO `payment_params` VALUES (146, 'x_login', '', 23, 'sd_ticket', 'common', 'login');
INSERT INTO `payment_params` VALUES (147, 'x_tran_key', 'A0ioIL6uXPs8q5cE', 23, 'sd_ticket', 'common', 'Transaction Key');
INSERT INTO `payment_params` VALUES (148, 'x_test_request', 'TRUE', 23, 'sd_ticket', 'common', 'Test Request');
-- --------------------------------------------------------

--
-- Struktur för tabell `payment_services`
--

CREATE TABLE IF NOT EXISTS `payment_services` (
  `ps_id` int(11) NOT NULL default '0',
  `pay_service` enum('premium','sd_ticket') NOT NULL default 'premium',
  PRIMARY KEY  (`ps_id`,`pay_service`)
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `payment_services`
--

INSERT INTO `payment_services` (`ps_id`, `pay_service`) VALUES
(1, 'premium'),
(1, 'sd_ticket'),
(2, 'premium'),
(2, 'sd_ticket'),
(3, 'premium'),
(4, 'premium'),
(5, 'premium'),
(5, 'sd_ticket'),
(6, 'premium'),
(6, 'sd_ticket'),
(7, 'premium'),
(7, 'sd_ticket'),
(8, 'premium'),
(8, 'sd_ticket'),
(9, 'premium'),
(10, 'premium'),
(10, 'sd_ticket'),
(11, 'premium'),
(11, 'sd_ticket'),
(12, 'premium'),
(12, 'sd_ticket'),
(13, 'premium'),
(13, 'sd_ticket'),
(14, 'premium'),
(14, 'sd_ticket'),
(15, 'premium'),
(15, 'sd_ticket'),
(16, 'premium'),
(16, 'sd_ticket'),
(17, 'premium'),
(17, 'sd_ticket'),
(18, 'premium'),
(18, 'sd_ticket'),
(19, 'premium'),
(19, 'sd_ticket'),
(22, 'premium'),
(22, 'sd_ticket'),
(23, 'premium'),
(23, 'sd_ticket');

-- --------------------------------------------------------

--
-- Struktur för tabell `payment_service_params`
--

CREATE TABLE IF NOT EXISTS `payment_service_params` (
  `psp_service` enum('premium','sd_ticket') NOT NULL default 'premium',
  `psp_title` varchar(250) NOT NULL default '',
  `psp_type` enum('onetime','recurring') NOT NULL default 'onetime',
  PRIMARY KEY  (`psp_service`)
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `payment_service_params`
--

INSERT INTO `payment_service_params` (`psp_service`, `psp_title`, `psp_type`) VALUES
('premium', 'Dating Premium Membership', 'onetime'),
('sd_ticket', 'SpeedDating Tickets', 'onetime');

-- --------------------------------------------------------

--
-- Struktur för tabell `payment_systems`
--

CREATE TABLE IF NOT EXISTS `payment_systems` (
  `ps_id` int(11) NOT NULL auto_increment,
  `ps_prefix` varchar(40) NOT NULL default '',
  `ps_title` varchar(250) NOT NULL default '',
  `ps_max_amount` int(11) NOT NULL default '1000000',
  `ps_active` enum('yes','no') NOT NULL default 'yes',
  `ps_onetime` enum('yes','no') NOT NULL default 'yes',
  `ps_recurring` enum('yes','no') NOT NULL default 'yes',
  PRIMARY KEY  (`ps_id`),
  UNIQUE KEY `prefix` (`ps_prefix`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Data i tabell `payment_systems`
--

INSERT INTO `payment_systems` (`ps_id`, `ps_prefix`, `ps_title`, `ps_max_amount`, `ps_active`, `ps_onetime`, `ps_recurring`) VALUES
(1, 'paypal', 'PayPal', 1000, 'yes', 'yes', 'yes'),
(2, '2checkoutV2', '2CheckOut', 150, 'no', 'yes', 'yes'),
(3, 'ccbill', 'CCBill', 100, 'no', 'yes', 'yes'),
(4, 'ibill', 'iBill', 1000000, 'no', 'yes', 'no'),
(6, 'worldpay', 'Worldpay', 1000000, 'no', 'yes', 'yes'),
(9, 'authorizenet_aim', 'Authorize.Net', 1000000, 'no', 'yes', 'yes'),
(11, 'epoch', 'Epoch', 1000000, 'yes', 'yes', 'yes'),
(12, 'nochex', 'Nochex Secure Online Payment', 1000000, 'yes', 'yes', 'no'),
(13, 'usaepay', 'USA ePay', 1000000, 'yes', 'yes', 'no'),
(14, 'google_checkout', 'Google Checkout', 1000000, 'yes', 'yes', 'no'),
(15, 'internetsecure', 'InternetSecure', 1000000, 'yes', 'yes', 'yes'),
(22, 'dibs', 'DIBS Payment System', 1000000, 'yes', 'yes', 'no'),
(17, 'paygate', 'PayGate', 1000000, 'no', 'yes', 'yes'),
(19, 'flex', 'Verotel', 1000000, 'no', 'yes', 'no');

INSERT INTO `payment_systems` VALUES (18, 'paypal_pro', 'Paypal Pro Payment System', 10000, 'yes', 'yes', 'yes');
INSERT INTO `payment_systems` VALUES (23, 'authorizenet_sim', 'Authorize.Net Sim', 1000000, 'no', 'yes', 'yes');

-- --------------------------------------------------------

--
-- Struktur för tabell `pictures`
--

CREATE TABLE IF NOT EXISTS `pictures` (
  `pic_id` int(11) NOT NULL auto_increment,
  `pic_userid` int(11) NOT NULL default '0',
  `pic_picture` varchar(75) NOT NULL default '',
  `pic_private` enum('Y','N') NOT NULL default 'N',
  `pic_default` enum('Y','N') NOT NULL default 'N',
  `pic_approved` int(1) NOT NULL default '0',
  PRIMARY KEY  (`pic_id`),
  KEY `pic_userid` (`pic_userid`,`pic_default`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=180 ;


--
-- Data i tabell `pictures`
--

-- --------------------------------------------------------

--
-- Struktur för tabell `premium_func`
--

CREATE TABLE IF NOT EXISTS `premium_func` (
  `prf_id` int(11) NOT NULL auto_increment,
  `prf_name` varchar(255) NOT NULL default '',
  `prf_uri` varchar(255) NOT NULL default '',
  `prf_app` int(11) NOT NULL default '0',
  `prf_active` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`prf_id`),
  KEY `URI` (`prf_uri`(10))
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Dumping data for table `premium_func`
--

INSERT INTO `premium_func` (`prf_id`, `prf_name`, `prf_uri`, `prf_app`, `prf_active`) VALUES
(3, 'Advanced Search', '/search.php', 0, 0),
(5, 'View Search Results', '/prgsearch.php', 0, 0),
(6, 'Access Mailbox', '/myemail.php', 0, 0),
(7, 'View Received Messages', '/prgshowmail.php', 0, 0),
(8, 'Reply to Messages', '/replymail.php', 0, 0),
(9, 'Send Messages', '/sendmail.php', 0, 0),
(10, 'Access Hotlist', '/prghotlist.php', 0, 0),
(11, 'Userplane IM', '/userplane/wm.php', 0, 0),
(12, 'Userplane Chat', '/userplane/chat/ch.php', 0, 0);

-- --------------------------------------------------------

--
-- Struktur för tabell `profiles`
--

CREATE TABLE IF NOT EXISTS `profiles` (
  `pro_recno` int(11) NOT NULL auto_increment,
  `pro_userid` int(11) NOT NULL default '0',
  `pro_person1` varchar(50) default NULL,
  `pro_person2` varchar(50) default NULL,
  `pro_person3` varchar(50) default NULL,
  `pro_philos1` varchar(50) default NULL,
  `pro_philos2` varchar(50) default NULL,
  `pro_philos3` varchar(50) default NULL,
  `pro_social1` varchar(50) default NULL,
  `pro_social2` varchar(50) default NULL,
  `pro_social3` varchar(50) default NULL,
  `pro_goal1` varchar(50) default NULL,
  `pro_goal2` varchar(50) default NULL,
  `pro_goal3` varchar(50) default NULL,
  `pro_hobby1` varchar(50) default NULL,
  `pro_hobby2` varchar(50) default NULL,
  `pro_hobby3` varchar(50) default NULL,
  `pro_sport1` varchar(50) default NULL,
  `pro_sport2` varchar(50) default NULL,
  `pro_sport3` varchar(50) default NULL,
  `pro_music1` varchar(50) default NULL,
  `pro_music2` varchar(50) default NULL,
  `pro_music3` varchar(50) default NULL,
  `pro_food1` varchar(50) default NULL,
  `pro_food2` varchar(50) default NULL,
  `pro_food3` varchar(50) default NULL,
  PRIMARY KEY  (`pro_recno`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3227 ;

--
-- Data i tabell `profiles`
--

-- --------------------------------------------------------

--
-- Struktur för tabell `ratedby`
--

CREATE TABLE IF NOT EXISTS `ratedby` (
  `rtb_userid` int(11) unsigned NOT NULL default '0',
  `rtb_raterid` int(11) unsigned NOT NULL default '0',
  `rtb_vote` decimal(3,1) unsigned NOT NULL default '0.0',
  PRIMARY KEY  (`rtb_userid`,`rtb_raterid`)
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `ratedby`
--

-- --------------------------------------------------------

--
-- Struktur för tabell `ratings`
--

CREATE TABLE IF NOT EXISTS `ratings` (
  `rte_userid` int(11) NOT NULL default '0',
  `rte_votes` int(11) default '0',
  `rte_value` int(11) default '0',
  `rte_average` decimal(3,1) unsigned default '0.0',
  PRIMARY KEY  (`rte_userid`)
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `ratings`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `rating_video`
--

CREATE TABLE IF NOT EXISTS `rating_video` (
  `id` int(11) NOT NULL default '0',
  `userid` int(11) NOT NULL default '0',
  `value` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`,`userid`)
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `rating_video`
--

-- --------------------------------------------------------

--
-- Struktur för tabell `receipts`
--

CREATE TABLE IF NOT EXISTS `receipts` (
  `rec_recno` int(11) NOT NULL auto_increment,
  `rec_memuserid` int(11) NOT NULL default '0',
  `rec_affuserid` int(11) NOT NULL default '0',
  `rec_buydate` date default NULL,
  `rec_paydate` date default NULL,
  `rec_earliestpay` date default NULL,
  `rec_paid` enum('Y','N') default 'N',
  `rec_amount` varchar(20) default NULL,
  `rec_currency` varchar(10) default NULL,
  `rec_transid` varchar(50) default NULL,
  `rec_percentage` smallint(3) default NULL,
  `rec_affamount` decimal(9,2) default NULL,
  PRIMARY KEY  (`rec_recno`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Data i tabell `receipts`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `reviews`
--

CREATE TABLE IF NOT EXISTS `reviews` (
  `review_recid` int(11) NOT NULL auto_increment,
  `review_userid` int(11) NOT NULL default '0',
  `review_id` int(11) NOT NULL default '0',
  `review_type` varchar(10) NOT NULL default '',
  `review_text` text NOT NULL,
  `review_createdate` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `review_approved` int(11) NOT NULL default '0',
  PRIMARY KEY  (`review_recid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Data i tabell `reviews`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `sarray`
--

CREATE TABLE IF NOT EXISTS `sarray` (
  `sar_recno` int(11) NOT NULL auto_increment,
  `sar_userid` int(11) NOT NULL default '0',
  `sar_type` varchar(50) default NULL,
  `sar_value` varchar(50) default NULL,
  PRIMARY KEY  (`sar_recno`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21806 ;

--
-- Data i tabell `sarray`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `sd_events`
--

CREATE TABLE IF NOT EXISTS `sd_events` (
  `sde_eventid` int(11) NOT NULL auto_increment,
  `sde_name` varchar(255) NOT NULL default '',
  `sde_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `sde_gender1` char(1) NOT NULL default '',
  `sde_gender2` char(1) NOT NULL default '',
  `sde_age_from` smallint(6) NOT NULL default '0',
  `sde_age_to` smallint(6) NOT NULL default '0',
  `sde_gender1_places` int(11) NOT NULL default '0',
  `sde_gender2_places` int(11) NOT NULL default '0',
  `sde_booked1` int(11) NOT NULL default '0',
  `sde_booked2` int(11) NOT NULL default '0',
  `sde_venueid` int(11) NOT NULL default '0',
  `sde_description` text NOT NULL,
  `sde_is_special` enum('yes','no') NOT NULL default 'no',
  `sde_special` varchar(200) NOT NULL default '',
  `sde_price` decimal(10,2) NOT NULL default '0.00',
  PRIMARY KEY  (`sde_eventid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Data i tabell `sd_events`
--

-- --------------------------------------------------------

--
-- Struktur för tabell `sd_matches`
--

CREATE TABLE IF NOT EXISTS `sd_matches` (
  `sdm_eventid` int(11) NOT NULL default '0',
  `sdm_userid_1` int(11) NOT NULL default '0',
  `sdm_userid_2` int(11) NOT NULL default '0',
  UNIQUE KEY `sdm_eventid` (`sdm_eventid`,`sdm_userid_1`,`sdm_userid_2`)
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `sd_matches`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `sd_settings`
--

CREATE TABLE IF NOT EXISTS `sd_settings` (
  `sd_name` varchar(30) NOT NULL default '',
  `sd_value` text NOT NULL,
  `sd_description` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`sd_name`)
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `sd_settings`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `sd_stories`
--

CREATE TABLE IF NOT EXISTS `sd_stories` (
  `sd_storyid` int(11) NOT NULL auto_increment,
  `sd_title` varchar(250) default NULL,
  `sd_body` mediumtext,
  `sd_who` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`sd_storyid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Data i tabell `sd_stories`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `sd_subscribe`
--

CREATE TABLE IF NOT EXISTS `sd_subscribe` (
  `sub_id` int(11) NOT NULL auto_increment,
  `sub_email` varchar(255) default NULL,
  `sub_sex` enum('M','F') default NULL,
  PRIMARY KEY  (`sub_id`),
  UNIQUE KEY `email` (`sub_email`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

--
-- Data i tabell `sd_subscribe`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `sd_tickets`
--

CREATE TABLE IF NOT EXISTS `sd_tickets` (
  `sdt_ticket_id` int(11) NOT NULL auto_increment,
  `sdt_ticket_num` int(11) NOT NULL default '0',
  `sdt_userid` int(11) NOT NULL default '0',
  `sdt_gender` enum('Gender1','Gender2') default 'Gender1',
  `sdt_paymentid` int(11) NOT NULL default '0',
  `sdt_eventid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`sdt_ticket_id`),
  KEY `sdt_userid` (`sdt_userid`,`sdt_eventid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=17 ;

--
-- Data i tabell `sd_tickets`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `sd_venues`
--

CREATE TABLE IF NOT EXISTS `sd_venues` (
  `vnu_venueid` int(11) NOT NULL auto_increment,
  `vnu_name` varchar(255) NOT NULL default '',
  `vnu_countryid` int(11) NOT NULL default '0',
  `vnu_stateid` int(11) NOT NULL default '0',
  `vnu_cityid` int(11) NOT NULL default '0',
  `vnu_address` tinytext NOT NULL,
  `vnu_phone` varchar(20) NOT NULL default '',
  `vnu_website` varchar(255) NOT NULL default '',
  `vnu_description` text NOT NULL,
  `vnu_directions` tinytext NOT NULL,
  `vnu_map` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`vnu_venueid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Data i tabell `sd_venues`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `sd_venue_pic`
--

CREATE TABLE IF NOT EXISTS `sd_venue_pic` (
  `vnp_id` int(11) NOT NULL auto_increment,
  `vnp_venueid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`vnp_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Data i tabell `sd_venue_pic`
--

-- --------------------------------------------------------

--
-- Struktur för tabell `sd_waiting`
--

CREATE TABLE IF NOT EXISTS `sd_waiting` (
  `swt_userid` int(10) unsigned NOT NULL default '0',
  `swt_eventid` int(10) unsigned NOT NULL default '0',
  `swt_date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`swt_eventid`,`swt_userid`)
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `sd_waiting`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `search`
--

CREATE TABLE IF NOT EXISTS `search` (
  `sea_userid` int(11) NOT NULL default '0',
  `sea_smoker` varchar(15) default NULL,
  `sea_children` varchar(15) default NULL,
  `sea_minheight` varchar(15) default NULL,
  `sea_maxheight` varchar(15) default NULL,
  `sea_religion` varchar(75) default NULL,
  `sea_seeksex` varchar(25) default NULL,
  `sea_agemin` tinyint(4) default NULL,
  `sea_agemax` tinyint(4) default NULL,
  `sea_agency` varchar(7) default NULL,
  `sea_income` varchar(20) default NULL,
  `sea_education` varchar(20) default NULL,
  `sea_date` int(11) NOT NULL default '0',
  PRIMARY KEY  (`sea_userid`)
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `search`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `sms_carrier`
--

CREATE TABLE IF NOT EXISTS `sms_carrier` (
  `id` int(11) NOT NULL auto_increment,
  `title` varchar(100) default NULL,
  `email` varchar(255) default NULL,
  `status` int(11) NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Data i tabell `sms_carrier`
--

INSERT INTO `sms_carrier` (`id`, `title`, `email`, `status`) VALUES
(1, 'T-Mobile', 'tmomail.net', 0),
(2, 'Virgin Mobile', 'vmobl.com', 0),
(3, 'Cingular1', 'cingularme.com', 1),
(4, 'Sprint', 'messaging.sprintpcs.com', 0),
(5, 'Verizon', 'vtext.com', 0),
(6, 'Nextel', 'messaging.nextel.com', 0);

-- --------------------------------------------------------

--
-- Struktur för tabell `social_network`
--

CREATE TABLE IF NOT EXISTS `social_network` (
  `User_ID` int(11) NOT NULL default '0',
  `Friend_ID` int(11) NOT NULL default '0',
  `Status` enum('P','A') NOT NULL default 'P',
  PRIMARY KEY  (`User_ID`,`Friend_ID`)
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `social_network`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `spamwords`
--

CREATE TABLE IF NOT EXISTS `spamwords` (
  `SpamWord_ID` int(11) NOT NULL auto_increment,
  `SpamWord` varchar(128) NOT NULL default '',
  PRIMARY KEY  (`SpamWord_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

--
-- Data i tabell `spamwords`
--

INSERT INTO `spamwords` (`SpamWord_ID`, `SpamWord`) VALUES
(1, 'president'),
(2, ' late husband'),
(3, 'assassination'),
(4, 'transaction'),
(5, 'security company'),
(6, 'sealed trunk'),
(7, 'BANK ACCOUNT'),
(8, 'MILLION US DOLLARS'),
(9, 'your commission'),
(10, 'US$'),
(11, 'million'),
(12, 'deposit'),
(13, '$10,000'),
(14, 'West Africa'),
(15, 'Nigeria'),
(16, 'Ivory Coast'),
(17, 'ivory coast'),
(18, 'shipping cost'),
(19, 'USD'),
(20, 'your assitance'),
(21, 'urgent business'),
(22, 'Nigerian telecommunication Plc'),
(23, 'SUCCESSFUL TRANSFER'),
(24, 'bank'),
(25, 'assitance'),
(26, 'transfer'),
(27, 'money'),
(28, 'assist'),
(29, 'deposit'),
(30, 'SUCCESSFUL TRANSFER'),
(31, 'nigeria');

-- --------------------------------------------------------

--
-- Struktur för tabell `stories`
--

CREATE TABLE IF NOT EXISTS `stories` (
  `story_id` int(11) NOT NULL auto_increment,
  `title` varchar(250) default NULL,
  `body` mediumtext,
  PRIMARY KEY  (`story_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

--
-- Data i tabell `stories`
--

-- --------------------------------------------------------

--
-- Struktur för tabell `tag`
--

CREATE TABLE IF NOT EXISTS `tag` (
  `id` int(11) NOT NULL auto_increment,
  `tag` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `tag` (`tag`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=36 ;

--
-- Data i tabell `tag`
--

-- --------------------------------------------------------

--
-- Struktur för tabell `tag_links`
--

CREATE TABLE IF NOT EXISTS `tag_links` (
  `tag_id` int(11) NOT NULL default '0',
  `ent_id` int(11) NOT NULL default '0',
  `ent_type` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`tag_id`,`ent_id`,`ent_type`)
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `tag_links`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `userplane_banned_chat`
--

CREATE TABLE IF NOT EXISTS `userplane_banned_chat` (
  `userid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`userid`)
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `userplane_banned_chat`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `userplane_blocked`
--

CREATE TABLE IF NOT EXISTS `userplane_blocked` (
  `userid` int(11) NOT NULL default '0',
  `targetuserid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`userid`,`targetuserid`)
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `userplane_blocked`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `userplane_blocked_chat`
--

CREATE TABLE IF NOT EXISTS `userplane_blocked_chat` (
  `userid` mediumint(9) NOT NULL default '0',
  `targetuserid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`userid`,`targetuserid`)
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `userplane_blocked_chat`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `userplane_friends`
--

CREATE TABLE IF NOT EXISTS `userplane_friends` (
  `userid` int(11) NOT NULL default '0',
  `targetuserid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`userid`,`targetuserid`)
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `userplane_friends`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `userplane_friends_chat`
--

CREATE TABLE IF NOT EXISTS `userplane_friends_chat` (
  `userid` int(11) NOT NULL default '0',
  `targetuserid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`userid`,`targetuserid`)
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `userplane_friends_chat`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `userplane_pending_ic`
--

CREATE TABLE IF NOT EXISTS `userplane_pending_ic` (
  `originatingUserID` int(11) NOT NULL default '0',
  `destinationUserID` int(11) NOT NULL default '0',
  `openedWindowAt` datetime default NULL,
  `insertedAt` datetime NOT NULL default '0000-00-00 00:00:00'
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `userplane_pending_ic`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `userplane_room_chat`
--

CREATE TABLE IF NOT EXISTS `userplane_room_chat` (
  `name` varchar(255) NOT NULL default '',
  `userid` int(11) NOT NULL default '0',
  `description` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`name`)
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `userplane_room_chat`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `userplane_room_chat_member`
--

CREATE TABLE IF NOT EXISTS `userplane_room_chat_member` (
  `name` varchar(255) NOT NULL default '',
  `userid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`name`,`userid`)
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `userplane_room_chat_member`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `videos`
--

CREATE TABLE IF NOT EXISTS `videos` (
  `vid_id` int(11) NOT NULL auto_increment,
  `vid_userid` int(11) NOT NULL default '0',
  `vid_video` varchar(75) NOT NULL default '',
  `vid_private` enum('Y','N') NOT NULL default 'N',
  `vid_title` varchar(60) NOT NULL default '',
  `vid_date` datetime NOT NULL default '0000-00-00 00:00:00',
  `vid_description` varchar(255) NOT NULL default '',
  `vid_status` enum('new','rejected','converted') NOT NULL default 'new',
  `vid_rating` float NOT NULL default '0',
  `vid_views` int(11) NOT NULL default '0',
  PRIMARY KEY  (`vid_id`),
  UNIQUE KEY `vid_id` (`vid_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1284 ;

--
-- Data i tabell `videos`
--

-- --------------------------------------------------------

--
-- Struktur för tabell `vlistbox`
--

CREATE TABLE IF NOT EXISTS `vlistbox` (
  `lst_recid` int(11) unsigned NOT NULL auto_increment,
  `lst_type` char(3) NOT NULL default '',
  `lst_value` varchar(50) NOT NULL default '',
  `lst_base` enum('Y','N') NOT NULL default 'Y',
  `lst_adult` enum('Y','N') NOT NULL default 'Y',
  `lst_order` int(3) NOT NULL default '0',
  PRIMARY KEY  (`lst_recid`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=280 ;

--
-- Data i tabell `vlistbox`
--

INSERT INTO `vlistbox` (`lst_recid`, `lst_type`, `lst_value`, `lst_base`, `lst_adult`, `lst_order`) VALUES
(1, 'CHL', 'No', 'Y', 'Y', 30),
(2, 'CHL', 'Yes', 'Y', 'Y', 20),
(3, 'CHL', 'Not stated', 'Y', 'Y', 10),
(4, 'RLG', 'Not stated', 'Y', 'Y', 10),
(5, 'RLG', 'None', 'Y', 'Y', 20),
(6, 'RLG', 'Agnostic', 'Y', 'Y', 30),
(7, 'RLG', 'Atheist', 'Y', 'Y', 40),
(8, 'RLG', 'Baha i', 'Y', 'Y', 50),
(9, 'RLG', 'Baptist', 'Y', 'Y', 60),
(10, 'RLG', 'Buddhist', 'Y', 'Y', 70),
(11, 'RLG', 'Catholic', 'Y', 'Y', 80),
(12, 'RLG', 'Christian', 'Y', 'Y', 90),
(13, 'RLG', 'Hindu', 'Y', 'Y', 100),
(14, 'RLG', 'Jewish', 'Y', 'Y', 110),
(15, 'RLG', 'Lutheran', 'Y', 'Y', 120),
(16, 'RLG', 'Methodist', 'Y', 'Y', 130),
(17, 'RLG', 'Mormon (LDS)', 'Y', 'Y', 140),
(18, 'RLG', 'Muslim', 'Y', 'Y', 150),
(19, 'RLG', 'New Age/Spiritualist', 'Y', 'Y', 160),
(20, 'RLG', 'Pagan/Wiccan', 'Y', 'Y', 170),
(21, 'RLG', 'Protestant/Anglican', 'Y', 'Y', 180),
(22, 'RLG', 'Voodoo', 'Y', 'Y', 190),
(23, 'RLG', 'Other Religion', 'Y', 'Y', 200),
(45, 'ETH', 'Native American', 'Y', 'Y', 70),
(44, 'ETH', 'Middle Eastern', 'Y', 'Y', 30),
(43, 'ETH', 'Indian', 'Y', 'Y', 40),
(42, 'ETH', 'Hispanic', 'Y', 'Y', 50),
(41, 'ETH', 'European', 'Y', 'Y', 60),
(40, 'ETH', 'Caucasian', 'Y', 'Y', 20),
(39, 'ETH', 'Asia/Pacific', 'Y', 'Y', 80),
(38, 'ETH', 'African American', 'Y', 'Y', 90),
(37, 'ETH', 'African', 'Y', 'Y', 100),
(36, 'ETH', 'Not stated', 'Y', 'Y', 10),
(46, 'ETH', 'South Asian', 'Y', 'Y', 110),
(47, 'ETH', 'Mixed Race', 'Y', 'Y', 120),
(48, 'ETH', 'Other Ethnicity', 'Y', 'Y', 130),
(49, 'EMP', 'Not stated', 'Y', 'Y', 10),
(50, 'EMP', 'Unemployed', 'Y', 'Y', 20),
(51, 'EMP', 'Student', 'Y', 'Y', 30),
(52, 'EMP', 'Homemaker', 'Y', 'Y', 40),
(53, 'EMP', 'Part-time', 'Y', 'Y', 50),
(54, 'EMP', 'Full-time', 'Y', 'Y', 60),
(55, 'EMP', 'Self-employed', 'Y', 'Y', 70),
(56, 'EMP', 'Retired', 'Y', 'Y', 80),
(57, 'EMP', 'Other', 'Y', 'Y', 90),
(58, 'EDU', 'Not stated', 'Y', 'Y', 10),
(59, 'EDU', 'Some High School', 'Y', 'Y', 20),
(60, 'EDU', 'High School Grad', 'Y', 'Y', 30),
(61, 'EDU', 'Some College', 'Y', 'Y', 40),
(62, 'EDU', 'College Grad', 'Y', 'Y', 50),
(63, 'EDU', 'Post-Graduate', 'Y', 'Y', 60),
(64, 'INC', 'Not stated', 'Y', 'Y', 10),
(65, 'INC', '00 - 20k', 'Y', 'Y', 20),
(66, 'INC', '21 - 40k', 'Y', 'Y', 30),
(67, 'INC', '41 - 60k', 'Y', 'Y', 40),
(68, 'INC', '61 - 80k', 'Y', 'Y', 50),
(69, 'INC', 'Above 100k', 'Y', 'Y', 60),
(70, 'MRT', 'Not stated', 'Y', 'Y', 10),
(71, 'MRT', 'Single', 'Y', 'Y', 20),
(72, 'MRT', 'Married', 'Y', 'Y', 30),
(73, 'MRT', 'Seperated', 'Y', 'Y', 40),
(74, 'MRT', 'Divorced', 'Y', 'Y', 50),
(75, 'MRT', 'Widowed', 'Y', 'Y', 60),
(76, 'SMK', 'Not stated', 'Y', 'Y', 10),
(78, 'SMK', 'Social', 'Y', 'Y', 30),
(79, 'SMK', 'Light', 'Y', 'Y', 40),
(80, 'SMK', 'Average', 'Y', 'Y', 50),
(81, 'SMK', 'Heavy', 'Y', 'Y', 60),
(82, 'SMK', 'Cigars', 'Y', 'Y', 70),
(83, 'SMK', 'Pipe', 'Y', 'Y', 80),
(84, 'SKG', 'Friendship', 'Y', 'Y', 20),
(85, 'SKG', 'Occasional', 'Y', 'Y', 80),
(99, 'SKG', 'Marriage', 'Y', 'Y', 40),
(87, 'SKG', 'Intimate', 'Y', 'Y', 50),
(88, 'SKG', 'Short-term', 'Y', 'Y', 60),
(89, 'SKG', 'Long-term', 'Y', 'Y', 70),
(90, 'SKG', 'E-mail chat', 'Y', 'Y', 10),
(91, 'BDY', 'Not stated', 'Y', 'Y', 10),
(92, 'BDY', 'Petite', 'Y', 'Y', 20),
(93, 'BDY', 'Slim', 'Y', 'Y', 30),
(94, 'BDY', 'Athletic', 'Y', 'Y', 40),
(95, 'BDY', 'Average', 'Y', 'Y', 50),
(96, 'BDY', 'Large', 'Y', 'Y', 60),
(97, 'BDY', 'Very large', 'Y', 'Y', 70),
(98, 'SMK', 'No', 'Y', 'Y', 20),
(213, 'FDT', 'Hispanic', 'Y', 'Y', 40),
(212, 'FDT', 'French', 'Y', 'Y', 30),
(211, 'FDT', 'BBQ', 'Y', 'Y', 20),
(210, 'FDT', 'Asian', 'Y', 'Y', 10),
(104, 'PST', 'Adventurous', 'Y', 'Y', 10),
(105, 'PST', 'Easygoing', 'Y', 'Y', 20),
(106, 'PST', 'Humourous', 'Y', 'Y', 30),
(107, 'PST', 'Introvert', 'Y', 'Y', 40),
(108, 'PST', 'Outgoing', 'Y', 'Y', 50),
(109, 'PST', 'Outspoken', 'Y', 'Y', 60),
(110, 'PST', 'Playful', 'Y', 'Y', 70),
(111, 'PST', 'Romantic', 'Y', 'Y', 80),
(112, 'PST', 'Sensitive', 'Y', 'Y', 90),
(113, 'PST', 'Sensual', 'Y', 'Y', 100),
(114, 'PST', 'Shy', 'Y', 'Y', 110),
(115, 'PST', 'Warm hearted', 'Y', 'Y', 120),
(116, 'PST', 'Wild', 'Y', 'Y', 130),
(117, 'PHI', 'Ambitious', 'Y', 'Y', 10),
(118, 'PHI', 'Idealistic', 'Y', 'Y', 20),
(119, 'PHI', 'Independent', 'Y', 'Y', 30),
(120, 'PHI', 'Optimistic', 'Y', 'Y', 40),
(121, 'PHI', 'Pessimistic', 'Y', 'Y', 50),
(122, 'PHI', 'Practical', 'Y', 'Y', 60),
(123, 'PHI', 'Rebellious', 'Y', 'Y', 70),
(124, 'PHI', 'Spiritual', 'Y', 'Y', 80),
(125, 'PHI', 'Traditional', 'Y', 'Y', 90),
(126, 'PHI', 'Unconventional', 'Y', 'Y', 100),
(127, 'PHI', 'Virtuous', 'Y', 'Y', 110),
(128, 'SOG', 'Average', 'Y', 'Y', 10),
(129, 'SOG', 'Country folks', 'Y', 'Y', 20),
(130, 'SOG', 'Creative people', 'Y', 'Y', 30),
(131, 'SOG', 'Classy people', 'Y', 'Y', 40),
(132, 'SOG', 'Technical', 'Y', 'Y', 50),
(133, 'SOG', 'Cultured people', 'Y', 'Y', 60),
(134, 'SOG', 'Entreprenuers', 'Y', 'Y', 70),
(135, 'SOG', 'Intellectuals', 'Y', 'Y', 80),
(136, 'SOG', 'Professionals', 'Y', 'Y', 90),
(137, 'SOG', 'Socialites', 'Y', 'Y', 100),
(138, 'GLS', 'Career', 'Y', 'Y', 10),
(139, 'GLS', 'Family', 'Y', 'Y', 20),
(140, 'GLS', 'Fame', 'Y', 'Y', 30),
(141, 'GLS', 'Fun', 'Y', 'Y', 40),
(142, 'GLS', 'Knowledge', 'Y', 'Y', 50),
(143, 'GLS', 'Serenity', 'Y', 'Y', 60),
(144, 'GLS', 'Wealth', 'Y', 'Y', 70),
(145, 'HBS', 'Animals', 'Y', 'Y', 10),
(146, 'HBS', 'Art', 'Y', 'Y', 20),
(147, 'HBS', 'Automobiles', 'Y', 'Y', 30),
(148, 'HBS', 'Boating', 'Y', 'Y', 40),
(149, 'HBS', 'Boardgames', 'Y', 'Y', 50),
(150, 'HBS', 'Cards', 'Y', 'Y', 60),
(151, 'HBS', 'Computers', 'Y', 'Y', 70),
(152, 'HBS', 'Cooking', 'Y', 'Y', 80),
(153, 'HBS', 'Gardening', 'Y', 'Y', 90),
(154, 'HBS', 'Motorcycles', 'Y', 'Y', 100),
(155, 'HBS', 'Music', 'Y', 'Y', 110),
(156, 'HBS', 'Needlework', 'Y', 'Y', 120),
(157, 'HBS', 'Photography', 'Y', 'Y', 130),
(158, 'HBS', 'Poetry', 'Y', 'Y', 140),
(159, 'HBS', 'Reading', 'Y', 'Y', 150),
(160, 'HBS', 'Outdoors', 'Y', 'Y', 160),
(161, 'HBS', 'Theater', 'Y', 'Y', 170),
(162, 'HBS', 'Travel', 'Y', 'Y', 180),
(163, 'HBS', 'Tv/movies', 'Y', 'Y', 190),
(164, 'HBS', 'Writing', 'Y', 'Y', 200),
(165, 'SPT', 'Aerobics', 'Y', 'Y', 10),
(166, 'SPT', 'Archery', 'Y', 'Y', 20),
(167, 'SPT', 'Athletics', 'Y', 'Y', 30),
(168, 'SPT', 'Badminton', 'Y', 'Y', 40),
(169, 'SPT', 'Bowling', 'Y', 'Y', 50),
(170, 'SPT', 'Bicycling', 'Y', 'Y', 60),
(171, 'SPT', 'Body shaping', 'Y', 'Y', 70),
(172, 'SPT', 'Climbing', 'Y', 'Y', 80),
(173, 'SPT', 'Cricket', 'Y', 'Y', 90),
(174, 'SPT', 'Dancing', 'Y', 'Y', 100),
(175, 'SPT', 'Golf', 'Y', 'Y', 110),
(176, 'SPT', 'Hiking', 'Y', 'Y', 120),
(177, 'SPT', 'Jogging', 'Y', 'Y', 130),
(178, 'SPT', 'Kabadi', 'Y', 'Y', 140),
(179, 'SPT', 'Lacrosse', 'Y', 'Y', 150),
(180, 'SPT', 'Martial arts', 'Y', 'Y', 160),
(181, 'SPT', 'Motor sports', 'Y', 'Y', 170),
(182, 'SPT', 'Riding', 'Y', 'Y', 180),
(183, 'SPT', 'Rugby', 'Y', 'Y', 190),
(184, 'SPT', 'Skating', 'Y', 'Y', 200),
(185, 'SPT', 'Skiing', 'Y', 'Y', 210),
(186, 'SPT', 'Skydiving', 'Y', 'Y', 220),
(187, 'SPT', 'Swimming', 'Y', 'Y', 230),
(188, 'SPT', 'Soccer', 'Y', 'Y', 240),
(189, 'SPT', 'Tennis', 'Y', 'Y', 250),
(190, 'SPT', 'Volleyball', 'Y', 'Y', 260),
(191, 'SPT', 'Walking', 'Y', 'Y', 270),
(192, 'SPT', 'Water sports', 'Y', 'Y', 280),
(193, 'MSC', 'Alternative', 'Y', 'Y', 10),
(194, 'MSC', 'Classical', 'Y', 'Y', 20),
(195, 'MSC', 'Blues', 'Y', 'Y', 30),
(196, 'MSC', 'Country', 'Y', 'Y', 40),
(197, 'MSC', 'Dance/disco', 'Y', 'Y', 50),
(198, 'MSC', 'Easy listening', 'Y', 'Y', 60),
(199, 'MSC', 'Folk', 'Y', 'Y', 70),
(200, 'MSC', 'Gospel', 'Y', 'Y', 80),
(201, 'MSC', 'Jazz', 'Y', 'Y', 90),
(202, 'MSC', 'Metal', 'Y', 'Y', 100),
(203, 'MSC', 'Modern', 'Y', 'Y', 110),
(204, 'MSC', 'New-age', 'Y', 'Y', 120),
(205, 'MSC', 'R & B', 'Y', 'Y', 130),
(206, 'MSC', 'Reggae', 'Y', 'Y', 140),
(207, 'MSC', 'Rap', 'Y', 'Y', 150),
(208, 'MSC', 'Rock', 'Y', 'Y', 160),
(209, 'MSC', 'Soul', 'Y', 'Y', 170),
(214, 'FDT', 'Italian', 'Y', 'Y', 50),
(215, 'FDT', 'Take-away', 'Y', 'Y', 60),
(216, 'FDT', 'Health foods', 'Y', 'Y', 70),
(217, 'FDT', 'Sea food', 'Y', 'Y', 80),
(218, 'FDT', 'Vegan food', 'Y', 'Y', 90),
(219, 'FDT', 'Vegetarian', 'Y', 'Y', 100),
(220, 'SKG', 'Activity Pals', 'Y', 'Y', 30),
(233, 'SKG', 'Casual Encounters', 'Y', 'Y', 90),
(232, 'CHL', 'Maybe', 'Y', 'Y', 40),
(234, 'BDY', 'Muscular', 'Y', 'Y', 80),
(235, 'BDY', 'Bodybuilder', 'Y', 'Y', 90),
(259, 'DNK', 'Yes frequently', 'Y', 'Y', 20),
(260, 'DNK', 'Yes sometimes', 'Y', 'Y', 30),
(261, 'DNK', 'Yes on special occasions', 'Y', 'Y', 40),
(262, 'DNK', 'No', 'Y', 'Y', 50),
(263, 'DNK', 'Not stated', 'Y', 'Y', 10),
(264, 'EYE', 'Gray', 'Y', 'Y', 20),
(265, 'EYE', 'Green', 'Y', 'Y', 30),
(266, 'HAR', 'Brown', 'Y', 'Y', 20),
(267, 'HAR', 'Blonde', 'Y', 'Y', 30),
(269, 'EYE', 'Blue', 'Y', 'Y', 40),
(270, 'EYE', 'Hazel', 'Y', 'Y', 50),
(271, 'EYE', 'Brown', 'Y', 'Y', 60),
(272, 'EYE', 'Black', 'Y', 'Y', 70),
(273, 'HAR', 'Red', 'Y', 'Y', 40),
(274, 'HAR', 'Gray', 'Y', 'Y', 50),
(275, 'HAR', 'Black', 'Y', 'Y', 60),
(276, 'EYE', 'Not stated', 'Y', 'Y', 10),
(277, 'HAR', 'Not stated', 'Y', 'Y', 10);

-- --------------------------------------------------------

--
-- Struktur för tabell `vlistbox_values`
--

CREATE TABLE IF NOT EXISTS `vlistbox_values` (
  `lst_recid` int(11) NOT NULL default '0',
  `lang_id` char(3) NOT NULL default '',
  `lst_value` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`lst_recid`,`lang_id`)
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `vlistbox_values`
--


-- --------------------------------------------------------

--
-- Struktur för tabell `whos_online`
--

CREATE TABLE IF NOT EXISTS `whos_online` (
  `customer_id` int(11) default NULL,
  `full_name` varchar(64) NOT NULL default '',
  `session_id` varchar(128) NOT NULL default '',
  `ip_address` varchar(15) NOT NULL default '',
  `time_entry` varchar(14) NOT NULL default '',
  `time_last_click` varchar(14) NOT NULL default '',
  `last_page_url` varchar(64) NOT NULL default ''
) ENGINE=MyISAM DEFAULT charset=utf8;

--
-- Data i tabell `whos_online`
--

--
-- Struktur för tabell `zipcodes`
--

CREATE TABLE IF NOT EXISTS `zipcodes` (
  `zip_zipcode` varchar(8) NOT NULL default '',
  `zip_codetype` char(1) NOT NULL default '',
  `zip_city` varchar(75) NOT NULL default '',
  `zip_citytype` char(1) NOT NULL default '',
  `zip_state` varchar(75) NOT NULL default '',
  `zip_statecode` char(2) NOT NULL default '',
  `zip_areacode` varchar(5) NOT NULL default '',
  `zip_latitude` double(9,4) NOT NULL default '0.0000',
  `zip_longitude` double(9,4) NOT NULL default '0.0000'
) ENGINE=MyISAM DEFAULT charset=utf8;


CREATE TABLE IF NOT EXISTS `tmp_adverts` (
  `adv_userid` int(11) NOT NULL default '0',
  `adv_username` varchar(30) NOT NULL default '',
  `adv_smoker` varchar(250) default NULL,
  `adv_children` varchar(250) default NULL,
  `adv_comment` text,
  `adv_location` varchar(250) default NULL,
  `adv_countryid` int(11) NOT NULL default '0',
  `adv_stateid` int(11) NOT NULL default '0',
  `adv_cityid` int(11) NOT NULL default '0',
  `adv_height` varchar(15) default NULL,
  `adv_marital` varchar(250) default NULL,
  `adv_bodytype` varchar(250) default NULL,
  `adv_ethnicity` varchar(250) default NULL,
  `adv_religion` varchar(250) default NULL,
  `adv_sex` char(1) default NULL,
  `adv_seekmen` char(1) default NULL,
  `adv_seekwmn` char(1) default NULL,
  `adv_seekcpl` char(1) default NULL,
  `adv_seeking` varchar(250) default NULL,
  `adv_picture` varchar(75) default NULL,
  `adv_dob` date default NULL,
  `adv_approved` int(11) default NULL,
  `adv_agency` varchar(7) default NULL,
  `adv_createdate` datetime default NULL,
  `adv_title` varchar(30) default NULL,
  `adv_education` varchar(250) default NULL,
  `adv_income` varchar(250) default NULL,
  `adv_profession` varchar(250) default NULL,
  `adv_expiredate` date default NULL,
  `adv_views` int(3) default '0',
  `adv_zipcode` varchar(5) default NULL,
  `adv_paused` enum('Y','N') NOT NULL default 'N',
  `adv_rejectreason` varchar(255) NOT NULL default '',
  `adv_eyecolor` varchar(250) NOT NULL default '',
  `adv_haircolor` varchar(250) NOT NULL default '',
  `adv_drink` varchar(250) NOT NULL default '',
  `adv_ip` varchar(255) NOT NULL default '',
  `block_time` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`adv_userid`)
) ENGINE=MyISAM DEFAULT charset=utf8;


CREATE TABLE IF NOT EXISTS `tmp_mymatch` (
  `mym_userid` int(11) NOT NULL default '0',
  `mym_gender` varchar(255) NOT NULL default '',
  `mym_smoker` varchar(15) default NULL,
  `mym_comment` text,
  `mym_minheight` varchar(15) default NULL,
  `mym_maxheight` varchar(15) default NULL,
  `mym_bodytype` varchar(15) default NULL,
  `mym_agemin` tinyint(4) default NULL,
  `mym_agemax` tinyint(4) default NULL,
  `mym_relationship` varchar(50) NOT NULL default '',
  PRIMARY KEY  (`mym_userid`)
) ENGINE=MyISAM DEFAULT charset=utf8;



ALTER TABLE `members` ADD `mem_featured` TINYINT NOT NULL DEFAULT '0';

ALTER TABLE `zipcodes` ADD PRIMARY KEY ( `zip_zipcode` );
ALTER TABLE `adverts` ADD INDEX ( `adv_userid` , `adv_title` );
ALTER TABLE `hotlist` ADD INDEX ( `hot_userid` , `hot_advid` );
ALTER TABLE `geo_state` DROP PRIMARY KEY ,
ADD PRIMARY KEY ( `gst_stateid` , `gst_countryid` );
ALTER TABLE `geo_city` DROP PRIMARY KEY ,
ADD PRIMARY KEY ( `gct_cityid` , `gct_countryid` , `gct_stateid` );
ALTER TABLE `geo_city` ADD INDEX ( `gct_countryid` , `gct_stateid` );
ALTER TABLE `encounters` ADD INDEX ( `enc_userid` , `enc_viewerid` );
ALTER TABLE `messages` ADD INDEX ( `msg_receiverid` , `msg_receiverdel` , `msg_read` );
ALTER TABLE `messages` ADD INDEX ( `msg_senderid` , `msg_receiverid` );
ALTER TABLE `notifications` ADD INDEX ( `ntf_senderid` , `ntf_receiverid` );
ALTER TABLE `members` ADD INDEX ( `mem_username` , `mem_password` );
ALTER TABLE `members` ADD INDEX ( `mem_email` );
ALTER TABLE `sarray` ADD INDEX ( `sar_userid` , `sar_type` );
ALTER TABLE `pictures` ADD INDEX ( `pic_userid` , `pic_default` );
ALTER TABLE `bb_subtopics` ADD INDEX ( `subtopic_status` , `forum_id` );
ALTER TABLE `bb_posts` ADD INDEX ( `subtopic_id` , `post_approved` );