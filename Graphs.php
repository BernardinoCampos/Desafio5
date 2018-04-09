<?
	$solucoes = json_decode(file_get_contents("./conf/solucoes.json"),true);
	$resultados = json_decode(file_get_contents("./conf/resultados.json"),true);

	if (filter_input(INPUT_POST, 'action', FILTER_SANITIZE_SPECIAL_CHARS)=='processa')
		$solucao = filter_input(INPUT_POST, 'solucao', FILTER_SANITIZE_SPECIAL_CHARS);
	else
		$solucao = key($solucoes);

	function sortFunc($a, $b) {
		GLOBAL $resultados;
		$item1 = (float)$resultados[$a]['12M']['media'];
		$item2 = (float)$resultados[$b]['12M']['media'];

		if ($item1==$item2)
			return 0;

		return ( ($item1>$item2)?1:-1 );
	}

	uksort($solucoes,'sortFunc');
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
							<tr><th>Solução</th><th>Linguagem</th><th>Criador</th><th>Source</th><th>Média para 30M</th></tr>
						</thead>
						<tbody>
							<?foreach ($solucoes as $key=>$solution) : ?>
								<tr>
									<td><?=$key?></td>
									<td><?=$solution['language']?></td>
									<td><?=$solution['creator']?></td>
									<? if (IsSet($solution['source'])) : ?>
										<td><a href='<?=$solution['source']?>'>Source</a></td>
									<? else : ?>
										<td> - </td>
									<? endif; ?>
									<td><?=$resultados[$key]['30M']['media']?></td>
								</tr>
							<?endforeach;?>
						</tbody>
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
