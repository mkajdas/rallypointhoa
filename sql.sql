CREATE TABLE `rallypoint` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `name` text,
  `subdomain` text,
  `filesize` decimal(20,2) default NULL,
  `units` int(11) default NULL,
  `numcars` int(11) default NULL,
  `currentplan` int(11) default '0',
  `experdate` date default NULL,
  `gon` longtext,
  `newplan` int(11) default NULL,
  `reglimit` int(11) default '1',
  `email` text,
  PRIMARY KEY  (`id`)
);

INSERT INTO  `rallypoint` (`name` ,  `subdomain` ,  `filesize` ,  `units` ,  `numcars` ,  `currentplan` , `reglimit` ,  `email` ) VALUES ('Example',  'Example',  629145600.00,  500,  5,  0, 1,  'example@example.net');