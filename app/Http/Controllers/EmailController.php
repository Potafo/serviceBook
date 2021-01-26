<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\Email;
use Illuminate\Support\Facades\Config;

class EmailController extends Controller
{
    protected $to_name;
    protected $to_email;
    public function __construct()
    {
        $this->to_name = "Potafo";
        $this->to_email = "jeshi.p88@gmail.com";

    }
    public function mail()
    {
    $to_name = $this->to_name;//"Riddhi";
    $to_email =$this->to_email;// 'jeshi.p88@gmail.com';
    $data = array('name'=>"Cloudways (sender_name)", 'body' => "A test mail");

    Mail::send('email.email', $data, function($message) use ($to_name, $to_email) {
        $message->to($to_email, $to_name)
                ->subject("Laravel Test Mail");
        $message->from('webdev.potafo@gmail.com','Test Mail');
    });

       return 'Email sent Successfully';


    // $send['emailFrom'] = isset($emailData['emailFrom']) ? 'webdev.potafo@gmail.com' : Config::get('mail.from.address');
    // $send['emailFromUsername'] = isset($emailData['emailFrom']) ? 'webdev.potafo@gmail.com' : Config::get('mail.mailers.smtp.username');
    // $send['emailTo'] = $emailData['toEmail'];
    // $send['emailContent'] = $emailData['emailContent'];
    // $send['subject'] = $emailData['subject'];
    // if($send['emailFrom']) {
    //     Config::set(['mail.from.address'=> $send['emailFrom'],
    //                  'mail.mailers.smtp.username' => $send['emailFromUsername']
    //                 ]);
    // }

    // Mail::send('emails.email', $send, function ($message) use ($send){
    //     $message->from($send['emailFrom'], 'Service Book');
    //     $message->to($send['emailTo']);
    //     $message->subject($send['subject']);
    // });
    // return ['status' => 1, 'msg' => 'Mail sent'];



    }
}
