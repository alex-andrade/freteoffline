<?PHP

include("./config.php");
include("./frete.lib.php");

$valores = Array();

$peso = $_REQUEST['nVlPeso'];
if ($peso < 1) $peso = "0.3";
else $peso = ceil($peso);

$cod_tipoFrete = explode(",", $_REQUEST['nCdServico']);

$cep_origem = $_REQUEST['sCepOrigem'];
$cep_destino = $_REQUEST['sCepDestino'];
if(isset($_REQUEST['nVlValorDeclarado'])) $valorDeclarado = $_REQUEST['nVlValorDeclarado'];
else $valorDeclarado = 0;
if(isset($_REQUEST['sCdAvisoRecebimento'])) $avisoRecebimento = $_REQUEST['sCdAvisoRecebimento'];
else $avisoRecebimento = 0;
if(isset($_REQUEST['sCdMaoPropria'])) $maoPropria = $_REQUEST['sCdMaoPropria'];
else $maoPropria = 0;

// Dimensoes
if(isset($_REQUEST['nVlAltura'])) $altura = $_REQUEST['nVlAltura'];
else $altura = 2;
if(isset($_REQUEST['nVlLargura'])) $largura = $_REQUEST['nVlLargura'];
else $largura = 11; 
if(isset($_REQUEST['nVlComprimento'])) $comprimento = $_REQUEST['nVlComprimento'];
else $comprimento = 16;

$soma_d = $altura + $largura + $comprimento;
$peso_cubico = ($altura * $largura * $comprimento) / 4800;

// limite da soma
if ($soma_d > 150) {
	// retornar erro, ultrapassou limite da soma das dimensoes
	echo "<br> <b> Erro: Soma das medidas ultrapassou limite 150cm</b><br>";
	exit;
}
// tamanho limite de cada dimensao
if ($altura > 60 || $largura > 60 || $comprimento > 60) {
	// retornar erro, maior que 60
	echo "<br> <b> Erro: Nenhuma medida pode ser maior que 60cm</b><br>";
	exit;
}

# Loop por tipo de frete
foreach($cod_tipoFrete as $cod_frete) {
	if ($cod_frete == "41106") {
		// Calculo de acrescimo por dimensao, para o PAC, caso o peso cubico seja maior que o peso
		if ($peso_cubico > $peso) $peso = $peso_cubico;
	}

	if ( ($tval = calcula_frete(Array(
					"cep_origem"=>"$cep_origem",
					"cep_destino"=>"$cep_destino",
					"peso"=>"$peso",
					"cod_frete"=>"$cod_frete"
				)) ) ) {

		$peso_valor = $tval['vtotal'] - $tval['valor_minimo'];
		$acrescimo = $peso_valor;
		// Valor do Valor Declarado
		if ($valorDeclarado > 50) {
			$vd_valor = $valores[$cod_frete]['valorDeclarado'] = ($valorDeclarado - 50) * 0.01;
		}
		else $vd_valor = $valores[$cod_frete]['valorDeclarado'] = 0;

		// Valor Aviso de Recebimento
		if ($avisoRecebimento == "S") $ar_valor = "2.70";
		else $ar_valor = "0";

		// Valor Mao Propria
		if ($maoPropria == "S") $mp_valor = "3.50";
		else $mp_valor = "0";

		// Dados do resultado
		$valores[$cod_frete]['prazo'] = $tval['prazo'];
		$valores[$cod_frete]['peso_cubico'] = $peso_cubico;
		$valores[$cod_frete]['valorMaoPropria'] = $mp_valor;
		$valores[$cod_frete]['valorAvisoRecebimento'] = $ar_valor;
		$valores[$cod_frete]['acrescimo'] = $acrescimo;
		$valores[$cod_frete]['valor'] = $tval['valor'];
		$valores[$cod_frete]['valor_minimo'] = $tval['valor_minimo'];
		$valores[$cod_frete]['vtotal'] = $tval['valor_minimo'] + $acrescimo + $mp_valor + $ar_valor + $vd_valor;
	}
	else $valores[$cod_frete]['valor'] = 0;
}


# OUTPUT
header('Content-type: text/xml');
echo '<?xml version="1.0" encoding="ISO-8859-1" ?>
';
echo '<Servicos>';
foreach($valores as $codfrete=>$vinfo) {
	echo '<cServico>
		<Codigo>'.$codfrete.'</Codigo>
		<Valor>'.$vinfo['vtotal'].'</Valor>
		<ValorMin>'.$vinfo['valor_minimo'].'</ValorMin>
		<AcrescimoDimensao>'.$vinfo['acrescimo_dim'].'</AcrescimoDimensao>
		<AcrescimoPeso>'.$vinfo['acrescimo_peso'].'</AcrescimoPeso>
		<PrazoEntrega>'.$vinfo['prazo'].'</PrazoEntrega>
		<ValorMaoPropria>'.$vinfo['valorMaoPropria'].'</ValorMaoPropria>
		<ValorAvisoRecebimento>'.$vinfo['valorAvisoRecebimento'].'</ValorAvisoRecebimento>
		<ValorValorDeclarado>'.$vinfo['valorDeclarado'].'</ValorValorDeclarado>
		<EntregaDomiciliar>Sim</EntregaDomiciliar>
		<EntregaSabado>Sim</EntregaSabado>
		<Erro>0</Erro>
		<MsgErro></MsgErro>
		</cServico>
	';
}
echo '</Servicos>';

?>
