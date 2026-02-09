<?php

namespace App\Exceptions;

use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Events\MissModelEvent;

class InvalidModelException extends Exception {
    
    use ResponseTrait;
    protected string $debugMessage;

    public function __construct( protected NotFoundHttpException $ex, protected Request $request ) {

        $message = $this->buildMessage( $request );
        parent::__construct( $message, 404, $ex );
    }

    public function render( Request $request ) {

        return $this->sendError( "Végrehajtási hiba", [ $this->getMessage() ], 404 );
    }

    public function report() {

        $payload = $this->request->all();
        $logData = [
            "message" => $this->debugMessage . "\n",
            "user" => $this->request->user()->name,
            "action" => $this->getFriendlyMethodName() . "\n",
            "url" => $this->request->fullUrl(),
            "ip" => $this->request->ip(). "\n"
        ];

        if( $payload ) {

            $logData[ "data" ] = json_encode( $payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE );
        }

        Log::channel( "invalid_model")->warning( "Adatbázis hiba", $logData );

        event( new MissModelEvent( $logData ) );
    }

    private function buildMessage( Request $request ) {

        $route = $request->route();
        $modelName = collect( $route->parameterNames() )->first();
        $value = collect( $route->parameters() )->first();
        $this->debugMessage = "HIÁNYZÓ REKORD: A(z) {$modelName} táblában a(z) 'id' = {$value} feltétellel.";
        $safeMessage = "Adatműveleti hiba, ellenőrizze az adatokat!";

        return config( "app.debug" ) ? $this->debugMessage : $safeMessage;
    }

    private function getFriendlyMethodName(): string {

        return match( $this->request->method() ) {

            "POST" => "Létrehozás",
            "PUT", "PATCH" => "Módosítás",
            "DELETE" => "Törlés",
            "GET" => "Megtekintés",
            default => "Ismeretlen művelet"
        };
    }
}
