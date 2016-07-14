<?php

// PREPARING ARTICLE FOR XML USED ON FEED
function strip($text) {
	$search = array('/\[include\](.*?)\[\/include\]/', '/\[func\](.*?)\[\/func\]/', '/\[break\]/', '/</', '/>/');
	$replace = array('', '', '', '<', '>');
	$output = preg_replace($search, $replace, $text);
	$output = stripslashes(strip_tags($output, '<a><img><h1><h2><h3><h4><h5><ul><li><ol><p><hr><br><b><i><strong><em><blockquote>'));
	return $output;
}

// RSS FEED - ARTICLES/PAGES/COMMENTS
function rss_contents($rss_item) {
	global $categorySEF, $subcatSEF, $articleSEF, $_ID, $commentsPage;
 	header('Content-type: text/xml; charset='.s('charset').'');
 	$limit = s('rss_limit');
 	switch($rss_item) {
		case 'rss-articles':
			$heading = l('articles');
			$table = 'articles';
			$where = 'position = 1 AND visible = \'YES\' AND published = 1';
			$order = 'ORDER BY date';
			break;
		case 'rss-pages':
			$heading = l('pages');
			$table = 'articles';
			$where = 'position = 3 AND visible = \'YES\' AND published = 1';
			$order = 'ORDER BY date';
			break;
		case 'rss-comments':
			$heading = l('comments');
			$table = 'comments';
			$where = 'approved = \'True\'';
			$order = 'ORDER BY id';
			break;
	}
 	echo '<?xml version="1.0" encoding="'.s('charset').'"?>
 		<rss version="2.0"><channel>
 		<title><![CDATA['.s('website_title').']]></title>
 		<description><![CDATA['.$heading.']]></description>
 		<link>'._SITE.'</link>
 		<copyright><![CDATA[Copyright '.s('website_title').']]></copyright>
 		<generator>sNews CMS</generator>';
 	$numrows = stats($table, '', $where);
 	$comments_order = s('comments_order');
 	$ordinal = $comments_order == 'DESC' ? 1 : $numrows;
 	$comment_limit = s('comment_limit') < 1 ? 1 : s('comment_limit');
 	$comments_order = s('comments_order'); $comment_link = '';
 	$query = "SELECT * FROM "._PRE.$table." WHERE $where $order DESC LIMIT $limit";
 	if ($result = db() -> query($query)) {
		while ($r = dbfetch($result)) {	
			switch($rss_item) {
				case 'rss-articles':
				case 'rss-pages':
					$date = date('D, d M Y H:i:s +0000', strtotime($r['date']));
					if ($r['category'] == 0) {
						$categorySEF = '';
					} else {
						$categorySEF = cat_rel($r['category'], 'seftitle').'/';
					}
					$articleSEF = $r['seftitle'];
					$title = $r['title'];
					$text = $r['text'];
					break;
				case 'rss-comments':
					$subquery = "SELECT id FROM "._PRE.'comments'."
						WHERE articleid = ".$r['articleid']."
						ORDER BY id $comments_order";
					if ($subresult = db() -> query($subquery)) {
						$num = 1;
						while ($subr = dbfetch($subresult)) {
							if ($subr['id'] == $r['id']) {
								$ordinal = $num;
							} $num++;
						}
					}
					$page = ceil($ordinal / $comment_limit);
					$articleSEF = retrieve('seftitle', 'articles', 'id', $r['articleid']);
					$articleCat = retrieve('category', 'articles', 'id', $r['articleid']);
					$articleTitle = retrieve('title', 'articles', 'id', $r['articleid']);
					if ($articleCat == 0) {
						$categorySEF = '';
					} else {
						$categorySEF = cat_rel($articleCat, 'seftitle').'/';
					}
					if (!empty($articleSEF)) {
						$paging = $page > 1 ? $page.'/' : '';
						$comment_link = 'c_'.$paging.'#'.l('comment').$ordinal;
					}
					$date = date('D, d M Y H:i:s +0000', strtotime($r['time']));
					$title = $articleTitle.' - '.$r['name'];
					$text = $r['comment'];
					break;
			}
			$link = _SITE.$categorySEF.$articleSEF.'/'.$comment_link;
			$item  =
				'<item>
				<title><![CDATA['.strip($title).']]></title>
				<description>
					<![CDATA[
					'.strip($text).'
					]]>
				</description>
				<pubDate>'.$date.'</pubDate>
				<link>'.$link.'</link>
				<guid>'.$link.'</guid>
				</item>';
				echo $item;
		}
	}
	echo '</channel></rss>';
	exit;
}

?>