<?php
if ( defined('ABSPATH') )
	require_once( ABSPATH . 'wp-config.php');
else
    require_once('../../wp-config.php');	
	

?><root>
<?php

echo '<?phpxml version="1.0" encoding="UTF-8"?>';
?>
<contenu>
<?php

$reqContenu="SELECT  link_url, link_name, link_description FROM {$table_prefix}links";
$resultContenu=$wpdb->get_results($reqContenu);
if(!$resultContenu){
}else{
	foreach($resultContenu as $h){?>		
		<lien>
			<nom><?php=$h->link_name?></nom>
			<url_demo><?php=$h->link_url?></url_demo>	
			<descr><![CDATA[<?php=$h->link_description?>]]></descr>				
		</lien>
	<?php}?>	
<?php}?>			
	
</contenu>
