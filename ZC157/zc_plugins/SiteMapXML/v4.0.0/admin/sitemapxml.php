<?php
/**
 * Sitemap XML Feed
 *
 * @package Sitemap XML Feed
 * @copyright Copyright 2005-2016 Andrew Berezin eCommerce-Service.com
 * @copyright Copyright 2003-2020 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @link http://www.sitemaps.org/
 * @version $Id: sitemapxml.php, v 3.9.2 09.11.2016 13:37:18 AndrewBerezin $
 * @modified for ZenCart v1.5.7 5 2020-09-23 1800 davewest $
 */

require('includes/application_top.php');
/*
//why????????
if (!is_file(DIR_WS_LANGUAGES . $_SESSION['language'] . '/sitemapxml.php')) {
  require_once(DIR_WS_LANGUAGES . 'english/sitemapxml.php');
  $messageStack->add(sprintf(TEXT_MESSAGE_LANGUGE_FILE_NOT_FOUND, $_SESSION['language']), 'warning');
} */

$action = (isset($_POST['action']) ? $_POST['action'] : '');

if (zen_not_null($action)) {
  switch ($action) {
    case 'view_file':
    case 'truncate_file':
    case 'delete_file':
    case 'test_view':
      if (isset($_POST['file']) && trim($_POST['file']) != '' && (($ext = substr($_POST['file'], strpos($_POST['file'], '.'))) == '.xml' || $ext = '.xml.gz')) {
        $file = zen_db_prepare_input($_POST['file']);
        switch ($action) {
          case 'view_file':
            if ($fp = fopen(DIR_FS_CATALOG . $file, 'r')) {
              header('Content-Length: ' . filesize(DIR_FS_CATALOG . $file));
              header('Content-Type: text/plain; charset=' . CHARSET);
              while (!feof($fp)) {
                $contents .= fread($fp, 8192);
                echo $contents;
              }
              fclose($fp); 
              die();
            } else {
              $messageStack->add_session(sprintf(TEXT_MESSAGE_FILE_ERROR_OPENED, $file), 'error');
            }
            break; 
          case 'truncate_file':
            if ($fp = fopen(DIR_FS_CATALOG . $file, 'w')) {
              fclose($fp);
              $messageStack->add_session(sprintf(TEXT_MESSAGE_FILE_TRUNCATED, $file), 'success');
            } else {
              $messageStack->add_session(sprintf(TEXT_MESSAGE_FILE_ERROR_OPENED, $file), 'error');
            }
            break;
          case 'delete_file':
            if (unlink(DIR_FS_CATALOG . $file)) {
              $messageStack->add_session(sprintf(TEXT_MESSAGE_FILE_DELETED, $file), 'success');
            } else {
              $messageStack->add_session(sprintf(TEXT_MESSAGE_FILE_ERROR_DELETED, $file), 'error');
            }
            break;
        }
      }
      zen_redirect(zen_href_link(FILENAME_SITEMAPXML));
      break;

    case 'select_plugins':
      $active_plugins = (isset($_POST['plugin']) ? $_POST['plugin'] : '');
      $active_plugins = (is_array($active_plugins) ? implode(';', $active_plugins) : $active_plugins);
      $sql = "UPDATE " . TABLE_CONFIGURATION . " SET configuration_value='" . zen_db_input($active_plugins) . "' where configuration_key='SITEMAPXML_PLUGINS'";
      $db->Execute($sql);
      zen_redirect(zen_href_link(FILENAME_SITEMAPXML));
      break;
  }

}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
  <head>
    <?php
    require DIR_WS_INCLUDES . 'admin_html_head.php';
    
    ?>
<title><?php echo HEADING_TITLE; ?></title>

<style type="text/css">
.sitemap {width:45%;float:left;margin:1em;}
.box {width: 20%;margin: 0 auto;background: rgba(255,255,255,0.2); padding: 5px;border: 2px solid #a40000;}
.overlay {position: fixed;top: 0;bottom: 0;left: 0; right: 0;background: rgba(0, 0, 0, 0.7);transition: opacity 500ms;visibility: hidden;opacity: 0;z-index: 9999;}
.overlay:target {visibility: visible;opacity: 1;}
.popup {margin: 70px auto;padding: 20px;background: #fff;border-radius: 5px;width: 40%;position: relative;transition: all 5s ease-in-out;}
.popup h2 {margin-top: 0;color: #333;font-family: Tahoma, Arial, sans-serif;}
.popup .close {position: absolute;top: 20px;right: 30px;transition: all 200ms;font-size: 30px;font-weight: bold; text-decoration: none;color: #333;}
.popup .content {max-height: 30%;overflow: auto;}
.btn-view {background-color: #3465a4;color: #eee;}
.btn-turn {background-color: #75507b;color: #eee;}
.btn-delete {background-color: #f00;color: #eee;}
@media screen and (max-width: 700px){.box{width: 70%;}.popup{width: 70%;}.sitemap{width:100%;}}
</style>
 <script>
  <!--
  function init()
  {
    cssjsmenu('navbar');
    if (document.getElementById)
    {
      var kill = document.getElementById('hoverJS');
      kill.disabled = true;
    }
  }
</script>

<script type="text/javascript">
function getFormFields(obj) {
  var getParms = "";
  for (var i=0; i<obj.childNodes.length; i++) {
    if (obj.childNodes[i].name == "securityToken") continue;
    if (obj.childNodes[i].tagName == "INPUT") {
      if (obj.childNodes[i].type == "text") {
        getParms += "&" + obj.childNodes[i].name + "=" + obj.childNodes[i].value;
      }
      if (obj.childNodes[i].type == "hidden") {
        getParms += "&" + obj.childNodes[i].name + "=" + obj.childNodes[i].value;
      }
      if (obj.childNodes[i].type == "checkbox") {
        if (obj.childNodes[i].checked) {
          getParms += "&" + obj.childNodes[i].name + "=" + obj.childNodes[i].value;
        }
      }
      if (obj.childNodes[i].type == "radio") {
        if (obj.childNodes[i].checked) {
          getParms += "&" + obj.childNodes[i].name + "=" + obj.childNodes[i].value;
        }
      }
    }
    if (obj.childNodes[i].tagName == "SELECT") {
      var sel = obj.childNodes[i];
      getParms += "&" + sel.name + "=" + sel.options[sel.selectedIndex].value;
    }
  }
  getParms = getParms.replace(/\s+/g," ");
  getParms = getParms.replace(/ /g, "+");
  return getParms;
}
</script>
</head>
<body onload="init()">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->

<!-- body //-->

<!-- body_text //-->
    <div>    
        <h1><?php echo HEADING_TITLE . '&nbsp;&nbsp;&nbsp; v' . SITEMAPXML_VERSION; ?> </h1>      
        <div class="col-6 col-md-4"><a href="#popup1" class="btn btn-primary"><?php echo TEXT_SITEMAPXML_TIPS_HEAD; ?></a></div>
   </div>
   <div id="popup1" class="overlay">
	<div class="popup">
	   <h2><?php echo TEXT_SITEMAPXML_TIPS_HEAD; ?></h2>
	   <a class="close" href="#">&times;</a>
	   <div class="content"><?php echo TEXT_SITEMAPXML_TIPS_TEXT; ?></div>
	</div>
  </div>

 <br class="clearBoth" />

<?php 
  $start_parms = '';
  if (defined('SITEMAPXML_EXECUTION_TOKEN') && zen_not_null(SITEMAPXML_EXECUTION_TOKEN)) {
    $start_parms = 'token=' . SITEMAPXML_EXECUTION_TOKEN;
  }
?>
   <div class="row">
   
     <fieldset class="sitemap"> 
      
                  <h3><?php echo TEXT_SITEMAPXML_INSTRUCTIONS_HEAD; ?></h3>                  
             <hr />
                  <h3><?php echo TEXT_SITEMAPXML_CHOOSE_PARAMETERS; ?></h3>
                  <?php echo zen_draw_form('pingSE', FILENAME_SITEMAPXML, '', 'post', 'id="pingSE" target="_blank" onsubmit="javascript:window.open(\'' .  zen_catalog_href_link(FILENAME_SITEMAPXML, $start_parms) . '\'+getFormFields(this), \'sitemapPing\', \'resizable=1,statusbar=5,width=860,height=800,top=0,left=0,scrollbars=yes,toolbar=yes\');return false;"'); ?>
                    <?php echo zen_draw_checkbox_field('rebuild', 'yes', false, '', 'id="rebuild"'); ?>
                    <label for="rebuild"><?php echo TEXT_SITEMAPXML_CHOOSE_PARAMETERS_REBUILD; ?></label>
                    <br class="clearBoth" />
                    <?php echo zen_draw_checkbox_field('ping', 'yes', false, '', 'id="ping"'); ?>
                    <label for="ping"><?php echo TEXT_SITEMAPXML_CHOOSE_PARAMETERS_PING; ?></label>
                    <br class="clearBoth" />
                    <div class="col-6 col-md-4"><button type="submit" class="btn btn-primary" title="<?php echo TEXT_IMAGE_SUBMIT; ?>"><?php echo TEXT_IMAGE_SUBMIT; ?></button></div><div class="col-6 col-md-4"><?php echo TEXT_SUBMIT_NOTE; ?> </div>
                  </form>
             
        </fieldset>   
                
     <fieldset  class="sitemap"> 
     
                  <h3><?php echo TEXT_SITEMAPXML_PLUGINS_LIST; ?></h3>
                  
             <hr /> 
                  <h3><?php echo TEXT_SITEMAPXML_PLUGINS_LIST_SELECT; ?></h3>
                    <?php
                    echo zen_draw_form('selectPlugins', FILENAME_SITEMAPXML, '', 'post', 'id="selectPlugins"');
                    echo zen_draw_hidden_field('action', 'select_plugins');
                    if (!($plugins_files = glob(DIR_FS_CATALOG_MODULES . 'pages/sitemapxml/' . 'sitemapxml_*.php'))) $plugins_files = array();
                    $plugins_files_active = explode(';', SITEMAPXML_PLUGINS);
                    foreach ($plugins_files as $plugin_file) {
                    $plugin_file = basename($plugin_file);
                    $plugin_name = str_replace('.php', '', $plugin_file);
                    $active = in_array($plugin_file, $plugins_files_active);
                    echo '<label for="plugin-' . $plugin_name . '" class="plugin' . ($active ? '_active' : '') . '">&nbsp;&nbsp;' . zen_draw_checkbox_field('plugin[]', $plugin_file, $active, '', 'id="plugin-' . $plugin_name . '"') . $plugin_file . '</label>&nbsp;&nbsp;&nbsp;';
                     } ?>
                    <br class="clearBoth" />
                   <div class="col-6 col-md-4"><button type="submit" class="btn btn-primary"><?php echo TEXT_IMAGE_SAVE; ?></button></div><div class="col-6 col-md-4"><?php echo TEXT_SAVE_SETTINGS; ?> </div>
                  </form>
             
        </fieldset>
                
 </div>               
                
                
                
   <div class="row">
                
                  <h3><?php echo TEXT_SITEMAPXML_FILE_LIST; ?></h3>
               <table class="table table-hover">
                  <thead>
                    <tr class="dataTableHeadingRow">
                      <th class="dataTableHeadingContent center"><?php echo TEXT_SITEMAPXML_FILE_LIST_TABLE_FNAME; ?></th>
                      <th class="dataTableHeadingContent center hidden-sm hidden-xs"><?php echo TEXT_SITEMAPXML_FILE_LIST_TABLE_FSIZE; ?></th>
                      <th class="dataTableHeadingContent center hidden-sm hidden-xs"><?php echo TEXT_SITEMAPXML_FILE_LIST_TABLE_FTIME; ?></th>
                      <th class="dataTableHeadingContent center hidden-sm hidden-xs"><?php echo TEXT_SITEMAPXML_FILE_LIST_TABLE_FPERMS; ?></th>
                      <th class="dataTableHeadingContent center"><?php echo TEXT_SITEMAPXML_FILE_LIST_TABLE_TYPE; ?></th>
                      <th class="dataTableHeadingContent center"><?php echo TEXT_SITEMAPXML_FILE_LIST_TABLE_ITEMS; ?></th>
                      <th class="dataTableHeadingContent center hidden-sm hidden-xs"><?php echo TEXT_SITEMAPXML_FILE_LIST_TABLE_COMMENTS; ?></th>
                      <th class="dataTableHeadingContent center"><?php echo TEXT_SITEMAPXML_FILE_LIST_TABLE_ACTION; ?></th>
                    </tr>
                 </thead>
               <tbody> 
<?php
$indexFile = SITEMAPXML_SITEMAPINDEX . (SITEMAPXML_COMPRESS == 'true' ? '.xml.gz' : '.xml');
if (!($sitemapFiles = glob(DIR_FS_CATALOG . 'sitemap' . '*' . '.xml'))) $sitemapFiles = array();
if (!($sitemapFilesGZ = glob(DIR_FS_CATALOG . 'sitemap' . '*' . '.xml.gz'))) $sitemapFilesGZ = array();
$sitemapFiles = array_merge($sitemapFiles, $sitemapFilesGZ);
if (SITEMAPXML_DIR_WS != '') {
  $sitemapxml_dir_ws = SITEMAPXML_DIR_WS;
  $sitemapxml_dir_ws = trim($sitemapxml_dir_ws, '/');
  $sitemapxml_dir_ws .= '/';
  if (($files = glob(DIR_FS_CATALOG . $sitemapxml_dir_ws . 'sitemap' . '*' . '.xml'))) $sitemapFiles = array_merge($sitemapFiles, $files);
  if (($files = glob(DIR_FS_CATALOG . $sitemapxml_dir_ws . 'sitemap' . '*' . '.xml.gz'))) $sitemapFiles = array_merge($sitemapFiles, $files);
}
sort($sitemapFiles);
if (in_array(DIR_FS_CATALOG . $indexFile, $sitemapFiles)) {
  $sitemapFiles = array_merge(array(DIR_FS_CATALOG . $indexFile), $sitemapFiles);
}
$sitemapFiles = array_unique($sitemapFiles);
clearstatcache();
$l = strlen(DIR_FS_CATALOG);
foreach ($sitemapFiles as $file) {
  $f['name'] = substr($file, $l);
  $f['size'] = filesize($file);
  $f['time'] = filemtime($file);
  $f['time'] = date(PHP_DATE_TIME_FORMAT, $f['time']);
  $f['perms'] = fileperms($file);
  $f['perms'] = substr(sprintf('%o', $f['perms']), -4);
  $class = '';
  $comments = '';
  $type = '';
  $items = '';
  if (!is_writable($file)) {
    $class .= ' alert';
    $comments .= ' ' . TEXT_SITEMAPXML_FILE_LIST_COMMENTS_READONLY;
  }
  if ($f['name'] == $indexFile) {
    $class .= ' index';
  }
  if ($f['size'] == 0) {
    $class .= ' zero';
    $comments .= ' ' . TEXT_SITEMAPXML_FILE_LIST_COMMENTS_IGNORED;
  }
  if ($f['size'] > 0) {
    if ($fp = fopen($file, 'r')) {
      $contents = '';
      while (!feof($fp)) {
        $contents .= fread($fp, 8192);
      }
      fclose($fp);
      if (strpos($contents, '</urlset>') !== false) {
        $type = TEXT_SITEMAPXML_FILE_LIST_TYPE_URLSET;
        $items = substr_count($contents, '</url>');
      } elseif (strpos($contents, '</sitemapindex>') !== false) {
        $type = TEXT_SITEMAPXML_FILE_LIST_TYPE_SITEMAPINDEX;
        $items = substr_count($contents, '</sitemap>');
      } else {
        $type = TEXT_SITEMAPXML_FILE_LIST_TYPE_UNDEFINED;
        $items = '';
      }
      unset($contents);
    } else {
      $items = '<span style="color:red">' . 'Error!!!' . '</span>';
    }
  }
?>
                    <tr class="dataTableRow<?php echo $class; ?>" onmouseout="rowOutEffect(this)" onmouseover="rowOverEffect(this)">
                      <td class="dataTableContent"><a href="<?php echo HTTP_CATALOG_SERVER . DIR_WS_CATALOG . $f['name']; ?>" target="_blank"><?php echo $f['name']; ?>&nbsp;<?php echo zen_image(DIR_WS_IMAGES . 'icon_popup.gif', TEXT_SITEMAPXML_IMAGE_POPUP_ALT, '10', '10'); ?></a></td>
                      <td class="dataTableContent center<?php echo $class; ?> hidden-sm hidden-xs"><?php echo $f['size']; ?></td>
                      <td class="dataTableContent center<?php echo $class; ?> hidden-sm hidden-xs"><?php echo $f['time']; ?></td>
                      <td class="dataTableContent center<?php echo $class; ?> hidden-sm hidden-xs"><?php echo $f['perms']; ?></td>
                      <td class="dataTableContent center<?php echo $class; ?>"><?php echo $type; ?></td>
                      <td class="dataTableContent center<?php echo $class; ?>"><?php echo $items; ?></td>
                      <td class="dataTableContent center<?php echo $class; ?> hidden-sm hidden-xs"><?php echo trim($comments); ?></td>
                      <td class="dataTableContent right<?php echo $class; ?>">
<?php
if ($f['size'] > 0) {
  echo zen_draw_form('view_file', FILENAME_SITEMAPXML, '', 'post', ' target="_blank"') . zen_draw_hidden_field('action', 'view_file') . zen_draw_hidden_field('file', $f['name']) . '<button type="submit" class="btn btn-sm btn-default btn-view" title="View Raw File"><i class="fa fa-eye fa-lg" aria-hidden="true"></i></button>' . '</form>';
  echo zen_draw_form('truncate_file', FILENAME_SITEMAPXML, '', 'post', 'onsubmit="return confirm(\'' . sprintf(TEXT_ACTION_TRUNCATE_FILE_CONFIRM, $f['name']) . '\');"') . zen_draw_hidden_field('action', 'truncate_file') . zen_draw_hidden_field('file', $f['name']) . '<button type="submit" class="btn btn-sm btn-default btn-turn" title="Truncate File"><i class="fa fa-asterisk fa-lg" aria-hidden="true"></i></button>' . '</form>';
}
echo zen_draw_form('delete_file', FILENAME_SITEMAPXML, '', 'post', 'onsubmit="return confirm(\'' . sprintf(TEXT_ACTION_DELETE_FILE_CONFIRM, $f['name']) . '\');"') . zen_draw_hidden_field('action', 'delete_file') . zen_draw_hidden_field('file', $f['name']) . '<button type="submit" class="btn btn-sm btn-default btn-delete" title="Delete File"><i class="fa fa-trash-o fa-lg" aria-hidden="true"></i></button>' . '</form>';
?>    
                      </td>
                    </tr>
<?php
}
?>
                   </tbody>
               </table>
                  <br /><a class="btn btn-primary" title="<?php echo TEXT_SITEMAPXML_RELOAD_WINDOW; ?>" href="javascript: window.location.reload()"><?php echo TEXT_SITEMAPXML_RELOAD_WINDOW; ?></a>
                </div>
               
               
<?php
/*
    if (!($robots_txt = @file_get_contents($this->savepath . 'robots.txt'))) {
      echo '<b>File "robots.txt" not found in save path - "' . $this->savepath . 'robots.txt"</b>' . '<br />';
    } elseif (!preg_match("@Sitemap:\s*(.*)\n@i", $robots_txt . "\n", $m)) {
      echo '<b>Sitemap location don\'t specify in robots.txt</b>' . '<br />';
    } elseif (trim($m[1]) != $this->base_url . $this->sitemapindex) {
      echo '<b>Sitemap location specified in robots.txt "' . trim($m[1]) . '" another than "' . $this->base_url . $this->sitemapindex . '"</b>' . '<br />';
    }
*/
?>
 
<!-- body_text_eof //-->

<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
