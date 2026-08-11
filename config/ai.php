<?php

return [
    'default_model' => env('AI_DEFAULT_MODEL', 'gpt-4o-mini'),
    'temperature' => (float) env('AI_TEMPERATURE', 0.3),
    'max_tokens' => (int) env('AI_MAX_TOKENS', 800),
];
