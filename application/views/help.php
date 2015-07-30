<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title>Help | wisdom-volkano</title>

  <link href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>" rel="stylesheet" />
  <link href="<?php echo base_url('assets/css/docs.min.css'); ?>" rel="stylesheet" />
</head>
<body>

<body>
  <div class="container">
      <div class="row Navigation">
          <div class="menu col-xs-12">
            <?php echo menu('Help'); ?>
          </div>
      </div>
      <div class="row Main">
          <div class="col-md-9">

          
<h1 id="help">Help of wisdom-volkano</h1>
<p>The work is motivated by the findings by Smets et al. [<a href="#ref1">1</a>]. With a multivariate analysis, some pre-eruptive signals were found up to 3 weeks prior the 2010 eruption at the volcano Nyamulagira. Each signal on its own was not significant enough; they went unnoticed until a subsequent re-analysis of the InSAR data with a new methodology, MSBAS, triggered the finding. However, these signals contrast with the precursor activity commonly recognized before an eruption at Nyamulagira, namely an increase of the seismicity few hours prior the lava outburst.</p>

<p>This analysis will contribute to some broader multidisciplinary research projects, like the GeoRisCa [<a href="#ref4">2</a>] or the RESIST project [<a href="#ref5">3</a>] among others, aiming at identifying, characterizing and understanding the source mechanisms driving volcanic eruptions, in order to potentially contribute to early eruption warning. Indeed, in order to improve the analysis and the results, methods and objectives might be adapted depending on the results of the two main projects mentioned.</p>

<p>Points of contact:<br/>
- Fulgencio Sanmartín, <a href="mailto:fulgencio.sanmartin@gmail.com">fulgencio.sanmartin@gmail.com</a>, student at Lund University and author of wisdom-volkano;<br/>
- Nicolas D’Oreye, <a href="mailto:ndo@ecgs.lu">ndo@ecgs.lu</a>, researcher at ECGS.</p>


<?php 
    if( $this->ion_auth->is_admin() ) 
    { 
?>
<h2 id="admin">Administration</h2>
<p>Only (logged in) administrators can read this.</p>

<h3 id="admin-users-geo">Users - Geoserver</h3>
<p>Only one user is needed for wisdom-volkano, with administrator privileges. However, it is recommended not to use the built-in <code>admin</code> user, whose default password prevents the REST interface to be accessed for security reasons.</p>
<p>The user will be created at installation and is configured in the config file <code>application/config/config.php</code>.</p>

<h3 id="admin-users-pg">Users - PostgreSQL</h3>
<p>Only one user is needed for wisdom-volkano, with select, update, insert and delete privileges on the tables and select, usage and update on the sequences.</p>
<p>The user will be created at installation and is configured in the config file <code>application/config/database.php</code>.</p>

<h3 id="admin-users-ci">Users - wisdom-volkano</h3>
<p>After logging in, only administrators will be able to see the Admin option in the menu. This option leads to five different options divided in two groups: users and layers (covered <a href="#admin-layers-ci">below</a>). The main submenu for the latter is User list.</p>
<p>The user list will show all users created within the wisdom-volkano. Here there are a number of options available:</p>
<ul>
  <li>Create a new user (also from the menu)</li>
  <li>Edit an existing user</li>
  <li>Deactivate or activate a user</li>
  <li>Create a group (from the menu)</li>
  <li>Edit a group</li>
</ul>

<h4>Create a new user</h4>
<p>All six fields listed (first and last names, institution, email, phone and password) are required. The email must be unique and will not be possible to edit it again. The password has a minimum length of eight (8) characters, and it must be confirmed. </p>
<p>Once done, clicking Submit will create the user and return to the list. By default, a new user is placed in the members group.</p>
<p>If there was a problem (for example, leaving the phone empty), the system will say the reason and stay in the same screen.</p>

<h4>Edit an existing user</h4>
<p>All fields can be modified except the email. Also, in this screen the groups to which the edited user belongs to can be added or modified.</p>
<p>Once done, clicking Submit will modify the user data and return to the list. </p>
<p>If there was a problem (for example, trying to modify the password with a too short one), the system will say the reason and stay in the same screen.</p>

<h4>Deactivate or activate a user</h4>
<p>There is no way to remove a user, but it can be deactivated. By default, all users are created active. In the list, clicking on Deactivate will lead to a screen for confirmation.</p>
<p>If confirmed, the user will be deactivated and will not be able to log in.</p>
<p>When deactivated, the link in User list will be the opposite, and will allow to Activate the user. There is no confirmation for activating a user.</p>

<h4>Create a group</h4>
<p>By default, there are two groups: administratos, who can access to this menu of options, and members, who cannot. New groups can be created for further functionalities in the future.</p> 
<p>Both fields (group name and description) are required. </p>
<p>Once done, clicking Submit will create the group and return to the user list. </p>
<p>If there was a problem (for example, leaving the description empty), the system will say the reason and stay in the same screen.</p>

<h4>Edit a group</h4>
<p>Clicking on the name of a group in the User list will allow to edit the name or the description of the group.</p>
<p>Groups cannot be deleted.</p>


<h3 id="admin-layers-geo">Layers - Geoserver</h3>

<p>GeoServer structures its content into three levels:</p>
<ol>
  <li>Workspaces: or namespaces. Names are recommended to be short, e.g. test or cint or dem.</li>
  <li>Stores: one per file. For features (e.g. <abbr title="Shape files" class="initialism">SHP</abbr> files), they can have several layers in one file. For rasters, usually just one layer per file, and both names (store, layer) shall be the same.</li>
  <li>Layers: depending on the Store, see above. The resulting name will be <code>workspace:layername</code>.</li>
</ol>
<p>It is highly recommended to adopt early a meaningful and short name convention.</p>

<h4>Create a workspace</h4>
<p>There are only two fields to be filled up, the name and the namespace URI, and both can be the same.</p>
<p>After the data analysis, it is recommended that at least the following workspaces are created (small letters are easier to read):</p>
<ul>
  <li><abbr title="Amplitude">amp</abbr></li>
  <li><abbr title="Complex Interferogram">cint</abbr></li>
  <li><abbr title="Coherence">coh</abbr></li>
  <li><abbr title="Digital Elevation Model">dem</abbr></li>
  <li><abbr title="Unwrapped Interferogram">uint</abbr></li>
  <li>mask</li>
</ul>
<p>Other workspaces will be added in future deliveries.</p>
<p>The default workspace can be any of them. After the creation, editing a workspace will allow to add more data which are not relevant for this project.</p>
<p>For further and updated information, please refer to the <a href="http://docs.geoserver.org/stable/en/user/webadmin/data/workspaces.html" title="GeoServer manual on Workspaces" target="_blank">GeoServer manual on Workspaces&nbsp;<span class="glyphicon glyphicon-new-window" aria-hidden="true"></span></a>.</p>

<h4>Create a store (link to a file)</h4>
<p>When creating a new store, there are three basic sequential steps:</p>
<ol>
  <li>Select the type of data source: GeoTIFF, Shapefile, etc. </li>
  <li>Create the store itself: there are three important data here: 
    <ul>
      <li>the workspace to associate the store with; it should be already created;</li>
      <li>the name of the store (and optionally a description); it is easier to select first the file and finally assign the name;</li>
      <li>and the link to the file.</li> 
    </ul>
  </li>
  <li>And finally the system offers to create ("Publish") the layer directly, which is covered below. </li>
</ol>  
<p>For further and updated information, please refer to the <a href="http://docs.geoserver.org/stable/en/user/webadmin/data/stores.html" title="GeoServer manual on Stores" target="_blank">GeoServer manual on Stores&nbsp;<span class="glyphicon glyphicon-new-window" aria-hidden="true"></span></a>.</p>

<h4>Create a layer</h4>
<p>The first step when adding a new layer is to choose from the existing stores. Only those added will be available. Once selected, there will be a Publish button, but just don't click it yet.</p>
<p>
<div class="alert alert-warning" role="alert">Attention for rasters: at this point, the name of the store and the <em>suggested</em> name of the layer are probably different, yet they should be the same.</div>
<p><img src="<?php echo base_url('assets/img/layer_create.gif');?>" alt="Create layer example" style="border: solid 1px black;"/></p>
<p>If it is a raster, keep in mind or copy the name of the store that you can see in the dropdown selected.</p>
<p>Click on Publish, and a lot of fields will be available to be modified, distributed in four tabs. You may leave default values for most of fields, as GeoServer does usually a very good job on them. Only the first tab is reviewed here:</p>
<ul>
  <li>Enabled must be checked, otherwise the layer will not work.</li>
  <li>Advertised must be checked, otherwise the layer will not be listed.</li>
  <li>Name: the one used for the GeoServer service. For rasters, you should change it to the store name from the previous screen, except the  workspace or the colon.</li>
  <li>Title: you may use something more descriptive, human readable, than in the name.</li>
</ul>  
<p>At the end, something like this list should be available: </p>
<p><img src="<?php echo base_url( 'assets/img/layer_list.gif' );?>" alt="Layer list example" style="border: solid 1px black;"/></p>
<p>For further and updated information, please refer to the <a href="http://docs.geoserver.org/stable/en/user/webadmin/data/layers.html" title="GeoServer manual on Stores" target="_blank">GeoServer manual on Stores&nbsp;<span class="glyphicon glyphicon-new-window" aria-hidden="true"></span></a>.</p>

<h4>Create a <abbr title="Styled Layer Descriptor" class="initialism">SLD</abbr></h4>
<p>The last link in the Data panel in GeoServer is Styles. Clicking in it will show a list of predefined styles, though some more can be added, specially for interferograms.</p>
<p>Some SLD file examples are included in the release, like <code>release/geoserver/sld/sld_cint.xml</code>. You need to add them to GeoServer in the Styles page, and then make them available for the layer editing it.</p>
<p>For further and updated information, an entire book on SLD is available in the <a href="http://docs.geoserver.org/latest/en/user/styling/sld-cookbook/" title="GeoServer SLD cookbook" target="_blank">GeoServer SLD cookbook&nbsp;<span class="glyphicon glyphicon-new-window" aria-hidden="true"></span></a>.</p>


<h3 id="admin-layers-ci">Layers - wisdom-volkano</h3>
<p>Layer administration is the other option only available for administrators in the menu. The submenu has two options: </p>
<ul>
  <li>List layers</li>
  <li>Create a new layer</li>
</ul>

<h4>List layers</h4>
<p>All layers already configured in wisdom-volkano will be listed here, with a number of data:</p>
<ul>
  <li>Who created the layer (referenced from GeoServer).</li>
  <li>The layer name in workspace:name format.</li>
  <li>What type the layer is, see below.</li>
  <li>Which users are granted the use of the layer.</li>
  <li>And the two actions available for each layer: edit and delete.</li>
</ul>
<div class="alert alert-info" role="alert">Layers are not really created or deleted in wisdom-volkano - both are only possible in GeoServer. Create in this context means referencing from GeoServer; delete means just removing the reference: the layer will be available to be created (referenced) again.</div>
<p>Editing a layer will bring to a similar screen than creating one.</p>

<h4>Create a new layer</h4>
<p>Getting a layer from GeoServer so it can be used in wisdom-volkano has four options:</p>
<ul>
  <li>Select layer from a dropdown of workspace:name_layers. These are the ones made available in the GeoServer layers above.</li>
  <li>Type of layer: raster, dem, feature (point, line, polygon). This has no use for the moment.</li>
  <li>Description.</li>
  <li>Users to grant the use of the layer.</li>
</ul>  

<?php
    } // end of admin 
?>

<h2 id="map">Map screen</h2>
<p>All users (logged in) can read this.</p>

<h3 id="login">Login/logout</h3>
<p>In order to use wisdom-volkano, you need to have a proper user and password provided by an administrator. Please do not try to break in without permission. If you know of any improper use, please let administrators know.</p>
<p>Once logged in, you will be directed to the <a href="#map-panel" title="map panel">map panel</a> by default. The session will continue to work while you keep doing actions (like clicking on points or loading new layers). </p>
<p>However, it will last around 30' (thirty minutes) if you do no interactions (like reading this help). In that case, you may be redirected to the login again - and lose any non saved work.</p>
<p>When you are done, be sure to log out by clicking the option in the top menu. That way, you will avoid any security problems (like a hacker stealing your session).</p>

<h3 id="map-panel">Map panel</h3>
<p>In the home screen, the main component and biggest panel is the map, where the layers are shown. Any configuration applied in the <a href="layers" title="Layers panel">layers panel</a> will be shown here.</p>
<p>Within the map there are three main controls:</p>
<ul>
  <li>Zoom: either clicking on the +/- buttons, or with the scroll button on your keyboard or mouse (wheel), you may zoom in and out in the map. The scale at the bottom left will be updated depending on the zoom.</li> 
  <li>Pan: you may click with the mouse, hold the click and drag around to pan. North is up.</li>
  <li>Click: when clicking and not holding with the mouse, a small announcement with the coordinates will be shown. In future iterations, this will be related to the <a href="config" title="Configuration panel">Configuration</a>, <a href="chart" title="Chart panel">Chart</a> and the <a href="data" title="Data panel">Data</a> panels.</li>
</ul>
<p>By default, when entering the system for the first time and no <a href="layers" title="Layers panel">layers</a> are added yet, wisdom-volkano is configured by default with some backgrounds.</p>

<h3 id="layers">Layers panel</h3>
<p>Layers are shown grouped by workspace. By default, some backgrounds are available. When it is your first time in wisdom-volkano, click on <a href="#manage-layers" title="Manage layers">Manage layers</a>:

<h4 id="manage-layers">Manage layers</h4> 
<p>By clicking on Manage layers, a dialog screen will open with all granted layers. The layers can be either enabled or disabled (by default). A layer will not be shown in the <a href="#layers" title="Layers panel">Layers panel</a> unless is enabled.</p>
<p>Also, layers can be moved by drag and drop in this dialog screen. They are stacked up: the last one in the list is the one most visible in the <a href="#map-panel" title="Map panel">Map panel</a>.</p>
<p>The order of layers can be any, but it is highly recommended that they are grouped by workspace.</p>
<p>Once finished, the Save button must be clicked to save the changes. To discard them, you may click the Close button.</p>

<h4 id="visib-layers">Layers visibility</h4>
<p>When clicking on one workspace, the list of layers available and enabled (in the <a href="Manage layers" title="Manage layers">Manage layers</a> dialog) will be shown. For each layer, including the backgrounds, there are two possibilities:
<ul>
    <li>If it is visible or not.</li>
    <li>The opacity, from completely transparent to completely opaque.</li>
</ul>

<h3 id="config">Config panel</h3>
<p>Iteration 2.</p>

<h3 id="chart">Chart panel</h3>
<p>Iteration 2.</p>

<h3 id="data">Data panel</h3>
<p>Iteration 2.</p>

<h2 id="ref">References</h2>

<p id="ref1">1. Smets, B. et al. Detailed multidisciplinary monitoring reveals pre- and co-eruptive signals at Nyamulagira volcano (North Kivu, Democratic Republic of Congo). <em>Bulletin of Volcanology. Springer Berlin Heidelberg.</em> 2013, 76:787. <a target="_blank" href="http://dx.doi.org/10.1007/s00445-013-0787-1">http://dx.doi.org/10.1007/s00445-013-0787-1</a></p>

<p id="ref4">2.	GeoRisCa project: Geo-Risk in Central Africa: integrating multi-hazards and vulnerability to support risk management. <a target="_blank" href="http://georisca.africamuseum.be">http://georisca.africamuseum.be</a></p>

<p id="ref5">3.	Royal Museum for Central Africa. National Museum of Natural History / European Centre for Geodynamics and Seismology. Centre Spatial de Liège. Belgian Institute for Space Aeronomy. NASA. RESIST project. <a target="_blank" href="http://www.ecgs.lu/gorisk/projects/resist-2015-2018"> http://www.ecgs.lu/gorisk/projects/resist-2015-2018</a>


          </div>


        <div class="col-md-3" role="complementary">
          <nav class="bs-docs-sidebar hidden-print hidden-xs hidden-sm">
            <ul class="nav bs-docs-sidenav">

<li><a href="#help">Help</a></li>

<?php 
    if( $this->ion_auth->is_admin() ) 
    { 
?>
<li>
  <a href="#admin">Administration</a>
  <ul class="nav">
    <li><a href="#admin-users-geo">Users Geoserver</a></li>
    <li><a href="#admin-users-pg">Users PostgreSQL</a></li>
    <li><a href="#admin-users-ci">Users wisdom-volkano</a></li>
    <li><a href="#admin-layers-geo">Layers Geoserver</a></li>
    <li><a href="#admin-layers-ci">Layers wisdom-volkano</a></li>
  </ul>
</li>
<?php
    } // end of admin 
?>

<li>
  <a href="#map">Map screen</a>
  <ul class="nav">
    <li><a href="#login">Login/logout</a></li>
    <li><a href="#map-panel">Map panel</a></li>
    <li><a href="#layers">Layers panel</a></li>
    <li><a href="#manage-layers">Manage layers</a></li>
    <li><a href="#visib-layers">Layers visibility</a></li>
    <li><a href="#config">Config panel</a></li>
    <li><a href="#chart">Chart panel</a></li>
    <li><a href="#data">Data panel</a></li>
  </ul>
</li>
<li>
  <a href="#ref">References</a>
  <ul class="nav">
    <li><a href="#ref1">Smets et al., 2013</a></li>
    <li><a href="#ref4">GeoRisCa</a></li>
    <li><a href="#ref5">R. Museum Central Africa</a></li>
  </ul>
</li>

            </ul>
            <a class="back-to-top" href="#top">
              Back to top
            </a>
          </nav>
        </div>
          
      </div>
  </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="<?php echo base_url('assets/js/jquery-2.1.4.min.js');?>"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?php echo base_url('assets/js/bootstrap.min.js');?>"></script>
    <script src="<?php echo base_url('assets/js/docs.min.js');?>"></script>

</body>
</html>
