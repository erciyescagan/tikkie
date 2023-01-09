<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Json;
use App\Models\User;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    private $request;
    public function __construct(Request $request){
        $this->request = $request;
        if ($this->request->route()->getPrefix() == "api") {
            $this->request->validate([
                'email' => 'required|string|email|max:255',
            ]);

            Json::setJson($this->request);

        }
    }

    public function index(){
        return $this->request->user()->contacts()->get();
    }

    public function store(){
        $email = Json::getJson()['email'];
        $contact = User::where('email', $email)->first();
        if ($contact){
            if (is_null($this->request->user()->contacts()->where('contact_id', $contact->id)->first())){
                $this->request->user()->contacts()->attach($contact);
                return response()->json([
                    'status' => 'success',
                    'statusCode' => 200,
                    'message' => 'Kullanıcıyı listenize başarıyla kaydettiniz!',
                    'data' => ['contact' => $contact, 'contacts' => $this->request->user()->contacts()->get()],
                ], 200);
            }
            return response()->json([
                'status' => 'error',
                'statusCode' => 500,
                'message' => 'Daha önce listenize eklediğiniz bir kullanıcıyı tekrar ekleyemezsiniz!',
                'data' => ['contact' => $this->request->user()->contacts()->where('contact_id', $contact->id)->first()],
            ], 200);
        }
        return response()->json([
            'status' => 'error',
            'statusCode' => 404,
            'message' => 'Aradığınız mail adresi ile bir kullanıcı bulunamadı!',
            ], 404);


    }
}
