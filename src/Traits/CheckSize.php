<?php

use Bigmom\VeEditor\Models\Asset;
use Illuminate\Support\Facades\Cache;

class CheckSize
{
    public function getSize($uploadedFile)
    {
        return $uploadedFile->getSize();
    }

    public function checkSizeLimit($uploadedFile)
    {
        $sizeLimit = config('ve.size-limit');

        if ($sizeLimit) {
            $fileSize = $this->getSize($uploadedFile);
            $occupiedSize = $this->getOccupiedSize;

            $newSize = $occupiedSize + $fileSize;
            if ($newSize > $sizeLimit) {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    public function getOccupiedSize()
    {
        return Cache::rememberForever('occupied-size', function () {
            $occupiedSize = 0;

            foreach (Asset::get() as $asset) {
                $occupiedSize += $asset->size;
            }

            return $occupiedSize;
        });
    }
}