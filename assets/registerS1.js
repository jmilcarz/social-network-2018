// register step 1 form validation
function validateRegisterS1() {
   let letters = /[^a-zA-Z]/;
   let firstname = document.forms['registerS1Form']['firstname'].value;
   let lastname = document.forms['registerS1Form']['lastname'].value;
   let email = document.forms['registerS1Form']['email'].value;
   let password = document.forms['registerS1Form']['password'].value;
   let rpassword = document.forms['registerS1Form']['rpassword'].value;
   let gender = document.forms['registerS1Form']['gender'].value;

   if (firstname == "" || lastname == "" || email == "" || password == "" || rpassword == "") {
      $("#errors").html("<span>All fields must be filled!</span>");
      return false;
   }else if (!validateEmail(email)) {
      $("#errors").html("<span>Incorrect Email!</span>");
      return false;
   }else if (password != rpassword) {
      $("#errors").html("<span>Passwords don't match!</span>");
      return false;
   }else if ((password.length <= 8) || (password.length >= 64)) {
      $("#errors").html("<span>Password has to be at least 8 characters long!</span>");
      return false;
   }else if ((rpassword.length <= 8) || (rpassword.length >= 64)) {
      $("#errors").html("<span>Password has to be at least 8 characters long!</span>");
      return false;
   }else if ((email.length <= 8) || (email.length >= 64)) {
      $("#errors").html("<span>Email has to be at least 8 characters long!</span>");
      return false;
   }else if ((gender[0].checked == false ) || (gender[1].checked == false)) {
      $("#errors").html("<span>Incorrect gender!</span>");
      return false;
   }else if ((firstname.length <= 2) || (firstname.length >= 32)) {
      $("#errors").html("<span>Incorrect first name! (min: 2 max: 32 characters)</span>");
      return false;
   }else if ((lastname.length <= 2) || (lastname.length >= 32)) {
      $("#errors").html("<span>Incorrect last name! (min: 2 max: 32 characters)</span>");
      return false;
   }else if ((firstname != firstname.replace(letters, "")) && (lastname != lastname.replace(letters, ""))) {
      $("#errors").html("<span>Name can contain only letters!</span>");
      return false;
   }
}

function validateEmail(email) {
   var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
   return re.test(email);
}
