<script>
    /**
     * This script should be placed right after the body tag for fast execution 
     * Note: the script is written in pure javascript and does not depend on thirdparty library
     **/
    'use strict';

    var classHolder = document.getElementsByTagName("BODY")[0],
        /** 
         * Load from localstorage
         **/
        themeSettings = (localStorage.getItem('themeSettings')) ? JSON.parse(localStorage.getItem('themeSettings')) : {},
        themeURL = themeSettings.themeURL || '<?php echo base_url()?>assets/css/themes/cust-theme-2.css', // Set the default theme URL to Atlantis
        themeOptions = themeSettings.themeOptions || 'theme-light'; // Set the default theme options to light mode

    /** 
     * Load theme options
     **/
    if (themeSettings.themeOptions) {
        classHolder.className = themeSettings.themeOptions;
        console.log("%c✔ Theme settings loaded", "color: #148f32");
    }
    else {
        classHolder.className = themeOptions; // Apply default theme options
        console.log("%c✔ Heads up! Theme settings is empty or does not exist, loading default settings...", "color: #ed1c24");
    }

    if (themeSettings.themeURL && !document.getElementById('Atlantis')) {
        var cssfile = document.createElement('link');
        cssfile.id = 'Atlantis';
        cssfile.rel = 'stylesheet';
        cssfile.href = themeSettings.themeURL;
        document.getElementsByTagName('head')[0].appendChild(cssfile);
    }
    else if (!themeSettings.themeURL && !document.getElementById('Atlantis')) {
        var cssfile = document.createElement('link');
        cssfile.id = 'Atlantis';
        cssfile.rel = 'stylesheet';
        cssfile.href = themeURL; // Apply default theme URL
        document.getElementsByTagName('head')[0].appendChild(cssfile);
    }
    else if (themeSettings.themeURL && document.getElementById('Atlantis')) {
        document.getElementById('Atlantis').href = themeSettings.themeURL;
    }

    /** 
     * Save to localstorage 
     **/
    var saveSettings = function () {
        themeSettings.themeOptions = String(classHolder.className).split(/[^\w-]+/).filter(function (item) {
            return /^(nav|header|footer|mod|display)-/i.test(item);
        }).join(' ');
        if (document.getElementById('Atlantis')) {
            themeSettings.themeURL = document.getElementById('Atlantis').getAttribute("href");
        };
        localStorage.setItem('themeSettings', JSON.stringify(themeSettings));
    }

    /** 
     * Reset settings
     **/
    var resetSettings = function () {
        localStorage.setItem("themeSettings", "");
    }

</script>