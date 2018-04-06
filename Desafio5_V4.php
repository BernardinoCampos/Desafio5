<?php
// php.ini sets
	ini_set('error_reporting', 0);
	ini_set('memory_limit', -1);
	ini_set('zend.enable_gc', 0);

	if(isset($argv[2])) {
		$array = explode(' ', microtime());
		$t0 = $array[1]+$array[0];
	}

	$filename = $argv[1];

	$areas = [];
	$ma = [];

	if(!file_exists($filename))
    		die('Arquivo!');

	$string = file_get_contents($filename);

	$end = strpos($string, '[', 20);
	do {
		$ptr = $end;
		$end = strpos($string, '}', ++$ptr);
		$json = substr($string, $ptr, ++$end-$ptr);

		$array = explode('"', $json);
		$areas[$array[3]] = $array[7];
		$ma[$array[3]] = array();
		$ma[$array[3]][0] = array();
		$ma[$array[3]][0]['sa'] = 0;

		$me[$array[3]] = array();
		$me[$array[3]][0] = array();
		$me[$array[3]][0]['sa'] = 99999999;
	} while($string[$end] != ']');

	function iJson() {
		Global $areas, $string;

		$end = strpos($string, '[');
		do {
			$ptr = $end;
			$end = strpos($string, '}', ++$ptr);
			$json = substr($string, $ptr, ++$end-$ptr);

			$array = explode('"', $json);
			$nome = $array[5];
			$sobrenome = $array[9];
			$salario = (float)substr($array[12],1,-1);
			$area = $array[15];

			yield (['no'=>$nome, 'so'=>$sobrenome, 'sa'=>$salario, 'ar'=>$area]);
		} while($string[$end] != ']');
	}

	$cont['gl'] = 0;
	$acum['gl'] = 0;
	$numFunc = [];
	$so = [];

	$ma['gl'] = array();
	$ma['gl'][0] = array();
	$ma['gl'][0]['sa'] = 0;

	$me['gl'] = array();
	$me['gl'][0] = array();
	$me['gl'][0]['sa'] = 99999999;

	$agl = (float)0;
	$cgl = (float)0;

	foreach(iJson() as $f) {
		$s = (float)$f['sa'];

		$mma = (float)$ma['gl'][0]['sa'];
		if ($s > $mma)
			@$ma['gl']=[$f];
 		else if ($s == $mma)
			@$ma['gl'][]=$f;

		$mme = (float)$me['gl'][0]['sa'];
		if ($s < $mme)
			@$me['gl']=[$f];
		else if ($s == $mme)
			@$me['gl'][]=$f;

		$mma = (float)$ma[$f['ar']][0]['sa'];
		if ($s > $mma)
			@$ma[$f['ar']]=[$f];
 		else if ($s == $mma)
			@$ma[$f['ar']][]=$f;

		$mme = (float)$me[$f['ar']][0]['sa'];
		if ($s < $mme)
			@$me[$f['ar']]=[$f];
		else if ($s == $mme)
			@$me[$f['ar']][]=$f;

		$mso = (float)$so[$f['so']][0]['sa'];
		if ($s > $mso)
			@$so[$f['so']] = [$f];
		else if ($s == $mso)
			@$so[$f['so']][] = $f;

		$agl+=$s;
		++$cgl;
		@$acum[$f['ar']]+=$s;
		@++$cont[$f['ar']];
		@++$numFunc[$f['ar']];
		@++$sNome[$f['so']];
	}

	if(isset($argv[2])) {
		$array = explode(' ', microtime());
		$t1 = $array[1]+$array[0];
		printf('trav : %.2f\n', $t1-$t0);
		die();
	}

	$acum['gl'] = $agl;
	$cont['gl'] = $cgl;

	foreach($ma as $key=>$value) {
		if ($key=='gl') {
			foreach($ma[$key] as $func)
				echo "global_max|{$func['no']} {$func['so']}|".number_format($ma[$key][0]['sa'],2,'.','')."\n";
			foreach($me[$key] as $func)
				echo "global_min|{$func['no']} {$func['so']}|".number_format($me[$key][0]['sa'],2,'.','')."\n";
			echo 'global_avg|'.number_format($acum[$key]/$cont[$key],2,'.','')."\n";
		}
		else {
			if($cont[$key]) {
				foreach($ma[$key] as $func)
					echo "area_max|{$areas[$key]}|{$func['no']} {$func['so']}|".number_format($ma[$key][0]['sa'],2,'.','')."\n";
				foreach($me[$key] as $func)
					echo "area_min|{$areas[$key]}|{$func['no']} {$func['so']}|".number_format($me[$key][0]['sa'],2,'.','')."\n";
				echo "area_avg|{$areas[$key]}|".number_format($acum[$key]/$cont[$key],2,'.','')."\n";
			}
		}
	}

	reset($numFunc);
	$total = current($numFunc);
	while ($total==current($numFunc)) {
		echo 'least_employees|'.$areas[key($numFunc)].'|'.current($numFunc)."\n";
		next($numFunc);
	}

	end($numFunc);
	$total = current($numFunc);
	while ($total==current($numFunc)) {
		echo 'most_employees|'.$areas[key($numFunc)].'|'.current($numFunc)."\n";
		prev($numFunc);
	}

	foreach ($so as $key=>$value) {
		if ($sNome[$key]==1)	continue;

		foreach($so[$key] as $func)
			echo "last_name_max|$key|{$func['no']} {$func['so']}|".number_format($value[0]['sa'],2,'.','')."\n";
	}
