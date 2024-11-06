<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PoliticasController extends Controller
{
    public function obtenerPoliticas()
    {
        // Define las políticas con texto HTML
        $politicas = "<p>La información proporcionada y a la que se tenga acceso por parte de los prestadores es confidencial y está sujeta a las siguientes condiciones:</p>

<p><strong>CONFIDENCIALIDAD:</strong> La información que los prestadores reciban de Nueva EPS está protegida y solo podrá ser utilizada para fines relacionados con la relación financiera. Los prestadores se comprometen a:</p>

<ol>
  <li>Mantener en estricta confidencialidad la información recibida de Nueva EPS y no compartirla con terceros ajenos a su equipo de trabajo o asesores autorizados, quienes también deben comprometerse a mantener la confidencialidad.</li>
  <li>No divulgar, comunicar ni utilizar la información financiera con proveedores o terceros. Los datos y documentos recibidos solo podrán utilizarse para cumplir con la relación contractual o comercial con Nueva EPS, evitando cualquier transferencia de información, ya sea oral o escrita.</li>
  <li>No utilizar la información técnica, comercial o financiera obtenida en virtud de esta relación para beneficio de terceros o para actividades distintas a las derivadas de la relación contractual con Nueva EPS.</li>
  <li>Abstenerse de realizar copias, apropiarse de información de manera personal o en beneficio de terceros, o divulgar la información recibida de forma total o parcial.</li>
</ol>

<p><strong>PARÁGRAFO PRIMERO:</strong> Las partes informarán a sus empleados y a terceros involucrados en la relación con Nueva EPS sobre el compromiso de confidencialidad, siendo responsables del cumplimiento de estas normas.</p>

<p><strong>PARÁGRAFO SEGUNDO:</strong> La obligación de confidencialidad es indefinida y se mantendrá vigente durante y después de la relación contractual con Nueva EPS.</p>

<p><strong>Soporte Técnico:</strong></p> 

<p>Si necesita ayuda con el acceso o la utilización del aplicativo, puede contactar al correo <a href='mailto:gestionfinanciera@nuevaeps.com.co'>gestionfinanciera@nuevaeps.com.co</a>.</p>

<p>Este aplicativo es una herramienta valiosa para que los prestadores mantengan un control eficiente de su información financiera y optimicen su relación con Nueva EPS, sin necesidad de intermediarios.</p>
";
        
        // Retorna las políticas como respuesta JSON
        return response()->json(['politicas' => $politicas]);
    }
}