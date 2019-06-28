

CREATE TABLE IF NOT EXISTS `__PREFIX__page` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `category_id` int(10) NOT NULL DEFAULT '0' COMMENT '分类ID',
  `title` varchar(50) NOT NULL DEFAULT '' COMMENT '标题',
  `keywords` varchar(255) NOT NULL DEFAULT '' COMMENT '关键字',
  `flag` set('hot','index','recommend') NOT NULL DEFAULT '' COMMENT '标志',
  `image` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  `content` text NOT NULL COMMENT '内容',
  `icon` varchar(50) NOT NULL DEFAULT '' COMMENT '图标',
  `views` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '点击',
  `comments` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '评论',
  `createtime` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updatetime` int(10) UNSIGNED NOT NULL DEFAULT '0' COMMENT '更新时间',
  `weigh` int(10) NOT NULL DEFAULT '0' COMMENT '权重',
  `status` varchar(30) NOT NULL DEFAULT '' COMMENT '状态',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='单页表' ROW_FORMAT=COMPACT;

BEGIN;
INSERT INTO `__PREFIX__page` (`id`, `category_id`, `title`, `keywords`, `flag`, `image`, `content`, `icon`, `views`, `comments`, `createtime`, `updatetime`, `weigh`, `status`) VALUES
(1, 4, 'test', 'test', 'recommend', '/assets/img/qrcode.png', '<p>test</p>', '', 0, 0, 1499769482, 1499769482, 0, 'normal');
COMMIT;