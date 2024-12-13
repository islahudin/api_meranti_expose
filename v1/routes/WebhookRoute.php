<?php

use App\Controllers\WebhookController;

$app->post('/webhook/git', WebhookController::class . ':webhookGit');
