<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Restaurant;

class ContactController extends Controller
{
    public function index(): void
    {
        $this->layout('contact.index', [
            'restaurants' => (new Restaurant())->findAll(),
            'pageTitle' => 'Nous Contacter',
        ]);
    }

    public function send(): void
    {
        $name = $this->sanitize($this->input('name', ''));
        $email = $this->sanitize($this->input('email', ''));
        $phone = $this->sanitize($this->input('phone', ''));
        $subject = $this->input('subject', '');
        $message = $this->sanitize($this->input('message', ''));

        if (empty($name) || empty($email) || empty($subject) || empty($message)) {
            Session::flash('error', 'Veuillez remplir tous les champs obligatoires.');
            Session::setOld($_POST);
            $this->redirect('/contact');
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Session::flash('error', 'Adresse email invalide.');
            Session::setOld($_POST);
            $this->redirect('/contact');
            return;
        }

        // Here you could send an email or save to database
        // For now, just show success message
        
        Session::flash('success', 'Merci pour votre message ! Nous vous répondrons dans les plus brefs délais.');
        $this->redirect('/contact');
    }
}
