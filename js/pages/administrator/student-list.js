function previewImage(event) {
    var reader = new FileReader();
    var imageField = document.getElementById("preview");
    reader.onload = function () {
        if (reader.readyState == 2) {
            imageField.src = reader.result;
        }
    }
    reader.readAsDataURL(event.target.files[0]);
}


function editProfile(user_id) {
    window.location.href = base_url + 'administrator/student_profile/' + user_id;
}

function editPassword(user_id) {
    window.location.href = base_url + 'administrator/change_student_password/' + user_id;
}

function updateProfile() {
    $.ajax({
        type: 'POST',
        url: base_url + 'Administrator/updateStudentProfile',
        data: new FormData($('#student-profile')[0]),
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
            swal({
                title: "Information!",
                text: "Student profile has been updated!",
                icon: "success",
                button: "OK"
            }).then((isOke) => {
                if (isOke) {
                    window.location.href = base_url + 'administrator/student_list';
                }
            });
        }
    });
}

$(document).ready(function () {

    $('.setActiveStudent').click(function () {
        var user_id = $(this).data('id');
        var status = $(this).data('status');
        if (status == 1) {
            active = 0;
        } else {
            active = 1;
        }
        $.ajax({
            type: 'POST',
            url: base_url + 'Administrator/setActiveStudent',
            data: {
                user_id: user_id,
                active, active
            },
            dataType: 'json',
            success: function (res) {
                if (res.msg == 'success') {
                    if (active == 1) {
                        _status = 'Active';
                    } else {
                        _status = 'Inactive';
                    }
                    $('.status_' + user_id).html(_status);
                    $('#' + user_id).data('status', active);
                }
            }
        });
    });

    $('#student_level').change(function () {
        var level = $(this).val();
        // AJAX request
        $.ajax({
            url: base_url + 'profile/get_school_list',
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

    $('#student-profile').submit(function () {
        return false;
    })

    $('#profile_btn').click(function () {
        var fullname = $('#profile_fullName').val();
        if (fullname == '') {
            $('.lb-fullname').html('Fullname : <b class="text-danger">Please, fill this field</b>');
        } else {
            updateProfile();
        }
    })

    $('.showStudentClassBtn').on('click', function (e) {
        e.preventDefault();
        var user_id = $(this).parent().find('.setActiveStudent').data('id');
        var tbl_row = $(this).parent().parent();
        var nextRow = $(this).parent().parent().next().attr('class');
        console.log(nextRow);
        if (nextRow === 'showStudentClassRecord' || nextRow === 'tbl_row_loading') {
            tbl_row.find('.fa-caret-up').removeClass('fa-caret-up').addClass('fa-caret-down');
            tbl_row.next().remove();
        } else {
            tbl_row.find('.fa-caret-down').removeClass('fa-caret-down').addClass('fa-caret-up');
            tbl_row.after('<tr class="tbl_row_loading"><td colspan="6"><i class="fa fa-spinner"></i></td></tr>');

            $.ajax({
                url: base_url + 'Administrator/getStudentClass/' + user_id,
                type: 'GET',
                dataType: 'json',
                success: function (res) {
                    data = res.data;
                    $('.tbl_row_loading').remove();
                    if (data.class_id.length == 0) {
                        $(tbl_row).after(`
                                    <tr class="showStudentClassRecord">
                                        <td colspan="6">
                                            <table class="table profile_table">
                                                <thead>
                                                    <tr class="info">
                                                        <th>Class Name</th>
                                                        <th>Level</th>
                                                        <th>Subject</th>
                                                        <th>Tutor</th>
                                                        <th>Site</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td colspan="5">Not class yet.</td>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                `);
                    } else {
                        var row = `<tr class="showStudentClassRecord">
                                        <td colspan="6">
                                            <div class="table-responsive">
                                                <table class="table profile_table">
                                                    <thead>
                                                        <tr class="info">
                                                            <th>Class Name</th>
                                                            <th>Level</th>
                                                            <th>Subject</th>
                                                            <th>Tutor</th>
                                                            <th>Site</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                        `;

                        for (i = 0; i < data.class_id.length; i++) {
                            row += `
                                <tr>
                                    <td>`+ data.class_name[i] + `</td>
                                    <td>`+ data.level_name[i] + `</td>
                                    <td>`+ data.subject_name[i] + `</td>
                                    <td>`+ data.tutor_name[i] + `</td>
                                    <td>`+ data.tutor_site[i] + `</td>
                                </tr>
                            `;
                        }
                        row += '</tbody></table></div></td></tr>';
                        $(tbl_row).after(row);

                    }
                    $('.showStudentClassRecord').mouseenter(function () {
                        $(this).css("background-color", "#f6f6f6");
                    }).mouseleave(function () {
                        $(this).css("background-color", "#ffffff");
                    });

                }

            });

        }

    });

    $('.showTutorClassBtn').on('click', function (e) {
        e.preventDefault();
        var user_id = $(this).parent().find('.setActiveTutor').data('id');
        var tbl_row = $(this).parent().parent();
        var nextRow = $(this).parent().parent().next().attr('class');
        console.log(nextRow);
        if (nextRow === 'showTutorClassRecord' || nextRow === 'tbl_row_loading') {
            console.log('a');
            tbl_row.find('.fa-caret-up').removeClass('fa-caret-up').addClass('fa-caret-down');
            tbl_row.next().remove();
        } else {
            console.log('b');
            tbl_row.find('.fa-caret-down').removeClass('fa-caret-down').addClass('fa-caret-up');
            tbl_row.after('<tr class="tbl_row_loading"><td colspan="6"><i class="fa fa-spinner"></i></td></tr>');

            $.ajax({
                url: base_url + 'Administrator/getTutorClass/' + user_id,
                type: 'GET',
                dataType: 'json',
                success: function (res) {
                    data = res.data;
                    console.log(data);
                    $('.tbl_row_loading').remove();
                    if (data.class_id.length == 0) {
                        $(tbl_row).after(`
                                    <tr class="showTutorClassRecord">
                                        <td colspan="6">
                                            <table class="table profile_table">
                                                <thead>
                                                    <tr class="info">
                                                        <th>Class Name</th>
                                                        <th>Level</th>
                                                        <th>Subject</th>
                                                        <th>Student Name</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <td colspan="5">Not class yet.</td>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                `);
                    } else {
                        var row = `<tr class="showTutorClassRecord">
                                        <td colspan="6">
                                            <div class="table-responsive">
                                                <table class="table profile_table">
                                                    <thead>
                                                        <tr class="info">
                                                            <th>Class Name</th>
                                                            <th>Level</th>
                                                            <th>Subject</th>
                                                            <th>Student Name</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                        `;

                        for (i = 0; i < data.student_id.length; i++) {
                            row += `
                                <tr>
                                    <td>`+ data.class_name2[i] + `</td>
                                    <td>`+ data.level_name2[i] + `</td>
                                    <td>`+ data.subject_name2[i] + `</td>
                                    <td>`+ data.student_name[i] + `</td>
                                </tr>
                            `;
                        }
                        row += '</tbody></table></div></td></tr>';
                        $(tbl_row).after(row);

                    }
                    $('.showTutorClassRecord').mouseenter(function () {
                        $(this).css("background-color", "#f6f6f6");
                    }).mouseleave(function () {
                        $(this).css("background-color", "#ffffff");
                    });

                }

            });

        }

    });

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

});