###################
wisdom-volkano
###################

Web Interface for Sharing Data On Monitoring Volkano
(swahili for volcano, from portuguese volcão)

wisdom-volkano is built on top of CodeIgniter 3.1.0, OpenLayers 3.18.2 and Geoserver 2.9.1. 
This project was part of a GIS master and foresaw in three iterations in 2015-6. It will be maintained in a best effort basis. Data has copyright and is not included. 

*******************
Release Information
*******************

This is version 1.1 as of September 2016.


**************************
Changelog and New Features
**************************

New version 1.1:
- Upgraded OpenLayers, Highcharts and jquery.
- Adapted the application to work in MacOSX (i.e., permissions to remove previously created files).

Third iteration (February 2016) aka v1.0:
- GNSS time series
- seismic location map
- fr5 and fr3.2: save to and load from timeseries favorites
- fr3.3: input coordinates manually to calculate and load timeseries of the point
- preload a timeseries with relevant events
- fr14: show one histogram and GNSS chart per station at the same time
- fr11: persistent configuration, zoom, position, time series and layers view 
- fre2: audit log
- fr8: detrend time series

Second iteration (October 2015):
- user manual update
- fr3.1: click on a raster loads the time series of the pixel as chart with time as X-axis 
- fr9: save time series pixel values
- fr13: calculate timeseries from raster stack if it does not exist
- fr14: calculate and load histogramme (seismic counting data)
- google maps added as a background
- layers of seismic and GNSS stations added as backgrounds

First iteration (July 2015):
- user manual
- fr1: basic gis: zoom, pan, 2 external rasters (gmaps, osm), rasters and features
- fr2: select pixel
- fre1: data security: login, admin, layer-user
- fr10: add layers 
- fro1: portability vs update of the system 


*******************
Server Requirements
*******************

Tested in Ubuntu 12.04, Windows 7 and 8.1, and MacOSX.
PHP version 5.3.1 or newer is recommended. 
Apache 2.2.x
PostgreSQL 9.x 
Geoserver version 2.9.1 on Tomcat 7, on Java 7
Browser Mozilla Firefox, Chrome or Safari

************
Installation (for Windows 7)
************

1. Wisdom-volkano
-----------------
- Download: <https://github.com/fulgen/wisdom-volkano>
- Extract: D:\\wisdomvolkano\\web\\



2. Apache httpd 
---------------
- Download: <http://www.apachelounge.com/download/> (64 bits binaries)
- Extract: D:\wisdomvolkano\Apache24
- Edit httdp.conf: C:/Apache24 => D:/wisdomvolkano/Apache24 (all occurrences)
- DOS: httpd.exe (or as a service)
- Browser: http://localhost/ + Edit Apache24/htdocs: => anything

- Edit httpd.conf: DocumentRoot becomes...
  ``ServerName localhost``
  ``ErrorLog d:/wisdomvolkano/Apache24/prod_error.log``
  ``LogLevel warn``
  ``CustomLog d:/wisdomvolkano/Apache24/prod_access.log combined``
  ``DocumentRoot "d:/wisdomvolkano/web"``
  ``<Directory "d:/wisdomvolkano/web">``
  ``  DirectoryIndex index.php``



3. PHP 5.x 
----------

- Download: <http://windows.php.net/download/> (5.6 64 bits thread safe)
- Extract: D:\wisdomvolkano\php
- Copy: php.ini-development php.ini
- Edit php.ini: Uncomment 
``
  doc_root="d:\wisdomvolkano\php"
  extension_dir="d:\wisdomvolkano\php\ext"
  extension=php_pdo_pgsql.dll
  extension=php_pgsql.dll
``
- Edit httdp.conf: add
``
  LoadFile "d:/wisdomvolkano/PostgreSQL/pg10/bin/libpq.dll"
  LoadModule php5_module "d:/wisdomvolkano/php/php5apache2_4.dll"
  AddHandler application/x-httpd-php .php
  PHPIniDir "d:/wisdomvolkano/php" 
``
- Edit Apache24/htdocs/info.php:  <?php phpinfo(); ?>
- Browser: http://localhost/info.php



4. PostgreSQL 
-------------

- Download: <https://www.postgresql.org/download/windows/> (10.0 win64 installer)
- Install: (includes pgAdmin), usr/pwd: postgres/postgresql  usr/pwd: progci/progci
- Import sql in order from D:\wisdomvolkano\web\db\:
``
  d:\wisdomvolkano\PostgreSQL\pg10\bin> psql -U postgres -d wisdomvolkano < d:\wisdomvolkano\web\db\01, 02, 03, 04
``

  
  
5. Geoserver
------------

- Download: <http://geoserver.org/release/stable/>  
- Install: d:\wisdomvolkano\Geoserver port 8080 usr/pwd admin/geoserver (run manual)
- Start 
- Browser: http://localhost:8080/geoserver/
- Login. 
- Menu: Passwords and change default master password: geoserver > wisdomvolkano
- Login root/wisdomvolkano to test
- Menu: Users, tab Users/groups: create usr/pwd progci/pwd
- Edit: webapps/geoserver/data_dir/security/rest.properties with:
``
  /**;GET=ADMIN,PROG
  /**;POST,DELETE,PUT=ADMIN 
``
- Logout. 
- Browser: http://localhost:8080/geoserver/rest



6. GDAL libraries 
-----------------

- Download: <http://geoserver.org/release/stable/>  
- Copy jar to geoserver/WEB-INF/lib
- Follow: <http://docs.geoserver.org/latest/en/user/data/raster/gdal.html>

- Download: <http://demo.geo-solutions.it/share/github/imageio-ext/releases/1.1.X/1.1.16/native/gdal/>
  gdal-data.zip
- Extract gdal-data.zip to d:\wisdomvolkano\geoserver\data_dir\gdal-data
- Env: GDAL_DATA=d:\wisdomvolkano\geoserver\data_dir\gdal-data

- Download: <http://demo.geo-solutions.it/share/github/imageio-ext/releases/1.1.X/1.1.16/native/gdal/windows/MSVC2010/> gdal-1.9.2-MSVC2010-x64.zip	
- Extract gdal to d:\wisdomvolkano\geoserver\data_dir\gdal
- Path: add d:\wisdomvolkano\geoserver\data_dir\gdal
- DOS: gdalinfo --formats (ENVI hdr should be listed)

- Restart Geoserver
- Login
- Menu: Stores, Add (ENVI should be listed)



7. Config wisdom-volkano
------------------------
- Edit web/application/config/database.php (production) with the params in section 4:
``
  'hostname' => '127.0.0.1', // 'localhost',
  'username' => 'progci',
  'password' => 'progci',
  'database' => 'wisdomvolkano', 
``
- Edit web/application/config/config.php (production) 
``
    // geoserver
  $config['geoserver_rest']    = 'http://localhost:8080/geoserver/rest/workspaces/';
  $config['geoserver_userpwd'] = 'admin:geoserver';
    // timeseries folders
  $config['bar_slash']         = '\\';
  $config['folder_msbas']      = 'd:\\wisdomvolkano\\web\\assets\\data\\msbas\\'; 
  $config['folder_msbas_ras']  = '\\RASTERS\\'; // example:  .../msbas/name_of_ts/RASTERS
  $config['folder_msbas_ts']   = '\\Time_Series\\';  // example:  .../msbas/name_of_ts/Time_Series
  $config['folder_histogram']  = 'd:\\wisdomvolkano\\web\\assets\\data\\seism-count\\'; 
  $config['folder_gnss']       = 'd:\\wisdomvolkano\\web\\assets\\data\\gnss-ts\\'; 
  $config['folder_detrend']    = 'detrend\\'; // added to folder msbas or gnss
    // sessions folder
  $config['sess_save_path']    = 'd:\\wisdomvolkano\\web\\ci_sessions\\';

  $config['base_url'] = 'http://localhost/'; 
``
- Get a Google Maps API key <https://developers.google.com/maps/documentation/javascript/get-api-key>
``
  $config['gmaps_key'] = 'Google_Maps_Javascript_API_Key';
``

 

8. cURL
-------

- Download: <https://curl.haxx.se/download.html> win x64
- Extract: d:\wisdomvolkano\curl
- Edit: (if needed) web\application\model\Geoserver_model.php 
``
  $curl = "curl"; // for linux
  $curl = '"D:\\wisdomvolkano\\cURL\\bin\\curl.exe"'; // for windows
``

 

9. Copy files to folders
------------------------

- Copy files to d:\wisdomvolkano\web\assets\data with the following structure:
  ├───DInSAR\
  │   ├───Amplitude
  │   │   ├───ENVISAT
  │   │   │   ├───Asc42i5
  │   │   │   └───Desc35i2
  │   │   └───ERS
  │   │       └───Asc228
  │   ├───Cint
  │   │   ├───ENVISAT
  │   │   │   ├───Asc42i5
  │   │   │   └───Desc35i2
  │   │   └───ERS
  │   │       └───Asc228
  │   ├───Coh
  │   │   ├───ENVISAT
  │   │   │   ├───Asc42i5
  │   │   │   └───Desc35i2
  │   │   └───ERS
  │   │       └───Asc228
  │   ├───MagCint
  │   │   └───ENVISAT
  │   │       └───Desc35i2
  │   ├───MASK
  │   └───Uint
  │       └───ENVISAT
  │           ├───Asc42i5
  │           └───Desc35i2
  ├───gnss-map\
  ├───gnss-ts\
  │   └───detrend
  ├───msbas\
  │   ├───crater-ew
  │   │   ├───RASTERS
  │   │   └───Time_Series
  │   │       └───detrend
  │   ├───crater-up
  │   │   ├───RASTERS
  │   │   └───Time_Series
  │   │       └───detrend
  │   ├───EW
  │   │   ├───RASTERS
  │   │   └───Time_Series
  │   │       └───detrend
  │   └───UP
  │       ├───RASTERS
  │       └───Time_Series
  │           └───detrend
  ├───seism-count\
  ├───seism-locat\
  ├───stations\
  └───events.js
  


10. Geoserver: load GNSS, Seismo stations
-----------------------------------------

- Login Geoserver
- Menu: Workspaces 
  - Remove all existing 7
  - Add geom, amp, cint, coh, uint (all same name as namespace URI)
- Menu: Stores
  - Add shapefile geom:GNSS_station from d:\wisdomvolkano\web\assets\data\stations\GPS-stations-kml.shp
  - Publish: name GNSS_station, title geom:GNSS_station
    Bounding Boxes: Compute from data, and Compute from native bounds
    
  - Add shapefile geom:Seismo_station from d:\wisdomvolkano\web\assets\data\stations\Seismos-stations-kml.shp
  - Publish: name Seismo_station, title geom:Seismo_station
    Bounding Boxes: Compute from data, and Compute from native bounds

- Menu: Styles
  - add name GNSS_station_sld from d:\wisdomvolkano\web\geoserver\sld\sl_station_1.xml (Upload, validate, submit)
  - add name Seismo_station_sld from d:\wisdomvolkano\web\geoserver\sld\sl_station_2.xml (Upload, validate, submit)
- Menu: Layers  
  - Edit geom:GNSS_station, tab Publishing, Default style geom:sld_station1, Save
  - Edit geom:Seismo_station, tab Publishing, Default style geom:sld_station2, Save

  
  
11. Geoserver and Wisdom-Volkano: load interferograms
-----------------------------------------------------

- Geoserver Menu: Stores, ENVI hdr 
  - Add D:\wisdomvolkano\web\assets\data\DInSAR\Amplitude\ENVISAT\Asc42i5\LonLatMagMas32160.dat.hdr as 
    workspace: amp
    name: ENVISAT_Asc42i5_LonLatMagMas32160
  Note: support of ENVI header is not very good in Geoserver; when it does not work, layers can be converted to Geotiff, which can be added without any issues
  - Publish: name: ENVISAT_Asc42i5_LonLatMagMas32160, title: amp:ENVISAT_Asc42i5_LonLatMagMas32160
- Geoserver Menu: Layer preview
  - ENVISAT_Asc42i5_LonLatMagMas32160 > OpenLayers
- Wisdom-Volkano: login and Menu: Add layer, find the added layer above
- Repeat above steps for every raster interferogram

  
  
12. Wisdom-Volkano: load time-series
------------------------------------

- Wisdom-Volkano: Menu: Add time-series
  - MSBAS, name "Nyiragongo-ew", group folder "EW". All other default
  - MSBAS, name "Nyiragongo-up", group folder "UP". All other default
  - Histogram, name "OVG-histogram", file "ovg.tsv", station OVG (as in the KML/Shapefile). Sample content: 
Date  LP  SP  LP-accumulated  SP-accumulated
``
  01/01/2010	1	0	1	0
  02/01/2010	2	1	3	1
  03/01/2010	21	0	24	1
  ...
``
  - GNSS, name "RBV-gnss", file "RBV.enu", station RBV (as in the KML/Shapefile). Sample content: 
``
  2010.73287671	0.00 0.00 0.00
  2010.73561644	-1.10 -1.30 6.20
  2010.73835616	0.70 0.60 -3.10
  2010.74109589	5.20 2.80 12.60
``
- Wisdom-Volkano: Menu: Home, Manage layers, enable the ones created in 11.
  
  

13. Seismic locations
---------------------

- Geoserver: add Store from Shapefile Seismic location, name geom:Seismic_location, Bounding boxes compute from data
  - Style: import Seismic_location_SLD, apply
- Wisdom-volkano: add layer, Manage layers, enable
  -  Click on a circle: info given. More opaque means closer in time, lighter means older. Bigger circle means higher magnitude.
- Geoserver: tab Publishing, show Legend

 
  
14. Out of scope: security  
--------------------------
- Securing all applications involved, from Apache to Geoserver and Codeigniter, aren't covered here but should be your concern.
- It is recommended that you keep at least two complete configurations, one for test and one for production. 
- Logging is not covered either but will help you finding and solving errors. 
- Also recommended backing files up: config, data, logs... 


*******
License
*******

EUPL v1.1
EUPL is an acronym  for “European Union Public Licence”. The EUPL is the first European Free/Open Source Software (F/OSS) licence. It has been created on the initiative of the European Commission. It is now approved by the European Commission in 22 linguistic versions and can be used by anyone for software distribution.

Please see the licence in the eupl1.1.-licence-en.pdf or in any other language in <https://joinup.ec.europa.eu/software/page/eupl/licence-eupl>

No dataset is included in this delivery, being the property of ECGS.

*********
Resources
*********

-  `ECGS <http://www.ecgs.lu/>`_
-  `Lunds Universitet-GIS Centre <http://www.gis.lu.se/english/index.htm>`_

Report ideas and security issues here in GitHub, thank you.


***************
Acknowledgement
***************

The author would like to thank Nicolas d'Oreye for his time and patience.
