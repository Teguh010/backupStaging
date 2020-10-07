var subject_id_list = [];
var worksheet_id_list = [];
var subject_id = 0;

function getSubjectLevelList(selector) {
    var gen_level = selector[0].selectize;
    $.ajax({
        url: base_url + 'lesson/getSubjectLevelList',
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            subject_id_list = [];
            for (x = 0; x < data.length; x++) {
                subject = data[x].subject_name.replace(/primary|secondary/gi, '');
                subject_status = false;
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
        }
    });
}


function getWorksheetList(selector, subject_id) {
    var worksheet_list = selector[0].selectize;
    $.ajax({
        url: base_url + 'lesson/getWorksheetList/' + subject_id,
        method: 'GET',
        dataType: 'json',
        success: function (data) {
            worksheet_id_list = [];
            for (x = 0; x < data.length; x++) {
                obj = {
                    worksheet_id: data[x].worksheet_id,
                    worksheet_name: data[x].worksheet_name,
                    disable: false
                }
                worksheet_id_list.push(obj);
            }
            worksheet_list.clear();
            worksheet_list.clearOptions();
            worksheet_list.addOption(worksheet_id_list);
        }
    });
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

var section_id = 1;

function nextTab(elem) {
    $(elem).next().find('a[data-toggle="tab"]').click();
}
function prevTab(elem) {
    $(elem).prev().find('a[data-toggle="tab"]').click();
}

$(document).ready(function () {

    createSelectize($('#level_id'), 'id', 'subject_level', 'subject_level', 'Please select Level - Subject');

    getSubjectLevelList($('#level_id'));

    createSelectize($('#worksheet_id'), 'worksheet_id', 'worksheet_name', 'worksheet_name', 'Please choose your worksheet');

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

    $('.play-video').magnificPopup({
        type: 'iframe',
        midClick: true
    });

    $('textarea').summernote();
    $('.form-lesson').hide();

    $(document).on('click', '#OKBtn', function () {

        $.ajax({
            type: 'POST',
            url: base_url + 'lesson/createStudentAssignment',
            data: new FormData($('#assign_lesson_form')[0]),
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            dataType: 'json',
            beforeSend: function (e) {
                if (e && e.overrideMimeType) {
                    e.overrideMimeType("application/json;charset=UTF-8");
                }
            },
            success: function (res) {
                if (res.msg == 'success') {
                    swal({
                        text: "Lesson has been successfully created",
                        icon: "success",
                        button: 'OK'
                    })
                        .then((willOK) => {
                            if (willOK) {
                                // window.location.href = base_url + 'profile/lessons';                                    
                                $('.form-lesson').fadeOut(250);
                                $('.list-lesson').fadeIn(500);
                                $('#btnNewLesson').fadeIn(500);
                                loadLesson();
                            }
                        });
                }
            }
        });

    });


    $(document).on('click', '#updateAssignmentBtn', function () {
        $.ajax({
            type: 'POST',
            url: base_url + 'lesson/updateStudentAssignment',
            data: new FormData($('#assign_lesson_form')[0]),
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            dataType: 'json',
            beforeSend: function (e) {
                if (e && e.overrideMimeType) {
                    e.overrideMimeType("application/json;charset=UTF-8");
                }
            },
            success: function (res) {
                if (res.msg == 'success') {
                    swal({
                        text: "Assignment has been successfully updated",
                        icon: "success",
                        button: 'OK'
                    })
                        .then((willOK) => {
                            if (willOK) {
                                // window.location.href = base_url + 'profile/lessons';
                                $('.form-edit-assignment').fadeOut(250);
                                $('.list-lesson').fadeIn(500);
                                $('#btnNewLesson').fadeIn(500);
                                loadLesson();
                            }
                        });
                }
            }
        });
    })


    $(document).on('click', '#cancelAssignmentBtn', function () {
        $('.form-edit-assignment').fadeOut(250);
        $('.list-lesson').fadeIn(500);
    })

    //Initialize tooltips
    $('.nav-tabs > li a[title]').tooltip();

    //Wizard
    $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {

        var $target = $(e.target);

        if ($target.parent().hasClass('disabled')) {
            return false;
        }
    });

    $(".next-step").click(function (e) {

        var $active = $('.wizard .nav-tabs li.active');
        $active.next().removeClass('disabled');
        nextTab($active);

    });

    $(".prev-step").click(function (e) {

        var $active = $('.wizard .nav-tabs li.active');
        prevTab($active);

    });


    $('#btnNewLesson').click(function () {
        section_id = 1;
        $('.list-lesson').hide();
        $(this).fadeOut(250);
        $('.form-lesson').fadeIn(500);
    });


    $('#btnNewSection').click(function () {
        section_id++;
        var content = `
        <div class="col-lg-6 mt-3" id="card-section`+ section_id + `">
            <div class="card">
                <div class="card-header-small">
                    <input type="text" class="form-control input_style1_red section_title" style="width: 80%" id="section_title`+ section_id + `" name="section_title[]" placeholder="Section Title" >
                    <a class="fa-caret icon_expand2">
                        <i class="fa fa-caret-down"></i>
                    </a>
                    <a class="close_question icon_close">
                        <i class="fa fa-times"></i>
                    </a>
                </div>                                        
                <div class="card-body-small">
                    <div class="row">
                        <div class="col-lg-12 subsection1">
                            <div class="form-inline p-1">
                                <div class="form-group mb-2" style="width: 100%">
                                    <input type="text" class="form-control input_style1_black subsection" name="subsection`+ section_id + `[]"
                                        placeholder="Lecture" style="width: 80%" />
                                    <a style="cursor: pointer; text-decoration: none;"
                                        class="removeLecture text-danger-active fs18 ml-2"><i
                                            class="fa fa-times"></i></a>     
                                </div>                                                                                                     
                            </div>
                        </div>

                        <div class="col-lg-12 col-sm-12 m-1 navNewLecture">
                            <a style="cursor: pointer; text-decoration: none;"
                                class="newLecture text-success-active fs14" data-id="`+ section_id + `"><i
                                    class="fa fa-plus mr-1"></i> Add
                                new lecture</a>
                        </div>
                    </div>
                </div>                                        
            </div>
        </div>
        `;

        $(content).insertAfter('#card-section' + (section_id - 1));
    });


    $(document).on('click', '.newLecture', function () {
        var id = $(this).data('id')
        var content = `
        <div class="col-lg-12 subsection`+ id + `">
            <div class="form-inline p-1">
                <div class="form-group mb-2" style="width: 100%">
                    <input type="text" class="form-control input_style1_black subsection" name="subsection`+ id + `[]"
                        placeholder="Lecture" style="width: 80%" />
                    <a style="cursor: pointer; text-decoration: none;"
                        class="removeLecture text-danger-active fs18 ml-2"><i
                            class="fa fa-times"></i></a>     
                </div>                                                                                                     
            </div>
        </div>
        `;
        $(content).insertBefore($(this).parent());
    });


    $(document).on('click', '.removeLecture', function () {
        $(this).parent().parent().remove();
    });


    $(".card-grid").on('click', '.card .card-header-small .fa-caret', function (e) {
        e.preventDefault();
        var caret = $(this).find('i').attr('class');
        if (caret == 'fa fa-caret-down') {
            $(this).children().removeClass('fa fa-caret-down').addClass('fa fa-caret-up');
            $(this).parent().parent().find('.card-body-small').slideUp(500);
        } else {
            $(this).children().removeClass('fa fa-caret-up').addClass('fa fa-caret-down');
            $(this).parent().parent().find('.card-body-small').slideDown(500);
        }

    });


    $('.card-grid').on('click', '.card .icon_close', function (e) {
        e.preventDefault();
        if (section_id > 1) {
            section_id--;
            $(this).parent().parent().parent().remove();
        }
    });

    $('#level_id').change(function () {
        subject_id = subject_id_list.find(x => x.id === $(this).val()).subject_id;
        console.log(subject_id);
    })

    $('#SaveLessonBtn').click(function () {
        var level_id = $('#level_id').val();
        var lesson_title = $('#lesson_title').val();
        var tags = $('#tags').val();
        var description = $('#description').val();
        if (level_id == '') {
            swal('Information', 'Please, select level and subject!', 'warning');
        } else if (lesson_title == '') {
            swal('Information', 'Please, fill lesson title!', 'warning');
        } else if (description == '') {
            swal('Information', 'Please, fill description!', 'warning');
        } else {
            $.ajax({
                type: 'POST',
                url: base_url + 'lesson/createLesson',
                data: {
                    level_id: level_id,
                    title: lesson_title,
                    tags: tags,
                    description: description
                },
                dataType: 'json',
                success: function (res) {
                    if (res.msg == 'success') {
                        toastr.success("Lesson is successfully saved");
                        $('#SaveLessonBtn').hide();
                        $('#form-lesson').prop('disabled', true);
                        $('#next-step1').show();
                        $('#next-step1').click();
                        getWorksheetList($('#worksheet_id'), subject_id);
                    }
                }
            });
        }

    })


    $('#SaveSectionBtn').click(function () {

        var subsection;
        var section_title;

        $('.subsection').each(function () {
            subsection = $(this).val();
        });

        $(".section_title").each(function () {
            section_title = $(this).val();
        });

        if (section_title == '') {
            swal('Information', 'Please, fill the section title!', 'warning');
        } else if (subsection == '') {
            swal('Information', 'Please, fill the lecture!', 'warning');
        } else {
            $.ajax({
                type: 'POST',
                url: base_url + 'lesson/createSection',
                data: new FormData($('#form-section')[0]),
                processData: false,
                contentType: false,
                cache: false,
                async: false,
                dataType: 'json',
                beforeSend: function (e) {
                    if (e && e.overrideMimeType) {
                        e.overrideMimeType("application/json;charset=UTF-8");
                    }
                },
                success: function (res) {
                    if (res.msg == 'success') {
                        toastr.success("Section lesson is successfully saved");
                        $('#SaveSectionBtn').hide();
                        $('#form-section').prop('disabled', true);
                        $('#next-step2').show();
                        $('#next-step2').click();

                        loadSectionLesson();
                    }
                }
            });
        }



    });


})


function loadLesson() {
    $.ajax({
        type: 'GET',
        url: base_url + 'lesson/loadLesson',
        dataType: 'text',
        success: function (res) {
            $('.worksheet_div_body').html(res);
            $('.table-lesson').DataTable();
        }
    });
}


function loadSectionLesson() {
    $.ajax({
        type: 'GET',
        url: base_url + 'lesson/loadSectionLesson',
        dataType: 'json',
        success: function (res) {
            var subject_type = res.data.data_lesson.subject_type;
            var data_section = res.data.data_section;
            var data_lecture = res.data.data_lecture;
            var content = '';

            for (x = 0; x < data_section.length; x++) {
                var listLecture = '';
                for (y = 0; y < data_lecture.length; y++) {
                    if (data_lecture[y].section_id == data_section[x].section_id) {
                        if (y == 0) {
                            var rowSet = `<div class="row fs18 pl-4 pb-4 pr-4 b-b" id="list-lecture-` + data_lecture[y].id + `">`;
                        } else {
                            var rowSet = `<div class="row fs18 p-4 b-b" data-id="` + data_lecture[y].id + `">`;
                        }
                        listLecture += rowSet + `
                                <div class="col-lg-10" style="display: none">
                                    <form id='form-upload` + data_lecture[y].id + `' method='post' enctype="multipart/form-data">
                                        <input type="file" id="file_upload` + data_lecture[y].id + `" name="file_upload` + data_lecture[y].id + `" />                                            
                                    </form>
                                </div>                                                    
                                <div class="col-lg-10">
                                    <ul class="ul-section pt-2">
                                        <li class="li-section">
                                            ` + data_lecture[y].lecture_title + `
                                        </li>
                                    </ul>
                                </div>
                                <div class="col-lg-2" id="actionBtn` + data_lecture[y].id + `">
                                                                    
                                    <div class="dropdown">
                                        <button class="btn btn-icon-o radius100 btn-outline-primary btn-no-margin-top dropdown-toggle" type="button" data-toggle="dropdown">
                                        <span class="fa fa-upload"></span></button>
                                        <ul class="dropdown-menu">
                                            <li class="dropdown-header">Upload a video from...</li>
                                            <li>
                                                <a style="cursor: pointer;" class="embedVideo" onClick="entryEmbedVideo(`+ data_lecture[y].id + `)" title="Embed Video"><i class="fa fa-link mr-2"></i> Embed Link</a>
                                            </li>
                                            <li>
                                                <a style="cursor: pointer;" class="uploadVideo" onClick="startUploadFile(`+ data_lecture[y].id + `, 'video')" title="Upload Video"><i class="fa fa-desktop mr-2"></i> Your Computer</a>
                                            </li>
                                            <li>
                                                <a style="cursor: pointer;" onClick="showPickerDialog(`+ data_lecture[y].id + `, 'video', false)"><i class="fa fa-google text-muted mr-2"></i> Google Drive</a>
                                            </li>
                                            <li class="divider"></li>
                                            <li class="dropdown-header">Upload a document from...</li>
                                            <li>
                                                <a style="cursor: pointer;" onClick="startUploadFile(`+ data_lecture[y].id + `, 'doc')" title="Upload Document"><i class="fa fa-desktop mr-2"></i> Your Computer</a>
                                            </li>
                                            <li>
                                                <a style="cursor: pointer;" onClick="showPickerDialog(`+ data_lecture[y].id + `, 'doc', false)"><i class="fa fa-google text-muted mr-2"></i> Google Drive</a>
                                            </li>
                                            <li class="divider"></li>
                                            <li class="dropdown-header">Add assessment...</li>
                                            <li>
                                                <a style="cursor: pointer;" onClick="addAssessment(`+ data_lecture[y].id + `, ` + subject_type + `, 'new')" title="Add Assessment" data-toggle="modal" data-target="#modalAssessment"><i class="fa fa-clipboard mr-2"></i> Your worksheet</a>
                                            </li>                                            
                                        </ul>
                                    </div>

                                </div>                                
                            </div>   
                        `;
                    }
                }

                content += `
                    <div class="col-lg-12 mt-4" id="card-section-lesson`+ x + `">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="mt-1">`+ data_section[x].section_title + `</h2>
                                <a class="fa-caret icon_expand">
                                    <i class="fa fa-caret-down"></i>
                                </a>                                
                            </div>                                        
                            <div class="card-body">
                                <ul class="list-group">                               
                                `+ listLecture + `
                                </ul>
                            </div>                                        
                        </div>
                    </div>
                `;
            }

            $('.load-section-lecture').html(content);

        }
    });
}


function getSectionDetail(id) {
    $.ajax({
        type: 'GET',
        url: base_url + 'lesson/getSectionDetail/' + id,
        dataType: 'json',
        success: function (res) {
            $('#sectionModule' + id).remove();
            var data = res.data_lecture;
            var moduleContent = `        
                <div class="col-lg-12" id="sectionModule`+ id + `">
                    <div class="form-inline p-1">
                        <div class="form-group mb-2">`;
            if (data.uploaded_video != '' && data.uploaded_video_type == 'local') {
                var parts = data.uploaded_video.split(".");
                var videoType = parts[parts.length - 1];
                moduleContent += `<a href="` + base_url + `/uploaded_file/video/` + data.uploaded_video + `" class="btn play-video btn-shadow btn-icon btn-rounded btn-outline-teal" style="width: 150px;"><i class="fa fa-play"></i> Video</a>`;
            } else if (data.uploaded_video != '' && data.uploaded_video_type == 'embed') {
                moduleContent += `<a href="` + data.uploaded_video + `" class="btn play-video btn-shadow btn-icon btn-rounded btn-outline-teal" style="width: 150px;"><i class="fa fa-play"></i> Video</a>`;
            } else if (data.uploaded_video != '' && data.uploaded_video_type == 'gdrive') {
                moduleContent += `<a href="https://drive.google.com/file/d/` + data.uploaded_video + `/preview" class="btn play-video btn-shadow btn-icon btn-rounded btn-outline-teal" style="width: 150px;"><i class="fa fa-play"></i> Video</a>`;
            }

            if (data.uploaded_doc != '') {
                if (data.uploaded_doc_type == 'gdrive') {
                    moduleContent += `<div class="dropdown uploaded_doc_` + id + `" style="display: inline;">
                                            <button class="btn btn-shadow btn-icon btn-rounded btn-outline-danger dropdown-toggle" style="width: 150px;" type="button" data-toggle="dropdown">
                                            <span class="fa fa-file-text-o"></span> Document</button>
                                            <ul class="dropdown-menu link_document_` + id + `">
                                                <li class="dropdown-header">Open document in...</li>
                                                <li>
                                                    <a style="cursor: pointer;" href="https://drive.google.com/file/d/`+ data.uploaded_doc + `/preview" target="_blank"><i class="fa fa-google mr-2"></i> Google Doc</a>
                                                </li>                                                                
                                                <li class="divider"></li>                                                                
                                                <li>
                                                    <a style="cursor: pointer;" href="https://drive.google.com/file/d/` + data.uploaded_doc + `/edit" target="_blank"><i class="fa fa-download mr-2"></i> Download</a>
                                                </li>                                                                                             
                                            </ul>
                                        </div>`;
                } else {
                    moduleContent += `<div class="dropdown uploaded_doc_` + id + `" style="display: inline;">
                                            <button class="btn btn-shadow btn-icon btn-rounded btn-outline-danger dropdown-toggle" style="width: 150px;" type="button" data-toggle="dropdown">
                                            <span class="fa fa-file-text-o"></span> Document</button>
                                            <ul class="dropdown-menu link_document_` + id + `">
                                                <li class="dropdown-header">Open document in...</li>
                                                <li>
                                                    <a style="cursor: pointer;" href="https://docs.google.com/gview?url=`+ base_url + `uploaded_file/doc/` + data.uploaded_doc + `&embedded=true" target="_blank"><i class="fa fa-google mr-2"></i> Google Doc</a>
                                                </li>
                                                <li>
                                                    <a style="cursor: pointer;" href="https://view.officeapps.live.com/op/view.aspx?src=`+ base_url + `uploaded_file/doc/` + data.uploaded_doc + `" target="_blank"><i class="fa fa-windows mr-2"></i> Ms. Office Online</a>
                                                </li>                                                                
                                                <li class="divider"></li>                                                                
                                                <li>
                                                    <a style="cursor: pointer;" href="` + base_url + `uploaded_file/doc/` + data.uploaded_doc + `" target="_blank"><i class="fa fa-download mr-2"></i> Download</a>
                                                </li>                                                                                             
                                            </ul>
                                        </div>`;
                }

            }

            if (data.worksheet_id != 0) {
                moduleContent += `<a href="` + base_url + `profile/worksheet/` + data.worksheet_id + `" target="_blank" class="btn btn-shadow btn-icon btn-rounded btn-outline-success" title="View Assessment" style="width: 150px;"><i class="fa fa-clipboard"></i> Assessment</a>`;
            }

            moduleContent += `</div></div></div>`;

            var selector = $('#actionBtn' + id);

            $(moduleContent).insertAfter(selector);

            $('.play-video').magnificPopup({
                type: 'iframe',
                midClick: true
            });

        }
    });
}

function entryEmbedVideo(id) {
    var entryContent = `
                <div class="col-lg-12" id = "entryEmbedVideo`+ id + `" >
                    <div class="form-inline p-2">
                        <div class="form-group mb-2" style="width: 100%">
                            <input type="text" class="form-control input_style1_black subsection" id="embed_video`+ id + `"
                                placeholder="Copy link youtube or vimeo here..." style="width: 50%" />
                            <a style="cursor: pointer; text-decoration: none;"
                                class="text-success-active fs26 ml-2 saveEmbedVideo" data-id="`+ id + `"><i
                                    class="fa fa-check-circle-o"></i></a>
                            <a style="cursor: pointer; text-decoration: none;"
                                class="text-danger-active fs26 ml-2 cancelEmbedVideo" data-id="`+ id + `"><i
                                    class="fa fa-times-circle-o"></i></a>
                            <span class="text-danger fs20 ml-3 msg-embed"></span>
                        </div>
                    </div>
            </div>
                `;

    var selector = $('#actionBtn' + id);

    $(entryContent).insertAfter(selector);

}


$(document).on('click', '.saveEmbedVideo', function (e) {
    e.preventDefault();
    var id = $(this).data('id');
    if ($('#embed_video' + id).val() == '') {
        $(this).next().next().html('Please, fill the field');
    } else {
        $.ajax({
            type: 'POST',
            url: base_url + 'lesson/updateVideo',
            data: {
                id: id,
                uploaded_video: $('#embed_video' + id).val(),
                type: 'embed'
            },
            dataType: 'json',
            success: function (res) {
                if (res.msg == 'success') {
                    $('#entryEmbedVideo' + id).remove();
                    toastr.success('Link has been saved');
                    getSectionDetail(id);
                }
            }
        });
    }
});


$(document).on('click', '.cancelEmbedVideo', function (e) {
    e.preventDefault();
    $('#entryEmbedVideo' + $(this).data('id')).remove();
})


var fileType = '';
var selected_id;
var statusUpload = '';

function startUploadFile(id, file) {
    statusUpload = 'create';
    fileType = file;
    var progressContent = `
                <div class="col-lg-12 progress progress`+ id + `" style = "display: none;">
                    <div class="progress-bar" id="progress-bar`+ id + `" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:0%">
                        <span id="status`+ id + `"></span>
                    </div>
        </div>
                `;

    var selector = $('#actionBtn' + id);

    $(progressContent).insertAfter(selector);
    selected_id = id;


    function uploadFile() {
        var file = document.getElementById("file_upload" + id).files[0];
        var formdata = new FormData();
        formdata.append("id", id);
        formdata.append("file_upload" + id, file);
        formdata.append("fileType", fileType);
        formdata.append("type", 'local');
        var ajax = new XMLHttpRequest();
        ajax.responseType = 'json';
        ajax.upload.addEventListener("progress", progressUpload, false);
        ajax.open('POST', base_url + 'lesson/uploadFile', true);
        ajax.onload = function () {
            var jsonResponse = ajax.response;
            if (jsonResponse.msg == 'success') {
                if (fileType == 'doc') {
                    toastr.success('Your document has been uploaded!');
                } else {
                    toastr.success('Your video has been uploaded!');
                }
                getSectionDetail(id);
            } else {
                toastr.error(jsonResponse.msg);
            }
        };
        ajax.send(formdata);
    }


    function progressUpload(event) {
        var percent = (event.loaded / event.total) * 100;
        document.getElementById("progress-bar" + id).style.width = Math.round(percent) + '%';
        document.getElementById("status" + id).innerHTML = Math.round(percent) + "% Completed";
        if (event.loaded == event.total) {
            setTimeout(function () {

                $('.progress' + id).remove();

            }, 500);
        }
    }


    $(document).on('change', '#file_upload' + id, function () {

        $('.progress' + id).show();
        if (statusUpload == 'create') {
            uploadFile();
        }

    });


    $('#file_upload' + id).click();

}


function editLesson(id) {
    $('.editLessonBtn' + id).prop('disabled', true);
    $.ajax({
        type: 'GET',
        url: base_url + 'lesson/getLessonById/' + id,
        dataType: 'json',
        success: function (res) {
            var entryContent = `
                <tr class="form-edit-lesson-`+ id + `">
                    <td colspan="5">
                        <form id="form-edit-lesson-`+ id + `" class="form-horizontal p-3">
                            <div class="row p-2">
                                <div class="col-lg-1"></div>
                                <label class="col-lg-3" for="level_id" style="position: right;">Level and Subject <span class="text-danger">*</span></label>
                                <div class="col-lg-7">
                                    <select class="form-control level_id" name="level_id" style="width: 100%">                                    
                                    </select>              
                                </div>                      
                                <div class="col-lg-1"></div>      
                            </div>
                            <div class="row p-2">
                                <div class="col-lg-1"></div>
                                <label class="col-lg-3" for="title">Title <span class="text-danger">*</span></label>
                                <div class="col-lg-7">
                                    <input type="text" style="width: 100%" class="form-control title" name="title" value="`+ res.title + `">
                                </div>
                                <div class="col-lg-1"></div>
                            </div>
                            <div class="row p-2">
                                <div class="col-lg-1"></div>
                                <label class="col-lg-3" for="title">Tags (e.g: tag1,tag2)</label>
                                <div class="col-lg-7">    
                                    <input type="text" style="width: 100%" class="form-control tags" name="tags" value="`+ res.tags + `">                                                       
                                </div>
                                <div class="col-lg-1"></div>
                            </div>
                            <div class="row p-2">
                                <div class="col-lg-1"></div>
                                <label class="col-lg-3" for="title">Description <span class="text-danger">*</span></label>
                                <div class="col-lg-7">
                                    <textarea class="form-control description" name="description"
                                            placeholder="Enter Description" style="width: 100%">`+ res.description + `</textarea>
                                </div>
                                <div class="col-lg-1"></div>
                            </div>
                            <div class="row">
                                <div class="col-lg-1"></div>
                                <div class="col-lg-10 text-right">
                                    <button type="button" id="updateLessonBtn`+ id + `" class="btn btn-rounded btn-icon btn-outline-success" style="width: 125px;"><i class="fa fa-check-circle-o"></i>Save</button>
                                    <button type="button" id="cancelLessonBtn`+ id + `" class="btn btn-rounded btn-icon btn-outline-danger" style="width: 125px;"><i class="fa fa-times-circle-o"></i>Cancel</button>
                                </div>
                                <div class="col-lg-1"></div>
                            </div>
                        </form>
                    </td>
                </tr>
            `;

            $(entryContent).insertAfter('#sectionRecord' + id);


            createSelectize($('#form-edit-lesson-' + id + ' .level_id'), 'id', 'subject_level', 'subject_level', 'Please select Level - Subject');
            getSubjectLevelList($('#form-edit-lesson-' + id + ' .level_id'));
            $('#form-edit-lesson-' + id + ' .description').summernote();

            setTimeout(function () {
                $('#form-edit-lesson-' + id + ' .level_id')[0].selectize.setValue(res.level_id);
            }, 300)

            $('#updateLessonBtn' + id).click(function () {
                var level_id = $('#form-edit-lesson-' + id + ' .level_id').val();
                var title = $('#form-edit-lesson-' + id + ' .title').val();
                var tags = $('#form-edit-lesson-' + id + ' .tags').val();
                var description = $('#form-edit-lesson-' + id + ' .description').val();

                if (level_id == '') {
                    swal('Warning', 'Please choose level and subject!', 'warning');
                } else if (title == '') {
                    swal('Warning', 'Please fill the title!', 'warning');
                } else if (description == '') {
                    swal('Warning', 'Please fill the description!', 'warning');
                } else {
                    $.ajax({
                        type: 'POST',
                        url: base_url + 'lesson/updateLesson',
                        data: {
                            lesson_id: id,
                            level_id: level_id,
                            title: title,
                            tags: tags,
                            description: description
                        },
                        dataType: 'json',
                        success: function (res) {
                            if (res.msg == 'success') {
                                var level_subject = $('#form-edit-lesson-' + id + ' .level_id option:selected').text();

                                $('#sectionRecord' + id + ' .level_subject').html(level_subject);
                                $('#sectionRecord' + id + ' .title').html(title);
                                $('.form-edit-lesson-' + id).remove();
                                toastr.success('The lesson has been successfully updated');
                                $('.editLessonBtn' + id).prop('disabled', false);
                            }
                        }
                    });
                }

            })

            $('#cancelLessonBtn' + id).click(function () {
                $('.form-edit-lesson-' + id).remove();
                $('.editLessonBtn' + id).prop('disabled', false);
            })
        }
    });
}


function deleteLesson(id) {
    swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this lesson!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {

                $.ajax({
                    type: 'POST',
                    url: base_url + 'lesson/deleteLesson/' + id,
                    dataType: 'json',
                    success: function (res) {
                        if (res.msg == 'success') {
                            swal("The lesson has been successfully deleted!", {
                                icon: "success",
                            });
                            loadLesson();
                        }
                    }
                });

            }
        });
}


function loadTableSectionLesson(tbl_row, lesson_id) {
    $.ajax({
        url: base_url + 'lesson/loadSectionLesson/' + lesson_id,
        type: 'GET',
        dataType: 'json',
        success: function (res) {
            $('.tbl_row_loading').remove();
            var data_lesson = res.data.data_lesson;
            var subject_type = data_lesson.subject_type;
            var data_section = res.data.data_section;
            var data_lecture = res.data.data_lecture;


            if (data_section.length == 0) {
                $(tbl_row).after(`
                        <tr class="showLessonSection" id="contentLesson_`+ lesson_id + `">
                            <td colspan="5">
                                <table class="table">
                                    <thead>
                                        <tr class="info">
                                            <th width="5%"></th>
                                            <th width="80%">Section</th>                                                    
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td colspan="3" class="text-left">Not section yet.</td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    `);
            } else {
                var row = `<tr class="showLessonSection" id="contentLesson_` + lesson_id + `">
                            <td colspan="5">
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead>
                                            <tr class="info">
                                                <th width="5%"></th>
                                                <th width="80%">Section</th>                                                    
                                                <th width="15%"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                `;

                for (x = 0; x < data_section.length; x++) {
                    var listLecture = '';
                    for (y = 0; y < data_lecture.length; y++) {
                        if (data_lecture[y].section_id == data_section[x].section_id) {
                            var moduleVideo = ``;
                            var moduleDocument = ``;
                            var moduleAssessment = ``;

                            if (data_lecture[y].uploaded_video != '' && data_lecture[y].uploaded_video_type == 'local') {
                                var parts = data_lecture[y].uploaded_video.split(".");
                                var videoType = parts[parts.length - 1];
                                moduleVideo += `<div class="dropdown" style="display: inline;">
                                                <button class="btn btn-icon-o radius100 btn-outline-teal btn-no-margin-top dropdown-toggle" type="button" data-toggle="dropdown">
                                                <span class="picons-thin-icon-thin-0588_play_movie_video_cinema_flm"></span></button>
                                                <ul class="dropdown-menu list_uploaded_video_` + data_lecture[y].id + `">`;
                                moduleVideo += `<li><a style="cursor: pointer;" href="` + base_url + `/uploaded_file/video/` + data_lecture[y].uploaded_video + `" class="play-video uploaded_video` + data_lecture[y].id + `"><i class="picons-thin-icon-thin-0158_arrow_next_right mr-2"></i> Play Video</a></li>
                                                <li class="divider"></li>
                                                <li><a style="cursor: pointer;" onClick="studentViewed('`+ data_lecture[y].lecture_title + `', ` + data_lecture[y].id + `, 'video')" ><i class="picons-thin-icon-thin-0704_users_profile_group_couple_man_woman mr-2"></i> Viewer (` + data_lecture[y].total_student_viewed + `)</a></li>
                                `;
                                moduleVideo += `</ul></div>`;
                            } else if (data_lecture[y].uploaded_video != '' && data_lecture[y].uploaded_video_type == 'embed') {
                                moduleVideo += `<div class="dropdown" style="display: inline;">
                                                <button class="btn btn-icon-o radius100 btn-outline-teal btn-no-margin-top dropdown-toggle" type="button" data-toggle="dropdown">
                                                <span class="picons-thin-icon-thin-0588_play_movie_video_cinema_flm"></span></button>
                                                <ul class="dropdown-menu list_uploaded_video_` + data_lecture[y].id + `">`;
                                moduleVideo += `<li><a style="cursor: pointer;" href="` + data_lecture[y].uploaded_video + `" class="play-video uploaded_video` + data_lecture[y].id + `"><i class="picons-thin-icon-thin-0158_arrow_next_right mr-2"></i> Play Video</a></li>
                                                <li class="divider"></li>
                                                <li><a style="cursor: pointer;" onClick="studentViewed('`+ data_lecture[y].lecture_title + `', ` + data_lecture[y].id + `, 'video')" ><i class="picons-thin-icon-thin-0704_users_profile_group_couple_man_woman mr-2"></i> Viewer (` + data_lecture[y].total_student_viewed + `)</a></li>
                                `;
                                moduleVideo += `</ul></div>`;
                            } else if (data_lecture[y].uploaded_video != '' && data_lecture[y].uploaded_video_type == 'gdrive') {
                                moduleVideo += `<div class="dropdown" style="display: inline;">
                                                <button class="btn btn-icon-o radius100 btn-outline-teal btn-no-margin-top dropdown-toggle" type="button" data-toggle="dropdown">
                                                <span class="picons-thin-icon-thin-0588_play_movie_video_cinema_flm"></span></button>
                                                <ul class="dropdown-menu list_uploaded_video_` + data_lecture[y].id + `">`;
                                moduleVideo += `<li><a style="cursor: pointer;" href="https://drive.google.com/file/d/` + data_lecture[y].uploaded_video + `/preview" class="play-video uploaded_video` + data_lecture[y].id + `"><i class="picons-thin-icon-thin-0158_arrow_next_right mr-2"></i> Play Video</a></li>
                                                <li class="divider"></li>
                                                <li><a style="cursor: pointer;" onClick="studentViewed('`+ data_lecture[y].lecture_title + `', ` + data_lecture[y].id + `, 'video')" ><i class="picons-thin-icon-thin-0704_users_profile_group_couple_man_woman mr-2"></i> Viewer (` + data_lecture[y].total_student_viewed + `)</a></li>
                                `;
                                moduleVideo += `</ul></div>`;
                            } else {
                                moduleVideo += `<div class="dropdown btn_uploaded_video_` + data_lecture[y].id + `" style="display: none;">
                                                    <button class="btn btn-icon-o radius100 btn-outline-teal btn-no-margin-top dropdown-toggle" type="button" data-toggle="dropdown">
                                                    <span class="picons-thin-icon-thin-0588_play_movie_video_cinema_flm"></span></button>
                                                    <ul class="dropdown-menu list_uploaded_video_` + data_lecture[y].id + `">
                                                        
                                                    </ul>
                                                </div>
                                `;
                            }


                            if (data_lecture[y].uploaded_doc != '') {
                                if (data_lecture[y].uploaded_doc_type == 'gdrive') {
                                    moduleDocument += `<div class="dropdown uploaded_doc_` + data_lecture[y].id + `" style="display: inline;">
                                                            <button class="btn btn-icon-o radius100 btn-outline-danger btn-no-margin-top dropdown-toggle" type="button" data-toggle="dropdown">
                                                            <span class="fa fa-file-text-o"></span></button>
                                                            <ul class="dropdown-menu link_document_` + data_lecture[y].id + `">
                                                                <li class="dropdown-header">Open document in...</li>
                                                                <li>
                                                                    <a style="cursor: pointer;" href="https://drive.google.com/file/d/`+ data_lecture[y].uploaded_doc + `/preview" target="_blank"><i class="fa fa-google mr-2"></i> Google Doc</a>
                                                                </li>                                                                
                                                                <li class="divider"></li>                                                                
                                                                <li>
                                                                    <a style="cursor: pointer;" href="https://drive.google.com/file/d/` + data_lecture[y].uploaded_doc + `/edit" target="_blank"><i class="fa fa-download mr-2"></i> Download</a>
                                                                </li>                                                                                             
                                                            </ul>
                                                        </div>`;
                                } else {
                                    moduleDocument += `<div class="dropdown uploaded_doc_` + data_lecture[y].id + `" style="display: inline;">
                                                            <button class="btn btn-icon-o radius100 btn-outline-danger btn-no-margin-top dropdown-toggle" type="button" data-toggle="dropdown">
                                                            <span class="fa fa-file-text-o"></span></button>
                                                            <ul class="dropdown-menu link_document_` + data_lecture[y].id + `">
                                                                <li class="dropdown-header">Open document in...</li>
                                                                <li>
                                                                    <a style="cursor: pointer;" href="https://docs.google.com/gview?url=`+ base_url + `uploaded_file/doc/` + data_lecture[y].uploaded_doc + `&embedded=true" target="_blank"><i class="fa fa-google mr-2"></i> Google Doc</a>
                                                                </li>
                                                                <li>
                                                                    <a style="cursor: pointer;" href="https://view.officeapps.live.com/op/view.aspx?src=`+ base_url + `uploaded_file/doc/` + data_lecture[y].uploaded_doc + `" target="_blank"><i class="fa fa-windows mr-2"></i> Ms. Office Online</a>
                                                                </li>                                                                
                                                                <li class="divider"></li>                                                                
                                                                <li>
                                                                    <a style="cursor: pointer;" href="` + base_url + `uploaded_file/doc/` + data_lecture[y].uploaded_doc + `" target="_blank"><i class="fa fa-download mr-2"></i> Download</a>
                                                                </li>                                                                                             
                                                            </ul>
                                                        </div>`;
                                }
                            } else {
                                moduleDocument += `<div class="dropdown uploaded_doc_` + data_lecture[y].id + `" style="display: none;">
                                                            <button class="btn btn-icon-o radius100 btn-outline-danger btn-no-margin-top dropdown-toggle" type="button" data-toggle="dropdown">
                                                            <span class="fa fa-file-text-o"></span></button>
                                                            <ul class="dropdown-menu link_document_` + data_lecture[y].id + `">                                                                                                                                                      
                                                            </ul>
                                                        </div>`;
                            }

                            if (data_lecture[y].worksheet_id != 0) {
                                moduleAssessment += `<a href="` + base_url + `profile/worksheet/` + data_lecture[y].worksheet_id + `" target="_blank" title="View Assessment" class="btn btn-icon-o radius100 btn-shadow btn-outline-success btn-no-margin-top assessment` + data_lecture[y].id + `"><i class="fa fa-clipboard"></i></a>`;
                            } else {
                                moduleAssessment += `<a href="" target="_blank" style="display: none;" class="btn btn-icon-o radius100 btn-shadow btn-outline-success btn-no-margin-top assessment` + data_lecture[y].id + `"><i class="fa fa-clipboard"></i></a>`;
                            }

                            listLecture += `
                                <tr class="lectureRecord" id="lectureRecord`+ data_lecture[y].id + `">
                                    <td>`+ data_lecture[y].number + ` )</td>
                                    <td class="text-left lecture_title">`+ data_lecture[y].lecture_title + `</td>
                                    <td class="text-left lectureActionBtn">                                        
                                        <button class="btn btn-icon-o radius100 btn-outline-warning btn-no-margin-top editLectureBtn`+ data_lecture[y].id + `" onClick="editLecture(` + data_lecture[y].id + `)" title="Edit Lecture"><i class="fa fa-pencil-square-o"></i></button>
                                        <div class="dropdown" style="display: inline;">
                                            <button class="btn btn-icon-o radius100 btn-outline-primary btn-no-margin-top dropdown-toggle" type="button" data-toggle="dropdown">
                                            <span class="fa fa-upload"></span></button>
                                            <ul class="dropdown-menu">
                                                <li class="dropdown-header">Upload a video from...</li>
                                                <li>
                                                    <a style="cursor: pointer;" class="embedVideo" onClick="entryUpdateEmbedVideo(`+ data_lecture[y].id + `)" title="Embed Video"><i class="fa fa-link mr-2"></i> Embed Link</a>
                                                </li>
                                                <li>
                                                    <a style="cursor: pointer;" class="uploadVideo" onClick="startUpdateUploadFile(`+ data_lecture[y].id + `, 'video')" title="Upload Video"><i class="fa fa-desktop mr-2"></i> Your Computer</a>
                                                </li>
                                                <li>
                                                    <a style="cursor: pointer;" onClick="showPickerDialog(`+ data_lecture[y].id + `, 'video', true)"><i class="fa fa-google mr-2"></i> Google Drive</a>
                                                </li>
                                                <li class="divider"></li>
                                                <li class="dropdown-header">Upload a document from...</li>
                                                <li>
                                                    <a style="cursor: pointer;" onClick="startUpdateUploadFile(`+ data_lecture[y].id + `, 'doc')" title="Upload Document"><i class="fa fa-desktop mr-2"></i> Your Computer</a>
                                                </li>
                                                <li>
                                                    <a style="cursor: pointer;" onClick="showPickerDialog(`+ data_lecture[y].id + `, 'doc', true)"><i class="fa fa-google mr-2"></i> Google Drive</a>
                                                </li>
                                                <li class="divider"></li>
                                                <li class="dropdown-header">Add assessment...</li>
                                                <li>
                                                    <a style="cursor: pointer;" onClick="addAssessment(`+ data_lecture[y].id + `, ` + subject_type + `, 'edit')" title="Add Assessment" data-toggle="modal" data-target="#modalAssessment"><i class="fa fa-clipboard mr-2"></i> Your worksheet</a>
                                                </li>
                                            </ul>
                                        </div>                                            
                                        `+ moduleVideo + `                                            
                                        `+ moduleDocument + ` 
                                        `+ moduleAssessment + `  
                                        <button class="btn btn-icon-o radius100 btn-outline-danger btn-no-margin-top" onClick="deleteLecture(`+ data_lecture[y].id + `, ` + data_section[x].section_id + `)" title="Delete Lecture"><i class="fa fa-trash-o"></i></button>
                                        <form id='form-upload` + data_lecture[y].id + `' method='post' enctype="multipart/form-data" style="display:none">
                                            <input type="file" id="file_upload` + data_lecture[y].id + `" name="file_upload` + data_lecture[y].id + `" />                                            
                                        </form>                                                                            
                                    </td>
                                </tr>
                            `;
                        }
                    }

                    row += `
                        <tr class='showLessonSectionRecord contentSection_` + data_section[x].section_id + `' id="showLessonSectionRecord` + data_section[x].section_id + `" data-status="showLectureRecord" data-id='` + data_section[x].section_id + `'>
                            <td width="5%">`+ (x + 1) + `</td>
                            <td width="80%" class="text-left section_title">`+ data_section[x].section_title + `</td>
                            <td width="15%">
                                <button class="btn btn-icon-o radius100 btn-outline-warning btn-no-margin-top editSectionBtn`+ data_section[x].section_id + `" onClick="editSection(` + data_section[x].section_id + `)" title="Edit Section"><i class="fa fa-pencil-square-o"></i></button>
                                <button class="btn btn-icon-o radius100 btn-outline-danger btn-no-margin-top" onClick="deleteSection(`+ data_section[x].section_id + `, ` + lesson_id + `)" title="Delete Section"><i class="fa fa-trash-o"></i></button>
                                <a class="btn-no-margin-top text-dark showLecture" data-status="showLecture" style="cursor: pointer; text-decoration: none" data-id="` + data_section[x].section_id + `" title="Expand/Collapse"><i class="fa fa-caret-down"></i></a>
                            </td>
                        </tr>                        
                        <tr class='listLecture contentSection_` + data_section[x].section_id + `' id='listLecture` + data_section[x].section_id + `' data-subject="` + subject_type + `" style='display: none;'>
                            <td colspan='3'>
                                <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr class="info">
                                            <th width="5%"></th>
                                            <th width="60%">Lecture</th>
                                            <th width="35%"></th>
                                        </tr>
                                    </thead>
                                    <tbody class="list_lecture_` + data_section[x].section_id + `">
                                        `+ listLecture + `
                                        <tr>
                                            <td></td>
                                            <td colspan="2" class="contentEntryLecture_`+ data_section[x].section_id + `"><a class="text-danger mr-1" style="cursor: pointer; text-decoration: none;" onClick="createNewLecture(` + data_section[x].section_id + `)"><i class="fa fa-plus"></i> New Lecture</a></td>
                                        </tr>
                                    </tbody>
                                </table>
                                </div>
                            </td>
                        </tr>
                    `;
                }
                row += `<tr>
                            <td></td>
                            <td colspan="2" class="contentEntrySection_`+ lesson_id + `"><a class="text-success mr-1" style="cursor: pointer; text-decoration: none;" onClick="createNewSection(` + lesson_id + `)"><i class="fa fa-plus"></i> New Section</a></td>
                        </tr></tbody></table></div></td></tr>`;

                $(tbl_row).after(row);

            }


            $('.showLecture').on('click', function (e) {
                e.preventDefault();
                var section_id = $(this).data('id');
                if ($(this).data('status') == "showLecture") {
                    $('#listLecture' + section_id).show("slow");
                    $(this).data("status", "hideLecture");
                    $(this).find($(".fa")).removeClass('fa-caret-down').addClass('fa-caret-up');
                } else if ($(this).data("status") == "hideLecture") {
                    $('#listLecture' + section_id).hide("slow");
                    $(this).data("status", "showLecture");
                    $(this).find($(".fa")).removeClass('fa-caret-up').addClass('fa-caret-down');
                }
            });


            $('.showLessonSectionRecord').mouseenter(function () {
                $(this).css("background-color", "#f6f6f6");
            }).mouseleave(function () {
                $(this).css("background-color", "#ffffff");
            });

            $('.play-video').magnificPopup({
                type: 'iframe',
                midClick: true
            });

        }

    });
}


$(document).on('click', '.showSectionBtn', function (e) {
    e.preventDefault();
    var lesson_id = $(this).data('id');
    var tbl_row = $('#sectionRecord' + lesson_id);
    var nextRow = tbl_row.next().attr('class');
    console.log(nextRow);
    if (nextRow === 'showLessonSection' || nextRow === 'tbl_row_loading') {
        tbl_row.find('.fa-caret-up').removeClass('fa-caret-up').addClass('fa-caret-down');
        tbl_row.next().remove();
    } else {
        tbl_row.find('.fa-caret-down').removeClass('fa-caret-down').addClass('fa-caret-up');
        tbl_row.after('<tr class="tbl_row_loading"><td colspan="5"><i class="fa fa-spinner"></i></td></tr>');

        loadTableSectionLesson(tbl_row, lesson_id);

    }

});


function createNewSection(lesson_id) {

    var entryContent = `
        <div class="form-inline p-1 form-add-section-`+ lesson_id + `">
            <div class="form-group mb-2" style="width: 100%">
                <input type="text" class="form-control input_style1_black section_title" placeholder="Section Title" style="width: 80%" />
                <a style="cursor: pointer; text-decoration: none;" id="saveSectionBtn`+ lesson_id + `" class="text-success-active fs22 ml-2"><i class="fa fa-check-circle-o"></i></a>     
                <a style="cursor: pointer; text-decoration: none;" id="cancelSectionBtn`+ lesson_id + `" class="text-danger-active fs22 ml-2"><i class="fa fa-times-circle-o"></i></a>     
            </div>                                                                                                     
        </div>`;


    $('.contentEntrySection_' + lesson_id).html(entryContent);

    $('#saveSectionBtn' + lesson_id).click(function () {
        var section_title = $('.form-add-section-' + lesson_id + ' .section_title').val();

        if (section_title == '') {
            swal("Please fill the field", {
                icon: "warning",
            });
        } else {
            $.ajax({
                type: 'POST',
                url: base_url + 'lesson/createNewSection',
                data: {
                    lesson_id: lesson_id,
                    section_title: section_title
                },
                dataType: 'json',
                success: function (res) {
                    if (res.msg == 'success') {
                        swal("The new section has been successfully created!", {
                            icon: "success",
                        });

                        var tbl_row = $('#sectionRecord' + lesson_id);
                        tbl_row.next().remove();
                        tbl_row.after('<tr class="tbl_row_loading"><td colspan="5"><i class="fa fa-spinner"></i></td></tr>');
                        loadTableSectionLesson(tbl_row, lesson_id);

                    }
                }
            });
        }
    })


    $('#cancelSectionBtn' + lesson_id).click(function () {
        $('.form-add-section-' + lesson_id).remove();
        $('.contentEntrySection_' + lesson_id).html(`<a class="text-success mr-1" style="cursor: pointer; text-decoration: none;" onClick="createNewSection(` + lesson_id + `)"><i class="fa fa-plus"></i> New Section</a>`);
    })


}


function editSection(id) {

    var old_section_title = $('#showLessonSectionRecord' + id + ' .section_title').text();

    $('.editSectionBtn' + id).prop('disabled', true);

    var editContent = `
        <div class="form-inline p-1 form-edit-section-`+ id + `">
            <div class="form-group mb-2" style="width: 100%">
                <input type="text" class="form-control input_style1_black section_title" value="`+ old_section_title + `" placeholder="Section" style="width: 80%" />
                <a style="cursor: pointer; text-decoration: none;" id="updateSectionBtn`+ id + `" class="text-success-active fs22 ml-2"><i class="fa fa-check-circle-o"></i></a>     
                <a style="cursor: pointer; text-decoration: none;" id="cancelSectionBtn`+ id + `" class="text-danger-active fs22 ml-2"><i class="fa fa-times-circle-o"></i></a>     
            </div>                                                                                                     
        </div>
    `;

    $('#showLessonSectionRecord' + id + ' .section_title').html(editContent);

    $('#updateSectionBtn' + id).click(function () {
        var section_title = $('.form-edit-section-' + id + ' .section_title').val();

        if (section_title == '') {
            swal("Please fill the field", {
                icon: "warning",
            });
        } else {
            $.ajax({
                type: 'POST',
                url: base_url + 'lesson/updateSection',
                data: {
                    section_id: id,
                    section_title: section_title
                },
                dataType: 'json',
                success: function (res) {
                    if (res.msg == 'success') {
                        swal("The section title has been successfully updated!", {
                            icon: "success",
                        });
                        $('.form-edit-section-' + id).remove();
                        $('#showLessonSectionRecord' + id + ' .section_title').html(section_title);
                        $('.editSectionBtn' + id).prop('disabled', false);
                    }
                }
            });
        }
    })

    $('#cancelSectionBtn' + id).click(function () {
        $('#showLessonSectionRecord' + id + ' .section_title').html(old_section_title);
        $('.form-edit-section-' + id).remove();
        $('.editSectionBtn' + id).prop('disabled', false);
    })

}


function deleteSection(id, lesson_id) {
    swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this section!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {

                $.ajax({
                    type: 'POST',
                    url: base_url + 'lesson/deleteSection/' + id,
                    dataType: 'json',
                    success: function (res) {
                        if (res.msg == 'success') {
                            swal("The section has been successfully deleted!", {
                                icon: "success",
                            });
                            var tbl_row = $('#sectionRecord' + lesson_id);
                            tbl_row.next().remove();
                            tbl_row.after('<tr class="tbl_row_loading"><td colspan="5"><i class="fa fa-spinner"></i></td></tr>');
                            loadTableSectionLesson(tbl_row, lesson_id);
                        }
                    }
                });

            }
        });
}


function loadTableLecture(section_id) {
    $.ajax({
        type: 'GET',
        url: base_url + 'lesson/loadLectureBySection/' + section_id,
        dataType: 'json',
        success: function (res) {
            var data_lecture = res;
            var listLecture = ``;
            var subject_type = $('#listLecture' + section_id).data('subject');

            for (var y = 0; y < data_lecture.length; y++) {
                var moduleVideo = ``;
                var moduleDocument = ``;
                var moduleAssessment = ``;

                if (data_lecture[y].uploaded_video != '' && data_lecture[y].uploaded_video_type == 'local') {
                    var parts = data_lecture[y].uploaded_video.split(".");
                    var videoType = parts[parts.length - 1];
                    moduleVideo += `<div class="dropdown" style="display: inline;">
                                    <button class="btn btn-icon-o radius100 btn-outline-teal btn-no-margin-top dropdown-toggle" type="button" data-toggle="dropdown">
                                    <span class="picons-thin-icon-thin-0588_play_movie_video_cinema_flm"></span></button>
                                    <ul class="dropdown-menu list_uploaded_video_` + data_lecture[y].id + `">`;
                    moduleVideo += `<li><a style="cursor: pointer;" href="` + base_url + `/uploaded_file/video/` + data_lecture[y].uploaded_video + `" class="play-video uploaded_video` + data_lecture[y].id + `"><i class="picons-thin-icon-thin-0158_arrow_next_right mr-2"></i> Play Video</a></li>
                                    <li class="divider"></li>
                                    <li><a style="cursor: pointer;" onClick="studentViewed('`+ data_lecture[y].lecture_title + `', ` + data_lecture[y].id + `, 'video')" ><i class="picons-thin-icon-thin-0704_users_profile_group_couple_man_woman mr-2"></i> Viewer (` + data_lecture[y].total_student_viewed + `)</a></li>
                    `;
                    moduleVideo += `</ul></div>`;
                } else if (data_lecture[y].uploaded_video != '' && data_lecture[y].uploaded_video_type == 'embed') {
                    moduleVideo += `<div class="dropdown" style="display: inline;">
                                    <button class="btn btn-icon-o radius100 btn-outline-teal btn-no-margin-top dropdown-toggle" type="button" data-toggle="dropdown">
                                    <span class="picons-thin-icon-thin-0588_play_movie_video_cinema_flm"></span></button>
                                    <ul class="dropdown-menu list_uploaded_video_` + data_lecture[y].id + `">`;
                    moduleVideo += `<li><a style="cursor: pointer;" href="` + data_lecture[y].uploaded_video + `" class="play-video uploaded_video` + data_lecture[y].id + `"><i class="picons-thin-icon-thin-0158_arrow_next_right mr-2"></i> Play Video</a></li>
                                    <li class="divider"></li>
                                    <li><a style="cursor: pointer;" onClick="studentViewed('`+ data_lecture[y].lecture_title + `', ` + data_lecture[y].id + `, 'video')" ><i class="picons-thin-icon-thin-0704_users_profile_group_couple_man_woman mr-2"></i> Viewer (` + data_lecture[y].total_student_viewed + `)</a></li>
                    `;
                    moduleVideo += `</ul></div>`;
                } else if (data_lecture[y].uploaded_video != '' && data_lecture[y].uploaded_video_type == 'gdrive') {
                    moduleVideo += `<div class="dropdown" style="display: inline;">
                                    <button class="btn btn-icon-o radius100 btn-outline-teal btn-no-margin-top dropdown-toggle" type="button" data-toggle="dropdown">
                                    <span class="picons-thin-icon-thin-0588_play_movie_video_cinema_flm"></span></button>
                                    <ul class="dropdown-menu list_uploaded_video_` + data_lecture[y].id + `">`;
                    moduleVideo += `<li><a style="cursor: pointer;" href="https://drive.google.com/file/d/` + data_lecture[y].uploaded_video + `/preview" class="play-video uploaded_video` + data_lecture[y].id + `"><i class="picons-thin-icon-thin-0158_arrow_next_right mr-2"></i> Play Video</a></li>
                                    <li class="divider"></li>
                                    <li><a style="cursor: pointer;" onClick="studentViewed('`+ data_lecture[y].lecture_title + `', ` + data_lecture[y].id + `, 'video')" ><i class="picons-thin-icon-thin-0704_users_profile_group_couple_man_woman mr-2"></i> Viewer (` + data_lecture[y].total_student_viewed + `)</a></li>
                    `;
                    moduleVideo += `</ul></div>`;
                } else {
                    moduleVideo += `<div class="dropdown btn_uploaded_video_` + data_lecture[y].id + `" style="display: inline;">
                                        <button class="btn btn-icon-o radius100 btn-outline-teal btn-no-margin-top dropdown-toggle" type="button" data-toggle="dropdown">
                                        <span class="picons-thin-icon-thin-0588_play_movie_video_cinema_flm"></span></button>
                                        <ul class="dropdown-menu list_uploaded_video_` + data_lecture[y].id + `">
                                            
                                        </ul>
                                    </div>
                    `;
                }


                if (data_lecture[y].uploaded_doc != '') {
                    if (data_lecture[y].uploaded_doc_type == 'gdrive') {
                        moduleDocument += `<div class="dropdown uploaded_doc_` + data_lecture[y].id + `" style="display: inline;">
                                                    <button class="btn btn-icon-o radius100 btn-outline-danger btn-no-margin-top dropdown-toggle" type="button" data-toggle="dropdown">
                                                    <span class="fa fa-file-text-o"></span></button>
                                                    <ul class="dropdown-menu link_document_` + data_lecture[y].id + `">
                                                        <li class="dropdown-header">Open document in...</li>
                                                        <li>
                                                            <a style="cursor: pointer;" href="https://drive.google.com/file/d/`+ data_lecture[y].uploaded_doc + `/preview" target="_blank"><i class="fa fa-google mr-2"></i> Google Doc</a>
                                                        </li>                                                                
                                                        <li class="divider"></li>                                                                
                                                        <li>
                                                            <a style="cursor: pointer;" href="https://drive.google.com/file/d/` + data_lecture[y].uploaded_doc + `/edit" target="_blank"><i class="fa fa-download mr-2"></i> Download</a>
                                                        </li>                                                                                             
                                                    </ul>
                                                </div>`;
                    } else {
                        moduleDocument += `<div class="dropdown uploaded_doc_` + data_lecture[y].id + `" style="display: inline;">
                                                    <button class="btn btn-icon-o radius100 btn-outline-danger btn-no-margin-top dropdown-toggle" type="button" data-toggle="dropdown">
                                                    <span class="fa fa-file-text-o"></span></button>
                                                    <ul class="dropdown-menu link_document_` + data_lecture[y].id + `">
                                                        <li class="dropdown-header">Open document in...</li>
                                                        <li>
                                                            <a style="cursor: pointer;" href="https://docs.google.com/gview?url=`+ base_url + `uploaded_file/doc/` + data_lecture[y].uploaded_doc + `&embedded=true" target="_blank"><i class="fa fa-google mr-2"></i> Google Doc</a>
                                                        </li>
                                                        <li>
                                                            <a style="cursor: pointer;" href="https://view.officeapps.live.com/op/view.aspx?src=`+ base_url + `uploaded_file/doc/` + data_lecture[y].uploaded_doc + `" target="_blank"><i class="fa fa-windows mr-2"></i> Ms. Office Online</a>
                                                        </li>                                                                
                                                        <li class="divider"></li>                                                                
                                                        <li>
                                                            <a style="cursor: pointer;" href="` + base_url + `uploaded_file/doc/` + data_lecture[y].uploaded_doc + `" target="_blank"><i class="fa fa-download mr-2"></i> Download</a>
                                                        </li>                                                                                             
                                                    </ul>
                                                </div>`;
                    }
                } else {
                    moduleDocument += `<div class="dropdown uploaded_doc_` + data_lecture[y].id + `" style="display: none;">
                                                    <button class="btn btn-icon-o radius100 btn-outline-danger btn-no-margin-top dropdown-toggle" type="button" data-toggle="dropdown">
                                                    <span class="fa fa-file-text-o"></span></button>
                                                    <ul class="dropdown-menu link_document_` + data_lecture[y].id + `">                                                                                                                                                      
                                                    </ul>
                                                </div>`;
                }

                if (data_lecture[y].worksheet_id != 0) {
                    moduleAssessment += `<a href="` + base_url + `profile/worksheet/` + data_lecture[y].worksheet_id + `" target="_blank" title="View Assessment" class="btn btn-icon-o radius100 btn-shadow btn-outline-success btn-no-margin-top assessment` + data_lecture[y].id + `"><i class="fa fa-clipboard"></i></a>`;
                } else {
                    moduleAssessment += `<a href="" target="_blank" style="display: none;" class="btn btn-icon-o radius100 btn-shadow btn-outline-success btn-no-margin-top assessment` + data_lecture[y].id + `"><i class="fa fa-clipboard"></i></a>`;
                }

                listLecture += `
                        <tr class="lectureRecord" id="lectureRecord`+ data_lecture[y].id + `">
                            <td>`+ data_lecture[y].number + ` )</td>
                            <td class="text-left lecture_title">`+ data_lecture[y].lecture_title + `</td>
                            <td class="text-left lectureActionBtn">                                        
                                <button class="btn btn-icon-o radius100 btn-outline-warning btn-no-margin-top editLectureBtn`+ data_lecture[y].id + `" onClick="editLecture(` + data_lecture[y].id + `)" title="Edit Lecture"><i class="fa fa-pencil-square-o"></i></button>
                                <div class="dropdown" style="display: inline;">
                                    <button class="btn btn-icon-o radius100 btn-outline-primary btn-no-margin-top dropdown-toggle" type="button" data-toggle="dropdown">
                                    <span class="fa fa-upload"></span></button>
                                    <ul class="dropdown-menu">
                                        <li class="dropdown-header">Upload a video from...</li>
                                        <li>
                                            <a style="cursor: pointer;" class="embedVideo" onClick="entryUpdateEmbedVideo(`+ data_lecture[y].id + `)" title="Embed Video"><i class="fa fa-link mr-2"></i> Embed Link</a>
                                        </li>
                                        <li>
                                            <a style="cursor: pointer;" class="uploadVideo" onClick="startUpdateUploadFile(`+ data_lecture[y].id + `, 'video')" title="Upload Video"><i class="fa fa-desktop mr-2"></i> Your Computer</a>
                                        </li>
                                        <li>
                                            <a style="cursor: pointer;" onClick="showPickerDialog(`+ data_lecture[y].id + `, 'video', true)"><i class="fa fa-google mr-2"></i> Google Drive</a>
                                        </li>
                                        <li class="divider"></li>
                                        <li class="dropdown-header">Upload a document from...</li>
                                        <li>
                                            <a style="cursor: pointer;" onClick="startUpdateUploadFile(`+ data_lecture[y].id + `, 'doc')" title="Upload Document"><i class="fa fa-desktop mr-2"></i> Your Computer</a>
                                        </li>
                                        <li>
                                            <a style="cursor: pointer;" onClick="showPickerDialog(`+ data_lecture[y].id + `, 'doc', true)"><i class="fa fa-google mr-2"></i> Google Drive</a>
                                        </li>
                                        <li class="divider"></li>
                                        <li class="dropdown-header">Add assessment...</li>
                                        <li>
                                            <a style="cursor: pointer;" onClick="addAssessment(`+ data_lecture[y].id + `, ` + subject_type + `, 'edit')" title="Add Assessment" data-toggle="modal" data-target="#modalAssessment"><i class="fa fa-clipboard mr-2"></i> Your worksheet</a>
                                        </li>
                                    </ul>
                                </div>                                            
                                `+ moduleVideo + `                                            
                                `+ moduleDocument + ` 
                                `+ moduleAssessment + `  
                                <button class="btn btn-icon-o radius100 btn-outline-danger btn-no-margin-top" onClick="deleteLecture(`+ data_lecture[y].id + `, ` + section_id + `)" title="Delete Lecture"><i class="fa fa-trash-o"></i></button>
                                <form id='form-upload` + data_lecture[y].id + `' method='post' enctype="multipart/form-data" style="display:none">
                                    <input type="file" id="file_upload` + data_lecture[y].id + `" name="file_upload` + data_lecture[y].id + `" />                                            
                                </form>                                                                            
                            </td>
                        </tr>                        
                    `;

            }

            listLecture += `
                    <tr>
                        <td></td>
                        <td colspan="2" class="contentEntryLecture_`+ section_id + `"><a class="text-danger mr-1" style="cursor: pointer; text-decoration: none;" onClick="createNewLecture(` + section_id + `)"><i class="fa fa-plus"></i> New Lecture</a></td>
                    </tr>`;

            $('.list_lecture_' + section_id).html(listLecture);

            $('.play-video').magnificPopup({
                type: 'iframe',
                midClick: true
            });

        }
    });
}


function createNewLecture(section_id) {
    var entryContent = `
    <div class="form-inline p-1 form-add-lecture-`+ section_id + `">
        <div class="form-group mb-2" style="width: 100%">
            <input type="text" class="form-control input_style1_black lecture_title" placeholder="Lecture Title" style="width: 80%" />
            <a style="cursor: pointer; text-decoration: none;" id="saveLectureBtn`+ section_id + `" class="text-success-active fs22 ml-2"><i class="fa fa-check-circle-o"></i></a>     
            <a style="cursor: pointer; text-decoration: none;" id="cancelLectureBtn`+ section_id + `" class="text-danger-active fs22 ml-2"><i class="fa fa-times-circle-o"></i></a>     
        </div>                                                                                                     
    </div>`;


    $('.contentEntryLecture_' + section_id).html(entryContent);

    $('#saveLectureBtn' + section_id).click(function () {
        var lecture_title = $('.form-add-lecture-' + section_id + ' .lecture_title').val();

        if (lecture_title == '') {
            swal("Please fill the field", {
                icon: "warning",
            });
        } else {
            $.ajax({
                type: 'POST',
                url: base_url + 'lesson/createNewLecture',
                data: {
                    section_id: section_id,
                    lecture_title: lecture_title
                },
                dataType: 'json',
                success: function (res) {
                    if (res.msg == 'success') {
                        swal("The new lecture has been successfully created!", {
                            icon: "success",
                        });
                        loadTableLecture(section_id);
                    }
                }
            });
        }
    })


    $('#cancelLectureBtn' + section_id).click(function () {
        $('.form-add-lecture-' + section_id).remove();
        $('.contentEntryLecture_' + section_id).html(`<a class="text-danger mr-1" style="cursor: pointer; text-decoration: none;" onClick="createNewLecture(` + section_id + `)"><i class="fa fa-plus"></i> New Lecture</a>`);
    })
}


function editLecture(id) {

    var old_lecture_title = $('#lectureRecord' + id + ' .lecture_title').text();

    $('.editLectureBtn' + id).prop('disabled', true);

    var editContent = `
        <div class="form-inline p-1 form-edit-lecture-`+ id + `">
            <div class="form-group mb-2" style="width: 100%">
                <input type="text" class="form-control input_style1_black lecture_title" value="`+ old_lecture_title + `" placeholder="Lecture" style="width: 80%" />
                <a style="cursor: pointer; text-decoration: none;" id="updateLectureBtn`+ id + `" class="text-success-active fs22 ml-2"><i class="fa fa-check-circle-o"></i></a>     
                <a style="cursor: pointer; text-decoration: none;" id="cancelLectureBtn`+ id + `" class="text-danger-active fs22 ml-2"><i class="fa fa-times-circle-o"></i></a>     
            </div>                                                                                                     
        </div>
    `;

    $('#lectureRecord' + id + ' .lecture_title').html(editContent);

    $('#updateLectureBtn' + id).click(function () {
        var lecture_title = $('.form-edit-lecture-' + id + ' .lecture_title').val();

        if (lecture_title == '') {
            swal("Please fill the field", {
                icon: "warning",
            });
        } else {
            $.ajax({
                type: 'POST',
                url: base_url + 'lesson/updateLecture',
                data: {
                    lecture_id: id,
                    lecture_title: lecture_title
                },
                dataType: 'json',
                success: function (res) {
                    if (res.msg == 'success') {
                        swal("The lecture has been successfully updated!", {
                            icon: "success",
                        });
                        $('.form-edit-lecture-' + id).remove();
                        $('#lectureRecord' + id + ' .lecture_title').html(lecture_title);
                        $('.editLectureBtn' + id).prop('disabled', false);
                    }
                }
            });
        }
    })

    $('#cancelLectureBtn' + id).click(function () {
        $('#lectureRecord' + id + ' .lecture_title').html(old_lecture_title);
        $('.form-edit-lecture-' + id).remove();
        $('.editLectureBtn' + id).prop('disabled', false);
    })

}


function deleteLecture(id, section_id) {
    swal({
        title: "Are you sure?",
        text: "Once deleted, you will not be able to recover this lecture!",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((willDelete) => {
            if (willDelete) {

                $.ajax({
                    type: 'POST',
                    url: base_url + 'lesson/deleteLecture/' + id,
                    dataType: 'json',
                    success: function (res) {
                        if (res.msg == 'success') {
                            swal("The lecture has been successfully deleted!", {
                                icon: "success",
                            });
                            loadTableLecture(section_id);
                        }
                    }
                });

            }
        });
}


function startUpdateUploadFile(id, file) {
    statusUpload = 'update';
    fileType = file;
    var progressContent = `  
        <tr class="rowprogress`+ id + `" style="display: none;">
            <td colspan="3">      
                <div class="progress progress`+ id + `">
                    <div class="progress-bar" id="progress-bar`+ id + `" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width:0%">
                        <span id="status`+ id + `"></span>
                    </div>
                </div>
            </td>
        </tr>
    `;

    var selector = $('#lectureRecord' + id);

    $(progressContent).insertAfter(selector);
    selected_id = id;


    function uploadUpdateFile() {
        var file = document.getElementById("file_upload" + id).files[0];
        var formdata = new FormData();
        formdata.append("id", id);
        formdata.append("file_upload" + id, file);
        formdata.append("fileType", fileType);
        formdata.append("type", 'local');
        var ajax = new XMLHttpRequest();
        ajax.responseType = 'json';
        ajax.upload.addEventListener("progress", progressUpload, false);
        ajax.open('POST', base_url + 'lesson/uploadFile', true);
        ajax.onload = function () {
            var jsonResponse = ajax.response;
            if (jsonResponse.msg == 'success') {
                if (fileType == 'doc') {
                    toastr.success('Your document has been uploaded!');
                } else {
                    toastr.success('Your video has been uploaded!');
                }
                getSectionUpdateDetail(id);
            } else {
                toastr.error(jsonResponse.msg);
            }
        };
        ajax.send(formdata);
    }


    function progressUpload(event) {
        var percent = (event.loaded / event.total) * 100;
        document.getElementById("progress-bar" + id).style.width = Math.round(percent) + '%';
        document.getElementById("status" + id).innerHTML = Math.round(percent) + "% Completed";
        if (event.loaded == event.total) {
            setTimeout(function () {
                if (statusUpload == 'update') {
                    $('.rowprogress' + id).remove();
                }
            }, 200);
        }
    }


    $('#file_upload' + id).click();

    $(document).on('change', '#file_upload' + id, function () {

        $('.rowprogress' + id).show();
        if (statusUpload == 'update') {
            uploadUpdateFile();
        }

    });

}


function getSectionUpdateDetail(id) {
    $.ajax({
        type: 'GET',
        url: base_url + 'lesson/getSectionDetail/' + id,
        dataType: 'json',
        success: function (res) {
            var data = res.data_lecture;

            if (data.uploaded_video != '' && data.uploaded_video_type == 'local') {
                var moduleVideo = `
                    <li><a style="cursor: pointer;" href="` + base_url + `/uploaded_file/video/` + data.uploaded_video + `" class="play-video uploaded_video` + data.id + `"><i class="picons-thin-icon-thin-0158_arrow_next_right mr-2"></i> Play Video</a></li>
                    <li class="divider"></li>
                    <li><a style="cursor: pointer;" onClick="studentViewed('`+ data.lecture_title + `', ` + data.id + `, 'video')" ><i class="picons-thin-icon-thin-0704_users_profile_group_couple_man_woman mr-2"></i> Viewer (` + data.total_student_viewed + `)</a></li>
                `;
                $('.btn_uploaded_video_' + id).attr('style', 'display: inline');
                $('.list_uploaded_video_' + id).html(moduleVideo);
            } else if (data.uploaded_video != '' && data.uploaded_video_type == 'embed') {
                var moduleVideo = `
                    <li><a style="cursor: pointer;" href="` + data.uploaded_video + `" class="play-video uploaded_video` + data.id + `"><i class="picons-thin-icon-thin-0158_arrow_next_right mr-2"></i> Play Video</a></li>
                    <li class="divider"></li>
                    <li><a style="cursor: pointer;" onClick="studentViewed('`+ data.lecture_title + `', ` + data.id + `, 'video')" ><i class="picons-thin-icon-thin-0704_users_profile_group_couple_man_woman mr-2"></i> Viewer (` + data.total_student_viewed + `)</a></li>
                `;
                $('.btn_uploaded_video_' + id).attr('style', 'display: inline');
                $('.list_uploaded_video_' + id).html(moduleVideo);
            } else if (data.uploaded_video != '' && data.uploaded_video_type == 'gdrive') {
                var moduleVideo = `
                    <li><a style="cursor: pointer;" href="https://drive.google.com/file/d/` + data.uploaded_video + `/preview" class="play-video uploaded_video` + data.id + `"><i class="picons-thin-icon-thin-0158_arrow_next_right mr-2"></i> Play Video</a></li>
                    <li class="divider"></li>
                    <li><a style="cursor: pointer;" onClick="studentViewed('`+ data.lecture_title + `', ` + data.id + `, 'video')" ><i class="picons-thin-icon-thin-0704_users_profile_group_couple_man_woman mr-2"></i> Viewer (` + data.total_student_viewed + `)</a></li>
                `;
                $('.btn_uploaded_video_' + id).attr('style', 'display: inline');
                $('.list_uploaded_video_' + id).html(moduleVideo);
            }

            if (data.uploaded_doc != '') {
                if (data.uploaded_doc_type == 'gdrive') {
                    var content = `<li class="dropdown-header">Open document in...</li>
                                <li>
                                    <a style="cursor: pointer;" href="https://drive.google.com/file/d/`+ data.uploaded_doc + `/preview" target="_blank"><i class="fa fa-google mr-2"></i> Google Doc</a>
                                </li>                                                                
                                <li class="divider"></li>                                                                
                                <li>
                                    <a style="cursor: pointer;" href="https://drive.google.com/file/d/` + data.uploaded_doc + `/edit" target="_blank"><i class="fa fa-download mr-2"></i> Download</a>
                                </li>`;

                    $('.link_document_' + id).html(content);
                    $('.uploaded_doc_' + id).attr('style', 'display:inline');
                } else {
                    var content = `<li class="dropdown-header">Open document in...</li>
                                    <li>
                                        <a style="cursor: pointer;" href="https://docs.google.com/gview?url=`+ base_url + `uploaded_file/doc/` + data.uploaded_doc + `&embedded=true" target="_blank"><i class="fa fa-google mr-2"></i> Google Doc</a>
                                    </li>
                                    <li>
                                        <a style="cursor: pointer;" href="https://view.officeapps.live.com/op/view.aspx?src=`+ base_url + `uploaded_file/doc/` + data.uploaded_doc + `" target="_blank"><i class="fa fa-windows mr-2"></i> Ms. Office Online</a>
                                    </li>                                                                
                                    <li class="divider"></li>                                                                
                                    <li>
                                        <a style="cursor: pointer;" href="` + base_url + `uploaded_file/doc/` + data.uploaded_doc + `" target="_blank"><i class="fa fa-download mr-2"></i> Download</a>
                                    </li>`;
                    $('.link_document_' + id).html(content);
                    $('.uploaded_doc_' + id).attr('style', 'display:inline');
                }
            }

            if (data.worksheet_id != 0) {
                $('.assessment' + id).attr('href', base_url + 'profile/worksheet/' + data.worksheet_id).show();
            }


            $('.play-video').magnificPopup({
                type: 'iframe',
                midClick: true
            });

        }
    });
}


function entryUpdateEmbedVideo(id) {
    var entryContent = `
                <tr id="entryUpdateEmbedVideo`+ id + `" >
                    <td colspan='3'>
                        <div class="form-inline p-2">
                            <div class="form-group mb-2" style="width: 100%">
                                <input type="text" class="form-control input_style1_black subsection" id="embed_video`+ id + `"
                                    placeholder="Copy link youtube or vimeo here..." style="width: 50%" />
                                <a style="cursor: pointer; text-decoration: none;"
                                    class="text-success-active fs26 ml-2 updateEmbedVideo`+ id + `"><i
                                        class="fa fa-check-circle-o"></i></a>
                                <a style="cursor: pointer; text-decoration: none;"
                                    class="text-danger-active fs26 ml-2 cancelUpdateEmbedVideo`+ id + `"><i
                                        class="fa fa-times-circle-o"></i></a>
                                <span class="text-danger fs20 ml-3 msg-embed"></span>
                            </div>
                        </div>
                    </td>
            </tr>
                `;

    var selector = $('#lectureRecord' + id);

    $(entryContent).insertAfter(selector);


    $('.updateEmbedVideo' + id).click(function (e) {
        e.preventDefault();
        if ($('#embed_video' + id).val() == '') {
            swal("Please fill the field", {
                icon: "warning",
            });
        } else {
            $.ajax({
                type: 'POST',
                url: base_url + 'lesson/updateVideo',
                data: {
                    id: id,
                    uploaded_video: $('#embed_video' + id).val(),
                    type: 'embed'
                },
                dataType: 'json',
                success: function (res) {
                    if (res.msg == 'success') {
                        $('#entryUpdateEmbedVideo' + id).remove();
                        toastr.success('Link has been saved');
                        getSectionUpdateDetail(id);
                    }
                }
            });
        }
    });


    $('.cancelUpdateEmbedVideo' + id).click(function (e) {
        e.preventDefault();
        $('#entryUpdateEmbedVideo' + id).remove();
    })

}


function addAssessment(id, subject, status) {
    $('#lecture_id').val(id);
    $('#assessment_status').val(status);

    getWorksheetList($('#worksheet_id'), subject);
}


$(document).on('click', '#saveAssessmentBtn', function () {

    var worksheet_id = $('#worksheet_id').val();

    if (worksheet_id == '') {
        swal("Please, choose your worksheet!", {
            icon: "warning",
        });
    } else {

        $.ajax({
            type: 'POST',
            url: base_url + 'lesson/updateAssessment',
            data: {
                id: $('#lecture_id').val(),
                worksheet_id: worksheet_id,
                worksheet_status: $('#assessment_status').val()
            },
            dataType: 'json',
            success: function (res) {
                if (res.msg == 'success') {
                    if ($('#assessment_status').val() == 'new') {
                        swal("Assessment has been added!", {
                            icon: "success",
                        });
                        getSectionDetail($('#lecture_id').val());
                        $('#modalAssessment').modal('toggle');
                    } else {
                        swal("Assessment has been added!", {
                            icon: "success",
                        });
                        getSectionUpdateDetail($('#lecture_id').val());
                        $('#modalAssessment').modal('toggle');
                    }
                }
            }
        });

    }


})


function playVideo(title, source, type, ext) {

    $('#modalPlayVideo').modal('toggle');

    $('.lecture-title').html(title);

    var videoContent;

    if (type == 'embed') {

        videoContent = `
        <iframe  
            src="`+ source + `" 
            frameborder="0" 
            allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" style="width:100%; height: 500px;"  
            allowfullscreen>
        </iframe>
        `;

        $('.panel-play-video').html(videoContent);

    } else if (type == 'local') {

        videoContent = `
        <video autoplay loop controls>
            <source src="`+ base_url + `uploaded_file/video/` + source + `" type="video/` + ext + `">
        </video>
        `;

        $('.panel-play-video').html(videoContent);

    }

}


function addAssignment() {
    if ($('.assign_lesson_edit').html().length > 0) {
        $('.assign_lesson_edit').children().appendTo('.assign_lesson_new');
    }

    $('.hidden_assigned_student').remove();
}


function editAssignment(lesson_id) {

    if ($('.assign_lesson_new').html().length > 0) {
        $('.assign_lesson_new').children().appendTo('.assign_lesson_edit');
    }

    $('.hidden_assigned_student').remove();

    $('.list-lesson').fadeOut(250);
    $('.form-edit-assignment').fadeIn(500);

    $('#deassign_student_list').html(`<li class="list-group-item question_text helper_text">No students</li>`);
    $('#assigned_student_list').html(`<li class="list-group-item question_text helper_text">No assigned student yet</li>`);

    $('#lesson_id').val(lesson_id);

    $.ajax({
        type: 'GET',
        url: base_url + 'lesson/getstudentassignment/' + lesson_id,
        dataType: 'json',
        success: function (res) {
            var list_student = ``;
            var list_student_assigned = ``;
            var data = res.list_student;
            var data1 = res.list_student_assigned;

            if (data.length > 0) {
                for (var x = 0; x < res.list_student.length; x++) {
                    list_student += `<li class="list-group-item question_text student_li">
                                        <span>` + data[x].fullname + ` (` + data[x].level_name + `)</span>
                                        <a href="#" id="` + data[x].student_id + `" class="btn btn-custom btn-no-margin pull-right assign_student">Assign</a>
                                    </li>`;
                }

                $('#deassign_student_list').html(list_student);
            }

            if (data1.length > 0) {
                for (var x = 0; x < data1.length; x++) {
                    list_student_assigned += `<li class="list-group-item question_text student_li">
                                        <span>` + data1[x].fullname + ` (` + data1[x].level_name + `)</span>
                                        <a href="#" id="` + data1[x].student_id + `" class="btn btn-danger btn-no-margin pull-right deassign_student">Remove</a>
                                    </li>`;

                    $('#assign_lesson_form').append(`<input type="hidden" class="hidden_assigned_student" name="assigned_students[]" value="` + data1[x].student_id + `" id="hidden_assigned_student_` + data1[x].student_id + `">`);
                }

                $('#assigned_student_list').html(list_student_assigned);
            }

        }
    });


}


function studentViewed(lecture_title, lecture_id, module_type) {
    $('.lecture-title').html(lecture_title);
    $('#modalStudentList').modal('toggle');

    $.ajax({
        type: 'GET',
        url: base_url + 'lesson/getStudentViewed/' + lecture_id,
        dataType: 'json',
        success: function (res) {
            var data = res;
            console.log(data);
            var studentList = ``;

            for (i = 0; i < data.length; i++) {
                studentList += `
                    <tr>
                        <td>`+ (i + 1) + `</td>
                        <td><img src="`+ base_url + `img/profile/user_placeholder.jpg" style="border-radius: 30px; height: 40px;"></td>
                        <td>`+ data[i].fullname + `</td>
                        <td>`+ data[i].date_viewed + `</td>
                    </tr>
                `;
            }

            $('.table-student tbody').html(studentList);
            $('.table-student').dataTable();
        }
    });

}


var gdriveType = "";
var gdriveFileType = "";
var updateFileGDrive = false;

// The Browser API key obtained from the Google API Console.
// Replace with your own Browser API key, or your own key.
var developerKey = 'AIzaSyDl09TpbtVZxJ2rCX-ebtprJVIZ8Blx8DQ';
// var developerKey = 'AIzaSyBvO6ebKQZ-QA7_3Fgm3vROFrBlveNd88c';

// The Client ID obtained from the Google API Console. Replace with your own Client ID.
var clientId = "970984330576-fm5d8p2d1dffksc2v8sqa6e6ons5ho8r.apps.googleusercontent.com"
// var clientId = "600974188866-d7473159cmo2ofoeu49881t0ctslnhps.apps.googleusercontent.com";

// Replace with your own project number from console.developers.google.com.
// See "Project number" under "IAM & Admin" > "Settings"
var appId = "970984330576";
// var appId = "600974188866";

// Scope to use to access user's Drive items.
var scope = ['https://www.googleapis.com/auth/drive.file'];

var pickerApiLoaded = false;
var oauthToken;

// Use the Google API Loader script to load the google.picker script.
function loadPicker() {
    gapi.load('auth', {
        'callback': onAuthApiLoad
    });
    gapi.load('picker', {
        'callback': onPickerApiLoad
    });
}

function onAuthApiLoad() {
    window.gapi.auth.authorize({
        'client_id': clientId,
        'scope': scope,
        'immediate': false
    },
        handleAuthResult);
}

function onPickerApiLoad() {
    pickerApiLoaded = true;
    createPicker();
}

function handleAuthResult(authResult) {
    if (authResult && !authResult.error) {
        oauthToken = authResult.access_token;
        createPicker();
    }
}

// Create and render a Picker object for searching images.
function createPicker() {
    if (pickerApiLoaded && oauthToken) {
        if (gdriveType == 'video') {
            var view = new google.picker.View(google.picker.ViewId.DOCS);
            view.setMimeTypes("video/mp4,video/mpeg,video/mkv,video/flv");
            var picker = new google.picker.PickerBuilder()
                .enableFeature(google.picker.Feature.NAV_HIDDEN)
                .setAppId(appId)
                .setOAuthToken(oauthToken)
                .addView(view)
                .addView(new google.picker.DocsUploadView())
                .setLocale('en')
                .setDeveloperKey(developerKey)
                .setCallback(pickerCallback)
                .build();
        } else {
            var picker = new google.picker.PickerBuilder()
                .addViewGroup(
                    new google.picker.ViewGroup(google.picker.ViewId.DOCS).
                        addView(google.picker.ViewId.DOCUMENTS).
                        addView(google.picker.ViewId.SPREADSHEETS).
                        addView(google.picker.ViewId.PRESENTATIONS).
                        addView(google.picker.ViewId.PDFS))
                .enableFeature(google.picker.Feature.NAV_HIDDEN)
                .setAppId(appId)
                .setOAuthToken(oauthToken)
                .addView(new google.picker.DocsUploadView())
                .setLocale('en')
                .setDeveloperKey(developerKey)
                .setCallback(pickerCallback)
                .build();
        }
        picker.setVisible(true);
    }
}

// A simple callback implementation.
function pickerCallback(data) {
    if (data.action == google.picker.Action.PICKED) {
        var fileId = data.docs[0].id;

        if (gdriveType == 'video') {

            $.ajax({
                type: 'POST',
                url: base_url + 'lesson/updateVideo',
                data: {
                    id: $('#lecture_id').val(),
                    uploaded_video: fileId,
                    type: 'gdrive'
                },
                dataType: 'json',
                success: function (res) {
                    if (res.msg == 'success') {
                        toastr.success('Video has been saved');
                        if (updateFileGDrive == true) {
                            getSectionUpdateDetail($('#lecture_id').val());
                        } else {
                            getSectionDetail($('#lecture_id').val());
                        }
                        Picker.setVisible(false);
                    }
                }
            });

        } else {

            $.ajax({
                type: 'POST',
                url: base_url + 'lesson/uploadFile',
                data: {
                    id: $('#lecture_id').val(),
                    uploaded_doc: fileId,
                    fileType: 'doc',
                    type: 'gdrive'
                },
                dataType: 'json',
                success: function (res) {
                    if (res.msg == 'success') {
                        toastr.success('Document has been saved');
                        if (updateFileGDrive == true) {
                            getSectionUpdateDetail($('#lecture_id').val());
                        } else {
                            getSectionDetail($('#lecture_id').val());
                        }
                        Picker.setVisible(false);
                    }
                }
            });

        }

    }
}


function showPickerDialog(id, type, updateStatus) {
    gdriveType = type;
    updateFileGDrive = updateStatus;
    $('#lecture_id').val(id);
    loadPicker();
}


