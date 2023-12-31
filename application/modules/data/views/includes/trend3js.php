<?php

?>
<script>
function renderGraph(data) {

    Highcharts.chart('line<?php echo $chartkpi; ?>', {
    
        title: {
            text: '<?php echo trim($title); ?>'
        },
        chart: {
            height: 550,
            type: '<?php echo $_SESSION['dimension_chart'] ?>'

        },
        tooltip: {
         valueSuffix: '<?php echo " " ?>'
        },
      title: {
            text: ''
        },
      
        yAxis: {
            title: {
                text: 'performance (%)'
            }
        },

        xAxis: {

            categories: data.quaters
        },


        legend: {
            align: 'center',
            verticalAlign: 'bottom',
            x: 0,
            y: 0
        },

        plotOptions: {
            series: {
                label: {
                    connectorAllowed: false
                },
                enableMouseTracking: true

            }
        },
        credits: {
            enabled: false
        },

        series: data.data


    });
};

$(document).ready(function() {



    $.ajax({
        url: '<?php echo base_url() . "data/dim3data/" . $this->uri->segment(3); ?>',
        success: function(response) {
            console.log(response);
            renderGraph(JSON.parse(response));
        }
    });

});
</script>


<script>
$("#trend3").submit(function(e) {

    e.preventDefault(); // avoid to execute the actual submit of the form.

    var form = $(this);
    var actionUrl = form.attr('action');

    $.ajax({
        type: "POST",
        url: '<?php echo base_url() . "data/dim3data/" . $this->uri->segment(3); ?>',
        data: form.serialize(), // serializes the form's elements.
        success: function(data) {
            renderGraph(JSON.parse(data));
            console.log(data); // show response from the php script.
        }
    });

});
</script>