<footer class="bg-white border-t border-emerald-100 py-4 mt-auto relative z-20">
        <div class="container mx-auto px-6 flex flex-col md:flex-row justify-between items-center text-gray-500 text-sm">
            <p>&copy; <?php echo date('Y'); ?> <span class="text-emerald-700 font-bold">Sari-Sari Store POS</span>. All rights reserved.</p>
            <div class="flex gap-4 mt-2 md:mt-0">
                <span class="flex items-center gap-1"><i data-lucide="shield-check" class="w-4 h-4 text-emerald-500"></i> Secure System</span>
                <span class="flex items-center gap-1"><i data-lucide="database" class="w-4 h-4 text-emerald-500"></i> Local Server</span>
            </div>
        </div>
    </footer>

    <div id="sidebar-overlay" class="fixed inset-0 bg-emerald-950/50 backdrop-blur-sm z-30 hidden lg:hidden"></div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // 1. SELECT ELEMENTS
            const elements = {
                mobileToggle: document.getElementById('mobile-sidebar-toggle'), // Must match header ID
                mobileClose: document.getElementById('mobile-close'),           // Close icon inside sidebar
                sidebar: document.getElementById('main-sidebar'),             // The sidebar itself
                overlay: document.getElementById('sidebar-overlay'),           // The dark background
                profileBtn: document.getElementById('profile-btn'),           // Profile button in header
                profileDropdown: document.getElementById('profile-dropdown')   // Profile menu
            };

            // 2. SIDEBAR FUNCTIONS
            const openSidebar = () => {
                elements.sidebar?.classList.remove('-translate-x-full');
                elements.overlay?.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            };

            const closeSidebar = () => {
                elements.sidebar?.classList.add('-translate-x-full');
                elements.overlay?.classList.add('hidden');
                document.body.style.overflow = '';
            };

            // 3. EVENT LISTENERS
            
            // Sidebar Toggles
            elements.mobileToggle?.addEventListener('click', (e) => {
                e.stopPropagation();
                openSidebar();
            });
            
            elements.mobileClose?.addEventListener('click', closeSidebar);
            elements.overlay?.addEventListener('click', closeSidebar);

            // Profile Dropdown Toggle
            elements.profileBtn?.addEventListener('click', (e) => {
                e.stopPropagation();
                elements.profileDropdown?.classList.toggle('hidden');
                // Rotate chevron if it exists
                elements.profileBtn.querySelector('[data-lucide="chevron-down"]')?.classList.toggle('rotate-180');
            });

            // Global Click-to-Close (Closes dropdowns when clicking anywhere else)
            document.addEventListener('click', (e) => {
                // Close Profile Dropdown
                if (elements.profileDropdown && !elements.profileDropdown.contains(e.target) && !elements.profileBtn.contains(e.target)) {
                    elements.profileDropdown.classList.add('hidden');
                    elements.profileBtn.querySelector('[data-lucide="chevron-down"]')?.classList.remove('rotate-180');
                }
                
                // Close Sidebar if clicking outside on mobile
                if (window.innerWidth < 1024 && elements.sidebar && !elements.sidebar.contains(e.target) && !elements.mobileToggle.contains(e.target)) {
                    closeSidebar();
                }
            });

            // 4. INITIALIZE ICONS
            if (window.lucide) {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>