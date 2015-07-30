<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2015-05-17 09:56:09 --> Could not find the language line "login_forgot_password"
ERROR - 2015-05-17 09:56:54 --> Severity: Warning --> pg_query(): Query failed: ERROR:  relation "users" does not exist
LINE 2: FROM "users"
             ^ /home/ubuntu/Dropbox/ecgs/test/system/database/drivers/postgre/postgre_driver.php 242
ERROR - 2015-05-17 09:56:54 --> Query error: ERROR:  relation "users" does not exist
LINE 2: FROM "users"
             ^ - Invalid query: SELECT "email", "username", "email", "id", "password", "active", "last_login"
FROM "users"
WHERE "email" = 'admin@admin.com'
ORDER BY "id" DESC
 LIMIT 1
ERROR - 2015-05-17 10:01:40 --> Could not find the language line "login_forgot_password"
ERROR - 2015-05-17 10:03:41 --> Could not find the language line "login_forgot_password"
ERROR - 2015-05-17 10:08:06 --> Severity: Error --> Call to undefined function menu() /home/ubuntu/Dropbox/ecgs/test/application/views/auth/index.php 20
ERROR - 2015-05-17 10:11:22 --> Could not find the language line "edit_user_validation_fname_label"
ERROR - 2015-05-17 10:11:22 --> Could not find the language line "edit_user_validation_lname_label"
ERROR - 2015-05-17 10:11:22 --> Could not find the language line "edit_user_validation_phone_label"
ERROR - 2015-05-17 10:11:22 --> Could not find the language line "edit_user_validation_company_label"
ERROR - 2015-05-17 10:11:22 --> Could not find the language line "edit_user_heading"
ERROR - 2015-05-17 10:11:22 --> Could not find the language line "edit_user_subheading"
ERROR - 2015-05-17 10:11:22 --> Could not find the language line "edit_user_fname_label"
ERROR - 2015-05-17 10:11:22 --> Could not find the language line "edit_user_lname_label"
ERROR - 2015-05-17 10:11:22 --> Could not find the language line "edit_user_company_label"
ERROR - 2015-05-17 10:11:22 --> Could not find the language line "edit_user_phone_label"
ERROR - 2015-05-17 10:11:22 --> Could not find the language line "edit_user_password_label"
ERROR - 2015-05-17 10:11:22 --> Could not find the language line "edit_user_password_confirm_label"
ERROR - 2015-05-17 10:11:22 --> Could not find the language line "edit_user_groups_heading"
ERROR - 2015-05-17 10:11:22 --> Could not find the language line "edit_user_submit_btn"
ERROR - 2015-05-17 10:11:26 --> Could not find the language line "edit_user_validation_fname_label"
ERROR - 2015-05-17 10:11:26 --> Could not find the language line "edit_user_validation_lname_label"
ERROR - 2015-05-17 10:11:26 --> Could not find the language line "edit_user_validation_phone_label"
ERROR - 2015-05-17 10:11:26 --> Could not find the language line "edit_user_validation_company_label"
ERROR - 2015-05-17 10:11:26 --> Severity: Warning --> pg_query(): Query failed: ERROR:  duplicate key value violates unique constraint "users_groups_pkey"
DETAIL:  Key (id)=(1) already exists. /home/ubuntu/Dropbox/ecgs/test/system/database/drivers/postgre/postgre_driver.php 242
ERROR - 2015-05-17 10:11:26 --> Query error: ERROR:  duplicate key value violates unique constraint "users_groups_pkey"
DETAIL:  Key (id)=(1) already exists. - Invalid query: INSERT INTO "users_groups" ("group_id", "user_id") VALUES (2, 2)
ERROR - 2015-05-17 14:21:36 --> Severity: Warning --> pg_connect():  D:\Dropbox\ecgs\test\system\database\drivers\postgre\postgre_driver.php 154
ERROR - 2015-05-17 14:21:36 --> Severity: Warning --> date(): It is not safe to rely on the system's timezone settings. You are *required* to use the date.timezone setting or the date_default_timezone_set() function. In case you used any of those methods and you are still getting this warning, you most likely misspelled the timezone identifier. We selected the timezone 'UTC' for now, but please set date.timezone to select your timezone. D:\Dropbox\ecgs\test\system\core\Log.php 176
ERROR - 2015-05-17 14:21:36 --> Severity: Warning --> date(): It is not safe to rely on the system's timezone settings. You are *required* to use the date.timezone setting or the date_default_timezone_set() function. In case you used any of those methods and you are still getting this warning, you most likely misspelled the timezone identifier. We selected the timezone 'UTC' for now, but please set date.timezone to select your timezone. D:\Dropbox\ecgs\test\system\core\Log.php 204
ERROR - 2015-05-17 14:21:36 --> Unable to connect to the database
ERROR - 2015-05-17 14:21:36 --> Severity: Warning --> Cannot modify header information - headers already sent by (output started at D:\Dropbox\ecgs\test\system\core\Log.php:176) D:\Dropbox\ecgs\test\system\core\Common.php 569
ERROR - 2015-05-17 16:26:01 --> Severity: Warning --> pg_connect():  D:\Dropbox\ecgs\test\system\database\drivers\postgre\postgre_driver.php 154
ERROR - 2015-05-17 16:26:01 --> Unable to connect to the database
ERROR - 2015-05-17 17:24:32 --> Could not find the language line "edit_user_validation_fname_label"
ERROR - 2015-05-17 17:24:32 --> Could not find the language line "edit_user_validation_lname_label"
ERROR - 2015-05-17 17:24:32 --> Could not find the language line "edit_user_validation_phone_label"
ERROR - 2015-05-17 17:24:32 --> Could not find the language line "edit_user_validation_company_label"
ERROR - 2015-05-17 17:24:32 --> Could not find the language line "edit_user_heading"
ERROR - 2015-05-17 17:24:32 --> Could not find the language line "edit_user_subheading"
ERROR - 2015-05-17 17:24:32 --> Could not find the language line "edit_user_fname_label"
ERROR - 2015-05-17 17:24:32 --> Could not find the language line "edit_user_lname_label"
ERROR - 2015-05-17 17:24:32 --> Could not find the language line "edit_user_company_label"
ERROR - 2015-05-17 17:24:32 --> Could not find the language line "edit_user_phone_label"
ERROR - 2015-05-17 17:24:32 --> Could not find the language line "edit_user_password_label"
ERROR - 2015-05-17 17:24:32 --> Could not find the language line "edit_user_password_confirm_label"
ERROR - 2015-05-17 17:24:32 --> Could not find the language line "edit_user_groups_heading"
ERROR - 2015-05-17 17:24:32 --> Could not find the language line "edit_user_submit_btn"
ERROR - 2015-05-17 17:43:01 --> Could not find the language line "edit_user_validation_fname_label"
ERROR - 2015-05-17 17:43:01 --> Could not find the language line "edit_user_validation_lname_label"
ERROR - 2015-05-17 17:43:01 --> Could not find the language line "edit_user_validation_phone_label"
ERROR - 2015-05-17 17:43:01 --> Could not find the language line "edit_user_validation_company_label"
ERROR - 2015-05-17 17:43:10 --> Could not find the language line "edit_user_validation_fname_label"
ERROR - 2015-05-17 17:43:10 --> Could not find the language line "edit_user_validation_lname_label"
ERROR - 2015-05-17 17:43:10 --> Could not find the language line "edit_user_validation_phone_label"
ERROR - 2015-05-17 17:43:10 --> Could not find the language line "edit_user_validation_company_label"
ERROR - 2015-05-17 17:43:10 --> Severity: Warning --> pg_query(): Query failed: ERROR:  permiso denegado a la secuencia users_groups_id_seq D:\Dropbox\ecgs\test\system\database\drivers\postgre\postgre_driver.php 242
ERROR - 2015-05-17 17:43:10 --> Query error: ERROR:  permiso denegado a la secuencia users_groups_id_seq - Invalid query: INSERT INTO "users_groups" ("group_id", "user_id") VALUES (2, 2)
ERROR - 2015-05-17 18:30:30 --> Could not find the language line "edit_user_validation_fname_label"
ERROR - 2015-05-17 18:30:30 --> Could not find the language line "edit_user_validation_lname_label"
ERROR - 2015-05-17 18:30:30 --> Could not find the language line "edit_user_validation_phone_label"
ERROR - 2015-05-17 18:30:30 --> Could not find the language line "edit_user_validation_company_label"
ERROR - 2015-05-17 18:30:30 --> Could not find the language line "error_csrf"
ERROR - 2015-05-17 18:54:08 --> Could not find the language line "login_forgot_password"
