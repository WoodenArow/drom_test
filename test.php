<?
require('core/general.php');

$db = new Database();
if ($db->connect()){
	echo 'ok';
}
?>