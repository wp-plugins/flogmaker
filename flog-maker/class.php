<?php

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

class Flog_Maker
{
 /************************************************************************************************************************************************************************************************************************************************************************************************************
  * Flog_Maker()
  * 
  * This constructor funtion does the following:
  * - Sets the location
  * - Caches the settings
  * - Loads and corrects the query variables
  * - Stores some SQL snippets
  * - Decides the permalink status
  * - Takes the location and loads the appropriate data and/or redirects the user
  */
  
  function Flog_Maker($autoload = true)
  {
    global $wpdb, $wp, $table_prefix, $wp_rewrite;
    
    $acceptable_places = array('index', 'flashBlog', 'couleur','onglet','newcouleur','editcouleur','editonglet','editinfos');
    
    $this->location = in_array(get_query_var('flog_maker_where'), $acceptable_places) ? get_query_var('flog_maker_where') : 'index';
    
    $this->error = false;
    
    $this->settings = array();
    $this->settings = get_option('flog_maker');

    $this->query_vars->newcouleur = stripslashes(trim(get_query_var('flog_maker_newcouleur')));
    $this->query_vars->editcouleur = stripslashes(trim(get_query_var('flog_maker_editcouleur')));
	$this->query_vars->editonglet = stripslashes(trim(get_query_var('flog_maker_editonglet')));
	$this->query_vars->editinfo = stripslashes(trim(get_query_var('flog_maker_editinfo')));
	
    $this->sql = new stdClass;
    
    $this->sql->infos = "SELECT * FROM {$table_prefix}flashblog where id_flog=1";       
    $this->sql->couleurs = "SELECT * FROM {$table_prefix}flashblog_couleurs ORDER BY id";       
    $this->sql->onglets = "SELECT * FROM {$table_prefix}flashblog_onglet ORDER BY nom"; 	
	
    if($autoload == true) {
      switch($this->location){
        case 'flashBlog':
          $this->load_flashBlog_info();
          break;
          
        case 'couleur':       
          $this->load_couleurs_list();
          break;
        
		case 'onglet':       
          $this->load_onglets_list();
          break; 
		  
        case 'newcouleur':
          if(!is_user_logged_in()){
            $this->error = __('You are not logged in!', 'flog-maker');
            return false;
          }

          $this->load_couleurs_list();
          
          if(isset($_POST['flog_maker_do_new_color'])) {
            $this->insert_new_color($_POST['couleur']);
            break;
          }
          
          break;
        
		case 'editinfos':
          if(!is_user_logged_in()) {
            $this->error = __('You are not logged in!', 'flog-maker');
            return false;
          }          
          $this->load_couleurs_info();          
                     
          if(isset($_POST['flog_maker_do_edit_info'])){  
			$this->update_infos($_POST['flog_maker_nom']);
			$this->update_infos($_POST['flog_maker_url']);
          }
          
          break;
		  
		  
        case 'editcouleur':
          if(!is_user_logged_in()) {
            $this->error = __('You are not logged in!', 'flog-maker');
            return false;
          }          
          $this->load_couleurs_info();          
                     
          if(isset($_POST['flog_maker_do_edit_color'])){  
			$this->update_couleur($_POST['flog_maker_couleur_id']);
			$this->update_couleur($_POST['flog_maker_couleur']);
          }
          
          break;
		  
        case 'editonglet':
          if(!is_user_logged_in()){
            $this->error = __('You are not logged in!', 'flog-maker');
            return false;
          }          
          $this->load_onglets_info();          
                     
          if(isset($_POST['flog_maker_do_edit_onglet'])) {		  
			$this->update_onglet($_POST['flog_maker_onglet_id']);
			$this->update_onglet($_POST['flog_maker_onglet_nom']);
			$this->update_onglet($_POST['flog_maker_onglet_fichier']);

		}
          
          break;
		  
        default:
          $this->load_flashBlog_info();
          break;
        
      } // end of switch
    } // end of autoload check
  }
  
 /************************************************************************************************************************************************************************************************************************************************************************************************************/
 
  function parse_arguments($arg_string, $defaults) {
    $decoded = array();
    $return = array();
    
    parse_str($arg_string, $decoded);
    
    foreach($defaults as $key => $value)    {
      $return[$key] = isset($decoded[$key]) ? $decoded[$key] : $value;
    }
    
    return $return;
  }
  
 
  
 /************************************************************************************************************************************************************************************************************************************************************************************************************/
   function load_flashBlog_info()  {
    global $wpdb, $table_prefix;
    
    $this->flashBlog_info = array();
    $result = $wpdb->get_results($this->sql->infos, ARRAY_A);
    
    if(!$result && !is_array($result) && !is_object($result))    {
      $this->error = __('Impossible d\'afficher la liste des couleurd.', 'flog-maker');
      return false;
    }
    
    foreach($result as $row)    {
      $return_obj = new stdClass;
      foreach($row as $key => $value)      {
        $return_obj->$key = $value;
      }
      $this->flashBlog_info[] = $return_obj;
    }
    
    return $this->flashBlog_info;
  }
  
/************************************************************************************************************************************************************************************************************************************************************************************************************
  * load_couleur_list()
  * Charge la liste des couleurs du blog
  */
  
  function load_couleurs_list()  {
    global $wpdb, $table_prefix;
    
    $this->couleurs_list = array();
    $result = $wpdb->get_results($this->sql->couleurs, ARRAY_A);
    
    if(!$result && !is_array($result) && !is_object($result))
    {
      $this->error = __('Impossible d\'afficher la liste des couleurs', 'flog-maker');
      return false;
    }
    
    foreach($result as $row)    {
      $return_obj = new stdClass;
      foreach($row as $key => $value)
      {
        $return_obj->$key = $value;
      }
      $this->couleurs_list[] = $return_obj;
    }
    
    return $this->couleurs_list;
  }
/************************************************************************************************************************************************************************************************************************************************************************************************************/
function load_onglets_list($arg_string = '') {
   global $wpdb, $table_prefix;
    
    $this->onglets_list = array();
    $result = $wpdb->get_results($this->sql->onglets, ARRAY_A);
    
    if(!$result && !is_array($result) && !is_object($result))    {
      $this->error = __('Impossible d\'afficher la liste des onglets.', 'flog-maker');
      return false;
    }
    
    foreach($result as $row)    {
      $return_obj = new stdClass;
      foreach($row as $key => $value)      {
        $return_obj->$key = $value;
      }
      $this->onglets_list[] = $return_obj;
    }
    
    return $this->onglets_list;
  }
  
 /************************************************************************************************************************************************************************************************************************************************************************************************************/
  
  function insert_new_color($post_content)  {
    global $wpdb, $userdata, $table_prefix;
    
    $couleur = trim($couleur);
    
    if($couleur == '')    {
      $this->error = __('Vous n\'avez pas renseigné de couleur!', 'flog-maker');
      return false;
    }

    $result = $wpdb->query("INSERT INTO {$table_prefix}flog_maker_couleurs (couleur}','{$couleur}')");
    
    if(!$result)    {
      $this->error = __('Nous avons des problèmes pour sauvegarder la couleur.', 'flog-maker');
      return false;
    }    
    
    header('Location: '.$this->build_url("page=edit-couleurs.php").'');
    header('Status: 302'); // Change here 180706
  }
  
 /************************************************************************************************************************************************************************************************************************************************************************************************************/
 
 function update_couleur($couleur)  {
    global $wpdb, $userdata, $table_prefix;
    
    $couleur = trim($couleur);
    
    if($couleur == '')    {
      $this->error = __('You didn\'t enter a color!', 'flog-maker');
      return false;
    }
    
    $result = $wpdb->query("UPDATE {$table_prefix}flog_maker_couleurs SET couleur = '{$couleur}' WHERE id = '".$wpdb->escape($this->query_vars->editcouleur)."' LIMIT 1");
    
    if(!$result && $result !== 0)    {
      $this->error = __('We had problems updating the color.', 'flog-maker');
      return false;
    }    else    {
       header('Location: '.$this->build_url("page=edit-couleurs").'');
	   header('Status: 302'); // Change here 180706
    }
  }

 /************************************************************************************************************************************************************************************************************************************************************************************************************/
 
  function update_onglet($onglet)  {
    global $wpdb, $userdata, $table_prefix;    
    $onglet = trim($onglet);    
    if($onglet == '')    {
      $this->error = __('You didn\'t enter info!', 'flog-maker');
      return false;
    }
    
    $result = $wpdb->query("UPDATE {$table_prefix}flashblog_onglets SET nom = '{$onglet}' WHERE id = '".$wpdb->escape($this->query_vars->editonglet)."' LIMIT 1");
    
    if(!$result && $result !== 0)    {
      $this->error = __('We had problems updating the onglet.', 'flog-maker');
      return false;
	  }    else    {
       header('Location: '.$this->build_url("page=edit-onglets").'');
	   header('Status: 302'); // Change here 180706
    }
  }
  
 /************************************************************************************************************************************************************************************************************************************************************************************************************/
  
  function build_url($arg_string = '')  {
    global $wp_rewrite, $userdata;
    
    $default_args = array('type' => null);
    $args = $this->parse_arguments($arg_string, $default_args);
    
    if($args['type'] == null) { return false; }
    
    if($args['page'])    {
      $page_part = $this->use_permalinks ? "page-{$args['page']}/" : "&flog_maker_page={$args['page']}";
    }    else    {
      $page_part = '';
    }    
    $page_root = $wp_rewrite->using_permalinks() ? get_bloginfo('home').'/'.$wp_rewrite->root."{$this->settings['forum_page_slug']}/" : get_bloginfo('home')."/index.php?pagename={$this->settings['forum_page_slug']}";
     
  }  

 /************************************************************************************************************************************************************************************************************************************************************************************************************/
 
  function edit_type_field()  {
    $str = '';    
    switch($this->location)    {
      case 'newcouleur':
        $str = '<input type="hidden" name="flog_maker_do_new_couleur" value="'.$this->query_vars->couleurs.'" />';
        break;
      
      case 'editcouleur':
        $str = '<input type="hidden" name="flog_maker_do_edit_color" value="'.$this->query_vars->couleurs.'" />';
        break;
		
	  case 'editonglet':
        $str = '<input type="hidden" name="flog_maker_do_edit_onglet" value="'.$this->query_vars->onglets.'" />';
        break;
    }
    
    return $str;
  }
  
 /************************************************************************************************************************************************************************************************************************************************************************************************************/
  
  function is_index()     { return ($this->location == 'index'   );}
  function is_couleur()     { return ($this->location == 'couleurs'   );}
  function is_onglet() { return ($this->location == 'onglets');}
  function is_bg()     { return ($this->location == 'edit-bg'   );}
  
 /************************************************************************************************************************************************************************************************************************************************************************************************************/
  
  function error_occured()     { return (  (bool)$this->error); }
  function couleurs_ok()     { return ( !(bool)$this->error && (bool)$this->couleurs_list); }
  function onglets_ok()     { return ( !(bool)$this->error && (bool)$this->onglets_list); }   
	  
}

?>