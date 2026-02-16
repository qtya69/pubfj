<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\ReserveRequest;
use App\Policies\ReservePolicy;

class ReserveController extends Controller {
    

    public function reserveTable( ReserveRequest $request){
        $request->validated();
    }
}
