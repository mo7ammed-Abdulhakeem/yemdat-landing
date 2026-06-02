<?php

namespace App\Support;

use DateTimeInterface;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Streams an array of rows to the browser as a downloadable CSV.
 *
 * Synchronous (no queue) and dependency-free — reliable on shared hosting.
 * Writes a UTF-8 BOM so Excel renders Arabic (and other non-ASCII) correctly.
 */
class CsvExport
{
    /**
     * @param  string  $filename  e.g. "members.csv"
     * @param  array<int, string>  $headers  Column header labels.
     * @param  iterable<array<int|string, mixed>>  $rows  Each row as an ordered array of values.
     */
    public static function download(string $filename, array $headers, iterable $rows): StreamedResponse
    {
        return response()->streamDownload(function () use ($headers, $rows) {
            $out = fopen('php://output', 'w');

            // BOM — makes Excel detect UTF-8 instead of mangling Arabic.
            fwrite($out, "\xEF\xBB\xBF");

            fputcsv($out, $headers);

            foreach ($rows as $row) {
                fputcsv($out, array_map(static fn ($value) => self::stringify($value), $row));
            }

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Normalise a cell value to a CSV-safe string.
     */
    protected static function stringify(mixed $value): string
    {
        if ($value instanceof DateTimeInterface) {
            return $value->format('Y-m-d H:i:s');
        }

        if (is_bool($value)) {
            return $value ? 'Yes' : 'No';
        }

        if (is_array($value)) {
            return implode(', ', $value);
        }

        return (string) ($value ?? '');
    }
}
