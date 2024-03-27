
    <div class="container mt-4">
        <h2>KPI Visualization</h2>

        <?php $facilities = Modules::run('dashboard/home/get_facilities');
       // dd($facilities);
          foreach($facilities as $facility):
        ?>
        <div class="row mt-4">
            <div class="col">
                <h4><?php echo $facility->facility;?></h4>
                <table class="table">
                    <thead> 
                    <tr>
                            <th>Staff</th> 
                            <th>KPI</th>                             
                            <th>Q1</th>
                            <th>Q2</th>
                            <th>Q3</th>
                            <th>Q4</th>
                    </tr>
                     </thead>

                     
                    <tbody>
                        <tr>
                      
                          
                        <?php foreach ($facility->staff as $staff) { ?>
                                    <tr>
                            
                                        <td>
                                            <?php echo $staff->surname; ?>
                                        </td>

                                      
                                    </tr>
                            
                            
                                <?php } ?>
                                <table>
                                        <tr>
                                        <td>Numerator</td>
                                        <td>Denominator</td>
                                        </tr>
                                </table>
                                
                           
                        </tr>
                      
                    </tbody>
                    
                   
                          
                        
                    
                          
                            
                        </tr>
                       
                   
                </table>
            </div>
        </div>
    <?php endforeach; ?>

 </div>
    