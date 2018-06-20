function moedaParaNumero(valor){
    return isNaN(valor) == false ? parseFloat(valor) :   parseFloat(valor.replace("R$","").replace(".","").replace(",","."));
}

function numeroParaMoeda(n, c, d, t){
    c = isNaN(c = Math.abs(c)) ? 2 : c, d = d == undefined ? "," : d, t = t == undefined ? "." : t, s = n < 0 ? "-" : "", i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
}

function getHighIndex(selector) {
    if (!selector) {
        selector = "*";
    }

    var index = 0;

    $(selector).each(function () {
        var atual = parseInt($(this).css("zIndex"), 10);
        if (atual > index) {
            index = atual;
        }
    });

    return index;
}

function mvc_post_json_ajax_var(controller, action, vars) {
  // MVC =========================
  return $.parseJSON($.ajax({
    type: "POST",
    url: HOME_URL + controller + '/' + action,
    data: vars,
    dataType: 'json',
    global: false,
    async: false,
    beforeSend: function () {
        /*show_loader();*/
    },
    complete: function () {
        /*hide_loader();
        setTimeout("obj_jquerys();", 250);*/
    },
    success: function (data) {
        return data;
    }
  }).responseText);
  // =============================
}

function fncTelaUpdateBd(scriptPath){
  $.ajax({
    type: "POST",
    url: HOME_URL + 'Start/jsonHtmlUpdateBd',
    data: 'scriptPath=' + scriptPath,
    dataType: 'json',
    success: function (ret) {
      var html = ret.html;
      confirmBootbox(html, function(){
        $("#dvLoaderBarUpdateDb").html( getHtmlLoader() );
        var retJson = mvc_post_json_ajax_var('Start', 'jsonUpdateDbScript', 'scriptPath=' + scriptPath);

        if(retJson.erro){
          $.gritter.add({
  					title: 'Alerta',
  					text: retJson.msg,
  				});
          var maxZindex = getHighIndex();
          $("#gritter-notice-wrapper").css({'z-index':maxZindex + 5});

          return false;
        } else {
          $("#dvLoaderBarUpdateDb").html(retJson.msg);
          setTimeout(" $('.bootbox .modal-dialog .btn-danger').click() ", 1500);
          return false;
        }
      });
      setTimeout("loadObjects()", 750);
    }
  });
}

// botoes da lista
$(document).on('click', '.dynatableLink', function(){
	var url = $(this).data('url');

	var currentUrl = document.location.href;
	var arrUrl     = currentUrl.split('?');
	if (typeof arrUrl[1] !== 'undefined') {
		url = url + '?' + arrUrl[1];
	}

	document.location.href = url;
});
// ===============

// Relatorios ====
$(document).on('change', '#frmRelatorios #cbRelatorios', function(){
  var objDvRet  = $('#dvOpenRelatorio');
  var stringRel = $(this).val();

  if(stringRel == ''){
    objDvRet.html('');
  } else {
    var splitRel   = stringRel.split('@');
    var controller = splitRel[0];
    var action     = splitRel[1];

    $.ajax({
      type: "POST",
      url: HOME_URL + controller + '/' + action,
      data: '',
  		success: function (ret) {
  			objDvRet.html(ret);
        setTimeout("loadObjects()", 350);
      }
    });
  }
});

$(document).on('click', '#postRelFluxoCx', function(){
  var frmVariaveis = $("#frmInfoRelFluxoCx").serialize();
  var dvRet        = $("#dvPostRelFluxoCx");

  $.ajax({
    type: "POST",
    url: HOME_URL + 'Relatorio/postRelFluxoCx',
    data: frmVariaveis,
    beforeSend: function(){
      dvRet.html(getHtmlLoader());
    },
    success: function (ret) {
      dvRet.html(ret);
      setTimeout("loadObjects()", 350);
    }
  });
});

$(document).on('click', 'a#opnDetRelFluxoCx', function(){
  var tipo = $(this).data("tipo");
  var dia  = $(this).data("dia");

  $.ajax({
    type: "POST",
    url: HOME_URL + 'Relatorio/jsonDetRelFluxoCx',
    data: 'tipo=' + tipo + '&dia=' + dia,
		dataType: 'json',
		success: function (ret) {
			var erro = ret.erro;
			var msg  = ret.msg;
      var html = ret.html;

			if(erro){
				$.gritter.add({
					title: 'Alerta',
					text: msg,
				});
			} else {
				openBootbox(html, false);
			}
    }
  });
});
// ===============

// Tb_Funcionario_Escala
function fncEditarEscala(fueId, calendarClick){
  calendarClick = typeof calendarClick !== 'undefined' ? calendarClick : false;

  $.ajax({
    type: "POST",
    url: HOME_URL + 'FuncionarioEscala/jsonHtmlEditEscala',
    data: 'fueId=' + fueId,
    dataType: 'json',
    success: function (ret) {
      if(ret.erro){
        $.gritter.add({
          title: 'Alerta',
          text: ret.msg,
        });
        var maxZindex = getHighIndex();
        $("#gritter-notice-wrapper").css({'z-index':maxZindex + 5});
      } else {
        var html = ret.html;
        confirmBootbox(html, function(){
          var variaveis = $("#frmJsonAddFuncEscala").serialize();
          var retJson   = mvc_post_json_ajax_var('FuncionarioEscala', 'jsonEditFuncEscala', variaveis);

          if(retJson.erro){
            $.gritter.add({
    					title: 'Alerta',
    					text: retJson.msg,
    				});
            var maxZindex = getHighIndex();
            $("#gritter-notice-wrapper").css({'z-index':maxZindex + 5});

            return false;
          } else {
            if(calendarClick){
              document.location.href = HOME_URL + 'Funcionario/escala';
            } else {
              $("#frmShowEscalaFunc #btnShowEscalaFunc").click();
            }
          }
        });
        setTimeout("loadObjects()", 750);
      }
    }
  });
}

$(document).on('click', '#dvRetPostShowEscalaFunc .TbFuncionarioEscala_ajax_deletar', function(){
	var fueId = $(this).data("id");
	var html  = 'Gostaria de deletar essa Escala ' + fueId + '?';

	confirmBootbox(html, function(){
    $.ajax({
      type: "POST",
      url: HOME_URL + 'FuncionarioEscala/jsonDelEscala',
      data: 'fueId=' + fueId,
  		dataType: 'json',
  		success: function (ret) {
  			var erro            = ret.erro;
  			var msg             = ret.msg;

  			if(erro){
  				$.gritter.add({
  					title: 'Alerta',
  					text: msg,
  				});
  			} else {
          $("#frmShowEscalaFunc #btnShowEscalaFunc").click();
  			}
      }
    });
	});
});

$(document).on('click', '#dvRetPostShowEscalaFunc .TbFuncionarioEscala_ajax_alterar', function(){
	var fueId = $(this).data("id");
  fncEditarEscala(fueId);
});

$(document).on('click', '#dvRetPostShowEscalaFunc .TbFuncionarioEscala_ajax_add', function(){
	var funId = $("#frmShowEscalaFunc #ge_funId").val();

  $.ajax({
    type: "POST",
    url: HOME_URL + 'FuncionarioEscala/jsonHtmlAddEscala',
    data: 'funId=' + funId,
    dataType: 'json',
    success: function (ret) {
      if(ret.erro){
        $.gritter.add({
          title: 'Alerta',
          text: ret.msg,
        });
        var maxZindex = getHighIndex();
        $("#gritter-notice-wrapper").css({'z-index':maxZindex + 5});
      } else {
        var html = ret.html;
        confirmBootbox(html, function(){
          var variaveis = $("#frmJsonAddFuncEscala").serialize();
          var retJson   = mvc_post_json_ajax_var('FuncionarioEscala', 'jsonAddFuncEscala', variaveis);

          if(retJson.erro){
            $.gritter.add({
    					title: 'Alerta',
    					text: retJson.msg,
    				});
            var maxZindex = getHighIndex();
            $("#gritter-notice-wrapper").css({'z-index':maxZindex + 5});

            return false;
          } else {
            $("#frmShowEscalaFunc #btnShowEscalaFunc").click();
          }
        });
        setTimeout("loadObjects()", 750);
      }
    }
  });
});
// =====================

// Tb_Cont_Receber
$(document).on('click', '#btnJsonAddContaReceb', function(){
  $.ajax({
    type: "POST",
    url: HOME_URL + 'ContaReceber/jsonHtmlAddConta',
    data: '',
    dataType: 'json',
    success: function (ret) {
      var html = ret.html;
      confirmBootbox(html, function(){
        var variaveis = $("#frmJsonAddContaReceb").serialize();
        var retJson   = mvc_post_json_ajax_var('ContaReceber', 'jsonAddContaReceb', variaveis);

        if(retJson.erro){
          $.gritter.add({
  					title: 'Alerta',
  					text: retJson.msg,
  				});
          var maxZindex = getHighIndex();
          $("#gritter-notice-wrapper").css({'z-index':maxZindex + 5});

          return false;
        } else {
          $("#btnFiltrarRecebimentos").click();
        }
      });
      setTimeout("loadObjects()", 750);
    }
  });
});

$(document).on('click', '#dvHtmlContaRecebTable .TbContReceber_ajax_alterar', function(){
	var ctrId = $(this).data("id");

  $.ajax({
    type: "POST",
    url: HOME_URL + 'ContaReceber/jsonHtmlEditConta',
    data: 'ctrId=' + ctrId,
    dataType: 'json',
    success: function (ret) {
      if(ret.erro){
        $.gritter.add({
          title: 'Alerta',
          text: ret.msg,
        });
        var maxZindex = getHighIndex();
        $("#gritter-notice-wrapper").css({'z-index':maxZindex + 5});
      } else {
        var html = ret.html;
        confirmBootbox(html, function(){
          var variaveis = $("#frmJsonAddContaReceb").serialize();
          var retJson   = mvc_post_json_ajax_var('ContaReceber', 'jsonEditContaReceb', variaveis);

          if(retJson.erro){
            $.gritter.add({
    					title: 'Alerta',
    					text: retJson.msg,
    				});
            var maxZindex = getHighIndex();
            $("#gritter-notice-wrapper").css({'z-index':maxZindex + 5});

            return false;
          } else {
            $("#btnFiltrarRecebimentos").click();
          }
        });
        setTimeout("loadObjects()", 750);
      }
    }
  });
});

$(document).on('click', '#dvHtmlContaRecebTable .TbContReceber_ajax_deletar_v2', function(){
	var ctrId = $(this).data("id");
	var html  = 'Gostaria de deletar a parcela ' + ctrId + '?';

	confirmBootbox(html, function(){
    $.ajax({
      type: "POST",
      url: HOME_URL + 'ContaReceber/jsonDelContaReceber',
      data: 'ctrId=' + ctrId,
  		dataType: 'json',
  		success: function (ret) {
  			var erro                = ret.erro;
  			var msg                 = ret.msg;
  			var htmlContaRecebTable = ret.htmlContaRecebTable;

  			if(erro){
  				$.gritter.add({
  					title: 'Alerta',
  					text: msg,
  				});
  			} else {
          $("#frmFiltrosRecebimentos #btnFiltrarRecebimentos").click();
  			}
      }
    });
	});
});

$(document).on('click', '#frmFiltrosRecebimentos #btnFiltrarRecebimentos', function(){
	var variaveis = $('#frmFiltrosRecebimentos').serialize();

  $.ajax({
    type: "POST",
    url: HOME_URL + 'ContaReceber/jsonHtmlContasReceber',
    data: variaveis,
		dataType: 'json',
    beforeSend: function(){
      var htmlLoader = getHtmlLoader();
      $("#dvHtmlContaRecebTable").html(htmlLoader);
    },
		success: function (ret) {
			var erro            = ret.erro;
			var msg             = ret.msg;
			var htmlContasReceb = ret.htmlContasReceb;

			if(erro){
				$.gritter.add({
					title: 'Alerta',
					text: msg,
				});
			} else {
        $("#dvHtmlContaRecebTable").html(htmlContasReceb);
        setTimeout("loadObjects();", 500);
			}
    }
  });
});
// ===============

// Tb_Cont_Pagar
$(document).on('click', '#frmFiltrosPagamentos #btnFiltrarPagamentos', function(){
	var variaveis = $('#frmFiltrosPagamentos').serialize();

  $.ajax({
    type: "POST",
    url: HOME_URL + 'ContaPagar/jsonHtmlContasPagar',
    data: variaveis,
		dataType: 'json',
    beforeSend: function(){
      var htmlLoader = getHtmlLoader();
      $("#dvhtmlContasPagarTable").html(htmlLoader);
    },
		success: function (ret) {
			var erro            = ret.erro;
			var msg             = ret.msg;
			var htmlContasPagar = ret.htmlContasPagar;

			if(erro){
				$.gritter.add({
					title: 'Alerta',
					text: msg,
				});
			} else {
        $("#dvhtmlContasPagarTable").html(htmlContasPagar);
        setTimeout("loadObjects();", 500);
			}
    }
  });
});

$(document).on('click', '#btnJsonAddContaPagar', function(){
  $.ajax({
    type: "POST",
    url: HOME_URL + 'ContaPagar/jsonHtmlAddConta',
    data: '',
    dataType: 'json',
    success: function (ret) {
      var html = ret.html;
      confirmBootbox(html, function(){
        var variaveis = $("#frmJsonAddContaPagar").serialize();
        var retJson   = mvc_post_json_ajax_var('ContaPagar', 'jsonAddContaPagar', variaveis);

        if(retJson.erro){
          $.gritter.add({
  					title: 'Alerta',
  					text: retJson.msg,
  				});
          var maxZindex = getHighIndex();
          $("#gritter-notice-wrapper").css({'z-index':maxZindex + 5});

          return false;
        } else {
          $("#btnFiltrarPagamentos").click();
        }
      });
      setTimeout("loadObjects()", 750);
    }
  });
});

$(document).on('click', '#dvhtmlContasPagarTable .TbContPagar_ajax_deletar', function(){
	var ctpId = $(this).data("id");
	var html  = 'Gostaria de deletar o pagamento ' + ctpId + '?';

	confirmBootbox(html, function(){
    $.ajax({
      type: "POST",
      url: HOME_URL + 'ContaPagar/jsonDelContaPagar',
      data: 'ctpId=' + ctpId,
  		dataType: 'json',
  		success: function (ret) {
  			var erro                = ret.erro;
  			var msg                 = ret.msg;
  			var htmlContaPagarTable = ret.htmlContaPagarTable;

  			if(erro){
  				$.gritter.add({
  					title: 'Alerta',
  					text: msg,
  				});
  			} else {
          $("#frmFiltrosPagamentos #btnFiltrarPagamentos").click();
  			}
      }
    });
	});
});

$(document).on('click', '#dvhtmlContasPagarTable .TbContReceber_ajax_alterar', function(){
	var ctpId = $(this).data("id");

  $.ajax({
    type: "POST",
    url: HOME_URL + 'ContaPagar/jsonHtmlEditPagamento',
    data: 'ctpId=' + ctpId,
    dataType: 'json',
    success: function (ret) {
      if(ret.erro){
        $.gritter.add({
          title: 'Alerta',
          text: ret.msg,
        });
        var maxZindex = getHighIndex();
        $("#gritter-notice-wrapper").css({'z-index':maxZindex + 5});
      } else {
        var html = ret.html;
        confirmBootbox(html, function(){
          var variaveis = $("#frmJsonAddContaPagar").serialize();
          var retJson   = mvc_post_json_ajax_var('ContaPagar', 'jsonEditContaPagar', variaveis);

          if(retJson.erro){
            $.gritter.add({
    					title: 'Alerta',
    					text: retJson.msg,
    				});
            var maxZindex = getHighIndex();
            $("#gritter-notice-wrapper").css({'z-index':maxZindex + 5});

            return false;
          } else {
            $("#btnFiltrarPagamentos").click();
          }
        });
        setTimeout("loadObjects()", 750);
      }
    }
  });
});
// =============

// Tb_Cliente
$(document).on('click', '.TbCliente_deletar', function(){
	var cliId = $(this).data("id");
	var html  = 'Gostaria de deletar o cliente ID ' + cliId + '?';

	confirmBootbox(html, function(){
		document.location.href = HOME_URL + 'Cliente/deletar/' + cliId;
	});
});
// ==========

// Tb_Funcionario
$(document).on('click', '.TbFuncionario_deletar', function(){
	var funId = $(this).data("id");
	var html  = 'Gostaria de deletar o funcionario ID ' + funId + '?';

	confirmBootbox(html, function(){
		document.location.href = HOME_URL + 'Funcionario/deletar/' + funId;
	});
});

function openGerEscalas(){
  var retJson = mvc_post_json_ajax_var('Funcionario', 'jsonGerenciarEscalas', '');
  if(retJson.erro){
    $.gritter.add({
      title: 'Alerta',
      text: retJson.msg,
    });
    var maxZindex = getHighIndex();
    $("#gritter-notice-wrapper").css({'z-index':maxZindex + 5});
  } else {
    openBootbox(retJson.html, false, false, false, function(){
      document.location.href = HOME_URL + 'Funcionario/escala';
    });

    setTimeout("loadObjects()", 500);
  }
}

function showEscalaFunc(){
  var params  = $('#frmShowEscalaFunc').serialize();
  var retJson = mvc_post_json_ajax_var('Funcionario', 'jsonPostShowEscalaFunc', params);

  if(retJson.erro){
    $.gritter.add({
      title: 'Alerta',
      text: retJson.msg,
    });
    var maxZindex = getHighIndex();
    $("#gritter-notice-wrapper").css({'z-index':maxZindex + 5});
  } else {
    $("#dvRetPostShowEscalaFunc").html(retJson.html);
    setTimeout("loadObjects()", 500);
  }
}
// ==========

function loadObjects(){
  $('.dynatable').dynatable({
		inputs: {
			paginationPrev: 'Anterior',
			paginationNext: 'Próximo',
			perPageText: 'Exibir: ',
			recordCountText: 'Exibindo ',
			processingText: 'Processando ...',
		}
	});
	$(".mask_cpf").mask("999.999.999-99");
	$(".mask_cep").mask("99.999-999");
  $(".mask_hora").mask("99:99");
	$(".mask_inteiro").numeric();
	$('.mask_moeda').mask("#.##0,00", {reverse: true});

  // date picker
  $('.mask_datepicker').datepicker({
      format: 'dd/mm/yyyy',
      //startDate: '+1d',
  });

  var maxZindex = getHighIndex() + 5;
  $('div.datepicker').css({'z-index':maxZindex});
  // ===========

	//$('.mask_moeda').mask('000.000.000.000.000,00', {reverse: true});

  // === Tooltips === //
	$('.tip').tooltip();
	$('.tip-left').tooltip({ placement: 'left' });
	$('.tip-right').tooltip({ placement: 'right' });
	$('.tip-top').tooltip({ placement: 'top' });
	$('.tip-bottom').tooltip({ placement: 'bottom' });

	// === Search input typeahead === //
	$('#search input[type=text]').typeahead({
		source: ['Dashboard','Form elements','Common Elements','Validation','Wizard','Buttons','Icons','Interface elements','Support','Calendar','Gallery','Reports','Charts','Graphs','Widgets'],
		items: 4
	});
}

function getHtmlLoader(){
  return '<div class="progress progress-striped active"> <div class="bar" style="width: 100%;"></div> </div>';
}

$(document).ready(function(){
	loadObjects();

	// === Sidebar navigation === //
	$('.submenu > a').click(function(e)
	{
		e.preventDefault();
		var submenu = $(this).siblings('ul');
		var li = $(this).parents('li');
		var submenus = $('#sidebar li.submenu ul');
		var submenus_parents = $('#sidebar li.submenu');
		if(li.hasClass('open'))
		{
			if(($(window).width() > 768) || ($(window).width() < 479)) {
				submenu.slideUp();
			} else {
				submenu.fadeOut(250);
			}
			li.removeClass('open');
		} else
		{
			if(($(window).width() > 768) || ($(window).width() < 479)) {
				submenus.slideUp();
				submenu.slideDown();
			} else {
				submenus.fadeOut(250);
				submenu.fadeIn(250);
			}
			submenus_parents.removeClass('open');
			li.addClass('open');
		}
	});

	var ul = $('#sidebar > ul');

	$('#sidebar > a').click(function(e)
	{
		e.preventDefault();
		var sidebar = $('#sidebar');
		if(sidebar.hasClass('open'))
		{
			sidebar.removeClass('open');
			ul.slideUp(250);
		} else
		{
			sidebar.addClass('open');
			ul.slideDown(250);
		}
	});

	// === Resize window related === //
	$(window).resize(function()
	{
		if($(window).width() > 479)
		{
			ul.css({'display':'block'});
			$('#content-header .btn-group').css({width:'auto'});
		}
		if($(window).width() < 479)
		{
			ul.css({'display':'none'});
			fix_position();
		}
		if($(window).width() > 768)
		{
			$('#user-nav > ul').css({width:'auto',margin:'0'});
			$('#content-header .btn-group').css({width:'auto'});
		}
	});

	if($(window).width() < 468)
	{
		ul.css({'display':'none'});
		fix_position();
	}

	if($(window).width() > 479)
	{
		$('#content-header .btn-group').css({width:'auto'});
		ul.css({'display':'block'});
	}

	// === Fixes the position of buttons group in content header and top user navigation === //
	function fix_position()
	{
		var uwidth = $('#user-nav > ul').width();
		$('#user-nav > ul').css({width:uwidth,'margin-left':'-' + uwidth / 2 + 'px'});

		var cwidth = $('#content-header .btn-group').width();
		$('#content-header .btn-group').css({width:cwidth,'margin-left':'-' + uwidth / 2 + 'px'});
	}

	// === Style switcher === //
	$('#style-switcher i').click(function()
	{
		if($(this).hasClass('open'))
		{
			$(this).parent().animate({marginRight:'-=190'});
			$(this).removeClass('open');
		} else
		{
			$(this).parent().animate({marginRight:'+=190'});
			$(this).addClass('open');
		}
		$(this).toggleClass('icon-arrow-left');
		$(this).toggleClass('icon-arrow-right');
	});

	$('#style-switcher a').click(function()
	{
		var style = $(this).attr('href').replace('#','');
		$('.skin-color').attr('href','css/maruti.'+style+'.css');
		$(this).siblings('a').css({'border-color':'transparent'});
		$(this).css({'border-color':'#aaaaaa'});
	});

	$('.lightbox_trigger').click(function(e) {

		e.preventDefault();

		var image_href = $(this).attr("href");

		if ($('#lightbox').length > 0) {

			$('#imgbox').html('<img src="' + image_href + '" /><p><i class="icon-remove icon-white"></i></p>');

			$('#lightbox').slideDown(500);
		}

		else {
			var lightbox =
			'<div id="lightbox" style="display:none;">' +
			'<div id="imgbox"><img src="' + image_href +'" />' +
			'<p><i class="icon-remove icon-white"></i></p>' +
			'</div>' +
			'</div>';

			$('body').append(lightbox);
			$('#lightbox').slideDown(500);
		}

	});


	$('#lightbox').live('click', function() {
		$('#lightbox').hide(200);
	});

});

// bootbox
/**
* Abre o modal Bootbox.
*
* @param {text} response [HTML com o conteudo da modal]
* @param {bool} bold [se TRUE vai colocar um span container no response com style bold]
* @param {text/function} on_open [funcao pra executar qdo o modal abrir][se funcao retornar false o modal nao fecha]
* @param {text/function} on_click [gera um btn OK e adiciona funcao pra executar qdo clicar][se funcao retornar false o modal nao fecha]
* @param {text/function} on_close [funcao pra executar qdo o modal abrir][se funcao retornar false o modal nao fecha]
*/
function openBootbox(response, bold, on_open, on_click, on_close) {
	bold = typeof bold !== 'undefined' ? bold : false;
	on_open = typeof on_open !== 'undefined' ? on_open : false;
	on_click = typeof on_click !== 'undefined' ? on_click : false;
	on_close = typeof on_close !== 'undefined' ? on_close : false;
	response = (bold) ? '<span class="bootbox-confirm">' + response + '</span>' : response;

	var buttons = {};
	if (on_click === false) {
		buttons = {
			btn_fechar: {
				label: "Fechar",
				className: "btn-danger",
				callback: function () {
					if (on_close !== false) {
						on_close();
					}
				}
			}
		};
	} else {
		buttons = {
			btn_ok: {
				label: "OK",
				className: "btn-success",
				callback: function () {
					if (on_click !== false) {
						var ret = on_click();

						// Se o retorno da funcao callback for false nao fecha o bootbox
						if (ret === false)
						return false;

						// Para que as chamadas sem retorno fechem o bootbox
						// Default sempre true
						return true;
					}
				}
			},
			btn_fechar: {
				label: "Fechar",
				className: "btn-danger",
				callback: function () {
					if (on_close !== false) {
						on_close();
					}
				}
			}
		};
	}

	window.bootModal = bootbox.dialog({
		message: response,
		size: 'large',
		locale: 'pt',
		closeButton: 'false',
		onEscape: function () {
			if (on_close !== false) {
				on_close();
			}
		},
		buttons: buttons,
	}).on("shown.bs.modal", function () {
		if (on_open !== false) {
			on_open();
		}
	});
}

function confirmBootbox(html, callback){
	var buttons = {};
	buttons = {
		btn_ok: {
			label: "Confirmar",
			className: "btn-success",
			callback: function () {
				var ret = callback();

				// Se o retorno da funcao callback for false nao fecha o bootbox
				if (ret === false)
				return false;

				// Para que as chamadas sem retorno fechem o bootbox
				// Default sempre true
				return true;
			}
		},
		cancel: {
			label: "Fechar",
			className: "btn-danger",
			callback: function () { }
		}
	};

	bootbox.dialog({
		message: html,
		size: 'large',
		locale: 'pt',
		closeButton: 'false',
		buttons: buttons,
	})
}
// =======
