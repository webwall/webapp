/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `firstname` varchar(256) DEFAULT NULL,
  `surname` varchar(256) DEFAULT NULL,
  `email` varchar(256) DEFAULT NULL,
  `password` varchar(512) DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL,
  `permissions` tinyint(4) DEFAULT '0',
  `lastlogin` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created` timestamp DEFAULT 0,
  `updated` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

CREATE TABLE IF NOT EXISTS `page` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `type` TINYINT DEFAULT 0 NOT NULL,
  `title` VARCHAR(256) NOT NULL,
  `stub` varchar(256) DEFAULT NULL,
  `content` TEXT DEFAULT NULL,
  `parent` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  `status` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  `template` SMALLINT UNSIGNED,
  `pubdate` timestamp,
  `created` timestamp DEFAULT 0,
  `updated` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
