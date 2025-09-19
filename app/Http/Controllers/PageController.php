<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
class PageController extends Controller
{
    // Handle the "home" page request
    // Returns the "home" view with a title variable passed to it
    public function home() {
        return view('home', ['title' => 'Accueil']);
    }
}
