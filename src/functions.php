<?php

function e($value)
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function parseEmployee($row)
{
    return [
        'id' => $row[0],
        'name' => $row[1],
        'department' => $row[2],
        'experience' => $row[3],
        'email' => $row[4],
    ];
}

function loadEmployees($path)
{
    $employees = [];
    $file = fopen($path, 'r');
    fgetcsv($file, 1000, ';', '"', '\\');

    while ($row = fgetcsv($file, 1000, ';', '"', '\\')) {
        $employees[] = parseEmployee($row);
    }

    fclose($file);
    return $employees;
}

function validateInput($form, $departments)
{
    $errors = [];

    if ($form['name'] == '') {
        $errors['name'] = 'Укажите имя';
    } elseif (mb_strlen($form['name']) < 2 || mb_strlen($form['name']) > 50) {
        $errors['name'] = 'Имя от 2 до 50 символов';
    }

    if (!filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Некорректный email';
    }

    if (!in_array($form['department'], $departments)) {
        $errors['department'] = 'Выберите отдел';
    }

    if (!is_numeric($form['experience']) || $form['experience'] < 0 || $form['experience'] > 50) {
        $errors['experience'] = 'Стаж от 0 до 50';
    }

    return $errors;
}
