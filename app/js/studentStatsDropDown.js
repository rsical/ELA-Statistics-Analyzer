
	$(document).ready(function(){

		//DEPENDENT DROPDOWN FOR STUDENT STATISTICS
	$("#ExamID").on('change', function(){
		var ExamID = $(this).val();
			if(ExamID){
				$.ajax({
					type:'POST',
					url:'studentStatAjax.php',
					data:'ExamID=' + ExamID,
					success:function(html){
						$("#scope").html(html);
					}
				});
			}
	});


	$("#ExamIDTeacher").on('change', function(){
		var ExamIDTeacher = $(this).val();
			if(ExamIDTeacher){
				$.ajax({
					type:'POST',
					url:'studentStatAjax.php',
					data:'ExamIDTeacher=' + ExamIDTeacher,
					success:function(html){
						$("#grade").html(html);
					}
				});
			}
	});




	$("#scope").on('change', function(){
		var scope = $(this).val();
			if(scope){
				$.ajax({
					type:'POST',
					url:'studentStatAjax.php',
					data:'scope=' + scope,
					success:function(html){
						$("#grade").html(html);
					}
				});
			}
	});




	$("#grade").on('change', function(){
		var grade = $(this).val();
			if(grade){
				$.ajax({
					type:'POST',
					url:'studentStatAjax.php',
					data:'grade=' + grade,
					success:function(html){
						$("#student").html(html);
						$(".chosen-select").chosen();
						$(".chosen-select").trigger("chosen:updated");
					}
				});
			}
	});



	});
