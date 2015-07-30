<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
ERROR - 2015-06-30 19:28:28 --> Severity: Error --> Call to undefined function get_layer_users() D:\Dropbox\ecgs\test\application\controllers\Layer.php 220
ERROR - 2015-06-30 19:50:23 --> Severity: Notice --> Undefined property: stdClass::$email D:\Dropbox\ecgs\test\application\views\auth\edit_layer.php 65
ERROR - 2015-06-30 19:50:23 --> Severity: Notice --> Undefined property: stdClass::$email D:\Dropbox\ecgs\test\application\views\auth\edit_layer.php 65
ERROR - 2015-06-30 19:50:23 --> Severity: Notice --> Undefined property: stdClass::$email D:\Dropbox\ecgs\test\application\views\auth\edit_layer.php 65
ERROR - 2015-06-30 19:50:23 --> Severity: Notice --> Undefined property: stdClass::$email D:\Dropbox\ecgs\test\application\views\auth\edit_layer.php 65
ERROR - 2015-06-30 19:50:23 --> Severity: Notice --> Undefined property: stdClass::$email D:\Dropbox\ecgs\test\application\views\auth\edit_layer.php 65
ERROR - 2015-06-30 19:50:23 --> Severity: Notice --> Undefined property: stdClass::$email D:\Dropbox\ecgs\test\application\views\auth\edit_layer.php 65
ERROR - 2015-06-30 19:50:48 --> Severity: Notice --> Undefined property: stdClass::$email D:\Dropbox\ecgs\test\application\views\auth\edit_layer.php 67
ERROR - 2015-06-30 19:50:48 --> Severity: Notice --> Undefined property: stdClass::$email D:\Dropbox\ecgs\test\application\views\auth\edit_layer.php 67
ERROR - 2015-06-30 19:50:48 --> Severity: Notice --> Undefined property: stdClass::$email D:\Dropbox\ecgs\test\application\views\auth\edit_layer.php 67
ERROR - 2015-06-30 19:50:48 --> Severity: Notice --> Undefined property: stdClass::$email D:\Dropbox\ecgs\test\application\views\auth\edit_layer.php 67
ERROR - 2015-06-30 19:50:48 --> Severity: Notice --> Undefined property: stdClass::$email D:\Dropbox\ecgs\test\application\views\auth\edit_layer.php 67
ERROR - 2015-06-30 19:50:48 --> Severity: Notice --> Undefined property: stdClass::$email D:\Dropbox\ecgs\test\application\views\auth\edit_layer.php 67
ERROR - 2015-06-30 20:03:56 --> Severity: Warning --> pg_query(): Query failed: ERROR:  llave duplicada viola restricción de unicidad «unique_layer_name»
DETAIL:  Ya existe la llave (layer_name_ws)=(cint:curltest.geotiff). D:\Dropbox\ecgs\test\system\database\drivers\postgre\postgre_driver.php 242
ERROR - 2015-06-30 20:03:56 --> Query error: ERROR:  llave duplicada viola restricción de unicidad «unique_layer_name»
DETAIL:  Ya existe la llave (layer_name_ws)=(cint:curltest.geotiff). - Invalid query: INSERT INTO "layer" ("creator", "layer_name_ws", "layer_type", "layer_description") VALUES ( E'admin@admin.com',  E'cint:curltest.geotiff',  E'dem',  E'test edit')
ERROR - 2015-06-30 20:04:48 --> Severity: Warning --> Missing argument 1 for Layer::edit_layer() D:\Dropbox\ecgs\test\application\controllers\Layer.php 154
ERROR - 2015-06-30 20:04:48 --> Severity: Notice --> Undefined variable: decription D:\Dropbox\ecgs\test\application\models\Layer_model.php 61
ERROR - 2015-06-30 20:04:48 --> Severity: Warning --> pg_query(): Query failed: ERROR:  llave duplicada viola restricción de unicidad «user_layers_pkey»
DETAIL:  Ya existe la llave (user_email, layer)=(fulgencio.sanmartin@publications.europa.eu, cint:curltest.geotiff). D:\Dropbox\ecgs\test\system\database\drivers\postgre\postgre_driver.php 242
ERROR - 2015-06-30 20:04:48 --> Query error: ERROR:  llave duplicada viola restricción de unicidad «user_layers_pkey»
DETAIL:  Ya existe la llave (user_email, layer)=(fulgencio.sanmartin@publications.europa.eu, cint:curltest.geotiff). - Invalid query: INSERT INTO "user_layers" ("user_email", "layer", "config_visible", "config_opacity", "config_order", "granted_by", "granted_when") VALUES ( E'fulgencio.sanmartin@publications.europa.eu',  E'cint:curltest.geotiff', 0, 100, 1,  E'admin@admin.com',  E'30/06/15')
ERROR - 2015-06-30 20:05:42 --> Severity: Warning --> Missing argument 1 for Layer::edit_layer() D:\Dropbox\ecgs\test\application\controllers\Layer.php 154
ERROR - 2015-06-30 20:05:43 --> Severity: Notice --> Undefined variable: decription D:\Dropbox\ecgs\test\application\models\Layer_model.php 61
ERROR - 2015-06-30 20:05:43 --> Severity: Warning --> pg_query(): Query failed: ERROR:  llave duplicada viola restricción de unicidad «user_layers_pkey»
DETAIL:  Ya existe la llave (user_email, layer)=(fulgencio.sanmartin@publications.europa.eu, cint:curltest.geotiff). D:\Dropbox\ecgs\test\system\database\drivers\postgre\postgre_driver.php 242
ERROR - 2015-06-30 20:05:43 --> Query error: ERROR:  llave duplicada viola restricción de unicidad «user_layers_pkey»
DETAIL:  Ya existe la llave (user_email, layer)=(fulgencio.sanmartin@publications.europa.eu, cint:curltest.geotiff). - Invalid query: INSERT INTO "user_layers" ("user_email", "layer", "config_visible", "config_opacity", "config_order", "granted_by", "granted_when") VALUES ( E'fulgencio.sanmartin@publications.europa.eu',  E'cint:curltest.geotiff', 0, 100, 1,  E'admin@admin.com',  E'30/06/15')
ERROR - 2015-06-30 20:06:12 --> Severity: Warning --> pg_query(): Query failed: ERROR:  llave duplicada viola restricción de unicidad «user_layers_pkey»
DETAIL:  Ya existe la llave (user_email, layer)=(fulgencio.sanmartin@publications.europa.eu, cint:curltest.geotiff). D:\Dropbox\ecgs\test\system\database\drivers\postgre\postgre_driver.php 242
ERROR - 2015-06-30 20:06:12 --> Query error: ERROR:  llave duplicada viola restricción de unicidad «user_layers_pkey»
DETAIL:  Ya existe la llave (user_email, layer)=(fulgencio.sanmartin@publications.europa.eu, cint:curltest.geotiff). - Invalid query: INSERT INTO "user_layers" ("user_email", "layer", "config_visible", "config_opacity", "config_order", "granted_by", "granted_when") VALUES ( E'fulgencio.sanmartin@publications.europa.eu',  E'cint:curltest.geotiff', 0, 100, 1,  E'admin@admin.com',  E'30/06/15')
ERROR - 2015-06-30 20:07:11 --> Severity: Warning --> pg_query(): Query failed: ERROR:  llave duplicada viola restricción de unicidad «user_layers_pkey»
DETAIL:  Ya existe la llave (user_email, layer)=(fulgencio.sanmartin@publications.europa.eu, cint:curltest.geotiff). D:\Dropbox\ecgs\test\system\database\drivers\postgre\postgre_driver.php 242
ERROR - 2015-06-30 20:07:11 --> Query error: ERROR:  llave duplicada viola restricción de unicidad «user_layers_pkey»
DETAIL:  Ya existe la llave (user_email, layer)=(fulgencio.sanmartin@publications.europa.eu, cint:curltest.geotiff). - Invalid query: INSERT INTO "user_layers" ("user_email", "layer", "config_visible", "config_opacity", "config_order", "granted_by", "granted_when") VALUES ( E'fulgencio.sanmartin@publications.europa.eu',  E'cint:curltest.geotiff', 0, 100, 1,  E'admin@admin.com',  E'30/06/15')
