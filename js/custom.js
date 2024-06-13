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

$(document).on('click', '#frmJsonAddLancamento #btn-nova-linha', function (e) {
    // duplicate tr from table id tblLancamentoDespesa and add it bellow
    let tr = $('#tblLancamentoDespesa tbody tr').first().clone();
    tr.find('input').val('');
    tr.find('select').val('');
    tr.appendTo('#tblLancamentoDespesa tbody');

    // make element id TbLancamentoDespesa_ajax_deletar visible on the new row
    tr.find('.TbLancamentoDespesa_ajax_deletar').css({'display': 'block'});
});

$(document).on('click', '#frmJsonAddLancamento .TbLancamentoDespesa_ajax_deletar', function (e) {
    $(this).closest('tr').remove();
});