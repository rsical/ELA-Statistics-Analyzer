	$(document).ready(function(){

$("#selectExamYear").on('change', function(){
  var selectExamYear = $(this).val();
    if(selectExamYear){
      $.ajax({
        type:'POST',
        url:'AddStuAssessmentAjax.php',
        data:'selectExamYear=' + selectExamYear,
        success:function(html){
          $("#examStudent").html(html);
        }
      });
    }
});

	});