<!doctype html>
<html>
 <body>
 
   <table border='0'>
     <tr>
       <td>Exam</td>
       <td>
         <select id='exam_code' name="exam_code">
           <option>-- Select Exam --</option>
           <?php
           foreach($exams as $exam){
             echo "<option value='".$exam['exam_code']."'>".$exam['description']."</option>";
           }
           ?>
        </select>
      </td>
    </tr>
    <tr>
      <td>Subject</td>
      <td>
        <select id='sel_subject' name="subject_code" >
          <option>-- Select subject --</option>
        </select>
      </td>
    </tr>
  </table>
 
  <!-- Script -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

  <script type='text/javascript'>
  // baseURL variable
  var baseURL= "<?php echo base_url();?>";
 
  $(document).ready(function(){
 
    // Exam change
    $('#exam_code').change(function()
    {
      var exam_code = $('#exam_code').val();
        alert(exam_code);
         // AJAX request
        $.ajax(
        {
          url:'<?=base_url()?>Scribe_form/getSubjects',
          method: 'post',
          data: {exam_code: exam_code},
          dataType: 'json',
          success: function(response)
          {
            alert(response);

            // Remove options 
              $('#sel_subject').find('option').not(':first').remove();
              // Add options
              $.each(response,function(index,subjects)
              {
               $('#sel_subject').append('<option value="'+subjects['subject_code']+'">'+subjects['subject_description']+'</option>'); 
              });
          }
        });
    });
});
 </script>
 </body>
</html>
