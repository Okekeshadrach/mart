<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function shop()
    {
        return view('pages.shop');
    }

    public function product(string $slug)
    {
        return view('pages.product', compact('slug'));
    }

    public function cart()
    {
        return view('pages.cart');
    }

    public function checkout()
    {
        return view('pages.checkout');
    }

    public function orders()
    {
        return view('pages.orders');
    }

    public function login()
    {
        return view('pages.login');
    }

    public function register()
    {
        return view('pages.register');
    }

    public function profile()
    {
        return view('pages.profile');
    }

    public function forgotPassword()
    {
        return view('pages.forgot-password');
    }

    public function resetPassword()
    {
        return view('pages.reset-password');
    }

    public function about()
    {
        return view('pages.about');
    }

    public function contact()
    {
        return view('pages.contact');
    }
}
