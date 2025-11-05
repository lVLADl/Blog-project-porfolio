<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected $context = [];
    public function __construct()
    {
        // $this->context['page_title'] = 'Travel Blog — Мир Путешествий';
    }
}
