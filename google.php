<?php
function GetMerchantID() {
    return "370784070153446";
}

function GetMerchantKey() {
    return "jZ_P1XGw7DZokZvhQDlPTg";
}
    $GLOBALS["logfile"] = "../../rallypointlog/log.out";
    $GLOBALS["error_report_type"] = "3";
    $GLOBALS["error_logfile"] = "../../rallypointlog/error.log";
    $GLOBALS["currency"] = "USD";
    $GLOBALS["schema_url"] = "http://checkout.google.com/schema/2";
   $GLOBALS["merchant_id"] = GetMerchantID();
    $GLOBALS["merchant_key"] = GetMerchantKey();
    $base_url = "https://checkout.google.com/cws/v2/Merchant/" . $GLOBALS["merchant_id"];
    $GLOBALS["checkout_url"] = $base_url . "/checkout";
    $GLOBALS["checkout_diagnose_url"] = $base_url . "/checkout/diagnose";
    $GLOBALS["request_url"] = $base_url . "/request";
    $GLOBALS["request_diagnose_url"] = $base_url . "/request/diagnose";
 
    $GLOBALS["mp_type"] = "MISSING_PARAM";

function CheckForError($error_type, $function_name, $param_name, $param_value="") {
    if ($param_value == "") {
    }
}
function CalcHmacSha1($data) {
    $key = $GLOBALS["merchant_key"];
    $error_function_name = "CalcHmacSha1()";
    CheckForError($GLOBALS["mp_type"], $error_function_name, 
        "data", $data);
    CheckForError($GLOBALS["mp_type"], $error_function_name, 
        "GLOBALS[\"merchant_key\"]", $key);
    $blocksize = 64;
    $hashfunc = 'sha1';
    if (strlen($key) > $blocksize) {
        $key = pack('H*', $hashfunc($key));
    }
    $key = str_pad($key, $blocksize, chr(0x00));
    $ipad = str_repeat(chr(0x36), $blocksize);
    $opad = str_repeat(chr(0x5c), $blocksize);
    $hmac = pack(
                    'H*', $hashfunc(
                            ($key^$opad).pack(
                                    'H*', $hashfunc(
                                            ($key^$ipad).$data
                                    )
                            )
                    )
                );
    return $hmac;
}


function creategoogle($xml_cart){
	$signature = CalcHmacSha1($xml_cart);
	$b64_cart = base64_encode($xml_cart);
	$b64_signature = base64_encode($signature);
	echo "<form action=\"" .$GLOBALS["checkout_url"] . "\" method=\"post\">";
	echo "<input type=\"hidden\" name=\"cart\" value=\"" . $b64_cart . "\" />";
	echo "<input type=\"hidden\" name=\"signature\" value=\"" . $b64_signature . "\" />";
	echo "<input type=\"image\" name=\"Google Checkout\" alt=\"Fast checkout through Google\" src=\"http://checkout.google.com/buttons/checkout.gif?merchant_id=" . $GLOBALS["merchant_id"] . "&w=160&h=43&style=white&variant=text&loc=en_US\" height=\"43\" width=\"160\" />";
	echo "</form>";
}


function createcart($accountid, $currentplan, $experdate, $units, $price, $newplan){
	$month = 6;
	$e = $experdate;
	if($currentplan==0){
		$timestamp = time();
		$date_time_array = getdate($timestamp);
		$hours = $date_time_array['hours'];
		$minutes = $date_time_array['minutes'];
		$seconds = $date_time_array['seconds'];
		$months = $date_time_array['mon'];
		$day = $date_time_array['mday'];
		$year = $date_time_array['year'];
		$timestamp = mktime($hours,$minutes,$seconds,$months+$month,$day,$year);
		$experdate = $timestamp;
	}else{	
		$month = 6;
		$timestamp = $experdate;
		$date_time_array = getdate($timestamp);
		$hours = $date_time_array['hours'];
		$minutes = $date_time_array['minutes'];
		$seconds = $date_time_array['seconds'];
		$months = $date_time_array['mon'];
		$day = $date_time_array['mday'];
		$year = $date_time_array['year'];
		$timestamp = mktime($hours,$minutes,$seconds,$months+$month,$day,$year);
		$experdate = $timestamp;

	}
	if($newplan>$currentplan && $currentplan>0){
		$r = $e - time();
		$rr = round($r/(60*60*24*30),0);
		if($rr==0){
			$upgradenote = "";
		}else{
		if($currentplan==1){
			$oldprice = 15;
		}
		if($currentplan==2){
			$oldprice = 30;
		}
		if($currentplan==3){
			$oldprice = 50;
		}
		if($currentplan==4){
			$oldprice = 100;
		}
		$pricechange = $price/$month - $oldprice;
		$price = $price+($pricechange*$rr);
		$upgradenote = " and Upgrading your Current Plan for " . $rr . " Month(s)";
		}
	}else{
		$upgradenote = "";
	}
	
	$cart = "<?xml version=\"1.0\" encoding=\"UTF-8\" ?>\n";
	$cart = $cart . "<checkout-shopping-cart xmlns=\"http://checkout.google.com/schema/2\">\n";
	$cart = $cart . "<shopping-cart>\n";
	$cart = $cart . "<items>\n";
	$cart = $cart . "<item>\n";
	$cart = $cart . "<item-name>Rally Point Subscription " . $month . " Month Subscribtion</item-name>\n";
	$cart = $cart . "<item-description>" . $units . " Unit Plan" . $upgradenote . "</item-description>\n";
	$cart = $cart . "<unit-price currency=\"USD\">" . $price . "</unit-price>\n";
	$cart = $cart . "<quantity>1</quantity>\n";
	$cart = $cart . "<merchant-private-item-data>\n";
	$cart = $cart . "<accountid>" . $accountid . "</accountid>\n";
	$cart = $cart . "<experdate>" . $experdate . "</experdate>\n";
	$cart = $cart . "<currentplan>" . $currentplan . "</currentplan>\n";
	$cart = $cart . "<newplan>" . $newplan . "</newplan>\n";
	$cart = $cart . "</merchant-private-item-data>\n";
	$cart = $cart . "</item>\n";
	$cart = $cart . "</items>\n";
	$cart = $cart . "</shopping-cart>\n";
	$cart = $cart . "<checkout-flow-support>\n";
	$cart = $cart . "<merchant-checkout-flow-support />\n";
	$cart = $cart . "</checkout-flow-support>\n";
	$cart = $cart . "</checkout-shopping-cart>\n";
	creategoogle($cart);
}


?>