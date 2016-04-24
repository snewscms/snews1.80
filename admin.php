<?php

// NO HACK PLEASE
$pagename = substr($_SERVER['SCRIPT_NAME'], strripos($_SERVER['SCRIPT_NAME'], '/')+1);
if ($pagename == 'admin.php') {die('Bye bye');}
if (!defined('SECURE_ID') || SECURE_ID != '1234') {die('ACCESS DENIED');}
if (!_ADMIN) {echo( notification(1,l('error_not_logged_in'),'login')); set_error();}

// ADMINISTRATION
function administration() {
	foreach ($_POST as $key) {unset($_POST[$key]);}
	echo '<div class="adminpanel">';
	    echo '<p class="admintitle"><a href="http://snewscms.com/" title="sNews CMS">sNews</a> '.l('administration').'</p>';
	    echo '<p>'.l('categories').': <a href="admin_category/">'.l('add_new').'</a>';
	    $link = ' '.l('divider').' <a href="';
	    $catnum = stats('categories', '');
	    if ($catnum > 0) {echo $link.'snews_categories/">'.l('view').'</a>';}
	    echo '</p><p>'.l('articles').': ';
	    $art_new = $catnum > 0 ? '<a href="article_new/">'.l('add_new').'</a>' : l('create_cat');
	    echo $art_new;
	    if (stats('articles','1') > 0) {
			echo $link.'snews_articles/">'.l('view').'</a>';
	    }
	    echo '</p><p>'.l('pages').': <a href="page_new/">'.l('add_new').'</a>';
	    if (stats('articles','3') > 0) {
			echo $link.'snews_pages/">'.l('view').'</a>';
	    }
	    echo '</p>';
	    if (s('enable_extras') == 'YES') {
	        echo '<p class="admintitle">'.l('extra_contents').'</p>';
			echo '<p>'.l('groupings').': <a href="admin_groupings/">'.l('add_new').'</a>';
			if (stats('extras','') > 0) {
			    echo $link.'groupings/">'.l('view').'</a>';
			}
			echo '</p>';
	    }
	    echo '<p>'.l('extra_contents').': <a href="extra_new/">'.l('add_new').'</a>';
	    if (stats('articles','2') > 0) {
			echo $link.'extra_contents/">'.l('view').'</a>';
	    }
	    echo '</p>';
	    echo '</div>';
	    $query = 'SELECT id, articleid, name FROM '._PRE.'comments'.' WHERE approved != \'True\'';
	    $unapproved = stats('comments', '', 'approved != \'True\'');
	    if ($unapproved > 0) {
			echo '<div class="adminpanel"><p class="admintitle">'.l('comments').'</p>';
			echo '<p><a onclick="toggle(\'sub1\')" style="cursor: pointer;" title="'.l('unapproved').'">
				'.$unapproved.' '.l('wait_approval').'</a></p>';
			echo '<div id="sub1" class="innerpanel" style="display: none;">';
			if ($result = db() -> query($query)) {
				while ($r = dbfetch($result)) {
				    $articleTITLE = retrieve('title', 'articles', 'id', $r['articleid']);
				    echo '<p class="spacelink">'.$r['name'].' (<strong>'.$articleTITLE.'</strong>) '.l('divider').'
					<a href="'._SITE.'?action=editcomment&amp;commentid='.$r['id'].'">'.l('edit').'</a></p>';
				}
			} echo '</div></div>';
	    }
	    echo '<div class="message"><p class="admintitle">'.l('site_settings').'</p>';
	    echo '<p><a href="snews_settings/">'.l('settings').'</a>&nbsp;|&nbsp;
			<a href="snews_files/">'.l('files').'</a></p></div>';
	    echo '<div class="message"><p class="admintitle">'.l('login_status').'</p>';
	    echo '<p><a href="logout/">'.l('logout').'</a></p>';
	echo '</div>';
}

// SETTINGS FORM
function settings() {
	echo '<div class="adminpanel"><p class="admintitle">'.l('settings_title').'</p>';
	echo html_input('form','','','','','','','','','','','','post', '?action=process&amp;task=save_settings','');
	    # Expandable Settings
	    echo '<p><a onclick="toggle(\'sub1\')" style="cursor: pointer;" title="'.l('a_openclose').''.l('settings').'">'.l('settings').'</a></p>';
	    echo '<div id="sub1" style="display: none;">';
		echo html_input('text', 'website_title', 'webtitle', s('website_title'), l('a_website_title'),'','','','','','','','','','');
		echo html_input('text', 'home_sef', 'webSEF', s('home_sef') == '' ? l('home_sef') : s('home_sef'), l('a_home_sef'), '', 
			'onkeypress="return SEFrestrict(event);"','','','','','','','','');
		echo html_input('text', 'website_description', 'wdesc', s('website_description'), l('a_description'),'','','','','','','','','','');
		echo html_input('text', 'website_keywords', 'wkey', s('website_keywords'), l('a_keywords'),'','','','','','','','','','');
	    echo '</div>';
		# Expandable Contact
	    echo '<p><a onclick="toggle(\'sub2\')" style="cursor: pointer;" title="'.l('a_openclose').''.l('a_contact_info').'">'.l('a_contact_info').'</a></p>';
	    echo '<div id="sub2" style="display: none;">';
		echo html_input('text', 'website_email', 'we', s('website_email'), l('a_website_email'),'','','','','','','','','','');
		echo html_input('text', 'contact_subject', 'cs', s('contact_subject'), l('a_contact_subject'),'','','','','','','','','','');
	    echo '</div>';
		# Expandable Time & Locale
	    echo '<p><a onclick="toggle(\'sub3\')" style="cursor: pointer;" title="'.l('a_openclose').''.l('a_time_settings').'">'.l('a_time_settings').'</a></p>';
	    echo '<div id="sub3" style="display: none;">';
		echo html_input('text', 'language', 'lang', s('language') == '' ? 'EN' : s('language'), l('a_language'),'','','','','','','','','','');
		echo html_input('text', 'charset', 'char', s('charset') == '' ? 'UTF-8' : s('charset'), l('charset'),'','','','','','','','','','');
		echo html_input('text', 'date_format', 'dt', s('date_format'), l('a_date_format'),'','','','','','','','','','');
	    echo '</div>';
		# Expandable Contents
	    echo '<p><a onclick="toggle(\'sub4\')" style="cursor: pointer;" title="'.l('a_openclose').''.l('contents').'">'.l('contents').'</a></p>';
	    echo '<div id="sub4" style="display: none;">';
		echo html_input('text', 'article_limit', 'artl', s('article_limit'), l('a_article_limit'),'','','','','','','','','','');
		echo html_input('text', 'rss_limit', 'rssl', s('rss_limit'), l('a_rss_limit'),'','','','','','','','','','');
		echo '<p><label for="dp">'.l('a_display_page').':</label><br /> <select name="display_page" id="dp">';
		echo '<option value="0"'.(s('display_page') == 0 ? ' selected="selected"' : '').'>'.l('none').'</option>';
		$query = 'SELECT id,title FROM '._PRE.'articles'.' WHERE position = 3 ORDER BY id ASC';
		if ($result = db() -> query($query)) {
			while ($r = dbfetch($result)) {
				echo '<option value="'.$r['id'].'"';
			    if (s('display_page') == $r['id']) { echo ' selected="selected"'; }
			    echo '>'.$r['title'].'</option>';
			}
		}
		echo '</select></p>';
		echo html_input('checkbox','display_new_on_home','dnoh','',l('a_display_new_on_home'),'','','','',
			(s('display_new_on_home') == 'on' ? 'ok' : ''),'','','','','');
		echo html_input('checkbox','display_pagination','dpag','',l('a_display_pagination'),'','','','',
			(s('display_pagination') == 'on' ? 'ok' : ''),'','','','','');
		echo html_input('checkbox','num_categories','nc','',l('a_num_categories'),'','','','',(s('num_categories') == 'on' ? 'ok' : ''),'','','','','');
		echo html_input('checkbox','show_cat_names','scn','',l('a_show_category_name'),'','','','',(s('show_cat_names') == 'on' ? 'ok' : ''),'','','','','');
		echo html_input('checkbox','enable_extras','ee','',l('enable_extras'),'','','','',(s('enable_extras') == 'YES' ? 'ok' : ''),'','','','','');
		echo html_input('text','file_ext','fileext',s('file_extensions'),l('file_extensions'),'','','','','','','','','','');
		echo html_input('text','allowed_file','all_file',s('allowed_files'),l('allowed_files'),'','','','','','','','','','');
		echo html_input('text','allowed_img','all_img',s('allowed_images'),l('allowed_images'),'','','','','','','','','','');
	    echo '</div>';              
		# Expandable Comments
	    echo '<p><a onclick="toggle(\'sub5\')" style="cursor: pointer;" title="'.l('a_openclose').''.l('comments').'">'.l('comments').'</a></p>';
	    echo '<div id="sub5" style="display: none;">';
		echo html_input('checkbox','approve_comments','ac','',l('a_approve_comments'),'','','','',(s('approve_comments') == 'on' ? 'ok' : ''),'','','','','');
		echo html_input('text','comment_repost_timer','crt',s('comment_repost_timer'),l('comment_repost_timer'),'','','','','','','','','','');
		echo html_input('checkbox','mail_on_comments','mc','',l('a_mail_on_comments'),'','','','',(s('mail_on_comments') == 'on' ? 'ok' : ''),'','','','','');
		echo html_input('checkbox','enable_comments','ec','',l('enable_comments'),'','','','',(s('enable_comments') == 'YES' ? 'ok' : ''),'','','','','');
		echo html_input('checkbox','freeze_comments','dc','',l('freeze_comments'),'','','','',(s('freeze_comments') == 'YES' ? 'ok' : ''),'','','','','');
		# ORDER
		echo '<p><label for="co">'.l('a_comments_order').':</label><br /><select id="co" name="comments_order">';
		echo '<option value="DESC"'.(s('comments_order') == 'DESC' ? ' selected="selected"' : '').'>'.l('newer_top').'</option>';
		echo '<option value="ASC"'.(s('comments_order') == 'ASC' ? ' selected="selected"' : '').'>'.l('newer_bottom').'</option></select>';
		echo '</p>';
		echo html_input('text','comment_limit','cl',s('comment_limit'),l('a_comment_limit'),'','','','','','','','','','');
		echo html_input('checkbox','word_filter_enable','wfe','',l('a_word_filter_enable'),'','','','',
			(s('word_filter_enable') == 'on' ? 'ok' : ''),'','','','','');
		echo html_input('text','word_filter_file','wff',s('word_filter_file'),l('a_word_filter_file'),'','','','','','','','','','');
		echo html_input('text','word_filter_change','wfc',s('word_filter_change'),l('a_word_filter_change'),'','','','','','','','','','');
	    echo '</div>';
	    echo '<p>';
		# Save Settings button
	    echo html_input('hidden','task','task','save_settings','','','','','','','','','','','');
	    echo html_input('submit','save','save',l('save'),'','button','','','','','','','','','');
	    echo '</p>';
	echo '</form>';
	echo '</div>';
	# Change Password panel
	echo html_input('form','','','','','','','','','','','','post','?action=process&amp;task=changeup','');
	echo '<div class="adminpanel">';
	    echo '<p><a onclick="toggle(\'sub6\')" style="cursor: pointer;" title="'.l('a_openclose').''.l('change_up').'">'.l('change_up').'</a>';
	    echo '<div id="sub6" style="display: none;">';
		echo '<p>'.l('login_limit').'</p>';
		echo html_input('text','uname','uname','',l('a_username'),'','','','','','','','','','');
		echo html_input('password','pass1','pass1','',l('a_password'),'','','','','','','','','','');
		echo html_input('password','pass2','pass2','',l('a_password2'),'','','','','','','','','','');
		echo '<p>';
		# Save Password Change button
		echo html_input('hidden','task','task_login','changeup','','','','','','','','','','','');
		echo html_input('submit','submit_pass','submit_pass',l('save'),'','button','','','','','','','','','');
	    echo '</p></div>';
	echo '</div>';
	echo '</form>';
}

// CATEGORIES - ADMIN LIST
function admin_categories() {
    $add = ' - <a href="admin_category/">'.l('add_new').'</a>';
    $link = '?action=admin_category';
    $tab = 1;
    echo '<div class="adminpanel">';
	echo '<p class="admintitle">'.l('categories').$add.'</p>';
	echo html_input('form', '', 'post', '', '', '', '', '', '', '', '', '', 'post', '?action=process&amp;task=reorder', '');
	echo '<p><input type="hidden" name="order" id="order" value="snews_categories" /></p>';
	$num_cat = stats('categories', '', 'subcat = 0');
	if ($num_cat == 0) {
		echo '<p>'.l('category_not_exist').'</p>';
	} else {
		$query = 'SELECT id, name, description, published, catorder FROM '._PRE.'categories'.' WHERE subcat = 0 ORDER BY catorder,id ASC';
		if ($result = db() -> query($query)) {
			while ($r = dbfetch($result)) {
				$cat_input = '<input type="text" name="cat_'.$r['id'].'" value="'.$r['catorder'].'" size="1" tabindex="'.$tab.'" /> &nbsp;';
				echo '<p>'.$cat_input.'<strong>'.$r['name'].'</strong>
					'.l('divider').' <a href="'._SITE.$link.'&amp;id='.$r['id'].'" title="'.$r['description'].'">'.l('edit').'</a> ';
				echo $r['published'] != 'YES' ? ' '.l('divider').' ['.l('status').' '.l('unpublished').']' : '';
				echo ' '.l('divider').' <a href="'._SITE.$link.'&amp;sub_id='.$r['id'].'" title="'.$r['description'].'">'.l('add_subcategory').'</a></p>';
				
				$subquery = 'SELECT id,name,description,published,catorder FROM '._PRE.'categories'.' WHERE subcat = '.$r['id'].' ORDER BY catorder,id ASC';
				if ($res = db() -> query($subquery)) { $tab2 = 1;
					while ($sub = dbfetch($res)) {
					    $subcat_input = '<input type="text" name="cat_'.$sub['id'].'" value="'.$sub['catorder'].'" size="1" tabindex="'.$tab2.'" /> &nbsp;';
					    echo '<p class="subcat">'.$subcat_input.'<strong>'.$sub['name'].'</strong>'.l('divider').' ';
						echo '<a href="'._SITE.$link.'&amp;id='.$sub['id'].'" title="'.$sub['description'].'">'.l('edit').'</a> ';
					    echo ($sub['published'] != 'YES' ? ' '.l('divider').' ['.l('status').' '.l('unpublished').']' : '');
					    echo '</p>'; $tab2++;
					}
				} $tab++;
		    }
		}
	}
	echo html_input('hidden', 'action', 'process', 'process','','','','','','','','','','','');
	echo html_input('hidden', 'task', 'task', 'reorder','','','','','','','','','','','');
	echo '<p>'.html_input('submit', 'reorder', 'reorder', l('order_content'), '', 'button', '', '', '', '', '', '', '', '', '');
	echo '</p></form>';
    echo '</div>';
}

// CATEGORIES FORM
function form_categories($subcat = 'cat') {
	if (isset($_GET['id']) && is_numeric($_GET['id']) && !is_null($_GET['id'])) {
		$categoryid = $_GET['id'];
		$query = 'SELECT id,name,seftitle,published,description,subcat,catorder FROM '._PRE.'categories'.' WHERE id='.$categoryid;
		if ($result = db() -> query($query)) {$r = dbfetch($result);}
		$qwr = "select name from "._PRE."categories	where id = ".$r['subcat'];
		if ($jresult = db() -> query($qwr)) {
			while ($rr = dbfetch($jresult)) {$name = $rr['name'];}
		}
		$frm_action = _SITE.'?action=process&amp;task=admin_category&amp;id='.$categoryid;
		$frm_add_edit = $r['subcat'] == '0' ? l('edit').' '.l('category') : l('edit').' '.l('subcategory').' '.$name ;
		$frm_name = $r['name'];
		$subcat = $r['subcat'] == 0 ? 'cat' : 'subcat';
		$frm_sef_title = $r['seftitle'];
		$frm_description = $r['description'];
		$frm_publish = $r['published'] == 'YES' ? 'ok' : '';
		$catorder = isset($r['catorder']) ? $r['catorder'] : 0;
		$frm_task = 'edit_category';
		$frm_submit = l('edit_button');
	} else {
		$sub_cat = isset($_GET['sub_id']) ? $_GET['sub_id'] : '0';
		if ($sub_cat !=' cat') {
			$query = 'SELECT name FROM '._PRE.'categories'.' WHERE id = '.$sub_cat;
			if ($result = db() -> query($query)) {
				while ($r = dbfetch($result)) {$name = $r['name'];}
			}
		}
		$frm_action = _SITE.'?action=process&amp;task=admin_category';
		$frm_add_edit = empty($sub_cat) ? l('add_category') : l('add_subcategory').' ('.$name.')';
		$title = isset($_POST['title']) ? cleanSEF($_POST['seftitle']) : '';
		$frm_sef_title = isset($_POST['name']) && $_POST['name'] == '' ? cleanSEF($_POST['name']) : $title;
		$frm_name = '';
		$catorder = 0;
		$frm_description = '';
		$frm_publish = 'ok';
		$frm_task = 'add_category';
		$frm_submit = l('add_category');
	}
	echo html_input('form', '', 'post', '', '', '', '', '', '', '', '', '', 'post', $frm_action, '');
	echo '<div class="adminpanel">';
	echo '<p class="admintitle">'.$frm_add_edit.'</p>';
	echo html_input('text', 'name', 't', $frm_name, l('name'), '', 'onchange="genSEF(this,document.forms[\'post\'].seftitle)"', 'onkeyup="genSEF(this,document.forms[\'post\'].seftitle)"', '', '', '', '', '', '', '');
	echo html_input('text', 'seftitle', 's', $frm_sef_title, l('sef_title_cat'), '', '', '', '', '', '', '', '', '', '');
	echo html_input('text', 'description', 'desc', $frm_description, l('description'), '', '', '', '', '', '', '', '', '', '');
	if (empty($sub_cat) && !empty($categoryid)) {
		echo '<p>'.l('subcategory').': <br />'; category_list($categoryid); echo '</p>';
	}
	$publish = $subcat == 'cat' ? l('publish_category') : l('publish_subcategory');
	echo html_input('checkbox', 'publish', 'pub', 'YES', $publish, '', '', '', '', $frm_publish, '', '', '', '', '');
	echo '</div><p>';
	if (!empty($sub_cat)) {
		echo html_input('hidden', 'subcat', 'subcat', $sub_cat, '', '', '', '', '', '', '', '', '', '', '');
	}
   	echo html_input('hidden', 'catorder', 'catorder', $catorder, '', '', '', '', '', '', '', '', '', '', '');
   	echo html_input('hidden', 'action', 'process', 'process','','','','','','','','','','','');
   	echo html_input('hidden', 'task', 'task', 'admin_category', '', '', '', '', '', '', '', '', '', '', '');
	echo html_input('submit', $frm_task, $frm_task, $frm_submit, '', 'button', '', '', '', '', '', '', '', '', '');
	if (!empty($categoryid)) {
		echo '&nbsp;&nbsp;';
		echo html_input('hidden', 'id', 'id', $categoryid, '', '', '', '', '', '', '', '', '', '', '');
		echo html_input('submit', 'delete_category', 'delete_category', l('delete'), '', 'button', 'onclick="javascript: return pop(\''.l('js_delete2').'\')"', '', '', '', '', '', '', '', '');
	}
	echo '</p></form>';
}

// DELETE CATEGORY BY ID
function delete_cat($id) {
	$catdata = retrieve('catorder, subcat', 'categories', 'id', $id);
	$cat_order = $catdata['catorder'];
	$cat_subcat = $catdata['subcat'];
	$query = "DELETE FROM "._PRE.'categories'." WHERE id = $id LIMIT 1";
	if ($result = db() -> query($query)) {$r = dbfetch($result);}
	$sql = "SELECT id,catorder FROM "._PRE.'categories'." WHERE catorder > $cat_order AND subcat = $cat_subcat";
	if ($res = db() -> query($sql)) {
		while ($rr = dbfetch($res)) {
			$sq = "UPDATE "._PRE.'categories'." SET catorder = catorder - 1 WHERE id = $r[id]";
			if ($ru = db() -> query($sq)) {
				$rq = dbfetch($ru);
			} unset($ru);
		}
	}
}

// FORM EXTRA GROUPINGS
function form_groupings() {
 	if (s('enable_extras') == 'YES') {
		if (isset($_GET['id']) && is_numeric($_GET['id']) && !is_null($_GET['id'])) {
			$extraid = $_GET['id'];
			$query = 'SELECT id,name,seftitle,description FROM '._PRE.'extras'.' WHERE id='.$extraid;
			if ($result = db() -> query($query)) {$r = dbfetch($result);}
			$frm_action = _SITE.'?action=process&amp;task=admin_groupings&amp;id='.$extraid;
			$frm_add_edit = l('edit');
			$frm_name = $r['name'];
			$frm_sef_title = $r['seftitle'];
			$frm_description = $r['description'];
			$frm_task = 'edit_groupings';
			$frm_submit = l('edit_button');
		} else {
			$frm_action = _SITE.'?action=process&amp;task=admin_groupings';
			$frm_add_edit = l('add_groupings');
			$frm_name = $_POST['name'];
			$frm_sef_title = $_POST['name'] == '' ? cleanSEF($_POST['name']) : cleanSEF($_POST['seftitle']);
			$frm_description = '';
			$frm_task = 'add_groupings';
			$frm_submit = l('add_groupings');
		}
		echo html_input('form', '', 'post', '', '', '', '', '', '', '', '', '', 'post', $frm_action, '');
		echo '<div class="adminpanel">';
		echo '<p class="admintitle">'.$frm_add_edit.'</p>';
		echo html_input('text', 'name', 't', $frm_name, l('name'), '',
			'onchange="genSEF(this,document.forms[\'post\'].seftitle)"',
			'onkeyup="genSEF(this,document.forms[\'post\'].seftitle)"', '', '', '', '', '', '', '');
		echo html_input('text', 'seftitle', 's', $frm_sef_title, l('extra_title'), '', '', '', '', '', '', '', '', '', '');
		echo html_input('text', 'description', 'desc', $frm_description, l('description'), '', '', '', '', '', '', '', '', '', '');
		echo '</div><p>';
		echo html_input('hidden', 'task', 'task', 'admin_groupings', '', '', '', '', '', '', '', '', '', '', '');
		echo html_input('submit', $frm_task, $frm_task, $frm_submit, '', 'button', '', '', '', '', '', '', '', '', '');
		if (!empty($extraid)) {
			echo '&nbsp;&nbsp;';
			echo html_input('hidden', 'id', 'id', $extraid, '', '', '', '', '', '', '', '', '', '', '');
			if ($extraid != 1) {
				echo html_input('submit', 'delete_groupings', 'delete_groupings', l('delete'), '',
				'button', 'onclick="javascript: return pop(\''.l('js_delete2').'\')"', '', '', '', '', '', '', '', '');
			}
		}
		echo '</p></form>';
	}
}

// ADMIN GROUPINGS
function admin_groupings() {
    if (s('enable_extras') == 'YES') {
		if (stats('extras', '') > 0) {
		    $add = ' - <a href="admin_groupings/" title="'.l('add_new').'">'.l('add_new').'</a>';
		} else {
		    $add = '';
		}
		echo '<div class="adminpanel">';
		echo '<p class="admintitle">'.l('groupings').$add.'</p>';
		$num = stats('extras', '');
		if ($num == 0) {echo '<p>'.l('group_not_exist').'</p>';}
		else {
			$query = 'SELECT id,name,description FROM '._PRE.'extras'.' ORDER BY id ASC';
			if ($result = db() -> query($query)) {
				while ($r = dbfetch($result)) {
					echo '<p>';
						echo '<strong>'.$r['name'].'</strong> '.l('divider');
						echo '<a href="'._SITE.'?action=admin_groupings&amp;id='.$r['id'].'" title="'.$r['description'].'">'.l('edit').'</a>';
					echo '</p>';
				}
			} else {echo '<p>'.l('group_not_exist').'</p>';}
		}
		echo '</div>';
	}
}

// ARTICLES FORM
function form_articles($contents) {
 	if (is_numeric($_GET['id']) && !is_null($_GET['id'])) {
		$id = $_GET['id'];
		$frm_position1 = ''; $frm_position2 = ''; $frm_position3 = '';
 		$query = 'SELECT * FROM '._PRE.'articles'.' WHERE id = '.$id;
 		if ($result = db() -> query($query)){
			while ($r = dbfetch($result)) {
				$article_category = $r['category'];
				$edit_option = $r['position'] == 0 ? 1 : $r['position'];
				$edit_page = $r['page_extra'];
				$extraid = $r['extraid'];
				$title = $r['seftitle'];
				$text = $r['text'];
				$dmeta = $r['description_meta'];
				$keywords = $r['keywords_meta'];
				$dtitle = $r['displaytitle'];
				$dinfo = $r['displayinfo'];
				$published = $r['published'];
				$showSub = $r['show_in_subcats'];
				$showHome = $r['show_on_home'];
				$com = $r['commentable'];
				switch ($edit_option) {
					case 1:
						$frm_fieldset = l('edit').' '.l('article');
						$toggle_div='show';
						$frm_position1 = 'selected="selected"';
						break;
					case 2:
						$frm_fieldset = l('edit').' '.l('extra_contents');
						$toggle_div='show';
						$frm_position2 = 'selected="selected"';
						break;
					case 3:
						$frm_fieldset = l('edit').' '.l('page');
						$toggle_div='show';
						$frm_position3 = 'selected="selected"';
						break;
				}
			}
		}
		$frm_action = _SITE.'?action=process&amp;task=admin_article&amp;id='.$id;
		$frm_title = isset($_SESSION[_SITE.'temp']['title']) ? $_SESSION[_SITE.'temp']['title'] : $title;
		$frm_sef_title = isset($_SESSION[_SITE.'temp']['seftitle']) ? cleanSEF($_SESSION[_SITE.'temp']['seftitle']) : $title;
		$frm_text = isset($_SESSION[_SITE.'temp']['text']) ? str_replace('&', '&amp;', $_SESSION[_SITE.'temp']['text']) : $text;
		$frm_meta_desc = isset($_SESSION[_SITE.'temp']['description_meta']) ?
			cleanSEF($_SESSION[_SITE.'temp']['description_meta']) : $dmeta;
		$frm_meta_key = isset($_SESSION[_SITE.'temp']['keywords_meta']) ?
			cleanSEF($_SESSION[_SITE.'temp']['keywords_meta']) : $keywords;
		$frm_display_title = $dtitle == 'YES' ? 'ok' : '';
		$frm_display_info = $dinfo == 'YES' ? 'ok' : '';
		$frm_publish = $published == 1 ? 'ok' : '';
		$show_in_subcats = $showSub == 'YES' ? 'ok' : '';
		$frm_showonhome = $showHome == 'YES' ? 'ok' : '';
		$frm_commentable = ($com == 'YES' || $com == 'FREEZ') ? 'ok' : '';
		$frm_task = 'edit_article';
		$frm_submit = l('edit_button');
	} else {
		switch ($contents) {
			case 'article_new':
				$frm_fieldset = l('article_new');
				$toggle_div='';
				$pos = 1;
				$frm_position1 = 'selected="selected"';
				break;
			case 'extra_new':
				$frm_fieldset = l('extra_new');
				$toggle_div='';
				$pos = 2;
				$frm_position2 = 'selected="selected"';
				break;
			case 'page_new':
				$frm_fieldset = l('page_new');
				$toggle_div='';
				$pos = 3;
				$frm_position3 = 'selected="selected"';
				break;
		}
		if (empty($frm_fieldset)) {
			$frm_fieldset =  l('article_new');
		}
		$frm_action = _SITE.'?action=process&amp;task=admin_article';
		$frm_title = $_SESSION[_SITE.'temp']['title'];
		$frm_sef_title = cleanSEF($_SESSION[_SITE.'temp']['seftitle']);
		$frm_text = $_SESSION[_SITE.'temp']['text'];
		$frm_meta_desc = cleanSEF($_SESSION[_SITE.'temp']['description_meta']);
		$frm_meta_key = cleanSEF($_SESSION[_SITE.'temp']['keywords_meta']);
		$frm_display_title = 'ok';
		$frm_display_info = ($contents == 'extra_new') ? '' : 'ok';
		$frm_publish = 'ok';
		$show_in_subcats = 'ok';
		$frm_showonhome = s('display_new_on_home') == 'on' ? 'ok' : '';
		$frm_commentable = ($contents == 'extra_new' || $contents == 'page_new' || s('enable_comments') != 'YES') ? '' : 'ok';
		$frm_task = 'add_article';
		$frm_submit = l('submit');
	}
	$catnum = stats('categories', '');
 	if ($contents == 'article_new' && $catnum['catnum'] < 1) {
 		echo l('create_cat');
 	} else {
		echo html_input('form', '', 'post', '', '', '', '', '', '', '', '', '', 'post', $frm_action, '');
		echo '<div class="adminpanel">';
		if ($toggle_div=='show') {
		    echo '<p class="admintitle"><a onclick="toggle(\'edit_article\')" style="cursor: pointer;" title="'.$frm_fieldset.'">'.$frm_fieldset.'</a></p>';
		    echo '<div id="edit_article" style="display: none;">';
		} else {
		     echo '<p class="admintitle">'.$frm_fieldset.'</p>';
		}
		echo html_input('text', 'title', 'at', $frm_title, l('title'), '', 
			'onchange="genSEF(this,document.forms[\'post\'].seftitle)"', 'onkeyup="genSEF(this,document.forms[\'post\'].seftitle)"', '', '', '', '', '', '', '');
		if ($contents == 'extra_new' || $edit_option == 2) {
		    echo '<div style="display: none;">';
		    echo html_input('text', 'seftitle', 'as', $frm_sef_title, l('sef_title'), '', '', '', '', '', '', '', '', '', '');
		    echo '</div>';
		} else {
		    echo html_input('text', 'seftitle', 'as', $frm_sef_title, l('sef_title'), '', '', '', '', '', '', '', '', '', '');
		}
		echo html_input('textarea', 'text', 'txt', $frm_text, l('text'), '', '', '', '', '', '2', '100', '', '', '');
		buttons();
		if ($contents != 'page_new' && $edit_option != 3) {
		    echo '<p><label for="cat">';
		    echo ($contents == 'extra_new' || $edit_option == 2) ?  l('appear_category') : l('category');
		    if ($contents == 'extra_new' || $edit_option == 2) {
				echo ':</label><br /><select name="define_category" id="cat" onchange="dependancy(\'extra\');">';
				echo '<option value="-1"'.($article_category == -1 ? ' selected="selected"' : '').'>'.l('all').'</option>';
				echo '<option value="-3"'.($article_category == -3 ? ' selected="selected"' : '').'>'.l('page_only').'</option>';
		    } else {echo ':</label><br /><select name="define_category" id="cat" onchange="dependancy(\'snews_articles\');">';}
		    $category_query = 'SELECT id,name,subcat FROM '._PRE.'categories'.'
				WHERE published = \'YES\' AND subcat = 0 ORDER BY catorder,id ASC';
		    if ($cat_result = db() -> query($category_query)) {
				while ($cat = dbfetch($cat_result)) {
					echo '<option value="'.$cat['id'].'"';
					if ($article_category == $cat['id']) {
						echo ' selected="selected"';
					}
					echo '>'.$cat['name'].'</option>';
					$subquery = 'SELECT id,name,subcat FROM '._PRE.'categories'.'
						WHERE subcat = '.$cat['id'].' ORDER BY catorder,id ASC';
					if ($subresult = db() -> query($subquery)) {
						while ($s = dbfetch($subresult)) {
							echo '<option value="'.$s['id'].'"';
							if ($article_category == $s['id']) {
								echo ' selected="selected"';
							}
							echo '>--'.$s['name'].'</option>';
						}
					}
						
				}
			}
			echo '</select></p>';
			if ($contents == 'extra_new' || $edit_option == 2) {
				$none_display = $article_category == -1 ? 'none' : 'inline';
				echo '<div id="def_page" style="display:'.$none_display.';"><p><label for="dp">'.l('appear_page').':</label>
					<br /><select name="define_page" id="dp">';
				echo '<option value="0"'.($edit_option != '2' ? ' selected="selected"' : '').'>'.l('all').'</option>';
				$query = 'SELECT id,title FROM '._PRE.'articles'.' WHERE position = 3 ORDER BY id ASC';
				if ($result = db() -> query($query)) {
					while ($r = dbfetch($result)) {
						echo '<option value="'.$r['id'].'"';
						if ($edit_page == $r['id']) {
							echo ' selected="selected"';
						}
						echo '>'.$r['title'].'</option>';
					}
				}
				echo '</select><br />'.
				html_input('checkbox', 'show_in_subcats', 'asc', 'YES', l('show_in_subcats'), '', '', '', '', $show_in_subcats, '', '', '', '', '').'</p></div>';
			}
		}
		if ($contents == 'article_new' || $edit_option == 1) {
		 	echo html_input('checkbox', 'show_on_home', 'sho', 'YES', l('show_on_home'), '', '', '', '', $frm_showonhome, '', '', '', '', '');
		}
		echo html_input('checkbox', 'publish_article', 'pu', 'YES', l('publish_article'), '', '', '', '', $frm_publish, '', '', '', '', '');
		if ($toggle_div=='show') {
			echo '</div>';
		}
		echo '</div>';
	
		echo '<div class="adminpanel">';
			echo '<p class="admintitle"><a onclick="toggle(\'preview\')" style="cursor: pointer;" title="'.l('preview').'">'.l('preview').'</a></p>';
			echo '<div id="preview" style="display: none;"></div>';
		echo '</div>';
		echo '<div class="adminpanel">';
		echo '<p class="admintitle"><a onclick="toggle(\'customize\')" style="cursor: pointer;" title="'.l('customize').'">'.l('customize').'</a></p>';
		echo '<div id="customize" style="display: none;">';
		if ($contents == 'extra_new' || $edit_option == 2) {
			if (s('enable_extras') == 'YES') {
				echo '<p><label for="ext">'.l('define_extra').'</label><br />';
				echo '<select name="define_extra" id="ext">';
				$extra_query = 'SELECT id,name FROM '._PRE.'extras'.' ORDER BY id ASC';
				if ($extra_result = db() -> query($extra_query)) {
					while ($ex = dbfetch($extra_result)) {
						echo '<option value="'.$ex['id'].'"';
						if ($extraid == $ex['id']) {
							echo ' selected="selected"';
						}
						echo '>'.$ex['name'].'</option>';
					}
				}
				echo '</select></p>';
			} else {
				echo html_input('hidden', 'define_extra', 'ext', 1, '', '', '', '', '', '', '', '', '', '', '');
			}
		}
		if (!empty($id)) {
			echo '<p><label for="pos">'.l('position').':</label>
				<br /><select name="position" id="pos">';
			echo '<option value="1"'.$frm_position1.'>'.l('center').'</option>';
			echo '<option value="2"'.$frm_position2.'>'.l('side').'</option>';
			echo '<option value="3"'.$frm_position3.'>'.l('display_page').'</option>';
			echo '</select></p>';
		} else {
			echo html_input('hidden', 'position', 'position', $pos, '', '', '', '', '', '', '', '', '', '', '');
		}
		if ($contents != 'extra_new' && $edit_option != '2') {
			echo html_input('text', 'description_meta', 'dm', $frm_meta_desc, l('description_meta'), '', '', '', '', '', '', '', '', '', '');
			echo html_input('text', 'keywords_meta', 'km', $frm_meta_key, l('keywords_meta'), '', '', '', '', '', '', '', '', '', '');
		}
		echo html_input('checkbox', 'display_title', 'dti', 'YES', l('display_title'), '', '', '', '', $frm_display_title, '', '', '', '', '');
		if ($contents != 'extra_new' && $edit_option != '2') {
			echo html_input('checkbox', 'display_info', 'di', 'YES', l('display_info'), '', '', '', '', $frm_display_info, '', '', '', '', '');
			echo html_input('checkbox', 'commentable', 'ca', 'YES', l('enable_commenting'), '', '', '', '', $frm_commentable, '', '', '', '', '');
				if (!empty($id)) {
					echo '<p><input name="freeze" type="checkbox" id="fc"';
					if ($r['commentable'] == 'FREEZ') {
						echo ' checked="checked" />';
					} else if ($r['commentable'] == 'YES') {
						echo ' />';
					} else {
						echo ' />';
					}
					echo ' <label for="fc"> '.l('freeze_comments').'</label></p>';
				}
			}
		echo '</div></div>';
		
		if ($contents == 'article_new' || $edit_option == 1) {
			echo '<div class="adminpanel">';
			echo '<p class="admintitle"><a onclick="toggle(\'admin_publish_date\')" style="cursor: pointer;" title="'.l('publish_date').'">'.l('publish_date').'</a></p>';
			echo '<div id="admin_publish_date" style="display: none;">';
			$onoff_status = $r['published'] == '2' ? 'ok' : ''; // Variable inserted in check-box string show is as checked if enabled.
			echo html_input('checkbox', 'fposting', 'fp', 'YES', l('enable'), '', '', '', '', $onoff_status, '', '', '', '', '');
			echo '<p>'.l('server_time').': '.date('d.m.Y. H:i:s').'</p>';
			echo '<p>'.l('article_date').'</p>';
			!empty($id) ? posting_time($r['date']) : posting_time();
			echo '</div></div>';
		}
		echo '<p>';
		echo html_input('hidden', 'action', 'action', 'process', '', '', '', '', '', '', '', '', '', '', '');
		echo html_input('hidden', 'task', 'task', 'admin_article', '', '', '', '', '', '', '', '', '', '', '');
		echo html_input('submit', $frm_task, $frm_task, $frm_submit, '', 'button', '', '', '', '', '', '', '', '', '');
		if (!empty($id)) {
			echo html_input('hidden', 'article_category', 'article_category', $article_category, '', '', '', '', '', '', '', '', '', '', '');
			echo html_input('hidden', 'id', 'id', $id, '', '', '', '', '', '', '', '', '', '', '').' ';
			echo html_input('submit', 'delete_article', 'delete_article', l('delete'), '', 'button', 
				'onclick="javascript: return pop(\''.l('js_delete2').'\')"', '', '', '', '', '', '', '', '');
		}
		echo '</p></form>';
	}
}

// ARTICLES
function admin_articles($contents) {
	global $categorySEF, $subcatSEF;
	$link = '<a href="'._SITE.$categorySEF.'/';
	switch ($contents) {
		case 'article_view':
			$title = l('articles');
			$sef = 'article_new';
			$goto = 'snews_articles';
			$p = 1;
			$qw = 'position < 2 AND position >-1 ';
		break;
		case 'extra_view':
			$title = l('extra_contents');
			$sef = 'extra_new';
			$goto = 'extra_contents';$p = '2';
			$qw = 'position = 2 ';
		break;
		case 'page_view':
			$title = l('pages');
			$sef = 'page_new';
			$p = '3';
			$goto = 'snews_pages';
			$qw = 'position = 3 ';
			break;
	}
	$subquery = 'AND '.$qw;
	if (stats('articles',$p) > 0) {
		$add = ' - <a href="'.$sef.'/" title="'.l('add_new').'">
			'.l('add_new').'</a> - '.l('see').' ('.$link.'">'.l('all').'</a>) -
			'.l('filter').' ('.$link.l('year').'">'.l('year').'</a> / '.$link.l('month').'">
			'.l('month').'</a>)';
	} else {
		$add = '';
	}
	$tab = 1;
	if ($subcatSEF == l('year') || $subcatSEF == l('month')) {
		$query = 'SELECT DISTINCT(YEAR(date)) AS dyear 
			FROM '._PRE.'articles'.' 
			WHERE '.$qw.' ORDER BY date DESC';
		$month_names = explode(', ', l('month_names'));
		echo '<div class="adminpanel">';
		echo '<p class="admintitle">'.l('articles').'</p>';
		echo ' - '.l('filter').' <span style="color: #0000FF">'.$subcatSEF.'</span> - '.l('see').' ('.$link.'">'.l('all').'</a>) - '.l('filter').' ('.$link.l('year').'">'.l('year').'</a> / '.$link.l('month').'">'.l('month').'</a>)</legend>';
		if ($sqr = db() -> query($query)) {
		    while ($r = dbfetch($sqr)) {
		    //print_r($r);
			 	$ryear = $r['dyear'];
				echo ($subcatSEF == l('month') ? '<span style="color: #0000FF">'.$r['dyear'].'</span>' : $link.l('year').'='.$r['dyear'].'">'.$r['dyear'].'</a> ');
				if ($subcatSEF == l('month')) {
				    $qx = "SELECT DISTINCT(MONTH(date)) AS dmonth 
						FROM "._PRE.'articles'." 
						WHERE $qw AND YEAR(date)=$ryear ORDER BY date ASC";
					if ($rqx = db() -> query($qx)) {
						while ($rx = dbfetch($rqx)) {
							$m = $rx['dmonth'] - 1;
							echo ' '.l('divider').' '.$link.l('year').'='.$r['dyear'].';'.l('month').'='.$rx['dmonth'].'">'.$month_names[$m].'</a> ';
					    }	
					}
				    
				    
				}
				echo '<br />';
		    }
		}
		echo '</div>';
		return;
	}
	$txtYear = l('year');
	$txtMonth = l('month');
  	if (substr($subcatSEF, 0, strlen($txtYear)) == $txtYear) {
  		$year = substr($subcatSEF, strlen($txtYear)+1, 4);
  	}
  	$find = strpos($subcatSEF,l('month'));
  	if ($find > 0) {
  		$month = substr($subcatSEF, $find + strlen($txtMonth) + 1, 2);
  	}
	$filterquery = !empty($year) ? "AND YEAR(date)='".$year."' " : '';
	$filterquery .= !empty($month) ? "AND MONTH(date)='".$month."' " : '';
	$no_content = !empty($filterquery) ? '<p>'.l('no_content_for_filter').'</p>' : '<p>'.l('article_not_exist').'</p>';
	echo html_input('form', '', 'post', '', '', '', '', '', '', '', '', '', 'post', '?action=process&amp;task=reorder', '');
	echo '<div class="adminpanel">';
	echo '<p class="admintitle">'.$title.$add.'</p>';
	echo '<p><input type="hidden" name="order" id="order" value="'.$goto.'" /></p>';
	if ($contents == 'extra_view') {
		$cat_array_irregular = array('-1','-3');
	 	foreach ($cat_array_irregular as $cat_value) {
	 	    $legend_label = $cat_value == -3 ? l('pages') : l('all');
	 	    $page_only_xsql = $cat_value == -3 ? 'page_extra ASC,' : '';
	 	    $sql = "SELECT id, title, seftitle, date, published, artorder, visible, default_page, page_extra
	 			FROM "._PRE.'articles'."
	 			WHERE category = $cat_value
	 				AND position = $p $filterquery
	 			ORDER BY $page_only_xsql artorder ASC, date DESC ";
	 	    $num_rows = stats('articles', '', 'category = '.$cat_value.' AND position = '.$p.' '.$filterquery);
	 	    $tab = 1;
	 	    echo '<div class="innerpanel">';
		    echo '<p class="admintitle">'.$legend_label.'</p>';
			if ($num_rows == 0) {
			    echo $no_content;
			} else {
			    $lbl_filter = -5;
			    if ($result = db() -> query($sql)) {
					while ($r = dbfetch($result)) {
					    if ($cat_value == -3) {
							if ($lbl_filter != $r['page_extra']) {
							    $assigned_page = retrieve('title','articles','id',$r['page_extra']);
							    echo !$assigned_page ? l('all_pages') : $assigned_page;
							}
					    }
					    $order_input = '<input type="text" name="page_'.$r['id'].'" value="'.$r['artorder'].'" size="1" tabindex="'.$tab.'" /> &nbsp;';
					    echo '<p>'.$order_input.'<strong title="'.date(s('date_format'), strtotime($r['date'])).'">	'.$r['title'].'</strong> ';
					    if ($r['default_page'] != 'YES') {
							echo  l('divider').' <a href="'._SITE.'?action=admin_article&amp;id='.$r['id'].'">'.l('edit').'</a> ';
					    }
					    $visiblity = $r['visible'] == 'YES' ?
							'<a href="'._SITE.'?action=process&amp;task=hide&amp;item='.$item.'&amp;id='.$r['id'].'">'.l('hide').'</a>' :
							l('hidden').' ( <a href="'._SITE.'?action=process&amp;task=show&amp;item='.$item.'&amp;id='.$r['id'].'">'.l('show').'</a> )';
						echo ' '.l('divider').' '.$visiblity;
						if ($r['published'] == 2) {
							echo  l('divider').' ['.l('status').' '.l('future_posting').']';
						}
						if ($r['published'] == 0) {
							echo  l('divider').' ['.l('status').' '.l('unpublished').']';
						}
						echo '</p>';
						$tab++;
						$lbl_filter = $r['page_extra'];
					}
				}
			}
		    echo '</div>';
		}
	}
 	if ($contents == 'article_view' || $contents == 'extra_view') {
 	 	$item = $contents == 'extra_view' ? 'extra_contents': 'snews_articles';
		$num = stats('categories', '', 'subcat = 0');
		if ($num == 0) {
			echo '<p>'.l('no_categories').'</p>';
		} else {
			$num_rows = stats('articles', '', 'category = 0 AND position = '.$p.' '.$subquery);
			$sql = "SELECT id, title, seftitle, date, published, artorder, visible, default_page
				FROM "._PRE.'articles'."
				WHERE category = '0'
					AND position = $p $subquery
					ORDER BY artorder ASC, date DESC ";
			if ($num_rows > 0) {
			    echo '<div class="innerpanel">';
				echo '<p class="admintitle">'.l('no_category_set').'</p>';
				if ($res = db() -> query($sql)) {
					while ($O = dbfetch($res)) {
						$order_input = '<input type="text" name="page_'.$O['id'].'" value="'.$O['artorder'].'" size="1" tabindex="'.$tab22.'" /> &nbsp;';
						echo '<p>'.$order_input.'<strong title="'.date(s('date_format'), strtotime($O['date'])).'">'.$O['title'].'</strong> ';
						if ($r['default_page'] != 'YES'){
							echo  l('divider').' <a href="'._SITE.'?action=admin_article&amp;id='.$O['id'].'">'.l('edit').'</a> ';
						}
						$visiblity = $O['visible'] == 'YES' ?
	               	 		'<a href="'._SITE.'?action=process&amp;task=hide&amp;item='.$item.'&amp;id='.$O['id'].'">'.l('hide').'</a>' :
	               	 		l('hidden').' ( <a href="'._SITE.'?action=process&amp;task=show&amp;item='.$item.'&amp;id='.$O['id'].'">'.l('show').'</a> )' ;
	               			echo ' '.l('divider').' '.$visiblity;
						if ($O['published'] == 2) {
							echo  l('divider').' ['.l('status').' '.l('future_posting').']';
						}
						if ($O['published'] == 0) {
							echo  l('divider').' ['.l('status').' '.l('unpublished').']';
						}
						echo '</p>';
						$tab22++;
					}
				}
			    echo '</div>';
			}
			$cat_query = "SELECT id, name, seftitle FROM "._PRE.'categories'." WHERE subcat = 0";
			if ($cat_res = db() -> query($cat_query)) {
				while ($row = dbfetch($cat_res)) {
				    echo '<div class="adminpanel">';
					echo '<p class="admintitle">'.$row['name'].'</p>';
					$sql1 = "SELECT id, title, seftitle, date, published, artorder, visible, default_page
						FROM "._PRE.'articles'."
						WHERE category = '".$row['id']."'
							AND position = $p $subquery $filterquery
						ORDER BY artorder ASC, date DESC ";
					$num_rows = stats('articles', '', 'category = '.$row['id'].' AND position = '.$p.' '.$subquery.' '.$filterquery);
					if ($num_rows == 0) {
						echo $no_content;
					} else
					if ($res1 = db() -> query($sql1)) {
						while ($r = dbfetch($res1)) {
							$order_input = '<input type="text" name="page_'.$r['id'].'" value="'.$r['artorder'].'" size="1" tabindex="'.$tab.'" /> &nbsp;';
							echo '<p>'.$order_input.'<strong title="'.date(s('date_format'), strtotime($r['date'])).'">
								'.$r['title'].'</strong> '.l('divider').'
								<a href="'._SITE.$row['seftitle'].'/'.$r['seftitle'].'/">'.l('view').'</a> ';
							if ($r['default_page'] != 'YES'){
								echo  l('divider').' <a href="'._SITE.'?action=admin_article&amp;id='.$r['id'].'">'.l('edit').'</a> ';
							}
							$visiblity = $r['visible'] == 'YES' ?
		               	 		'<a href="'._SITE.'?action=process&amp;task=hide&amp;item='.$item.'&amp;id='.$r['id'].'">'.l('hide').'</a>' :
		               	 		l('hidden').' ( <a href="'._SITE.'?action=process&amp;task=show&amp;item='.$item.'&amp;id='.$r['id'].'">'.l('show').'</a> )' ;
		               			echo ' '.l('divider').' '.$visiblity;
							if ($r['published'] == 2) {
								echo  l('divider').' ['.l('status').' '.l('future_posting').']';
							}
							if ($r['published'] == 0) {
								echo  l('divider').' ['.l('status').' '.l('unpublished').']';
							}
							echo '</p>';
							$tab++;
						}
					
					}
					$query2 = "SELECT id, name, seftitle FROM "._PRE.'categories'." WHERE subcat = '$row[id]' ORDER BY catorder ASC";
					$tab2 = 1;
					if ($res2 = db() -> query($query2)) {
						while ($row2 = dbfetch($res2)) {
							echo '<a class="subcat" onclick="toggle(\'subcat'.$row2['id'].'\')" style="cursor: pointer;">'.$row2['name'].'</a><br />';
							echo '<div id="subcat'.$row2['id'].'" style="display: none;" class="subcat">';
							$catart_sql2 = "SELECT id, title, seftitle, date, published, artorder, visible
								FROM "._PRE.'articles'."
								WHERE category = '$row2[id]' $subquery $filterquery
								ORDER BY category ASC, artorder ASC, date DESC ";
							$num_rows2 = stats('articles', '', 'category = '.$row2['id'].' '.$subquery.' '.$filterquery);
							if ($num_rows2 == 0) {
								echo $no_content;
							}
							if ($res3 = db() -> query($catart_sql2)) {
								while ($ca_r2 = dbfetch($res3)) {
									$order_input2 = '<input type="text" name="page_'.$ca_r2['id'].'" value="'.$ca_r2['artorder'].'" size="1" tabindex="'.$tab2.'" /> &nbsp;';
									$catSEF = cat_rel($row2['id'],'seftitle');
									echo '<p>'.$order_input2.'<strong title="'.date(s('date_format'), strtotime($ca_r2['date'])).'">
										'.$ca_r2['title'].'</strong> '.l('divider').'
										<a href="'._SITE.$catSEF.'/'.$ca_r2['seftitle'].'/">'.l('view').'</a> ';
									echo  l('divider').' <a href="'._SITE.'?action=admin_article&amp;id='.$ca_r2['id'].'">'.l('edit').'</a> ';
									$visiblity2 = $ca_r2['visible'] == 'YES' ?
						       	 		'<a href="'._SITE.'?action=process&amp;task=hide&amp;item=snews_articles&amp;id='.$ca_r2['id'].'">'.l('hide').'</a>' :
			            	   	 		l('hidden').' ( <a href="'._SITE.'?action=process&amp;task=show&amp;item=snews_articles&amp;id='.$ca_r2['id'].'">
			            	   	 			'.l('show').'</a> )';
			       					echo ' '.l('divider').' '.$visiblity2;
									if ($ca_r2['published'] == 2) {
										echo  l('divider').' ['.l('status').' '.l('future_posting').']';
									}
									if ($ca_r2['published'] == 0) {
										echo  l('divider').' ['.l('status').' '.l('unpublished').']';
									}
									echo '</p>';
								}
							}
							echo '</div>';
							$tab2++;
						}
					}
				    echo '</div>';
				}
			}
		}
	} elseif ($contents == 'page_view') {
		$sql = "SELECT id, title, seftitle, date, published, artorder, visible, default_page
			FROM "._PRE.'articles'."
			WHERE position = 3 $subquery
			ORDER BY artorder ASC, date DESC ";
		$num_rows = stats('articles', '', 'position = 3 '.$subquery);
		if ($num_rows == 0) {
			echo '<p>'.l('article_not_exist').'</p>';
		}
		if ($result = db() -> query($sql)) {
			while ($r = dbfetch($result)) {
				$order_input = '<input type="text" name="page_'.$r['id'].'" value="'.$r['artorder'].'" size="1" tabindex="'.$tab.'" /> &nbsp;';
				echo '<p>'.$order_input.'<strong title="'.date(s('date_format'), strtotime($r['date'])).'">
					'.$r['title'].'</strong> '.l('divider').'
					<a href="'._SITE.$r['seftitle'].'/">'.l('view').'</a> ';
				if ($r['default_page'] != 'YES') {
					echo  l('divider').' <a href="'._SITE.'?action=admin_article&amp;id='.$r['id'].'">'.l('edit').'</a> ';
				}
				$visiblity = $r['visible'] == 'YES' ?
	                '<a href="'._SITE.'?action=process&amp;task=hide&amp;item=snews_pages&amp;id='.$r['id'].'">'.l('hide').'</a>' :
	                l('hidden').' ( <a href="'._SITE.'?action=process&amp;task=show&amp;item=snews_pages&amp;id='.$r['id'].'">'.l('show').'</a> )' ;
				echo ' '.l('divider').' '.$visiblity;
				if ($r['published'] == 2) {
					echo  l('divider').' ['.l('status').' '.l('future_posting').']';
				}
				if ($r['published'] == 0) {
					echo  l('divider').' ['.l('status').' '.l('unpublished').']';
				}
				echo '</p>';
				$tab++;
			}
		}
	}
	echo '<p>'.html_input('submit', 'reorder', 'reorder', l('order_content'), '', 'button', '', '', '', '', '', '', '', '', '');
	echo '</p></div></form>';
}

// COMMENTS - EDIT
function edit_comment() {
	$commentid = $_GET['commentid'];
	$query = 'SELECT id,articleid,name,url,comment,approved FROM '._PRE.'comments'.' WHERE id='.$commentid;
	if ($result = db() -> query($query)) {$r = dbfetch($result);}
	$articleTITLE = retrieve('title', 'articles', 'id', $r['articleid']);
	echo html_input('form', '', 'post', '', '', '', '', '', '', '', '', '', 'post', '?action=process&amp;task=editcomment&amp;id='.$commentid, '');
	echo '<div class="adminpanel">';
	echo '<p class="admintitle">'.l('edit_comment').' (<strong> '.$articleTITLE.'</strong> )</p>';
	echo html_input('textarea', 'editedcomment', 'ec', stripslashes($r['comment']), l('comment'), '', '', '', '', '', '2', '100', '', '', '');
	echo html_input('text', 'name', 'n', $r['name'], l('name'), '', '', '', '', '', '', '', '', '', '');
	echo html_input('text', 'url', 'url', $r['url'], l('url'), '', '', '', '', '', '', '', '', '', '');
	echo html_input('checkbox', 'approved', 'a', '', l('approved'), '', '', '', '', $r['approved'] == 'True' ? 'ok' : '', '', '', '', '', '');
	echo '</div><p>';
	echo html_input('hidden', 'id', 'id', $r['articleid'], '', '', '', '', '', '', '', '', '', '', '');
	echo html_input('submit', 'submit_text', 'submit_text', l('edit'), '', 'button', '', '', '', '', '', '', '', '', '');
	echo html_input('hidden', 'commentid', 'commentid', $r['id'], '', '', '', '', '', '', '', '', '', '', '');
	echo html_input('submit', 'delete_text', 'delete_text', l('delete'), '',
		'button', 'onclick="javascript: return pop()"', '', '', '', '', '', '', '', '');
	echo '</p></form>';
}

// FILES
function files() {
 	$upload_file = isset($_POST['upload']) ? $_POST['upload'] : null;
 	$ip = (isset($_POST['ip']) && $_POST['ip'] == $_SERVER['REMOTE_ADDR']) ? $_POST['ip'] : null;
 	$time = (isset($_POST['time']) && (time() - $_POST['time']) > 4) ? $_POST['time'] : null;
 	if ($ip && $time && $upload_file && _ADMIN) {
		$ignore = explode(',', l('ignored_items'));
		$file_types = explode(',', s('allowed_files'));
		$image_types = explode(',', s('allowed_images'));
		$extension = array_merge($file_types, $image_types);
		if ($_FILES['imagefile']['type']) {
			$filetemp = $_FILES['imagefile']['tmp_name'];
			$filename = strtolower($_FILES['imagefile']['name']);
			$filetype = $_FILES['imagefile']['type'];
			if (!in_array(substr(strrchr($filename, '.'), 1), $extension) || in_array($filename, $ignore)) {
				die(notification(2,l('file_error'),'snews_files'));
			} else {
				$upload_dir = $_POST['upload_dir'].'/';
				copy ($filetemp, $upload_dir.$filename) or die (l('file_error'));
				echo notification(0,'','snews_files');
				$kb_size = round(($_FILES['imagefile']['size'] / 1024), 1);
				echo '<p><a href="'.$upload_dir.$filename.'" title="'.$filename.'">'.$filename.'</a> ['.$kb_size.' KB] ['.$filetype.']</p>';
			}
		} else {
			die(notification(2,l('file_error'),'snews_files'));
		}
	} else {
		if (isset($_GET['task']) == 'delete') {
			$file_to_delete = $_GET['folder'].'/'.$_GET['file'];
			@unlink($file_to_delete);
			echo notification(0,'','snews_files');
		} else {
			echo '<div class="adminpanel">';
			echo '<p class="admintitle">'.l('upload').'</p>';
			echo '<form method="post" action="snews_files/" enctype="multipart/form-data">';
			echo '<p>'.l('uploadto').
			'&nbsp;&nbsp;&nbsp;<select name="upload_dir" id="ud1">';
			echo '<option value=".">..</option>';
			filelist('option',".", 0);
			echo '</select></p><p>'.l('uploadfrom').
			'&nbsp;&nbsp;&nbsp;<input type="file" name="imagefile" /></p><p>';
			echo html_input('hidden', 'ip', 'ip1', $_SERVER['REMOTE_ADDR'], '', '', '', '', '', '', '', '', '', '', '');
			echo html_input('hidden', 'time', 'time1', time(), '', '', '', '', '', '', '', '', '', '', '');
			echo html_input('submit', 'upload', 'upload', l('upload'), '', 'button', '', '', '', '', '', '', '', '', '');
			echo '</p></form></div>';
			echo '<div class="adminpanel">';
			
			echo '<p class="admintitle">'.l('view_files').' '.(!isset($_POST['upload_dir']) ? ' root' : ' '.str_replace('.', 'root', $_POST['upload_dir']));
			echo '</p>';
			echo '<form method="post" action="snews_files/" enctype="multipart/form-data">';
			echo '<p><select name="upload_dir" id="ud2"><option value=".">..</option>';
			filelist('option',".");
			echo '</select>';
			echo html_input('hidden', 'file', 'file', $file, '', '', '', '', '', '', '', '', '', '', '');
			echo html_input('hidden', 'ip', 'ip2', $_SERVER['REMOTE_ADDR'], '', '', '', '', '', '', '', '', '', '', '');
			echo html_input('hidden', 'time', 'time2', time(), '', '', '', '', '', '', '', '', '', '', '');
			echo html_input('submit', 'show', 'show', l('view'), '', 'button', '', '', '', '', '', '', '', '', '');
			$handle = (isset($_POST['upload_dir']) && strlen($_POST['upload_dir']) > 2) ? substr($_POST['upload_dir'], 2) : ".";
			echo '</p><p>';
			filelist('list', $handle);
			echo '</p></form></div>';
		}
	}
}

// FILELIST FUNCTION
function filelist($mode, $path, $depth = 0) {
	$ignore = explode(',', l('ignored_items'));
	$file_types = explode(',', s('allowed_files'));
	$image_types = explode(',', s('allowed_images'));
	$types = array_merge($file_types, $image_types);
	$dh = @opendir($path);
	while (false !== ($file = readdir($dh))) {
		$target = $path.'/'.$file;
		if(!in_array($file, $ignore)) {
			$spaces = str_repeat(l('divider').' ', ($depth));
			switch(true) {
				case ($mode == 'option' && is_dir($target)):
					$selected = $_POST['view_dir'] == $target ? ' selected="selected"' : '';
					echo '<option value="'.$target.'"'.$selected.'>'.$spaces.$file.'</option>';
			  		filelist('option', $target, ($depth + 1));
			  		break;
			  	case ($mode == 'list' && is_file($target) && in_array(substr(strrchr($target, '.'), 1), $types)):
			  		echo '
					<a href="'.$target.'" title="'.l('view').' '.$file.'">'.$file.'</a>
						'.l('divider').'
					<a href="?action=snews_files&amp;task=delete&amp;folder='.$path.'&amp;file='.$file.'" title="'.l('delete').' '.$file.'" onclick="return pop()">	'.l('delete').'</a><br />';
			  		break;
			}
		}
	}
	closedir($dh);
}

// CHECK IF UNIQUE
function check_if_unique($what, $text, $not_id = 'x', $subcat) {
	$text = clean($text);
	switch ($what) {
		case 'article_seftitle':
			$sql = _PRE.'articles'.' WHERE seftitle = "'.$text.(!empty($not_id) ? '"
				AND category = '.$not_id : '"');
			break;
		case 'article_title':
			$sql = _PRE.'articles'.' WHERE title = "'.$text.(!empty($not_id) ? '"
				AND category = '.$not_id : '"');
			break;
		case 'subcat_seftitle':
			$sql = _PRE.'categories'.' WHERE seftitle = "'.$text.'"
				AND subcat = '.$subcat;
			break;
		case 'subcat_name':
			$sql = _PRE.'categories'.' WHERE name = "'.$text.'"
				AND subcat = '.$subcat;
			break;
		case 'cat_seftitle_edit':
			$sql = _PRE.'categories'.' WHERE seftitle = "'.$text.'"
				AND id != '.$not_id;
			break;
		case 'cat_name_edit':
			$sql = _PRE.'categories'.' WHERE name = "'.$text.'"
				AND id != '.$not_id;
			break;
		case 'subcat_seftitle_edit':
			$sql = _PRE.'categories'.' WHERE seftitle = "'.$text.'"
				AND subcat = '.$subcat.' AND id != '.$not_id;
			break;
		case 'subcat_name_edit':
			$sql = _PRE.'categories'.' WHERE name = "'.$text.'"
				AND subcat = '.$subcat.' AND id != '.$not_id;
			break;
		case 'group_seftitle':
			$sql = _PRE.'extras'.' WHERE seftitle = "'.$text.(!empty($not_id) ? '"
				AND id != '.$not_id : '"');
			break;
		case 'group_name':
			$sql = _PRE.'extras'.' WHERE name = "'.$text.(!empty($not_id) ? '"
				AND id != '.$not_id : '"');
			break;
	}
	$query = 'SELECT count(DISTINCT id) as total FROM '.$sql;
	if ($result = db() -> query($query)) {
		while ($r = dbfetch($result)) {$rows = $r['total'];}
	} else {$rows = 0;}
	if ($rows == 0) {return false;} 
	else {return true;}
}


/*** PROCESSING (CATEGORIES, CONTENTS, COMMENTS) ***/
function processing() {
	if (!_ADMIN) {
		echo (notification(1,l('error_not_logged_in'),'home'));
	} else {
	$action = clean(cleanXSS($_GET['action']));
  	$id = isset($_GET['id']) ? clean(cleanXSS($_GET['id'])) :  0;
  	$commentid = isset($_POST['commentid']) ? $_POST['commentid'] : 0;
  	$approved = isset($_POST['approved']) && $_POST['approved'] == 'on' ? 'True' : '';
  	$name = isset($_POST['name']) ? clean(entity($_POST['name'])) : '';
  	$category = !empty($_POST['define_category']) ? $_POST['define_category'] : 0;
  	$subcat = isset($_POST['subcat']) ? $_POST['subcat'] : 0;
  	$page = isset($_POST['define_page']) ? $_POST['define_page'] : '';
  	$def_extra = isset($_POST['define_extra']) ? $_POST['define_extra'] : '';
  	$description = isset($_POST['description']) ? clean(entity($_POST['description'])) : '';
  	$title = isset($_POST['title']) ? clean(entity($_POST['title'])) : '';
  	$seftitle = isset($_POST['seftitle']) ? $_POST['seftitle'] : '';
	$url = isset($_POST['url']) ? cleanXSS($_POST['url']) : '';
	$comment = isset($_POST['editedcomment']) ? $_POST['editedcomment'] : '';
	$text = isset($_POST['text']) ? clean_mysql($_POST['text']) : '';
  	$date = date('Y-m-d H:i:s');
  	$description_meta = isset($_POST['description_meta']) ? entity($_POST['description_meta']) : '';
	$keywords_meta = isset($_POST['keywords_meta']) ? entity($_POST['keywords_meta']) : '';
  	$display_title = isset($_POST['display_title']) && $_POST['display_title'] == 'on' ? 'YES' : 'NO';
	$display_info = isset($_POST['display_info']) && $_POST['display_info'] == 'on' ? 'YES' : 'NO';
  	$commentable = isset($_POST['commentable']) && $_POST['commentable'] == 'on' ? 'YES' : 'NO';
	$freez = isset($_POST['freeze']) && $_POST['freeze'] == 'on' ? 'YES' : 'NO';
  	if ($freez == 'YES' && $commentable == 'YES') {
  		$commentable = 'FREEZ';
  	}
	$position = isset($_POST['position']) && $_POST['position']> 0 ? $_POST['position'] : 1;
	if ($position == 2) {
		$position = $_POST['cat_dependant'] == 'on' ? 21 : 2;
	}
  	$publish_article = (isset($_POST['publish_article']) && $_POST['publish_article'] == 'on') ? 1 : 0;
  	$show_in_subcats = isset($_POST['show_in_subcats']) && $_POST['show_in_subcats'] == 'on' ? 'YES' : 'NO';
	$show_on_home = ((isset($_POST['show_on_home']) && $_POST['show_on_home'] == 'on') || $position > 1) ? 'YES' : 'NO';
	$publish_category = isset($_POST['publish']) && $_POST['publish'] == 'on' ? 'YES' : 'NO';
  	$fpost_enabled = false;
    if (isset($_POST['fposting']) && $_POST['fposting'] == 'on') {
		$fpost_enabled = true;
		$date = $_POST['fposting_year'].'-'.$_POST['fposting_month'].'-'.$_POST['fposting_day'].' '.
		$_POST['fposting_hour'].':'.$_POST['fposting_minute'].':00';
     	if (date('Y-m-d H:i:s') < $date) $publish_article = 2;
    }
    //$task = clean(cleanXSS($_GET['task']));
    $task = isset($_POST['task']) ? clean(cleanXSS($_POST['task'])) : '';
//    echo $task;
///	echo '<pre>'; print_r($_POST); echo '</pre>';
	switch ($task) {
 		case 'save_settings':
	 		if (isset($_POST['save'])) {
				$website_title = $_POST['website_title'];
				$home_sef = $_POST['home_sef'];
				$website_description = $_POST['website_description'];
				$website_keywords = $_POST['website_keywords'];
				$website_email = $_POST['website_email'];
				$contact_subject = $_POST['contact_subject'];
				$language = $_POST['language'];
				$charset = $_POST['charset'];
				$date_format = $_POST['date_format'];
				$article_limit = $_POST['article_limit'];
				$rss_limit = $_POST['rss_limit'];
				$display_page = $_POST['display_page'];
				$display_new_on_home = isset($_POST['display_new_on_home']) ? $_POST['display_new_on_home'] : '';
				$display_pagination = $_POST['display_pagination'];
				$num_categories = $_POST['num_categories'];
				$show_cat_names = isset($_POST['show_cat_names']) && $_POST['show_cat_names'] ? $_POST['show_cat_names'] : '';
				$approve_comments = isset($_POST['approve_comments']) ? $_POST['approve_comments'] : '';
				$mail_on_comments = isset($_POST['mail_on_comments']) ? $_POST['mail_on_comments'] : '';
				$comments_order = $_POST['comments_order'];
				$comment_limit = $_POST['comment_limit'];
				$word_filter_enable = isset($_POST['word_filter_enable']) ? $_POST['word_filter_enable'] : '';
				$word_filter_file = $_POST['word_filter_file'];
				$word_filter_change = $_POST['word_filter_change'];
				$enable_extras = isset($_POST['enable_extras']) && $_POST['enable_extras'] == 'on' ? 'YES' : 'NO';
				$enable_comments = isset($_POST['enable_comments']) && $_POST['enable_comments'] == 'on' ? 'YES' : 'NO';
				$comment_repost_timer = is_numeric($_POST['comment_repost_timer']) ? $_POST['comment_repost_timer'] : '15';
				$freeze_comments = isset($_POST['freeze_comments']) && $_POST['freeze_comments'] == 'on' ? 'YES' : 'NO';
				$file_ext = $_POST['file_ext'];
				$allowed_file = $_POST['allowed_file'];
				$allowed_img = $_POST['allowed_img'];
				$ufield = array(
					'website_title' => $website_title,
					'home_sef' => $home_sef,
					'website_description' => $website_description,
					'website_keywords' => $website_keywords,
					'website_email' => $website_email,
					'contact_subject' => $contact_subject,
					'language' => $language,
					'charset' => $charset,
					'date_format' => $date_format,
					'article_limit' => $article_limit,
					'rss_limit' => $rss_limit,
					'display_page' => $display_page,
					'comments_order' => $comments_order,
					'comment_limit' => $comment_limit,
					'word_filter_file' => $word_filter_file,
					'word_filter_change' => $word_filter_change,
					'display_new_on_home' => $display_new_on_home,
					'display_pagination' => $display_pagination,
					'num_categories' => $num_categories,
					'show_cat_names' => $show_cat_names,
					'approve_comments' => $approve_comments,
					'mail_on_comments' => $mail_on_comments,
					'word_filter_enable' => $word_filter_enable,
					'enable_extras' => $enable_extras,
					'enable_comments' => $enable_comments,
					'freeze_comments' => $freeze_comments,
					'comment_repost_timer' => $comment_repost_timer,
					'file_extensions' => $file_ext,
					'allowed_files' => $allowed_file,
					'allowed_images' => $allowed_img
			 );
		 	while (list($key, $value) = each($ufield)) {
		 		$sql = "UPDATE "._PRE.'settings'." SET value = ? WHERE name = ? LIMIT 1";
		 		if ($result = db() -> prepare($sql)) {
					$result = dbbind($result, array($value, $key), 'ss'); 
					$r = dbfetch($result, true);
					unset($result);
				}
			}
			echo notification(0,'','snews_settings');
		}
		break;
		case 'changeup':
			if (isset($_POST['submit_pass'])) {
				$user = checkUserPass($_POST['uname']);
				$pass1 = checkUserPass($_POST['pass1']);
				$pass2 = checkUserPass($_POST['pass2']);
				if ($user && $pass1 && $pass2 && $pass1 === $pass2) {
					$uname = md5($user);
					$pass = md5($pass2);
					# USERNAME
					$q1 = "UPDATE "._PRE.'settings'." SET value = ? WHERE name = ? LIMIT 1";
					if ($res1 = db() -> prepare($q1)) {
						$res1 = dbbind($res1, array($uname, 'username'), 'ss');
						$r1 = dbfetch($res1, true);
						unset($res1);
					}
					# PASSWORD
					$q2 = "UPDATE "._PRE.'settings'." SET value= ? WHERE name = ? LIMIT 1";
					if ($res2 = db() -> prepare($q2)) {
						$res2 = dbbind($res2, array($pass, 'password'), 'ss'); 
						$r2 = dbfetch($res2, true);
						unset($res2);
					}
					echo notification(0,'','administration');
        		} else {
					die(notification(2,l('pass_mismatch'),'snews_settings'));
        		}
			}
		break;
		case 'admin_groupings':
			switch (true) {
				case (empty($name)):
					echo notification(1,l('err_TitleEmpty').l('errNote'));
					form_groupings();
					break;
				case (empty($seftitle)):
					echo notification(1,l('err_SEFEmpty').l('errNote'));
					form_groupings();
					break;
				case(check_if_unique('group_name', $name, $id, '')):
					echo notification(1,l('err_TitleExists').l('errNote'));
					form_groupings();
					break;
				case(check_if_unique('group_seftitle', $seftitle, $id, '')):
					echo notification(1,l('err_SEFExists').l('errNote'));
					form_groupings();
					break;
				case(cleancheckSEF($seftitle) == 'notok'):
					echo notification(1,l('err_SEFIllegal').l('errNote'));
					form_groupings();
					break;
				default:
			  		switch (true) {
						case (isset($_POST['add_groupings'])):
							$sql1 = "INSERT INTO "._PRE.'extras'."(name, seftitle, description)
								VALUES(?, ?, ?)";
							if ($result = db() -> prepare($sql1)) {
								$result = dbbind($result, array($name, $seftitle, $description), 'sss'); 
								$r = dbfetch($result, true);
								unset($result);
							}
							break;
						case (isset($_POST['edit_groupings'])):
							$sql2 = "UPDATE "._PRE.'extras'." SET
								name = ?,
								seftitle = ?,
								description = ?
								WHERE id = ? LIMIT 1";
							if ($result = db() -> prepare($sql2)) {
								$result = dbbind($result, array($name, $seftitle, $description, $id), 'sssi'); 
								$r = dbfetch($result, true);
								unset($result);
							}
							break;
						case (isset($_POST['delete_groupings'])):
							$sql3 = "DELETE FROM "._PRE.'extras'." WHERE id = ? LIMIT 1";
							if ($result = db() -> prepare($sql3)) {
								$result = dbbind($result, array($id), 'i'); 
								$r = dbfetch($result, true);
							}
							break;
			  		}
				echo notification(0,'','groupings');
			}
			break;
		case 'admin_category':
		case 'admin_subcategory':
//		echo '<pre>'; print_r($_POST); echo '</pre>';
			switch (true) {
				case (empty($name)):
					echo notification(1,l('err_TitleEmpty').l('errNote'));
					form_categories();
					break;
				case (empty($seftitle)):
					echo notification(1,l('err_SEFEmpty').l('errNote'));
					form_categories();
					break;
				case (isset($_POST['add_category']) && check_if_unique('subcat_name', $name, '', $subcat)):
					echo notification(1,l('err_TitleExists').l('errNote'));
					form_categories();
					break;
				case (isset($_POST['add_category']) && check_if_unique('subcat_seftitle', $seftitle, '', $subcat)):
					echo notification(1,l('err_SEFExists').l('errNote'));
					form_categories();
					break;
				case (isset($_POST['edit_category']) && $subcat == 0 && check_if_unique('cat_name_edit', $name, $id, '')):
					echo notification(1,l('err_TitleExists').l('errNote'));
					form_categories();
					break;
				case (isset($_POST['edit_category']) && $subcat == 0 && check_if_unique('cat_seftitle_edit', $seftitle, $id, '')):
					echo notification(1,l('err_SEFExists').l('errNote'));
					form_categories();
					break;
				case (isset($_POST['edit_category']) && $subcat != 0 && check_if_unique('subcat_name_edit', $name, $id, $subcat)):
					echo notification(1,l('err_TitleExists').l('errNote'));
					form_categories();
					break;
				case (isset($_POST['edit_category']) && $subcat != 0 && check_if_unique('subcat_seftitle_edit', $seftitle, $id, $subcat)):
					echo notification(1,l('err_SEFExists').l('errNote'));
					form_categories();
					break;
				case (cleancheckSEF($seftitle) == 'notok'):
					echo notification(1,l('err_SEFIllegal').l('errNote'));
					form_categories();
					break;
				case ($subcat==$id && $id != 0):
					echo ' '.$id;
					echo notification(1,l('errNote'));
					form_categories();
					break;
				default:
					switch(true) {
						case(isset($_POST['add_category'])):
							$catorder = stats('categories', '', 'subcat = '.$subcat, false);	
							$catorder = $catorder + 1;
							$query = "INSERT INTO "._PRE.'categories'."
								(name, seftitle, description, published, catorder, subcat) VALUES(?, ?, ?, ?, ?, ?)";
							if ($sql = db() -> prepare($query)) {
								$sql = dbbind($sql, array($name, $seftitle, $description, $publish_category, $catorder, $subcat), 'ssssii');
								$r = dbfetch($sql, true);
								unset($sql);
							}
							break;
						# EDIT CATEGORY
						case(isset($_POST['edit_category'])):
							$catorder = stats('categories', '', 'subcat = '.$subcat, false);
							$catorder = isset($_POST['catorder']) ? $_POST['catorder'] : $catorder + 1;
							$query = "UPDATE "._PRE.'categories'." 
								SET	name = ?, seftitle = ?, description = ?, published = ?, subcat = ?, catorder = ? 
								WHERE id = ?";
							if ($res = db() -> prepare($query)) {
								$arr = array($name, $seftitle, $description, $publish_category, $subcat, $catorder, $id);
								$res = dbbind($res, $arr, 'ssssiii');
								$data = dbfetch($res, true);
							} break;
						case (isset($_POST['delete_category'])):
							$any_subcats = retrieve('COUNT(id)','categories','subcat',$id);
							$any_articles = retrieve('COUNT(id)','articles','category',$id);
							if ($any_subcats > 0 || $any_articles > 0) {
								echo notification(1,l('warn_catnotempty'),'');
								echo '<p><a href="'._SITE.'administration/" title="'.l('administration').'">
									'.l('administration').'</a>  OR  <a href="'._SITE.'?action=process&amp;task=delete_category_all&amp;id='.$id.'" onclick="javascript: return pop(\'x\')" title="'.l('administration').'">
									'.l('empty_cat').'</a></p>';
								$no_success = true;
							} else {delete_cat($id);}
							break;
					}
				$success = isset($no_success) ? '' : notification(0,'','snews_categories');
				echo $success;
			}
			break;
		case 'reorder':
			if (isset($_POST['reorder'])) {
				switch ($_POST['order']){
					case 'snews_articles':
					case 'extra_contents':
					case 'snews_pages':
						$table = 'articles';
						$order_type = 'artorder';
						$remove = 'page_';
						break;
					case 'snews_categories':
						$table = 'categories';
						$order_type = 'catorder';
						$remove = 'cat_';
						break;
				}
				foreach ($_POST as $key => $value){
					$type_id = str_replace($remove,'',$key);
					$key = clean(cleanXSS(trim($value)));
					if ($key != 'reorder' && $key != 'order' && $key != $table && $key != l('order_content') && $key != $_POST['order']){
						$query = "UPDATE "._PRE.$table." SET $order_type = ? WHERE id = ? LIMIT 1;";
						if ($result = db() -> prepare($query)) {
							$result = dbbind($result, array($value, $type_id), 'ii');
							$data = dbfetch($result, true);
						}
					}
				}
				echo notification(0,l('please_wait'));
				echo '<meta http-equiv="refresh" content="1; url='._SITE.$_POST['order'].'/">';
			}
			break;
		case 'admin_article':
			$_SESSION[_SITE.'temp']['title'] = $_POST['title'];
			$_SESSION[_SITE.'temp']['seftitle'] = $_POST['seftitle'];
			$_SESSION[_SITE.'temp']['text'] = $_POST['text'];
			switch (true) {
				case (empty($title)):
					echo notification(1,l('err_TitleEmpty').l('errNote'));
					form_articles('');
					unset($_SESSION[_SITE.'temp']);
					break;
				case (empty($seftitle)):
					echo notification(1,l('err_SEFEmpty').l('errNote'));
					$_SESSION[_SITE.'temp']['seftitle'] = $_SESSION[_SITE.'temp']['title'];
					form_articles('');
					unset($_SESSION[_SITE.'temp']);
					break;
				case (cleancheckSEF($seftitle) == 'notok'):
					echo notification(1,l('err_SEFIllegal').l('errNote'));
					form_articles('');
					unset($_SESSION[_SITE.'temp']);
					break;
				case ($position == 1 && $_POST['article_category'] != $category && isset($_POST['edit_article'])
						&& check_if_unique('article_title', $title, $category, '')):
					echo notification(1,l('err_TitleExists').l('errNote'));
					form_articles('');
					unset($_SESSION[_SITE.'temp']);
					break;
				case ($position == 1 && $_POST['article_category'] != $category && isset($_POST['edit_article'])
						&& check_if_unique('article_seftitle', $seftitle, $category, '')):
					echo notification(1,l('err_SEFExists').l('errNote'));
					form_articles('');
					unset($_SESSION[_SITE.'temp']);
					break;
				case (!isset($_POST['delete_article']) && !isset($_POST['edit_article'])
						&& check_if_unique('article_title', $title, $category, '')):
					echo notification(1,l('err_TitleExists').l('errNote'));
					form_articles('');
					unset($_SESSION[_SITE.'temp']);
					break;
				case (!isset($_POST['delete_article']) && !isset($_POST['edit_article'])
						&& check_if_unique('article_seftitle', $seftitle, $category, '')):
					echo notification(1,l('err_SEFExists').l('errNote'));
					form_articles('');
					unset($_SESSION[_SITE.'temp']);
					break;
				default:
					$pos = $position;
					$sub = !empty($category) ? ' AND category = '.$category : '';
					$curr_artorder = retrieve('artorder','articles','id',$id);
					if (!$curr_artorder){
						$artorder = 1;
					} else {
						$artorder = $curr_artorder;
					}
					switch ($pos) {
						case 1: $link = 'snews_articles'; break;
						case 2: $link = 'extra_contents'; break;
						case 3: $link = 'snews_pages'; break;
					}
					switch (true) {
						case (isset($_POST['add_article'])):
							$query = "INSERT INTO "._PRE.'articles '."(
								title, seftitle, text, date, category,
								position, extraid, page_extra, displaytitle,
								displayinfo, commentable, published, description_meta,
								keywords_meta, show_on_home, show_in_subcats, artorder)
							VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,	?, ?)";
							if ($result = db() -> prepare($query)) {
								$result = dbbind($result, array($title, $seftitle, $text, $date, $category,
								$position, $def_extra, $page, $display_title,
								$display_info, $commentable, $publish_article,
								$description_meta, $keywords_meta, $show_on_home,
								$show_in_subcats, $artorder), 'ssssiissssssssssi');
								$data = dbfetch($result, true);
							}	
							break;
						case (isset($_POST['edit_article'])):
							$category = $position == 3 ? 0 : $category;
							$old_pos = retrieve('position','articles','id',$id);
							// Only do this if page is changed to art/extra
							if ($position != $old_pos && $old_pos == 3) {
								$chk_extra_query = "SELECT id FROM "._PRE.'articles'."
									WHERE position = 2 AND category = -3 AND  page_extra = ?";
								if ($rextra = db() -> prepare($chk_extra_query)) {
									$rextra = dbbind($rextra, array($id), 'i');
									while ($xtra = dbfetch($rextra, true)) {
										$xtra_id = $xtra['id'];
										$sql = "UPDATE "._PRE.'articles'." SET category = ?, page_extra = ?
											WHERE id = ?";
										if ($rextra = db() -> prepare($chk_extra_query)) {
											$rextra = dbbind($rextra, array('0', '', $xtra_id), 'isi');
											$ok = dbfetch($rextra, true);
										}
									}
								}
							}
							if ($fpost_enabled == true) {
								$future = "date = '$date',";
								//allows backdating of article
								$publish_article = strtotime($date) < time() ? 1 : $publish_article;
							} else {$future = '';}
							$art_qwr = "UPDATE "._PRE.'articles'." SET
								title = ?, seftitle = ?, text = ?, ".$future." category = ?, position = ?, 
								extraid = ?, page_extra = ?, displaytitle = ?, displayinfo = ?, commentable = ?,
								published = ?, description_meta = ?, keywords_meta = ?, show_on_home = ?,
								show_in_subcats = ?, artorder = ? WHERE id = ?";
							if ($res_art = db() -> prepare($art_qwr)) {
								$res_art = dbbind($res_art, array($title, $seftitle, $text, $category, $position, $def_extra, $page,
									$display_title, $display_info, $commentable, $publish_article, $description_meta, $keywords_meta,
									$show_on_home, $show_in_subcats, $artorder, $id), 'sssiisssssissssii');
								$dart = dbfetch($res_art, true);
							}
							break;
						case(isset($_POST['delete_article'])):
							if ($position == 3) {
								$chk_extra_query = "SELECT id FROM "._PRE.'articles'."
									WHERE position = 2 AND category = -3 AND  page_extra = $id";
								if ($res1 = db() -> query($chk_extra_query)) {
									while ($xtra = dbfetch($res1)) {
										$xtra_id = $xtra['id'];
										$extra2 = "UPDATE "._PRE.'articles'." SET category = ? page_extra = ? WHERE id = ?";
										if ($res_xtra = db() -> prepare($extra2)) {
											$res_art = dbbind($res_art, array('0', '', $xtra_id), 'isi');
										}
									}
								}
							}
							mysql_query("DELETE FROM "._PRE.'articles'." WHERE id = $id");
							mysql_query("DELETE FROM "._PRE.'comments'." WHERE articleid = $id");
							if ($id == s('display_page')) {
								mysql_query("UPDATE "._PRE.'settings'." SET
									VALUE = 0 WHERE name = 'display_page'");
							}
							break;
					}
				echo notification(0,'',$link);
				unset($_SESSION[_SITE.'temp']);
			}
			break;
		case 'editcomment':
			$articleID = retrieve('articleid', 'comments', 'id', $commentid);
			$articleSEF = retrieve('seftitle', 'articles', 'id', $articleID);
			$articleCAT = retrieve('category','articles','seftitle',$articleSEF);
			$postCat = cat_rel($articleCAT, 'seftitle');
			$link = $postCat.'/'.$articleSEF;
			if (isset($_POST['submit_text'])) {
				mysql_query("UPDATE "._PRE.'comments'." SET
					name = '$name',
					url = '$url',
					comment = '$comment',
					approved = '$approved'
					WHERE id = $commentid");
			} else if (isset($_POST['delete_text'])) {
				mysql_query("DELETE FROM "._PRE.'comments'." WHERE id = $commentid");
			}
			echo notification(0,'',$link);
			break;
		case 'deletecomment':
			$commentid = $_GET['commentid'];
			$articleid = retrieve('articleid', 'comments', 'id', $commentid);
			$articleSEF = retrieve('seftitle', 'articles', 'id', $articleid);
			$articleCAT = retrieve('category','articles','id', $articleid);
			$postCat = cat_rel($articleCAT, 'seftitle');
			$link = $postCat.'/'.$articleSEF;
       		mysql_query("DELETE FROM "._PRE.'comments'." WHERE id = $commentid");
			echo notification(0,'', $link);
			echo '<meta http-equiv="refresh" content="1; url='._SITE.$postCat.'/'.$articleSEF.'/">';
			break;
		case 'delete_category_all':
			$art_query = mysql_query("SELECT id FROM "._PRE.'articles'." WHERE category = $id");
			while ($rart = mysql_fetch_array($art_query)) {
				mysql_query("DELETE FROM "._PRE.'comments'." WHERE articleid = $rart[id]");
			}
			mysql_query("DELETE FROM "._PRE.'articles'." WHERE category = $id");
			$sub_query = mysql_query("SELECT id FROM "._PRE.'categories'." WHERE subcat = $id");
			while ($rsub = mysql_fetch_array($sub_query)) {
				$art_query = mysql_query("SELECT id FROM "._PRE.'articles'." WHERE category = $rsub[id]");
				while ($rart = mysql_fetch_array($art_query)) {
					mysql_query("DELETE FROM "._PRE.'comments'." WHERE articleid = $rart[id]");
				}
				mysql_query("DELETE FROM "._PRE.'articles'." WHERE category = $rsub[id]");
			}
			mysql_query("DELETE FROM "._PRE.'categories'." WHERE subcat = $id"); delete_cat($id);
			echo notification(0,'', 'snews_categories');
			break;
		case 'hide':
        case 'show':
            $id = $_GET['id'];
            $item = $_GET['item'];
            $back = $_GET['back'];
            $no_yes = $task == 'hide' ? 'NO' : 'YES';
            switch ($item) {
                case 'snews_articles':
                	$order = 'artorder';
                	$link = empty($back) ? 'snews_articles' : $back;
                	break;
                case 'extra_contents':
                	$order = 'artorder';
                	$link = empty($back) ? 'extra_contents' : $back;
                	break;
                case 'snews_pages':
                	$order = 'artorder';
                	$link = empty($back) ? 'snews_pages' : $back;
                	break;
            }
            $item = 'articles';
            $query = "UPDATE "._PRE."$item SET visible = ?  WHERE id = ? ";
            if ($result = db() -> prepare($query)) {
            	$result = dbbind($result, array($no_yes, $id), 'si'); 
				$result = dbfetch($result, true);
			} echo notification(0,l('please_wait'));
            echo '<meta http-equiv="refresh" content="1; url='._SITE.$link.'/">';
        break;
		}
	}
}

?>