<?php

/**
 * Laravel Application Entry Point (Root Directory)
 * 
 * This file forwards all requests to public/index.php
 * while maintaining proper paths for Laravel routing.
 */

// Get the request URI
$request_uri = $_SERVER['REQUEST_URI'];

// Check if we have a _route parameter (from index.html fallback)
if (isset($_GET['_route'])) {
    $request_uri = $_GET['_route'];
    // Remove _route from $_GET to avoid conflicts
    unset($_GET['_route']);
    // Rebuild query string without _route
    if (!empty($_GET)) {
        $request_uri .= '?' . http_build_query($_GET);
    }
}

// Remove the base path (/bmv) from the URI
$base_path = '/bmv';
if (strpos($request_uri, $base_path) === 0) {
    $request_uri = substr($request_uri, strlen($base_path));
}

// Remove query string
$uri_parts = explode('?', $request_uri);
$path = $uri_parts[0];

// Check if the request is for a static file in the public directory
$public_file = __DIR__ . '/public' . $path;
if ($path !== '/' && $path !== '' && file_exists($public_file) && is_file($public_file)) {
    // Serve the static file
    $mime_types = [
        'css' => 'text/css',
        'js' => 'application/javascript',
        'json' => 'application/json',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif' => 'image/gif',
        'svg' => 'image/svg+xml',
        'ico' => 'image/x-icon',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2',
        'ttf' => 'font/ttf',
        'eot' => 'application/vnd.ms-fontobject',
    ];
    
    $extension = pathinfo($public_file, PATHINFO_EXTENSION);
    $mime_type = $mime_types[$extension] ?? 'application/octet-stream';
    
    header('Content-Type: ' . $mime_type);
    readfile($public_file);
    exit;
}

// Adjust server variables for Laravel
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/public/index.php';
$_SERVER['SCRIPT_NAME'] = $base_path . '/index.php';
$_SERVER['PHP_SELF'] = $base_path . '/index.php';
$_SERVER['REQUEST_URI'] = $base_path . $path . (isset($uri_parts[1]) ? '?' . $uri_parts[1] : '');

// Set the document root to the public directory
$_SERVER['DOCUMENT_ROOT'] = __DIR__ . '/public';

// Include the actual Laravel entry point
require __DIR__ . '/public/index.php';
