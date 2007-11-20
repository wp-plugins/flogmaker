<?php  
require_once (ABSPATH . WPINC . '/rss.php');
if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

  function flog_maker_admin_overview()  {
    global $wpdb, $table_prefix, $userdata, $weekday;    
    ?>   
	<?php
		if($_GET['sitemap']){
          echo '<div id="message" class="updated fade-ffff00"><p><strong>'.__('Le fichier sitemap.xml a &eacute;t&eacute; mis a jour', 'flog-maker').'</strong></p></div>';
         
        } 
		?>    
      <div class="wrap">
       
		<h2><?php _e('Flog Maker Overview', 'flog-maker') ?></h2>        
              
        <h3><?php _e('Bienvenue', 'flog-maker') ?></h3>        
        <p>
          <?php
            $userlevel = '<strong>' . (current_user_can('manage_options') ? __('administrateur', 'flog-maker') : __('moderateur', 'flog-maker')) . '</strong>';
            printf(__('Ceci est le panel d\'administration du pluggin flog-maker.<br \><br \>Il permet de configurer et de personnaliser celui ci tr&egrave;s simplement.<br \>Une exp&eacute;rimentation de Wordpress bas&eacute; sur du Flash.<br \>Vos droits actuels : %s .', 'flog-maker'), $userlevel);
          ?>
        </p>
        
        <ul>		
          <li><a href="../wp-flashblog/"><?php _e('Voir le flashBlog', 'flog-maker') ?></li>
          <li><a href="admin.php?page=flog-maker-infos"><?php _e('Editer mes param&egrave;tres g&eacute;n&eacute;raux', 'flog-maker') ?></li>
          <li><a href="admin.php?page=flog-maker-couleurs"><?php _e('Modifiez les couleurs du flashblog', 'flog-maker') ?></li>
          <li><a href="admin.php?page=flog-maker-onglets"><?php _e('Modifiez les onglets du flashblog', 'flog-maker') ?></li>
          <li><a href="admin.php?page=flog-maker-advanced-fond"><?php _e('Modifiez le fond du flashblog', 'flog-maker') ?></li> 
          <li><a href="../wp-flashblog/PHP/sitemap.php"><?php _e('Reg&eacute;n&eacute;rez le fichier sitemap.xml', 'flog-maker') ?></li> 
          <li><a href="../wp-flashblog/sitemap.xml"><?php _e('Voir le fichier sitemap.xml', 'flog-maker') ?></li> 
		</ul>
        
		<br \><br \>
		
		<h3><a href="http://www.lutincapuche.com/?feed=rss&cat=13"><?php _e('Flog Maker Latest News', 'flog-maker') ?></a></h3>
        
         <?php
          $rss = fetch_rss('http://www.lutincapuche.com/?feed=rss&cat=13');
          
          if ( isset($rss->items) && 0 != count($rss->items) )
          {
            $rss->items = array_slice($rss->items, 0, 3);
            foreach ($rss->items as $item)
            {
            ?>
              <h4><a href='<?php echo wp_filter_kses($item['link']); ?>'><?php echo wp_specialchars($item['title']); ?></a> &#8212; <?php echo human_time_diff(strtotime($item['pubdate'], time())); ?></h4>
              <p><?php echo $item['description']; ?></p>
            <?php
            }
          }
          else
          {
            ?>
            <p><?php printf(__('Newsfeed could not be loaded.  Check the <a href="http://www.lutincapuche.com">front page</a> to check for updates.', 'rs-discuss'), 'http://www.sargant.com/') ?></p>
            <?php
          }
        ?>
		
		
		

		
        <br style="clear: both" />  	  
      </div>      
<?php }?>