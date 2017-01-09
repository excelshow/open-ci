<script>
	$(function () {
		$('#container').highcharts(
				{
					credits: {
						enabled: false
					},
					// plotOptions: {
					//     area: {
					//         marker: {
					//             enabled: false,
					//             symbol: 'circle',
					//             radius: 2,
					//             states: {

					//             }
					//         }
					//     }
					// },
					// chart: {
					//     type: 'area'  //指定图表的类型，默认是折线图（line）
					// },

					title   : {
						text: '近一月收入',
						x   : -20 //center
					},
					subtitle: {
						text: '',//副标题
						x   : -20
					},
					xAxis   : {
						type                : 'datetime',
						dateTimeLabelFormats: {
							day: '%m-%d'
						}
					},
					tooltip : {
						xDateFormat: '%m-%d',
						shared     : true,
						valueSuffix: '元'
					},
					yAxis   : {
						title    : {
							text: '单位（元）'
						},
						plotLines: [{
							value: 0,
							width: 1,
							color: '#808080'
						}]
					},
					legend  : false,
					series  : [{
						name         : '收入金额',
						pointStart   : {$chatDateStart},
						pointInterval: 24 * 3600 * 1000, // one day
						data         : [{$chatAmountList}]
					}]
				}
		);
	});

</script>
