$.get( "getdata.php", function( json ) {
    Morris.Line({
        element: 'morris-one-line-chart',
            data: json,
        xkey: 'year',
        ykeys: ['value'],
        resize: true,
        lineWidth:4,
        labels: ['Value'],
        lineColors: ['#1ab394'],
        pointSize:5,
    });


});
