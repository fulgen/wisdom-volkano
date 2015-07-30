<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2015-06-24 08:33:23 --> Could not find the language line "login_forgot_password"
ERROR - 2015-06-24 08:33:43 --> app/model/layer/E-009 Error Cannot find any layers 
ERROR - 2015-06-24 08:34:26 --> app/model/layer/E-009 Error Cannot find any layers 
ERROR - 2015-06-24 08:34:35 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Dropbox\ecgs\test\application\views\auth\create_layer.php 39
ERROR - 2015-06-24 08:34:35 --> Severity: Warning --> explode() expects parameter 2 to be string, array given D:\Dropbox\ecgs\test\application\views\auth\create_layer.php 40
ERROR - 2015-06-24 08:35:09 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Dropbox\ecgs\test\application\views\auth\create_layer.php 40
ERROR - 2015-06-24 08:35:09 --> Severity: Warning --> explode() expects parameter 2 to be string, array given D:\Dropbox\ecgs\test\application\views\auth\create_layer.php 41
ERROR - 2015-06-24 08:36:13 --> Severity: Warning --> explode() expects parameter 2 to be string, array given D:\Dropbox\ecgs\test\application\views\auth\create_layer.php 42
ERROR - 2015-06-24 08:38:43 --> Severity: Warning --> pg_query(): Query failed: ERROR:  no existe la columna «layer» en la relación «layer»
LINE 1: INSERT INTO &quot;layer&quot; (&quot;creator&quot;, &quot;layer&quot;, &quot;layer_type&quot;) VALUE...
                                        ^ D:\Dropbox\ecgs\test\system\database\drivers\postgre\postgre_driver.php 242
ERROR - 2015-06-24 08:38:44 --> Query error: ERROR:  no existe la columna «layer» en la relación «layer»
LINE 1: INSERT INTO "layer" ("creator", "layer", "layer_type") VALUE...
                                        ^ - Invalid query: INSERT INTO "layer" ("creator", "layer", "layer_type") VALUES ( E'admin@admin.com',  E'dem', '')
ERROR - 2015-06-24 08:41:13 --> Severity: Warning --> pg_query(): Query failed: ERROR:  permiso denegado a la secuencia layer_layer_id_seq D:\Dropbox\ecgs\test\system\database\drivers\postgre\postgre_driver.php 242
ERROR - 2015-06-24 08:41:13 --> Query error: ERROR:  permiso denegado a la secuencia layer_layer_id_seq - Invalid query: INSERT INTO "layer" ("creator", "layer_name_ws", "layer_type", "layer_description") VALUES ( E'admin@admin.com',  E'test2',  E'dem',  E'test')
ERROR - 2015-06-24 08:42:03 --> Severity: Notice --> Undefined property: stdClass::$id D:\Dropbox\ecgs\test\application\controllers\Layer.php 53
ERROR - 2015-06-24 08:42:03 --> Severity: Notice --> Undefined property: CI_DB_postgre_result::$row D:\Dropbox\ecgs\test\application\models\Userlayer_model.php 141
ERROR - 2015-06-24 08:42:03 --> Severity: Notice --> Undefined property: stdClass::$layer_name D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 49
ERROR - 2015-06-24 08:42:03 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 53
ERROR - 2015-06-24 08:42:03 --> Severity: Notice --> Undefined variable: user D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 57
ERROR - 2015-06-24 08:42:03 --> Severity: Notice --> Trying to get property of non-object D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 57
ERROR - 2015-06-24 08:42:03 --> Severity: Notice --> Undefined variable: user D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 57
ERROR - 2015-06-24 08:42:03 --> Severity: Notice --> Trying to get property of non-object D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 57
ERROR - 2015-06-24 08:42:03 --> Severity: Notice --> Undefined variable: user D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 58
ERROR - 2015-06-24 08:42:03 --> Severity: Notice --> Trying to get property of non-object D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 58
ERROR - 2015-06-24 08:43:42 --> Severity: Notice --> Undefined property: stdClass::$id D:\Dropbox\ecgs\test\application\controllers\Layer.php 53
ERROR - 2015-06-24 08:43:42 --> Severity: Notice --> Undefined property: CI_DB_postgre_result::$row D:\Dropbox\ecgs\test\application\models\Userlayer_model.php 141
ERROR - 2015-06-24 08:43:42 --> Severity: Notice --> Undefined property: stdClass::$layer_name D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 49
ERROR - 2015-06-24 08:43:42 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 53
ERROR - 2015-06-24 08:43:42 --> Severity: Notice --> Undefined variable: user D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 57
ERROR - 2015-06-24 08:43:42 --> Severity: Notice --> Trying to get property of non-object D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 57
ERROR - 2015-06-24 08:43:42 --> Severity: Notice --> Undefined variable: user D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 57
ERROR - 2015-06-24 08:43:42 --> Severity: Notice --> Trying to get property of non-object D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 57
ERROR - 2015-06-24 08:43:42 --> Severity: Notice --> Undefined variable: user D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 58
ERROR - 2015-06-24 08:43:42 --> Severity: Notice --> Trying to get property of non-object D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 58
ERROR - 2015-06-24 08:44:12 --> Severity: Notice --> Undefined property: CI_DB_postgre_result::$row D:\Dropbox\ecgs\test\application\models\Userlayer_model.php 141
ERROR - 2015-06-24 08:44:12 --> Severity: Notice --> Undefined property: stdClass::$layer_name D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 49
ERROR - 2015-06-24 08:44:12 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 53
ERROR - 2015-06-24 08:44:12 --> Severity: Notice --> Undefined variable: user D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 57
ERROR - 2015-06-24 08:44:12 --> Severity: Notice --> Trying to get property of non-object D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 57
ERROR - 2015-06-24 08:44:12 --> Severity: Notice --> Undefined variable: user D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 57
ERROR - 2015-06-24 08:44:12 --> Severity: Notice --> Trying to get property of non-object D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 57
ERROR - 2015-06-24 08:44:12 --> Severity: Notice --> Undefined variable: user D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 58
ERROR - 2015-06-24 08:44:12 --> Severity: Notice --> Trying to get property of non-object D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 58
ERROR - 2015-06-24 08:44:42 --> Severity: Notice --> Undefined property: stdClass::$layer_name D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 49
ERROR - 2015-06-24 08:44:43 --> Severity: Notice --> Undefined variable: user D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 57
ERROR - 2015-06-24 08:44:43 --> Severity: Notice --> Trying to get property of non-object D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 57
ERROR - 2015-06-24 08:44:43 --> Severity: Notice --> Undefined variable: user D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 57
ERROR - 2015-06-24 08:44:43 --> Severity: Notice --> Trying to get property of non-object D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 57
ERROR - 2015-06-24 08:44:43 --> Severity: Notice --> Undefined variable: user D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 58
ERROR - 2015-06-24 08:44:43 --> Severity: Notice --> Trying to get property of non-object D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 58
ERROR - 2015-06-24 08:45:02 --> Severity: Notice --> Undefined variable: user D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 57
ERROR - 2015-06-24 08:45:02 --> Severity: Notice --> Trying to get property of non-object D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 57
ERROR - 2015-06-24 08:45:02 --> Severity: Notice --> Undefined variable: user D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 57
ERROR - 2015-06-24 08:45:02 --> Severity: Notice --> Trying to get property of non-object D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 57
ERROR - 2015-06-24 08:45:02 --> Severity: Notice --> Undefined variable: user D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 58
ERROR - 2015-06-24 08:45:02 --> Severity: Notice --> Trying to get property of non-object D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 58
