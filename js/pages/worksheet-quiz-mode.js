/*
    Created by Anom, 2020
	Smartgen Start - Worksheet Design
*/

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
                obj = {
                    id: data[x].id,
                    level_id: data[x].level_id,
                    subject_id: data[x].subject_id,
                    level_name: data[x].level_name,
                    subject_level: data[x].level_name + subject
                }
                subject_id_list.push(obj);
            }
            gen_level.clear();
            gen_level.clearOptions();
            gen_level.addOption(subject_id_list);
            if (session_wg_level !== "") {
                gen_level.setValue([session_wg_level]);
            }
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


function getWsSubstrandList(subject_id, level_name) {
    var substrand_select = $('.substrand_select')[0].selectize;
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
            console.log(check);

            substrand_select.clear();
            substrand_select.clearOptions();
            substrand_select.addOption(substrand_id_list);
            substrand_select.setValue(substrand_id_list[0].substrand_id);
            // substrand_select.updateOption('Option 1', { name: 'Option 1', disable: true });
            // substrand_select.refreshOptions();            

            if (session_wg_substrand !== "") $('.substrand_select')[0].selectize.setValue([session_wg_substrand]);
        }
    });
}


function getWsTopicList(substrand_id, level_name) {
    var topic_select = $('.topic_select')[0].selectize;
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

            if (session_wg_topic !== "") $('.topic_select')[0].selectize.setValue([session_wg_topic]);
        }
    });
}


function getWsHeuristicList(subject_id, substrand_id, topic_id, level_name) {
    var heuristic_select = $('#gen_heuristic')[0].selectize;
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


function getWsStrategyList(subject_id, substrand_id, topic_id, heuristic_id, level_name) {
    var strategy_select = $('#gen_strategy')[0].selectize;
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


function clearHeuristicStrategy(clearOptions) {
    $('#gen_heuristic')[0].selectize.clear();
    $('#gen_strategy')[0].selectize.clear();
    $('#gen_heuristic')[0].selectize.settings.placeholder = 'No Heuristic';
    $('#gen_heuristic')[0].selectize.updatePlaceholder();

    $('#gen_strategy')[0].selectize.settings.placeholder = 'No Strategy';
    $('#gen_strategy')[0].selectize.updatePlaceholder();
    if (clearOptions == true) {
        $('#gen_heuristic')[0].selectize.clearOptions();
        $('#gen_strategy')[0].selectize.clearOptions();
    }
}


function clearSession() {
    session_wg_quesbank = 'public';
    session_wg_subject = '';
    session_wg_level = '';
    session_wg_substrand = '';
    session_wg_topic = '';
    session_wg_strategy = '';
    session_wg_quetype = '1';
    session_wg_difficulty = '';
}


$(document).ready(function () {

    $('#gen_tag').attr("disabled", "disabled");

    $('#gen_subjectlevel').selectize({
        valueField: 'id',
        labelField: 'subject_level',
        searchField: 'subject_level',
        placeholder: 'Please type or select Level - Subject',
        options: [],
        disabledField: 'disable',
        dropdownParent: 'body',
        create: false,
    });

    $('.substrand_select').selectize({
        valueField: 'substrand_id',
        labelField: 'substrand_name',
        searchField: 'substrand_name',
        placeholder: 'Please select Strands',
        options: [],
        disabledField: 'disable',
        create: false
    });

    $('.topic_select').selectize({
        valueField: 'topic_id',
        labelField: 'topic_name',
        searchField: 'topic_name',
        placeholder: 'Please select Topic',
        options: [],
        disabledField: 'disable',
        create: false
    });

    $('#gen_heuristic').selectize({
        valueField: 'heuristic_id',
        labelField: 'heuristic_name',
        searchField: 'heuristic_name',
        placeholder: 'Please select Heuristic',
        options: [],
        disabledField: 'disable',
        create: false
    });

    $('#gen_strategy').selectize({
        valueField: 'strategy_id',
        labelField: 'strategy_name',
        searchField: 'strategy_name',
        placeholder: 'Please select Strategy',
        options: [],
        disabledField: 'disable',
        create: false
    });

    getSubjectLevelList();

    $('input[name="gen_que_bank"][value="' + session_wg_quesbank + '"]').prop('checked', true);
    $('input[name="gen_que_type"][value="' + session_wg_quetype + '"]').prop('checked', true);

    // if (session_wg_difficulty !== "") {
    //     $.each(session_wg_difficulty, function (index, value) {
    //         $('input[name="gen_difficulties"][value="' + value.toString() + '"]').prop("checked", true);
    //     });
    // }

    $('input:radio[name="gen_que_bank"]').click(function () {
        var className = $(this).attr('class');

        if (className == 'public_type') {
            $('#gen_tag').attr("disabled", "disabled");
        } else {
            $('#gen_tag').attr("disabled", false);
        }
    });

    $(document).on('change', '#gen_subjectlevel', function () {
        var subject_id = subject_id_list.find(x => x.id === $(this).val()).subject_id;
        var level_id = subject_id_list.find(x => x.id === $(this).val()).level_id;
        var level_name = subject_id_list.find(x => x.id === $(this).val()).level_name;
        console.log('subject_id:' + subject_id + ', level: ' + level_name);
        $('#gen_subject').val(subject_id);
        $('#gen_level').val(level_id);
        getWsSubstrandList(subject_id, level_name);
        if (subject_id == 2) {
            getStrategyList(subject_id);
            getHeuristicsList(subject_id);
            setTimeout(function () {
                getWsHeuristicList(subject_id, '', '', level_name);
                getWsStrategyList(subject_id, '', '', '', level_name);
            }, 300);
        } else {
            clearHeuristicStrategy(true);
        }


        return false;
    });


    $('#substrand_select').change(function (e) {
        e.preventDefault();
        var substrand_id = $(this).val();
        var subject_id = subject_id_list.find(x => x.id === $('#gen_subjectlevel').val()).subject_id;
        var level_name = subject_id_list.find(x => x.id === $('#gen_subjectlevel').val()).level_name;
        // console.log(substrand_id);
        if (subject_id == 2) {
            getWsTopicList(substrand_id, level_name);
            if (substrand_id != 'all' && substrand_id != '') {
                getWsHeuristicList('', substrand_id, '', level_name);
                getWsStrategyList('', substrand_id, '', '', level_name);
            } else {
                getWsHeuristicList(subject_id, '', '', level_name);
                getWsStrategyList(subject_id, '', '', '', level_name);
            }
        } else {
            getWsTopicList(substrand_id, level_name);
        }

        return false;
    });


    $('#topic_select').change(function (e) {
        e.preventDefault();
        var substrand_id = $('#substrand_select').val();
        var topic_id = $(this).val();
        var level_name = subject_id_list.find(x => x.id === $('#gen_subjectlevel').val()).level_name;
        var subject_id = subject_id_list.find(x => x.id === $('#gen_subjectlevel').val()).subject_id;
        // console.log(topic_id);        
        if (subject_id == 2 && substrand_id != 'all' && topic_id != 'all') {
            getWsHeuristicList('', '', topic_id, level_name);
        } else if (subject_id == 2 && substrand_id != 'all' && topic_id == 'all') {
            getWsHeuristicList('', substrand_id, '', level_name);
        }

        return false;
    });


    $('#gen_heuristic').change(function (e) {
        e.preventDefault();
        var substrand_id = $('#substrand_select').val();
        var topic_id = $('#topic_select').val();
        var heuristic_id = $(this).val();
        var level_name = subject_id_list.find(x => x.id === $('#gen_subjectlevel').val()).level_name;
        var subject_id = subject_id_list.find(x => x.id === $('#gen_subjectlevel').val()).subject_id;
        // console.log(heuristic_id);

        if (subject_id == 2 && substrand_id == 'all' && topic_id == 'all' && heuristic_id == 'all') {
            getWsStrategyList(subject_id, '', '', '', level_name);
        } else if (subject_id == 2 && substrand_id != 'all' && topic_id == 'all' && heuristic_id == 'all') {
            getWsStrategyList('', substrand_id, '', '', level_name);
        } else if (subject_id == 2 && substrand_id != 'all' && topic_id != 'all' && heuristic_id == 'all') {
            getWsStrategyList('', substrand_id, topic_id, '', level_name);
        } else if (subject_id == 2 && substrand_id != 'all' && topic_id != 'all' && heuristic_id != 'all') {
            getWsStrategyList('', '', topic_id, heuristic_id, level_name);
        } else if (subject_id == 2 && substrand_id == 'all' && topic_id == 'all' && heuristic_id != 'all') {
            getWsStrategyList('', '', '', heuristic_id, level_name);
        } else if (subject_id == 2 && substrand_id != 'all' && topic_id == 'all' && heuristic_id != 'all') {
            getWsStrategyList('', substrand_id, '', heuristic_id, level_name);
        }

        clearSession();
        return false;
    });

    $(document).on('change', '#gen_tag', function (e) {
        // e.preventDefault();
        var gen_tag = $(this).val();

        if (gen_tag != 'all') {
            e.stopImmediatePropagation();
            $('#gen_level').attr("disabled", "disabled");
            $('.substrand_select').attr("disabled", "disabled");
            $('.topic_select').attr("disabled", "disabled");
            $('#gen_strategy').attr("disabled", "disabled");
            $('#gen_subject').attr("disabled", "disabled");
            $('#gen_num_of_question').attr("disabled", "disabled");
            $("input[type=radio][value='1']").attr("disabled", "disabled");
            $("input[type=radio][value='2']").attr("disabled", "disabled");
            $('input:checkbox[value="1"]').attr("disabled", "disabled");
            $('input:checkbox[value="2"]').attr("disabled", "disabled");
            $('input:checkbox[value="3"]').attr("disabled", "disabled");
            $('input:checkbox[value="4"]').attr("disabled", "disabled");
            var select = $('.worksheet_form').find(".selectized");
            /*disable select initially*/
            select.each(function () {
                var thisSelect = $(this).selectize();
                thisSelectDisable = thisSelect[0].selectize;
                thisSelectDisable.disable();
            });
        } else {
            $('#gen_level').attr("disabled", false);
            $('.substrand_select').attr("disabled", false);
            $('.topic_select').attr("disabled", false);
            $('#gen_strategy').attr("disabled", false);
            $('#gen_subject').attr("disabled", false);
            $('#gen_num_of_question').attr("disabled", false);
            $("input[type=radio][value='1']").attr("disabled", false);
            $("input[type=radio][value='2']").attr("disabled", false);
            $('input:checkbox[value="1"]').attr("disabled", false);
            $('input:checkbox[value="2"]').attr("disabled", false);
            $('input:checkbox[value="3"]').attr("disabled", false);
            $('input:checkbox[value="4"]').attr("disabled", false);
        }

    });


    $('.worksheet_form').submit(function () {
        return false;
    });


    $('#gen_button').click(function () {
        if ($('#gen_tag').val() == 'all') {

            if ($('#gen_subjectlevel').val() == '' || $('#gen_subjectlevel').val() == 'all') {

                swal('Warning!', 'Please, select level & subject!', 'warning');

            } else {

                $('.worksheet_form')[0].submit();

            }

        } else {

            $('.worksheet_form').attr('action', base_url + 'smartgen/customizeAdminWorksheet/' + $('#gen_tag').val());
            $('.worksheet_form')[0].submit();

        }

    });

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


})