<?php if (isset($log_data) && count($log_data) > 0)
{ ?>
  <div class="ibox">
    <div class="ibox-content">
      <h4 class="log_table_title"><?php echo $log_title; ?></h4>
      <div class="table-responsive">
        <table class="table table-bordered table-hover dataTables-example log_table" style="width:100%">
          <thead>
            <tr class="log_table_head">
              <th class="text-center" style="width:60px;">Sr. No.</th>
              <th class="text-center">Description</th>
              <th class="text-center">Date</th>
            </tr>
          </thead>

          <tbody>
            <?php
            $sr_no = 1;
            foreach ($log_data as $res)
            { ?>
              <tr>
                <td class="text-center"><?php echo $sr_no; ?></td>
                <td class="wrap"><?php echo $res['description']; ?></td>
                <td class="nowrap"><?php echo show_log_date($res['created_on']); /* ncvet/ncvet_helper.php */ ?></td>
              </tr>
            <?php $sr_no++;
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
<?php } ?>