Popup = function($){

  this.checkEmail = false;

  this.init = function(email){
    $('#npNewUserEmail').attr('value', email);
  }

  this.toggle = function(){
    $("#createUserPopup").toggleClass("show");
    if(this.checkEmail) $('#NPCheckEmail').toggleClass('show');
  }

  this.loading = function(){
    $("#npNewLastName").addClass("green");
    $("#npNewFirstName").addClass("green");
    $("#NPCreateUserWait").addClass("show");
  }

  this.success = function(){
    $('#NPSuccess').addClass('show');
  }

  this.errorScreen = function(){
    $('#NPCreateUserError').addClass('show');
  }

  this.wrongFirstName = function(){
    $("#npNewFirstName").addClass("red");
    setTimeout(function(){
      $("#npNewFirstName").removeClass("red");
    }, 2500);
  }

  this.wrongLastName = function(){
    $("#npNewLastName").addClass("red");
    setTimeout(function(){
      $("#npNewLastName").removeClass("red");
    }, 2500);
  }

  this.correctData = function(){
    $("#npNewLastName").addClass("green");
    $("#npNewFirstName").addClass("green");
  }

}
