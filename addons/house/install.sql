
CREATE TABLE IF NOT EXISTS `__PREFIX__building` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `community_code` varchar(50) NOT NULL COMMENT '所属小区编号，建议CM开头',
  `code` varchar(50) NOT NULL COMMENT '栋数编号，建议BD开头',
  `name` varchar(255) NOT NULL COMMENT '栋数名称',
  `house` int(10) NOT NULL COMMENT '总户数',
  `lift` int(10) COMMENT '电梯数',
  `desc` varchar(255) COMMENT '描述',
  `create_time` int(10) unsigned COMMENT '添加时间',
  `update_time` int(10) unsigned COMMENT '修改时间',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `code`(`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='栋数信息表';

CREATE TABLE IF NOT EXISTS `__PREFIX__house` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `community_code` varchar(50) NOT NULL COMMENT '所属小区编号，建议CM开头',
  `building_code` varchar(50) NOT NULL COMMENT '栋数编号，建议BD开头',
  `code` varchar(50) NOT NULL COMMENT '房产编号，建议HS开头',
  `name` varchar(255) NOT NULL COMMENT '房产名称',
  `owner_name` varchar(100) NOT NULL COMMENT '户主姓名',
  `owner_tel` varchar(50) NOT NULL COMMENT '户主联系方式',
  `rooms` int(10) NOT NULL COMMENT '房间数',
  `unit` varchar(50) NOT NULL COMMENT '单元信息',
  `floor` int(10) NOT NULL COMMENT '楼层信息',
  `desc` varchar(255) COMMENT '房产描述',
  `enter_time` int(10) unsigned COMMENT '入住时间',
  PRIMARY KEY (`id`),
  UNIQUE INDEX `code`(`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='房产信息表';