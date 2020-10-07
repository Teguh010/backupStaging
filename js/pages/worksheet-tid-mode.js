var add_session_number = 0;
var number_of_question = 10;

function getTopicLevelList(level) {
    var session = 0;
    var check_element = document.getElementById("gen_topic_"+session.toString());
    while(check_element) {
        var ses_num = session.toString();
        $.ajax({
            url: base_url + 'smartgen/getTIDList',
            method: 'POST',
            data: {
                level: level
            },
            dataType: 'json',
            success: function (data) {
                // alert($('#gen_topic_'+ses_num).val());
                $('#gen_topic_'+ses_num)
                    .find('option')
                    .remove()
                    .end();
                $('#gen_topic_'+ses_num)
                        .append('<option value="all" >Any topic</option>');
                for (i = 0; i < data.length; i++) {
                    $('#gen_topic_'+ses_num)
                        .append('<option value="' + data[i].topic_id + '" >' + data[i].topic_name + '</option>');
                }
            }
        });
        session++;
        check_element = document.getElementById("gen_topic_"+session.toString());
    }
}

function getAbilityList(ability="all") {
    var session = 0;
    var check_element = document.getElementById("gen_ability_"+session.toString());
    while(check_element) {
        var ses_num = session.toString();
        $.ajax({
            url: base_url + 'smartgen/getTIDAbilityList',
            method: 'POST',
            data: {
            },
            dataType: 'json',
            success: function (data) {
                $('#gen_ability_'+ses_num)
                    .find('option')
                    .remove()
                    .end();
                $('#gen_ability_'+ses_num)
                        .append('<option value="all" >Any ability</option>');
                for (i = 0; i < data.length; i++) {
                    var selected = (ability==data[i].ability_id) ? "selected" : "";
                    $('#gen_ability_'+ses_num)
                        .append('<option value="' + data[i].ability_id + '" '+selected+'>' + data[i].ability_name + '</option>');
                }
            }
        });
        session++;
        check_element = document.getElementById("gen_ability_"+session.toString());
    }
}

$(document).ready(function () {
    getAbilityList();
    $('#gen_button').click(function () {
        if ($('#gen_level').val() == '') {
            swal('Warning!', 'Please, select level & subject!', 'warning');
            return false;
        } else {
            // var gen_que_type = [];
            // var gen_difficulties = [];
            // $('.gen_que_type:radio:checked').each(function () {
            //     gen_que_type.push($(this).val());
            // });
            // $('.gen_difficulties:radio:checked').each(function () {
            //     gen_difficulties.push($(this).val());
            // });
            // $('#gen_que_type').val(gen_que_type);
            // $('#gen_difficulties').val(gen_difficulties);
            // $('.worksheet_form')[0].submit();
        }
    });

    $('.setActiveMCQ').click(function () {
        var id = $(this).data('id');
        var status = $(this).data('status');
        if (status == 1) {
            active = 2;
        } else {
            active = 1;
        }
        $('#cek_que_type_' + id).data('status', active);
        $('#gen_que_type_' + id).val(active);
    });

    $('.setActiveAND').click(function () {
        var id = $(this).data('id');
        var status = $(this).data('status');
        if (status == 1) {
            active = 2;
        } else {
            active = 1;
        }
        $('#cek_operator_' + id).data('status', active);
        $('#gen_operator_' + id).val(active);
    });

    $('#gen_button').click(function () {
        if ($('#gen_level').val() == '') {
            swal('Warning!', 'Please, select level & subject!', 'warning');
        } else {
            var gen_que_type = [];
            var gen_difficulties = [];
            $('.gen_que_type:radio:checked').each(function () {
                gen_que_type.push($(this).val());
            });
            $('.gen_difficulties:checkbox:checked').each(function () {
                gen_difficulties.push($(this).val());
            });
            $('#gen_que_type').val(gen_que_type);
            $('#gen_difficulties').val(gen_difficulties);
            $('.worksheet_form')[0].submit();
        }
    });

    $(document).on('change', '#gen_end_of_question_0', function () {
        if (add_session_number == 0) {
            $('#gen_num_of_question').val($('#gen_end_of_question_0').val());
        }
    });

    $(document).on('change', '.gen_start_of_question', function () {
        //  var currentStartNumber = $(this).val();
        var currentNumber = $(this).data('id');
        changeStart(currentNumber);
    });

    $(document).on('change', '.gen_end_of_question', function () {
        var currentEndNumber = $(this).val();
        var currentNumber = $(this).data('id');
        var x = currentNumber;
        var next_x = x + 1;
        var num = +$("#gen_end_of_question_" + x).val() + 1;
        $("#gen_start_of_question_" + next_x).val(num);
        // changeStart(next_x);
        $("#gen_start_of_question_" + next_x).trigger("change");
    });

    // CAHYO
    function changeStart(x = 1) {
        var begin_x = x;
        var next_x = x + 1; // 2 
        while ($('#gen_start_of_question_' + next_x).length) { // start 2
            var val_start_x = $("#gen_start_of_question_" + x).val();
            var val_end_x = $("#gen_end_of_question_" + x).val();
            // alert("NEXT val_start_x:"+val_start_x+">"+"val_end_x:"+val_end_x);
            if (parseInt(val_start_x) > parseInt(val_end_x)) {
                // alert("#gen_end_of_question_" + x +" isi "+val_start_x);
                $("#gen_end_of_question_" + x).val(val_start_x);
                var plus_one = parseInt(val_start_x) + 1;
                // alert("#gen_start_of_question_" + next_x +" isi "+ plus_one);
                $("#gen_start_of_question_" + next_x).val(plus_one);
                next_x++; // 3
                x++; // 2
            } else {
                break
            }
        }
        x = begin_x;
        var prev_x = x - 1;
        while ($('#gen_start_of_question_' + prev_x).length) {
            var val_start_x = $("#gen_start_of_question_" + x).val();
            var min_one = parseInt(val_start_x) - 1;
            // alert("#gen_end_of_question_" + prev_x +" isi " + min_one);
            $("#gen_end_of_question_" + prev_x).val(min_one);
            var val_end_prev_x = $("#gen_end_of_question_" + prev_x).val();
            var val_start_prev_x = $("#gen_start_of_question_" + prev_x).val();
            // alert("PREV val_end_prev_x:"+val_end_prev_x+"<="+"val_start_prev_x:"+val_start_prev_x+" prev_x>0");
            if (parseInt(val_end_prev_x) <= parseInt(val_start_prev_x) && prev_x > 0) {
                // alert("#gen_start_of_question_" + prev_x +" isi "+val_end_prev_x);
                $("#gen_start_of_question_" + prev_x).val(val_end_prev_x);
                x--; prev_x--;
            } else {
                // alert('PREV break');
                break;
            }
        }
    }

    $(document).on('change', '#gen_num_of_question', function () {
        // var gen_end_of_question_0 = $('#gen_end_of_question_0').val();
        var lastNumBeforeChange = $('#gen_end_of_question_' + add_session_number).val();
        var currentCountQuestion = parseInt($(this).val());
        if (!$('#gen_end_of_question_1').length) {
            $('#gen_end_of_question_0')
                .find('option')
                .remove()
                .end();
            $('#gen_end_of_question_0')
                .append('<option value="' + currentCountQuestion + '">' + currentCountQuestion + '</option>');
        } else {
            var x = 1;
            var next_x = x + 1;
            while ($('#gen_start_of_question_' + x).length) {
                if (!$('#gen_start_of_question_' + next_x).length) {
                    $('#gen_start_of_question_' + x)
                        .find('option')
                        .remove()
                        .end();
                    var selected = "";
                    for (var i = next_x; i <= currentCountQuestion; i++) {
                        selected = (i == currentCountQuestion) ? "selected" : "";
                        $('#gen_start_of_question_' + x)
                            .append('<option value="' + i + '" ' + selected + '>' + i + '</option>');
                    }
                    $('#gen_end_of_question_' + x)
                        .find('option')
                        .remove()
                        .end();
                    $('#gen_end_of_question_' + x)
                        .append('<option value="' + currentCountQuestion + '">' + currentCountQuestion + '</option>');
                    break;
                }
                next_x++;
                x++;
            }
        }

        if (add_session_number > 1) {
            if (currentCountQuestion > lastNumBeforeChange) {
                $('#gen_start_of_question_' + add_session_number).val(lastNumBeforeChange);
            } else {
                $('#gen_end_of_question_0').val($(this).val() - 1);
            }
        }
    });

    $(document).on('click', '.add_session', function (e) {
        e.preventDefault();
        if ($('#gen_level').val() == '') {
            swal('Warning!', 'Please, select level & subject!', 'warning');
            return;
        }

        var subject_id = 1; // subject_id_list.find(x => x.id === $('#gen_subjectlevel').val()).subject_id;
        //    alert(add_session_number.toString());
        add_session_number += 1;
        //    alert(add_session_number.toString());

        var option = '';
        var options = '';
        var startQueVal = 1;
        var endQueVal = 5;
        var i = 1;

        function generateOptions(i, value, exp) {
            var htmloption = '';
            for (i; i <= value; i++) {
                if (i == exp) {
                    htmloption += '<option value = "' + i + '" selected="selected">' + i + '</option>';
                } else {
                    htmloption += '<option value = "' + i + '">' + i + '</option>';
                }
            }
            return htmloption;
        }

        if (add_session_number == 1) {
            startQueVal = $('#gen_start_of_question_0').val();
            endQueVal = $('#gen_end_of_question_0').val();
            deviationVal = parseInt(endQueVal) - parseInt(startQueVal) + 1;

            newStartQue = parseInt(startQueVal) + parseInt(deviationVal);
            newEndQue = parseInt(endQueVal) + parseInt(deviationVal);
            if (newEndQue > 50) {
                newEndQue = 50;
            }
            // option = generateOptions(i, 50, newStartQue); // start
            // options = generateOptions(i, 50, newEndQue); // end
            // CAHYO
            var currentCountQuestion = parseInt($('#gen_num_of_question').val());
            option = generateOptions(2, currentCountQuestion, currentCountQuestion); // start for gen_start_of_question_1
            options = generateOptions(currentCountQuestion, currentCountQuestion, currentCountQuestion); // end for gen_end_of_question_1
            currentCountQuestion--;
            $('#gen_end_of_question_0')
                .find('option')
                .remove()
                .end();
            for (var i = 1; i <= currentCountQuestion; i++) {
                selected = (i == currentCountQuestion) ? "selected" : "";
                $('#gen_end_of_question_0')
                    .append('<option value="' + i + '" ' + selected + '>' + i + '</option>');
            }

        } else if (add_session_number > 1) {
            var prevNoOfQue = add_session_number - 1;
            startQueVal = $('#gen_start_of_question_' + prevNoOfQue).val();
            endQueVal = $('#gen_end_of_question_' + prevNoOfQue).val();
            deviationVal = parseInt(endQueVal) - parseInt(startQueVal) + 1;
            var number_of_question = parseInt($('#gen_num_of_question').val());
            option = generateOptions(add_session_number + 1, number_of_question, number_of_question);
            options = generateOptions(number_of_question, number_of_question, number_of_question);
            while ($('#gen_end_of_question_' + prevNoOfQue).length) {
                var startQueVal = $('#gen_start_of_question_' + prevNoOfQue).val();
                var endQueVal = $('#gen_end_of_question_' + prevNoOfQue).val();
                if (startQueVal == endQueVal) {
                    // alert(prevNoOfQue.toString() +' loop ' + endQueVal);
                    var new_end = parseInt(endQueVal) - 1;
                    $('#gen_start_of_question_' + prevNoOfQue)
                        .find('option')
                        .remove()
                        .end();
                    for (var i = add_session_number; i <= new_end; i++) {
                        selected = (i == new_end) ? "selected" : "";
                        $('#gen_start_of_question_' + prevNoOfQue)
                            .append('<option value="' + i + '" ' + selected + '>' + i + '</option>');
                    }
                    $('#gen_end_of_question_' + prevNoOfQue)
                        .find('option')
                        .remove()
                        .end();
                    for (var i = add_session_number; i <= new_end; i++) {
                        selected = (i == new_end) ? "selected" : "";
                        $('#gen_end_of_question_' + prevNoOfQue)
                            .append('<option value="' + i + '" ' + selected + '>' + i + '</option>');
                    }
                    prevNoOfQue--;
                } else {
                    var new_end = parseInt(endQueVal) - 1;
                    $('#gen_end_of_question_' + prevNoOfQue)
                        .find('option')
                        .remove()
                        .end();
                    for (var i = startQueVal; i <= new_end; i++) {
                        selected = (i == new_end) ? "selected" : "";
                        $('#gen_end_of_question_' + prevNoOfQue)
                            .append('<option value="' + i + '" ' + selected + '>' + i + '</option>');
                    }
                    // alert('break');
                    break;
                }
            }
        }

        $(this).hide();
        if ($(this).next().attr('class') == 'remove_session text-danger-active fs14 ml-2') {
            $(this).next().hide();
        }

        var html = `<li class="list-group-item add_session_group" id="add_session_group_` + add_session_number + `">
                        <div class="session-group">
                            <div class="form-group">
                                <label for="" class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4">Topics :</label>
                                <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
                                    <select name="gen_topic[]" id="gen_topic_` + add_session_number + `" class="form-control gen_topic" placeholder="Please select Topic">
                                        <option value="all">Any topic</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">                                    
                                <label for="" class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4">Ability :</label>
                                <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
                                    <select name="gen_ability[]" id="gen_ability_` + add_session_number + `" class="form-control gen_ability" placeholder="Ability"> 
                                    <option value="all">Any ability</option>
                                    </select>                                          
                                </div>
                            </div>
                        </div>
                        <div class="form-group" style="padding-top: 10px;">
                            <label for="" class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4">Start - End of Question :</label>
                            <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                                <select name="gen_start_of_question[]" class="form-control gen_start_of_question" id="gen_start_of_question_` + add_session_number + `" data-id="` + add_session_number + `">` + option + `</select>
                            </div>
                            <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                                <select name="gen_end_of_question[]" class="form-control gen_end_of_question" id="gen_end_of_question_` + add_session_number + `" data-id="` + add_session_number + `">` + options + `</select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="gen_que_type_0" class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4">Question type :</label>
                            <div class="col-sm-7 col-md-7 col-lg-7" style="padding-top: 5px; text-align: left;">
                                    <label class="switch">
                                        <input type="checkbox" id="cek_que_type_` + add_session_number + `" data-id="` + add_session_number + `" data-status="1" class="setActiveMCQ" checked>
                                        <div class="slider round"><span class="on">MCQ</span><span class="off">Non-MCQ</span></div>
                                    </label>
                            </div>
                            <input type="hidden" name="gen_que_type[]" id="gen_que_type_` + add_session_number + `" value="1" />
                        </div>
                        <div class="form-group">
                            <label for="gen_operator_` + add_session_number + `" class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4">Ability x Difficulty :</label>
                            <div class="col-sm-7 col-md-7 col-lg-7" style="padding-top: 5px; text-align: left;">
                                <label class="switch">
                                    <input type="checkbox" id="cek_operator_` + add_session_number + `" data-id="` + add_session_number + `" data-status="1" class="setActiveAND" checked>
                                    <div class="slider round"><span class="on">AND</span><span class="off" style="right: 25px;">OR</span></div>
                                </label>
                            </div>
                            <input type="hidden" name="gen_operator[]" id="gen_operator_` + add_session_number + `" value="1" />
                        </div>`;

        html += `<div class="form-group">
                <label for="gen_difficulties` + add_session_number + `" class="control-label col-sm-4 col-md-4 col-lg-4">Difficulties% :</label>
                <div class="col-sm-7 col-md-7 col-lg-7" style="padding-top: 10px; text-align: left;">
                    <div class="col-sm-3 col-md-3 col-lg-3">
                        <label style="font-weight: initial;"><input type="checkbox" name="gen_difficulties_` + add_session_number + `[]" class="gen_difficulties" value="1">  Easy</label>
                                </div>
                        <div class="col-sm-3 col-md-3 col-lg-3">
                            <label style="font-weight: initial;"><input type="checkbox" name="gen_difficulties_` + add_session_number + `[]" class="gen_difficulties" value="2" checked> Normal</label>
                                </div>
                            <div class="col-sm-3 col-md-3 col-lg-3">
                                <label style="font-weight: initial;"><input type="checkbox" name="gen_difficulties_` + add_session_number + `[]" class="gen_difficulties" value="3"> Hard</label>
                                </div>
                                <div class="col-sm-3 col-md-3 col-lg-3">
                                    <label style="font-weight: initial;"><input type="checkbox" name="gen_difficulties_` + add_session_number + `[]" class="gen_difficulties" value="4"> Genius</label>
                                </div>
                                </div>
                            </div>
                            <div class="form-group" style="padding-top: 10px;">
                                <div class="col-sm-12 col-md-12 col-lg-12 text-left">
                                    <a href="#" class="add_session text-success-active fs14 mr-3"><i class="fa fa-plus mr-1"></i> Add</a> &nbsp;
                                <a href="#" class="remove_session text-danger-active fs14 ml-2"><i class="fa fa-times mr-1"></i> Remove</a>
                                </div>
                            </div>
                    </li>`;
        $(html).hide().appendTo('#add_session_group_0').fadeIn('fast');
        var level_name = $('#gen_level').val();
        getTopicLevelList(level_name,add_session_number);
        getAbilityList();
    });

    $(document).on('change', '#gen_num_of_question', function () {
        // var gen_end_of_question_0 = $('#gen_end_of_question_0').val();
        var lastNumBeforeChange = $('#gen_end_of_question_' + add_session_number).val();
        var currentCountQuestion = parseInt($(this).val());
        if (!$('#gen_end_of_question_1').length) {
            $('#gen_end_of_question_0')
                .find('option')
                .remove()
                .end();
            $('#gen_end_of_question_0')
                .append('<option value="' + currentCountQuestion + '">' + currentCountQuestion + '</option>');
        } else {
            var x = 1;
            var next_x = x + 1;
            while ($('#gen_start_of_question_' + x).length) {
                if (!$('#gen_start_of_question_' + next_x).length) {
                    $('#gen_start_of_question_' + x)
                        .find('option')
                        .remove()
                        .end();
                    var selected = "";
                    for (var i = next_x; i <= currentCountQuestion; i++) {
                        selected = (i == currentCountQuestion) ? "selected" : "";
                        $('#gen_start_of_question_' + x)
                            .append('<option value="' + i + '" ' + selected + '>' + i + '</option>');
                    }
                    $('#gen_end_of_question_' + x)
                        .find('option')
                        .remove()
                        .end();
                    $('#gen_end_of_question_' + x)
                        .append('<option value="' + currentCountQuestion + '">' + currentCountQuestion + '</option>');
                    break;
                }
                next_x++;
                x++;
            }
        }

        if (add_session_number > 1) {
            if (currentCountQuestion > lastNumBeforeChange) {
                $('#gen_start_of_question_' + add_session_number).val(lastNumBeforeChange);
            } else {
                $('#gen_end_of_question_0').val($(this).val() - 1);
            }
        }
    });
    
    $(document).on('click', '.remove_session', function (e) {
        e.preventDefault();
        add_session_number -= 1;
        var count_length = $('.add_session_group').length;
        var currentSelected = parseInt($('.gen_end_of_question_0').val());
        var val = parseInt(2);
        var setSelected = currentSelected + val;
        console.log(setSelected);
        $('.gen_end_of_question_0').val(setSelected);

        if (count_length > 2) {
            $(this).closest('.list-group-item').prev().find('.add_session').show();
            $(this).closest('.list-group-item').prev().find('.remove_session').show();
            $(this).closest('.list-group-item').fadeOut('fast', function () {
                $(this).remove();
            });
        } else {
            $(this).closest('.list-group-item').prev().find('.add_session').show();
            $(this).closest('.list-group-item').fadeOut('fast', function () {
                $(this).remove();
            });
        }
        var prevNoOfQue = add_session_number;
        var number_of_question = parseInt($('#gen_num_of_question').val());
        var currentStartNumber = $('#gen_start_of_question_' + prevNoOfQue).val();
        $('#gen_end_of_question_' + prevNoOfQue)
            .find('option')
            .remove()
            .end();
        for (var i = number_of_question; i <= number_of_question; i++) {
            selected = (i == number_of_question) ? "selected" : "";
            $('#gen_end_of_question_' + prevNoOfQue)
                .append('<option value="' + i + '" ' + selected + '>' + i + '</option>');
        }
        $('#gen_start_of_question_' + prevNoOfQue)
            .find('option')
            .remove()
            .end();
        for (var i = add_session_number; i <= number_of_question; i++) {
            selected = (i == currentStartNumber) ? "selected" : "";
            $('#gen_start_of_question_' + prevNoOfQue)
                .append('<option value="' + i + '" ' + selected + '>' + i + '</option>');
        }
    });
});