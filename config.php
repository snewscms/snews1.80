; <?php exit; ?>


[DATABASE]
; database engine (mysql,sqlite)
engine = "sqlite"

; database.dsn	= "mysql:host=localhost;dbname=snews18"
dsn = "sqlite:snews.db3"

; Database User
user = "root"

; Database Password
pass = ""

; Database Options
options[PDO::ATTR_AUTOCOMMIT] = "false"

; Database Prefix
prefix	= ""




[SECURITY]
; KEY SECURE_ID. Do not forget put same value in admin.php (line n.6)
key_ID = '1234'

; Your HASH String
string_hash = "use_silly_strings"




[OPTIONS]
; page_list order (home, archive, contact, sitemap, pages)
page_list = "home,archive,contact,sitemap,pages"

; Ignore pages that you created
ignore_pages = ""

; Ignore category + sub-Categories
ignore_cats = ""

; Divider character
divider = '&middot;'

; Used in article pagination links
paginator = 'p_'

; Used in comments pagination links
comment_pages = 'c_'



[INFO_TAGS]
; Infoline 
infoline = '<p class="date">,readmore,comments,date,edit,</p>'

; Comments 
comments = '<p class="meta">,name, $on ,date,edit,</p>,<p class="comment">,comment,</p>'
