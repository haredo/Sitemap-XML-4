<?php
/**
 * Sitemap XML
 *
 * @package Sitemap XML
 * @copyright Copyright 2005-2012 Andrew Berezin eCommerce-Service.com
 * @copyright Copyright 2003-2012 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @link Testimonial Manager http://www.zen-cart.com/downloads.php?do=file&id=299
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: sitemapxml_testimonials.php, v 3.2.2 07.05.2012 19:12 AndrewBerezin $
 */

/**
 * sitemap format
 *
 * <url>
 * <loc>https://www.cowboygeek.com/cbgshop/index.php?main_page=testimonials_manager&amp;testimonials_id=3</loc>
 * <lastmod>2020-09-10T16:33:02-07:00</lastmod>
 * <changefreq>weekly</changefreq>
 * <priority>1.00</priority>
 * </url>
 *   
 */
 
 //define undfine tables 
if (!defined('TABLE_TESTIMONIALS_MANAGER')) define('TABLE_TESTIMONIALS_MANAGER', DB_PREFIX . 'testimonials_manager');

 //switched to ZC sniffer class
if ($sniffer->table_exists(TABLE_TESTIMONIALS_MANAGER)) {

  echo '<h3>' . TEXT_HEAD_TESTIMONIALS . '</h3>';
  $last_date = $db->Execute("SELECT MAX(GREATEST(date_added, IFNULL(last_update, '0000-00-00 00:00:00'))) AS last_date
                             FROM " . TABLE_TESTIMONIALS_MANAGER . " 
                             WHERE status = '1'");
  if ($sitemapXML->SitemapOpen('testimonials', $last_date->fields['last_date'])) {
    $testimonials = $db->Execute("SELECT testimonials_id, GREATEST(date_added, IFNULL(last_update, '0000-00-00 00:00:00')) AS last_date, language_id
                                  FROM " . TABLE_TESTIMONIALS_MANAGER . " 
                                  WHERE status = '1'
                                  AND language_id IN (" . $sitemapXML->getLanguagesIDs() . ") " .
                                  (SITEMAPXML_TESTIMONIALS_ORDERBY != '' ? "ORDER BY " . SITEMAPXML_TESTIMONIALS_ORDERBY : ''));
    $sitemapXML->SitemapSetMaxItems($testimonials->RecordCount());
    while (!$testimonials->EOF) {
      $sitemapXML->writeItem(FILENAME_TESTIMONIALS_MANAGER, 'testimonials_id=' . $testimonials->fields['testimonials_id'], $testimonials->fields['language_id'], $testimonials->fields['last_date'], SITEMAPXML_TESTIMONIALS_CHANGEFREQ);
      $testimonials->MoveNext();
    }
    $sitemapXML->SitemapClose();
    unset($testimonials);
  }

}

// EOF
