
CREATE TABLE IF NOT EXISTS `__PREFIX__parking_space` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `community_code` varchar(50) NOT NULL COMMENT '所属小区编号，建议CM开头',
  `code` varchar(50) NOT NULL COMMENT '车位编号，建议PK开头',
  `name` varchar(255) NOT NULL COMMENT '车位名称',
  `status` tinyint(1) unsigned DEFAULT 0 COMMENT '状态 0 闲置中 1 使用中',
  `create_time` int(10) unsigned COMMENT '创建时间',
  `update_time` int(10) unsigned COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `code`(`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='停车位基本信息表';

CREATE TABLE IF NOT EXISTS `__PREFIX__parking_space_use` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `community_code` varchar(50) NOT NULL COMMENT '所属小区编号，建议CM开头',
  `pk_code` varchar(50) NOT NULL COMMENT '车位编号，建议PK开头',
  `license_plate` varchar(50) NOT NULL COMMENT '车辆牌照',
  `owner` varchar(100) NOT NULL COMMENT '车辆所有人',
  `tel` varchar(50) NOT NULL COMMENT '联系电话',
  `begin_time` int(10) unsigned COMMENT '开始时间',
  `end_time` int(10) unsigned COMMENT '截止时间',
  `type` tinyint(1) unsigned NOT NULL COMMENT '使用性质 1 租 2 买',
  `cost` decimal(10,2) NOT NULL COMMENT '费用',
  `create_time` int(10) unsigned COMMENT '创建时间',
  `update_time` int(10) unsigned COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='停车位使用记录表';