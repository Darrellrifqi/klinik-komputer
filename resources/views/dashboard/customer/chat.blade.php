@extends('layouts.dashboard')
@section('title', 'Hubungi CS | Panel ' . (auth()->user()->laptopKits()->exists() ? 'Member' : 'Customer'))
@section('page_title', 'Hubungi CS (Chat Support)')
@section('page_subtitle', 'Tanyakan kendala perangkat Anda langsung ke Customer Service kami')

@section('sidebar_nav')
<a href="{{ route('dashboard.customer') }}" class="{{ request()->routeIs('dashboard.customer') ? 'active' : '' }}">
    <span class="nav-icon">Tiket & Laptop Saya</span>
</a>
<a href="{{ route('dashboard.customer.chat') }}" class="{{ request()->routeIs('dashboard.customer.chat*') ? 'active' : '' }}">
    <span class="nav-icon">Hubungi CS (Chat)</span>
    @php $unreadCust = \App\Models\Chat::where('customer_id', auth()->id())->where('unread_by_customer', true)->count(); @endphp
    @if($unreadCust > 0)<span class="badge-count">{{ $unreadCust }}</span>@endif
</a>
<a href="{{ route('service.booking') }}">
    <span class="nav-icon">Booking Servis Baru</span>
</a>
<a href="{{ route('service.track') }}">
    <span class="nav-icon">Cek Status Tiket</span>
</a>
<div class="sidebar-section-label">Navigasi</div>
<a href="{{ route('products') }}">
    <span class="nav-icon">Katalog Produk</span>
</a>
<a href="{{ route('home') }}">
    <span class="nav-icon">Beranda</span>
</a>
@endsection

@section('content')
<div style="height: calc(100vh - 180px); display: flex; flex-direction: column;" class="dash-card">
    <div class="dash-card-header" style="padding: 14px 20px; border-bottom: 1px solid var(--border); background: #fff; display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <div style="width: 40px; height: 40px; border-radius: 50%; background: rgba(95, 138, 99, 0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.1rem;">
                CS
            </div>
            <div>
                <h4 style="margin: 0; font-size: 0.95rem; font-weight: 700; color: var(--text-primary);">Support Klinik Komputer</h4>
                <div style="display: flex; align-items: center; gap: 6px;">
                    <span style="width: 8px; height: 8px; border-radius: 50%; background: #22c55e; display: inline-block;"></span>
                    <span style="font-size: 0.72rem; color: var(--text-muted);">Online</span>
                </div>
            </div>
        </div>
        @php $mbrId = \App\Models\ProcurementLaptopKit::where('customer_id', auth()->id())->value('member_id'); @endphp
        @if($mbrId)
        <div style="font-size: 0.72rem; color: var(--text-muted); font-family: monospace;">
            ID Member: <strong>{{ $mbrId }}</strong>
        </div>
        @endif
    </div>

    <!-- Chat Messages Box -->
    <div id="chatMessages" style="flex: 1; overflow-y: auto; padding: 20px; background: var(--bg-alt); display: flex; flex-direction: column; gap: 14px;">
        @if($messages->count() === 0)
            <div style="margin: auto; text-align: center; max-width: 400px; padding: 20px;">
                <div style="font-size: 2.2rem; margin-bottom: 10px;">👋</div>
                <h4 style="color: var(--text-primary); font-weight: 700; margin-bottom: 6px;">Halo {{ auth()->user()->name }}!</h4>
                <p style="color: var(--text-secondary); font-size: 0.8rem; line-height: 1.4;">Ada yang bisa kami bantu? Silakan tuliskan kendala laptop atau pertanyaan konsultasi Anda di bawah untuk memulai obrolan.</p>
            </div>
        @else
            @foreach($messages as $msg)
                @php $isMe = ($msg->sender_id == auth()->id()); @endphp
                <div style="max-width: 75%; display: flex; flex-direction: column; gap: 3px; align-self: {{ $isMe ? 'flex-end' : 'flex-start' }};">
                    <div style="padding: 10px 14px; border-radius: 12px; font-size: 0.85rem; line-height: 1.45;
                                background-color: {{ $isMe ? 'var(--primary)' : '#ffffff' }};
                                color: {{ $isMe ? '#ffffff' : 'var(--text-primary)' }};
                                border: {{ $isMe ? 'none' : '1px solid var(--border)' }};
                                border-bottom-{{ $isMe ? 'right' : 'left' }}-radius: 2px;">
                        {{ $msg->message }}
                    </div>
                    <span style="font-size: 0.65rem; color: var(--text-muted); text-align: {{ $isMe ? 'right' : 'left' }}; padding: 0 4px;">
                        {{ $msg->created_at->format('H:i') }}
                    </span>
                </div>
            @endforeach
        @endif
    </div>

    <!-- Footer Input Bar -->
    <div style="padding: 14px 20px; border-top: 1px solid var(--border); background: #fff;">
        <form id="chatForm" action="{{ route('dashboard.customer.chat.store') }}" method="POST" style="display: flex; gap: 12px; align-items: center;">
            @csrf
            <input type="text" name="message" id="messageInput" autocomplete="off" required placeholder="Tulis pesan konsultasi Anda di sini..." 
                   style="flex: 1; padding: 12px 16px; border: 1px solid var(--border); border-radius: 24px; font-size: 0.88rem; outline: none; background: var(--bg-alt); transition: all 0.2s;"
                   onfocus="this.style.borderColor='var(--primary)'; this.style.backgroundColor='#fff';"
                   onblur="this.style.borderColor='var(--border)'; this.style.backgroundColor='var(--bg-alt)';">
            
            <button type="submit" style="width: 44px; height: 44px; border-radius: 50%; background: var(--primary); border: none; color: #fff; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: transform 0.1s;"
                    onmousedown="this.style.transform='scale(0.95)';" onmouseup="this.style.transform='scale(1)';">
                <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2.5" fill="none" style="transform: rotate(45deg); margin-left: -2px; margin-top: 2px;"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
            </button>
        </form>
    </div>
</div>

@push('scripts')
<script>
    const chatMessages = document.getElementById('chatMessages');
    const chatForm = document.getElementById('chatForm');
    const messageInput = document.getElementById('messageInput');
    const currentUserId = {{ auth()->id() }};
    const chatApiUrl = "{{ route('chat.api.messages', $chat->id) }}";

    // Auto scroll to bottom
    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    // Initial scroll
    scrollToBottom();

    // Handle AJAX Form Submit
    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const text = messageInput.value.trim();
        if(!text) return;

        const formData = new FormData(this);

        // Optimistically clean input
        messageInput.value = '';
        messageInput.focus();
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(res => res.json())
        .then(data => {
            fetchMessages(); // Refresh immediately
        })
        .catch(err => {
            console.error("Gagal mengirim pesan:", err);
        });
    });

    let lastMessagesJson = '';

    // Fetch messages via Polling API
    function fetchMessages() {
        fetch(chatApiUrl)
        .then(res => res.json())
        .then(data => {
            const currentJson = JSON.stringify(data.messages);
            if (currentJson === lastMessagesJson) return; // No change, skip DOM render

            lastMessagesJson = currentJson;
            
            if(data.messages.length === 0) {
                chatMessages.innerHTML = `
                    <div style="margin: auto; text-align: center; max-width: 400px; padding: 20px;">
                        <div style="font-size: 2.2rem; margin-bottom: 10px;">👋</div>
                        <h4 style="color: var(--text-primary); font-weight: 700; margin-bottom: 6px;">Halo {{ auth()->user()->name }}!</h4>
                        <p style="color: var(--text-secondary); font-size: 0.8rem; line-height: 1.4;">Ada yang bisa kami bantu? Silakan tuliskan kendala laptop atau pertanyaan konsultasi Anda di bawah untuk memulai obrolan.</p>
                    </div>
                `;
                return;
            }

            let html = '';
            data.messages.forEach(msg => {
                const isMe = (msg.sender_id == currentUserId);
                html += `
                    <div style="max-width: 75%; display: flex; flex-direction: column; gap: 3px; align-self: ${isMe ? 'flex-end' : 'flex-start'};">
                        <div style="padding: 10px 14px; border-radius: 12px; font-size: 0.85rem; line-height: 1.45;
                                    background-color: ${isMe ? 'var(--primary)' : '#ffffff'};
                                    color: ${isMe ? '#ffffff' : 'var(--text-primary)'};
                                    border: ${isMe ? 'none' : '1px solid var(--border)'};
                                    border-bottom-${isMe ? 'right' : 'left'}-radius: 2px;">
                            ${escapeHtml(msg.message)}
                        </div>
                        <span style="font-size: 0.65rem; color: var(--text-muted); text-align: ${isMe ? 'right' : 'left'}; padding: 0 4px;">
                            ${msg.time}
                        </span>
                    </div>
                `;
            });
            chatMessages.innerHTML = html;
            scrollToBottom();
        })
        .catch(err => console.error("Gagal sinkronisasi pesan:", err));
    }

    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, function(m) { return map[m]; });
    }

    // Polling Interval (every 3 seconds)
    let pollInterval = setInterval(fetchMessages, 3000);

    // Pause polling when tab is not active to save client & server resources
    document.addEventListener("visibilitychange", function() {
        if (document.hidden) {
            clearInterval(pollInterval);
        } else {
            fetchMessages();
            pollInterval = setInterval(fetchMessages, 3000);
        }
    });
</script>
@endpush
@endsection
