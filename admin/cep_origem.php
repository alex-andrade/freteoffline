<?PHP

require_once("./inc/Global.php");

$linkjs=$linkcss="";
// adiciona
if ($action == "add") {
  $novo_cep_origem = $_REQUEST['nco'];

  /** pegar todos os dados de um cep_origem qualquer
    e inserir cada registro obtido modificando o cep_origem para o novo, e o campo lastupdate para NULL **/
  // um cep_origem qualquer
  $sql = "SELECT cep_origem FROM frete GROUP By cep_origem ORDER By cep_origem Limit 0,1";
  $res = mysql_query($sql);
  if ( ($row=mysql_fetch_object($res)) ) {
    $ref_cep_origem = $row->cep_origem;
  }
  $new_Insert = "INSERT INTO frete (servico,peso,valor,prazo,nome,regiao,cep_origem,cep_destino_ini,cep_destino_fim,lastupdate,cep_dest_ref)
		  (SELECT servico,peso,valor,prazo,nome,regiao,\"".$novo_cep_origem."\" AS cep_origem,cep_destino_ini,cep_destino_fim,NULL AS lastupdate,cep_dest_ref
			FROM frete WHERE frete.cep_origem='".$ref_cep_origem."')
		";
  if(mysql_query($new_Insert)) {
	$msg = "CEP ".$novo_cep_origem." Adicionado com sucesso.";
  }
  else {
	$msg = "Houve um problema ao tentar adicionar o novo CEP de Origem ".$novo_cep_origem.". Tente novamente mais tarde.";
  }
  // alerta e redireciona p/ o index
  echo "<script type='text/javascript'>
           alert('".$msg."');
           document.location.href='./index.php';
        </script>";
  exit;
}
// remove
elseif ($action == "rem") {
  if (!is_numeric($_REQUEST['co'])) {
    // retornar erro
    exit;
  }
  $co_id=$_REQUEST['co'];
  $sql = "SELECT cep_origem FROM frete WHERE cep_origem != '".$co_id."' GROUP By cep_origem Limit 0,2";
  $res = mysql_query($sql);
  if (mysql_num_rows($res) >= 1) {
    $sql = "DELETE FROM frete WHERE cep_origem='".$co_id."'";
    if(mysql_query($sql)) {
	$msg = "CEP ".$co_id." excluído com sucesso.";
    }
    else {
	$msg = "Houve um problema ao tentar excluir o CEP ".$co_id.". Tente novamente mais tarde.";
    }
  }
  else $msg = "Este é o único CEP de Origem no seu banco de dados e não pode ser removido, cadastre outro CEP e tente novamente.";

  // alerta e redireciona p/ o index
  echo "<script type='text/javascript'>
	   alert('".$msg."');
	   document.location.href='./index.php';
	</script>";
  exit;
}
// edita
elseif ($action == "edit") {
 if (!is_numeric($_REQUEST['edco']) OR !is_numeric($_REQUEST['edcoid'])) {
	echo "<script type='text/javascript'> alert('Requisição inválida'); document.location.href='./index.php'; </script>";
	exit;
 }
 $n_edco = $_REQUEST['edco'];
 $edcoid = $_REQUEST['edcoid'];

 $sql = "SELECT cep_origem FROM frete WHERE cep_origem='".$n_edco."' Limit 0,1";
 $res = mysql_query($sql);
 if ( ($row=mysql_fetch_object($res)) ) {
	echo "<script type='text/javascript'> alert('O CEP ".$n_edco." Já existe no banco de dados.'); document.location.href='./index.php'; </script>";
	exit;
 }

 $sql = "UPDATE frete SET cep_origem = '".$n_edco."' WHERE cep_origem='".$edcoid."'";
 mysql_query($sql);

 echo "<script type='text/javascript'> alert('CEP Origem ".$edcoid." alterado para ".$n_edco."'); document.location.href='./index.php'; </script>";
 exit;
}
// lista
elseif ($action == "lista" OR $action == "default") {
  $sql = "SELECT cep_origem,count(peso) AS total_peso FROM frete Group By cep_origem";
  $res = mysql_query($sql);
  $lista = Array();
  while ( ($row=mysql_fetch_object($res)) ) {
	$lista[$row->cep_origem] = Array();
	$lista[$row->cep_origem]['total'] = $row->total_peso;
  }
  $linkjs = "<script stype='text/javascript' src='./html/js/cep_origem.js'></script>";
  $linkcss = "<link rel='stylesheet' type='text/css' href='./html/css/cep_origem.css'></link>";
  require("./html/cep_origem_lista.php");
}

?>
