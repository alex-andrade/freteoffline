create table frete (
	id int auto_increment primary key,
	servico varchar(50),
	nome varchar(50),
	regiao varchar(150),
	prazo int,
	peso decimal(8,4),
	valor decimal(8,2),
	cep_origem varchar(50),
	cep_destino_ini int,
	cep_destino_fim int,
	lastupdate datetime,
	cep_dest_ref int
);
 
create table servico (
	id int auto_increment primary key,
	codigo varchar(15),
	nome varchar(80),
	usasenha int NOT NULL DEFAULT '0',
	cod_empresa varchar(40),
	senha varchar(30)
);

create table atualizacoes_config (
	ws_url varchar(250),
	skin_xml text,
	limite_exec int,
	frequencia int,
	tipoconfig varchar(25),
	id_servico int NOT NULL DEFAULT '0',
	ativa int NOT NULL DEFAULT '1'
);

INSERT INTO atualizacoes_config (ws_url,skin_xml,limite_exec,frequencia, tipoconfig) VALUES ('http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?StrRetorno=xml', '', 400, 86400, "global");

INSERT INTO servico (codigo,nome) VALUES ('40010','SEDEX'),('41106','PAC');
