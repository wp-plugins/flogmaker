<?php
require_once('config.php');	
	
$reqContenu="SELECT ID,post_title, post_date FROM wp_posts WHERE post_status='publish' order by post_date desc";
$resultPage=$wpdb->get_results($reqContenu);

$resultURL=$wpdb->get_row("SELECT option_value FROM wp_options WHERE option_name='siteurl'");
$myURL = $resultURL->option_value."/wp-flashblog";


$filename = '../sitemap.xml';
$somecontent = "";
$somecontent = $somecontent."<?xml version='1.0' encoding='UTF-8'?><urlset xmlns='http://www.google.com/schemas/sitemap/0.84' xmlns:xsi='http://www.w3.org/2001/XMLSchema-instance' xsi:schemaLocation='http://www.google.com/schemas/sitemap/0.84http://www.google.com/schemas/sitemap/0.84/sitemap.xsd'>";
if(!$resultPage){
}else{
	foreach($resultPage as $e){	
		$somecontent = $somecontent."<url>";
		$reqCategorie="SELECT cat_ID FROM wp_categories WHERE cat_ID IN (SELECT category_id FROM wp_post2cat WHERE post_id =".$e->ID.")";
		$resultCategorie=$wpdb->get_results($reqCategorie);
		if(!$resultCategorie){
		}else{
			foreach($resultCategorie as $f){	
				$idmacat = $f->cat_ID;
			}
		}
		$somecontent = $somecontent."<loc><![CDATA[".$myURL."/index.php#idCat=".$idmacat."&idArticle=".$e->ID."&".urlencode($e->post_title)."]]></loc>";
		$somecontent = $somecontent."<lastmod>".$e->post_date."</lastmod>";
		$somecontent = $somecontent."<changefreq>daily</changefreq>";
		$somecontent = $somecontent."<priority>0.9</priority>";
		$somecontent = $somecontent."</url>";
	}
	
	$somecontent = $somecontent."<url>";
	$somecontent = $somecontent."<loc><![CDATA[".$myURL."/index.php#page=Chercher]]></loc>";
	$somecontent = $somecontent."<lastmod>07/01/2007</lastmod>";
	$somecontent = $somecontent."<changefreq>daily</changefreq>";
	$somecontent = $somecontent."<priority>0.9</priority>";
	$somecontent = $somecontent."</url>";
	$somecontent = $somecontent."<url>";
	$somecontent = $somecontent."<loc><![CDATA[".$myURL."/index.php#page=Liens]]></loc>";
	$somecontent = $somecontent."<lastmod>07/01/2007</lastmod>";
	$somecontent = $somecontent."<changefreq>daily</changefreq>";
	$somecontent = $somecontent."<priority>0.9</priority>";
	$somecontent = $somecontent."</url>";
	$somecontent = $somecontent."<url>";
	$somecontent = $somecontent."<loc><![CDATA[".$myURL."/index.php#page=Profil]]></loc>";
	$somecontent = $somecontent."<lastmod>07/01/2007</lastmod>";
	$somecontent = $somecontent."<changefreq>daily</changefreq>";
	$somecontent = $somecontent."<priority>0.9</priority>";
	$somecontent = $somecontent."</url>";	
	$somecontent = $somecontent."<url>";
	$somecontent = $somecontent."<loc><![CDATA[".$myURL."/index.php#page=Contact]]></loc>";
	$somecontent = $somecontent."<lastmod>07/01/2007</lastmod>";
	$somecontent = $somecontent."<changefreq>daily</changefreq>";
	$somecontent = $somecontent."<priority>0.9</priority>";
	$somecontent = $somecontent."</url>";	
	$somecontent = $somecontent."<url>";
	$somecontent = $somecontent."<loc><![CDATA[".$myURL."/index.php#page=Archives]]></loc>";
	$somecontent = $somecontent."<lastmod>07/01/2007</lastmod>";
	$somecontent = $somecontent."<changefreq>daily</changefreq>";
	$somecontent = $somecontent."<priority>0.9</priority>";
	$somecontent = $somecontent."</url>";	
	$somecontent = $somecontent."</urlset>";

	// Assurons nous que le fichier est accessible en écriture
	if (is_writable($filename)) {
	   // Dans notre exemple, nous ouvrons le fichier $filename en mode d'ajout
	   // Le pointeur de fichier est placé à la fin du fichier
	   // c'est là que $somecontent sera placé
	   if (!$handle = fopen($filename, 'w')) {
	         echo "Impossible d'ouvrir le fichier ($filename)";
	         exit;
	   }

	   // Ecrivons quelque chose dans notre fichier.
	   if (fwrite($handle, $somecontent) === FALSE) {
	       echo "Impossible d'écrire dans le fichier ($filename)";
	       exit;
	   }	  
	  
		$location ="../../wp-admin/admin.php?page=flog-maker&sitemap=updated" ;
		wp_redirect($location);
	    fclose($handle);
	} else {
	   echo "Le fichier $filename n'est pas accessible en écriture.";
	}
}
?>