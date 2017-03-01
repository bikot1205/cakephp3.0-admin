var DashboardCharts = function() {

	var totalUsers = function() {
	        var chart = AmCharts.makeChart("totalUsers", {
		    "type": "serial",
		    "dataLoader": {
		         "url": SiteAdminUrl+"dashboard/totalUsers",
		        "format": "json",
		        "showErrors": true,
		        "noStyles": true,
		        "async": true,
		        
		    },
		    "theme": "light",
		    "marginTop": 30,
		    "marginRight": 30,
		    "marginLeft": 50,
		    "autoMarginOffset": 0,
		    "mouseWheelZoomEnabled":true,
		    "dataDateFormat": "YYYY-MM-DD",
		    "valueAxes": [{
		        "id": "v1",
		        "axisAlpha": 0,
		        "position": "left",
		        "ignoreAxisWidth":true
		    }],
		    
		    "graphs": [{
		        "id": "g1",
		        "balloon":{
		          "drop":true,
		          "adjustBorderColor":false,
		          "color":"#ffffff"
		        },
		        "bullet": "round",
		        "bulletBorderAlpha": 1,
		        "bulletColor": "#FFFFFF",
		        "bulletSize": 5,
		        "hideBulletsCount": 50,
		        "lineThickness": 2,
		        "title": "red line",
		        "useLineColorForBulletBorder": true,
		        "valueField": "value",
		        "balloonText": "<span style='font-size:11px;'>[[value]]</span>"
		    }],
		    "chartScrollbar": {
		        "graph": "g1",
		        "oppositeAxis":false,
		        "offset":30,
		        "scrollbarHeight": 80,
		        "backgroundAlpha": 0,
		        "selectedBackgroundAlpha": 0.1,
		        "selectedBackgroundColor": "#888888",
		        "graphFillAlpha": 0,
		        "graphLineAlpha": 1,
		        "selectedGraphFillAlpha": 0,
		        "selectedGraphLineAlpha": 1,
		        "autoGridCount":true,
		        "color":"#AAAAAA"
		    },
		    "chartCursor": {
		        "pan": true,
		        "valueLineEnabled": true,
		        "valueLineBalloonEnabled": true,
		        "cursorAlpha":1,
		        "cursorColor":"#258cbb",
		        "limitToGraph":"g1",
		        "valueLineAlpha":0.2
		    },
		    
		    "categoryField": "date",
		    "categoryAxis": {
		        "parseDates": false,
		        "dashLength": 1,
		        "minorGridEnabled": true
		    },
		    

		});

		chart.addListener("rendered", zoomChart);

		//zoomChart();

		function zoomChart() {
		    chart.zoomToIndexes(chart.dataProvider.length - 40, chart.dataProvider.length - 1);
		}				
	}

	var topRooms = function() {

		var chart = AmCharts.makeChart("topRooms", {
			  "type": "serial",
			  "theme": "light",
			  "marginRight": 15,
			  "dataLoader": {
		        "url": SiteAdminUrl+"dashboard/topRooms",
		        "format": "json",
		        "showErrors": true,
		        "noStyles": true,
		        "async": true,
		        
		    },
			  //"dataProvider": [{"country":"Acupressurist","color":"#B0DE09","visits":"1"},{"country":"Anesthesiologist","color":"#B0DE09","visits":"1"},{"country":"Ayurvedic","color":"#B0DE09","visits":"1"},{"country":"Cardiologist","color":"#B0DE09","visits":"1"},{"country":"Dentist","color":"#B0DE09","visits":"1"},{"country":"Dermatologist","color":"#B0DE09","visits":"1"},{"country":"Gastroenterologist","color":"#B0DE09","visits":"1"},{"country":"General Physician","color":"#B0DE09","visits":"2"},{"country":"Physiotherapist","color":"#B0DE09","visits":"1"},{"country":"Pulmonologist","color":"#B0DE09","visits":"1"}],
			  "valueAxes": [{
			    "axisAlpha": 0,
			    "position": "left",
			    "title": "User Count"
			  }],
			  "startDuration": 1,
			  "graphs": [{
			    "balloonText": "<b>[[country]]: [[value]]</b>",
			   // "fillColorsField": "color",
			    "fillAlphas": 0.9,
			    "lineAlpha": 0.2,
			    "type": "column",
			    "valueField": "visits"
			  }],
			  "chartCursor": {
			    "categoryBalloonEnabled": false,
			    "cursorAlpha": 0,
			    "zoomable": false
			  },
			  "categoryField": "country",
			  "categoryAxis": {
			    "gridPosition": "start",
			    "labelRotation": 45
			  },

			});
	}


	var topCountries = function() {

		var chart = AmCharts.makeChart("topCountries", {
			  "type": "serial",
			  "theme": "light",
			  "marginRight": 15,
			  "dataLoader": {
		        "url": SiteAdminUrl+"dashboard/topCountries",
		        "format": "json",
		        "showErrors": true,
		        "noStyles": true,
		        "async": true,
		        
		    },
			  //"dataProvider": [{"country":"Acupressurist","color":"#B0DE09","visits":"1"},{"country":"Anesthesiologist","color":"#B0DE09","visits":"1"},{"country":"Ayurvedic","color":"#B0DE09","visits":"1"},{"country":"Cardiologist","color":"#B0DE09","visits":"1"},{"country":"Dentist","color":"#B0DE09","visits":"1"},{"country":"Dermatologist","color":"#B0DE09","visits":"1"},{"country":"Gastroenterologist","color":"#B0DE09","visits":"1"},{"country":"General Physician","color":"#B0DE09","visits":"2"},{"country":"Physiotherapist","color":"#B0DE09","visits":"1"},{"country":"Pulmonologist","color":"#B0DE09","visits":"1"}],
			  "valueAxes": [{
			    "axisAlpha": 0,
			    "position": "left",
			    "title": "User Count"
			  }],
			  "startDuration": 1,
			  "graphs": [{
			    "balloonText": "<b>[[country]]: [[value]]</b>",
			   // "fillColorsField": "color",
			    "fillAlphas": 0.9,
			    "lineAlpha": 0.2,
			    "type": "column",
			    "valueField": "visits"
			  }],
			  "chartCursor": {
			    "categoryBalloonEnabled": false,
			    "cursorAlpha": 0,
			    "zoomable": false
			  },
			  "categoryField": "country",
			  "categoryAxis": {
			    "gridPosition": "start",
			    "labelRotation": 45
			  },

			});
	}


	var topLanguages = function() {

		var chart = AmCharts.makeChart("topLanguages", {
			  "type": "serial",
			  "theme": "light",
			  "marginRight": 15,
			  "dataLoader": {
		        "url": SiteAdminUrl+"dashboard/topLanguages",
		        "format": "json",
		        "showErrors": true,
		        "noStyles": true,
		        "async": true,
		        
		    },
			  //"dataProvider": [{"country":"Acupressurist","color":"#B0DE09","visits":"1"},{"country":"Anesthesiologist","color":"#B0DE09","visits":"1"},{"country":"Ayurvedic","color":"#B0DE09","visits":"1"},{"country":"Cardiologist","color":"#B0DE09","visits":"1"},{"country":"Dentist","color":"#B0DE09","visits":"1"},{"country":"Dermatologist","color":"#B0DE09","visits":"1"},{"country":"Gastroenterologist","color":"#B0DE09","visits":"1"},{"country":"General Physician","color":"#B0DE09","visits":"2"},{"country":"Physiotherapist","color":"#B0DE09","visits":"1"},{"country":"Pulmonologist","color":"#B0DE09","visits":"1"}],
			  "valueAxes": [{
			    "axisAlpha": 0,
			    "position": "left",
			    "title": "User Count"
			  }],
			  "startDuration": 1,
			  "graphs": [{
			    "balloonText": "<b>[[country]]: [[value]]</b>",
			   // "fillColorsField": "color",
			    "fillAlphas": 0.9,
			    "lineAlpha": 0.2,
			    "type": "column",
			    "valueField": "visits"
			  }],
			  "chartCursor": {
			    "categoryBalloonEnabled": false,
			    "cursorAlpha": 0,
			    "zoomable": false
			  },
			  "categoryField": "country",
			  "categoryAxis": {
			    "gridPosition": "start",
			    "labelRotation": 45
			  },

			});
	}

	var ageGroup = function() {

		var chart = AmCharts.makeChart("ageGroup", {
			  "type": "serial",
			  "theme": "light",
			  "marginRight": 15,
			  "dataLoader": {
		        "url": SiteAdminUrl+"dashboard/ageGroup",
		        "format": "json",
		        "showErrors": true,
		        "noStyles": true,
		        "async": true,
		        
		    },
			  //"dataProvider": [{"country":"Acupressurist","color":"#B0DE09","visits":"1"},{"country":"Anesthesiologist","color":"#B0DE09","visits":"1"},{"country":"Ayurvedic","color":"#B0DE09","visits":"1"},{"country":"Cardiologist","color":"#B0DE09","visits":"1"},{"country":"Dentist","color":"#B0DE09","visits":"1"},{"country":"Dermatologist","color":"#B0DE09","visits":"1"},{"country":"Gastroenterologist","color":"#B0DE09","visits":"1"},{"country":"General Physician","color":"#B0DE09","visits":"2"},{"country":"Physiotherapist","color":"#B0DE09","visits":"1"},{"country":"Pulmonologist","color":"#B0DE09","visits":"1"}],
			  "valueAxes": [{
			    "axisAlpha": 0,
			    "position": "left",
			    "title": "User Count"
			  }],
			  "startDuration": 1,
			  "graphs": [{
			    "balloonText": "<b>[[country]]: [[value]]</b>",
			   // "fillColorsField": "color",
			    "fillAlphas": 0.9,
			    "lineAlpha": 0.2,
			    "type": "column",
			    "valueField": "visits"
			  }],
			  "chartCursor": {
			    "categoryBalloonEnabled": false,
			    "cursorAlpha": 0,
			    "zoomable": false
			  },
			  "categoryField": "country",
			  "categoryAxis": {
			    "gridPosition": "start",
			    "labelRotation": 45
			  },

			});
	}

	var genderGroup = function() {

		var chart = AmCharts.makeChart("genderGroup", {
			  "type": "serial",
			  "theme": "light",
			  "marginRight": 15,
			  "dataLoader": {
		        "url": SiteAdminUrl+"dashboard/genderGroup",
		        "format": "json",
		        "showErrors": true,
		        "noStyles": true,
		        "async": true,
		        
		    },
			  //"dataProvider": [{"country":"Acupressurist","color":"#B0DE09","visits":"1"},{"country":"Anesthesiologist","color":"#B0DE09","visits":"1"},{"country":"Ayurvedic","color":"#B0DE09","visits":"1"},{"country":"Cardiologist","color":"#B0DE09","visits":"1"},{"country":"Dentist","color":"#B0DE09","visits":"1"},{"country":"Dermatologist","color":"#B0DE09","visits":"1"},{"country":"Gastroenterologist","color":"#B0DE09","visits":"1"},{"country":"General Physician","color":"#B0DE09","visits":"2"},{"country":"Physiotherapist","color":"#B0DE09","visits":"1"},{"country":"Pulmonologist","color":"#B0DE09","visits":"1"}],
			  "valueAxes": [{
			    "axisAlpha": 0,
			    "position": "left",
			    "title": "User Count"
			  }],
			  "startDuration": 1,
			  "graphs": [{
			    "balloonText": "<b>[[country]]: [[value]]</b>",
			   // "fillColorsField": "color",
			    "fillAlphas": 0.9,
			    "lineAlpha": 0.2,
			    "type": "column",
			    "valueField": "visits"
			  }],
			  "chartCursor": {
			    "categoryBalloonEnabled": false,
			    "cursorAlpha": 0,
			    "zoomable": false
			  },
			  "categoryField": "country",
			  "categoryAxis": {
			    "gridPosition": "start",
			    "labelRotation": 45
			  },

			});
	}


	

			
	

    return {
        //main function to initiate the module

        init: function() {
            totalUsers();
            topRooms();
            topCountries();
            topLanguages();
            ageGroup();
            genderGroup();
        }

    };

}();

