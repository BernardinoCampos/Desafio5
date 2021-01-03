#!/usr/bin/php
<?php

$conf = json_decode(file_get_contents('./ftp.conf'),TRUE);

function putFtpArray(array $arr,string $nome) {
	Global $conf;

	$filename = "/var/tmp/{$nome}";

	file_put_contents($filename, json_encode($arr));

	if (!file_exists($filename)) {
		echo "Não foi possível achar o arquivo {$filename}\n";
		exit;
	}

	$fd = ftp_connect($conf['host']);
	$lg = ftp_login($fd, $conf['user'], $conf['passwd']);
	ftp_pasv($fd, false	);
	ftp_put($fd, "/conf/{$nome}", $filename, FTP_BINARY);
	ftp_close($fd);
}

function getFtpArray(string $nome) : array {
	Global $conf;

	$filename = "/var/tmp/{$nome}";

	$fd = ftp_connect($conf['host']);
	$lg = ftp_login($fd, $conf['user'], $conf['passwd']);
	ftp_pasv($fd, false	);
	ftp_get($fd, $filename, "/conf/{$nome}", FTP_BINARY);
	ftp_close($fd);

	if (!file_exists($filename)) {
		echo "Não foi possível achar o arquivo {$filename}\n";
		exit;
	}

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

#$solucoes ['C#-RafaelPires']			= ['dir'=>'/OsProgramadores', 'exec'=>NULL,'env'=>[], 'language'=>'C#','creator'=>'Rafael Pires','source'=>'https://github.com/faelpires/op-desafios/tree/master/desafio-05/faelpires', 'serious'=>TRUE];
#$solucoes ['C#-Washington']			= ['dir'=>'/OsProgramadores', 'exec'=>'dotnet Sources/C#-Washington/D5/bin/Release/netcoreapp2.0/D5.dll','env'=>['LANG'=>'en_US.UTF-8'], 'language'=>'C#','creator'=>'Washington Ramos','source'=>'', 'serious'=>TRUE];
#$solucoes ['C++-Caloni']				= ['dir'=>'/OsProgramadores', 'exec'=>'./Exec/C++-Caloni 16 ', 'env'=>['GOGC'=>'off'], 'language'=>'C++','creator'=>'Caloni','source'=>'https://github.com/OsProgramadores/op-desafios/blob/master/desafio-05/caloni/cpp', 'serious'=>TRUE];
#$solucoes ['C++-EliasCorrea']			= ['dir'=>'/OsProgramadores', 'exec'=>'./Exec/C++-Elias 16 ', 'env'=>['GOGC'=>'off'], 'language'=>'C++','creator'=>'Elias Correa','source'=>'https://github.com/OsProgramadores/op-desafios/tree/master/desafio-05/correaelias/cpp', 'serious'=>TRUE];
#$solucoes ['C++-JonathasValeriano']	= ['dir'=>'/OsProgramadores', 'exec'=>'./Exec/C++-JonathasValeriano 16 ', 'env'=>['GOGC'=>'off'], 'language'=>'C++','creator'=>'Jonathas Valeriano','source'=>'https://github.com/OsProgramadores/op-desafios/tree/master/desafio-05/JonathasValeriano/cpp', 'serious'=>TRUE];
#$solucoes ['C++-JonathasValeriano2']	= ['dir'=>'/OsProgramadores', 'exec'=>'./Exec/C++-JonathasValeriano2 8 ', 'env'=>['GOGC'=>'off'], 'language'=>'C++','creator'=>'Jonathas Valeriano','source'=>'https://github.com/OsProgramadores/op-desafios/tree/master/desafio-05/JonathasValeriano/cpp', 'serious'=>TRUE];
#$solucoes ['C++-JonathasValeriano3']	= ['dir'=>'/OsProgramadores', 'exec'=>'./Exec/C++-JonathasValeriano3 8 ', 'env'=>['GOGC'=>'off'], 'language'=>'C++','creator'=>'Jonathas Valeriano','source'=>'https://github.com/OsProgramadores/op-desafios/tree/master/desafio-05/JonathasValeriano/cpp', 'serious'=>FALSE];
#$solucoes ['C++-SergioCorreia']			= ['dir'=>'/OsProgramadores', 'exec'=>'./Exec/C++-SergioCorreia ', 'env'=>['GOGC'=>'off'], 'language'=>'C++','creator'=>'Sérgio Correia','source'=>NULL, 'serious'=>FALSE];
$solucoes ['C-CarlosAlves']			= ['dir'=>'/OsProgramadores', 'exec'=>'./Exec/C-CarlosAlves-3','env'=>[], 'language'=>'C','creator'=>'Carlos Alves','source'=>'https://github.com/OsProgramadores/op-desafios/tree/master/desafio-05/cbcalves/c/v2', 'serious'=>TRUE];
#$solucoes ['C-CarlosAlves-V2']			= ['dir'=>'/OsProgramadores', 'exec'=>NULL,'env'=>[], 'language'=>'C','creator'=>'Carlos Alves','source'=>'https://github.com/OsProgramadores/op-desafios/tree/master/desafio-05/cbcalves/c/v2', 'serious'=>TRUE];
#$solucoes ['C-CarlosAlves-V3']			= ['dir'=>'/OsProgramadores', 'exec'=>NULL,'env'=>[], 'language'=>'C','creator'=>'Carlos Alves','source'=>'https://github.com/OsProgramadores/op-desafios/tree/master/desafio-05/cbcalves/c/v2', 'serious'=>TRUE];
#$solucoes ['C-Kortkamp']				= ['dir'=>'/OsProgramadores', 'exec'=>'./Exec/C-Kortkamp','env'=>[], 'language'=>'C','creator'=>'Marcelo Kortkamp','source'=>'https://github.com/OsProgramadores/op-desafios/tree/master/desafio-05/kortkamp/c', 'serious'=>TRUE];
#$solucoes ['C-SergioCorreia']			= ['dir'=>'/OsProgramadores', 'exec'=>'./Exec/C-SergioCorreia','env'=>[], 'language'=>'C','creator'=>'Sérgio Correia','source'=>'https://github.com/OsProgramadores/op-desafios/blob/master/desafio-05/qrwteyrutiyoup/c', 'serious'=>TRUE];
#$solucoes ['Go-SergioCorreia']			= ['dir'=>'/OsProgramadores', 'exec'=>'./Exec/Go-SergioCorreia 8 ', 'env'=>['GOGC'=>'off'], 'language'=>'Go','creator'=>'Sérgio Correia','source'=>'https://github.com/OsProgramadores/op-desafios/blob/master/desafio-05/qrwteyrutiyoup/go', 'serious'=>TRUE];
#$solucoes ['Go-JoaoVitor']				= ['dir'=>'/OsProgramadores', 'exec'=>'./Exec/Go-JoaoVitor ', 'env'=>['GOGC'=>'off'], 'language'=>'Go','creator'=>'João Vitor','source'=>'https://github.com/OsProgramadores/op-desafios/tree/master/desafio-05/ancogamer/go', 'serious'=>TRUE];
#$solucoes ['Java-AnaPaulaMazi']		= ['dir'=>'/OsProgramadores/Sources/Java-AnaPaulaMazi', 'exec'=>'/usr/bin/java -cp "lib/gson-2.8.6.jar:" ClasseMain','env'=>['LANG'=>'pt_BR.UTF-8'], 'language'=>'Java','creator'=>'Ana Paula Mazi','source'=>'https://github.com/OsProgramadores/op-desafios/blob/master/desafio-05/AnaPaulaMazi/java', 'serious'=>TRUE];
#$solucoes ['Java-MarcoAntonio']		= ['dir'=>'/OsProgramadores', 'exec'=>'/usr/bin/java -jar Exec/Java-MarcoAntonio.jar','env'=>['LANG'=>'pt_BR.UTF-8'], 'language'=>'Java','creator'=>'Marco Antônio','source'=>'https://github.com/mrcrch/op-d05-java/tree/jsoniter2', 'serious'=>TRUE];
#$solucoes ['Kotlin-Anthony']			= ['dir'=>'/OsProgramadores', 'exec'=>'/usr/bin/java -XX:+UseParallelGC -jar Exec/Kotlin-Anthony.jar','env'=>['LANG'=>'pt_BR.UTF-8'], 'language'=>'Kotlin','creator'=>'Anthony Louis','source'=>'https://github.com/osprogramadores/op-desafios/desafio-05/anthonyfisicabsb/kotlin', 'serious'=>TRUE];
#$solucoes ['PHP-Gleydson']				= ['dir'=>'/OsProgramadores', 'exec'=>'/usr/bin/php Exec/PHP-Gleydson', 'env'=>[], 'language'=>'Php', 'creator'=>'Gleydson José', 'source'=>'https://github.com/OsProgramadores/op-desafios/tree/master/desafio-05/satuctkode/php', 'serious'=>TRUE];
#$solucoes ['Pascal-EliasCorrea']		= ['dir'=>'/OsProgramadores', 'exec'=>'./Exec/Pascal-EliasCorrea2 16 ','env'=>[], 'language'=>'Pascal','creator'=>'Elias Correa','source'=>'https://github.com/OsProgramadores/op-desafios/blob/master/desafio-05/correaelias/pascal/desafio5.pas', 'serious'=>TRUE];
#$solucoes ['Pascal-EliasCorrea']		= ['dir'=>'/OsProgramadores', 'exec'=>'./Exec/Pascal-EliasCorrea3','env'=>[], 'language'=>'Pascal','creator'=>'Elias Correa','source'=>'https://github.com/OsProgramadores/op-desafios/blob/master/desafio-05/correaelias/pascal/desafio5.pas', 'serious'=>TRUE];
#$solucoes ['Php8-Bcampos']				= ['dir'=>'/OsProgramadores', 'exec'=>'/usr/bin/php80 Exec/Php-Bcampos_V2.php', 'env'=>[], 'language'=>'Php', 'creator'=>'Bernardino Campos', 'source'=>'https://github.com/BernardinoCampos/Desafio5/blob/master/Desafio5.php', 'serious'=>TRUE];
#$solucoes ['Php8-Mockba']				= ['dir'=>'/OsProgramadores', 'exec'=>'/usr/bin/php80 Exec/Php-Mockba4a.php', 'env'=>[], 'language'=>'Php','creator'=>'Mockba - The Borg','source'=>'https://github.com/BernardinoCampos/Desafio5/blob/master/Desafio5_V4a.php', 'serious'=>TRUE];
#$solucoes ['Python-Demetrescu']		= ['dir'=>'/OsProgramadores', 'exec'=>'/usr/bin/python3 Exec/Python-Demetrescu.py','env'=>[], 'language'=>'Python','creator'=>'Roger Demetrescu','source'=>'https://github.com/rdemetrescu/OsProgramadores/tree/master/desafio-5', 'serious'=>TRUE];
#$solucoes ['Python-LuizLima']			= ['dir'=>'/OsProgramadores', 'exec'=>'/usr/bin/python3 Exec/Python-LuizLima.py','env'=>[], 'language'=>'Python','creator'=>'Luiz Lima','source'=>'', 'serious'=>TRUE];
#$solucoes ['Python-MatheusBarbosa']	= ['dir'=>'/OsProgramadores', 'exec'=>'.//usr/bin/python3.6 Exec/Python-MatheusBarbosa.py','env'=>[], 'language'=>'Python','creator'=>'Matheus Barbosa','source'=>'https://github.com/OsProgramadores/op-desafios/blob/master/desafio-05/WhoisBsa/python/main.py', 'serious'=>TRUE];
#$solucoes ['Python-MekyleiBelchior']	= ['dir'=>'/OsProgramadores', 'exec'=>'/usr/bin/python3 Exec/Python-Mekylei-Belchior.py','env'=>['LC_ALL'=>'en_US.utf8'], 'language'=>'Python','creator'=>'Mekylei Belchior','source'=>'', 'serious'=>TRUE];
#$solucoes ['Rust-AndreGarzia']			= ['dir'=>'/OsProgramadores', 'exec'=>'./Exec/Rust-AndreGarzia','env'=>[], 'language'=>'Rust','creator'=>'André Garzia','source'=>'https://bitbucket.org/andregarzia/desafio-5-rust', 'serious'=>TRUE];
#$solucoes ['Rust-Leovano']				= ['dir'=>'/OsProgramadores', 'exec'=>'./Exec/Rust-Leovano2 8 ','env'=>[], 'language'=>'Rust','creator'=>'Leo Silva Souza','source'=>'https://github.com/leovano/op-desafios/tree/master/desafio-05/leovano/rust', 'serious'=>TRUE];
#$solucoes ['Rust-Leovano2']				= ['dir'=>'/OsProgramadores', 'exec'=>'./Exec/Rust-Leovano-2 8 ','env'=>['GOGC'=>'off'], 'language'=>'Rust','creator'=>'Leo Silva Souza','source'=>'https://github.com/leovano/op-desafios/tree/master/desafio-05/leovano/rust', 'serious'=>FALSE];
#$solucoes ['Rust-Leovano-JL']				= ['dir'=>'/OsProgramadores', 'exec'=>'./Exec/Rust-Leovano-JL 8 ','env'=>['GOGC'=>'off'], 'language'=>'Rust','creator'=>'Leo Silva Souza','source'=>'https://github.com/leovano/op-desafios/tree/master/desafio-05/leovano/rust', 'serious'=>FALSE];
#$solucoes ['Scala-Lucena']				= ['dir'=>'/OsProgramadores', 'exec'=>'/usr/bin/scala Exec/Scala-Lucena.jar','env'=>['LC_ALL'=>'en_US.utf8'], 'language'=>'Scala','creator'=>'Leonardo Lucena','source'=>'https://github.com/OsProgramadores/op-desafios/tree/master/desafio-05/lrlucena/scala', 'serious'=>TRUE];
#$solucoes ['NodeJS-BrunoSana']			= ['dir'=>'/OsProgramadores', 'exec'=>'/usr/bin/node Exec/Js-BrunoSana.js','env'=>['LC_ALL'=>'en_US.utf8'], 'language'=>'JavaScript','creator'=>'Bruno Sana','source'=>'', 'serious'=>TRUE];
#$solucoes ['NodeJS-JuscelinoDJJ']		= ['dir'=>'/OsProgramadores', 'exec'=>'/usr/bin/node --expose_gc --max-old-space-size=16384 Exec/Js-JuscelinoDJJ.js','env'=>['LC_ALL'=>'en_US.utf8'], 'language'=>'JavaScript','creator'=>'Juscelino Júnior','source'=>'https://github.com/OsProgramadores/op-desafios/tree/master/desafio-05/juscelinodjj/javascript', 'serious'=>TRUE];
#$solucoes ['NodeJS-JuscelinoDJJ-2']	= ['dir'=>'/OsProgramadores', 'exec'=>'/usr/bin/node --max-old-space-size=16384 Exec/Js-JuscelinoDJJ-2.js','env'=>['LC_ALL'=>'en_US.utf8'], 'language'=>'JavaScript','creator'=>'Juscelino Júnior','source'=>'https://github.com/OsProgramadores/op-desafios/tree/master/desafio-05/juscelinodjj/javascript', 'serious'=>TRUE];
#$solucoes ['NodeJS-Fdaciuk']			= ['dir'=>'/OsProgramadores', 'exec'=>'/usr/bin/node Exec/Js-Fdaciuk.js','env'=>['LC_ALL'=>'en_US.utf8'], 'language'=>'JavaScript','creator'=>'Fernando Daciuk','source'=>'https://github.com/OsProgramadores/op-desafios/tree/master/desafio-05/fdaciuk/nodejs', 'serious'=>TRUE];
#$solucoes ['LiquidHaskel-RenanRoberto']		= ['dir'=>'/OsProgramadores/Sources/LiquidHaskel-RenanRoberto', 'exec'=>'/usr/local/bin/stack run ','env'=>['LC_ALL'=>'en_US.utf8', 'HOME'=>'/root'], 'language'=>'LiquidHaskel','creator'=>'Renan Roberto','source'=>'', 'serious'=>TRUE];

$numExecucoes = 5;

$limMem = 28;	// limite em GBytes

$resultados = getFtpArray('resultados.json');

if (IsSet($solucoes)) {
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

	foreach ($tamanhos as $tam=>$v) {
		$arquivo = "Funcionarios-{$tam}.json";

		for($aa=0;$aa<$numExecucoes;$aa++)
			foreach ($solucoes as $key => $vector) {
				if ($vector['exec']==NULL)
					continue;

				if (!file_exists("./HD/{$arquivo}")) {
					copy ("./Arquivos/{$arquivo}","./HD/{$arquivo}") or die('Não foi possível copiar o arquivo\n');
					@system("sync");
				}

				echo "Processando ($aa) $key [$tam] - ";
				system('sync; echo 3 > /proc/sys/vm/drop_caches');
				$cmd = "/usr/bin/cgexec -g memory:OsProgramadores {$vector['exec']} /OsProgramadores/HD/{$arquivo}";

				$oldDir = getcwd();

				unlink ("/var/tmp/resultado");
				unlink ("/var/tmp/lixo");

				chdir($vector['dir']);
				$tempo = microtime(True);
				$process = proc_open($cmd,$descriptorspec,$pipes,getcwd(),$vector['env']);
				fclose($pipes[0]);
				$resultado = proc_close($process);
				$tempo = microtime(True)-$tempo;

				chdir($oldDir);

				if ($resultado!=0) {
					echo "Falhou [{$resultado}]- Tempo {$tempo}\n";
					$tempos[$key][$tam][]=0;
					exit;
					continue;
				}

				echo "OK - Tempo {$tempo}\n";

				$fd = popen("cat /var/tmp/resultado | sed '/^$/d' | sort | md5sum", 'r');
	            fscanf($fd,"%s %s",$hash,$lixo);
	            pclose($fd);

				if ($hash!=$v['Hash']) {
					echo "Hash de $key falhou em $tam - {$hash} {$v['Hash']} \n";
					exit;
					$otal=0;
				}

				$tempos[$key][$tam][]=$tempo;
			}

		@unlink("./HD/{$arquivo}");
	 }
	 foreach($tempos as $solucao=>$a)
		if ($solucoes[$solucao]['serious'])
			foreach($a as $tam=>$b)
		 		$resultados[$solucao][$tam]=['media'=>array_sum($b)/Count($b), 'stdDev'=>stddev($b), 'stdError'=>(stddev($b)/sqrt(Count($b))), 'tempos'=>$b, 'Size'=>$tamanhos[$tam]['Size']];
}

foreach (getFtpArray('solucoes.json') as $key=>$sol)
	if (!isSet($solucoes[$key]))
		$solucoes[$key] = $sol;

foreach ($solucoes as $key=>$sol){
	if ($sol['exec']===NULL) {
		unset($solucoes[$key]);
		unset($resultados[$key]);
	}
}

$solucoes['LIMITE'] = ['exec'=>'','language'=>'LIMITE','creator'=>'Admin','source'=>''];
$resultados['LIMITE'] = $resultados["Php8-Bcampos"];

foreach ($resultados['LIMITE'] as $key=>$value) {
	$resultados['LIMITE'][$key]['media']*=1.2;
	for ($ii=0; $ii<$numExecucoes; $ii++)
		$resultados['LIMITE'][$key]['tempos'][$ii]*=1.2;
}

putFtpArray($solucoes,'solucoes.json');
putFtpArray($resultados,'resultados.json');
