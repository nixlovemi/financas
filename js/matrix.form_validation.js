$(document).ready(function() {

  // $('input[type=checkbox],input[type=radio],input[type=file]').uniform();

  // $('select').select2();

  // Form Validation
	/*
	ESTAVA DANDO ERRO NO integer
	jQuery.validator.addClassRules('validate-text', {
	  required: true,
		minlength: 2
	});
	jQuery.validator.addClassRules('validate-integer', {
	  required: true,
		step: 1
	});*/

	jQuery.validator.addClassRules('validate-required', {
	  required: true
	});
	$(".form-validation").validate({
    errorClass: "help-inline",
    errorElement: "span",
    highlight: function(element, errorClass, validClass) {
      $(element).parents('.control-group').addClass('error');
    },
    unhighlight: function(element, errorClass, validClass) {
      $(element).parents('.control-group').removeClass('error');
      $(element).parents('.control-group').addClass('success');
    }
  });

  /*$("#basic_validate").validate({
    rules: {
      required: {
        required: true
      },
      email: {
        required: true,
        email: true
      },
      date: {
        required: true,
        date: true
      },
      url: {
        required: true,
        url: true
      }
    },
    errorClass: "help-inline",
    errorElement: "span",
    highlight: function(element, errorClass, validClass) {
      $(element).parents('.control-group').addClass('error');
    },
    unhighlight: function(element, errorClass, validClass) {
      $(element).parents('.control-group').removeClass('error');
      $(element).parents('.control-group').addClass('success');
    }
  });

  $("#number_validate").validate({
    rules: {
      min: {
        required: true,
        min: 10
      },
      max: {
        required: true,
        max: 24
      },
      number: {
        required: true,
        number: true
      }
    },
    errorClass: "help-inline",
    errorElement: "span",
    highlight: function(element, errorClass, validClass) {
      $(element).parents('.control-group').addClass('error');
    },
    unhighlight: function(element, errorClass, validClass) {
      $(element).parents('.control-group').removeClass('error');
      $(element).parents('.control-group').addClass('success');
    }
  });

  $("#password_validate").validate({
    rules: {
      pwd: {
        required: true,
        minlength: 6,
        maxlength: 20
      },
      pwd2: {
        required: true,
        minlength: 6,
        maxlength: 20,
        equalTo: "#pwd"
      }
    },
    errorClass: "help-inline",
    errorElement: "span",
    highlight: function(element, errorClass, validClass) {
      $(element).parents('.control-group').addClass('error');
    },
    unhighlight: function(element, errorClass, validClass) {
      $(element).parents('.control-group').removeClass('error');
      $(element).parents('.control-group').addClass('success');
    }
  });*/

  jQuery.extend(jQuery.validator.messages, {
    required: "Este campo &eacute; requerido.",
    remote: "Por favor, corrija este campo.",
    email: "Por favor, forne&ccedil;a um endere&ccedil;o eletr&ocirc;nico v&aacute;lido.",
    url: "Por favor, forne&ccedil;a uma URL v&aacute;lida.",
    date: "Por favor, forne&ccedil;a uma data v&aacute;lida.",
    dateISO: "Por favor, forne&ccedil;a uma data v&aacute;lida (ISO).",
    number: "Por favor, forne&ccedil;a um n&uacute;mero v&aacute;lido.",
    digits: "Por favor, forne&ccedil;a somente d&iacute;gitos.",
    creditcard: "Por favor, forne&ccedil;a um cart&atilde;o de cr&eacute;dito v&aacute;lido.",
    equalTo: "Por favor, forne&ccedil;a o mesmo valor novamente.",
    accept: "Por favor, forne&ccedil;a um valor com uma extens&atilde;o v&aacute;lida.",
    maxlength: jQuery.validator.format("Por favor, forne&ccedil;a n&atilde;o mais que {0} caracteres."),
    minlength: jQuery.validator.format("Por favor, forne&ccedil;a ao menos {0} caracteres."),
    rangelength: jQuery.validator.format("Por favor, forne&ccedil;a um valor entre {0} e {1} caracteres de comprimento."),
    range: jQuery.validator.format("Por favor, forne&ccedil;a um valor entre {0} e {1}."),
    max: jQuery.validator.format("Por favor, forne&ccedil;a um valor menor ou igual a {0}."),
    min: jQuery.validator.format("Por favor, forne&ccedil;a um valor maior ou igual a {0}.")
  });
});
