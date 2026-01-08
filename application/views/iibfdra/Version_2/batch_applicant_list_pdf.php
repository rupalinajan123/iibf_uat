<style type="text/css">
  table, th, td { border: 1px solid #303030; border-collapse: collapse;}
  td, td { padding:4px 4px;  }
</style>

<div class="content-wrapper"> 
  <!-- Content Header (Page header) -->
  <section class="content-header" style="text-align: center;">
    <h1> Batch Checklist Report </h1>
  </section>

  <div class="col-md-12"> <br />
  </div>
  <!-- Main content -->
  <form method="POST" action="<?php echo base_url('iibfdra/Version_2/TrainingBatches/export_to_pdf'); ?>">
  <section class="content">
    

    <div class="row" id="batch_inspecton_div" >
      <div class="col-xs-12">
        
        <div class="box">
          <div class="box-body">  
            <div class="table-responsive">
              <table id="listitems" class="table table-bordered table-striped" width="100%" >
                <thead>
                  <tr>
                    <th>Sr</th> 
                    <th>Training Id</th>
                    <th>Candidate Name</th>
                    <th>Gender</th>
                    <th>DOB</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    <th>Address</th> 
                    <th>Qualification</th>
                    <th>Education Qualification</th>
                    <th>Id Proof</th>
                    <th>Id Proof No</th>
                    <th>Photo</th>
                    <th>Signature</th>
                    <th>Id Proof Photo</th>
                    <th>Qualification Certificate</th>
                  </tr>
                </thead>
                <tbody class="no-bd-y" id="list">
                  <?php foreach ($candidate_data as $key => $value) {
                    $i = $key+1; ?>
                      <tr>
                        <td><?php echo $i; ?></td>
                        <td><?php echo $value['training_id']; ?></td>
                        <td><?php echo $value['name']; ?></td>
                        <td><?php echo $value['gender']; ?></td>
                        <td><?php echo $value['dateofbirth']; ?></td>
                        <td><?php echo $value['mobile_no']; ?></td>
                        <td><?php echo $value['email_id']; ?></td>
                        <td><?php echo $value['address']; ?></td>
                        <td><?php echo $value['qualification_type']; ?></td>
                        <td><?php echo $value['qualification']; ?></td>
                        <td><?php echo $value['idtype_name']; ?></td>
                        <td><?php echo $value['idproof_no']; ?></td>
                        
                        <?php 
                          /* $photo = base_url('assets/images/no_image1.png');
                          $scannedsignaturephoto = base_url('assets/images/no_image1.png');
                          $idproofphoto = base_url('assets/images/no_image1.png');
                          $quali_certificate = base_url('assets/images/no_image1.png'); */ 

                          $photo = $scannedsignaturephoto = $idproofphoto = $quali_certificate = '';
                         
                          //if(!file_exists('uploads/iibfdra/Version_2/'.$value['scannedphoto'])) 
                          if($value['scannedphoto'] != "" && file_exists('uploads/iibfdra/'.$value['scannedphoto']))
                          {
                            $photo = '<img height="90" width="70" src="'.base_url('uploads/iibfdra/Version_2/'.$value['scannedphoto']).'" alt="">';
                          }

                          //if(!file_exists(base_url('uploads/iibfdra/Version_2/'.$value['scannedsignaturephoto']))) 
                          if($value['scannedsignaturephoto'] != "" && file_exists('uploads/iibfdra/'.$value['scannedsignaturephoto']))
                          {
                            $scannedsignaturephoto = '<img height="90" width="70" src="'.base_url('uploads/iibfdra/'.$value['scannedsignaturephoto']).'" alt="">';
                          }

                          //if(!file_exists(base_url('uploads/iibfdra/Version_2/'.$value['idproofphoto']))) 
                          if($value['idproofphoto'] != "" && file_exists('uploads/iibfdra/'.$value['idproofphoto']))
                          {
                            $idproofphoto = '<img height="90" width="70" src="'.base_url('uploads/iibfdra/'.$value['idproofphoto']).'" alt="">';
                          }
                          
                          //if(!file_exists(base_url('uploads/iibfdra/Version_2/'.$value['quali_certificate']))) 
                          if($value['quali_certificate'] != "" && file_exists('uploads/iibfdra/Version_2/'.$value['quali_certificate']))
                          {
                           $quali_certificate = '<img height="90" width="70" src="'.base_url('uploads/iibfdra/'.$value['quali_certificate']).'" alt="">';
                          }                          
                        ?>

                        <td><?php echo $photo; ?></td>
                        <td><?php echo $scannedsignaturephoto; ?></td>
                        <td><?php echo $idproofphoto; ?></td>
                        <td><?php echo $quali_certificate; ?></td>
                      </tr>
                <?php } ?>
                </tbody>
              </table>
              
            </div>
          </div>
          <!-- /.box-body --> 
        </div>
        <!-- /.box --> 
      </div>
      <!-- /.col collapsed-box-->
    </div>
 
  </section>
  </form>
</div>






