<?php

	ini_set('error_reporting', 0);
	ini_set('memory_limit', -1);
	ini_set('zend.enable_gc', 0);

	$var = json_decode(file_get_contents($argv[1], "r"),true);

	$cont['global'] = 0;
	$acum['global'] = 0;
	$numFunc = [];
	$sobrenome = [];

	foreach ($var['areas'] as $value)
		$areas[$value['codigo']] = $value['nome'];

	$menor['global'][0]['salario']=99999999;

	foreach($var['funcionarios'] as $func) {

		$salario = (float) $func['salario'];
		$area = $func['area'];
		$sname = $func['sobrenome'];

		if ($salario > $maior['global'][0]['salario'])
			$maior['global']=[$func];
 		else if ($salario == $maior['global'][0]['salario'])
			$maior['global'][]=$func;

		if ($salario < $menor['global'][0]['salario'])
			$menor['global']=[$func];
		else if ($salario == $menor['global'][0]['salario'])
			$menor['global'][]=$func;

		if ($salario > $maior[$area][0]['salario'])
			$maior[$area]=[$func];
 		else if ($salario == $maior[$area][0]['salario'])
			$maior[$area][]=$func;

		if (!IsSet($menor[$area]) || $salario < $menor[$area][0]['salario'])
			$menor[$area]=[$func];
		else if ($salario == $menor[$area][0]['salario'])
			$menor[$area][]=$func;

		if ($salario > $sobrenome[$sname][0]['salario'] )
			$sobrenome[$sname] = [$func];
		else if ($salario == $sobrenome[$sname][0]['salario'])
			$sobrenome[$sname][] = $func;

		@$acum['global']+=$salario;
		@$cont['global']++;
		@$acum[$area]+=$salario;
		@$cont[$area]++;
		@$numFunc[$area]++;
		@$sNome[$sname]++;
	}

	asort($numFunc);

	foreach($maior as $key=>$value) {
		if ($key=='global') {
			foreach($maior[$key] as $func)
				echo "global_max|{$func['nome']} {$func['sobrenome']}|".number_format($maior[$key][0]['salario'],2,".","")."\n";
			foreach($menor[$key] as $func)
				echo "global_min|{$func['nome']} {$func['sobrenome']}|".number_format($menor[$key][0]['salario'],2,".","")."\n";
			echo "global_avg|".number_format($acum[$key]/$cont[$key],2,".","")."\n";
		}
		else {
			foreach($maior[$key] as $func)
				echo "area_max|{$areas[$key]}|{$func['nome']} {$func['sobrenome']}|".number_format($maior[$key][0]['salario'],2,".","")."\n";
			foreach($menor[$key] as $func)
				echo "area_min|{$areas[$key]}|{$func['nome']} {$func['sobrenome']}|".number_format($menor[$key][0]['salario'],2,".","")."\n";
			echo "area_avg|{$areas[$key]}|".number_format($acum[$key]/$cont[$key],2,".","")."\n";
		}
	}

	reset($numFunc);
	$total = current($numFunc);
	while ($total==current($numFunc)) {
		echo "least_employees|".$areas[key($numFunc)]."|".current($numFunc)."\n";
		next($numFunc);
	}

	end($numFunc);
	$total = current($numFunc);
	while ($total==current($numFunc)) {
		echo "most_employees|".$areas[key($numFunc)]."|".current($numFunc)."\n";
		prev($numFunc);
	}

	foreach ($sobrenome as $key=>$value) {
		if ($sNome[$key]==1)	continue;

		foreach($sobrenome[$key] as $func)
			echo "last_name_max|$key|{$func['nome']} {$func['sobrenome']}|".number_format($value[0]['salario'],2,".","")."\n";
	}
