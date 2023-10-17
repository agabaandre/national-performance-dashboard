<style>
    input text {
    border:1px solid #000;
    border-radius:4px;
    }
</style>
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
                                    <?php echo form_open_multipart(base_url('person/save'), array('id' => 'person', 'class' => 'person')); ?>
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="financial_year">Financial Year:</label>
                                            <select class="form-control" name="financial_year" onchange="this.form.submit()">

                                                <?php
                                                $kpis = $this->db->query("SELECT distinct financial_year FROM new_data")->result();

                                                foreach ($kpis as $row) : ?>
                                                    <option value="<?php echo $row->financial_year; ?>"><?php echo $row->financial_year; ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>

                                        <div class="form-group col-md-6">
                                            <label for="period">Period:</label>
                                            <select class="form-control" name="period" onchange="this.form.submit()">
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
                                                <th>#</th>
                                                <th>Indicator</th>
                                                <th>Numerator</th>
                                                <th>Denominator</th>
                                                <th>Comments</th>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $i = 1;
                                            foreach ($kpidatas as $kpi) :

                                            ?>
                                                <tr>
                                                    <td>
                                                        <?= $i++ ?>

                                                    </td>

                                                    <td>
                                                        <?= $kpi->short_name ?>

                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <label><?= $kpi->numerator ?></label>
                                                            <input type="text" class="form-control" id="numerator" name="numerator[<?= $kpi->kpi_id ?>][]" value="">
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="form-group">
                                                            <label><?= $kpi->denominator ?></label>
                                                            <input type="text" class="form-control" id="denominator" name="denominator[<?= $kpi->kpi_id ?>][]" value="">
                                                        </div>
                                                    </td>

                                                    <td>
                                                        <label>Comment on the values</label>
                                                        <input type="text" class="form-control" id="comment" name="comment[<?= $kpi->kpi_id ?>][]" value="">
                                                    </td>


                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>

                                    </form>

                                </div>


                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>