<?php

/*
 * TodoList API corso Udemy
 * Pietro Onesti  * 
 * 2023  * 
 */

/**
 * Description of ErrorHandler: classe che intercetta gli errori e restituisce 
 * un json con il testo degli errori
 *
 * @author pietroonesti
 */
class ErrorHandler {
    
    //errori
    
    public static function handleError(
            int     $errno,
            string  $errstr,
            string  $errfile,
            int     $errline): void
    {
        throw new ErrorException($errstr,0,$errno,$errfile, $errline);
    }


    //exceptions
    public static function handleException(Throwable $exception): void {
        
        http_response_code(500);
        
        echo json_encode([
            
            "code" => $exception->getCode(),
            "message" => $exception->getMessage(),
            "file" => $exception->getFile(),
            "line" => $exception->getLine()            
        ]);
    }
}
