<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Clear App Cache</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Optional: Add Tailwind or Bootstrap -->
    <style>
        button {
            padding: 10px 20px;
            background: red;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            margin-top: 30px;
        }
    </style>
</head>
<body style="text-align:center; padding-top: 50px;">

    <h1>Clear PWA Cache</h1>
    <button id="clear-cache-btn">Clear App Cache</button>

    <script>
    document.getElementById('clear-cache-btn').addEventListener('click', async () => {
        if ('caches' in window) {
            const cacheNames = await caches.keys();
            await Promise.all(cacheNames.map(name => caches.delete(name)));
            //alert('App cache cleared successfully!');
        }

        if ('serviceWorker' in navigator) {
            const registrations = await navigator.serviceWorker.getRegistrations();
            for (let registration of registrations) {
                await registration.unregister();
            }
            //alert('Service worker unregistered!');

        }

        window.location.reload();
window.location.href = "/";
    });
    </script>
</body>
</html>
