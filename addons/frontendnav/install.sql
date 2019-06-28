
CREATE TABLE IF NOT EXISTS `__PREFIX__front_nav_cate` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '导航分类名称',
  `code` varchar(100) NOT NULL COMMENT '导航分类标识，必须保证唯一性',
  `remark` varchar(255) DEFAULT '' COMMENT '备注',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='前台导航分类表';

CREATE TABLE IF NOT EXISTS `__PREFIX__front_nav` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cate_id` int(11) NOT NULL COMMENT '导航分类id',
  `pid` int(11) NOT NULL COMMENT '父级导航id',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '菜单名称',
  `url` varchar(255) DEFAULT '' COMMENT '链接',
  `target`varchar(50) DEFAULT '_self' COMMENT '打开方式，可选值：_self，_blank',
  `icon` varchar(255) DEFAULT '' COMMENT '图标',
  `status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '状态;1:显示;0:隐藏',
  `sort` int(10) DEFAULT 0 COMMENT '排序',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='前台导航菜单表';