
SET FOREIGN_KEY_CHECKS=0;

DROP TABLE IF EXISTS `lenyue_weixin_scores`;

CREATE TABLE `lenyue_weixin_scores` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(50) NOT NULL DEFAULT 0 COMMENT '用户id',
  `openid` varchar(100) NOT NULL DEFAULT '' COMMENT 'openid',
  `scores` int(100) NOT NULL COMMENT '历史最高分',
  `money` int(100) NOT NULL COMMENT '抽奖累计金额',
  `updatetime` int(100) NOT NULL COMMENT '更新时间',
  `ip` VARCHAR(100) NOT NULL COMMENT 'ip',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

