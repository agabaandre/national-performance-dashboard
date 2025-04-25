<div class="dropdown-menu dropdown-menu-animated w-auto h-auto">
    <div class="dropdown-header bg-trans-gradient d-flex justify-content-center align-items-center rounded-top">
        <h4 class="m-0 text-center color-white">
            Quick Shortcut
            <small class="mb-0 opacity-80">User Applications & Addons</small>
        </h4>
    </div>
    <div class="custom-scroll h-100">
        <ul class="app-list">
            <li>
                <a href="<?php echo base_url(); ?>kpi/kpis" class="app-list-item hover-white">
                    <span class="icon-stack">
                        <i class="base-7 icon-stack-3x color-info-500"></i>
                        <i class="base-7 icon-stack-2x color-info-700"></i>
                        <i class="ni ni-graph icon-stack-1x text-white"></i>
                    </span>
                    <span class="app-list-name">
                        <?php echo "Performance Indicators" ?>
                    </span>
                </a>
            </li>
            <li>
                <a href="<?php echo base_url(); ?>kpi/subject" class="app-list-item hover-white">
                    <span class="icon-stack">
                        <i class="base-2 icon-stack-3x color-primary-400"></i>
                        <i class="base-10 text-white icon-stack-1x"></i>
                        <i class="ni md-profile color-primary-800 icon-stack-2x"></i>
                    </span>
                    <span class="app-list-name">
                        <?php echo "Subject Areas" ?>
                    </span>
                </a>
            </li>
            <li>
                <a href="<?php echo base_url(); ?>kpi/info_category" class="app-list-item hover-white">
                    <span class="icon-stack">
                        <i class="base-9 icon-stack-3x color-success-400"></i>
                        <i class="base-2 icon-stack-2x color-success-500"></i>
                        <i class="ni ni-shield icon-stack-1x text-white"></i>
                    </span>
                    <span class="app-list-name">
                        <?php echo "Info Category" ?>
                    </span>
                </a>
            </li>
            <li>
                <a href="<?php echo base_url(); ?>kpi/categoryTwo" class="app-list-item hover-white">
                    <span class="icon-stack">
                        <i class="base-18 icon-stack-3x color-info-700"></i>
                        <span class="position-absolute pos-top pos-left pos-right color-white fs-md mt-2 fw-400">28</span>
                    </span>
                    <span class="app-list-name">
                        <?php echo "Output" ?>
                    </span>
                </a>
            </li>
            <li>
                <a href="<?php echo base_url(); ?>kpi/view_kpi_data" class="app-list-item hover-white">
                    <span class="icon-stack">
                        <i class="base-2 icon-stack-3x color-primary-600"></i>
                        <i class="base-3 icon-stack-2x color-primary-700"></i>
                        <i class="ni ni-settings icon-stack-1x text-white fs-lg"></i>
                    </span>
                    <span class="app-list-name">
                        <?php echo "KPI Data" ?>
                    </span>
                </a>
            </li>
            <li>
                <a href="<?php echo base_url(); ?>kpi/kpiDisplay"" class="app-list-item hover-white">
                    <span class="icon-stack">
                        <i class="base-4 icon-stack-3x color-danger-500"></i>
                        <i class="base-4 icon-stack-1x color-danger-400"></i>
                        <i class="ni ni-envelope icon-stack-1x text-white"></i>
                    </span>
                    <span class="app-list-name">
                        <?php echo "Kpi Display Control" ?>
                    </span>
                </a>
            </li>
            <li>
                <a href="<?php echo base_url('dashboard/setting') ?>" class="app-list-item hover-white">
                    <span class="icon-stack">
                        <i class="base-4 icon-stack-3x color-fusion-400"></i>
                        <i class="base-5 icon-stack-2x color-fusion-200"></i>
                        <i class="base-5 icon-stack-1x color-fusion-100"></i>
                        <i class="fal fa-keyboard icon-stack-1x color-info-50"></i>
                    </span>
                    <span class="app-list-name">
                        <?php echo display('application_setting') ?>
                    </span>
                </a>
            </li>
            <li>
                <a href="<?php echo base_url('dashboard/language') ?>" class="app-list-item hover-white">
                    <span class="icon-stack">
                        <i class="base-16 icon-stack-3x color-fusion-500"></i>
                        <i class="base-10 icon-stack-1x color-primary-50 opacity-30"></i>
                        <i class="base-10 icon-stack-1x fs-xl color-primary-50 opacity-20"></i>
                        <i class="fal fa-dot-circle icon-stack-1x text-white opacity-85"></i>
                    </span>
                    <span class="app-list-name">
                        <?php echo display('language') ?>
                    </span>
                </a>
            </li>
            <li class="treeview <?php echo (($this->uri->segment(2) == "backup_restore") ? "active" : null) ?>">
                <a href="<?php echo base_url('dashboard/backup_restore/index') ?>" class="app-list-item hover-white">
                    <span class="icon-stack">
                        <i class="base-19 icon-stack-3x color-primary-400"></i>
                        <i class="base-7 icon-stack-2x color-primary-300"></i>
                        <i class="base-7 icon-stack-1x fs-xxl color-primary-200"></i>
                        <i class="base-7 icon-stack-1x color-primary-500"></i>
                        <i class="fal fa-globe icon-stack-1x text-white opacity-85"></i>
                    </span>
                    <span class="app-list-name">
                        <?php echo display('backup_and_restore') ?>
                    </span>
                </a>
            </li>
            <!-- <li class="w-100">
                <a href="#" class="btn btn-default mt-4 mb-2 pr-5 pl-5"> Add more apps </a>
            </li> -->
        </ul>
    </div>
</div>