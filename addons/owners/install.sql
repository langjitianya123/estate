
CREATE TABLE IF NOT EXISTS `__PREFIX__member` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `community_code` varchar(50) NOT NULL COMMENT '所属小区编号，建议CM开头',
  `house_code` varchar(50) NOT NULL COMMENT '房产编号，建议HS开头',
  `name` varchar(255) NOT NULL COMMENT '成员姓名',
  `identity_id` varchar(30) COMMENT '身份证号',
  `tel` varchar(50) NOT NULL COMMENT '联系方式',
  `occupation` varchar(255) COMMENT '职业',
  `birth` varchar(20) NOT NULL COMMENT '出生日期',
  `gender` tinyint(1) NOT NULL COMMENT '性别 0 女 1 男',
  `owner_type` tinyint(1) DEFAULT 0 NOT NULL COMMENT '成员类型 1 户主 2 家庭成员，3 租户',
  `photo` text COMMENT '成员照片，拍照上传即可',
  `create_time` int(10) unsigned COMMENT '添加时间',
  `update_time` int(10) unsigned COMMENT '修改时间',
  `remark` varchar(255) COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='小区成员信息表';

CREATE TABLE IF NOT EXISTS `__PREFIX__vehicle` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `member_id` int(11) NOT NULL COMMENT '家庭成员id',
  `name` varchar(255) NOT NULL COMMENT '车辆名称',
  `license_plate` varchar(50) NOT NULL COMMENT '车辆牌照',
  `color` varchar(50) COMMENT '车辆颜色',
  `photo` text COMMENT '车辆照片，拍照上传即可',
  `create_time` int(10) unsigned COMMENT '添加时间',
  `update_time` int(10) unsigned COMMENT '修改时间',
  `remark` varchar(255) COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='车辆信息表';

CREATE TABLE IF NOT EXISTS `__PREFIX__pet` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `member_id` int(11) NOT NULL COMMENT '家庭成员id',
  `name` varchar(255) NOT NULL COMMENT '宠物名称',
  `color` varchar(50) COMMENT '宠物颜色',
  `photo` text COMMENT '宠物照片，拍照上传即可',
  `adopt_time` int(10) unsigned COMMENT '收养时间',
  `create_time` int(10) unsigned COMMENT '添加时间',
  `update_time` int(10) unsigned COMMENT '修改时间',
  `remark` varchar(255) COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='宠物信息表';
