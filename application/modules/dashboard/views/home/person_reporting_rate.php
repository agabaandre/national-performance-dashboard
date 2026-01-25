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
        <h2>Reporting Rates</h2>
        
        <?php $this->load->view('dashboard/home/partials/filters_person_rates') ?>

        <div class="col-md-12 text-align-center">
            <h4 id="financial-year-display">Financial Year: <?= $current_financial_year ?? $this->session->userdata('financial_year') ?></h4>
        </div>

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
            <p>Loading reporting rates data...</p>
        </div>

        <!-- Data container -->
        <div id="facilities-container"></div>

        <!-- Pagination container -->
        <div id="pagination-container" class="mt-4"></div>

        <!-- Error message container -->
        <div id="error-message" class="alert alert-danger" style="display: none;"></div>
    </div>
</div>

<script>
(function() {
    'use strict';

    // Get URL parameters
    const urlParams = new URLSearchParams(window.location.search);
    let currentPage = parseInt(urlParams.get('page')) || 0;
    let currentSearch = urlParams.get('search') || '';
    const perPage = 5;

    // Get filter values
    function getFilterValues() {
        const form = document.getElementById('preview');
        return {
            kpi_group: form.querySelector('[name="kpi_group"]').value || '',
            financial_year: form.querySelector('[name="financial_year"]').value || '<?= $current_financial_year ?? $this->session->userdata('financial_year') ?>',
            facility_id: form.querySelector('[name="facility_id"]') ? form.querySelector('[name="facility_id"]').value : '',
            search: currentSearch,
            page: currentPage
        };
    }

    // Helper function to get reporting rate color
    function getReportingRateColor(rate) {
        if (rate === null || rate === undefined) {
            return "background-color: grey; color: white;";
        }
        if (rate < 75) {
            return "background-color: #de1a1a; color: #FFF;";
        } else if (rate < 95) {
            return "background-color: #FFA500; color: #FFF;";
        } else {
            return "background-color: #008000; color: #FFF;";
        }
    }

    // Render facility table
    function renderFacility(facility) {
        let html = `
            <div class="facility-container mt-4">
                <h3>${facility.facility}</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Employee</th>
                <th>KPI Group</th>
                <th>Position</th>
                <th>Quarter 1</th>
                <th>Quarter 2</th>
                <th>Quarter 3</th>
                <th>Quarter 4</th>
            </tr>
        </thead>
        <tbody>
        `;

        let staffIndex = 1;
        facility.staff.forEach(function(staff) {
            const totalKpis = staff.total_kpis || 0;
            const q1Rate = totalKpis > 0 ? (staff.reporting_rates.Q1 / totalKpis) * 100 : null;
            const q2Rate = totalKpis > 0 ? (staff.reporting_rates.Q2 / totalKpis) * 100 : null;
            const q3Rate = totalKpis > 0 ? (staff.reporting_rates.Q3 / totalKpis) * 100 : null;
            const q4Rate = totalKpis > 0 ? (staff.reporting_rates.Q4 / totalKpis) * 100 : null;

            html += `
                <tr>
                    <td>${staffIndex++}</td>
                    <td>${staff.surname} ${staff.firstname}</td>
                    <td>${staff.job_category_name || '-'}</td>
                    <td>${staff.job_name || '-'}</td>
                    <td style="${getReportingRateColor(q1Rate)}">${staff.reporting_rates.Q1}/${totalKpis}</td>
                    <td style="${getReportingRateColor(q2Rate)}">${staff.reporting_rates.Q2}/${totalKpis}</td>
                    <td style="${getReportingRateColor(q3Rate)}">${staff.reporting_rates.Q3}/${totalKpis}</td>
                    <td style="${getReportingRateColor(q4Rate)}">${staff.reporting_rates.Q4}/${totalKpis}</td>
            </tr>
            `;
        });

        html += `
        </tbody>
    </table>
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

        paginationInfo.innerHTML = `Showing ${startRecord}-${endRecord} of ${totalCount} facilities`;

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
                loadReportingRatesData();
            });
        });
    }

    // Load data via AJAX
    function loadReportingRatesData() {
        const container = document.getElementById('facilities-container');
        const loadingIndicator = document.getElementById('loading-indicator');
        const errorMessage = document.getElementById('error-message');
        const searchInput = document.getElementById('facility-search');
        const financialYearDisplay = document.getElementById('financial-year-display');

        if (searchInput && searchInput.value !== currentSearch) {
            currentSearch = searchInput.value;
            currentPage = 0;
        }

        loadingIndicator.style.display = 'block';
        errorMessage.style.display = 'none';
        container.innerHTML = '';
        document.getElementById('pagination-container').innerHTML = '';

        const filters = getFilterValues();
        
        // Update financial year display
        if (financialYearDisplay && filters.financial_year) {
            financialYearDisplay.textContent = 'Financial Year: ' + filters.financial_year;
        }

        const ajaxUrl = '<?= base_url("dashboard/slider/ajax_person_reporting_rates") ?>' + 
                       '?kpi_group=' + encodeURIComponent(filters.kpi_group) +
                       '&financial_year=' + encodeURIComponent(filters.financial_year) +
                       '&facility_id=' + encodeURIComponent(filters.facility_id) +
                       '&search=' + encodeURIComponent(filters.search) +
                       '&page=' + filters.page +
                       '&per_page=' + perPage;

        const newUrl = new URL(window.location);
        newUrl.searchParams.set('page', filters.page);
        if (filters.kpi_group) newUrl.searchParams.set('kpi_group', filters.kpi_group);
        if (filters.financial_year) newUrl.searchParams.set('financial_year', filters.financial_year);
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
                    // Render all facilities at once for faster display
                    let allFacilitiesHtml = '';
                    data.data.forEach(function(facility) {
                        allFacilitiesHtml += renderFacility(facility);
                    });
                    container.innerHTML = allFacilitiesHtml;

                    if (data.pagination) {
                        renderPagination(data.pagination);
                    }
                } else {
                    container.innerHTML = '<div class="alert alert-info">No facilities found for the selected criteria.</div>';
                    if (data.pagination) {
                        renderPagination(data.pagination);
                    }
                }
            })
            .catch(function(error) {
                console.error('Error loading data:', error);
                loadingIndicator.style.display = 'none';
                errorMessage.textContent = 'Failed to load reporting rates data. Please try again.';
                errorMessage.style.display = 'block';
            });
    }

    // Setup event listeners
    function setupEventListeners() {
        // Search button
        const searchBtn = document.getElementById('search-btn');
        if (searchBtn) {
            searchBtn.addEventListener('click', function() {
                currentPage = 0;
                loadReportingRatesData();
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
                    loadReportingRatesData();
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
                    loadReportingRatesData();
                }
            });
        }

        // Filter form submit
        const filterForm = document.getElementById('preview');
        if (filterForm) {
            filterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                currentPage = 0;
                currentSearch = '';
                const searchInput = document.getElementById('facility-search');
                if (searchInput) {
                    searchInput.value = '';
                }
                loadReportingRatesData();
            });
        }

        // Filter change handlers
        if (filterForm) {
            const filterSelects = filterForm.querySelectorAll('select');
            filterSelects.forEach(function(select) {
                select.addEventListener('change', function() {
                    currentPage = 0;
                    loadReportingRatesData();
                });
            });
        }

        // Export button
        const exportBtn = document.getElementById('export_button');
        if (exportBtn) {
            exportBtn.addEventListener('click', function() {
                const filters = getFilterValues();
                const exportUrl = '<?= base_url("dashboard/slider/export_person_reporting_rates") ?>' + 
                                '?kpi_group=' + encodeURIComponent(filters.kpi_group) +
                                '&financial_year=' + encodeURIComponent(filters.financial_year) +
                                '&facility_id=' + encodeURIComponent(filters.facility_id) +
                                '&search=' + encodeURIComponent(filters.search);
                window.location.href = exportUrl;
            });
        }
    }

    // Load data when page is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            loadReportingRatesData();
            setupEventListeners();
        });
    } else {
        loadReportingRatesData();
        setupEventListeners();
    }
})();
</script>
