<?php

use Illuminate\Support\Facades\Route;

Route::redirect('/graphql-playground', '/graphiql');

Route::get('/', function () {
    return view('welcome');
});
