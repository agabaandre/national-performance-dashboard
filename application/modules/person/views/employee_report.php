<?php
$this->load->helper('settings');

// Fetch distinct financial years
$financial_years = $this->db->query("
    SELECT DISTINCT financial_year 
    FROM performanace_data 
    WHERE financial_year IS NOT NULL 
    ORDER BY financial_year DESC
")->result();

// Fetch periods
$periods = ['Q1', 'Q2', 'Q3', 'Q4'];

// Fetch distinct employees (for filter dropdown)
$employees = $this->db->query("
    SELECT DISTINCT ihris_pid, CONCAT(surname, ' ', firstname) AS employee_name 
    FROM performanace_data 
    WHERE surname IS NOT NULL AND firstname IS NOT NULL
    ORDER BY surname ASC
")->result();

// Fetch facilities only if admin
$facilities = [];
if ($this->session->userdata('user_type') == 'admin') {
    $facilities = $this->db->query("
        SELECT DISTINCT d.facility_id, d.facility AS facility_name
        FROM ihrisdata d
        WHERE d.facility_id IN (
            SELECT DISTINCT facility FROM performanace_data
        )
        ORDER BY d.facility ASC
    ")->result();
}

// Get KPI groups (already loaded in controller)
$kpigroups = isset($kpigroups) ? $kpigroups : [];

// Get current financial year as default
$current_financial_year = isset($current_financial_year) ? $current_financial_year : $this->session->userdata('financial_year');

// Get selected KPI group to load KPIs
$selected_kpi_group = $this->input->get('kpi_group');
$kpis = [];
if (!empty($selected_kpi_group)) {
    // Query directly since helper returns single row
    $kpis_query = $this->db->query("SELECT kpi_id, short_name FROM kpi WHERE job_id = " . $this->db->escape($selected_kpi_group));
    $kpis = $kpis_query ? $kpis_query->result() : [];
}
?>

<style>
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
    }
    th, td {
        border: 1px solid #ddd;
        padding: 8px;
    }
    th {
        background-color: #f8f9fa;
    }
    .employee-name {
        font-weight: bold;
        background-color: #e2e3e5;
        text-align: center;
    }
    .filter-form {
        background: #f8f9fa;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 5px;
    }
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
    .employee-container {
        opacity: 0;
        animation: fadeIn 0.5s ease-in forwards;
    }
    @keyframes fadeIn {
        to { opacity: 1; }
    }
    .financial-year-header {
        background-color: #007bff;
        color: white;
        font-weight: bold;
        padding: 10px;
        margin-top: 15px;
        border-radius: 5px;
    }
    .kpi-group {
        margin-left: 20px;
        margin-top: 10px;
    }
</style>

<h2>Employee Performance Report</h2>

<!-- Filter Form -->
<form class="filter-form" method="get" action="<?= base_url('person/employee_reporting') ?>" id="filter-form">
<div class="row">
    <div class="col-md-3 mb-3">
        <label>Financial Year *</label>
        <select name="financial_year" class="form-control w-100" id="financial_year_filter" required>
            <option value="">-- Select Year --</option>
            <?php foreach ($financial_years as $year): ?>
                <option value="<?= htmlspecialchars($year->financial_year) ?>" 
                    <?= ($this->input->get('financial_year') == $year->financial_year || 
                         (empty($this->input->get('financial_year')) && $year->financial_year == $current_financial_year)) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($year->financial_year) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-2 mb-3">
        <label>Period</label>
        <select name="period" class="form-control w-100" id="period_filter">
            <option value="">-- All Periods --</option>
            <?php foreach ($periods as $p): ?>
                <option value="<?= $p ?>" <?= ($this->input->get('period') == $p) ? 'selected' : '' ?>>
                    <?= $p ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-2 mb-3">
        <label>KPI Group / Cadre</label>
        <select name="kpi_group" class="form-control select2" id="kpi_group_filter" style="width: 100% !important;" onchange="loadKPIs(this.value)">
            <option value="">-- All Groups --</option>
            <?php foreach ($kpigroups as $group): ?>
                <option value="<?= $group->job_id ?>" <?= ($this->input->get('kpi_group') == $group->job_id) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($group->job) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-md-4 mb-3">
        <label>KPI</label>
        <select name="kpi_id[]" class="form-control select2" id="kpi_id_filter" multiple="multiple" style="width: 100% !important;">
            <?php 
            $selected_kpis = $this->input->get('kpi_id');
            if (!is_array($selected_kpis)) {
                $selected_kpis = !empty($selected_kpis) ? [$selected_kpis] : [];
            }
            foreach ($kpis as $kpi): ?>
                <option value="<?= $kpi->kpi_id ?>" <?= in_array($kpi->kpi_id, $selected_kpis) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($kpi->short_name) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-3 mb-3">
        <label>Person (Employee)</label>
        <select name="ihris_pid" class="form-control select2" id="ihris_pid_filter" style="width: 100% !important;">
            <option value="">-- All Employees --</option>
            <?php foreach ($employees as $emp): ?>
                <option value="<?= htmlspecialchars($emp->ihris_pid) ?>" <?= ($this->input->get('ihris_pid') == $emp->ihris_pid) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($emp->employee_name) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <?php if ($this->session->userdata('user_type') == 'admin'): ?>
        <div class="col-md-2 mb-3">
            <label>Facility</label>
            <select class="form-control select2" name="facility_id" id="facility_id_filter" style="width: 100% !important;">
                <option value="">-- All Facilities --</option>
                <?php foreach ($facilities as $f): ?>
                    <option value="<?= $f->facility_id ?>" <?= ($this->input->get('facility_id') == $f->facility_id) ? 'selected' : '' ?>>
                        <?= $f->facility_name ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    <?php endif; ?>

    <div class="col-md-2 mb-3">
        <label>&nbsp;</label>
        <button type="button" class="btn btn-primary btn-block" id="apply-filters-btn">Apply Filters</button>
    </div>
</div>

<!-- Search and Pagination Controls -->
    <div class="row mt-3">
    <div class="col-md-6">
        <div class="input-group">
            <input type="text" id="employee-search" class="form-control" placeholder="Search employees..." value="<?= $this->input->get('search') ?>">
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
        <button type="button" class="btn btn-success mt-2" id="export-btn">
            <i class="fa fa-file-excel"></i> Export to Excel
        </button>
    </div>
    </div>
</form>

<!-- Loading indicator -->
<div id="loading-indicator" class="loading-container">
    <div class="spinner"></div>
    <p>Loading employee performance data...</p>
</div>

<!-- Data container -->
<div id="employees-container"></div>

<!-- Pagination container -->
<div id="pagination-container" class="mt-4"></div>

<!-- Error message container -->
<div id="error-message" class="alert alert-danger" style="display: none;"></div>

<script>
// Helper function to safely initialize Select2
function initSelect2(element, options) {
    if (typeof jQuery === 'undefined' || typeof jQuery.fn.select2 === 'undefined') {
        // Wait for Select2 to be available
        setTimeout(function() {
            initSelect2(element, options);
        }, 100);
        return;
    }
    
    const $element = jQuery(element);
    if ($element.hasClass('select2-hidden-accessible')) {
        $element.select2('destroy');
    }
    $element.select2(options);
}

// Function to load KPIs when KPI group changes
function loadKPIs(kpiGroupId) {
    const kpiSelect = document.getElementById('kpi_id_filter');
    if (!kpiSelect) {
        console.error('KPI select element not found');
        return;
    }
    
    console.log('Loading KPIs for group:', kpiGroupId);
    
    // Destroy existing Select2 instance if it exists
    if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
        const $kpiSelect = jQuery(kpiSelect);
        if ($kpiSelect.hasClass('select2-hidden-accessible')) {
            $kpiSelect.select2('destroy');
        }
    }
    
    // Clear existing options
    kpiSelect.innerHTML = '';
    
    if (!kpiGroupId || kpiGroupId === '') {
        // Disable and initialize empty Select2 for KPI filter
        kpiSelect.disabled = true;
        initSelect2(kpiSelect, {
            placeholder: 'Select KPI group first',
            allowClear: true,
            width: '100%',
            disabled: true
        });
        return;
    }
    
    // Enable select before loading
    kpiSelect.disabled = false;
    
    // Show loading state
    const loadingOption = document.createElement('option');
    loadingOption.value = '';
    loadingOption.textContent = 'Loading KPIs...';
    loadingOption.disabled = true;
    kpiSelect.appendChild(loadingOption);
    
    // Load KPIs via AJAX
    fetch('<?= base_url("person/get_kpis_by_group") ?>?kpi_group=' + encodeURIComponent(kpiGroupId))
        .then(function(response) {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(function(data) {
            console.log('KPIs loaded:', data);
            console.log('KPI count:', data.count || (data.kpis ? data.kpis.length : 0));
            // Clear loading option
            kpiSelect.innerHTML = '';
            
            if (data.success && data.kpis && data.kpis.length > 0) {
                console.log('Adding ' + data.kpis.length + ' KPIs to select');
                data.kpis.forEach(function(kpi) {
                    const option = document.createElement('option');
                    option.value = kpi.kpi_id;
                    option.textContent = kpi.short_name;
                    kpiSelect.appendChild(option);
                });
                
                // Ensure select is enabled
                kpiSelect.disabled = false;
                
                // Initialize Select2 for multiple selection with wider dropdown
                initSelect2(kpiSelect, {
                    placeholder: 'Select KPIs (multiple) - ' + data.kpis.length + ' available',
                    allowClear: true,
                    width: '100%',
                    dropdownAutoWidth: true
                });
                
                console.log('Select2 initialized, ' + data.kpis.length + ' KPIs are selectable');
            } else {
                // Enable select even if no KPIs found
                kpiSelect.disabled = false;
                const noOption = document.createElement('option');
                noOption.value = '';
                noOption.textContent = 'No KPIs found';
                noOption.disabled = true;
                kpiSelect.appendChild(noOption);
                initSelect2(kpiSelect, {
                    placeholder: 'No KPIs found',
                    allowClear: true,
                    width: '100%',
                    disabled: true
                });
            }
        })
        .catch(function(error) {
            console.error('Error loading KPIs:', error);
            kpiSelect.innerHTML = '';
            kpiSelect.disabled = false;
            const errorOption = document.createElement('option');
            errorOption.value = '';
            errorOption.textContent = 'Error loading KPIs';
            errorOption.disabled = true;
            kpiSelect.appendChild(errorOption);
            initSelect2(kpiSelect, {
                placeholder: 'Error loading KPIs',
                allowClear: true,
                width: '100%',
                disabled: true
            });
        });
}

(function() {
    'use strict';

    // Get URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    let currentPage = parseInt(urlParams.get('page')) || 0;
    let currentSearch = urlParams.get('search') || '';
    const perPage = 20;

    // Get filter values
    function getFilterValues() {
        const kpiIdSelect = document.getElementById('kpi_id_filter');
        let kpiIds = [];
        if (kpiIdSelect) {
            // Get all selected values from multiple select
            const selectedOptions = Array.from(kpiIdSelect.selectedOptions);
            kpiIds = selectedOptions.map(option => option.value).filter(val => val !== '');
        }
        
        return {
            financial_year: document.getElementById('financial_year_filter').value || '<?= $current_financial_year ?>',
            period: document.getElementById('period_filter').value || '',
            kpi_group: document.getElementById('kpi_group_filter') ? document.getElementById('kpi_group_filter').value : '',
            kpi_id: kpiIds, // Array of selected KPI IDs
            ihris_pid: document.getElementById('ihris_pid_filter') ? document.getElementById('ihris_pid_filter').value : '',
            facility_id: document.getElementById('facility_id_filter') ? document.getElementById('facility_id_filter').value : '',
            search: currentSearch,
            page: currentPage
        };
    }

    // Helper function to get color based on performance
    function getColorBasedOnPerformance(value, target) {
        if (value === null || value == 0) {
            return '#FF0000';
        }
        if ((value - target) >= 0) {
            return '#008000';
        } else if (value - target >= -10) {
            return '#FFA500';
        } else {
            return '#FF0000';
        }
    }

    // Render employee data with unique KPIs per financial year
    function renderEmployee(employee) {
        let html = '<div class="employee-container mt-4">';
        
        // Display employee name with KPI group and facility name
        let employeeDisplay = employee.employee_name;
        if (employee.job_category_name && employee.job_category_name.trim() !== '') {
            employeeDisplay += ` <span class="badge badge-secondary" style="font-size: 0.8em; margin-left: 10px;">${employee.job_category_name}</span>`;
        }
        if (employee.facility_name && employee.facility_name.trim() !== '') {
            employeeDisplay += ` <span class="badge badge-info" style="font-size: 0.8em; margin-left: 10px;">${employee.facility_name}</span>`;
        }
        html += `<h4 class="employee-name p-2">${employeeDisplay}</h4>`;

        // Group by financial year and show unique KPIs
        Object.keys(employee.financial_years).sort().reverse().forEach(function(fy) {
            html += `<div class="financial-year-header">Financial Year: ${fy}</div>`;
            
            const kpis = employee.financial_years[fy];
            Object.keys(kpis).forEach(function(kpiId) {
                const kpiData = kpis[kpiId];
                const firstRecord = kpiData[0];
                
                html += '<div class="kpi-group">';
                html += `<strong>KPI: ${firstRecord.kpi_name}</strong><br>`;
                html += `<small>Numerator: ${firstRecord.numerator_description || 'N/A'}<br>`;
                html += `Denominator: ${firstRecord.denominator_description || 'N/A'}</small><br><br>`;
                
                // Show all periods for this KPI
                html += '<table class="table table-bordered table-sm">';
                html += '<thead><tr><th>Period</th><th>Numerator</th><th>Denominator</th><th>Score</th><th>Target</th><th>Comment</th></tr></thead>';
                html += '<tbody>';
                
                kpiData.forEach(function(record) {
                    const bgColor = getColorBasedOnPerformance(record.score, record.data_target);
                    html += `<tr style="background-color: ${bgColor}; color: #FFF;">`;
                    html += `<td>${record.period}</td>`;
                    html += `<td>${record.numerator || ''}</td>`;
                    html += `<td>${record.denominator || ''}</td>`;
                    html += `<td>${record.score ? Math.round(record.score) : ''}</td>`;
                    html += `<td>${record.data_target || ''}</td>`;
                    html += `<td>${record.comment || ''}</td>`;
                    html += '</tr>';
                });
                
                html += '</tbody></table></div>';
            });
        });

        html += '</div>';
        return html;
    }

    // Render pagination controls
    function renderPagination(pagination) {
        const paginationContainer = document.getElementById('pagination-container');
        const paginationInfo = document.getElementById('pagination-info');
        
        if (!pagination || pagination.total_pages <= 1) {
            paginationContainer.innerHTML = '';
            if (pagination) {
                paginationInfo.innerHTML = `Showing ${pagination.total_count} employee(s)`;
            }
            return;
        }

        const totalPages = pagination.total_pages;
        const currentPageNum = pagination.current_page;
        const totalCount = pagination.total_count;
        const startRecord = (currentPageNum * perPage) + 1;
        const endRecord = Math.min((currentPageNum + 1) * perPage, totalCount);

        paginationInfo.innerHTML = `Showing ${startRecord}-${endRecord} of ${totalCount} employees`;

        let paginationHtml = '<nav><ul class="pagination justify-content-center">';

        if (currentPageNum > 0) {
            paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-page="${currentPageNum - 1}">Previous</a></li>`;
        } else {
            paginationHtml += '<li class="page-item disabled"><span class="page-link">Previous</span></li>';
        }

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

        if (currentPageNum < totalPages - 1) {
            paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-page="${currentPageNum + 1}">Next</a></li>`;
        } else {
            paginationHtml += '<li class="page-item disabled"><span class="page-link">Next</span></li>';
        }

        paginationHtml += '</ul></nav>';
        paginationContainer.innerHTML = paginationHtml;

        paginationContainer.querySelectorAll('a.page-link[data-page]').forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                currentPage = parseInt(this.getAttribute('data-page'));
                loadEmployeeData();
            });
        });
    }

    // Load data via AJAX
    function loadEmployeeData() {
        const container = document.getElementById('employees-container');
        const loadingIndicator = document.getElementById('loading-indicator');
        const errorMessage = document.getElementById('error-message');
        const searchInput = document.getElementById('employee-search');

        if (searchInput && searchInput.value !== currentSearch) {
            currentSearch = searchInput.value;
            currentPage = 0;
        }

        loadingIndicator.style.display = 'block';
        errorMessage.style.display = 'none';
        container.innerHTML = '';
        document.getElementById('pagination-container').innerHTML = '';

        const filters = getFilterValues();
        let ajaxUrl = '<?= base_url("person/ajax_employee_reporting") ?>' + 
                       '?financial_year=' + encodeURIComponent(filters.financial_year) +
                       '&period=' + encodeURIComponent(filters.period) +
                       '&kpi_group=' + encodeURIComponent(filters.kpi_group) +
                       '&ihris_pid=' + encodeURIComponent(filters.ihris_pid) +
                       '&facility_id=' + encodeURIComponent(filters.facility_id) +
                       '&search=' + encodeURIComponent(filters.search) +
                       '&page=' + filters.page +
                       '&per_page=' + perPage;
        
        // Add multiple KPI IDs
        if (filters.kpi_id && filters.kpi_id.length > 0) {
            filters.kpi_id.forEach(function(kpiId) {
                ajaxUrl += '&kpi_id[]=' + encodeURIComponent(kpiId);
            });
        }

        const newUrl = new URL(window.location);
        newUrl.searchParams.set('page', filters.page);
        if (filters.financial_year) newUrl.searchParams.set('financial_year', filters.financial_year);
        if (filters.period) newUrl.searchParams.set('period', filters.period);
        if (filters.ihris_pid) newUrl.searchParams.set('ihris_pid', filters.ihris_pid);
        if (filters.facility_id) newUrl.searchParams.set('facility_id', filters.facility_id);
        if (currentSearch) newUrl.searchParams.set('search', currentSearch);
        else newUrl.searchParams.delete('search');
        window.history.pushState({}, '', newUrl);

        fetch(ajaxUrl)
            .then(function(response) {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(function(data) {
                loadingIndicator.style.display = 'none';

                if (data.success && data.data && data.data.length > 0) {
                    data.data.forEach(function(employee, index) {
                        setTimeout(function() {
                            const employeeHtml = renderEmployee(employee);
                            container.insertAdjacentHTML('beforeend', employeeHtml);
                        }, index * 50);
                    });

                    if (data.pagination) {
                        renderPagination(data.pagination);
                    }
                } else {
                    container.innerHTML = '<div class="alert alert-info">No employee performance data found for the selected criteria.</div>';
                    if (data.pagination) {
                        renderPagination(data.pagination);
                    }
                }
            })
            .catch(function(error) {
                console.error('Error loading data:', error);
                loadingIndicator.style.display = 'none';
                errorMessage.textContent = 'Failed to load employee performance data. Please try again.';
                errorMessage.style.display = 'block';
            });
    }

    // Setup event listeners
    function setupEventListeners() {
        document.getElementById('apply-filters-btn').addEventListener('click', function() {
            currentPage = 0;
            currentSearch = '';
            document.getElementById('employee-search').value = '';
            loadEmployeeData();
        });

        document.getElementById('search-btn').addEventListener('click', function() {
            currentPage = 0;
            loadEmployeeData();
        });

        document.getElementById('clear-search-btn').addEventListener('click', function() {
            document.getElementById('employee-search').value = '';
            currentSearch = '';
            currentPage = 0;
            loadEmployeeData();
        });

        document.getElementById('employee-search').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                currentPage = 0;
                loadEmployeeData();
            }
        });

        // Export button
        document.getElementById('export-btn').addEventListener('click', function() {
            const filters = getFilterValues();
            let exportUrl = '<?= base_url("person/export_employee_reporting") ?>' + 
                            '?financial_year=' + encodeURIComponent(filters.financial_year) +
                            '&period=' + encodeURIComponent(filters.period) +
                            '&kpi_group=' + encodeURIComponent(filters.kpi_group) +
                            '&ihris_pid=' + encodeURIComponent(filters.ihris_pid) +
                            '&facility_id=' + encodeURIComponent(filters.facility_id) +
                            '&search=' + encodeURIComponent(filters.search);
            
            // Add multiple KPI IDs
            if (filters.kpi_id && filters.kpi_id.length > 0) {
                filters.kpi_id.forEach(function(kpiId) {
                    exportUrl += '&kpi_id[]=' + encodeURIComponent(kpiId);
                });
            }
            
            window.location.href = exportUrl;
        });
        
        // KPI group change handler to load KPIs
        const kpiGroupSelect = document.getElementById('kpi_group_filter');
        if (kpiGroupSelect) {
            kpiGroupSelect.addEventListener('change', function() {
                const kpiSelect = document.getElementById('kpi_id_filter');
                if (kpiSelect) {
                    // Destroy existing Select2 instance safely
                    if (typeof jQuery !== 'undefined' && typeof jQuery.fn.select2 !== 'undefined') {
                        const $kpiSelect = jQuery(kpiSelect);
                        if ($kpiSelect.hasClass('select2-hidden-accessible')) {
                            $kpiSelect.select2('destroy');
                        }
                    }
                    // Clear selections
                    kpiSelect.innerHTML = '';
                }
                loadKPIs(this.value);
            });
            
            // Load KPIs if a group is already selected on page load
            // Wait for Select2 to be available first
            setTimeout(function() {
                if (kpiGroupSelect.value) {
                    loadKPIs(kpiGroupSelect.value);
                } else {
                    // Initialize empty Select2 for KPI filter
                    const kpiSelect = document.getElementById('kpi_id_filter');
                    if (kpiSelect) {
                        initSelect2(kpiSelect, {
                            placeholder: 'Select KPI group first',
                            allowClear: true,
                            width: '100%',
                            disabled: true
                        });
                    }
                }
            }, 500);
        }
    }

    // Load data when page is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            loadEmployeeData();
            setupEventListeners();
        });
    } else {
        loadEmployeeData();
        setupEventListeners();
    }
})();
</script>
