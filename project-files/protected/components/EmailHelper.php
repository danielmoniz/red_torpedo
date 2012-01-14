<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
class EmailHelper {

    /**
     * Used for sending email. Wraps the 'mailer' extension.
     * @param type $username
     * @param Email $email
     */
    public function sendEmail($username, $email) {
        $settings= Yii::app()->params['emailSettings'];
        $userSettings = $settings['users'][$username];
        
        $mailer = Yii::app()->mailer;

        $mailer->Host = $settings['host'];
        $mailer->SMTPAuth = true;
        $mailer->IsSMTP();
        
        // ensure we are using secure SMTP
        $mailer->SMTPSecure = $settings['SMTPSecure'];
        // set the SMTP port for the GMAIL server
        $mailer->Port       = $settings['port'];
        // SMTP account username
        $mailer->Username   = $userSettings['username'];
        // SMTP account password
        $mailer->Password   = $userSettings['password'];

        $mailer->SetFrom($email->from,$email->fromName);
        $mailer->AddReplyTo($email->from,$email->fromName);
        //Set to
        $mailer->clearAllRecipients(); // remove previous recipients
        $mailer->AddAddress($email->to,$email->toName);
        if (!empty($email->ccReceipt)){
            foreach($email->ccReceipt as $ccRecipient){
                $mailer->AddCC($ccRecipient);
            }
        }
        
        $mailer->Subject = $email->subject;
        $mailer->Body = $email->body;
        if ($email->html){
            $mailer->MsgHTML($email->body);
            $mailer->isHTML(true);
        }
        
        if(!$mailer->Send()){
            throw new CHttpException(500,$mailer->ErrorInfo);
        }
        
        return true;
    }
}
?>