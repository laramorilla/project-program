<?php
$jQueryValidation = '
<script>
var date = new Date();
$(function() {
	$("#departuredate").datepicker({ 
            dateFormat: "dd-mm-yy",
            defaultDate: new Date(2017, 8, 25),
            beforeShowDay: function(date) {
                var disabledDays = ["2017-9-25","2017-9-26","2017-9-27","2017-9-28","2017-9-29"];

                var m = date.getMonth(), d = date.getDate(), y = date.getFullYear();
                for (i = 0; i < disabledDays.length; i++) {
                    if($.inArray(y + "-" + (m+1) + "-" + d,disabledDays) != -1) {
                        //return [false];
                        return [true, "ui-state-active", "splc2017"];
                    }
                }
                return [true,"",""];
            }
        }); 
	 $("#arrivaldate").datepicker({ 
            dateFormat: "dd-mm-yy",
            defaultDate: new Date(2017, 8, 25),
            beforeShowDay: function(date) {
                var disabledDays = ["2017-9-25","2017-9-26","2017-9-27","2017-9-28","2017-9-29"];

                var m = date.getMonth(), d = date.getDate(), y = date.getFullYear();
                for (i = 0; i < disabledDays.length; i++) {
                    if($.inArray(y + "-" + (m+1) + "-" + d,disabledDays) != -1) {
                        //return [false];
                        return [true, "ui-state-active", "SPLC 2017"];
                    }
                }
                return [true,"",""];
            }
        }); 
});
</script>
<script>$(document).ready(function() {
	calculateTotal();
    $.validator.methods.email = function( value, element ) {
        return this.optional( element ) || /^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$/.test( value );
    }
    // validate signup form on keyup and submit
    $("#registrationform").validate({
      rules: {
        firstname: "required",
        lastname: "required",
        email: {
          required: true,
          email: true
        },
        affiliation: "required",
        profile: "required",
        country:"required"
      },
      messages: {
        firstname: "Please enter your firstname",
        lastname: "Please enter your lastname",
        email: "Please enter a valid email address",
        affiliation: "Please enter your affiliation",
        profile: "Please enter your desired name in the badge",
        country:"Please enter your country"
      }
    });
});
</script>';

function registration_form($registrationinfo, $student = false, $params = array()){
    wp_enqueue_script( 'scriptvalidation', plugin_dir_url( __FILE__ ) . '/js/jquery.validate.min.js', array( 'jquery' ), '1.0.0', true );
    wp_enqueue_script( 'scriptdatepicker', plugin_dir_url( __FILE__ ) . '/js/jquery-ui.min.js', array( 'jquery' ), '1.0.0', true );
    
	global $jQueryValidation;
    $specialCode = "";
    if (isset($params["specialCode"])){
        $specialCode = $params["specialCode"];
    }
    $currentPeriod = get_option('splcregistration_settings_input')["paymentperiod"];
    $periods = array(0=>"Early Registration", 1=>"Regular Registration", 2=>"Late/On Conference");
    $prices = array(array(180,210,240),array(180,210,240),array(360,420,480),array(520,620,690),array(690,790,890));
    $discounts = array(100,50,0);
    $dinner = 60;
    $jsAmount = "<script>
    function duplicateInfoToBilling(){
        document.getElementById('billingname').value = document.getElementById('firstname').value;
        document.getElementById('billingaddress').value = document.getElementById('address').value;
        document.getElementById('billingpostcode').value = document.getElementById('postcode').value;
        document.getElementById('billingcity').value = document.getElementById('city').value;
        document.getElementById('billingstate').value = document.getElementById('state').value;
        document.getElementById('billingcountry').value = document.getElementById('country').value;

    }
    function calculateTotal(){
        var optionprice = [".$prices[0][$currentPeriod].",".$prices[1][$currentPeriod].",".$prices[2][$currentPeriod].",".$prices[3][$currentPeriod].",".$prices[4][$currentPeriod]."]; 
        var dinner = ".$dinner.";
        var discount = ".$discounts[$currentPeriod].";
        var choice = 5;
        var dinner = document.getElementById('extradinner').value;
        var studentcheck = 0;
        if (document.getElementById('regoption1').checked) {
          choice = 1;
        }
        if (document.getElementById('regoption2').checked) {
          choice = 2;
        }if (document.getElementById('regoption3').checked) {
          choice = 3;
        }if (document.getElementById('regoption4').checked) {
          choice = 4;
        }if (document.getElementById('regoption5').checked) {
          choice = 5;
        }
        if (document.getElementById('studentcheck').value=='true' && (choice == 4 || choice == 5)){
          studentcheck = 1;
        }
    
        var totalPrice = optionprice[choice-1] + dinner*60 - studentcheck*discount;
    	var legendPrice = '';
    	if (dinner > 0 || studentcheck==1){
    		legendPrice = legendPrice + '(' +optionprice[choice-1];
    		if (dinner > 0)
    			legendPrice = legendPrice + ' + ' + dinner + '*60';
    		if (studentcheck==1)
    			legendPrice = legendPrice + ' - ' + discount;
    		legendPrice = legendPrice + ')';
    	}
        ";
    if (isset($params["disableRegOption"]) && $params["disableRegOption"]==true){
        $jsAmount .=      
        "totalPrice = ".$params["customPrice"]."+ dinner*60;
        legendPrice = '(".$specialCode.")';
        document.getElementById('totalamount').innerHTML = 'Total: '+totalPrice+' € '+legendPrice;
        document.getElementById('totaltopay').value = totalPrice;";
 
    }else{
        $jsAmount .=      
        "document.getElementById('totalamount').innerHTML = 'Total: '+totalPrice+' € '+legendPrice;
        document.getElementById('totaltopay').value = totalPrice;";
 
    }
    $jsAmount .= "}</script>";

    echo $jQueryValidation.$jsAmount.'
        <form action="'.$_SERVER['REQUEST_URI'].'" id="registrationform" method="post">   
          <h1>Registration</h1>
          <div>
          <fieldset>
            <legend>Basic info</legend>
            <label for="firstname">First Name: <strong>*</strong>
            <input type="text" id="firstname" name="firstname" value="'.( isset( $registrationinfo['firstname'] ) ? $registrationinfo["firstname"] : "" ).'"/></label>
            <label for="lastname">Last Name: <strong>*</strong>
            <input type="text" id="lastname" name="lastname" value="'.( isset( $registrationinfo['lastname'] ) ? $registrationinfo["lastname"] : "" ).'"/></label>
            <label for="email">Email:<strong>*</strong>
            <input type="email" id="mail" name="email"  value="'.( isset( $registrationinfo['email'] ) ? $registrationinfo["email"] : "" ).'"/></label>
            <label for="affiliation">Affiliation(University/Institution...): <strong>*</strong>
            <input type="text" id="affiliation" name="affiliation" value="'.( isset( $registrationinfo['affiliation'] ) ? $registrationinfo["affiliation"] : "" ).'"/></label>
            <label for="profile">Name as it would appear on the badge(including title): <strong>*</strong>
            <input type="text" id="profile" name="profile"  value="'.( isset( $registrationinfo['profile'] ) ? $registrationinfo["profile"] : "" ).'"/></label>
            <label for="address">Address: 
            <input type="text" id="address" name="address"  style="width:32em;" value="'.( isset( $registrationinfo['address'] ) ? $registrationinfo["address"] : "" ).'"></label>
            <label for="postcode">PostCode:
            <input type="text" id="postcode" name="postcode" style="width:8em;"  value="'.( isset( $registrationinfo['postcode'] ) ? $registrationinfo["postcode"] : "" ).'"/></label>
            <label for="city">City: 
            <input type="text" id="city" name="city" style="width:12em;"  value="'.( isset( $registrationinfo['city'] ) ? $registrationinfo["city"] : "" ).'"/></label>
            <label for="state">State/Province:
            <input type="text" id="state" name="state" style="width:12em;" value="'.( isset( $registrationinfo['state'] ) ? $registrationinfo["state"] : "" ).'"/></label>
            <label for="country">Country: <strong>*</strong>'.countryHtmlCombo("country").'</label>
            <label for="arrivaldate">Estimated Arrival Date:
            <input type="input" id="arrivaldate" name="arrivaldate" value="'.( isset( $registrationinfo['arrivaldate'] ) ? $registrationinfo["arrivaldate"] : "" ).'"/></label>
            <label for="departuredate">Estimated Departure Date:
            <input type="input" id="departuredate" name="departuredate" value="'.( isset( $registrationinfo['departuredate'] ) ? $registrationinfo["departuredate"] : "" ).'"/></label>
            <label for="phone">Phone (Include Country code):
            <input type="text" id="phone" name="phone"  value="'.( isset( $registrationinfo['phone'] ) ? $registrationinfo["phone"] : "" ).'"/></label>
            <span>
            <input type="checkbox" id="needvisa" name="needvisa"/><label for="needvisa">Do you need us to support you with the visa?</label>
            </span> 
            <label for="others">Other remarks (special food needs, disabilities,...):
            <textarea id="others" name="others">'.( isset( $registrationinfo['others'] ) ? $registrationinfo["others"] : "" ).'</textarea></label>
          </fieldset>
          <fieldset>
            <legend>Registration Choice <b>('.$periods[$currentPeriod].')</b></legend>
            <span><input type="radio" id="regoption1" value="1" '.($params["disableRegOption"]==true?'disabled ':'').'name="regoption"  onchange="calculateTotal()"/><label for="regoption1" class="light">Option 1: Monday 22nd Workshop/Tutorials ('.$prices[0][$currentPeriod].' €)</label> </span>
            <span><input type="radio" id="regoption2" value="2" '.($params["disableRegOption"]==true?'disabled ':'').'name="regoption" onchange="calculateTotal()"/><label for="regoption2" class="light">Option 2: Tuesday 23rd Workshop/Tutorials ('.$prices[1][$currentPeriod].' €)</label></span>
            <span><input type="radio" id="regoption3" value="3" '.($params["disableRegOption"]==true?'disabled ':'').'name="regoption" onchange="calculateTotal()"/><label for="regoption3" class="light">Option 3: Both Workshops/Tutorial Days ('.$prices[2][$currentPeriod].' €)</label></span>
            <span><input type="radio" id="regoption4" value="4" '.($params["defaultoption"]==4?'checked ':'').' '.($params["disableRegOption"]==true && !($params["defaultoption"]==4)?'disabled ':'').'name="regoption" onchange="calculateTotal()"/><label for="regoption4" class="light">Option 4: Main Conference ('.$prices[3][$currentPeriod].' €)</label></span>
            <span><input type="radio" id="regoption5" value="5" '.($params["defaultoption"]!=4?'checked ':'disabled').' name="regoption" onchange="calculateTotal()"/><label for="regoption5" class="light">Option 5: Full Registration (Main Conference+ 2 Workshops/Days) ('.$prices[4][$currentPeriod].' €)</label></span>
            <span><label>Extra dinner tickets (60€ per ticket)</label><select style="width:3em;height: 28px !important;
    border: 1px solid #ABADB3;
    margin: 0 0 0 2px;padding-left:4px;" id="extradinner" name="extradinner" onchange="calculateTotal()">
            <option value="0" selected>0</option>          
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>          
            </select>
            </span>
            <span>
            <input type="checkbox" id="firsttime" name="firsttime"/><label for="firsttime">Will SPLC 2017 be the first time you attend an SPLC?</label>
            </span>
            <span>
            <label for="easychairid">Please enter the submission ID(s) in EasyChair of ALL your paper(s). Please specify one paper ID per line:
            <textarea id="easychairid" name="easychairid"></textarea></label>          
            </span>          
            <span>
            <input type="hidden" id="studentcheck" name="studentcheck" value="'.(($student==false)?"false":"true").'"/><label for="studentcert">';
            if ($student=="true") echo '<strong>Certified Student (-'.$discounts[$currentPeriod].' € applied in Option 4 & 5)</strong>';
            else echo '<p style="text-align: justify;">If you are a student please contact <a href="mailto:splc2017@us.es">splc2017@us.es</a> before registration to obtain the discounted student fee. You will need to attach the scanned proof and we will provide you with the payment process</p>';
            echo '</label><strong><label for="totalamount" id="totalamount">Total: 180 €</label></strong>
            </fieldset>
            <fieldset>
            <legend>Payment Method</legend>
            <span><input type="radio" id="paymentmethod1" value="methodtransfer" checked name="paymentmethod"/><label for="regoption1" class="light">Bank Transfer</label></span>
            <span><strong>National Spanish Transfer:</strong></span>
            <span>VIAJES EL CORTE INGLÉS, S.A.</span>
            <span>BANCO SANTANDER - PLAZA DE CANALEJAS, 1 - 28014 Madrid</span>
            <span>Número de cuenta: ES37 0049 1500 0328 1035 5229</span>
            <span><strong>International:</strong></span>
            <span>BENEFICIARY: VIAJES EL CORTE INGLÉS, S.A.</span>
            <span>NAME OF ACCOUNT: BANCO BILBAO VIZCAYA ARGENTARIA</span>
            <span>ADRESS OF BANK: Oficina Corporativa c/ Alcalá 16 - 28014 Madrid</span>
            <span>BIC (Swift number): BBVAESMMXXX</span>
            <span>IBAN: ES97 0182 3999 3702 0066 4662</span>
  <span>Please, indicate in the bank transfer concept: \'SPLC 2017 - IDORDER - The participant\'s name\'. For instance: SPLC 2017 - 202- Mcilroy</span> 
  <span>Any bank charges must be paid by the sender. This guarantees that we will receive your payment in full</span>
            <span><input type="radio" id="paymentmethod2" value="methodtpv" name="paymentmethod""/><label for="regoption2" class="light">Payment Gateway (you will be redirected after confirmation)</label></span>
          </fieldset>
          <fieldset>
            <legend>Billing Info</legend>
            To fill ONLY if an invoice is requested. The corresponding invoice will be sent by e-mail.
            <a href="javascript:duplicateInfoToBilling();">Duplicate Basic Info</a>
            <label for="billingname">Institution/Company (or Full Name for the Invoice):
            <input type="text" id="billingname" name="billingname" value="'.( isset( $registrationinfo['billingname'] ) ? $registrationinfo["billingname"] : "" ).'"/></label>
            <label for="billingvat">VAT Identification Number:
            <input type="text" id="billingvat" name="billingvat"  value="'.( isset( $registrationinfo['billingvat'] ) ? $registrationinfo["billingvat"] : "" ).'"/></label>
            <label for="billingaddress">Address:
            <input type="text" id="billingaddress" name="billingaddress" style="width:32em;" value="'.( isset( $registrationinfo['billingaddress'] ) ? $registrationinfo["billingaddress"] : "" ).'"/></label>
            <label for="billingpostcode">PostCode:
            <input type="text" id="billingpostcode" name="billingpostcode" style="width:8em;" value="'.( isset( $registrationinfo['billingpostcode'] ) ? $registrationinfo["billingpostcode"] : "" ).'"/></label>
            <label for="billingcity">City:
            <input type="text" id="billingcity" name="billingcity" style="width:12em;"  value="'.( isset( $registrationinfo['billingcity'] ) ? $registrationinfo["billingcity"] : "" ).'"/></label>
            <label for="billingstate">State/Province:
            <input type="text" id="billingstate" name="billingstate"  style="width:12em;" value="'.( isset( $registrationinfo['billingstate'] ) ? $registrationinfo["billingstate"] : "" ).'"/></label>
            <label for="billingcountry">Country:'.countryHtmlCombo("billingcountry").'</label>
          </fieldset>
          For any problem or doubt about registation process, please write to <a href="mailto:contact@splc2017.net">contact</a><br/>
          <input type="hidden" name="totaltopay" id="totaltopay" value="180"/>
          <input type="hidden" name="status" value="registrationform"/>
          <button name="submit" type="submit">Register</button>
    </form>';
    
}
?>