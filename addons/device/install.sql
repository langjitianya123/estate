
CREATE TABLE IF NOT EXISTS `__PREFIX__device` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `community_code` varchar(50) NOT NULL COMMENT '所属小区编号，建议CM开头',
  `code` varchar(50) NOT NULL COMMENT '设备编号，建议DV开头',
  `name` varchar(255) NOT NULL COMMENT '设备名称',
  `brand` varchar(100) NOT NULL COMMENT '品牌',
  `price` decimal(10,2) NOT NULL COMMENT '购买价格（单价）',
  `quantity` int(10) unsigned COMMENT '购买数量',
  `buy_time` int(10) unsigned COMMENT '购买时间',
  `durable_years` int(10) unsigned COMMENT '预计使用年限',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='资产设备信息表';

CREATE TABLE IF NOT EXISTS `__PREFIX__device_maintain` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `device_code` varchar(50) NOT NULL COMMENT '设备编号，建议DV开头',
  `unit` varchar(100) NOT NULL COMMENT '维修单位名称',
  `contacts` varchar(50) NOT NULL COMMENT '维修人名称',
  `contacts_tel` varchar(50) NOT NULL COMMENT '维修人联系方式',
  `remark` varchar(255) COMMENT '备注',
  `last_maintain_time` int(10) unsigned COMMENT '最后一次维护时间',
  `next_maintain_time` int(10) unsigned COMMENT '下次维护时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='资产设备维修记录表';