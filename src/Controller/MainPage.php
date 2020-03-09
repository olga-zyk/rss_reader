<?php


namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;


class MainPage
{
    public function main()
    {
        $number = random_int(0, 100);

        return new Response(
            '<html><body>Check random: ' . $number . '</body></html>'
        );
    }

}