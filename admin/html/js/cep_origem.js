function abre_corigem_addform() {
	document.getElementById("corigem_addform").style.display="block";
}
function fecha_corigem_addform() {
	document.getElementById("corigem_addform").style.display="none";
}
function envia_novo_ceporigem() {
	var nco = document.getElementById("nceporigem").value;
	if (nco != "" && parseInt(nco) > 0 && parseInt(nco) < 99999999) {
		document.location.href='./cep_origem.php?action=add&nco='+nco;
	}
	else {
		alert("CEP inválido. Informe o novo CEP corretamente!");
	}
}
function remove_ceporigem(co) {
	if (confirm("Deseja remover o CEP de Origem "+co+" do banco de dados ?")) {
		document.location.href='./cep_origem.php?action=rem&co='+co;
	}
}

function abre_ceporigem_editform(coedid) {
	document.getElementById("co_ed_id").value=coedid;
	document.getElementById("cepinfo").innerHTML=coedid;
	document.getElementById("corigem_editform").style.display="block";
}
function fecha_ceporigem_editform() {
	document.getElementById("co_ed_id").value='0';
	document.getElementById("corigem_editform").style.display="none";
}
function salva_edceporigem() {
	var edco = document.getElementById("edceporigem").value;
	var edcoid = document.getElementById("co_ed_id").value;
	if (edco != "" && parseInt(edco) > 0 && parseInt(edco) < 99999999) {
		document.location.href='./cep_origem.php?action=edit&edco='+edco+'&edcoid='+edcoid;
	}
	else {
		alert("CEP inválido. Informe o novo CEP corretamente!");
	}
}