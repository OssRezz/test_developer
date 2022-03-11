<?php
$attendanceIn = "08:00";
$attendanceOut = "11:30";


$concepts = [
    [
        "id" => "HO",
        "name" => "HO",
        "start" => "08:00",
        "end" => "17:59"
    ],
    [
        "id" => "HED",
        "name" => "HED",
        "start" => "18:00",
        "end" => "20:59"
    ],
    [
        "id" => "HEN",
        "name" => "HEN",
        "start" => "21:00",
        "end" => "05:59"
    ]
];

classifyAttendances($concepts, $attendanceIn, $attendanceOut);


function classifyAttendances($concepts, $attendanceIn, $attendanceOut)
{
    foreach ($concepts as $concepts) {
        if ($concepts['id'] === "HO") {
            $nameHO = $concepts['name'];
            $startHO = $concepts['start'];
            $endHO = $concepts['end'];
        } else if ($concepts['id'] === "HED") {
            $nameHED = $concepts['name'];
            $startHED = $concepts['start'];
            $endHED = $concepts['end'];
        } else if ($concepts['id'] === "HEN") {
            $nameHEN = $concepts['name'];
            $startHEN = $concepts['start'];
            $endHEN = $concepts['end'];
        }
    }

    $finalExtrasNocturna = 360;
    $inicioHorasOrdinarias = 480;
    $inicioExtras = 1080;
    $inicioExtrasNocturnas = 1260;
    $mediaNoche = 1440;
    $HO = null;
    $HED = null;
    $HEN = null;


    echo "Entrada: " . $horaEntrada = hoursToMinutes($attendanceIn);
    echo "<br>";
    echo "Entrada: " .  $horaSalida = hoursToMinutes($attendanceOut);

    if ($horaEntrada >= $inicioHorasOrdinarias && $horaSalida < $inicioExtras) {
        $HED = 0;
        $HEN = 0;

        $HO = (($inicioExtras - $horaEntrada) - ($inicioExtras - $horaSalida)) / 60;
    }


    echo "<br>";
    echo json_encode(['HO' => $HO]);





    echo "<br>";
    echo "<br>";
    echo "<br>";


    echo $nameHO . ": horas ordinarias";
    echo "<br>";
    echo $startHO;
    echo "<br>";
    echo $endHO;


    echo "<br>";
    echo "<br>";

    echo $nameHED . ": horas extras";
    echo "<br>";
    echo $startHED;
    echo "<br>";
    echo $endHED;

    echo "<br>";
    echo "<br>";

    echo $nameHEN . ": horas extras nocturnas ";
    echo "<br>";
    echo $startHEN;
    echo "<br>";
    echo $endHEN;
}



function hoursToMinutes($hours)
{
    $v_HorasPartes = explode(":", $hours);
    $minutosTotales = ($v_HorasPartes[0] * 60) + $v_HorasPartes[1];
    return $minutosTotales;
}




    // echo $nameHO . ": horas ordinarias";
    // echo "<br>";
    // echo $startHO;
    // echo "<br>";
    // echo $endHO;
    // echo "<br>";

    // echo  hoursToMinutes($startHO);

    // echo "<br>";
    // echo "<br>";

    // echo $nameHED . ": horas extras";
    // echo "<br>";
    // echo $startHED;
    // echo "<br>";
    // echo $endHED;

    // echo "<br>";
    // echo "<br>";

    // echo $nameHEN . ": horas extras nocturnas ";
    // echo "<br>";
    // echo $startHEN;
    // echo "<br>";
    // echo $endHEN;