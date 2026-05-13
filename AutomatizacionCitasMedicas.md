# Plan Maestro de Desarrollo: AuraSpa (Sistema de Gestión para Estética y Centro de Belleza)

## Visión Estratégica
El proyecto **AuraSpa** es un sistema empresarial integral diseñado exclusivamente para la gestión y automatización de centros de estética, spas y salones de belleza. El sistema no solo administrará el acceso de diferentes tipos de usuarios (Admin, Especialistas/Cosmetólogas, Clientes), sino que también será altamente proactivo: enviará notificaciones de confirmación por correo electrónico, generará tickets de servicio en PDF y automatizará recordatorios precisos de citas mediante WhatsApp **un día antes** de la reserva (vía Cron Jobs) para reducir el ausentismo. Todo desarrollado aplicando patrones de diseño, principios de arquitectura limpia y una estricta política de control de versiones.

## Tecnologías Principales (Stack Recomendado)
*   **Backend:** Laravel (PHP) / Node.js (NestJS o Express)
*   **Base de Datos:** MySQL / PostgreSQL
*   **Generación de Documentos:** DomPDF / Puppeteer
*   **Comunicaciones:** Mailtrap (SMTP para correos), UltraMsg o Twilio (API de WhatsApp)
*   **Automatización:** Task Scheduling (Cron Jobs / Node-cron)
*   **Control de Versiones:** Git & GitHub

---

## Fases de Desarrollo y Planificación

### Fase 1: Fundamentos y Arquitectura Base
**Objetivo:** Establecer la infraestructura inicial del proyecto, configurando el entorno de desarrollo y las herramientas base.
*   **Funcionalidades a desarrollar:** Creación del proyecto, configuración de variables de entorno, conexión a la base de datos, y configuración del repositorio remoto.
*   **Tecnologías:** CLI del framework elegido, Git.
*   **Posible nombre del commit:** `init: configuracion inicial del proyecto, variables de entorno y estructura base`
*   **Resultado esperado:** Repositorio en GitHub creado y un proyecto en blanco ejecutándose localmente con conexión exitosa a la BD.
*   **Riesgos o dependencias:** Conflictos de versiones en el entorno local (PHP/Node) o problemas de puertos.

### Fase 2: Modelado de Datos y Relaciones
**Objetivo:** Trasladar el Diseño Entidad-Relación (DER) a la base de datos real enfocado en el sector de la belleza.
*   **Funcionalidades a desarrollar:** Creación de migraciones, modelos y relaciones (Clientes, Especialistas/Staff, Servicios, Citas). Implementación obligatoria de *Soft Deletes* para prevenir la pérdida de registros históricos de transacciones e ingresos.
*   **Tecnologías:** ORM del framework (Eloquent / Prisma / TypeORM).
*   **Posible nombre del commit:** `feat: implementacion de migraciones, modelos de estetica (clientes, staff, servicios) y relaciones con soft deletes`
*   **Resultado esperado:** Base de datos estructurada, documentada por código y lista para operar.
*   **Riesgos o dependencias:** Errores de integridad referencial si las migraciones no se ejecutan en el orden lógico.

### Fase 3: Seguridad, Autenticación y Autorización
**Objetivo:** Restringir y controlar el acceso al sistema mediante roles bien definidos.
*   **Funcionalidades a desarrollar:** Sistema de Login/Registro, gestión de sesiones o tokens (JWT), y Middlewares para proteger rutas según el rol del usuario (Administrador, Especialista/Cosmetóloga, Cliente).
*   **Tecnologías:** Laravel Sanctum / Passport o JWT.
*   **Posible nombre del commit:** `feat: integracion de sistema de autenticacion y middlewares de control de roles`
*   **Resultado esperado:** Solo usuarios autenticados y con los permisos adecuados pueden acceder al panel o realizar operaciones críticas.
*   **Riesgos o dependencias:** Vulnerabilidades en la expiración de tokens o exposición de endpoints privados.

### Fase 4: Core de Negocio (CRUD Principal y Catálogo de Estética)
**Objetivo:** Desarrollar los módulos centrales de gestión del centro de belleza.
*   **Funcionalidades a desarrollar:** CRUD completo para gestión de Clientes, Staff (Cosmetólogas, Masajistas, Estilistas) y el Catálogo de Servicios (Limpieza Facial, Depilación Láser, Masajes, Manicura, Lifting de Pestañas, etc. especificando precios y **duración estimada en minutos**). Validaciones estrictas de datos de entrada.
*   **Tecnologías:** Controladores, Servicios y Validadores nativos.
*   **Posible nombre del commit:** `feat: desarrollo de modulos de clientes, staff y catalogo de servicios de estetica con validaciones backend`
*   **Resultado esperado:** Interfaz y backend capaces de crear, leer, actualizar y dar de baja (Soft Delete) registros operativos de manera segura.
*   **Riesgos o dependencias:** Errores en el manejo de tipos de datos para precios y cálculo de tiempos en minutos de los servicios.

### Fase 5: Motor de Agendamiento de Citas
**Objetivo:** Implementar la lógica compleja del sistema de reservas en cabinas y estaciones.
*   **Funcionalidades a desarrollar:** CRUD de Citas integrando lógica de negocio (validando superposiciones de horarios, sumando la duración del servicio seleccionado para bloquear correctamente la franja de tiempo de la especialista, disponibilidad en la fecha indicada y estados de la cita).
*   **Tecnologías:** Librerías de manejo de fechas y zonas horarias (Carbon / Date-fns).
*   **Posible nombre del commit:** `feat: implementacion de motor de reservas con calculo de duracion de servicios y validacion de disponibilidad`
*   **Resultado esperado:** Un cliente puede agendar una cita (ej: Limpieza Facial + Masaje = 90 min) asignada a una especialista concreta, bloqueando su agenda durante ese lapso.
*   **Riesgos o dependencias:** Complejidad algorítmica al calcular intervalos de tiempo (`slots`) dinámicos basados en la duración variable de cada tratamiento estético.

### Fase 6: Generación de Comprobantes PDF
**Objetivo:** Proveer tickets digitales formales de los tratamientos prestados.
*   **Funcionalidades a desarrollar:** Generación de PDF para confirmación de la cita y comprobante de pago/servicio. El documento debe poseer cabeceras elegantes del centro de estética, detalle de los tratamientos adquiridos, nombre de la especialista asignada y costos totales.
*   **Tecnologías:** Librerías generadoras de PDF (DomPDF, Snappy, o Puppeteer).
*   **Posible nombre del commit:** `feat: servicio de generacion de tickets de atencion y comprobantes de tratamientos en PDF`
*   **Resultado esperado:** Descarga de un archivo PDF estilizado al confirmar una reserva o finalizar un servicio.
*   **Riesgos o dependencias:** Problemas de carga de assets estáticos (logos o fuentes cursivas elegantes) al renderizar el PDF en el servidor.

### Fase 7: Sistema de Notificaciones Transaccionales (Emails)
**Objetivo:** Brindar confirmación oficial e inmediata al cliente tras agendar su cita.
*   **Funcionalidades a desarrollar:** Envío automático de correo electrónico al momento de confirmar la cita, enviando plantillas HTML y adjuntando el ticket PDF generado en el paso anterior.
*   **Tecnologías:** Servicio de colas (Queues/Workers) y servidor SMTP (Mailtrap para testing).
*   **Posible nombre del commit:** `feat: configuracion de servidor SMTP y dispatch de emails de confirmacion de reserva asincronos`
*   **Resultado esperado:** Recepción inmediata de un correo en el buzón del usuario tras apartar su turno en la estética.
*   **Riesgos o dependencias:** Lentitud en la respuesta HTTP del servidor si el correo se envía de forma síncrona. Obligatorio implementar colas de trabajos.

### Fase 8: Interacción Directa (API de WhatsApp)
**Objetivo:** Preparar la infraestructura de mensajería para reducir el ausentismo (No-shows).
*   **Funcionalidades a desarrollar:** Integración con API externa de WhatsApp. El sistema debe estructurar un mensaje amigable: *"Hola [Nombre], en AuraSpa te recordamos tu cita de [Servicio] con tu especialista [Nombre Especialista] para mañana a las [Hora]. ¡Te esperamos para relajarte!"*
*   **Tecnologías:** Guzzle/Axios interactuando con una API REST externa (UltraMsg, Twilio, etc.).
*   **Posible nombre del commit:** `feat: integracion de API de mensajeria y armado de plantillas para notificaciones via WhatsApp`
*   **Resultado esperado:** El backend es capaz de enviar mensajes estructurados vía HTTP a los teléfonos celulares de los clientes.
*   **Riesgos o dependencias:** Fallos al validar y formatear los números de teléfono desde la base de datos (Ej: faltante de código de país).

### Fase 9: Automatización de Recordatorios (Task Scheduling)
**Objetivo:** El sistema debe operar de forma autónoma enviando alertas preventivas en el momento exacto.
*   **Funcionalidades a desarrollar:** Implementación de un Cron Job que se ejecute a diario para buscar en la base de datos exclusivamente las citas programadas para el **día siguiente** y disparar masivamente las notificaciones vía WhatsApp desarrolladas en la fase anterior.
*   **Tecnologías:** Task Scheduling nativo del framework vinculado al Cron de Linux/Windows.
*   **Posible nombre del commit:** `feat: programacion de cron jobs para disparo automatico de recordatorios de citas por WhatsApp un dia antes`
*   **Resultado esperado:** Todos los días a una hora programada (ej: 10:00 AM), el sistema notifica automáticamente a los clientes del día siguiente sin necesidad de intervención manual.
*   **Riesgos o dependencias:** Configuraciones del entorno necesarias para que el Cron se inicie de forma regular.

### Fase 10: Pulido, Entregables y Documentación Final
**Objetivo:** Cerrar el ciclo de desarrollo garantizando calidad y un despliegue a prueba de errores.
*   **Funcionalidades a desarrollar:** Redacción meticulosa del archivo README.md, adjuntando el diagrama DER, instrucciones de clonación, y provisión de credenciales de prueba semilla (Seeders) para facilitar el testeo de los roles (Admin, Especialista, Cliente).
*   **Posibles nombres de commits progresivos:**
    *   `fix: resolucion de deuda tecnica y optimizacion de validaciones en solapamiento de turnos`
    *   `docs: elaboracion del README con DER, seeders e instrucciones completas de instalacion`
*   **Resultado esperado:** Un repositorio profesional, listo para ser clonado, ejecutado y evaluado por terceros.
*   **Riesgos o dependencias:** Olvidar documentar las claves de entorno necesarias para la API de WhatsApp y SMTP.

---

## Distribución Sugerida de Commits (Cronograma 2 Semanas)

Este esquema garantiza que tendrás un historial de control de versiones coherente y enfocado exclusivamente en el modelo de un Centro de Estética (Mínimo requerido: 10 commits).

*   **Día 1:** `init: configuracion inicial del proyecto, base de datos y arquitectura de directorios`
*   **Día 3:** `feat: implementacion de migraciones, modelos de estetica (clientes, staff, servicios) con soft deletes`
*   **Día 4:** `feat: integracion de sistema de autenticacion y middlewares de control de roles`
*   **Día 5:** `feat: desarrollo de modulos de gestion de clientes, especialistas y catalogo de tratamientos`
*   **Día 7:** `feat: implementacion de motor de reservas con calculo dinamico de tiempo por tratamiento`
*   **Día 9:** `feat: servicio de generacion de tickets y comprobantes de tratamientos en PDF`
*   **Día 10:** `feat: configuracion de SMTP y dispatch de emails de confirmacion asincronos (colas)`
*   **Día 11:** `feat: integracion de API REST para armado y envio de recordatorios por WhatsApp`
*   **Día 12:** `feat: programacion de cron jobs para envio de recordatorios de spa un dia antes de la cita`
*   **Día 13:** `fix: resolucion de bugs menores en horarios y refinamiento de interfaces`
*   **Día 14:** `docs: elaboracion del README maestro con diagrama DER, seeders e instrucciones de despliegue`
