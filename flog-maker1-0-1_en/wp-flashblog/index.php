<?
require_once('PHP/config.php');	
?>
<?
$reqContenu="SELECT nom ,url FROM {$table_prefix}flashblog";
$resultContenu=$wpdb->get_results($reqContenu);
if(!$resultContenu){
}else{
	foreach($resultContenu as $e){
		$nom=$e->nom;
		$url=$e->url;
	}
}
echo $table_prefix;
$reqEmail="SELECT user_email FROM {$table_prefixUser}users WHERE user_status=0";
$resultMail=$wpdb->get_results($reqEmail);
if(!$resultContenu){

}else{
	foreach($resultContenu as $e){		
		$dest=$e->user_email;
		$nicename=$e->user_nicename;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//FR" "http://www.w3.org/TR/html40/strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr" xml:lang="fr" />
<head>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=iso-8859-1" />
<meta http-equiv="Content-Language" content="fr" />
<meta name="Author" content="<?echo $nicename?>" />	
<meta name="Reply-to" content="<?echo $dest?>" />	
<meta name="Identifier-URL" content="<?echo $url?>" />	
<meta name="Copyright" content="<?echo $nicename?>" />	
<meta name="Revisit-after" content="2 days" />	
<meta name="Robots" content="all" />	
<meta name="ICBM" content="48.6034, 7.7766" />	
<meta name="DC.title" content="<?echo $nom?>" />
<meta name="Description" content="FlashBlog, blog flash" />
<meta name="Keywords" content="FlashBlog, blog flash" />
<title><?echo $nom?>  |  FlashBlog</title>	
<link rel="alternate" type="application/rss+xml" title="RSS" href="<?echo $url?>/?feed=rss2">
<style type="text/css">	
		@import url(css/interface.css);	
</style>
<script type="text/javascript" src="js/swfobject.js"></script>
<script language="javascript">
function retourne_cat(){
	var url = window.location.href;
	var posparamUrl = url.indexOf("#",0);
	var url = url.substr(posparamUrl+1,500);
	var posparamUrl = url.indexOf("#",0);
	var paramUrl = url.substr(posparamUrl+1,500);
	var posAnd = paramUrl.indexOf("&",0);
	var chaineCategorie = paramUrl.substr(0,posAnd);//idCat=0
	var idCategorie = chaineCategorie.substr(chaineCategorie.indexOf("=",0)+1,100);//idCat=0
	return idCategorie;
}

function retourne_art(){
	var url = window.location.href;
	var posparamUrl = url.indexOf("#",0);
	var url = url.substr(posparamUrl+1,500);
	var posparamUrl = url.indexOf("#",0);
	var paramUrl = url.substr(posparamUrl+1,500);
	var posAnd = paramUrl.indexOf("&",0);
	var chaineCategorie = paramUrl.substr(0,posAnd);//idCat=0
	var idCategorie = chaineCategorie.substr(chaineCategorie.indexOf("=",0)+1,100);//idCat=0
	var reste = paramUrl.substr(posAnd+1,500);//idCat=0
	var posAnd = reste.indexOf("&",0);
	var chaineArticle = reste.substr(0,posAnd);//idCat=0
	var idArticle = chaineArticle.substr(chaineArticle.indexOf("=",0)+1,100);//idCat=0
	return idArticle;
}
function retourne_page(){
	var url = window.location.href;
	var posparamUrl = url.indexOf("#",0);
	var url = url.substr(posparamUrl+1,500);
	var posparamUrl = url.indexOf("page",0);
	if (posparamUrl!=-1){
		var paramUrl = url.substr(posparamUrl+5,500);
		var chainePage = paramUrl;//page=bla
		var page = chainePage.substr(chainePage.indexOf("=",0)+1,100);
		return page;
	}else{
		page="";
		return page;
	}	
}
</script>
<?
$idArticle="<script language='javascript'>document.write retourne_cat();</script>";
$idCategorie="<script language='javascript'>document.write retourne_art();</script>";
?>
</head>
<body bgcolor="#000000">


<div class="monFlash">

<!--URL utilisées dans l'animation-->
<!--texte utilisé dans l'animation-->
	<div id="flashcontent">
		<strong>Merci de mettre a jour vos versions de flash </strong>		
	</div>	
	<script type="text/javascript">
		// <![CDATA[		
		var categorie = retourne_cat();
		var article = retourne_art();
		var page = retourne_page();
		var so = new SWFObject("index.swf?idCategorie="+categorie+"&idArticle="+article+"&page="+page+"", "<?echo $nom?>", "100%", "100%", "8", "#000000");
		so.addParam("scale", "noscale");
		so.addParam("allowdomain", "always");
		so.addParam("idCategorie", categorie);
		so.addParam("idArticle", article);
		so.addParam("page", page);
		so.addVariable("flashVarText", "this is passed in via FlashVars"); // this line is optional, but this example uses the variable and displays this text inside the flash movie
		so.useExpressInstall('expressinstall.swf');
		so.write("flashcontent");
		// ]]>
	</script>

</div>
</body>
</html>
