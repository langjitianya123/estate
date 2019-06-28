
CREATE TABLE IF NOT EXISTS `__PREFIX__duty` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `community_code` varchar(50) NOT NULL COMMENT '所属小区编号，建议CM开头',
  `name` varchar(255) NOT NULL COMMENT '值班人名称，多个值班人用英文逗号隔开',
  `start_time` int(10) unsigned COMMENT '值班开始时间',
  `end_time` int(10) unsigned COMMENT '值班结束时间',
  `remark` varchar(255) COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='物业员工值班信息表';