<?PHP

$dbuser="Usuario do Banco de Dados";
$dbpass="Senha do banco de dados";
$dbhost="host do banco de dados(localhost?)";
$dbname="nome da base de dados";

mysql_connect($dbhost,$dbuser,$dbpass);
mysql_select_db($dbname);

?>
