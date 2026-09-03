<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\StreamedResponse;

class AdministrasiImportExportService
{
    /**
     * Generate a StreamedResponse for CSV/Excel download with UTF-8 BOM.
     *
     * @param string $filename
     * @param array $headers
     * @param array|\Traversable $rows
     * @return StreamedResponse
     */
    public static function exportCsv(string $filename, array $headers, iterable $rows): StreamedResponse
    {
        $responseHeaders = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($headers, $rows) {
            $handle = fopen('php://output', 'w');
            // Write UTF-8 BOM so Excel properly opens Indonesian accents & numbers
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Write header columns
            fputcsv($handle, $headers);

            // Write data rows
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);
        }, 200, $responseHeaders);
    }

    /**
     * Parse an uploaded CSV / text file with auto-delimiter detection (, or ; or \t).
     *
     * @param string $filePath
     * @return array
     */
    public static function parseCsv(string $filePath): array
    {
        if (!file_exists($filePath)) {
            return [];
        }

        $content = file_get_contents($filePath);
        // Remove UTF-8 BOM if present
        $bom = pack('H*', 'EFBBBF');
        $content = preg_replace("/^$bom/", '', $content);

        $lines = preg_split('/\r\n|\r|\n/', trim($content));
        if (empty($lines)) {
            return [];
        }

        // Detect delimiter from header line
        $firstLine = $lines[0];
        $delimiter = ',';
        if (substr_count($firstLine, ';') > substr_count($firstLine, ',')) {
            $delimiter = ';';
        } elseif (substr_count($firstLine, "\t") > substr_count($firstLine, ',')) {
            $delimiter = "\t";
        }

        $csvData = [];
        $handle = fopen($filePath, 'r');
        if ($handle !== false) {
            // Check BOM in stream
            $bomCheck = fread($handle, 3);
            if ($bomCheck !== "\xEF\xBB\xBF") {
                rewind($handle);
            }

            while (($data = fgetcsv($handle, 4096, $delimiter)) !== false) {
                // Filter out empty lines
                if (count($data) === 1 && $data[0] === null) {
                    continue;
                }
                // Trim each cell
                $csvData[] = array_map(function ($val) {
                    return is_string($val) ? trim($val) : $val;
                }, $data);
            }
            fclose($handle);
        }

        return $csvData;
    }
}
