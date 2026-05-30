<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>SAW Kids - Manajemen Siswa PAUD</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700;900&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "tertiary-fixed-dim": "#80d0f0",
                        "error": "#e53e3e",
                        "secondary-fixed-dim": "#c8a8e8",
                        "inverse-surface": "#2e1a28",
                        "primary-fixed-dim": "#f0a0cc",
                        "tertiary": "#0096cc",
                        "surface-container-high": "#f2e8f2",
                        "outline-variant": "#dcc8e0",
                        "outline": "#907898",
                        "surface-container-highest": "#ece2ec",
                        "secondary-container": "#eedcff",
                        "primary": "#e040a0",
                        "on-tertiary": "#ffffff",
                        "on-error-container": "#9b1c1c",
                        "background": "#fef7ff",
                        "on-secondary-container": "#2e2040",
                        "secondary": "#7c52aa",
                        "primary-fixed": "#ffd6ee",
                        "on-secondary": "#ffffff",
                        "secondary-fixed": "#eedcff",
                        "primary-container": "#f080c0",
                        "tertiary-container": "#40c0ee",
                        "on-surface-variant": "#604868",
                        "surface-container-lowest": "#ffffff",
                        "surface": "#fef7ff",
                        "on-tertiary-fixed": "#001a33",
                        "surface-dim": "#e0d6e0",
                        "on-tertiary-fixed-variant": "#005580",
                        "error-container": "#ffe8e8",
                        "on-tertiary-container": "#00334d",
                        "on-background": "#2e1a28",
                        "on-secondary-fixed": "#1a1030",
                        "on-primary": "#ffffff",
                        "inverse-on-surface": "#fef7ff",
                        "on-primary-fixed": "#3d0028",
                        "tertiary-fixed": "#c8eaff",
                        "surface-container": "#f8eef8",
                        "surface-bright": "#fef7ff",
                        "on-primary-container": "#2e1a28",
                        "inverse-primary": "#f0a0cc",
                        "on-primary-fixed-variant": "#a02070",
                        "on-error": "#ffffff",
                        "on-secondary-fixed-variant": "#4a3068",
                        "surface-container-low": "#fbf2fb",
                        "surface-tint": "#e040a0",
                        "on-surface": "#2e1a28",
                        "surface-variant": "#f2e8f2"
                    },
                    "borderRadius": {
                        "DEFAULT": "1rem",
                        "lg": "2rem",
                        "xl": "3rem",
                        "full": "9999px"
                    },
                    "fontFamily": {
                        "headline": ["DM Sans", "sans-serif"],
                        "display": ["DM Sans", "sans-serif"],
                        "body": ["DM Sans", "sans-serif"],
                        "label": ["DM Sans", "sans-serif"]
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'DM Sans', sans-serif;
        }

        .bouncy-spring {
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
    </style>
</head>

<body class="bg-background text-on-background min-h-screen flex flex-col md:flex-row font-body">
    <?php $this->load->view('components/sidebar'); ?>
    <main class="flex-1 p-6 md:p-10 lg:p-14 overflow-y-auto w-full relative">
        