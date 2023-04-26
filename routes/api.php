<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



    Route::get('getAllCountries','GuestController@getAllCountries');

    Route::get('getAllCategories','GuestController@getAllCategories');

