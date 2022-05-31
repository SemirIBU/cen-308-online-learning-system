class Login {
    static init() {
      if (window.localStorage.getItem("token")) {
        window.location = "index.html";
      } else {
        $("body").show();
      }
  
      let token = this.getUrlVars().token;
      if (token) {
        $("#token-reset").val(token);
        Login.showResetPassword();
      } else {
        this.showLoginForm();
      }
  
      $("#bar-code").hide();
  
      if (getCookie("attempts") < 3) this.hideCaptcha();
    }
  
    static hideCaptcha() {
      $(".h-captcha").hide();
    }
    static showCaptcha() {
      $(".h-captcha").show();
    }
    static showLoginForm() {
      $("#forgotPassword").hide();
      $("#register").hide();
      $("#resetPassword").hide();
      $("#login").show();
      $("#confirm-otp").hide();
      $("#confirm-sms").hide();
    }
    static showForgotPassword() {
      $("#login").hide();
      $("#register").hide();
      $("#resetPassword").hide();
      $("#forgotPassword").show();
      $("#confirm-otp").hide();
      $("#confirm-sms").hide();
    }
    static showRegister() {
      $("#login").hide();
      $("#forgotPassword").hide();
      $("#resetPassword").hide();
      $("#register").show();
      $("#confirm-otp").hide();
      $("#confirm-sms").hide();
    }
    static showResetPassword() {
      $("#login").hide();
      $("#forgotPassword").hide();
      $("#register").hide();
      $("#resetPassword").show();
      $("#confirm-otp").hide();
      $("#confirm-sms").hide();
    }
    static showOtpForm() {
      $("#login").hide();
      $("#forgotPassword").hide();
      $("#register").hide();
      $("#resetPassword").hide();
      $("#confirm-otp").show();
      $("#confirm-sms").hide();
    }
    static showSmsForm() {
      $("#login").hide();
      $("#forgotPassword").hide();
      $("#register").hide();
      $("#resetPassword").hide();
      $("#confirm-otp").hide();
      $("#confirm-sms").show();
    }
  
    static getUrlVars() {
      var vars = [],
        hash;
      var hashes = window.location.href
        .slice(window.location.href.indexOf("?") + 1)
        .split("&");
      for (var i = 0; i < hashes.length; i++) {
        hash = hashes[i].split("=");
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
      }
      return vars;
    }
  }
  