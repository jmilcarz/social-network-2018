<?php
require('../classes/db.php');

$firstnamesM = [
   'jan', 'janek', 'kuba', 'jakub', 'piotrek',
   'piotr', 'kacper', 'dominik', 'wiktor', 'jerzy',
   'jurek', 'paweł', 'marek', 'radek', 'radosław',
   'mariusz', 'darek', 'dariusz', 'maciek', 'maciej',
   'michał', 'miłosz', 'szymon', 'patryk', 'janusz',
   'bartek', 'bartosz', 'bartłomiej', 'karol', 'włodek',
   'włodzimierz', 'andrzej', 'kazimierz', 'mirosław', 'mirek',
   'antek', 'adam', 'aleks', 'aleksander', 'olek',
   'antoni', 'artur', 'alan', 'bartłomiej', 'bronisław',
   'ben', 'benek', 'benjamin', 'czesław', 'czarek',
   'cezary', 'damian', 'daniel', 'dawid', 'emil',
   'eryk', 'filip', 'grzesiek', 'grzegorz', 'hubert',
   'henryk', 'ignacy', 'igor', 'jędrzej', 'kamil',
   'kornel', 'krzysiek', 'krzysztof', 'leszek', 'lech',
   'łukasz', 'marcel', 'marcin', 'mateusz', 'mati',
   'mikołaj', 'norbert', 'olaf', 'oliwier', 'olgierd',
   'oskar', 'przemek', 'przemysław', 'rafał', 'remek',
   'remigiusz', 'sebastian', 'sergiusz', 'sławomir', 'sławek',
   'stanisław', 'stach', 'stefan', 'władysław'
];

$firstnamesF = [
   'agata', 'agnieszka', 'aleksandra', 'alexandra', 'ola',
   'alicja', 'amelia', 'aneta', 'anastazja', 'asia',
   'joanna', 'basia', 'barbara', 'ania', 'anna',
   'beata', 'daria', 'dorota', 'edyta', 'eliza',
   'emilia', 'emilka', 'ewa', 'ewelina', 'gabrysia',
   'gabriela', 'hania', 'hanna', 'helena', 'iga',
   'ilona', 'irena', 'izabela', 'iwona', 'iza',
   'jagoda', 'julia', 'julka', 'justyna', 'kaja',
   'kamila', 'karolina', 'kasia', 'katarzyna', 'kinga',
   'laura', 'lena', 'lili', 'liza', 'lila',
   'luna', 'łucja', 'magda', 'magdalena', 'maja',
   'majka', 'malina', 'malwina', 'małgorzata', 'gosia',
   'małgosia', 'marcela', 'marcelina', 'maria', 'marysia',
   'marta', 'michalina', 'milena', 'mira', 'monika',
   'natalia', 'nata', 'nela', 'nila', 'olga',
   'oliwia', 'patrycja', 'paula', 'pola', 'paulina',
   'rita', 'renata', 'renia', 'róża', 'rozelia',
   'samanta', 'sandra', 'sara', 'sofia', 'sylwia',
   'tola', 'urszula', 'ula', 'wanda', 'wanessa',
   'weronika', 'wiktoria', 'zofia', 'zosia', 'zuza',
   'zuzia', 'zuzanna'
];

$lastnamesM = [
   'nowak', 'kowalski', 'wiśniewski', 'wójcik', 'kowalczyk',
   'kowalski', 'kamiński', 'lewandowski', 'dąbrowski', 'zieliński',
   'szymański', 'woźniak', 'kozłowski', 'jankowski', 'mazur',
   'wojciechowski', 'kwiatkowski', 'krawczyk', 'kaczmarek', 'piotrowski',
   'grabowski', 'zając', 'pawłowski', 'michalski', 'król',
   'nowakowski', 'wieczorek', 'wróbel', 'jabłoński', 'dudek',
   'adamczyk', 'majewski', 'nowicki', 'olszewski', 'stępień',
   'jaworski', 'malinowski', 'pawlak', 'górski', 'witkowski',
   'walczak', 'sikora', 'klekowski', 'pietruszka', 'tobiasz',
   'rutkowski', 'baran', 'michalak', 'szewczyk', 'ostrowski',
   'tomaszewski', 'pietrzak', 'orłowski', 'duda', 'zalewski',
   'wróblewski', 'jasiński', 'marciniak', 'bąk', 'zawadzki',
   'sadowski', 'jakubowski', 'wilk', 'andrzejuk', 'włodarczyk',
   'chmielewski', 'borkowski', 'sokołowski', 'szczepański', 'sawicki',
   'lis', 'wojciechowski', 'kucharski', 'kubiak', 'kalinowski',
   'wysocki', 'maciejewski', 'czarnecki', 'kołodziej', 'urbański',
   'kaźmierczyk', 'sobczak', 'konieczny', 'głowacki', 'zakrzewski',
   'krupa', 'wasilewski', 'krajewski', 'adamski', 'sikorski',
   'mróz', 'barański', 'laskowski', 'gajewski', 'ziółkowski',
   'szulc', 'makowski', 'czerwiński', 'baranowski', 'szymczak',
   'brzezińska', 'kaczmarczyk', 'przybylski', 'cieślak', 'borowski',
   'błaszczyk', 'andrzejewski'
];

$lastnamesF = [
   'adamczyk', 'ankiewicz', 'ankewicz', 'nowak', 'kowalska',
   'wiśniewska', 'wójcik', 'kowalczyk', 'kamińska', 'lewandowska',
   'dąbrowska', 'zielińska', 'szymańska', 'woźniak', 'kozłowska',
   'jankowska', 'wojciechowska', 'kwiatkowska', 'mazur', 'krawczyk',
   'piotrowska', 'kaczmarek', 'grabowska', 'pawłowska', 'michalska',
   'zając', 'król', 'nowakowska',' wieczorek', 'wieczorkiewicz',
   'jabłońska', 'majewska', 'wróbel', 'nowicka', 'dudek',
   'oleszewska', 'jaworska', 'malinowska', 'stępień', 'górska',
   'witkowska', 'pawlak', 'walczyk', 'rutkowska', 'sikora',
   'michalak', 'szewczyk', 'ostrowska', 'baran', 'tomaszewska',
   'pietrzak', 'jasińska', 'wróbelewska', 'wróblewska', 'zalewska',
   'marciniak', 'zawadzka', 'jakubowska', 'duda', 'sadowska',
   'bąk', 'włodarczyk', 'borowska', 'chmielewska', 'sokołowska',
   'wilk', 'sawicka', 'szczepańska', 'kucharska', 'lis',
   'maciejewska', 'czarnecka', 'kalinowska', 'kubiak', 'wysocka',
   'mazurek', 'urbańska', 'kołodziej', 'kaźmierczak', 'sobczak',
   'sikorska', 'głowacka', 'krajewska', 'zakrzewska', 'adamska',
   'wasilewska', 'laskowska', 'gajewska', 'ziółkowska', 'krupa', '
   szulc', 'czerwińska', 'makowska', 'brzezińska', 'szymczak',
   'przybylska', 'baranowska', 'mróz', 'błaszczyk', 'borowska',
   'andrzejewska', 'cieślak', 'górecka', 'kaczmarczyk'
];

$genders = ['male', 'female'];
$sexes = ['m', 'f'];

$normalize = array('Š'=>'S', 'š'=>'s', 'Ð'=>'Dj','Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E', 'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ń'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U', 'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss','à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c', 'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ń'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o', 'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ü'=>'u', 'ý'=>'y', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', 'ƒ'=>'f', 'ă'=>'a', 'î'=>'i', 'â'=>'a', 'ș'=>'s', 'ț'=>'t', 'Ă'=>'A', 'Î'=>'I', 'Â'=>'A', 'Ș'=>'S', 'Ț'=>'T', 'ą'=>'a', 'Ą'=>'A', 'ę'=>'e', 'Ę'=>'E', 'ó'=>'o', 'Ó'=>'O', 'ł'=>'l', 'Ł'=>'L', 'ć'=>'c', 'Ć'=>'C', 'ś'=>'s', 'Ś'=>'S', 'ź'=>'z', 'Ź'=>'Z','ż'=>'z', 'Ż'=>'Z');


for ($i = 0; $i <= 1000; $i++)
{
   $sex = $sexes[array_rand($sexes)];

   if ($sex == "f") {
      $gender = $genders[1];
      $firstname = $firstnamesF[array_rand($firstnamesF)];
      $lastname = $lastnamesF[array_rand($lastnamesF)];
   } else if ($sex == "m") {
      $gender = $genders[0];
      $firstname = $firstnamesM[array_rand($firstnamesM)];
      $lastname = $lastnamesM[array_rand($lastnamesM)];
   }

   # usernames
   $username = strtr($firstname, $normalize) . '.' . strtr($lastname, $normalize) . rand(1, 100);
   $usernames = DB::query('SELECT user_username FROM users');

   $name = ucfirst($firstname) . ' ' . ucfirst($lastname);

   # phone
   $numbers = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];
   $phone = $numbers[array_rand($numbers)] . $numbers[array_rand($numbers)] . $numbers[array_rand($numbers)] . $numbers[array_rand($numbers)] . $numbers[array_rand($numbers)] . $numbers[array_rand($numbers)] . $numbers[array_rand($numbers)] . $numbers[array_rand($numbers)] . $numbers[array_rand($numbers)];

   # email
   $emailEndings = ['yahoo.com', 'onet.pl', 'gmail.com', 'wp.pl', 'interia.pl', 'icloud.com'];
   $email = $username . '@' . $emailEndings[array_rand($emailEndings)];

   # password
   $password = password_hash("qwerty123", PASSWORD_DEFAULT);

   # photos
   $avatar = 'no-photo';
   $backgroundphoto = 'no-photo';

   # country & city
   $countries = ["Afghanistan", "Aland Islands", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Barbuda", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Trty.", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Caicos Islands", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "French Guiana", "French Polynesia", "French Southern Territories", "Futuna Islands", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guernsey", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard", "Herzegovina", "Holy See", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Isle of Man", "Israel", "Italy", "Jamaica", "Jan Mayen Islands", "Japan", "Jersey", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea", "Korea (Democratic)", "Kuwait", "Kyrgyzstan", "Lao", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macao", "Macedonia", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "McDonald Islands", "Mexico", "Micronesia", "Miquelon", "Moldova", "Monaco", "Mongolia", "Montenegro", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "Nevis", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Palestinian Territory, Occupied", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Principe", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Barthelemy", "Saint Helena", "Saint Kitts", "Saint Lucia", "Saint Martin (French part)", "Saint Pierre", "Saint Vincent", "Samoa", "San Marino", "Sao Tome", "Saudi Arabia", "Senegal", "Serbia", "Seychelles", "Sierra Leone", "Singapore", "Slovakia", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia", "South Sandwich Islands", "Spain", "Sri Lanka", "Sudan", "Suriname", "Svalbard", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan", "Tajikistan", "Tanzania", "Thailand", "The Grenadines", "Timor-Leste", "Tobago", "Togo", "Tokelau", "Tonga", "Trinidad", "Tunisia", "Turkey", "Turkmenistan", "Turks Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "Uruguay", "US Minor Outlying Islands", "Uzbekistan", "Vanuatu", "Vatican City State", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (US)", "Wallis", "Western Sahara", "Yemen", "Zambia", "Zimbabwe"];

   $country = $countries[array_rand($countries)];
   $city = 'heh';
   $bio = 'standard bio';

   $language = 'en';

   # insert to database
   DB::query('INSERT INTO users VALUES (\'\', :username, :firstname, :lastname, :name, :email, :phone, :password, :birthday, :sex, :avatar, :backgroundphoto)', [':username' => $username, ':firstname' => $firstname, ':lastname' => $lastname, ':name' => $name, ':email' => $email, ':phone' => $phone, ':password' => $password, ':birthday' => '0', ':sex' => $sex, ':avatar' => $avatar, ':backgroundphoto' => $backgroundphoto]);
   $userid = DB::query('SELECT id FROM users WHERE user_username = :username AND user_email = :email AND user_password = :password', [':username' => $username, ':email' => $email, ':password' => $password])[0]['id'];
   DB::query('INSERT INTO user_info VALUES (\'\', :userid, :lang, :country, :city, :bio, :gender, :religion)', [':userid' => $userid, ':lang' => $language, ':country' => $country, ':city' => $city, ':bio' => $bio, ':gender' => $gender, ':religion' => '0']);
   # '
   echo 'success!' . "\n";
}
