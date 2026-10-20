<?php
$isLocal = $_SERVER['SERVER_NAME'] == 'localhost';
if ($isLocal) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
}

require_once __DIR__ . '/../src/functions.php';

$appVersion = '1.0';
$departments = ['Разработка', 'Аналитика', 'Тестирование', 'Поддержка'];
$employees = loadEmployees(__DIR__ . '/../data/employees.csv');
$count = count($employees);

$filter = $_GET['department'] ?? '';
$shown = [];
foreach ($employees as $emp) {
    if ($filter == '' || $emp['department'] == $filter) {
        $shown[] = $emp;
    }
}

$form = ['name' => '', 'email' => '', 'department' => '', 'experience' => ''];
$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $form['name'] = trim($_POST['name'] ?? '');
    $form['email'] = trim($_POST['email'] ?? '');
    $form['department'] = trim($_POST['department'] ?? '');
    $form['experience'] = trim($_POST['experience'] ?? '');

    $errors = validateInput($form, $departments);
    $success = count($errors) == 0;
}
?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Практическая работа 4</title>
    <style>
        body { font-family: sans-serif; margin: 0; background: #f4f5fa; color: #1f2937; }
        header { background: #111827; color: white; padding: 10px 40px; }
        header h1 { margin: 0; font-size: 24px; }
        header p { margin: 5px 0; color: #9ca3af; }
        header a { color: #cbd5e1; margin-right: 15px; }
        main { max-width: 900px; margin: 30px auto; }
        section { background: white; border: 1px solid #e5e7eb; border-radius: 15px; padding: 20px 30px; margin-bottom: 25px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        td, th { text-align: left; padding: 10px; border-bottom: 1px solid #e5e7eb; }
        input, select { padding: 6px; width: 250px; }
        button { padding: 7px 15px; background: #2563eb; color: white; border: none; border-radius: 6px; cursor: pointer; }
        .error { color: #dc2626; }
        .success { color: #16a34a; font-weight: bold; }
    </style>
</head>
<body>
<header>
    <h1>Сотрудники</h1>
    <p>Версия <?= $appVersion ?>, сегодня <?= date('d.m.Y') ?></p>
    <nav>
        <a href="#employees">Сотрудники</a>
        <a href="#request">Заявка</a>
    </nav>
</header>

<main>
    <section id="employees">
        <h2>Сотрудники (<?= $count ?>)</h2>

        <form method="get" action="#employees">
            <select name="department">
                <option value="">Все отделы</option>
                <?php foreach ($departments as $dep): ?>
                    <option <?php if ($dep == $filter) echo 'selected'; ?>><?= $dep ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit">Показать</button>
        </form>

        <?php if (count($shown) == 0): ?>
            <p>Сотрудники не найдены</p>
        <?php endif; ?>

        <table>
            <tr><th>ID</th><th>Имя</th><th>Отдел</th><th>Стаж</th><th>Email</th></tr>
            <?php foreach ($shown as $emp): ?>
                <tr>
                    <td><?= e($emp['id']) ?></td>
                    <td><?= e($emp['name']) ?></td>
                    <td><?= e($emp['department']) ?></td>
                    <td><?= e($emp['experience']) ?></td>
                    <td><?= e($emp['email']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>

    <section id="request">
        <h2>Заявка</h2>

        <?php if ($success): ?>
            <p class="success">Заявка принята: <?= e($form['name']) ?></p>
        <?php endif; ?>

        <form method="post" action="#request">
            <p>
                Имя<br>
                <input type="text" name="name" value="<?= e($form['name']) ?>">
                <span class="error"><?= $errors['name'] ?? '' ?></span>
            </p>
            <p>
                Email<br>
                <input type="text" name="email" value="<?= e($form['email']) ?>">
                <span class="error"><?= $errors['email'] ?? '' ?></span>
            </p>
            <p>
                Отдел<br>
                <select name="department">
                    <option value="">-- выберите --</option>
                    <?php foreach ($departments as $dep): ?>
                        <option <?php if ($dep == $form['department']) echo 'selected'; ?>><?= $dep ?></option>
                    <?php endforeach; ?>
                </select>
                <span class="error"><?= $errors['department'] ?? '' ?></span>
            </p>
            <p>
                Стаж<br>
                <input type="text" name="experience" value="<?= e($form['experience']) ?>">
                <span class="error"><?= $errors['experience'] ?? '' ?></span>
            </p>
            <button type="submit">Отправить</button>
        </form>
    </section>
</main>
</body>
</html>
