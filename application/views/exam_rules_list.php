<div class="content-wrapper">
	<div class="container">
        <section class="content-header">
            <h1>Rules / Syllabus / Eligiblity for Examinations</h1>
        </section>
        <section class="content">
            <div class="row">
            	<div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="box box-info">
                        <div class="box-body">
                            <?php if( count( $exams ) > 0 ) { ?>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Examination</th>
                                            <th>Rules & Syllabus</th>
                                        </tr>
                                    </thead>
                                    <?php $i = 0; 
                                    $memtype = base64_encode($memtype);
                                    foreach( $exams as $type => $exam ) { ?>
                                        <tr><th colspan="2"><?php echo $type;?></th></tr>
                                        <?php foreach($exam as $exm) { 
											if( !empty( $exm['exam_instruction_file'] ) ) {
												$i++;?>
												<tr>
													<td><?php echo $i;?></td>
													<?php 
													$examcode = base64_encode( $exm['exam_code'] );
													?>
													<td><?php echo $exm['description'];?></td>
													<td><a target="_blank" href="<?php echo base_url();?>uploads/exam_instruction/<?php echo $exm['exam_instruction_file'];?>"><img src="<?php echo base_url().'assets/images/pdf.gif';?>" /></a></td>
												</tr>
                                        <?php }//if
										}//foreach ?>
                                    <?php }?>
                                </table>
                            <?php }?>
                         </div><!-- .box-body -->
                     </div><!-- .box-info -->
                 </div><!--.col-md-8-->
                 <div class="col-md-2"></div>
           </div><!--.row-->
       </section><!--.content-->
   </div><!--.container-->
</div><!-- .content-wrapper -->