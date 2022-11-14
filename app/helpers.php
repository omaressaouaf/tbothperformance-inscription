<?php

use App\Models\Lead;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;

/**
 * Parse given date into carbon if needed
 *
 * @param string|\Illuminate\Support\Carbon
 * @return \Illuminate\Support\Carbon
 */
function parse_date(string|Carbon $date)
{
    return $date instanceof Carbon ? $date : Carbon::parse($date);
}

/**
 * Format date
 *
 * @param string|\Illuminate\Support\Carbon
 * @param string $format
 * @return string
 */
function format_date(string|Carbon $date, string $format = "MMMM D, YYYY"): string
{
    return parse_date($date)->isoFormat($format);
}

/**
 * Format date time
 *
 * @param string|\Illuminate\Support\Carbon
 * @return string
 */
function format_date_time(string|Carbon $date): string
{
    return parse_date($date)->isoFormat("MMMM D, YYYY LT");
}

/**
 * Return the previously intended location
 *
 * @param  mixed  $default
 * @return string
 */
function intended($default = '/'): string
{
    return session()->pull('url.intended', $default);
}

/**
 * Resolve json data
 *
 * @param string $path
 * @return object|array
 */
function resolve_json_data(string $path)
{
    return json_decode(File::get(database_path("json/{$path}.json")), true);
}

/**
 * Get file url
 *
 * @param string $path
 * @param string $disk
 * @return string
 */
function file_url(string $path, string $disk = null): string
{
    return route("files.serve", [
        "path" => $path,
        "disk" => $disk
    ]);
}

/**
 * Return storage disk by name
 *
 * @param string $name
 * @return \Illuminate\Filesystem\FilesystemAdapter
 */
function storage_disk($name): FilesystemAdapter
{
    return Storage::disk($name);
}

/**
 * Generate public s3 url
 *
 * @param string $path
 * @return string
 *
 * @throws \RuntimeException
 */
function s3_url(string $path): string
{
    return Str::replace("%5C", "/", storage_disk("s3")->url($path));
}

/**
 * Generate public temporary s3 url
 *
 * @param string $path
 * @return string
 *
 * @throws \RuntimeException
 */
function s3_temporary_url(string $path, \DateTimeInterface $expiration, array $options = []): string
{
    return Str::replace("%5C", "/", storage_disk("s3")->temporaryUrl($path, $expiration, $options));
}

/**
 * Return a decimal as string.
 *
 * @param mixed $value
 * @param int $decimals
 * @return string
 */
function as_decimal(mixed $value, int $decimals = 2): string
{
    return number_format($value, $decimals, '.', '');
}

/**
 * Format money
 *
 * @param mixed $number
 * @return string
 */
function format_money(mixed $number): string
{
    return number_format($number, 2) . " " . __("€");
}

/**
 * Get auth user
 *
 * @return \App\Models\User|\App\Models\Lead|null
 */
function auth_user(): User|Lead|null
{
    return auth()->user();
}
