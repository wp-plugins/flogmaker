<?
if ( defined('ABSPATH') )
	require_once( ABSPATH . 'wp-config.php');
else
    require_once('../../wp-config.php');	
	

$reqCategorie="SELECT cat_ID, cat_name FROM {$table_prefix}categories order by cat_name";
$resultCategorie=$wpdb->query($reqCategorie);
?><root>
<?
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<categories>
<?if(!$resultCategorie){
}else{
	foreach($resultCategorie as $e){?>
		<categorie>			
			<id_cat><?=$e->cat_ID?></id_cat>
			<cat_name><?=$e->cat_name?></cat_name>					
		</categorie>						
	<?}?>	
<?}?>	

</categories>
