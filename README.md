﻿#Content management for Anax-MVC with unit testing
This is a module for Anax-MVC, that adds pages and a blog to your page, along with management tools för CRUD actions.##Requirements- PHP 5.4 or higher- Anax-MVC- mos/cdatabase- mos/cform##Install1. Install using Composer("sofa15/anax-mvc-content": "dev-master") or download files from GitHub. If downloading, remember to also download and install mos/cdatabase and mos/cform. 2. Move/copy the config file corresponding to your database from cdatabase/webroot to your app/config folder. Make sure to edit the config file with your database connection info.3. To load the services, direct your webroot/config.php or configwithapp.php file to use Anax/DI/CDIFactoryContent as $di or add the services in CDIFactoryContent.php to your own DI-class.4. Move/copy the viewfiles from app/view to your own app.##Usage**To try the module out using prepared files:**Move/copy webroot/testcontent.php to your own webroot and open the file in your browser. First do a setup of pages and blog. Then you can view a list of contents in both tables, and try out the CRUD actions.**To include the pages and blog into your own site:**When using the module with your own site, feel free to edit viewfiles. Link to pages by using route 'page/view/' followed by the page slug. Link to the blog by using route 'blog' and link to specific posts by using 'blog/view/' followed by the post slug.Find the filters supported for content in your src/Content/CTextfilter class.If you wish to edit the pages and blogpost inserted at setup and reset they are located in the setup.php files in src/page/ or blog/ respectively. If you wish to alter what columns are in the tables you will also have to edit the CFormAdd and Update classes for each type of content, also found in src/page and src/blog, to make sure inserts and updates are done correctly when editing content.##LicenseThis software is free software and carries a MIT license.