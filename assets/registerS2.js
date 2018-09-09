// register step 2 form validation
function validateRegisterS2() {
   let letters = "[a-zA-ZąćęłńóśźżĄĘŁŃÓŚŹŻ]";
   let numbers = /[^0-9]/;
   let username = document.forms['registerS2Form']['username'].value;
   let phone = document.forms['registerS2Form']['phone'].value;
   let bio = document.forms['registerS2Form']['bio'].value;
   let avatar = document.forms['registerS2Form']['avatar'].value;
   let backgroundphoto = document.forms['registerS2Form']['backgroundphoto'].value;

   if (username == "") {
      $("#errors").html("<span>Username field must be filled!</span>");
      return false;
   }else if ((username.length <= 3) || (username.length >= 32)) {
      $("#errors").html("<span>Incorrect username! (min: 3 max: 32 characters)</span>");
      return false;
   }else if ((phone != phone.replace(numbers, ""))) {
      $("#errors").html("<span>Phone number can contain only letters!</span>");
      return false;
   }else if (bio != "" && (bio.length <= 3) || (bio.length >= 64)) {
      $("#errors").html("<span>Incorrect bio! (min: 3 max: 64 characters)</span>");
      return false;
   }

}

function validateFile(photoid, previewbox) {
   let file = document.getElementById(photoid);
   let filePath = file.value;
   let allowedExtensions = /(\.jpg|\.jpeg|\.png|\.gif)$/i;

   if (!allowedExtensions.exec(file.value)) {
      $("#errors").html('Please upload file having extensions .jpeg/.jpg/.png/.gif only.');
      file.value = "";
      return false;
   }else {
      if (file.files[0].size >= 5000000) {
         $("#errors").html('Incorrect file size! max 5 megabytes');
         file.value = "";
         return false;
      }

      //Image preview
      if (file.files && file.files[0]) {
         var reader = new FileReader();
         reader.onload = function(e) {
         document.getElementById(previewbox).innerHTML = '<img style="width: 100%; height: 100%; object-fit: cover;" src="'+e.target.result+'"/>';
      };

      reader.readAsDataURL(file.files[0]);
      }
   }
}
