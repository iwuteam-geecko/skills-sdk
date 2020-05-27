<?php
\Illuminate\Support\Facades\Route::namespace('Geecko\Skills\Controllers')->group(function () {
    \Illuminate\Support\Facades\Route::post('skills-service/webhooks/session-started', 'WebhookController@sessionStarted');
    \Illuminate\Support\Facades\Route::post('skills-service/webhooks/session-finished', 'WebhookController@sessionFinished');
    \Illuminate\Support\Facades\Route::post('skills-service/webhooks/session-canceled', 'WebhookController@sessionCanceled');
    \Illuminate\Support\Facades\Route::post('skills-service/webhooks/results-received', 'WebhookController@sessionResultsReceived');
    \Illuminate\Support\Facades\Route::post('skills-service/webhooks/feedback-received', 'WebhookController@sessionFeedbackReceived');

});
