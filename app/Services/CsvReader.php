<?php

namespace App\Services;

class CsvReader
{
    public function read(string $path): array
    {
        $content = file_get_contents($path);

        // Fjern BOM (usynligt mærke som Excel nogle gange sætter forrest)
        if (str_starts_with($content, "\xEF\xBB\xBF")) {
            $content = substr($content, 3);
        }

        // Dansk Excel gemmer ofte i Windows-1252, så vi omskriver til UTF-8
        if (! mb_check_encoding($content, 'UTF-8')) {
            $content = mb_convert_encoding($content, 'UTF-8', 'Windows-1252');
        }

        // Find skilletegn ud fra første linje
        $firstLine = strtok($content, "\r\n");
        $delimiter = substr_count($firstLine, ';') > substr_count($firstLine, ',') ? ';' : ',';

        // Læs filen række for række
        $handle = fopen('php://temp', 'r+');
        fwrite($handle, $content);
        rewind($handle);

        $headers = array_map(
            fn ($header) => strtolower(trim($header)),
            fgetcsv($handle, 0, $delimiter) ?: []
        );

        $rows = [];

        while (($line = fgetcsv($handle, 0, $delimiter)) !== false) {
            if ($line === [null]) {
                continue; // tom linje
            }

            $line = array_pad($line, count($headers), '');
            $rows[] = array_combine($headers, array_slice($line, 0, count($headers)));
        }

        fclose($handle);

        return [
            'delimiter' => $delimiter,
            'headers' => $headers,
            'rows' => $rows,
        ];
    }
}