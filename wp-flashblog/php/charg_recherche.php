<?php
if ( defined('ABSPATH') )
	require_once( ABSPATH . 'wp-config.php');
else
    require_once('../../wp-config.php');	
	

$reqCategorie="SELECT term_id, name FROM {$table_prefix}terms order by name";
$resultCategorie=$wpdb->get_results($reqCategorie);
?><root>
<?php
echo '<?phpxml version="1.0" encoding="UTF-8"?>';
?>
<categories>
<?phpif(!$resultCategorie){
}else{
	foreach($resultCategorie as $e){?>
		<categorie>			
			<id_cat><?php=$e->term_ID?></id_cat>
			<cat_name><?php=$e->name?></cat_name>					
		</categorie>						
	<?php}?>	
<?php}?>	

</categories>
