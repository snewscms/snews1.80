--
--  Use this file to create the tables in a new sNews 1.80 database only.
--  Last Updated: April 25, 2016
-- -----------------------------------------------------------------------------

DROP TABLE IF EXISTS articles;
-- ---- Create Articles Table:
	CREATE TABLE `articles` (
	`id` int(11) primary key auto_increment,
	`title` varchar(100) default NULL,
	`seftitle` varchar(100) default NULL,
	`text` longtext,
	`date` datetime default NULL,
	`category` int(8) NOT NULL default '0',
	`position` int(6) default NULL,
	`extraid` varchar(8) default NULL,
	`page_extra` varchar(8) default NULL,
	`displaytitle` char(3) NOT NULL default 'YES',
	`displayinfo` char(3) NOT NULL default 'YES',
	`commentable` varchar(5) NOT NULL default '',
	`published` int(3) NOT NULL default '1',
	`description_meta` varchar(255) default NULL,
	`keywords_meta` varchar(255) default NULL,
	`show_on_home` enum('YES','NO') default 'YES',
	`show_in_subcats` enum('YES','NO') default 'NO',
	`artorder` smallint(6) NOT NULL default '0',
	`visible` varchar(6) default 'YES',
	`default_page` varchar(6) default 'NO',
	KEY show_on_home (show_on_home)
	);

-- ---- Insert data in Articles table:
INSERT INTO `articles` VALUES (1, 'Welcome to sNews 1.8', 'welcome-to-snews-18', '<p>If you\'re seeing this article, you have installed <strong>sNews 1.8</strong> and it is connected to the database.</p><p>It is <strong>strongly</strong> suggested that you <a href="login/" title="Login">login</a> right away, then go to the page <em>Settings</em>. At the bottom of that page you\'ll find <em>Change Username and Password</em>. <strong>Do it</strong> and make the password hard to guess.</p><p>After doing that, feel free to delete this article and start building your site.</p>[break]<p>If you stumble along the way, check the <a href="http://snewscms.com/faq/faq/" title="Troubleshooting and FAQ">Troubleshooting and FAQ</a> page.  If you\'re still having trouble search the <a href="http://snewscms.com/forum/" title="sNews CMS Forum">sNews CMS Forum</a> before posting your question, it may already be answered.  Still lost? We\'ll be there to assist you in any way we can.</p><p>Thank you for choosing sNews.  We hope you enjoy it as much as we do.</p>', NOW(), 1, 1, '', '', 'YES', 'YES', 'NO', 1, '', '', 'YES', 'NO', '1', 'YES', 'NO');

DROP TABLE IF EXISTS categories;
-- ---- Create Categories table:
        CREATE TABLE `categories` (
	`id` int(8) primary key auto_increment,
 	`name` varchar(100) NOT NULL,
 	`seftitle` varchar(100) NOT NULL,
 	`description` varchar(255) NOT NULL,
 	`published` varchar(4) NOT NULL default 'YES',
 	`catorder` smallint(6) NOT NULL default '0',
 	`subcat` int(8) NOT NULL default '0'
	);

-- ---- Insert data in Categories table:
	INSERT INTO `categories` VALUES ('1', 'Uncategorized', 'uncategorized', '', 'YES', '1', '0');

DROP TABLE IF EXISTS comments;
-- ---- Create Comments table:
	CREATE TABLE `comments` (
 	`id` int(11) primary key auto_increment,
 	`articleid` int(11) default '0',
 	`name` varchar(50),
 	`url` varchar(100) NOT NULL,
 	`comment` text,
 	`time` datetime NOT NULL default '0000-00-00 00:00:00',
 	`approved` varchar(5) NOT NULL default 'True',
 	KEY articleid (articleid)
	);

DROP TABLE IF EXISTS extras;
-- ---- Create Extras table:
	CREATE TABLE `extras` (
	`id` int(8) primary key auto_increment,
 	`name` varchar(40) NOT NULL,
	`seftitle` varchar(100) default NULL,
	`description` varchar(100) NOT NULL
	);

-- ---- Insert date in Extras table:
	INSERT INTO `extras` VALUES (1, 'Extra', 'extra', 'The default extra');

DROP TABLE IF EXISTS settings;
-- ---- Create Settings table:
	CREATE TABLE `settings` (
 	`id` int(8) primary key auto_increment,
 	`name` varchar(20) NOT NULL,
 	`value` varchar(255) NOT NULL
	);

-- ---- Insert data in Settings Table:
	INSERT INTO `settings` VALUES (1, 'website_title', 'sNews 1.8');
	INSERT INTO `settings` VALUES (2, 'home_sef', 'home');
	INSERT INTO `settings` VALUES (3, 'website_description', 'sNews CMS');
	INSERT INTO `settings` VALUES (4, 'website_keywords', 'new, site, snews');
	INSERT INTO `settings` VALUES (5, 'website_email', 'info@mydomain.com');
	INSERT INTO `settings` VALUES (6, 'contact_subject', 'Contact Form');
	INSERT INTO `settings` VALUES (7, 'language', 'EN');
	INSERT INTO `settings` VALUES (8, 'charset', 'UTF-8');
	INSERT INTO `settings` VALUES (9, 'date_format', 'd.m.Y. H:i');
	INSERT INTO `settings` VALUES (10, 'article_limit', '3');
	INSERT INTO `settings` VALUES (11, 'rss_limit', '5');
	INSERT INTO `settings` VALUES (12, 'display_page', '0');
	INSERT INTO `settings` VALUES (13, 'display_new_on_home', '');
	INSERT INTO `settings` VALUES (14, 'display_pagination', '');
	INSERT INTO `settings` VALUES (15, 'num_categories', 'on');
	INSERT INTO `settings` VALUES (16, 'show_cat_names', '');
	INSERT INTO `settings` VALUES (17, 'approve_comments', '');
	INSERT INTO `settings` VALUES (18, 'mail_on_comments', '');
	INSERT INTO `settings` VALUES (19, 'comment_repost_timer', '20');
	INSERT INTO `settings` VALUES (20, 'comments_order', 'ASC');
	INSERT INTO `settings` VALUES (21, 'comment_limit', '30');
	INSERT INTO `settings` VALUES (22, 'enable_comments', 'NO');
	INSERT INTO `settings` VALUES (23, 'freeze_comments', 'NO');
	INSERT INTO `settings` VALUES (24, 'word_filter_enable', '');
	INSERT INTO `settings` VALUES (25, 'word_filter_file', '');
	INSERT INTO `settings` VALUES (26, 'word_filter_change', '');
	INSERT INTO `settings` VALUES (27, 'username', '098f6bcd4621d373cade4e832627b4f6');
	INSERT INTO `settings` VALUES (28, 'password', '098f6bcd4621d373cade4e832627b4f6');
	INSERT INTO `settings` VALUES (29, 'enable_extras', 'NO');
	INSERT INTO `settings` VALUES (30, 'last_date', NOW());
	INSERT INTO `settings` VALUES (31, 'file_extensions', 'phps,php,txt,inc,htm,html');
	INSERT INTO `settings` VALUES (32, 'allowed_files', 'php,htm,html,txt,inc,css,js,swf');
	INSERT INTO `settings` VALUES (33, 'allowed_images', 'gif,jpg,jpeg,png');
