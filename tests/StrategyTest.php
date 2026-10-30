<?php

require_once __DIR__ . '/../src/Pricing/PricingStrategy.php';
require_once __DIR__ . '/../src/Pricing/PrivatePatientStrategy.php';
require_once __DIR__ . '/../src/Pricing/InsuranceStrategy.php';
require_once __DIR__ . '/../src/Pricing/RetiredStrategy.php';
require_once __DIR__ . '/../src/Pricing/PrepaidStrategy.php';
require_once __DIR__ . '/../src/Pricing/PriceCalculator.php';

$casos = [
    ['Particular', new PrivatePatientStrategy(), 10000],
    ['Obra social', new InsuranceStrategy(), 7000],
    ['Jubilado', new RetiredStrategy(), 5000],
    ['Prepaga', new PrepaidStrategy(), 8000],
];

foreach ($casos as [$tipo, $estrategia, $esperado]) {

    $calculadora = new PriceCalculator($estrategia);

    $resultado = $calculadora->calculate(10000);

    if (abs($resultado - $esperado) > 0.001) {
        echo "ERROR - $tipo: esperado $esperado, obtenido $resultado" . PHP_EOL;
        exit(1);
    }

    echo "OK - $tipo: $resultado" . PHP_EOL;
}

echo "Todas las pruebas pasaron correctamente." . PHP_EOL;