<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Database Connection
require_once '../../config/config.php'; 

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'CASHIER') {
    header("Location: ../../auth/login.php");
    exit();
}

if (!isset($_SESSION['active_shift_id'])) {
    header("Location: cashier_shift_start.php");
    exit();
}

// 4. Include the UI components
include '../includes/header.php'; 
?>

<div class="flex flex-1 overflow-hidden" style="height: calc(100vh - 64px);">
    <?php include '../includes/sidebar.php'; ?>

    <main class="flex-1 flex flex-col md:flex-row bg-gray-100 overflow-hidden">
        
        <section class="flex-1 flex flex-col p-4 overflow-hidden">
            <div class="mb-4 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                <div>
                    <h2 class="text-xl font-black text-emerald-900 uppercase tracking-tight">Store Products</h2>
                    <p class="text-[10px] text-emerald-600 font-bold uppercase tracking-widest">Select items to sell</p>
                </div>
                
                <div class="relative w-full lg:w-72">
                    <input type="text" id="product-search" onkeyup="filterProducts()" placeholder="Search products..." 
                        class="w-full pl-10 pr-4 py-2 rounded-xl border border-emerald-100 shadow-sm focus:ring-2 focus:ring-emerald-500 outline-none text-sm">
                    <i data-lucide="search" class="absolute left-3 top-2.5 w-4 h-4 text-emerald-400"></i>
                </div>
            </div>

            <div id="product-grid" class="flex-1 overflow-y-auto grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 pb-6">
                <?php
                // Fetch products available in stock
                $stmt = $pdo->query("SELECT * FROM products WHERE stock_quantity > 0 ORDER BY product_name ASC");
                while ($product = $stmt->fetch()):
                    // Check if image exists, otherwise use default
                    $img = !empty($product['product_image']) ? $product['product_image'] : 'default_product.png';
                ?>
                <button onclick="addToCart(<?php echo htmlspecialchars(json_encode($product)); ?>)" 
                    class="product-card bg-white rounded-xl shadow-sm border border-transparent hover:border-emerald-500 hover:shadow-md transition-all text-left flex flex-col overflow-hidden group active:scale-95"
                    data-name="<?php echo strtolower($product['product_name']); ?>">
                    
                    <div class="h-24 w-full bg-gray-50 relative overflow-hidden">
                        <img src="../../public/uploads/products/<?php echo $img; ?>" 
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                             onerror="this.src='../../public/uploads/products/default_product.png'">
                        <div class="absolute inset-0 bg-black/5 group-hover:bg-transparent transition-colors"></div>
                    </div>

                    <div class="p-3 flex-1 flex flex-col justify-between">
                        <h3 class="text-[11px] font-bold text-gray-700 line-clamp-2 leading-tight mb-2">
                            <?php echo $product['product_name']; ?>
                        </h3>
                        <div class="flex justify-between items-center">
                            <span class="text-sm font-black text-emerald-700">₱<?php echo number_format($product['selling_price'], 2); ?></span>
                            <span class="text-[9px] font-bold text-gray-400">Qty: <?php echo $product['stock_quantity']; ?></span>
                        </div>
                    </div>
                </button>
                <?php endwhile; ?>
            </div>
        </section>

        <section class="w-full md:w-80 lg:w-96 bg-white border-l border-emerald-100 flex flex-col shadow-2xl">
            <div class="p-4 bg-emerald-600 text-white flex justify-between items-center">
                <span class="font-black uppercase tracking-widest text-xs">Current Order</span>
                <button onclick="clearCart()" class="text-emerald-200 hover:text-white transition"><i data-lucide="refresh-ccw" class="w-4 h-4"></i></button>
            </div>

            <div id="cart-items" class="flex-1 overflow-y-auto p-4 space-y-2">
                <div class="h-full flex flex-col items-center justify-center text-gray-300 opacity-50">
                    <i data-lucide="shopping-bag" class="w-12 h-12 mb-2"></i>
                    <p class="text-xs font-bold uppercase">Empty Cart</p>
                </div>
            </div>

            <div class="p-6 bg-emerald-50 border-t border-emerald-100">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-[10px] font-bold text-emerald-800 uppercase tracking-widest">Total to Pay</span>
                    <span id="grand-total" class="text-3xl font-black text-emerald-600 tracking-tighter">₱0.00</span>
                </div>
                
                <button id="checkout-btn" disabled 
                    class="w-full py-4 bg-emerald-600 hover:bg-emerald-700 disabled:bg-gray-300 text-white rounded-2xl font-black shadow-lg shadow-emerald-900/10 transition-all flex items-center justify-center gap-2">
                    PROCESS PAYMENT <i data-lucide="arrow-right" class="w-5 h-5"></i>
                </button>
            </div>
        </section>
    </main>
</div>

<script>
let cart = [];

function addToCart(product) {
    const existing = cart.find(item => item.id === product.id);
    if (existing) {
        if (existing.qty < product.stock_quantity) {
            existing.qty++;
        } else {
            alert("Insufficient stock!");
        }
    } else {
        cart.push({ id: product.id, name: product.product_name, price: parseFloat(product.selling_price), qty: 1 });
    }
    renderCart();
}

function renderCart() {
    const container = document.getElementById('cart-items');
    const totalEl = document.getElementById('grand-total');
    const btn = document.getElementById('checkout-btn');

    if (cart.length === 0) {
        container.innerHTML = `<div class="h-full flex flex-col items-center justify-center text-gray-300 opacity-50"><i data-lucide="shopping-bag" class="w-12 h-12 mb-2"></i><p class="text-xs font-bold uppercase">Empty Cart</p></div>`;
        totalEl.innerText = "₱0.00";
        btn.disabled = true;
    } else {
        btn.disabled = false;
        container.innerHTML = '';
        let total = 0;
        cart.forEach((item, idx) => {
            const sub = item.price * item.qty;
            total += sub;
            container.innerHTML += `
                <div class="flex justify-between items-center bg-white p-3 rounded-xl border border-emerald-50 shadow-sm animate-in fade-in slide-in-from-bottom-2">
                    <div class="flex-1">
                        <p class="text-[11px] font-black text-gray-800 leading-tight">${item.name}</p>
                        <p class="text-[10px] text-emerald-600 font-bold">₱${item.price.toFixed(2)} x ${item.qty}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-black text-emerald-900">₱${sub.toFixed(2)}</span>
                        <button onclick="cart.splice(${idx},1); renderCart();" class="text-red-300 hover:text-red-500 transition">
                            <i data-lucide="x-circle" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>
            `;
        });
        totalEl.innerText = "₱" + total.toLocaleString(undefined, {minimumFractionDigits: 2});
    }
    lucide.createIcons();
}

function clearCart() {
    if(confirm('Clear the current order?')) {
        cart = [];
        renderCart();
    }
}

function filterProducts() {
    const q = document.getElementById('product-search').value.toLowerCase();
    document.querySelectorAll('.product-card').forEach(card => {
        card.style.display = card.dataset.name.includes(q) ? 'flex' : 'none';
    });
}
</script>

<?php include '../includes/footer.php'; ?>