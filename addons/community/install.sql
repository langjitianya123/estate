
CREATE TABLE IF NOT EXISTS `__PREFIX__community` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `code` varchar(50) NOT NULL COMMENT '小区编号，建议CM开头',
  `name` varchar(255) NOT NULL COMMENT '小区名称',
  `introduction` text COMMENT '简介',
  `thumb` varchar(255) DEFAULT '' COMMENT '缩略图',
  `address` varchar(255) NOT NULL COMMENT '坐落地址',
  `area` decimal(15,2) NOT NULL COMMENT '占地面积，单位：平米',
  `developer` varchar(255) NOT NULL COMMENT '开发商名称',
  `estate` varchar(255) NOT NULL COMMENT '物业公司名称',
  `greening_rate` decimal(10,2) NOT NULL COMMENT '绿化率，单位：百分比',
  `total_building` int(11) NOT NULL COMMENT '总栋数',
  `total_owner` int(11) NOT NULL COMMENT '总户数',
  `create_time` int(10) unsigned COMMENT '创建时间',
  `update_time` int(10) unsigned COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `code`(`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='小区信息表';

CREATE TABLE IF NOT EXISTS `__PREFIX__community_admin` (
  `community_code` varchar(50) NOT NULL COMMENT '小区编号，建议CM开头',
  `admin_id` int(11) unsigned NOT NULL COMMENT '管理员id',
  UNIQUE KEY `cm_admin_id` (`community_code`,`admin_id`),
  KEY `community_code` (`community_code`),
  KEY `admin_id` (`admin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='小区管理员关系表';
