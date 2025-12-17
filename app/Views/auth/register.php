<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Trade Flow Globalex</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/style.css">
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" defer></script>
    <style>
        .auth-box-wide {
            max-width: 700px;
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        .password-wrapper {
            position: relative;
        }
        .password-toggle {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            padding: 4px;
        }
        .password-toggle:hover {
            color: var(--text-primary);
        }
        .phone-input-wrapper {
            display: flex;
            gap: 8px;
        }
        .phone-code {
            width: 90px;
            text-align: center;
            background: var(--bg-secondary);
        }
        @media (max-width: 600px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-box auth-box-wide">
            <div class="auth-logo">
                <h1>TradeFlow</h1>
            </div>
            
            <h2 class="auth-title">Sign Up for Free</h2>
            <p class="auth-subtitle">It's Free to Sign up and only takes a minute.</p>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST" action="/register">
                <input type="hidden" name="_csrf_token" value="<?= $csrf_token ?? '' ?>">
                
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="name">Full Name</label>
                        <input type="text" id="name" name="name" class="form-control" placeholder="Enter your Name" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="username">Username</label>
                        <input type="text" id="username" name="username" class="form-control" placeholder="Enter Preferred Username" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="email">Email</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="gender">Gender</label>
                        <select id="gender" name="gender" class="form-control">
                            <option value="">Select Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="country">Country</label>
                        <select id="country" name="country" class="form-control" onchange="updatePhoneCode()">
                            <option value="" data-code="">Select Country</option>
                            <option value="AF" data-code="+93">Afghanistan</option>
                            <option value="AX" data-code="+358">Aland Islands</option>
                            <option value="AL" data-code="+355">Albania</option>
                            <option value="DZ" data-code="+213">Algeria</option>
                            <option value="AS" data-code="+1684">American Samoa</option>
                            <option value="AD" data-code="+376">Andorra</option>
                            <option value="AO" data-code="+244">Angola</option>
                            <option value="AI" data-code="+1264">Anguilla</option>
                            <option value="AQ" data-code="+672">Antarctica</option>
                            <option value="AG" data-code="+1268">Antigua and Barbuda</option>
                            <option value="AR" data-code="+54">Argentina</option>
                            <option value="AM" data-code="+374">Armenia</option>
                            <option value="AW" data-code="+297">Aruba</option>
                            <option value="AU" data-code="+61">Australia</option>
                            <option value="AT" data-code="+43">Austria</option>
                            <option value="AZ" data-code="+994">Azerbaijan</option>
                            <option value="BS" data-code="+1242">Bahamas</option>
                            <option value="BH" data-code="+973">Bahrain</option>
                            <option value="BD" data-code="+880">Bangladesh</option>
                            <option value="BB" data-code="+1246">Barbados</option>
                            <option value="BY" data-code="+375">Belarus</option>
                            <option value="BE" data-code="+32">Belgium</option>
                            <option value="BZ" data-code="+501">Belize</option>
                            <option value="BJ" data-code="+229">Benin</option>
                            <option value="BM" data-code="+1441">Bermuda</option>
                            <option value="BT" data-code="+975">Bhutan</option>
                            <option value="BO" data-code="+591">Bolivia</option>
                            <option value="BQ" data-code="+599">Bonaire</option>
                            <option value="BA" data-code="+387">Bosnia and Herzegovina</option>
                            <option value="BW" data-code="+267">Botswana</option>
                            <option value="BR" data-code="+55">Brazil</option>
                            <option value="IO" data-code="+246">British Indian Ocean Territory</option>
                            <option value="BN" data-code="+673">Brunei Darussalam</option>
                            <option value="BG" data-code="+359">Bulgaria</option>
                            <option value="BF" data-code="+226">Burkina Faso</option>
                            <option value="BI" data-code="+257">Burundi</option>
                            <option value="CV" data-code="+238">Cabo Verde</option>
                            <option value="KH" data-code="+855">Cambodia</option>
                            <option value="CM" data-code="+237">Cameroon</option>
                            <option value="CA" data-code="+1">Canada</option>
                            <option value="KY" data-code="+1345">Cayman Islands</option>
                            <option value="CF" data-code="+236">Central African Republic</option>
                            <option value="TD" data-code="+235">Chad</option>
                            <option value="CL" data-code="+56">Chile</option>
                            <option value="CN" data-code="+86">China</option>
                            <option value="CO" data-code="+57">Colombia</option>
                            <option value="KM" data-code="+269">Comoros</option>
                            <option value="CG" data-code="+242">Congo</option>
                            <option value="CD" data-code="+243">Congo (DRC)</option>
                            <option value="CR" data-code="+506">Costa Rica</option>
                            <option value="CI" data-code="+225">Cote d'Ivoire</option>
                            <option value="HR" data-code="+385">Croatia</option>
                            <option value="CU" data-code="+53">Cuba</option>
                            <option value="CW" data-code="+599">Curacao</option>
                            <option value="CY" data-code="+357">Cyprus</option>
                            <option value="CZ" data-code="+420">Czechia</option>
                            <option value="DK" data-code="+45">Denmark</option>
                            <option value="DJ" data-code="+253">Djibouti</option>
                            <option value="DM" data-code="+1767">Dominica</option>
                            <option value="DO" data-code="+1809">Dominican Republic</option>
                            <option value="EC" data-code="+593">Ecuador</option>
                            <option value="EG" data-code="+20">Egypt</option>
                            <option value="SV" data-code="+503">El Salvador</option>
                            <option value="GQ" data-code="+240">Equatorial Guinea</option>
                            <option value="ER" data-code="+291">Eritrea</option>
                            <option value="EE" data-code="+372">Estonia</option>
                            <option value="SZ" data-code="+268">Eswatini</option>
                            <option value="ET" data-code="+251">Ethiopia</option>
                            <option value="FJ" data-code="+679">Fiji</option>
                            <option value="FI" data-code="+358">Finland</option>
                            <option value="FR" data-code="+33">France</option>
                            <option value="GF" data-code="+594">French Guiana</option>
                            <option value="PF" data-code="+689">French Polynesia</option>
                            <option value="GA" data-code="+241">Gabon</option>
                            <option value="GM" data-code="+220">Gambia</option>
                            <option value="GE" data-code="+995">Georgia</option>
                            <option value="DE" data-code="+49">Germany</option>
                            <option value="GH" data-code="+233">Ghana</option>
                            <option value="GI" data-code="+350">Gibraltar</option>
                            <option value="GR" data-code="+30">Greece</option>
                            <option value="GL" data-code="+299">Greenland</option>
                            <option value="GD" data-code="+1473">Grenada</option>
                            <option value="GP" data-code="+590">Guadeloupe</option>
                            <option value="GU" data-code="+1671">Guam</option>
                            <option value="GT" data-code="+502">Guatemala</option>
                            <option value="GN" data-code="+224">Guinea</option>
                            <option value="GW" data-code="+245">Guinea-Bissau</option>
                            <option value="GY" data-code="+592">Guyana</option>
                            <option value="HT" data-code="+509">Haiti</option>
                            <option value="HN" data-code="+504">Honduras</option>
                            <option value="HK" data-code="+852">Hong Kong</option>
                            <option value="HU" data-code="+36">Hungary</option>
                            <option value="IS" data-code="+354">Iceland</option>
                            <option value="IN" data-code="+91">India</option>
                            <option value="ID" data-code="+62">Indonesia</option>
                            <option value="IR" data-code="+98">Iran</option>
                            <option value="IQ" data-code="+964">Iraq</option>
                            <option value="IE" data-code="+353">Ireland</option>
                            <option value="IL" data-code="+972">Israel</option>
                            <option value="IT" data-code="+39">Italy</option>
                            <option value="JM" data-code="+1876">Jamaica</option>
                            <option value="JP" data-code="+81">Japan</option>
                            <option value="JO" data-code="+962">Jordan</option>
                            <option value="KZ" data-code="+7">Kazakhstan</option>
                            <option value="KE" data-code="+254">Kenya</option>
                            <option value="KI" data-code="+686">Kiribati</option>
                            <option value="KP" data-code="+850">Korea (North)</option>
                            <option value="KR" data-code="+82">Korea (South)</option>
                            <option value="KW" data-code="+965">Kuwait</option>
                            <option value="KG" data-code="+996">Kyrgyzstan</option>
                            <option value="LA" data-code="+856">Laos</option>
                            <option value="LV" data-code="+371">Latvia</option>
                            <option value="LB" data-code="+961">Lebanon</option>
                            <option value="LS" data-code="+266">Lesotho</option>
                            <option value="LR" data-code="+231">Liberia</option>
                            <option value="LY" data-code="+218">Libya</option>
                            <option value="LI" data-code="+423">Liechtenstein</option>
                            <option value="LT" data-code="+370">Lithuania</option>
                            <option value="LU" data-code="+352">Luxembourg</option>
                            <option value="MO" data-code="+853">Macao</option>
                            <option value="MG" data-code="+261">Madagascar</option>
                            <option value="MW" data-code="+265">Malawi</option>
                            <option value="MY" data-code="+60">Malaysia</option>
                            <option value="MV" data-code="+960">Maldives</option>
                            <option value="ML" data-code="+223">Mali</option>
                            <option value="MT" data-code="+356">Malta</option>
                            <option value="MH" data-code="+692">Marshall Islands</option>
                            <option value="MQ" data-code="+596">Martinique</option>
                            <option value="MR" data-code="+222">Mauritania</option>
                            <option value="MU" data-code="+230">Mauritius</option>
                            <option value="MX" data-code="+52">Mexico</option>
                            <option value="FM" data-code="+691">Micronesia</option>
                            <option value="MD" data-code="+373">Moldova</option>
                            <option value="MC" data-code="+377">Monaco</option>
                            <option value="MN" data-code="+976">Mongolia</option>
                            <option value="ME" data-code="+382">Montenegro</option>
                            <option value="MS" data-code="+1664">Montserrat</option>
                            <option value="MA" data-code="+212">Morocco</option>
                            <option value="MZ" data-code="+258">Mozambique</option>
                            <option value="MM" data-code="+95">Myanmar</option>
                            <option value="NA" data-code="+264">Namibia</option>
                            <option value="NR" data-code="+674">Nauru</option>
                            <option value="NP" data-code="+977">Nepal</option>
                            <option value="NL" data-code="+31">Netherlands</option>
                            <option value="NC" data-code="+687">New Caledonia</option>
                            <option value="NZ" data-code="+64">New Zealand</option>
                            <option value="NI" data-code="+505">Nicaragua</option>
                            <option value="NE" data-code="+227">Niger</option>
                            <option value="NG" data-code="+234">Nigeria</option>
                            <option value="NO" data-code="+47">Norway</option>
                            <option value="OM" data-code="+968">Oman</option>
                            <option value="PK" data-code="+92">Pakistan</option>
                            <option value="PW" data-code="+680">Palau</option>
                            <option value="PS" data-code="+970">Palestine</option>
                            <option value="PA" data-code="+507">Panama</option>
                            <option value="PG" data-code="+675">Papua New Guinea</option>
                            <option value="PY" data-code="+595">Paraguay</option>
                            <option value="PE" data-code="+51">Peru</option>
                            <option value="PH" data-code="+63">Philippines</option>
                            <option value="PL" data-code="+48">Poland</option>
                            <option value="PT" data-code="+351">Portugal</option>
                            <option value="PR" data-code="+1787">Puerto Rico</option>
                            <option value="QA" data-code="+974">Qatar</option>
                            <option value="MK" data-code="+389">North Macedonia</option>
                            <option value="RO" data-code="+40">Romania</option>
                            <option value="RU" data-code="+7">Russian Federation</option>
                            <option value="RW" data-code="+250">Rwanda</option>
                            <option value="RE" data-code="+262">Reunion</option>
                            <option value="KN" data-code="+1869">Saint Kitts and Nevis</option>
                            <option value="LC" data-code="+1758">Saint Lucia</option>
                            <option value="VC" data-code="+1784">Saint Vincent</option>
                            <option value="WS" data-code="+685">Samoa</option>
                            <option value="SM" data-code="+378">San Marino</option>
                            <option value="ST" data-code="+239">Sao Tome and Principe</option>
                            <option value="SA" data-code="+966">Saudi Arabia</option>
                            <option value="SN" data-code="+221">Senegal</option>
                            <option value="RS" data-code="+381">Serbia</option>
                            <option value="SC" data-code="+248">Seychelles</option>
                            <option value="SL" data-code="+232">Sierra Leone</option>
                            <option value="SG" data-code="+65">Singapore</option>
                            <option value="SK" data-code="+421">Slovakia</option>
                            <option value="SI" data-code="+386">Slovenia</option>
                            <option value="SB" data-code="+677">Solomon Islands</option>
                            <option value="SO" data-code="+252">Somalia</option>
                            <option value="ZA" data-code="+27">South Africa</option>
                            <option value="SS" data-code="+211">South Sudan</option>
                            <option value="ES" data-code="+34">Spain</option>
                            <option value="LK" data-code="+94">Sri Lanka</option>
                            <option value="SD" data-code="+249">Sudan</option>
                            <option value="SR" data-code="+597">Suriname</option>
                            <option value="SE" data-code="+46">Sweden</option>
                            <option value="CH" data-code="+41">Switzerland</option>
                            <option value="SY" data-code="+963">Syrian Arab Republic</option>
                            <option value="TW" data-code="+886">Taiwan</option>
                            <option value="TJ" data-code="+992">Tajikistan</option>
                            <option value="TZ" data-code="+255">Tanzania</option>
                            <option value="TH" data-code="+66">Thailand</option>
                            <option value="TL" data-code="+670">Timor-Leste</option>
                            <option value="TG" data-code="+228">Togo</option>
                            <option value="TO" data-code="+676">Tonga</option>
                            <option value="TT" data-code="+1868">Trinidad and Tobago</option>
                            <option value="TN" data-code="+216">Tunisia</option>
                            <option value="TR" data-code="+90">Turkey</option>
                            <option value="TM" data-code="+993">Turkmenistan</option>
                            <option value="TC" data-code="+1649">Turks and Caicos Islands</option>
                            <option value="TV" data-code="+688">Tuvalu</option>
                            <option value="UG" data-code="+256">Uganda</option>
                            <option value="UA" data-code="+380">Ukraine</option>
                            <option value="AE" data-code="+971">United Arab Emirates</option>
                            <option value="GB" data-code="+44">United Kingdom</option>
                            <option value="US" data-code="+1">United States</option>
                            <option value="UY" data-code="+598">Uruguay</option>
                            <option value="UZ" data-code="+998">Uzbekistan</option>
                            <option value="VU" data-code="+678">Vanuatu</option>
                            <option value="VE" data-code="+58">Venezuela</option>
                            <option value="VN" data-code="+84">Viet Nam</option>
                            <option value="VG" data-code="+1284">Virgin Islands (British)</option>
                            <option value="VI" data-code="+1340">Virgin Islands (U.S.)</option>
                            <option value="YE" data-code="+967">Yemen</option>
                            <option value="ZM" data-code="+260">Zambia</option>
                            <option value="ZW" data-code="+263">Zimbabwe</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="phone">Phone</label>
                        <div class="phone-input-wrapper">
                            <input type="text" id="phone_code" class="form-control phone-code" readonly placeholder="+">
                            <input type="tel" id="phone" name="phone" class="form-control" style="flex: 1;" placeholder="Enter your phone number">
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label" for="password">Password</label>
                        <div class="password-wrapper">
                            <input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required minlength="8">
                            <button type="button" class="password-toggle" onclick="togglePassword('password', this)">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="confirm_password">Confirm Password</label>
                        <div class="password-wrapper">
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('confirm_password', this)">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="cf-turnstile" data-sitekey="<?= $turnstile_site_key ?? '' ?>" data-theme="dark"></div>
                </div>

                <button type="submit" class="btn btn-primary btn-block mt-4">Sign Up</button>
            </form>

            <p class="text-center mt-4" style="color: var(--text-secondary);">
                Already have an account? <a href="/login">Sign in</a>
            </p>
        </div>
    </div>

    <script>
        function updatePhoneCode() {
            const countrySelect = document.getElementById('country');
            const phoneCodeInput = document.getElementById('phone_code');
            const selectedOption = countrySelect.options[countrySelect.selectedIndex];
            const code = selectedOption.getAttribute('data-code');
            phoneCodeInput.value = code || '';
        }

        function togglePassword(fieldId, btn) {
            const field = document.getElementById(fieldId);
            if (field.type === 'password') {
                field.type = 'text';
            } else {
                field.type = 'password';
            }
        }
    </script>
</body>
</html>
