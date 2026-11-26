<?php

namespace App\Services;

class RowValidator
{
    private const REQUIRED = [
        'sku' => 'Varenummer (SKU)',
        'name' => 'Produktnavn',
        'brand' => 'Mærke',
        'ean' => 'EAN',
        'recommended_price' => 'Vejledende pris',
    ];

    public function validate(array $row): array
    {
        $issues = [];

        foreach ($this->rules() as $rule) {
            $issue = $rule($row);

            if ($issue !== null) {
                $issues[] = $issue;
            }
        }

        return $issues;
    }

        public function validateAll(array $rows): array
    {
        $result = [];

        foreach ($rows as $index => $row) {
            $result[$index] = $this->validate($row);
        }

        foreach (['sku' => 'SKU', 'ean' => 'EAN'] as $field => $label) {
            $first = [];

            foreach ($rows as $index => $row) {
                $value = trim($row[$field] ?? '');

                if ($value === '') {
                    continue;
                }

                if ($field === 'ean' && ! preg_match('/^\d{13}$/', $value)) {
                    continue; // ugyldige EAN'er har allerede fået en fejl
                }

                if (isset($first[$value])) {
                    $result[$index][] = $this->error(
                        $field,
                        $label . ' ' . $value . ' findes allerede i række ' . ($first[$value] + 1),
                        'Fjern dubletten, eller ret ' . $label . ', så hver række har en unik værdi.'
                    );
                } else {
                    $first[$value] = $index;
                }
            }
        }

        return $result;
    }

    private function rules(): array
    {
        $rules = [];

        foreach (self::REQUIRED as $field => $label) {
            $rules[] = fn (array $row) => trim($row[$field] ?? '') === ''
                ? $this->error($field, $label . ' mangler', 'Udfyld ' . $label . ' i filen og upload igen.')
                : null;
        }

                $rules[] = function (array $row) {
            $ean = trim($row['ean'] ?? '');

            if ($ean === '') {
                return null; // manglende EAN fanges af den påkrævede regel
            }

            if (preg_match('/^\d+[.,]?\d*E\+\d+$/i', $ean)) {
                return $this->error(
                    'ean',
                    'EAN er gemt som et tal i Excel (' . $ean . ')',
                    'Formatér EAN-kolonnen som tekst i Excel, og indtast EAN-numrene igen.'
                );
            }

            if (! preg_match('/^\d{13}$/', $ean)) {
                return $this->error(
                    'ean',
                    'EAN skal være præcis 13 cifre (der er ' . strlen($ean) . ')',
                    'Ret EAN til 13 cifre uden mellemrum eller bogstaver.'
                );
            }

            return null;
        };

        $rules[] = function (array $row) {
            $price = trim($row['recommended_price'] ?? '');

            if ($price === '') {
                return null; // manglende pris fanges af den påkrævede regel
            }

            if (! preg_match('/^\d+([.,]\d{1,2})?$/', $price)) {
                return $this->error(
                    'recommended_price',
                    'Vejl. pris "' . $price . '" er ikke et gyldigt beløb',
                    'Skriv prisen som et tal med højst to decimaler, fx 3950,50 eller 3950.50, uden valuta og tusindtalsseparator.'
                );
            }

            if ((float) str_replace(',', '.', $price) <= 0) {
                return $this->error(
                    'recommended_price',
                    'Vejl. pris skal være større end 0',
                    'Ret prisen til et beløb over 0.'
                );
            }

            return null;
        };

        return $rules;
    }

    private function error(string $field, string $message, string $suggestion): array
    {
        return ['level' => 'error', 'field' => $field, 'message' => $message, 'suggestion' => $suggestion];
    }

    private function warning(string $field, string $message, string $suggestion): array
    {
        return ['level' => 'warning', 'field' => $field, 'message' => $message, 'suggestion' => $suggestion];
    }
}