/*
  Screen object is used to render the login page. This file is responsible for the compact design.
*/


Scene = function($){

  this.init = function(){
    setTimeout(function(){
      $(".loginScreenCompactWrapper").addClass("show");
    }, 200);
    this.showHint();
  }

  this.showError = function(errorMessage, type){
    clearTimeout(this.hideError);
    $("#npError").addClass("show");
    $("#npError").html(errorMessage);

    this.showHint(type);

    this.hideError = setTimeout(function () {
        $("#npError").removeClass("show");
    }, 2500);
  }

  this.showHint = function(type){
    if (type == 'password'){
      $(".hint.nphint").removeClass("show");
      $(".hint.passwordhint").addClass("show");
    }
    else{
      $(".hint.passwordhint").removeClass("show");
      $(".hint.nphint").addClass("show");
    }
  }

  this.wrongEmail = function(){
    $("#npMail").removeClass("green");
    $("#npMail").addClass("red");
    $("#npMail").focus();
    setTimeout(function(){
      $("#npMail").removeClass("red");
    },2500);
  }

  this.wrongPassword = function(){
    $("#npPassword").removeClass("green");
    $("#npPassword").addClass("red");
    $("#npPassword").focus();
    setTimeout(function(){
      $("#npPassword").removeClass("red");
    },2500);
  }

  this.correctEmail = function(){
    $("#npMail").removeClass("red");
    $("#npMail").addClass("green");
  }

  this.showDefaultScreen = function(){
    $("#npPassword").val('').attr('type', 'email').attr('id', 'npMail').attr('placeholder', 'Enter your email').attr('name', 'login_user_name');
    $('#npTitle').html('Login with NoPassword');
    $(".compactAwaiting").removeClass("show");
    $("#npMail").removeAttr("readonly");
    $("#npMail").removeClass("green");
    $("#npMail").removeClass("red");
    $("#npLoginButtonPassword").attr('id', 'npLoginButton');
    clearTimeout(countDown);
  }
  showDefaultScreen = function(){
    $("#npPassword").val('').attr('type', 'email').attr('id', 'npMail').attr('placeholder', 'Enter your email').attr('name', 'login_user_name');
    $('#npTitle').html('Login with NoPassword');
    $(".compactAwaiting").removeClass("show");
    $("#npMail").removeAttr("readonly");
    $("#npMail").removeClass("green");
    $("#npMail").removeClass("red");
    $("#npLoginButtonPassword").attr('id', 'npLoginButton');
    clearTimeout(countDown);
  }

  endCountdown = function(){
    timerCounter = 0;
    clearTimeout(countDown);
  }

  this.startCountdown = function(){

    $(".compactAwaiting").addClass("show");

    $("#avaiting .progress").circleProgress({
      startAngle: -Math.PI / 2,
      size: 50,
      value: 1,
      fill: "#FFFFFF",
      emptyFill: "transparent",
      thickness: 3,
      animation: { duration: 60000, easing: "linear" }
    });
    timerCounterCompact = 60;

    function onTimerCompact() {
      if (timerCounterCompact<10)
        $('.phoneWrapper .timer').css("left", "155px");
      else
        $('.phoneWrapper .timer').css("left", "150px");
      $("#avaiting .timer .inner").html(timerCounterCompact);
      timerCounterCompact--;
      if (timerCounterCompact >= 0) {
        countDown = setTimeout(onTimerCompact, 1000);
      } else {
        $("#avaiting .timer .inner").html("0");
        $("#avaiting").removeClass("show");
        endCountdown();
      }
    }
    onTimerCompact();

  }

  this.showPasswordScreen = function(){
    $(".compactAwaiting").removeClass("show");
    $("#npMail").val('').attr('type', 'password').attr('value', '').attr('id', 'npPassword').attr('placeholder', 'Password').attr('name', 'login_user_password');
    $('#npTitle').html('Please enter your Password');
    $("#npPassword, #npMail").removeAttr("readonly");
    $("#npPassword, #npMail").removeClass('green');
    $("#npLoginButton").attr('onclick', 'passwordlogin()');
    this.showHint('password');
  }

  this.showAccountCreateLink = function(){
    $('#createUser').addClass('show');
  }

}
