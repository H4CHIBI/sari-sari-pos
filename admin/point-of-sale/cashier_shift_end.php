<?php
session_start();
require_once '../../config/config.php';

if (!isset($_SESSION['active_shift_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Calculate total sales made during this shift
$shift_id = $_SESSION['active_shift_id'];
$stmt = $pdo->prepare("SELECT SUM(total_amount) as total_sales FROM sales WHERE shift_id = ?");
$stmt->execute([$shift_id]);
$sales_data = $stmt->fetch();
$total_sales = $sales_data['total_sales'] ?? 0;

// Get starting cash
$stmt = $pdo->prepare("SELECT starting_cash_total FROM shifts WHERE id = ?");
$stmt->execute([$shift_id]);
$shift_info = $stmt->fetch();
$starting_cash = $shift_info['starting_cash_total'];

$expected_total = $starting_cash + $total_sales;

include '../includes/header.php'; 
?>

<div class="flex">
    <?php include '../includes/sidebar.php'; ?>

    <main class="flex-1 p-4 md:p-8 bg-emerald-50">
        <div class="max-w-4xl mx-auto">
            <div class="mb-6 flex justify-between items-end">
                <div>
                    <h1 class="text-2xl font-bold text-emerald-900">End Shift & Remittance</h1>
                    <p class="text-emerald-600 italic">"Finalize your workflow: Verify today's earnings."</p>
                </div>
                <div class="text-right">
                    <span class="text-xs font-bold text-gray-500 uppercase">System Expected Cash</span>
                    <div class="text-xl font-bold text-emerald-700">₱<?php echo number_format($expected_total, 2); ?></div>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-lg border border-red-100 overflow-hidden">
                <div class="bg-red-600 px-6 py-4 text-white flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-white">Closing Cash Count</h2>
                    <i data-lucide="lock" class="w-8 h-8 opacity-50"></i>
                </div>

                <form action="modules/process_end_shift.php" method="POST" class="p-6">
                    <input type="hidden" name="expected_total" value="<?php echo $expected_total; ?>">
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-3">
                            <h3 class="text-sm font-bold text-red-700 uppercase border-b border-red-100 pb-2 mb-4">Paper Bills</h3>
                            <?php foreach([1000, 500, 200, 100, 50, 20] as $val): ?>
                            <div class="flex items-center gap-4 bg-gray-50 p-2 rounded-lg">
                                <label class="w-20 font-bold text-gray-600 text-sm">₱<?php echo $val; ?></label>
                                <input type="number" name="denom[<?php echo $val; ?>]" class="denom-input w-full bg-white border border-gray-200 rounded px-3 py-2 text-right focus:ring-2 focus:ring-red-500 outline-none" value="0" min="0" data-value="<?php echo $val; ?>">
                            </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="space-y-3">
                            <h3 class="text-sm font-bold text-red-700 uppercase border-b border-red-100 pb-2 mb-4">Coins</h3>
                            <?php foreach([20, 10, 5, 1] as $val): ?>
                            <div class="flex items-center gap-4 bg-gray-50 p-2 rounded-lg">
                                <label class="w-20 font-bold text-gray-600 text-sm">₱<?php echo $val; ?></label>
                                <input type="number" name="denom[<?php echo $val; ?>]" class="denom-input w-full bg-white border border-gray-200 rounded px-3 py-2 text-right focus:ring-2 focus:ring-red-500 outline-none" value="0" min="0" data-value="<?php echo $val; ?>">
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="mt-10 bg-gray-900 rounded-xl p-6 text-white flex flex-col md:flex-row items-center justify-between">
                        <div>
                            <span class="text-gray-400 text-sm uppercase font-bold tracking-widest">Actual Cash in Drawer</span>
                            <div class="text-4xl font-black">₱ <span id="grand-total">0.00</span></div>
                        </div>
                        
                        <input type="hidden" name="actual_total" id="total_cash_val" value="0">
                        
                        <button type="submit" onclick="return confirm('Are you sure you want to end your shift? This cannot be undone.')" 
                            class="w-full md:w-auto bg-red-600 hover:bg-red-500 text-white font-black px-10 py-4 rounded-lg shadow-lg transition-all flex items-center gap-2">
                            CLOSE SHIFT & LOGOUT
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
            total += (parseFloat(input.getAttribute('data-value')) * (parseInt(input.value) || 0));
        });
        totalDisplay.innerText = total.toLocaleString('en-PH', {minimumFractionDigits: 2});
        totalHidden.value = total;
    }

    inputs.forEach(input => {
        input.addEventListener('input', calculateTotal);
        input.addEventListener('focus', function() { if(this.value === "0") this.value = ""; });
        input.addEventListener('blur', function() { if(this.value === "") this.value = "0"; });
    });
</script>

<?php include '../includes/footer.php'; ?>