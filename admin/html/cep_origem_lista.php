<?PHP echo $linkcss;?>
<?PHP echo $linkjs;?>

<div class='corigem_editform' id='corigem_editform'>
 <div class='form_tit'>Alterar o CEP <span id='cepinfo'></span> Para:</div>
<input type=hidden name=co_ed_id id=co_ed_id value='0'>
 <div class='form_i'><input type=text name=edceporigem id=edceporigem> <input class='editar' type=button value='Salvar Altera&ccedil;&atilde;o' onClick='salva_edceporigem();'></div>
 <div class='form_cancelar'><input type=button class='cancelar' value='Cancelar' onClick='fecha_ceporigem_editform();'></div>
</div>

<div class='corigem_addform' id='corigem_addform'>
 <div class='form_tit'>Adicionar novo CEP de Origem</div>
 <div class='form_i'><input type=text name=nceporigem id=nceporigem> <input class='adicionar' type=button value='Adicionar' onClick='envia_novo_ceporigem();'></div>
 <div class='form_cancelar'><input type=button class='cancelar' value='Cancelar' onClick='fecha_corigem_addform();'></div>
</div>
<div class='corigem_lista'>
   <div class='head'> CEPs De Origem <span id='add_ceporigem' onClick='abre_corigem_addform();'><img src='./html/images/add.png' width=26 height=26 border=0 /></span></div>
   <div class='items_titulos'>
    <div class='margin'>
	<div class='info_nome'>CEP</div>
	<div class='info_total'>Total</div>
	<div class='acoes'>A&ccedil;&otilde;es</div>
    </div>
   </div>
   <div class='lista_items'>
<?PHP foreach($lista as $corigem=>$info) { ?>
   <div class='lista_item'>
	<div class='info_nome'><?PHP echo $corigem;?> </div>
	<div class='info_total'><?PHP echo $info['total'];?></div>
	<div class='acoes'>
	   <div class='editar'><span></span><img src='./html/images/Tests-48.png' onClick='abre_ceporigem_editform("<?PHP echo $corigem;?>");' width=20 height=20 border=0/ ></div>
	   <div class='excluir'><span></span><img src='./html/images/rem.jpg' onClick='remove_ceporigem("<?PHP echo $corigem;?>");' width=20 height=20 border=0 /></div>
	</div>
   </div>
<?PHP } ?>
   </div>
   <div class='info_titulo'> <?PHP echo count($lista); if (count($lista) == 1) echo " CEP"; else echo " CEPs";?> de Origem</div>
</div>
