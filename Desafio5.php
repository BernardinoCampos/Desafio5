<?php

	ini_set('error_reporting', 0);
	ini_set('memory_limit', -1);
	ini_set('zend.enable_gc', 0);
	#ini_set('xdebug.profiler_output_dir','/Storage/Desafio5/');
	#ini_set('xdebug.profiler_enable',1);

	function echoStr($str) {
		echo $str;
	}

	if (strlen($argv[1])<10)		die ("\nFaltou o Arquivo\n\n");

	$var = json_decode(file_get_contents($argv[1], "r"),true);

	$cont['gl'] = 0;
	$acum['gl'] = 0;
	$numFunc = [];
	$sobrenome = [];

	foreach ($var['areas'] as $value)
		$areas[$value['codigo']] = $value['nome'];

	$megl[0]['salario']=99999999;
	$magl = [];

	foreach($var['funcionarios'] as $func) {

		$salario = (float) $func['salario'];
		$area = $func['area'];
		$sname = $func['sobrenome'];

		$mag = (float)$magl[0]['salario'];
		if ($salario > $mag)
			$magl=[$func];
 		else if ($salario == $mag)
			$magl[]=$func;

		$meg = (float)$megl[0]['salario'];
		if ($salario < $meg)
			$megl=[$func];
		else if ($salario == $meg)
			$megl[]=$func;

		$ma = (float)$maior[$area][0]['salario'];
		if ($salario > $ma)
			$maior[$area]=[$func];
 		else if ($salario == $ma)
			$maior[$area][]=$func;

		$mea = (float)$menor[$area][0]['salario'];
		if (!IsSet($menor[$area]) || $salario < $mea)
			$menor[$area]=[$func];
		else if ($salario == $mea)
			$menor[$area][]=$func;

		$sn = (float)$sobrenome[$sname][0]['salario'];
		if ($salario >$sn )
			$sobrenome[$sname] = [$func];
		else if ($salario == $sn)
			$sobrenome[$sname][] = $func;

		@$agl+=$salario;
		@++$cgl;
		@$acum[$area]+=$salario;
		@++$cont[$area];
		@++$numFunc[$area];
		@++$sNome[$sname];
	}

	asort($numFunc);

	foreach($magl as $func)
		echoStr("global_max|{$func['nome']} {$func['sobrenome']}|".number_format($magl[0]['salario'],2,".","")."\n");
	foreach($megl as $func)
		echoStr("global_min|{$func['nome']} {$func['sobrenome']}|".number_format($megl[0]['salario'],2,".","")."\n");
	echoStr("global_avg|".number_format($agl/$cgl,2,".","")."\n");

	foreach($maior as $key=>$value) {
		foreach($maior[$key] as $func)
			echoStr("area_max|{$areas[$key]}|{$func['nome']} {$func['sobrenome']}|".number_format($maior[$key][0]['salario'],2,".","")."\n");
		foreach($menor[$key] as $func)
			echoStr("area_min|{$areas[$key]}|{$func['nome']} {$func['sobrenome']}|".number_format($menor[$key][0]['salario'],2,".","")."\n");
		echoStr("area_avg|{$areas[$key]}|".number_format($acum[$key]/$cont[$key],2,".","")."\n");
	}

	reset($numFunc);
	$total = current($numFunc);
	while ($total==current($numFunc)) {
		echoStr("least_employees|".$areas[key($numFunc)]."|".current($numFunc)."\n");
		next($numFunc);
	}

	end($numFunc);
	$total = current($numFunc);
	while ($total==current($numFunc)) {
		echoStr("most_employees|".$areas[key($numFunc)]."|".current($numFunc)."\n");
		prev($numFunc);
	}

	foreach ($sobrenome as $key=>$value) {
		if ($sNome[$key]==1)	continue;

		foreach($sobrenome[$key] as $func)
			echoStr("last_name_max|$key|{$func['nome']} {$func['sobrenome']}|".number_format($value[0]['salario'],2,".","")."\n");
	}
