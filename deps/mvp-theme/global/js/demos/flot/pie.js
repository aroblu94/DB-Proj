$(function () {

	var data, chartOptions

	data = [
		{ label: "Product 1", data: Math.floor (Math.random() * 100 + 250) }, 
		{ label: "Product 2", data: Math.floor (Math.random() * 100 + 350) }, 
		{ label: "Product 3", data: Math.floor (Math.random() * 100 + 650) }, 
		{ label: "Product 4", data: Math.floor (Math.random() * 100 + 50) }
	]

	chartOptions = {		
		series: {
			pie: {
				show: true,  
				innerRadius: 0, 
				stroke: {
					width: 4
				}
			}
		}, 
		legend: {
			show: false,
			position: 'ne'
		}, 
		tooltip: true,
		tooltipOpts: {
			content: '%s: %y'
		},
		grid: {
			hoverable: true
		},
		colors: mvpready_core.layoutColors
	}

	var holder = $('#pie-chart')

	if (holder.length) {
		$.plot(holder, data, chartOptions )
	}


})