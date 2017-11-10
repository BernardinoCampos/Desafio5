#!/usr/bin/php
<?php

$tamanhos = [
			'10K'=> ['Hash'=>'967eb7059d62e6d430d67eeb16e45e44','Size'=>10000],
			'50K'=> ['Hash'=>'1c25b8e3d52ff9ae5ec9883570c49d59','Size'=>50000],
			'100K'=>['Hash'=>'fc4caf6d53d265453d62da0983bb3fb5','Size'=>100000],
			'250K'=>['Hash'=>'6e41f0316ee66d9266c1e5d32891b3bf','Size'=>250000],
			'500K'=>['Hash'=>'98fecdfd321a5cd966eefbb9f8b31785','Size'=>500000],
			'1M'=>  ['Hash'=>'b9012db943149e069920bf7c3ec49984','Size'=>1000000],
			'2M'=>  ['Hash'=>'483cc5423f6502a84c4ec9adc0ce8cbb','Size'=>2000000],
			'3M'=>  ['Hash'=>'93912b5d0ffeccc86db7d596f0078115','Size'=>3000000],
			'5M'=>  ['Hash'=>'92d5d4b4dd1bf5c965f79053145ae0f2','Size'=>5000000],
			'8M'=>  ['Hash'=>'50801387d4d06ed42043ca2325a01122','Size'=>8000000],
			'12M'=> ['Hash'=>'cddb5d244bca76b71e5ee7db95e022e8','Size'=>12000000],
			'15M'=> ['Hash'=>'acd7306f4ef82721bc301f488dd59d60','Size'=>15000000],
			'20M'=> ['Hash'=>'66d8426057595b172e7a50be8ce65db7','Size'=>20000000],
			'25M'=> ['Hash'=>'8aa026b23a51940347335f5b22d0177b','Size'=>25000000],
			'30M'=> ['Hash'=>'1c0e814e642c5fd58a2ee3dcd8c9e807','Size'=>30000000]
			];

$solucoes = ['php'=>'/usr/bin/php json.php', 'rust'=>'./desafio_Rust', 'c'=>'./desafio_C'];

$tempos = [];
foreach ($tamanhos as $tam=>$v) {
	$arquivo = "Funcionarios-{$tam}.json";

	copy ("{$arquivo}","./HD/{$arquivo}") or die('Não foi possível copiar o arquivo\n');

	for($aa=0;$aa<3;$aa++)
		foreach ($solucoes as $key => $value) {
			echo "Processando ($aa) $key [$tam] - ";
			$cmd = $value." {$arquivo} | /usr/bin/sort | /usr/bin/md5sum";
			$fd = popen($value." ./HD/{$arquivo} | /usr/bin/sort | /usr/bin/md5sum", 'r');
			$inicio = microtime(True);
			fscanf($fd,"%s %s",$hash,$lixo);
			pclose($fd);
			$total = microtime(True)-$inicio;

			echo "Tempo {$total} \n";

			if ($hash!=$v['Hash']) {
				echo "Hash de $key falhou em $tam\n";
				$otal=0;
			}

			$tempos[$key][$tam]['tempo'][]=$total;
			$tempos[$key][$tam]['hash'][]=$hash;
		}
	unlink("./HD/{$arquivo}");
 }

foreach($tempos as $solucao=>$a) {
	$fd = fopen("{$solucao}.dat","a");
	foreach($a as $tam=>$b) {
		$media=array_sum($b['tempo'])/Count($b['tempo']);
		fwrite($fd,"{$tamanhos[$tam]['Size']} {$media}\n");
	}
	fclose($fd);
}
