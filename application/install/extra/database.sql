CREATE TABLE IF NOT EXISTS `{{prefix}}article` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '0',
  `category_id` int(11) NOT NULL,
  `content` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `draft` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `description` text CHARACTER SET utf8 COLLATE utf8_general_ci,
  `password` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `thumb` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `allow_comment` int(1) NOT NULL DEFAULT '1',
  `views` int(11) DEFAULT '0',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `{{prefix}}article` (`id`, `title`, `slug`, `status`, `category_id`, `content`, `draft`, `description`, `password`, `thumb`, `allow_comment`, `views`, `create_time`, `update_time`) VALUES
(1, '你好 KurBlog', 'hello_world', 1, 1, '现在依然很美好，所有的等待都是值得的。', NULL, NULL, NULL, NULL, 1, 0, {{time}}, {{time}});

CREATE TABLE IF NOT EXISTS `{{prefix}}category` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `slug` varchar(50) COLLATE utf8_general_ci DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

INSERT INTO `{{prefix}}category` (`id`, `title`, `slug`) VALUES
(1, '默认分类', 'default');

CREATE TABLE IF NOT EXISTS `{{prefix}}comment` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `type` varchar(10) DEFAULT NULL,
  `value` int(11) DEFAULT NULL,
  `status` int(1) NOT NULL DEFAULT '1',
  `author` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `url` varchar(100) DEFAULT NULL,
  `content` tinytext,
  `time` varchar(11) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;



INSERT INTO `{{prefix}}comment` (`id`, `parent_id`, `type`, `value`, `status`, `author`, `email`, `url`, `content`, `time`, `user_agent`, `ip`) VALUES
(1, 0, 'article', 1, 1, '关关', 'admin@kurblog.com', 'https://www.sinkey.cc', '这是您的网站的第一条评论，非常感谢您使用KurBlog！', {{time}}, '', '0.0.0.0');


CREATE TABLE IF NOT EXISTS `{{prefix}}link` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `description` varchar(300) DEFAULT NULL,
  `image` varchar(300) DEFAULT NULL,
  `weight` int(11) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


INSERT INTO `{{prefix}}link` (`id`, `title`, `url`, `description`, `weight`) VALUES
(1, 'KurBlog', 'http://www.kurblog.com', 'KurBlog官方网站', 1);


CREATE TABLE IF NOT EXISTS `{{prefix}}member` (
  `id` int(11) NOT NULL,
  `username` varchar(20) COLLATE utf8_general_ci NOT NULL,
  `password` varchar(32) COLLATE utf8_general_ci NOT NULL,
  `email` varchar(50) COLLATE utf8_general_ci NOT NULL,
  `create_time` int(11) NOT NULL,
  `update_time` int(11) NOT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


INSERT INTO `{{prefix}}member` (`id`, `username`, `password`, `email`, `create_time`, `update_time`) VALUES
(1, '{{username}}', '{{password}}', '{{email}}', {{time}}, {{time}});


CREATE TABLE IF NOT EXISTS `{{prefix}}menu` (
  `id` int(11) NOT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `title` varchar(100) NOT NULL,
  `weight` int(11) DEFAULT NULL,
  `type` varchar(10) NOT NULL,
  `value` varchar(100) NOT NULL,
  `class` varchar(30) NULL DEFAULT ''
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


INSERT INTO `{{prefix}}menu` (`id`, `parent_id`, `title`, `weight`, `type`, `value`) VALUES
(1, 0, '首页', 1, 'url', '/'),
(2, 0, '关于', 2, 'page', '1'),
(3, 0, '链接', 3, 'page', '2');


CREATE TABLE IF NOT EXISTS `{{prefix}}page` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_general_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8_general_ci DEFAULT NULL,
  `status` int(11) NOT NULL,
  `content` text COLLATE utf8_general_ci,
  `draft` text COLLATE utf8_general_ci,
  `template` varchar(100) COLLATE utf8_general_ci DEFAULT 'Index',
  `allow_comment` int(1) NOT NULL DEFAULT '1',
  `create_time` int(11) DEFAULT NULL,
  `update_time` int(11) DEFAULT NULL
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


INSERT INTO `{{prefix}}page` (`id`, `title`, `slug`, `status`, `content`, `draft`, `template`, `allow_comment`, `create_time`, `update_time`) VALUES
(1, '关于', 'about', 1, '## 关于 我\r\n这里是我的一些介绍，你可以在后台修改，如果你会一些代码的话，也可以为这个页面指定一个页面样式。', '', 'Index', 1, {{time}}, {{time}}),
(2, '链接', 'link', 1, '', '', 'Link', 0, {{time}}, {{time}});



CREATE TABLE IF NOT EXISTS `{{prefix}}setting` (
  `key` varchar(50) NOT NULL,
  `value` varchar(200) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


INSERT INTO `{{prefix}}setting` (`key`, `value`) VALUES
('blog_name', '{{blog_name}}'),
('sub_title', '采用KurBlog创建'),
('siteurl', 'http://www.kurblog.com'),
('admin_email', '{{email}}'),
('icp_num', ''),
('home', '{"type":"article","value":""}'),
('per_page', '6'),
('order_by_time', 'asc'),
('need_review', '0'),
('rewrite', 'slug'),
('description_length', '202'),
('comment_mail', 'true'),
('theme', 'default'),
('smtp_mail', '{{email}}'),
('smtp_server', ''),
('smtp_port', ''),
('smtp_password', ''),
('smtp_ssl', ''),
('seo_index_title', '{blog_name} - {sub_title}'),
('seo_index_keywords', '{blog_name},KurBlog'),
('seo_index_description', '{sub_title}'),
('seo_list_title', '{category_name} - 第{page}页 - {blog_name}'),
('seo_list_keywords', '{category_name}'),
('seo_list_description', '{sub_title}'),
('seo_article_title', '{title} - {blog_name}'),
('seo_article_keywords', '{category_name},{blog_name}'),
('seo_article_description', '{description}'),
('seo_page_title', '{title} - {blog_name}'),
('seo_page_keywords', '{blog_name}'),
('seo_page_description', '{description}');


ALTER TABLE `{{prefix}}article`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `{{prefix}}category`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `{{prefix}}comment`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `{{prefix}}link`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `{{prefix}}member`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `{{prefix}}menu`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `{{prefix}}page`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `{{prefix}}setting`
  ADD PRIMARY KEY (`key`);

ALTER TABLE `{{prefix}}article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;

ALTER TABLE `{{prefix}}category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;

ALTER TABLE `{{prefix}}comment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;

ALTER TABLE `{{prefix}}link`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;

ALTER TABLE `{{prefix}}member`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;

ALTER TABLE `{{prefix}}menu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;

ALTER TABLE `{{prefix}}page`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=3;
