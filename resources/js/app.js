const modal = document.querySelector('#booking-modal');
const navigation = document.querySelector('header nav');
const menuButton = document.querySelector('.mobile-menu');
const bookingForm = document.querySelector('#booking-form');
const bookingSteps = [...document.querySelectorAll('.booking-step')];
const progressSteps = [...document.querySelectorAll('.booking-progress span')];
const dateInput = bookingForm?.querySelector('[name="preferred_date"]');
const timeInputs = [...(bookingForm?.querySelectorAll('[name="preferred_time"]') ?? [])];
const availabilityMessage = bookingForm?.querySelector('.availability-message');
let currentBookingStep = 1;
let availabilityReady = false;
let availabilityRequest;

const openModal = () => {
    modal?.classList.add('is-open');
    modal?.setAttribute('aria-hidden', 'false');
    document.body.style.overflow = 'hidden';
};

const closeModal = () => {
    modal?.classList.remove('is-open');
    modal?.setAttribute('aria-hidden', 'true');
    document.body.style.overflow = '';
};

const showBookingStep = (step) => {
    currentBookingStep = Math.min(Math.max(step, 1), bookingSteps.length);
    bookingSteps.forEach((panel) => panel.classList.toggle('active', Number(panel.dataset.step) === currentBookingStep));
    progressSteps.forEach((item, index) => {
        item.classList.toggle('active', index < currentBookingStep);
        item.setAttribute('aria-current', index + 1 === currentBookingStep ? 'step' : 'false');
    });
};

const setAvailabilityMessage = (message = '', type = '') => {
    if (!availabilityMessage) return;
    availabilityMessage.textContent = message;
    availabilityMessage.className = `availability-message ${type}`;
};

const loadAvailability = async () => {
    const date = dateInput?.value;
    availabilityReady = false;
    timeInputs.forEach((input) => {
        input.checked = false;
        input.disabled = !date;
        input.closest('label')?.classList.toggle('is-unavailable', !date);
    });
    dateInput?.setCustomValidity('');
    setAvailabilityMessage(date ? 'Comprobando horarios disponibles...' : 'Selecciona una fecha para ver los horarios.');
    if (!date) return;

    const selectedDate = new Date(`${date}T00:00:00`);
    if (Number.isNaN(selectedDate.getTime()) || selectedDate.getDay() === 0 || selectedDate.getDay() === 6) {
        dateInput?.setCustomValidity('Las visitas guiadas están disponibles de lunes a viernes.');
        setAvailabilityMessage('Las visitas guiadas están disponibles de lunes a viernes.', 'is-error');
        return;
    }

    availabilityRequest?.abort();
    availabilityRequest = new AbortController();
    try {
        const response = await fetch(`${bookingForm.dataset.availabilityUrl}?date=${encodeURIComponent(date)}`, {
            headers: { Accept: 'application/json' },
            signal: availabilityRequest.signal,
        });
        const data = await response.json();
        if (!response.ok) throw new Error(data.message ?? 'No pudimos consultar la disponibilidad.');
        data.slots.forEach(({ time, available }) => {
            const input = timeInputs.find((item) => item.value === time);
            if (!input) return;
            input.disabled = !available;
            input.closest('label')?.classList.toggle('is-unavailable', !available);
        });
        availabilityReady = true;
        const availableCount = data.slots.filter((slot) => slot.available).length;
        setAvailabilityMessage(availableCount ? `${availableCount} horarios disponibles para esta fecha.` : 'No quedan horarios para esta fecha. Elige otro día.', availableCount ? 'is-success' : 'is-error');
    } catch (error) {
        if (error.name === 'AbortError') return;
        setAvailabilityMessage('No pudimos comprobar los horarios. Inténtalo de nuevo.', 'is-error');
    }
};

const updateReview = () => {
    const interest = bookingForm?.querySelector('[name="interest"]:checked')?.value ?? '—';
    const date = dateInput?.value ? new Intl.DateTimeFormat('es-ES', { day: '2-digit', month: '2-digit', year: 'numeric' }).format(new Date(`${dateInput.value}T00:00:00`)) : '—';
    const time = bookingForm?.querySelector('[name="preferred_time"]:checked')?.value ?? '—';
    const email = bookingForm?.querySelector('[name="email"]')?.value ?? '—';
    bookingForm?.querySelector('[data-review="interest"]')?.replaceChildren(document.createTextNode(interest));
    bookingForm?.querySelector('[data-review="date"]')?.replaceChildren(document.createTextNode(date));
    bookingForm?.querySelector('[data-review="time"]')?.replaceChildren(document.createTextNode(time));
    bookingForm?.querySelector('[data-review="contact"]')?.replaceChildren(document.createTextNode(email || '—'));
};

const chatLauncher = document.querySelector('.chat-launcher');
const chatWidget = document.querySelector('.chat-widget');
const toggleMenu = (force) => {
    const isOpen = typeof force === 'boolean' ? force : !navigation?.classList.contains('is-open');
    navigation?.classList.toggle('is-open', isOpen);
    menuButton?.setAttribute('aria-expanded', String(isOpen));
    document.body.style.overflow = isOpen ? 'hidden' : '';
    document.body.classList.toggle('menu-open', isOpen);
    if (isOpen) chatWidget?.classList.remove('is-open');
};
menuButton?.addEventListener('click', () => toggleMenu());
navigation?.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => {
    navigation.querySelectorAll('a').forEach((item) => item.removeAttribute('aria-current'));
    link.setAttribute('aria-current', 'true');
    toggleMenu(false);
}));
document.querySelectorAll('.nav-menu-cta [data-open-booking]').forEach((btn) => btn.addEventListener('click', () => toggleMenu(false)));
document.querySelectorAll('[data-open-booking]').forEach((button) => button.addEventListener('click', openModal));
document.querySelectorAll('[data-close-booking]').forEach((button) => button.addEventListener('click', closeModal));
modal?.addEventListener('click', (event) => { if (event.target === modal) closeModal(); });
document.addEventListener('keydown', (event) => {
    if (event.key === 'Escape') {
        if (navigation?.classList.contains('is-open')) toggleMenu(false);
        closeModal();
    }
});
if (document.querySelector('.success-message, .error-message')) openModal();

const siteFooter = document.querySelector('.site-footer');
if (siteFooter && chatLauncher) {
    const footerObserver = new IntersectionObserver(
        (entries) => {
            const isFooterVisible = entries[0]?.isIntersecting;
            chatLauncher.style.transition = 'transform 0.3s ease, opacity 0.3s ease';
            chatWidget.style.transition = 'transform 0.3s ease, opacity 0.3s ease';
            if (isFooterVisible) {
                chatLauncher.style.transform = 'translateY(120%)';
                chatLauncher.style.opacity = '0';
                chatLauncher.style.pointerEvents = 'none';
                if (chatWidget?.classList.contains('is-open')) {
                    chatWidget.style.transform = 'translateY(16px) scale(0.97)';
                    chatWidget.style.opacity = '0';
                    chatWidget.style.pointerEvents = 'none';
                }
            } else if (!document.body.classList.contains('menu-open')) {
                chatLauncher.style.transform = '';
                chatLauncher.style.opacity = '';
                chatLauncher.style.pointerEvents = '';
                if (chatWidget) {
                    chatWidget.style.transform = '';
                    chatWidget.style.opacity = '';
                    chatWidget.style.pointerEvents = '';
                }
            }
        },
        { threshold: 0.1, rootMargin: '0px 0px -20px 0px' },
    );
    footerObserver.observe(siteFooter);
}

const navbar = document.querySelector('.main-navbar');
let ticking = false;
window.addEventListener(
    'scroll',
    () => {
        if (ticking) return;
        ticking = true;
        requestAnimationFrame(() => {
            navbar?.classList.toggle('scrolled', window.scrollY > 24);
            ticking = false;
        });
    },
    { passive: true },
);

const revealEls = document.querySelectorAll(
    '.after-hero-heading, .after-hero-grid article, .after-hero-cta, .program-detail .detail-copy, .detail-visual, .studio-section > div, .community-section > div, .community-collage, .products-heading, .product-cards article, .suites-section > div, .suites-section img, .stories-heading, .stories-list article, .faq-section > div, .faq-list details, .site-footer > div',
);
revealEls.forEach((el, i) => {
    el.classList.add('reveal');
    if (i % 3 === 1) el.classList.add('reveal-delay-1');
    if (i % 3 === 2) el.classList.add('reveal-delay-2');
});
const revealObserver = new IntersectionObserver(
    (entries) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                revealObserver.unobserve(entry.target);
            }
        });
    },
    { threshold: 0.14, rootMargin: '0px 0px -40px 0px' },
);
revealEls.forEach((el) => revealObserver.observe(el));

if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
    revealEls.forEach((el) => el.classList.add('visible'));
}

dateInput?.addEventListener('change', loadAvailability);
document.querySelectorAll('.step-next').forEach((button) => button.addEventListener('click', async () => {
    const panel = bookingSteps.find((item) => Number(item.dataset.step) === currentBookingStep);
    if (currentBookingStep === 2 && !availabilityReady) {
        await loadAvailability();
        if (!availabilityReady) return;
    }
    if (currentBookingStep === 2 && !bookingForm.querySelector('[name="preferred_time"]:checked')) {
        setAvailabilityMessage('Selecciona uno de los horarios disponibles.', 'is-error');
        return;
    }
    const fields = [...panel.querySelectorAll('input, select, textarea')];
    if (!fields.every((field) => field.checkValidity())) {
        panel.querySelector(':invalid')?.reportValidity();
        return;
    }
    if (currentBookingStep === 3) updateReview();
    showBookingStep(currentBookingStep + 1);
}));
document.querySelectorAll('.step-back').forEach((button) => button.addEventListener('click', () => showBookingStep(currentBookingStep - 1)));
bookingForm?.addEventListener('submit', (event) => {
    if (!bookingForm.checkValidity() || !availabilityReady || !bookingForm.querySelector('[name="preferred_time"]:checked')) {
        event.preventDefault();
        bookingForm.querySelector(':invalid')?.reportValidity();
    }
});
