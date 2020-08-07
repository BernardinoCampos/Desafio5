<?php
// php.ini sets
	ini_set('error_reporting', 0);
	ini_set('memory_limit', -1);
	ini_set('zend.enable_gc', 0);
	
	const NL = "\n";

	if(isset($argv[2])) {
		list($p0, $p1) = explode(' ', microtime());
		$t0 = $p0 + $p1;
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
		$ma[$array[3]][0][2] = 0;

		$me[$array[3]] = array();
		$me[$array[3]][0] = array();
		$me[$array[3]][0][2] = 99999999;
	} while($string[$end] != ']');

	function iJson(&$areas, &$string) {
		$end = strpos($string, '[');
		do {
			$ptr = $end;
			$end = strpos($string, '}', ++$ptr);
			$json = substr($string, $ptr, ++$end-$ptr);
			$array = explode('"', $json);
			yield ([$array[5], $array[9], (float)substr($array[12],1,-1), $array[15]]);
		} while($string[$end] != ']');
	}

	$cont[4] = 0;
	$acum[4] = 0;
	$numFunc = [];
	$so = [];

	$ma[4] = [];
	$ma[4][0] = [];
	$ma[4][0][2] = 0;

	$me[4] = [];
	$me[4][0] = [];
	$me[4][0][2] = 99999999;

	$agl = (float)0;
	$cgl = (float)0;

	foreach(iJson($areas, $string) as $f) {
		$s = (float)$f[2];

		$mma = (float)$ma[4][0][2];
		if ($s > $mma)
			@$ma[4]=[$f];
 		else if ($s == $mma)
			@$ma[4][]=$f;

		$mme = (float)$me[4][0][2];
		if ($s < $mme)
			@$me[4]=[$f];
		else if ($s == $mme)
			@$me[4][]=$f;

		$mma = (float)$ma[$f[3]][0][2];
		if ($s > $mma)
			@$ma[$f[3]]=[$f];
 		else if ($s == $mma)
			@$ma[$f[3]][]=$f;

		$mme = (float)$me[$f[3]][0][2];
		if ($s < $mme)
			@$me[$f[3]]=[$f];
		else if ($s == $mme)
			@$me[$f[3]][]=$f;

		$mso = (float)$so[$f[1]][0][2];
		if ($s > $mso)
			@$so[$f[1]] = [$f];
		else if ($s == $mso)
			@$so[$f[1]][] = $f;

		$agl+=$s;
		++$cgl;
		@$acum[$f[3]]+=$s;
		@++$cont[$f[3]];
		@++$numFunc[$f[3]];
		@++$sNome[$f[1]];
	}

	if(isset($argv[2])) {
		list($p0, $p1) = explode(' ', microtime());
		$t1 = $p0 + $p1;
		printf('Time : %.2f'.NL, $t1-$t0);
		die();
	}

	$acum[4] = $agl;
	$cont[4] = $cgl;

	foreach($ma as $key=>$value) {
		if ($key==4) {
			foreach($ma[$key] as $func)
				echo "global_max|{$func[0]} {$func[1]}|".number_format($ma[$key][0][2],2,'.','').NL;
			foreach($me[$key] as $func)
				echo "global_min|{$func[0]} {$func[1]}|".number_format($me[$key][0][2],2,'.','').NL;
			echo 'global_avg|'.number_format($acum[$key]/$cont[$key],2,'.','').NL;
		}
		else {
			if($cont[$key]) {
				foreach($ma[$key] as $func)
					echo "area_max|{$areas[$key]}|{$func[0]} {$func[1]}|".number_format($ma[$key][0][2],2,'.','').NL;
				foreach($me[$key] as $func)
					echo "area_min|{$areas[$key]}|{$func[0]} {$func[1]}|".number_format($me[$key][0][2],2,'.','').NL;
				echo "area_avg|{$areas[$key]}|".number_format($acum[$key]/$cont[$key],2,'.','').NL;
			}
		}
	}

	reset($numFunc);
	$total = current($numFunc);
	while ($total==current($numFunc)) {
		echo 'least_employees|'.$areas[key($numFunc)].'|'.current($numFunc).NL;
		next($numFunc);
	}

	end($numFunc);
	$total = current($numFunc);
	while ($total==current($numFunc)) {
		echo 'most_employees|'.$areas[key($numFunc)].'|'.current($numFunc).NL;
		prev($numFunc);
	}

	foreach ($so as $key=>$value) {
		if ($sNome[$key]==1)	continue;

		foreach($so[$key] as $func)
			echo "last_name_max|$key|{$func[0]} {$func[1]}|".number_format($value[0][2],2,'.','').NL;
	}
