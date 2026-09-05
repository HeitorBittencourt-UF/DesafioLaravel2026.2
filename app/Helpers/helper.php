<?php

if (! function_exists('formatarTelefone')) {

    function formatarTelefone(?string $telefone): string
    {
        if (empty($telefone)) {
            return '';
        }

        $numeros = preg_replace(
            '/\D/',
            '',
            $telefone
        );

        /*
         * Telefone brasileiro.
         */
        if (str_starts_with($numeros, '55')) {

            $numeroBrasileiro =
                substr($numeros, 2);

            /*
             * Celular:
             *
             * +5532999999999
             *
             * vira:
             *
             * +55 (32) 99999-9999
             */
            if (strlen($numeroBrasileiro) === 11) {

                return sprintf(
                    '+55 (%s) %s-%s',
                    substr($numeroBrasileiro, 0, 2),
                    substr($numeroBrasileiro, 2, 5),
                    substr($numeroBrasileiro, 7, 4)
                );
            }


            /*
             * Telefone fixo:
             *
             * +553233334444
             *
             * vira:
             *
             * +55 (32) 3333-4444
             */
            if (strlen($numeroBrasileiro) === 10) {

                return sprintf(
                    '+55 (%s) %s-%s',
                    substr($numeroBrasileiro, 0, 2),
                    substr($numeroBrasileiro, 2, 4),
                    substr($numeroBrasileiro, 6, 4)
                );
            }
        }


        /*
         * Caso seja telefone internacional,
         * mantém o formato normalizado.
         */
        return str_starts_with($telefone, '+')
            ? $telefone
            : '+' . $numeros;
    }
}