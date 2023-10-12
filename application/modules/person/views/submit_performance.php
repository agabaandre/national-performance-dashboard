<div class="row">
    <div class="col-sm-12 col-md-12">
        <div class="panel panel-bd lobidrag">
            <div class="panel-heading">
                <div class="panel-title">
                    <h4>
                        <?php echo (!empty($title) ? $title : null) ?>
                    </h4>
                    <p style="float:right; margin-right: 4px;">
                        <a href="<?php echo base_url(); ?>person/performance" class="btn btn-sucess">
                            My Performance
                        </a>
                    <p>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">





                            <div class="card-content">
                                <div class="col-md-12">
                                    <h5 style="text-align:left; padding-bottom:1em; text-weight:bold;">Staff KPI Data Capture Form
                                    </h5>

                                    <form method="post" enctype="multipart/form-data" action="<?php echo base_url(); ?>person/do_upload">

                                        <div class="row">
                                            <div class="form-group col-md-4">
                                                <label for="financial_year">Financial Year:</label>
                                                <select class="form-control" name="financial_year">

                                                    <?php
                                                    $kpis = $this->db->query("SELECT distinct financial_year FROM new_data")->result();

                                                    foreach ($kpis as $row) : ?>
                                                        <option value="<?php echo $row->financial_year; ?>"><?php echo $row->financial_year; ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="period_year">Period Year:</label>
                                                <select class="form-control" name="period_year">
                                                    <option value="2023">2023 </option>
                                                    <option value="2024">2024</option>
                                                    <option value="2025">2025</option>
                                                </select>

                                            </div>
                                            <div class="form-group col-md-4">
                                                <label for="period">Period:</label>
                                                <select class="form-control" name="period">
                                                    <option value="Q4">Q4
                                                    </option>
                                                    <option value="Q3">Q3
                                                    </option>
                                                    <option value="Q2">Q2
                                                    </option>
                                                    <option value="Q1">Q1
                                                    </option>
                                                </select>
                                            </div>

                                            <button type="submit" class="btn btn-primary">Submit</button>

                                        </div>

                                        <table class="table table-responsive">
                                            <thead>
                                                <tr>
                                                    <th>Indicator</th>
                                                    <th>Numerator</th>
                                                    <th>Denominator</th>
                                                    <th>Comments</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>

                                                       
                                                    </td>

                                                    <td>

                                                        <input type="text" class="form-control" id="numerator" name="numerator" value="">
                                                    </td>
                                                    <td>

                                                        <input type="text" class="form-control" id="denominator" name="denominator" value="">
                                                    </td>

                                                    <td>

                                                        <input type="text" class="form-control" id="comment" name="comment" value="">
                                                    </td>


                                                </tr>
                                            </tbody>
                                        </table>

                                    </form>

                                </div>


                            </div>
                        </div>
                    </div>

                    <!-- <div class="col-sm-6"> -->
                    <!-- <div class="card">

                            <div class="card-content">
                                <div class="col-lg-6">
                                    <form method="get" action="<?php echo base_url(); ?>files/generate_csv_file">
                                        <select class="form-control" name="kpi_id">

                                            <?php
                                            $info_cat = $_SESSION['info_category'];
                                            if (!empty($_SESSION['subject_area'])) {
                                                @$id = implode(",", json_decode($_SESSION['subject_area']));

                                                $kpis = $this->db->query("SELECT * FROM `kpi` where subject_area in ($id)")->result();
                                            } else {
                                                $kpis = $this->db->query("SELECT * FROM `kpi` where subject_area in (select id from subject_areas where info_category=$info_cat)  ")->result();
                                            }

                                            foreach ($kpis as $row) : ?>
                                                <option value="<?php echo $row->kpi_id; ?>"><?php echo $row->short_name . '(' . $row->kpi_id . ')'; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <br />
                                        <button type="submit" class="btn btn-primary">Sample CSV File </a>
                                    </form>

                                </div>


                            </div>
                        </div> -->
                    <!-- </div> -->
                </div>
            </div>
        </div>
    </div>
</div>