<!-- ItemPedia Custom "Gak Biasa" Confirmation Modal (High-End SweetAlert Alternative) -->
<div id="customConfirmModal" class="hidden fixed inset-0 z-[99999] flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-md transition-all duration-300 opacity-0 pointer-events-none" role="dialog" aria-modal="true">
    
    <!-- Modal Card Container -->
    <div id="customConfirmCard" class="relative w-full max-w-md bg-white rounded-[2.2rem] border border-slate-200/80 shadow-2xl shadow-slate-950/40 p-6 sm:p-7 text-center transform scale-90 transition-all duration-300 ease-out overflow-hidden">
        
        <!-- Decorative Ambient Background Glow -->
        <div id="confirmGlowEffect" class="absolute -top-24 -left-24 w-48 h-48 rounded-full bg-rose-400/20 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-48 h-48 rounded-full bg-blue-400/15 blur-3xl pointer-events-none"></div>

        <!-- Top Close Button (Subtle) -->
        <button type="button" onclick="closeCustomConfirm()" class="absolute top-5 right-5 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-400 hover:text-slate-700 flex items-center justify-center transition active:scale-90">
            <i class="fa-solid fa-xmark text-xs"></i>
        </button>

        <!-- Top Animated Floating Icon Badge -->
        <div class="relative w-20 h-20 mx-auto mb-4 flex items-center justify-center">
            <!-- Pulsing Ring -->
            <div id="confirmPulseRing" class="absolute inset-0 rounded-full bg-rose-500/20 animate-ping opacity-75"></div>
            <!-- Outer Glow Aura -->
            <div id="confirmOuterAura" class="absolute w-18 h-18 rounded-3xl bg-rose-100 border border-rose-200/60 flex items-center justify-center shadow-inner"></div>
            <!-- Core Gradient Icon Box -->
            <div id="confirmIconBox" class="relative w-14 h-14 rounded-2xl bg-gradient-to-tr from-rose-500 to-red-600 text-white flex items-center justify-center text-2xl shadow-xl shadow-rose-500/35 border-2 border-white/50 transform transition duration-300">
                <i id="confirmIcon" class="fa-solid fa-trash-can animate-bounce"></i>
            </div>
        </div>

        <!-- Title & Description -->
        <h3 id="confirmTitle" class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mb-2">
            Konfirmasi Hapus
        </h3>
        <p id="confirmMessage" class="text-xs sm:text-sm text-slate-500 leading-relaxed max-w-sm mx-auto mb-4">
            Apakah Anda yakin ingin menghapus data ini dari sistem?
        </p>

        <!-- Highlight Item Name Card (Optional) -->
        <div id="confirmItemBox" class="mb-4 p-3 rounded-2xl bg-slate-50 border border-slate-200/80 flex items-center justify-center gap-2 text-xs font-bold text-slate-800 shadow-2xs">
            <i id="confirmItemIcon" class="fa-solid fa-tag text-rose-500"></i>
            <span id="confirmItemName" class="truncate max-w-[280px] font-black text-rose-600">Nama Item</span>
        </div>

        <!-- Safety Notice Pill -->
        <div id="confirmWarningPill" class="mb-6 py-2 px-3 rounded-xl bg-rose-50 border border-rose-200 text-[11px] font-bold text-rose-700 flex items-center justify-center gap-1.5">
            <i class="fa-solid fa-triangle-exclamation text-rose-500"></i>
            <span>Tindakan ini permanen dan tidak dapat dibatalkan</span>
        </div>

        <!-- Action Buttons -->
        <div class="grid grid-cols-2 gap-3">
            <button type="button" 
                    id="confirmCancelBtn" 
                    onclick="closeCustomConfirm()" 
                    class="w-full py-3 px-4 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-extrabold text-xs sm:text-sm transition-all duration-150 active:scale-95 flex items-center justify-center gap-1.5 shadow-2xs">
                <i class="fa-solid fa-xmark text-xs"></i>
                <span id="confirmCancelText">Batal</span>
            </button>
            <button type="button" 
                    id="confirmSubmitBtn" 
                    class="w-full py-3 px-4 rounded-2xl bg-gradient-to-r from-rose-500 via-red-500 to-rose-600 hover:from-rose-600 hover:to-red-700 text-white font-black text-xs sm:text-sm shadow-lg shadow-rose-500/30 hover:shadow-rose-500/50 hover:scale-[1.02] active:scale-[0.98] transition-all duration-150 flex items-center justify-center gap-2">
                <i id="confirmSubmitIcon" class="fa-solid fa-trash-can text-xs"></i>
                <span id="confirmSubmitText">Ya, Hapus</span>
            </button>
        </div>

    </div>
</div>

<script>
/**
 * ItemPedia Custom "Gak Biasa" Alert & Confirmation Framework
 */
let customConfirmCallback = null;
let currentSubmittingForm = null;

function showCustomConfirm(options = {}) {
    const modal = document.getElementById('customConfirmModal');
    const card = document.getElementById('customConfirmCard');
    if (!modal || !card) return;

    const {
        title = 'Konfirmasi Hapus',
        message = 'Apakah Anda yakin ingin menghapus data ini? Tindakan ini tidak dapat dibatalkan.',
        itemName = '',
        itemIcon = 'fa-solid fa-tag',
        type = 'danger', // danger | warning | info | success
        confirmText = 'Ya, Hapus',
        cancelText = 'Batal',
        confirmIcon = 'fa-solid fa-trash-can',
        warningNote = 'Tindakan ini permanen dan tidak dapat dibatalkan',
        onConfirm = null
    } = options;

    customConfirmCallback = onConfirm;

    // Elements
    const elTitle = document.getElementById('confirmTitle');
    const elMessage = document.getElementById('confirmMessage');
    const elItemBox = document.getElementById('confirmItemBox');
    const elItemName = document.getElementById('confirmItemName');
    const elItemIcon = document.getElementById('confirmItemIcon');
    const elWarningPill = document.getElementById('confirmWarningPill');
    const elPulseRing = document.getElementById('confirmPulseRing');
    const elOuterAura = document.getElementById('confirmOuterAura');
    const elIconBox = document.getElementById('confirmIconBox');
    const elIcon = document.getElementById('confirmIcon');
    const elGlow = document.getElementById('confirmGlowEffect');
    const elSubmitBtn = document.getElementById('confirmSubmitBtn');
    const elSubmitText = document.getElementById('confirmSubmitText');
    const elSubmitIcon = document.getElementById('confirmSubmitIcon');
    const elCancelText = document.getElementById('confirmCancelText');

    // Set Text Content
    elTitle.textContent = title;
    elMessage.innerHTML = message;
    elSubmitText.textContent = confirmText;
    elCancelText.textContent = cancelText;

    // Set Highlight Item
    if (itemName) {
        elItemBox.classList.remove('hidden');
        elItemName.textContent = itemName;
        elItemIcon.className = itemIcon;
    } else {
        elItemBox.classList.add('hidden');
    }

    // Set Warning Note
    if (warningNote) {
        elWarningPill.classList.remove('hidden');
        elWarningPill.querySelector('span').textContent = warningNote;
    } else {
        elWarningPill.classList.add('hidden');
    }

    // Set Color Theme based on Type
    if (type === 'danger') {
        elPulseRing.className = 'absolute inset-0 rounded-full bg-rose-500/20 animate-ping opacity-75';
        elOuterAura.className = 'absolute w-18 h-18 rounded-3xl bg-rose-100 border border-rose-200/60 flex items-center justify-center shadow-inner';
        elIconBox.className = 'relative w-14 h-14 rounded-2xl bg-gradient-to-tr from-rose-500 to-red-600 text-white flex items-center justify-center text-2xl shadow-xl shadow-rose-500/35 border-2 border-white/50';
        elIcon.className = confirmIcon + ' animate-bounce';
        elGlow.className = 'absolute -top-24 -left-24 w-48 h-48 rounded-full bg-rose-400/20 blur-3xl pointer-events-none';
        elSubmitBtn.className = 'w-full py-3 px-4 rounded-2xl bg-gradient-to-r from-rose-500 via-red-500 to-rose-600 hover:from-rose-600 hover:to-red-700 text-white font-black text-xs sm:text-sm shadow-lg shadow-rose-500/30 hover:shadow-rose-500/50 hover:scale-[1.02] active:scale-[0.98] transition-all duration-150 flex items-center justify-center gap-2';
        elSubmitIcon.className = confirmIcon + ' text-xs';
    } else if (type === 'warning') {
        elPulseRing.className = 'absolute inset-0 rounded-full bg-amber-500/20 animate-ping opacity-75';
        elOuterAura.className = 'absolute w-18 h-18 rounded-3xl bg-amber-100 border border-amber-200/60 flex items-center justify-center shadow-inner';
        elIconBox.className = 'relative w-14 h-14 rounded-2xl bg-gradient-to-tr from-amber-500 to-orange-500 text-white flex items-center justify-center text-2xl shadow-xl shadow-amber-500/35 border-2 border-white/50';
        elIcon.className = (confirmIcon || 'fa-solid fa-triangle-exclamation') + ' animate-pulse';
        elGlow.className = 'absolute -top-24 -left-24 w-48 h-48 rounded-full bg-amber-400/20 blur-3xl pointer-events-none';
        elSubmitBtn.className = 'w-full py-3 px-4 rounded-2xl bg-gradient-to-r from-amber-500 via-orange-500 to-amber-600 hover:from-amber-600 hover:to-orange-600 text-white font-black text-xs sm:text-sm shadow-lg shadow-amber-500/30 hover:shadow-amber-500/50 hover:scale-[1.02] active:scale-[0.98] transition-all duration-150 flex items-center justify-center gap-2';
        elSubmitIcon.className = 'fa-solid fa-check text-xs';
    } else if (type === 'info') {
        elPulseRing.className = 'absolute inset-0 rounded-full bg-blue-500/20 animate-ping opacity-75';
        elOuterAura.className = 'absolute w-18 h-18 rounded-3xl bg-blue-100 border border-blue-200/60 flex items-center justify-center shadow-inner';
        elIconBox.className = 'relative w-14 h-14 rounded-2xl bg-gradient-to-tr from-blue-500 to-indigo-600 text-white flex items-center justify-center text-2xl shadow-xl shadow-blue-500/35 border-2 border-white/50';
        elIcon.className = (confirmIcon || 'fa-solid fa-circle-info') + ' animate-pulse';
        elGlow.className = 'absolute -top-24 -left-24 w-48 h-48 rounded-full bg-blue-400/20 blur-3xl pointer-events-none';
        elSubmitBtn.className = 'w-full py-3 px-4 rounded-2xl bg-gradient-to-r from-blue-500 via-indigo-500 to-blue-600 hover:from-blue-600 hover:to-indigo-600 text-white font-black text-xs sm:text-sm shadow-lg shadow-blue-500/30 hover:shadow-blue-500/50 hover:scale-[1.02] active:scale-[0.98] transition-all duration-150 flex items-center justify-center gap-2';
        elSubmitIcon.className = 'fa-solid fa-arrow-right text-xs';
    }

    // Attach click handler to confirm button
    elSubmitBtn.onclick = function() {
        // Show micro-loading spinner
        elSubmitText.textContent = 'Memproses...';
        elSubmitIcon.className = 'fa-solid fa-circle-notch fa-spin text-xs';
        elSubmitBtn.disabled = true;

        if (typeof customConfirmCallback === 'function') {
            customConfirmCallback();
        } else if (currentSubmittingForm) {
            currentSubmittingForm.submit();
        }
    };

    // Show with smooth animation
    modal.classList.remove('hidden');
    modal.classList.remove('pointer-events-none');
    setTimeout(() => {
        modal.classList.remove('opacity-0');
        modal.classList.add('opacity-100');
        card.classList.remove('scale-90');
        card.classList.add('scale-100');
    }, 10);
}

function closeCustomConfirm() {
    const modal = document.getElementById('customConfirmModal');
    const card = document.getElementById('customConfirmCard');
    if (!modal || !card) return;

    modal.classList.remove('opacity-100');
    modal.classList.add('opacity-0');
    card.classList.remove('scale-100');
    card.classList.add('scale-90');

    setTimeout(() => {
        modal.classList.add('hidden');
        modal.classList.add('pointer-events-none');
        customConfirmCallback = null;
        currentSubmittingForm = null;

        // Reset submit button state
        const elSubmitBtn = document.getElementById('confirmSubmitBtn');
        const elSubmitText = document.getElementById('confirmSubmitText');
        const elSubmitIcon = document.getElementById('confirmSubmitIcon');
        if (elSubmitBtn) {
            elSubmitBtn.disabled = false;
            elSubmitText.textContent = 'Ya, Hapus';
            elSubmitIcon.className = 'fa-solid fa-trash-can text-xs';
        }
    }, 250);
}

/**
 * Helper Universal untuk Form OnSubmit Interceptor
 * Penggunaan: onsubmit="return confirmFormSubmit(event, { title: 'Hapus Dagangan?', itemName: 'Glazyn Egg Event' })"
 */
function confirmFormSubmit(event, options = {}) {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    const form = event.target || event.currentTarget;
    currentSubmittingForm = form;

    showCustomConfirm({
        title: options.title || 'Konfirmasi Hapus Data',
        message: options.message || 'Apakah Anda yakin ingin menghapus data ini secara permanen dari sistem ItemPedia?',
        itemName: options.itemName || '',
        itemIcon: options.itemIcon || 'fa-solid fa-trash-can',
        type: options.type || 'danger',
        confirmText: options.confirmText || 'Ya, Hapus Sekarang',
        cancelText: options.cancelText || 'Batal',
        confirmIcon: options.confirmIcon || 'fa-solid fa-trash-can',
        warningNote: options.warningNote || 'Tindakan ini permanen dan tidak dapat dibatalkan',
        onConfirm: function() {
            form.submit();
        }
    });

    return false;
}
</script>

<!-- Floating Toast Container -->
<div id="customToastContainer" class="fixed bottom-6 right-6 z-[999999] flex flex-col gap-2.5 pointer-events-none max-w-sm w-full px-4"></div>

<script>
/**
 * ItemPedia Custom Floating Glass Toast
 * Penggunaan: showCustomToast('Data berhasil disimpan', 'success')
 */
function showCustomToast(message, type = 'success') {
    const container = document.getElementById('customToastContainer');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = 'pointer-events-auto transform translate-y-6 opacity-0 transition-all duration-300 ease-out p-4 rounded-2xl border shadow-2xl backdrop-blur-md flex items-center justify-between gap-3 text-xs sm:text-sm font-bold ' + 
        (type === 'success' ? 'bg-emerald-950/90 text-emerald-200 border-emerald-500/40 shadow-emerald-950/50' : 
        (type === 'error' || type === 'danger' ? 'bg-rose-950/90 text-rose-200 border-rose-500/40 shadow-rose-950/50' : 
        'bg-slate-900/90 text-white border-slate-700/60 shadow-slate-950/50'));

    const iconHtml = type === 'success' ? '<div class="w-7 h-7 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center flex-shrink-0 text-sm"><i class="fa-solid fa-check"></i></div>' : 
        (type === 'error' || type === 'danger' ? '<div class="w-7 h-7 rounded-xl bg-rose-500/20 text-rose-400 flex items-center justify-center flex-shrink-0 text-sm"><i class="fa-solid fa-triangle-exclamation"></i></div>' : 
        '<div class="w-7 h-7 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center flex-shrink-0 text-sm"><i class="fa-solid fa-circle-info"></i></div>');

    toast.innerHTML = `
        <div class="flex items-center gap-3">
            ${iconHtml}
            <span>${message}</span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-slate-400 hover:text-white p-1 transition flex-shrink-0">
            <i class="fa-solid fa-xmark text-xs"></i>
        </button>
    `;

    container.appendChild(toast);

    setTimeout(() => {
        toast.classList.remove('translate-y-6', 'opacity-0');
        toast.classList.add('translate-y-0', 'opacity-100');
    }, 10);

    setTimeout(() => {
        toast.classList.remove('translate-y-0', 'opacity-100');
        toast.classList.add('translate-y-4', 'opacity-0');
        setTimeout(() => toast.remove(), 300);
    }, 3500);
}

/**
 * Custom Alert (Pengganti alert bawaan browser)
 */
function showCustomAlert(options = {}) {
    const title = typeof options === 'string' ? 'Informasi' : (options.title || 'Informasi');
    const message = typeof options === 'string' ? options : (options.message || '');
    const type = options.type || 'info';

    showCustomConfirm({
        title: title,
        message: message,
        type: type,
        confirmText: 'Mengerti',
        cancelText: '',
        confirmIcon: type === 'success' ? 'fa-solid fa-check' : (type === 'danger' ? 'fa-solid fa-xmark' : 'fa-solid fa-check'),
        warningNote: '',
        onConfirm: () => closeCustomConfirm()
    });

    // Hide cancel button if cancelText is empty
    const cancelBtn = document.getElementById('confirmCancelBtn');
    if (cancelBtn) {
        cancelBtn.classList.add('hidden');
    }
}

// Global Keyboard Shortcut (ESC to close modal) & Click Backdrop
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const modal = document.getElementById('customConfirmModal');
        if (modal && !modal.classList.contains('hidden')) {
            closeCustomConfirm();
        }
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('customConfirmModal');
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeCustomConfirm();
            }
        });
    }
});
</script>
