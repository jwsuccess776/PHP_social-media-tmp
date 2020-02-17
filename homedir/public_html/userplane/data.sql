CREATE TABLE `userplane_banned_chat` (
  `userid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`userid`)
) TYPE=MyISAM;

CREATE TABLE `userplane_blocked` (
  `userid` int(11) NOT NULL default '0',
  `targetuserid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`userid`,`targetuserid`)
) TYPE=MyISAM;

CREATE TABLE `userplane_blocked_chat` (
  `userid` mediumint(9) NOT NULL default '0',
  `targetuserid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`userid`,`targetuserid`)
) TYPE=MyISAM;

CREATE TABLE `userplane_friends` (
  `userid` int(11) NOT NULL default '0',
  `targetuserid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`userid`,`targetuserid`)
) TYPE=MyISAM;

CREATE TABLE `userplane_friends_chat` (
  `userid` int(11) NOT NULL default '0',
  `targetuserid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`userid`,`targetuserid`)
) TYPE=MyISAM;

CREATE TABLE `userplane_pending_ic` (
  `originatingUserID` int(11) NOT NULL default '0',
  `destinationUserID` int(11) NOT NULL default '0',
  `openedWindowAt` datetime default NULL,
  `insertedAt` datetime NOT NULL default '0000-00-00 00:00:00'
) TYPE=MyISAM;

CREATE TABLE `userplane_room_chat` (
  `name` varchar(255) NOT NULL default '',
  `userid` int(11) NOT NULL default '0',
  `description` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`name`)
) TYPE=MyISAM;

CREATE TABLE `userplane_room_chat_member` (
  `name` varchar(255) NOT NULL default '',
  `userid` int(11) NOT NULL default '0',
  PRIMARY KEY  (`name`,`userid`)
) TYPE=MyISAM;
