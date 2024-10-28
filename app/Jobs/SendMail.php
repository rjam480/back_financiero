<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\CorreoCreacion;
use Illuminate\Support\Facades\Mail;
class SendMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
      
        foreach ($data as $key => $value) {
            
            Mail::to($value->email)->send(new CorreoCreacion([
                'password' => $value->password,
                'nit' => $value->nit,
            ]));
        }
    }
}
