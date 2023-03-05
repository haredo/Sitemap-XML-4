# SitemapXML v4 
Author:  Andrew Berezin 

Modified for Zen-Cart Version 1.5.7 by: davewest (CowboyGeek.com)

## Description
This Script generates a Sitemap as described here:
http://www.sitemaps.org/
http://support.google.com/webmasters/bin/answer.py?hl=en&answer=156184&topic=8476&ctx=topic

Upgrading this to ZenCart 1.5.7 and php7.4 was not easy.  I'm still in the process of following code flow.  I was considering adding XML template builders for Auctions and Maps, but I did not see anything new in Google instructions.  Still looking.

## Upgrading to Zen Cart 1.5.8
Simply overwrite the existing 1.5.7 file(s) with those in the ZC158 directory.

## **READ THE ISSUES ON GITHUB PRIOR TO LOADING!!!**

## Disclaimer
You download and use at your own risk and all that shit...
I don't have a business in web design and so have no need to degrade code for older stores or bad versions of PHP. 
There are others who do have a business and provide such support.
If you truly have an issue, ask, There's a donate button on my web site, coffee is not cheep!

## Site Map XML files
Still reading up on the standard format of the xml files to see what else can be added.  If you know of other tags we can add, speak up.

Current XML setup for everything is
```xml

  <url>
  <loc>https:// URL /index.php?main_page=product_info&amp;products_id=72</loc>
  <lastmod>2020-05-22T03:54:02-07:00</lastmod>
  <changefreq>weekly</changefreq>
  <priority>1.00</priority>
  <image:image>
   <image:loc>https:// URL /images/large/maps/AZ_TNM_Quads_LRG.jpg</image:loc>
  </image:image>
  </url>

```
Looking at adding ROR setup which for products is
```xml

 <Resource>
 <type>Product</type>
 <title>Name or Title of Product Two</title>
 <url>https:// URL /index.php?main_page=product_info&amp;products_id=72</url>
 <desc>Description of Product Two</desc>
 <keywords>keyword1, keyword2, keyword3</keywords>
 <image>https:// URL /images/large/maps/AZ_TNM_Quads_LRG.jpg</image>
 <imageSmall>https:// URL /images/maps/AZ_TNM_Quads.jpg</imageSmall>
 <price>19.95</price>
 <currency>USD</currency>
 <available>yes</available>
 </Resource>

```
 
 With a greater number of types Classified Ad, Auctions, maps would be nice..
 
 NOTE:  Recent installations have been done outside the Modules  >>  Plugin Manger solution.  This may result in the sitemap directory being created in the root of your site.  If the directory is not there, create it with the proper permission (755) and go to the mod's page on your site.  Leave the Actions unclicked and run the mod.  If all files look okay, select both actions and run the mod again.
 
