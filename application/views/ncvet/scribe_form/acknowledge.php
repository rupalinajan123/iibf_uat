<?php
header('Cache-Control: must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
?>

<head>
    <?php $this->load->view('scribe_form/inc_header'); ?>
    <style type="text/css">
        /*.container 
      {
        position:relative;
        margin-top: 50px;
        border: 1px solid #1287c0;
      }*/
    </style>

</head>
<div class="container m-5">
    <div class="container-fluid mt-5">
        <section class="content-header with-border center" style="background-color: #1287C0; margin-top:10px; border:1px solid #1287c0; margin: ">
            <h1 class=""> Scribe Registration Application Successful.</h1>
        </section>
        <?php if (!empty($user_info[0]['name_of_scribe'])) { ?>
            <form class="form-horizontal">
                <div class="container-fluid">
                    <section class="content">
                        <div class="row">
                            <div class="col-md-12" align="center"><br />
                                <h1>Thank You,</h1>
                                <br />
                                <h4>You have successfully register for scribe!&nbsp;<strong><?php echo $user_info[0]['exam_name']; ?>&nbsp;EXAM</strong>&nbsp;.</h4>

                                <h4>Your application ID is&nbsp;<strong><?php echo $user_info[0]['scribe_uid']; ?>&nbsp;</strong>&nbsp;.</h4>

                                <h4> <strong>Subject Name</strong>&nbsp;:&nbsp;<?php echo $user_info[0]['subject_name']; ?>.<br />
                                    <br />

                                    <h4> <strong>Scribe Name</strong>&nbsp;:&nbsp;<?php echo $user_info[0]['name_of_scribe']; ?>.<br />
                                        <br />

                                        <strong>Scribe Mobile no.</strong>&nbsp;:&nbsp;<?php echo $user_info[0]['mobile_scribe']; ?>.<br />
                                        <br />

                                        <strong>Scribe Email</strong>&nbsp;:&nbsp;<?php echo $user_info[0]['scribe_email']; ?>.<br />
                                        <br />

                                        <strong>Center Name</strong>&nbsp;:&nbsp;<?php echo $user_info[0]['center_name']; ?>. <br />
                                        <br />

                                    </h4>
                            </div>
                        </div>
                    </section>
                </div>
            </form>
        <?php } else { ?>
            <!-- Display form when no name and mobile for scribe info -->
            <form class="form-horizontal">
                <div class="container-fluid">
                    <section class="content">
                        <div class="row">
                            <div class="col-md-12" align="center"><br />
                                <h1>Thank You,</h1>
                                <br />
                                <h4>You have successfully register for Special Assistance/Extra time scribe!&nbsp;<strong><?php echo $user_info[0]['exam_name']; ?>&nbsp;EXAM</strong>&nbsp;.</h4>
                                <h4>
                                    <h4>Your application ID is&nbsp;<strong><?php echo $user_info[0]['scribe_uid']; ?>&nbsp;</strong>&nbsp;.</h4>

                                    <h4> <strong>Subject Name</strong>&nbsp;:&nbsp;<?php echo $user_info[0]['subject_name']; ?>.<br />

                                        <br />
                                        <strong>Center Name</strong>&nbsp;:&nbsp;<?php echo $user_info[0]['center_name']; ?>. <br />
                                        <br />

                                    </h4>
                            </div>
                        </div>
                    </section>
                </div>
            </form>
        <? } ?>
    </div>
</div>

<?php $this->load->view('scribe_form/inc_footer'); ?>