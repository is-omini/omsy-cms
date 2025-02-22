<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
include "PHPMailer/src/PHPMailer.php";
include "PHPMailer/src/SMTP.php";
include "PHPMailer/src/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Mail {
	private $CMS;
	function __construct($CMS){
		$this->CMS = $CMS;

        //Create an instance; passing `true` enables exceptions
        $this->mailSet = new PHPMailer(true);
        try {
            //Server settings
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $this->mailSet->isSMTP();                                            //Send using SMTP
            $this->mailSet->Host       = 'mail.floagg.org';                     //Set the SMTP server to send through
            $this->mailSet->SMTPAuth   = true;
            $this->mailSet->Username   = 'system@floagg.org';
            $this->mailSet->Password   = 'SystemFloagg18200';
            //$this->mailSet->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $this->mailSet->Port       = 587;
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$this->mailSet->ErrorInfo}";
        }		
	}

	public function send($cc, $Subject, $Body, $AltBody) {
		//Recipients
		$this->mailSet->setFrom($cc);
		$this->mailSet->addCC($cc);

		//Content
		$this->mailSet->isHTML(true);
		$this->mailSet->Subject = $Subject;
		$this->mailSet->Body    = $Body;
		$this->mailSet->AltBody = $AltBody;

		$this->mailSet->send();
	}
}