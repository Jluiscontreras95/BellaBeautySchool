<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="BELA Beauty Studio: academia, studio y comunidad para tu futuro profesional.">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>BELA Beauty Studio</title>
    @vite(['resources/css/app.css','resources/js/app.js','resources/js/chat.js'])
</head>
<body>
    <div class="landing-wrapper" id="inicio">
        <video class="hero-video" autoplay muted loop playsinline preload="auto"
            poster="{{ asset('media/bela-poster.jpg') }}" aria-hidden="true">
            <source src="{{ asset('media/bela-ecosystem.mp4') }}" type="video/mp4">
        </video>
        <header class="main-navbar">
            <a class="logo" href="#inicio" aria-label="BELA Beauty Studio">
                <img class="logo-img" src="{{ asset('media/bela-logo.png') }}" alt="BELA Beauty Studio">
            </a>
            <nav aria-label="Navegación principal">
                <ul>
                    <li><a href="#academia"><small>01</small><span>Academia</span></a></li>
                    <li><a href="#studio"><small>02</small><span>Studio</span></a></li>
                    <li><a href="#comunidad"><small>03</small><span>Comunidad</span></a></li>
                    <li><a href="#productos"><small>04</small><span>Productos</span></a></li>
                    <li><a href="#suites"><small>05</small><span>Suites</span></a></li>
                </ul>
                <div class="nav-menu-cta">
                    <button class="btn-primary" data-open-booking>RESERVA TU VISITA →</button>
                    <span class="nav-menu-status"><i></i> AGENDA ABIERTA</span>
                </div>
            </nav>
            <div class="nav-actions"><span class="nav-status"><i></i> AGENDA ABIERTA</span><button class="btn-descubre"
                    data-open-booking>RESERVA TU VISITA <b>↗</b></button><button class="mobile-menu"
                    aria-label="Abrir menú" aria-expanded="false"><span></span><span></span></button></div>
        </header>

        <div class="hero-left">
            <h1>MÁS QUE UNA<br>ACADEMIA.<br><span>UN ECOSISTEMA<br>PARA TU FUTURO.</span></h1>
            <p>Aprende, certifícate, trabaja,<br>emprende y crece dentro de BELA.</p>
            <div class="cta-group"><a href="#academia" class="btn-primary">EXPLORA EL ECOSISTEMA</a><button
                    class="btn-secondary" data-open-booking>CONOCE EL PROGRAMA</button></div>
            <div class="program-card"><img
                    src="https://images.unsplash.com/photo-1604654894610-df63bc536371?auto=format&fit=crop&w=300&q=80"
                    alt="Manicura">
                <div class="program-info"><span class="program-subtitle">Tu Primer Paso</span><b
                        class="program-title">PROGRAMA DE<br>MANICURISTA</b><span class="badge-hours">600 HORAS</span>
                </div>
            </div>
        </div>
    </div>
    <div class="career-flow">
        <div class="career-step"><img class="career-img"
                src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=150&q=80"
                alt="Alumna"><b class="career-title">ALUMNA</b></div><i>➜</i>
        <div class="career-step"><img class="career-img"
                src="https://images.unsplash.com/photo-1580894732444-8ecded7900cd?auto=format&fit=crop&w=150&q=80"
                alt="Profesional"><b class="career-title">PROFESIONAL</b></div><i>➜</i>
        <div class="career-step"><img class="career-img"
                src="https://images.unsplash.com/photo-1573497019940-1c28c88b4f3e?auto=format&fit=crop&w=150&q=80"
                alt="Emprendedora"><b class="career-title">EMPRENDEDORA</b></div><i>➜</i>
        <div class="career-step"><img class="career-img"
                src="https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91?auto=format&fit=crop&w=150&q=80"
                alt="Propietaria"><b class="career-title">PROPIETARIA</b></div>
    </div>

    <section class="after-hero" id="academia">
        <div class="after-hero-heading"><span>EL UNIVERSO BELA</span>
            <h2>Un lugar para<br><em>crecer con intención.</em></h2>
            <p>La imagen que ves es solo el comienzo. Descubre todo lo que puedes construir dentro de nuestra comunidad.
            </p>
        </div>
        <div class="after-hero-grid">
            <article id="studio-card"><span>01</span>
                <h3>Academia</h3>
                <p>Formación práctica, certificada y pensada para abrirte camino en la industria de la belleza.</p><a
                    href="#programa">Ver formación →</a>
            </article>
            <article id="comunidad-card"><span>02</span>
                <h3>Comunidad</h3>
                <p>Una red de mujeres que te acompaña antes, durante y después de tu transformación.</p><a
                    href="#comunidad">Conocer Bella →</a>
            </article>
            <article id="productos-card"><span>03</span>
                <h3>Productos</h3>
                <p>Herramientas y productos profesionales seleccionados para elevar cada servicio.</p><a
                    href="#productos">Ver productos →</a>
            </article>
            <article id="suites-card"><span>04</span>
                <h3>Beauty Suites</h3>
                <p>Espacios listos para que conviertas tu talento en una experiencia inolvidable.</p><a
                    href="#suites">Ver suites →</a>
            </article>
        </div>
        <div class="after-hero-cta" id="reserva">
            <p>¿Lista para dar el primer paso?</p><button class="btn-primary" data-open-booking>RESERVA TU VISITA GUIADA
                →</button>
        </div>
    </section>

    <section class="detail-section program-detail" id="programa">
        <div class="detail-copy"><span class="section-kicker">01 / FORMACIÓN PROFESIONAL</span>
            <h2>Domina la técnica.<br><em>Construye tu futuro.</em></h2>
            <p>Un programa de 600 horas para convertir tu pasión en una profesión rentable, creativa y tuya.</p>
            <div class="detail-list">
                <div><b>600</b><span>horas presenciales y prácticas</span></div>
                <div><b>12</b><span>módulos de especialización</span></div>
                <div><b>1:1</b><span>acompañamiento personalizado</span></div>
            </div><button class="btn-primary" data-open-booking>QUIERO SABER MÁS →</button>
        </div>
        <div class="detail-visual"><img
                src="https://images.unsplash.com/photo-1604654894610-df63bc536371?auto=format&fit=crop&w=1000&q=85"
                alt="Formación en manicura profesional"><span>APRENDE<br>HACIENDO</span></div>
    </section>
    <section class="studio-section" id="studio">
        <div class="studio-image"><img
                src="https://images.unsplash.com/photo-1560066984-138dadb4c035?auto=format&fit=crop&w=1200&q=85"
                alt="Interior del Studio Bella"></div>
        <div class="studio-copy"><span class="section-kicker">02 / BELLA STUDIO</span>
            <h2>La práctica<br>se vuelve <em>arte.</em></h2>
            <p>Un espacio real para observar, practicar y empezar a trabajar junto a profesionales que viven de lo que
                aman.</p><a class="gold-link" href="#reserva">Conoce nuestros espacios →</a>
        </div>
    </section>
    <section class="community-section" id="comunidad">
        <div><span class="section-kicker">03 / COMUNIDAD BELA</span>
            <h2>Nadie crece<br><em>sola.</em></h2>
            <p>Clases, encuentros y conexiones que siguen mucho después de tu certificación.</p>
            <div class="community-points"><span>✦ Mentorías reales</span><span>✦ Eventos exclusivos</span><span>✦ Red de
                    graduadas</span></div>
        </div>
        <div class="community-collage"><img
                src="https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=700&q=85"
                alt="Comunidad de mujeres Bella"><img
                src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=500&q=85"
                alt="Aprendizaje en comunidad"></div>
    </section>
    <section class="products-section" id="productos">
        <div class="products-heading"><span class="section-kicker">04 / BELA PROFESSIONAL</span>
            <h2>Todo lo que necesitas<br><em>para crear mejor.</em></h2>
        </div>
        <div class="product-cards">
            <article><span>01</span>
                <h3>Herramientas</h3>
                <p>Selección profesional para elevar cada servicio.</p>
            </article>
            <article><span>02</span>
                <h3>Productos</h3>
                <p>Fórmulas y marcas en las que puedes confiar.</p>
            </article>
            <article><span>03</span>
                <h3>Tu kit</h3>
                <p>Empieza tu carrera con lo esencial.</p>
            </article>
        </div>
    </section>
    <section class="suites-section" id="suites">
        <div class="suites-copy"><span class="section-kicker">05 / BEAUTY SUITES</span>
            <h2>Tu nombre.<br><em>Tu espacio.</em></h2>
            <p>Suites profesionales listas para que atiendas, crezcas y construyas una marca con tu propia firma.</p>
            <button class="btn-primary" data-open-booking>AGENDA UN RECORRIDO →</button>
        </div><img src="https://images.unsplash.com/photo-1600948836101-f9ffda59d250?auto=format&fit=crop&w=1200&q=85"
            alt="Beauty suite profesional">
    </section>
    <section class="stories-section" id="historias">
        <div class="stories-heading"><span class="section-kicker">06 / VOCES BELA</span>
            <h2>Ellas ya están<br><em>haciendo historia.</em></h2>
        </div>
        <div class="stories-list">
            <article>
                <div class="story-quote">“BELA me dio la técnica, pero también la confianza para cobrar por mi talento.”
                </div><b>Camila R.</b><span>Graduada 2024 · Studio propio</span>
            </article>
            <article>
                <div class="story-quote">“Encontré una comunidad que celebra mis avances y me reta a seguir.”</div>
                <b>Sofía M.</b><span>Graduada 2023 · Nail artist</span>
            </article>
        </div>
    </section>
    <section class="faq-section">
        <div><span class="section-kicker">07 / PREGUNTAS FRECUENTES</span>
            <h2>Todo claro<br><em>desde el principio.</em></h2>
        </div>
        <div class="faq-list">
            <details>
                <summary>¿Necesito experiencia previa? <span>+</span></summary>
                <p>No. Nuestro programa está diseñado para acompañarte desde cero hasta un nivel profesional.</p>
            </details>
            <details>
                <summary>¿Qué incluye la formación? <span>+</span></summary>
                <p>Incluye técnica, práctica supervisada, materiales de clase, certificación y módulos de
                    emprendimiento.</p>
            </details>
            <details>
                <summary>¿Puedo visitar Bella antes de inscribirme? <span>+</span></summary>
                <p>Por supuesto. Reserva una visita guiada y conoce las aulas, el Studio y nuestra comunidad.</p>
            </details>
        </div>
    </section>
    <footer class="site-footer">
        <div class="footer-brand"><a class="logo" href="#inicio"><img class="logo-img"
                    src="{{ asset('media/bela-logo.png') }}" alt="BELA Beauty Studio"></a>
            <p>Tu talento merece un lugar donde crecer.</p>
        </div>
        <div class="footer-links"><a href="#academia">Academia</a><a href="#programa">Programa</a><a
                href="#comunidad">Comunidad</a><a href="#reserva">Reservar visita</a></div><small>© 2026 BELA Beauty
            Studio · Todos los derechos reservados</small>
    </footer>

    <div class="booking-modal-wrap" id="booking-modal" aria-hidden="true">
        <div class="booking-modal"><button class="modal-close" data-close-booking aria-label="Cerrar">×</button><span
                class="modal-kicker">RESERVA TU VISITA GUIADA</span>
            <h2>Conoce tu próximo<br><em>capítulo en BELA.</em></h2>
            <div class="booking-progress"><span
                    class="active">01</span><i></i><span>02</span><i></i><span>03</span><i></i><span>04</span></div>
            @if($errors->any())<div class="error-message">Revisa los campos indicados y vuelve a intentarlo.</div>@endif
            <form method="POST" action="{{ route('appointments.store') }}" id="booking-form"
                data-availability-url="{{ route('appointments.availability') }}">@csrf
                <div class="booking-step active" data-step="1">
                    <p class="step-title">¿Qué quieres conocer?</p>
                    <div class="choice-grid"><label class="choice-card"><input type="radio" name="interest"
                                value="Programa de Manicurista" required><span><b>Programa profesional</b><small>600
                                    horas de formación</small></span></label><label class="choice-card"><input
                                type="radio" name="interest" value="Cursos intensivos"><span><b>Cursos
                                    intensivos</b><small>Aprende una técnica nueva</small></span></label><label
                            class="choice-card"><input type="radio" name="interest"
                                value="Conocer el Studio"><span><b>Conocer el Studio</b><small>Visita nuestros
                                    espacios</small></span></label></div><button type="button"
                        class="btn-primary step-next">CONTINUAR →</button>
                </div>
                <div class="booking-step" data-step="2">
                    <p class="step-title">Elige cuándo venir</p><label>Fecha preferida<input type="date"
                            name="preferred_date" min="{{ now()->format('Y-m-d') }}"
                            max="{{ now()->addMonths(3)->format('Y-m-d') }}" value="{{ old('preferred_date') }}"
                            required></label>
                    <p class="slot-label">Horarios disponibles</p>
                    <div class="slot-grid"><label><input type="radio" name="preferred_time" value="10:00"
                                required><span>10:00</span></label><label><input type="radio" name="preferred_time"
                                value="12:00"><span>12:00</span></label><label><input type="radio" name="preferred_time"
                                value="16:00"><span>16:00</span></label><label><input type="radio" name="preferred_time"
                                value="18:00"><span>18:00</span></label></div>
                    <p class="availability-message" aria-live="polite"></p>
                    <div class="step-actions"><button type="button" class="step-back">← ATRÁS</button><button
                            type="button" class="btn-primary step-next">CONTINUAR →</button></div>
                </div>
                <div class="booking-step" data-step="3">
                    <p class="step-title">¿Cómo te contactamos?</p>
                    <div class="form-grid"><label>Nombre<input type="text" name="name" value="{{ old('name') }}"
                                required></label><label>Teléfono<input type="tel" name="phone" value="{{ old('phone') }}"
                                required></label></div><label>Email<input type="email" name="email"
                            value="{{ old('email') }}" required></label><label>Cuéntanos algo más <span
                            class="optional">(opcional)</span><textarea name="message" rows="3"
                            placeholder="¿Qué te gustaría saber?"></textarea></label>
                    <div class="step-actions"><button type="button" class="step-back">← ATRÁS</button><button
                            type="button" class="btn-primary step-next">REVISAR DATOS →</button></div>
                </div>
                <div class="booking-step" data-step="4">
                    <p class="step-title">Confirma tu solicitud</p>
                    <div class="booking-review">
                        <div><small>INTERÉS</small><b data-review="interest">—</b></div>
                        <div><small>FECHA Y HORA</small><b><span data-review="date">—</span> · <span
                                    data-review="time">—</span></b></div>
                        <div><small>CONTACTO</small><b data-review="contact">—</b></div>
                    </div><label class="consent"><input type="checkbox" name="consent" value="1" required><span>Acepto
                            que BELA me contacte para confirmar mi visita.</span></label>
                    <div class="step-actions"><button type="button" class="step-back">← ATRÁS</button><button
                            class="btn-primary submit" type="submit">CONFIRMAR VISITA →</button></div>
                </div>
            </form>
        </div>
    </div>

    @include('partials.chat-widget')
</body>
</html>