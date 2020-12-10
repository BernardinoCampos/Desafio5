<?
	$solucoes = json_decode(file_get_contents("./conf/solucoes.json"),true);
	$resultados = json_decode(file_get_contents("./conf/resultados.json"),true);

	if (filter_input(INPUT_POST, 'action', FILTER_SANITIZE_SPECIAL_CHARS)=='processa')
		$solucao = filter_input(INPUT_POST, 'solucao', FILTER_SANITIZE_SPECIAL_CHARS);
	else
		$solucao = key($solucoes);

	if ($_GET['Action']=='Resultado') {
		$size = intval($_GET['Size']);
		if ($size==10 || $size==50 || $size==100 || $size==250 || $size==500) {
			ob_end_clean();
			header("Content-type: application/octet-stream; charset=UTF-8;");
			header("Content-Disposition: attachment; filename=\"Resultado-{$size}K.txt\"");

			passthru("cat Resultado-{$size}K.txt");
			exit;
		}

		header("Location: https://www.bcampos.com/Graphs.php");
		exit;
	}

	function sortFunc($a, $b) {
		GLOBAL $resultados;
		$item1 = (float)$resultados[$a]['30M']['media'];
		$item2 = (float)$resultados[$b]['30M']['media'];

		if ($item1==$item2)
			return 0;

		if ($item1==0)
			return 1;

		if ($item2==0)
			return 1;

		return ( ($item1>$item2)?1:-1 );
	}

	function sortLang($a, $b) {
		$item1 = (float)($a[Time]/$a['Num']);
		$item2 = (float)($b[Time]/$b['Num']);

		if ($item1==$item2)
			return 0;

		if ($item1==0)
			return 1;

		if ($item2==0)
			return 1;

		return ( ($item1>$item2)?1:-1 );
	}

	uksort($solucoes,'sortFunc');

	$lang = [];

	foreach ($solucoes as $key=>$sol) {
		if ($resultados[$key]['30M']['media']!=0) {
			@$lang[$sol['language']]['Time'] += (float)$resultados[$key]['30M']['media'];
			@$lang[$sol['language']]['Num']++;
		}
	}

	uksort($lang,'sortLang');


?>
<html>
	<head>
		<link href="/assets/css/bootstrap.min.css" rel="stylesheet">
		<link href="/assets/css/docs.min.css" rel="stylesheet">
	</head>
	<body>
		<div id="wrapper">
			<div class="row">
				<div class="col-lg-12">
					<h1 class="text-center">Desafio 5</h1>
					<div id="Graph1" style="height:700px; width: 80%; margin:0 auto;"></div>
				<div>
			</div>
			<div class="row mt-5 mb-5">
				<div class="col-lg-3">
				</div>
				<div class="col-lg-6">
					<table class="table">
						<thead  class="thead-dark">
							<tr><th>Solução</th><th class='text-center'>Linguagem</th><th class='text-center'>Criador</th><th>Source</th><th>Média para 30M</th></tr>
						</thead>
						<tbody>
							<?foreach ($solucoes as $key=>$solution) : ?>
								<tr>
									<td><?=$key?></td>
									<td class='text-center'><?=$solution['language']?></td>
									<td class='text-center'><?=$solution['creator']?></td>
									<? if (IsSet($solution['source'])) : ?>
										<td><a href='<?=$solution['source']?>'>Source</a></td>
									<? else : ?>
										<td> - </td>
									<? endif; ?>
									<? if ($resultados[$key]['30M']['media']!=0) : ?>
										<td class='text-right'><?=number_format($resultados[$key]['30M']['media'],2)?>s</td>
									<? else : ?>
										<td class='text-right'> - </td>
									<? endif; ?>
								</tr>
							<?endforeach;?>
						</tbody>
					</table>
				</div>
				<div class="col-lg-3">
				</div>
			</div>
					<div class="row mt-5 mb-5">
						<div class="col-lg-3">
						</div>
						<div class="col-lg-6">
							<table class="table">
								<thead  class="thead-dark">
								<tr><th class='text-center'>Linguagem</th><th>Versões</th><th>Média para 30M</th></tr>
								</thead>
								<tbody>
								<?foreach ($lang as $k=>$l) : ?>
									<tr>
										<td><?=$k?></td>
										<td><?=$l['Num']?></td>
										<td class='text-right'><?=number_format(((float)$l['Time']/(float)$l['Num']),2)?>s</td>
									</tr>
								<?endforeach;?>
								</tbody>
							</table>
						</div>
						<div class="col-lg-3">
						</div>
					</div>
			<div class="row mt-5 mb-5">
				<div class="col-lg-3">
				</div>
				<div class="col-lg-6">
					<table class="table">
						<thead class="thead-dark">
							<tr><th>Arquivos de Teste</th><th>MD5 Resultado</th><th>Arquivo Resultado</th></tr>
						</thead>
						<tbody>
						<tr><td align='center'><a href='http://www.bcampos.com/Funcionarios-10K.json.7z'>10K Registros</a></td><td>967eb7059d62e6d430d67eeb16e45e44</td>  <td><a href='http://www.bcampos.com/Graphs.php?Action=Resultado&Size=10'>Resultado 10K</a></td></tr>
						<tr><td align='center'><a href='http://www.bcampos.com/Funcionarios-50K.json.7z'>50K Registros</a></td><td>1c25b8e3d52ff9ae5ec9883570c49d59</td>  <td><a href='http://www.bcampos.com/Graphs.php?Action=Resultado&Size=50'>Resultado 50K</a></td></tr>
						<tr><td align='center'><a href='http://www.bcampos.com/Funcionarios-100K.json.7z'>100K Registros</a></td><td>fc4caf6d53d265453d62da0983bb3fb5</td><td><a href='http://www.bcampos.com/Graphs.php?Action=Resultado&Size=100'>Resultado 100K</a></td></tr>
						<tr><td align='center'><a href='http://www.bcampos.com/Funcionarios-250K.json.7z'>250K Registros</a></td><td>6e41f0316ee66d9266c1e5d32891b3bf</td><td><a href='http://www.bcampos.com/Graphs.php?Action=Resultado&Size=250'>Resultado 250K</a></td></tr>
						<tr><td align='center'><a href='http://www.bcampos.com/Funcionarios-500K.json.7z'>500K Registros</a></td><td>98fecdfd321a5cd966eefbb9f8b31785</td><td><a href='http://www.bcampos.com/Graphs.php?Action=Resultado&Size=500'>Resultado 500K</a></td></tr>
						<tr><td align='center'><a href='http://www.bcampos.com/Funcionarios-1M.json.7z'>1M Registros</a></td><td>b9012db943149e069920bf7c3ec49984</td>    <td>&nbsp;</td></tr>
						<tr><td align='center'><a href='http://www.bcampos.com/Funcionarios-2M.json.7z'>2M Registros</a></td><td>483cc5423f6502a84c4ec9adc0ce8cbb</td>    <td>&nbsp;</td></tr>
						<tr><td align='center'><a href='http://www.bcampos.com/Funcionarios-3M.json.7z'>3M Registros</a></td><td>93912b5d0ffeccc86db7d596f0078115</td>    <td>&nbsp;</td></tr>
						<tr><td align='center'><a href='http://www.bcampos.com/Funcionarios-5M.json.7z'>5M Registros</a></td><td>92d5d4b4dd1bf5c965f79053145ae0f2</td>    <td>&nbsp;</td></tr>
						<tr><td align='center'><a href='http://www.bcampos.com/Funcionarios-8M.json.7z'>8M Registros</a></td><td>50801387d4d06ed42043ca2325a01122</td>    <td>&nbsp;</td></tr>
						<tr><td align='center'><a href='http://www.bcampos.com/Funcionarios-12M.json.7z'>12M Registros</a></td><td>cddb5d244bca76b71e5ee7db95e022e8</td>  <td>&nbsp;</td></tr>
						<tr><td align='center'><a href='http://www.bcampos.com/Funcionarios-15M.json.7z'>15M Registros</a></td><td>acd7306f4ef82721bc301f488dd59d60</td>  <td>&nbsp;</td></tr>
						<tr><td align='center'><a href='http://www.bcampos.com/Funcionarios-20M.json.7z'>20M Registros</a></td><td>66d8426057595b172e7a50be8ce65db7</td>  <td>&nbsp;</td></tr>
						<tr><td align='center'><a href='http://www.bcampos.com/Funcionarios-25M.json.7z'>25M Registros</a></td><td>8aa026b23a51940347335f5b22d0177b</td>  <td>&nbsp;</td></tr>
						<tr><td align='center'><a href='http://www.bcampos.com/Funcionarios-30M.json.7z'>30M Registros</a></td><td>1c0e814e642c5fd58a2ee3dcd8c9e807</td>  <td>&nbsp;</td></tr>
					</table>
				</div>
				<div class="col-lg-3">
				</div>
			</div>
			<form action='/Graphs.php' method="post">
				<input type='hidden' name='action' value='processa'>
				<div class='row'>
					<div class="col-lg-5">
					</div>
					<div class="col-lg-2 text-center">
						<select name="solucao" class="select" onchange="this.form.submit()">
							<? foreach ($solucoes as $key=>$value) :?>
								<option value="<?=$key?>" <?=($solucao==$key)?'selected':''?>><?=$key?></option>
							<? endforeach; ?>
						</select>
					</div>
					<div class="col-lg-5">
					</div>
				</div>
			</form>
			<div class="row">
				<div class="col-lg-12">
					<div id="Graph2" style="height:700px; width: 80%; margin:0 auto;"></div>
				<div>
			</div>
		</div>
		<script src="/echarts.min.js"></script>
		<script type="text/javascript">
			var myChart1 = echarts.init(document.getElementById('Graph1'));

			var option1 = {
				animation: true,
				tooltip: { trigger: 'axis' },
				legend: { data : [<?foreach($solucoes as $key=>$value) echo "'{$key}',"?>] },
				dataZoom: [
			        {
						type: 'slider',
						start: 0,
			        }
			    ],
				xAxis : [
					{
						type : 'value',
						boundaryGap : false,
						data : [<?$vector = current($resultados); foreach($vector as $key=>$value) echo "'{$key}',"?>],
						axisLabel : {
							formatter: function (value, index) {
								if (value>1000000)
									return (value/1000000)+"M"
								if (value>1000)
									return (value/1000)+"K"
						    	return value;
							}
						}
					}
				],
				yAxis : [
					{
						type : 'value'
					}
				],
				series : [
					<? foreach($resultados as $key=>$vector) :?>
						{
							name: "<?=$key?>",
							type: "line",
							data: [<? foreach ($vector as $k=>$v) echo "[{$v['Size']},".number_format($v['media'],3,".","")."],";?>],
							markPoint : { data : [{type : 'max'},{type : 'min'}] }
						},
					<? endforeach; ?>
				]
			};

			// Load data into the ECharts instance
			myChart1.setOption(option1);

			//var legVal = new Array (<? foreach ($resultados[$solucao] as $key=>$result) echo "'{$key}',"; ?>);

			var data2 = [
				<? foreach ($resultados[$solucao] as $key=>$result) echo "{
							'value':".number_format($result['media'],3,".","").",
							'legend':'{$key}',
							'lower':".number_format($result['media']-2*$result['stdError'],3,".","").",
							'upper':".number_format($result['media']+2*$result['stdError'],3,".","")."},\n"; ?>
			];

			var myChart2 = echarts.init(document.getElementById('Graph2'));

			option2 = {
			        title: {
			            text: 'Intervalo de Confiança 95%',
			            subtext: '',
			            left: 'center'
			        },
			        tooltip: {
			            trigger: 'axis',
			            axisPointer: {
			                type: 'cross',
			                animation: false,
			                label: {
			                    backgroundColor: '#ccc',
			                    borderColor: '#aaa',
			                    borderWidth: 1,
			                    shadowBlur: 0,
			                    shadowOffsetX: 0,
			                    shadowOffsetY: 0,
			                    textStyle: {
			                        color: '#222'
			                    }
			                }
			            },
			            formatter: function (params) {
			                return params[2].name + '<br />' + params[2].value;
			            }
			        },
			        grid: {
			            left: '3%',
			            right: '4%',
			            bottom: '3%',
			            containLabel: true
			        },
			        xAxis: {
			            type: 'category',
			            data: data2.map(function (item) {
			                return item.legend;
			            }),
			            axisLabel: {
			                formatter: function (value, idx) {
			                    return value;
			                }
			            },
			            splitLine: {
			                show: false
			            },
			            boundaryGap: false
			        },
			        yAxis: {
			            axisLabel: {
			                formatter: function (val) {
			                    return val;
			                }
			            },
			            axisPointer: {
			                label: {
			                    formatter: function (params) {
			                        return params.value;
			                    }
			                }
			            },
			            splitNumber: 3,
			            splitLine: {
			                show: false
			            }
			        },
			        series: [{
			            name: 'L',
			            type: 'line',
			            data: data2.map(function (item) {
			                return item.lower;
			            }),
			            lineStyle: {
			                normal: {
			                    opacity: 0
			                }
			            },
			            stack: 'confidence-band',
			            symbol: 'none'
			        }, {
			            name: 'U',
			            type: 'line',
			            data: data2.map(function (item) {
			                return item.upper - item.lower;
			            }),
			            lineStyle: {
			                normal: {
			                    opacity: 0
			                }
			            },
			            areaStyle: {
			                normal: {
			                    color: '#ccc'
			                }
			            },
			            stack: 'confidence-band',
			            symbol: 'none'
			        }, {
			            type: 'line',
			            data: data2.map(function (item) {
			                return item.value;
			            }),
			            hoverAnimation: false,
			            symbolSize: 6,
			            itemStyle: {
			                normal: {
			                    color: '#c23531'
			                }
			            },
			            showSymbol: false
			        }]
			    };
			// Load data into the ECharts instance
			myChart2.setOption(option2);
		</script>
	</body>
</html>
