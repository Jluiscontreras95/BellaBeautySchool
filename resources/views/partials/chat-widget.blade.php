<button class="chat-launcher" data-chat-toggle aria-label="Abrir chat de Bela" aria-expanded="false">
    <span class="chat-launcher-icon" aria-hidden="true">B</span>
    <span class="chat-launcher-label">Chatea con Bela</span>
</button>

<div class="chat-widget" id="chat-widget" aria-hidden="true" role="dialog" aria-label="Asistente virtual Bela">
    <header class="chat-header">
        <span class="chat-header-avatar" aria-hidden="true">B</span>
        <div class="chat-header-info">
            <b>Bela</b>
            <span>Asistente de BELA · en línea</span>
        </div>
        <button class="chat-close" data-chat-close aria-label="Cerrar chat">×</button>
    </header>

    <div class="chat-body" id="chat-body" aria-live="polite">
        <div class="chat-message assistant">
            <div class="chat-bubble">¡Hola! Soy <strong>Bela</strong>, tu asistente. Puedo contarte sobre nuestros programas, horarios y ayudarte a reservar tu visita guiada. ¿En qué te ayudo?</div>
        </div>
    </div>

    <div class="chat-quick" id="chat-quick">
        <button type="button" data-quick="¿Qué incluye el Programa de Manicurista?">Programa de Manicurista</button>
        <button type="button" data-quick="¿Qué horarios hay para visitas?">Horarios de visita</button>
        <button type="button" data-quick="Quiero reservar una visita guiada">Reservar visita</button>
    </div>

    <form class="chat-form" id="chat-form" autocomplete="off">
        <textarea id="chat-input" rows="1" placeholder="Escribe tu mensaje…" aria-label="Mensaje"></textarea>
        <button type="submit" aria-label="Enviar mensaje">
            <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path d="M3 11l18-8-8 18-2-8-8-2z" fill="currentColor"/></svg>
        </button>
    </form>
</div>
