<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvoiceEmail extends Mailable
{
    use SerializesModels;

    public $data;
    public $pdf;

    public function __construct($data, $pdf)
    {
        $this->data = $data;
        $this->pdf = $pdf;
    }

    public function build()
    {
        return $this->subject('Asunto del Correo')
            ->view('invoicePayment') // Vista del correo si deseas enviar HTML
            ->attachData($this->pdf->output(), 'archivo.pdf'); // Adjunta el PDF
    }
}