<?
// Connexion à la BDD
if ( defined('ABSPATH') )
	require_once( ABSPATH . 'wp-config.php');
else
    require_once('../../wp-config.php');	

// Récupération des variables transmise par l'animation
$mois=$_POST["mois"];
$annee=$_POST["annee"];
/*$mois="10";
$annee="2007";*/
$txt="";

$req="SELECT DISTINCT ID,post_date FROM {$table_prefix}posts WHERE post_date>= '".$annee."-".$mois."-01' AND post_date< '".$annee."-".($mois+1)."-01' AND post_status='publish'";
//echo $req;
$result=$wpdb->get_results($req);
if(!$result){
}else{
	foreach($result as $obj){			
		$Jour = date("j", strtotime($obj->post_date));		
		$reqCat="SELECT term_taxonomy_id as postCat FROM {$table_prefix}term_relationships WHERE object_id=".$obj->ID." AND term_taxonomy_id IN(SELECT term_id FROM wp_term_taxonomy WHERE taxonomy='category')";
		//echo "<br><br>".$reqCat;
		$resultCat=$wpdb->get_results($reqCat);
		if(!$resultCat){		
		}else{
			foreach($resultCat as $objCat){	
				$cat = $objCat->postCat;
				$txt.="&textevent".$Jour."=".$obj->ID."&IDCattextevent".$Jour."=".$cat;
				//echo "<br><br>&textevent".$Jour."=".$obj->ID."&IDCattextevent".$Jour."=".$cat;;
			}
		}
	}
}
echo"?textevide=\"\"".$txt;
@mysql_free_result($res);
mysql_close();
?>