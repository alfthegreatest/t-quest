<?php

namespace App\Livewire\Traits;

trait WithImageValidation
{
    public function getMaxImageSizeMbProperty()
    {
        preg_match('/max:(\d+)/', $this->rules['image'], $matches);

        return isset($matches[1]) ? $matches[1] / 1024 : null;
    }
}
