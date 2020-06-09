<?php
/**
 * User: HungNT
 * Date: 7/20/2018
 */

/**
new Ajax.Request(
    'libs_local/logAjaxFtp.php',
    {
        method: 'post',
        postBody: 'content=put content here'
    }
);
 */
require_once 'RemoteLogger.php';

$content = $_REQUEST['content'];

if ($content) {
    RemoteLogger::log($content);
}
