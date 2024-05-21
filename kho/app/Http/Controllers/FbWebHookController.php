<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FbWebHookController extends Controller
{
    //
    public function webhook(Request $req) 
    {
        $localVerifyToken = env('WEBHOOK_VERIFY_TOKEN');
        $hubVerifyToken = $req->get('hub_verify_token');

        if ($localVerifyToken == $hubVerifyToken) {
            return $req->get('hub_challenge');
        } else
            return 'Bad verify token';
        return "This is Webhook.";
    }
}
