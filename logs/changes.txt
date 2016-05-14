====================================================================================
								CHANGES LOG:
====================================================================================


NEW FUNCTIONS
-------------
- value
- ini_value
- db (Database connections), all configurations become independent file (config.php)
- dbfetch to save with PDO
- readAddons
- set_error
- logout
- show_404
- clean_mysql
- searchform2
- verify_login


NO CHANGES
-----------
- token
- site
- checkMathCaptcha
- mathCaptcha
- checkUserPass
- check_category
- paginator
- searchform
- login_link
- html_input
- posting_time
- strip
- entity
- file_incude
- br2nl
- cleanSEF
- cleancheckSEF



MODIFIED
-------------
[populate_retr_cache]
- fix mysql_* (deprecated) for PDO

[retrieve]
- fix mysql_* (deprecated) for PDO
- return text if single column otherwise return an array

[tags]
- Removed as a global array and become a function


CONSTANTS
- Read information from config file

[s]
- fix mysql_* (deprecated) for PDO

[clean]
- removed mysql_real_escape_string
- and replaced with: strip_tags(htmlspecialchars($text));

[lang/eng.php] - language file
- transform global variable into a function
- lines not changed

LANGUAGE VARIABLES
- moved some lines into [l] function
- no global variable used anymore

[update_articles]
- fix mysql_* (deprecated) for PDO

[cat_rel]
- fix mysql_* (deprecated) for PDO

[stats]
- fix mysql_* (deprecated) for PDO
- return max or count rows

[notification]
- add new option "home" to return homepage

GET URL
- ADD $_TYPE to identify correctly

[title] Function
- Replace include admn.js with (src = "js/admin.js")

[breadcrumbs]
- No changes, only format structure

[categories]
- fix mysql_* (deprecated) for PDO
- add ignore categories on config file

[subcategories]
- fix mysql_* (deprecated) for PDO
- change $num for better
- add ignore categories on config file

[pages]
- fix mysql_* (deprecated) for PDO
- removed $_No3
- add ignore pages on config file

[extra]
- fix mysql_* (deprecated) for PDO
- fix characters, spaces and some variables

[menu_articles]
- fix mysql_* (deprecated) for PDO

[articles]
- fix mysql_* (deprecated) for PDO
- fix characters, spaces and some variables
- rewritten function
- it is possible hide some internal pages like "home,archive,contact,sitemap", find config-file to do it

[cleanWords], [xss_clean], [filterTags], [cleanXSS]
- litle changes removed from global and them inside a function

[archive]
- fix mysql_* (deprecated) for PDO

[contact]
- fix mysql_* (deprecated) for PDO

[new_comments]
- fix mysql_* (deprecated) for PDO

[rss_links]
- fix mysql_* (deprecated) for PDO

[login]
- Add sid encrypted and verification

[category_list]
- Add sid encrypted and verification

[send_email]
- Verify if function "mail" is enabled

[center]
- fix mysql_* (deprecated) for PDO
- Everything was check



[ADMINISTRATION]

- Mostly functions were modify 
- fix mysql_* (deprecated) for PDO