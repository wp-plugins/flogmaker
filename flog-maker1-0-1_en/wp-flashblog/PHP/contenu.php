<?
require_once('config.php');	

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
	
	return $new_content;
}
?><root>
<?
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<contenu>
<categorie>
	<nom>Derniers Posts</nom>
	<id_categorie>0</id_categorie>
		<articles><?
			$resultContenu=$wpdb->get_results("SELECT t1.ID, post_title, t1.post_name AS post_name, DATE_FORMAT(post_date,'%d-%m-%Y') AS post_date,post_date as post_date_full, t2.user_nicename AS pseudo, t2.user_email AS user_email, t2.user_url AS user_url, post_content FROM {$table_prefix}posts AS t1 JOIN {$table_prefixUser}users AS t2 ON t1.post_author = t2.ID WHERE t1.post_status='publish' AND t1.post_type='post' ORDER BY post_date_full DESC LIMIT 0 ,5");
			if(!$resultContenu){
			}else{
				foreach($resultContenu as $e){?>
					<article>
						<?
						$reqCatSpecial="SELECT cat_name FROM {$table_prefix}categories where cat_ID IN (select category_id FROM {$table_prefix}post2cat WHERE  post_id =".$e->ID.") LIMIT 0 , 1";
						$resultCatSpecial=$wpdb->get_results($reqCatSpecial);	
						if(!$resultCatSpecial){
						}else{
							foreach($resultCatSpecial as $b){	
								$catName = $b->cat_name;
							}
						}?>							
						<titre><?=$e->post_title?></titre>
						<url_demo><?=$e->post_title?></url_demo>
						<texte><![CDATA[<?="<span class='classic'>".replace_balise($e->post_content)."</span>"?>]]></texte>
						<commentaires>			
							<?
							$resultCommentaire=$wpdb->get_results("SELECT comment_ID,comment_author,comment_author_email, comment_author_url,DATE_FORMAT(comment_date,'%d-%m-%Y') AS comment_date, comment_content FROM {$table_prefix}comments WHERE comment_post_ID=".$e->ID." AND comment_approved='1'");
							if(!$resultCommentaire){
							}else{
								foreach($resultCommentaire as $f){?>				
									<commentaire>
										<auteur><?=$f->comment_author?></auteur>
										<date><?=$f->comment_date?></date>
										<texte><![CDATA[<?=$f->comment_content?>]]></texte>
										<auteur_url><?=$f->comment_author_url?></auteur_url>
										<auteur_email><?=$f->comment_author_email?></auteur_email>
										<comment_id><?=$f->comment_ID?></comment_id>
									</commentaire>
								<?}
							}
							?>						
						</commentaires>
						<auteur><?=$e->pseudo?></auteur>
						<id_post><?=$e->ID?></id_post>
						<date_post><?=$e->post_date?></date_post>
						<user_email><?=$e->user_email?></user_email>
						<user_url><?=$e->user_url?></user_url>
						<postname><?=$e->post_name?></postname>
						<?
						$resultSource=$wpdb->get_results("SELECT guid FROM {$table_prefix}posts WHERE post_status='attachment' AND post_name='".$e->post_name."'");
						if(!$resultSource){
						}else{
							foreach($resultSource as $d){?>						
								<source><?=$d->ID?></source>
							<?}
						}?>
					</article>
				<?}
			}?>		
		</articles>
	</categorie>
<?
$reqCategorie="SELECT cat_ID, cat_name FROM {$table_prefix}categories";
$resultCategorie=$wpdb->get_results($reqCategorie);
if(!$resultCategorie){
}else{
	foreach($resultCategorie as $c){?>
		<categorie>
			<nom><?=$c->cat_name?></nom>
			<id_categorie><?=$c->cat_ID?></id_categorie>	
			<articles>
				<?
				$resultContenu=$wpdb->get_results("SELECT t1.ID,post_title,t1.post_name as post_name,DATE_FORMAT(post_date,'%d-%m-%Y') AS post_date,post_date as post_date_full,t2.user_nicename as pseudo,t2.user_email as user_email, t2.user_url as user_url,post_content FROM {$table_prefix}posts as t1 JOIN {$table_prefixUser}users as t2 on t1.post_author=t2.ID WHERE t1.ID IN (SELECT post_id FROM {$table_prefix}post2cat WHERE t1. post_status='publish' AND t1.post_type='post' AND category_id=".$c->cat_ID.") order by post_date_full desc");
				if(!$resultContenu){
				}else{
					foreach($resultContenu as $e){?>
						<article>
							<titre><?=$e->post_title?></titre>
							<url_demo><?=$e->post_title?></url_demo>
							<texte><![CDATA[<?="<span class='classic'>".replace_balise($e->post_content)."</span>"?>]]></texte>
							<commentaires>
							<?
							$resultCommentaire=$wpdb->get_results("SELECT comment_ID,comment_author,comment_author_email, comment_author_url,DATE_FORMAT(comment_date,'%d-%m-%Y') AS comment_date,comment_content FROM {$table_prefix}comments WHERE comment_approved='1' AND comment_post_ID=".$e->ID);
							if(!$resultCommentaire){
							}else{
								foreach($resultCommentaire as $f){?>				
									<commentaire>
										<auteur><?=$f->comment_author?></auteur>
										<date><?=$f->comment_date?></date>
										<texte><![CDATA[<?=$f->comment_content?>]]></texte>
										<auteur_url><?=$f->comment_author_url?></auteur_url>
										<auteur_email><?=$f->comment_author_email?></auteur_email>
										<comment_id><?=$f->comment_ID?></comment_id>
									</commentaire>
								<?}?>	
							<?}?>
							</commentaires>
							<auteur><?=$e->pseudo?></auteur>
							<id_post><?=$e->ID?></id_post>
							<date_post><?=$e->post_date?></date_post>
							<user_email><?=$e->user_email?></user_email>
							<user_url><?=$e->user_url?></user_url>
							<postname><?=$e->post_name?></postname>
							<?
							$resultSource=$wpdb->get_results("SELECT guid FROM {$table_prefix}posts WHERE post_status='attachment' AND post_name='".$e->post_name."'");
							if(!$resultSource){
							}else{
								foreach($resultSource as $d){?>						
									<source><?=$d->ID?></source>
								<?}?>
							<?}?>
						</article>
					<?}?>
				<?}?>						
			</articles>			
		</categorie>
	<?}?>	
<?}?>
</contenu>
