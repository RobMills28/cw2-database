<?php
// Ensure this is included at the top of all PHP files that need session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Police Traffic Database'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            prefix: 'tw-',
            theme: {
                extend: {
                    colors: {
                        blue: {
                            50: '#eff6ff',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8'
                        },
                        gray: {
                            50: '#f9fafb',
                            100: '#f3f4f6',
                            200: '#e5e7eb',
                            600: '#4b5563',
                            700: '#374151'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        /* Common table styles */
        .data-table {
            @apply tw-w-full tw-bg-white tw-shadow-sm tw-rounded-lg tw-overflow-hidden;
        }
        .data-table thead {
            @apply tw-bg-gray-50 tw-border-b tw-border-gray-200;
        }
        .data-table th {
            @apply tw-px-6 tw-py-3 tw-text-left tw-text-xs tw-font-medium tw-text-gray-500 tw-uppercase tw-tracking-wider;
        }
        .data-table td {
            @apply tw-px-6 tw-py-4 tw-whitespace-nowrap tw-text-sm tw-text-gray-900 tw-border-b tw-border-gray-200;
        }
        .data-table tr:hover {
            @apply tw-bg-gray-50;
        }
        
        /* Common form styles */
        .form-input {
            @apply tw-w-full tw-px-3 tw-py-2 tw-border tw-border-gray-300 tw-rounded-md tw-shadow-sm focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-blue-500 focus:tw-border-blue-500;
        }
        
        /* Common button styles */
        .btn {
            @apply tw-px-4 tw-py-2 tw-rounded-md tw-text-white tw-bg-blue-600 hover:tw-bg-blue-700 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-blue-500 focus:tw-ring-offset-2;
        }
        
        /* Common card styles */
        .card {
            @apply tw-bg-white tw-rounded-lg tw-shadow-sm tw-p-6;
        }
    </style>
</head>
<body class="tw-bg-gray-50 tw-min-h-screen">