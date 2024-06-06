$(document).on('submit', '#frmRelDespesasMes', function (e) {
    e.preventDefault();

    let form = $('#frmRelDespesasMes');
    let data = form.serialize();
    let download = form.find('#frmDownload').val();
    let action = form.attr('action');
    let retDiv = $('#dvPostRelDespesasMes');

    if (download == 1) {
        window.open(action + `?${data}`, '_blank').focus();
        return;
    }

    retDiv.html('Carregando ...');
    $.get(action, data).done(function (response) {
        retDiv.html(response);
    });
})