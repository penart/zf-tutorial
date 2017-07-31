
function getNow() {
    return new Date();
}

function formatDate(date) {
    if (date) {
        var dd = date.getDate();
        if (dd < 10) dd = '0' + dd;
        var mm = date.getMonth() + 1;
        if (mm < 10) mm = '0' + mm;
        var yyyy = date.getFullYear();
        return yyyy + '-' + mm + '-' + dd;
    } else {
        return '';
    }
}

function buildCurrencyCourseTable(data) {
    $('#rows').find('tr').remove().end();
    $('#rows').append('<tr><td colspan="5">Загрузка данных ...</td></tr>');
    $('#rowcount').text('0');
    $('#rows').find('tr').remove().end();
    var context = '';
    if (data.rowcount > 0) {
        $('#refresh_time').text(data.rows[0].dt);
        $.each(data.rows, function (index, row) {
            context += '<tr>';
            context += '<td class="col-md-1">' + row.numericCode + '</td>';
            context += '<td class="col-md-1">' + row.charCode + '</td>';
            context += '<td class="col-md-1">' + row.nominal + '</td>';
            context += '<td class="col-md-1">' + row.name + '</td>';
            context += '<td class="col-md-1">' + row.value + '</td>';
            context += '</tr>';
        });
    } else
        context += '<tr><td colspan="5">Нет строк</td></tr>';
    $('#rows').append(context);
    $('#rowcount').text(data.rowcount);
}


function loadCurrencyCourse() {
    var param = { dt: getNow().getTime() };
    $.get('index/load', param, function (data) {
        if (data !== null) {
            buildCurrencyCourseTable(data);
        }
        $('#btn_refresh').prop('disabled', false);
    });
}

function refreshCurrencyCourse() {
    var param = { dt: getNow().getTime() };
    $.get('index/refresh', param, function (data) {
        if (data !== null) {
            if (data.error===true) {
                showMessageBox(data.error_message);
            } else {
                buildCurrencyCourseTable(data);
            }
        }
        $('#btn_refresh').prop('disabled', false);
    });
}


function selectCurrencyCourse() {

    $('#currencyForm').modal('show');
    $('#val_rows').find('tr').remove().end();
    $('#val_rows').append('<tr><td colspan="4">Загрузка данных ...</td></tr>');

    var param = { dt: getNow().getTime() };

    $.get('index/select', param, function (data) {
        if (data !== null) {
            $('#val_rows').find('tr').remove().end();
            var context = '';

            if (data.rowcount > 0) {
                $.each(data.rows, function (index, row) {
                    context += '<tr>';
                    context += '<td class="col-md-1"><input type="checkbox" name="currency" value="'+row.id+'" '+(row.isActive==1 ? 'checked': '')+'></td>';
                    context += '<td class="col-md-1">' + row.numericCode + '</td>';
                    context += '<td class="col-md-1">' + row.charCode + '</td>';
                    context += '<td class="col-md-1">' + row.name + '</td>';
                    context += '</tr>';
                });
            } else
                context += '<tr><td colspan="4">Нет строк</td></tr>';
            $('#val_rows').append(context);
        }
    });
}

function saveCurrencyCourse() {
    var keyPars = '';
    var isActive = 0;

    $('#btn_refresh').prop('disabled',true);
    
    $('input[name="currency"]').each(function() {
        
        if($(this).is(':checked'))
            isActive=1;
        else
            isActive=0;
        
        if (''===keyPars)
            keyPars = this.value+'='+isActive;
        else
            keyPars += ','+this.value+'='+isActive;
        
    });

    var param = { dt: getNow().getTime(), keyPars: keyPars };

    $.get('index/save', param, function (data) {
        
        if (data !== null) {
           buildCurrencyCourseTable(data);
        }
        $('#btn_refresh').prop('disabled', false);
        
    });
}

function showMessageBox(msg) {
    $('#msgBoxText').text(msg);
    $('#msgBoxForm').modal('show');
}

$(document).ready(function () {   
    loadCurrencyCourse();
});
