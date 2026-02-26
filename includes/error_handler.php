<?php
/**
 * Global Error Interceptor Engine
 * 
 * Catch all fatal runtime errors and uncaught exceptions directly at the PHP execution level,
 * scrub the output buffer, and securely redirect the DOM payload to a styled 500 error page.
 */

function dynaBio_exception_handler($exception)
{
    _render_fatal_500_view();
}

function dynaBio_error_handler()
{
    $error = error_get_last();
    // E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR are fatal crashes
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        _render_fatal_500_view();
    }
}

function _render_fatal_500_view()
{
    // 1. Scrub the broken DOM output buffer to prevent half-rendered HTML
    while (ob_get_level()) {
        ob_end_clean();
    }

    // 2. Transmit the proper HTTP Status Code header
    http_response_code(500);

    // 3. Render the safe visual fallback template exclusively
    require_once __DIR__ . '/../views/500.php';

    // 4. Halt execution mechanically
    exit();
}

// Attach interceptors
set_exception_handler('dynaBio_exception_handler');
register_shutdown_function('dynaBio_error_handler');

// Ensure buffering is running at runtime so we can scrub it if a crash fires midway through a script
if (ob_get_level() === 0) {
    ob_start();
}
?>