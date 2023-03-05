<?php
/**
 * Sitemap XML Feed
 *
 * @package Sitemap XML Feed
 * @copyright Copyright 2005-2017 Andrew Berezin eCommerce-Service.com
 * @copyright Copyright 2003-2017 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @link http://www.sitemaps.org/
 *
 * @version $Id: sitemapxml_install.php, v 3.9.3 19.02.2017 18:11:03 AndrewBerezin $
 * @version $Id: sitemapxml 4.0.0 2 2020-07-09 1800 davewest $
 */
 
 
use Zencart\PluginSupport\ScriptedInstaller as ScriptedInstallBase;

class ScriptedInstaller extends ScriptedInstallBase
{

    protected function executeInstall()
    {
      
     global $sniffer, $db;
     
         // set version
        $version = '4.0';
     


  //now we install this version
    $insert_result1 = $db->Execute("INSERT INTO " . TABLE_CONFIGURATION_GROUP . " (configuration_group_title, configuration_group_description, sort_order, visible) VALUES ('SitemapXML', 'Set SitemapXML Options', '1', '1');");

    $db->Execute("UPDATE ". TABLE_CONFIGURATION_GROUP . " SET `sort_order` = LAST_INSERT_ID() WHERE configuration_group_id = LAST_INSERT_ID()");

     if ($insert_result1 === false) exit ('Error in Createing New Configuration Group - SitemapXML<br/> ');

 // Get the id of the new configuration category
    $categoryid = array();
    $id_result = $db->Execute("SELECT configuration_group_id FROM ". TABLE_CONFIGURATION_GROUP . " WHERE configuration_group_title = 'SitemapXML'");
  if (!$id_result->EOF) {
     $categoryid = $id_result->fields;
     $sm_config_id = $categoryid['configuration_group_id'];
  } else {
    exit ('Failed Finding SitemapXML Configuration_Group ID<br/>Exit');
    }

    
$sql = "INSERT INTO " . TABLE_CONFIGURATION . " (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `last_modified`, `date_added`, `use_function`, `set_function`, `val_function`) VALUES
('Module version', 'SITEMAPXML_VERSION', $version, 'Current SitemapXML Version.', $sm_config_id, -10, NOW(), NOW(), NULL, NULL, NULL),
('SitemapXML Index file name', 'SITEMAPXML_SITEMAPINDEX', 'sitemapindex', 'SitemapXML Index file name - this file should be given to the search engines', $sm_config_id, 1, NOW(), NOW(), NULL, NULL, NULL),
('Sitemap directory', 'SITEMAPXML_DIR_WS', 'sitemap', 'Directory for sitemap files. If empty all sitemap xml files saved on shop root directory.', $sm_config_id, 1, NOW(), NOW(), NULL, NULL, NULL),
('Compress SitemapXML Files', 'SITEMAPXML_COMPRESS', 'false', 'Compress SitemapXML files', $sm_config_id, 2, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),', NULL),
('Lastmod tag format', 'SITEMAPXML_LASTMOD_FORMAT', 'full', 'Lastmod tag format:<br />date - Complete date: YYYY-MM-DD (eg 1997-07-16)<br />full -    Complete date plus hours, minutes and seconds: YYYY-MM-DDThh:mm:ssTZD (eg 1997-07-16T19:20:30+01:00)', $sm_config_id, 3, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'date\', \'full\'),', NULL),
('Start Security Token', 'SITEMAPXML_EXECUTION_TOKEN', '', 'Used to prevent a third party not authorized start of the generator Sitemap XML. To avoid the creation of intentional excessive load on the server, DDoS-attacks.', $sm_config_id, 3, NOW(), NOW(), NULL, NULL, NULL),
('Use Existing Files', 'SITEMAPXML_USE_EXISTING_FILES', 'true', 'Use Existing XML Files', $sm_config_id, 4, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),', NULL),
('Generate links only for default language', 'SITEMAPXML_USE_ONLY_DEFAULT_LANGUAGE', 'false', 'Generate links for all languages or only for default language', $sm_config_id, 5, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),', NULL),
('Using parameter language in links', 'SITEMAPXML_USE_LANGUAGE_PARM', 'true', 'Using parameter language in links:<br />true - normally use it,<br />all - using for all languages including pages for default language,<br />false - don\'t use it', $sm_config_id, 6, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'all\', \'false\'),', NULL),
('Check Duplicates', 'SITEMAPXML_CHECK_DUPLICATES', 'true', 'true - check duplicates,<br />mysql - check duplicates using mySQL (used to store a large number of products),<br />false - don\'t check duplicates', $sm_config_id, 7, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'mysql\', \'false\'),', NULL),
('Ping urls', 'SITEMAPXML_PING_URLS', 'Google => http://www.google.com/webmasters/sitemaps/ping?sitemap=%s;\r\nBing => http://www.bing.com/webmaster/ping.aspx?siteMap=%s', 'List of pinging urls separated by ;', $sm_config_id, 10, NOW(), NOW(), NULL, 'zen_cfg_textarea(', NULL),
('Active plugins', 'SITEMAPXML_PLUGINS', 'sitemapxml_categories.php;sitemapxml_mainpage.php;sitemapxml_manufacturers.php;sitemapxml_products.php;sitemapxml_products_reviews.php;sitemapxml_testimonials.php', 'What plug-ins from existing uses to generate the site map', $sm_config_id, 15, NOW(), NOW(), NULL, 'zen_cfg_read_only(', NULL),
('Home page order by', 'SITEMAPXML_HOMEPAGE_ORDERBY', 'sort_order ASC', '', $sm_config_id, 20, NOW(), NOW(), NULL, NULL, NULL),
('Home page changefreq', 'SITEMAPXML_HOMEPAGE_CHANGEFREQ', 'weekly', 'How frequently the Home page is likely to change.', $sm_config_id, 21, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'no\', \'always\', \'hourly\', \'daily\', \'weekly\', \'monthly\', \'yearly\', \'never\'),', NULL),
('Products order by', 'SITEMAPXML_PRODUCTS_ORDERBY', 'products_sort_order ASC, last_date DESC', '', $sm_config_id, 30, NOW(), NOW(), NULL, NULL, NULL),
('Products changefreq', 'SITEMAPXML_PRODUCTS_CHANGEFREQ', 'weekly', 'How frequently the Product pages page is likely to change.', $sm_config_id, 31, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'no\', \'always\', \'hourly\', \'daily\', \'weekly\', \'monthly\', \'yearly\', \'never\'),', NULL),
('Use cPath parameter', 'SITEMAPXML_PRODUCTS_USE_CPATH', 'false', 'Use cPath parameter in products url. Coordinate this value with the value of variable $includeCPath in file init_canonical.php', $sm_config_id, 32, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),', NULL),
('Add Products Images', 'SITEMAPXML_PRODUCTS_IMAGES', 'false', 'Generate Products Image tags for products urls', $sm_config_id, 35, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),', NULL),
('Use Products Images Caption/Title', 'SITEMAPXML_PRODUCTS_IMAGES_CAPTION', 'false', 'Generate Product image tags Title and Caption', $sm_config_id, 36, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),', NULL),
('Products Images license', 'SITEMAPXML_PRODUCTS_IMAGES_LICENSE', '', 'A URL to the license of the Products images', $sm_config_id, 37, NOW(), NOW(), NULL, NULL, NULL),
('Categories order by', 'SITEMAPXML_CATEGORIES_ORDERBY', 'sort_order ASC, last_date DESC', '', $sm_config_id, 40, NOW(), NOW(), NULL, NULL, NULL),
('Category changefreq', 'SITEMAPXML_CATEGORIES_CHANGEFREQ', 'weekly', 'How frequently the Category pages page is likely to change.', $sm_config_id, 41, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'no\', \'always\', \'hourly\', \'daily\', \'weekly\', \'monthly\', \'yearly\', \'never\'),', NULL),
('Add Categories Images', 'SITEMAPXML_CATEGORIES_IMAGES', 'false', 'Generate Categories Image tags for categories urls', $sm_config_id, 42, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),', NULL),
('Use Categories Images Caption/Title', 'SITEMAPXML_CATEGORIES_IMAGES_CAPTION', 'false', 'Generate Categories image tags Title and Caption', $sm_config_id, 43, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),', NULL),
('Categories Images license', 'SITEMAPXML_CATEGORIES_IMAGES_LICENSE', '', 'A URL to the license of the Categories images', $sm_config_id, 44, NOW(), NOW(), NULL, NULL, NULL),
('Category paging', 'SITEMAPXML_CATEGORIES_PAGING', 'false', 'Add all category pages (with page=) to sitemap', $sm_config_id, 45,NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),', NULL),
('Reviews order by', 'SITEMAPXML_REVIEWS_ORDERBY', 'reviews_rating ASC, last_date DESC', '', $sm_config_id, 50, NOW(), NOW(), NULL, NULL, NULL),
('Reviews changefreq', 'SITEMAPXML_REVIEWS_CHANGEFREQ', 'weekly', 'How frequently the Reviews pages page is likely to change.', $sm_config_id, 51, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'no\', \'always\', \'hourly\', \'daily\', \'weekly\', \'monthly\', \'yearly\', \'never\'),', NULL),
('EZPages order by', 'SITEMAPXML_EZPAGES_ORDERBY', 'sidebox_sort_order ASC, header_sort_order ASC, footer_sort_order ASC', '', $sm_config_id, 60, NOW(), NOW(), NULL, NULL, NULL),
('EZPages changefreq', 'SITEMAPXML_EZPAGES_CHANGEFREQ', 'weekly', 'How frequently the EZPages pages page is likely to change.', $sm_config_id, 61, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'no\', \'always\', \'hourly\', \'daily\', \'weekly\', \'monthly\', \'yearly\', \'never\'),', NULL),
('Testimonials order by', 'SITEMAPXML_TESTIMONIALS_ORDERBY', 'last_date DESC', '', $sm_config_id, 70, NOW(), NOW(), NULL, NULL, NULL),
('Testimonials changefreq', 'SITEMAPXML_TESTIMONIALS_CHANGEFREQ', 'weekly', 'How frequently the Testimonials page is likely to change.', $sm_config_id, 71, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'no\', \'always\', \'hourly\', \'daily\', \'weekly\', \'monthly\', \'yearly\', \'never\'),', NULL),
('News Articles order by', 'SITEMAPXML_NEWS_ORDERBY', 'last_date DESC', '', $sm_config_id, 80, NOW(), NOW(), NULL, NULL, NULL),
('News Articles changefreq', 'SITEMAPXML_NEWS_CHANGEFREQ', 'weekly', 'How frequently the News Articles is likely to change.', $sm_config_id, 81, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'no\', \'always\', \'hourly\', \'daily\', \'weekly\', \'monthly\', \'yearly\', \'never\'),', NULL),
('Manufacturers order by', 'SITEMAPXML_MANUFACTURERS_ORDERBY', 'last_date DESC', '', $sm_config_id, 90, NOW(), NOW(), NULL, NULL, NULL),
('Manufacturers changefreq', 'SITEMAPXML_MANUFACTURERS_CHANGEFREQ', 'weekly', 'How frequently the Manufacturers is likely to change.', $sm_config_id, 91, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'no\', \'always\', \'hourly\', \'daily\', \'weekly\', \'monthly\', \'yearly\', \'never\'),', NULL),
('Add Manufacturers Images', 'SITEMAPXML_MANUFACTURERS_IMAGES', 'false', 'Generate Manufacturers Image tags for manufacturers urls', $sm_config_id, 92, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),', NULL),
('Use Images Caption/Title', 'SITEMAPXML_MANUFACTURERS_IMAGES_CAPTION', 'false', 'Generate Manufacturer image tags Title and Caption', $sm_config_id, 93, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'true\', \'false\'),', NULL),
('Manufacturers Images license', 'SITEMAPXML_MANUFACTURERS_IMAGES_LICENSE', '', 'A URL to the license of the Manufacturers images', $sm_config_id, 94, NOW(), NOW(), NULL, NULL, NULL),
('News Box Manager - order by', 'SITEMAPXML_BOXNEWS_ORDERBY', 'last_date DESC', '', $sm_config_id, 100, NOW(), NOW(), NULL, NULL, NULL),
('News Box Manager - changefreq', 'SITEMAPXML_BOXNEWS_CHANGEFREQ', 'weekly', 'How frequently the News Box Manager is likely to change.', $sm_config_id, 101, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'no\', \'always\', \'hourly\', \'daily\', \'weekly\', \'monthly\', \'yearly\', \'never\'),', NULL),
('Products Reviews - order by', 'SITEMAPXML_PRODUCTS_REVIEWS_ORDERBY', 'last_date DESC', '', $sm_config_id, 110, NOW(), NOW(), NULL, NULL, NULL),
('Products Reviews - changefreq', 'SITEMAPXML_PRODUCTS_REVIEWS_CHANGEFREQ', 'weekly', 'How frequently the Products Reviews is likely to change.', $sm_config_id, 111, NOW(), NOW(), NULL, 'zen_cfg_select_option(array(\'no\', \'always\', \'hourly\', \'daily\', \'weekly\', \'monthly\', \'yearly\', \'never\'),', NULL)";

  $this->executeInstallerSql($sql);
    //  $db->Execute($sql);


      // find next sort order in admin_pages table
    $result = $db->Execute("SELECT (MAX(sort_order)+2) as sort FROM ".TABLE_ADMIN_PAGES);
    $admin_page_sort = $result->fields['sort'];
        
    zen_deregister_admin_pages('sitemapxmlConfig');
    zen_register_admin_page('sitemapxmlConfig', 'BOX_CONFIGURATION_SITEMAPXML', 'FILENAME_CONFIGURATION', 'gID='. $sm_config_id . '', 'configuration', 'Y', $admin_page_sort);
   
    zen_deregister_admin_pages('sitemapxml');
    zen_register_admin_page('sitemapxml', 'BOX_SITEMAPXML', 'FILENAME_SITEMAPXML', '', 'tools', 'Y', $admin_page_sort);  



    
 
}



    protected function executeUninstall()
    {
 	
        zen_deregister_admin_pages('sitemapxmlConfig');
        zen_deregister_admin_pages('sitemapxml');

        $deleteTC = "'SITEMAPXML_VERSION', 'SITEMAPXML_SITEMAPINDEX', 'SITEMAPXML_DIR_WS', 'SITEMAPXML_COMPRESS', 'SITEMAPXML_LASTMOD_FORMAT', 'SITEMAPXML_EXECUTION_TOKEN', 'SITEMAPXML_USE_EXISTING_FILES', 'SITEMAPXML_USE_ONLY_DEFAULT_LANGUAGE', 'SITEMAPXML_USE_LANGUAGE_PARM', 'SITEMAPXML_CHECK_DUPLICATES', 'SITEMAPXML_PING_URLS', 'SITEMAPXML_PLUGINS', 'SITEMAPXML_HOMEPAGE_ORDERBY', 'SITEMAPXML_HOMEPAGE_CHANGEFREQ', 'SITEMAPXML_PRODUCTS_ORDERBY', 'SITEMAPXML_PRODUCTS_CHANGEFREQ', 'SITEMAPXML_PRODUCTS_USE_CPATH', 'SITEMAPXML_PRODUCTS_IMAGES', 'SITEMAPXML_PRODUCTS_IMAGES_CAPTION', 'SITEMAPXML_PRODUCTS_IMAGES_LICENSE', 'SITEMAPXML_CATEGORIES_ORDERBY', 'SITEMAPXML_CATEGORIES_CHANGEFREQ', 'SITEMAPXML_CATEGORIES_IMAGES', 'SITEMAPXML_CATEGORIES_IMAGES_CAPTION', 'SITEMAPXML_CATEGORIES_IMAGES_LICENSE', 'SITEMAPXML_CATEGORIES_PAGING', 'SITEMAPXML_REVIEWS_ORDERBY', 'SITEMAPXML_REVIEWS_CHANGEFREQ', 'SITEMAPXML_EZPAGES_ORDERBY', 'SITEMAPXML_EZPAGES_CHANGEFREQ', 'SITEMAPXML_TESTIMONIALS_ORDERBY', 'SITEMAPXML_TESTIMONIALS_CHANGEFREQ', 'SITEMAPXML_NEWS_ORDERBY', 'SITEMAPXML_NEWS_CHANGEFREQ', 'SITEMAPXML_MANUFACTURERS_ORDERBY', 'SITEMAPXML_MANUFACTURERS_CHANGEFREQ', 'SITEMAPXML_MANUFACTURERS_IMAGES', 'SITEMAPXML_MANUFACTURERS_IMAGES_CAPTION', 'SITEMAPXML_MANUFACTURERS_IMAGES_LICENSE', 'SITEMAPXML_BOXNEWS_ORDERBY', 'SITEMAPXML_BOXNEWS_CHANGEFREQ', 'SITEMAPXML_PRODUCTS_REVIEWS_ORDERBY', 'SITEMAPXML_PRODUCTS_REVIEWS_CHANGEFREQ'";
       
        $sql = "DELETE FROM " . TABLE_CONFIGURATION . " WHERE configuration_key IN (" . $deleteTC . ")";
        $this->executeInstallerSql($sql);

         $sql = "DELETE FROM " . TABLE_CONFIGURATION_GROUP . " WHERE configuration_group_title = 'SitemapXML'";
         $this->executeInstallerSql($sql);
             
    }


}
