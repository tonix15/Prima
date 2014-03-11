<?php
class Prima {
	const FORMAT_SAVE_DATE = 'Y-m-d';
	const FORMAT_SAVE_DATETIME = 'Y-m-d H:i:s';
	const FORMAT_FILE_DATETIME = 'Y-m-d H.i.s';
	
	public static function convertStringToImage($imageString) {
	   	return imagecreatefromstring(base64_decode($imageString));	
	}
   
	public static function saveImageToDisk($image, $dir, $image_name = '', $ext = '.png') {
	   	$image_file = $dir . '/' . $image_name . $ext;
	   	 
	   	if (!file_exists($dir)) {
	   		mkdir($dir, 0644);
	   	}
	   	
	   	imagejpeg($image, $image_file, 100);
	   	return imagedestroy($image);
	}
   
	public static function formatToSaveDate($date) {
		return date(self::FORMAT_SAVE_DATE, strtotime($date));
	}
	
	public static function formatToSaveDateTime($date) {
		return date(self::FORMAT_SAVE_DATETIME, strtotime($date));
	}
	
	public static function getSaveDateTime() {
		return date(self::FORMAT_SAVE_DATETIME);
	}
	
	public static function getSaveDate() {
		return date(self::FORMAT_SAVE_DATE);
	}
	
	public static function getFileDateTime() {
		return date(self::FORMAT_FILE_DATETIME);
	}
	
	public static  function getUtilityType($utility_type_list, $utilityPK) {
   		if (!empty($utility_type_list)) {
   			foreach ($utility_type_list as $utility_type) {
   				if ($utility_type['UtilityTypePk'] == $utilityPK) {
   					return $utility_type;
   				}
   			}
   		}
   		
   		return null;
	}
	
	public static function getMeterPhoto($meterPK, $photo_name) {
		$image_title = self::formatToSaveDate($photo_name);
		$image_file = DOCROOT . '/uploads/meters/' . $meterPK . '/' . $image_title . '.png';
			
		if (file_exists($image_file)) {
			return DOMAIN_NAME . '/uploads/meters/' . $meterPK . '/' . $image_title . '.png';
		} 
			
		return DOMAIN_NAME . '/uploads/default/no-image.png';
	}
	
	public static function isFileExists($meterPK, $photo_name) {
		$image_title = self::formatToSaveDate($photo_name);
		$image_file = DOCROOT . '/uploads/meters/' . $meterPK . '/' . $image_title . '.png';
			
		if (file_exists($image_file)) {
			return true;
		}
			
		return false;
	}
	
	public static  function formatDecimal($value) {
		return number_format ($value, 2 , '.', ',');
	}
	
	public static function mailCustomer($customerEmail, $accountNumber, $outstandingBalance, $type = 'Cut Notification') {
		// subject
		$subject = 'Account ' . $accountNumber . ' overdue notice to avoid disconnection';
		$logo = 'https://viisnovis.zendesk.com/attachments/token/flkkedbhvxpbtbc/?name=image001.png';
		// message
		$message = '<html>
			<head>
				<style>
					p {
						margin:0;
						font-family: "Helvetica Neue","Segoe UI",Helvetica,Arial,"Lucida Grande",sans-serif;
					}
				</style>
			</head>
			<body>
				<div lang="EN-ZA">
					<div>
						<p><u></u>&nbsp;<u></u></p>
						<p>Date:&nbsp; ' . '02/10/2014' . '<u></u><u></u></p>
						<p><u></u>&nbsp;<u></u></p>
						<p>Account ' . $accountNumber . ' <u></u><u></u></p>
						<p><u></u>&nbsp;<u></u></p>
						<p>Good day, this email is a friendly reminder that your account with Triple M Metering is in arrears by R' . $outstandingBalance . '<u></u><u></u></p>
						<p><u></u>&nbsp;<u></u></p>
						<p>Please settle immediately to avoid disconnection and send proof of payment to <a rel="nofollow" ymailto="mailto:reception@triple-m.co.za" target="_blank" href="mailto:reception@triple-m.co.za" id="yui_3_13_0_ym1_1_1391967060626_2993">reception@triple-m.co.za</a><u></u><u></u></p>
						<p><u></u>&nbsp;<u></u></p>
						<p><u></u>&nbsp;<u></u></p>
						<p>Thank you<u></u><u></u></p>
						<p><u></u>&nbsp;<u></u></p>
						<p>Triple M Metering Team<u></u><u></u></p>
						<p><span style="color:navy;"><img src="' . $logo . '" border="0" style="margin-top:10px;margin-left:-12px;width:350px;height:75px;"></span><span lang="EN-US" style="color:navy;font-size:12pt;"><u></u><u></u></span></p>
						<span lang="EN-GB" style="color:navy;font-size:10pt;" >416 Theuns van Niekerk Street, Wierda Park, Centurion<b><u></u><u></u></b></span></p>
						<p><b><span lang="EN-GB" style="color:navy;font-size:10pt;">Telephone Numbers:&nbsp; </span></b><span lang="EN-GB" style="color:navy;font-size:10pt;" >012-653 0600<u></u><u></u></span></p>
						<p><b><span lang="EN-GB" style="color:navy;font-size:10pt;">Fax Number&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:&nbsp; </span></b><span lang="EN-GB" style="color:navy;font-size:10pt;" >012-653 0650<u></u><u></u></span></p>
						<p><span style="font-size:10pt;">&nbsp; <u></u><u></u></span></p>
						<p><span lang="EN-GB" style="color:navy;font-size:10pt;"><u></u>&nbsp;<u></u></span></p>
						<p><u></u>&nbsp;<u></u></p>
					</div>
				</div>
			</body>
		</html>';
	
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	
		$headers .= 'From: <billing@triple-m.co.za>' . "\r\n";
		$headers .= 'Bcc: <marlise@triple-m.co.za>' . "\r\n";
		// $headers .= 'Bcc: <billing@triple-m.co.za>' . "\r\n";
		
		try {
			return mail($customerEmail, $subject, $message, $headers);
		} catch(Exception $e) { echo $e->getMessage();  }

		return false;
	}
	
	public static function formatCellphoneNumber($cellphone) {
		$cellphone = preg_replace('/\s+/', '', $cellphone);
		if ($cellphone[0] == '0') {
			return substr($cellphone, 1);
		}
		
		return $cellphone;
	}
	
	private static function create_csv_string($data) {
		$csv_dir = DOCROOT . '/uploads/sms csv files/';
		// Open temp file pointer
		if (!$fp = fopen('php://temp', 'w+')) return false;
		
		if (!file_exists($csv_dir)) {
			mkdir($csv_dir, 0644);	
		}
		
		$csv_dir .= Prima::getFileDateTime() . '.csv';
		$csv_file = fopen($csv_dir, 'w');
		
		// Loop data and write to file pointer
		foreach ($data as $line) { 
			fputcsv($fp, $line); // for email
			fputcsv($csv_file, $line); // copy for the disk
		}
		
		fclose($csv_file);
		// Place stream pointer at beginning
		rewind($fp);
	  	// Return the data
		$my_file = DOCROOT . '/uploads/sms csv files/file.csv';
		
	  	return stream_get_contents($fp);
	}

	public static function send_csv_mail ($csvData, $body, $to = 'triplemmetering@smsgw1.gsm.co.za', $subject = 'Bulk SMS overdue notice', $from = 'billing@triple-m.co.za') {
		  // This will provide plenty adequate entropy
		  $multipartSep = '-----'.md5(time()).'-----';
		
		  // Arrays are much more readable
		  $headers = array(
		    "Bcc: " . 'billing@triple-m.co.za',
		    "From: $from",
		    "Reply-To: $from",
		    "Content-Type: multipart/mixed; boundary=\"$multipartSep\""
		  );

		  // s$headers .= 'Bcc: <phillip.brits@viisnovis.co.za>' . "\r\n";
		
  		  // Make the attachment
		  $attachment = chunk_split(base64_encode(self::create_csv_string($csvData)));
		  $filename = 'file-' . self::getFileDateTime() . ".csv";
		  // Make the body of the message
		  $body = "--$multipartSep\r\n"
		        . "Content-Type: text/plain; charset=ISO-8859-1; format=flowed\r\n"
		        . "Content-Transfer-Encoding: 7bit\r\n"
		        . "\r\n"
		        . "$body\r\n"
		        . "--$multipartSep\r\n"
		        . "Content-Type: text/csv\r\n"
		        . "Content-Transfer-Encoding: base64\r\n"
		        . "Content-Disposition: attachment; filename=\"" . $filename . "\"\r\n"
		        . "\r\n"
		        . "$attachment\r\n"
		        . "--$multipartSep--";
		  // Send the email, return the result
		  try {
		  	 return mail($to, $subject, $body, implode("\r\n", $headers)); 
		  } catch(Exception $e) { echo $e->getMessage(); }
		  
		  return false;
	}
}
?>