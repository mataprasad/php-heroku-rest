<?php

/** The name of the database */
define('DB_NAME', 'db_cinemaol');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

define('APP_DEBUG', TRUE);

define('GRID_PAGE_SIZE', "7");

define('APP_NAME', "");


if (!defined('ABSPATH'))
    define('ABSPATH', dirname(__FILE__));

require_once(ABSPATH . '/system.web/server_utility.php');
require_once(ABSPATH . '/system.web/file_upload.php');
require_once(ABSPATH . '/lib/db_helper/shared/ez_sql_core.php');
require_once(ABSPATH . '/lib/db_helper/sqlite/ez_sql_sqlite3.php');