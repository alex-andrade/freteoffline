<?PHP
require_once("./inc/Global.php");

$linkcss="";

// Atualiza
if ($action == "atualizar") {
   $sv_id = $_REQUEST['svid'];
   $ca_metodo_id_Where = "";
   $sql = "SELECT sv.codigo, sv.usasenha, sv.senha, sv.cod_empresa, ac.tipoconfig, ac.ws_url, ac.limite_exec, ac.frequencia FROM servico AS sv
		LEFT JOIN atualizacoes_config AS ac ON (ac.id_servico=sv.id OR (ac.id_servico='0' AND ac.tipoconfig='global'))
		WHERE sv.id='".$sv_id."'";
   $res = mysql_query($sql) or die(mysql_error());
   if ( ($row=mysql_fetch_object($res)) ) {
	$nCdServico = $row->codigo;
	$tipoconfig = $row->tipoconfig;
	#if ($tipoconfig == "metodo") $ca_metodo_id_Where = " AND id_servico='".$sv_id."' ";
	#elseif ($tipoconfig == "") $tipoconfig = "global";

	$url = $row->ws_url;
	$limite = $row->limite_exec;
	$frequencia = $row->frequencia;

	$usaSenha = $row->usasenha;
	if ($usaSenha == 1) {
		$sDsSenha = $row->senha;
		$nCdEmpresa = $row->cod_empresa;
	}
	else $nCdEmpresa = $sDsSenha = 0;
   }
   else {
	echo "<script type='text/javascript'> alert('Requisição inválida!'); document.location.href='./index.php'; </script>";
	exit;
   }

/***
   $sql = "SELECT * FROM atualizacoes_config WHERE tipoconfig='".$tipoconfig."' $ca_metodo_id_Where";
   $res = mysql_query($sql);
   if ( ($row=mysql_fetch_object($res)) ) {
	$url = $row->ws_url;
	$limite = $row->limite_exec;
	$frequencia = $row->frequencia;
   }
   else {
	echo "<script type='text/javascript'> alert('Requisição inválida!'); document.location.href='./index.php'; </script>";
	exit;
   }
***/
   # Medidas minimas
   #  ja que o custo da dimensao é calculada atraves de formula substituindo o peso pelo pesocubico
   $nVlComprimento=16;
   $nVlAltura=2;
   $nVlLargura=11;

   // confere ceps de origem para esta atualizacao, e faz uma execucao por cep
   $sql = "SELECT * FROM frete WHERE (lastupdate IS NULL OR lastupdate < ".(mktime() - $frequencia).") AND servico='".$nCdServico."' Limit 0,".$limite;
   $res = mysql_query($sql);
   $total_fail=$total_updated=0;
   while ( ($row=mysql_fetch_object($res)) ) {
	$cep_origem = $row->cep_origem;
	$cep_destino = $row->cep_dest_ref;
	$peso = $row->peso;
	$url_d = $url."&nCdEmpresa=".$nCdEmpresa."&sDsSenha=".$sDsSenha."&nCdFormato=1&nCdServico=".$nCdServico."&nVlComprimento=".$nVlComprimento."&nVlAltura=".$nVlAltura."&nVlLargura=".$nVlLargura."&sCepOrigem=".$cep_origem."&sCdMaoPropria=N&sCdAvisoRecebimento=N&nVlValorDeclarado=0&nVlPeso=".$peso."&sCepDestino=".$cep_destino;

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url_d);
	curl_setopt($ch, CURLOPT_HEADER, 0);

	ob_start();
	curl_exec($ch);
	curl_close($ch);
	$content = ob_get_contents();
	ob_end_clean();

	if( ($xml = new SimpleXMLElement($content)) ) 
	  foreach($xml->cServico as $servico) {
	   if ($servico->Erro == "0") {
		$sql = "UPDATE frete SET
			 valor='".str_replace(",",".",$servico->Valor)."',
			 prazo='".$servico->PrazoEntrega."',
			 lastupdate=NOW()
			WHERE id='".$row->id."'
		";
		if (mysql_query($sql)) {
			$total_updated++;
		}
		else $total_fail++;
	   } else {
		echo "<script type='text/javascript'>
			alert('".$servico->MsgErro."');
			document.location.href='./index.php';
		      </script>
		";
		exit;
	   }
	  }
	else $erro = "Correios fora do ar ?";
   }

if (!isset($erro) OR $erro = "") $msg = $total_updated." registros atualizados.";
else $msg = $erro;
   echo "<script type='text/javascript'>
		alert('".$msg."');
		document.location.href='./index.php';
	</script>";
   exit;

}
// Lista
elseif ($action == "lista" OR $action == "default") {
  $sql = "SELECT frequencia FROM atualizacoes_config";
  $res = mysql_query($sql) or die(mysql_error());
  if ( ($row=mysql_fetch_object($res)) ) {
	$frequencia = $row->frequencia;
  }
  else $frequencia= 2 * (24 * 60 * 60);


  $sql = "SELECT servico, count(f.valor) as tserv,sv.nome AS svnome, sv.id AS svid FROM frete AS f, servico AS sv WHERE (sv.codigo=f.servico) AND (f.lastupdate is NULL OR f.lastupdate<=".(mktime() - $frequencia).") GROUP By f.servico";
  $res = mysql_query($sql);
  $lista = Array();
  while ( ($row=mysql_fetch_object($res)) ) {
	$lista[$row->servico] = Array();
	$lista[$row->servico]['svnome'] = $row->svnome;
	$lista[$row->servico]['svid'] = $row->svid;
	$lista[$row->servico]['total'] = $row->tserv;
  }

  // codigo p/ barra de progresso
  $sql = "SELECT count(id) AS total FROM frete";
  $res = mysql_query($sql);
  if ( ($row=mysql_fetch_object($res)) ) {
	$total_reg = $row->total;
  }
  $sql = "SELECT count(id) AS total FROM frete WHERE (lastupdate IS NULL OR lastupdate <= ".(mktime() - $frequencia).")";
  $res = mysql_query($sql);
  if ( ($row=mysql_fetch_object($res)) ) {
	$total_notup = $row->total;
  }
  $total_upok = $total_reg - $total_notup;
  $pct_tupok = floor(($total_upok / $total_reg) * 100);

  $linkcss = "<link rel='stylesheet' type='text/css' href='./html/css/atualizacoes.css'></link>";
  require("./html/atualizacoes_lista.php");
}

?>
