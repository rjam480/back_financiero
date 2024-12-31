<?php

namespace App\Jobs;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\CorreoCreacion;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
class SendMail implements ShouldQueue
{
    use Batchable,Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public $data;
    public function __construct($data)
    {
        $this->data = $data;
        
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // procesar los 100 o el 1 a 1 de los elementos
       
        $data =  json_decode($this->data);
        $usersToInsert = []; // Array para inserción masiva
        $emailsToSend = []; // Almacena datos para los correos
        foreach ($data as $key => $value) {
            // $nuevoUsuario =  new User();
            if (!User::where('nit', $value->nit)->exists()) {
                $usersToInsert[] =[
                    'name' => $value->name,
                    'nit' => $value->nit,
                    'email' => $value->email,
                    'password' => Hash::make($value->password),
                    'estado' => $value->estado,
                    'is_admin' => $value->is_admin,
                    'expira_password' => $value->expira_password,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
               
                if ($value->email) {
                   
                    $emailsToSend[] = [
                        'email' => $value->email,
                        'password' => $value->password,
                        'nit' => $value->nit,
                    ];
                    
                }
            }

        }

        if (!empty($usersToInsert)) {
            User::insert($usersToInsert);
        }

        foreach ($emailsToSend as $emailData) {
            Mail::to($emailData['email'])->queue(new CorreoCreacion([
                'password' => $emailData['password'],
                'nit' => $emailData['nit'],
            ]));
        }
    }
}
