<?
require_once('config.php');	
// Récupération des variables transmise par l'animation
$mois=$_POST["mois"];
$annee=$_POST["annee"];

$txt="";

$req="SELECT ID,post_date,{$table_prefix}post2cat.category_id as postCat FROM {$table_prefix}posts JOIN {$table_prefix}post2cat ON {$table_prefix}posts.ID={$table_prefix}post2cat.post_id WHERE post_date>= '".$annee."-".$mois."-01' AND post_date< '".$annee."-".($mois+1)."-01' AND post_status='publish'";
$result=$wpdb->get_results($req);
if(!$result){
}else{
	foreach($result as $obj){
		$Jour = date("j", strtotime($obj->post_date));
		$txt.="&textevent".$Jour."=".$obj->ID."&IDCattextevent".$Jour."=".$obj->postCat;	
	}
}
echo"?textevide=\"\"".$txt;
@mysql_free_result($res);
mysql_close();
?>