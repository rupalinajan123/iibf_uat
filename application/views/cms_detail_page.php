<div class="content-wrapper">
	<div class="container">
        <section class="content-header">
            <h1><?php echo $page_title;?></h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-info">
                        <div class="box-body">
                        	 <?php 
							 //$string = str_replace("#url#uploads/", "".base_url()."uploads/", $page_desc);
							 //$string1 = str_replace("#url#images/", "".base_url()."images/", $string);
							 $newstring = str_replace("#url#", "".base_url()."", $page_desc);
							 echo $finalstring = str_replace("{url}", "javascript:void(0);", $newstring);
							 ?>
                         </div><!-- .box-body -->
                     </div><!-- .box-info -->
                 </div><!--.col-md-8-->
                 <div class="col-md-2"></div>
           </div><!--.row-->
       </section><!--.content-->
   </div><!--.container-->
</div><!-- .content-wrapper -->
