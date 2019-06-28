
CREATE TABLE IF NOT EXISTS `__PREFIX__activity` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `community_code` varchar(50) NOT NULL COMMENT '所属小区编号，建议CM开头',
  `title` varchar(255) NOT NULL COMMENT '活动标题',
  `content` text COMMENT '活动内容',
  `place` varchar(255) NOT NULL COMMENT '活动地点',
  `begin_time` int(10) unsigned COMMENT '活动开始时间',
  `end_time` int(10) unsigned COMMENT '活动截止时间',
  `sponsor_unit` varchar(255) NOT NULL COMMENT '举办单位',
  `status` tinyint(1) unsigned DEFAULT 0 COMMENT '状态 0 无效 1 有效',
  `create_time` int(10) unsigned COMMENT '添加时间',
  `update_time` int(10) unsigned COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='小区活动信息表';

CREATE TABLE IF NOT EXISTS `__PREFIX__repair` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `community_code` varchar(50) NOT NULL COMMENT '所属小区编号，建议CM开头',
  `member_id` int(11) NOT NULL COMMENT '报修成员id',
  `device_name` varchar(255) NOT NULL COMMENT '报修设备名称',
  `desc` text COMMENT '报修描述',
  `status` tinyint(1) unsigned DEFAULT 0 COMMENT '状态 0 待受理 1 已受理 2 已维修',
  `create_time` int(10) unsigned COMMENT '添加时间',
  `update_time` int(10) unsigned COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='业主报修信息表';

CREATE TABLE IF NOT EXISTS `__PREFIX__complain` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `community_code` varchar(50) NOT NULL COMMENT '所属小区编号，建议CM开头',
  `member_id` int(11) NOT NULL COMMENT '投诉成员id',
  `title` varchar(255) NOT NULL COMMENT '投诉名称',
  `reason` text COMMENT '投诉事由',
  `is_anonymity` tinyint(1) unsigned DEFAULT 1 COMMENT '是否匿名 0 不匿名 1 匿名',
  `create_time` int(10) unsigned COMMENT '添加时间',
  `update_time` int(10) unsigned COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='业主投诉信息表';

CREATE TABLE IF NOT EXISTS `__PREFIX__mailbox` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `community_code` varchar(50) NOT NULL COMMENT '所属小区编号，建议CM开头',
  `title` varchar(255) NOT NULL COMMENT '信件标题',
  `content` text COMMENT '信件内容',
  `member_id` int(11) NOT NULL COMMENT '成员id',
  `status` tinyint(1) unsigned DEFAULT 0 COMMENT '状态 0 未读 1 已读',
  `create_time` int(10) unsigned COMMENT '添加时间',
  `update_time` int(10) unsigned COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='业主信箱信息表';