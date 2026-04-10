<aside id="main-sidebar" class="fixed inset-y-0 left-0 z-50 w-64 bg-emerald-950 text-white transform -translate-x-full lg:translate-x-0 lg:static lg:inset-0 transition-transform duration-300 ease-in-out shadow-2xl flex flex-col border-r border-emerald-800">
    
    <div class="p-6 border-b border-emerald-800/50 flex items-center justify-between bg-emerald-900/20">
        <div class="flex items-center gap-2">
            <div class="bg-emerald-500 p-1.5 rounded-lg">
                <i data-lucide="layout-grid" class="w-5 h-5 text-emerald-950"></i>
            </div>
            <span class="font-black tracking-widest uppercase text-sm">Navigation</span>
        </div>
        <button id="mobile-close" class="lg:hidden p-1 hover:bg-emerald-800 rounded transition">
            <i data-lucide="x" class="w-5 h-5"></i>
        </button>
    </div>

    <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto">
        
        <?php if ($_SESSION['role'] === 'ADMIN'): ?>
            <div class="pb-2 px-3 text-[10px] font-bold text-emerald-500 uppercase tracking-[0.2em]">Administration</div>
            
            <a href="dashboard.php" class="flex items-center gap-3 p-3 rounded-xl hover:bg-emerald-800/50 transition-all group <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'bg-emerald-800 text-white' : 'text-emerald-100/70'; ?>">
                <i data-lucide="home" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                <span class="font-medium">Admin Dashboard</span>
            </a>

            <a href="inventory.php" class="flex items-center gap-3 p-3 rounded-xl hover:bg-emerald-800/50 transition-all group <?php echo basename($_SERVER['PHP_SELF']) == 'inventory.php' ? 'bg-emerald-800 text-white' : 'text-emerald-100/70'; ?>">
                <i data-lucide="package" class="w-5 h-5 group-hover:rotate-6 transition-transform"></i>
                <span>Inventory</span>
            </a>
            
            <a href="users.php" class="flex items-center gap-3 p-3 rounded-xl hover:bg-emerald-800/50 transition-all group <?php echo basename($_SERVER['PHP_SELF']) == 'users.php' ? 'bg-emerald-800 text-white' : 'text-emerald-100/70'; ?>">
                <i data-lucide="users" class="w-5 h-5"></i>
                <span>User Accounts</span>
            </a>
        <?php endif; ?>

        <?php if ($_SESSION['role'] === 'CASHIER'): ?>
            <div class="pt-6 pb-2 px-3 text-[10px] font-bold text-emerald-500 uppercase tracking-[0.2em]">Store Front</div>
            
            <a href="pos_dashboard.php" class="flex items-center gap-3 p-3 rounded-xl hover:bg-emerald-800/50 transition-all group <?php echo basename($_SERVER['PHP_SELF']) == 'pos_dashboard.php' ? 'bg-emerald-800 text-white' : 'text-emerald-100/70'; ?>">
                <i data-lucide="shopping-cart" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                <span>Point of Sale</span>
            </a>
            
            <?php if (!isset($_SESSION['active_shift_id'])): ?>
            <a href="cashier_shift_start.php" class="flex items-center gap-3 p-3 rounded-xl hover:bg-emerald-800/50 transition-all group <?php echo basename($_SERVER['PHP_SELF']) == 'cashier_shift_start.php' ? 'bg-emerald-800 text-white' : 'text-emerald-100/70'; ?>">
                <i data-lucide="play-circle" class="w-5 h-5"></i>
                <span>Start Shift</span>
            </a>
            <?php endif; ?>
        <?php endif; ?>

    </nav>

    <div class="p-4 border-t border-emerald-800/50 bg-emerald-900/10">
        <?php if (isset($_SESSION['active_shift_id'])): ?>
            <a href="cashier_shift_end.php" class="relative flex items-center gap-3 p-3 rounded-xl bg-red-600 hover:bg-red-500 text-white transition-all shadow-lg shadow-red-900/20 group overflow-hidden">
                <div class="absolute inset-0 bg-white/10 translate-y-full group-hover:translate-y-0 transition-transform"></div>
                <i data-lucide="power" class="w-5 h-5 relative z-10 animate-pulse"></i>
                <span class="font-bold relative z-10">End Current Shift</span>
            </a>
        <?php else: ?>
            <a href="../../auth/logout.php" class="flex items-center gap-3 p-3 rounded-xl hover:bg-emerald-800 transition-all text-emerald-400 group">
                <i data-lucide="log-out" class="w-5 h-5 group-hover:-translate-x-1 transition-transform"></i>
                <span class="font-medium">Sign Out</span>
            </a>
        <?php endif; ?>
    </div>
</aside>