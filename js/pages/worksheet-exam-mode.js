/*
    Created by Anom, 2020
    Smartgen Start - Worksheet Design
*/
var add_session_number = 0;
var number_of_question = 10;

var subject_id_list = [];
var substrand_id_list = [];
var topic_id_list = [];
var heuristic_scid_list = [];
var heuristic_id_list = [];
var strategy_scid_list = [];
var strategy_id_list = [];

function getSubjectLevelList() {
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
                // if (data[x].subject_id != 2 && data[x].subject_id != 3) {
                //     subject_status = true;
                // }
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
            console.log(subject_id_list);
        }
    });
}


function getHeuristicsList(subject_id) {
    $.ajax({
        url: base_url + 'smartgen/getHeuristicsList',
        method: 'GET',
        data: {
            subject_id: subject_id
        },
        dataType: 'json',
        success: function (data) {
            heuristic_scid_list = [];
            obj = {
                'heuristic_id': 'all',
                'heuristic_name': 'Any Heuristic',
                disable: false
            };
            heuristic_scid_list.push(obj);
            for (i = 0; i < data.length; i++) {
                obj = {
                    'heuristic_id': data[i].heuristic_id,
                    'heuristic_name': data[i].heuristic_name,
                    disable: true
                };
                heuristic_scid_list.push(obj);
            }
        }
    });
}


function getStrategyList(subject_id) {
    $.ajax({
        url: base_url + 'smartgen/getStrategyList',
        method: 'GET',
        data: {
            subject_id: subject_id
        },
        dataType: 'json',
        success: function (data) {
            strategy_scid_list = [];
            obj = {
                'strategy_id': 'all',
                'strategy_name': 'Any Strategy',
                disable: false
            };
            strategy_scid_list.push(obj);
            for (i = 0; i < data.length; i++) {
                obj = {
                    'strategy_id': data[i].id,
                    'strategy_name': data[i].name,
                    disable: true
                };
                strategy_scid_list.push(obj);
            }
        }
    });
}


function getWsSubstrandList(selector, subject_id, level_name) {
    var substrand_select = selector[0].selectize;
    substrand_select.settings.placeholder = 'Please select Strands';
    substrand_select.updatePlaceholder();
    $.ajax({
        url: base_url + 'smartgen/worksheetGetSubstr',
        method: 'GET',
        data: {
            subject_id: subject_id,
            level_name: level_name
        },
        dataType: 'json',
        success: function (data) {
            check = false;
            substrand_id_list = [];
            obj = {
                'substrand_id': 'all',
                'substrand_name': 'Any Strand'
            };
            substrand_id_list.push(obj);
            for (i = 0; i < data.length; i++) {
                if (data[i].level == 1) {
                    obj = {
                        'substrand_id': data[i].substrand_id,
                        'substrand_name': data[i].substrand_name,
                        disable: false
                    };
                } else if (data[i].level == 0) {
                    obj = {
                        'substrand_id': data[i].substrand_id,
                        'substrand_name': data[i].substrand_name,
                        disable: true
                    };
                } else {
                    obj = {
                        'substrand_id': data[i].substrand_id,
                        'substrand_name': data[i].substrand_name,
                        disable: false
                    };
                }
                substrand_id_list.push(obj);
                check = true;
            }

            substrand_select.clear();
            substrand_select.clearOptions();
            substrand_select.addOption(substrand_id_list);
            substrand_select.setValue(substrand_id_list[0].substrand_id);
            // substrand_select.updateOption('Option 1', { name: 'Option 1', disable: true });
            // substrand_select.refreshOptions();
        }
    });
}


function getWsTopicList(selector, substrand_id, level_name) {
    var topic_select = selector[0].selectize;
    topic_select.settings.placeholder = 'Please select Topic';
    topic_select.updatePlaceholder();
    $.ajax({
        url: base_url + 'smartgen/worksheetGetTopic',
        method: 'GET',
        data: {
            substrand_id: substrand_id,
            level_name: level_name
        },
        dataType: 'json',
        success: function (data) {
            check = false;
            topic_id_list = [];
            obj = {
                'topic_id': 'all',
                'topic_name': 'Any Topic'
            };
            topic_id_list.push(obj);
            for (i = 0; i < data.length; i++) {
                if (data[i].level == 1) {
                    obj = {
                        'topic_id': data[i].topic_id,
                        'topic_name': data[i].topic_name,
                        disable: false
                    };
                } else if (data[i].level == 0) {
                    obj = {
                        'topic_id': data[i].topic_id,
                        'topic_name': data[i].topic_name,
                        disable: true
                    };
                } else {
                    obj = {
                        'topic_id': data[i].topic_id,
                        'topic_name': data[i].topic_name,
                        disable: false
                    };
                }
                topic_id_list.push(obj);
                check = true;
            }

            topic_select.clear();
            topic_select.clearOptions();
            topic_select.addOption(topic_id_list);
            topic_select.enable();
            topic_select.setValue(topic_id_list[0].topic_id);
        }
    });
}


function getWsHeuristicList(selector, subject_id, substrand_id, topic_id, level_name) {
    var heuristic_select = selector[0].selectize;
    heuristic_select.settings.placeholder = 'Any Heuristic';
    heuristic_select.updatePlaceholder();
    $.ajax({
        url: base_url + 'smartgen/worksheetGetHeuristic',
        method: 'GET',
        data: {
            subject_id: subject_id,
            substrand_id: substrand_id,
            topic_id: topic_id,
            level_name: level_name
        },
        dataType: 'json',
        success: function (data) {
            check = false;
            heuristic_id_list = [];
            if (subject_id !== '') {
                for (i = 0; i < heuristic_scid_list.length; i++) {
                    obj = {
                        'heuristic_id': heuristic_scid_list[i].heuristic_id,
                        'heuristic_name': heuristic_scid_list[i].heuristic_name,
                        disable: false
                    }
                    heuristic_id_list.push(obj);
                }
            } else {
                for (i = 0; i < heuristic_scid_list.length; i++) {
                    obj = {
                        'heuristic_id': heuristic_scid_list[i].heuristic_id,
                        'heuristic_name': heuristic_scid_list[i].heuristic_name,
                        disable: heuristic_scid_list[i].disable
                    }
                    heuristic_id_list.push(obj);
                }

                for (j = 0; j < data.length; j++) {
                    for (i = 0; i < heuristic_id_list.length; i++) {
                        if (data[j].heuristic_id == heuristic_scid_list[i].heuristic_id && data[j].level == 1) {
                            heuristic_id_list[i].disable = false;
                            check = true;
                            break;
                        }
                    }
                }
            }

            console.log(check);

            heuristic_select.clear();
            heuristic_select.clearOptions();
            heuristic_select.addOption(heuristic_id_list);
            heuristic_select.setValue(heuristic_id_list[0].heuristic_id);

            // if (session_wg_heuristic !== "") $('#gen_heuristic')[0].selectize.setValue([session_wg_heuristic]);
        }
    });
}


function getWsStrategyList(selector, subject_id, substrand_id, topic_id, heuristic_id, level_name) {
    var strategy_select = selector[0].selectize;
    strategy_select.settings.placeholder = 'Any Strategy';
    strategy_select.updatePlaceholder();
    $.ajax({
        url: base_url + 'smartgen/worksheetGetStrategy',
        method: 'GET',
        data: {
            subject_id: subject_id,
            substrand_id: substrand_id,
            topic_id: topic_id,
            heuristic_id: heuristic_id,
            level_name: level_name
        },
        dataType: 'json',
        success: function (data) {
            check = false;
            strategy_id_list = [];
            if (subject_id !== '') {
                for (i = 0; i < strategy_scid_list.length; i++) {
                    obj = {
                        'strategy_id': strategy_scid_list[i].strategy_id,
                        'strategy_name': strategy_scid_list[i].strategy_name,
                        disable: false
                    }
                    strategy_id_list.push(obj);
                }
            } else {
                for (i = 0; i < strategy_scid_list.length; i++) {
                    obj = {
                        'strategy_id': strategy_scid_list[i].strategy_id,
                        'strategy_name': strategy_scid_list[i].strategy_name,
                        disable: strategy_scid_list[i].disable
                    }
                    strategy_id_list.push(obj);
                }

                for (j = 0; j < data.length; j++) {
                    for (i = 0; i < strategy_scid_list.length; i++) {
                        if (data[j].strategy_id == strategy_scid_list[i].strategy_id && data[j].level == 1) {
                            strategy_id_list[i].disable = false;
                            check = true;
                            break;
                        }
                    }
                }

            }
            console.log(check);
            strategy_select.clear();
            strategy_select.clearOptions();
            strategy_select.addOption(strategy_id_list);
            strategy_select.setValue(strategy_id_list[0].strategy_id);
        }
    });
}


function clearHeuristicStrategy(selector_heuristic, selector_strategy, clearOptions) {
    selector_heuristic[0].selectize.clear();
    selector_strategy[0].selectize.clear();
    selector_heuristic[0].selectize.settings.placeholder = 'No Heuristic';
    selector_heuristic[0].selectize.updatePlaceholder();

    selector_strategy[0].selectize.settings.placeholder = 'No Strategy';
    selector_strategy[0].selectize.updatePlaceholder();
    if (clearOptions == true) {
        selector_heuristic[0].selectize.clearOptions();
        selector_strategy[0].selectize.clearOptions();
    }
}


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

function difficulty() {
    $('#gen_difficulties_form').on('input change', function(e){
		if (parseInt($(this).val()) < 30 ) {
			$('#gen_difficulties_output').text("Beginner");
		} else if (parseInt($(this).val()) >= 30 && parseInt($(this).val()) < 60) {
			$('#gen_difficulties_output').text("Intermediate");
		} else {
			$('#gen_difficulties_output').text("Advance");
        }
        $("#gen_difficulties_output").css({"left": $(this).val() + "%"});
	});
}


$(document).ready(function () {

    createSelectize($('#gen_subjectlevel'), 'id', 'subject_level', 'subject_level', 'Please type or select Level - Subject');
    createSelectize($('.gen_substrand'), 'substrand_id', 'substrand_name', 'substrand_name', 'Please select Strand');
    createSelectize($('.gen_topic'), 'topic_id', 'topic_name', 'topic_name', 'Please select Topic');
    createSelectize($('.gen_heuristic'), 'heuristic_id', 'heuristic_name', 'heuristic_name', 'Please select Heuristic');
    createSelectize($('.gen_strategy'), 'strategy_id', 'strategy_name', 'strategy_name', 'Please select Strategy');

    getSubjectLevelList();

    difficulty();

    // $(document).on('change', '#gen_num_of_question', function () {
    //     number_of_question = $('#gen_num_of_question').val();
    // });

    $(document).on('change', '#gen_subjectlevel', function (e) {
        e.preventDefault();
        var subject_id = subject_id_list.find(x => x.id === $(this).val()).subject_id;
        var level_id = subject_id_list.find(x => x.id === $(this).val()).level_id;
        var level_name = subject_id_list.find(x => x.id === $(this).val()).level_name;
        console.log('subject_id:' + subject_id + ', level: ' + level_name);
        $('#gen_subject').val(subject_id);
        $('#gen_level').val(level_id);
        getWsSubstrandList($('.gen_substrand'), subject_id, level_name);
        if (subject_id == 2) {
            $('.learning_objective').removeClass('hidden');
            $('.question_types').removeClass('hidden');
            $('.difficulty_level').removeClass('hidden');
            getStrategyList(subject_id);
            getHeuristicsList(subject_id);
            setTimeout(function () {
                getWsHeuristicList($('.gen_heuristic'), subject_id, '', '', level_name);
                getWsStrategyList($('.gen_strategy'), subject_id, '', '', '', level_name);
            }, 300);
        } else if(subject_id == 1) {
            $('.learning_objective').addClass('hidden');
            $('.question_types').addClass('hidden');
            $('.difficulty_level').addClass('hidden');
        } else {
            $('.learning_objective').removeClass('hidden');
            $('.question_types').removeClass('hidden');
            $('.difficulty_level').removeClass('hidden');
            clearHeuristicStrategy($('.gen_heuristic'), $('.gen_strategy'), true);
            if(subject_id == 3 && $('.gen_que_type').val() == 1) {
                $('#gen_difficulties_form').prop("disabled", true);
            } else {
                $('#gen_difficulties_form').prop("disabled", false);
            } 
        }

        return false;
    });


    $(document).on('change', '.gen_substrand', function (e) {
        e.preventDefault();
        var substrand_id = $(this).val();
        var subject_id = subject_id_list.find(x => x.id === $('#gen_subjectlevel').val()).subject_id;
        var level_name = subject_id_list.find(x => x.id === $('#gen_subjectlevel').val()).level_name;
        if (subject_id == 2) {
            getWsTopicList($(this).parent().closest('.session-group').find('.gen_topic'), substrand_id, level_name);
            if (substrand_id != 'all' && substrand_id != '') {
                getWsHeuristicList($(this).parent().closest('.session-group').find('.gen_heuristic'), '', substrand_id, '', level_name);
                getWsStrategyList($(this).parent().closest('.session-group').find('.gen_strategy'), '', substrand_id, '', '', level_name);
            } else {
                getWsHeuristicList($(this).parent().closest('.session-group').find('.gen_heuristic'), subject_id, '', '', level_name);
                getWsStrategyList($(this).parent().closest('.session-group').find('.gen_strategy'), subject_id, '', '', '', level_name);
            }
        } else {
            getWsTopicList($(this).parent().closest('.session-group').find('.gen_topic'), substrand_id, level_name);
        }

        return false;
    });

    $(document).on('change', '.gen_heuristic', function (e) {
        e.preventDefault();
        var heuristic = $(this).val();
        var subject = $('#gen_subjectlevel').val();
        if(subject >= 1 && subject < 7) {
            if(heuristic != 'all') {
                $('#gen_difficulties_form').prop("disabled", true);
            } else {
                $('#gen_difficulties_form').prop("disabled", false);
            }
        } else if(subject > 12 && subject <= 17) {
            if($('.gen_que_type').val() == 1) {
                $('#gen_difficulties_form').prop("disabled", false);
            } else {
                $('#gen_difficulties_form').prop("disabled", true);
            }
        } else {
            $('#gen_difficulties_form').prop("disabled", false);
        }
    });


    $(document).on('change', '.gen_topic', function (e) {
        e.preventDefault();
        var substrand_id = $(this).parent().closest('.session-group').find('.gen_substrand').val();
        var topic_id = $(this).val();

        console.log('substrand_id : ' + substrand_id + ',  topic_id : ' + topic_id);
        var level_name = subject_id_list.find(x => x.id === $('#gen_subjectlevel').val()).level_name;
        var subject_id = subject_id_list.find(x => x.id === $('#gen_subjectlevel').val()).subject_id;
        // console.log(topic_id);        
        if (subject_id == 2 && substrand_id != 'all' && topic_id != 'all') {
            getWsHeuristicList($(this).parent().closest('.session-group').find('.gen_heuristic'), '', '', topic_id, level_name);
        } else if (subject_id == 2 && substrand_id != 'all' && topic_id == 'all') {
            getWsHeuristicList($(this).parent().closest('.session-group').find('.gen_heuristic'), '', substrand_id, '', level_name);
        }

        return false;
    });


    $(document).on('change', '.gen_heuristic', function (e) {
        e.preventDefault();
        var substrand_id = $(this).parent().closest('.session-group').find('.gen_substrand').val();
        var topic_id = $(this).parent().closest('.session-group').find('.gen_topic').val();
        var heuristic_id = $(this).val();
        var level_name = subject_id_list.find(x => x.id === $('#gen_subjectlevel').val()).level_name;
        var subject_id = subject_id_list.find(x => x.id === $('#gen_subjectlevel').val()).subject_id;
        // console.log(heuristic_id);

        if (subject_id == 2 && substrand_id == 'all' && topic_id == 'all' && heuristic_id == 'all') {
            getWsStrategyList($(this).parent().closest('.session-group').find('.gen_strategy'), subject_id, '', '', '', level_name);
        } else if (subject_id == 2 && substrand_id != 'all' && topic_id == 'all' && heuristic_id == 'all') {
            getWsStrategyList($(this).parent().closest('.session-group').find('.gen_strategy'), '', substrand_id, '', '', level_name);
        } else if (subject_id == 2 && substrand_id != 'all' && topic_id != 'all' && heuristic_id == 'all') {
            getWsStrategyList($(this).parent().closest('.session-group').find('.gen_strategy'), '', substrand_id, topic_id, '', level_name);
        } else if (subject_id == 2 && substrand_id != 'all' && topic_id != 'all' && heuristic_id != 'all') {
            getWsStrategyList($(this).parent().closest('.session-group').find('.gen_strategy'), '', '', topic_id, heuristic_id, level_name);
        } else if (subject_id == 2 && substrand_id == 'all' && topic_id == 'all' && heuristic_id != 'all') {
            getWsStrategyList($(this).parent().closest('.session-group').find('.gen_strategy'), '', '', '', heuristic_id, level_name);
        } else if (subject_id == 2 && substrand_id != 'all' && topic_id == 'all' && heuristic_id != 'all') {
            getWsStrategyList($(this).parent().closest('.session-group').find('.gen_strategy'), '', substrand_id, '', heuristic_id, level_name);
        }

        return false;
    });


    $('.worksheet_form').submit(function () {
        return false;
    });


    $('#gen_button').click(function () {
        if ($('#gen_subjectlevel').val() == '' || $('#gen_subjectlevel').val() == 'all') {
            swal('Warning!', 'Please, select level & subject!', 'warning');
        } else {
            var gen_que_type = [];
            var gen_difficulties = [];
            $('.gen_que_type:radio:checked').each(function () {
                gen_que_type.push($(this).val());
            });
            $('.gen_difficulties:radio:checked').each(function () {
                gen_difficulties.push($(this).val());
            });
            $('#gen_que_type').val(gen_que_type);
            $('#gen_difficulties').val(gen_difficulties);
            $('.worksheet_form')[0].submit();
        }
    });

    $('.gen_que_type').click(function () {
        
        if ($('#gen_subjectlevel').val() == '' || $('#gen_subjectlevel').val() == 'all') {
            $('#gen_difficulties_form').prop("disabled", false);
        } else if($('#gen_subjectlevel').val() >= 14 && $('#gen_subjectlevel').val() < 18 ) {
            if($('.mcq_type').is(':checked')) {
                $('#gen_difficulties_form').prop("disabled", true);
            } else {
                $('#gen_difficulties_form').prop("disabled", false);
            }
        } else {
            $('#gen_difficulties_form').prop("disabled", false);
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

        // var prevNumber = parseInt(currentNumber) - 1;
        // var prevEndNumber = $('#gen_end_of_question_' + prevNumber);
        // prevEndNumber.val(parseInt(currentStartNumber) - 1);
        // $("#gen_end_of_question_" + prevNumber).trigger("change");
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
        // var currentStartNumber = $('#gen_start_of_question_' + currentNumber).val();
        // // alert("#gen_start_of_question_" + currentNumber + ' - ' + currentEndNumber +'==' + currentStartNumber );
        // if(parseInt(currentStartNumber) > parseInt(currentEndNumber)) {

        //     $("#gen_start_of_question_" + currentNumber).val(currentEndNumber);
        //     $("#gen_start_of_question_" + currentNumber).trigger("change");
        // } else {

        // }
        // var nextNumber = parseInt(currentNumber) + 1;
        // var nextStartNumber = $('#gen_start_of_question_' + nextNumber);
        // nextStartNumber.val(parseInt(currentEndNumber) + 1);
        // $("#gen_start_of_question_" + nextNumber).trigger("change");
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
                // alert("val_start_x:"+val_start_x+"<="+"val_end_x:"+val_end_x);
                // alert('NEXT break');
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
            // if(parseInt(val_start_x) > parseInt(val_end_prev_x)) {
            //     alert("val_start_x:"+val_start_x+">"+"val_end_prev_x:"+val_end_prev_x);
            //     $("#gen_end_of_question_" + prev_x).val(val_start_x-1);
            //     if(prev_x>0)
            //         $("#gen_start_of_question_" + prev_x).val(val_start_x-1);
            //     break;
            // } else {
            //     alert("prev_x:"+prev_x+" val_start_x:"+val_start_x+"<="+"val_end_prev_x:"+val_end_prev_x);
            //     $("#gen_end_of_question_" + prev_x).val(val_start_x-1);
            //     var val_start_prev = $("#gen_start_of_question_" + prev_x).val();
            //     if(prev_x>0) {
            //         if(parseInt(val_start_x-1)>=parseInt(val_start_prev))
            //             $("#gen_start_of_question_" + prev_x).val(val_start_x-1);
            //         else
            //             $("#gen_start_of_question_" + prev_x).val(val_start_x-1);
            //     }
            //     x--; prev_x--;
            // }
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

    // design exam add session
    $(document).on('click', '.add_session', function (e) {
        e.preventDefault();
        var subject_id = subject_id_list.find(x => x.id === $('#gen_subjectlevel').val()).subject_id;
        
        if ($('#gen_subjectlevel').val() == '') {
            swal('Warning!', 'Please, select level & subject!', 'warning');
            return;
        }
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

        function change_difficulty() {
            $('#gen_difficulties_form_' + add_session_number).on('input change', function(e){
                if (parseInt($(this).val()) < 30 ) {
                    $('#gen_difficulties_output_' + add_session_number).text("Beginner");
                } else if (parseInt($(this).val()) >= 30 && parseInt($(this).val()) < 60) {
                    $('#gen_difficulties_output_' + add_session_number).text("Intermediate");
                } else {
                    $('#gen_difficulties_output_' + add_session_number).text("Advance");
                }
                $("#gen_difficulties_output_" + add_session_number).css({"left": $(this).val() + "%"});
            });
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

        if(subject_id == 1) {
            var disabled = 'hidden';
        } else {
            var disabled = '';
        }

        var html = `<li class="list-group-item add_session_group" id="add_session_group_` + add_session_number + `">
                        <div class="session-group">
                            <div class="form-group">
                                <label for="" class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4"> Topics :</label>
                                <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                                    <select name="gen_substrand[]" class="form-control gen_substrand gen_substrand`+ add_session_number + `"></select>
                                </div>
                                <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
                                    <select name="gen_topic[]" class="form-control gen_topic gen_topic`+ add_session_number + `"></select>
                                </div>
                            </div>
                            <div class="form-group ` + disabled + `">
                                <label for="" class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4">Learning Objective :</label>
                                <div class="col-xs-6 col-sm-3 col-md-3 col-lg-3">
                                    <select name="gen_heuristic[]" class="form-control gen_heuristic gen_heuristic`+ add_session_number + `"></select>
                                </div>
                                <div class="col-xs-6 col-sm-4 col-md-4 col-lg-4">
                                    <select name="gen_strategy[]" class="form-control gen_strategy gen_strategy`+ add_session_number + `"></select>
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
                        <div class="form-group ` + disabled + `">
                            <label for="gen_que_type` + add_session_number + `" class="control-label col-xs-12 col-sm-4 col-md-4 col-lg-4">Question Type :</label>
                            <div class="col-sm-7 col-md-7 col-lg-7" style="padding-top: 5px; text-align: left;">
                                <div class="col-sm-3 col-md-3 col-lg-3">
                                    <label class="radio-inline"><input type="radio" name="gen_que_type_` + add_session_number + `[]" class="mcq_type gen_que_type" value="1" checked>MCQ</label>
                                </div>
                                <div class="col-sm-3 col-md-3 col-lg-3">
                                    <label class="radio-inline"><input type="radio" name="gen_que_type_` + add_session_number + `[]" class="non_mcq_type gen_que_type" value="2">Non-MCQ</label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group ` + disabled + `">
                            <label for="gen_difficulties` + add_session_number + `" class="control-label col-sm-4 col-md-4 col-lg-4">Difficulties% :</label>
                            
                            <div class="col-sm-7 col-md-7 col-lg-7">
                                <input type="range" min="0" max="100" step="1" data-thumbwidth="20"
                                        value="50"
                                        name="gen_difficulties_` + add_session_number + `" id="gen_difficulties_form_` + add_session_number + `">
                                <output for="gen_difficulties_` + add_session_number + `" id="gen_difficulties_output_` + add_session_number + `">Intermediate</output>
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
        var subject_id = subject_id_list.find(x => x.id === $('#gen_subjectlevel').val()).subject_id;
        var level_name = subject_id_list.find(x => x.id === $('#gen_subjectlevel').val()).level_name;
        createSelectize($('.gen_substrand' + add_session_number), 'substrand_id', 'substrand_name', 'substrand_name', 'Please select Strand');
        createSelectize($('.gen_topic' + add_session_number), 'topic_id', 'topic_name', 'topic_name', 'Please select Topic');
        createSelectize($('.gen_heuristic' + add_session_number), 'heuristic_id', 'heuristic_name', 'heuristic_name', 'Please select Heuristic');
        createSelectize($('.gen_strategy' + add_session_number), 'strategy_id', 'strategy_name', 'strategy_name', 'Please select Strategy');
        getWsSubstrandList($('.gen_substrand' + add_session_number), subject_id, level_name);
        change_difficulty();
        // return false;
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

})