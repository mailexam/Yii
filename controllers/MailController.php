<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;

class MailController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionTest(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $data = json_decode(Yii::$app->request->rawBody, true) ?? [];

        $from = getenv('MAIL_FROM') ?: 'noreply@example.test';
        $to = $data['to'] ?? 'user@example.test';
        $subject = $data['subject'] ?? 'Yii + Mailexam';
        $body = $data['body'] ?? $data['text'] ?? 'Mailexam test from Yii';

        Yii::$app->mailer->compose()
            ->setFrom([$from => 'Mailexam Test'])
            ->setTo($to)
            ->setSubject($subject)
            ->setTextBody($body)
            ->send();

        return ['status' => 'ok'];
    }
}
