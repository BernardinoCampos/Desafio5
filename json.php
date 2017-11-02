<?php

	function cmp ($a, $b) {
		return strcmp("{$a['nome']} {$a['sobrenome']}","{$b['nome']} {$b['sobrenome']}");
	}

	function showNome($var) {
		$ret = "";

		usort($var,'cmp');
		
		foreach($var as $func)
			$ret.= "{$func['nome']} {$func['sobrenome']},";

		$ret = trim($ret,' ,');

		return $ret;
	}

	$var = json_decode(file_get_contents($argv[1], "r"),true);

	$cont['global'] = 0;
	$acum['global'] = 0;
	$numFunc = [];
	$sobrenome = [];

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
	ksort($sobrenome);

	foreach($maior as $key=>$value) {
		echo "{$key}|MAX|".showNome($maior[$key])."|{$maior[$key][0]['salario']}\n";
		echo "{$key}|MIN|".showNome($menor[$key])."|{$menor[$key][0]['salario']}\n";
		echo "{$key}|AVG|".($acum[$key]/$cont[$key])."\n";
	}

	reset($numFunc);
	echo "LESS_WORKERS|".key($numFunc)."|".current($numFunc)."\n";
	end($numFunc);
	echo "MORE_WORKERS|".key($numFunc)."|".current($numFunc)."\n";

	foreach ($sobrenome as $key=>$value) {
		if ($sNome[$key]==1)	continue;

		echo "$key|".showNome($sobrenome[$key])."|".$value[0]['salario']."\n";
	}
