<?php

namespace App\Traits;

use Carbon\Carbon;

trait HasSerialReferenceNumber
{
    public function nextReferenceNumber(string $serialStart = '0000001', ?string $prefix = '', ?self $record = null)
    {
        $search = '-' . Carbon::now()->format('Y') . '-' . Carbon::now()->format('m') . '-';
        $lastRecordReference = $record == null ? self::where('reference_number', 'LIKE', '%' . $search . '%')->orderBy('id', 'desc')->limit(1)->first() : $record;

        $nextSerial = $this->nextSerialAfter(record: $lastRecordReference, serialStart: $serialStart);
        $newReferenceNumber = "{$prefix}{$search}{$nextSerial}";
        $referenceNumberExistingRecord = self::where('reference_number', $newReferenceNumber)->first();

        return !$referenceNumberExistingRecord ? $newReferenceNumber : $this->nextReferenceNumber(record: $this->$referenceNumberExistingRecord);
    }

    private function nextSerialAfter(string $serialStart, ?self $record = null)
    {
        if ($record == null) return $serialStart;

        $referenceNumberParts = \explode('-', $record->reference_number);
        $newSerial = (string) (((int) \array_pop($referenceNumberParts)) + 1);
        $zerosCountToAddAsPrefix = \strlen($serialStart) - \strlen($newSerial);

        return \str_repeat('0', $zerosCountToAddAsPrefix) . $newSerial;
    }
}
