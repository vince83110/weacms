<?php
ob_start('ob_gzhandler');
header('Content-type: text/javascript; charset: ISO-8859-1');
header('Expires: '.gmdate('D, d M Y H:i:s \G\M\T', time() + 3600));

echo (file_get_contents ($_GET['file']));
?>