<?PHP
require_once("./inc/database.php");

if (isset($_REQUEST['action'])) $action = $_REQUEST['action'];
else $action = "default";

?>
