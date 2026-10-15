<?php

namespace App\Mail;

use App\Models\Intervention;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InterventionPlanifiee extends Mailable
{
    use Queueable, SerializesModels;

    public $intervention;

    public function __construct(Intervention $intervention)
    {
        $this->intervention = $intervention;
    }

    public function build()
    {
        return $this->subject(' Intervention planifiée')
                    ->markdown('emails.intervention.planifiee');
    }
}
