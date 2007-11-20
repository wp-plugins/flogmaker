<?php
// Connexion à la BDD
//require(  '/wp-config.php' );

if ( defined('ABSPATH') )
	require_once( ABSPATH . 'wp-config.php');
else
    require_once('../../wp-config.php');	
	

function replace_balise($content){
	$new_content =str_replace("<strong>","</span><span class='bold'>",$content);
	$new_content =str_replace("</strong>","</span><span class='classic'>",$new_content);
	$new_content =str_replace("<a href","</span><span class='lien'><a href",$new_content);
	$new_content =str_replace("</a>","</a></span><span class='classic'>",$new_content);
	$new_content =str_replace("<h1>","</span><span class='h1_titre'>",$new_content);
	$new_content =str_replace("</h1>","</span><span class='classic'>",$new_content);
	$new_content =str_replace("<h2>","</span><span class='h2_titre'>",$new_content);
	$new_content =str_replace("</h2>","</span><span class='classic'>",$new_content);
	$new_content =str_replace("<h3>","</span><span class='h3_titre'>",$new_content);
	$new_content =str_replace("</h3>","</span><span class='classic'>",$new_content);
	$new_content =str_replace("<li>","</span><span class='liste'>",$new_content);
	$new_content =str_replace("</li>","</span><span class='classic'>",$new_content);
	$new_content =str_replace("// <![CDATA[","CDATA",$new_content);
	$new_content =str_replace("// ]]>","FIN CDATA",$new_content);
	
	return $new_content;
}
?><root>
<?php
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<contenu>
<categorie>
	<nom>Derniers Posts</nom>
	<id_categorie>0</id_categorie>
		<articles><?php
			$resultContenu=$wpdb->get_results("SELECT t1.ID, post_title, t1.post_name AS post_name, DATE_FORMAT(post_date,'%d-%m-%Y') AS post_date,post_date as post_date_full, t2.user_nicename AS pseudo, t2.user_email AS user_email, t2.user_url AS user_url, post_content FROM {$table_prefix}posts AS t1 JOIN {$table_prefix}users AS t2 ON t1.post_author = t2.ID WHERE t1.post_status='publish' AND t1.post_type='post' ORDER BY  post_date DESC LIMIT 0 ,5");
			if(!$resultContenu){
			}else{
				foreach($resultContenu as $e){
				
				?>
					<article>
						<?php
						$reqCatSpecial="SELECT name FROM {$table_prefix}terms where term_ID IN (select term_taxonomy_id FROM {$table_prefix}term_relationships WHERE object_id =".$e->ID.") LIMIT 0,1";
						$resultCatSpecial=$wpdb->get_results($reqCatSpecial);	
						if(!$resultCatSpecial){
						}else{
							foreach($resultCatSpecial as $b){	
								$catName = $b->name;
							}
						}
						?>							
						<titre><?php echo $e->post_title?></titre>
						<url_demo><?php echo $e->post_title?></url_demo>
						<texte><![CDATA[<?php echo "<span class='classic'>".replace_balise($e->post_content)."</span>"?>]]></texte>
						<commentaires>			
							<?php
							$resultCommentaire=$wpdb->get_results("SELECT comment_ID,comment_author,comment_author_email, comment_author_url,DATE_FORMAT(comment_date,'%d-%m-%Y') AS comment_date, comment_content FROM {$table_prefix}comments WHERE comment_post_ID=".$e->ID." AND comment_approved='1'");
							if(!$resultCommentaire){
							}else{
								foreach($resultCommentaire as $f){?>				
									<commentaire>
										<auteur><?php echo $f->comment_author?></auteur>
										<date><?php echo $f->comment_date?></date>
										<texte><![CDATA[<?php echo $f->comment_content?>]]></texte>
										<auteur_url><?php echo $f->comment_author_url?></auteur_url>
										<auteur_email><?php echo $f->comment_author_email?></auteur_email>
										<comment_id><?php echo $f->comment_ID?></comment_id>
									</commentaire>
								<?php
								}
							}
							?>						
						</commentaires>
						<auteur><?php echo $e->pseudo?></auteur>
						<id_post><?php echo $e->ID?></id_post>
						<date_post><?php echo $e->post_date?></date_post>
						<user_email><?php echo $e->user_email?></user_email>
						<user_url><?php echo $e->user_url?></user_url>
						<postname><?php echo $e->post_name?></postname>
						<?php
						$resultSource=$wpdb->get_results("SELECT guid FROM {$table_prefix}posts WHERE post_status='attachment' AND post_name='".$e->post_name."'");
						if(!$resultSource){
						}else{
							foreach($resultSource as $d){?>						
								<source><?php echo $d->ID?></source>
							<?php
							}
						}?>
					</article>
				<?php
				}
			}?>		
		</articles>
	</categorie>
<?php
$reqCategorie="SELECT term_id, name FROM {$table_prefix}terms WHERE term_id IN (SELECT term_id FROM wp_term_taxonomy WHERE taxonomy='category')";
$resultCategorie=$wpdb->get_results($reqCategorie);
if(!$resultCategorie){
}else{
	foreach($resultCategorie as $c){?>
		<categorie>
			<nom><?php echo $c->name ?></nom>
			<id_categorie><?php echo $c->term_id ?></id_categorie>	
			<articles>
				<?php
				$resultContenu=$wpdb->get_results("SELECT t1.ID,post_title,t1.post_name as post_name,DATE_FORMAT(post_date,'%d-%m-%Y') AS post_date,post_date as post_date_full,t2.user_nicename as pseudo,t2.user_email as user_email, t2.user_url as user_url,post_content FROM {$table_prefix}posts as t1 JOIN {$table_prefix}users as t2 on t1.post_author=t2.ID WHERE t1.ID IN (SELECT object_id FROM {$table_prefix}term_relationships WHERE t1.post_status='publish' AND t1.post_type='post' AND term_taxonomy_id=".$c->term_id.") order by post_date_full desc");
				if(!$resultContenu){
				}else{
					foreach($resultContenu as $e){?>
						<article>
							<titre><?php echo $e->post_title ?></titre>
							<url_demo><?php echo $e->post_title ?></url_demo>
							<texte><![CDATA[<?php echo "<span class='classic'>".replace_balise($e->post_content)."</span>"?>]]></texte>
							<commentaires>
							<?php
							$resultCommentaire=$wpdb->get_results("SELECT comment_ID,comment_author,comment_author_email, comment_author_url,DATE_FORMAT(comment_date,'%d-%m-%Y') AS comment_date,comment_content FROM {$table_prefix}comments WHERE comment_approved='1' AND comment_post_ID=".$e->ID);
							if(!$resultCommentaire){
							}else{
								foreach($resultCommentaire as $f){?>				
									<commentaire>
										<auteur><?php echo $f->comment_author?></auteur>
										<date><?php echo $f->comment_date?></date>
										<texte><![CDATA[<?php echo $f->comment_content?>]]></texte>
										<auteur_url><?php echo $f->comment_author_url?></auteur_url>
										<auteur_email><?php echo $f->comment_author_email?></auteur_email>
										<comment_id><?php echo $f->comment_ID?></comment_id>
									</commentaire>
								<?php
								}?>	
							<?php
							}?>
							</commentaires>
							<auteur><?php echo $e->pseudo?></auteur>
							<id_post><?php echo $e->ID?></id_post>
							<date_post><?php echo $e->post_date?></date_post>
							<user_email><?php echo $e->user_email?></user_email>
							<user_url><?php echo $e->user_url?></user_url>
							<postname><?php echo $e->post_name?></postname>
							<?php
							$resultSource=$wpdb->get_results("SELECT guid FROM {$table_prefix}posts WHERE post_status='attachment' AND post_name='".$e->post_name."'");
							if(!$resultSource){
							}else{
								foreach($resultSource as $d){?>						
									<source><?php echo $d->ID?></source>
								<?php
								}?>
							<?php
							}?>
						</article>
					<?php
					}?>
				<?php
				}?>						
			</articles>			
		</categorie>
	<?php
	}?>	
<?php
}?>
</contenu>
