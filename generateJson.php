<?php

$inicio = microtime(True);

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

function openJson($key,&$array) {
	$nome = "Funcionarios-{$key}.json";
	$array['fd'] = popen("/usr/bin/p7zip > $nome.7z","w");
	fwrite($array['fd'],"{\"funcionarios\":[\n");
}

function closeJson($key,&$array,&$areas) {
	GLOBAL $inicio;

	if ($array['fd']==NULL)
		return;

	$nome = "Funcionarios-{$key}.json";

	fwrite($array['fd'],"],\n\"areas\":[\n");
	$first=true;
	foreach($areas as $area) {
		if ($first)
			$first=False;
		else
			fwrite($array['fd'],",\n");
		fwrite($array['fd'],"{\"codigo\":\"{$area['codigo']}\",\"nome\":\"{$area['nome']}\"}");
	}
	fwrite($array['fd'],"]}");
	fclose($array['fd']);
	$array['fd']=NULL;

	echo "{$array['size']} ".number_format((microtime(True)-$inicio),4,".","")."\n";
}

function saveJson($arquivos, $funcionarios, &$first) {
	$str = "";
	foreach($funcionarios as $func) {
		if ($first)
			$first=False;
		else
			$str.=',';
		$str.= "{".randNewline().'"id":'.$func['id'].','.randNewline().'"nome":"'.$func['nome'].'",'.randNewline().'"sobrenome":"'.$func['sobrenome'].'",'.randNewline().'"salario":'.$func['salario'].','.randNewline().'"area":"'.$func['area'].'"'.randNewline().'}';
	}
	foreach($arquivos as $arq)
		if ($arq['fd']!=NULL)
			fwrite($arq['fd'],$str);
}

$areas = [
			["codigo"=>"A1","nome"=>"Área 1"],
			["codigo"=>"A2","nome"=>"Área 2"],
			["codigo"=>"A3","nome"=>"Área 3"],
			["codigo"=>"CC","nome"=>"Controladoria Central"],
			["codigo"=>"D1","nome"=>"Departamento 1"],
			["codigo"=>"D2","nome"=>"Departamento 2"],
			["codigo"=>"D3","nome"=>"Departamento 3"],
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

$arquivos = [
				"10K"=>['fd'=>NULL,'size'=>10000],
				"50K"=>['fd'=>NULL,'size'=>50000],
				"100K"=>['fd'=>NULL,'size'=>100000],
				"250K"=>['fd'=>NULL,'size'=>250000],
				"500K"=>['fd'=>NULL,'size'=>500000],
				"1M"=>['fd'=>NULL,'size'=>1000000],
				"2M"=>['fd'=>NULL,'size'=>2000000],
				"3M"=>['fd'=>NULL,'size'=>3000000],
				"5M"=>['fd'=>NULL,'size'=>5000000],
				"8M"=>['fd'=>NULL,'size'=>8000000],
				"12M"=>['fd'=>NULL,'size'=>12000000],
				"15M"=>['fd'=>NULL,'size'=>15000000],
				"20M"=>['fd'=>NULL,'size'=>20000000],
				"25M"=>['fd'=>NULL,'size'=>25000000],
				"30M"=>['fd'=>NULL,'size'=>30000000]
			];

foreach($arquivos as $key=>$arr)
	openJson($key,$arquivos[$key]);

$aa=0;
$first = true;
foreach($firstNames as $nome)
	foreach ($lastNames as $sobrenome) {
		if ($aa%13)
			$funcionarios[$aa++] = ['id'=>$aa,'nome'=>$nome, 'sobrenome'=>$sobrenome, 'salario'=>number_format(mt_rand(100,10000000)/100,2,".",""), 'area'=>$areas[0]['codigo']];
		else if (($aa+1)%13)
			$funcionarios[$aa++] = ['id'=>$aa,'nome'=>$nome, 'sobrenome'=>$sobrenome, 'salario'=>number_format(mt_rand(100,10000000)/100,2,".",""), 'area'=>$areas[1]['codigo']];
		else
			$funcionarios[$aa++] = ['id'=>$aa,'nome'=>$nome, 'sobrenome'=>$sobrenome, 'salario'=>number_format(mt_rand(100,10000000)/100,2,".",""), 'area'=>$areas[mt_rand(2,24)]['codigo']];

		if ($aa%1000==0) {
			saveJson($arquivos, $funcionarios, $first);
			foreach($arquivos as $key=>$arr)
				if ($aa>=$arr['size'])
					closeJson($key,$arquivos[$key],$areas);
			$funcionarios = [];
		}
	}
