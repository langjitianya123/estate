
CREATE TABLE IF NOT EXISTS `__PREFIX__expenses` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `community_code` varchar(50) NOT NULL COMMENT '所属小区编号，建议CM开头',
  `house_code` varchar(50) NOT NULL COMMENT '缴费人，即房产编号，建议HS开头',
  `project_code` varchar(50) NOT NULL COMMENT '缴费项目编号，建议EP开头',
  `amount_total` decimal(10,2) NOT NULL COMMENT '应收金额',
  `amount_paid` decimal(10,2) NOT NULL COMMENT '实收金额',
  `remark` varchar(255) COMMENT '备注',
  `create_time` int(10) unsigned COMMENT '缴费时间',
  `update_time` int(10) unsigned COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='费用信息表';

CREATE TABLE IF NOT EXISTS `__PREFIX__expenses_project` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `community_code` varchar(50) NOT NULL COMMENT '所属小区编号，建议CM开头',
  `code` varchar(50) NOT NULL COMMENT '项目编号，建议EP开头',
  `name` varchar(255) NOT NULL COMMENT '项目名称',
  `create_time` int(10) unsigned COMMENT '添加时间',
  `update_time` int(10) unsigned COMMENT '修改时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='收费项目信息表';