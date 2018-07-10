
SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `lenyue_weixin_user`;

CREATE TABLE `lenyue_weixin_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `openid` varchar(100) NOT NULL DEFAULT '' COMMENT 'openid',
  `nickname` varchar(32) NOT NULL DEFAULT '' COMMENT '微信昵称',
  `sex` int(10) unsigned NOT NULL COMMENT '性别',
  `country` varchar(30) NOT NULL COMMENT '国家',
  `province` varchar(30) NOT NULL COMMENT '省份',
  `city` varchar(30) NOT NULL COMMENT '地区',
  `headimgurl` varchar(200) NOT NULL COMMENT '微信头像',
  `createtime` int(100) NOT NULL COMMENT '创建时间',
  `updatetime` int(100) NOT NULL COMMENT '更新时间',
  `ip` VARCHAR(100) NOT NULL COMMENT 'ip',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

