<?php
ini_set('max_execution_time', 0); 
ini_set('memory_limit','2048M');
class Sys extends CI_Controller 
{
	function __construct() 
	{
		parent::__construct();
		$this->load->model('sys_m');
		//$this->load->model('migration_m');
	}
	
	function update()
	{
		$this->sys_m->db_init();
		echo "System update complete";
		// set_msg('System update complete.',false);
		// redirect('site');
	}

	function updateTotalDifficulty(){
		$sql = "SELECT reference_id, SUM(difficulty) AS sum_difficulty FROM sj_questions WHERE subject_type='2' GROUP BY reference_id";

		$questions = $this->db->query($sql)->result();

		foreach($questions as $question){

			$reference_id = $question->reference_id;
			$totalDifficulty = $question->sum_difficulty;

			$sqlUpdate = "UPDATE sj_questions SET total_difficulty='$totalDifficulty' WHERE reference_id='$reference_id' ";
			$this->db->query($sqlUpdate);

		}

		echo $this->db->affected_rows();

	}


	function updateQuestionType(){
		$sql = "SELECT a.worksheet_id, a.requirement_id, b.question_type FROM sj_worksheet a 
				JOIN sj_worksheet_requirement b ON a.requirement_id=b.requirement_id
		";

		$quesType = $this->db->query($sql)->result();

		foreach($quesType as $row){

			$worksheet_id = $row->worksheet_id;
			$question_type = $row->question_type;
			
			if($question_type == 1 || $question_type == 2){

				$sqlUpdate = "UPDATE sj_worksheet_questions SET question_type='$question_type' WHERE worksheet_id='$worksheet_id' ";
				$this->db->query($sqlUpdate);

			}


		}

		echo $this->db->affected_rows();
	}


	function test(){
		echo '<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>';
		echo "<form id='myForm' enctype='application/json' method='POST' action = '".base_url()."services/access' >";
		echo "<input type='text' name='token' >";
		echo "<input id='btnSubmit' type='button' value='Submit'>";
		echo "</form>";
		echo '<script>
				$(function(){

					$("#btnSubmit").on("click", function(){

					$("#myForm").submit();
					// $.ajax({
					// 	type: "POST",
					// 	url: "'.base_url().'services/access",
					// 	data: {"token":$("[name=\"token\"]").val()},
					// 	success: function(data){

					// 		if (data.redirect) {
					// 			// data.redirect contains the string URL to redirect to
					// 			window.location.href = data.redirect;
					// 		}
					// 	},
					// 	error: function(data){
					// 		if (data.redirect) {
					// 			// data.redirect contains the string URL to redirect to
					// 			window.location.href = data.redirect;
					// 		}
					// 		console.log(data);
					// 	},
					// 	dataType: "json",
					// 	contentType : "application/json"
					//   });

					 });
					

				});
			</script>';
	}
	
}
?>