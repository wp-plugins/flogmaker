==== FlogMaker Plugin ====
Contributors: Céline Mornet
Donate link: http://www.lutincapuche.com/
Tags: formatting, flash
Requires at least: 2.0.3
Tested up to: 2.0.3
Stable tag: 1.0.1

FLOG MAKER is an entirely self-contained alternative flash blog system that resides within a WordPress plugin


== Description ==

FLOG MAKER is an entirely self-contained alternative flash blog system that resides within a WordPress plugin, designed for absolutely seamless integration with your WordPress installation. Among its features are:

- Full integration with WordPress' own user system
- Multiple flash blog setup
- Search capabilities
- Calendar capabilities
- Contact capabilities
- Totally customisable
- Clean uninstall if you don't like it :)


== Installation ==

NOTE: Flog maker is ALPHA software! It may malfunction, corrupt its own data and bite your dog. Please bear these in mind while I try and iron out the bugs!

The lowest specs Flog Maker has been tested on so far PHP 4.4.4, MySQL 4.0, Apache 1.3.33 and WordPress 2.0.3.
If it works for you on worse specs, please let me know. My email address is at the bottom.

To install FlashBlog, follow these steps:

1.   Upload the /flog-maker/ directory to your WordPress plugins folder (note: upload the DIRECTORY itself, not just the files)
2.   Upload the /wp-flashblog/ directory to your WordPress plugins folder (note: upload the DIRECTORY itself, not just the files)
3.   Activate the plugin
4.   Be sure the flashblog url in the managment plugin settings is the right one
5.   You're done!

This is how the files fit into the overall file structure of your site:

wordpress/
+ wp-content/
  + plugins/
   + flog-maker/
    + admin/
      |+ admin.php
      | + couleur.php
      | + onglets.php
      | + infos.php
      | + edit-bg.php
      | + overview.php
      | + advanced.php
    + languages/
    + flog-maker.php
    + class.php
+ wp-admin/
+ wp-flashblog/

You'll find a new top-level menu option in your site admin, labelled "FlashBlog". From here you can set up your configuration variables, and be ready to run in seconds.

The flashblog is accessible by your url /wp-flashblog/

== Arbitrary section ==

Deactive the plugin, remove the flog-maker directory and replace it entirely with the new one. Reactivate the plugin afterwards.
HOWEVER, any customisation of your flashblog will be lost.
You will have to re-enable any custom templates.
UNINSTALLING

If you don't like FlogMaker and want to remove it permanently, there is a clean uninstall option that will remove all data that was created when you installed. Simply go to FlahBlog > Advanced > Uninstall and follow the prompts and it'll be out of your hair in no time.
CSS AND FLOGMAKER

Css is not well interpreted in flash textfields. only few attributs are allowed (see the documentation), and that is why this plugin is absolutely not compatible with plugins like “syntaxHighlighter».
You can add pictures but no movies.

Rare are css tags which are interpreted by Flash. Don’t hope to use div or tables, they are not interpreted and can make bugs and break page layout.

Texts styles are defined manually in the file : wp-flashblog/css/style.css. You can edit attributes and values, colors, fonts, sizes …
These tags are mapped to the content with a replacement function of the following tags. If you do not use these tags, your text styles will be not visible in FlashBlog.

So try to use at the maximum these tags in your content to have a nice and various article:

    * <strong></strong>
    * <a href=”" target=”" title=”"></a>
    * <h1></h1>
    * <h2></h2>
    * <h3></h3>
    * <li></li>
    * <img src=”/84095907_0af4994686.jpg” alt=”un arbre ” name=”image109? hspace=”20? vspace=”20? align=”absmiddle” id=”image109? />
    * <i></i>
    * <br />

For the moment, flog maker allow only simple html contents, but i am working on insertion of medias like movies or swf files, and on a picture galery.


