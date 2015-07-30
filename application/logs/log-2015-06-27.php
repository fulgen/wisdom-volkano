<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

ERROR - 2015-06-27 12:45:09 --> Could not find the language line "login_forgot_password"
ERROR - 2015-06-27 13:06:15 --> Severity: Notice --> Undefined variable: user D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 57
ERROR - 2015-06-27 13:06:15 --> Severity: Notice --> Trying to get property of non-object D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 57
ERROR - 2015-06-27 13:06:15 --> Severity: Notice --> Undefined variable: user D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 57
ERROR - 2015-06-27 13:06:15 --> Severity: Notice --> Trying to get property of non-object D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 57
ERROR - 2015-06-27 13:06:15 --> Severity: Notice --> Undefined variable: user D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 58
ERROR - 2015-06-27 13:06:15 --> Severity: Notice --> Trying to get property of non-object D:\Dropbox\ecgs\test\application\views\auth\layer_list.php 58
ERROR - 2015-06-27 13:17:47 --> Severity: Notice --> Undefined variable: layers D:\Dropbox\ecgs\test\application\views\auth\create_layer.php 34
ERROR - 2015-06-27 13:44:17 --> 404 Page Not Found: Layer/del_layer
ERROR - 2015-06-27 13:48:58 --> 404 Page Not Found: Layer/del_layer
ERROR - 2015-06-27 13:49:20 --> Severity: Notice --> Undefined variable: user_email D:\Dropbox\ecgs\test\application\models\Layer_model.php 62
ERROR - 2015-06-27 13:49:20 --> Severity: Warning --> pg_query(): Query failed: ERROR:  no existe la columna «user_email»
LINE 2: WHERE &quot;user_email&quot; IS NULL
              ^ D:\Dropbox\ecgs\test\system\database\drivers\postgre\postgre_driver.php 242
ERROR - 2015-06-27 13:49:20 --> Query error: ERROR:  no existe la columna «user_email»
LINE 2: WHERE "user_email" IS NULL
              ^ - Invalid query: DELETE FROM "layer"
WHERE "user_email" IS NULL
AND "layer" = E'test2'
ERROR - 2015-06-27 13:49:51 --> Severity: Warning --> pg_query(): Query failed: ERROR:  el ingreso de tipos compuestos anónimos no está implementado
LINE 2: WHERE &quot;layer&quot; = E'test2'
                        ^ D:\Dropbox\ecgs\test\system\database\drivers\postgre\postgre_driver.php 242
ERROR - 2015-06-27 13:49:51 --> Query error: ERROR:  el ingreso de tipos compuestos anónimos no está implementado
LINE 2: WHERE "layer" = E'test2'
                        ^ - Invalid query: DELETE FROM "layer"
WHERE "layer" = E'test2'
ERROR - 2015-06-27 13:52:30 --> Severity: Warning --> pg_query(): Query failed: ERROR:  llave duplicada viola restricción de unicidad «unique_layer_name»
DETAIL:  Ya existe la llave (layer_name_ws)=(cint:cint-a42-32160-32661). D:\Dropbox\ecgs\test\system\database\drivers\postgre\postgre_driver.php 242
ERROR - 2015-06-27 13:52:30 --> Query error: ERROR:  llave duplicada viola restricción de unicidad «unique_layer_name»
DETAIL:  Ya existe la llave (layer_name_ws)=(cint:cint-a42-32160-32661). - Invalid query: INSERT INTO "layer" ("creator", "layer_name_ws", "layer_type", "layer_description") VALUES ( E'admin@admin.com',  E'cint:cint-a42-32160-32661',  E'raster', '')
ERROR - 2015-06-27 13:57:16 --> Severity: Notice --> Undefined property: CI_DB_postgre_result::$row D:\Dropbox\ecgs\test\application\models\Layer_model.php 91
ERROR - 2015-06-27 13:57:16 --> app/model/layer/E-008 Error This layer cint:curltest.geotiff does not exist.
ERROR - 2015-06-27 13:57:16 --> app/model/layer/E-008 Error This layer test:amp-32160 does not exist.
ERROR - 2015-06-27 13:57:16 --> app/model/layer/E-008 Error This layer test:dem_SRTM_Rift_28_31_0_3s_shaded35 does not exist.
ERROR - 2015-06-27 13:57:16 --> app/model/layer/E-008 Error This layer test:uint-a42-32160-43182 does not exist.
ERROR - 2015-06-27 13:57:16 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Dropbox\ecgs\test\application\views\auth\create_layer.php 35
ERROR - 2015-06-27 13:59:20 --> Severity: Notice --> Undefined property: CI_DB_postgre_result::$row D:\Dropbox\ecgs\test\application\models\Layer_model.php 93
ERROR - 2015-06-27 13:59:20 --> app/model/layer/E-008 Error This layer cint:curltest.geotiff does not exist.
ERROR - 2015-06-27 13:59:20 --> app/model/layer/E-008 Error This layer test:amp-32160 does not exist.
ERROR - 2015-06-27 13:59:20 --> app/model/layer/E-008 Error This layer test:dem_SRTM_Rift_28_31_0_3s_shaded35 does not exist.
ERROR - 2015-06-27 13:59:20 --> app/model/layer/E-008 Error This layer test:uint-a42-32160-43182 does not exist.
ERROR - 2015-06-27 13:59:20 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Dropbox\ecgs\test\application\views\auth\create_layer.php 35
ERROR - 2015-06-27 13:59:39 --> Severity: Notice --> Undefined property: CI_DB_postgre_result::$row D:\Dropbox\ecgs\test\application\models\Layer_model.php 93
ERROR - 2015-06-27 13:59:39 --> app/model/layer/E-008 Error This layer cint:curltest.geotiff does not exist.
ERROR - 2015-06-27 13:59:39 --> app/model/layer/E-008 Error This layer test:amp-32160 does not exist.
ERROR - 2015-06-27 13:59:39 --> app/model/layer/E-008 Error This layer test:dem_SRTM_Rift_28_31_0_3s_shaded35 does not exist.
ERROR - 2015-06-27 13:59:39 --> app/model/layer/E-008 Error This layer test:uint-a42-32160-43182 does not exist.
ERROR - 2015-06-27 13:59:39 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Dropbox\ecgs\test\application\views\auth\create_layer.php 35
ERROR - 2015-06-27 14:00:03 --> Severity: Notice --> Undefined property: CI_DB_postgre_result::$result D:\Dropbox\ecgs\test\application\models\Layer_model.php 93
ERROR - 2015-06-27 14:00:03 --> app/model/layer/E-008 Error This layer cint:curltest.geotiff does not exist.
ERROR - 2015-06-27 14:00:03 --> app/model/layer/E-008 Error This layer test:amp-32160 does not exist.
ERROR - 2015-06-27 14:00:03 --> app/model/layer/E-008 Error This layer test:dem_SRTM_Rift_28_31_0_3s_shaded35 does not exist.
ERROR - 2015-06-27 14:00:03 --> app/model/layer/E-008 Error This layer test:uint-a42-32160-43182 does not exist.
ERROR - 2015-06-27 14:00:03 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Dropbox\ecgs\test\application\views\auth\create_layer.php 35
ERROR - 2015-06-27 14:00:55 --> Severity: Notice --> Undefined property: CI_DB_postgre_result::$result D:\Dropbox\ecgs\test\application\models\Layer_model.php 94
ERROR - 2015-06-27 14:00:55 --> app/model/layer/E-008 Error This layer cint:curltest.geotiff does not exist.
ERROR - 2015-06-27 14:00:55 --> app/model/layer/E-008 Error This layer test:amp-32160 does not exist.
ERROR - 2015-06-27 14:00:55 --> app/model/layer/E-008 Error This layer test:dem_SRTM_Rift_28_31_0_3s_shaded35 does not exist.
ERROR - 2015-06-27 14:00:55 --> app/model/layer/E-008 Error This layer test:uint-a42-32160-43182 does not exist.
ERROR - 2015-06-27 14:00:55 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Dropbox\ecgs\test\application\views\auth\create_layer.php 35
ERROR - 2015-06-27 14:02:16 --> Severity: Notice --> Undefined property: CI_DB_postgre_result::$result D:\Dropbox\ecgs\test\application\models\Layer_model.php 94
ERROR - 2015-06-27 14:02:16 --> app/model/layer/E-008 Error This layer cint:curltest.geotiff does not exist.
ERROR - 2015-06-27 14:02:16 --> app/model/layer/E-008 Error This layer test:amp-32160 does not exist.
ERROR - 2015-06-27 14:02:16 --> app/model/layer/E-008 Error This layer test:dem_SRTM_Rift_28_31_0_3s_shaded35 does not exist.
ERROR - 2015-06-27 14:02:16 --> app/model/layer/E-008 Error This layer test:uint-a42-32160-43182 does not exist.
ERROR - 2015-06-27 14:02:16 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Dropbox\ecgs\test\application\views\auth\create_layer.php 35
ERROR - 2015-06-27 14:02:35 --> app/model/layer/E-008 Error This layer cint:cint-a42-32160-32661 does not exist.
ERROR - 2015-06-27 14:02:35 --> app/model/layer/E-008 Error This layer cint:curltest.geotiff does not exist.
ERROR - 2015-06-27 14:02:35 --> app/model/layer/E-008 Error This layer test:amp-32160 does not exist.
ERROR - 2015-06-27 14:02:35 --> app/model/layer/E-008 Error This layer test:dem_SRTM_Rift_28_31_0_3s_shaded35 does not exist.
ERROR - 2015-06-27 14:02:35 --> app/model/layer/E-008 Error This layer test:uint-a42-32160-43182 does not exist.
ERROR - 2015-06-27 14:02:35 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Dropbox\ecgs\test\application\views\auth\create_layer.php 35
ERROR - 2015-06-27 14:34:18 --> app/model/layer/E-008 Error This layer cint:cint-a42-32160-32661 does not exist.
ERROR - 2015-06-27 14:34:18 --> app/model/layer/E-008 Error This layer cint:curltest.geotiff does not exist.
ERROR - 2015-06-27 14:34:18 --> app/model/layer/E-008 Error This layer test:amp-32160 does not exist.
ERROR - 2015-06-27 14:34:18 --> app/model/layer/E-008 Error This layer test:dem_SRTM_Rift_28_31_0_3s_shaded35 does not exist.
ERROR - 2015-06-27 14:34:18 --> app/model/layer/E-008 Error This layer test:uint-a42-32160-43182 does not exist.
ERROR - 2015-06-27 14:34:18 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Dropbox\ecgs\test\application\views\auth\create_layer.php 36
ERROR - 2015-06-27 14:37:31 --> app/model/layer/E-008 Error This layer cint:cint-a42-32160-32661 does not exist.
ERROR - 2015-06-27 14:37:31 --> app/model/layer/E-008 Error This layer cint:curltest.geotiff does not exist.
ERROR - 2015-06-27 14:37:31 --> app/model/layer/E-008 Error This layer test:amp-32160 does not exist.
ERROR - 2015-06-27 14:37:31 --> app/model/layer/E-008 Error This layer test:dem_SRTM_Rift_28_31_0_3s_shaded35 does not exist.
ERROR - 2015-06-27 14:37:31 --> app/model/layer/E-008 Error This layer test:uint-a42-32160-43182 does not exist.
ERROR - 2015-06-27 14:37:31 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Dropbox\ecgs\test\application\views\auth\create_layer.php 36
ERROR - 2015-06-27 14:38:02 --> app/model/layer/E-008 Error This layer cint:cint-a42-32160-32661 does not exist.
ERROR - 2015-06-27 14:38:02 --> app/model/layer/E-008 Error This layer cint:curltest.geotiff does not exist.
ERROR - 2015-06-27 14:38:02 --> app/model/layer/E-008 Error This layer test:amp-32160 does not exist.
ERROR - 2015-06-27 14:38:02 --> app/model/layer/E-008 Error This layer test:dem_SRTM_Rift_28_31_0_3s_shaded35 does not exist.
ERROR - 2015-06-27 14:38:02 --> app/model/layer/E-008 Error This layer test:uint-a42-32160-43182 does not exist.
ERROR - 2015-06-27 14:38:02 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Dropbox\ecgs\test\application\views\auth\create_layer.php 36
ERROR - 2015-06-27 14:38:48 --> app/model/layer/E-008 Error This layer cint:cint-a42-32160-32661 does not exist.
ERROR - 2015-06-27 14:38:48 --> app/model/layer/E-008 Error This layer cint:curltest.geotiff does not exist.
ERROR - 2015-06-27 14:38:48 --> app/model/layer/E-008 Error This layer test:amp-32160 does not exist.
ERROR - 2015-06-27 14:38:48 --> app/model/layer/E-008 Error This layer test:dem_SRTM_Rift_28_31_0_3s_shaded35 does not exist.
ERROR - 2015-06-27 14:38:48 --> app/model/layer/E-008 Error This layer test:uint-a42-32160-43182 does not exist.
ERROR - 2015-06-27 14:38:48 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Dropbox\ecgs\test\application\views\auth\create_layer.php 36
ERROR - 2015-06-27 14:45:11 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Dropbox\ecgs\test\application\views\auth\create_layer.php 36
ERROR - 2015-06-27 14:47:44 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Dropbox\ecgs\test\application\views\auth\create_layer.php 36
ERROR - 2015-06-27 14:48:41 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Dropbox\ecgs\test\application\views\auth\create_layer.php 36
ERROR - 2015-06-27 14:50:06 --> Severity: Warning --> Invalid argument supplied for foreach() D:\Dropbox\ecgs\test\application\views\auth\create_layer.php 36
ERROR - 2015-06-27 14:55:02 --> app/model/layer/E-007 Error Cannot remove layer cint:curltest.geotiff.
ERROR - 2015-06-27 14:55:06 --> app/model/layer/E-007 Error Cannot remove layer layer.
ERROR - 2015-06-27 14:56:22 --> app/model/layer/E-007 Error Cannot remove layer layer.
ERROR - 2015-06-27 14:56:45 --> app/model/layer/E-007 Error Cannot remove layer layer.
ERROR - 2015-06-27 14:57:03 --> app/model/layer/E-007 Error Cannot remove layer layer.
ERROR - 2015-06-27 14:58:17 --> app/model/layer/E-007 Error Cannot remove layer layer.
ERROR - 2015-06-27 14:58:40 --> app/model/layer/E-007 Error Cannot remove layer layer.
ERROR - 2015-06-27 15:02:16 --> 404 Page Not Found: Layer/layer_list
ERROR - 2015-06-27 15:02:24 --> 404 Page Not Found: Auth/layer_list
ERROR - 2015-06-27 15:03:12 --> app/model/layer/E-009 Error Cannot find any layers 
