
<script src="<?= base_url() ?>assets/js/datagrid/datatables/datatables.bundle.js"></script>
<script>
            (function(i, s, o, g, r, a, m)
            {
                i['GoogleAnalyticsObject'] = r;
                i[r] = i[r] || function()
                {
                    (i[r].q = i[r].q || []).push(arguments)
                }, i[r].l = 1 * new Date();
                a = s.createElement(o),
                    m = s.getElementsByTagName(o)[0];
                a.async = 1;
                a.src = g;
                m.parentNode.insertBefore(a, m)
            })(window, document, 'script', '../../../../www.google-analytics.com/analytics.js', 'ga');

            ga('create', 'UA-141754477-1', 'auto');
            ga('send', 'pageview');

        </script>
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
                const filename = "media/data/project-structure";
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
                ng_bgColors_URL = "media/data/ng-bg-colors.json",
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