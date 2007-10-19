<?
// Connexion à la BDD
if ( defined('ABSPATH') )
	require_once( ABSPATH . 'wp-config.php');
else
    require_once('../../wp-config.php');	

?><root><?


$champs=$_GET["champs"];
$categories = $_GET["categories"];
$texteToSearch=$_GET["texteToSearch"];
/*
$champs="post_content";
$categories = "1351,1";
$texteToSearch="Bien";
*/
//je decoupe la chaine des champs sur lesquels effectuer la rechrche
$tabChamps = explode(',', $champs);
//je decoupe la chaine des id de categories sur lesquels faire la recherche
$tabCategorie = explode(',', $categories);
$tabMots = explode(' ', $texteToSearch);
$maChaineCat="";
$maChaineChamps="";

foreach ( $tabCategorie as $categorie ){
	if ($maChaineCat!=""){
		$maChaineCat=$maChaineCat.",".$categorie." ";
	}else{
		$maChaineCat=$categorie." ";
	}		
}

foreach ( $tabMots as $mot ){
	foreach ( $tabChamps as $champ ){	
		if ($maChaineChamps!=""){
			$maChaineChamps=$maChaineChamps." OR ".$champ." LIKE '%".$mot."%' ";	
		}else{
			$maChaineChamps=$maChaineChamps." AND (".$champ." LIKE '%".$mot."%' ";	
		}	
	}	
}


	
$reqContenu="SELECT t1.ID,post_title,post_date,t2.user_nicename as pseudo,t2.user_email as user_email, t2.user_url as user_url,post_content,post_content,t3.term_taxonomy_id as category FROM {$table_prefix}posts as t1 JOIN {$table_prefix}users as t2  on t1.post_author=t2.ID JOIN {$table_prefix}term_relationships as t3 ON t1.ID=t3.object_id WHERE t1.ID IN (SELECT object_id FROM {$table_prefix}term_relationships WHERE t1.post_status='publish' AND t3.term_taxonomy_id IN (".$maChaineCat.")) ".$maChaineChamps.") ORDER BY t3.term_taxonomy_id";
$resultContenu=$wpdb->get_results($reqContenu);

echo '<?xml version="1.0" encoding="'.get_option('blog_charset').'"?'.'>'; 
echo '<contenu>';
if(!$resultContenu){
}else{
	foreach($resultContenu as $g){	
		echo '<article>';			
			echo '<id_post>'.$g->ID.'</id_post>';
			echo '<cat_post>'.$g->category.'</cat_post>';
			echo '<titre>'.$g->post_title.'</titre>';			
			echo '<texte><![CDATA['.substr($g->post_content,0,150).']]></texte>';				
			echo '<auteur>'.$g->pseudo.'</auteur>';
			echo '<date_post>'.$g->post_date.'</date_post>';
		echo '</article>';						
	}}
echo '</contenu>';
?>