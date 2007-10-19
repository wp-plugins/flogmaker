<?php
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }
function flog_maker_admin_onglets()  {  
    global $wpdb, $table_prefix;
    
    if(isset($_POST['add_onglet_submit'])){
	  //check_admin_referer('add-flog');      
	   
		$_POST['new_onglet'] = strtolower($_POST['new_onglet']);
		
		$nom_onglet = strtolower($_POST['nom_onglet']);
		$nomurl_onglet = strtolower($_POST['nomurl_onglet']);
		$fichier_onglet = strtolower($_POST['fichier_onglet']);
		$picto_onglet = strtolower($_POST['picto_onglet']);
		
		$wpdb->query("INSERT INTO {$table_prefix}flashblog_onglets (nom,nom_url,fichier,actif,picto) VALUES ('".$nom_onglet."','".$nomurl_onglet."','".$fichier_onglet."',".(isset($_POST['actif_onglet']) ? 1 : 0).",'".$picto_onglet."')");		
        if(trim($_POST['nom_onglet']) == '' || preg_match("/[^a-f0-9%_-]/", $_POST['nm_onglet'])){
          echo '<div id="message" class="updated fade-ff0000"><p><strong>'.__('L\'onglet n\'a pas &eacute;t&eacute; cr&eacute;&eacute;e', 'flog-maker').'</strong> &ndash; '.__('Votre onglet est incorrecte.  Utilisez la notation hexad&eacute;cimale', 'flog-maker').'</p></div>';
         
        }    
	  
    } elseif(isset($_POST['edit_onglet_submit'])){	
		check_admin_referer('alter-flog');      
		if(trim($_GET['idonglet']) != ''){
			$update_result = $wpdb->query("UPDATE {$table_prefix}flashblog_onglets SET nom = '".$wpdb->escape(stripslashes($_POST['nom_onglet']))."',nom_url='".$wpdb->escape(stripslashes($_POST['nomurl_onglet']))."',fichier='".$wpdb->escape(stripslashes($_POST['fichier_onglet']))."',actif = ".(isset($_POST['actif_onglet']) ? 1 : 0).",picto='".$wpdb->escape(stripslashes($_POST['picto_onglet']))."' WHERE id = '".$wpdb->escape(stripslashes($_GET['idonglet']))."'");
			if($update_result){
				echo '<div id="message" class="updated fade-ffff00"><p>'.__('L\'onglet a &eacute;t&eacute; mis &agrave; jour', 'flog-maker').'</p></div>';
			}else{
				echo '<div id="message" class="updated fade-ff0000"><p>'.__('L\'onglet n\'a pas &eacute;t&eacute; mis &agrave; jour.', 'flog-maker').'</p></div>';
			}
		}
	} elseif(isset($_POST['delete_onglet_submit'])){
	  check_admin_referer('alter-flog');      
      if(trim($_GET['idonglet']) != ''){        
          $flog_from_exists = $wpdb->get_var("SELECT id FROM {$table_prefix}flashblog_onglets WHERE id = '".$wpdb->escape(stripslashes($_GET['idonglet']))."'");
          if(!$flog_from_exists)  { 
			echo  '<div id="message" class="updated fade-ff0000"><p>'.__('Error deleting', 'flog-maker').' &ndash; '.__('&quot;delete from&quot; location doesn\'t exist.', 'flog-maker').'</p></div>'; 
		  }else{
            $wpdb->query("DELETE FROM {$table_prefix}flashblog_onglets WHERE id = '{$flog_from_exists}'");
             echo '<div id="message" class="updated fade-ffff00"><p>'.__('La onglet a correctement &eacute;t&eacute; supprim&eacute;e', 'flog-maker').'</p></div>';
          }        
      }	
	}elseif(isset($_GET['idonglet'])){	
	  $onglet_info = $wpdb->get_row("SELECT * FROM {$table_prefix}flashblog_onglets WHERE id = '".$wpdb->escape(stripslashes($_GET['idonglet']))."' LIMIT 1");
      if(!$onglet_info){
        echo '<div id="message" class="updated fade-ff0000"><p>'.sprintf(__("Pas d\'onglets avec cet id !", "flog-maker"), $_GET['idonglet']).'</p></div>';
      }else{ ?>        
        <div class="wrap">
          <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">            
            <?php if(function_exists('wp_nonce_field')) { wp_nonce_field('alter-flog'); }?>            
            <h2><?php printf(__('Editer l\'onglet', 'flog-maker'), $_GET['idonglet']) ?></h2>            
            <table width="100%" cellspacing="2" cellpadding="5" class="editform"> 
              <tr valign="top">
                <th width="33%" scope="row">
                  <label for="nom_onglet"><?php _e('Nom de l\'onglet', 'flog-maker') ?></label>
                </th>
                <td>
                  <input type="text" id="nom_onglet" name="nom_onglet" size="30" value="<?php echo format_to_edit($onglet_info->nom) ?>" />
                </td>
              </tr> 
			  <tr valign="top"> 
	              <th width="33%" scope="row">
	                <label for="nomurl_onglet"><?php _e('Nom simple pour l\'url', 'flog-maker') ?></label>
	              </th>
	              <td>
	                
	              </td>
	            </tr>
				<tr valign="top"> 
				  <td align="right">              
					 <i><?php _e('http://'.$_SERVER['SERVER_NAME'].'/wp-flashblog/#', 'flog-maker') ?></i> 
				  </td>
				  <td>
					  <input type="text" name="nomurl_onglet" id="nomurl_onglet" size="30" value="<?php echo format_to_edit($onglet_info->nom_url) ?>"/>
				  </td>
				</tr>
				<tr valign="top"> 
	              <th width="33%" scope="row">
	                <label for="fichier_onglet"><?php _e('Fichier associ&eacute;', 'flog-maker') ?></label>					
	              </th>
	              <td>
	                <input type="text" name="fichier_onglet" id="fichier_onglet" size="30" value="<?php echo format_to_edit($onglet_info->fichier) ?>"/>
	              </td>		  
	            </tr> 
				<tr valign="top">
					<td></td>
					<td>
						<i><?php _e('Le fichier est &agrave; placer dans le r&eacute;pertoire /wp-flashblog/onglets<br /> Largeur conseill&eacute;e : 220px<br />Hauteur conseill&eacute;e : 240px<br />Utilisez le ciblage relatif pour le bon fonctionnement de l\'onglet.', 'flog-maker') ?>
						</i><br /><br />
					</td>		  
	            </tr> 					
				<tr valign="top"> 
	              <th width="33%" scope="row">
	                <label for="picto_onglet"><?php _e('Picto associ&eacute;', 'flog-maker') ?></label>
				
					
	              </th>
	              <td>
	                <input type="text" name="picto_onglet" id="picto_onglet" size="30" value="<?php echo format_to_edit($onglet_info->picto) ?>"/>
	              </td>		  
	            </tr> 
				<tr valign="top">
				<td align="right">
				<br \>
					<script type="text/javascript" src="<?echo ABSPATH;?>wp-flashblog/js/swfobject.js"></script>
					<div id="flashcontent">
						<strong>Merci de mettre a jour vos versions de flash </strong>		
					</div>
					<script type="text/javascript">
						// <![CDATA[						
						var so = new SWFObject("<?echo ABSPATH;?>wp-flashblog/pictos/<?php echo $onglet_info->picto?>", "<?php echo $onglet_info->picto?>", "35", "35", "8", "#000000");
						so.addParam("scale", "noscale");
						so.addParam("allowdomain", "always");						
						so.useExpressInstall('<?echo ABSPATH;?>wp-flashblog/expressinstall.swf');
						so.write("flashcontent");
						// ]]>
					</script>
						</td>
					<td><i><?php _e('Le fichier est &agrave; placer dans le r&eacute;pertoire /wp-flashblog/pictos<br /> Largeur conseill&eacute;e : 35px	<br /> Hauteur conseill&eacute;e : 35px<br />L\'origine x y est au centre du picto.', 'flog-maker') ?></i><br /><br />
					</td>		  
				</tr>
				<tr valign="top">
                <th width="33%" scope="row">
                  <label for="actif_onglet"><?php _e('Actif ?', 'rs-discuss') ?></label>
                </th>
                <td>
                  <input type="checkbox" name="actif_onglet" id="actif_onglet" value="1" <?if($onglet_info->actif==1){echo "checked";}?>/>
                </td>
              </tr>			  
              <tr valign="top">
                <th width="33%" scope="row">
                  &nbsp;
                </th>
                <td>
                  <p class="submit" style="text-align: left">
                    <input type="submit" name="edit_onglet_submit" value="<?php _e('Confirmer les changements', 'flog-maker') ?> &raquo;" />
                  </p>
                </td>
              </tr>
            </table>            
            <h2><?php _e('Supprimer un onglet', 'flog-maker') ?></h2>
            
            <table width="100%" cellspacing="2" cellpadding="5" class="editform">               
              <tr valign="top" >
                <td width="33%" align="right">
				
                </td>
                <td>
                  <p class="submit" style="text-align: left">
                    <input type="submit" name="delete_onglet_submit" value="<?php _e('Supprimer l\'onglet', 'flog-maker') ?> &raquo;" style="background: #c33; color: white" onclick="return confirm('<?php _e('etes vous sur de vouloir supprimer cet onglet.\n&quot;Cancel&quot; pour annuler, &quot;OK&quot; pour confirmer.', 'flog-maker') ?>')" />
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
    ?>      
      <div class="wrap">
        <h2><?php _e('Les onglets', 'flog-maker') ?></h2>
        <p><?php _e('Les onglets sont les icones accessibles &agrave; gauche de l\'interface.<br \>Vous pouvez en ajouter autant que vous le souhaitez dans la limite de la zone d\'affichage.<br \>La cr&eacute;ation des onglets n&eacute;c&eacute;ssite un minimum de connaissances en flash.<br \>Les onglets "recherche", "contact" et "archives" propos&eacute;s par d&eacute;faut sont greff&eacute;s  automatiquement &agrave; votre base.<br \>L\'onglet "liens" est bas&eacute; sur l\'outil liens par d&eacute;faut de Wordpress. il r&eacute;cup&egrave;re les cat&eacute;gories et les liens qui sont actifs.', 'flog-maker') ?></p>
        <table id="the-list-x" width="100%" cellpadding="3" cellspacing="3" style="white-space: nowrap"> 
          <tr style="white-space: normal">
            <th scope="col" align="left"><?php _e('ID', 'flog-maker') ?></th>
            <th scope="col" align="left"><?php _e('Nom', 'flog-maker') ?></th>	
            <th scope="col" align="left"><?php _e('Nom url', 'flog-maker') ?></th>	
            <th scope="col" align="left"><?php _e('Actif', 'flog-maker') ?></th>
            <th scope="col" align="left"><?php _e('Nom du fichier', 'flog-maker') ?></th>
            <th scope="col" align="right"></th>
            <th scope="col" align="left"><?php _e('Picto', 'flog-maker') ?></th>
            <th scope="col"></th>
          </tr>
          <?php 
            $onglets = $wpdb->get_results("SELECT * FROM {$table_prefix}flashblog_onglets ORDER BY id");   
			
			
            if(!$onglets){
				echo '<tr class="alternate"><th colspan="9">'.__('Vous n\'avez pas encore cr&eacute;&eacute; de onglets!', 'flog-maker').'</th></tr>';
            }else{									
				foreach($onglets as $f){
					$class = ($class != '') ? '' : 'alternate';   						
					echo '<tr class="'.$class.'">';
					echo '<td style="width: 20px; white-space: normal">'.$f->id.'</td>';
					echo '<td style="width: 100px; white-space: normal">'.$f->nom.'</td>';
					echo '<td style="width: 120px; white-space: normal">'.$f->nom_url.'</td>';
					if ($f->actif==1){
						echo '<td>Oui</td>';
					}else{
						echo '<td>Non</td>';
					}					
					echo '<td >'.$f->fichier .'</td>';
					echo '<td style="width: 40px; white-space: normal">';	?>
						<script type="text/javascript" src="../wp-flashblog/js/swfobject.js"></script>
						<div id="flashcontent">
							<strong>Merci de mettre a jour vos versions de flash </strong>		
						</div>
						<script type="text/javascript">
							// <![CDATA[						
							var so = new SWFObject("../wp-flashblog/pictos/<?php echo $f->picto?>", "<?php echo $f->picto?>", "35", "35", "8", "#000000");
							so.addParam("scale", "noscale");
							so.addParam("allowdomain", "always");						
							so.useExpressInstall('../wp-flashblog/expressinstall.swf');
							so.write("flashcontent");
							// ]]>
						</script></div>						
					</td>	
					<td style="width: 150px; white-space: normal">&nbsp;&nbsp;<? echo $f->picto?>
					</td>								
					<?
					echo '<td><a href="?page=flog-maker-onglets&idonglet='.$f->id.'" class="edit" rel="permalink">'.__('Edit', 'flog-maker').'</a></td>';
					echo '</tr>';	

				}				
			}
		  ?>
		</table>        
      </div>      
      <div class="wrap">        
        <h2><?php _e('Ajouter un onglet', 'flog-maker') ?></h2>        
        <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
          <table width="100%" cellspacing="2" cellpadding="5" class="editform"> 
            <tr valign="top"> 
              <th width="33%" scope="row">
                <label for="nom_onglet"><?php _e('Nom de l\'onglet', 'flog-maker') ?></label>
              </th>
              <td>
                <input type="text" name="nom_onglet" id="nom_onglet" size="30" />
              </td>
            </tr>
			<tr valign="top"> 
              <th width="33%" scope="row">
                <label for="nomurl_onglet"><?php _e('Nom simple pour l\'url', 'flog-maker') ?></label>
              </th>
              <td>
              </td>
            </tr>
			<tr valign="top"> 
              <td align="right">              
				 <i><?php _e('http://'.$_SERVER['SERVER_NAME'].'/wp-flashblog/#', 'flog-maker') ?></i> 
              </td>
              <td>
			      <input type="text" name="nomurl_onglet" id="nomurl_onglet" size="30" />
              </td>
            </tr>
			<tr valign="top"> 
               <th width="33%" scope="row">
	                <label for="fichier_onglet"><?php _e('Fichier associ&eacute;', 'flog-maker') ?></label>
	              </th>
              <td>
                <input type="text" name="fichier_onglet" id="fichier_onglet" size="30" />
              </td>		  
            </tr> 
			<tr valign="top">
				<td></td>
					<td>
					<i><?php _e('Le fichier est &agrave; placer dans le r&eacute;pertoire /wp-flashblog/onglets<br /> Largeur conseill&eacute;e : 220px<br />Hauteur conseill&eacute;e : 240px<br />Utilisez le ciblage relatif pour le bon fonctionnement de l\'onglet.', 'flog-maker') ?>
					</i>
					<br /><br />
				</td>		  
			</tr> 			
			<tr valign="top"> 
			  <th width="33%" scope="row">
				<label for="picto_onglet"><?php _e('Picto associ&eacute;', 'flog-maker') ?></label>

			  </th>
			  <td>
				<input type="text" name="picto_onglet" id="picto_onglet" size="30"/>
			  </td>		  
	        </tr> 
			<tr valign="top">
				<td></td>
					<td><i><?php _e('Le fichier est &agrave; placer dans le r&eacute;pertoire /wp-flashblog/pictos<br /> Largeur conseill&eacute;e : 35px	<br /> Hauteur conseill&eacute;e : 35px<br />L\'origine x y est au centre du picto.', 'flog-maker') ?></i><br /><br />
				</td>		  
			</tr>
			<tr valign="top">
                <th width="33%" scope="row">
                  <label for="actif_onglet"><?php _e('Actif ?', 'rs-discuss') ?></label>
                </th>
                <td>
                  <input type="checkbox" name="actif_onglet" id="actif_onglet" value="1" />
                </td>
              </tr>
          </table>          
          <?php if (function_exists('wp_nonce_field')) { wp_nonce_field('add-flog-maker'); } ?>          
          <p class="submit">
            <input type="submit" name="add_onglet_submit" value="<?php _e('Cr&eacute;er l\'onglet', 'flog-maker') ?> &raquo;" />
          </p>          
        </form>        
      </div>      
    <?php    
  }
?>
