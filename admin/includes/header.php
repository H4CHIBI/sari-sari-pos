<?php
/**
 * header.php
 * Note: session_start() and security checks should be in the parent file
 * to prevent "headers already sent" errors.
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sari-Sari POS | Terminal</title>
    <link rel="stylesheet" href="../../public/css/output.css">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        /* Custom scrollbar to match your emerald theme */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-track { background: #ecfdf5; }
        ::-webkit-scrollbar-thumb { background: #10b981; border-radius: 10px; }
        
        /* Smooth transition for the dropdown */
        .dropdown-animate {
            transform-origin: top right;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
    </style>
</head>
<body class="bg-emerald-50 min-h-screen flex flex-col font-sans antialiased">

<nav class="bg-emerald-600 text-white shadow-lg sticky top-0 z-[100] border-b border-emerald-500">
    <div class="px-4 py-2.5">
        <div class="flex items-center justify-between">
            
            <div class="flex items-center gap-4">
                <button id="mobile-sidebar-toggle" class="lg:hidden p-2 hover:bg-emerald-700 rounded-xl transition">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
                <div class="flex items-center gap-2 select-none">
                    <div class="bg-white p-1.5 rounded-xl shadow-inner hidden sm:block">
                        <i data-lucide="store" class="w-5 h-5 text-emerald-600"></i>
                    </div>
                    <span class="text-xl font-black tracking-tighter uppercase italic">Sari<span class="text-emerald-200">POS</span></span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                
                <?php if (isset($_SESSION['active_shift_id'])): ?>
                <div class="hidden md:flex items-center gap-2 bg-emerald-900/40 px-3 py-1 rounded-full border border-emerald-400/30">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-400"></span>
                    </span>
                    <span class="text-[9px] font-black uppercase tracking-widest text-emerald-100">Live Shift</span>
                </div>
                <?php endif; ?>

                <div class="relative">
                    <button id="profile-btn" class="flex items-center gap-3 p-1 pr-3 hover:bg-emerald-700 rounded-full transition-all active:scale-95 focus:outline-none">
                        <?php 
                            $profileImg = !empty($_SESSION['profile_image']) ? $_SESSION['profile_image'] : 'default_user.png';
                        ?>
                        <img src="../../public/uploads/profiles/<?php echo $profileImg; ?>" 
                             class="w-9 h-9 rounded-full border-2 border-emerald-400 object-cover shadow-sm">
                        <div class="hidden sm:block text-left">
                            <p class="text-[10px] font-black uppercase text-emerald-200 leading-none mb-0.5"><?php echo $_SESSION['role']; ?></p>
                            <p class="text-xs font-bold leading-none"><?php echo $_SESSION['username']; ?></p>
                        </div>
                        <i data-lucide="chevron-down" class="w-4 h-4 text-emerald-300"></i>
                    </button>

                    <div id="profile-dropdown" class="hidden absolute right-0 mt-3 w-60 bg-white rounded-2xl shadow-2xl border border-emerald-100 overflow-hidden dropdown-animate z-[110]">
                        <div class="p-4 bg-emerald-50 border-b border-emerald-100">
                            <p class="text-[10px] text-emerald-600 font-black uppercase tracking-[0.2em] mb-1">Authenticated As</p>
                            <p class="text-sm font-black text-gray-800"><?php echo $_SESSION['username']; ?></p>
                            <p class="text-[10px] text-gray-400 font-medium"><?php echo $_SESSION['user_id']; ?> • Narcy Store Terminal</p>
                        </div>
                        
                        <div class="p-1.5">
                            <a href="profile.php" class="flex items-center gap-3 px-3 py-2.5 text-sm text-gray-600 hover:bg-emerald-50 hover:text-emerald-700 rounded-xl transition group">
                                <i data-lucide="settings" class="w-4 h-4 group-hover:rotate-45 transition-transform"></i> 
                                <span class="font-bold">Account Settings</span>
                            </a>
                            <a href="cashier_shift_end.php" class="flex items-center gap-3 px-3 py-2.5 text-sm text-red-500 hover:bg-red-50 rounded-xl transition group">
                                <i data-lucide="power" class="w-4 h-4"></i> 
                                <span class="font-black">End Session</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
