<?
require_once('config.php');	
?><root>
<?
$reqContenu="SELECT nom ,url, logo FROM {$table_prefix}flashblog";
$resultContenu=$wpdb->get_results($reqContenu);
echo '<?xml version="1.0" encoding="UTF-8"?>';

if(!$resultContenu){
}else{
	foreach($resultContenu as $e){
		$nom=$e->nom;
		$url=$e->url;
		$logo=$e->logo;
	}
}
echo "<parametres><nom>".$nom."</nom>";
echo "<url>".$url."</url>";

echo "<couleurs>";
	$reqCouleur="SELECT couleur FROM {$table_prefix}flashblog_couleurs ORDER BY id";
	$resultCouleur=$wpdb->get_results($reqCouleur);
	if(!$resultCouleur){
	}else{
		foreach($resultCouleur as $f){
			$couleur=$f->couleur;
			echo "<couleur>0x".$couleur."</couleur>";	
		}
	}
echo "</couleurs>";
echo "<onglets>";
	$reqOnglet="SELECT nom,fichier,picto,nom_url,actif FROM {$table_prefix}flashblog_onglets WHERE actif=1 ORDER BY id";
	$resultOnglet=$wpdb->get_results($reqOnglet);
	foreach($resultOnglet as $g){
		$nom_onglet=$g->nom;
		$fichier_onglet=$g->fichier;
		$actif_onglet=$g->actif;
		$nom_url=$g->nom_url;
		$picto_onglet=$g->picto;
		if ($actif_onglet=="1"){
			echo "<onglet>";
				echo "<nom_onglet>".$nom_onglet."</nom_onglet>";
				echo "<fichier_onglet>".$fichier_onglet."</fichier_onglet>";
				echo "<nom_url>".$nom_url."</nom_url>";
				echo "<picto>".$picto."</picto>";
			echo "</onglet>";
		}
	}
	
echo "</onglets>";
echo "<logo>".$logo."</logo>";
echo "</parametres>";
?>
