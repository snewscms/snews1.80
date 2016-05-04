; <?php exit; ?>


[DATABASE]
; database engine (mysql,sqlite)
engine = "sqlite"

; database.dsn	= "mysql:host=localhost;dbname=snews17"
dsn = "sqlite:snews.db3"

; Database User
user = "root"

; Database Password
pass = "password"

; Database Options
options[PDO::ATTR_AUTOCOMMIT] = "false"

; Database Prefix
prefix	= ""




[SECURITY]
; KEY SECURE_ID. Do not forget put same value in admin.php (line n.6)
key_ID = '1234'

; HASH string use silly strings
string_hash = "use_silly_strings"




[OPTIONS]
; Ignore pages (home, sitemap, contact,...)
ignore_pages = ""

; Divider character
divider = '&middot;'

; Used in article pagination links
paginator = 'p_'

; Used in comments pagination links
comment_pages = 'c_'
