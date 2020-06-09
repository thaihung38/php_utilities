<?php
/**
 * User: HungNT
 * Date: 7/20/2018
 */

/**
new Ajax.Request(
    'libs_local/logAjax.php',
    {
        method: 'post',
        postBody: 'content=put content here'
    }
);
 */
require_once 'StaticLogger.php';

$content = $_REQUEST['content'];

if ($content) {
    StaticLogger::append($content);
}
