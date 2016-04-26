<?php
/*------------------------------------------------------------------------------
  sNews Version:	1.8.0
  CodeName:			REBORN
  Developper: 		Rui Mendes
  Copyright (C):	Solucija.com
  Licence:			sNews is licensed under a Creative Commons License.
-------------------------------------------------------------------------------- */

// Start sNews session
session_start();

# SECURITY KEYS
	define('SECURE_ID', '1234');

//error_reporting(E_ALL ^ E_NOTICE);
error_reporting(E_ALL ^ E_NOTICE);	// 0 - No Error Reporting
include('report_errors.php');		// FILE TO DEBUG ERRORS FINAL VERSION ERASE FILE AND THIS LINE

// CONFIGURE DATABASE VARIABLES (eBookCMS.com)
function db_cfg($field) { static $dbcfg;
	if (!$dbcfg) {
		$dbcfg = array(
			'driver' => 'pdo',		// by default is pdo but if your database is mysql (mysqli/pdo)
			'dbtype' => 'sqlite',	// database type
			'server' => 'localhost',
			'database' => 'snews18',
			'username' => 'root',
			'password' => '',
			'dbpath' => 'snews.db3', // only sqlite
			'prefix' => ''
		);
	} return $dbcfg[$field];
}

// DATABASE CONNECTION
function db() { static $conn;
	if (!$conn) { $conn = false;
		$driver = db_cfg('driver');
		$server = db_cfg('server');
		$dbtype = db_cfg('dbtype');
		$database = db_cfg('database');
		$username = db_cfg('username');
		$password = db_cfg('password');
		$dbpath = db_cfg('dbpath');
		// DATABASE TYPE
		switch($dbtype) {
			case "mysql"  : $dbconn = "mysql:host=$server;dbname=$database;"; break;
	  		case "sqlite" : $dbconn = "sqlite:$dbpath"; break;
			case "postgresql" : $dbconn = "pgsql:host=$server dbname=$database"; break;
			case "sqlexpress" : $dbconn = "mssql:host=$server dbname=$database"; break;
			case "firebird" : $dbconn = "firebird:dbname=$server:$dbpath"; break;
		} 
		if ($driver == 'pdo' || $driver == 'PDO') {
			try {$conn = new PDO($dbconn, $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));}
			catch (PDOException $msg) {die ('Connection error, because: '.$msg->getMessage());}
		} else if ($driver == 'mysqli' || $driver == 'MYSQLI') {
			$conn = new mysqli($server, $username, $password, $database);
			if (mysqli_connect_errno()) {printf("Connect failed: %s\n", mysqli_connect_error()); exit();}
		} else {die('<h1>Driver Error.</h1><p>Check your db_cfg (<b>DB_DRIVER</b>)</p>');}
	} return $conn;
}

// eBookCMS - Mysqli Bind and PDO
function dbbind($result, $args, $binds) {
	$driver = db_cfg('driver') != 'mysqli' ? true : false;
	$mysqlnd = function_exists('mysqli_fetch_all');
	if ($driver !== false) {try {$result -> execute($args);} catch (PDOException $msg) {
		die ('Connection error, because: '.$msg->getMessage());} return $result;
	} else {
		$count = is_array($args) ? count($args) : 0;
		$bindParamsMethod = new ReflectionMethod('mysqli_stmt', 'bind_param');
		$bindParamsReferences = array();
		foreach($args as $key => $value) {$bindParamsReferences[$key] = &$args[$key];}
		array_unshift($bindParamsReferences, $binds);
		$bindParamsMethod -> invokeArgs($result, $bindParamsReferences);
		$result -> execute();
		if ($mysqlnd) {return $result -> get_result();} else {return $result;}
	}
}

// eBookCMS - FETCH
function dbfetch($result, $prepared = false) {
	$driver = db_cfg('driver');
	$mysqlnd = function_exists('mysqli_fetch_all');
	if (!isset($result)) {return null;}
	if ($driver == 'pdo') {return $result -> fetch(PDO::FETCH_ASSOC);} else
	if ($prepared && $driver == 'mysqli' && $mysqlnd) {return $result -> fetch_assoc();} else
	if ($prepared && $driver == 'mysqli' && !$mysqlnd) {
		echo 'Extension "mysqlnd" is disable or not installed. Please use PDO see db_cfg)'; return;
	} else if ($driver == 'mysqli') {return mysqli_fetch_assoc($result);}
}

// SESSION TOKEN
function token() {
	$a = md5(substr(session_id(), 2, 7));
	$b = $_SERVER['HTTP_USER_AGENT'];
	$token = md5($a.$b._SITE);
	return $token;
}

// SMART RETRIEVE FUNCTION
function populate_retr_cache() { global $retr_cache_cat_id, $retr_cache_cat_sef;
	$query = 'SELECT id, seftitle, name FROM '._PRE.'categories';
	if ($result = db() -> query($query)) {
		while ($r = dbfetch($result)) {
			$retr_cache_cat_id[$r['id']] = $r['seftitle'];
			$retr_cache_cat_sef[$r['seftitle']] = $r['name'];
		}
	}
} $retr_init = false;

// RETRIEVE FUNCTION
function retrieve($column, $table, $field, $value) {
	if (is_null($value)) {return null;}
	if ($table == 'categories') { global $retr_cache_cat_id, $retr_cache_cat_sef, $retr_init;
		if (!$retr_init) {populate_retr_cache(); $retr_init = true;}
		if ($column == 'name') {return $retr_cache_cat_sef[$value];} 
		else if ($column == 'seftitle') {return $retr_cache_cat_id[$value];}
	} $retrieve = '';
	$query = "SELECT $column FROM "._PRE."$table WHERE $field = '$value'";
	if ($result = db() -> query($query)) {
		while ($r = dbfetch($result)) {$retrieve = $r[$column];}
	} return $retrieve;
}

// SITE - Automatically detects the scripts location.
function site() {
	$host = 'http://'.$_SERVER['HTTP_HOST'];
	$directory = dirname($_SERVER['SCRIPT_NAME']);
	$website = $directory == '/' ? $host.'/' : $host.$directory.'/';
	return $website;
} $_TYPE = 0;

// INFO LINE TAGS
function tags($tag) { static $tags;
	if (!$tags) {
		$tags = array(
			'infoline' => '<p class="date">,readmore,comments,date,edit,</p>',
			'comments' => '<p class="meta">,name, '.l('on').' ,date,edit,</p>,<p class="comment">,comment,</p>'
		);
	} return $tags[$tag];
}

// CONSTANTS
	# Website
	define('_SITE',site());
	# Prefix
	define('_PRE', db_cfg('prefix'));
	# Set login constant
	define('_ADMIN',(isset($_SESSION[_SITE.'Logged_In']) && $_SESSION[_SITE.'Logged_In'] == token() ? true : false));

// SITE SETTINGS - grab site settings from database
function s($var) { static $site_settings;
	if (!$site_settings) {
		$query = 'SELECT name,value FROM '._PRE.'settings';
		if ($result = db()->query($query)) {
			while ($r = dbfetch($result)) {
				$site_settings[$r['name']] = $r['value'];
			}
		}
	} $value = $site_settings[$var];
	return $value;
}

// eBookCMS - CLEAN URL TO AVOID INJECTION HACK
function clean($text) {
	if (get_magic_quotes_gpc()) {$text = stripslashes($text);}
	$text = strip_tags(htmlspecialchars($text));
	return $text;
}

// CHECK MATH CAPTCHA RESULT
function checkMathCaptcha() {
	$result = false;
   	$testNumber = isset($_SESSION[_SITE.'mathCaptcha-digit']) ? $_SESSION[_SITE.'mathCaptcha-digit'] : 'none';
   	unset($_SESSION[_SITE.'mathCaptcha-digit']);
   	if (is_numeric($testNumber) && is_numeric($_POST['calc']) && ($testNumber == $_POST['calc'])) {
    	$result = true;
    } return $result;
}

// MATH CAPTCHA
function mathCaptcha() {
	$x = rand(1, 9);
	$y = rand(1, 9);
	if (!isset($_SESSION[_SITE.'mathCaptcha-digit'])) {
	    $_SESSION[_SITE.'mathCaptcha-digit'] = $x + $y;
	    $_SESSION[_SITE.'mathCaptcha-digit-x'] = $x;
	    $_SESSION[_SITE.'mathCaptcha-digit-y'] = $y;
	}
	$math = '<p><label for="calc">* '.l('math_captcha').': </label><br />';
	$math .= $_SESSION[_SITE.'mathCaptcha-digit-x'].' + '.$_SESSION[_SITE.'mathCaptcha-digit-y'].' = ';
	$math .= '<input type="text" name="calc" id="calc" /></p>';
	return $math;
}

// USER/PASS CHECK
function checkUserPass($input) {
	$output = clean(cleanXSS($input));
	$output = strip_tags($output);
	if (ctype_alnum($output) === true && strlen($output) > 3 && strlen($output) < 14) {
		return $output;
	} else {return null;}
}

// INCLUDE ADDONS
	$fd = opendir('addons/');
	while (($file = @readdir($fd)) == true) {
		clearstatcache();
		$ext = substr($file, strrpos($file, '.') + 1);
		if ($ext == 'php' || $ext == 'txt') {include_once('addons/'.$file);}
	} closedir($fd); unset($fd, $file, $ext);

// LANGUAGE VARIABLES
	s('language') != 'EN' && file_exists('lang/'.s('language').'.php') == true ? include('lang/'.s('language').'.php') : include('lang/EN.php');

// LANGUAGE
function l($var) {static $lang; global $l;
	if (!$lang) {
		$lang = load_lang();
		# SYSTEM VARIABLES & RESERVED (not to be translated)
		$lang['cat_listSEF'] = 'archive,contact,sitemap,login';
		if (_ADMIN) {
			$lang['cat_listSEF'] .= ',administration,admin_category,admin_article,article_new,extra_new,page_new,snews_categories';
			$lang['cat_listSEF'] .= ',snews_articles,extra_contents,snews_pages,snews_settings,snews_files,logout,groupings,admin_groupings';
		}
		# SET FOCUS
		$lang['js_inc'] = 'login,contact'.(isset($l['focus']) && !empty($l['focus']) ? $l['focus'] : '');
		# DIVIDER CHARACTER
		$lang['divider'] = '&middot;';
		# used in article pagination links
		$lang['paginator'] = 'p_';
		$lang['comment_pages'] = 'c_';
		# list of files & folders ignored by upload/file list routine
		$lang['ignored_items'] = '.,..,cgi-bin,.htaccess,Thumbs.db,snews.php,admin.php,index.php,lib.php,style.css,admin.js,'.s('language').'.php';
		while (list($key, $value) = each($l)) {$lang[$key] = $value;}
	} return $lang[$var];
}

// ARTICLES - FUTURE POSTING
function update_articles() {
	$last_date = s('last_date');
	$updatetime = !empty($last_date) ? strtotime($last_date) : time();
	$dif_time = time() - $updatetime;
	$now = strtotime("now");
	if ($dif_time > 1200 || empty($last_date)) {
		$sql1 = 'UPDATE '._PRE.'articles 
			SET published = ? 
			WHERE published = ?	AND date <= ?';
		if ($q1 = db() -> prepare($sql1)) {$q1 = dbbind($q1, array('1', '2', $now), 'iis');}
		$sql2 = 'UPDATE '._PRE.'settings
			SET value = ?
			WHERE name = ?';
		if ($q2 = db() -> prepare($sql2)) {$q2 = dbbind($q2, array($now, 'last_date'), 'ss');}
	}
}

// CATEGORY CHECK
function check_category($category) {
	$main_menu = explode(',', l('cat_listSEF'));
	if (in_array($category, $main_menu)) {return true;}
	else {return false;}
}

// GET PARENT/CHILD FROM AN id
function cat_rel($var, $column) {
	$categoryid = $var; $parent = '';
	$sub = "SELECT $column FROM "._PRE.'categories'." WHERE id = $categoryid";
	$join = "SELECT parent.$column FROM "._PRE.'categories'." as child
		INNER JOIN "._PRE.'categories'." as parent
			ON parent.id = child.subcat
		WHERE child.id = $categoryid";
	if ($result = db() -> query($join)) {
		while ($r = dbfetch($result)) {$parent = $r[$column].'/';}
	}
	if ($res = db() -> query($sub)) {
		while ($c = dbfetch($res)) {$child = $c[$column];}
	} return $parent.$child;
}

// CONTENTS COUNTER OR MAX
function stats($table, $position, $other = '', $count = true) {
	$field = $count != false ? 'count(DISTINCT id)' : 'MAX(catorder)';
	$pos = !empty($position) ? " WHERE position = $position" : "";
	$alternative = empty($position) && !empty($other)? " WHERE ".$other : "";
	$query = 'SELECT '.$field.' as num FROM '._PRE.$table.$pos.$alternative;
	if ($result = db() -> query($query)) {
		while ($r = dbfetch($result)) {$numrows = $r['num'];}
	} else {$numrows = 0;}
	return $numrows;
}

// NOTIFICATION
function notification($error = 0, $note = '', $link = '') {
	$title = $error == 0 ? l('operation_completed') : ($error !== 2 ? l('warning') : l('admin_error'));
	$note = (!$note || empty($note)) ? '' : '<p>'.$note.'</p>';
	switch(true){
		case (!$link) :
			$goto = '';
			break;
		case ($link == 'home') :
			$goto = '<p><a href="'._SITE.'">'.l('backhome').'</a></p>';
			break;
		case ($link != 'home') :
			$goto = '<p><a href="'._SITE.$link.'/" title="'.$link.'">'.l('back').'</a></p>';
			break;
	}
	if ($error == 2) {
		$_SESSION[_SITE.'fatal'] = $note == '' ? '' : '<h3>'.$title.'</h3>'.$note.$goto;
		echo '<meta http-equiv="refresh" content="0; url='._SITE.$link.'/">';
		return;
	} else {
		$output = '<h3>'.$title.'</h3>'.$note.$goto;
		return $output;
	}
}

if ($_POST) {
	# CHECK LOGIN CREDENTIALS
	if (isset($_POST['Loginform'])  && !_ADMIN) {
		$user = checkUserPass($_POST['uname']);
		$pass = checkUserPass($_POST['pass']);
		unset($_POST['uname'],$_POST['pass']);
		if (checkMathCaptcha() && md5($user) === s('username') && md5($pass) === s('password')) {
			$_SESSION[_SITE.'Logged_In'] = token();
			notification(2, '', 'administration');
		} else { die( notification(2, l('err_Login'), 'login')); }
	}
	# SUBMIT BUT NOT LOGGED
	if (isset($_POST['submit_text']) && !_ADMIN) {
		die (notification(2, l('error_not_logged_in'), 'home'));
	}
}

// CHECK URL - NOT HOME
if ($_GET) {
	if (isset($_GET['category']) && !empty($_GET['category'])) {
		$url = explode('/', clean($_GET['category']));
		# CATEGORY
		$categorySEF = $url[0];
		if (check_category($categorySEF)) {$_catID = 0;}
		# RSS FEEDS
		if (strpos($categorySEF, 'rss-') !== false) {if (function_exists('rss_contents')) {die(rss_contents($categorySEF));} die('No feed addon'); exit;}
		# SUB-CATEGORY
		$subcatSEF = isset($url[1]) ? $url[1] : '';
		# COMMENT PAGE
		if (isset($url[1]) && substr($url[1], 0, 1) == l('comment_pages') && is_numeric(substr($url[1], 1, 1))) {$commentsPage = $url[1];}
		else {$commentsPage = isset($url[3]) ? $url[3] : '';}
		# ARTICLE
		$articleSEF = isset($url[2]) ? $url[2] : '';
		// ADMIN CONTENT CAN SEE EVERYTHING
		if (_ADMIN) {
			$pub_a = ''; $pub_c = ''; $pub_x = '';
		} else {
			$pub_a = ' AND a.published = 1';
			$pub_c = ' AND c.published =\'YES\'';
			$pub_x = ' AND x.published =\'YES\'';
		}
	 	// [TYPE = 1] : QUERY FOR ->  CATEGORY/SUBCATEGORY/ARTICLE/
		if ($articleSEF && substr( $articleSEF, 0, 2) != l('paginator') && substr( $articleSEF, 0, 2) != l('comment_pages')) {
			$MainQuery = 'SELECT
				a.id AS id, title, position, description_meta, keywords_meta,
				c.id AS catID, c.name AS name, c.description, x.name AS xname
				FROM '._PRE.'articles'.' AS a,
					'._PRE.'categories'.' AS c
				LEFT JOIN '._PRE.'categories'.' AS x
					ON c.subcat=x.id
				WHERE a.category=c.id
					'.$pub_a.$pub_c.$pub_x.'
					AND x.seftitle="'.$categorySEF.'"
					AND c.seftitle="'.$subcatSEF.'"
					AND a.seftitle="'.$articleSEF.'"
			'; $_TYPE = 1;
		}
	 	// TWO QUERIES FOR ->  CATEGORY / SUBCATEGORY  /  | OR |  / CATEGORY / ARTICLE /
		else if ($subcatSEF  && substr( $subcatSEF, 0, 2) != l('paginator') && substr( $subcatSEF, 0, 2) != l('comment_pages')) {
			# [TYPE = 2] : TRY ARTICLE - QUERY  FOR -> CATEGORY/ARTICLE/
			$Try_Article = 'SELECT
					a.id AS id, title, position, description_meta, keywords_meta,
					c.id as catID, name, description, subcat
				FROM '._PRE.'articles'.' AS a
				LEFT JOIN '._PRE.'categories'.' AS c
					ON category =  c.id
				WHERE c.seftitle = "'.$categorySEF.'"
					AND a.seftitle ="'.$subcatSEF.'"
					'.$pub_a.$pub_c.'
					AND subcat = 0';
			$num = stats('articles', '', 'seftitle ="'.$subcatSEF.'"');
			if ($num != 0) {
				if ($result = db() -> query($Try_Article)) {$R = dbfetch($result);}
				$_TYPE = 2;
			} else {
	 		# [TYPE = 3] : QUERY  FOR -> CATEGORY/SUBCATEGORY/
				$MainQuery = 'SELECT
					c.id AS catID, c.name AS name, c.description, c.subcat,
					x.name AS xname
				FROM '._PRE.'categories'.' AS x
				LEFT JOIN '._PRE.'categories'.' AS c
					ON  c.subcat = x.id
				WHERE x.seftitle = "'.$categorySEF.'"
					AND c.seftitle = "'.$subcatSEF.'"
					'.$pub_c.$pub_x ;
				$_TYPE = 3;
			} unset($num);
		} else {
			# [TYPE = 4] - PAGINATOR
			if (substr($categorySEF, 0, 2) == l('paginator')) {$MainQuery = ''; $_TYPE = 4;}
			else {
				# [TYPE = 5] : QUERY  FOR -> ARTICLE/
				$Try_Page = 'SELECT id, title, category, description_meta, keywords_meta, position
					FROM '._PRE.'articles'.' AS a
					WHERE seftitle = "'.$categorySEF.'" '.$pub_a.' AND position = 3';
				if ($result = db() -> query($Try_Page)) {$R = dbfetch($result); $_TYPE = 5;}
				# [TYPE = 6] : QUERY  FOR -> CATEGORY/
				if (!$R) {
					$MainQuery ='SELECT id AS catID, name, description
						FROM '._PRE.'categories'.' AS c
						WHERE seftitle = "'.$categorySEF.'" AND subcat = 0 '.$pub_c;
					$_TYPE = 6; unset($Try_Page);
					//$num = stats('categories', '', 'seftitle = "'.$categorySEF.'" AND subcat = 0');
					//if ($num != 0) {} else {$TYPE = 10;}
				}
			}
		}
		// MAIN QUERY
		if (!empty($MainQuery)) {
			if ($main = db() -> query($MainQuery)) {$R = dbfetch($main);}
			else if (!in_array($_GET['action'], explode(',', l('cat_listSEF')))) {
			 echo $categorySEF;
				if (function_exists('public_'.$categorySEF)){
					$TYPE = 10;
				} else {
					$categorySEF = '404';
					header('HTTP/1.1 404 Not Found');
					unset($subcatSEF, $articleSEF);
					set_error();
				}
			} update_articles();
		}
	// ADMIN
	} if (_ADMIN && isset($_GET['action'])) {
		$url = explode('/', clean($_GET['action']));
		$categorySEF = $url[0];
	}
	
// HOME
} else {
	if (s('display_page') !== 0) {$_ID = s('display_page'); $_TYPE = 7;}
	else {$_TYPE = 8;}
} unset($Try_Article, $MainQuery, $result, $pub_a, $pub_b, $pub_c, $pub_x);

// GLOBAL DATA
if (isset($R)) {
	$_ID = !empty($R['id'])	? intval($R['id']) : 0;
	$CAT = !empty($R['category']) ?	$R['category'] : 0;
	$_POS = !empty($R['position']) ? $R['position']: 0;
	$_catID = !empty($R['catID']) ? $R['catID']	: 0;
	$_TITLE = !empty($R['title']) ? $R['title'] : '';
	$_NAME =  !empty($R['name'])  ? $R['name']  : '';
	$_XNAME = !empty($R['xname']) ?	$R['xname'] : '';
	$_KEYW =  !empty($R['keywords_meta']) ?	$R['keywords_meta']	: '';
	$_DESCR = !empty($R['description_meta']) ? $R['description_meta'] : (!empty($R['description']) ? $R['description'] : '');
	unset($R);
}

// SET COMMENTS PAGE FOR -> /CATEGORY/ARTICLE/
if (isset($url[3]) && !$_XNAME) {$commentsPage = $url[2]; $_TYPE = 9;}

// TITLE
function title() {
	global $categorySEF, $_DESCR, $_KEYW, $_TITLE, $_NAME, $_XNAME;
	$lfeed = PHP_EOL.chr(9);
	echo '<base href="'._SITE.'" />'.$lfeed;
	$title  = $_TITLE ? $_TITLE.' - ' : '';
	$title .= $_NAME ? $_NAME.' - ' : '';
   	$title .= $_XNAME ? $_XNAME.' - ' : '';
	if (check_category($categorySEF) == true && $categorySEF != 'administration' && $categorySEF) {
		$title .= l($categorySEF).' - ';
	}
	$title .= s('website_title');
	echo '<title>'.$title.'</title>'.$lfeed;
	echo '<meta http-equiv="Content-Type" content="text/html; charset='.s('charset').'" />'.$lfeed;
	echo '<meta name="description" content="'.(!empty($_DESCR) ? $_DESCR : s('website_description')).'" />'.$lfeed;
	echo '<meta name="keywords" content="'.(!empty($_KEYW) ? $_KEYW : s('website_keywords')).'" />'.PHP_EOL;
	$js_list = explode(',', l('js_inc'));
	if (_ADMIN  || in_array($categorySEF, $js_list)) {
		echo chr(9).'<script type = "text/javascript" src = "js/admin.js"></script>'.PHP_EOL;
	}
}

// BREADCRUMBS
function breadcrumbs() {
	global $categorySEF, $subcatSEF, $_POS, $_TITLE, $_NAME, $_XNAME;
	$subcat = !empty($subcatSEF) &&  substr($subcatSEF, 0, 2) != l('paginator')  ? $subcatSEF : '';
	$link = '<a href="'._SITE.'';
	if (_ADMIN) {
		echo $link.'administration/" title="'.l('administration').'">'.l('administration').'</a> '.l('divider').' ';
	}
	# CATEGORY
	echo (!empty($categorySEF) ? $link.'">'.l('home').'</a>' : l('home'));
	if (!empty($categorySEF) && check_category($categorySEF) == false) {
	 	echo ($categorySEF == '404' ? ' '.l('divider').' HTTP 404' : '');
		echo (!empty($subcat) ? ' '.l('divider').' '.$link.$categorySEF.'/">'.
			 (!empty($_XNAME) ? $_XNAME : $_NAME).'</a>' :
			 (!empty($_NAME) ? ' '.l('divider').' '.$_NAME:'')
		);
		if (!empty($subcat) && $_XNAME) {
			echo ($_POS == 1 ? ' '.l('divider').' '.$link.$categorySEF.'/'.$subcat.'/">'.$_NAME.'</a>' : ' '.l('divider').' '.$_NAME);
		}
		echo (!empty($_TITLE)? ' '.l('divider').' '.$_TITLE : '');
	}
	if (check_category($categorySEF) == true && $categorySEF != 'administration' && $categorySEF) {
		echo ' '.l('divider').' '.l($categorySEF);
	}
}

// LOGIN LOGOUT LINK
function login_link() {
	$login = '<a href="'._SITE;
	$must_login = 'login/" title="'.l('login').'">'.l('login');
	$login .= _ADMIN ? 'administration/" title="'.l('administration').'">'.l('administration').'</a> '.l('divider') : $must_login;
	$login .= _ADMIN ? ' <a href="'._SITE.'logout/" title="'.l('logout').'">'.l('logout') : '';
	$login .= '</a>';
	echo $login;
}

// DISPLAY CATEGORIES
function categories() {
	global $categorySEF;
	$qwr = !_ADMIN ? ' AND a.visible=\'YES\'' : '';
	if (s('num_categories') == 'on') {
		$count = ', COUNT(DISTINCT a.id) as total';
		$join = 'LEFT OUTER JOIN '._PRE.'articles'.' AS a
			ON (a.category = c.id AND a.position = 1  AND a.published = 1'.$qwr.')';
	} else {
		$count = '';
		$join = '';
	}
	$query = 'SELECT c.seftitle, c.name, description, c.id AS parent'.$count.'
		FROM '._PRE.'categories'.' AS c '.$join.'
		WHERE c.subcat = 0 AND c.published = \'YES\'
		GROUP BY c.id
		ORDER BY c.catorder, c.id';
	if ($result = db() -> query($query)) {
		while ($r = dbfetch($result)) {
			$category_title = $r['seftitle'];
			$r['name'] = (s('language')!='EN' && $r['name'] == 'Uncategorized' && $r['parent']==1) ? l('uncategorised') : $r['name'];
			$class = $category_title == $categorySEF ? ' class="current"' : '';
			if (isset($r['total'])) {$num=' ('.$r['total'].')';}
			echo '<li><a'.$class.' href="'._SITE.$category_title.'/" title="'.$r['name'].' - '.$r['description'].'">'.$r['name'].$num.'</a>';
			$parent = $r['parent'];
			if ($category_title == $categorySEF) {subcategories($parent);}
			echo '</li>';
		}
	} else {
		echo '<li>'.l('no_categories').'</li>';
	}
}

// SUB-CATEGORIES
function subcategories($parent) {
	global $categorySEF, $subcatSEF;
	$qwr = !_ADMIN ? ' AND a.visible=\'YES\'' : '';
	if (s('num_categories') == 'on') {
		$count = ', COUNT(DISTINCT a.id) AS total';
		$join = 'LEFT OUTER JOIN '._PRE.'articles'.' AS a
			ON (a.category = c.id AND a.position = 1 AND a.published = 1'.$qwr.')';
	} else {
		$count = '';
		$join = '';
	}
	$query = 'SELECT c.seftitle AS subsef, description, name'.$count.'
		FROM '._PRE.'categories'.' AS c '.$join.'
		WHERE c.subcat = '.$parent.' AND c.published = \'YES\'
		GROUP BY c.id
		ORDER BY c.catorder,c.id';
	if ($result = db() -> query($query)) {
		echo '<ul>';
		while ($s = dbfetch($result)) {
			$subSEF = $s['subsef'];
			$class = $subSEF == $subcatSEF ? ' class="current"' : '';
			$num = isset($s['total']) ? ' ('.$s['total'].')' : '';
			echo '
			<li class="subcat">
				<a'.$class.' href="'._SITE.$categorySEF.'/'.$subSEF.'/" title="'.$s['description'].'">'.$s['name'].$num.'</a>
			</li>';
		}
		echo '</ul>';
	}
}

// DISPLAY PAGES
function pages() {
	global $categorySEF;
	$qwr = !_ADMIN ? ' AND visible=\'YES\'' : '';
	$class = empty($categorySEF) ? ' class="current"' : '';
	echo '<li><a'.$class.' href="'._SITE.'">'.l('home').'</a></li>';
	$class = ($categorySEF == 'archive') ? ' class="current"' : '';
	echo '<li><a'.$class.' href="'._SITE.'archive/">'.l('archive').'</a></li>';
	$query = "SELECT id, seftitle, title FROM "._PRE.'articles'." WHERE position = 3 $qwr ORDER BY artorder ASC, id";
	if ($result = db() -> query($query)) {
		while ($r = dbfetch($result)) {
			$title = $r['title'];
			$class = ($categorySEF == $r['seftitle'])? ' class="current"' : '';
			if ($r['id'] != s('display_page')) {
				echo '<li><a'.$class.' href="'._SITE.$r['seftitle'].'/">'.$title.'</a></li>';
			}
		}
	}
	$class = ($categorySEF == 'contact') ? ' class="current"' : '';
	echo '<li><a'.$class.' href="'._SITE.'contact/">'.l('contact').'</a></li>';
	$class = ($categorySEF == 'sitemap') ? ' class="current"' : '';
	echo '<li><a'.$class.' href="'._SITE.'sitemap/">'.l('sitemap').'</a></li>';
}

// EXTRA CONTENT
function extra($mode='', $styleit = 0, $classname = '', $idname = '') {
	global $categorySEF, $subcatSEF, $articleSEF, $_ID, $_catID;
	if (empty($mode)) {
		$mode = retrieve('seftitle', 'extras', 'id' ,1);
	}
	$qwr = !_ADMIN ? ' AND visible=\'YES\'' : '';
	$mode = strtolower($mode);
	$getExtra = retrieve('id', 'extras', 'seftitle', $mode);
	$subCat = retrieve('subcat', 'categories', 'id', $_catID);
	$getArt = !empty($_ID) ? $_ID : 0;
	if (!empty($subcatSEF)) {$catSEF = $subcatSEF;}
	$url = $categorySEF.(!empty($subcatSEF)? '/'.$subcatSEF:'').(!empty($articleSEF)?'/'.$articleSEF :'');
	$url = !empty($url) ? $url : 'home';
	$sql = 'SELECT
			id, title, seftitle, text, category, extraid, page_extra,
			position, displaytitle, show_in_subcats, visible
		FROM '._PRE.'articles'.'
		WHERE published = 1 AND position = 2 ';
	$query = $sql.(!empty($getExtra) ? ' AND extraid = '.$getExtra : ' AND extraid = 1');
	$query = $query.$qwr.' ORDER BY artorder ASC,id ASC';
	if ($result = db() -> query($query)) {
		while ($r = dbfetch($result)) {
			$category = $r['category'];
			$page = $r['page_extra'];
			switch (true) {
				case ($category == 0 && $page < 1) :
					$print = false;
					break;
				case ($category == 0 && empty($_catID) && $page != '') :
					$print = check_category($catSEF) != true ? true : false;
					break;
				case ($category == $_catID || ($category == $subCat && $r['show_in_subcats'] == 'YES')) :
					$print = true;
					break;
				case ($category == -3 && $getArt == $page) :
					$print = true;
					break;
				case ($category == -3 && $_catID == 0 && $getArt != $page && $page == 0
						&& $categorySEF != '' && !in_array($categorySEF, explode(',', l('cat_listSEF')))
						&& substr( $categorySEF, 0, 2) != l('paginator') ) :
					$print = true;
					break;
				// To show up on all pages only
				case ($category == -1 && $_catID == 0 && $getArt != $page && $page == 0):
					$print = true;
					break;
				// To show up on all categories and pages
				case ($category == -1) :
					$print = true;
					break;
				default :
					$print = false;
			}
			if ($print == true) {
				if ($styleit == 1) {
					$container ='<div';
					$container .= !empty($classname) ? ' class="'.$classname.'"' : '';
					$container .= !empty($idname) ? ' id="'.$idname.'"' : '';
					$container .= '>';
					echo $container;
				}
				if ($r['displaytitle'] == 'YES') {
					echo '<h3>'. $r['title'] .'</h3>';
				}
				file_include($r['text'], 9999000);
				$action = '?action=';
				$visiblity = $r['visible'] == 'YES' ?
					'<a href="'._SITE.$action.'hide&amp;item=snews_articles&amp;id='.$r['id'].'&amp;back='.$url.'">'.l('hide').'</a>' :
					l('hidden').' ( <a href="'._SITE.$action.'show&amp;item=snews_articles&amp;id='.$r['id'].'&amp;back='.$url.'">'.l('show').'</a> )';
				echo _ADMIN ? '<p><a href="'._SITE.'?action=admin_article&amp;id='.$r['id'].'" title="'.l('edit').' '.$r['seftitle'].'">
					'.l('edit').'</a>'.' '.l('divider').' '.$visiblity.'</p>' : '';
				if ($styleit == 1) {
				 echo $url;
					echo '</div>';
				}
			}
		}
	}
}

// PAGINATOR
function paginator($pageNum, $maxPage, $pagePrefix) {
	global $categorySEF,$subcatSEF, $articleSEF,$_ID, $_catID,$_POS, $_XNAME;
	switch (true){
		case !$_ID && !$_catID :
			$uri ='';
			break;
		case $_ID && $_XNAME :
			$uri = $categorySEF.'/'.$subcatSEF.'/'.$articleSEF.'/';
			break;
		case $_POS == 1 || $_XNAME :
			$uri = $categorySEF.'/'.$subcatSEF.'/';
			break;
		default :
			$uri = $categorySEF.'/';
	}
	$link = '<a href="'._SITE.$uri ;
	$prefix = !empty($pagePrefix) ? $pagePrefix : '';
	if ($pageNum > 1) {
		$goTo =  $link;
		$prev = (($pageNum-1)==1 ? $goTo :
			$link.$prefix.($pageNum - 1).'/').'" title="'.l('page').' '.($pageNum - 1).'">
				&lt; '.l('previous_page').'</a> ';
		$first = $goTo.'" title="'.l('first_page').' '.l('page').'">
			&lt;&lt; '.l('first_page').'</a>';
    } else {
		$prev = '&lt; '.l('previous_page');
		$first = '&lt;&lt; '.l('first_page');
	}
	if ($pageNum < $maxPage) {
		$next = $link.$prefix.($pageNum + 1).'/" title="'.l('page').' '.($pageNum + 1).'">
			'.l('next_page').' &gt;</a> ';
		$last = $link.$prefix.$maxPage.'/" title="'.l('last_page').' '.l('page').'">
			'.l('last_page').' &gt;&gt;</a> ';
	} else {
		$next = l('next_page').' &gt; ';
		$last = l('last_page').' &gt;&gt;';
	}
	echo '
		<div class="paginator">
			'.$first.' '.$prev.'
			<strong>['.$pageNum.'</strong> / <strong>'.$maxPage.']</strong>
			'.$next.' '.$last.'
		</div>';
}

// SET TO ONE ERROR
function set_error() {
	echo '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
		"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
		<head>
			<title>Error: 404</title>
			<meta http-equiv="refresh" content="0; url='.site().'404/" />
		</head>
		<body>
			<p class="warning">'.l('error_404').'</p>
		</body>
	</html>';
	exit;
}

// LOGOUT
function logout() {
	session_destroy();
	echo '<meta http-equiv="refresh" content="2; url='._SITE.'">';
	echo '<h2>'.l('log_out').'</h2>';
}

// SHOW ERROR 404 INFORMATION
function show_404() {
	header('HTTP/1.1 404 Not Found');
	echo '<h1>HTTP 404</h1>';
	echo '<p class="warning">'.l('error_404').'</p>';
}

// PREPARE TEXT TO DATABASE
function clean_mysql($text) {
	if ((function_exists("get_magic_quotes_gpc") && get_magic_quotes_gpc()) || 
		(ini_get('magic_quotes_sybase') && (strtolower(ini_get('magic_quotes_sybase')) != "off"))) {
			$text = stripslashes(addslashes($text));
			$text = str_replace('\\\"', '"', $text);
	} else { // If use another RTE you must clean text before save text, ebookrte doesn`t need
		$text = urldecode($text);
		$text = str_replace('\\\"', '"', $text);
		/*$find = array('<', '>');
		$replace = array('&lt;', '&gt;');
		$text = str_replace($find, $replace, $text);*/
	} return $text;
}

// MENU ARTICLES
function menu_articles($start = 0, $size = 5, $cat_specific = 0) {
	global $categorySEF, $_catID,$subcatSEF;
	$no_articles = '<li>'.l('no_articles').'</li>';
	switch ($cat_specific){
		case 1 : $subcat = !empty($_catID) && empty($subcatSEF) ? 'AND c.subcat = '.$_catID : ''; break;
		case 2 : $subcat = !empty($_catID) ? 'AND c.subcat = '.$_catID : ''; break;
		default: $subcat = '';
	}
	$query = 'SELECT
			title,a.seftitle AS asef,date,
			c.name AS name,c.seftitle AS csef,
			x.name AS xname,x.seftitle AS xsef
		FROM '._PRE.'articles'.' AS a
		LEFT OUTER JOIN '._PRE.'categories'.' as c
			ON category = c.id
		LEFT OUTER JOIN '._PRE.'categories'.' as x
			ON c.subcat =  x.id AND x.published =\'YES\'
		WHERE position = 1
			AND a.published = 1
			AND c.published =\'YES\'
			AND a.visible = \'YES\'
			'.$subcat.'
		ORDER BY date DESC
			LIMIT '."$start, $size";
		if ($result = db() -> query($query)) {
			$n = 0;
			while ($r = dbfetch($result)) {
				$name = s('show_cat_names') == 'on' ? ' ('.$r['name'].')' : '';
				$date = date(s('date_format'), strtotime($r['date']));
				$link = isset($r['xsef']) ? $r['xsef'].'/'.$r['csef'] : $r['csef'];
				echo  '<li><a href="'._SITE.$link.'/'.$r['asef'].'/"
					title="'.$r['name'].' / '.$r['title'].' ('.$date.')">'.$r['title'].$name.'</a>
				</li>'; $n++;
			} if ($n == 0) {echo $no_articles;}
		} else {echo $no_articles;}
}

// ARTICLES
function articles() {
	global $categorySEF, $subcatSEF, $_NAME, $articleSEF, $_ID, $_POS, $_catID, $_XNAME, $_TYPE;
	$frontpage = s('display_page'); $num = 0;
	$display = intval(s('display_page'));
	$admin = '<a href="'._SITE.'administration/" title="'.l('administration').'">'.l('administration').'</a>';
	$title_not_found  = '<h2>'.l('none_yet').'</h2>';
	$title_not_found .= _ADMIN ? '<p>'.l('create_new').' '.$admin.'</p>' : '';
	$visible = _ADMIN ? '' : ' AND a.visible=\'YES\' ';
	$on = s('display_pagination') == 'on' ? true : false;
	if ($_catID == 0 && $_TYPE != 4 && $_TYPE != 5 && $_TYPE != 7) {set_error(); return;}
	if ($_TYPE >= 1 && $_TYPE < 10) {
		if ($_TYPE == 1 || $_TYPE == 2 || $_TYPE == 9) {$num = stats('articles', '', 'id='.$_ID);} else
		if (($_TYPE == 3 || $_TYPE == 6) && $_catID != 0) {$num = stats('articles', '', 'category='.$_catID.' AND position = 1');} else
		if ($_TYPE == 5) {$num = stats('articles', '', 'id='.$_ID);} else
		if ($_TYPE == 7  || $_TYPE == 4) {
			$num = $display != 0 && $_TYPE == 7 ? 
				stats('articles', '', 'id='.$_ID) : 
				stats('articles', '', 'show_on_home = "YES" AND position = 1');
		}
		if ($num != 0) {
			$position = $_TYPE == 5 || ($_TYPE == 7 && $display != 0) ? 3 : 1;
			$with_category = $_TYPE != 4 && $_TYPE != 7 ? ' AND category = '.$_catID : ' AND show_on_home = "YES"';
			if ($_TYPE == 5) {$with_category = '';}
			$articleID = $_ID != 0 ? ' AND a.id = '.$_ID.' ' : '';
			$articleCount = s('article_limit');
			$article_limit = (empty($articleCount) || $articleCount < 1) ? 100 : $articleCount;
			$totalPages = ceil($num/$article_limit);
			// PAGINATION
			if ($on == true) {
				if ($articleSEF) {$SEF = $articleSEF;}
				elseif ($subcatSEF) {$SEF = $subcatSEF;}
				else {$SEF = $categorySEF;}
				$currentPage = strpos($SEF, l('paginator')) === 0 ? str_replace(l('paginator'), '', $SEF) : '';
			} 
			if (!isset($currentPage) || !is_numeric($currentPage) || $currentPage < 1) {$currentPage = 1;}
			# QUERY
			$query_articles = 'SELECT
					a.id AS aid,title,a.seftitle AS asef,text,a.date, a.category, 
					a.displaytitle,a.displayinfo,a.commentable,a.visible
				FROM '._PRE.'articles'.' AS a
				WHERE a.position = '.$position.'
					AND a.published = 1 '.$with_category.$articleID.$visible.' 
				ORDER BY a.artorder ASC, a.date DESC
				LIMIT '.($currentPage - 1) * $article_limit.','.$article_limit;
			if ($result = db() -> query($query_articles)) {
				$link = '<a href="'._SITE;
				while ($r = dbfetch($result)) {
					$infoline = $r['displayinfo'] == 'YES' ? true : false;
					$text = stripslashes($r['text']);
					if (!empty($currentPage) && $_ID == 0 && !empty($text)) {
						$short_display = strpos($text, '[break]');
						$shorten = $short_display == 0 ? 9999000 : $short_display;
						if (!empty($text) && $shorten != $short_display && strlen($text)>255) {
							$short_display = strpos($text, '.', 255) + 1;
							$shorten = $short_display > 255 ? $short_display : $shorten;
						}
					} else {
						$shorten = 9999000;
					}
					$comments_num = stats('comments', '', 'articleid = '.$r['aid'].' AND approved = \'True\'');
					$a_date_format = date(s('date_format'), strtotime($r['date']));
					$uri = $r['category'] != 0 ? cat_rel($r['category'], 'seftitle') : '';
					$title = $r['title'];
					if ($r['displaytitle'] == 'YES') {
						if (!$_ID) {echo '<h2 class="big">'.$link.$uri.'/'.$r['asef'].'/">'.$title.'</a></h2>';}
						else {echo '<h2>'.$title.'</h2>';}
					}
					file_include(str_replace('[break]', '',$text), $shorten);
					$commentable = $r['commentable'];
					$hide = '?action=hide&amp;item=snews_articles&amp;id='.$r['aid'].'&amp;back='.$uri;
					$show = '?action=show&amp;item=snews_articles&amp;id='.$r['aid'].'&amp;back='.$uri;
					$visiblity = $r['visible'] == 'YES' ? 
						$link.$hide.'">'.l('hide').'</a>' :
						l('hidden').' ( '.$link.$show.'">'.l('show').'</a> )';
					$edit_link = $link.'?action=admin_article&amp;id='.$r['aid'].'" title="'.$title.'">'.l('edit').'</a> ';
					$edit_link.= ' '.l('divider').' '.$visiblity;
					if (!empty($currentPage)) {
						if ($infoline == true) {
							$tag = explode(',', tags('infoline'));
							foreach ($tag as $tag) {
								switch (true) {
									case ($tag == 'date'):
										echo $a_date_format;
										break;
									case ($tag == 'readmore' && strlen($r['text']) > $shorten):
										echo $link.$uri.'/'.$r['asef'].'/">'.l('read_more').'</a> ';
										break;
									case ($tag == 'comments' && ($commentable == 'YES' || $commentable == 'FREEZ')):
										echo $link.$uri.'/'.$r['asef'].'/#'.l('comment').'1">
										'.l('comments').' ('.$comments_num.')</a> ';
										break;
									case ($tag == 'edit' && _ADMIN):
										echo ' '.$edit_link;
										break;
									case ($tag != 'readmore' && $tag != 'comments' && $tag != 'edit'):
										echo $tag;
										break;
								}
							}
						} else 
						if (_ADMIN) {
							echo '<p>'.$edit_link.'</p>';
						}
					} else if (empty($currentPage) || $_TYPE == 2) {
						if ($infoline == true) {
							$tag = explode(',', tags('infoline'));
							foreach ($tag as $tag ) {
								switch ($tag) {
									case 'date':
										echo $a_date_format;
										break;
									case 'readmore':
									case 'comments': ;
										break;
									case 'edit':
										if (_ADMIN) {echo ' '.$edit_link;}
										break;
									default:
										echo $tag;
								}
							}
						} else if (_ADMIN) {
							echo '<p>'.$edit_link.'</p>';
						}
					}
				}
			}
			//	PAGINATOR
			if ($_TYPE != 2 && !empty($currentPage) && ($num> $article_limit) && $on) {
				paginator( $currentPage, $totalPages, l('paginator'));
			}
			// COMMENTS
			if ($_ID > 0 && $infoline == true) {
				if ($commentable == 'YES') {
					comment('unfreezed');
				} else if ($commentable == 'FREEZ') {
					comment('freezed');
				}
			}
		} else {
			if (_ADMIN) {echo '<h2>'.$title_not_found.'</h2>';}
			else {
				echo '<h2 class="big">'.$_NAME.'</h2>';
				echo '<p>'.l('no_articles').'</p>';
			}
		}
	}
}

// CLEAN - WORD FILTER
function cleanWords($text) {
    if ((strtolower(s('word_filter_enable')) == 'on') && (file_exists(s('word_filter_file')))) {
        $bad_words_from_what = preg_replace('/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/', '', file(s('word_filter_file')));
        $bad_words_from_what = preg_replace('/^(.*)$/', '/\\1/i', $bad_words_from_what);
        $bad_words_to_what = s('word_filter_change');
        $text = preg_replace($bad_words_from_what, $bad_words_to_what, $text);
        return $text;
    } else {
		return $text;
	}
}

// XSS CLEAN
function xss_clean() { static $BlackList;
	if (!$BlackList) {
		$ra1 = array('applet', 'body', 'bgsound', 'base', 'basefont', 'embed', 'frame', 'frameset', 'head', 'html',
	             'id', 'iframe', 'ilayer', 'layer', 'link', 'meta', 'name', 'object', 'script', 'style', 'title', 'xml');
		$ra2 = array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'style', 'script',
	            'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base',
	            'onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy',
	            'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint',
	            'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick',
	            'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged',
	            'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave',
	            'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus',
	            'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload',
	            'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover',
	            'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange',
	            'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit',
	            'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart',
	            'onstop', 'onsubmit', 'onunload');
		$BlackList = array_merge($ra1, $ra2);
	} return $BlackList;
}
$XSS_cache = array();


//FILTER TAGS
function filterTags($source) {
	$tagBlacklist = xss_clean();
	$preTag = NULL;
	$postTag = $source;
	$tagOpen_start = strpos($source, '<');
	while($tagOpen_start !== FALSE) {
		$preTag .= substr($postTag, 0, $tagOpen_start);
		$postTag = substr($postTag, $tagOpen_start);
		$fromTagOpen = substr($postTag, 1);
		$tagOpen_end = strpos($fromTagOpen, '>');
		if ($tagOpen_end === false) break;
		$tagOpen_nested = strpos($fromTagOpen, '<');
		if (($tagOpen_nested !== false) && ($tagOpen_nested < $tagOpen_end)) {
			$preTag .= substr($postTag, 0, ($tagOpen_nested+1));
			$postTag = substr($postTag, ($tagOpen_nested+1));
			$tagOpen_start = strpos($postTag, '<');
			continue;
		}
		$tagOpen_nested = (strpos($fromTagOpen, '<') + $tagOpen_start + 1);
		$currentTag = substr($fromTagOpen, 0, $tagOpen_end);
		$tagLength = strlen($currentTag);
		if (!$tagOpen_end) {
			$preTag .= $postTag;
			$tagOpen_start = strpos($postTag, '<');
		}
		$tagLeft = $currentTag;
		$attrSet = array();
		$currentSpace = strpos($tagLeft, ' ');
		if (substr($currentTag, 0, 1) == '/') {
			$isCloseTag = TRUE;
			list($tagName) = explode(' ', $currentTag);
			$tagName = substr($tagName, 1);
		} else {
			$isCloseTag = FALSE;
			list($tagName) = explode(' ', $currentTag);
		}
		if ((!preg_match('/^[a-z][a-z0-9]*$/i',$tagName)) || (!$tagName) || ((in_array(strtolower($tagName), $tagBlacklist)))) {
			$postTag = substr($postTag, ($tagLength + 2));
			$tagOpen_start = strpos($postTag, '<');
			continue;
		}
		while ($currentSpace !== FALSE) {
			$fromSpace = substr($tagLeft, ($currentSpace+1));
			$nextSpace = strpos($fromSpace, ' ');
			$openQuotes = strpos($fromSpace, '"');
			$closeQuotes = strpos(substr($fromSpace, ($openQuotes+1)), '"') + $openQuotes + 1;
			if (strpos($fromSpace, '=') !== FALSE) {
				if (($openQuotes !== FALSE) && (strpos(substr($fromSpace, ($openQuotes+1)), '"') !== FALSE))
					$attr = substr($fromSpace, 0, ($closeQuotes+1));
					else $attr = substr($fromSpace, 0, $nextSpace);
			} else $attr = substr($fromSpace, 0, $nextSpace);
			if (!$attr) $attr = $fromSpace;
				$attrSet[] = $attr;
				$tagLeft = substr($fromSpace, strlen($attr));
				$currentSpace = strpos($tagLeft, ' ');
		}
		$postTag = substr($postTag, ($tagLength + 2));
		$tagOpen_start = strpos($postTag, '<');
	}
	$preTag .= $postTag;
	return $preTag;
}

// CLEANXSS
function cleanXSS($val) {
	if ($val != "") {
		global $XSS_cache;
		if (!empty($XSS_cache) && array_key_exists($val, $XSS_cache)) return $XSS_cache[$val];
		$source = html_entity_decode($val, ENT_QUOTES, 'ISO-8859-1');
		$source = preg_replace('/&#38;#(\d+);/mi','chr(\\1)', $source);
		$source = preg_replace('/&#38;#x([a-f0-9]+);/mi','chr(0x\\1)', $source);
		while($source != filterTags($source)) {
			$source = filterTags($source);
		}
		$source = nl2br($source);
		$XSS_cache[$val] = $source;
		return $source;
	}
	return $val;
}

// COMMENTS
function comment($freeze_status) {
 	global $categorySEF, $subcatSEF, $articleSEF, $_ID, $commentsPage;
 	if (isset($commentsPage)) {
 		$commentsPage = str_replace(l('comment_pages'),'',$commentsPage);
 	}
 	if (strpos($articleSEF, l('paginator')) === 0) {
 		$articleSEF = str_replace(l('paginator'), '', $articleSEF);
 	}
 	if (!isset($commentsPage) || !is_numeric($commentsPage) || $commentsPage < 1) {
 		$commentsPage = 1;
 	}
 	echo '<h3>'.l('comments').'</h3>';
 	$comments_order = s('comments_order');
 	if (isset($_POST['comment'])) {
		$comment = cleanWords(trim($_POST['text']));
		$comment = strlen($comment) > 4 ? clean(cleanXSS($comment)) : null;
		$name = trim($_POST['name']);
		$name = preg_replace('/[^a-zA-Z0-9_\s-]/', '', $name);
		if (empty($name)) { $name = 'Anonymous'; }
		$name = strlen($name) > 1 ? clean(cleanXSS($name)) : null;
		$url = trim($_POST['url']);
		$url = preg_replace('/[^a-zA-Z0-9_:\/\.-]/', '', $url);
		$url = (strlen($url) > 8 && strpos($url, '?') === false) ? clean(cleanXSS($url)) : null;
		$post_article_id = (is_numeric($_POST['id']) && $_POST['id'] > 0) ? $_POST['id'] : null;
		$ip = (strlen($_POST['ip']) < 16) ? clean(cleanXSS($_POST['ip'])) : null;
		if (_ADMIN) {
			$doublecheck = 1;
			$ident=1;
		} else {
			$contentCheck = retrieve('id', 'comments', 'comment', $comment);
			$ident = !$contentCheck || (time() - $_SESSION[_SITE.'poster']['time']) > s('comment_repost_timer') ||
				$_SESSION[_SITE.'poster']['ip'] !== $ip ? 1 : 0;
			$doublecheck = isset($_SESSION[_SITE.'poster']['article']) && $_SESSION[_SITE.'poster']['article'] === "$comment:|:$post_article_id" &&
				(time()-$_SESSION[_SITE.'poster']['time']) < s('comment_repost_timer') ? 0 : 1;
		}
		if ($ip == $_SERVER['REMOTE_ADDR'] && $comment && $name && $post_article_id  &&
	 		checkMathCaptcha() && $doublecheck == 1 && $ident == 1) {
				$url = preg_match('/((http)+(s)?:(\/\/)|(www\.))([a-z0-9_\-]+)/', $url) ? $url : '';
				$url = substr($url, 0, 3) == 'www' ? 'http://'.$url : $url;
				$time = date('Y-m-d H:i:s');
				unset($_SESSION[_SITE.'poster']);
				$approved = s('approve_comments') != 'on'|| _ADMIN ? 'True' : '';
				$query = 'INSERT INTO '._PRE.'comments (articleid, name, url, comment, time, approved) 
					VALUES (?, ?, ?, ?, ?, ?)';
				if ($sql = db() -> prepare($query)) {
					$sql = dbbind($sql, array($post_article_id, $name, $url, $comment, $time, $approved), 'isssss');
					unset($sql); $fail = false;
				} else {$fail = true;}
				$_SESSION[_SITE.'poster']['article']="$comment:|:$post_article_id";
				$_SESSION[_SITE.'poster']['time'] = time();
				// this is to set session for checking multiple postings.
				$_SESSION[_SITE.'poster']['ip'] = $ip;
				$commentStatus = s('approve_comments') == 'on'&& !_ADMIN ? l('comment_sent_approve') : l('comment_sent');
				// eMAIL COMMENTS
				if (s('mail_on_comments') == 'on' && !_ADMIN) {
					if (s('approve_comments') == 'on') {
						$status = l('approved_text');
						$subject =l('subject_a');
					} else {
						$status = l('not_waiting_approved');
						$subject =l('subject_b');
					}
					$to = s('website_email');
					$send_array = array(
						'to'=>$to,
						'name'=>$name,
						'comment'=>$comment,
						'ip'=>$ip,
						'url'=>$url,
						'subject'=>$subject,
						'status'=>$status);
					send_email($send_array);
				}
				// End of Mail
		} else {
			$commentStatus = l('comment_error');
			$commentReason = l('ce_reasons');
			$fail = true;
			$_SESSION[_SITE.'comment']['name'] = $name;
			$_SESSION[_SITE.'comment']['comment'] = br2nl($comment);
			$_SESSION[_SITE.'comment']['url'] = $url;
			$_SESSION[_SITE.'comment']['fail'] = $fail;
		}
		echo '<h2>'.$commentStatus.'</h2>';
		if (!empty($commentReason)) {
			echo '<p>'.$commentReason.'</p>';
		}
		$postArt = clean(cleanXSS($_POST['article']));
		$postArtID = retrieve('category','articles','id',$post_article_id);
		if ($postArtID == 0) {
			$postCat = '' ;
		} else {
			$postCat = cat_rel($postArtID, 'seftitle').'/';
		}
		if ($fail != false) {
			$back_link = _SITE.$postCat.$postArt;
			echo '<a href="'.$back_link.'/">'.l('back').'</a>';
		} else {
			echo '<meta http-equiv="refresh" content="1; url='._SITE.$postCat.$postArt.'/">';
		}
	} else {
		$commentCount = s('comment_limit');
		$comment_limit = (empty($commentCount) || $commentCount < 1) ? 100 : $commentCount;
		if (isset($commentsPage)) {
			$pageNum = $commentsPage;
		}
		$offset = ($pageNum - 1) * $comment_limit;
		$numrows = stats('comments', '', 'articleid = '.$_ID.' AND approved = \'True\'');
		if ($numrows > 0) {
			$query = 'SELECT
					id,articleid,name,url,comment,time,approved
				FROM '._PRE.'comments'.'
				WHERE articleid = '.$_ID.'
					AND approved = \'True\'
				ORDER BY id '.$comments_order.'
				LIMIT '."$offset, $comment_limit";
			if ($result = db() -> query($query)) {
			$ordinal = 1;
			$date_format = s('date_format');
			$edit_link = ' <a href="'._SITE.'?action=';
				while ($r = dbfetch($result)) {
					$date = date($date_format, strtotime($r['time']));
					$commentNum = $offset + $ordinal;
					$tag = explode(',', tags('comments'));
					foreach ($tag as $tag) {
				 	switch (true) {
						case ($tag == 'date'):
							echo '<a id="'.l('comment').$commentNum.'"
								name="'.l('comment').$commentNum.'"></a>'.$date;
							break;
						case ($tag == 'name'):
							$name = $r['name'];
							echo !empty($r['url']) ?
								'<a href="'.$r['url'].'" title="'.$r['url'].'" rel="nofollow">
								'.$name.'</a> ' : $name;
							break;
						case ($tag == 'comment'):
							echo $r['comment'];
							break;
						case ($tag == 'edit' && _ADMIN):
							echo $edit_link.'editcomment&amp;commentid='.$r['id'].'"
								title="'.l('edit').' '.l('comment').'">'.l('edit').'</a> ';
							echo $edit_link.'process&amp;task=deletecomment&amp;commentid='.$r['id'].'"
								title="'.l('delete').' '.l('comment').'" onclick="return pop()">'.l('delete').'</a>';
							break;
						case ($tag == 'edit'): ;
							break;
						default:
							echo $tag;
					}
				}
				$ordinal++;
			}
		}
		$maxPage = ceil($numrows / $comment_limit);
		$back_to_page = ceil(($numrows + 1) / $comment_limit);
		if ($maxPage > 1) {
			paginator($pageNum, $maxPage,l('comment_pages'));
		}
	}
	if ($freeze_status != 'freezed' && s('freeze_comments') != 'YES') {
	    if ($numrows == 0) {echo '<p>'.l('no_comment').'</p>';}
		// recall and set vars for reuse when botched post
		if(isset($_SESSION[_SITE.'comment']['fail']) && $_SESSION[_SITE.'comment']['fail'] == true) {
			$name = $_SESSION[_SITE.'comment']['name'];
			$comment = $_SESSION[_SITE.'comment']['comment'];
			$url = $_SESSION[_SITE.'comment']['url'];
			unset($_SESSION[_SITE.'comment']);
		} else {
			$url = $name = $comment = '';
			$back_to_page = '';
		}
		// end var retrieval
		$art_value = empty($articleSEF) ? $subcatSEF : $articleSEF;
		echo '<div class="commentsbox"><h2>'.l('addcomment').'</h2>'."\r\n";
		echo '<p>'.l('required').'</p>'."\r\n";
		echo html_input('form', '', 'post', '', '', '', '', '', '', '', '', '', 'post', _SITE, '')."\r\n";
		echo html_input('text', 'name', 'name', $name, '* '.l('name'), 'text', '', '', '', '', '', '', '', '', '')."\r\n";
		echo html_input('text', 'url', 'url', $url, l('url'), 'text', '', '', '', '', '', '', '', '', '')."\r\n";
		echo html_input('textarea', 'text', 'text', $comment, '* '.l('comment'), '', '', '', '', '', '5', '5', '', '', '')."\r\n";
		echo mathCaptcha()."\r\n";
		echo '<p>';
		echo html_input('hidden', 'category', 'category', $categorySEF, '', '', '', '', '', '', '', '', '', '', '')."\r\n";
		echo html_input('hidden', 'id', 'id', $_ID, '', '', '', '', '', '', '', '', '', '', '')."\r\n";
		echo html_input('hidden', 'article', 'article', $art_value, '', '', '', '', '', '', '', '', '', '', '')."\r\n";
		echo html_input('hidden', 'commentspage', 'commentspage', $back_to_page, '', '', '', '', '', '', '', '', '', '', '')."\r\n";
		echo html_input('hidden', 'ip', 'ip', $_SERVER['REMOTE_ADDR'], '', '', '', '', '', '', '', '', '', '', '')."\r\n";
		echo html_input('hidden', 'time', 'time', time(), '', '', '', '', '', '', '', '', '', '', '');
		echo html_input('submit', 'comment', 'comment', l('submit'), '', 'button', '', '', '', '', '', '', '', '', '')."\r\n";
		echo '</p></form></div>';
	} else {
		echo '<p>'.l('frozen_comments').'</p>';
		}
	}
}

// ARCHIVE
function archive($start = 0, $size = 200) {
	echo '<h2>'.l('archive').'</h2>';
	$query = 'SELECT id FROM '._PRE.'articles'.'
		WHERE position = 1
			AND published = 1
			AND visible = \'YES\'
		ORDER BY date DESC
		LIMIT '."$start, $size";
	if ($result = db() -> query($query)) {
		while ($r = dbfetch($result)) {
			$Or_id[] = 'a.id ='.$r['id'];
		} $last = '';
		$Or_id = implode(' OR ', $Or_id);
		$qwr = 'SELECT
				title,a.seftitle AS asef,a.date AS date,
				c.name AS name,c.seftitle AS csef,
				x.name AS xname,x.seftitle AS xsef
			FROM '._PRE.'articles'.' AS a
			LEFT OUTER JOIN '._PRE.'categories'.' as c
				ON category = c.id
			LEFT OUTER JOIN '._PRE.'categories'.' as x
				ON c.subcat =  x.id
			WHERE ('.$Or_id.')
				AND a.published = 1
				AND c.published =\'YES\'
				AND (x.published =\'YES\' || x.published IS NULL)
			ORDER BY date DESC
				LIMIT '."$start, $size";
		$month_names = explode(', ', l('month_names'));
		$dot = l('divider');
		echo '<p>';
		if ($res = db() -> query($qwr)) {
			while ($rr = dbfetch($res)) {
				$year = substr($rr['date'], 0, 4);
				$month = substr($rr['date'], 5, 2) -1;
				$month_name = (substr($month, 0, 1) == 0) ? $month_names[substr($month, 1, 1)] : $month_names[$month];
				if ($last <> $year.$month) {
					echo '<strong>'.$month_name.', '.$year.'</strong><br />';
				}
				$last = $year.$month;
				$link = isset($rr['xsef']) ? $rr['xsef'].'/'.$rr['csef'] : $rr['csef'];
				echo $dot.' <a href="'._SITE.$link.'/'.$rr['asef'].'/">
					'.$rr['title'].' ('.$rr['name'].')</a><br />';
			}
		} echo'</p>';
	} else {echo '<p>'.l('no_articles').'</p>';}
}

// SITEMAP
function sitemap() {
	echo '<h2>'.l('sitemap').'</h2>
		<h3><strong>'.l('pages').'</strong></h3>
		<ul>';
	$link = '<li><a href="'._SITE;
	echo $link.'">'.l('home').'</a></li>';
	echo $link.'archive/">'.l('archive').'</a></li>';
	$query = "SELECT id,title,seftitle
		FROM "._PRE.'articles'."
		WHERE position = 3
			AND published = 1
			AND visible = 'YES'
			AND id <> '".s('display_page')."'
		ORDER BY artorder ASC, date, id";
	if ($result = db() -> query($query)) {
		while ($r = dbfetch($result)) {
			echo $link.$r['seftitle'].'/">'.$r['title'].'</a></li>';
		}
	}
	echo $link.'contact/">'.l('contact').'</a></li>';
	echo $link.'sitemap/">'.l('sitemap').'</a></li>';
	echo '</ul>
		<h3><strong>'.l('articles').'</strong></h3>
		<ul>';
	$art_query = 'SELECT title, seftitle, date
		FROM '._PRE.'articles'.'
		WHERE position = 1
			AND published = 1
			AND visible = \'YES\'';
	$cat_query = 'SELECT id, name, seftitle, description, subcat
		FROM '._PRE.'categories'.'
		WHERE published = \'YES\'
			AND subcat = 0
			ORDER BY catorder,id';
	if ($result = db() -> query($cat_query)) {
		while ($c = dbfetch($result)) {
			$category_title = $c['seftitle'];
			echo '<li><strong><a href="'._SITE.$category_title.'/" title="'.$c['description'].'">
				'.$c['name'].'</a></strong>';
			$catid = $c['id'];
			$query = $art_query.' AND category = '.$catid.' ORDER BY id DESC';
			if ($res = db() -> query($query)) {
				echo '<ul>';
				while ($r = dbfetch($res)) {
					echo '<li>'.l('divider').'  <a href="'._SITE.$category_title.'/'.$r['seftitle'].'/">
						'.$r['title'].'</a></li>';
				} echo '</ul>';
			}
			$subcat = 'SELECT id, name, seftitle, description, subcat
				FROM '._PRE.'categories'.'
				WHERE published = \'YES\'
					AND subcat = '.$c['id'].'
				ORDER BY catorder ASC';
			if ($subcat_result = db() -> query($subcat)) {
				echo '<ul>';
				while ($s = dbfetch($subcat_result)) {
					$subcat_title = $s['seftitle'];
					$subcat_name = $s['name'];
					echo '<li class="subcat"><strong><a href="'.
						_SITE.$category_title.'/'.$subcat_title.'/" title="'.$s['description'].'">'.$subcat_name.'</a></strong>';
					$subcatid = $s['id'];
					$query2 = $art_query.' AND category = '.$subcatid.' ORDER BY id DESC';
					if ($artresult = db() -> query($query2)) {
						echo '<ul>';
						while ($ss = dbfetch($artresult)) {
							echo '<li class="subcat">'.l('divider').'
								<a href="'._SITE.$category_title.'/'.$subcat_title.'/'.$ss['seftitle'].'/">
								'.$ss['title'].'</a></li>';
						} echo '</ul>';
					}
					echo '</li>';
				}
				echo '</ul>';
			}
			echo '</li>';
		}
		echo '</ul>';
	} else {echo '<li>'.l('no_articles').'</li></ul>';}		
}

// CONTACT FORM
function contact() {
 	if (!isset($_POST['contactform'])) {
	$_SESSION[_SITE.'time'] = $time = time();
	echo
	'<div class="commentsbox"><h2>'.l('contact').'</h2>
	<p>'.l('required').'</p>
	<form method="post" action="'._SITE.'" id="post" accept-charset="UTF-8">
	<p><label for="name">* ',l('name'),'</label>:<br />
	<input type="text" name="name" id="name" maxlength="100" class="text" value="" /></p>
	<p><label for="email">* ',l('email'),'</label>:<br />
	<input type="text" name="email" id="email" maxlength="320" class="text" value="" /></p>
	<p><label for="weblink">',l('url'),'</label>:<br />
	<input type="text" name="weblink" id="weblink"  maxlength="160" class="text" value="" /></p>
	<p><label for="message">* ',l('message'),'</label>:<br />
	<textarea name="message" rows="5" cols="5" id="message"></textarea></p>
	',mathCaptcha(),'
	<p><input type="hidden" name="ip" id="ip" value="',$_SERVER['REMOTE_ADDR'],'" />
	<input type="hidden" name="time" id="time" value="',time(),'" />
	<input type="submit" name="contactform" id="contactform" class="button" value="',l('submit'),'" /></p>
	</form>
	</div>';

	} else if( isset( $_SESSION[_SITE.'time'] ) ) {
		$count = $magic = 0;
		if( get_magic_quotes_gpc() ){ $magic = 1; }
		foreach($_POST as $k => $v){
		if($count === 8 ) die;
		if ($magic) $k = stripslashes($v);
		else $$k = $v;
		++$count;
		}
		$to = s('website_email');
		$subject = s('contact_subject');

		$name = (isset($name[0]) && ! isset($name[300]) ) ? trim($name) : null;
		$name = ! preg_match('/[\\n\\r]/', $name) ? $name : die;

		$mail = (isset($email[6]) && ! isset($email[320]) ) ? trim($email) : null;
		$mail = ! preg_match('/[\\n\\r]/', $mail) ? $mail : die;

		$url = (isset($weblink[4]) && ! isset($weblink[160]) ) ? trim($weblink) : null;
		$url = ( strpos($url, '?') === false && ! preg_match('/[\\n\\r]/', $url)) ? $url : null;
		$message = (isset($message[10]) && ! isset($message[6000]) ) ? strip_tags($message) : null;
		$time = ( isset($_SESSION[_SITE.'time']) && $_SESSION[_SITE.'time'] === (int)$time && (time() - $time) > 10) ? $time : null ;
		if ( isset($ip) && $ip === $_SERVER['REMOTE_ADDR'] && $time
		&& $name && $mail && $message && checkMathCaptcha()) {
			unset($_SESSION[_SITE.'time']);
			$send_array = array(
				'to'=>$to,
				'name'=>$name,
				'email'=>$mail,
				'message'=>$message,
				'ip'=>$ip,
				'url'=>$url,
				'subject'=>$subject);
			send_email($send_array);
		} else {
			echo notification(1, l('contact_not_sent'), 'contact');
		}
	}
}

// NEW COMMENTS
function new_comments($number = 5, $stringlen = 30) {
	$query = 'SELECT
			a.id AS aid,title,a.seftitle AS asef,
			category,co.id,articleid,co.name AS coname,comment,
			c.name,c.seftitle AS csef,c.subcat,
			x.name,x.seftitle AS xsef
		FROM '._PRE.'comments'.' AS co
		LEFT OUTER JOIN '._PRE.'articles'.' AS a
			ON articleid = a.id
		LEFT OUTER JOIN '._PRE.'categories'.' AS c
			ON category = c.id AND c.published =\'YES\'
		LEFT OUTER JOIN '._PRE.'categories'.' AS x
			ON c.subcat = x.id AND x.published =\'YES\'
		WHERE a.published = 1 AND (a.commentable = \'YES\' || a.commentable = \'FREEZ\' )
			AND approved = \'True\'
		ORDER BY co.id DESC LIMIT '.$number;
	if ($result = db() -> query($query)) {
	 	$comlim = s('comment_limit'); $num = 0;
	 	$comment_limit = $comlim < 1 ? 1 : $comlim;
	 	$comments_order = s('comments_order');
	 	while ($r = dbfetch($result)) {
			$loopr = "SELECT id FROM "._PRE.'comments'."
				WHERE articleid = '$r[articleid]'
				AND approved = 'True'
				ORDER BY id $comments_order";
			if ($res2 = db() -> query($loopr)) {
				$num = 1;
				while ($r_art = dbfetch($res2)) {
					if ($r_art['id'] == $r['id']) {
						$ordinal = $num;
					}
				$num++;
				}
			}
			$name = $r['coname'];
			$comment = strip_tags($r['comment']);
			$page = ceil($ordinal / $comment_limit);
			$ncom = $name.' ('.$comment;
			$ncom = strlen($ncom) > $stringlen ? substr($ncom, 0, $stringlen - 3).'...' : $ncom;
			$ncom.= strlen($name) < $stringlen ? ')' : '';
			$ncom = str_replace(' ...', '...', $ncom);
			$paging = $page > 1 ? '/'.l('comment_pages').$page : '';
			if (isset($r['xsef'])) { $link = $r['xsef'].'/'; }
			if (isset($r['csef'])) { $link = !empty($link) ? $r['csef'].'/' : ''; }
			$link .= $r['asef'];
			echo '<li><a href="'._SITE.$link.$paging.'/#'.l('comment').$ordinal.'"
					title="'.l('comment_info').' '.$r['title'].'">'.$ncom.'</a>
				</li>';
		}
	} else {echo '<li>'.l('no_comments').'</li>';}
}

// SEARCH FORM
function searchform() { ?>
	<form id="search_engine" method="post" action="<?php echo _SITE; ?>" accept-charset="<?php echo s('charset');?>">
		<p><input class="searchfield" name="search_query" type="text" id="keywords" value="<?php echo l('search_keywords');
?>" onfocus="document.forms['search_engine'].keywords.value='';" onblur="if (document.forms['search_engine'].keywords.value == '') document.forms['search_engine'].keywords.value='<?php echo l('search_keywords'); ?>';" />
		<input class="searchbutton" name="submit" type="submit" value="<?php echo l('search_button')?>" /></p>
	</form>
<?php }

// SEARCH ENGINE
function search($limit = 20) {
	$search_query = clean(cleanXSS($_POST['search_query']));
	echo '<h2>'.l(search_results).'</h2>';
	if (strlen($search_query) < 4 || $search_query == l('search_keywords')) {
		echo '<p>'.l('charerror').'</p>';
	} else {
		$keywords = explode(' ', $search_query);
		$keyCount = count($keywords);
		$query = 'SELECT a.id
			FROM '._PRE.'articles'.' AS a
			LEFT OUTER JOIN '._PRE.'categories'.' as c
				ON category = c.id AND c.published =\'YES\'
			LEFT OUTER JOIN '._PRE.'categories'.' as x
				ON c.subcat =  x.id AND x.published =\'YES\'
			WHERE position != 2
				AND a.published = 1
				AND';
		if(!_ADMIN){
			$query = $query.' a.visible = \'YES\' AND ';
		}
		if ($keyCount > 1) {
			for ($i = 0; $i < $keyCount - 1; $i++) {
				$query = $query.' (title LIKE "%'.$keywords[$i].'%" ||
					text LIKE "%'.$keywords[$i].'%" ||
					keywords_meta LIKE "%'.$keywords[$i].'%") &&';
			}
			$j = $keyCount - 1;
			$query = $query.'(title LIKE "%'.$keywords[$j].'%" ||
				text LIKE "%'.$keywords[$j].'%" ||
				keywords_meta LIKE "%'.$keywords[$j].'%")';
		} else {
			$query = $query.'(title LIKE "%'.$keywords[0].'%" ||
				text LIKE "%'.$keywords[0].'%" ||
				keywords_meta LIKE "%'.$keywords[0].'%")';
		}
		$query = $query.' ORDER BY id DESC LIMIT '.$limit;
		echo $query;
		
		
		
		if ($result = db() -> query($query)) {
			echo '<p><strong>'.$numrows.'</strong> '.l('resultsfound').' <strong>'.stripslashes($search_query).'</strong>.</p>';
			while ($r = dbfetch($result)) {
				$Or_id[] = 'a.id ='.$r['id'];
			}
			$Or_id = implode(' OR ',$Or_id);
			$sql = 'SELECT
					title,a.seftitle AS asef,a.date AS date,
					c.name AS name,c.seftitle AS csef,
					x.name AS xname,x.seftitle AS xsef
				FROM '._PRE.'articles'.' AS a
				LEFT OUTER JOIN '._PRE.'categories'.' as c
					ON category = c.id
				LEFT OUTER JOIN '._PRE.'categories'.' as x
					ON c.subcat =  x.id
				WHERE '.$Or_id;
			if ($res = db() -> query($sql)) {
				while ($s = dbfetch($res)) {
					$date = date(s('date_format'), strtotime($r['date']));
					$name = isset($r['name']) ? ' ('.$r['name'].')' : '';
					if (isset($r['xsef']))  $link = $r['xsef'].'/'.$r['csef'].'/';
					else $link = isset($r['csef']) ? $r['csef'].'/' : '';
					echo '<p><a href="'._SITE.$link.$r['asef'].'/">'.$r['title'].$name.'</a> - '.$date.'</p>';
				}
			}
		} else {
			echo '<p>'.l('noresults').'
				<strong>'.stripslashes($search_query).'</strong>.</p>';
		}
	}
	echo '<p><a href="'._SITE.'">'.l('backhome').'</a></p>';
}



// RSS FEED - LINK BUILDER
function rss_links() {
	$query = 'SELECT COUNT(id) as articles_count,
		(SELECT COUNT(id) FROM '._PRE.'articles WHERE position = 3 AND published = 1) as pages_count,
		(SELECT COUNT(id) FROM '._PRE.'comments WHERE approved = "True" ) as comments_count
		FROM '._PRE.'articles WHERE position = 1 AND published = 1';
	if ($result = db() -> query($query)) {
		$l_error = array(); // catch any errors
		while ($r = dbfetch($result)) {
		    foreach ($r as $k => $v) {
			if ( $v > 0 ) {
			    $l = explode('_', $k);
			    echo '<li><a href="rss-'.$l[0].'/">'.l( 'rss_'.$l[0] ).'</a></li>';
			} else {
			    $l_error[] = $k;
			}
		    }
		}
		if (count($l_error) == 3) {
		    echo '<li>'.l('no_rss').'</li>';
		}
	}
}

// LOGIN
function login() {
	if (!_ADMIN) {
        echo '<div class="adminpanel">
		<h2>'.l('login').'</h2>';
		echo html_input('form', '', 'post', '', '', '', '', '', '', '', '', '', 'post', _SITE.'administration/', '');
		echo '<p>'.l('login_limit').'</p>';
		echo html_input('text', 'uname', 'uname', '', l('username'), 'text', '', '', '', '', '', '', '', '', '');
		echo html_input('password', 'pass', 'pass', '', l('password'), 'text', '', '', '', '', '', '', '', '', '');
		echo mathCaptcha();
		echo '<p>';
		echo html_input('hidden', 'Loginform', 'Loginform', 'True', '', '', '', '', '', '', '', '', '', '', '');
		echo html_input('submit', 'submit', 'submit', l('login'), '', 'button', '', '', '', '', '', '', '', '', '');
		echo '</p></form></div>';
	} else {
		echo '<h2>'.l('logged_in').'</h2>
			<p><a href="'._SITE.'logout/" title="'.l('logout').'">'.l('logout').'</a></p>';
	}
}

// FORM GENERATOR
function html_input($type, $name, $id, $value, $label, $css, $script1, $script2, $script3, $checked, $rows, $cols, $method, $action, $legend) {
	$lbl = !empty($label) ? '<label for="'.$id.'">'.$label.'</label>' : '';
	$ID = !empty($id) ? ' id="'.$id.'"' : '';
	$style = !empty($css) ? ' class="'.$css.'"' : '';
	$js1 = !empty($script1) ? ' '.$script1 : '';
	$js2 = !empty($script2) ? ' '.$script2 : '';
	$js3 = !empty($script3) ? ' '.$script3 : '';
	$attribs = $ID.$style.$js1.$js2.$js3;
	$val = ' value="'.$value.'"';
	$input = '<input type="'.$type.'" name="'.$name.'"'.$attribs;
	switch($type) {
		case 'form': $output = (!empty($method) && $method != 'end') ?
			'<form method="'.$method.'" action="'.$action.'"'.$attribs.' accept-charset="'.s('charset').'">' : '</form>'; break;
		case 'fieldset': $output = (!empty($legend) && $legend != 'end') ?
			'<fieldset><legend'.$attribs.'>'.$legend.'</legend>' : '</fieldset>'; break;
		case 'text':
		case 'password': $output = '<p>'.$lbl.':<br />'.$input.$val.' /></p>'; break;
		case 'checkbox':
		case 'radio': $check = $checked == 'ok' ? ' checked="checked"' : ''; $output = '<p>'.$input.$check.' /> '.$lbl.'</p>'; break;
		case 'hidden':
		case 'submit':
		case 'reset':
		case 'button': $output = $input.$val.' />'; break;
		case 'textarea':
			$output = '<p>'.$lbl.':<br />
			<textarea name="'.$name.'" rows="'.$rows.'" cols="'.$cols.'"'.$attribs.'>'.$value.
			'</textarea></p>'; break;
	}
	return $output;
}

// LISTS CATEGORIES
function category_list($id) {
	if (isset($_GET['id']) && is_numeric($_GET['id']) && !is_null($_GET['id'])) {$var = $id;}
	echo '<select name="subcat" id="subcat">';
	$selected =' selected="selected"';
	$query = 'SELECT id,name FROM '._PRE.'categories 
			WHERE subcat = 0 ORDER BY catorder, id';
	$parent_selection = !empty($var) ? $selected : '';
	if ($result = db() -> query($query)) {
		echo '<option value="0"'.$parent_selection.'>'.l('not_sub').'</option>';
	   	while ($r = dbfetch($result)) {
			$child = retrieve('subcat', 'categories', 'id', $var);
			if ($r['id'] == $child) {
				echo '<option value="'.$r['id'].'"'.$selected.'>'.$r['name'].'</option>';
			} elseif ($id!=$r['id']){
	    		echo '<option value="'.$r['id'].'">'.$r['name'].'</option>';
			}
		}
	} echo '</select>';
}

// ARTICLES - POSTING TIME
function posting_time($time='') {
	echo '<p>'.l('day').':&nbsp;<select name="fposting_day">';
	$thisDay = !empty($time) ? substr($time, 8, 2) : intval(date('d'));
	for($i = 1; $i < 32; $i++) {
		echo '<option value="'.$i.'"';
		if($i == $thisDay) {
			echo ' selected="selected"';
		}
		echo '>'.$i.'</option>';
	}
	echo '</select>&nbsp;&nbsp;'.l('month').':&nbsp;<select name="fposting_month">';
	$thisMonth = !empty($time) ? substr($time, 5, 2) : intval(date('m'));
	for($i = 1; $i < 13; $i++) {
		echo '<option value="'.$i.'"';
		if($i == $thisMonth) {
			echo ' selected="selected"';
		}
		echo '>'. $i .'</option>';
	}
	echo '</select>&nbsp;&nbsp;'.l('year').':&nbsp;<select name="fposting_year">';
   	$PresentYear = intval(date('Y'));
   	$thisYear = !empty($time) ? substr($time, 0, 4) : $PresentYear;
   	for($i = $thisYear-3; $i < $PresentYear + 3; $i++) {
		echo '<option value="'.$i.'"';
		if($i == $thisYear) {
			echo ' selected="selected"';
		}
		echo '>'.$i.'</option>';
	}
	echo '</select>&nbsp;&nbsp;'.l('hour').':&nbsp;<select name="fposting_hour">';
	$thisHour = !empty($time) ? substr($time, 11, 2) : intval(date('H'));
	for($i = 0; $i < 24; $i++) {
		echo '<option value="'.$i.'"';
		if($i == $thisHour) {
			echo ' selected="selected"';
		}
		echo '>'.$i.'</option>';
	}
	echo '</select>&nbsp;&nbsp;'.l('minute').':&nbsp;<select name="fposting_minute">';
	$thisMinute = !empty($time) ? substr($time, 14, 2) : intval(date('i'));
	for($i = 0; $i < 60; $i++) {
		echo '<option value="'.$i.'"';
		if($i == $thisMinute) {
			echo ' selected="selected"';
		}
		echo '>'.$i.'</option>';
	}
	echo '</select></p>';
	return;
}



//BUTTONS
function buttons(){
   	echo '<div class="clearer"></div>
	<p>'.l('formatting').':
	<br class="clearer" />';
   	$formatting = array(
		'strong' => '',
		'em' => 'key',
		'underline' => 'key',
		'del' => 'key',
		'p' => '',
		'br' => ''
	);
   	foreach ($formatting as $key => $var) {
      	$css = $var == 'key' ? $key :'buttons';
      	echo '<input type="button" name="'.$key.'" title="'.l($key).'" class="'.$css.'" onclick="tag(\''.$key.'\')" value="'.
		l($key.'_value').'" />';
	}
   	echo '</p><br class="clearer" /><p>'.l('insert').': <br class="clearer" />';
   	$insert = array('img', 'link', 'include', 'func','intro');
   	foreach ($insert as $key) {
      	echo '<input type="button" name="'.$key.'" title="'.l($key).'" class="buttons" onclick="tag(\''.
		$key.'\')" value="'.l($key.'_value').'" />';
	}
   	echo '<br class="clearer" /></p>';
}

// PREPARING ARTICLE FOR XML
function strip($text) {
	$search = array('/\[include\](.*?)\[\/include\]/', '/\[func\](.*?)\[\/func\]/', '/\[break\]/', '/</', '/>/');
	$replace = array('', '', '', '<', '>');
	$output = preg_replace($search, $replace, $text);
	$output = stripslashes(strip_tags($output, '<a><img><h1><h2><h3><h4><h5><ul><li><ol><p><hr><br><b><i><strong><em><blockquote>'));
	return $output;
}

// HTML ENTITIES
function entity($item) {
	$item = htmlspecialchars($item, ENT_QUOTES, s('charset'));
	return $item;
}

//FILE INCLUSION
function file_include($text, $shorten) {
	$fulltext = substr($text, 0, $shorten);
   if(substr_count ($fulltext, '&')>0){$fulltext = str_replace('&', '&amp;', str_replace('&amp;', '&', $fulltext));}
	if ($shorten < 9999000 && preg_match('<p>',$fulltext)) {
		if (substr_count ($fulltext, '<p>') > substr_count ($fulltext, '</p>')) {
			$fulltext .='</p>';
		}
	}
    $ins = strpos($fulltext, '[/func]');
    if ($ins > 0) {
       	$text = str_replace('[func]', '|&|', $fulltext);
       	$text = str_replace('[/func]', '|&|', $text);
       	$text = explode('|&|', $text);
		$num = count($text) - 1;
		$i = 1;
		while ($i <= $num) {
			$func = explode(':|:', $text[$i]);
			ob_start();
			$returned = call_user_func_array($func[0], explode(',',$func[1]));
			$text[$i] = ob_get_clean();
			if (empty($text[$i])) {
				$text[$i] = $returned;
			}
			$i = $i + 2;
		}
		$fulltext = implode($text);
    }
	$inc = strpos($fulltext, '[/include]');
	if ($inc > 0) {
		$text = str_replace('[include]', '|&|', $fulltext);
		$text = str_replace('[/include]', '|&|', $text);
		$text = explode('|&|', $text);
		$num = count($text);
		$extension = explode(',', s('file_extensions'));
		for ($i = 0; $i<$num; $i++) {
			if ($i == $num) {
				break;
			}
			if (!in_array(substr(strrchr($text[$i], '.'), 1), $extension)) {
				echo substr($text[$i], 0);
			} else {
				if (preg_match('/^[a-z0-9_\-.\/]+$/i', $text[$i])) {
					$filename=$text[$i];
					file_exists($filename) ? include($filename) : print l('error_file_exists');
				} else {
					echo l('error_file_name');
				}
			}
		}
	} else {
		echo $fulltext;
	}
}

// BREAK TO NEW LINE
function br2nl($text){
    $text = str_replace('\r\n','',str_replace("<br />","\n",preg_replace('/<br\\\\s*?\\/??>/i', "\\n", $text)));
    return $text;
}

// SEND EMAIL
function send_email($send_array) {
	if (function_exists('mail')) {
		foreach ($send_array as $var => $value) {$$var = $value;}
	   	$body = isset($status) ? $status."\n" : '';
	   	if (isset($message)) {
	 		$text = l('message').': '."\n".br2nl($message)."\n";
	   	}
	   	if (isset($comment)) {
	   		$text = l('comment').': '."\n".br2nl($comment)."\n";
	   	}
	   	$header = "MIME-Version: 1.0\n";
	   	$header .= "Content-type: text/plain; charset=".s('charset')."\n";
	   	$header .= "From: $name <$email>\r\nReply-To: $name <$email>\r\nReturn-Path: <$email>\r\n";
	   	$body .= isset($name) ? l('name').': '.$name."\n" : '';
	   	$body .= isset($email) ? l('email').': '.$email."\n" : '';
	   	$body .= isset($url) && $url!='' ? l('url').': '.$url."\n\n" : '';
	   	$body .= $text."\n";
	   	$status = mail($to, $subject, $body, $header);
	   	if ($status != false) {echo notification(0, l('contact_sent'), 'home'); return true;}
	   	echo notification(1, l('contact_not_sent'), 'home');
	   	return false;
	} else {
		$message = l('contact_not_sent').'<p>'.l('mail_nexists').'</p>';
		echo notification(1, $message, '');
		return false;
	}
	
}

// MAKE A CLEAN SEF URL
function cleanSEF($string) {
	$string = str_replace(' ', '-', $string);
	$string = preg_replace('/[^0-9a-zA-Z-_]/', '', $string);
	$string = str_replace('-', ' ', $string);
	$string = preg_replace('/^\s+|\s+$/', '', $string);
	$string = preg_replace('/\s+/', ' ', $string);
	$string = str_replace(' ', '-', $string);
	return strtolower($string);
}

// CLEAN CHECK SEF
function cleancheckSEF($string) {
    $ret = !preg_match('/^[a-z0-9-_]+$/i', $string) ? 'notok' : 'ok';
    return $ret;
}

// CENTER
function center() {
	global $_catID, $categorySEF, $subcatSEF, $articleSEF, $_TYPE;
	# FATAL SESSION
	if (isset($_SESSION[_SITE.'fatal'])) {unset($_SESSION[_SITE.'fatal']); return;}
	# BAD ADMIN REQUEST OR NOT LOGGED
	if (isset($_GET['action']) && !_ADMIN) {set_error(); return;}
	# CHECK POST FIRST
	if ($_POST) {
		if (_ADMIN) {include('admin.php');}
		switch(true) {
			case isset($_POST['search_query'])	: search(); return; break;
			case isset($_POST['comment']) 		: comment('comment_posted'); return; break;
			case isset($_POST['contactform'])	: contact(); return; break;
			case isset($_POST['Loginform'])		: administration(); return; break;
			case isset($_POST['action']) 		: if (_ADMIN && $_POST['action'] == 'process') {processing();} else {set_error();} return; break;
			default : 
				if (isset($_POST['addon']) && function_exists('public_'.$categorySEF)) {
					$func = 'public_'.$categorySEF;
					$func(); return; break;
				} else {set_error();} return; break;
		}
	# CHECK GET NOW
	} else if ($_GET) {
		$action = !empty($categorySEF) ? $categorySEF : '404';
		switch ($action) {
			case 'archive'	: archive(); break;
			case 'sitemap'	: sitemap(); break;
			case 'contact'	: contact(); break;
			case 'login'	: login(); break;
			case '404'		: show_404(); break;
			default :
				if (_ADMIN) {
					include('admin.php');
					$action = isset($_GET['action']) ? clean(cleanXSS($_GET['action'])) : $action;
					switch ($action) {
						case 'administration'	:	administration(); break;
						case 'snews_settings'	:	settings(); break;
						case 'snews_categories'	:	admin_categories(); break;
						case 'admin_category'	:	form_categories(); break;
						case 'admin_subcategory':	form_categories('sub'); break;
						case 'groupings'		:	admin_groupings(); break;
						case 'admin_groupings'	:	form_groupings(); break;
						case 'snews_articles'	:	admin_articles('article_view'); break;
						case 'extra_contents'	:	admin_articles('extra_view'); break;
						case 'snews_pages'		:	admin_articles('page_view'); break;
						case 'admin_article'	:	form_articles(''); break;
						case 'article_new'		:	form_articles('article_new'); break;
						case 'extra_new'		:	form_articles('extra_new'); break;
						case 'page_new'			:	form_articles('page_new'); break;
						case 'editcomment'		:	edit_comment(); break;
						case 'snews_files'		:	files(); return; break;
						case 'hide'				:	visibility('hide'); break;
						case 'show'				:	visibility('show'); break;
						case 'logout'			:	logout(); return; break;
						default:
							if (!empty($action)) {
								if (substr($action, 0, 6) == 'admin_' && function_exists($action)) {
									$action(); return;
								} articles();
							}
					}
				} else {
					if (function_exists('public_'.$categorySEF)) {
						$func = 'public_'.$categorySEF;
						$func();
					} else {articles();}
				}
		}
	} else {articles();}
}

?>