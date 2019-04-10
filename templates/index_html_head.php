<!DOCTYPE html>
<html lang="en" xml:lang="en" xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>Example PHP - Paweł Drabowicz</title>
		<meta http-equiv="x-ua-compatible" content="IE=edge">
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		<meta name="author" content="Paweł Drabowicz" />
		<meta name="keywords" content="" />
		<meta name="description" content="" />
		<meta name="revisit-after" content="2 days" />
		
		<link rel="stylesheet" type="text/css" href="<?=__ROOT_PATH__;?>public/assets/css/style.css" />
		<link rel="stylesheet" type="text/css" href="<?=__ROOT_PATH__;?>public/assets/js/jquery/ui/jquery-ui-1.12.1.css" />
		
		<link rel="stylesheet" type="text/css" href="<?=__ROOT_PATH__;?>public/assets/js/easyautocomplete/easy-autocomplete.css" />
		<link rel="stylesheet" type="text/css" href="<?=__ROOT_PATH__;?>public/assets/js/easyautocomplete/easy-autocomplete.themes.css" />
		<link rel="shortcut icon" type="image/x-icon" href="icon.png" />
		
		
		<script src="<?=__ROOT_PATH__;?>public/assets/js/jquery/jquery-1.12.1.min.js"></script>
		<script src="<?=__ROOT_PATH__;?>public/assets/js/jquery/ui/jquery-ui-1.12.1.js"></script>
		<script src="<?=__ROOT_PATH__;?>public/assets/js/jquery/jarallax/jarallax.js"></script>
		<script src="<?=__ROOT_PATH__;?>public/assets/js/jquery/scrollto/jquery.scrollto.min.js"></script>
		
		<script src="<?=__ROOT_PATH__;?>node_modules/chart.js/dist/Chart.js"></script>
		<script src="<?=__ROOT_PATH__;?>node_modules/chart.js/utils.js"></script>
		<script>
		// <![CDATA[
			
			function jQuery_Ajax_Load(id, url)
			{
				$("#" + id).load
				(
					url
					, 
					function(response, status, xhr)
					{
						switch(xhr.status)
						{
							case 200:	{break;}
							case 404:	{break;}
							case 0:		{break;}
							default:	{}
						}
					}
				);
			}
			
			function jQuery_Ajax_Post(id, url, form)
			{
				$.post
				(
					url
					, 
					$("#" + form).serialize()
					,
					function(data)
					{
						$("#" + id).html(data);
					}
				);
			}
			
			function toggleDataSeries(e)
			{
				if(typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {e.dataSeries.visible = false;} else {e.dataSeries.visible = true;} chart.render();
			}
			
			function keyPressed(e, nameInput)
			{
				if(e.keyCode == 13) 
				{
					document.forms['searchForm_1'].action = '?search,id,value,&search=' + document.getElementById('searchInput_1').value; 
					document.forms['searchForm_1'].submit();
					
					return false;
				}
				else
				{
					return true;
				}
			}
			
		// ]]>
		</script>
	</head>
	<body>
		
		<?
		//include(__ROOT__. '/templates/index_html_head_body.php');
		include('templates/index_html_head_body.php');
		?>
		
		<script src="<?=__ROOT_PATH__;?>public/assets/js/canvasjs/jquery.canvasjs.min.js"></script>
		<script src="<?=__ROOT_PATH__;?>public/assets/js/easyautocomplete/jquery.easy-autocomplete.min.js"></script> 
		<script>
		// <![CDATA[
			
			window.onload = function ()
			{
				var chart = new CanvasJS.Chart
				(
					"chartContainer",
					{
						animationEnabled: true,
						title:
						{
							text: "Kurs złota"
						},
						axisX:
						{
							title: "Data",
							valueFormatString: "YYYY-MM-DD"
						},
						axisY:
						{
							title: "Cena",
							includeZero: false,
							suffix: " PLN"
						},
						legend:
						{
							cursor: "pointer",
							fontSize: 16,
							itemclick: toggleDataSeries
						},
						toolTip:
						{
							shared: true
						},
						data: 
						[
							{
								name: "Przykładowe dane",
								type: "spline",
								yValueFormatString: "#0.## PLN",
								showInLegend: true,
								dataPoints: 
								[
									<?=$show->points($data_json);?>
								]
							}
						]
					}
				);
				
				
				chart.render();
				
				
				/* - - - - - - - - - - */
				
				
				var options = 
				{
					url: function(phrase) 
					{
						return "index_search_json.php?search=" + phrase + "&format=json";
					},
					list: 
					{
						maxNumberOfElements: 25,
						onSelectItemEvent: function() 
						{
							var index = $("#searchInput_1").getSelectedItemData().id;
							var value = $("#searchInput_1").getSelectedItemData().name;
							
							$("#searchIndex_1").val(index).trigger("change");
							$("#searchValue_1").val(value).trigger("change");
						},
						onChooseEvent: function()
						{
							var index	= $("#searchInput_1").getSelectedItemData().id;
							var value	= $("#searchInput_1").getSelectedItemData().name;
							
							location.href = '?search,' + index + ',' + value + ',&search=' + value;
						},
						showAnimation: 
						{
							type: "fade",					//normal|slide|fade
							time: 200,
							callback: function() 
							{
								//...
							}
						},
						hideAnimation: 
						{
							type: "slide",					//normal|slide|fade
							time: 200,
							callback: function() 
							{
								//...
							}
						}
					},
					type: "POST",
					getValue: "name",
					categories: 
					[
						{
							listLocation: "searchResult",	//category albums
							header: "Items found..",
							maxNumberOfElements: 25
						}
					]
				};
				
				
				$("#searchInput_1").easyAutocomplete(options);
			}
			
			
		var config = 
		{
			type: 'line',
			data: 
			{
				labels: [<?=$show->points_x;?>],
				datasets: 
				[
					{
						label: 'Wykres kursu złota od <?=$data_json_data_beg. ' do '. $data_json_data_end;?> wykonany na podstawie danych z pliku JSON.',
						backgroundColor: 'gold',
						borderColor: 'grey',
						data: [<?=$points;?>],
						fill: false,
					}
				]
			},
			options: 
			{
				responsive: true,
				title: {
					display: false,
					text: 'Kurs złota'
				},
				tooltips: {
					mode: 'index',
					intersect: false,
				},
				hover: {
					mode: 'nearest',
					intersect: true
				},
				scales: {
					xAxes: [{
						display: true,
						scaleLabel: {
							display: false,
							labelString: 'Data'
						}
					}],
					yAxes: [{
						display: true,
						scaleLabel: {
							display: false,
							labelString: 'Cena PLN'
						}
					}]
				}
			}
		};

		window.onload = function() {
			var ctx = document.getElementById('canvas').getContext('2d');
			window.myLine = new Chart(ctx, config);
		};

		

		
		// ]]>
		</script>
	</body>
</html>