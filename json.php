<?php

	$var = json_decode(file_get_contents($argv[1], "r"),true);

	$cont['global'] = 0;
	$acum['global'] = 0;
	$numFunc = [];
	$sobrenome = [];

	foreach ($var['areas'] as $value)
			$areas[$value['codigo']] = $value['nome'];

	foreach($var['funcionarios'] as $func) {
		if (!IsSet($maior['global']) || $func['salario'] > $maior['global'][0]['salario'])
			$maior['global']=[$func];
 		else if ($func['salario'] == $maior['global'][0]['salario'])
			$maior['global'][]=$func;

		if (!IsSet($menor['global']) || $func['salario'] < $menor['global'][0]['salario'])
			$menor['global']=[$func];
		else if ($func['salario'] == $menor['global'][0]['salario'])
			$menor['global'][]=$func;

		if (!IsSet($maior[$func['area']]) || $func['salario'] > $maior[$func['area']][0]['salario'])
			$maior[$func['area']]=[$func];
 		else if ($func['salario'] == $maior[$func['area']][0]['salario'])
			$maior[$func['area']][]=$func;

		if (!IsSet($menor[$func['area']]) || $func['salario'] < $menor[$func['area']][0]['salario'])
			$menor[$func['area']]=[$func];
		else if ($func['salario'] == $menor[$func['area']][0]['salario'])
			$menor[$func['area']][]=$func;

		if (!IsSet($sobrenome[$func['sobrenome']]) || $func['salario'] > $sobrenome[$func['sobrenome']][0]['salario'] )
			$sobrenome[$func['sobrenome']] = [$func];
		else if ($func['salario'] == $sobrenome[$func['sobrenome']][0]['salario'])
			$sobrenome[$func['sobrenome']][] = $func;

		@$acum['global']+=$func['salario'];
		@$cont['global']++;
		@$acum[$func['area']]+=$func['salario'];
		@$cont[$func['area']]++;
		@$numFunc[$func['area']]++;
		@$sNome[$func['sobrenome']]++;
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
