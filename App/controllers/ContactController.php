<?php

namespace App\controllers;

use App\core\Mailer;
use App\core\Session;
use App\core\Validation;
use App\models\Contact;
use PDO;
class ContactController
{
    protected PDO $db;
   
    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function submitContactForm()
    {
        $data = $_POST;
        $user = Session::get('user');

        if ($user) {
            $data['user_id'] = $user['id'];
            $data['name'] = $user['name'];
            $data['email'] = $user['email'];
        } else {
            $data['user_id'] = null;
        }

        $rules = [
            'phone' => ['required', 'phone'],
            'subject' => ['required', 'in:Appointment Inquiry,Medical Advice,Feedback,Complaint,Other'],
            'message' => ['required', 'string', 'min:10'],
        ];

        if (!$user) {
            $rules['name'] = ['required', 'string', 'min:3'];
            $rules['email'] = ['required', 'email'];
        }

        $validator = new Validation();
        if (!$validator->validate($data, $rules)) {
            Session::set('errors', $validator->getErrors());
            Session::set('old', $data);
            header("Location: ?page=contact");
            exit;
        }

       

        $contactModel = new Contact($this->db);
        $contactModel->create($data);

    $mailer = new Mailer();
    
    $body = "
        <h3>New message from the contact form</h3>
        <p><strong>Name:</strong> {$data['name']}</p>
        <p><strong>Email:</strong> {$data['email']}</p>
        <p><strong>Phone:</strong> {$data['phone']}</p>
        <p><strong>Subject:</strong> {$data['subject']}</p>
        <p><strong>Message:</strong><br>{$data['message']}</p>
    ";
    
    
    $to = 'c91870041@gmail.com';
    $subject = 'New Contact Message: ' . $data['subject'];
    $success = $mailer->sendMail($to, $subject, $body, $data['email'], $data['name']);
    if($success){
        Session::set('success', 'Message sent successfully!');
    }else {
        Session::set('error', 'Failed to send the message. Please try again later.');
    }


        header("Location: ?page=contact");
        exit;
    }
}
