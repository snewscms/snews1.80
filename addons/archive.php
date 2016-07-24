<?php

// ARCHIVE
function public_archive($start = 0, $size = 200) {
	echo '<h2>'.l('archive').'</h2>';
	$query = "SELECT id FROM "._PRE."articles
		WHERE position = 1
			AND published = 1
			AND visible = 'YES'
		ORDER BY date DESC
		LIMIT $start, $size";
	if ($result = db() -> query($query)) {
		while ($r = dbfetch($result)) {
			$Or_id[] = 'a.id ='.$r['id'];
		}
		$last = '';
		$Or_id = implode(' OR ', $Or_id);
		$qwr = "SELECT
				title,a.seftitle AS asef,a.date AS date,
				c.name AS name,c.seftitle AS csef,
				x.name AS xname,x.seftitle AS xsef
			FROM "._PRE."articles AS a
			LEFT OUTER JOIN "._PRE."categories as c
				ON category = c.id
			LEFT OUTER JOIN "._PRE."categories as x
				ON c.subcat =  x.id
			WHERE ($Or_id)
				AND a.published = 1
				AND c.published = 'YES'
				AND (x.published ='YES' || x.published IS NULL)
			ORDER BY date DESC
				LIMIT $start, $size";
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
		}
		echo'</p>';
	}
	else {
		echo '<p>'.l('no_articles').'</p>';
	}
}

?>