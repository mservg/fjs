<?php
/**
 * Theme Test File
 * This file helps diagnose theme activation issues
 */

// Test if WordPress is loaded
if (!defined('ABSPATH')) {
    echo "WordPress not loaded properly";
    exit;
}

// Test basic theme functions
echo "<h1>Theme Test Results</h1>";

// Test 1: Check if functions.php is loaded
if (function_exists('saab_theme_setup')) {
    echo "<p style='color: green;'>✅ Theme setup function exists</p>";
} else {
    echo "<p style='color: red;'>❌ Theme setup function missing</p>";
}

// Test 2: Check if post types are registered
if (post_type_exists('film')) {
    echo "<p style='color: green;'>✅ Film post type registered</p>";
} else {
    echo "<p style='color: red;'>❌ Film post type not registered</p>";
}

if (post_type_exists('news')) {
    echo "<p style='color: green;'>✅ News post type registered</p>";
} else {
    echo "<p style='color: red;'>❌ News post type not registered</p>";
}

if (post_type_exists('training_workshop')) {
    echo "<p style='color: green;'>✅ Training Workshop post type registered</p>";
} else {
    echo "<p style='color: red;'>❌ Training Workshop post type not registered</p>";
}

// Test 3: Check if custom functions exist
$functions_to_test = [
    'saab_breadcrumbs',
    'saab_display_related_content',
    'saab_film_stills_gallery',
    'saab_get_video_url',
    'saab_display_film_video'
];

foreach ($functions_to_test as $function) {
    if (function_exists($function)) {
        echo "<p style='color: green;'>✅ Function '$function' exists</p>";
    } else {
        echo "<p style='color: red;'>❌ Function '$function' missing</p>";
    }
}

// Test 4: Check theme directory
echo "<p>Theme directory: " . get_template_directory() . "</p>";
echo "<p>Theme URI: " . get_template_directory_uri() . "</p>";

// Test 5: Check if styles are enqueued
echo "<h2>Enqueued Styles:</h2>";
global $wp_styles;
if (isset($wp_styles->queue)) {
    foreach ($wp_styles->queue as $handle) {
        echo "<p>- $handle</p>";
    }
} else {
    echo "<p>No styles enqueued</p>";
}

// Test 6: Check if scripts are enqueued
echo "<h2>Enqueued Scripts:</h2>";
global $wp_scripts;
if (isset($wp_scripts->queue)) {
    foreach ($wp_scripts->queue as $handle) {
        echo "<p>- $handle</p>";
    }
} else {
    echo "<p>No scripts enqueued</p>";
}

echo "<h2>Theme Test Complete</h2>";
?> 