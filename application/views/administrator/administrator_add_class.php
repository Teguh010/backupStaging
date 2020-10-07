<div class="section">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-10 col-lg-8 col-md-push-1 col-lg-push-2">
                <?php
                        $this->load->helper('form');
                        if(isset($class)) {
                            echo form_open("administrator/edit_class/".$class_id."/".$page."/".$view);
                            $submit_text = "Edit Class";
                        }
                        else{
                            echo form_open("administrator/add_class");
                            $submit_text = "Add Class";
                        }
                        ?>
                <h2><?php echo $submit_text?></h2><hr>
                <?php if(isset($error['class_name'])) { ?>
                    <div class="alert alert-danger">
                        <?php echo $error['class_name'];?>
                    </div>
                <?php } ?>
                <?php if(isset($error['subject'])) { ?>
                    <div class="alert alert-danger">
                        <?php echo $error['subject'];?>
                    </div>
                <?php } ?>
                <?php if(isset($error['level'])) { ?>
                    <div class="alert alert-danger">
                        <?php echo $error['level'];?>
                    </div>
                <?php } ?>
                <div class="row">
                    <div class="col-sm-9 col-md-9 col-lg-9 add_user">
                    
                        
                        <div class="form-group <?php echo (isset($error['class_name'])) ? 'has-error':''?>" id="class_name">
                            <label for="profile_username">Class Name:  </label><span class="required-field"></span>
                            <input type="text" name="class_name" id="class_name" placeholder="Class Name" class="form-control" value="<?php echo isset($post['class_name']) ? $post['class_name'] : '' ?>">
                        </div>

                        <div class="form-group <?php echo form_error('subject')?'has-error':''?>" id="subject">
                            <label for="gen_subjectlevel">Level & Subject :  </label>
                                <select id="gen_subjectlevel" name="gen_subjectlevel" placeholder="Please type or select Level - Subject"></select>
                        </div>
                       
                        <div>
                            <a href="<?php echo base_url();?>administrator/class_list<?php echo "/".$view."/".$page?>" class="btn btn-danger">Cancel</a>
                            <input type="submit" class="btn btn-custom" value="<?php echo $submit_text?>" name="submit_new" style="width:100px;">
                            <input type="hidden" id="subject_id" name="subject_id" value="<?php echo isset($post['subject']) ? $post['subject'] : '' ?>" />
                            <input type="hidden" id="level" name="level" value="<?php echo isset($post['level']) ? $post['level'] : '' ?>" />
                            <input type="hidden" id="id_value" name="id_value" value="<?php echo isset($post['id_value']) ? $post['id_value'] : '' ?>" />
                        </div>
                    </div>
                </div>
                <?php
                    echo form_close();
                ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$(document).ready(function () {
    createSelectize($('#gen_subjectlevel'), 'id', 'subject_level', 'subject_level', 'Please type or select Level - Subject');
    getSubjectLevelList();
});
function getSubjectLevelList() {
    var level_val = $('#id_value').val();
    var gen_level = $('#gen_subjectlevel')[0].selectize;
    $.ajax({
        url: base_url + 'smartgen/getSubjectLevelList',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            subject_id_list = [];
            for (x = 0; x < data.length; x++) {
                subject = data[x].subject_name.replace(/primary|secondary/gi, '');
                subject_status = false;
                if (data[x].subject_id != 2 && data[x].subject_id != 3) {
                    subject_status = true;
                }
                obj = {
                    id: data[x].id,
                    level_id: data[x].level_id,
                    subject_id: data[x].subject_id,
                    level_name: data[x].level_name,
                    subject_level: data[x].level_name + subject,
                    disable: subject_status
                }
                subject_id_list.push(obj);
            }
            gen_level.clear();
            gen_level.clearOptions();
            gen_level.addOption(subject_id_list);
            gen_level.setValue(level_val, false);
            console.log(subject_id_list);
        }
    });
}
$(document).on('change', '#gen_subjectlevel', function (e) {
    e.preventDefault();
    var gen_level = $('#gen_subjectlevel')[0].selectize;
    var data_value = gen_level.getValue();
    var subject_id = subject_id_list.find(x => x.id === $(this).val()).subject_id;
    var level_id = subject_id_list.find(x => x.id === $(this).val()).level_id;
    var level_name = subject_id_list.find(x => x.id === $(this).val()).level_name;
    $('#subject_id').val(subject_id);
    $('#level').val(level_id);
    $('#id_value').val(data_value);
    console.log('subject_id:' + subject_id + ', level: ' + level_name);
    //return false;
});
function createSelectize(selector, valueField, labelField, searchField, placeholder) {
    selector.selectize({
        valueField: valueField,
        labelField: labelField,
        searchField: searchField,
        placeholder: placeholder,
        options: [],
        disabledField: 'disable',
        create: false
    });
}
</script>