<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Register - GODEVI</title>

  <!-- Font Icon -->
  <link rel="stylesheet" href="{{ url('frontdata/fonts/material-icon/css/material-design-iconic-font.min.css') }}">

  <!-- Main css -->
</head>

<body>

  <div class="main">

    <!-- Sign up form -->
    <section class="signup">
      <div class="container">
        <div class="signup-content">
          <div class="signup-form">
            
            <h2 class="form-title">Sign up</h2>
            @if ($errors->any())
            <ul>
              @foreach ($errors->all() as $error)
              <li style="color:orangered">{{ $error }}</li>
              @endforeach
            </ul>
            @endif
            <form method="POST" class="register-form" id="register-form" action="{{ route('register')}}">
              <div class="form-group">
                <label for="name"><i class="zmdi zmdi-account material-icons-name"></i></label>
                <input type="text" name="name" id="name" placeholder="Your Name" required />
                <input type="hidden" name="role_id" value="3">
                @csrf
              </div>
              <div class="form-group">
                <label for="email"><i class="zmdi zmdi-email"></i></label>
                <input type="email" name="email" id="email" placeholder="Your Email" required />
              </div>
              <div class="form-group">
                <label for="pass"><i class="zmdi zmdi-lock"></i></label>
                <input type="password" name="password" id="pass" placeholder="Password" required />
              </div>
              <div class="form-group">
                <label for="re-pass"><i class="zmdi zmdi-lock-outline"></i></label>
                <input type="password" name="password_confirmation" id="re_pass" placeholder="Repeat your password" required />
              </div>
              <div class="form-group">
                <label for="name"><i class="zmdi zmdi-account material-icons-name"></i></label>
                <select name="country">
                  <option value="" selected="selected">Select Country</option>
                  <option value="United States">United States</option>
                  <option value="United Kingdom">United Kingdom</option>
                  <option value="Afghanistan">Afghanistan</option>
                  <option value="Albania">Albania</option>
                  <option value="Algeria">Algeria</option>
                  <option value="American Samoa">American Samoa</option>
                  <option value="Andorra">Andorra</option>
                  <option value="Angola">Angola</option>
                  <option value="Anguilla">Anguilla</option>
                  <option value="Antarctica">Antarctica</option>
                  <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                  <option value="Argentina">Argentina</option>
                  <option value="Armenia">Armenia</option>
                  <option value="Aruba">Aruba</option>
                  <option value="Australia">Australia</option>
                  <option value="Austria">Austria</option>
                  <option value="Azerbaijan">Azerbaijan</option>
                  <option value="Bahamas">Bahamas</option>
                  <option value="Bahrain">Bahrain</option>
                  <option value="Bangladesh">Bangladesh</option>
                  <option value="Barbados">Barbados</option>
                  <option value="Belarus">Belarus</option>
                  <option value="Belgium">Belgium</option>
                  <option value="Belize">Belize</option>
                  <option value="Benin">Benin</option>
                  <option value="Bermuda">Bermuda</option>
                  <option value="Bhutan">Bhutan</option>
                  <option value="Bolivia">Bolivia</option>
                  <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                  <option value="Botswana">Botswana</option>
                  <option value="Bouvet Island">Bouvet Island</option>
                  <option value="Brazil">Brazil</option>
                  <option value="British Indian Ocean Territory">British Indian Ocean Territory</option>
                  <option value="Brunei Darussalam">Brunei Darussalam</option>
                  <option value="Bulgaria">Bulgaria</option>
                  <option value="Burkina Faso">Burkina Faso</option>
                  <option value="Burundi">Burundi</option>
                  <option value="Cambodia">Cambodia</option>
                  <option value="Cameroon">Cameroon</option>
                  <option value="Canada">Canada</option>
                  <option value="Cape Verde">Cape Verde</option>
                  <option value="Cayman Islands">Cayman Islands</option>
                  <option value="Central African Republic">Central African Republic</option>
                  <option value="Chad">Chad</option>
                  <option value="Chile">Chile</option>
                  <option value="China">China</option>
                  <option value="Christmas Island">Christmas Island</option>
                  <option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option>
                  <option value="Colombia">Colombia</option>
                  <option value="Comoros">Comoros</option>
                  <option value="Congo">Congo</option>
                  <option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option>
                  <option value="Cook Islands">Cook Islands</option>
                  <option value="Costa Rica">Costa Rica</option>
                  <option value="Cote D'ivoire">Cote D'ivoire</option>
                  <option value="Croatia">Croatia</option>
                  <option value="Cuba">Cuba</option>
                  <option value="Cyprus">Cyprus</option>
                  <option value="Czech Republic">Czech Republic</option>
                  <option value="Denmark">Denmark</option>
                  <option value="Djibouti">Djibouti</option>
                  <option value="Dominica">Dominica</option>
                  <option value="Dominican Republic">Dominican Republic</option>
                  <option value="Ecuador">Ecuador</option>
                  <option value="Egypt">Egypt</option>
                  <option value="El Salvador">El Salvador</option>
                  <option value="Equatorial Guinea">Equatorial Guinea</option>
                  <option value="Eritrea">Eritrea</option>
                  <option value="Estonia">Estonia</option>
                  <option value="Ethiopia">Ethiopia</option>
                  <option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option>
                  <option value="Faroe Islands">Faroe Islands</option>
                  <option value="Fiji">Fiji</option>
                  <option value="Finland">Finland</option>
                  <option value="France">France</option>
                  <option value="French Guiana">French Guiana</option>
                  <option value="French Polynesia">French Polynesia</option>
                  <option value="French Southern Territories">French Southern Territories</option>
                  <option value="Gabon">Gabon</option>
                  <option value="Gambia">Gambia</option>
                  <option value="Georgia">Georgia</option>
                  <option value="Germany">Germany</option>
                  <option value="Ghana">Ghana</option>
                  <option value="Gibraltar">Gibraltar</option>
                  <option value="Greece">Greece</option>
                  <option value="Greenland">Greenland</option>
                  <option value="Grenada">Grenada</option>
                  <option value="Guadeloupe">Guadeloupe</option>
                  <option value="Guam">Guam</option>
                  <option value="Guatemala">Guatemala</option>
                  <option value="Guinea">Guinea</option>
                  <option value="Guinea-bissau">Guinea-bissau</option>
                  <option value="Guyana">Guyana</option>
                  <option value="Haiti">Haiti</option>
                  <option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option>
                  <option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option>
                  <option value="Honduras">Honduras</option>
                  <option value="Hong Kong">Hong Kong</option>
                  <option value="Hungary">Hungary</option>
                  <option value="Iceland">Iceland</option>
                  <option value="India">India</option>
                  <option value="Indonesia">Indonesia</option>
                  <option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option>
                  <option value="Iraq">Iraq</option>
                  <option value="Ireland">Ireland</option>
                  <option value="Israel">Israel</option>
                  <option value="Italy">Italy</option>
                  <option value="Jamaica">Jamaica</option>
                  <option value="Japan">Japan</option>
                  <option value="Jordan">Jordan</option>
                  <option value="Kazakhstan">Kazakhstan</option>
                  <option value="Kenya">Kenya</option>
                  <option value="Kiribati">Kiribati</option>
                  <option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option>
                  <option value="Korea, Republic of">Korea, Republic of</option>
                  <option value="Kuwait">Kuwait</option>
                  <option value="Kyrgyzstan">Kyrgyzstan</option>
                  <option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option>
                  <option value="Latvia">Latvia</option>
                  <option value="Lebanon">Lebanon</option>
                  <option value="Lesotho">Lesotho</option>
                  <option value="Liberia">Liberia</option>
                  <option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option>
                  <option value="Liechtenstein">Liechtenstein</option>
                  <option value="Lithuania">Lithuania</option>
                  <option value="Luxembourg">Luxembourg</option>
                  <option value="Macao">Macao</option>
                  <option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option>
                  <option value="Madagascar">Madagascar</option>
                  <option value="Malawi">Malawi</option>
                  <option value="Malaysia">Malaysia</option>
                  <option value="Maldives">Maldives</option>
                  <option value="Mali">Mali</option>
                  <option value="Malta">Malta</option>
                  <option value="Marshall Islands">Marshall Islands</option>
                  <option value="Martinique">Martinique</option>
                  <option value="Mauritania">Mauritania</option>
                  <option value="Mauritius">Mauritius</option>
                  <option value="Mayotte">Mayotte</option>
                  <option value="Mexico">Mexico</option>
                  <option value="Micronesia, Federated States of">Micronesia, Federated States of</option>
                  <option value="Moldova, Republic of">Moldova, Republic of</option>
                  <option value="Monaco">Monaco</option>
                  <option value="Mongolia">Mongolia</option>
                  <option value="Montserrat">Montserrat</option>
                  <option value="Morocco">Morocco</option>
                  <option value="Mozambique">Mozambique</option>
                  <option value="Myanmar">Myanmar</option>
                  <option value="Namibia">Namibia</option>
                  <option value="Nauru">Nauru</option>
                  <option value="Nepal">Nepal</option>
                  <option value="Netherlands">Netherlands</option>
                  <option value="Netherlands Antilles">Netherlands Antilles</option>
                  <option value="New Caledonia">New Caledonia</option>
                  <option value="New Zealand">New Zealand</option>
                  <option value="Nicaragua">Nicaragua</option>
                  <option value="Niger">Niger</option>
                  <option value="Nigeria">Nigeria</option>
                  <option value="Niue">Niue</option>
                  <option value="Norfolk Island">Norfolk Island</option>
                  <option value="Northern Mariana Islands">Northern Mariana Islands</option>
                  <option value="Norway">Norway</option>
                  <option value="Oman">Oman</option>
                  <option value="Pakistan">Pakistan</option>
                  <option value="Palau">Palau</option>
                  <option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option>
                  <option value="Panama">Panama</option>
                  <option value="Papua New Guinea">Papua New Guinea</option>
                  <option value="Paraguay">Paraguay</option>
                  <option value="Peru">Peru</option>
                  <option value="Philippines">Philippines</option>
                  <option value="Pitcairn">Pitcairn</option>
                  <option value="Poland">Poland</option>
                  <option value="Portugal">Portugal</option>
                  <option value="Puerto Rico">Puerto Rico</option>
                  <option value="Qatar">Qatar</option>
                  <option value="Reunion">Reunion</option>
                  <option value="Romania">Romania</option>
                  <option value="Russian Federation">Russian Federation</option>
                  <option value="Rwanda">Rwanda</option>
                  <option value="Saint Helena">Saint Helena</option>
                  <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                  <option value="Saint Lucia">Saint Lucia</option>
                  <option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option>
                  <option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option>
                  <option value="Samoa">Samoa</option>
                  <option value="San Marino">San Marino</option>
                  <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                  <option value="Saudi Arabia">Saudi Arabia</option>
                  <option value="Senegal">Senegal</option>
                  <option value="Serbia and Montenegro">Serbia and Montenegro</option>
                  <option value="Seychelles">Seychelles</option>
                  <option value="Sierra Leone">Sierra Leone</option>
                  <option value="Singapore">Singapore</option>
                  <option value="Slovakia">Slovakia</option>
                  <option value="Slovenia">Slovenia</option>
                  <option value="Solomon Islands">Solomon Islands</option>
                  <option value="Somalia">Somalia</option>
                  <option value="South Africa">South Africa</option>
                  <option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option>
                  <option value="Spain">Spain</option>
                  <option value="Sri Lanka">Sri Lanka</option>
                  <option value="Sudan">Sudan</option>
                  <option value="Suriname">Suriname</option>
                  <option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option>
                  <option value="Swaziland">Swaziland</option>
                  <option value="Sweden">Sweden</option>
                  <option value="Switzerland">Switzerland</option>
                  <option value="Syrian Arab Republic">Syrian Arab Republic</option>
                  <option value="Taiwan, Province of China">Taiwan, Province of China</option>
                  <option value="Tajikistan">Tajikistan</option>
                  <option value="Tanzania, United Republic of">Tanzania, United Republic of</option>
                  <option value="Thailand">Thailand</option>
                  <option value="Timor-leste">Timor-leste</option>
                  <option value="Togo">Togo</option>
                  <option value="Tokelau">Tokelau</option>
                  <option value="Tonga">Tonga</option>
                  <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                  <option value="Tunisia">Tunisia</option>
                  <option value="Turkey">Turkey</option>
                  <option value="Turkmenistan">Turkmenistan</option>
                  <option value="Turks and Caicos Islands">Turks and Caicos Islands</option>
                  <option value="Tuvalu">Tuvalu</option>
                  <option value="Uganda">Uganda</option>
                  <option value="Ukraine">Ukraine</option>
                  <option value="United Arab Emirates">United Arab Emirates</option>
                  <option value="United Kingdom">United Kingdom</option>
                  <option value="United States">United States</option>
                  <option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option>
                  <option value="Uruguay">Uruguay</option>
                  <option value="Uzbekistan">Uzbekistan</option>
                  <option value="Vanuatu">Vanuatu</option>
                  <option value="Venezuela">Venezuela</option>
                  <option value="Viet Nam">Viet Nam</option>
                  <option value="Virgin Islands, British">Virgin Islands, British</option>
                  <option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option>
                  <option value="Wallis and Futuna">Wallis and Futuna</option>
                  <option value="Western Sahara">Western Sahara</option>
                  <option value="Yemen">Yemen</option>
                  <option value="Zambia">Zambia</option>
                  <option value="Zimbabwe">Zimbabwe</option>
                </select>
              </div>
              <div class="form-group">
                <input type="checkbox" name="agree-term" id="agree-term" class="agree-term" required />
                <label for="agree-term" class="label-agree-term"><span><span></span></span>I agree all statements in <a href="{{url('term')}}" class="term-service">Terms of service</a></label>
              </div>
              <div class="form-group form-button">
                <input type="submit" name="signup" id="signup" class="form-submit" value="Register" />
              </div>
            </form>
          </div>
          <div class="signup-image">
            <figure><img src="{{ asset('frontdata/images/logo.png') }}" width="100%" style="margin-top:30px;"></figure>
            <a href="{{url('login')}}" class="signup-image-link">I am already member</a>
          </div>
        </div>
      </div>
    </section>


  </div>


</body><!-- This templates was made by Colorlib (https://colorlib.com) -->

</html>

<style>
  select {
    border: none;
    padding: 10px 30px;
    width: 100%;
  }

  /* @extend display-flex; */
  display-flex,
  .display-flex,
  .display-flex-center,
  .signup-content,
  .signin-content,
  .social-login,
  .socials {
    display: flex;
    display: -webkit-flex;
  }

  /* @extend list-type-ulli; */
  list-type-ulli,
  .socials {
    list-style-type: none;
    margin: 0;
    padding: 0;
  }

  /* poppins-300 - latin */
  @font-face {
    font-family: 'Poppins';
    font-style: normal;
    font-weight: 300;
    src: url("../frontdata/fonts/poppins/poppins-v5-latin-300.eot");
    /* IE9 Compat Modes */
    src: local("Poppins Light"), local("Poppins-Light"), url("../frontdata/fonts/poppins/poppins-v5-latin-300.eot?#iefix") format("embedded-opentype"), url("../frontdata/fonts/poppins/poppins-v5-latin-300.woff2") format("woff2"), url("../frontdata/fonts/poppins/poppins-v5-latin-300.woff") format("woff"), url("../frontdata/fonts/poppins/poppins-v5-latin-300.ttf") format("truetype"), url("../frontdata/fonts/poppins/poppins-v5-latin-300.svg#Poppins") format("svg");
    /* Legacy iOS */
  }

  /* poppins-300italic - latin */
  @font-face {
    font-family: 'Poppins';
    font-style: italic;
    font-weight: 300;
    src: url("../frontdata/fonts/poppins/poppins-v5-latin-300italic.eot");
    /* IE9 Compat Modes */
    src: local("Poppins Light Italic"), local("Poppins-LightItalic"), url("../frontdata/fonts/poppins/poppins-v5-latin-300italic.eot?#iefix") format("embedded-opentype"), url("../frontdata/fonts/poppins/poppins-v5-latin-300italic.woff2") format("woff2"), url("../frontdata/fonts/poppins/poppins-v5-latin-300italic.woff") format("woff"), url("../frontdata/fonts/poppins/poppins-v5-latin-300italic.ttf") format("truetype"), url("../frontdata/fonts/poppins/poppins-v5-latin-300italic.svg#Poppins") format("svg");
    /* Legacy iOS */
  }

  /* poppins-regular - latin */
  @font-face {
    font-family: 'Poppins';
    font-style: normal;
    font-weight: 400;
    src: url("../frontdata/fonts/poppins/poppins-v5-latin-regular.eot");
    /* IE9 Compat Modes */
    src: local("Poppins Regular"), local("Poppins-Regular"), url("../frontdata/fonts/poppins/poppins-v5-latin-regular.eot?#iefix") format("embedded-opentype"), url("../frontdata/fonts/poppins/poppins-v5-latin-regular.woff2") format("woff2"), url("../frontdata/fonts/poppins/poppins-v5-latin-regular.woff") format("woff"), url("../frontdata/fonts/poppins/poppins-v5-latin-regular.ttf") format("truetype"), url("../frontdata/fonts/poppins/poppins-v5-latin-regular.svg#Poppins") format("svg");
    /* Legacy iOS */
  }

  /* poppins-italic - latin */
  @font-face {
    font-family: 'Poppins';
    font-style: italic;
    font-weight: 400;
    src: url("../frontdata/fonts/poppins/poppins-v5-latin-italic.eot");
    /* IE9 Compat Modes */
    src: local("Poppins Italic"), local("Poppins-Italic"), url("../frontdata/fonts/poppins/poppins-v5-latin-italic.eot?#iefix") format("embedded-opentype"), url("../frontdata/fonts/poppins/poppins-v5-latin-italic.woff2") format("woff2"), url("../frontdata/fonts/poppins/poppins-v5-latin-italic.woff") format("woff"), url("../frontdata/fonts/poppins/poppins-v5-latin-italic.ttf") format("truetype"), url("../frontdata/fonts/poppins/poppins-v5-latin-italic.svg#Poppins") format("svg");
    /* Legacy iOS */
  }

  /* poppins-500 - latin */
  @font-face {
    font-family: 'Poppins';
    font-style: normal;
    font-weight: 500;
    src: url("../frontdata/fonts/poppins/poppins-v5-latin-500.eot");
    /* IE9 Compat Modes */
    src: local("Poppins Medium"), local("Poppins-Medium"), url("../frontdata/fonts/poppins/poppins-v5-latin-500.eot?#iefix") format("embedded-opentype"), url("../frontdata/fonts/poppins/poppins-v5-latin-500.woff2") format("woff2"), url("../frontdata/fonts/poppins/poppins-v5-latin-500.woff") format("woff"), url("../frontdata/fonts/poppins/poppins-v5-latin-500.ttf") format("truetype"), url("../frontdata/fonts/poppins/poppins-v5-latin-500.svg#Poppins") format("svg");
    /* Legacy iOS */
  }

  /* poppins-500italic - latin */
  @font-face {
    font-family: 'Poppins';
    font-style: italic;
    font-weight: 500;
    src: url("../frontdata/fonts/poppins/poppins-v5-latin-500italic.eot");
    /* IE9 Compat Modes */
    src: local("Poppins Medium Italic"), local("Poppins-MediumItalic"), url("../frontdata/fonts/poppins/poppins-v5-latin-500italic.eot?#iefix") format("embedded-opentype"), url("../frontdata/fonts/poppins/poppins-v5-latin-500italic.woff2") format("woff2"), url("../frontdata/fonts/poppins/poppins-v5-latin-500italic.woff") format("woff"), url("../frontdata/fonts/poppins/poppins-v5-latin-500italic.ttf") format("truetype"), url("../frontdata/fonts/poppins/poppins-v5-latin-500italic.svg#Poppins") format("svg");
    /* Legacy iOS */
  }

  /* poppins-600 - latin */
  @font-face {
    font-family: 'Poppins';
    font-style: normal;
    font-weight: 600;
    src: url("../frontdata/fonts/poppins/poppins-v5-latin-600.eot");
    /* IE9 Compat Modes */
    src: local("Poppins SemiBold"), local("Poppins-SemiBold"), url("../frontdata/fonts/poppins/poppins-v5-latin-600.eot?#iefix") format("embedded-opentype"), url("../frontdata/fonts/poppins/poppins-v5-latin-600.woff2") format("woff2"), url("../frontdata/fonts/poppins/poppins-v5-latin-600.woff") format("woff"), url("../frontdata/fonts/poppins/poppins-v5-latin-600.ttf") format("truetype"), url("../frontdata/fonts/poppins/poppins-v5-latin-600.svg#Poppins") format("svg");
    /* Legacy iOS */
  }

  /* poppins-700 - latin */
  @font-face {
    font-family: 'Poppins';
    font-style: normal;
    font-weight: 700;
    src: url("../frontdata/fonts/poppins/poppins-v5-latin-700.eot");
    /* IE9 Compat Modes */
    src: local("Poppins Bold"), local("Poppins-Bold"), url("../frontdata/fonts/poppins/poppins-v5-latin-700.eot?#iefix") format("embedded-opentype"), url("../frontdata/fonts/poppins/poppins-v5-latin-700.woff2") format("woff2"), url("../frontdata/fonts/poppins/poppins-v5-latin-700.woff") format("woff"), url("../frontdata/fonts/poppins/poppins-v5-latin-700.ttf") format("truetype"), url("../frontdata/fonts/poppins/poppins-v5-latin-700.svg#Poppins") format("svg");
    /* Legacy iOS */
  }

  /* poppins-700italic - latin */
  @font-face {
    font-family: 'Poppins';
    font-style: italic;
    font-weight: 700;
    src: url("../frontdata/fonts/poppins/poppins-v5-latin-700italic.eot");
    /* IE9 Compat Modes */
    src: local("Poppins Bold Italic"), local("Poppins-BoldItalic"), url("../frontdata/fonts/poppins/poppins-v5-latin-700italic.eot?#iefix") format("embedded-opentype"), url("../frontdata/fonts/poppins/poppins-v5-latin-700italic.woff2") format("woff2"), url("../frontdata/fonts/poppins/poppins-v5-latin-700italic.woff") format("woff"), url("../frontdata/fonts/poppins/poppins-v5-latin-700italic.ttf") format("truetype"), url("../frontdata/fonts/poppins/poppins-v5-latin-700italic.svg#Poppins") format("svg");
    /* Legacy iOS */
  }

  /* poppins-800 - latin */
  @font-face {
    font-family: 'Poppins';
    font-style: normal;
    font-weight: 800;
    src: url("../frontdata/fonts/poppins/poppins-v5-latin-800.eot");
    /* IE9 Compat Modes */
    src: local("Poppins ExtraBold"), local("Poppins-ExtraBold"), url("../frontdata/fonts/poppins/poppins-v5-latin-800.eot?#iefix") format("embedded-opentype"), url("../frontdata/fonts/poppins/poppins-v5-latin-800.woff2") format("woff2"), url("../frontdata/fonts/poppins/poppins-v5-latin-800.woff") format("woff"), url("../frontdata/fonts/poppins/poppins-v5-latin-800.ttf") format("truetype"), url("../frontdata/fonts/poppins/poppins-v5-latin-800.svg#Poppins") format("svg");
    /* Legacy iOS */
  }

  /* poppins-800italic - latin */
  @font-face {
    font-family: 'Poppins';
    font-style: italic;
    font-weight: 800;
    src: url("../frontdata/fonts/poppins/poppins-v5-latin-800italic.eot");
    /* IE9 Compat Modes */
    src: local("Poppins ExtraBold Italic"), local("Poppins-ExtraBoldItalic"), url("../frontdata/fonts/poppins/poppins-v5-latin-800italic.eot?#iefix") format("embedded-opentype"), url("../frontdata/fonts/poppins/poppins-v5-latin-800italic.woff2") format("woff2"), url("../frontdata/fonts/poppins/poppins-v5-latin-800italic.woff") format("woff"), url("../frontdata/fonts/poppins/poppins-v5-latin-800italic.ttf") format("truetype"), url("../frontdata/fonts/poppins/poppins-v5-latin-800italic.svg#Poppins") format("svg");
    /* Legacy iOS */
  }

  /* poppins-900 - latin */
  @font-face {
    font-family: 'Poppins';
    font-style: normal;
    font-weight: 900;
    src: url("../frontdata/fonts/poppins/poppins-v5-latin-900.eot");
    /* IE9 Compat Modes */
    src: local("Poppins Black"), local("Poppins-Black"), url("../frontdata/fonts/poppins/poppins-v5-latin-900.eot?#iefix") format("embedded-opentype"), url("../frontdata/fonts/poppins/poppins-v5-latin-900.woff2") format("woff2"), url("../frontdata/fonts/poppins/poppins-v5-latin-900.woff") format("woff"), url("../frontdata/fonts/poppins/poppins-v5-latin-900.ttf") format("truetype"), url("../frontdata/fonts/poppins/poppins-v5-latin-900.svg#Poppins") format("svg");
    /* Legacy iOS */
  }

  a:focus,
  a:active {
    text-decoration: none;
    outline: none;
    transition: all 300ms ease 0s;
    -moz-transition: all 300ms ease 0s;
    -webkit-transition: all 300ms ease 0s;
    -o-transition: all 300ms ease 0s;
    -ms-transition: all 300ms ease 0s;
  }

  input,
  select,
  textarea {
    outline: none;
    appearance: unset !important;
    -moz-appearance: unset !important;
    -webkit-appearance: unset !important;
    -o-appearance: unset !important;
    -ms-appearance: unset !important;
  }

  input::-webkit-outer-spin-button,
  input::-webkit-inner-spin-button {
    appearance: none !important;
    -moz-appearance: none !important;
    -webkit-appearance: none !important;
    -o-appearance: none !important;
    -ms-appearance: none !important;
    margin: 0;
  }

  input:focus,
  select:focus,
  textarea:focus {
    outline: none;
    box-shadow: none !important;
    -moz-box-shadow: none !important;
    -webkit-box-shadow: none !important;
    -o-box-shadow: none !important;
    -ms-box-shadow: none !important;
  }

  input[type=checkbox] {
    appearance: checkbox !important;
    -moz-appearance: checkbox !important;
    -webkit-appearance: checkbox !important;
    -o-appearance: checkbox !important;
    -ms-appearance: checkbox !important;
  }

  input[type=radio] {
    appearance: radio !important;
    -moz-appearance: radio !important;
    -webkit-appearance: radio !important;
    -o-appearance: radio !important;
    -ms-appearance: radio !important;
  }

  img {
    max-width: 100%;
    height: auto;
  }

  figure {
    margin: 0;
  }

  p {
    margin-bottom: 0px;
    font-size: 15px;
    color: #777;
  }

  h2 {
    line-height: 1.66;
    margin: 0;
    padding: 0;
    font-weight: bold;
    color: #222;
    font-family: Poppins;
    font-size: 36px;
  }

  .main {
    background: #f8f8f8;
    padding: 150px 0;
  }

  .clear {
    clear: both;
  }

  body {
    font-size: 13px;
    line-height: 1.8;
    color: #222;
    background: #f8f8f8;
    font-weight: 400;
    font-family: Poppins;
  }

  .container {
    width: 900px;
    background: #fff;
    margin: 0 auto;
    box-shadow: 0px 15px 16.83px 0.17px rgba(0, 0, 0, 0.05);
    -moz-box-shadow: 0px 15px 16.83px 0.17px rgba(0, 0, 0, 0.05);
    -webkit-box-shadow: 0px 15px 16.83px 0.17px rgba(0, 0, 0, 0.05);
    -o-box-shadow: 0px 15px 16.83px 0.17px rgba(0, 0, 0, 0.05);
    -ms-box-shadow: 0px 15px 16.83px 0.17px rgba(0, 0, 0, 0.05);
    border-radius: 20px;
    -moz-border-radius: 20px;
    -webkit-border-radius: 20px;
    -o-border-radius: 20px;
    -ms-border-radius: 20px;
  }

  .display-flex {
    justify-content: space-between;
    -moz-justify-content: space-between;
    -webkit-justify-content: space-between;
    -o-justify-content: space-between;
    -ms-justify-content: space-between;
    align-items: center;
    -moz-align-items: center;
    -webkit-align-items: center;
    -o-align-items: center;
    -ms-align-items: center;
  }

  .display-flex-center {
    justify-content: center;
    -moz-justify-content: center;
    -webkit-justify-content: center;
    -o-justify-content: center;
    -ms-justify-content: center;
    align-items: center;
    -moz-align-items: center;
    -webkit-align-items: center;
    -o-align-items: center;
    -ms-align-items: center;
  }

  .position-center {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    -moz-transform: translate(-50%, -50%);
    -webkit-transform: translate(-50%, -50%);
    -o-transform: translate(-50%, -50%);
    -ms-transform: translate(-50%, -50%);
  }

  .signup {
    margin-bottom: 150px;
  }

  .signup-content {
    padding: 75px 0;
  }

  .signup-form,
  .signup-image,
  .signin-form,
  .signin-image {
    width: 50%;
    overflow: hidden;
  }

  .signup-image {
    margin: 0 55px;
  }

  .form-title {
    margin-bottom: 33px;
  }

  .signup-image {
    margin-top: 45px;
  }

  figure {
    margin-bottom: 50px;
    text-align: center;
  }

  .form-submit {
    display: inline-block;
    background: #6dabe4;
    color: #fff;
    border-bottom: none;
    width: auto;
    padding: 15px 39px;
    border-radius: 5px;
    -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    -o-border-radius: 5px;
    -ms-border-radius: 5px;
    margin-top: 25px;
    cursor: pointer;
  }

  .form-submit:hover {
    background: #4292dc;
  }

  #signin {
    margin-top: 16px;
  }

  .signup-image-link {
    font-size: 14px;
    color: #222;
    display: block;
    text-align: center;
  }

  .term-service {
    font-size: 13px;
    color: #222;
  }

  .signup-form {
    margin-left: 75px;
    margin-right: 75px;
    padding-left: 34px;
  }

  .register-form {
    width: 100%;
  }

  .form-group {
    position: relative;
    margin-bottom: 25px;
    overflow: hidden;
  }

  .form-group:last-child {
    margin-bottom: 0px;
  }

  input {
    width: 100%;
    display: block;
    border: none;
    border-bottom: 1px solid #999;
    padding: 6px 30px;
    font-family: Poppins;
    box-sizing: border-box;
  }

  input::-webkit-input-placeholder {
    color: #999;
  }

  input::-moz-placeholder {
    color: #999;
  }

  input:-ms-input-placeholder {
    color: #999;
  }

  input:-moz-placeholder {
    color: #999;
  }

  input:focus {
    border-bottom: 1px solid #222;
  }

  input:focus::-webkit-input-placeholder {
    color: #222;
  }

  input:focus::-moz-placeholder {
    color: #222;
  }

  input:focus:-ms-input-placeholder {
    color: #222;
  }

  input:focus:-moz-placeholder {
    color: #222;
  }

  input[type=checkbox]:not(old) {
    width: 2em;
    margin: 0;
    padding: 0;
    font-size: 1em;
    display: none;
  }

  input[type=checkbox]:not(old)+label {
    display: inline-block;
    line-height: 1.5em;
    margin-top: 6px;
  }

  input[type=checkbox]:not(old)+label>span {
    display: inline-block;
    width: 13px;
    height: 13px;
    margin-right: 15px;
    margin-bottom: 3px;
    border: 1px solid #999;
    border-radius: 2px;
    -moz-border-radius: 2px;
    -webkit-border-radius: 2px;
    -o-border-radius: 2px;
    -ms-border-radius: 2px;
    background: white;
    background-image: -moz-linear-gradient(white, white);
    background-image: -ms-linear-gradient(white, white);
    background-image: -o-linear-gradient(white, white);
    background-image: -webkit-linear-gradient(white, white);
    background-image: linear-gradient(white, white);
    vertical-align: bottom;
  }

  input[type=checkbox]:not(old):checked+label>span {
    background-image: -moz-linear-gradient(white, white);
    background-image: -ms-linear-gradient(white, white);
    background-image: -o-linear-gradient(white, white);
    background-image: -webkit-linear-gradient(white, white);
    background-image: linear-gradient(white, white);
  }

  input[type=checkbox]:not(old):checked+label>span:before {
    content: '\f26b';
    display: block;
    color: #222;
    font-size: 11px;
    line-height: 1.2;
    text-align: center;
    font-family: 'Material-Design-Iconic-Font';
    font-weight: bold;
  }

  .agree-term {
    display: inline-block;
    width: auto;
  }

  label {
    position: absolute;
    left: 0;
    top: 50%;
    transform: translateY(-50%);
    -moz-transform: translateY(-50%);
    -webkit-transform: translateY(-50%);
    -o-transform: translateY(-50%);
    -ms-transform: translateY(-50%);
    color: #222;
  }

  .label-has-error {
    top: 22%;
  }

  label.error {
    position: relative;
    background: url("../images/unchecked.gif") no-repeat;
    background-position-y: 3px;
    padding-left: 20px;
    display: block;
    margin-top: 20px;
  }

  label.valid {
    display: block;
    position: absolute;
    right: 0;
    left: auto;
    margin-top: -6px;
    width: 20px;
    height: 20px;
    background: transparent;
  }

  label.valid:after {
    font-family: 'Material-Design-Iconic-Font';
    content: '\f269';
    width: 100%;
    height: 100%;
    position: absolute;
    /* right: 0; */
    font-size: 16px;
    color: green;
  }

  .label-agree-term {
    position: relative;
    top: 0%;
    transform: translateY(0);
    -moz-transform: translateY(0);
    -webkit-transform: translateY(0);
    -o-transform: translateY(0);
    -ms-transform: translateY(0);
  }

  .material-icons-name {
    font-size: 18px;
  }

  .signin-content {
    padding-top: 67px;
    padding-bottom: 87px;
  }

  .social-login {
    align-items: center;
    -moz-align-items: center;
    -webkit-align-items: center;
    -o-align-items: center;
    -ms-align-items: center;
    margin-top: 80px;
  }

  .social-label {
    display: inline-block;
    margin-right: 15px;
  }

  .socials li {
    padding: 5px;
  }

  .socials li:last-child {
    margin-right: 0px;
  }

  .socials li a {
    text-decoration: none;
  }

  .socials li a i {
    width: 30px;
    height: 30px;
    color: #fff;
    font-size: 14px;
    border-radius: 5px;
    -moz-border-radius: 5px;
    -webkit-border-radius: 5px;
    -o-border-radius: 5px;
    -ms-border-radius: 5px;
    transform: translateZ(0);
    -moz-transform: translateZ(0);
    -webkit-transform: translateZ(0);
    -o-transform: translateZ(0);
    -ms-transform: translateZ(0);
    -webkit-transition-duration: 0.3s;
    transition-duration: 0.3s;
    -webkit-transition-property: transform;
    transition-property: transform;
    -webkit-transition-timing-function: ease-out;
    transition-timing-function: ease-out;
  }

  .socials li:hover a i {
    -webkit-transform: scale(1.3) translateZ(0);
    transform: scale(1.3) translateZ(0);
  }

  .zmdi-facebook {
    background: #3b5998;
  }

  .zmdi-twitter {
    background: #1da0f2;
  }

  .zmdi-google {
    background: #e72734;
  }

  .signin-form {
    margin-right: 90px;
    margin-left: 80px;
  }

  .signin-image {
    margin-left: 110px;
    margin-right: 20px;
    margin-top: 10px;
  }

  @media screen and (max-width: 1200px) {
    .container {
      width: calc(100% - 30px);
      max-width: 100%;
    }
  }

  @media screen and (min-width: 1024px) {
    .container {
      max-width: 1200px;
    }
  }

  @media screen and (max-width: 768px) {

    .signup-content,
    .signin-content {
      flex-direction: column;
      -moz-flex-direction: column;
      -webkit-flex-direction: column;
      -o-flex-direction: column;
      -ms-flex-direction: column;
      justify-content: center;
      -moz-justify-content: center;
      -webkit-justify-content: center;
      -o-justify-content: center;
      -ms-justify-content: center;
    }

    .signup-form {
      margin-left: 0px;
      margin-right: 0px;
      padding-left: 0px;
      /* box-sizing: border-box; */
      padding: 0 30px;
    }

    .signin-image {
      margin-left: 0px;
      margin-right: 0px;
      margin-top: 50px;
      order: 2;
      -moz-order: 2;
      -webkit-order: 2;
      -o-order: 2;
      -ms-order: 2;
    }

    .signup-form,
    .signup-image,
    .signin-form,
    .signin-image {
      width: auto;
    }

    .social-login {
      justify-content: center;
      -moz-justify-content: center;
      -webkit-justify-content: center;
      -o-justify-content: center;
      -ms-justify-content: center;
    }

    .form-button {
      text-align: center;
    }

    .signin-form {
      order: 1;
      -moz-order: 1;
      -webkit-order: 1;
      -o-order: 1;
      -ms-order: 1;
      margin-right: 0px;
      margin-left: 0px;
      padding: 0 30px;
    }

    .form-title {
      text-align: center;
    }
  }

  @media screen and (max-width: 400px) {
    .social-login {
      flex-direction: column;
      -moz-flex-direction: column;
      -webkit-flex-direction: column;
      -o-flex-direction: column;
      -ms-flex-direction: column;
    }

    .social-label {
      margin-right: 0px;
      margin-bottom: 10px;
    }
  }

  /*# sourceMappingURL=style.css.map */
</style>
<script src="//code.jquery.com/jquery-1.11.2.min.js"></script>
<!-- JS file -->
<script src="{{url('frontdata/autocomplete')}}/jquery.easy-autocomplete.min.js"></script>

<!-- CSS file -->
<link rel="stylesheet" href="{{url('frontdata/autocomplete')}}/easy-autocomplete.min.css">

<!-- Additional CSS Themes file - not required-->
<link rel="stylesheet" href="{{url('frontdata/autocomplete')}}/easy-autocomplete.themes.min.css">

<script src="{{ url('frontdata/js/main.js') }}"></script>