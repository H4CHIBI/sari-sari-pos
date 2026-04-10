<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../../config/config.php';

// Access Control: Ensure only logged-in Cashiers can access this
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'CASHIER') {
    header("Location: ../../auth/login.php");
    exit();
}

/**
 * REDIRECT GUARD: 
 * If a shift is already active, we send them to the POS.
 * This prevents the user from starting a second shift while one is open.
 */
if (isset($_SESSION['active_shift_id'])) {
    header("Location: pos_dashboard.php");
    exit();
}

include '../includes/header.php'; 
?>

<div class="flex flex-1 overflow-hidden">
    <?php include '../includes/sidebar.php'; ?>

    <main class="flex-1 p-4 md:p-8 bg-emerald-50 overflow-y-auto">
        <div class="max-w-4xl mx-auto">
            
            <div class="mb-8">
                <h1 class="text-3xl font-black text-emerald-900 tracking-tight uppercase">Shift Initialization</h1>
                <p class="text-emerald-600 font-medium italic">"Ensure your drawer is balanced before you start selling."</p>
            </div>

            <div class="bg-white rounded-3xl shadow-xl border border-emerald-100 overflow-hidden mb-10">
                <div class="bg-emerald-600 px-8 py-6 text-white flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold uppercase tracking-tight">Beginning Cash Count</h2>
                        <p class="text-xs text-emerald-100 font-bold opacity-80 uppercase tracking-widest">Philippine Peso Denominations</p>
                    </div>
                    <div class="p-3 bg-emerald-500 rounded-2xl shadow-inner">
                        <i data-lucide="calculator" class="w-8 h-8 text-emerald-950"></i>
                    </div>
                </div>

                <form action="modules/process_shift.php" method="POST" class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                        
                        <div class="space-y-4">
                            <h3 class="text-xs font-black text-emerald-800 uppercase tracking-[0.2em] border-b border-emerald-100 pb-3 mb-6">Paper Bills</h3>
                            <?php 
                            $bills = [1000, 500, 200, 100, 50, 20];
                            foreach($bills as $val): ?>
                            <div class="flex items-center gap-4 group">
                                <label class="w-24 font-black text-gray-500 text-sm">₱<?php echo $val; ?></label>
                                <input type="number" name="denom[<?php echo $val; ?>]" 
                                    class="denom-input w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-4 py-3 text-right font-bold text-emerald-900 focus:outline-none focus:border-emerald-500 focus:bg-white transition-all" 
                                    value="0" min="0" data-value="<?php echo $val; ?>">
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="space-y-4">
                            <h3 class="text-xs font-black text-emerald-800 uppercase tracking-[0.2em] border-b border-emerald-100 pb-3 mb-6">Coins</h3>
                            <?php 
                            $coins = [20, 10, 5, 1];
                            foreach($coins as $val): ?>
                            <div class="flex items-center gap-4 group">
                                <label class="w-24 font-black text-gray-500 text-sm">₱<?php echo $val; ?></label>
                                <input type="number" name="denom[<?php echo $val; ?>]" 
                                    class="denom-input w-full bg-gray-50 border-2 border-gray-100 rounded-xl px-4 py-3 text-right font-bold text-emerald-900 focus:outline-none focus:border-emerald-500 focus:bg-white transition-all" 
                                    value="0" min="0" data-value="<?php echo $val; ?>">
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="mt-12 bg-emerald-950 rounded-2xl p-8 text-white flex flex-col md:flex-row items-center justify-between shadow-2xl relative overflow-hidden">
                        <i data-lucide="banknote" class="absolute -left-4 -bottom-4 w-32 h-32 text-emerald-900 opacity-20 -rotate-12"></i>
                        
                        <div class="mb-6 md:mb-0 relative z-10">
                            <span class="text-emerald-400 text-[10px] uppercase font-black tracking-[0.3em]">Calculated Starting Cash</span>
                            <div class="text-5xl font-black tracking-tighter">₱ <span id="grand-total">0.00</span></div>
                        </div>
                        
                        <input type="hidden" name="total_cash" id="total_cash_val" value="0">
                        
                        <button type="submit" class="relative z-10 w-full md:w-auto bg-emerald-500 hover:bg-emerald-400 text-emerald-950 font-black px-10 py-5 rounded-xl shadow-xl transition-all transform active:scale-95 flex items-center justify-center gap-3 group">
                            CONFIRM & START SHIFT 
                            <i data-lucide="play" class="w-5 h-5 fill-current group-hover:translate-x-1 transition-transform"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<script>
    const inputs = document.querySelectorAll('.denom-input');
    const totalDisplay = document.getElementById('grand-total');
    const totalHidden = document.getElementById('total_cash_val');

    function calculateTotal() {
        let total = 0;
        inputs.forEach(input => {
            const val = parseFloat(input.getAttribute('data-value'));
            const qty = parseInt(input.value) || 0;
            total += val * qty;
        });
        totalDisplay.innerText = total.toLocaleString('en-PH', {minimumFractionDigits: 2});
        totalHidden.value = total;
    }

    inputs.forEach(input => {
        input.addEventListener('input', calculateTotal);
        
        // Enhance Workflow: Auto-clear zero for faster input
        input.addEventListener('focus', function() { 
            if(this.value === "0") this.value = ""; 
        });
        
        input.addEventListener('blur', function() { 
            if(this.value === "") this.value = "0"; 
        });
    });
</script>

<?php include '../includes/footer.php'; ?>