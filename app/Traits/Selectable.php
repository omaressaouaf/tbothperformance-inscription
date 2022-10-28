<?php

namespace App\Traits;

trait Selectable
{
    public function scopeSelectColumns($query, array $extraColumns = [])
    {
        if (count($this->getSelectable())) {
            return $query->select([...$this->getSelectable(), ...$extraColumns]);
        }

        if (count($extraColumns)) {
            return $query->select($extraColumns);
        }

        return $query;
    }

    /**
     * Get selectable columns
     *
     * @return array
     */
    private function getSelectable(): array
    {
        return $this->selectable ?? [];
    }
}
