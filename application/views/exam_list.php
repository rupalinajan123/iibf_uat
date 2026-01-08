<div class="content-wrapper">
	<div class="container">
        <section class="content-header">
            <h1>Instructions to Applicants</h1>
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
                                        </tr>
                                    </thead>
                                    <?php $i = 0; 
                                    $memtype = base64_encode($memtype);
                                    foreach( $exams as $type => $exam ) { ?>
                                        <tr><th colspan="2"><?php echo $type;?></th></tr>
                                        <?php foreach($exam as $exm) { 
                                            $i++;?>
                                            <tr>
                                                <td><?php echo $i;?></td>
                                                <?php 
                                                $examcode = base64_encode( $exm['exam_code'] );
                                                ?>
                                                <td><a href="<?php echo base_url();?>ExamInstruction/exam?type=<?php echo $memtype;?>&exCd=<?php echo $examcode;?>"><?php echo $exm['description'];?></a></td>
                                            </tr>
                                        <?php }?>
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