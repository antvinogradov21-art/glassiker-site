<?php
// Защита от прямого доступа
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit('Method Not Allowed');
}

$to      = 'zavod@steklodom.online';
$type    = isset($_POST['type']) ? $_POST['type'] : 'order';
$name    = isset($_POST['name'])    ? htmlspecialchars(strip_tags($_POST['name']))    : '—';
$phone   = isset($_POST['phone'])   ? htmlspecialchars(strip_tags($_POST['phone']))   : '—';
$email   = isset($_POST['email'])   ? htmlspecialchars(strip_tags($_POST['email']))   : '—';
$product = isset($_POST['product']) ? htmlspecialchars(strip_tags($_POST['product'])) : '—';
$comment = isset($_POST['comment']) ? htmlspecialchars(strip_tags($_POST['comment'])) : '—';

if ($type === 'catalog') {
    $subject = '=?UTF-8?B?' . base64_encode('Запрос каталога — Glassiker') . '?=';
    $body  = "Новый запрос каталога продукции\n";
    $body .= "================================\n";
    $body .= "Имя:    $name\n";
    $body .= "E-mail: $email\n";
} else {
    $subject = '=?UTF-8?B?' . base64_encode('Новая заявка — Glassiker') . '?=';
    $body  = "Новая заявка с сайта Glassiker\n";
    $body .= "================================\n";
    $body .= "Имя:          $name\n";
    $body .= "Телефон:      $phone\n";
    $body .= "Тип продукции: $product\n";
    $body .= "Комментарий:  $comment\n";
}

$body .= "================================\n";
$body .= "Дата: " . date('d.m.Y H:i') . "\n";
$body .= "IP: " . $_SERVER['REMOTE_ADDR'] . "\n";

$headers  = "From: noreply@steklodom.online\r\n";
$headers .= "Reply-To: $email\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

$sent = mail($to, $subject, $body, $headers);

header('Content-Type: application/json');
echo json_encode(['success' => $sent]);
?>
