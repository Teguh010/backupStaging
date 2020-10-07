<script src="<?= base_url('js/pages/administrator/tutor-list.js?'.date('YmdHis')) ?>"></script>


<div class="container panel_navigation pt-10 pb-20">	

	<div class="row">
		<div class="col-lg-12">
			<div class="form-inline form-control-static">
				<div class="has-feedback has-search-2" style="display: inline;">
					<span class="fa fa-search form-control-feedback pl-4 pr-3 top8"></span>
					<input type="text" id="searchKeyword" class="form-control search_input_control"
						placeholder="Search">
				</div>
				<a href="<?= base_url('administrator/create-new-question') ?>" class="btn btn-xl btn-teal"
					style="width: 205px;">New Question</a>
			</div>
		</div>

		<div class="col-lg-12">
			<div class="form-inine">
				<div class="form-group">
					<div class="customUi-checkbox checkboxUi-teal mr-4">
						<input id="filter" type="checkbox" name="filter">
						<label for="filter">
							<span class="label-checkbox"></span>
							<span class="label-helper fs14 font300">Filter :</span>
						</label>
					</div>
					<div class="label_filter" style="display: inline;">
			
					</div>
				</div>
			</div>
		</div>		


	</div>

	<div class="row panel_filter" style="display: none;">
		<!-- <div class="col-lg-12 pb-20">
			<input name="tagssearch" id="tagssearch" class="tagsinput" data-role="tagsinput" placeholder="Tags:">
		</div> -->


		<!-- <div class="col-lg-3 mb-6 col-md-6 mb-20">
			<div class="list border1 rounded shadow-sm overflow-hidden cursor_pointer btnSubject"
				data-name="Maths" onclick="getLevel('Maths')">
				<div class="list-item">
					<div class="list-thumb avatar avatar60 shadow-sm">
						<img src="<?= base_url('img/icon-subject-math-2.png') ?>" class="img-fluid">
					</div>
					<div class="list-body text-right">
						<span class="list-title fs-1x font400">Maths</span>

					</div>
				</div>
			</div>
		</div>
		
		<div class="col-lg-3 mb-6 col-md-6 mb-20">
			<div class="list border1 rounded shadow-sm overflow-hidden cursor_pointer btnSubject"
				data-name="English" onclick="getLevel('English')">
				<div class="list-item">
					<div class="list-thumb avatar avatar60 shadow-sm">
						<img src="<?= base_url('img/icon-subject-english.png') ?>" class="img-fluid">
					</div>
					<div class="list-body text-right">
						<span class="list-title fs-1x font400">English</span>
					</div>
				</div>
			</div>
		</div>
		
		<div class="col-lg-3 mb-6 col-md-6 mb-20">
			<div class="list border1 rounded shadow-sm overflow-hidden cursor_pointer btnSubject"
				data-name="Science" onclick="getLevel('Science')">
				<div class="list-item">
					<div class="list-thumb avatar avatar60 shadow-sm">
						<img src="<?= base_url('img/icon-subject-science.png') ?>" class="img-fluid">
					</div>
					<div class="list-body text-right">
						<span class="list-title fs-1x font400">Science</span>

					</div>
				</div>
			</div>
		</div> -->
		


		<!-- <div class="mt-20 content_level">
			<div class="col-lg-12 primary_level">

			</div>
			<div class="col-lg-12 secondary_level">

			</div>
		</div> -->

		<div class="col-lg-12 pt-20">
			<div class="row">
				<div class="col-lg-6 pb-10">
					<select name="subject_id" id="subject_id" placeholder="-- Subject and Level --"></select>					
				</div>
				<div class="col-lg-6 pb-10">
					<select name="topic_id" id="topic_id" placeholder="-- Topic --"></select>
				</div>			
			</div>
		</div>


		<div class="col-lg-12">
			<div class="row">
				<div class="col-lg-6 pb-10">
					<select name="strategy_id" id="strategy_id" placeholder="-- Strategy --"></select>
				</div>
				<div class="col-lg-6 pb-10">
					<select name="substrategy_id" id="substrategy_id" placeholder="-- Sub Strategy --"></select>
				</div>
			</div>
		</div>



		<div class="col-lg-12">
			<div class="row">
				<div class="col-lg-6">
					<label for="difficulty" class="fs14 font300 mr-2 text-muted">Marks :</label>

					<div class="customUi-checkbox checkboxUi-warning mr-4">
						<input id="marks_1_0" class="marks" type="checkbox" name="marks[]" value="1">
						<label for="marks_1_0">
							<span class="label-checkbox"></span>
							<span class="label-helper fs14 font300">1</span>
						</label>
					</div>
					
					<div class="customUi-checkbox checkboxUi-success mr-4">
						<input id="marks_2_0" class="marks" type="checkbox" name="marks[]" value="2">
						<label for="marks_2_0">
							<span class="label-checkbox"></span>
							<span class="label-helper fs14 font300">2</span>
						</label>
					</div>
					
					<div class="customUi-checkbox checkboxUi-danger mr-4">
						<input id="marks_3_0" class="marks" type="checkbox" name="marks[]" value="3">
						<label for="marks_3_0">
							<span class="label-checkbox"></span>
							<span class="label-helper fs14 font300">3</span>
						</label>
					</div>
					
					<div class="customUi-checkbox checkboxUi-pink mr-4">
						<input id="marks_4_0" class="marks" type="checkbox" name="marks[]" value="4">
						<label for="marks_4_0">
							<span class="label-checkbox"></span>
							<span class="label-helper fs14 font300">4</span>
						</label>
					</div>

					<div class="customUi-checkbox checkboxUi-dark">
						<input id="marks_5_0" class="marks" type="checkbox" name="marks[]" value="5">
						<label for="marks_5_0">
							<span class="label-checkbox"></span>
							<span class="label-helper fs14 font300">5</span>
						</label>
					</div>
									
				</div>
				<div class="col-lg-6 text-right">
					<button id="btnConfirm" class="btn btn-icon btn-lg btn-default" style="width: 205px;"><i class="fa fa-search"></i>Confirm</button>
				</div>		
			</div>
		</div>		

		<input type="hidden" id="user_grid" value="<?php echo $page; ?>">

		<!-- <div class="col-lg-6">
			<label for="difficulty" class="fs14 font300 mr-2 text-muted">Sub Question :</label>
			<div class="customUi-checkbox checkboxUi-primary mr-4">
                <input id="subquestion" type="checkbox" name="subquestion" value="1">
                <label for="subquestion">
                	<span class="label-checkbox"></span>
                    <span class="label-helper fs14 font300">Yes</span>
                </label>
			</div>				
		</div> -->

		<!-- <div class="col-lg-12 pt-20">
			<div class="row"> -->
				<!-- <div class="col-lg-6">
					<div class="row">
						<div class="col-lg-6">
							<button class="btn btn-icon btn-block btn-xl btn-outline-teal mb-2 btnQuesType" data-id="1">
								<i class="picons-thin-icon-thin-0209_radiobuttons_bullets_lines"></i>
								MCQ - Single Choice
							</button>
						</div>
						<div class="col-lg-6">
							<button class="btn btn-icon btn-block btn-xl btn-outline-teal mb-2 btnQuesType" data-id="8">
								<i class="picons-thin-icon-thin-0210_checkboxes_lines_check"></i>
								MCQ - Multiple Choice
							</button>
						</div>
						<div class="col-lg-6">
							<button class="btn btn-icon btn-block btn-xl btn-outline-teal mb-2 btnQuesType" data-id="3">
								<i class="picons-thin-icon-thin-0161_on_off_switch_toggle_settings_preferences"></i>
								True & False
							</button>
						</div>
						<div class="col-lg-6">
							<button class="btn btn-icon btn-block btn-xl btn-outline-teal mb-2 btnQuesType" data-id="2">
								<i class="picons-thin-icon-thin-0069a_menu_hambuger"></i>
								Open
							</button>
						</div>
						<div class="col-lg-6">
							<button class="btn btn-icon btn-block btn-xl btn-outline-teal mb-2 btnShowFITB">
								<i class="picons-thin-icon-thin-0101_notes_text_notebook"></i>
								Fill in The Blank
							</button>
						</div>
						<div class="col-lg-6 panel_fitb" style="display: none;">
							<div class="customUi-checkbox checkboxUi-pink mr-4">
                                <input id="ck_with_option_0" type="radio" class="ck_fitb" name="ck_fitb_0" value="5">
                                <label for="ck_with_option_0">
                                    <span class="label-checkbox"></span>
                                    <span class="label-helper text-pink fs14 font400">With Option</span>
                                </label>
                            </div>
							<div class="customUi-checkbox checkboxUi-pink">
                                <input id="ck_without_option_0" type="radio" class="ck_fitb" name="ck_fitb_0" value="6">
                                <label for="ck_without_option_0">
                                    <span class="label-checkbox"></span>
                                    <span class="label-helper text-pink fs14 font400">Without Option</span>
                                </label>
                            </div>
						</div>
					</div>
				</div> -->

				
			<!-- </div>
		</div> -->
		
	</div>

</div>

<div class="bg-light-active panel_data">
	
</div>