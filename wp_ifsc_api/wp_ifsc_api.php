<?php
/*
*Plugin Name: WP IFSC API
*Description: My plugin to explain enter ifsc code than display bank information [bank_ifsc_code] 
*Version: 1.0
*Author: Shailesh Parmar
*Author URI: https://xyz.com/
*/ 


defined( 'ABSPATH' ) or die( "No Direct Access" );


add_shortcode('bank_ifsc_code','wp_bank_ifsc_func');

function wp_bank_ifsc_func(){

	echo "<h3>Bank Information: </h3>";

	if(isset($_POST['ifsc'])){

		$val = $_POST['ifsc'];

		// From URL to get webpage contents.
		$url="https://ifsc.razorpay.com/".$val;

		// Initialize a CURL session.
		$ch = curl_init();

		//grab URL and pass it to the variable.
		curl_setopt($ch, CURLOPT_URL, $url);

		// Return Page contents.
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		//execute curl
		$data=curl_exec($ch);

		curl_close($ch); // close url here

		$amit=json_decode($data);

	}

	if(isset($amit->BRANCH))
	{
		echo "<div class='ifsc-bank-details'>";
		echo "<b> Branch: </b>" . " " .$amit->BRANCH ."<br> ";
		echo "<b> DISTRICT:</b>" . " ".$amit->DISTRICT ."<br> ";
		echo "<b> STATE:</b>" . " " .$amit->STATE ." <br>";
		echo "<b> ADDRESS:</b>" . " " .$amit->ADDRESS ."<br> ";
		echo "<b> CONTACT No.</b>"." " .$amit->CONTACT ."<br> ";
		echo "<b> BANK :</b>" . " " .$amit->BANK ."<br> ";
		echo "<b> CITY :</b>" . " " .$amit->CITY ."<br> ";
		echo "<b> BANKCODE :</b>"." " .$amit->BANKCODE ." <br>";
		echo "<b> IFSC:</b>" . " " .$amit->IFSC ."<br> ";
		echo "<b> MICR </b>"." ".$amit->MICR;
		echo "</div>";

	}
	else{
		if(empty($val)){
			echo "<div class='ifsc-bank-details'>";
			echo "No Record found";
			echo "</div>";
		}
	}

?>

<form method="post" class="bank-ifsc">
	<h4>Please Enter Your IFSC Code of your bank</h4><br>
	<input name="ifsc" type="text" value="" />
    <input name="submit" type="submit" />
</form>
<style type="text/css">
	form.bank-ifsc {
       margin-left: 180px;
    }
</style>

<?php }  ?>