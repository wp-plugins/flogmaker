<?
require_once('config.php');	

// Récupération des variables transmise par l'animation

$id_article=$_POST["id_article"];
$auteur=$_POST["auteur"];
$auteur_email=$_POST["auteur_email"];
$auteur_url=$_POST["auteur_url"];
$commentaire=$_POST["commentaire"];
$comment_date =  date("Y-m-d G:i:s");
$wpdb->query("INSERT INTO {$table_prefix}comments (comment_post_ID,comment_author,comment_author_email, comment_author_url,comment_date,comment_date_gmt,comment_content) VALUES (".$id_article.",'".$auteur."','".$auteur_email."','".$auteur_url."','".$comment_date."','".$comment_date."','".$commentaire."')");

$reqCom="SELECT comment_count FROM {$table_prefix}posts WHERE ID=".$id_article;
$resultCom=$wpdb->get_results($reqCom);
if(!$resultCom){
}else{
	foreach($resultCom as $e){
		$comment_count=$e->comment_count;
	}
}
$comment_count2 = $comment_count+1;
$wpdb->query("UPDATE {$table_prefix}posts SET comment_count=".$comment_count2." WHERE ID=".$id_article);
echo "&TRANSFERTOK=1";
?>