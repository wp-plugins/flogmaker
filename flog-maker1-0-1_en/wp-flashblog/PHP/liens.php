<?
require_once('config.php');	
?><root>
<?

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<contenu>
<?

$reqContenu="SELECT  link_url, link_name, link_description FROM {$table_prefix}links";
$resultContenu=$wpdb->get_results($reqContenu);
if(!$resultContenu){
}else{
	foreach($resultContenu as $h){?>		
		<lien>
			<nom><?=$h->link_name?></nom>
			<url_demo><?=$h->link_url?></url_demo>	
			<descr><![CDATA[<?=$h->link_description?>]]></descr>				
		</lien>
	<?}?>	
<?}?>			
	
</contenu>
