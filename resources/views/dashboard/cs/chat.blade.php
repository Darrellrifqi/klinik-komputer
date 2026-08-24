@extends('layouts.dashboard')
@section('title', 'Konsultasi Member | Panel CS')
@section('page_title', 'Konsultasi Member')
@section('page_subtitle', 'Tanggapi pesan masuk dan konsultasi teknis dari member Klinik Komputer')

@section('sidebar_nav')
@include('dashboard.cs.sidebar')
@endsection

@section('content')

<style>
    .cs-chat-container {
        height: calc(100vh - 180px);
        display: grid;
        grid-template-columns: 320px 1fr;
    }
    .cs-mobile-back-btn {
        display: none;
    }

    @media (max-width: 768px) {
        .dashboard-content {
            padding: 8px !important;
        }
        .cs-chat-container {
            display: flex !important;
            flex-direction: column !important;
            height: calc(100vh - 130px) !important;
            border-radius: 8px !important;
            overflow: hidden !important;
        }
        .cs-mobile-back-btn {
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            font-size: 1rem !important;
            font-weight: 800 !important;
            color: var(--primary) !important;
            text-decoration: none !important;
            margin-right: 4px !important;
            padding: 0 !important;
            width: 32px !important;
            height: 32px !important;
            background: var(--bg-alt) !important;
            border-radius: 50% !important;
            border: 1px solid var(--border-light) !important;
            flex-shrink: 0 !important;
        }

        @if(isset($chat))
            /* Active Chat Mode: Hide Chat List on Mobile */
            .cs-chat-left-panel {
                display: none !important;
            }
            .cs-chat-right-panel {
                display: flex !important;
                flex: 1 !important;
                width: 100% !important;
            }
        @else
            /* List Mode: Hide Active Chat Room Empty State on Mobile */
            .cs-chat-left-panel {
                display: flex !important;
                flex: 1 !important;
                width: 100% !important;
            }
            .cs-chat-right-panel {
                display: none !important;
            }
        @endif

        .cs-header-member-id {
            display: none !important;
        }

        #chatMessages > div {
            max-width: 85% !important;
        }
    }
</style>

<div class="dash-card cs-chat-container">
    
    <!-- LEFT PANEL: Chat Rooms List -->
    <div class="cs-chat-left-panel" style="border-right: 1px solid var(--border); display: flex; flex-direction: column; background: #fff;">
        <div style="padding: 16px 20px; border-bottom: 1px solid var(--border); background: var(--bg-alt);">
            <h4 style="margin: 0; font-size: 0.9rem; font-weight: 800; color: var(--text-primary); text-transform: uppercase; letter-spacing: 0.05em;">Ruang Obrolan</h4>
        </div>
        
        <div style="flex: 1; overflow-y: auto; display: flex; flex-direction: column;">
            @if($chats->count() === 0)
                <div style="padding: 30px 20px; text-align: center; color: var(--text-muted); font-size: 0.8rem;">
                    Belum ada member yang memulai obrolan.
                </div>
            @else
                @foreach($chats as $c)
                    @php $isActive = (isset($chat) && $chat->id === $c->id); @endphp
                    <a href="{{ route('dashboard.cs.chat.show', $c->id) }}" 
                       style="display: flex; gap: 12px; padding: 14px 20px; border-bottom: 1px solid var(--border-light); text-decoration: none; transition: background 0.2s;
                              background: {{ $isActive ? 'rgba(95, 138, 99, 0.05)' : '#ffffff' }};">
                        
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: {{ $isActive ? 'var(--primary)' : 'var(--bg-alt)' }}; 
                                    color: {{ $isActive ? '#ffffff' : 'var(--primary)' }}; 
                                    display: flex; align-items: center; justify-content: center; font-weight: 700; flex-shrink: 0; font-size: 0.95rem;">
                            {{ strtoupper(substr($c->customer->name ?? 'M', 0, 1)) }}
                        </div>
                        
                        <div style="flex: 1; min-width: 0;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                <strong style="font-size: 0.85rem; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: block; max-width: 140px;">
                                    {{ $c->customer->name ?? 'Guest Member' }}
                                </strong>
                                <span style="font-size: 0.65rem; color: var(--text-muted);">
                                    {{ $c->updated_at->format('H:i') }}
                                </span>
                            </div>
                            
                            <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px;">
                                <p style="margin: 0; font-size: 0.76rem; color: var(--text-secondary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                    {{ $c->last_message ?? 'Memulai percakapan baru...' }}
                                </p>
                                @if($c->unread_by_cs)
                                    <span style="width: 8px; height: 8px; border-radius: 50%; background: var(--danger); flex-shrink: 0; display: inline-block;"></span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            @endif
        </div>
    </div>

    <!-- RIGHT PANEL: Active Chat Room -->
    <div class="cs-chat-right-panel" style="display: flex; flex-direction: column; background: var(--bg-alt);">
        @if(isset($chat))
            <!-- Chat Room Header -->
            <div style="padding: 12px 16px; border-bottom: 1px solid var(--border); background: #fff; display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 10px; min-width: 0; overflow: hidden;">
                    <a href="{{ route('dashboard.cs.chat') }}" class="cs-mobile-back-btn" title="Kembali ke Ruang Obrolan">
                        ←
                    </a>
                    <div style="width: 36px; height: 36px; border-radius: 50%; background: rgba(95, 138, 99, 0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1rem; flex-shrink: 0;">
                        {{ strtoupper(substr($chat->customer->name ?? 'M', 0, 1)) }}
                    </div>
                    <div style="min-width: 0; overflow: hidden;">
                        <h4 style="margin: 0; font-size: 0.9rem; font-weight: 700; color: var(--text-primary); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $chat->customer->name }}</h4>
                        <div style="font-size: 0.7rem; color: var(--text-muted); display: flex; gap: 6px; align-items: center; flex-wrap: wrap;">
                            <span>{{ $chat->customer->phone }}</span>
                        </div>
                    </div>
                </div>
                @if($chat->customer->laptopKits->count() > 0)
                <div class="cs-header-member-id" style="text-align: right; flex-shrink: 0;">
                    <span class="badge badge-primary" style="font-family: monospace; font-size: 0.7rem; padding: 4px 8px;">
                        Member ID: {{ $chat->customer->laptopKits->first()->member_id }}
                    </span>
                </div>
                @endif
            </div>

            <!-- Messages Body -->
            <div id="chatMessages" style="flex: 1; overflow-y: auto; padding: 20px; display: flex; flex-direction: column; gap: 14px;">
                @if($messages->count() === 0)
                    <div style="margin: auto; text-align: center; color: var(--text-muted); font-size: 0.8rem;">
                        Belum ada pesan di percakapan ini.
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

            <!-- Send Input Bar -->
            <div style="padding: 14px 20px; border-top: 1px solid var(--border); background: #fff;">
                <form id="chatForm" action="{{ route('dashboard.cs.chat.store', $chat->id) }}" method="POST" style="display: flex; gap: 12px; align-items: center;">
                    @csrf
                    <input type="text" name="message" id="messageInput" autocomplete="off" required placeholder="Ketik balasan Anda di sini..." 
                           style="flex: 1; padding: 12px 16px; border: 1px solid var(--border); border-radius: 24px; font-size: 0.88rem; outline: none; background: var(--bg-alt); transition: all 0.2s;"
                           onfocus="this.style.borderColor='var(--primary)'; this.style.backgroundColor='#fff';"
                           onblur="this.style.borderColor='var(--border)'; this.style.backgroundColor='var(--bg-alt)';">
                    
                    <button type="submit" style="width: 44px; height: 44px; border-radius: 50%; background: var(--primary); border: none; color: #fff; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: transform 0.1s;"
                            onmousedown="this.style.transform='scale(0.95)';" onmouseup="this.style.transform='scale(1)';">
                        <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" stroke-width="2.5" fill="none" style="transform: rotate(45deg); margin-left: -2px; margin-top: 2px;"><line x1="22" y1="2" x2="11" y2="13"></line><polygon points="22 2 15 22 11 13 2 9 22 2"></polygon></svg>
                    </button>
                </form>
            </div>
        @else
            <!-- Empty State -->
            <div style="margin: auto; text-align: center; padding: 40px; max-width: 320px;">
                <div style="font-size: 2.5rem; color: var(--text-muted); margin-bottom: 12px;">💬</div>
                <h4 style="color: var(--text-primary); font-weight: 700; margin-bottom: 6px;">Mulai Konsultasi</h4>
                <p style="color: var(--text-secondary); font-size: 0.8rem; line-height: 1.4;">Pilih salah satu ruang obrolan member dari daftar di samping kiri untuk membaca pesan dan membalas konsultasi.</p>
            </div>
        @endif
    </div>

</div>

@if(isset($chat))
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
            console.error("Gagal membalas pesan:", err);
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
                    <div style="margin: auto; text-align: center; color: var(--text-muted); font-size: 0.8rem;">
                        Belum ada pesan di percakapan ini.
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

    // Pause polling when tab is not active to save resources
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
@endif
@endsection
