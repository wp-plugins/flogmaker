<?php
  //require_once (ABSPATH . WPINC . '/rss-functions.php');
  
  add_action('admin_menu', 'flog_maker_admin_menu_populate');
  
  function flog_maker_admin_menu_populate()  {    
    add_menu_page(__('FlashBlog', 'flog-maker'), __('FlashBlog', 'flog-maker'), 'manage_options', 'flog-maker', 'flog_maker_admin_overview');
    add_submenu_page('flog-maker', __('FlashBlog Infos', 'flog-maker'), __('Gestion des infos', 'flog-maker'), 'manage_options', 'flog-maker-infos', 'flog_maker_admin_infos');
    add_submenu_page('flog-maker', __('Editer les onglets', 'flog-maker'), __('Gestion des onglets', 'flog-maker'), 'manage_options', 'flog-maker-onglets', 'flog_maker_admin_onglets');
    add_submenu_page('flog-maker', __('Editer couleurs et fond', 'flog-maker'), __('Couleurs et fond', 'flog-maker'), 'manage_options', 'flog-maker-couleurs', 'flog_maker_admin_couleurs');
    add_submenu_page('flog-maker', __('Advanced', 'flog-maker'), __('Advanced', 'flog-maker'), 'manage_options', 'flog-maker-advanced', 'flog_maker_advanced');
  
  }
  
  /************************************************************************/
  include 'overview.php';
  include 'infos.php';
  include 'couleurs.php';
  include 'onglets.php';
  include 'advanced.php';
  
  
  /**************************************************************************/

  add_action('admin_head', 'flog_maker_admin_linkback_style');

  function flog_maker_admin_linkback_style(){
    //if(!current_user_can('edit_others_posts'))
    //{
      echo '<style type="text/css">#flashblog_linkback { position: absolute; right: 1em; top: 3em; color: #fff; font-size: .9em; } #flashblog_linkback a, #flashblog_linkback a:hover {color: #ffffff} </style>';
    //}
  }

  /**************************************************************************/

  add_action('admin_footer', 'flog_maker_admin_linkback_message');

  function flog_maker_admin_linkback_message(){
    global $wp_rewrite;
    $options = get_option('flog_maker');

  }
  
  /************************************************************************/
  
  
  add_action('deactivate_flog-maker/flog-maker.php', 'flog_maker_admin_deactivation');
  
  function flog_maker_admin_deactivation(){
    global $wpdb, $table_prefix;
    $options = get_option('flog_maker');
    $wpdb->query("DELETE FROM {$table_prefix}posts WHERE post_name = '".$wpdb->escape($options['flashblog_page_slug'])."' LIMIT 1");
  }
  
  /************************************************************************/
  
  add_action('activate_flog-maker/flog-maker.php', 'flog_maker_admin_installer');
  
  function flog_maker_admin_installer(){
    global $wpdb, $table_prefix, $wp_rewrite;
    
    $default_options = array
    (
      'flashblog_page_slug' => 'flashblog',
      'edit_duration' => "30",
      'flog_maker_version' => FLOG_MAKER_VERSION,          
    );
    
    $options = get_option('flog_maker');
    
    if(!is_array($options)){
      $type = 'install';
      $options = $default_options;
    }elseif(version_compare($options['flog_maker_version'], FLOG_MAKER_VERSION, "<")){
      $type = 'upgrade';
      foreach($default_options as $key => $value){
        if(!isset($options[$key])) { $options[$key] = $value; }
      }
      unset($options['flog_maker_version']);
      $options['flog_maker_version'] = FLOG_MAKER_VERSION;
      update_option('flog_maker', $options);
    } else{
      $type = 'reactivate';
    }
    
    if($type == 'install' || $type == 'upgrade'){
      $mysql_version = $wpdb->get_var("SELECT VERSION()");
      $parts = explode('.', $mysql_version);
      $engine_string = ($parts[0] < 5) ? 'TYPE' : 'ENGINE';
      
      require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
      
      dbDelta(
       "CREATE TABLE {$table_prefix}flashblog 
        (
          id_flog int(11) unsigned NOT NULL auto_increment,
          nom varchar(200) NOT NULL,
          url varchar(255) NOT NULL,
		  logo varchar(255),
          PRIMARY KEY  (id_flog)
        ) {$engine_string}=MyISAM;"
      );
      
      dbDelta(
       "CREATE TABLE {$table_prefix}flashblog_couleurs 
        (
          id bigint(20) unsigned NOT NULL auto_increment,
          couleur varchar(6) NOT NULL,          
          PRIMARY KEY  (id)
         ) {$engine_string}=MyISAM ;"
      );
      
      dbDelta(
       "CREATE TABLE {$table_prefix}flashblog_onglets
        (
          id bigint(20) unsigned NOT NULL auto_increment,
          nom varchar(200) NOT NULL,
		  fichier varchar(200) NOT NULL,
		  actif int(11) NOT NULL,
		  nom_url varchar(200) NOT NULL,
		  picto varchar(200) NOT NULL,
		  PRIMARY KEY  (id)
        ) {$engine_string}=MyISAM;"
      );
    }
    
       
	$wpdb->query("INSERT INTO {$table_prefix}flashblog_couleurs (couleur) VALUES ('85C5A5') ");
	$wpdb->query("INSERT INTO {$table_prefix}flashblog_couleurs (couleur) VALUES ('978C63') ");
	$wpdb->query("INSERT INTO {$table_prefix}flashblog_couleurs (couleur) VALUES ('F6DB56') ");
	$wpdb->query("INSERT INTO {$table_prefix}flashblog_couleurs (couleur) VALUES ('895B55') ");
	$wpdb->query("INSERT INTO {$table_prefix}flashblog_couleurs (couleur) VALUES ('92BB4B') ");
	$wpdb->query("INSERT INTO {$table_prefix}flashblog_couleurs (couleur) VALUES ('DFA594') ");
	$wpdb->query("INSERT INTO {$table_prefix}flashblog_couleurs (couleur) VALUES ('A0C1D0') ");
	$wpdb->query("INSERT INTO {$table_prefix}flashblog_couleurs (couleur) VALUES ('8CCD89') ");	 
	
	$wpdb->query("INSERT INTO {$table_prefix}flashblog_onglets (nom,fichier,actif,nom_url,picto) VALUES ('Archives','agenda.swf',1,'archives','agenda.swf') ");
	$wpdb->query("INSERT INTO {$table_prefix}flashblog_onglets (nom,fichier,actif,nom_url,picto) VALUES ('Contact','contact.swf',1,'contact','contact.swf') ");
	$wpdb->query("INSERT INTO {$table_prefix}flashblog_onglets (nom,fichier,actif,nom_url,picto) VALUES ('Links','liens.swf',1,'Links','liens.swf')");
	$wpdb->query("INSERT INTO {$table_prefix}flashblog_onglets (nom,fichier,actif,nom_url,picto) VALUES ('Search','recherche.swf',1,'Search','recherche.swf') ");
	
	$wpdb->query("INSERT INTO {$table_prefix}flashblog (id_flog,nom,url,logo) VALUES (1,'FlashBlog', 'wp-flashblog/', 'logo.png') ");
	
	$filenameConfig = '../wp-config.php';
	// Assurons nous que le fichier est accessible en écriture
	if (file_exists($filenameConfig)) {
	   // Dans notre exemple, nous ouvrons le fichier $filename en mode d'ajout
	   // Le pointeur de fichier est placé à la fin du fichier
	   // c'est là que $somecontent sera placé
	   if (!$handle = fopen($filenameConfig, 'r')) {
	         echo "Impossible d'ouvrir le fichier ($filenameConfig)";
	         exit;
	   }	   
		$somecontent ="<?php
";

		if ($handle) {
			while (!feof($handle)) {
				$buffer = fgets($handle, 4096);
				
				$findme="DB_NAME";
				$pos = strpos($buffer, $findme);
				if ($pos === false) {
					//echo "La chaîne '$findme' n'a pas été trouvée dans la chaîne '$mystring'";
				} else {
					$somecontent =$somecontent.$buffer;
				}
				$findme="DB_USER";
				$pos = strpos($buffer, $findme);
				if ($pos === false) {
					//echo "La chaîne '$findme' n'a pas été trouvée dans la chaîne '$mystring'";
				} else {
					$somecontent =$somecontent.$buffer;
				}
				$findme="DB_PASSWORD";
				$pos = strpos($buffer, $findme);
				if ($pos === false) {
					//echo "La chaîne '$findme' n'a pas été trouvée dans la chaîne '$mystring'";
				} else {
					$somecontent =$somecontent.$buffer;
				}
				$findme="DB_HOST";
				$pos = strpos($buffer, $findme);
				if ($pos === false) {
					//echo "La chaîne '$findme' n'a pas été trouvée dans la chaîne '$mystring'";
				} else {
					$somecontent =$somecontent.$buffer;
				}
				$findme="table_prefix";
				$pos = strpos($buffer, $findme);
				if ($pos === false) {
					//echo "La chaîne '$findme' n'a pas été trouvée dans la chaîne '$mystring'";
				} else {
					$somecontent =$somecontent.$buffer;
				}
			}
			fclose($handle);
		}	   
		// $table_prefix is deprecated as of 2.1
$somecontent =$somecontent."if ( file_exists('../../wp-includes/wp-db.php') )";
$somecontent =$somecontent."
include_once ('../../wp-includes/wp-db.php');";
$somecontent =$somecontent."
else";
$somecontent =$somecontent."
include_once ('../wp-includes/wp-db.php');";
$somecontent =$somecontent."
//change this line for wpmu. The prefixe is the id of your blog and the config table prefixe";
$somecontent =$somecontent.'
$wpdb->prefix = $table_prefix;';
$somecontent =$somecontent."
//change this line for wpmu. The user table prefixe is not the same that table_prefix";
$somecontent =$somecontent.'
$table_prefixUser=$table_prefix;';

	   	$somecontent =$somecontent."
?>"; 
	 
	} else {
	   echo "Le fichier $filenameConfig n'est pas accessible en écriture.";
	}
	$filename = '../wp-flashblog/PHP/config.php';
	if (is_writable($filename)) {
	   if (!$handle = fopen($filename, 'w')) {
	         echo "Impossible d'ouvrir le fichier ($filename)";
	         exit;
	   }
	   // Ecrivons quelque chose dans notre fichier.
	   if (fwrite($handle, $somecontent) === FALSE) {
	       echo "Impossible d'écrire dans le fichier ($filename)";
	       exit;
	   }	  
	    fclose($handle);
	} else {
	   echo "Le fichier $filename n'est pas accessible en écriture.";
	}
	
    $options['flashblog_page_slug'] = $name;
    update_option('flog_maker', $options);
    $wp_rewrite->flush_rules();
  }

?>