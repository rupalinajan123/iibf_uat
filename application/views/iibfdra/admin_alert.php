<?php if ($this->session->flashdata('error_message') != "")  { ?>
    <div class="alert alert-danger alert-dismissable p-10" style="margin: 10px 0;">
      <i class="fa fa-ban"></i>
      <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
      <b>Alert!</b> <?php echo $this->session->flashdata('error_message'); ?>          </div>
      <?php } ?>
      <?php if ($this->session->flashdata('success_message') != "") { ?>
      <div class="alert alert-success alert-dismissable" style="margin: 10px 0;"> 
        <i class="fa fa-check"></i>
        <button class="close" aria-hidden="true" data-dismiss="alert" type="button">×</button>
        <b>Success!</b> <?php echo $this->session->flashdata('success_message'); ?>          </div>
        <?php }  ?>
         <?php if($this->router->fetch_class() != 'events') { ?>

         <?php if(validation_errors()!="" ){ ?> 
                  <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-ban"></i> Alert !</h4>
               <?php echo validation_errors();  ?>
              </div>
            <?php }  }

              $csrf = array(
                      'name' => $this->security->get_csrf_token_name(),
                      'hash' => $this->security->get_csrf_hash()
              );
            
              
             ?>
          