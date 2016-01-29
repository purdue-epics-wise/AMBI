
diaryConstants = { 
  'breastfeeding': { 
    'duration': ['1-2', '2-3', '5-10', '11-15'],
    'side' : [ '', 'left', 'right', 'both' ],
    'state' : [ '', 'Difficult to awake', 'Drowsy', 'Quiet and alert', 
                'Active and alert', 'Crying' ],
    'latching' : [ '', 'Not at all', 'Slipping of the breast',
                   'Latched correctly', 'Latched with nipple shield' ],
    'problems' : [ '', 'Breast tissue is soft/no milk', 'Sore nipple', 
                   'Flat/inverted nipple', 'Engorgement', 'Mastitis', 
                   'No problems' ]
  },
  'pumping': {
    'duration': ['1-2', '2-3', '5-10', '11-15'],
    'method': ['', 'Hand pump', 'Manual hand pump', 'Double electric pump',
               'Not applicable']
  },
  'supplement': {
    'type': ['', 'Expressed milk', 'Pasteurized human milk', 'Formula'],
    'method': ['', 'Bottle', 'Cup', 'Supplemental set', 'Spoon']
  },
  'output': {
    'urineColor': ['', 'Amber yellow', 'Dark yellow'],
    'urineSaturation': ['', 'Not wet at all', 'Slightly wet', 'Moderately wet',
                        'Heavily wet'],
    'stoolColor': ['', 'Black/tarry meconium', 'Black/green', 'Yellow'],
    'stoolConsistency': ['', 'Loose and seedy', 'Formed', 'Watery']
  },
  'health': {
    'type': ['', 'Jaundice', 'Decrease in body temperature', 
             'Decrease in blood glucose', 'Difficult or troubled breathing',
             'Infection', 'Dehydration', 'Weight Loss']
  }
}

function graph(type, force) {
  // only draw each graph once.
  if (graph[type] && !force)
    return;
  $.ajax({
    url:'https://lactor.org/services/diary_count.php?type=' + type,
    dataType: 'jsonp',
    success: function(response) {
      if (response.error) {
        app.showMessage(response.error);
        return;
      }
      graph[type] = true;
      // we really only want the last 7.
      response.dates = response.dates.slice(response.dates.length-7)
      $.jqplot('diary_'+type+'_chart',  [response.count], {
//        title: response.title,
        axes: {
          xaxis: {
            min: 0,
            ticks: response.dates,
            tickRenderer: $.jqplot.CanvasAxisTickRenderer,
            renderer:$.jqplot.DateAxisRenderer,
            tickOptions: {
              formatString:'%b %#d, %y',
              angle: -90,
              mark: 'inside',
              showGridline: false,
              markSize: 0
            }
          },
          yaxis: {
            min: 0,
            tickInterval: 1,
            tickOptions: {
              formatString:'%d',
              mark: 'inside',
            },
          }
        },
        series:[{
          color: '#fff', 
          shadow: false,
          markerOptions: { 
            shadow: false,
            color: '#fff'
          }
        }], 
        grid: {
          gridLineColor: '#fff',
          shadow: false,
          borderColor: '#fff',
          borderWidth: 0.75,
          background: '#862447'
        },
      });
    },
    error: function(jqxhr, status, error) {
      console.log(error);
    }
  });
}

