<!-- ================= DOCKED CHAT WIDGET (SELLER CENTER STYLE - MATCHING SCREENSHOT) ================= -->
<div id="floatingChatDockContainer" class="select-none">
    
    <!-- 1. Floating Minimized Trigger Button (Bottom Right) -->
    <div id="floatingChatTriggerBtn" class="fixed bottom-5 right-5 z-40 flex items-center">
        <button type="button" 
                onclick="toggleFloatingChat(true)" 
                class="bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-800 dark:text-slate-100 border border-slate-200/90 dark:border-slate-700 font-extrabold py-2.5 px-4 rounded-full shadow-lg flex items-center gap-2.5 transition transform active:scale-95 cursor-pointer">
            <i class="fa-solid fa-envelope text-blue-600 dark:text-blue-400 text-sm"></i>
            <span class="text-xs font-bold text-slate-900 dark:text-white">Pesan</span>
            <?php $unreadWidgetCount = $unreadChatCount ?? 0; ?>
            <span id="floatingTotalUnreadBadge" class="<?= $unreadWidgetCount > 0 ? '' : 'hidden' ?> w-5 h-5 rounded-full bg-blue-600 text-white text-[10px] font-black flex items-center justify-center">
                <?= $unreadWidgetCount ?>
            </span>
        </button>
    </div>

    <!-- 2. Expanded Floating Chat Box Window (Persis Screenshot media_1790151486020.png) -->
    <div id="floatingChatWindow" 
         class="hidden fixed bottom-4 right-4 sm:right-6 z-50 w-[95vw] sm:w-[760px] md:w-[820px] h-[540px] sm:h-[580px] bg-white dark:bg-[#0c1e33] border border-slate-300/80 dark:border-slate-800 rounded-2xl shadow-2xl flex flex-col overflow-hidden animate-in fade-in zoom-in-95 duration-200">
        
        <!-- Window Top Header Bar -->
        <div class="px-4 py-3 bg-white dark:bg-[#0c1e33] border-b border-slate-200 dark:border-slate-800 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-2">
                <i class="fa-solid fa-envelope text-slate-700 dark:text-slate-300 text-sm"></i>
                <span class="font-extrabold text-slate-900 dark:text-white text-sm">Pesan</span>
            </div>

            <!-- Minimize / Collapse Button (Down Chevron) -->
            <button type="button" 
                    onclick="toggleFloatingChat(false)" 
                    class="w-7 h-7 rounded-lg text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 flex items-center justify-center transition" 
                    title="Tutup Kotak Pesan">
                <i class="fa-solid fa-chevron-down text-xs"></i>
            </button>
        </div>

        <!-- Main Body: Split View (Left: Inbox, Right: Chat Thread) -->
        <div class="flex-grow flex overflow-hidden">
            
            <!-- LEFT PANEL: Conversation List (w-64 to w-72) -->
            <div class="w-64 sm:w-72 border-r border-slate-200 dark:border-slate-800 flex flex-col bg-white dark:bg-[#0c1e33] flex-shrink-0">
                
                <!-- Filter Dropdown & History Link -->
                <div class="p-3 border-b border-slate-100 dark:border-slate-800 space-y-2">
                    <div class="relative">
                        <select id="chatInboxFilter" onchange="filterChatInbox(this.value)" class="w-full px-3 py-1.5 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 focus:outline-none focus:border-blue-500 shadow-2xs cursor-pointer">
                            <option value="ALL">Semua Pesan</option>
                            <option value="UNREAD">Belum Dibaca</option>
                            <option value="SUCCESS">Pesanan Selesai</option>
                            <option value="NEED_PROCESS">Perlu Diproses</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-1.5 text-[11px] text-slate-400 dark:text-slate-500 px-1 font-medium">
                        <i class="fa-regular fa-clock text-[10px]"></i>
                        <span>Lihat riwayat pesan <a href="/admin/orders" class="text-blue-600 dark:text-blue-400 font-bold hover:underline">di sini</a></span>
                    </div>
                </div>

                <!-- Conversation Items List Container -->
                <div id="chatConversationList" class="flex-grow overflow-y-auto divide-y divide-slate-50 dark:divide-slate-800/60 no-scrollbar">
                    <!-- Populated dynamically via JS -->
                    <div class="p-8 text-center text-slate-400 dark:text-slate-500 text-xs">
                        <i class="fa-solid fa-spinner fa-spin text-base mb-1"></i>
                        <p>Memuat percakapan...</p>
                    </div>
                </div>
            </div>

            <!-- RIGHT PANEL: Active Conversation Stream -->
            <div id="chatRightPanel" class="flex-grow flex flex-col bg-white dark:bg-[#0c1e33] overflow-hidden relative">
                
                <!-- Active Conversation Header -->
                <div id="chatActiveHeader" class="p-3 px-4 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between bg-white dark:bg-[#0c1e33] flex-shrink-0">
                    <div class="flex items-center gap-2.5 min-w-0">
                        <!-- Initial Avatar Circle (Matching Cyan in Screenshot) -->
                        <div id="chatActiveAvatar" class="w-9 h-9 rounded-full bg-cyan-400 text-white font-black text-sm flex items-center justify-center flex-shrink-0 shadow-2xs">
                            J
                        </div>
                        <div class="min-w-0">
                            <div class="flex items-center gap-1.5">
                                <span id="chatActiveUsername" class="font-extrabold text-slate-900 dark:text-white text-sm truncate">Jajie</span>
                                <span id="chatActiveCountry" class="text-xs font-bold text-slate-400 dark:text-slate-500">MY</span>
                            </div>
                        </div>
                    </div>

                    <!-- Right Action Button: Daftar Pesanan -->
                    <div class="flex items-center gap-2">
                        <a id="chatActiveOrderBtn" 
                           href="#" 
                           target="_blank" 
                           class="px-3 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-50 dark:hover:bg-slate-800 text-slate-700 dark:text-slate-200 text-xs font-bold flex items-center gap-1.5 shadow-2xs transition active:scale-95">
                            <span>Daftar Pesanan</span>
                            <i class="fa-solid fa-bag-shopping text-slate-400 dark:text-slate-500 text-xs"></i>
                        </a>
                    </div>
                </div>

                <!-- Message Bubbles Scroll Area -->
                <div id="chatMessageStream" class="flex-grow p-4 space-y-3 overflow-y-auto bg-white dark:bg-[#0c1e33] text-xs no-scrollbar">
                    <!-- Messages will be populated here via JavaScript -->
                </div>

                <!-- Quick Suggestions / VIP Server Link Chip Strip -->
                <div class="px-3 py-1.5 bg-slate-50/70 dark:bg-slate-900/60 border-t border-slate-100 dark:border-slate-800 flex items-center gap-1.5 overflow-x-auto no-scrollbar text-[11px]">
                    <button type="button" onclick="sendQuickChatPreset('https://www.roblox.com/games/share?code=' + Math.random().toString(36).substring(2,10) + '&type=Server')" class="px-2.5 py-1 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-blue-600 dark:text-blue-400 font-bold hover:bg-blue-50 dark:hover:bg-slate-700 whitespace-nowrap transition shadow-2xs">
                        🔗 Kirim Link Private Server
                    </button>
                    <button type="button" onclick="sendQuickChatPreset('Ok siap kak, item sedang disiapkan ya!')" class="px-2.5 py-1 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-semibold hover:bg-slate-100 dark:hover:bg-slate-700 whitespace-nowrap transition shadow-2xs">
                        Item disiapkan
                    </button>
                    <button type="button" onclick="sendQuickChatPreset('ntar mimin ganti akun dulu ya kak')" class="px-2.5 py-1 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-semibold hover:bg-slate-100 dark:hover:bg-slate-700 whitespace-nowrap transition shadow-2xs">
                        Ganti akun dulu
                    </button>
                    <button type="button" onclick="sendQuickChatPreset('Makasih banyak ya kak sudah order!')" class="px-2.5 py-1 rounded-lg bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-semibold hover:bg-slate-100 dark:hover:bg-slate-700 whitespace-nowrap transition shadow-2xs">
                        Terima kasih
                    </button>
                </div>

                <!-- Chat Input Bar (Persis Screenshot) -->
                <div class="p-3 border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-[#0c1e33] flex-shrink-0">
                    <form id="floatingChatForm" onsubmit="handleSendFloatingChat(event)" class="flex items-center gap-2">
                        
                        <!-- Text Input -->
                        <div class="relative flex-grow">
                            <input type="text" 
                                   id="floatingChatInput" 
                                   placeholder="Ketik pesan di sini" 
                                   autocomplete="off"
                                   class="w-full pl-3.5 pr-9 py-2 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 rounded-xl text-xs sm:text-sm text-slate-800 dark:text-slate-100 placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:border-blue-500 shadow-2xs">
                            
                            <!-- Paperclip Icon inside input or side -->
                            <button type="button" 
                                    onclick="sendQuickChatPreset('https://www.roblox.com/games/share?code=3fb049a07853207a5ac8b8&type=Server')" 
                                    class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 text-xs" 
                                    title="Lampirkan Link Server">
                                <i class="fa-solid fa-paperclip"></i>
                            </button>
                        </div>

                        <!-- Dark Send Button Pill with Paper Plane -->
                        <button type="submit" 
                                id="floatingBtnSend" 
                                class="w-9 h-9 rounded-full bg-slate-800 dark:bg-blue-600 hover:bg-slate-900 dark:hover:bg-blue-700 text-white flex items-center justify-center text-xs shadow-xs transition active:scale-95 flex-shrink-0" 
                                title="Kirim Pesan">
                            <i class="fa-solid fa-paper-plane text-xs"></i>
                        </button>
                    </form>
                </div>

            </div>

        </div>

    </div>

</div>

<!-- JavaScript Controller for the Docked Chat Widget -->
<script>
let allChatConversations = [];
let activeChatInvoice = null;
let chatPollingTimer = null;
let knownMessageIds = new Set();

// Toggle Chat Window Open / Close
function toggleFloatingChat(open) {
    const win = document.getElementById('floatingChatWindow');
    const trigger = document.getElementById('floatingChatTriggerBtn');
    if (!win) return;

    if (open) {
        win.classList.remove('hidden');
        trigger.classList.add('hidden');
        loadChatInboxList();
        if (!chatPollingTimer) {
            chatPollingTimer = setInterval(pollCurrentChatMessages, 3000);
        }
    } else {
        win.classList.add('hidden');
        trigger.classList.remove('hidden');
        if (chatPollingTimer) {
            clearInterval(chatPollingTimer);
            chatPollingTimer = null;
        }
    }
}

// Update Unread Badges Across UI (Dock Button & Sidebar)
function updateUnreadBadges(totalUnread) {
    const unreadBadge = document.getElementById('floatingTotalUnreadBadge');
    if (unreadBadge) {
        if (totalUnread > 0) {
            unreadBadge.classList.remove('hidden');
            unreadBadge.innerText = totalUnread;
        } else {
            unreadBadge.classList.add('hidden');
            unreadBadge.innerText = '0';
        }
    }

    // Update sidebar badges if present
    document.querySelectorAll('.admin-sidebar-chat-unread').forEach(el => {
        if (totalUnread > 0) {
            el.classList.remove('hidden');
            el.innerText = totalUnread;
        } else {
            el.classList.add('hidden');
            el.innerText = '0';
        }
    });
}

// Load Inbox List from API
async function loadChatInboxList(preserveActive = true) {
    try {
        const res = await fetch('/api/chat/inbox');
        const data = await res.json();
        if (data.success && Array.isArray(data.conversations)) {
            allChatConversations = data.conversations;

            // Jika ada percakapan aktif yang sedang terbuka, set unread_count-nya jadi 0
            if (activeChatInvoice) {
                const activeConv = allChatConversations.find(c => c.invoice_number === activeChatInvoice);
                if (activeConv) {
                    activeConv.unread_count = 0;
                }
            }

            // Hitung total unread
            let totalUnread = 0;
            allChatConversations.forEach(c => {
                totalUnread += (c.unread_count || 0);
            });
            updateUnreadBadges(totalUnread);

            renderInboxList(allChatConversations);

            if (!activeChatInvoice && allChatConversations.length > 0) {
                selectChatConversation(allChatConversations[0].invoice_number);
            } else if (activeChatInvoice && !preserveActive) {
                selectChatConversation(activeChatInvoice);
            }
        }
    } catch (e) {
        console.error("Gagal load inbox:", e);
    }
}

// Render the Conversation List on the Left Panel
function renderInboxList(list) {
    const container = document.getElementById('chatConversationList');
    if (!container) return;

    if (list.length === 0) {
        container.innerHTML = '<div class="p-6 text-center text-slate-400 dark:text-slate-500 text-xs">Tidak ada percakapan.</div>';
        return;
    }

    container.innerHTML = list.map(c => {
        const isSelected = (c.invoice_number === activeChatInvoice);
        const initial = (c.roblox_username || 'U').charAt(0).toUpperCase();
        
        return `
            <div onclick="selectChatConversation('${c.invoice_number}')" 
                 class="p-3 sm:p-3.5 flex items-start gap-2.5 cursor-pointer transition ${isSelected ? 'bg-blue-50/80 dark:bg-blue-900/30 border-l-4 border-l-blue-600' : 'hover:bg-slate-50 dark:hover:bg-slate-800/50'}">
                
                <!-- Initial Avatar Circle (Cyan/Teal like screenshot) -->
                <div class="w-8 h-8 sm:w-9 sm:h-9 rounded-full bg-cyan-400 text-white font-black text-xs sm:text-sm flex items-center justify-center flex-shrink-0 shadow-2xs">
                    ${initial}
                </div>

                <!-- Middle: Username & Message Snippet -->
                <div class="min-w-0 flex-grow">
                    <div class="flex items-center justify-between gap-1">
                        <span class="font-extrabold text-slate-900 dark:text-white text-xs sm:text-sm truncate">${escapeChatHtml(c.roblox_username)}</span>
                        <span class="text-[10px] text-slate-400 dark:text-slate-500 font-semibold flex-shrink-0">${c.time_formatted || ''}</span>
                    </div>

                    <div class="flex items-center justify-between gap-1 mt-0.5">
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-normal truncate max-w-[130px] sm:max-w-[150px]">
                            ${escapeChatHtml(c.last_message || 'Pesanan')}
                        </p>
                        ${c.unread_count > 0 ? `
                            <span class="w-4 h-4 rounded-full bg-blue-600 text-white text-[9px] font-black flex items-center justify-center flex-shrink-0">
                                ${c.unread_count}
                            </span>
                        ` : ''}
                    </div>
                </div>

            </div>
        `;
    }).join('');
}

// Filter Inbox
function filterChatInbox(type) {
    if (type === 'ALL') {
        renderInboxList(allChatConversations);
    } else if (type === 'UNREAD') {
        renderInboxList(allChatConversations.filter(c => (c.unread_count > 0)));
    } else if (type === 'SUCCESS') {
        renderInboxList(allChatConversations.filter(c => (c.status === 'SUCCESS')));
    } else if (type === 'NEED_PROCESS') {
        renderInboxList(allChatConversations.filter(c => (c.status === 'PAID' || c.status === 'PROCESSING')));
    }
}

// Select Active Conversation & Mark as Read
async function selectChatConversation(invoice) {
    activeChatInvoice = invoice;
    knownMessageIds.clear();

    const conversation = allChatConversations.find(c => c.invoice_number === invoice);
    if (conversation) {
        // 1. Reset unread count seketika di state lokal
        conversation.unread_count = 0;
        
        const initial = (conversation.roblox_username || 'U').charAt(0).toUpperCase();
        document.getElementById('chatActiveAvatar').innerText = initial;
        document.getElementById('chatActiveUsername').innerText = conversation.roblox_username;
        document.getElementById('chatActiveCountry').innerText = (conversation.invoice_number.includes('JJI') ? 'MY' : 'ID');
        document.getElementById('chatActiveOrderBtn').href = '/order/' + conversation.invoice_number;
    }

    // 2. Hitung ulang total unread dan perbarui badge
    let totalUnread = 0;
    allChatConversations.forEach(c => {
        totalUnread += (c.unread_count || 0);
    });
    updateUnreadBadges(totalUnread);

    // 3. Render ulang list agar badge unread bulat biru (1) langsung HILANG
    renderInboxList(allChatConversations);

    const stream = document.getElementById('chatMessageStream');
    stream.innerHTML = '<div class="text-center py-10 text-slate-400 dark:text-slate-500 text-xs"><i class="fa-solid fa-spinner fa-spin text-base"></i><p class="mt-2">Memuat pesan...</p></div>';

    // 4. Panggil endpoint untuk ambil pesan dan otomatis update is_read = 1 di database
    await loadActiveChatMessages();
}

// Load Active Messages & Render Order Card
async function loadActiveChatMessages() {
    if (!activeChatInvoice) return;

    try {
        const res = await fetch(`/api/chat/${encodeURIComponent(activeChatInvoice)}?role=seller`);
        const data = await res.json();

        if (data.success) {
            const stream = document.getElementById('chatMessageStream');
            stream.innerHTML = '';

            // 1. Render Embedded Order Card (Persis Screenshot)
            if (data.order) {
                const ord = data.order;
                const statusLabel = ord.status === 'SUCCESS' ? 'Pesanan Selesai' : (ord.status === 'PAID' ? 'Perlu Diproses' : (ord.status === 'PROCESSING' ? 'Sedang Dikirim' : 'Menunggu Konfirmasi'));
                
                const cardHtml = `
                    <div class="mx-auto max-w-sm p-3.5 rounded-2xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-xs my-2">
                        <div class="flex items-start gap-3">
                            <div class="w-12 h-12 rounded-xl bg-amber-100/70 dark:bg-amber-900/30 border border-amber-200 dark:border-amber-700/50 flex items-center justify-center text-amber-500 text-xl flex-shrink-0 p-1 overflow-hidden">
                                <img src="${ord.image_url}" alt="" class="w-full h-full object-cover rounded-lg" onerror="this.src='/images/logo-icon.png'">
                            </div>
                            <div class="min-w-0 flex-grow">
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-100/80 dark:bg-amber-900/50 text-amber-800 dark:text-amber-300 inline-block mb-0.5">
                                    ${statusLabel}
                                </span>
                                <h4 class="text-xs font-black text-slate-900 dark:text-white uppercase tracking-tight truncate leading-tight" title="${escapeChatHtml(ord.product_name)}">
                                    ${escapeChatHtml(ord.product_name)}
                                </h4>
                                <div class="text-xs font-black text-amber-600 dark:text-amber-400 mt-0.5">
                                    Rp ${Number(ord.price).toLocaleString('id-ID')}
                                </div>
                            </div>
                        </div>

                        <a href="/order/${encodeURIComponent(ord.invoice_number)}" 
                           target="_blank" 
                           class="mt-2.5 w-full py-2 rounded-xl border border-blue-600 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-950/40 text-xs font-bold block text-center transition">
                            Lihat Pesanan
                        </a>
                    </div>
                `;
                stream.insertAdjacentHTML('beforeend', cardHtml);
            }

            // 2. Render Message Bubbles
            if (Array.isArray(data.messages)) {
                data.messages.forEach(msg => {
                    appendSingleMessageBubble(msg);
                });
            }

            stream.scrollTop = stream.scrollHeight;
        }
    } catch (e) {
        console.error("Gagal load pesan:", e);
    }
}

// Format Chat Message Content (Auto-Detect URLs & Roblox Share Links)
function formatChatMessageHtml(rawText, isSeller) {
    if (!rawText) return '';

    // Check if it's a sticker
    const stickerMatch = rawText.match(/^\[sticker:([a-z0-9_-]+)\]$/i);
    if (stickerMatch) {
        const sid = stickerMatch[1].toLowerCase();
        return `
            <div class="inline-block p-1">
                <div class="px-3 py-2 rounded-2xl bg-gradient-to-tr from-amber-400 to-yellow-500 text-white shadow-xs font-black text-xs inline-flex items-center gap-1.5 border border-white/40">
                    <i class="fa-solid fa-face-smile"></i>
                    <span>${sid.toUpperCase()}</span>
                </div>
            </div>
        `;
    }

    // Escape HTML first
    let escaped = rawText.replace(/[&<>"']/g, function(m) {
        return {'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'}[m];
    });

    let detectedRobloxUrl = null;

    // Detect http:// or https:// URLs
    const urlPattern = /(https?:\/\/[^\s]+)/gi;
    let formatted = escaped.replace(urlPattern, function(url) {
        if (url.includes('roblox.com')) {
            detectedRobloxUrl = url;
        }
        return `<a href="${url}" target="_blank" rel="noopener noreferrer" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 underline font-bold break-all inline-flex items-center gap-1 bg-white/70 dark:bg-slate-800/70 hover:bg-white dark:hover:bg-slate-800 px-1.5 py-0.5 rounded transition"><span>${url}</span><i class="fa-solid fa-arrow-up-right-from-square text-[9px] text-blue-500 dark:text-blue-400"></i></a>`;
    });

    // Detect raw share code like 3fb049a07853207a5ac8b8&type=Server
    if (!detectedRobloxUrl) {
        const rawCodePattern = /([a-z0-9_-]{8,}&type=Server|games\/share\?code=[a-z0-9_-]+(&type=Server)?)/gi;
        formatted = formatted.replace(rawCodePattern, function(match) {
            const cleanCode = match.includes('code=') ? match.split('code=')[1].split('&')[0] : match.split('&')[0];
            const fullRobloxUrl = 'https://www.roblox.com/games/share?code=' + cleanCode + '&type=Server';
            detectedRobloxUrl = fullRobloxUrl;
            return `<a href="${fullRobloxUrl}" target="_blank" rel="noopener noreferrer" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 underline font-bold break-all inline-flex items-center gap-1 bg-white/70 dark:bg-slate-800/70 hover:bg-white dark:hover:bg-slate-800 px-1.5 py-0.5 rounded transition"><span>${match}</span><i class="fa-solid fa-arrow-up-right-from-square text-[9px] text-blue-500 dark:text-blue-400"></i></a>`;
        });
    }

    // If a Roblox link is detected, add 1-click Join Button
    if (detectedRobloxUrl) {
        formatted += `
            <div class="mt-2 pt-2 border-t border-slate-200/60 dark:border-slate-700/60">
                <a href="${detectedRobloxUrl}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-[11px] font-extrabold shadow-2xs transition active:scale-95">
                    <i class="fa-solid fa-gamepad text-xs"></i>
                    <span>Buka / Join Server Roblox</span>
                    <i class="fa-solid fa-arrow-up-right-from-square text-[9px] ml-0.5 opacity-80"></i>
                </a>
            </div>
        `;
    }

    return formatted;
}

// Append Single Message Bubble
function appendSingleMessageBubble(msg) {
    const stream = document.getElementById('chatMessageStream');
    if (!stream) return;

    const id = Number(msg.id);
    if (knownMessageIds.has(id)) return;
    knownMessageIds.add(id);

    const isSeller = (msg.sender === 'seller');
    let bubbleHtml = '';

    if (isSeller) {
        // Outgoing Bubble (Light Sky Blue bg-blue-100 / bg-[#e0f2fe] in light, blue-600 in dark)
        bubbleHtml = `
            <div class="flex justify-end items-end gap-1.5 my-1.5">
                <div class="space-y-0.5 max-w-[85%] text-right">
                    <div class="p-3 px-3.5 rounded-2xl rounded-tr-xs bg-[#e0f2fe] dark:bg-blue-600 text-slate-800 dark:text-white text-xs font-medium text-left leading-relaxed break-words shadow-2xs">
                        ${formatChatMessageHtml(msg.message, true)}
                    </div>
                    <div class="text-[10px] text-slate-400 dark:text-slate-500 font-semibold flex items-center justify-end gap-1 px-1">
                        <span>${msg.time_formatted || ''}</span>
                        <i class="fa-solid fa-check-double text-blue-500 dark:text-blue-400 text-[9px]" title="Terkirim"></i>
                    </div>
                </div>
            </div>
        `;
    } else {
        // Incoming Bubble (White with border in light, dark:bg-slate-800 in dark)
        bubbleHtml = `
            <div class="flex justify-start items-start gap-1.5 my-1.5">
                <div class="space-y-0.5 max-w-[85%]">
                    <div class="p-3 px-3.5 rounded-2xl rounded-tl-xs bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-800 dark:text-slate-100 text-xs font-medium leading-relaxed break-words shadow-2xs">
                        ${formatChatMessageHtml(msg.message, false)}
                    </div>
                    <div class="text-[10px] text-slate-400 dark:text-slate-500 font-semibold px-1">
                        <span>${msg.time_formatted || ''}</span>
                    </div>
                </div>
            </div>
        `;
    }

    stream.insertAdjacentHTML('beforeend', bubbleHtml);
}

// Polling background update
async function pollCurrentChatMessages() {
    if (!activeChatInvoice) return;

    try {
        const maxId = knownMessageIds.size > 0 ? Math.max(...knownMessageIds) : 0;
        const res = await fetch(`/api/chat/${encodeURIComponent(activeChatInvoice)}?role=seller&after_id=${maxId}`);
        const data = await res.json();

        if (data.success && Array.isArray(data.messages)) {
            const stream = document.getElementById('chatMessageStream');
            let shouldScroll = false;
            data.messages.forEach(msg => {
                appendSingleMessageBubble(msg);
                shouldScroll = true;
            });
            if (shouldScroll && stream) {
                stream.scrollTop = stream.scrollHeight;
            }
        }
    } catch (e) {}
}

// Send Message Handler
async function handleSendFloatingChat(e) {
    e.preventDefault();
    const input = document.getElementById('floatingChatInput');
    const text = input.value.trim();
    if (!text || !activeChatInvoice) return;

    const btn = document.getElementById('floatingBtnSend');
    btn.disabled = true;

    try {
        const formData = new FormData();
        formData.append('message', text);
        formData.append('sender', 'seller');
        formData.append('sender_name', 'Seller ItemPedia');

        input.value = '';

        const res = await fetch(`/api/chat/${encodeURIComponent(activeChatInvoice)}/send`, {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.success && data.message) {
            appendSingleMessageBubble(data.message);
            const stream = document.getElementById('chatMessageStream');
            if (stream) stream.scrollTop = stream.scrollHeight;
            loadChatInboxList(true);
        }
    } catch (err) {
        alert('Gagal mengirim pesan');
    } finally {
        btn.disabled = false;
        input.focus();
    }
}

// Quick Preset / Server Link Sender
function sendQuickChatPreset(presetText) {
    const input = document.getElementById('floatingChatInput');
    if (!input) return;
    input.value = presetText;
    input.focus();
}

function escapeChatHtml(str) {
    if (!str) return '';
    return str.replace(/[&<>"']/g, function(m) {
        return {'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;'}[m];
    });
}

window.openChatDockForInvoice = function(invoice) {
    toggleFloatingChat(true);
    selectChatConversation(invoice);
};

window.toggleSellerDockChat = function(open) {
    toggleFloatingChat(open);
};
</script>
