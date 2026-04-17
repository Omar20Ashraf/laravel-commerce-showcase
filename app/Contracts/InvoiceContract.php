<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Relations\MorphOne;

interface InvoiceContract
{
    ## Relations

    public function invoice(): MorphOne;

    ## Getters & Setters

    public function getModuleIdAttribute(): int;

    ## Query Scope Methods

    ## Other Methods
}
