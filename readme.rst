###################
wisdom-volkano
###################

Web Interface for Sharing Data On Monitoring Volkano
(swahili for volcano, from portuguese volcão)

wisdom-volkano is built on top of CodeIgniter 3.0.2 and OpenLayers 3.7.0. 

*******************
Release Information
*******************

This is the second iteration out of three. 
Next one is foreseen in February 2016.


**************************
Changelog and New Features
**************************

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

Tested in Ubuntu 12.04 and Windows 7 and 8.
PHP version 5.4 or newer is recommended. 
Apache 2.2.x
PostgreSQL 9.x + PostGIS 2.1.x
Geoserver version 2.7 on Tomcat 7, on Java 7


************
Installation
************

1. Install Apache httpd on port 80.
2. Install Tomcat on port 8080.
3. Deploy the Geoserver war in Tomcat.
   a. Log in http://name-of-server:8080/geoserver (default is admin/geoserver) and create a new user with admin privileges. Log out and check that it works.
   b. Disable the default admin user.
4. Install PostgreSQL + PostGIS. 
   a. Create a user with enough privileges (at least create, update, select, delete, execute)
5. Copy the wisdom-volkano files in a suitable place por Apache httpd.
6. Go to applications/config and edit
   a. config.php with the geoserver admin user created in 3.a
   b. database.php with the postgresql admin user created in 4.a
7. Log in http://name-of-server/ with admin@admin.com / password
   a. go to Help to read the administration manual
   

*******
License
*******

EUPL v1.1
EUPL is an acronym  for “European Union Public Licence”. The EUPL is the first European Free/Open Source Software (F/OSS) licence. It has been created on the initiative of the European Commission. It is now approved by the European Commission in 22 linguistic versions and can be used by anyone for software distribution.

Please see the licence in the eupl1.1.-licence-en.pdf or in any other language in <https://joinup.ec.europa.eu/software/page/eupl/licence-eupl>

No file data is included in this delivery, being the property of ECGS.

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