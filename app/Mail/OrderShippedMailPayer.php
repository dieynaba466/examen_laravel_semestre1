<?php

namespace App\Mail;

use App\Models\Commande;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;


class OrderShippedMailPayer extends Mailable
{
    use Queueable, SerializesModels;

    public $commande;

    /**
     * Create a new message instance.
     */
    public function __construct(Commande $commande)
    {
        $this->commande = $commande;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $pdf = Pdf::loadView('factures.show', ['commande' => $this->commande]);

        return $this->subject('Votre commande a été paye')
            ->view('emails.commandpayer')
            ->with(['commande' => $this->commande])
            ->attachData($pdf->output(), 'recu' . $this->commande->id . '.pdf', [
                'mime' => 'application/pdf',
            ]);
    }
}
