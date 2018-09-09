// register step 3 form validation
function validateRegisterS3() {
   let letters = "[a-zA-ZąćęłńóśźżĄĘŁŃÓŚŹŻ]";
   let numbers = /[^0-9]/;

   let language = document.forms['registerS3Form']['language'].value;
   let country = document.forms['registerS3Form']['country'].value;
   let city = document.forms['registerS3Form']['city'].value;
   let religion = document.forms['registerS3Form']['religion'].value;

   if (language == "" || country == "" || city == "") {
      $("#errors").html("<span>All fields except religion & website must be filled out!</span>");
      return false;
   }
   if (language == "en" || language == "es" || language == "pl" || language == "de" || language == "fr") {
      ("#errors").html(" ");
   }else {
      ("#errors").html("<span>Incorrect language!</span>");
      return false;
   }

   if (language != language.replace(letters, "")) {
      ("#errors").html("<span>Incorrect language!</span>");
      return false;
   }else if (city != city.replace(letters, "")) {
      ("#errors").html("<span>Incorrect city!</span>");
      return false;
   }else if ((city.length <= 3) && (city.length >= 48)) {
      ("#errors").html("<span>City name has to be at least 3 characters long!</span>");
      return false;
   }else if (religion != religion.replace(letters, "")) {
      ("#errors").html("<span>Incorrect religion!</span>");
      return false;
   }else if ((religion.length <= 3) && (religion.length >= 48)) {
      ("#errors").html("<span>Religion has to be at least 3 characters long!</span>");
      return false;
   }

   return true;
}
