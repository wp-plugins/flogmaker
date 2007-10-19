<?
if ( defined('ABSPATH') )
	require_once( ABSPATH . 'wp-config.php');
else
    require_once('../../wp-config.php');	
	

$reqCategorie="SELECT term_id, name FROM {$table_prefix}terms order by name";
$resultCategorie=$wpdb->get_results($reqCategorie);
?><root>
<?
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<categories>
<?if(!$resultCategorie){
}else{
	foreach($resultCategorie as $e){?>
		<categorie>			
			<id_cat><?=$e->term_ID?></id_cat>
			<cat_name><?=$e->name?></cat_name>					
		</categorie>						
	<?}?>	
<?}?>	

</categories>
