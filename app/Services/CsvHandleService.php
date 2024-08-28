<?php

namespace App\Services;

class CsvHandleService
{
    public function handle(string $file): array
    {
        $data = array_map('str_getcsv', file($file));
        $headers = array_shift($data);

        return $this->combineHeadersAndData($headers, $data);
    }

    /**
     * Combine headers and data into associative arrays.
     *
     * @param array $headers
     * @param array $data
     * @return array
     */
    private function combineHeadersAndData(array $headers, array $data): array
    {
        $combinedData = [];

        foreach ($data as $row) {
            $combinedData[] = array_combine($headers, $row);
        }

        return $combinedData;
    }
}
