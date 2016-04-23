<?php
/*---------------------------------------------------------
  sNews 1.8.0 (lines are the same as sNew 1.7.1 except last lines)
  EN.php
  English Language File
---------------------------------------------------------*/

// function language 
function load_lang() {

// Editing may begin here

	# SITE LANGUAGE VARIABLES
	$l['home'] = 'Home';
	// default value is used only if "home_SEF" is not set in the database
	// allowed characters are [a-z] [A-Z] [0-9] [-] [_]
	$l['home_sef'] = 'home';
	$l['archive'] = 'Archive';
	$l['contact'] = 'Contact';
	$l['sitemap'] = 'Site Map';

	# categories
	$l['month_names'] = 'January, February, March, April, May, June, July, August, September, October, November, December';
	$l['none_yet'] = 'No content yet...';

	# search
	$l['search_keywords'] = 'Search Keywords';
	$l['search_button'] = 'Search';
	$l['search_results'] = 'Search results';
	$l['charerror'] = 'At least 4 characters are needed to perform the search.';
	$l['noresults'] = 'There are no results for query ';
	$l['resultsfound'] = 'results were found for query';

	#comments
	$l['addcomment'] = 'Write a comment';
	$l['comment'] = 'Comment';
	$l['comment_info'] = 'Comment posted in';
	$l['page'] = 'Page';
	// preposition word used in comments infoline
	$l['on'] = 'on';

	#paginator
	$l['first_page'] = 'First';
	$l['last_page'] = 'Last';
	$l['previous_page'] = 'Previous';
	$l['next_page'] = 'Next';
	$l['name'] = 'Name';

	#comments
	$l['comment_sent'] = 'Your comment has been sent';
	$l['comment_sent_approve'] = 'Your comment is waiting moderation.';
	$l['comment_error'] = 'Your comment was not sent';
	$l['no_comment'] = 'This article hasn\'t been commented yet.';
	$l['no_comments'] = 'No comments at the moment';
	$l['ce_reasons'] = '<strong>Possible reasons:</strong><br />
		<ul>
			<li>A required field is blank.</li>
			<li>The comment is too short.</li>
			<li>You haven\'t entered the right math captcha code.</li>
			<li>Or you have tried to post identical message too quickly on this article.</li>
		</ul>';
	$l['url'] = 'Website URL';
	$l['enable_comments'] = 'Enable Comments (default setting for new articles)';
	$l['freeze_comments'] = 'Freeze All Comments (SiteWide)';
	$l['frozen_comments'] = 'No commenting allowed at this time.';

	#contact
	$l['required'] = '* = required field';
	$l['email'] = 'Email';
	$l['message'] = 'Message';
	$l['math_captcha'] = 'Please enter the correct sum of these two numbers';
	$l['contact_sent'] = 'Thank you, your message has been sent.';
	$l['contact_not_sent'] = 'Your message was not sent';
	$l['message_error'] = '<strong>Possible reasons:</strong> You left name or message field blank, or email address does not exist.';

	#generic links
	$l['backhome'] = 'Back home';
	$l['read_more'] = 'Continue reading';

	#contents error
	$l['article_not_exist'] = 'No content at this time';
	$l['category_not_exist'] = 'Requested category does not exist';
	$l['not_found'] = 'Content not found';
	$l['no_content_for_filter'] = 'No content matching this filter restriction';
	$l['no_category_set'] = 'Items require category to be set.';

	#rss links
	$l['rss_articles'] = 'RSS Articles';
	$l['rss_pages'] = 'RSS Pages';
	$l['rss_comments'] = 'RSS Comments';
	$l['rss_comments_article'] = 'RSS Comments for this article';
	$l['no_rss'] = 'No RSS feeds at the moment';

	# ADMINISTRATION LANGUAGE VARIABLES
	$l['uncategorised'] = 'Uncategorised';
	$l['new_content'] = 'Create New Content';
	$l['home_if_used'] = 'Default Home (if used)';

	#breadcrumbs in admin
	$l['snews_articles'] = 'sNews Articles';
	$l['snews_pages'] = 'sNews Pages';
	$l['snews_categories'] = 'sNews Categories';
	$l['snews_settings'] = 'sNews Settings';
	$l['snews_files'] = 'sNews Files';

	#administration
	$l['administration'] = 'Admin';
	$l['articles'] = 'Articles';
	$l['extra_contents'] = 'Extra Contents';
	$l['pages'] = 'Pages';
	$l['all_pages'] = 'All Pages';

	#basic buttons
	$l['view'] = 'View';
	$l['add_new'] = 'Add new';
	$l['admin_category'] = 'New Category';
	$l['article_new'] = 'New Article';
	$l['extra_new'] = 'New Extra Contents';
	$l['page_new'] = 'New Page';
	$l['edit'] = 'Edit';
	$l['edit_button'] = 'Save'; // Patch #11 - 1.7.1 - added for 3 Save buttons
	$l['update'] = 'Update';
	$l['delete'] = 'Delete';
	$l['save'] = 'Save';
	$l['submit'] = 'Submit';

	#settings
	$l['settings'] = 'Settings';
	$l['site_settings'] = 'Site';
	$l['settings_title'] = 'Site Management Panels'; // Patch #19 - 1.7.1

	#login
	$l['login_status'] = 'Login status';
	$l['login'] = 'Login';
	$l['username'] = 'Username';
	$l['password'] = 'Password';
	$l['login_limit'] = 'User/pass limitations: 4-13 alphanumeric characters only';
	$l['logged_in'] = 'You are Logged In';
	$l['log_out'] = 'Logging out';
	$l['logout'] = 'Logout';

	#categories
	$l['categories'] = 'Categories';
	$l['category'] = 'Category';
	$l['subcategory'] = 'Subcategory of';
	$l['not_sub'] = 'Not a Subcategory';
	$l['show_in_subcats'] = 'Show in Subcategories?';
	$l['add_subcategory'] = 'Add subcategory';
	$l['publish_subcategory'] = 'Publish subcategory';
	$l['appear_category'] = 'Appear only on Category';
	$l['appear_page'] = 'Appear only on Page';
	$l['add_category'] = 'New category';
	$l['category_order'] = 'Category order';
	$l['order_category'] = 'Reorder';
	$l['description'] = 'Description';
	$l['publish_category'] = 'Publish category';
	$l['status'] = 'Status:';
	$l['published'] = 'Published';
	$l['unpublished'] = '<span style="color: #FF0000">Unpublished</span>';
	$l['create_cat'] = '<em>You must create a category before adding articles.</em>';
	$l['no_categories'] = 'No categories at the moment';

	#articles
	$l['article'] = 'Article';
	$l['article_date'] = 'Article date (enter a higher date for future posting)';
	$l['preview'] = 'Preview';
	$l['no_articles'] = 'No articles at the moment';
	$l['show_on_home'] = 'Show on Home page';
	$l['filter'] = 'Filter by:';

	#customize article
	$l['customize'] = 'Customize';
	$l['display_title'] = 'Display title';
	$l['display_info'] = 'Display info line (read more/ comments/ date)';
	$l['server_time'] = 'Time on Server';
	$l['future_posting'] = '<span style="color: #FF9900;">Future posting</span>';
	$l['publish_date'] = 'Publish Date';
	$l['day'] = 'Day';
	$l['month'] = 'Month';
	$l['year'] = 'Year';
	$l['hour'] = 'Hour';
	$l['minute'] = 'Minute';
	$l['publish_article'] = 'Publish Now';
	$l['operation_completed'] = 'Operation completed successfully!';
	$l['deleted_success'] = 'Succesfully deleted';

	#files
	$l['files'] = 'Files';
	$l['upload'] = 'Upload';
	$l['uploadto'] = 'Upload file to:';
	$l['uploadfrom'] = 'Upload file from:';
	$l['view_files'] = 'View files in';
	$l['file_error'] = 'File could not be copied!';
	$l['deleted'] = 'File deleted!';
	#comments
	$l['comments'] = 'Comments';
	$l['enable_commenting'] = 'Enable comments';
	$l['edit_comment'] = 'Edit comment';
	$l['freeze_comments'] = 'Freeze comments';
	$l['enable'] = 'Enable';
	$l['approved'] = 'Approved';
	$l['enabled'] = 'Enabled';
	$l['disabled'] = 'Disabled';
	$l['unapproved'] = 'Unapproved comments';
	$l['wait_approval'] = 'comments waiting approval';

	#article structure
	$l['title'] = 'Title';
	$l['sef_title'] = 'Search engine friendly title (will be used as link to the article)';
	$l['sef_title_cat'] = 'Search engine friendly title (will be used as link to the category)';
	$l['text'] = 'Text';
	$l['position'] = 'Position';
	$l['display_page'] = 'Page';
	$l['center'] = 'Center';
	$l['contents'] = 'Contents';
	$l['side'] = 'Extra Contents';
	$l['advanced'] = 'Advanced Settings';
	$l['enable_extras'] = 'Enable Multiple Extra Options';
	$l['extra_title'] = 'Extra group title (used in index.php to display the group - eg extra(\'extra\') )';
	$l['define_extra'] = 'Appear under Extra Grouping:';
	$l['page_only'] = 'None - Page Only';
	$l['groupings'] = 'Extra Grouping';
	$l['group_not_exist'] = 'Extra Grouping does not exist';
	$l['add_groupings'] = 'New Extra Group';
	$l['file_extensions'] = 'Allowed file extensions for includes (Separated by comma)';
	$l['allowed_files'] = 'Allowed file extensions for Uploads (Separated by comma)';
	$l['allowed_images'] = 'Allowed image extensions for Uploads (Separated by comma)';

	#errors
	//Database error message
	$l['dberror'] = '<strong>There was an error while connecting to the database.</strong>
		<br /> Check your database settings.';
	//Database table error message
	$l['db_tables_error'] = '<strong>Your database table "prefix" is incorrect OR your database tables have not been created.</strong>
		<br /> Check your database "prefix" setting OR create your database tables (see <a href="'._SITE.'readme.html">readme.html</a>).';
	$l['error_404'] = 'The content you requested could not be found and may not exist.<br />Please choose from content listed below or return to the previous page.'; // Patch #18 - 1.7.1 - revised message
	$l['error_not_logged_in'] = 'You are not permitted to do that until you are logged in.';
	$l['admin_error'] = 'Error';
	$l['back'] = 'Back';
	$l['err_TitleEmpty'] = 'The Title cannot be empty.';
	$l['err_TitleExists'] = 'The Title already exists.';
	$l['err_SEFEmpty'] = 'The SEF Title cannot be empty.';
	$l['err_SEFExists'] = 'The SEF Title already exists.';
	$l['err_SEFIllegal'] = 'The SEF title you entered contains illegal characters.<br />
		You can only use <strong>a-z 0-9_-</strong><br />A new SEF url has been selected from the title. Please check it.';
	$l['errNote'] = '<br /><strong>Be careful:</strong>
		Due to the fact that when something goes wrong most posting options are lost, please check them before posting again.';
	$l['warning'] = '<span style="color: #FF0000; font-weight: 700;">Warning!</span>';
	$l['empty_cat'] = 'Delete Contents';
	$l['warn_catnotempty'] = 'The Category you are attempting to delete is not empty!<br />
		You can either click <strong>"'.$l['administration'].'"</strong> to manually move items associated with this category<br />
		OR click <strong>"'.$l['empty_cat'].'"</strong> to delete <span style="color: #FF0000; font-weight: 700;">ALL</span>
		sub categories, articles, extra content and comments associated with this category.<br /><br />
		<span style="color: #FF0000; font-weight: 700;">Deleted data CAN NOT be recovered ..... you\'ve been warned</span><br />';
	$l['extra_error_cp'] = 'Category option was not set to Page';
	$l['extra_error_selection'] = 'There was not a category or page selection';

	#settings form
	$l['create_new'] = 'Do you want to create new content?';
	$l['none'] = 'None';
	$l['change_up'] = 'Change Username and Password';
	$l['newer_top'] = 'Newer on top';
	$l['newer_bottom'] = 'Newer on bottom';
	$l['err_Login'] = 'Wrong username and/or password and/or sum entered.';
	$l['pass_mismatch'] = 'Passwords are outside length limit or do not match';
	$l['a_username'] = 'Username';
	$l['a_password'] = 'Password';
	$l['a_password2'] = 'Repeat password';
	$l['a_display_page'] = 'Use Page as Home Page';
	$l['a_display_new_on_home'] = 'Display new Articles on home';
	$l['a_display_pagination'] = 'Display Pagination on articles';
	$l['a_website_title'] = 'Website Title';
	$l['a_home_sef'] = 'Home SEF (used as link to <em>Home</em>)';
	$l['a_website_email'] = 'Email';
	$l['a_description'] = 'Default description META Tag (for search engines)';
	$l['a_keywords'] = 'Default keywords META Tag (keywords separated by comma)';
	$l['a_contact_info'] = 'Contact info';
	$l['a_contact_subject'] = 'Contact Form Subject';
	$l['a_word_filter_file'] = 'Badwords filter file';
	$l['a_word_filter_change'] = 'Badwords replacement word';
	$l['a_word_filter_enable'] = 'Enable Badwords filter';
	$l['error_file_name'] = '<br /><span style="color: #FF0000; font-weight: 700;">Include Error: Forbidden file name</span><br />';
	$l['error_file_exists'] = '<br /><span style="color: #FF0000; font-weight: 700;">Include Error: File doesn\'t exists</span><br />';
	$l['a_openclose'] = 'Expand or Collapse - ';
	$l['a_num_categories'] = 'Display number of articles next to a category';
	$l['charset'] = 'Default charset';
	$l['a_time_settings'] = 'Time & Locale Info';
	$l['a_date_format'] = 'Date Format';
	$l['a_comments_order'] = 'Comments Order';
	$l['a_comment_limit'] = 'Comment results per page';
	$l['a_show_category_name'] = 'Show category name in new posts listing';
	$l['comment_repost_timer'] = 'Comment repost timer - Delay before user can post on same article';
	$l['a_rss_limit'] = 'RSS Articles Limit';
	$l['a_approve_comments'] = 'Approve comments before publishing';
	$l['a_article_limit'] = 'Articles per page limit';
	$l['a_language'] = 'sNews Language';
	$l['description_meta'] = 'Description META Tag (for search engines)';
	$l['keywords_meta'] = 'Keywords META Tag (keywords separated by comma)';
	$l['see'] = 'See:';
	$l['all'] = 'All';

	#formatting buttons:
  	$l['formatting'] = 'Formatting';
  	$l['insert'] = 'Insert';
  	$l['strong'] = 'Bold';
  	$l['strong_value'] = 'B';
  	$l['em'] = 'Italics';
  	$l['em_value'] = 'I';
  	$l['underline'] = 'Underline';
  	$l['underline_value'] = 'U';
  	$l['del'] = 'Strike';
  	$l['del_value'] = 'S';
  	$l['p'] = 'Paragraph';
  	// no need to translate
  	$l['p_value'] = '&para;';
  	$l['br'] = 'Line Break';
  	$l['br_value'] = 'Line Break';
  	$l['intro'] = 'Introduction Break';
  	$l['intro_value'] = 'Introduction Break';
 	$l['img'] = 'Insert Image';
  	$l['img_value'] = 'Image';
  	$l['link'] = 'Insert Link';
  	$l['link_value'] = 'Link';
  	$l['include'] = 'Insert File';
  	$l['include_value'] = 'File';
  	$l['func'] = 'Insert PHP Function';
  	$l['func_value'] = 'Function';

  	#javascript
	$l['function']='Function Name - no brackets.';
	$l['parameters']='Parameters - if more than one required, separate with a comma.
		Do not use quotes for empty parameters.';

  	#js alert prompts
  	$l['js_func1'] = 'Function Name - do not use brackets.';
  	$l['js_func2'] = "Parameters - if more than one required, separate with a comma (no spaces). Do not use quotes for empty parameters, just empty commas.";
  	$l['js_file'] = 'Enter File URL';
  	$l['js_image1'] = 'Enter Image URL';
  	$l['js_image2'] = 'Enter Image Alt';
  	$l['js_link1'] = 'Enter Link URL';
  	$l['js_link2'] = 'Enter Link Title';
  	$l['js_delete1'] = 'Last Warning: Deleted data CAN NOT be recovered!';
  	$l['warn_cat_last'] = 'Last Warning: Deleted data CAN NOT be recovered!';
	$l['warning_delete'] = 'Are you sure you want to delete this?';
	$l['image_url'] = 'Enter Image URL';
	$l['image_alt'] = 'Enter Image Alt';
	$l['file_url'] = 'Enter File URL';
	$l['link_url'] = 'Enter Link URL';
	$l['link_title'] = 'Enter Link Title';
	$l['js_delete2'] = 'Are you sure you want to delete this?';

  	#comment mailing
  	$l['a_mail_on_comments'] = 'Send posted comments to your e-mail? <br />
  		<i>Note: If you\'re using \'Approve comments before publishing\', a notification message will be sent to you.</i>';
  	$l['subject_a'] = 'A new comment needs to be approved';
  	$l['subject_b'] = 'A new comment was posted into your site';
  	$l['approved_text'] = 'You need to approve a new comment posted into your site. Here\'s a copy: ';
  	$l['not_waiting_approved'] = 'A new comment was posted into your site. Here\'s a copy:';
  	$l['from'] = 'From: ';

  	#order contents
  	$l['order_content'] = 'Order Content';
  	$l['up'] = 'Up';
  	$l['down'] = 'Down';
  	$l['hide'] = 'Hide';
  	$l['show'] = 'Show';
  	
  	// MISS LINES - snews 1.7.1
  	$l['please_wait'] = 'Please wait';
  	$l['admin_article'] = 'Admin article';
  	
  	// NEW LINES sNews1.80
  	$l['mail_nexists'] = 'Your server does not support mail function.';

return $l;
}
?>