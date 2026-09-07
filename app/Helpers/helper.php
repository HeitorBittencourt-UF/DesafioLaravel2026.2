<?php

if (! function_exists('formatarTelefone')) {
    function formatarTelefone(?string $telefone): string
    {
        if (empty($telefone)) {
            return '';
        }

        $numeros = preg_replace('/\D/', '', $telefone);

        if (str_starts_with($numeros, '55')) {
            $numeroBrasileiro = substr($numeros, 2);

            if (strlen($numeroBrasileiro) === 11) {
                return sprintf(
                    '+55 (%s) %s-%s',
                    substr($numeroBrasileiro, 0, 2),
                    substr($numeroBrasileiro, 2, 5),
                    substr($numeroBrasileiro, 7, 4)
                );
            }

            if (strlen($numeroBrasileiro) === 10) {
                return sprintf(
                    '+55 (%s) %s-%s',
                    substr($numeroBrasileiro, 0, 2),
                    substr($numeroBrasileiro, 2, 4),
                    substr($numeroBrasileiro, 6, 4)
                );
            }
        }

        return str_starts_with($telefone, '+') ? $telefone : '+' . $numeros;
    }
}