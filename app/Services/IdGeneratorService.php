<?php

namespace App\Services;

use Illuminate\Support\Str;

class IdGeneratorService
{
    /**
     * Generate auto increment ID with prefix / suffix
     *
     * @param string $model
     * @param string $field
     * @param int $paddingLength
     * @param string $prefix
     * @param string $suffix
     * @return string
     */
    public static function generate(
        string $model,
        string $field = "id",
        int $paddingLength = 5,
        string $prefix = "",
        string $suffix  = "",
    ): string {
        $prefixLength = strlen($prefix);
        $suffixLength = strlen($suffix);

        $maxId = $model::query()
            ->where($field, "like", $prefix . '%' . $suffix)
            ->select("{$field} as max_id")
            // Select max id without prefix  : (e.g CL-00001/2022 => 00001/2022)
            ->selectRaw("SUBSTR({$field}, {$prefixLength} + 1) as max_id_without_prefix")
            // Remove suffix from previously created max_id_without_prefix and check if the sliced id is a number with regex (e.g 00001/2022 => 00001)
            ->groupBy($field)
            ->havingRaw(
                "SUBSTR(max_id_without_prefix, 1, LENGTH(max_id_without_prefix) - {$suffixLength}) REGEXP ?",
                ["^[0-9]+$"]
            )
            ->orderBy($field, "desc")
            ->first()
            ?->max_id;

        // Assumed length of the entire id
        $length = $prefixLength + $paddingLength + $suffixLength;

        // adaptive length in case the stripped id length surpasses the padding length (e.g 1000 while padding is 3)
        $adaptiveLength = $paddingLength + (strlen($maxId) - $length);

        // stripped max id from maxId starting from prefix length and extracting according to adaptive length
        $strippedMaxId = Str::substr($maxId, $prefixLength, $adaptiveLength);

        $nextStrippedId = (int)$strippedMaxId + 1;

        return $prefix . Str::padLeft($nextStrippedId, $paddingLength, '0') . $suffix;
    }
}
