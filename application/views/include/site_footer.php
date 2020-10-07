<!-- previous mathjax version -->
<!-- <script type="text/javascript" src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_SVG"></script> -->

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.3/MathJax.js?config=TeX-AMS-MML_SVG"></script>

<!-- <script type="text/javascript" src="<?php echo base_url(); ?>js/MathJax.js"></script> -->

<!-- <script type="text/javascript" async
  src="https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.5/latest.js?config=TeX-MML-AM_CHTML">
</script> -->

<!-- <script type="text/javascript" src="https://many-worlds.glitch.me/mathquill/mathquill/branch/master/build/mathquill.js"></script> -->

<!-- <script type="text/javascript" src="<?php echo base_url(); ?>js/mathquill_custom.js"></script> -->

<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>js/placeholder.jquery.min.js"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.circlechart.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap.min.js"></script>

<!-- <script type="text/javascript" src="<?php echo base_url(); ?>js/datatables.js"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>js/datatables.min.js"></script> -->

<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-tagsinput.js"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>js/masonry.pkgd.js"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-datepicker.js"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>js/moment.min.js"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>js/daterangepicker.min.js"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>js/fastselect.js"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.autocomplete.js"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>js/selectize-master.js?<?= date('YmdHis') ?>"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>js/plugins/magnific-popup/dist/jquery.magnific-popup.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.16/dist/summernote.min.js"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>js/qrcode-generator/js/qrcode.js"></script>

<!-- The Google API Loader script. -->
<script type="text/javascript" src="https://apis.google.com/js/api.js"></script>

<?php 
	if(@$page == 'worksheet-quiz-mode'){
		echo '<script src="'.base_url().'js/pages/worksheet-quiz-mode.js?'.date('YmdHis').'"></script>';		
	}else if(@$page == 'worksheet-exam-mode'){
		echo '<script src="'.base_url().'js/pages/worksheet-exam-mode.js?'.date('YmdHis').'"></script>';	
	}else if(@$page == 'worksheet-tid-mode'){
		echo '<script src="'.base_url().'js/pages/worksheet-tid-mode.js?'.date('YmdHis').'"></script>';	
	}else if(@$page == 'generate-exam'){
		echo '<script src="'.base_url().'js/pages/generate-exam.js?'.date('YmdHis').'"></script>';		
	}else if(@$page == 'generate-tid'){
		echo '<script src="'.base_url().'js/pages/generate-tid.js?'.date('YmdHis').'"></script>';		
	}else if(@$page == 'student-list' || @$page == 'edit-student-profile' || @$page == 'change-student-password'){
		echo '<script src="'.base_url().'js/pages/administrator/student-list.js?'.date('YmdHis').'"></script>';				
	} else if(@$page == 'tutor-list'){
		echo '<script src="'.base_url().'js/pages/administrator/tutor-list.js?'.date('YmdHis').'"></script>';				
	}else if(@$page == 'lessons' || @$page == 'mylessons' || @$page == 'create-new-question' || @$page == 'questions'){
		
	}else{
		echo '<script type="text/javascript" src="'.base_url().'js/custom.js?'.date('YmdHis').'"></script>';
	}
?>

<script src="<?= base_url(); ?>js/pages/feedback.js?<?= date('YmdHis'); ?>"></script>

</body>

</html>