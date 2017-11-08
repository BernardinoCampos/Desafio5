<?php

function formataSegundos($seg) {
	$locale_info = localeconv();

	if (Is_Float($seg))
		$msec = sprintf("%s%03d",$locale_info['decimal_point'],floor(($seg - floor($seg))*1000));
	else
		$msec = "";

	if ($seg<60)
		return (sprintf("%2d%s s",$seg%60,$msec));
	else if ($seg>60 && $seg<(60*60))
		return (sprintf("%2d:%02d%s",floor($seg/60),$seg%60,$msec));
	else if ($seg>(60*60))
		return (sprintf("%2d:%02d:%02d%s",floor($seg/(60*60)),(floor($seg/60)%60),$seg%60,$msec));
}

function randNewline() {
	if (mt_rand(1,5)==3)
		return "\n";
}

function geraJson($nome, &$funcionarios, &$areas) {
	$fd = popen("/usr/bin/p7zip > $nome.7z","w");
	fwrite($fd,"{\"funcionarios\":[\n");
	$first=true;
	foreach($funcionarios as $func) {
		if ($first)
			$first=False;
		else
			fwrite($fd,',');
		fwrite($fd,"{".randNewline().'"id":'.$func['id'].','.randNewline().'"nome":"'.$func['nome'].'",'.randNewline().'"sobrenome":"'.$func['sobrenome'].'",'.randNewline().'"salario":'.$func['salario'].','.randNewline().'"area":"'.$func['area'].'"'.randNewline().'}');
	}
	fwrite($fd,"],\"areas\":[\n");
	$first=true;
	foreach($areas as $area) {
		if ($first)
			$first=False;
		else
			fwrite($fd,',');
		fwrite($fd,"{".randNewline().'"codigo":"'.$area['codigo'].'",'.randNewline().'"nome":"'.$area['nome'].'"'.randNewline().'}');
	}
	fwrite($fd,']}');
}

$inicio = microtime(True);

$areas = [
			["codigo"=>"CC","nome"=>"Controladoria Central"],
			["codigo"=>"DC","nome"=>"Departamento Comercial"],
			["codigo"=>"DJ","nome"=>"Departamento Jurídico"],
			["codigo"=>"ES","nome"=>"Engenharia Social"],
			["codigo"=>"GA","nome"=>"Gestão de Ativos"],
			["codigo"=>"GT","nome"=>"Governança de TI"],
			["codigo"=>"ME","nome"=>"Manutenção Elétrica"],
			["codigo"=>"MH","nome"=>"Manutenção Hidráulica"],
			["codigo"=>"MM","nome"=>"Manutenção Mecânica"],
			["codigo"=>"MP","nome"=>"Manutenção Predial"],
			["codigo"=>"PE","nome"=>"Pagadoria Externa"],
			["codigo"=>"PI","nome"=>"Pagadoria Interna"],
			["codigo"=>"PM","nome"=>"Produção Multimídia"],
			["codigo"=>"PP","nome"=>"Produção de Panfletos"],
			["codigo"=>"PV","nome"=>"Programação Visual"],
			["codigo"=>"RH","nome"=>"Recursos Humanos"],
			["codigo"=>"DS","nome"=>"Desenvolvimento de Software"],
			["codigo"=>"GS","nome"=>"Gerenciamento de Software"],
			["codigo"=>"UD","nome"=>"Designer de UI/UX"]
		];

	$firstNames = explode("\n",file_get_contents("FirstNames.txt", 'r'));
	$lastNames  = explode("\n",file_get_contents("LastNames.txt", 'r'));

	$aa=0;
	$fatores = ["10K"=>10000,"50K"=>50000,"100K"=>100000,"250K"=>250000,"500K"=>500000,"1M"=>1000000,"2M"=>2000000,"3M"=>3000000,"5M"=>5000000,"8M"=>8000000,"12M"=>12000000,"15M"=>15000000,"20M"=>20000000,"25M"=>25000000,"30M"=>30000000];
	foreach($firstNames as $nome)
		foreach ($lastNames as $sobrenome) {
			$funcionarios[] = ['id'=>$aa,'nome'=>$nome, 'sobrenome'=>$sobrenome, 'salario'=>number_format(mt_rand(100,10000000)/100,2,".",""), 'area'=>$areas[mt_rand(1,18)]['codigo']];

			if ($key=array_search($aa,$fatores)) {
				geraJson("Funcionarios-$key.json",$funcionarios,$areas);
				echo "Gerado Funcionarios-$key.json em ".Trim(formataSegundos(microtime(True)-$inicio))."\n";
			}

			$aa++;
		}
