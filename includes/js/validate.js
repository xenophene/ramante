$(function () {
  $('#contact-form').validate({
    rules: {
      fname: "required",
      email: {
        required: true,
        email: true
      },
      cmt: "required"
    },
    messages: {
      fname: "Please enter your Name",
      email: "Please enter a valid Email",
      cmt: "Please enter you Comment"
    },
    submitHandler: function () {
      $.ajax({
        url: 'record-feedback.php',
        type: 'GET',
        data: {
          cname: $('#cname').val(),
          email: $('#email').val(),
          comment: $('#cmt').val()
        },
        success: function (msg) {
          $('#contact-box').html('<h2>Thanks!</h2>');
        }
      });
    },
    errorClass: 'help-block',
    validClass: 'success',
    errorElement: 'p',
    highlight: function (element, errorClass) {
      $(element).parent().parent().addClass('error');
    },
    unhighlight: function (element, errorClass) {
      $(element).parent().parent().removeClass('error');
    }
  });
  $('#contact-form2').validate({
    rules: {
      email: {
        required: true,
        email: true
      }
    },
    messages: {
      email: "Please enter a valid Email",
    },
    submitHandler: function () {
      $.ajax({
        url: 'record-email.php',
        type: 'GET',
        data: {
          email: $('#email2').val(),
          desc: $('#cmt2').val()
        },
        success: function (msg) {
          $('#contact-box2').html('<h2>Thanks!</h2>');
        }
      });
    },
    errorClass: 'help-block',
    validClass: 'success',
    errorElement: 'p',
    highlight: function (element, errorClass) {
      $(element).parent().parent().addClass('error');
    },
    unhighlight: function (element, errorClass) {
      $(element).parent().parent().removeClass('error');
    }
  });
});
