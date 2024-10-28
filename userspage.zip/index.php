<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Authentication System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-light: #e0e5ec;
            --text-light: #2d3436;
            --shadow-light: #a3b1c6;
            --highlight-light: #ffffdf;
            --primary-dark: #1a1a1a;
            --text-dark: #ffffff;
            --shadow-dark: #0000f0;
            --highlight-dark: #2d2d2d;
            --accent: #4a94e2;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            transition: all 0.05s ease;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Verdana, sans-serif;
            background: var(--primary-light);
            color: var(--text-light);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem;
        }

        body.dark-mode {
            background: var(--primary-dark);
            color: var(--text-dark);
        }

        .theme-toggle {
            position: fixed;
            top: 20px;
            right: 20px;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text-light);
            z-index: 1000;
        }

        .dark-mode .theme-toggle {
            color: var(--text-dark);
        }

        .container {
            width: 100%;
            max-width: 400px;
            padding: 2rem;
            border-radius: 20px;
            background: var(--primary-light);
            box-shadow: 8px 8px 16px var(--shadow-light),
                       -8px -8px 16px var(--highlight-light);
            position: relative;
            overflow: hidden;
        }

        .dark-mode .container {
            background: var(--primary-dark);
            box-shadow: 8px 8px 16px var(--shadow-dark),
                       -8px -8px 16px var(--highlight-dark);
        }

        h1 {
            text-align: center;
            margin-bottom: 2rem;
            color: var(--accent);
            font-size: 2rem;
        }

        .form-group {
            position: relative;
            margin-bottom: 1.5rem;
        }

        input {
            width: 100%;
            padding: 12px 20px;
            border: none;
            border-radius: 10px;
            background: var(--primary-light);
            box-shadow: inset 4px 4px 8px var(--shadow-light),
                       inset -4px -4px 8px var(--highlight-light);
            color: var(--text-light);
            font-size: 1rem;
            outline: none;
        }

        .dark-mode input {
            background: var(--primary-dark);
            box-shadow: inset 4px 4px 8px var(--shadow-dark),
                       inset -4px -4px 8px var(--highlight-dark);
            color: var(--text-dark);
        }

        label {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: #666;
            pointer-events: none;
            transition: 0.1s ease all;
        }

        input:focus + label,
        input:not(:placeholder-shown) + label {
            top: -10px;
            left: 10px;
            font-size: 0.8rem;
            color: var(--accent);
            background: var(--primary-light);
            padding: 0 5px;
        }

        .dark-mode input:focus + label,
        .dark-mode input:not(:placeholder-shown) + label {
            background: var(--primary-dark);
        }

        button {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            background: var(--accent);
            color: white;
            font-size: 1rem;
            cursor: pointer;
            box-shadow: 4px 4px 8px var(--shadow-light),
                       -4px -4px 8px var(--highlight-light);
            transition: all 0.4s ease;
        }

        .dark-mode button {
            box-shadow: 4px 4px 8px var(--shadow-dark),
                       -4px -4px 8px var(--highlight-dark);
        }

        button:hover {
            transform: translateY(-2px);
            background:linear-gradient(to right,#c9d6ff,#FFFFFF,#c9d6ff);
            box-shadow: 6px 6px 12px var(--shadow-light),
                       -6px -6px 12px var(--highlight-light);
            color: black;
        }

        .dark-mode button:hover {
            box-shadow: 6px 6px 12px var(--shadow-dark),
                       -6px -6px 12px var(--highlight-dark);
        }

        .switch-form {
            text-align: center;
            margin-top: 1rem;
        }

        .switch-form a {
            color: var(--accent);
            text-decoration: none;
            font-weight: 500;
        }

        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 2rem;
            border-radius: 10px;
            background: var(--accent);
            color: white;
            opacity: 0;
            transform: translateX(100%);
            transition: all 0.3s ease;
        }

        .toast.show {
            opacity: 1;
            transform: translateX(0);
        }

        .loading {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid var(--accent);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .profile-section {
            display: none;
        }

        .admin-section {
            display: none;
        }

        .user-list {
            margin-top: 2rem;
        }

        .user-item {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 10px;
            background: var(--primary-light);
            box-shadow: 4px 4px 8px var(--shadow-light),
                       -4px -4px 8px var(--highlight-light);
        }

        .dark-mode .user-item {
            background: var(--primary-dark);
            box-shadow: 4px 4px 8px var(--shadow-dark),
                       -4px -4px 8px var(--highlight-dark);
        }
    </style>
</head>
<body>
    <button class="theme-toggle">
        <i class="fas fa-moon"></i>
    </button>

    <div class="container">
        <!-- Login Form -->
        <div id="loginForm">
            <h1>Login</h1>
            <form onsubmit="return handleLogin(event)">
                <div class="form-group">
                    <input type="email" id="loginEmail" placeholder=" " required>
                    <label for="loginEmail">Email</label>
                </div>
                <div class="form-group">
                    <input type="password" id="loginPassword" placeholder=" " required>
                    <label for="loginPassword">Password</label>
                </div>
                <button type="submit">Login</button>
            </form>
            <div class="switch-form">
                <p>Don't have an account? <a href="#" onclick="toggleForms('register')">Register</a></p>
            </div>
        </div>

        <!-- Register Form -->
        <div id="registerForm" style="display: none;">
            <h1>Register</h1>
            <form onsubmit="return handleRegister(event)">
                <div class="form-group">
                    <input type="text" id="registerUsername" placeholder=" " required>
                    <label for="registerUsername">Username</label>
                </div>
                <div class="form-group">
                    <input type="email" id="registerEmail" placeholder=" " required>
                    <label for="registerEmail">Email</label>
                </div>
                <div class="form-group">
                    <input type="password" id="registerPassword" placeholder=" " required>
                    <label for="registerPassword">Password</label>
                </div>
                <button type="submit">Register</button>
            </form>
            <div class="switch-form">
                <p>Already have an account? <a href="#" onclick="toggleForms('login')">Login</a></p>
            </div>
        </div>

        <!-- Profile Section -->
        <div id="profileSection" class="profile-section">
            <h1>Profile</h1>
            <form onsubmit="return handleProfileUpdate(event)">
                <div class="form-group">
                    <input type="text" id="profileUsername" placeholder=" " required>
                    <label for="profileUsername">Username</label>
                </div>
                <div class="form-group">
                    <input type="email" id="profileEmail" placeholder=" " required>
                    <label for="profileEmail">Email</label>
                </div>
                <div class="form-group">
                    <input type="password" id="profilePassword" placeholder=" " required>
                    <label for="profilePassword">New Password</label>
                </div>
                <button type="submit">Update Profile</button>
                <button type="button" onclick="handleLogout()" style="margin-top: 1rem; background: #e74c3c;">Logout</button>
            </form>
        </div>

        <!-- Admin Section -->
        <div id="adminSection" class="admin-section">
            <h1>Admin Dashboard</h1>
            <div class="user-list" id="userList">
                <!-- User list will be populated here -->
            </div>
        </div>

        <div class="loading">
            <div class="spinner"></div>
        </div>
    </div>

    <div class="toast" id="toast"></div>

    <script>
        // Theme Toggle
        const themeToggle = document.querySelector('.theme-toggle');
        const body = document.body;
        let isDarkMode = false;

        themeToggle.addEventListener('click', () => {
            isDarkMode = !isDarkMode;
            body.classList.toggle('dark-mode');
            themeToggle.innerHTML = isDarkMode ? 
                '<i class="fas fa-sun"></i>' : 
                '<i class="fas fa-moon"></i>';
        });

        // Form Toggle
        function toggleForms(form) {
            const loginForm = document.getElementById('loginForm');
            const registerForm = document.getElementById('registerForm');
            const profileSection = document.getElementById('profileSection');
            const adminSection = document.getElementById('adminSection');

            loginForm.style.display = 'none';
            registerForm.style.display = 'none';
            profileSection.style.display = 'none';
            adminSection.style.display = 'none';

            if (form === 'login') {
                loginForm.style.display = 'block';
            } else if (form === 'register') {
                registerForm.style.display = 'block';
            } else if (form === 'profile') {
                profileSection.style.display = 'block';
            } else if (form === 'admin') {
                adminSection.style.display = 'block';
            }
        }

        // Toast Notification
        function showToast(message, isError = false) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.style.background = isError ? '#e74c3c' : '#2ecc71';
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 3000);
        }

        // Loading Spinner
        function toggleLoading(show) {
            const loading = document.querySelector('.loading');
            loading.style.display = show ? 'flex' : 'none';
        }

        // Handle Login
        async function handleLogin(event) {
            event.preventDefault();
            
            const email = document.getElementById('loginEmail').value;
            const password = document.getElementById('loginPassword').value;

            toggleLoading(true);

            try {
                const response = await fetch('login.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ email, password }),
                });

                const data = await response.json();

                if (data.success) {
                    showToast('Login successful!');
                    if (email === 'admin@gmail.com') {
                        toggleForms('admin');
                        loadUsers();
                    } else {
                        toggleForms('profile');
                        loadProfile();
                    }
                } else {
                    showToast(data.message, true);
                }
            } catch (error) {
                showToast('An error occurred', true);
            } finally {
                toggleLoading(false);
            }
        }

        // Handle Register
        async function handleRegister(event) {
            event.preventDefault();
            
            const username = document.getElementById('registerUsername').value;
            const email = document.getElementById('registerEmail').value;
            const password = document.getElementById('registerPassword').value;

            toggleLoading(true);

            try {
                const response = await fetch('register.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ username, email, password }),
                });

                const data = await response.json();

                if (data.success) {
                    showToast('Registration successful!');
                    toggleForms('login');
                } else {
                    showToast(data.message, true);
                }
            } catch (error) {
                showToast('An error occurred', true);
            } finally {
                toggleLoading(false);
            }
        }

        // Handle Profile Update
        async function handleProfileUpdate(event) {
            event.preventDefault();
            
            const username = document.getElementById('profileUsername').value;
            const email = document.getElementById('profileEmail').value;
            const password = document.getElementById('profilePassword').value;

            toggleLoading(true);

            try {
                const response = await fetch('update_profile.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ username, email, password }),
                });

                const data = await response.json();

                if (data.success) {
                    showToast('Profile updated successfully!');
                } else {
                    showToast(data.message, true);
                }
            } catch (error) {
                showToast('An error occurred', true);
            } finally {
                toggleLoading(false);
            }
        }

        // Load Profile
        async function loadProfile() {
            toggleLoading(true);

            try {
                const response = await fetch('get_profile.php');
                const data = await response.json();

                if (data.success) {
                    document.getElementById('profileUsername').value = data.user.username;
                    document.getElementById('profileEmail').value = data.user.email;
                } else {
                    showToast(data.message, true);
                }
            } catch (error) {
                showToast('An error occurred', true);
            } finally {
                toggleLoading(false);
            }
        }

        // Load Users (Admin)
        async function loadUsers() {
            toggleLoading(true);

            try {
                const response = await fetch('get_users.php');
                const data = await response.json();

                if (data.success) {
                    const userList = document.getElementById('userList');
                    userList.innerHTML = '';

                    data.users.forEach(user => {
                        const userItem = document.createElement('div');
                        userItem.className = 'user-item';
                        userItem.innerHTML = `
                            <p><strong>Username:</strong> ${user.username}</p>
                            <p><strong>Email:</strong> ${user.email}</p>
                            <button onclick="deleteUser(${user.id})" 
                                    style="background: #e74c3c; margin-top: 0.5rem;">
                                Delete User
                            </button>
                        `;
                        userList.appendChild(userItem);
                    });
                } else {
                    showToast(data.message, true);
                }
            } catch (error) {
                showToast('An error occurred', true);
            } finally {
                toggleLoading(false);
            }
        }

        // Delete User (Admin)
        async function deleteUser(userId) {
            if (!confirm('Are you sure you want to delete this user?')) {
                return;
            }

            toggleLoading(true);

            try {
                const response = await fetch('delete_user.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ userId }),
                });

                const data = await response.json();

                if (data.success) {
                    showToast('User deleted successfully!');
                    loadUsers();
                } else {
                    showToast(data.message, true);
                }
            } catch (error) {
                showToast('An error occurred', true);
            } finally {
                toggleLoading(false);
            }
        }

        // Handle Logout
        function handleLogout() {
            fetch('logout.php')
                .then(() => {
                    showToast('Logged out successfully!');
                    toggleForms('login');
                })
                .catch(() => {
                    showToast('An error occurred', true);
                });
        }
    </script>
</body>
</html>