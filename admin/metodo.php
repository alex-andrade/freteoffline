<?PHP

require_once("./inc/Global.php");

$linkjs=$linkcss="";

// adiciona
if ($action == "add") {
  if (!isset($_REQUEST['mnome']) OR !isset($_REQUEST['mcodigo'])) {
    // retornar erro
    exit;
  }

  $sv_nome = $_REQUEST['mnome'];
  $sv_codigo = $_REQUEST['mcodigo'];
  /** pegar todos os dados de um metodo (pode ser o primeiro da lista de servicos)
    e inserir cada registro obtido, modificando o codigo/servico para o novo, e o campo lastupdate para NULL **/
  // um método qualquer
  $sql = "SELECT id,codigo FROM servico ORDER By nome DESC Limit 0,1";
  $res = mysql_query($sql);
  if ( ($row=mysql_fetch_object($res)) ) {
    $servico = $row->codigo;
    $ref_sv_id = $row->id;
  }

  // obtendo dados do metodo a ser duplicado
  $new_Insert = "";
  $sql1 = $sql = "SELECT * FROM frete WHERE servico='".$servico."'";
  $res = mysql_query($sql) or die(mysql_error());
  while ( ($row=mysql_fetch_object($res)) ) {
	// insert do novo metodo com dados semelhantes
	$new_Insert .= " ('".$sv_codigo."',".$row->peso.",'".$row->valor."','".$row->prazo."','".$row->nome."','".$row->regiao."','".$row->cep_origem."','".$row->cep_destino_ini."','".$row->cep_destino_fim."',NULL,'".$row->cep_dest_ref."'),";
  }

  // executando insert dos dados
  if ($new_Insert != "") {
  	$new_Insert = "INSERT INTO frete (servico,peso,valor,prazo,nome,regiao,cep_origem,cep_destino_ini,cep_destino_fim,lastupdate,cep_dest_ref)
			VALUES ".substr($new_Insert,0,-1);

	if (@$_REQUEST['usasenha'] == 1) {
		$senha = $_REQUEST['senha'];
		$cod_empresa = $_REQUEST['cod_empresa'];
		$usasenha = 1;
	}
	else $usasenha=$senha=$cod_empresa=0;
	$metodo_sql = "INSERT INTO servico (nome,codigo,usasenha,cod_empresa,senha) VALUES ('".$sv_nome."','".$sv_codigo."',".$usasenha.",'".$cod_empresa."','".$senha."')";

	if ( (mysql_query($new_Insert)) AND ($id_servico = mysql_query($metodo_sql)) ) {
  	   if ($_REQUEST['metodotipoconfig'] == "metodo") {
	      $conf_sql = "INSERT INTO atualizacoes_config (ws_url,skin_xml,limite_exec,frequencia,id_servico,ativa,tipoconfig)
	 			VALUES ('".$_REQUEST['ws_url']."','".$_REQUEST['skinxml']."','".$_REQUEST['limite']."','".$_REQUEST['frequencia']."','".$id_servico."',1,'".$_REQUEST['metodotipoconfig']."')";
	      mysql_query($conf_sql);
	   }
	   $msg = "Método adicionado com sucesso.";
	}
	else {
		$msg = "Problema ".mysql_error();
		echo $msg;
		exit;
	}
  }
  if (!isset($msg) OR $msg == "") $msg = "Houve um problema ao tentar adicionar novo Método. Tente novamente.(".$servico.")";

  // alerta e redireciona p/ o index
  echo "<script type='text/javascript'>
           alert('".$msg."');
           document.location.href='./index.php';
        </script>";
  exit;
}
// remove
elseif ($action == "rem") {
  if (!is_numeric($_REQUEST['id'])) {
    // retornar erro
    exit;
  }
  $sv_id=$_REQUEST['id'];
  $sql = "SELECT codigo FROM servico WHERE id='".$sv_id."'";
  $res = mysql_query($sql);
  if ( ($row=mysql_fetch_object($res)) ) {
    $servico = $row->codigo;
  }

  $sql = "DELETE FROM servicos WHERE id='".$sv_id."'";
  mysql_query($sql);
  $sql = "DELETE FROM frete WHERE servico = '".$servico."'";
  if (mysql_query($sql)) {
	$msg = "Método excluído com sucesso.";
  } else $msg = "Houve um problema ao tentar excluir o método. Tente novamente.";

  // alerta e redireciona p/ o index
  echo "<script type='text/javascript'>
           alert('".$msg."');
           document.location.href='./index.php';
        </script>";
  exit;
}
// dados p/ editar
elseif ($action == "dadosedita") {
   if (!is_numeric($_REQUEST['id'])) {
	echo "<script type='text/javascript'>
		  alert('Requisição Inválida!');
		  document.location.href='./index.php';
		</script>";
	exit;
   }
   $sql = "SELECT sv.*, ac.* FROM servico AS sv
		LEFT OUTER JOIN atualizacoes_config AS ac ON ac.id_servico=sv.id AND ac.ativa=1
		WHERE sv.id='".$_REQUEST['id']."'
   ";
   $res = mysql_query($sql) or die(mysql_error());
   if ( ($row=mysql_fetch_object($res)) ) {
	$op = "<?xml version='1.0' encoding='utf-8' ?>
	   <DadosEdita>
		<Nome>".$row->nome."</Nome>
		<Codigo>".$row->codigo."</Codigo>
		<TipoConfig>".$row->tipoconfig."</TipoConfig>
		<ws_url>".$row->ws_url."</ws_url>
		<skinxml>".$row->skin_xml."</skinxml>
		<frequencia>".$row->frequencia."</frequencia>
		<limite>".$row->limite_exec."</limite>
		<usasenha>".$row->usasenha."</usasenha>
		<senha>".$row->senha."</senha>
		<cod_empresa>".$row->cod_empresa."</cod_empresa>
	   </DadosEdita>
	";
   }
   header("Content-Type: text/xml;");
   echo $op;
   exit;
}
// edita
elseif ($action == "edit") {
   if (!is_numeric($_REQUEST['metodo_id']) OR $_REQUEST['metodo_id'] < 1) {
	echo "<script type='text/javascript'>
		alert('Requisição Inválida!');
		document.location.href='./index.php';
		</script>";
	exit;
   }
   $msg = "Dados atualizados com sucesso.";

   // verificar alteracao no "codigo", se sim, modificar o mesmo na tabela de fretes
   $sql_upfrete="";
   $sql = "SELECT codigo FROM servico WHERE id='".$_REQUEST['metodo_id']."'";
   $res = mysql_query($sql);
   if ( ($row=mysql_fetch_object($res)) ) {
	$cod_atual = $row->codigo;
      if ($cod_atual != $_REQUEST['mcodigo']) {
		$sql_upfrete = "UPDATE frete SET servico='".$_REQUEST['mcodigo']."' WHERE servico = '".$cod_atual."'";
	}
   }
   if ($_REQUEST['usasenha'] == 1 AND $_REQUEST['senha'] != "" AND $_REQUEST['cod_empresa'] != "") {
	$usasenha=1;
	$senha = $_REQUEST['senha'];
	$cod_empresa = $_REQUEST['cod_empresa'];
   }
   else $usasenha=$senha=$cod_empresa=0;

   $sql = sprintf("UPDATE servico SET
		  nome='%s',
		  codigo='%s',
		  tipoconfig='%s',
		  usasenha='%s',
		  senha='%s',
		  cod_empresa='%s'
		WHERE id='%s'", $_REQUEST['mnome'], $_REQUEST['mcodigo'], $_REQUEST['metodotipoconfig'], $_REQUEST['metodo_id'], $usasenha, $senha, $cod_empresa);
   if (!mysql_query($sql)) {
	$msg = "Erro ao atualizar dados. Tente novamente.";
	echo "<script type='text/javascript'> alert('".$msg."'); document.location.href='./index.php'; </script>";
	exit;
   }

   $sql = "SELECT * FROM atualizacoes_config WHERE id_servico='".$_REQUEST['metodo_id']."'";
   $res = mysql_query($sql);
   if (mysql_num_rows($res) > 0) {
	if ($_REQUEST['metodotipoconfig'] == "metodo") {
	   $sql_conf = sprintf("UPDATE atualizacoes_config SET
						ws_url='%s',
						skin_xml='%s',
						limite_exec='%s',
						frequencia='%s'
					   WHERE id_servico='%s'",
					$_REQUEST['ws_url'], $_REQUEST['skinxml'], $_REQUEST['limite'], $_REQUEST['frequencia'], $_REQUEST['metodo_id']);
	}
	elseif ($_REQUEST['metodotipoconfig'] == "global") {
		$sql_conf = "UPDATE atualizacoes_config SET ativa=0 WHERE id_servico='".$_REQUEST['metodo_id']."'";
	}
   }
   elseif ($_REQUEST['metodotipoconfig'] == "metodo") {
	$sql_conf = "INSERT INTO atualizacoes_config (ws_url, skin_xml, limite_exec, frequencia, id_servico, ativa) VALUES ('".$_REQUEST['ws_url']."','".$_REQUEST['skinxml']."',".$_REQUEST['limite'].",".$_REQUEST['frequencia'].",'".$_REQUEST['metodo_id']."',1)";
   }

   if (isset($sql_conf)) if (!mysql_query($sql_conf)) {
	$msg = "Erro parcial ao atualizar dados. Verifique as configurações de atualização do método.";
	echo "<script type='text/javascript'> alert('".$msg."'); document.location.href='./index.php'; </script>";
	exit;
   }
   if (isset($sql_upfrete) AND $sql_upfrete!="") if (!mysql_query($sql_upfrete)) {
	$sql_voltacodigo = "UPDATE servico SET codigo = '".$cod_atual."' WHERE id='".$_REQUEST['metodo_id']."'";
	mysql_query($sql_voltacodigo);
	$msg = "Erro ao atualizar o codigo do serviço e por isso o codigo antigo foi mantido. Tente novamente";
	echo "<script type='text/javascript'> alert('".$msg."'); document.location.href='./index.php'; </script>";
	exit;
   }

  // alerta e redireciona p/ o index
  echo "<script type='text/javascript'>
           alert('".$msg."');
           document.location.href='./index.php';
        </script>";
  exit;
}
// lista
elseif ($action == "lista" OR $action == "default") {
  // dados para o form
  $configAtualiza = Array();
  $sql = "SELECT * FROM atualizacoes_config WHERE tipoconfig='global'";
  $res = mysql_query($sql);
  if ( ($row=mysql_fetch_object($res)) ) {
	$configAtualiza['ws_url'] = $row->ws_url;
	$configAtualiza['skin_xml'] = $row->skin_xml;
	$configAtualiza['frequencia'] = $row->frequencia;
	$configAtualiza['limite_exec'] = $row->limite_exec;
  }
  // obtem metodos p/ lista
  $metodos = "SELECT f.servico,sv.nome,sv.id FROM frete AS f,servico AS sv WHERE f.servico=sv.codigo Group By f.servico";
  $metodos = mysql_query($metodos);
  $lista = Array();
  while ( ($row=mysql_fetch_object($metodos)) ) {
	$lista[$row->id] = Array();
	$lista[$row->id]['nome'] = $row->nome;
	$lista[$row->id]['servico'] = $row->servico;
  }
  $linkjs = "<script type='text/javascript' src='./html/js/metodo.js'></script>";
  $linkcss = "<link rel='stylesheet' type='text/css' href='./html/css/metodo.css'></link>";
  require("./html/metodos_lista.php");
}

?>
