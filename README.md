Obai Backend 🤖✨

Obai es un asistente digital proactivo, natural y profundamente personalizado. Este backend está construido sobre Laravel para ofrecer alta escalabilidad, tiempo real y personalización avanzada.

Este documento sirve tanto como README como guía técnica inicial para desarrolladores.

🌟 Visión

Obai no es un asistente tradicional; su objetivo es ser un compañero digital proactivo que:

Actúe por iniciativa propia: Interacciones automáticas cuando hay información relevante.

Sea natural: Respuestas humanas, con personalidad y rasgos definidos.

Detecte presencia en múltiples dispositivos: Móvil, PC o tablet.

Mantenga al usuario informado: Clima, noticias y contenido relevante sin solicitud directa.

🏗 Arquitectura General
┌─────────────────┐
│   Frontend App  │
└───────┬─────────┘
        │ WebSocket / HTTP
        ▼
┌─────────────────┐
│   Laravel API   │
│  - Controllers  │
│  - Services     │
│  - Jobs / Queue │
└───────┬─────────┘
        │
        ▼
┌─────────────────┐
│  Gemini 2.5 AI  │
│  - Prompts      │
│  - Responses    │
└─────────────────┘


Componentes clave:

Controllers: Gestionan solicitudes HTTP y WebSocket.

Services: Lógica desacoplada para IA, clima, noticias, rasgos de asistentes.

Jobs / Queue: Mensajes proactivos y tareas programadas.

WebSockets (Reverb): Comunicación en tiempo real con el frontend.

🗄 Base de Datos

Tablas principales:

assistants
Almacena asistentes, rasgos únicos y configuración.

conversations
Registro de conversaciones por asistente.

messages
Mensajes individuales asociados a conversaciones.

devices
Dispositivos activos por usuario para notificaciones en tiempo real.

traits_hash
Controla unicidad de rasgos de asistentes.

⚙️ Endpoints Principales
1️⃣ Asistentes
Método	Endpoint	Descripción
GET	/assistants	Listar asistentes del usuario
POST	/assistants	Crear asistente con rasgos automáticos
GET	/assistants/{id}	Obtener detalles de asistente
2️⃣ Conversaciones
Método	Endpoint	Descripción
GET	/conversations	Listar conversaciones de un asistente
POST	/conversations	Crear conversación manualmente
POST	/messages	Crear mensaje, genera conversación si no existe
GET	/conversations/{id}/messages	Listar mensajes con scroll infinito (before_id)
3️⃣ Mensajes
Método	Endpoint	Descripción
POST	/messages	Crear mensaje en conversación existente o nueva
GET	/messages/{id}	Obtener mensaje específico
🧬 Flujo de conversación

Usuario envía mensaje → /messages.

Backend valida conversation_id:

Si existe → agrega mensaje.

Si no existe → crea nueva conversación y asocia mensaje.

Backend envía evento WebSocket → frontend recibe mensaje en tiempo real.

IA genera respuesta → se almacena y se emite en tiempo real.

Mensajes antiguos cargan incrementalmente con before_id.

🚀 Instalación y Configuración
# Clonar repositorio
git clone https://github.com/tu-usuario/obai-backend.git
cd obai-backend

# Instalar dependencias
composer install
npm install

# Configurar entorno
cp .env.example .env
php artisan key:generate

# Migrar base de datos
php artisan migrate

# Iniciar servidores (Octane + Reverb)
php artisan octane:start --watch
php artisan reverb:start

🔒 Seguridad

Autenticación: Laravel Sanctum para APIs.

Autorización: Validación de ownership de conversaciones y asistentes.

Rate limiting: Control de mensajes por minuto y límites por usuario.

📊 Observabilidad

Logs de requests a Gemini y WebSockets.

Métricas: número de conversaciones, mensajes enviados, disparos proactivos.

Manejo de errores y alertas de Jobs fallidos.

🧪 Buenas Prácticas

Usar cursorPaginate() para mensajes largos (scroll infinito).

Registrar traits_hash para evitar duplicados de asistentes.

Mantener prompts de IA estructurados y versionados.

Cachear configuración de asistentes y resultados externos (clima, noticias).

🤝 Contribuciones

Haz un fork del proyecto.

Crea una rama: git checkout -b feature/nueva-funcionalidad.

Realiza tus cambios y haz commit: git commit -m "Añade nueva funcionalidad".

Haz push: git push origin feature/nueva-funcionalidad.

Abre un Pull Request para revisión.

📄 Licencia

MIT License