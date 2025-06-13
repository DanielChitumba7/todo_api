<?php

namespace App\Http\Controllers;



/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="API de Gestão de Tarefas",
 *      description="Documentação da API de Gestão de Tarefas com Laravel Sanctum",
 *      @OA\Contact(
 *          email="seu_email@example.com"
 *      )
 * )
 *
 * @OA\Server(
 *      url="http://127.0.0.1:8000/api/",
 *      description="Servidor da API"
 * )
 *
 * @OA\SecurityScheme(
 *      securityScheme="bearerAuth",
 *      type="http",
 *      scheme="bearer",
 *      bearerFormat="JWT"
 * )
 */

abstract class Controller
{
    //
}
