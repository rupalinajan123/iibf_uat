<div class="content-wrapper">
	<div class="container">
        <section class="content-header" style="padding-left: 0px;">
            <h1>Examination</h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-body">
                        	<p style="background-color:#dcf1fc;padding: 5px;"><strong>IMPORTANT NOTE FOR CANDIDATES DESIROUS TO APPLY FOR MORE THAN ONE EXAMINATION</strong><br>
Institute is conducting certain examinations simultaneously on the same day. Candidates are, therefore, requested to see the time table carefully and apply for only one <strong>examination scheduled to be conducted on a date given in the time table.</strong></p>

<p style="background-color:#dcf1fc;padding: 5px;">If candidates already have a membership/registration no., then they have to register for examinations through their membership/registration no. only. In case a non-member has obtained membership, he/she should only register for examinations using his/her membership no.<br>
In case it is found that the candidate has registered for any IIBF examination using additional/multiple membership/registration numbers, their results of such examinations are liable for cancellation.</p>

<p style="color:#f00; background-color:#dcf1fc;"><strong>Login Id and Password is not required for Examination Online Registration.</strong> &nbsp; &nbsp; &nbsp; &nbsp;</p>

<ul>
	<?php if( $memtype == 'NM' ) { ?>
    	<li><a class="disability" href="<?php echo base_url();?>InstructionsExamination/listing/?type=Tk0=">Rules / Syllabus / Eligiblity for Examinations</a></li>
        <li><a class="disability" href="<?php echo base_url();?>ExamInstruction/listing/?type=Tk0=">Important Instructions for Examinations</a></li>
        <li><a class="disability" href="<?php echo base_url();?>HowtoApplyExamination/?type=Tk0=">How to Apply for Examinations</a></li>
        <?php 
	
		if( count( $examtypes ) > 0 ) { 
				$flg = 0;
				foreach( $examtypes as $examtype ) { 
					$typearr = explode("*", $examtype);
					$typeid = $typearr[0];
					$typename = $typearr[1];
					$encodetypeid = base64_encode($typeid);
					if( $typeid != 4 ) { // if type is not main examination (which includes JAIIB, CAIIB)
					?>
                    	<li><a class="disability" href="<?php echo base_url();?>nonreg/examlist/?Extype=<?php echo $encodetypeid;?>&amp;Mtype=Tk0="><?php echo $typename;?></a></li>
						
				<?php // echo '1';
				} else if($typeid == 4) {
                            $flg = 1;
                      } 
					
		        }//foreach
				if( $flg == 1 ) { //display main examination type exams directly without displaying it's type e.g. DB&F
					if( count( $examlist ) > 0 ) { 
                    	foreach( $examlist as $indexam ) { 
							if( $indexam["exam_code"] == $this->config->item('examCodeDBF') ) { //DB&F ?>
								<li><a class="disability" href="<?php echo base_url();?>Dbfuser/login/?Extype=<?php echo base64_encode($indexam["exam_type"]);?>&Mtype=REI=&ExId=<?php echo base64_encode($indexam["exam_code"]);?>">DB&F (for persons who aspire for a career in banking and finance)
                                </a></li> 
                            <?php } else { ?>
	                             <?php 
								 if(base64_encode($indexam["exam_code"]=='528')||base64_encode($indexam["exam_code"]=='529') ||base64_encode($indexam["exam_code"]=='530') ||base64_encode($indexam["exam_code"]=='531') || base64_encode($indexam["exam_code"]=='534'))
								{?>
									<li><a class="disability" href="<?php echo base_url();?>ELearning/exapplylogin/?Extype=<?php echo base64_encode($indexam["exam_type"]);?>&Mtype=<?php echo $this->input->get('type')?>&ExId=<?php echo base64_encode($indexam["exam_code"]);?>"><?php echo $indexam["description"];?></a></li>
								<?php }
								else
								{?>
										 <li><a class="disability" href="<?php echo base_url();?>nonreg/memlogin/?Extype=<?php echo base64_encode($indexam["exam_type"]);?>&Mtype=Tk0=&ExId=<?php echo base64_encode($indexam["exam_code"]);?>"><?php echo $indexam["description"];?></a></li>
							<?php }?>
                        	<?php }?>
				<?php   }
					} 
				} 
			}//if count ?>
    <?php } else if( $memtype == 'O' ) { ?>
    	<li><a class="disability" href="<?php echo base_url();?>InstructionsExamination/listing/?type=Tw==">Rules / Syllabus / Eligiblity for Examinations</a></li>
         <li><a class="disability" href="<?php echo base_url();?>ExamInstruction/listing/?type=Tw==">Important Instructions for Examinations</a></li>
         <li><a class="disability" href="<?php echo base_url();?>HowtoApplyExamination/?type=Tw==">How to Apply for Examinations</a></li>
         <?php  
		 
		 if( count( $examtypes ) > 0 ) { 
		 		$flg = 0;
				foreach( $examtypes as $examtype ) { 
					$typearr = explode("*", $examtype);
					$typeid = $typearr[0];
					$typename = $typearr[1];
					$encodetypeid = base64_encode($typeid);
					if( $typeid != 4 ) { // if type is not main examination (which includes JAIIB, CAIIB)
					?>
                    	<li><a class="disability" href="<?php echo base_url();?>Register/examlist/?Extype=<?php echo $encodetypeid;?>&amp;Mtype=Tw=="><?php echo $typename;?></a></li>
				<?php } else if($typeid == 4) {
                            $flg = 1;
                      } 
				} //foreach
				if( $flg == 1 ) { //display main examination type exams directly without displaying it's type 
			//	echo '12';
		//	echo'<pre>';print_r($examlist);
					if( count( $examlist ) > 0 ) { 
                    	foreach( $examlist as $indexam ) { 
							if(base64_encode($indexam["exam_code"]=='528')||base64_encode($indexam["exam_code"]=='529') ||base64_encode($indexam["exam_code"]=='530')  ||base64_encode($indexam["exam_code"]=='531') || base64_encode($indexam["exam_code"]=='534'))
							{
								?>
								<li><a class="disability" href="<?php echo base_url();?>ELearning/exapplylogin/?Extype=<?php echo base64_encode($indexam["exam_type"]);?>&Mtype=<?php echo $this->input->get('type')?>==&ExId=<?php echo base64_encode($indexam["exam_code"]);?>"><?php echo $indexam["description"];?></a></li>
							<?php } ## Condition added on 12 May to hide exam links from non eligible members
							
							else
							{
						?>

					
								<li>
									<a class="disability" href="<?php echo base_url();?>Applyexam/exapplylogin/?Extype=<?php echo base64_encode($indexam["exam_type"]);?>&Mtype=Tw==&ExId=<?php echo base64_encode($indexam["exam_code"]);?>"><?php echo $indexam["description"];?></a>
								</li>
									
            			<?php }?>
							
                            
                            
                            
				<?php   }
					} 
				} 
			} ?>
    <?php } ?>
    <li><a class="disability" href="https://iibf.esdsconnect.com/Scribe_form">Apply For Scribe/Special Assistance/Extra Time</a></li>
    <li><a class="disability" target="_blank" href="<?php echo base_url().'uploads/SCRIBE_Guidelines_2022.pdf';?>">GUIDELINES/RULES FOR USING SCRIBE BY VISUALLY IMPAIRED & ORTHOPEADICALLY CHALLENGED CANDIDATES</a></li>
    
</ul>

<p><b>Note:</b> Provision of payment of application fee through Debit card/ Credit card / Internet Banking is available.</p>

<p style="color:#f00; background-color:#dcf1fc;"><b>In case payment is made through net banking or debit card and transaction is not successful/ complete, the fees will be refunded by concern bank within 10-12 working days. if the payment is made vide credit card, the amount will be re-credited to candidate's account by the bank in the next billing cycle of credit card. Refund timeline indicated is approximate, as the processing of refund is dependent on multiple organization and working days/holidays of bank/financial Institutes.</b></p>
                        </div><!-- .box-body -->
                     </div><!-- .box-info -->
                 </div><!--.col-md-8-->
                 <div class="col-md-2"></div>
           </div><!--.row-->
       </section><!--.content-->
   </div><!--.container-->
</div><!-- .content-wrapper -->