<?php
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }
function flog_maker_admin_couleurs()  {  
    global $wpdb, $table_prefix;
    
    if(isset($_POST['add_couleur_submit'])){
	  //check_admin_referer('add-flog');      
	   
		$_POST['new_couleur'] = strtolower($_POST['new_couleur']);
		$wpdb->query("INSERT INTO {$table_prefix}flashblog_couleurs (couleur) VALUES ('".$couleur."')");		
        if(trim($_POST['new_couleur']) == '' || preg_match("/[^a-f0-9%_-]/", $_POST['new_couleur'])){
          echo '<div id="message" class="updated fade-ff0000"><p><strong>'.__('La couleur n\'a pas &eacute;t&eacute; cr&eacute;&eacute;e', 'flog-maker').'</strong> &ndash; '.__('Votre couleur est incorrecte.  Utilisez la notation hexad&eacute;cimale', 'flog-maker').'</p></div>';
         
        }    
	  
    } elseif(isset($_POST['edit_couleur_submit'])){	
		check_admin_referer('alter-flog');      
		if(trim($_GET['idcouleur']) != ''){
			$update_result = $wpdb->query("UPDATE {$table_prefix}flashblog_couleurs SET couleur = '".$wpdb->escape(stripslashes($_POST['new_couleur']))."' WHERE id = '".$wpdb->escape(stripslashes($_GET['idcouleur']))."'");
			if($update_result){
				echo '<div id="message" class="updated fade-ffff00"><p>'.__('La couleur a &eacute;t&eacute; mise &agrave; jour', 'flog-maker').'</p></div>';
			}else{
				echo '<div id="message" class="updated fade-ff0000"><p>'.__('La couleur n\'a pas &eacute;t&eacute; mise &agrave; jour.', 'flog-maker').'</p></div>';
			}
		}
	} elseif(isset($_POST['delete_couleur_submit'])){
	  check_admin_referer('alter-flog');
      
      if(trim($_GET['idcouleur']) != ''){       
        
          $flog_from_exists = $wpdb->get_var("SELECT id FROM {$table_prefix}flashblog_couleurs WHERE id = '".$wpdb->escape(stripslashes($_GET['idcouleur']))."'");
          if(!$flog_from_exists)  { 
			echo  '<div id="message" class="updated fade-ff0000"><p>'.__('Error deleting', 'flog-maker').' &ndash; '.__('&quot;delete from&quot; location doesn\'t exist.', 'flog-maker').'</p></div>'; 
		  }else{
            $wpdb->query("DELETE FROM {$table_prefix}flashblog_couleurs WHERE id = '{$flog_from_exists}'");
             echo '<div id="message" class="updated fade-ffff00"><p>'.__('La couleur a correctement &eacute;t&eacute; supprim&eacute;e', 'flog-maker').'</p></div>';
          }
        
      }	
	}elseif(isset($_GET['idcouleur'])){	
	  $flog_info = $wpdb->get_row("SELECT * FROM {$table_prefix}flashblog_couleurs WHERE id = '".$wpdb->escape(stripslashes($_GET['idcouleur']))."' LIMIT 1");
     
	
		
	  if(!$flog_info){
        echo '<div id="message" class="updated fade-ff0000"><p>'.sprintf(__("Pas de couleurs avec cet id !", "flog-maker"), $_GET['idcouleur']).'</p></div>';
      }else{        
        ?>        
        <div class="wrap">
          <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">            
            <?php if(function_exists('wp_nonce_field')) { wp_nonce_field('alter-flog'); }?>            
            <h2><?php printf(__('Editer la couleur', 'flog-maker'), $_GET['idcouleur']) ?></h2>
            
            <table width="100%" cellspacing="2" cellpadding="5" class="editform"> 
              <tr valign="top">
                <th width="33%" scope="row">
                 
                </th>
                <td>
                  <b> <label for="new_couleur"><?php _e('Couleur', 'flog-maker') ?></label></b>
				 </td>
              </tr>  
			  <tr valign="top">
                <th width="33%" scope="row">                
                </th>
                <td>
                 <div style="float:left;"><b>#</B></div><input type="text" id="new_couleur" name="new_couleur" size="30" value="<?php echo format_to_edit($flog_info->couleur) ?>" style="float:left;" /><div style="background-color:#<?php echo $flog_info->couleur ?>;width:20px;height:25px;float:left;"></div>
                </td>
              </tr> 
              <tr valign="top">
                <th width="33%" scope="row">
                  &nbsp;
                </th>
                <td>
                  <p class="submit" style="text-align: left">
                    <input type="submit" name="edit_couleur_submit" value="<?php _e('Confirmer les changements', 'flog-maker') ?> &raquo;" />
                  </p>
                </td>
              </tr>
            </table>
            
            <h2><?php _e('Supprimer une couleur', 'flog-maker') ?></h2>
            
            <table width="100%" cellspacing="2" cellpadding="5" class="editform">               
              <tr valign="top" >
                <td width="33%" align="right">
				
                </td>
                <td>
                  <p class="submit" style="text-align: left">
                   <input type="submit" name="delete_couleur_submit" value="<?php _e('Supprimer la couleur', 'flog-maker') ?> &raquo;" style="background: #c33; color: white" onclick="return confirm('<?php _e('etes vous sur de vouloir supprimer cette couleur.\n&quot;Cancel&quot; pour annuler, &quot;OK&quot; pour confirmer.', 'flog-maker') ?>')" />
                  </p>
                </td>
              </tr>
            </table>            
          </form>
        </div>        
        <?php        
        return true;
      }      
    }    
      
		if(isset($_GET['bg'])){	
			echo '<div id="message" class="updated fade-ffff00"><p>'.__('Le motif de fond a &eacute;t&eacute; mis &agrave; jour', 'flog-maker').'</p></div>';
	  } ?>   
      <div class="wrap">		<h2><?php _e('Le background', 'flog-maker') ?></h2>
		<p><?php _e('Cr&eacute;ez votre motif de fond ! Il sera automatiquement enregistr&eacute; dans le r&eacute;pertoire wp-flashblog/img<br \>Vous pouvez aussi le t&eacute;l&eacute;charger directement, mais ce n\'est pas obligatoire.', 'flog-maker') ?></p>
		<div class="flash_bgmaker">
			<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="100%" height="500" id="dobg_adminwp" align="middle">
			<param name="allowScriptAccess" value="always" />
			<param name="movie" value="../wp-flashblog/dobg_adminwp.swf" /><param name="quality" value="high" />
			<param name="bgcolor" value="#ffffff" />
			<embed src="../wp-flashblog/dobg_adminwp.swf" quality="high" bgcolor="#ffffff" width="100%" height="500" name="dobg_adminwp" align="middle" allowScriptAccess="always" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
			</object>
		</div>		
		<br \><br \>
        <h2><?php _e('Les couleurs', 'flog-maker') ?></h2>
		<p><?php _e('Chaque couleur est attribu&eacute;e &agrave; une cat&eacute;gorie dans le blog flash. Si une cat&eacute;gorie n\'a pas de couleur associ&eacute;e, elle sera par d&eacute;faut en noir.', 'flog-maker') ?></p>
        <table id="the-list-x" width="100%" cellpadding="3" cellspacing="3" style="white-space: nowrap"> 
          <tr style="white-space: normal">
            <th scope="col"><?php _e('ID', 'flog-maker') ?></th>
            <th scope="col"  align="left"><?php _e('Cat&eacute;gorie', 'flog-maker') ?></th>
			<th scope="col"><?php _e('Couleur', 'flog-maker') ?></th>     
			<th scope="col"><?php _e('Code Couleur', 'flog-maker') ?></th>                   
            <th scope="col"></th>
          </tr>
          <?php 
            $couleurs = $wpdb->get_results("SELECT * FROM {$table_prefix}flashblog_couleurs ORDER BY id");   
			$reqCategorie=$wpdb->get_results("SELECT cat_ID, cat_name FROM {$table_prefix}categories ORDER BY cat_name");	
			$cat_nom[0] = "Derniers posts";
			
			
            if(!$couleurs){
				echo '<tr class="alternate"><th colspan="9">'.__('Vous n\'avez pas encore cr&eacute;&eacute; de couleurs!', 'flog-maker').'</th></tr>';
            }else{	
				$i=1;			
				foreach ($reqCategorie as $cat_row) {			
					$cat_nom[$i]  = $cat_row->cat_name;
					$i=$i+1;
				}
				$i=0;				
				foreach($couleurs as $f){
					$class = ($class != '') ? '' : 'alternate';   						
					echo '<tr class="'.$class.'">';
					echo '<th scope="row">'.$f->id.'</td>';
					if ($cat_nom[$i]!=""){
						echo '<td>'.$cat_nom[$i].'</td>';
					}else{
						echo '<td align="center">Pas de cat&eacute;gorie associ&eacute;e</td>';
					}
					
					echo '<td style="width: 40px; white-space: normal" align="center"><div style="background-color:#'.$f->couleur.';width:20px;height:20px;"></div></td>';
					echo '<td style="width: 200px; white-space: normal" align="center">#'.$f->couleur.'</td>';	
					echo '<td><a href="?page=flog-maker-couleurs&idcouleur='.$f->id.'" class="edit" rel="permalink">'.__('Edit', 'flog-maker').'</a></td>';
					echo '</tr>';	
					$i=$i+1;					
				}				
			}
		  ?>
		</table>        
      </div>      
      <div class="wrap">        
        <h2><?php _e('Ajouter une couleur', 'flog-maker') ?></h2>        
        <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
          <table width="100%" cellspacing="2" cellpadding="5" class="editform"> 
            <tr valign="top"> 
              <th width="33%" scope="row">                
              </th>
              <td>
               <b><label for="new_couleur"> <?php _e('Nouvelle Couleur', 'flog-maker') ?></label></b>
              </td>
            </tr>  
			<tr valign="top"> 
              <th width="33%" scope="row">
                
              </th>
              <td>
                #<input type="text" name="new_couleur" id="new_couleur" size="30" />
              </td>
            </tr>			
          </table>          
          <?php if (function_exists('wp_nonce_field')) { wp_nonce_field('add-flog-maker'); } ?>          
          <p class="submit">
            <input type="submit" name="add_couleur_submit" value="<?php _e('Cr&eacute;er la couleur', 'flog-maker') ?> &raquo;" />
          </p>          
        </form>        
      </div>      
    <?php    
  }
?>
