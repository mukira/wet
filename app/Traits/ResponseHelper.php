<?php

namespace App\Traits;

trait ResponseHelper {

    /**
     * Formats http responses
     * 
     * @param   Boolean     $ok         The response status
     * @param   Mixed       $results    The results object
     * @param   Request     $request    The request object
     * @return  Response
     */
    public function respond($ok, $results, $request) {
        return response()
            ->json([
                'ok' => $ok,
                'initialQuery' => $request->json()->all(),
                'results' => $results
            ]);
    }
}