<?PHP echo $linkcss;?>
<div class='atualizacoes_lista'>
   <div class='head'> Atualiza&ccedil;&otilde;es </div>
<?PHP if (count($lista) == 0) { ?>
   <div class='info_ok'>Todos os seus dados est&atilde;o atualizados!</div>
<?PHP } else {
	$total=0;
	foreach($lista as $l) $total += $l['total'];
?>
<?PHP }?>
   <div class='items_titulos'>
     <div class='margin'>
	<div class='item_info_nome'>M&eacute;todo</div>
	<div class='item_info_total'>Total</div>
	<div class='item_acao_atualizar'>Atualizar</div>
     </div>
   </div>
   <div class='lista_items'>

<?PHP foreach($lista as $servico=>$info) { ?>
   <div class='lista_item'>
	<div class='item_info_nome'> <?PHP echo $info['svnome'];?> </div>
	<div class='item_info_total'> <?PHP echo $info['total'];?> </div>
	<div class='item_acao_atualizar'><a href='./atualizacoes.php?action=atualizar&svid=<?PHP echo $info['svid'];?>'>Atualizar</a></div>
   </div>
<?PHP } ?>
   </div>
   <div style='width:244px;margin-right: 30px; margin-left: 40px;margin-top: 7px;'>
   <div style='width:200px;height:22px;float: left;'>
     <div style='height:100%;float:left;background-color: #32a932; width: <?PHP echo ($pct_tupok * 2);?>px;'> &nbsp; </div>
     <div style='height:100%;float:left;background-color: #aa3232; width: <?PHP echo (200 - ($pct_tupok * 2));?>px;'> &nbsp; </div>
   </div>
   <div style='width: 40px;float:right; margin-left: 2px;'> <?PHP echo $pct_tupok;?>% </div>
   </div>
   <div class='info_aviso'> <?PHP if (isset($total) AND $total > 0) { echo $total." Itens precisando de atualiza&ccedil;&atilde;o!"; } ?></div>
</div>
