<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// メール送信ライブラリ
// Version 1.0 2012/11/11

class Smail
{
	var $mail;
	
	public function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->config->load('my/smail');
		
		mb_language("ja");
		mb_internal_encoding("UTF-8");
		
		require( 'jphpmailer.php' );
		
		$this->mail = new JPHPMailer();
		
		//$this->mail->SMTPDebug  = 2;

		$this->mail->IsSMTP();              	//「SMTPサーバーを使うよ」設定
		$this->mail->SMTPAuth = TRUE;			//「SMTP認証を使うよ」設定
		$this->mail->Host = SMTP_HOST;    		// SMTPサーバーアドレス:ポート番号
		$this->mail->Username = SMTP_USERNAME;	// SMTP認証用のユーザーID
		$this->mail->Password = SMTP_PASSWORD;	// SMTP認証用のパスワード
		$this->mail->SMTPSecure = SMTP_SECURE;
		
		$this->mail->Sender = RETURN_PATH;
	}
	
	
	/**
	 * Set FROM
	 */
	public function from($from_email, $from_name = '')
	{
		$this->mail->setFrom($from_email, $from_name);
	}
	
	
	/**
	 * Set Body
	 */
	public function message($body)
	{
		$this->mail->setBody($body);
	}
	
	
	/**
	 * Set To
	 */
	public function to($to, $name = '')
	{
		$this->mail->addTo($to, $name);
	}
	
	
	/**
	 * Set Email Subject
	 */
	public function subject($subject)
	{
		$this->mail->setSubject($subject);
	}
	
	
	/**
	 * Set BCC
	 */
	public function bcc($bcc, $name = '')
	{
		if (!isset($bcc)) return;
		
		$this->mail->addBcc($bcc, $name);
	}
	
	
	/**
	 * Send Email
	 */
	public function send()
	{
		$result = $this->mail->Send();

		$this->mail->ClearAddresses();
		$this->mail->ClearBCCs();
		
		return $result;
	}
	
	
	/**
	 * エラーメッセージを取得する
	 * 
	 * @return string エラーメッセージ
	 */
	public function get_error_message()
	{
		return $this->mail->getErrorMessage();
	}
	
	
	/**
	 * SMTPKeepAlive
	 */
	public function smtp_keep()
	{
		$this->mail->SMTPKeepAlive = TRUE;
	}
	
	/**
	 * smtp_close
	 */
	public function smtp_close()
	{
		return $this->mail->SmtpClose();
	}
}
