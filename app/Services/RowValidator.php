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