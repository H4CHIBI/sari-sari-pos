<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sari-Store POS Login</title>
    <link rel="stylesheet" href="public/css/output.css">
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-emerald-50 flex items-center justify-center min-h-screen">

    <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md border-t-8 border-emerald-600">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-emerald-800">Sari-Sari POS</h1>
            <p class="text-emerald-600">Inventory & Sales System</p>
        </div>

        <?php if(isset($_GET['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4 text-sm">
                Invalid username or password.
            </div>
        <?php endif; ?>

        <form action="auth/auth.php" method="POST" class="space-y-6">
            <div>
                <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                <input for="username" type="text" name="username" id="username" 
                    value="Enter Username" 
                    onclick="clearInput(this, 'Enter Username')"
                    required 
                    class="mt-1 block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 transition outline-none">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <div class="relative mt-1">
                    <input type="password" name="password" id="password" 
                        value="Password123" 
                        onclick="clearInput(this, 'Password123')"
                        required 
                        class="block w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-emerald-500 focus:border-emerald-500 transition outline-none">
                    
                    <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-emerald-600">
                        <i id="eye-icon" data-lucide="eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" 
                class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-3 rounded-lg shadow-lg hover:shadow-emerald-200 transition-all transform active:scale-95">
                Login to System
            </button>
        </form>

        <div class="mt-6 text-center">
            <p class="text-xs text-gray-400">© 2026 Sari-Sari POS System</p>
        </div>
    </div>

    <script>
        // Initialize Lucide Icons
        lucide.createIcons();

        // Function to clear input only if it matches the default value
        function clearInput(element, defaultValue) {
            if (element.value === defaultValue) {
                element.value = '';
            }
        }

        // Toggle Password Visibility
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const icon = document.getElementById('eye-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                // Update icon to eye-off
                icon.setAttribute('data-lucide', 'eye-off');
            } else {
                passwordInput.type = 'password';
                // Update icon back to eye
                icon.setAttribute('data-lucide', 'eye');
            }
            // Re-render the icon
            lucide.createIcons();
        }
    </script>
</body>
</html>