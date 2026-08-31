<?php

namespace Database\Seeders;

use App\Models\KnowledgeEntry;
use App\Services\KnowledgeBase;
use Illuminate\Database\Seeder;

class KnowledgeSeeder extends Seeder
{
    public function run(): void
    {
        $entries = [
            ['academia', '¿Qué es BELA Beauty Studio?', 'BELA Beauty Studio es una academia y comunidad de belleza que combina formación profesional, un studio real de práctica y una red de mujeres que acompañan tu crecimiento. Aprende, certifícate, trabaja, emprende y crece dentro de BELA.'],
            ['programa', 'Programa de Manicurista', 'El Programa de Manicurista es una formación profesional de 600 horas presenciales y prácticas, organizada en 12 módulos de especialización, con acompañamiento personalizado 1:1. Está pensado para llevarte desde cero hasta un nivel profesional.'],
            ['programa', '¿Qué incluye la formación?', 'La formación incluye técnica, práctica supervisada, materiales de clase, certificación y módulos de emprendimiento.'],
            ['programa', '¿Necesito experiencia previa?', 'No. El programa está diseñado para acompañarte desde cero hasta un nivel profesional.'],
            ['academia', 'Metodología de la academia', 'La academia se basa en el aprendizaje práctico: aprende haciendo, con práctica supervisada y acompañamiento personalizado.'],
            ['studio', 'BELLA Studio', 'El Studio es un espacio real donde puedes observar, practicar y empezar a trabajar junto a profesionales que viven de lo que aman.'],
            ['comunidad', 'Comunidad BELA', 'La comunidad de BELA incluye mentorías reales, eventos exclusivos y una red de graduadas que te acompaña antes, durante y después de tu transformación.'],
            ['productos', 'BELLA Professional (productos)', 'BELA ofrece herramientas y productos profesionales seleccionados para elevar cada servicio, además de un kit para empezar tu carrera con lo esencial.'],
            ['suites', 'Beauty Suites', 'Las Beauty Suites son espacios profesionales listos para que atiendas, crezcas y construyas una marca con tu propia firma.'],
            ['visitas', 'Visitas guiadas', 'Puedes reservar una visita guiada para conocer las aulas, el Studio y la comunidad. Las visitas se realizan de lunes a viernes y tienen una duración de dos horas.'],
            ['visitas', 'Horarios de visita disponibles', 'Los horarios disponibles para visitas guiadas son: 10:00, 12:00, 16:00 y 18:00, de lunes a viernes. Cada visita dura dos horas.'],
            ['visitas', 'Cómo agendar una visita', 'Puedes agendar tu visita guiada desde el botón "Reserva tu visita" de la web o conversando con la asistente virtual. Solo necesitas tu nombre, correo, teléfono y la fecha y hora que prefieras.'],
            ['faq', '¿Puedo visitar BELA antes de inscribirme?', 'Sí. Reserva una visita guiada y conoce las aulas, el Studio y la comunidad.'],
            ['contacto', 'Contacto de BELA', 'Para más información escribe a hola@bela.beauty o reserva una visita guiada. Nuestro equipo te contactará para confirmar la disponibilidad.'],
        ];

        foreach ($entries as [$category, $title, $content]) {
            KnowledgeEntry::updateOrCreate(
                ['title' => $title],
                [
                    'category' => $category,
                    'content' => $content,
                    'active' => true,
                ],
            );
        }

        if (! blank(env('OPENAI_API_KEY'))) {
            $indexed = app(KnowledgeBase::class)->indexAll();
            $this->command?->info("Se indexaron {$indexed} entradas de conocimiento.");
        } else {
            $this->command?->warn('OPENAI_API_KEY no está configurada. Las entradas se crearon sin embeddings; ejecuta "php artisan bela:index-knowledge" cuando agregues la clave.');
        }
    }
}
