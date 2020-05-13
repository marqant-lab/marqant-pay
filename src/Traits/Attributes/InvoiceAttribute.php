<?php

namespace Marqant\MarqantPay\Traits\Attributes;

use Illuminate\Support\Facades\Storage;

/**
 * Trait InvoiceAttribute
 *
 * @package Marqant\MarqantPay\Traits\Attributes
 */
trait InvoiceAttribute
{

    /**
     * Method to get the invoice as string path value.
     *
     * @param string|null $invoice
     *
     * @return string
     */
    public function getInvoiceAttribute(string $invoice = null): string
    {
        if (Storage::disk(env('FILESYSTEM_DRIVER', 'public'))->exists($invoice)) {
            return $invoice;
        }

        return '';
    }

    /**
     * Method to get the invoice as file url.
     *
     * @param string|null $invoice
     *
     * @return string
     */
    public function getInvoiceUrlAttribute(string $invoice = null): string
    {
        $disc = Storage::disk(env('FILESYSTEM_DRIVER', 'public'));
        if ($disc->exists($invoice)) {
            return $disc->url($invoice);
        }

        return '';
    }

}
