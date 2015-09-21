CREATE TABLE `Users` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `last_login` varchar(100) DEFAULT NULL,
  `admin` int(1) DEFAULT '0',
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `status` int(1) DEFAULT '0',
  `roles` text,
  `page_roles` text,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

CREATE TABLE `roles_definitions` (
  `definition_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) DEFAULT NULL,
  `permission_type` varchar(100) DEFAULT NULL,
  `permission_object` varchar(100) DEFAULT NULL,
  `permission` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`definition_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `profiles` (
  `profile_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `profile_name` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`profile_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;