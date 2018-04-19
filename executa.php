#!/usr/bin/php
<?php

$conf = json_decode(file_get_contents('./ftp.conf'),TRUE);

function putFtpArray(array $arr,string $nome) {
	Global $conf;

	$filename = "/var/tmp/{$nome}";

	file_put_contents($filename, json_encode($arr));

	$fd = ftp_connect($conf['host']);
	$lg = ftp_login($fd, $conf['user'], $conf['passwd']);
	ftp_pasv($fd, false	);
	ftp_put($fd, "/conf/{$nome}", $filename, FTP_BINARY);
	ftp_close($fd);
	unlink($filename);
}

function getFtpArray(string $nome) : array {
	Global $conf;

	$filename = "/var/tmp/{$nome}";

	$fd = ftp_connect($conf['host']);
	$lg = ftp_login($fd, $conf['user'], $conf['passwd']);
	ftp_pasv($fd, false	);
	ftp_get($fd, $filename, "/conf/{$nome}", FTP_BINARY);
	ftp_close($fd);

	$var = json_decode(file_get_contents($filename),TRUE);
	unlink($filename);

	return $var;
}

function sd_square($x, $mean) { return pow($x - $mean,2); }

function stddev(array $array) {
    return sqrt(array_sum(array_map("sd_square", $array, array_fill(0,count($array), (array_sum($array) / count($array)) ) ) ) / (count($array)-1) );
}

$tamanhos['10K'] = ['Hash'=>'967eb7059d62e6d430d67eeb16e45e44','Size'=>   10000];
$tamanhos['50K'] = ['Hash'=>'1c25b8e3d52ff9ae5ec9883570c49d59','Size'=>   50000];
$tamanhos['100K']= ['Hash'=>'fc4caf6d53d265453d62da0983bb3fb5','Size'=>  100000];
$tamanhos['250K']= ['Hash'=>'6e41f0316ee66d9266c1e5d32891b3bf','Size'=>  250000];
$tamanhos['500K']= ['Hash'=>'98fecdfd321a5cd966eefbb9f8b31785','Size'=>  500000];
$tamanhos['1M']  = ['Hash'=>'b9012db943149e069920bf7c3ec49984','Size'=> 1000000];
$tamanhos['2M']  = ['Hash'=>'483cc5423f6502a84c4ec9adc0ce8cbb','Size'=> 2000000];
$tamanhos['3M']  = ['Hash'=>'93912b5d0ffeccc86db7d596f0078115','Size'=> 3000000];
$tamanhos['5M']  = ['Hash'=>'92d5d4b4dd1bf5c965f79053145ae0f2','Size'=> 5000000];
$tamanhos['8M']  = ['Hash'=>'50801387d4d06ed42043ca2325a01122','Size'=> 8000000];
$tamanhos['12M'] = ['Hash'=>'cddb5d244bca76b71e5ee7db95e022e8','Size'=>12000000];
$tamanhos['15M'] = ['Hash'=>'acd7306f4ef82721bc301f488dd59d60','Size'=>15000000];
$tamanhos['20M'] = ['Hash'=>'66d8426057595b172e7a50be8ce65db7','Size'=>20000000];
$tamanhos['25M'] = ['Hash'=>'8aa026b23a51940347335f5b22d0177b','Size'=>25000000];
$tamanhos['30M'] = ['Hash'=>'1c0e814e642c5fd58a2ee3dcd8c9e807','Size'=>30000000];

$solucoes ['Go-SergioCorreia_V2'] 	= ['exec'=>NULL,'language'=>'Go','creator'=>'Sérgio Correia','source'=>'https://github.com/OsProgramadores/op-desafios/blob/master/desafio-05/qrwteyrutiyoup/go'];
$solucoes ['Go-SergioCorreia'] 	= ['exec'=>'./Go-SergioCorreia_V2','language'=>'Go','creator'=>'Sérgio Correia','source'=>'https://github.com/OsProgramadores/op-desafios/blob/master/desafio-05/qrwteyrutiyoup/go'];
$solucoes ['Go-SergioCorreia_V3'] 	= ['exec'=>NULL,'language'=>'Go','creator'=>'Sérgio Correia','source'=>'https://github.com/OsProgramadores/op-desafios/blob/master/desafio-05/qrwteyrutiyoup/go'];
$solucoes ['C-SergioCorreia']		= ['exec'=>'./C-SergioCorreia','language'=>'C','creator'=>'Sérgio Correia','source'=>'https://github.com/OsProgramadores/op-desafios/blob/master/desafio-05/qrwteyrutiyoup/c'];
$solucoes ['Go-MarcoPaganini'] 	= ['exec'=>'./Go-MarcoPaganini','language'=>'Go','creator'=>'Marco Paganini','source'=>'https://github.com/OsProgramadores/op-desafios/tree/master/desafio-05/marcopaganini'];
$solucoes ['Php-Bcampos']			= ['exec'=>'/usr/bin/php Php-Bcampos.php','language'=>'Php','creator'=>'Bernardino Campos','source'=>'https://github.com/BernardinoCampos/Desafio5/blob/master/Desafio5.php'];
$solucoes ['Php-Bcampos-hhvm']		= ['exec'=>NULL,'language'=>'Php','creator'=>'Bernardino Campos','source'=>'https://github.com/BernardinoCampos/Desafio5/blob/master/Desafio5.php'];
$solucoes ['Php-Bcampos_V2']		= ['exec'=>NULL,'language'=>'Php','creator'=>'Bernardino Campos','source'=>'https://github.com/BernardinoCampos/Desafio5/blob/master/Desafio5.php'];
$solucoes ['Php-Mockba']			= ['exec'=>'/usr/bin/php Php-Mockba.php','language'=>'Php','creator'=>'Mockba - The Borg','source'=>'https://github.com/BernardinoCampos/Desafio5/blob/master/Desafio5_V4.php'];
$solucoes ['Java-MarcoAntonio']	= ['exec'=>'/usr/bin/java -jar Java-MarcoAntonio.jar','language'=>'Java','creator'=>'Marco Antônio','source'=>'https://github.com/mrcrch/op-d05-java/tree/jsoniter2'];
$solucoes ['Python-Demetrescu']	= ['exec'=>'/usr/bin/python3 Python-Demetrescu.py','language'=>'Python','creator'=>'Roger Demetrescu','source'=>'https://github.com/rdemetrescu/OsProgramadores/tree/master/desafio-5'];
$solucoes ['Python-LuizLima'] 	= ['exec'=>'/usr/bin/python3 Python-LuizLima.py','language'=>'Python','creator'=>'Luiz Lima','source'=>''];
$solucoes ['Python-CarlosAugusto'] = ['exec'=>NULL,'language'=>'Python','creator'=>'Carlos Augusto','source'=>'https://github.com/engaugusto/desafio_5_python/blob/master/main.py'];
$solucoes ['Rust-AndreGarzia'] 	= ['exec'=>'./Rust-AndreGarzia','language'=>'Rust','creator'=>'André Garzia','source'=>'https://bitbucket.org/andregarzia/desafio-5-rust'];

$numExecucoes = 10;
#$numExecucoes = 5;
$limMem = 28;	// limite em GBytes

foreach ($solucoes as $key => $value)
	@unlink($key.".dat");

$tempos = [];

@system("rm -f ./HD/*");

if (!file_exists('/sys/fs/cgroup/memory/OsProgramadores'))
	@system ('/usr/bin/cgcreate -g memory:/OsProgramadores');

@system ("echo \$(( {$limMem} * 1073741824 )) > /sys/fs/cgroup/memory/OsProgramadores/memory.limit_in_bytes");
@system ("echo \$(( {$limMem} * 1073741824 )) > /sys/fs/cgroup/memory/OsProgramadores/memory.max_usage_in_bytes");
@system ("echo 0 > /sys/fs/cgroup/memory/OsProgramadores/memory.swappiness");

$descriptorspec = [
   0 => ["pipe", "r"],
   1 => ["file", "/var/tmp/resultado", "w"],
   2 => ["file", "/var/tmp/lixo", "w"]
];

$env = ['GOGC'=>'off'];

foreach ($tamanhos as $tam=>$v) {
	$arquivo = "Funcionarios-{$tam}.json";

	copy ("./Arquivos/{$arquivo}","./HD/{$arquivo}") or die('Não foi possível copiar o arquivo\n');
	@system("sync");

	for($aa=0;$aa<$numExecucoes;$aa++)
		foreach ($solucoes as $key => $vector) {
			if ($vector['exec']==NULL)
				continue;
			echo "Processando ($aa) $key [$tam] - ";
			system('sync; echo 3 > /proc/sys/vm/drop_caches');
			$cmd = "/usr/bin/cgexec -g memory:OsProgramadores {$vector['exec']} ./HD/{$arquivo}";

			$tempo = microtime(True);
			$process = proc_open($cmd,$descriptorspec,$pipes,getcwd(),$env);
			fclose($pipes[0]);
			$resultado = proc_close($process);
			$tempo = microtime(True)-$tempo;

			if ($resultado!=0) {
				echo "Falhou - Tempo {$tempo}\n";
				$tempos[$key][$tam][]=0;
				continue;
			}
			echo "OK - Tempo {$tempo}\n";

			$fd = popen("sort < /var/tmp/resultado | md5sum", 'r');
            fscanf($fd,"%s %s",$hash,$lixo);
            pclose($fd);

			if ($hash!=$v['Hash']) {
				echo "Hash de $key falhou em $tam\n";
				$otal=0;
			}

			$tempos[$key][$tam][]=$tempo;
		}
	@unlink("./HD/{$arquivo}");
 }

$resultados = getFtpArray('resultados.json');

foreach($tempos as $solucao=>$a)
	foreach($a as $tam=>$b)
		$resultados[$solucao][$tam]=['media'=>array_sum($b)/Count($b), 'stdDev'=>stddev($b), 'stdError'=>(stddev($b)/sqrt(Count($b))), 'tempos'=>$b, 'Size'=>$tamanhos[$tam]['Size']];

foreach (getFtpArray('solucoes.json') as $key=>$sol) {
	if (!isSet($solucoes[$key]))
		$solucoes[$key] = $sol;
	if ($sol['exec']===NULL) {
		unset($solucoes[$key]);
		unset($resultados[$key]);
	}
}

putFtpArray($solucoes,'solucoes.json');
putFtpArray($resultados,'resultados.json');
