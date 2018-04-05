<?php
	error_reporting(0);
	if(isset($argv[2])) {
		$array = explode(" ", microtime());
		$t0 = $array[1]+$array[0];
	}

	$areas = [];

	function iJson($filename) {
		Global $areas;

		if(!file_exists($filename))
        		die("Arquivo!");

		$string = file_get_contents($filename);

		$end = strpos($string, '[');
		do {
			$ptr = $end;
			$end = strpos($string, '}', ++$ptr);
			$json = substr($string, $ptr, ++$end-$ptr);

			$array = explode('"', $json);
			$nome = $array[5];
			$sobrenome = $array[9];
			$salario = substr($array[12],1,-1);
			$area = $array[15];

			yield (['no'=>$nome, 'so'=>$sobrenome, 'sa'=>(float)$salario, 'ar'=>$area]);
		} while($string[$end] != ']');

		$end = strpos($string, '[', $end);
		do {
			$ptr = $end;
			$end = strpos($string, '}', ++$ptr);
			$json = substr($string, $ptr, ++$end-$ptr);

			$array = explode('"', $json);
			$areas[$array[3]] = $array[7];
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

	$a = array("A1","A2","A3","CC","D1","D2","D3","DC","DJ","ES","GA","GT","ME","MH","MM","MP","PE","PI","PM","PP","PV","RH","DS","GS","UD");
	foreach($a as $key => $value) {
		$ma[$value] = array();
		$ma[$value][0] = array();
		$ma[$value][0]['sa'] = 0;

		$me[$value] = array();
		$me[$value][0] = array();
		$me[$value][0]['sa'] = 99999999;
	}

	foreach(iJson($argv[1]) as $f) {
		$s = $f['sa'];
		if ($s > $ma['gl'][0]['sa'])
			@$ma['gl']=[$f];
 		else if ($s == $ma['gl'][0]['sa'])
			@$ma['gl'][]=$f;

		if ($s < $me['gl'][0]['sa'])
			@$me['gl']=[$f];
		else if ($s == $me['gl'][0]['sa'])
			@$me['gl'][]=$f;

		if ($s > $ma[$f['ar']][0]['sa'])
			@$ma[$f['ar']]=[$f];
 		else if ($s == $ma[$f['ar']][0]['sa'])
			@$ma[$f['ar']][]=$f;

		if ($s < $me[$f['ar']][0]['sa'])
			@$me[$f['ar']]=[$f];
		else if ($s == $me[$f['ar']][0]['sa'])
			@$me[$f['ar']][]=$f;

		if ($s > $so[$f['so']][0]['sa'] )
			$so[$f['so']] = [$f];
		else if ($s == $so[$f['so']][0]['sa'])
			$so[$f['so']][] = $f;

		@$acum['gl']+=$s;
		@$cont['gl']++;
		@$acum[$f['ar']]+=$s;
		@$cont[$f['ar']]++;
		@$numFunc[$f['ar']]++;
		@$sNome[$f['so']]++;
	}

	if(isset($argv[2])) {
		$array = explode(" ", microtime());
		$t1 = $array[1]+$array[0];
		printf("trav : %.2f\n", $t1-$t0);
		die();
	}
	
	asort($numFunc);

	foreach($ma as $key=>$value) {
		if ($key=='gl') {
			foreach($ma[$key] as $func)
				echo "global_max|{$func['no']} {$func['so']}|".number_format($ma[$key][0]['sa'],2,".","")."\n";
			foreach($me[$key] as $func)
				echo "global_min|{$func['no']} {$func['so']}|".number_format($me[$key][0]['sa'],2,".","")."\n";
			echo "global_avg|".number_format($acum[$key]/$cont[$key],2,".","")."\n";
		}
		else {
			if($cont[$key]) {
				foreach($ma[$key] as $func)
					echo "area_max|{$areas[$key]}|{$func['no']} {$func['so']}|".number_format($ma[$key][0]['sa'],2,".","")."\n";
				foreach($me[$key] as $func)
					echo "area_min|{$areas[$key]}|{$func['no']} {$func['so']}|".number_format($me[$key][0]['sa'],2,".","")."\n";
				echo "area_avg|{$areas[$key]}|".number_format($acum[$key]/$cont[$key],2,".","")."\n";
			}
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

	foreach ($so as $key=>$value) {
		if ($sNome[$key]==1)	continue;

		foreach($so[$key] as $func)
			echo "last_name_max|$key|{$func['no']} {$func['so']}|".number_format($value[0]['sa'],2,".","")."\n";
	}
