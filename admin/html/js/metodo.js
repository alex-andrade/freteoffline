function abre_metodo_addform() {
	document.getElementById("metodo_id").value='0';
	document.getElementById("metodo_action").value="add";
	document.getElementById("metodo_form").style.display="block";
	$("#mnome").val("");
	$("#mcodigo").val("");

	$("#usasenha").attr("checked",false);
	metodo_trocaFormUsaSenha();
	$("#metodotipoconfig_global").checked=true;
	metodo_trocaTipoConfig("global");
	$("#config_atualizador").hide();
	$("#metodo_form").removeClass("metodo_form_tall");
	
	$("#formtit_add").show();
	$("#formtit_edit").hide();
}
function envia_novo_metodo() {
	var mnome = document.getElementById("mnome").value;
	var mcod = document.getElementById("mcodigo").value;

	if (mnome != "" && mcodigo != "") {
		document.getElementById('metodo').submit();
	}
	else {
		alert("Dados inválidos. Informe o nome e o código do método a ser adicionado!");
	}
}
function remove_metodo(id, nome) {
	if (confirm("Deseja remover o Método "+nome+" do banco de dados ?")) {
		document.location.href='./metodo.php?action=rem&id='+id;
	}
}

function metodo_trocaAtualizadorConfig() {
	var ca = document.getElementById("config_atualizador");
	var display_ca = ca.style.display;
	if (display_ca == "block") {
		ca.style.display='none';
		$("#metodo_form").removeClass("metodo_form_tall");
		$("#metodo_form").removeClass("metodo_form_xtall");
	}
	else if (display_ca == "none" || display_ca == "") {
		$(".radio_tipoconfig").each(function() {
			if (this.checked) {
				var aux = this.id.substring(17);
				$(".metodo_ca_form").hide();
				$("#ca_"+aux).show();
			}
		});
		ca.style.display='block';
		$("#metodo_form").addClass("metodo_form_tall");
	}
}

function metodo_trocaTipoConfig(tipo) {
	if (tipo == 'global') {
		$("#ca_global").show();
		$("#ca_metodo").hide();
	}
	else if (tipo == 'metodo') {
		$("#ca_global").hide();
		$("#ca_metodo").show();
	}
}

function abre_metodo_editform(id) {
	document.getElementById("metodo_id").value=id;
	document.getElementById("metodo_action").value="edit";
	document.getElementById("metodo_form").style.display="block";
	$("#formtit_edit").show();
	$("#formtit_add").hide();

	$.ajax({
		type: "GET",
		url: "./metodo.php?action=dadosedita&id="+id,
		dataType: "xml",
		success: function(xml) {
			$(xml).find("DadosEdita").each(function(){
				$("#mnome").val($(this).find("Nome").text());
				$("#mnome_editinfo").html($(this).find("Nome").text());
				$("#mcodigo").val($(this).find("Codigo").text());
				var usasenha = $(this).find("usasenha").text();
				if (usasenha == "1") {
					document.getElementById("usasenha").checked=true;
					metodo_trocaFormUsaSenha();
					$("#senha").val($(this).find("senha").text());
					$("#cod_empresa").val($(this).find("cod_empresa").text());
				}
				else {
					document.getElementById("usasenha").checked=false;
					metodo_trocaFormUsaSenha();
				}
				var tipoconfig = $(this).find("TipoConfig").text();
				if (tipoconfig == "") tipoconfig = "global";
				$(".radio_tipoconfig").each(function() {
					if (this.value == tipoconfig) {
						this.checked=true;
						metodo_trocaTipoConfig(tipoconfig);
					}
					else {
						$("#ca_"+this.value).hide();
						this.checked=false;
					}
				});

				if (tipoconfig == 'metodo') {
					$("#ws_url").val($(this).find("ws_url").text());
					$("#skinxml").val($(this).find("skinxml").text());
					$("#frequencia").val($(this).find("frequencia").text());
					$("#limite").val($(this).find("limite").text());
				}
			});
		}
	});
}
function fecha_metodo_form() {
	if ($("#metodo_action").val() == "edit") {
	 $("#mnome").val("");
	 $("#mcodigo").val("");
	 document.getElementById("metodotipoconfig_global").checked=true;
	 $("#ca_global").show();
	 $("#ca_metodo").hide();
	 $("#ws_url").val("");
	 $("#skinxml").val("");
	 $("#frequencia").val("");
	 $("#limite").val("");
	}
	document.getElementById("metodo_form").style.display="none";
}

function metodo_trocaFormUsaSenha() {
	var obj = document.getElementById("usasenha");
	if (obj.checked == true) {
		$("#metodo_usasenha").show();
		//$("#metodo_form").removeClass("metodo_form_tall");
		//$("#metodo_form").addClass("metodo_form_xtall");
		$("#config_atualizador").removeClass("normal");
		$("#config_atualizador").addClass("tall");
	}
	else {
		$("#metodo_usasenha").hide();
		$("#senha").val("");
		$("#cod_empresa").val("");
		$("#config_atualizador").removeClass("tall");
		$("#config_atualizador").addClass("normal");
	}
}
