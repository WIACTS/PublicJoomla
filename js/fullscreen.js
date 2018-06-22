/*
  Screen object is used to render the login page. This file is responsible for the fullscreen design.
*/
Scene = function($){

  this.init = function(){
    setTimeout(function(){
      $(".loginScreenPopUpWrapper .lsFirst").addClass("show");
    }, 200);
    setTimeout(function(){
      $(".rsWrapper").addClass("show");
      $(".lScreenPopupWrapper").addClass("show");
    }, 300);
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
    $(".loginScreenPopUpWrapper .lsAwaiting .timer .inner").html("0");
    $(".loginScreenPopUpWrapper .lsFirst").addClass("show");
    $(".loginScreenPopUpWrapper .lsAwaiting").removeClass("show");
    $(".loginScreenPopUpWrapper .lScreenPopup").removeClass("busy");
    $("#npMail").removeAttr("readonly");
    $("#npMail").removeClass("green");
    $("#npMail").removeClass("red");
    $("#npLoginButtonPassword").attr('id', 'npLoginButton');
    $("p#createUser").removeClass('show');
    clearInterval(countDown);
  }
  showDefaultScreen = function(){
    $("#npPassword").val('').attr('type', 'email').attr('id', 'npMail').attr('placeholder', 'Enter your email').attr('name', 'login_user_name');
    $('#npTitle').html('Login with NoPassword');
    $(".loginScreenPopUpWrapper .lsAwaiting .timer .inner").html("0");
    $(".loginScreenPopUpWrapper .lsFirst").addClass("show");
    $(".loginScreenPopUpWrapper .lsAwaiting").removeClass("show");
    $(".loginScreenPopUpWrapper .lScreenPopup").removeClass("busy");
    $("#npMail").removeAttr("readonly");
    $("#npMail").removeClass("green");
    $("#npMail").removeClass("red");
    $("#npLoginButtonPassword").attr('id', 'npLoginButton');
    clearInterval(countDown);
  }

  endCountdown = function(){

    timerCounter = 0;
    clearInterval(countDown);

  }


  this.startCountdown = function(){

    timerCounter = 60;

    $(".loginScreenPopUpWrapper .lsFirst").removeClass("show");
    $(".loginScreenPopUpWrapper .lsAwaiting").addClass("show");
    $(".loginScreenPopUpWrapper .lScreenPopup").addClass("busy");
    $("#npMail").attr("readonly","readonly");

    $(".loginScreenPopUpWrapper .lsAwaiting .button-cancel a").click(function(){
      showDefaultScreen();
      endCountdown();
    });

    $(".loginScreenPopUpWrapper .lsAwaiting .progress").circleProgress({
      startAngle: -Math.PI / 2,
      size: 108,
      value: 1,
      fill: "#FFFFFF",
      emptyFill: "#008097",
      thickness: 5,
      animation: { duration: 60000, easing: "linear" }
    });

    $(".loginScreenPopUpWrapper .lsAwaiting .timer .inner").html(timerCounter);
    countDown = setInterval(function(){
      if(timerCounter == 0){
        endCountdown();
        clearInterval(countDown);
      }
      else{
        $(".loginScreenPopUpWrapper .lsAwaiting .timer .inner").html(timerCounter);
        timerCounter--;
      }
    },1000)

  }

  this.showPasswordScreen = function(){
    this.showDefaultScreen();
    $("#npMail").val('').attr('type', 'password').attr('value', '').attr('id', 'npPassword').attr('placeholder', 'Password').attr('name', 'login_user_password');
    $('#npTitle').html('Please enter your Password');
    $("#npPassword, #npMail").removeAttr("readonly");
    $("#npPassword, #npMail").removeClass('green');
    $("#npLoginButton").attr('onclick', 'passwordlogin()');
    $("p.npButton.back a").attr('onclick', 'window.location.reload();return false;');
    this.showHint('password');
  }

  this.showAccountCreateLink = function(){
    $('#createUser').addClass('show');
  }

}
