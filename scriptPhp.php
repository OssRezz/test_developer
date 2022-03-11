<?php
$attendanceIn = "14:00";
$attendanceOut = "21:30";


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

    $inicioHorasOrdinarias =  hoursToMinutes($startHO); //480
    $inicioExtras = hoursToMinutes($startHED); //1080
    $inicioExtrasNocturnas = hoursToMinutes($startHEN); //1260
    $finalExtrasNocturna = hoursToMinutes($endHEN);
    $finalExtrasNocturna = $finalExtrasNocturna + 1; //360

    $mediaNoche = 1440;

    $HO = null;
    $HED = null;
    $HEN = null;


    $horaEntrada = hoursToMinutes($attendanceIn);
    $horaSalida = hoursToMinutes($attendanceOut);

    //Condicional para conocer las horas ordinarias
    if ($horaEntrada < $horaSalida && $horaEntrada >= $inicioHorasOrdinarias && $horaSalida < $inicioExtras) {
        $HED = 0;
        $HEN = 0;
        $HO = (($inicioExtras - $horaEntrada) - ($inicioExtras - $horaSalida)) / 60;
        if ($HO >= 9.98) {
            $HO = ceil($HO);
        }

        //Condicional para saber las horas extras ordinarias
    } else if ($horaEntrada < $horaSalida && $horaEntrada >= $inicioHorasOrdinarias && $horaSalida < $inicioExtrasNocturnas) {
        $HEN = 0;


        $horasTotales = (($horaSalida - $inicioExtrasNocturnas) - ($horaEntrada - $inicioExtrasNocturnas)) / 60;
        $HED = (($horaSalida - $inicioExtras) / 60);
        $HO = $horasTotales - $HED;

        if ($HED > 2.98) {
            $HED = ceil($HED);
        }


        //Condicional para horas extras nocturnas
    } else if ($horaEntrada >= $inicioHorasOrdinarias && $horaSalida >= $inicioExtrasNocturnas) {
        $horasTotales = (($horaSalida - $inicioExtrasNocturnas) - ($horaEntrada - $inicioExtrasNocturnas)) / 60;
        $HED = (($horaSalida - $inicioExtras) - ($horaSalida - $inicioExtrasNocturnas)) / 60;
        $HEN = (($horaSalida - $inicioExtrasNocturnas) / 60);

        $HO = $horasTotales - $HED - $HEN;

        if ($HEN > 2.98) {
            $HEN = ceil($HED);
        }

        //Condicional para saber las horas ordinarias, horas nocturnas, horas extras
        // si el valor de salida es mayor a la media noche
    } else if ($horaSalida < $horaEntrada && $horaEntrada <= $inicioExtras) {
        $HO = ($inicioExtras - $horaEntrada) / 60;
        $HED = ($inicioExtrasNocturnas - $inicioExtras) / 60;
        $HEN = (($mediaNoche - $inicioExtrasNocturnas) + ($horaSalida)) / 60;

        if ($HEN > 8.98) {
            $HEN = ceil($HEN);
        }

        //Condicional para saber las horas extras y horas extras nocturnas
    } else if ($horaSalida < $horaEntrada && $horaEntrada >= $inicioExtras && $horaEntrada <  $inicioExtrasNocturnas) {
        $HO = 0;
        $HED = (($inicioExtrasNocturnas - $inicioExtras) - ($inicioExtrasNocturnas - $horaEntrada)) / 60;
        $HEN = (($mediaNoche - $inicioExtrasNocturnas) + ($horaSalida)) / 60;
        if ($HEN > 8.98) {
            $HEN = ceil($HEN);
        }
        if ($HED > 2.98) {
            $HED = ceil($HED);
        }

        //Condicional para saber el total de horas nocturnas, despues de las 20:59 hasta las 5:59 (HO = 0, HED=0)
    } else if ($horaEntrada >= $inicioExtrasNocturnas && $horaSalida <= $finalExtrasNocturna) {
        $HED = 0;
        $HO = 0;

        $HEN = (($mediaNoche - $horaEntrada) + ($horaSalida)) / 60;
        if ($HEN > 8.98) {
            $HEN = ceil($HEN);
        }
        //Condiccional para saber si la hora es despues de las 24:00 hasta las 5:59
    } else if ($horaEntrada < $finalExtrasNocturna && $horaSalida <= $finalExtrasNocturna) {
        $HED = 0;
        $HO = 0;

        $HEN = ($horaSalida - $horaEntrada) / 60;
    }

    if ($HO != 0 && $HED === 0 && $HED === 0) {
        //
        echo json_encode(['HO' => round($HO, 2)]);
        //
    } else if ($HO != 0 && $HED != 0 && $HEN === 0) {
        //
        echo json_encode(['HO' => round($HO, 2), 'HED' => round($HED, 2)]);
        //
    } else if ($HO != 0 && $HED != 0 && $HEN != 0) {
        //
        echo json_encode(['HO' => round($HO, 2), 'HED' => round($HED, 2), 'HEN' => round($HEN, 2)]);
        //
    } else if ($HO === 0 && $HED != 0 && $HEN === 0) {
        //
        echo json_encode(['HED' => round($HED, 2)]);
        //
    } else if ($HO === 0 && $HED != 0 && $HEN != 0) {
        //
        echo json_encode(['HED' => round($HED, 2), 'HEN' => round($HEN, 2)]);
        //
    } else if ($HO === 0 && $HED === 0 && $HEN != 0) {
        //
        echo json_encode(['HEN' => round($HEN, 2)]);
        //
    }
}

function hoursToMinutes($hours)
{
    $v_HorasPartes = explode(":", $hours);
    $minutosTotales = ($v_HorasPartes[0] * 60) + $v_HorasPartes[1];
    return $minutosTotales;
}
