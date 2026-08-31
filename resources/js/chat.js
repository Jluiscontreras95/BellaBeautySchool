const widget = document.querySelector('#chat-widget');
const launcher = document.querySelector('.chat-launcher');
const body = document.querySelector('#chat-body');
const form = document.querySelector('#chat-form');
const input = document.querySelector('#chat-input');
const quickWrap = document.querySelector('#chat-quick');
const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

const STORAGE_KEY = 'bela_chat_conversation';

const getConversationId = () => {
    let id = localStorage.getItem(STORAGE_KEY);
    if (!id) {
        id = crypto.randomUUID?.() ?? `${Date.now()}-${Math.random().toString(16).slice(2)}`;
        localStorage.setItem(STORAGE_KEY, id);
    }
    return id;
};

const isOpen = () => widget?.classList.contains('is-open');

const setOpen = (open) => {
    widget?.classList.toggle('is-open', open);
    widget?.setAttribute('aria-hidden', String(!open));
    launcher?.classList.toggle('is-open', open);
    launcher?.setAttribute('aria-expanded', String(open));
    if (open) input?.focus();
};

const escapeHtml = (value) => {
    const node = document.createElement('div');
    node.textContent = value;
    return node.innerHTML;
};

const renderText = (text) => text
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>')
    .replace(/\n/g, '<br>');

const renderMarkdown = (text) => {
    const escaped = text.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
    const lines = escaped.split('\n');
    let html = '';
    let inList = false;
    const mdInline = (s) => s.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>').replace(/(^|[^*])\*(?!\*)(.+?)\*(?!\*)/g, '$1<em>$2</em>');
    for (const raw of lines) {
        const trimmed = raw.trim();
        if (trimmed.startsWith('- ')) {
            const content = mdInline(trimmed.slice(2).trim());
            if (!inList) { html += '<ul class="chat-md-list">'; inList = true; }
            html += `<li>${content}</li>`;
        } else if (trimmed.startsWith('• ')) {
            const content = mdInline(trimmed.slice(2).trim());
            if (!inList) { html += '<ul class="chat-md-list">'; inList = true; }
            html += `<li>${content}</li>`;
        } else {
            if (inList) { html += '</ul>'; inList = false; }
            if (trimmed === '') {
                html += '<div style="height:6px"></div>';
            } else {
                html += `<p class="chat-md-p">${mdInline(raw)}</p>`;
            }
        }
    }
    if (inList) html += '</ul>';
    return html;
};

const addMessage = (role, content) => {
    const el = document.createElement('div');
    el.className = `chat-message ${role}`;
    const bubble = document.createElement('div');
    bubble.className = 'chat-bubble';
    bubble.innerHTML = renderText(content);
    el.appendChild(bubble);
    body?.appendChild(el);
    body?.scrollTo({ top: body.scrollHeight, behavior: 'smooth' });
    return el;
};

const setTyping = (on) => {
    document.querySelector('.chat-typing')?.remove();
    if (!on) return;
    const el = document.createElement('div');
    el.className = 'chat-message assistant chat-typing';
    el.innerHTML = '<div class="chat-bubble"><span></span><span></span><span></span></div>';
    body?.appendChild(el);
    body?.scrollTo({ top: body.scrollHeight, behavior: 'smooth' });
};

let hasInteracted = false;
const hideQuickActions = () => {
    if (!quickWrap || hasInteracted) return;
    hasInteracted = true;
    quickWrap.classList.add('is-hidden');
    quickWrap.setAttribute('aria-hidden', 'true');
    setTimeout(() => {
        if (quickWrap.classList.contains('is-hidden')) quickWrap.style.display = 'none';
    }, 400);
};

const send = async (message) => {
    const text = message?.trim();
    if (!text) return;

    hideQuickActions();
    addMessage('user', text);
    input.value = '';
    input.style.height = 'auto';
    setTyping(true);

    try {
        const response = await fetch('/chat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                Accept: 'text/event-stream',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: JSON.stringify({ message: text, conversation_id: getConversationId() }),
        });

        if (!response.ok || !response.body) {
            throw new Error('No se pudo conectar con la asistente.');
        }

        setTyping(false);
        const assistantEl = addMessage('assistant', '');
        const bubble = assistantEl.querySelector('.chat-bubble');
        let fullText = '';

        const reader = response.body.getReader();
        const decoder = new TextDecoder();
        let buffer = '';

        const flush = () => {
            const blocks = buffer.split('\n\n');
            buffer = blocks.pop() ?? '';
            for (const block of blocks) {
                for (const line of block.split('\n')) {
                    if (!line.startsWith('data:')) continue;
                    const payload = line.slice(5).trim();
                    if (!payload || payload === '[DONE]') continue;
                    try {
                        const event = JSON.parse(payload);
                        if (event.type === 'text_delta' && event.delta) {
                            fullText += event.delta;
                            bubble.innerHTML = renderMarkdown(fullText);
                            body.scrollTo({ top: body.scrollHeight });
                        }
                    } catch {
                        // Ignorar eventos no JSON.
                    }
                }
            }
        };

        while (true) {
            const { done, value } = await reader.read();
            if (done) break;
            buffer += decoder.decode(value, { stream: true });
            flush();
        }
        buffer += decoder.decode();
        flush();

        if (!bubble.textContent.trim()) {
            bubble.textContent = 'No recibí respuesta. Inténtalo de nuevo.';
        }
    } catch (error) {
        setTyping(false);
        addMessage('assistant', 'Lo siento, no pude procesar tu mensaje ahora. Inténtalo de nuevo en unos segundos.');
    }
};

launcher?.addEventListener('click', () => setOpen(!isOpen()));
widget?.addEventListener('click', (event) => {
    if (event.target.closest('[data-chat-close]')) setOpen(false);
});

form?.addEventListener('submit', (event) => {
    event.preventDefault();
    send(input.value);
});

input?.addEventListener('keydown', (event) => {
    if (event.key === 'Enter' && !event.shiftKey) {
        event.preventDefault();
        send(input.value);
    }
});

input?.addEventListener('input', () => {
    input.style.height = 'auto';
    input.style.height = `${Math.min(input.scrollHeight, 120)}px`;
});

quickWrap?.querySelectorAll('[data-quick]').forEach((button) => {
    button.addEventListener('click', () => send(button.dataset.quick));
});
