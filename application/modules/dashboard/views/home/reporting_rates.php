<style>
    .vertical {
        border-left: 6px solid blue;
        height: 200px;
        position: absolute;
    }

    .table {
        border-collapse: collapse;
    }

    .table th,
    .table td {
        padding: 0.3em;
        border: 1px solid #E1DDDC;
        text-align: left;
    }

    .table th {
        background-color: #f2f2f2;
    }

    .table th:first-child,
    .table td:first-child {
        text-align: left;
    }

    .table th[colspan],
    .table td[colspan] {
        background-color: #d9d9d9;
    }

    .table th[rowspan],
    .table td[rowspan] {
        vertical-align: middle;
    }

    .table tbody tr:nth-child(even) {
        background-color: #f9f9f9;
        padding: 0.5rem !important;
    }

    /* Loading indicator styles */
    .loading-container {
        text-align: center;
        padding: 40px;
    }

    .spinner {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3498db;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        animation: spin 1s linear infinite;
        margin: 0 auto;
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    .facility-container {
        opacity: 0;
        animation: fadeIn 0.5s ease-in forwards;
    }

    @keyframes fadeIn {
        to { opacity: 1; }
    }
</style>

<div class="mt-4">
    <div id="employee_data">
        <h2>Performance Report</h2>

        <?php
        $this->load->view('dashboard/home/partials/filters');
        ?>

            <?php
            // Calculate previous financial year as default
            $current_date = date('Y-m-d');
            $current_year = date('Y', strtotime($current_date));
            if (date('m-d', strtotime($current_date)) < '07-01') {
                // Before July 1st, previous FY is (current_year-2)-(current_year-1)
                $default_financial_year = ($current_year - 2) . '-' . ($current_year - 1);
            } else {
                // After July 1st, previous FY is (current_year-1)-current_year
                $default_financial_year = ($current_year - 1) . '-' . $current_year;
            }

            $kpi_group = $this->input->get('kpi_group');
            $kpi_id = $this->input->get('kpi_id');
            $financial_year = $this->input->get('financial_year');
            
            // Set default financial year to previous year if not provided
            if (empty($financial_year)) {
                $financial_year = $default_financial_year;
            }
            ?>
        
            <br><br>
        <div id="kpi-info-header">
            <?php if (!empty($kpi_id)): 
                $kpi_info = getkpi_info($kpi_id);
                $kpi_name = ($kpi_info && isset($kpi_info->short_name)) ? $kpi_info->short_name : 'Unknown KPI';
                
                // Get numerator and denominator descriptions
                $numerator_desc = '';
                $denominator_desc = '';
                
                // First try to get from performanace_data
                $ci =& get_instance();
                $perf_query = $ci->db->query("SELECT DISTINCT numerator_description, denominator_description 
                                                FROM performanace_data 
                                                WHERE kpi_id = " . $ci->db->escape($kpi_id) . " 
                                                AND numerator_description IS NOT NULL 
                                                AND numerator_description != '' 
                                                LIMIT 1");
                if ($perf_query && $perf_query->num_rows() > 0) {
                    $perf = $perf_query->row();
                    $numerator_desc = isset($perf->numerator_description) ? $perf->numerator_description : '';
                    $denominator_desc = isset($perf->denominator_description) ? $perf->denominator_description : '';
                }
                
                // Fallback to kpi table if not found
                if (empty($numerator_desc) && isset($kpi_info->numerator)) {
                    $numerator_desc = $kpi_info->numerator;
                }
                if (empty($denominator_desc) && isset($kpi_info->denominator)) {
                    $denominator_desc = $kpi_info->denominator;
                }
            ?>
                <h5>Showing KPI: <?= htmlspecialchars($kpi_name, ENT_QUOTES, 'UTF-8'); ?></h5>
                <script>
                    // Store KPI name and descriptions for JavaScript
                    window.phpKpiName = <?= json_encode($kpi_name, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
                    // Initialize descriptions from PHP if available
                    if (typeof window.facilityReportingKpiDescriptions === 'undefined') {
                        window.facilityReportingKpiDescriptions = {
                            numerator: '',
                            denominator: ''
                        };
                    }
                    // Set descriptions from PHP if they exist
                    window.facilityReportingKpiDescriptions.numerator = <?= json_encode($numerator_desc, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
                    window.facilityReportingKpiDescriptions.denominator = <?= json_encode($denominator_desc, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT); ?>;
                    console.log('PHP-loaded descriptions:', {
                        numerator: window.facilityReportingKpiDescriptions.numerator,
                        denominator: window.facilityReportingKpiDescriptions.denominator
                    });
                </script>
            <?php else: ?>
                <h5>Showing All KPIs</h5>
            <?php endif; ?>
            <?php 
            $job_category = kpi_job_category($kpi_group);
        $job_name = ($job_category && isset($job_category->job)) ? $job_category->job : 'Unknown Category';
        ?>
            <h6>Job Category: <?= htmlspecialchars($job_name, ENT_QUOTES, 'UTF-8'); ?></h6>
        </div>
        <hr>

        <!-- Search and Pagination Controls -->
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" id="facility-search" class="form-control" placeholder="Search facilities..." value="<?= $this->input->get('search') ?>">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button" id="search-btn">
                            <i class="fa fa-search"></i> Search
                        </button>
                        <button class="btn btn-secondary" type="button" id="clear-search-btn">
                            <i class="fa fa-times"></i> Clear
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-6 text-right">
                <div id="pagination-info" class="mt-2"></div>
            </div>
        </div>

        <!-- Loading indicator -->
        <div id="loading-indicator" class="loading-container">
            <div class="spinner"></div>
            <p>Loading performance data...</p>
        </div>

        <!-- Data container -->
        <div id="facilities-container"></div>

        <!-- Pagination container -->
        <div id="pagination-container" class="mt-4"></div>

        <!-- Error message container -->
        <div id="error-message" class="alert alert-danger" style="display: none;"></div>
    </div>
</div>

<?php $this->load->view('dashboard/home/partials/excel_util') ?>

<script>
(function() {
    'use strict';

    // Get URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    const kpiGroup = urlParams.get('kpi_group') || '';
    let kpiId = urlParams.get('kpi_id') || '';
    const financialYear = urlParams.get('financial_year') || '<?= $financial_year ?>';
    
    // Log for debugging
    console.log('Filter values:', { kpiGroup, kpiId, financialYear });
    
    // Pagination state
    let currentPage = parseInt(urlParams.get('page')) || 0;
    let currentSearch = urlParams.get('search') || '';
    const perPage = 20;
    
    // Store KPI numerator and denominator descriptions globally
    // Use window object to avoid redeclaration errors if script loads twice
    // Note: PHP may have already set these values above, so only initialize if not already set
    if (typeof window.facilityReportingKpiDescriptions === 'undefined') {
        window.facilityReportingKpiDescriptions = {
            numerator: '',
            denominator: ''
        };
    } else {
        // Preserve any values that were set by PHP (they should already be there)
        console.log('Descriptions already initialized:', window.facilityReportingKpiDescriptions);
    }

    // Helper function to get color based on performance
    function getColorBasedOnPerformance(value, target) {
        const numValue = value !== null && value !== undefined ? parseFloat(value) : null;
        const numTarget = target !== null && target !== undefined ? parseFloat(target) : null;
        
        if (numValue === null || isNaN(numValue) || numValue == 0) {
            return '#FF0000'; // Red
        }
        
        if (numTarget === null || isNaN(numTarget)) {
            return '#FF0000'; // Red if no target
        }
        
        if ((numValue - numTarget) >= 0) {
            return '#008000'; // Green
        } else if (numValue - numTarget >= -10) {
            return '#FFA500'; // Orange
        } else {
            return '#FF0000'; // Red
        }
    }

    // Helper function to format score
    function formatScore(score) {
        if (score === null || score === undefined || score === '') {
            return '';
        }
        const numScore = parseFloat(score);
        if (isNaN(numScore)) {
            return '';
        }
        return Math.round(numScore);
    }
    
    // Function to update KPI info header dynamically - returns a Promise
    function updateKpiInfoHeader(kpiId, kpiGroup) {
        const headerDiv = document.getElementById('kpi-info-header');
        if (!headerDiv) {
            return Promise.resolve();
        }
        
        if (kpiId && kpiId !== '') {
            // Fetch KPI info via AJAX and return a Promise
            return fetch('<?= base_url("dashboard/home/get_kpi_info") ?>?kpi_id=' + encodeURIComponent(kpiId))
                .then(function(response) {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(function(data) {
                    if (data.success && data.kpi) {
                        const kpiName = data.kpi.short_name || data.kpi.full_name || 'Unknown KPI';
                        const jobCategory = '<?= htmlspecialchars($job_name ?? "N/A", ENT_QUOTES, "UTF-8") ?>';
                        headerDiv.innerHTML = '<h5>Showing KPI: ' + kpiName + '</h5><h6>Job Category: ' + jobCategory + '</h6>';
                        
                        // Store numerator and denominator descriptions globally
                        window.facilityReportingKpiDescriptions.numerator = data.kpi.numerator_description || '';
                        window.facilityReportingKpiDescriptions.denominator = data.kpi.denominator_description || '';
                        
                        // Mark as loaded
                        window.facilityReportingKpiDescriptionsLoaded = true;
                        
                        console.log('KPI descriptions loaded:', {
                            numerator: window.facilityReportingKpiDescriptions.numerator,
                            denominator: window.facilityReportingKpiDescriptions.denominator,
                            fullData: data.kpi
                        });
                    } else {
                        // Try to get KPI name from PHP-loaded data if available
                        const phpKpiName = window.phpKpiName || '';
                        if (phpKpiName) {
                            headerDiv.innerHTML = '<h5>Showing KPI: ' + phpKpiName + '</h5>';
                        } else {
                            headerDiv.innerHTML = '<h5>Showing KPI: Unknown</h5>';
                        }
                        window.facilityReportingKpiDescriptions.numerator = '';
                        window.facilityReportingKpiDescriptions.denominator = '';
                    }
                })
                .catch(function(error) {
                    console.error('Error loading KPI info:', error);
                    // Try to get KPI name from PHP-loaded data if available
                    const phpKpiName = window.phpKpiName || '';
                    if (phpKpiName) {
                        headerDiv.innerHTML = '<h5>Showing KPI: ' + phpKpiName + '</h5>';
                    } else {
                        headerDiv.innerHTML = '<h5>Showing KPI: Unknown</h5>';
                    }
                    window.facilityReportingKpiDescriptions.numerator = '';
                    window.facilityReportingKpiDescriptions.denominator = '';
                });
        } else {
            headerDiv.innerHTML = '<h5>Showing All KPIs</h5>';
            window.facilityReportingKpiDescriptions.numerator = '';
            window.facilityReportingKpiDescriptions.denominator = '';
            return Promise.resolve();
        }
    }

    // Render facility table
    function renderFacility(facility, kpiId) {
        // kpiId is a string - empty string means show all KPIs, otherwise it's a specific KPI ID
        const hasSpecificKpi = kpiId && kpiId !== '';
        
        // Get descriptions from window object
        const numDesc = window.facilityReportingKpiDescriptions.numerator || '';
        const denDesc = window.facilityReportingKpiDescriptions.denominator || '';
        
        console.log('renderFacility - Descriptions:', {
            hasSpecificKpi: hasSpecificKpi,
            numerator: numDesc,
            denominator: denDesc
        });
        
        let html = `
            <div class="row mt-4 facility-container">
                    <div class="col">
                    <h3>${facility.facility} - ${financialYear}</h3>
                        <table class="table table-bordered">
                            <tr>
                            <th colspan="2">${hasSpecificKpi ? '' : 'KPI Details'}</th>
                                <td colspan="3">Q1</td>
                                <td colspan="3">Q2</td>
                                <td colspan="3">Q3</td>
                                <td colspan="3">Q4</td>
                            </tr>
                            <tr>
                            <th>Staff</th>
                                <th>${hasSpecificKpi ? 'Numerator(N)/Denominator(D)' : 'KPI'}</th>
                                <td>Data</td>
                                <td>Score</td>
                                <td>Target</td>
                                <td>Data</td>
                                <td>Score</td>
                                <td>Target</td>
                                <td>Data</td>
                                <td>Score</td>
                                <td>Target</td>
                                <td>Data</td>
                                <td>Score</td>
                                <td>Target</td>
                            </tr>
        `;

        let staffIndex = 1;
        facility.staff.forEach(function(staff) {
            const q1 = staff.performance.Q1 || {};
            const q2 = staff.performance.Q2 || {};
            const q3 = staff.performance.Q3 || {};
            const q4 = staff.performance.Q4 || {};

            const q1Color = getColorBasedOnPerformance(q1.score, q1.data_target);
            const q2Color = getColorBasedOnPerformance(q2.score, q2.data_target);
            const q3Color = getColorBasedOnPerformance(q3.score, q3.data_target);
            const q4Color = getColorBasedOnPerformance(q4.score, q4.data_target);

            html += `
                <tr>
                    <th rowspan="2">${staffIndex++}. ${staff.surname} ${staff.firstname}</th>
                    <td>${hasSpecificKpi ? (numDesc && numDesc.trim() !== '' ? 'N: ' + numDesc : 'N: Numerator') : 'N/A'}</td>
                    <td>${q1.numerator !== null && q1.numerator !== undefined ? q1.numerator : ''}</td>
                    <td rowspan="2" style="font-weight:bold; color:#FFF; background-color: ${q1Color}">
                        ${formatScore(q1.score)}
                        ${q1.comment ? '<i class="fa fa-info-circle" title="' + (q1.comment || '').replace(/"/g, '&quot;') + '" aria-hidden="true"></i>' : ''}
                                    </td>
                    <td rowspan="2">${q1.data_target !== null && q1.data_target !== undefined ? q1.data_target : ''}</td>
                    <td>${q2.numerator !== null && q2.numerator !== undefined ? q2.numerator : ''}</td>
                    <td rowspan="2" style="font-weight:bold; color:#FFF; background-color: ${q2Color}">
                        ${formatScore(q2.score)}
                        ${q2.comment ? '<i class="fa fa-info-circle" title="' + (q2.comment || '').replace(/"/g, '&quot;') + '" aria-hidden="true"></i>' : ''}
                                    </td>
                    <td rowspan="2">${q2.data_target !== null && q2.data_target !== undefined ? q2.data_target : ''}</td>
                    <td>${q3.numerator !== null && q3.numerator !== undefined ? q3.numerator : ''}</td>
                    <td rowspan="2" style="font-weight:bold; color:#FFF; background-color: ${q3Color}">
                        ${formatScore(q3.score)}
                        ${q3.comment ? '<i class="fa fa-info-circle" title="' + (q3.comment || '').replace(/"/g, '&quot;') + '" aria-hidden="true"></i>' : ''}
                                    </td>
                    <td rowspan="2">${q3.data_target !== null && q3.data_target !== undefined ? q3.data_target : ''}</td>
                    <td>${q4.numerator !== null && q4.numerator !== undefined ? q4.numerator : ''}</td>
                    <td rowspan="2" style="font-weight:bold; color:#FFF; background-color: ${q4Color}">
                        ${formatScore(q4.score)}
                        ${q4.comment ? '<i class="fa fa-info-circle" title="' + (q4.comment || '').replace(/"/g, '&quot;') + '" aria-hidden="true"></i>' : ''}
                                    </td>
                    <td rowspan="2">${q4.data_target !== null && q4.data_target !== undefined ? q4.data_target : ''}</td>
                                </tr>
                <tr style="border-bottom:2px solid #FDE693;">
                    <td>${hasSpecificKpi ? (denDesc && denDesc.trim() !== '' ? 'D: ' + denDesc : 'D: Denominator') : 'N/A'}</td>
                    <td>${q1.denominator !== null && q1.denominator !== undefined ? q1.denominator : ''}</td>
                    <td>${q2.denominator !== null && q2.denominator !== undefined ? q2.denominator : ''}</td>
                    <td>${q3.denominator !== null && q3.denominator !== undefined ? q3.denominator : ''}</td>
                    <td>${q4.denominator !== null && q4.denominator !== undefined ? q4.denominator : ''}</td>
                                </tr>
            `;
        });

        html += `
                        </table>
                </div>
        </div>
        `;

        return html;
    }

    // Render pagination controls
    function renderPagination(pagination) {
        const paginationContainer = document.getElementById('pagination-container');
        const paginationInfo = document.getElementById('pagination-info');
        
        if (!pagination || pagination.total_pages <= 1) {
            paginationContainer.innerHTML = '';
            if (pagination) {
                paginationInfo.innerHTML = `Showing ${pagination.total_count} facility(ies)`;
            }
            return;
        }

        const totalPages = pagination.total_pages;
        const currentPageNum = pagination.current_page;
        const totalCount = pagination.total_count;
        const startRecord = (currentPageNum * perPage) + 1;
        const endRecord = Math.min((currentPageNum + 1) * perPage, totalCount);

        // Update pagination info
        paginationInfo.innerHTML = `Showing ${startRecord}-${endRecord} of ${totalCount} facilities`;

        // Build pagination HTML
        let paginationHtml = '<nav><ul class="pagination justify-content-center">';

        // Previous button
        if (currentPageNum > 0) {
            paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-page="${currentPageNum - 1}">Previous</a></li>`;
        } else {
            paginationHtml += '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
        }

        // Page numbers
        const maxPagesToShow = 10;
        let startPage = Math.max(0, currentPageNum - Math.floor(maxPagesToShow / 2));
        let endPage = Math.min(totalPages - 1, startPage + maxPagesToShow - 1);
        
        if (endPage - startPage < maxPagesToShow - 1) {
            startPage = Math.max(0, endPage - maxPagesToShow + 1);
        }

        if (startPage > 0) {
            paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-page="0">1</a></li>`;
            if (startPage > 1) {
                paginationHtml += '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
        }

        for (let i = startPage; i <= endPage; i++) {
            if (i === currentPageNum) {
                paginationHtml += `<li class="page-item active"><span class="page-link">${i + 1}</span></li>`;
            } else {
                paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-page="${i}">${i + 1}</a></li>`;
            }
        }

        if (endPage < totalPages - 1) {
            if (endPage < totalPages - 2) {
                paginationHtml += '<li class="page-item disabled"><span class="page-link">...</span></li>';
            }
            paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-page="${totalPages - 1}">${totalPages}</a></li>`;
        }

        // Next button
        if (currentPageNum < totalPages - 1) {
            paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-page="${currentPageNum + 1}">Next</a></li>`;
        } else {
            paginationHtml += '<li class="page-item disabled"><span class="page-link">Next</span></li>';
        }

        paginationHtml += '</ul></nav>';
        paginationContainer.innerHTML = paginationHtml;

        // Attach click handlers
        paginationContainer.querySelectorAll('a.page-link[data-page]').forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const page = parseInt(this.getAttribute('data-page'));
                currentPage = page;
                loadFacilityData();
            });
        });
    }

    // Load data via AJAX
    function loadFacilityData() {
        const container = document.getElementById('facilities-container');
        const loadingIndicator = document.getElementById('loading-indicator');
        const errorMessage = document.getElementById('error-message');
        const searchInput = document.getElementById('facility-search');

        // Update search from input if changed
        if (searchInput && searchInput.value !== currentSearch) {
            currentSearch = searchInput.value;
            currentPage = 0; // Reset to first page on new search
        }

        // Show loading indicator
        loadingIndicator.style.display = 'block';
        errorMessage.style.display = 'none';
        container.innerHTML = '';
        document.getElementById('pagination-container').innerHTML = '';

        // Build AJAX URL
        const ajaxUrl = '<?= base_url("dashboard/home/ajax_facility_data") ?>' + 
                       '?kpi_group=' + encodeURIComponent(kpiGroup) +
                       '&kpi_id=' + encodeURIComponent(kpiId) +
                       '&financial_year=' + encodeURIComponent(financialYear) +
                       '&search=' + encodeURIComponent(currentSearch) +
                       '&page=' + currentPage +
                       '&per_page=' + perPage;

        // Update URL without reload
        const newUrl = new URL(window.location);
        newUrl.searchParams.set('page', currentPage);
        if (currentSearch) {
            newUrl.searchParams.set('search', currentSearch);
        } else {
            newUrl.searchParams.delete('search');
        }
        window.history.pushState({}, '', newUrl);

        // Make AJAX request
        fetch(ajaxUrl)
            .then(function(response) {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(function(data) {
                loadingIndicator.style.display = 'none';
                console.log('AJAX Response:', data);
                if (data.debug) {
                    console.log('Debug info:', data.debug);
                }

                if (data.success) {
                    if (data.data && data.data.length > 0) {
                        console.log('Rendering ' + data.data.length + ' facilities');
                        
                        // Update KPI info header dynamically (this will also set descriptions)
                        // We need to wait for this to complete before rendering facilities
                        if (kpiId && kpiId !== '') {
                            // Check if descriptions are already loaded (from initial page load)
                            if (window.facilityReportingKpiDescriptionsLoaded && 
                                window.facilityReportingKpiDescriptions.numerator) {
                                console.log('Descriptions already loaded, rendering immediately');
                                renderFacilities(data.data, kpiId, data.pagination);
                            } else {
                                // First update the header to get descriptions, then render
                                updateKpiInfoHeader(kpiId, kpiGroup)
                                    .then(function() {
                                        // Mark as loaded
                                        window.facilityReportingKpiDescriptionsLoaded = true;
                                        // Descriptions are now loaded, render facilities
                                        console.log('Descriptions loaded, rendering facilities');
                                        renderFacilities(data.data, kpiId, data.pagination);
                                    })
                                    .catch(function(error) {
                                        console.error('Error updating KPI header:', error);
                                        // Render anyway even if header update failed
                                        renderFacilities(data.data, kpiId, data.pagination);
                                    });
                            }
                        } else {
                            renderFacilities(data.data, kpiId, data.pagination);
                        }
                    } else {
                        const debugMsg = data.debug ? ' (Filters: ' + JSON.stringify(data.debug.filters) + ', Facilities found: ' + data.debug.facilities_count + ')' : '';
                        container.innerHTML = '<div class="alert alert-info">No facilities found for the selected criteria. Please check your filters.' + debugMsg + '</div>';
                        if (data.pagination) {
                            renderPagination(data.pagination);
                        }
                    }
                } else {
                    // Handle error response
                    const errorMsg = data.error || 'Failed to load data';
                    const debugInfo = data.debug ? ' Debug: ' + JSON.stringify(data.debug) : '';
                    container.innerHTML = '<div class="alert alert-danger">' + errorMsg + debugInfo + '</div>';
                    errorMessage.textContent = errorMsg;
                    errorMessage.style.display = 'block';
                }
            })
            .catch(function(error) {
                console.error('Error loading data:', error);
                loadingIndicator.style.display = 'none';
                errorMessage.textContent = 'Failed to load performance data: ' + error.message + '. Please check the console for details.';
                errorMessage.style.display = 'block';
                container.innerHTML = '<div class="alert alert-danger">Failed to load data. Please check your filters and try again.</div>';
            });
    }
    
    // Function to render facilities (called after KPI info is loaded)
    function renderFacilities(facilities, kpiId, pagination) {
        // Get container element
        const container = document.getElementById('facilities-container');
        if (!container) {
            console.error('Container element not found');
            return;
        }
        
        // Log current descriptions before rendering
        console.log('Rendering facilities with descriptions:', {
            numerator: window.facilityReportingKpiDescriptions.numerator,
            denominator: window.facilityReportingKpiDescriptions.denominator,
            kpiId: kpiId
        });
        
        // When kpiId is empty, we show all KPIs grouped by KPI
        // When kpiId is provided, we show data for that specific KPI
        let html = '';
        facilities.forEach(function(facility, index) {
            console.log('Facility ' + index + ':', facility.facility, 'Staff count:', facility.staff ? facility.staff.length : 0);
            if (facility.staff && facility.staff.length > 0) {
                console.log('First staff member:', facility.staff[0]);
                console.log('First staff performance Q1:', facility.staff[0].performance ? facility.staff[0].performance.Q1 : 'No performance data');
                console.log('First staff performance Q2:', facility.staff[0].performance ? facility.staff[0].performance.Q2 : 'No performance data');
                console.log('KPI ID being used:', kpiId);
            }
            // Render immediately for faster display
            // Pass kpiId as string (empty string means show all KPIs)
            const facilityHtml = renderFacility(facility, kpiId);
            html += facilityHtml;
        });
        container.innerHTML = html;

        // Render pagination
        if (pagination) {
            renderPagination(pagination);
        }
    }

    // Load KPI info first if kpiId is provided, then load facility data
    function initializePage() {
        if (kpiId && kpiId !== '') {
            // Load KPI info first to get descriptions
            updateKpiInfoHeader(kpiId, kpiGroup)
                .then(function() {
                    // KPI info loaded, now load facility data
                    loadFacilityData();
                })
                .catch(function(error) {
                    console.error('Error loading KPI info on init:', error);
                    // Load facility data anyway
                    loadFacilityData();
                });
        } else {
            // No KPI selected, just load facility data
            loadFacilityData();
        }
        setupEventListeners();
    }
    
    // Load data when page is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            initializePage();
        });
    } else {
        initializePage();
    }

    // Setup event listeners
    function setupEventListeners() {
        // Search button
        const searchBtn = document.getElementById('search-btn');
        if (searchBtn) {
            searchBtn.addEventListener('click', function() {
                currentPage = 0;
                loadFacilityData();
            });
        }

        // Clear search button
        const clearSearchBtn = document.getElementById('clear-search-btn');
        if (clearSearchBtn) {
            clearSearchBtn.addEventListener('click', function() {
                const searchInput = document.getElementById('facility-search');
                if (searchInput) {
                    searchInput.value = '';
                    currentSearch = '';
                    currentPage = 0;
                    loadFacilityData();
                }
            });
        }

        // Search on Enter key
        const searchInput = document.getElementById('facility-search');
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    currentPage = 0;
                    loadFacilityData();
                }
            });
        }

        // Reload data when filters change
        const filterForm = document.getElementById('preview');
        if (filterForm) {
            filterForm.addEventListener('submit', function(e) {
                // Reset pagination and search when filters change
                currentPage = 0;
                currentSearch = '';
                const searchInput = document.getElementById('facility-search');
                if (searchInput) {
                    searchInput.value = '';
                }
            });
        }
    }
})();
</script>
