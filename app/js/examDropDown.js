
	$(document).ready(function(){

		//DEPENDENT DROPDOWN FOR STUDENT STATISTICS
	$("#myExamID").on('change', function(){
		var myExamID = $(this).val();
			if(myExamID){
				$.ajax({
					type:'POST',
					url:'examAjax.php',
					data:'myExamID=' + myExamID,
					success:function(html){
						$("#ExamScope").html(html);
					}
				});
			}
	});


	$("#ExamTeacher").on('change', function(){
		var ExamTeacher = $(this).val();
			if(ExamTeacher){
				$.ajax({
					type:'POST',
					url:'examAjax.php',
					data:'ExamTeacher=' + ExamTeacher,
					success:function(html){
						$("#ExamGrade").html(html);
					}
				});
			}
	});




	$("#ExamScope").on('change', function(){
		var ExamScope = $(this).val();
			if(ExamScope){
				$.ajax({
					type:'POST',
					url:'examAjax.php',
					data:'ExamScope=' + ExamScope,
					success:function(html){
						$("#ExamGrade").html(html);
					}
				});
			}
	});




	$("#ExamGrade").on('change', function(){
		var ExamGrade = $(this).val();
			if(ExamGrade){
				$.ajax({
					type:'POST',
					url:'examAjax.php',
					data:'ExamGrade=' + ExamGrade,
					success:function(html){
						$("#ExamStudent").html(html);
					}
				});
			}
	});



	});
