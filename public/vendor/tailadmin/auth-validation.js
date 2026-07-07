(function ($) {
  function showErrors($error, errors) {
    var html = '<ul class="list-disc pl-5 text-red-700">';
    $.each(errors, function (_, message) {
      html += '<li>' + message + '</li>';
    });
    html += '</ul>';
    $error
      .html(html)
      .removeClass('hidden')
      .addClass('border-red-300 bg-red-50 text-red-700');
  }

  function hideErrors($error) {
    $error.addClass('hidden');
  }

  function clearInvalid($form) {
    $form.find('input').removeClass('border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500/20');
  }

  function markInvalid($field) {
    $field.addClass('border-red-500 bg-red-50 focus:border-red-500 focus:ring-red-500/20');
  }

  function validateEmail(email) {
    return /^\S+@\S+\.\S+$/.test(email);
  }

  $(function () {
    var $signin = $('#admin-signin-form');
    var $forgot = $('#admin-forgot-form');
    var $reset = $('#admin-reset-form');

    if ($signin.length) {
      var $signinError = $('#signin-error');
      $signin.on('submit', function (event) {
        clearInvalid($signin);
        var $email = $signin.find('[name="email"]');
        var $password = $signin.find('[name="password"]');
        var email = $.trim($email.val());
        var password = $.trim($password.val());
        var errors = [];

        if (!email) {
          errors.push('Email is required.');
          markInvalid($email);
        } else if (!validateEmail(email)) {
          errors.push('Enter a valid email address.');
          markInvalid($email);
        }

        if (!password) {
          errors.push('Password is required.');
          markInvalid($password);
        }

        if (errors.length) {
          event.preventDefault();
          showErrors($signinError, errors);
          return false;
        }

        hideErrors($signinError);
      });
    }

    if ($forgot.length) {
      var $forgotError = $('#forgot-error');
      $forgot.on('submit', function (event) {
        clearInvalid($forgot);
        var $email = $forgot.find('[name="email"]');
        var email = $.trim($email.val());
        var errors = [];

        if (!email) {
          errors.push('Email is required.');
          markInvalid($email);
        } else if (!validateEmail(email)) {
          errors.push('Enter a valid email address.');
          markInvalid($email);
        }

        if (errors.length) {
          event.preventDefault();
          showErrors($forgotError, errors);
          return false;
        }

        hideErrors($forgotError);
      });
    }

    if ($reset.length) {
      var $resetError = $('#reset-error');
      $reset.on('submit', function (event) {
        clearInvalid($reset);
        var $otp = $reset.find('[name="otp"]');
        var $password = $reset.find('[name="password"]');
        var $confirm = $reset.find('[name="password_confirmation"]');
        var otp = $.trim($otp.val());
        var password = $.trim($password.val());
        var confirm = $.trim($confirm.val());
        var errors = [];

        if (!otp) {
          errors.push('OTP is required.');
          markInvalid($otp);
        } else if (!/^\d{6}$/.test(otp)) {
          errors.push('OTP must be exactly 6 digits.');
          markInvalid($otp);
        }

        if (!password) {
          errors.push('Password is required.');
          markInvalid($password);
        } else if (password.length < 8) {
          errors.push('Password must be at least 8 characters.');
          markInvalid($password);
        }

        if (!confirm) {
          errors.push('Password confirmation is required.');
          markInvalid($confirm);
        } else if (password !== confirm) {
          errors.push('Passwords do not match.');
          markInvalid($confirm);
        }

        if (errors.length) {
          event.preventDefault();
          showErrors($resetError, errors);
          return false;
        }

        hideErrors($resetError);
      });
    }
  });
})(jQuery);
