$(document).on('click', '.admin-late-in-box', function() {
    var data = $(this).data('latein');
    var html = '';
    var counter = 1;
    $.each(data, function(index, value) {
        var type = value.type;
        if(type=='earlyout'){
            type = '<span class="badge bg-label-warning">Early Out</span>';
        }else{
            type = '<span class="badge bg-label-danger">Late In</span>';
        }
        html += '<tr>' +
            '<td>' + counter++ +'</td>' +
            '<td>' + value.employee + '</td>' +
            '<td>' + value.date + '</td>' +
            '<td>' + type + '</td>' +
            '<td>' + value.punchIn + '</td>' +
            '<td>' + value.punchOut + '</td>' +
            '</tr>';
    });

    $('#admin-late-in-summary').html(html);
});

$(document).on('click', '.admin-half-day-box', function() {
    var data = $(this).data('halfday');
    
    var html = '';
    var counter = 0;
    $.each(data, function(index, value) {
        var type = '<span class="badge bg-label-half-day">Half Day</span>';
        html += '<tr>' +
            '<td>' +
                '<div>'+ ++counter +'.</div>' +
            '</td>' +
            '<td>'+value.employee+'</td>'+
            '<td>' + value.date + '</td>' +
            '<td>' + value.punchIn + '</td>' +
            '<td>' + type + '</td>' +
            '<td>' + value.punchOut + '</td>' +
            '</tr>';
    });

    $('#admin-half-days-summary').html(html);
});

$(document).on('click', '.admin-absent-dates-box', function() {
    var data = $(this).data('absent');
    
    var html = '';
    var counter = 0;
    $.each(data, function(index, value) {
        var type = '<span class="badge bg-label-full-day">Full Day</span>';
        html += '<tr>' +
            '<td>' + counter++ +'</td>' +
            '<td>' + value.employee + '</td>' +
            '<td>' + value.date + '</td>' +
            '<td>' + type + '</td>' +
            '</tr>';
    });

    $('#admin-team-absent-modal-content-body').html(html);
});

$(document).on('click', '.admin-team-summary-box', function() {
    var data = $(this).data('today-summary');
    
    var targeted_modal = $(this).attr('data-bs-target');
    var title = $(this).attr('title');

    $(targeted_modal).find('#modal-label').html(title);

    var html = '';
    var counter = 1;
    $.each(data, function(index, value) {
        var type = value.type;
        if(type=='firsthalf' || type=='lasthalf'){
            type = '<span class="badge bg-label-half-day">Half Day</span>';
        }else if(type=='absent'){
            type = '<span class="badge bg-label-full-day">Absent</span>';
        }else if(type=='earlyout'){
            type = '<span class="badge bg-label-late-in">Early Out</span>';
        }else if(type=='lateIn'){
            type = '<span class="badge bg-label-late-in">Late In</span>';
        }else{
            type = '<span class="badge bg-label-regular">Regular</span>';
        }
        
        html += '<tr>' +
            '<td>' + counter++ +'.</td>' +
            '<td>' + value.employee + '</td>' +
            '<td>' + value.date + '</td>' +
            '<td>' + type + '</td>' +
            '<td>--</td>' + //value.punchIn
            '<td>--</td>' + //value.punchOut
            '</tr>';
    });

    $('#admin-team-summary').html(html);
});