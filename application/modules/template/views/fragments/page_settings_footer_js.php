
        <!-- base vendor bundle: 
            DOC: if you remove pace.js from core please note on Internet Explorer some CSS animations may execute before a page is fully loaded, resulting 'jump' animations 
                        + pace.js (recommended)
                        + jquery.js (core)
                        + jquery-ui-cust.js (core)
                        + popper.js (core)
                        + bootstrap.js (core)
                        + slimscroll.js (extension)
                        + app.navigation.js (core)
                        + ba-throttle-debounce.js (core)
                        + waves.js (extension)
                        + smartpanels.js (extension)
                        + src/../jquery-snippets.js (core) --> 
        <script src="<?= base_url() ?>assets/js/vendors.bundle.js"></script>
        <script src="<?= base_url() ?>assets/js/app.bundle.js"></script>
        <script src="<?= base_url() ?>assets/js/json-path-picker/json-path-picker.js"></script>
        <script type="text/javascript">
            $(document).ready(function()
            {
                const $pathTarget = document.querySelectorAll('.path');
                const $source = document.querySelector('#json-renderer');
                const filename = "<?= base_url() ?>assets/project-structure";
                const defaultOpts = {
                    pathNotation: 'dots',
                    pathQuotesType: 'single',
                    processKeys: false,
                    outputCollapsed: true
                };

                $.getJSON(filename + ".json").then(function(data)
                {
                    let jsonData = null;
                    jsonData = JSON.parse(JSON.stringify(data))
                    JPPicker.render($source, jsonData, $pathTarget, defaultOpts);
                }).fail(function()
                {
                    console.log("failed");
                });
            });

        </script>

        <script>
            /* infinite nav pills */
            $('.user-tables-data').menuSlider(
            {
                element: $('.user-tables-data'),
                wrapperId: 'test-nav'
            });


            var ng_bgColors,
                ng_bgColors_URL = "<?php echo base_url()?>assets/ng-bg-colors.json",
                formatBgColors = [];

            $.when(
                $.getJSON(ng_bgColors_URL, function(data)
                {
                    ng_bgColors = data;
                })
            ).then(function()
            {
                if (ng_bgColors)
                {

                    formatBgColors.push($('<option></option>').attr("value", null).text("select background"));

                    //formatTextColors
                    jQuery.each(ng_bgColors, function(index, item)
                    {
                        formatBgColors.push($('<option></option>').attr("value", item).addClass(item).text(item))
                    });

                    $("select.js-bg-color").empty().append(formatBgColors);

                }
                else
                {
                    console.log("somethign went wrong!")
                }
            });

            /* change background */
            $(document).on('change', '.js-bg-color', function()
            {
                var setBgColor = $('select.js-bg-color').val();
                var setValue = $('select.js-bg-target').val();

                $('select.js-bg-color').removeClassPrefix('bg-').addClass(setBgColor);
                $(setValue).removeClassPrefix('bg-').addClass(setBgColor);
            })

            /* change border */
            $(document).on('change', '.js-border-color', function()
            {
                var setBorderColor = $('select.js-border-color').val();
                $("#cp-2").removeClassPrefix('border-').addClass(setBorderColor);
                $('select.js-border-color').removeClassPrefix('border-').addClass(setBorderColor);
            })

            /* change target */
            $(document).on('change', '.js-bg-target', function()
            {
                //reset color selection
                $('select.js-bg-color').prop('selectedIndex', 0).removeClassPrefix('bg-');
            })

        </script>
        
<script src="<?= base_url() ?>assets/js/datagrid/datatables/datatables.bundle.js"></script>
<script src="<?php echo base_url() ?>assets/js/notify.min.js" type="text/javascript"></script>
<!-- <script src="<?php echo base_url() ?>assets/js/js/select2.min.js" type="text/javascript"></script> -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<!-- End Theme label Script-->
<script type="text/javascript">
    $(document).ready(function () {
        $('.select2').select2();
       
    });

</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('.select2-multiple').select2();

    });
</script>
<script type='text/javascript'>
    $(document).ready(function() {
         $('.carousel').carousel({
             interval: <?php echo $setting->slider_timer; ?>
        })
    });

        Highcharts.setOptions({
            colors: ['#90ed7d', '#434348', '#f7a35c', '#8085e9', '#f15c80', '#e4d354', '#2b908f', '#f45b5b', '#91e8e1', '#1aadce', '#492970', '#f28f43', '#77a1e5', '#c42525', '#a6c96a', '#4572A7', '#AA4643', '#89A54E', '#80699B', '#3D96AE', '#DB843D', '#92A8CD', '#A47D7C', '#B5CA92', '#058DC7', '#50B432', '#ED561B', '#DDDF00']

        });

    function getSubs(val) {

        $.ajax({
            method: "GET",
            url: "<?php echo base_url(); ?>kpi/get_cat_subjects",
            data: val,
            success: function (data) {
                console.log(data);
                $(".cat_subject_areas").html(data);
            }
            //  console.log('iwioowiiwoow');
        });
    }



</script>



