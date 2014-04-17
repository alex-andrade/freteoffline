<?PHP echo $linkcss;?>
<?PHP echo $linkjs;?>
<div class='metodo_form' id='metodo_form'>
 <form method='post' name='metodo' id='metodo' action='./metodo.php'>
 <div id="formtit_add" class='form_tit'>Adicionar novo M&eacute;todo de Servi&ccedil;o</div>
 <div id="formtit_edit" class='form_tit'>Alterar Dados do M&eacute;todo <span id='mnome_editinfo'></span></div>
<input type=hidden name='metodo_id' id='metodo_id' value='0'>
<input type=hidden name='action' id='metodo_action' value='lista'>
 <div class='form_i'> Nome: <input class='textI' type=text name=mnome id=mnome value=''></div>
 <div class='form_i'> C&oacute;digo: <input class='textI' type=text name=mcodigo id=mcodigo value=''></div>
 <div class='form_i'><div style='width: 100%;text-align:left;cursor: pointer;' id='metodo_trocaAtualizadorConfig' onClick='metodo_trocaAtualizadorConfig();'>[+]&nbsp;Configura&ccedil;&otilde;es do Atualizador</div></div>
 <div id='config_atualizador' class='config_atualizador normal'>
  <div class='form_i'> <input type=checkbox id='usasenha' name='usasenha' value='1' onClick='metodo_trocaFormUsaSenha();'> Com Senha/Contrato </div>
  <div id='metodo_usasenha'>
	   <div class='form_i'> Senha: <input type=text class='textI' name='senha' id='senha' value='' /></div>
   <div class='form_i'> Código da Empresa: <input type=text class='textI' name='cod_empresa' id='cod_empresa' value='' /></div>
  </div>

  <div class='form_i' style='width:100%;height:22px;text-align:left;'>
	<div style='width:34px;float:left;height:100%;'>Usar</div>
	<div style='width:169px;float: right;height:100%;'>
	  <input type=radio name=metodotipoconfig class='radio_tipoconfig' id=metodotipoconfig_global value='global' onClick="metodo_trocaTipoConfig('global');" checked> Global ou
	  <input type=radio name=metodotipoconfig class='radio_tipoconfig' id=metodotipoconfig_metodo value='metodo' onClick="metodo_trocaTipoConfig('metodo');"> M&eacute;todo
	</div>
  </div>
  <div id='ca_global' class='metodo_ca_form'>
    <div class='form_i' style='height: 52px;'>URL:<span style='color:#000;'><?PHP echo $configAtualiza['ws_url']; ?></span></div>
    <div class='form_i'>Skin XML:<span style='color:#000;'><?PHP echo htmlentities($configAtualiza['skin_xml']); ?></span></div>
    <div class='form_i'>Frequ&ecirc;ncia do Atualizador:<span style='color:#000;'><?PHP echo $configAtualiza['frequencia']; ?></span></div>
    <div class='form_i'>Limite de execu&ccedil;&atilde;o:<span style='color:#000;'><?PHP echo $configAtualiza['limite_exec']; ?></span></div>
  </div>
  <div id='ca_metodo' class='metodo_ca_form'>
    <div class='form_i'> URL: <input type='text' name='ws_url' id='ws_url' class='textI' value='' /> </div>
    <div class='form_i'> Skin XML: <input type='text' name='skinxml' id='skin_xml' class='textI' value='' /> </div>
    <div class='form_i'> Frequ&ecirc;ncia: <input type='text' name='frequencia' id='frequencia' class='textI' value='' /> </div>
    <div class='form_i'> Limite de execu&ccedil;&atilde;o: <input type='text' name='limite' id='limite' class='textI' value='' /> </div>
  </div>
 </div>
 <div class='acoes'>
    <div class='acao'><input type=button class='cancelar' value='Cancelar' onClick='fecha_metodo_form();'></div>
    <div class='acao'><input class='adicionar' type=button value='Salvar' onClick='envia_novo_metodo();'></div>
 </div>
 </form>
</div>
<div class='metodos_lista'>
	<div class='head'> M&eacute;todos de Servi&ccedil;o <span id='add_metodo' onClick='abre_metodo_addform();'><img src='./html/images/add.png' width=26 height=26 border=0 /></span> </div>
   <div class='items_titulos'>
	<div class='margin'>
	  <div class='info_nome'>M&eacute;todo</div>
	  <div class='acoes'>A&ccedil;&otilde;es</div>
	</div>
   </div>
   <div class='lista_items'>
<?PHP foreach($lista as $id=>$info) { ?>
   <div class='lista_item'>
	<div class='info_nome'> <?PHP echo $info['nome'];?> </div>
	<div class='acoes'>
	   <div class='editar'><span><img onClick="abre_metodo_editform('<?PHP echo $id;?>');" src='./html/images/Tests-48.png' width=20 height=20 border=0 /></span></div>
	   <div class='excluir'><span><img onClick="remove_metodo('<?PHP echo $id;?>','<?PHP echo $info['nome'];?>');" src='./html/images/rem.jpg' width=20 height=20 border=0 /></span></div>
	</div>
   </div>
<?PHP } ?>
   </div>
   <div class='info_titulo'> <?PHP echo count($lista);?> M&eacute;todos de Servi&ccedil;o </div>
</div>
