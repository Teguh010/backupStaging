/*
 * *
 *  * Created by PhpStorm.
 *  * User: boonkhailim
 *  * Year: 2017
 *
 */

$(document).ready(function () {
	/* facebook login */

	// This is called with the results from from FB.getLoginStatus().
	function statusChangeCallback(response) {
		console.log('statusChangeCallback');
		console.log(response);
		// The response object is returned with a status field that lets the
		// app know the current login status of the person.
		// Full docs on the response object can be found in the documentation
		// for FB.getLoginStatus().
		if (response.status === 'connected') {
			// Logged into your app and Facebook.
			loginToSmartJen();
			//} //else if (response.status === 'not_authorized') {
			// The person is logged into Facebook, but not your app.
			//document.getElementById('status').innerHTML = 'Please log into this app.';
		} else {
			// The person is not logged into Facebook, so we're not sure if
			// they are logged into this app or not.
			FB.login(function (response) {
				if (response.status === 'connected') {
					loginToSmartJen();
				} else {

				}
			}, { scope: 'public_profile,email' });


		}
	}

	// This function is called when someone finishes with the Login
	// Button.  See the onlogin handler attached to it in the sample
	// code below.
	function checkLoginState() {
		FB.getLoginStatus(function (response) {
			statusChangeCallback(response);
		});
	}

	window.fbAsyncInit = function () {
		FB.init({
			appId: '364028880471243',
			cookie: true,  // enable cookies to allow the server to access 
			// the session
			xfbml: true,  // parse social plugins on this page
			version: 'v2.6' // use graph api version 2.5
		});

		// Now that we've initialized the JavaScript SDK, we call 
		// FB.getLoginStatus().  This function gets the state of the
		// person visiting this page and can return one of three states to
		// the callback you provide.  They can be:
		//
		// 1. Logged into your app ('connected')
		// 2. Logged into Facebook, but not your app ('not_authorized')
		// 3. Not logged into Facebook and can't tell if they are logged into
		//    your app or not.
		//
		// These three cases are handled in the callback function.

		// FB.getLoginStatus(function(response) {
		//   statusChangeCallback(response);
		// });

	};

	// Load the SDK asynchronously
	(function (d, s, id) {
		var js, fjs = d.getElementsByTagName(s)[0];
		if (d.getElementById(id)) return;
		js = d.createElement(s); js.id = id;
		js.src = "//connect.facebook.net/en_US/sdk.js";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));

	// Here we run a very simple test of the Graph API after login is
	// successful.  See statusChangeCallback() for when this call is made.
	function loginToSmartJen() {
		console.log('Welcome!  Fetching your information.... ');
		FB.api('/me?fields=id,name,email,first_name,last_name', function (response) {
			console.log('Successful login for: ' + response.name);
			console.log(response);
			console.log(response.email);
			// document.getElementById('status').innerHTML =
			//   'Thanks for logging in, ' + response.name + '!';

			var fb_email = response.email;
			var fb_id = response.id;
			var full_name = response.name;

			$.ajax({
				url: base_url + 'site/js_facebook_login',
				method: "post",
				data: {
					fb_email: fb_email,
					fb_id: fb_id,
					full_name: full_name
				},
				dataType: 'json',
				success: function (data) {
					if (data.success) {
						window.location.replace(base_url + "profile");
					}
				}

			});
		});
	}


	$('.facebook_login_btn').on('click', function (e) {
		e.preventDefault();
		checkLoginState();
	});

	/* facebook login end */

	$(document).ready(function () {
		$('#student_level').change(function () {
			var level = $(this).val();

			// AJAX request
			$.ajax({
				url: '<?=base_url()?>profile/get_school_list',
				method: 'post',
				data: { level: level },
				dataType: 'json',
				success: function (response) {

					// Remove options
					$('#student_school').empty();

					// Add options
					$.each(response, function (index, data) {
						$('#student_school').append('<option value="' + data['school_id'] + '">' + data['school_name'] + '</option>');
					});
				}
			});
		});

	});

	/* feedback logic start */
	/* feedback logic end */

	/* login / register start */
	$('#forgot_password_a').on('click', function (e) {
		$('#forgot_password_error').hide();
		$('#forgot_password_success').hide();
	});

	$('#reset_password_button').on('click', function (e) {
		e.preventDefault();
		$('#forgot_password_error').hide('fast');
		$('#forgot_password_success').hide('fast');
		var $this = $(this);
		$this.button('loading');

		var ajax_url = base_url + 'site/forgot_password';

		$.ajax({
			url: ajax_url,
			method: "post",
			dataType: 'json',
			data: {
				"reset_password_email": $('#reset_password_email').val(),
			},
			success: function (data) {
				if (data['success'] == true) {
					$('#forgot_password_success').show('fast');
				} else {
					$('#forgot_password_error').html(data['message']).show('fast');
				}

				$('#reset_password_email').val('');

				$this.button('reset');
			}

		});

	});

	/* login / register end */

	/*
		Smartgen start
	*/

	add_substrand_row = '';
	$.ajax({
		url: base_url + 'smartgen/getSubstrandList/',
		method: "post",
		dataType: 'json',
		success: function (data) {
			for (var i = 0; i < data.length; i++) {
				add_substrand_row += "<option value='" + data[i].id + "'>" + data[i].name + "</option>";
			}
		}

	});

	add_topic_row = '';
	$.ajax({
		url: base_url + 'smartgen/getTopicList/1',
		method: "post",
		dataType: 'json',
		success: function (data) {
			add_topic_row += "<option value='all'>Any Topic</option>";
			for (var i = 0; i < data.length; i++) {
				add_topic_row += "<option value='" + data[i].id + "'>" + data[i].name + "</option>";
			}
		}
	})


	$(document).on('click', '.add_level_topic', function (e) {
		e.preventDefault();
		var numLevel = $('select[name*="gen_topic"]').length;

		var html = '<div class="form-group">' +
			'<div class="col-sm-offset-4 col-md-offset-4 col-lg-offset-4 col-xs-6 col-sm-3 col-md-3 col-lg-3">' +
			'<select name="gen_substrand[]" class="form-control substrand_select">' +
			add_substrand_row +
			'</select>' +
			'</div>' +
			'<div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">' +
			'<select name="gen_topic[]" class="form-control topic_select">' +
			add_topic_row +
			'</select>' +
			'</div>';

		if (numLevel == 1) {
			html += '<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2 add_minus_btn_group">' +
				'<a href="#" class="btn btn-custom btn-no-margin add_level_topic"> + </a>' +
				'<a href="#" class="btn btn-danger btn-no-margin-top remove_level_topic"> - </a>' +
				'</div>' +
				'</div>';

			$(html).hide().appendTo($('.topic-group').parent()).fadeIn('fast');
			$(this).remove();

		} else if (numLevel == 2) {
			//add one row with minus button
			html += '<div class="col-xs-12 col-sm-1 col-md-1 col-lg-1 add_minus_btn_group">' +
				'<a href="#" class="btn btn-danger btn-no-margin-top remove_level_topic"> - </a>' +
				'</div>' +
				'</div>';

			$(html).hide().appendTo($('.topic-group').parent()).fadeIn('fast');
			$(this).next().remove();
			$(this).remove();
		}
		return false;
	});

	$(document).on('click', '.remove_level_topic', function (e) {
		e.preventDefault();
		var numLevel = $('select[name*="gen_topic"]').length;

		if (numLevel == 3) {
			var html = '<a href="#" class="btn btn-custom btn-no-margin add_level_topic"> + </a>' +
				'<a href="#" class="btn btn-danger btn-no-margin-top remove_level_topic"> - </a>';
			$(html).hide().appendTo($(this).closest('.form-group').prev().children('.add_minus_btn_group')).fadeIn('fast');
			$(this).closest('.form-group').fadeOut('fast', function () {
				$(this).remove();
			});
		} else if (numLevel == 2) {
			var html = '<a href="#" class="btn btn-custom btn-no-margin add_level_topic"> + </a>';
			$(html).hide().appendTo($(this).closest('.form-group').prev().children('.add_minus_btn_group')).fadeIn('fast');
			$(this).closest('.form-group').fadeOut('fast', function () {
				$(this).remove();
			});
		}
	});


	/**Secondary Math Prototype**/

	/*add_sc_substrand_row = '';
	$.ajax({
		url: base_url + 'smartgen/getScSubstrandList/',
		method: "post",
		dataType: 'json',
		success: function(data) {
			for (var i = 0; i < data.length; i++) {
				add_sc_substrand_row += "<option value='" + data[i].id + "'>" + data[i].name + "</option>";
			}
		}	

	});

	add_sc_topic_row = '';
	$.ajax({
		url: base_url + 'smartgen/getScTopicList/1',
		method: "post",
		dataType: 'json',
		success: function(data) {
			add_topic_row += "<option value='all'>Any Topic</option>";
			for (var i = 0; i < data.length; i++) {
				add_sc_topic_row += "<option value='" + data[i].id + "'>" + data[i].name + "</option>";
			}
		}	
	})


	$(document).on('click', '.add_sc_level_topic', function(e){
		e.preventDefault();
		var numLevel = $('select[name*="gen_topic"]').length;

		var html = '<div class="form-group">' + 
							'<div class="col-sm-offset-4 col-md-offset-4 col-lg-offset-4 col-xs-6 col-sm-3 col-md-3 col-lg-3">' + 
								'<select name="gen_substrand[]" class="form-control substrand_selects">' + 
									add_sc_substrand_row +
								'</select>' +
							'</div>' + 
							'<div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">' + 
								'<select name="gen_topic[]" class="form-control topic_select">' + 
									add_sc_topic_row + 
								'</select>' +
							'</div>';
		
		if (numLevel == 1) {
			html += 	'<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2 add_minus_btn_group">' +
							'<a href="#" class="btn btn-custom btn-no-margin add_sc_level_topic"> + </a>' + 
							'<a href="#" class="btn btn-danger btn-no-margin-top remove_sc_level_topic"> - </a>' + 
						'</div>' +
					'</div>';

			$(html).hide().appendTo($('.topic-group').parent()).fadeIn('fast');
			$(this).remove();

		} else if (numLevel == 2) {
			//add one row with minus button
			html +=  		'<div class="col-xs-12 col-sm-1 col-md-1 col-lg-1 add_sc_level_topic">' + 
								'<a href="#" class="btn btn-danger btn-no-margin-top remove_sc_level_topic"> - </a>' + 
							'</div>' +
						'</div>';

			$(html).hide().appendTo($('.topic-group').parent()).fadeIn('fast');
			$(this).next().remove();
			$(this).remove();
		} 
		return false;
	});

	$(document).on('click', '.remove_sc_level_topic', function(e){
		e.preventDefault();
		var numLevel = $('select[name*="gen_topic"]').length;

		if (numLevel == 3) {
			var html = '<a href="#" class="btn btn-custom btn-no-margin add_sc_level_topic"> + </a>' + 
					   '<a href="#" class="btn btn-danger btn-no-margin-top remove_sc_level_topic"> - </a>';
			$(html).hide().appendTo($(this).closest('.form-group').prev().children('.add_minus_btn_group')).fadeIn('fast');
			$(this).closest('.form-group').fadeOut('fast', function() {
				$(this).remove();
			});
		} else if (numLevel == 2) {
			var html = '<a href="#" class="btn btn-custom btn-no-margin add_sc_level_topic"> + </a>';
			$(html).hide().appendTo($(this).closest('.form-group').prev().children('.add_minus_btn_group')).fadeIn('fast');
			$(this).closest('.form-group').fadeOut('fast', function() {
				$(this).remove();
			});
		}
	}); */

	/*** Secondary Math Prototype*/


	$('.regen_question').on('click', function (e) {
		e.preventDefault();
		var clickedStr = ($(this).attr('id')).split("_");
		var quesNum = clickedStr[1];
		var nextQuesNum = quesNum + 1;
		var ajaxUrl = $(this).attr('href');
		var quesList = $(this).parents('#question_' + quesNum);
		var nextQuesList = quesList.next().attr('class');

		$.ajax({
			url: ajaxUrl,
			method: "post",
			dataType: 'json',
			data: {
				quesNum: quesNum
			},
			success: function (data) {
				console.log(data);
				if (nextQuesList === 'list-group-item clearfix') {
					$(quesList).nextUntil('#addNewSubQuestionDiv_' + nextQuesNum).remove();
				}
				$('.sub_question').remove();
				$('#question_' + quesNum + " .question_category").animate({ opacity: 0 }, function () {
					$(this).html('');
					$(this).append('[' + data['substrand'] + '] ' + data['category'] + '<a href="#" class="question-remove" title="Remove Question" data-id="' + data['question'].question_id + '"><i class="fa fa-times"></i></a>').animate({ opacity: 1 });
				});
				$('#question_' + quesNum + " .question_strategy").animate({ opacity: 0 }, function () {
					$(this).html('');
					$(this).append(data['strategy']).animate({ opacity: 1 });
				});
				$('#question_' + quesNum + " .question_text").animate({ opacity: 0 }, function () {
					$(this).html('');
					$('#correct_answer_' + quesNum).html('(' + data.correctAnswerOptionNum + ') ' + data.correctAnswer);
					var answerOptionHtml = "<br><br>";
					if (data['que_type'] == 1) {
						var answerOption = data['answerOption'];
						var i = 1;
						for (var answer in answerOption) {
							answerOptionHtml += i + ") " + answerOption[answer].answer_text + "<br>";
							i++;
						}
					} else {
						switch (data['question'].difficulty) {
							case 2:
								answerOptionHtml += '<br><br><br><br><br>';
								break;
							case 3:
								answerOptionHtml += '<br><br><br><br><br><br><br><br><br>';
								break;
							case 4:
								answerOptionHtml += '<br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
								break;
							case 5:
								answerOptionHtml += '<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
								break;
							default:
								break;
						}
					}

					if (data['question'].graphical != "0") {
						$(this).append(data['question'].question_text).append('<img src="' + data['imageUrl'] + '/questionImage/' + data['question'].graphical + '" class="img-responsive" style="max-width:60%;">').append(answerOptionHtml).animate({ opacity: 1 });
					} else {
						$(this).append(data['question'].question_text + answerOptionHtml).animate({ opacity: 1 });
					}

					if (data['sub_question'] == "B") {
						$(this).parent().append('<button class="btn btn-warning pull-right sub_question" id="subqid_"' + data['question'].question_id + '><span data-toggle="tooltip" data-placement="top" data-original-title="Add sub question, please">Sub Question</span></button>');
					}

					if (data['que_type'] == 1) {
						$("span.que_type").replaceWith('<span class="btn btn-info pull-right que_type" data-toggle="tooltip" data-placement="top" title="This question is only available in MCQ">MCQ</span>').animate({ opacity: 1 });
					} else {
						$("span.que_type").replaceWith('<span class="btn btn-info pull-right que_type" data-toggle="tooltip" data-placement="top" title="This question is only available in Non-MCQ">Non-MCQ</span>').animate({ opacity: 1 });
					}

					$(this).next().attr('id', 'qid_' + data['question'].question_id);

					MathJax.Hub.Typeset();

				});

				// MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
			}

		});

		return false;
	});

	$('.regen_question_admin').on('click', function (e) {
		e.preventDefault();
		var clickedStr = ($(this).attr('id')).split("_");
		var quesNum = clickedStr[1];
		var nextQuesNum = quesNum + 1;
		var ajaxUrl = $(this).attr('href');
		var quesList = $(this).parents('#question_' + quesNum);
		var nextQuesList = quesList.next().attr('class');

		$.ajax({
			url: ajaxUrl,
			method: "post",
			dataType: 'json',
			data: {
				quesNum: quesNum
			},
			success: function (data) {
				console.log(data);
				if (nextQuesList === 'list-group-item clearfix') {
					$(quesList).nextUntil('#addNewSubQuestionDiv_' + nextQuesNum).remove();
				}
				$('.sub_question').remove();
				$('#question_' + quesNum + " .question_category").animate({ opacity: 0 }, function () {
					$(this).html('');
					$(this).append('[' + data['substrand'] + '] ' + data['category'] + '<a href="#" class="question-remove" title="Remove Question" data-id="' + data['question'].question_id + '"><i class="fa fa-times"></i></a>').animate({ opacity: 1 });
				});
				$('#question_' + quesNum + " .question_strategy").animate({ opacity: 0 }, function () {
					$(this).html('');
					$(this).append(data['strategy']).animate({ opacity: 1 });
				});
				$('#question_' + quesNum + " .question_text").animate({ opacity: 0 }, function () {
					$(this).html('');
					$('#correct_answer_' + quesNum).html('(' + data.correctAnswerOptionNum + ') ' + data.correctAnswer);
					var answerOptionHtml = "<br><br>";
					if (data['que_type'] == 1) {
						var answerOption = data['answerOption'];
						var i = 1;
						for (var answer in answerOption) {
							answerOptionHtml += i + ") " + answerOption[answer].answer_text + "<br>";
							i++;
						}
					} else {
						switch (data['question'].difficulty) {
							case 2:
								answerOptionHtml += '<br><br><br><br><br>';
								break;
							case 3:
								answerOptionHtml += '<br><br><br><br><br><br><br><br><br>';
								break;
							case 4:
								answerOptionHtml += '<br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
								break;
							case 5:
								answerOptionHtml += '<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>';
								break;
							default:
								break;
						}
					}

					if (data['question'].graphical != "0") {
						$(this).append(data['question'].question_text).append('<img src="' + data['imageUrl'] + '/questionImage/' + data['question'].graphical + '" class="img-responsive" style="max-width:60%;">').append(answerOptionHtml).animate({ opacity: 1 });
					} else {
						$(this).append(data['question'].question_text + answerOptionHtml).animate({ opacity: 1 });
					}

					if (data['sub_question'] == "B") {
						$(this).parent().append('<button class="btn btn-warning pull-right sub_question" id="subqid_"' + data['question'].question_id + '><span data-toggle="tooltip" data-placement="top" data-original-title="Add sub question, please">Sub Question</span></button>');
					}

					if (data['que_type'] == 1) {
						$("span.que_type").replaceWith('<span class="btn btn-info pull-right que_type" data-toggle="tooltip" data-placement="top" title="This question is only available in MCQ">MCQ</span>').animate({ opacity: 1 });
					} else {
						$("span.que_type").replaceWith('<span class="btn btn-info pull-right que_type" data-toggle="tooltip" data-placement="top" title="This question is only available in Non-MCQ">Non-MCQ</span>').animate({ opacity: 1 });
					}

					$(this).next().attr('id', 'qid_' + data['question'].question_id);

					MathJax.Hub.Typeset();

				});

				// MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
			}

		});

		return false;
	});

	$('#save_worksheet_button').on('click', function (e) {
		if ($('#worksheet_name').val() == "") {
			$('#worksheet_name').parents('.form-group').addClass("has-error");
			$('#save_worksheet_form .modal-body').append('<div class="alert alert-danger">Worksheet name cannot be empty.</div>');
			$('#worksheet_name').focus();
			return false;
		} else {
			return true;
		}

	});

	$('#outputPDF').on('submit', function (e) {
		var question_difficulty = $("input[name='question_difficulty[]']")
			  .map(function(){return $(this).val();}).get();

		var postStudentId = document.getElementById("student_id");
		if(postStudentId){
			var student_id= postStudentId.value;
		} else {
			var student_id = undefined;
		}
		
		var postTutor_id = document.getElementById("tutor_id");	
		if(postTutor_id) {
			var tutor_id = postTutor_id.value
		}

		var postWorksheet_id = document.getElementById("worksheet_id");
		if(postWorksheet_id){
			var worksheet_id = postWorksheet_id.value;
		}

		var defs = '<svg>' + $('#MathJax_SVG_Hidden').next().html() + '</svg>';

		// var postnoQR = document.outputPDF.noQR;
		// if(postnoQR){
		// 	var noQR = postnoQR;
		// }	
		// else {
		// 	var noQR = 0;
		// }
		var form = document.getElementById('outputPDF');
		var postnoQR = form.elements['noQR'];
		var postnoQR2 = form.elements['noQR2'];
		if(postnoQR){
			var noQR = postnoQR.value;
		}	
		else if (postnoQR2) {
			var noQR = postnoQR2.value;
		}
		else {
			var noQR = 0;
		}
		// var noQR = postnoQR.value;

		console.log(postnoQR);
		console.log(noQR);
		// console.log(noQR);
		// var new_svg = $('.mj_element').html();

		// $('#pdfOutputString').val(window.btoa(encodeURIComponent(new_svg)));

		var svg = "";
		var index = 1;
		var index_split;
		var index_array;
		var qns_no = 0;
		$('.question_text').each(function () {
			var ques_no = $(this).prev();
			if (ques_no.find('option:selected').length > 0) {
				index_split = ques_no.find('option:selected').text();
				index_array = index_split.split(" ");
				index = index_array[2];
			} else {
				index_split = ques_no.text();
				index_array = index_split.split(" ");
				index_array = index_array[2].split("[");
				index = index_array[0];
			}

			qns = $(this).html();
			let question_answer_tag_start = qns.search(/\<div class="question_answer">/i);
			let question_answer_tag_end = qns.search(/\<\/div>/i);
			let html = qns.slice(0,question_answer_tag_start) + '</div>';

			svg += "<div class='question'><p>" + index + ") " + html + "</p>";

			if (noQR == 1) {
				// svg += "<div class='question'><p>" + index + ") " + $(this).html() + "</p></div>";
				var ques_difficulty = $(this).parent().find('#ques_difficulty').val();

				if(ques_difficulty == 1) {
					svg += "<br><br>";
				}
				
				if(ques_difficulty == 2) {
					svg += "<br><br>";
				}
				
				if(ques_difficulty == 3) {
					svg += "<br><br><br>";
				}
				
				if(ques_difficulty == 4) {
					svg += "<br><br><br><br>";
				}
				
				if(ques_difficulty == 5) {
					svg += "<br><br><br><br><br>";
				}

				if(ques_difficulty == 6) {
					svg += "<br><br><br><br><br><br>";
				}

				if(ques_difficulty == 7) {
					svg += "<br><br><br><br><br><br><br>";
				}

				if(ques_difficulty == 8) {
					svg += "<br><br><br><br><br><br><br><br>";
				}

				if(ques_difficulty == 9) {
					svg += "<br><br><br><br><br><br><br><br><br>";
				}

				if(ques_difficulty == 10) {
					svg += "<br><br><br><br><br><br><br><br><br><br>";
				}
				// svg += "<br><br>";
			}
			else {
				// svg += "<div class='question'><p>" + index + ") " + $(this).html() + "</p></div>";
				// svg += "<br><br>";
				

				// Creates the working and answer box and overlay the QR code beside the answer box
				if (question_difficulty[qns_no] == 2) { // Need to add in more handling for other difficulty levels

					// Prepare QR code data
					let qr_data = qns_no+1 + '/' + student_id + '/' + tutor_id + '/' + worksheet_id;
					
					// Generate QR code
					var qr = qrcode(3, 'H');
					qr.addData(qr_data);
					qr.make();

					// Extract the QR Code svg <path></path> code
					let QRCode = qr.createSvgTag();
					let pathTagStart = QRCode.search(/\<path /i);
					let svgTagEnd = QRCode.search(/\<\/svg>/i);
					let pathTagHTML = QRCode.slice(pathTagStart,svgTagEnd);

					svg += 
					`	<svg width='800' height='300' xmlns='http://www.w3.org/2000/svg'>\
							<g> \
							<rect height='300' width='800' \
								style='fill:none;stroke-width:1;stroke:rgb(0,0,0)'/>\
							<line id='vert' x1='550' y1='220' x2='550' y2='300'\
								style='stroke:rgb(0,0,0);stroke-width:1' />\
							<line id='hori' x1='550' y1='220' x2='800' y2='220'\
								style='stroke:rgb(0,0,0);stroke-width:1' />\
							<text x='565' y='265' style='font-family:Arial, Helvetica, sans-serif;\
								font-size:18; stroke:#000;' >Ans:</text>\
							</g>\
							<g transform='translate(470,220) scale(1)'>\
								${pathTagHTML}\
							</g>\
						</svg>\
					`;
				}
				svg += "</div>";
				qns_no++;
			}
		});

		var ans = "";
		index = 1;
		$('.correctAnswerText').each(function () {
			index_split = $(this).attr('id');
			index_array = index_split.split("_");
			index = index_array[2];

			ans += "<p>" + index + ") " + $(this).html() + "</p>";
		});

		ans = '<div class="answer">' + ans + '</div>';
		$('#pdfOutputString').val(window.btoa(encodeURIComponent(svg + ans + defs)));
		return true;
	});

	$('#outputMockExamPDF').on('submit', function (e) {
		var defs = '<svg>' + $('#MathJax_SVG_Hidden').next().html() + '</svg>';
		var svg = "";
		var index = 0;
		$('.question_text').each(function () {
			svg += "<div class='question'><p><u><b>" + $('.question_number_text:eq(' + index + ')').text() + " " + $('.question_difficulty_text:eq(' + index + ')').text() + ".</b></u></p> " + $(this).html() + "</div>";
			svg += "<br><br>";
			index++;
		});

		var ans = "";
		$('.correctAnswer').each(function () {
			ans += "<p>" + $(this).html() + "</p>";
		});
		ans = '<div class="answer">' + ans + '</div>';

		$('#pdfOutputString').val(window.btoa(encodeURIComponent(svg + ans + defs)));
		return true;
	});


	$('.sub_question').on('click', function (e) {
		var clickedStr = ($(this).attr('id')).split("_");
		var subQueId = clickedStr[1];
		var ajax_url = base_url + 'smartgen/subQuestion';
		var newDiv = $(this).parent();
		var newDivSplit = (newDiv.attr('id')).split("_");
		var newDivId = newDivSplit[1];
		var spanList = newDiv.children()[0].children[0].innerText;
		var getSpanList = spanList.split("]");
		var substrandList = newDiv.children().find("span")[0].innerText;
		var categoryList = newDiv.children().find("span")[1].innerText;

		$.ajax({
			url: ajax_url,
			method: "post",
			dataType: 'json',
			data: {
				"sub_question_id": subQueId,
			},
			success: function (data) {
				let mcqCount = 1;
				let questionAnswer = '';
				let questionImage = '';
				let alp = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm'];
				var subQuestions = new Array();

				for (i = 0; i < data.questionList.length; i++) {
					var j = ((data.questionList[i].question_id) - (data.questionList[i].reference_id)) - 1;
					subQuestions.push(data.questionList[i].question_id);
					questionText = data.questionList[i].question_text;
					questionId = data.questionList[i].question_id;
					var newDivs = document.createElement("LI");
					newDivs.className = "list-group-item clearfix";
					newDivs.setAttribute("id", "question_" + newDivId + alp[j]);

					if (data.questionList[i].graphical != 0) {
						questionImage = '<img src="' + data.questionList[i].branch_image_url + '/questionImage/' + data.questionList[i].graphical + '" class="img-responsive" style="max-width:60%;">';
					}

					questionAnswer = 1 + ') ' + data.answerList[i].answerOption[0].answer_text + ' <br >';
					questionAnswer1 = 2 + ') ' + data.answerList[i].answerOption[1].answer_text + ' <br >';
					questionAnswer2 = 3 + ') ' + data.answerList[i].answerOption[2].answer_text + ' <br >';
					questionAnswer3 = 4 + ') ' + data.answerList[i].answerOption[3].answer_text + ' <br >';

					$(newDivs).append(`
							<div class="question_number"> Question ${newDivId}${alp[j]}<span class="pull-right question_category">${substrandList}</span>
							<br>
							<span class="pull-right" style="padding-top:9px;">${categoryList}</span>
							</div>
							<div class="question_text">`+ questionText + `
							<div>`
						+ questionImage +
						`</div>
							<div class="question_answer">`
						+ questionAnswer + questionAnswer1 + questionAnswer2 + questionAnswer3 +
						`</div>
							</div>
							<button class="btn btn-warning pull-right remove_sub_question" id="remove_sub_question_${questionId}"><span data-toggle="tooltip" data-placement="top" title="Remove this question, please.">Remove Sub Question</span></button>
					`);

					if ($('#correct_answer_' + newDivId + alp[j]).length) {
						$('#correct_answer_' + newDivId + alp[j]).after(`
							<div id="correct_answer_` + newDivId + alp[j] + `" class="correctAnswer">
							(` + data.answerList[i].correctAnswerOptionNum + `) ` + data.answerList[i].correctAnswer + ` 
							</div>
						`);
					} else {
						$('#correct_answer_' + newDivId).after(`
							<div id="correct_answer_` + newDivId + alp[j] + `" class="correctAnswer">
							(` + data.answerList[i].correctAnswerOptionNum + `) ` + data.answerList[i].correctAnswer + ` 
							</div>
						`);
					}



					MathJax.Hub.Queue(['Typeset', MathJax.Hub, questionText]);
					MathJax.Hub.Queue(['Typeset', MathJax.Hub, questionAnswer]);
					MathJax.Hub.Queue(['Typeset', MathJax.Hub, questionAnswer1]);
					MathJax.Hub.Queue(['Typeset', MathJax.Hub, questionAnswer2]);
					MathJax.Hub.Queue(['Typeset', MathJax.Hub, questionAnswer3]);
					$("#addNewSubQuestionDiv_" + newDivId).append(newDivs);

				}
				// Add reference to parent question
				$('[data-id="' + subQueId + '"]').data('sub_question', subQuestions);

				$('.flag_question').on('click', function () {
					$('#flag_question_error').hide();
					$('#flag_question_success').hide();
					$('#flagged_question_id').val($(this).attr('id').split('_')[1]);
				});
			}
		});
		$(this).hide();
	});

	var array = new Array();

	$(document).on('click', '.remove_sub_question', function (e) {
		e.preventDefault();

		var sub_que_id = $(this).attr('id').split('_')[3];

		var parent_que_id = $(this).parent().parent().children().find('.question_order').attr('data-id');

		$(this).parent().remove();

		$.ajax({
			url: base_url + 'smartgen/removeSubQuestion',
			type: 'POST',
			dataType: 'json',
			data: {
				parent_question_id: parent_que_id,
				sub_question_id: sub_que_id
			},
			success: function (result) {
				if (typeof result[parent_que_id] == 'undefined') {

					$("#subqid_" + parent_que_id).show();
				}
			}
		});
	});

	$('.flag_question').on('click', function () {
		$('#flag_question_error').hide();
		$('#flag_question_success').hide();
		$('#flagged_question_id').val($(this).attr('id').split('_')[1]);
	});

	$('#flag_question_button').on('click', function (e) {
		e.preventDefault();
		if ($('#error_comment').val().length > 1024) {
			$('#flag_question_error').html('Comment exceeded 1024 characters').show('fast').delay(3000).hide('slow');
			return false;
		}

		var $this = $(this);
		$this.button('loading');

		var ajax_url = base_url + 'smartgen/flagQuestion';

		$.ajax({
			url: ajax_url,
			method: "post",
			dataType: 'json',
			data: {
				"user_id": $('#user_id').val(),
				"error_type": $('#error_type').val(),
				"error_comment": $('#error_comment').val(),
				"flagged_question_id": $('#flagged_question_id').val()
			},
			success: function (data) {
				if (data['success'] == true) {
					$('#flag_question_success').show('fast').delay(3000).hide('slow');
					$('#error_comment').val('');
				} else {
					$('#flag_question_error').html('Some issue sending to Smartjen.. please try again later').show('fast').delay(5000).hide('slow');
				}
				$this.button('reset');
			}
		});
	});


	var assigned_student_list = $('#assigned_student_list');
	var deassigned_student_list = $('#deassign_student_list');
	/*$(document).on('click', '.assign_student', function(e) {
		e.preventDefault();
		var new_button = $(this).removeClass('assign_student btn-custom').addClass('deassign_student btn-danger').html('De-assign')[0];
		var new_row = '<li class="list-group-item question_text student_li"><span>' +
		    $(this).prev().text() +
		    '</span>'+
			'<a href="#" id="' + $(this).attr('id') + '" class="btn btn-danger btn-no-margin pull-right deassign_student">Remove</a>' +
			'</li>';
		$(this).parent().remove();

		if (assigned_student_list.children('.helper_text').length > 0) {
			assigned_student_list.children('.helper_text')[0].remove();
		}

		assigned_student_list.append(new_row);

		if (deassigned_student_list.children().length == 0) {
			deassigned_student_list.append('<li class="list-group-item question_text helper_text">No students</li>');
		}
	});*/

	/*$(document).on('click', '.assign_all_students', function(e) {
		e.preventDefault();
		$('#deassign_student_list').children('.student_li').map(function(index, student) {
			student.children[1].click();
		});
	});*/

	$(document).on('click', '.deassign_student', function (e) {
		e.preventDefault();
		var new_row = '<li class="list-group-item question_text student_li"><span>' +
			$(this).prev().text() +
			'</span>' +
			'<a href="#" id="' + $(this).attr('id') + '" class="btn btn-custom btn-no-margin pull-right assign_student">Assign</a>' +
			'</li>';
		$(this).parent().remove();

		if (deassigned_student_list.children('.helper_text').length > 0) {
			deassigned_student_list.children('.helper_text')[0].remove();
		}
		deassigned_student_list.append(new_row);

		if (assigned_student_list.children().length == 0) {
			assigned_student_list.append('<li class="list-group-item question_text helper_text">No assigned student yet</li>');
		}
		$("#hidden_assigned_student_" + $(this).attr('id')).remove();
	});

	/*$(document).on('click', '.deassign_all_students', function(e) {
		e.preventDefault();
		$('#assigned_student_list').children('.student_li').map(function(index, student) {
			student.children[1].click();
		});
	});*/

	$(document).on('click', '#assign_student_submit_button', function (e) {
		e.preventDefault();
		$('.assigned_student').each(function () {
			$(this).remove();
		});

		// $('#assigned_student_list').children('.student_li').map(function(index, student) {
		// 	$('#assign_worksheet_form').append('<input type="hidden" class="hidden_assigned_student" name="assigned_students[]" value="' + student.children[1].id + '">');
		// });

		// console.log($('.hidden_assigned_student').val());return false;

		$('#assign_worksheet_form').submit();

	});


	/*
		Smartgen stop
	*/

	/*
		View worksheet start
	*/
	$('.type-select').on('click', function (e) {
		e.preventDefault();
		if ($(this).attr('href') == "#MCQ") {
			$('.cmn-toggle').each(function () {
				$(this).prop('checked', '');
			});

			$("*[class^='question_type_']").each(function () {
				$(this).val("MCQ");
			});
		}
		else if ($(this).attr('href') == "#openEnded") {
			$('.cmn-toggle').each(function () {
				$(this).prop('checked', 'checked');
			});

			$("*[class^='question_type_']").each(function () {
				$(this).val("openEnded");
			});
		}
	});

	$('.cmn-toggle').on('click', function () {
		var idArray = $(this).attr('id').split('-');
		var id = idArray[2];

		if ($('.question_type_' + id).val() == "MCQ") {
			$('.question_type_' + id).val("openEnded");
		} else {
			$('.question_type_' + id).val("MCQ");
		}

	});

	/*
		View worksheet stop
	*/

	/*
		Profile page start
	*/

	$('.datatable').DataTable({
		// "sDom": "<'row'<'col-xs-6'l><'col-xs-6'f>r>t<'row'<'col-xs-6'i><'col-xs-6'p>>",
		"aaSorting": [],
		"oLanguage": {
			"sLengthMenu": "_MENU_ records per pages",
			"sSearch": ""
		},
		//"order": [[ 1, "desc" ]],
		"aoColumnDefs": [
			{
				"bSortable": false,
				"aTargets": [-1] // <-- gets last column and turns off sorting
			}
		],
		"columnDefs": [
			{ "orderable": false, "targets": [0, 1, 2, 3] }
		],
		//	"columnDefs": [{orderable: false, targets: -1}],
		"bLengthChange": false,
		"bInfo": false,
		initComplete: function () {
			var i = 1;
			this.api().columns(':visible :not(:last-child)').every(function () {
				var column = this;
				var col_header = $(column.header()).html();
				console.log(col_header);
				if (col_header == 'Created Date') {
					var select = $('<select class="form-control sort_dataTable_' + i + '"><option value="">' + col_header + '</option></select>')
						.appendTo($(column.header()).empty())
						.on('change', function () {
							var val = $.fn.dataTable.util.escapeRegex(
								$(this).val()
							);

							column
								.search(val ? "\\b" + val + "\\b" : '', true, false)
								.draw();
						});
					//$('<span class="fa fa-sort sort_data"></span>').appendTo( $(column.header()));
					var date_arr = [];
					column.data().unique().each(function (d, j) {
						var date_only = d.substring(0, 10);
						if (!date_arr.includes(date_only)) {
							select.append('<option value="' + date_only + '">' + date_only + '</option>');
							date_arr.push(date_only);
						}
					});
				} else if (col_header == 'Group') {
					var select = $('<select class="form-control sort_dataTable_' + i + '"><option value="">' + col_header + '</option></select>')
						.appendTo($(column.header()).empty())
						.on('change', function () {
							var val = $.fn.dataTable.util.escapeRegex(
								$(this).val()
							);
							console.log(val);
							column
								.search(val ? "\\b" + val + "\\b" : '', true, false)
								.draw();
						});
					var group_arr = [];
					column.data().unique().sort().each(function (d, j) {
						d = d.replace(/(<([^>]+)>)/ig, "");
						if (!group_arr.includes(d)) {
							select.append('<option value="' + d + '">' + d + '</option>');
							group_arr.push(d);
						}
					});
				} else {
					var select = $('<select class="form-control sort_dataTable_' + i + '"><option value="">' + col_header + '</option></select>')
						.appendTo($(column.header()).empty())
						.on('change', function () {
							var val = $.fn.dataTable.util.escapeRegex(
								$(this).val()
							);

							column
								.search(val ? '^' + val + '$' : '', true, false)
								.draw();
						});
					//$('<span class="fa fa-sort sort_data"></span>').appendTo( $(column.header()));
					column.data().unique().sort().each(function (d, j) {
						select.append('<option value="' + d + '">' + d + '</option>')
					});
				}
				i++;
			});
		}
	});

	$("#datepicker").datepicker({ format: 'yyyy-mm-dd' });
	$('.dataTables_filter input').addClass('form-control').attr('placeholder', 'Search...');
	$('.dataTables_length select').addClass('form-control');

	$('.delete_worksheet_btn').on('click', function () {
		var worksheetIdArray = $(this).attr('id').split('_');
		var worksheetId = worksheetIdArray[1];
		$('#delete_worksheet_id').val(worksheetId);
	});

	$('.analytics_worksheet_btn').on('click', function (e) {
		e.preventDefault();
		var worksheetIdArray = $(this).attr('id').split('_');
		var worksheetId = worksheetIdArray[1];
		$('#analytics_worksheet_id').val(worksheetId);
	});

	$('.unarchive_worksheet_btn').on('click', function () {
		var worksheetIdArray = $(this).attr('id').split('_');
		var worksheetId = worksheetIdArray[1];
		$('#archive_worksheet_id').val(worksheetId);
	});


	$('.untag_student_btn').on('click', function () {
		var studentIdArray = $(this).attr('id').split('_');
		var studentId = studentIdArray[2];
		$('#untag_student_id').val(studentId);
	});

	$("#tagGroupModal").on("hidden.bs.modal", function () {
		$('#exist_group').val('');
		$('#tag_group_student_id').val('');
		$('.new-group').css('display', 'none');
	});

	$('.remove_tutor_btn').on('click', function () {
		var tutorIdArray = $(this).attr('id').split('_');
		var tutorId = tutorIdArray[1];
		$('#untag_tutor_id').val(tutorId);
	});

	$('#create_student_error_div').hide();

	$('#create_group_error_div').hide();

	$('#create_user_error_div').hide();

	$('#blast_student_error_div').hide();

	$('#parent_email_div').hide();

	$('#is_student_parent_checkbox').on('change', function () {
		if ($(this).prop('checked')) {
			$('#parent_email_div').hide('fast');
		} else {
			$('#parent_email_div').show('fast');
		}
	});

	$('#admin_register_btn').on('click', function (e) {

		e.preventDefault();
		var errorMsg = "";
		var usernameError = false;
		var fullnameError = false;
		var emailError = false;
		var parEmailError = false;
		var mobileError = false;
		var parMobileError = false;
		var passwordError = false;
		var cpasswordError = false;
		var regexp = /^[a-zA-Z0-9-_]+$/;
		var emailRegexp = /(^$|^.*@.*\..*$)/;
		var mobileRegexp = /^$|[8|9]\d{3}\s\d{4}/;
		var mobileRegexps = /^$|[8|9]\d{7}/;
		var validUsername = $('#register_username').val().match(regexp);
		var validEmail = $('#register_email').val().match(emailRegexp);
		var validParEmail = $('#register_parent_email').val().match(emailRegexp);
		var validMobile = $('#register_mobile').val().match(mobileRegexp);
		var validMobiles = $('#register_mobile').val().match(mobileRegexps);
		var validParMobile = $('#register_parent_mobile').val().match(mobileRegexp);
		var validParMobiles = $('#register_parent_mobile').val().match(mobileRegexps);
		var account_type = $('#register_type_role').find('option:selected').val();

		if ($('#register_username').val().trim() == "") {
			errorMsg += "<p> Username cannot be empty </p>";
			usernameError = true;
		}

		if (validUsername == null) {
			errorMsg += "<p> Username field may only contain alpha-numeric characters, underscores, and dashes </p>";
			usernameError = true;
		}

		if ($('#register_username').val().length > 30) {
			errorMsg += "<p> Username cannot be more than 30 characters </p>";
			usernameError = true;
		}

		if (validEmail == null) {
			errorMsg += "<p>Email field is not valid. Please select valid email address </p>";
			emailError = true;
		}

		if (validMobile == null && validMobiles == null) {
			errorMsg += "<p> Please use Singapore registered mobile number starting with 8 or 9. </p>";
			mobileError = true;
		}

		if ($('#register_mobile').val().trim() == "") {
			errorMsg += "<p> Contact number is a required field. Please fill in to register an account. </p>";
			mobileError = true;
		}

		if ($('#register_email').val().trim() == "") {
			errorMsg += "<p> Email address is a required field. Please fill in to register an account. </p>";
			emailError = true;
		}


		if ($('#register_fullName').val().trim() == "") {
			errorMsg += "<p> User Fullname cannot be empty </p>";
			fullnameError = true;
		}

		if ($('#register_password').val().length < 5) {
			errorMsg += "<p> Password length needs to have 5 or more characters </p>";
			passwordError = true;
		}

		if ($('#register_cpassword').val() != $('#register_password').val()) {
			errorMsg += "<p> Passwords do not match  </p>";
			cpasswordError = true;
		}

		if (account_type == 'parent') {
			if (validParMobile == null && validParMobiles == null) {
				errorMsg += "<p> Please use Singapore registered mobile number starting with 8 or 9. </p>";
				parMobileError = true;
			}

			if ($('#register_parent_email').val().trim() == "" && $('#register_parent_mobile').val().trim() == "") {
				errorMsg += "<p> Parent Email and Parent Mobile cannot be empty </p>";
				parEmailError = true;
				parMobileError = true;
			}

			if ($('#register_parent_email').val().trim() == "") {
				errorMsg += "<p> Parent Email cannot be empty </p>";
				parEmailError = true;
			}

			if (validParEmail == null) {
				errorMsg += "<p> Parent email field is not valid. Please select valid email address </p>";
				parEmailError = true;
			}
		}

		$.ajax({
			url: base_url + 'administrator/checkUserExist',
			method: "post",
			dataType: 'json',
			data: {
				userName: $('#register_username').val().trim(),
				email: $('#register_email').val().trim(),
			},
			success: function (data) {

				if (data.usernameExist == true) {
					errorMsg += "<p>Username existed. Please select another username </p>";
					usernameError = true;
				}

				if (data.emailExist == true) {
					errorMsg += "<p>Email existed. Please select another email address </p>";
					emailError = true;
				}

				if (usernameError) {
					$('#create_user_username_div').addClass('has-error');
				} else {
					$('#create_user_username_div').removeClass('has-error');
				}

				if (fullnameError) {
					$('#create_user_fullname_div').addClass('has-error');
				} else {
					$('#create_user_fullname_div').removeClass('has-error');
				}

				if (emailError) {
					$('#register_email_div').addClass('has-error');
				} else {
					$('#register_email_div').removeClass('has-error');
				}

				if (parEmailError) {
					$('#register_parent_email_div').addClass('has-error');
				} else {
					$('#register_parent_email_div').removeClass('has-error');
				}

				if (mobileError) {
					$('#register_mobile_div').addClass('has-error');
				} else {
					$('#register_mobile_div').removeClass('has-error');
				}

				if (parMobileError) {
					$('#register_parent_mobile_div').addClass('has-error');
				} else {
					$('#register_parent_mobile_div').removeClass('has-error');
				}

				if (passwordError) {
					$('#create_user_password_div').addClass('has-error');
				} else {
					$('#create_user_password_div').removeClass('has-error');
				}

				if (cpasswordError) {
					$('#create_user_cpassword_div').addClass('has-error');
				} else {
					$('#create_user_cpassword_div').removeClass('has-error');
				}

				if (usernameError || fullnameError || emailError || parEmailError || mobileError || passwordError || cpasswordError) {
					displayErrorMessageInForm($('#admin_create_user_form'), $('#create_user_error_div'), errorMsg);
					return false;
				} else {
					$('#admin_create_user_form').submit();
				}

			}
		});

		return false;
	});


	$('#create_student_button').on('click', function (e) {
		e.preventDefault();
		var errorMsg = "";
		var usernameError = false;
		var fullnameError = false;
		var emailError = false;
		var parEmailError = false;
		var mobileError = false;
		var parMobileError = false;
		var passwordError = false;
		var cpasswordError = false;
		var regexp = /^[a-zA-Z0-9-_]+$/;
		var emailRegexp = /(^$|^.*@.*\..*$)/;
		var mobileRegexp = /^$|[8|9]\d{3}\s\d{4}/;
		var mobileRegexps = /^$|[8|9]\d{7}/;
		var validUsername = $('#create_student_username').val().match(regexp);
		var validEmail = $('#create_student_email').val().match(emailRegexp);
		var validParEmail = $('#create_parent_email').val().match(emailRegexp);
		var validMobile = $('#create_student_mobile').val().match(mobileRegexp);
		var validMobiles = $('#create_student_mobile').val().match(mobileRegexps);
		var validParMobile = $('#create_parent_mobile').val().match(mobileRegexp);
		var validParMobiles = $('#create_parent_mobile').val().match(mobileRegexps);
		if ($('#create_student_username').val().trim() == "") {
			errorMsg += "<p> Student Username cannot be empty </p>";
			usernameError = true;
		}

		if (validUsername == null) {
			errorMsg += "<p> Student Username field may only contain alpha-numeric characters, underscores, and dashes </p>";
			usernameError = true;
		}

		if ($('#create_student_username').val().length > 30) {
			errorMsg += "<p> Student Username cannot be more than 30 characters </p>";
			usernameError = true;
		}

		if (validEmail == null) {
			errorMsg += "<p> Student email field is not valid. Please select valid email address </p>";
			emailError = true;
		}

		if (validParEmail == null) {
			errorMsg += "<p> Parent email field is not valid. Please select valid email address </p>";
			parEmailError = true;
		}

		if (validMobile == null && validMobiles == null) {
			errorMsg += "<p> Please use Singapore registered mobile number starting with 8 or 9. </p>";
			mobileError = true;
		}

		if (validParMobile == null && validParMobiles == null) {
			errorMsg += "<p> Please use Singapore registered mobile number starting with 8 or 9. </p>";
			parMobileError = true;
		}

		if ($('#create_student_fullname').val().trim() == "") {
			errorMsg += "<p> Student Fullname cannot be empty </p>";
			fullnameError = true;
		}


		// if ($('#create_student_email').val().trim() == "" && $('#create_student_mobile').val().trim() == "") {
		// 	errorMsg += "<p> Student Email and Student Mobile cannot be empty </p>";
		// 	emailError = true;
		// 	mobileError = true;
		// }

		if ($('#create_parent_email').val().trim() == "") {
			errorMsg += "<p> Parent Email cannot be empty </p>";
			parEmailError = true;
		}

		if ($('#create_parent_email').val().trim() == "" && $('#create_parent_mobile').val().trim() == "") {
			errorMsg += "<p> Parent Email and Parent Mobile cannot be empty </p>";
			parEmailError = true;
			parMobileError = true;
		}

		if ($('#create_student_password').val().length < 5) {
			errorMsg += "<p> Password length needs to have 5 or more characters </p>";
			passwordError = true;
		}

		if ($('#create_student_cpassword').val() != $('#create_student_password').val()) {
			errorMsg += "<p> Passwords do not match  </p>";
			cpasswordError = true;
		}

		$.ajax({
			url: base_url + 'profile/checkUserExist',
			method: "post",
			dataType: 'json',
			data: {
				userName: $('#create_student_username').val().trim(),
				email: $('#create_student_email').val().trim(),
			},
			success: function (data) {
				if (data.usernameExist == true) {
					errorMsg += "<p> Student Username existed. Please select another username </p>";
					usernameError = true;
				}

				if (data.emailExist == true) {
					errorMsg += "<p> Student Email existed. Please select another email address </p>";
					emailError = true;
				}

				if (usernameError) {
					$('#create_student_username_div').addClass('has-error');
				} else {
					$('#create_student_username_div').removeClass('has-error');
				}

				if (fullnameError) {
					$('#create_student_fullname_div').addClass('has-error');
				} else {
					$('#create_student_fullname_div').removeClass('has-error');
				}

				if (emailError) {
					$('#create_student_email_div').addClass('has-error');
				} else {
					$('#create_student_email_div').removeClass('has-error');
				}

				if (parEmailError) {
					$('#create_parent_email_div').addClass('has-error');
				} else {
					$('#create_parent_email_div').removeClass('has-error');
				}

				if (mobileError) {
					$('#create_student_mobile_div').addClass('has-error');
				} else {
					$('#create_student_mobile_div').removeClass('has-error');
				}

				if (parMobileError) {
					$('#create_parent_mobile_div').addClass('has-error');
				} else {
					$('#create_parent_mobile_div').removeClass('has-error');
				}

				if (passwordError) {
					$('#create_student_password_div').addClass('has-error');
				} else {
					$('#create_student_password_div').removeClass('has-error');
				}

				if (cpasswordError) {
					$('#create_student_cpassword_div').addClass('has-error');
				} else {
					$('#create_student_cpassword_div').removeClass('has-error');
				}

				if (usernameError || fullnameError || emailError || parEmailError || mobileError || passwordError || cpasswordError) {
					displayErrorMessageInForm($('#create_student_form'), $('#create_student_error_div'), errorMsg);
					return false;
				} else {
					$('#create_student_form').submit();
				}

			}
		});

		return false;
	});

	$('#create_group_button').on('click', function (e) {
		e.preventDefault();
		var errorMsg = "";
		var groupnameError = false;
		if ($('#create_group_name').val().trim() == "") {
			errorMsg += "<p> Group name cannot be empty </p>";
			groupnameError = true;
			$('#create_group_name').addClass('has-error');
			displayErrorMessageInForm($('#create_group_form'), $('#create_group_error_div'), errorMsg);
		} else {
			$('#create_group_name').removeClass('has-error');
		}

		if (!groupnameError) {
			$.ajax({
				url: base_url + 'profile/checkGroupExist',
				method: "post",
				dataType: 'json',
				data: {
					groupName: $('#create_group_name').val().trim(),
					userId: $('#created_by').val().trim(),
				},
				success: function (data) {
					if (data.groupExist == true) {
						errorMsg += "<p> Group name existed. Please select another </p>";
						groupnameError = true;
						$('#create_group_name').addClass('has-error');
					}
					if (groupnameError) {
						displayErrorMessageInForm($('#create_group_form'), $('#create_group_error_div'), errorMsg);
						return false;
					} else {
						$('#create_group_form').submit();
					}

				}
			});
		}



		return false;
	});


	$('#blast_student_button').on('click', function (e) {
		e.preventDefault();
		var errorMsg = "";
		var fullnameError = false;
		var emailRegexp = /(^$|^.*@.*\..*$)/;
		var validEmail = $('#blast_student').val().match(emailRegexp);
		var userId = ($('#blast_student').attr('class')).split('_')[2];

		if ($('#blast_student').val().trim() == "") {
			errorMsg += "<p> This field cannot be empty </p>";
			fullnameError = true;
		}

		if ($('#blast_radio_button1').is(":checked")) {
			var tag_student = $('#blast_student').val().trim();
		} else if ($('#blast_radio_button2').is(":checked")) {
			var tag_student = $('#blast_student').val().trim();
		}

		$.ajax({
			url: base_url + 'profile/checkNotTagStudent',
			method: "post",
			dataType: 'json',
			data: {
				tag_student: tag_student,
				userId: userId
			},
			success: function (data) {


				if ($('#blast_radio_button1').is(":checked")) {
					if (data.validEmail == true) {
						errorMsg += "<p> The email field is not valid. Please input valid email address.</p>";
						fullnameError = true;
					}
					else
						if (data.emailExist == true) {
							$('#blast_student_form').hide();
							$('#blast_form').after(
								'<form class="form-horizontal" action="' + base_url + 'profile/inviteStudent" method="post" accept-charset="utf-8" id="invite_student_form">' +
								'<div class="modal-body clearfix">' +
								'<div class="col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-sm-10 col-md-10 col-lg-10">' +
								'<div id="invite_student_error_div" class="alert alert-danger">' +
								'</div>' +
								'<label class="col-sm-4 col-md-4 col-lg-4" style="width:155px;"> Parent\'s Email : ' +
								'</label>' +
								'<div class="form-group asterisk col-sm-8 col-md-8 col-lg-8">' +
								'<div class="col-sm-12 col-md-12 col-lg-12">' +
								'<input type="text" name="invite_parent_email" id="invite_parent_email" placeholder="Parent\'s Email" class="form-control invite_parent_email_' + userId + '" autocomplete="off" value="">' +
								'</div>' +
								'</div>' +
								'<label class="col-sm-4 col-md-4 col-lg-4" style="padding-top:25px;width:155px;"> Student\'s Email : ' +
								'</label>' +
								'<div class="form-group col-sm-8 col-md-8 col-lg-8" id="#" style="padding-top:20px;">' +
								'<div class="col-sm-12 col-md-12 col-lg-12">' +
								'<input type="text" name="invite_student_email" id="invite_student_email" placeholder="Student\'s Email" class="form-control invite_student_email_' + userId + '" autocomplete="off" value="' + tag_student + '" disabled>' +
								'<input type="hidden" name="invite_student_email" value="' + tag_student + '">' +
								'</div>' +
								'</div>' +
								'</div>' +
								'</div>' +
								'<div class="modal-footer">' +
								'<input type="button" class="btn btn-default" id="blast_back_button" value="Back">' +
								'<input type="submit" class="btn btn-custom" id="invite_student_button" value="Send">' +
								'</div>' +
								'</form>'
							);
							errorMsg += "<p> The student account does not exist. Would you like to send an invitation email to the student at " + tag_student + " ? </p>";
							$('#invite_student_error_div').append(errorMsg);
							return false;
						}
				}

				if ($('#blast_radio_button2').is(":checked")) {
					if (data.usernameExist == true) {
						$('#blast_student_form').hide();
						$('#blast_form').after(
							'<form class="form-horizontal" action="' + base_url + 'profile/inviteStudent" method="post" accept-charset="utf-8" id="invite_student_form">' +
							'<div class="modal-body clearfix">' +
							'<div class="col-sm-offset-1 col-md-offset-1 col-lg-offset-1 col-sm-10 col-md-10 col-lg-10">' +
							'<div id="invite_student_error_div" class="alert alert-danger">' +
							'</div>' +
							'<label class="col-sm-4 col-md-4 col-lg-4" style="width:155px;"> Parent\'s Email : ' +
							'</label>' +
							'<div class="form-group asterisk col-sm-8 col-md-8 col-lg-8">' +
							'<div class="col-sm-12 col-md-12 col-lg-12">' +
							'<input type="text" name="invite_parent_email" id="invite_parent_email" placeholder="Parent\'s Email" class="form-control invite_parent_email_' + userId + '" autocomplete="off" value="">' +
							'</div>' +
							'</div>' +
							'<label class="col-sm-4 col-md-4 col-lg-4" style="padding-top:25px;width:155px;"> Student\'s Email : ' +
							'</label>' +
							'<div class="form-group col-sm-8 col-md-8 col-lg-8" id="#" style="padding-top:20px;">' +
							'<div class="col-sm-12 col-md-12 col-lg-12">' +
							'<input type="text" name="invite_student_email" id="invite_student_email" placeholder="Student\'s Email" class="form-control invite_student_email_' + userId + '" autocomplete="off" value="">' +
							'</div>' +
							'</div>' +
							'</div>' +
							'</div>' +
							'<div class="modal-footer">' +
							'<input type="button" class="btn btn-default" id="blast_back_button" value="Back">' +
							'<input type="submit" class="btn btn-custom" id="invite_student_button" value="Send">' +
							'</div>' +
							'</form>'
						);
						errorMsg += "<p> The student account does not exist. Please key in student email below. An invitation email will be sent to the student. </p>";
						$('#invite_student_error_div').append(errorMsg);
						return false;
					}
				}


				if (data.tutorExist == true) {
					errorMsg += "<p> The student account does not exist in the system. </p>";
					fullnameError = true;
				}

				if (data.parentExist == true) {
					errorMsg += "<p> The student account does not exist in the system. </p>";
					fullnameError = true;
				}

				if (data.ownStudent == true) {
					errorMsg += "<p> The student account is already tagged under your tutor account. </p>";
					fullnameError = true;
					data.studentExist = false;
				}

				if (data.studentExist == true) {
					errorMsg += "<p> The student account does not exist in the system. </p>";
					fullnameError = true;
				}

				if (fullnameError) {
					$('#blast_student_div').addClass('has-error');
				} else {
					$('#blast_student_div').removeClass('has-error');
				}

				if (fullnameError) {
					displayErrorMessageInForm($('#blast_student_form'), $('#blast_student_error_div'), errorMsg);
					return false;
				} else {
					$('#blast_student_form').submit();
				}

			}
		});

		return false;
	});

	$(document).on('click', '#blast_back_button', function () {
		$('#blast_student_form').show();
		$('#invite_student_form').hide();
	});

	function displayErrorMessageInForm(form_name, error_div, error_msg) {
		if (form_name.find('.has-error').length > 0) {
			error_div.html(error_msg);
			error_div.show('fast');
		} else {
			error_div.hide('fast');
		}
	}

	$('#create_children_modal_button').on('click', function (e) {
		$('#is_student_parent_checkbox').prop('checked', 'checked');
		$('#parent_email_div').hide();
	});

	$('#create_student_modal_button').on('click', function (e) {
		$('#is_student_parent_checkbox').prop('checked', '');
		$('#parent_email_div').show();
	});

	$('#search_student_button').on('click', function (e) {
		e.preventDefault();
		var studentUsername = $('#search_student_username').val().trim();
		$('#search_student_results').hide('fast').empty();
		$.ajax({
			url: base_url + 'profile/searchStudent',
			dataType: 'json',
			method: 'post',
			data: {
				studentUsername: studentUsername
			},
			success: function (data) {
				if (data.length == 0) {
					var appendValue = '<div class="row">';
					appendValue += '<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">';
					appendValue += '<div class="alert alert-danger">No username match the input name</div>';
					appendValue += '</div>';
					$('#search_student_results').append(appendValue);
				} else {
					$.each(data, function (index, value) {
						var appendValue = '<div class="row student-row">';
						appendValue += '<div class="col-xs-4 col-sm-2 col-md-2 col-lg-2">';
						appendValue += '<img src="' + base_url + 'img/profile/' + value.profile_pic + '" class="img-responsive center-block img-circle student-pic">';
						appendValue += '</div><div class="col-xs-8 col-sm-10 col-md-10 col-lg-10 text-center">';
						appendValue += '<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">';
						appendValue += '<h3>' + value.username + '</h3>';
						appendValue += '</div>';
						appendValue += '<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">';
						appendValue += '<a href="studId_' + value.id + '" class="btn btn-custom send_link_request_to_student"> + </a>';
						appendValue += '</div>';
						appendValue += '</div>';
						appendValue += '</div>';
						$('#search_student_results').append(appendValue);
					});
				}

				$('#search_student_results').show('fast');
			}
		});
	});

	$('.topics_score_table').hide();
	var shown_table = '';
	$('.toggle_category_table').on('click', function (e) {
		e.preventDefault();
		var target_table = $(this).attr('href');

		if (target_table == shown_table) {
			$('.topics_score_table').fadeOut('slow');
			shown_table = '';
		} else {
			$('.topics_score_table').fadeOut('slow');
			shown_table = '';
			// contains student ID inside, i.e. in tutor page
			if (/#[0-9+]/.test(target_table)) {
				regExp = /#(\d+)_.*/g;
				res = regExp.exec(target_table);
				console.log(res);
				student_row = "#" + res[1] + "_student_row";

				$(student_row + ' .topics_score_table').fadeOut('slow').queue(function () {
					$(target_table).fadeIn('fast');
					$(this).dequeue();
				});

			} else {
				$(target_table).fadeIn('slow');
				// 				$('.topics_score_table').fadeOut('fast').queue(function() {
				// 					$(target_table).fadeIn('fast');
				// 					$(this).dequeue();
				// 				});
			}
			shown_table = target_table;
		}
	});

	$.each($('.progress_circles'), function () {
		var color = $(this).data('color');
		$(this).percentcircle({
			animate: true,
			diameter: 100,
			guage: 2,
			coverBg: '#000',
			bgColor: '#efefef',
			fillColor: color,
			percentSize: '15px',
			percentWeight: 'normal'
		});
	});


	$('.modal').on('shown.bs.modal', function () {
		$(this).find("[autofocus]:first").focus();
	})

	$('.jump_to_div').on('click', function (e) {
		e.preventDefault();
		var href = $(this).attr('href');
		$('html, body').animate({
			scrollTop: $(href).offset().top
		}, 800);


	});

	$('[data-toggle="tooltip"]').tooltip();

	$('.stats_row').on('click', function () {
		$(this).toggleClass('bold');
	})

	$('.fa_minimize_div').on('click', function () {
		if ($(this).hasClass('fa-minus')) {
			$(this).removeClass('fa-minus').addClass('fa-plus');
		} else if ($(this).hasClass('fa-plus')) {
			$(this).removeClass('fa-plus').addClass('fa-minus');
		}

		$(this).parent().next().slideToggle();
	});

	$('.profile_minimize_div').on('click', function () {
		if ($(this).text() == "Expand") {
			$(this).text("Collapse");
		} else if ($(this).text() == "Collapse") {
			$(this).text("Expand");
		}
		$(this).parent().next().slideToggle();
	});


	/*
		Profile page stop
	*/

	/* 
		Quiz page start
	*/
	$('.showHistoricalAttempt').on('click', function (e) {
		e.preventDefault();
		if ($(this).text() == "Show Attempt History") {
			$(this).closest("tr").nextAll('.attemptHistory').first().show("slow");
			$(this).text("Hide Attempt History");
		} else if ($(this).text() == "Hide Attempt History") {
			$(this).closest("tr").nextAll('.attemptHistory').first().hide("slow");
			$(this).text("Show Attempt History");
		}
	});

	/* 
		Quiz page stop
	*/

    /*
        Quiz results page start
    */
	$('.quiz_results_marks_id').on('change', function () {

		//Selected value
		var inputValue = $(this).val();

		var attemptId = $(this).attr('id').split('_')[0];
		var questionNo = $(this).attr('id').split('_')[1];
		var selectedMarks = $(this).val();
		var selectElement = $(this);

		//Ajax for calling php function
		var ajax_url = base_url + 'onlinequiz/modifyMarks';
		$.ajax({
			url: ajax_url,
			method: "post",
			dataType: 'json',
			data: {
				"attemptId": attemptId,
				"selectedMarks": selectedMarks,
				"questionNo": questionNo,
			},
			success: function (data) {
				$('#total_marks_id').text(data);
				if (selectElement[0].selectedIndex == selectElement[0].length - 1) {
					selectElement.parent().removeClass("wrongAnswer").addClass("correctAnswer");
				} else {
					selectElement.parent().removeClass("correctAnswer").addClass("wrongAnswer");
				}
				toastr.success("Mark successfully updated");
			},
			error: function (jqXHR, exception) {
				console.log(jqXHR);
				console.log(exception);
			}
		});

	});
	/*
		 Quiz results page stop
	 */

	/* Admin page start */
	$('#admin_insert_fraction').on('click', function (e) {
		e.preventDefault();
		alert($('#question_text').val());
	});

	$('.resolved_btn').on('click', function (e) {
		e.preventDefault();

		var $this = $(this);
		$this.button('loading');
		var ajax_url = base_url + 'administrator/mark_question_issue_resolved';

		$.ajax({
			url: ajax_url,
			method: "post",
			dataType: 'json',
			data: {
				"issue_id": $(this).attr('id'),
			},
			success: function (data) {
				if (data['success'] == true) {
					$this.parent().parent().addClass('success');
				}
				$this.button('reset');
			}

		});
	});

	$('.read_btn').on('click', function (e) {
		e.preventDefault();

		var $this = $(this);
		$this.button('loading');
		var feedback_id = $(this).attr('id').split('_')[1];
		var ajax_url = base_url + 'administrator/mark_feedback_read';

		$.ajax({
			url: ajax_url,
			method: "post",
			dataType: 'json',
			data: {
				"feedback_id": feedback_id,
			},
			success: function (data) {
				if (data['success'] == true) {
					$this.parent().parent().addClass('success');
				}
				$this.button('reset');
			}

		});
	});

	//create new question

	$('#open_ended_input_answers_div').hide();
	$(document).on('change', '.question_type_id', function (e) {
		var ids = $(this).attr('id');
		console.log($(this).val());
		if ($(this).val() == 1 || $(this).val() == 4) {
			$('#open_ended_input_answers_div' + ids).hide();
			$('#mcq_input_answers_div' + ids).show();

			if ($(this).val() == 1) {
				$('#answerType').show();
			} else {
				$('#answerType').hide();
			}
		} else {
			$('#open_ended_input_answers_div' + ids).show();
			$('#mcq_input_answers_div' + ids).hide();
		}
	});

	// initialize
	if ($('.question_type_id').val() == 2) {
		$('#open_ended_input_answers_div').show();
		$('#mcq_input_answers_div').hide();
	} else {
		$('#open_ended_input_answers_div').hide();
		$('#mcq_input_answers_div').show();

		if ($('.question_type_id').val() == 1) {
			$('#answerType').show();
		} else {
			$('#answerType').hide();
		}
	}

	// question editor
	$('#open_ended_input_answers_divs').hide();
	$(document).on('change', '.question_type_ids', function (e) {
		var ids = $(this).attr('id');
		if ($(this).val() == 1 || $(this).val() == 4 || $(this).val() == 5) {
			$('#open_ended_input_answers_divs').hide();
			$('#mcq_input_answers_divs').show();

			if ($(this).val() == 1) {
				$('#answerTypes').show();
				$('#answer_type_id [value="6"]').prop("disabled", true);
				$('#answer_type_id [value="7"]').prop("disabled", true);
				$('#answer_type_id [value="8"]').prop("disabled", true);
			} else {
				$('#answerTypes').hide();
			}

		} else {

			if ($(this).val() == 2) {
				$('#answerTypes').show();
				$('#answer_type_id [value="6"]').prop("disabled", false);
				$('#answer_type_id [value="7"]').prop("disabled", false);
				$('#answer_type_id [value="8"]').prop("disabled", false);
			} else {
				$('#answerTypes').hide();
			}

			$('#open_ended_input_answers_divs').show();
			$('#mcq_input_answers_divs').hide();

		}
	});

	// initialize
	if ($('.question_type_ids').val() == 2 || $('.question_type_ids').val() == 6) {

		if ($('.question_type_ids').val() == 2) {
			$('#answerTypes').show();
			$('#answer_type_id [value="6"]').prop("disabled", false);
			$('#answer_type_id [value="7"]').prop("disabled", false);
			$('#answer_type_id [value="8"]').prop("disabled", false);
		} else {
			$('#answerTypes').hide();
		}

		$('#open_ended_input_answers_divs').show();
		$('#mcq_input_answers_divs').hide();
	} else {
		$('#open_ended_input_answers_divs').hide();
		$('#mcq_input_answers_divs').show();

		if ($('.question_type_ids').val() == 1) {
			$('#answerTypes').show();
			$('#answer_type_id [value="6"]').prop("disabled", true);
			$('#answer_type_id [value="7"]').prop("disabled", true);
			$('#answer_type_id [value="8"]').prop("disabled", true);
		} else {
			$('#answerTypes').hide();
		}
	}

	$(document).on('keypress', '.bootstrap-tagsinput input', function (e) {
		if (e.keyCode == 13) {
			e.keyCode = 188;
			e.preventDefault();
		};
	});

	$('#addSubQuestion').on('click', function (e) {
		e.preventDefault();
		var totalQuestion = parseInt($('#subQuestionNumber').val());
		// var newSubQuestion = document.createElement('div');
		var newSubQuestion = ``;
		// $(newSubQuestion).attr('id', `subQuestion${totalQuestion}`);
		newSubQuestion += '<hr><h3 class="text-center">Sub Question - ' + (totalQuestion + 1) + '</h3>';
		newSubQuestion += `
			<div class="form-group">
                <label for="question_text_${totalQuestion}" class="col-sm-4 col-md-4 col-lg-4 control-label">Question Content</label>
				<div class="col-sm-6 col-md-6 col-lg-6">
					<div class="panel panel-default">
						<div class="panel-body">
						<div class="row question_content${totalQuestion}"></div>
						
						<div class="dropdown" id="addQuestionContenButton${totalQuestion}">
							<button class="btn btn-icon-o radius100 text-muted btn-outline-default dropdown-toggle" type="button" data-toggle="dropdown">
							<span class="fa fa-plus"></span></button>
								<ul class="dropdown-menu">
									<li class="dropdown-header">Please choose text or image</li>
									<li>
										<a style="cursor: pointer;" onClick="addSubQuestionContent('text', ${totalQuestion})"><i class="fa fa-text-height mr-2"></i> Text</a>
									</li>
									<li>
										<a style="cursor: pointer;" onClick="addSubQuestionContent('image', ${totalQuestion})"><i class="fa fa-picture-o mr-2"></i> Image</a>
									</li>
								</ul>
						</div>

					</div>					
                </div>
            </div>`
			;

		newSubQuestion += `
        	<div class="form-group">
                <label for="difficulty_${totalQuestion}" class="col-sm-4 col-md-4 col-lg-4 control-label">Difficulty</label>
                <div class="col-sm-6 col-md-6 col-lg-6">
                    <select name="difficulty_${totalQuestion}" class="form-control">
                        <option value='1'>1 - Easy</option>
                        <option value='2'>2 - Normal</option>
                        <option value='3'>3 - Hard</option>
                        <option value='4'>4 - Genius</option>
                        <option value='5'>5 - Genius</option>
                    </select>
                </div>
            </div>`;

		newSubQuestion += `
        	<div class="form-group">
                <label for="casa_${totalQuestion}" class="col-sm-4 col-md-4 col-lg-4 control-label">Type/Tags/Label (Max 5)</label>
                <div class="col-sm-6 col-md-6 col-lg-6">
                    <input name="tagsinput_${totalQuestion}" class="tagsinput" data-role="tagsinput" placeholder="eg: CA1, Mock Exam 2" value="" style="display: none;">
                </div>
            </div>
        `;

		newSubQuestion += `
        	<div class="form-group">
	            <label for="question_type_id_${totalQuestion}" class="col-sm-4 col-md-4 col-lg-4 control-label">Question Type</label>
	            <div class="col-sm-6 col-md-6 col-lg-6">
	                <select name="question_type_id_${totalQuestion}" class="form-control question_type_id" id="_${totalQuestion}">
	                   <option value="1">MCQ (4 options)</option>
	                   <option value="2">Open ended</option>
	                </select>
	            </div>
			</div>


			<div class="form-group">
				<label for="answer_type_id_${totalQuestion}" class="col-sm-4 col-md-4 col-lg-4 control-label">AnswerType</label>
				<div class="col-sm-6 col-md-6 col-lg-6">
					<select name="answer_type_id_${totalQuestion}" id="answer_type_id_${totalQuestion}" class="form-control">
						<option value="3">Numbers</option>
						<option value="3">Numbers with units</option>
						<option value="4">Words</option>
						<option value="5">Numbers with words</option>
						<option value="6">Drawing on graphics</option>
					</select>
				</div>
			</div>
			

			<div id="mcq_input_answers_div_${totalQuestion}">

				<div class="form-group">
					<label for="answer_type_mcq" class="col-sm-4 col-md-4 col-lg-4 control-label">Answer Type</label>
					<div class="col-sm-6 col-md-6 col-lg-6">
						<div class="col-sm-3 col-md-3 col-lg-2">
                            <label class="radio-inline"><input type="radio" class="answer_type_mcq" data-id="${totalQuestion}" name="answer_type_mcq_${totalQuestion}" value="text" checked>Text</label>
                       	</div>
                    	<div class="col-sm-3 col-md-3 col-lg-2">
                       		<label class="radio-inline"><input type="radio" class="answer_type_mcq" data-id="${totalQuestion}" name="answer_type_mcq_${totalQuestion}" value="image">Image</label>
                        </div>								
					</div>
				</div>

                <div class="form-group">
                    <label for="question_answers_${totalQuestion}" class="col-sm-4 col-md-4 col-lg-4 control-label">Answer 1</label>
                    <div class="col-sm-5 col-md-5 col-lg-5 input_answer_text">
						<span id="mcq_ans_${totalQuestion}_1" style="width: 100%; padding: 0.5em" class="math_text"></span>
						<input type="hidden" name="mcq_answers_${totalQuestion}[]">
					</div>
					<div class="col-sm-5 col-md-5 col-lg-5 input_answer_image" style="display: none;">									
						<input type="file" class="form-control" id="mcq_answers_image_${totalQuestion}_1" name="mcq_answers_image_${totalQuestion}[]" style="padding-top: 5px;" multiple="multiple"/>
					</div>
                    <div class="col-sm-3 col-md-3 col-lg-3">
                        <label>
                            <input type="radio" name="mcq_correct_answer_${totalQuestion}" value="1" checked="checked">
                            Select for correct ans
                        </label>
                    </div>
                </div>

                 <div class="form-group">
                    <label for="question_answers_${totalQuestion}" class="col-sm-4 col-md-4 col-lg-4 control-label">Answer 2</label>
                    <div class="col-sm-5 col-md-5 col-lg-5 input_answer_text">
						<span id="mcq_ans_${totalQuestion}_2" style="width: 100%; padding: 0.5em" class="math_text"></span>
						<input type="hidden" name="mcq_answers_${totalQuestion}[]">
					</div>
					<div class="col-sm-5 col-md-5 col-lg-5 input_answer_image" style="display: none;">									
						<input type="file" class="form-control" id="mcq_answers_image_${totalQuestion}_2" name="mcq_answers_image_${totalQuestion}[]" style="padding-top: 5px;" multiple="multiple"/>
					</div>
                    <div class="col-sm-3 col-md-3 col-lg-3">
                        <label>
                            <input type="radio" name="mcq_correct_answer_${totalQuestion}" value="2">
                            Select for correct ans
                        </label>
                    </div>
                </div>

                 <div class="form-group">
                    <label for="question_answers_${totalQuestion}" class="col-sm-4 col-md-4 col-lg-4 control-label">Answer 3</label>
                    <div class="col-sm-5 col-md-5 col-lg-5 input_answer_text">
						<span id="mcq_ans_${totalQuestion}_3" style="width: 100%; padding: 0.5em" class="math_text"></span>
						<input type="hidden" name="mcq_answers_${totalQuestion}[]">
					</div>
					<div class="col-sm-5 col-md-5 col-lg-5 input_answer_image" style="display: none;">									
						<input type="file" class="form-control" id="mcq_answers_image_${totalQuestion}_3" name="mcq_answers_image_${totalQuestion}[]" style="padding-top: 5px;" multiple="multiple"/>
					</div>
                    <div class="col-sm-3 col-md-3 col-lg-3">
                        <label>
                            <input type="radio" name="mcq_correct_answer_${totalQuestion}" value="3">
                            Select for correct ans
                        </label>
                    </div>
                </div>

                <div class="form-group">
                    <label for="question_answers_${totalQuestion}" class="col-sm-4 col-md-4 col-lg-4 control-label">Answer 4</label>
                    <div class="col-sm-5 col-md-5 col-lg-5 input_answer_text">
						<span id="mcq_ans_${totalQuestion}_4" style="width: 100%; padding: 0.5em" class="math_text"></span>
						<input type="hidden" name="mcq_answers_${totalQuestion}[]">
					</div>
					<div class="col-sm-5 col-md-5 col-lg-5 input_answer_image" style="display: none;">									
						<input type="file" class="form-control" id="mcq_answers_image_${totalQuestion}_4" name="mcq_answers_image_${totalQuestion}[]" style="padding-top: 5px;" multiple="multiple"/>
					</div>
                    <div class="col-sm-3 col-md-3 col-lg-3">
                        <label>
                            <input type="radio" name="mcq_correct_answer_${totalQuestion}" value="4">
                            Select for correct ans
                        </label>
                    </div>
                </div>

			</div>
			
			<div id="open_ended_input_answers_div_${totalQuestion}">	

				<div class="form-group">
					<label for="answer_type_nmcq" class="col-sm-4 col-md-4 col-lg-4 control-label">Answer Type</label>
					<div class="col-sm-6 col-md-6 col-lg-6">
						<div class="col-sm-3 col-md-3 col-lg-2">
							<label class="radio-inline"><input type="radio" class="answer_type_nmcq" data-id="${totalQuestion}" name="answer_type_nmcq_${totalQuestion}" value="text" checked>Text</label>
						</div>
						<div class="col-sm-3 col-md-3 col-lg-2">
							<label class="radio-inline"><input type="radio" class="answer_type_nmcq" data-id="${totalQuestion}" name="answer_type_nmcq_${totalQuestion}" value="image">Image</label>
						</div>								
					</div>
				</div>		

                <div class="form-group">
                    <label for="question_answers_${totalQuestion}" class="col-sm-4 col-md-4 col-lg-4 control-label">Answer</label>
                    <div class="col-sm-5 col-md-5 col-lg-5 input_answer_text">
						<span id="open_ended_answer_${totalQuestion}" style="width: 100%; padding: 0.5em" class="math_text"></span>
						<input type="hidden" name="open_ended_answer_${totalQuestion}" class="form-control math_text">
					</div>
					<div class="col-sm-5 col-md-5 col-lg-5 input_answer_image" style="display: none;">									
						<input type="file" class="form-control" id="nmcq_answers_image_${totalQuestion}" name="nmcq_answers_image_${totalQuestion}[]" style="padding-top: 5px;"/>
					</div>
                </div>
            </div>            
			`;
			
		newSubQuestion += `
			<div class="form-group">
                <label for="working_text_${totalQuestion}" class="col-sm-4 col-md-4 col-lg-4 control-label">Workings</label>
				<div class="col-sm-6 col-md-6 col-lg-6">
					<div class="panel panel-default">
						<div class="panel-body">
						<div class="row working_content${totalQuestion}"></div>
						
						<div class="dropdown" id="addWorkingContenButton${totalQuestion}">
							<button class="btn btn-icon-o radius100 text-muted btn-outline-default dropdown-toggle" type="button" data-toggle="dropdown">
							<span class="fa fa-plus"></span></button>
								<ul class="dropdown-menu">
									<li class="dropdown-header">Please choose text or image</li>
									<li>
										<a style="cursor: pointer;" onClick="addSubWorkingContent('text', ${totalQuestion})"><i class="fa fa-text-height mr-2"></i> Text</a>
									</li>
									<li>
										<a style="cursor: pointer;" onClick="addSubWorkingContent('image', ${totalQuestion})"><i class="fa fa-picture-o mr-2"></i> Image</a>
									</li>
								</ul>
						</div>

					</div>					
                </div>
            </div>`
		;

		$('.panel_subquestion').append('<div id="subQuestion' + totalQuestion + '" >' + newSubQuestion + '</div>');
		$("input[data-role=tagsinput]").tagsinput();
		$('.math_text').each(function (index) {
			let span_id = $(this).attr('id');
			var mathFieldSpan = document.getElementById(span_id);
			var MQ = MathQuill.getInterface(2);
			var mathField = MQ.MathField(mathFieldSpan, {
				handlers: {
					edit: function () {
						mathField.focus();
					}
				}
			});
		});
		$(`#open_ended_input_answers_div_${totalQuestion}`).hide();
		totalQuestion++;
		$('#removeSubQuestion').show();
		$('#subQuestionNumber').val(totalQuestion);
	});

	$('#removeSubQuestion').hide();
	$('#removeSubQuestion').on('click', function (e) {
		e.preventDefault();
		let totalSubQuestion = parseInt($('#subQuestionNumber').val());
		$(`#subQuestion${totalSubQuestion - 1}`).remove();
		$('#subQuestionNumber').val(totalSubQuestion - 1);
		if (totalSubQuestion - 1 == 0) {
			$('#removeSubQuestion').hide();
		}
	});

	$('#updateQuestionForm').on('submit', function () {

		$('.input_question_content').each(function () {
			var type = $(this).data('type');
			iqc.push(type);
		})

		$('#input_question_content').val(iqc);


		// $('.input_working_content').each(function () {
		// 	var type = $(this).data('type');
		// 	iwc.push(type);
		// })

		// $('#input_working_content').val(iwc);

		$('.math_text').each(function () {
			let mathSpanId = $(this).attr('id');
			let spanTarget = document.getElementById(mathSpanId);
			let mathSpan = MQ(spanTarget);
			let latex = mathSpan.latex();
			latex = latex.replace('\\left', '');  // quick fix to prevent bracket becoming left and right
			latex = latex.replace('\\right', '');
			$(this).next().val(latex);
		});

		return true;
	});

	$('#addNewQuestionForm').on('submit', function () {

		let totalSubQuestion = parseInt($('#subQuestionNumber').val());

		$('.input_question_content').each(function () {
			var type = $(this).data('type');
			iqc.push(type);
		})

		$('#input_question_content').val(iqc);


		if (totalSubQuestion > 0) {

			for (i = 0; i < totalSubQuestion; i++) {

				iqc = [];
				$('.input_question_content' + i).each(function () {
					var type = $(this).data('type');
					iqc.push(type);
				})
				$('#input_question_content' + i).val(iqc);

			}

		}
		

		$('.input_working_content').each(function () {
			var type = $(this).data('type');
			iwc.push(type);
		})

		$('#input_working_content').val(iwc);


		if (totalSubQuestion > 0) {

			for (i = 0; i < totalSubQuestion; i++) {

				iwc = [];
				$('.input_working_content' + i).each(function () {
					var type = $(this).data('type');
					iwc.push(type);
				})
				$('#input_working_content' + i).val(iwc);

			}

		}

		console.log(iwc);

		$('.math_text').each(function () {
			let mathSpanId = $(this).attr('id');
			let spanTarget = document.getElementById(mathSpanId);
			let mathSpan = MQ(spanTarget);
			let latex = mathSpan.latex();
			latex = latex.replace('\\left', '');  // quick fix to prevent bracket becoming left and right
			latex = latex.replace('\\right', '');
			$(this).next().val(latex);
		});

		var e = document.getElementById("school_id");
		var strUser = e.options[e.selectedIndex].text;
		if (strUser == "Not applicable") {
			toastr.error('Please select school name');
			return false;
		}

		var e = document.getElementById("topic_id");
		var strUser = e.options[e.selectedIndex].text;
		if (strUser == "-") {
			toastr.error('Please select topic 1');
			return false;
		}

		// if (!$('#question_text_hidden').val()) {
		// 	toastr.error('Please enter question text');
		// 	return false;
		// }

		return true;
	});

	$('.approve_user').on('click', function () {
		$('#approve_user_error').hide();
		$('#approve_user_success').hide();
		$('#approve_user_id').val($(this).attr('id').split('_')[1]);
	});

	$('#approve_user_btn').on('click', function (e) {
		e.preventDefault();

		var ajax_url = base_url + 'administrator/add_temp_user';
		var user_key = $('#approve_user_id').val();
		var $this = $(this);
		$this.button('loading');

		$.ajax({
			url: ajax_url,
			method: "post",
			dataType: 'json',
			data: {
				"user_key": user_key,
				"user_pass": $('#approve_user_pass').val()
			},
			success: function (data) {
				if (data['success'] == true) {
					$('#approve_user_success').html(data['message']).show('fast').delay(3000).hide('slow');
				} else {
					$('#approve_user_error').html(data['message']).show('fast').delay(5000).hide('slow');
				}
				$this.button('reset');
			}
		});
	});

	$('#level').hide();
	$('#school').hide();
	$('#new_word').hide();

	$("input[type='radio']").on('click', function (e) {
		var account_btn = $("input[id='register_type_btn']:checked").val();
		var add_user = $('.add_user');
		var add_div = $('.form-group.email');

		if (account_btn == 'student') {
			$(".required-field.email").remove();
			$(".required-field.mobile").remove();

			$('#new_word').show();
			$('#level').show();
			$('#school').show();

			//add_div.after("<p id='new_word' style='text-align:center'>OR</p>");
		} else if (account_btn == 'tutor') {
			$('#new_word').hide();
			$('#level').hide();
			$('#school').hide();
			$('.label-email').after("<span class='required-field email'></span>");
			$('.label-mobile').after("<span class='required-field mobile'></span>");
		}
	});

	// $('#levels').hide();
	// $('#schools').hide();
	// $('#new_words').hide();

	// var account_btn = $("input[id='role']:selected").val();
	var account_btn = $('#role_option').find('option:selected').val();

	if (account_btn == 'student') {

		$('#new_words').show();
		$('#levels').show();
		$('#schools').show();
		$('#register_parent_email_div').show();
		$('#register_parent_mobile_div').show();

	} else if (account_btn == 'tutor') {
		$('#new_words').hide();
		$('#levels').hide();
		$('#schools').hide();
		$('#register_parent_email_div').hide();
		$('#register_parent_mobile_div').hide();
	}

	$("#role_option").on('change', function (e) {
		// var account_btn = $("input[id='role']:checked").val();
		var account_btn = $('#role_option').find('option:selected').val();

		if (account_btn == 'student') {

			$('#new_words').show();
			$('#levels').show();
			$('#schools').show();
			$('#register_parent_email_div').show();
			$('#register_parent_email_div').parent().attr('id', "asterisks");
			$('#register_parent_mobile_div').show();
			$('#register_parent_mobile_div').parent().attr('id', "asterisks");
			$('#register_email_div').parent().attr('id', "asterisk");
			$('#register_mobile_div').parent().attr('id', "asterisk");
			$('#register_username').attr("placeholder", "Student Username");
			$('#register_fullName').attr("placeholder", "Student Full Name");
			$('#register_email').attr("placeholder", "Student Email");
			$('#register_mobile').attr("placeholder", "Student Mobile No");

		} else if (account_btn == 'tutor') {
			$('#new_words').hide();
			$('#levels').hide();
			$('#schools').hide();
			$('#register_parent_email_div').hide();
			$('#register_parent_mobile_div').hide();
			$('#register_email_div').parent().attr('id', "asterisks");
			$('#register_mobile_div').parent().attr('id', "asterisks");
			$('#register_username').attr("placeholder", "Username");
			$('#register_fullName').attr("placeholder", "Full Name");
			$('#register_email').attr("placeholder", "Email");
			$('#register_mobile').attr("placeholder", "Mobile No");
		}
	});

	/* Admin page stop */

	/* Askjen start */
	// $('.question-row').on('click', function() {
	// 	window.location.replace($(this).find('.askjen_question_url').attr('href'))
	// });

	$('.askjen_category_url').on('click', function (e) {
		e.stopPropagation();
	})

	$('.fade_out_div').delay(3000).fadeOut('slow');

	/* Askjen stop */

	// $('.smartgen-pre-group').bind('cut copy paste', function (e) {
	// 	e.preventDefault();
	// });
});