<?php

if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

 function flog_maker_advanced()
  {
    global $wpdb, $table_prefix;
    
    $options = get_option('flog_maker'); 
    
    ?>
      <style type="text/css">
        .diagnostics-ok
        {
          font-weight: bold;
          color: #090;
        }
        
        .diagnostics-fail
        {
          font-weight: bold;
          color: #c00;
        }
        
        .diagnostics-warning
        {
          font-weight: bold;
          color: #c60;
        }
      </style>
      <form action="<?php echo $_SERVER['REQUEST_URI'] ?>" method="post">
        <div class="wrap">
          
          <?php if(isset($_POST['commence_diagnostics'])): ?>
            
            <h2><?php _e('Diagnostics', 'flog-maker') ?></h2>
            <p>
              <?php _e('Diagnostics started...', 'flog-maker') ?>
            </p>
            <h3><?php _e('Systems Check', 'flog-maker') ?></h3>
              <ul>
                <li><?php _e('PHP Version:', 'flog-maker') ?> <strong><?php echo phpversion() ?></strong></li>
                <li><?php _e('MySQL Version:', 'flog-maker') ?> <strong><?php echo $wpdb->get_var("SELECT VERSION()") ?></strong></li>
                <li><?php _e('Server Software:', 'flog-maker') ?> <strong><?php echo $_SERVER['SERVER_SOFTWARE'] ?></strong></li>
                <li><?php _e('WordPress Version', 'flog-maker') ?> <strong><?php echo bloginfo('version') ?></strong></li>
                <li><?php _e('Flash Blog Plugin Version:', 'flog-maker') ?> <strong><?php echo FLOG_MAKER_VERSION ?></strong></li>
              </ul>
            <h3><?php _e('Table Check', 'flog-maker') ?></h3>
            <ul>
              <?php
                $tables = array($table_prefix."flashblog", $table_prefix."flashblog_couleurs", $table_prefix."flashblog_onglets")
              ?>
              <?php foreach($tables as $t): ?>
                <li>
                  <?php printf(__('Checking existence of %s...', 'flog-maker'), '<code>'.$t.'</code>') ?>
                  <?php 
                    $x = (bool) ($wpdb->get_var("SHOW TABLES LIKE '{$t}'")); 
                    if($x) { echo '<span class="diagnostics-ok">'.__('OK', 'flog-maker').'</span>'; }
                    else { echo '<span class="diagnostics-fail">'.__('Failed', 'flog-maker').'</span><br /><em>'.__('The table was not found in your database.  Check your database manually to see if the table has been renamed, or you have changed your WordPress table prefixes.  If the table does not exist, you may have to fully reinstall this plugin.', 'flog-maker').'</em>'; }
                  ?>
                </li>
              <?php endforeach; ?>
            </ul>             
            
                        
                      
          <?php elseif($_POST['commence_uninstall']): ?>
            
            <?php check_admin_referer('run-advanced-tools_flog-maker') ?>
            
            <?php if(function_exists('wp_nonce_field')) { wp_nonce_field('uninstall-data_flog-maker');} ?>
            
            <h2><?php _e('Uninstall', 'flog-maker') ?></h2>
            
            <div style="text-align: center">
              <h3 style="font-size: 2.5em; color: #c00; text-transform: uppercase"><?php _e('Warning', 'flog-maker') ?></h3>
              <div style="width: 50%; text-align: left; margin: 0 auto 2em auto; padding: 10px; border: 1px solid #c00; background-color: #fcc;">
                <?php _e('You are about to delete the following database tables:', 'flog-maker') ?>
                <ul>
                  <li><?php echo $table_prefix ?>flashblog</li>
                  <li><?php echo $table_prefix ?>flashblog_couleurs</li>
                  <li><?php echo $table_prefix ?>flashblog_onglets</li>
                </ul>
                
                <p>
                  <?php _e('You will <strong>lose all data</strong> stored in the tables. Make absolutely sure you have these tables backed up if you wish to reinstall at a later date.', 'flog-maker') ?>
                </p>
              </div>
              
              <p><?php _e('Are you still sure you wish to uninstall?', 'flog-maker') ?></p>
              <p class="submit" style="text-align: center"><input type="submit" name="commence_uninstall_cancelled" value="<?php _e('Cancel', 'flog-maker') ?>" style="background: #c00; color: #fff; font-size: 2em; font-weight: bold; text-transform: uppercase" /></p>
              <p class="submit" style="text-align: center"><input type="submit" name="commence_uninstall_confirmed" value="<?php _e('Confirm', 'flog-maker') ?>" style="background: #390; color: #fff" /></p>
            </div>
            
          <?php elseif($_POST['commence_uninstall_confirmed']): ?>
            
            <?php check_admin_referer('uninstall-data_flog-maker') ?>
            
            <h2><?php _e('Uninstall', 'flog-maker') ?></h2>
            
            <h3><?php _e('Deleting...', 'flog-maker') ?></h3>
            
            <ul>
              <?php 
                foreach(array("", "_couleurs", "_onglets") as $table)
                {
                  ?>
                    <li>
                      <?php printf(__('Dropping table %s...', 'flog-maker'), "<code>{$table_prefix}flashblog{$table}</code>"); ?>
                      <?php $wpdb->query("DROP TABLE {$table_prefix}flashblog{$table}"); 
					  $wpdb->query("DELETE FROM {$table_prefix}options WHERE option_name='flog_maker'");
					  ?>
                      <span class="diagnostics-ok"><?php _e('OK', 'flog-maker') ?></span>
                    </li>
                  <?php
                }
              ?>
              
              <li>
                <?php 
                  $deactivate_url = "plugins.php?action=deactivate&amp;plugin=flog-maker/flog-maker.php";
                  if(function_exists('wp_nonce_url')) { $deactivate_url = wp_nonce_url($deactivate_url, 'deactivate-plugin_flog-maker/flog-maker.php'); }
                ?>
                <?php printf(__('Deactivating plugin... to finish this process, <a href="%s">click here</a>.', 'flog-maker'), $deactivate_url) ?>
              </li>
            </ul>
            
          <?php else: ?>
            
            <?php if(function_exists('wp_nonce_field')) { wp_nonce_field('run-advanced-tools_flog-maker'); } ?>
            
                       
             <div style="width: 48%; float: left">
              <h2><?php _e('Uninstall', 'flog-maker') ?></h2>
              <p>
                <?php _e('Disabling the plugin does not remove any data that may have been created, such as posts and topics.  To completely remove this plugin, you can uninstall it here.', 'flog-maker') ?>
              </p>
              <p style="color: #c00">
                <?php vprintf(__('<strong>WARNING:</strong> once uninstalled, this cannot be undone!  You should use the WordPress Database Backup plugin to back up all this data first.  Your data is stored in the %1$s, %2$s and %3$s tables.', 'flog-maker'), array("<strong><em>{$table_prefix}flashblog</em></strong>", "<strong><em>{$table_prefix}flashblog_couleurs</em></strong>", "<strong><em>{$table_prefix}flashblog_onglets</em></strong>")) ?>
              </p>
              <p>
                <?php printf(__('<strong>Note:</strong> this will <em>not</em> delete any user information. Any users that registered are stored in the WordPress user management system.  You will have to remove personal accounts through the <a href="%s">Authors &amp; Users</a> page.', 'flog-maker'), "users.php") ?>
              </p>
              <p class="submit">
                <input type="submit" value="<?php _e('Uninstall Flog Maker', 'flog-maker') ?> &raquo;" style="background: #c33; color: white" name="commence_uninstall" />
              </p>
            </div>			
            
            <div style="width: 48%; float: right">
              <h2><?php _e('Diagnostics', 'flog-maker') ?></h2>
              <p>
                <?php _e('Here you can check that your blog is suitably configured for running Flog Maker, and that all tables are intact and valid.', 'flog-maker') ?>
              </p>
              <p class="submit">
                <input type="submit" value="<?php _e('Start Diagnostic Run', 'flog-maker') ?> &raquo;" name="commence_diagnostics" />
              </p>
            </div>         
           
            
            <br style="clear: both;" />
          <?php endif; ?>
        </div>
      </form>
    <?php
  }

?>