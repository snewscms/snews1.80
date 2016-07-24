<?php

// SITEMAP
function public_sitemap() {
	echo '<h2>'.l('sitemap').'</h2>
		<h3><strong>'.l('pages').'</strong></h3>
		<ul>';
	$link = '<li><a href="'._SITE;
	echo $link.'">'.l('home').'</a></li>';
	echo $link.'archive/">'.l('archive').'</a></li>';
	$query = "SELECT id,title,seftitle
		FROM "._PRE."articles
		WHERE position = 3
			AND published = 1
			AND visible = 'YES'
			AND id <> ".s('display_page')."
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
	$art_query = "SELECT title, seftitle, date
		FROM "._PRE."articles
		WHERE position = 1
			AND published = 1
			AND visible = 'YES'";
	$cat_query = "SELECT id, name, seftitle, description, subcat
		FROM "._PRE."categories
		WHERE published = 'YES'
			AND subcat = 0
			ORDER BY catorder, id";
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
				}
				echo '</ul>';
			}
			$subcat = "SELECT id, name, seftitle, description, subcat
				FROM "._PRE."categories
				WHERE published = 'YES'
					AND subcat = $c[id]
				ORDER BY catorder ASC";
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
						}
						echo '</ul>';
					}
					echo '</li>';
				}
				echo '</ul>';
			}
			echo '</li>';
		}
		echo '</ul>';
	} else {
		echo '<li>'.l('no_articles').'</li></ul>';
	}
}


?>