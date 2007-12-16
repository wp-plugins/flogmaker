<?php

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

function flog_maker_admin_infos(){    
    global $wpdb, $table_prefix;
	  
    if(isset($_POST['update'])){
        check_admin_referer('update-configuration_flog_maker');   		
		$update_result = $wpdb->query("UPDATE {$table_prefix}flashblog SET nom = '".$wpdb->escape(stripslashes($_POST['nom_flog']))."',url='".$wpdb->escape(stripslashes($_POST['url_flog']))."'");
		if($update_result){
			echo '<div id="message" class="updated fade-ffff00"><p>'.__('Param&egrave;tres enregistr&eacute;s.', 'flog-maker').'</p></div>';
		}else{
			echo '<div id="message" class="updated fade-ff0000"><p>'.__('Param&egrave;tres non enregistr&eacute;s.', 'flog-maker').'</p></div>';
		}
	}	

	$target     = '../wp-flashblog/img/';  // Repertoire cible
	$max_size   = 100000;     // Taille max en octets du fichier
	$width_max  = 203;        // Largeur max de l'image en pixels
	$height_max = 64;        // Hauteur max de l'image en pixels
	$nom_file   = $_FILES['fichier']['name'];
	$taille     = $_FILES['fichier']['size'];
	$tmp        = $_FILES['fichier']['tmp_name']; 
	
	if(isset($_POST['update_logo'])){
		if(!empty($_POST['posted'])) {
			if(!empty($_FILES['fichier']['name'])) {				
				$infos_img = getimagesize($_FILES['fichier']['tmp_name']);
				if(($infos_img[0] <= $width_max) && ($infos_img[1] <= $height_max) && ($_FILES['fichier']['size'] <= $max_size)) {
					if(move_uploaded_file($_FILES['fichier']['tmp_name'],$target.$_FILES['fichier']['name'])) {
						echo '<div id="message" class="updated fade-ffff00"><p>'.__('Logo mis &agrave; jour', 'flog-maker').'</p></div>';						  		
						$update_result = $wpdb->query("UPDATE {$table_prefix}flashblog SET logo = '".$nom_file."'");		
					} else {
						echo '<div id="message" class="updated fade-ff0000"><p>'.__('Le t&eacute;l&eacute;chargement a &eacute;chou&eacute;', 'flog-maker').'</p></div>';
					}
				} else {
					echo '<div id="message" class="updated fade-ff0000"><p>'.__('Attention, le logo est trop grand ! taille maxi : 203*64', 'flog-maker').'</p></div>';
				}				
			} else {
				echo '<b>Le champ du formulaire est vide !</b><br /><br />';
			}
		}
	}
	
	$options = $wpdb->get_row("SELECT * FROM {$table_prefix}flashblog WHERE id_flog = 1 LIMIT 1");       
    ?>
	 <div class="wrap">	
      <form method="post" action="<?php echo $_SERVER['REQUEST_URI'] ?>"> 
        <?php if (function_exists('wp_nonce_field')) { wp_nonce_field('update-configuration_flog_maker'); } ?>
       
			 <h2><?php _e('Param&egrave;tres', 'flog-maker') ?></h2>		
            <table width="100%" cellspacing="2" cellpadding="5" class="editform"> 			
              <tr valign="top"> 
                <th width="33%" scope="row">
                  <label for="nom_flog"><?php _e('Nom du blog:', 'flog_maker') ?></label>
                </th> 
                <td>
                  <input name="nom_flog" type="text" id="nom_flog" style="text-align: left" value="<?php echo format_to_edit($options->nom) ?>" size="50" /> <br />
                </td>
              </tr>
			  <tr valign="top"> 
                <th width="33%" scope="row">
                  <label for="url_flog"><?php _e('URL :', 'flog_maker') ?></label>
                </th> 
                <td>
                  <input name="url_flog" type="text" id="url_flog" style="text-align: left" value="<?php echo format_to_edit($options->url) ?>" size="50" /> <br />
                </td>
              </tr>
		    </table> 
            <p class="submit"><input type="submit" name="update" value="<?php _e('Update Options', 'flog_maker') ?> &raquo;" /></p>	  
       
        </form>   
		<form enctype="multipart/form-data" action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="POST">
			<table width="100%" cellspacing="2" cellpadding="5" class="editform"> 			
	            <tr valign="top"> 
	                <th width="33%" scope="row">
						<b><?php _e('Logo actuel :', 'flog_maker') ?></b>
					</th> 
	                <td>
						<img src="../wp-flashblog/img/<? echo $options->logo?>" border="0">
					</td>
				</tr>
				<tr valign="top"> 
	                <th width="33%" scope="row">
						<b><?php _e('Fichier :', 'flog_maker') ?></b><br />
						<?php _e('Taille maxi 203*64 ', 'flog_maker') ?>
					</th> 
	                <td>
						<input type="hidden" name="posted" value="1" />
						<input name="fichier" type="file" />
					</td>
				</tr>
					</table>
				<p class="submit"><input type="submit" value="Upload Logo &raquo;" name="update_logo"/></p>	
		</form> 
	</div>	  
    <?php
  }  
?>