<?php

namespace App\controllers;

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
            'subject' => ['required', 'string', 'min:3'],
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

        Session::set('success', "Message sent successfully!");
        header("Location: ?page=contact");
        exit;
    }
}
