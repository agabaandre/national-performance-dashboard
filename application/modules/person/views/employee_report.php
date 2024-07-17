
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .employee-name {
            font-weight: bold;
            background-color: #e0e0e0;
            text-align: center;
        }

        .empty-cell {
            border: none;
        }
    </style>
 
    <table>
        <thead>
            <tr>
                <th>Employee Name</th>
                <th>KPI</th>
                <th>Definition</th>
                <th>Report</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($performance_data)): ?>
                <?php
                $current_employee = '';
                $rowspan = 0;
                $employee_data = [];
                foreach ($performance_data as $row):
                    if ($current_employee != $row['employee_name']):
                        if ($current_employee != ''):
                            // Print the previously collected employee data
                            echo '<tr><td class="employee-name" rowspan="' . $rowspan . '">' . $current_employee . '</td>';
                            foreach ($employee_data as $index => $data):
                                if ($index > 0)
                                    echo '<tr>';
                                echo '<td>' . $data['kpi_name'] . '</td>';
                                echo '<td><strong>Numerator Description:</strong> ' . $data['numerator_description'] . '<br><strong>Denominator Description:</strong> ' . $data['denominator_description'] . '</td>';
                                echo '<td style="color:#FFF; background-color: ' . getColorBasedOnPerformance($data['score'], $data['data_target']) . '">
                                        <strong>Financial Year:</strong> ' . $data['financial_year'] . '<br>
                                        <strong>Period:</strong> ' . $data['period'] . '<br>
                                        <strong>Numerator:</strong> ' . $data['numerator'] . '<br>
                                        <strong>Denominator:</strong> ' . $data['denominator'] . '<br>
                                        <strong>Score:</strong> ' . round($data['score']) . '<br>
                                        <strong>Comment:</strong> ' . $data['comment'] . '
                                      </td>';
                                echo '</tr>';
                            endforeach;
                        endif;
                        $current_employee = $row['employee_name'];
                        $employee_data = [];
                        $rowspan = 0;
                    endif;
                    $employee_data[] = $row;
                    $rowspan++;
                endforeach;

                // Print the last collected employee data
                if ($current_employee != ''):
                    echo '<tr><td class="employee-name" rowspan="' . $rowspan . '">' . $current_employee . '</td>';
                    foreach ($employee_data as $index => $data):
                        if ($index > 0)
                            echo '<tr>';
                        echo '<td>' . $data['kpi_name'] . '</td>';
                        echo '<td><strong>Numerator Description:</strong> ' . $data['numerator_description'] . '<br><strong>Denominator Description:</strong> ' . $data['denominator_description'] . '</td>';
                        echo '<td style="color:#FFF; background-color: ' . getColorBasedOnPerformance($data['score'], $data['data_target']) . '">
                                <strong>Financial Year:</strong> ' . $data['financial_year'] . '<br>
                                <strong>Period:</strong> ' . $data['period'] . '<br>
                                <strong>Numerator:</strong> ' . $data['numerator'] . '<br>
                                <strong>Denominator:</strong> ' . $data['denominator'] . '<br>
                                <strong>Score:</strong> ' . round($data['score']) . '<br>
                                <strong>Comment:</strong> ' . $data['comment'] . '
                              </td>';
                        echo '</tr>';
                    endforeach;
                endif;
                ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">No performance data available.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
