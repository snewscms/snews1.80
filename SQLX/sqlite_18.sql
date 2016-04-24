
-- ---- Create Articles Table:
CREATE TABLE [articles] (
	[id] INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
	[title] VARCHAR(100) default NULL,
	[seftitle] VARCHAR(100) default NULL,
	[text] text,
	[date] datetime default NULL,
	[category] INTEGER NOT NULL default 0,
	[position] INTEGER default NULL,
	[extraid] VARCHAR(8) default NULL,
	[page_extra] VARCHAR(8) default NULL,
	[displaytitle] char(3) NOT NULL default 'YES',
	[displayinfo] char(3) NOT NULL default 'YES',
	[commentable] VARCHAR(5) NOT NULL default '',
	[published] TINYINT NOT NULL default 1,
	[description_meta] VARCHAR(255) default NULL,
	[keywords_meta] VARCHAR(255) default NULL,
	[show_on_home] VARCHAR(3) default 'YES',
	[show_in_subcats] VARCHAR(3) default 'NO',
	[artorder] INTEGER NOT NULL default '0',
	[visible] VARCHAR(6) default 'YES',
	[default_page] VARCHAR(6) default 'NO'
	);
-- ---- Insert data in Articles table:
INSERT INTO [articles] VALUES (1, 'Welcome to sNews 1.80', 'welcome-to-snews-18', 
'<p>If you are seeing this article, you have installed <strong>sNews 1.80</strong> and it is connected to the database (sQlite 3).</p><p>It is <strong>strongly</strong> suggested that you <a href="login/" title="Login">login</a> right away, then go to the page <em>Settings</em>. At the bottom of that page you will find <em>Change Username and Password</em>. <strong>Do it</strong> and make the password hard to guess.</p>
<p>It is very important you rename your database to a filename hard to guess and change inside snews.php your filename (dbpath)</p>
<p>After doing that, feel free to delete this article and start building your site.</p>[break]<p>If you stumble along the way, check the <a href="http://snewscms.com/faq/faq/" title="Troubleshooting and FAQ">Troubleshooting and FAQ</a> page.  If you are still having trouble search the <a href="http://snewscms.com/forum/" title="sNews CMS Forum">sNews CMS Forum</a> before posting your question, it may already be answered.  Still lost? We will be there to assist you in any way we can.</p><p>Thank you for choosing sNews.  We hope you enjoy it as much as we do.</p>', 
'2016-04-30 12:00:00', 1, 1, '', '', 'YES', 'YES', 'NO', 1, '', '', 'YES', 'NO', '1', 'YES', 'NO');


-- ---- Create Categories table:
CREATE TABLE  [categories] (
	[id] INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
 	[name] VARCHAR(100) NOT NULL,
 	[seftitle] VARCHAR(100) NOT NULL,
 	[description] VARCHAR(255) NOT NULL,
 	[published] VARCHAR(4) NOT NULL default 'YES',
 	[catorder] INTEGER NOT NULL default 0,
 	[subcat] TINYINT NOT NULL default 0
	);

-- ---- Insert data in Categories table:
	INSERT INTO  [categories] VALUES ('1', 'Uncategorized', 'uncategorized', '', 'YES', '1', '0');





-- ---- Create Comments table:
CREATE TABLE [comments] (
 	[id] INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
 	[articleid] INTEGER default 0,
 	[name] VARCHAR(50),
 	[url] VARCHAR(100) NOT NULL,
 	[comment] TEXT,
 	[time] datetime NOT NULL default '0000-00-00 00:00:00',
 	[approved] VARCHAR(5) NOT NULL default 'True'
);

-- ---- Create Extras table:
CREATE TABLE [extras] (
	[id] INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
 	[name] VARCHAR(40) NOT NULL,
	[seftitle] VARCHAR(100) default NULL,
	[description] VARCHAR(100) NOT NULL
);

INSERT INTO [extras] VALUES (1, 'Extra', 'extra', 'The default extra');



-- ---- Create Settings table:
CREATE TABLE [settings] (
 	[id] INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
 	[name] VARCHAR(20) NOT NULL,
 	[value] VARCHAR(255) NOT NULL
);

-- ---- Insert data in Settings Table:
	INSERT INTO  [settings] VALUES (1, 'website_title', 'sNews 1.80');
	INSERT INTO [settings] VALUES (2, 'home_sef', 'home');
	INSERT INTO [settings] VALUES (3, 'website_description', 'sNews CMS');
	INSERT INTO [settings] VALUES (4, 'website_keywords', 'new, site, snews');
	INSERT INTO [settings] VALUES (5, 'website_email', 'info@mydomain.com');
	INSERT INTO [settings] VALUES (6, 'contact_subject', 'Contact Form');
	INSERT INTO [settings] VALUES (7, 'language', 'EN');
	INSERT INTO [settings] VALUES (8, 'charset', 'UTF-8');
	INSERT INTO [settings] VALUES (9, 'date_format', 'd.m.Y. H:i');
	INSERT INTO [settings] VALUES (10, 'article_limit', '3');
	INSERT INTO [settings] VALUES (11, 'rss_limit', '5');
	INSERT INTO [settings] VALUES (12, 'display_page', '0');
	INSERT INTO [settings] VALUES (13, 'display_new_on_home', '');
	INSERT INTO [settings] VALUES (14, 'display_pagination', '');
	INSERT INTO [settings] VALUES (15, 'num_categories', 'on');
	INSERT INTO [settings] VALUES (16, 'show_cat_names', '');
	INSERT INTO [settings] VALUES (17, 'approve_comments', '');
	INSERT INTO [settings] VALUES (18, 'mail_on_comments', '');
	INSERT INTO [settings] VALUES (19, 'comment_repost_timer', '20');
	INSERT INTO [settings] VALUES (20, 'comments_order', 'ASC');
	INSERT INTO [settings] VALUES (21, 'comment_limit', '30');
	INSERT INTO [settings] VALUES (22, 'enable_comments', 'NO');
	INSERT INTO [settings] VALUES (23, 'freeze_comments', 'NO');
	INSERT INTO [settings] VALUES (24, 'word_filter_enable', '');
	INSERT INTO [settings] VALUES (25, 'word_filter_file', '');
	INSERT INTO [settings] VALUES (26, 'word_filter_change', '');
	INSERT INTO [settings] VALUES (27, 'username', '098f6bcd4621d373cade4e832627b4f6');
	INSERT INTO [settings] VALUES (28, 'password', '098f6bcd4621d373cade4e832627b4f6');
	INSERT INTO [settings] VALUES (29, 'enable_extras', 'NO');
	INSERT INTO [settings] VALUES (30, 'last_date', '2016-04-30 12:00:00');
	INSERT INTO [settings] VALUES (31, 'file_extensions', 'phps,php,txt,inc,htm,html');
	INSERT INTO [settings] VALUES (32, 'allowed_files', 'php,htm,html,txt,inc,css,js,swf');
	INSERT INTO [settings] VALUES (33, 'allowed_images', 'gif,jpg,jpeg,png');

