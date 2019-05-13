
	$(document).ready(function(){

	$("#matchExamID").on('change', function(){
		var matchExamID = $(this).val();
			if(matchExamID){
				$.ajax({
					type:'POST',
					url:'matchingAjax.php',
					data:'matchExamID=' + matchExamID,
					success:function(html){
						$("#matchScope").html(html);
					}
				});
			}
	});


	$("#matchTeacher").on('change', function(){
		var matchTeacher = $(this).val();
			if(matchTeacher){
				$.ajax({
					type:'POST',
					url:'matchingAjax.php',
					data:'matchTeacher=' + matchTeacher,
					success:function(html){
						$("#matchGrade").html(html);
					}
				});
			}
	});


	$("#matchScope").on('change', function(){
		var matchScope = $(this).val();
			if(matchScope){
				$.ajax({
					type:'POST',
					url:'matchingAjax.php',
					data:'matchScope=' + matchScope,
					success:function(html){
						$("#matchGrade").html(html);
					}
				});
			}
	});



	$("#matchGrade").on('change', function(){
		var matchGrade = $(this).val();

			if(matchGrade){
				$.ajax({
					type:'POST',
					url:'matchingAjax.php',
					data:'matchGrade=' + matchGrade,
					success:function(html){
						$("#matchStudent").html(html);
						$(".chosen-select").chosen();
						$(".chosen-select").trigger("chosen:updated");
					}
				});
			}
	});

	$("#matchStudent").on('change', function(){
		var matchStudent = $(this).val();

			if(matchStudent){
				$.ajax({
					type:'POST',
					url:'matchingAjax.php',
					data:'matchStudent=' + matchStudent,
					success:function(html){
						$("#matchNumber").html(html);

					}
				});

			}
	});
//<button onclick="myFunction()">Try it</button>
	$("#matchNumber").on('change', function(){
		var matchNumber = $(this).val();
			if(matchNumber){
				checkbox();
					}
	});
	});
	//<button onclick="myFunction()">Try it</button>



	function checkbox() {
	 $("input[type=checkbox]").attr('disabled', false);
	}
