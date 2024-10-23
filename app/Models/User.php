<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableTrait;
use OwenIt\Auditing\Models\Audit;
class User extends Authenticatable implements Auditable
{
    use HasApiTokens, HasFactory, Notifiable,AuditableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'nit',
        'email',
        'password',
        'estado',
        'is_admin',
        'acepta_terminos',
        'expira_password',
        'remember_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function validarFechaExpedicion($fechaExpira)
    {
        $fechaActual =  date('Y-m-d');

        if ($fechaActual > $fechaExpira) {
            return true;
        }else{
            return false;
        }
       
    }

    public function auditEvent($event)
    {
        // Create an audit entry with a custom event (e.g., login, logout)
        Audit::create([
            'auditable_type' => self::class,
            'auditable_id'   => $this->id,
            'event'          => $event,
            'url'            => request()->fullUrl(),
            'ip_address'     => request()->ip(),
            'user_agent'     => request()->userAgent(),
            'created_at'     => now(),
        ]);
    }
}
