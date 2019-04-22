
SET SQL_MODE='NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `user_account`;
CREATE TABLE `user_account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `status` int(1) DEFAULT NULL COMMENT 'çŠ¶æ€',
  `low` int(255) DEFAULT NULL COMMENT 'è´¦å·ä½Žçº§',
  `high` int(255) DEFAULT NULL COMMENT 'è´¦å·é«˜çº§',
  `middle` int(255) DEFAULT NULL COMMENT 'ä¸­é—´çº§',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

