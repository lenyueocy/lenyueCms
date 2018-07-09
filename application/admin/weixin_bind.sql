
SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `admin_weixin_bind`;

CREATE TABLE `admin_weixin_bind` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `appid` varchar(20) NOT NULL DEFAULT '' COMMENT 'appid',
  `appsecret` varchar(32) NOT NULL DEFAULT '' COMMENT 'appsecret',
  `bindtime` int(10) unsigned NOT NULL COMMENT '绑定时间',
  `ip` varchar(30) NOT NULL COMMENT '操作IP',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

