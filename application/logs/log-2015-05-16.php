<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2015-05-16 16:17:40 --> 404 Page Not Found: Mapa/index
ERROR - 2015-05-16 16:17:41 --> 404 Page Not Found: Mapa/index
ERROR - 2015-05-16 16:18:36 --> 404 Page Not Found: Auth/index
ERROR - 2015-05-16 16:19:16 --> 404 Page Not Found: Auth/index
ERROR - 2015-05-16 16:19:21 --> 404 Page Not Found: Mapa/index
ERROR - 2015-05-16 16:21:23 --> 404 Page Not Found: Mapa/index
ERROR - 2015-05-16 16:21:25 --> 404 Page Not Found: Mapa/index
ERROR - 2015-05-16 16:21:31 --> 404 Page Not Found: Mapa/index
ERROR - 2015-05-16 16:22:10 --> 404 Page Not Found: Mapa/index
ERROR - 2015-05-16 16:23:01 --> Severity: Error --> Call to undefined function base_url() /home/ubuntu/Dropbox/ecgs/test/application/views/map.php 8
ERROR - 2015-05-16 16:25:29 --> Severity: Notice --> Undefined property: Mapa::$ion_auth /home/ubuntu/Dropbox/ecgs/test/application/controllers/Mapa.php 23
ERROR - 2015-05-16 16:25:29 --> Severity: Error --> Call to a member function logged_in() on a non-object /home/ubuntu/Dropbox/ecgs/test/application/controllers/Mapa.php 23
ERROR - 2015-05-16 16:25:41 --> Severity: Notice --> Undefined property: Mapa::$ion_auth /home/ubuntu/Dropbox/ecgs/test/application/controllers/Mapa.php 23
ERROR - 2015-05-16 16:25:41 --> Severity: Error --> Call to a member function logged_in() on a non-object /home/ubuntu/Dropbox/ecgs/test/application/controllers/Mapa.php 23
ERROR - 2015-05-16 16:25:42 --> Severity: Notice --> Undefined property: Mapa::$ion_auth /home/ubuntu/Dropbox/ecgs/test/application/controllers/Mapa.php 23
ERROR - 2015-05-16 16:25:42 --> Severity: Error --> Call to a member function logged_in() on a non-object /home/ubuntu/Dropbox/ecgs/test/application/controllers/Mapa.php 23
ERROR - 2015-05-16 16:26:33 --> Severity: Warning --> pg_connect(): Unable to connect to PostgreSQL server: FATAL:  database "test-gis" does not exist /home/ubuntu/Dropbox/ecgs/test/system/database/drivers/postgre/postgre_driver.php 154
ERROR - 2015-05-16 16:26:33 --> Unable to connect to the database
ERROR - 2015-05-16 16:27:31 --> Could not find the language line "login_forgot_password"
ERROR - 2015-05-16 16:28:13 --> Severity: Warning --> pg_query(): Query failed: ERROR:  relation "users" does not exist
LINE 2: FROM "users"
             ^ /home/ubuntu/Dropbox/ecgs/test/system/database/drivers/postgre/postgre_driver.php 242
ERROR - 2015-05-16 16:28:13 --> Query error: ERROR:  relation "users" does not exist
LINE 2: FROM "users"
             ^ - Invalid query: SELECT "email", "username", "email", "id", "password", "active", "last_login"
FROM "users"
WHERE "email" = 'admin@admin.com'
ORDER BY "id" DESC
 LIMIT 1
