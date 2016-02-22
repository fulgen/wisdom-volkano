###################
wisdom-volkano
###################

Web Interface for Sharing Data On Monitoring Volkano
(swahili for volcano, from portuguese volcão)

wisdom-volkano is built on top of CodeIgniter 3.0.4, OpenLayers 3.12.1 and Geoserver 2.8.1. 
This project was part of a GIS master and foreseen in three iterations in 2015. It will be maintained in a best effort basis. Data has copyright and is not included. 

*******************
Release Information
*******************

This is the third and last iteration, aka v1.0 as of February 2016.


**************************
Changelog and New Features
**************************

Third iteration (February 2016):
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
-  user manual update
-  fr3.1: click on a raster loads the time series of the pixel as chart with time as X-axis 
-  fr9: save time series pixel values
-  fr13: calculate timeseries from raster stack if it does not exist
-  fr14: calculate and load histogramme (seismic counting data)
-  google maps added as a background
-  layers of seismic and GNSS stations added as backgrounds

First iteration (July 2015):
-  user manual
-  fr1: basic gis: zoom, pan, 2 external rasters (gmaps, osm), rasters and features
-  fr2: select pixel
-  fre1: data security: login, admin, layer-user
-  fr10: add layers 
-  fro1: portability vs update of the system 


*******************
Server Requirements
*******************

Tested in Ubuntu 12.04 and Windows 7 and 8.
PHP version 5.3.1 or newer is recommended. 
Apache 2.2.x
PostgreSQL 9.x 
Geoserver version 2.8.1 on Tomcat 7, on Java 7
Browser Mozilla Firefox, Chrome or Safari

************
Installation
************

1. Install Apache httpd 
2. Install Tomcat 
3. Deploy the Geoserver war in Tomcat.
4. Install PostgreSQL  
5. Copy the wisdom-volkano files in a suitable place for Apache httpd.
6. Go to applications/config and edit
   a. config.php 
   b. database.php 
7. Log in http://apache-httpd/ with admin@admin.com / password
8. Go to Help to read the administration manual   

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
