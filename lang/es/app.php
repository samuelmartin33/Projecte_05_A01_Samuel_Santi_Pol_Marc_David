<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Navegación principal
    |--------------------------------------------------------------------------
    */
    'nav' => [
        'explorar'       => 'Explorar',
        'bolsa'          => 'Bolsa de Trabajo',
        'social'         => 'Social',
        'panel'          => 'Panel',
        'candidaturas'   => 'Candidaturas',
        'entrar'         => 'Entrar',
        'registro'       => 'Registro',
    ],

    /*
    |--------------------------------------------------------------------------
    | Menú de usuario (dropdown)
    |--------------------------------------------------------------------------
    */
    'usuario' => [
        'mi_perfil'      => 'Mi perfil',
        'panel_empresa'  => 'Panel Empresa',
        'curriculos'     => 'Revisar Currículums',
        'mis_entradas'   => 'Mis entradas',
        'amigos'         => 'Amigos',
        'panel_admin'    => 'Panel Admin',
        'cerrar_sesion'  => 'Cerrar sesión',
    ],

    /*
    |--------------------------------------------------------------------------
    | Selector de idioma
    |--------------------------------------------------------------------------
    */
    'idioma' => [
        'es'    => 'ES',
        'en'    => 'EN',
        'label' => 'Idioma',
    ],

    /*
    |--------------------------------------------------------------------------
    | Pie de página
    |--------------------------------------------------------------------------
    */
    'footer' => [
        'privacidad' => 'Privacidad',
        'contacto'   => 'Contacto',
        'tagline'    => 'Plataforma de eventos para jóvenes',
    ],

    /*
    |--------------------------------------------------------------------------
    | Autenticación
    |--------------------------------------------------------------------------
    */
    'auth' => [
        'login_titulo'       => 'Bienvenido de nuevo',
        'login_subtitulo'    => 'Accede a tu cuenta para continuar',
        'login_btn'          => 'Iniciar sesión',
        'login_sin_cuenta'   => '¿No tienes cuenta?',
        'login_registrate'   => 'Regístrate',

        'register_titulo'    => 'Crea tu cuenta',
        'register_subtitulo' => 'Únete a VIBEZ y empieza a vibrar',
        'register_btn'       => 'Crear cuenta',
        'register_ya_cuenta' => '¿Ya tienes cuenta?',
        'register_login'     => 'Inicia sesión',

        'email'              => 'Correo electrónico',
        'password'           => 'Contraseña',
        'password_confirm'   => 'Confirmar contraseña',
        'nombre'             => 'Nombre',
        'apellido1'          => 'Primer apellido',
        'apellido2'          => 'Segundo apellido',
        'fecha_nacimiento'   => 'Fecha de nacimiento',
        'telefono'           => 'Teléfono',
        'tipo_cuenta'        => 'Tipo de cuenta',
        'tipo_cliente'       => 'Cliente',
        'tipo_empresa'       => 'Empresa',

        'mostrar_password'   => 'Mostrar contraseña',
        'ocultar_password'   => 'Ocultar contraseña',
        'login_page_title'   => 'Iniciar sesión — VIBEZ',

        'js_email_oblig'     => 'El email es obligatorio',
        'js_email_invalido'  => 'Introduce un email válido',
        'js_pass_oblig'      => 'La contraseña es obligatoria',
        'js_pass_corta'      => 'Mínimo 8 caracteres',
        'js_sesion_ok'       => '¡Sesión iniciada!',
        'js_credenciales'    => 'Credenciales incorrectas. Inténtalo de nuevo.',
        'js_error_conexion'  => 'Error de conexión. Verifica tu red e inténtalo de nuevo.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Página de inicio (welcome)
    |--------------------------------------------------------------------------
    */
    'welcome' => [
        'titulo'        => 'Descubre tu próximo evento',
        'hero_titulo'   => 'Descubre tu próximo',
        'hero_span'     => 'evento',
        'subtitulo'     => 'La plataforma de eventos para jóvenes. Descubre, crea, compra entradas y conecta con tu escena.',
        'btn_registro'  => 'Regístrate gratis',
        'pill_entradas' => '🎟️ Entradas con QR',
        'pill_eventos'  => '🎉 Crea eventos',
        'pill_cupones'  => '🏷️ Cupones',
        'pill_bolsa'    => '💼 Bolsa de trabajo',
        'pill_social'   => '👥 Social',
        'copyright'     => '© :year VIBEZ — Todos los derechos reservados.',
        'btn_admin'     => 'Panel de Admin',
        'btn_cerrar'    => 'Cerrar sesión',
        'btn_entrar'    => 'Iniciar sesión',
    ],

    /*
    |--------------------------------------------------------------------------
    | Explorar eventos (home)
    |--------------------------------------------------------------------------
    */
    'home' => [
        'badge'                 => 'La plataforma de la escena joven',
        'titulo'                => 'Tu próxima',
        'titulo_span'           => 'aventura empieza aquí',
        'subtitulo'             => 'Eventos, conciertos, festivales y trabajo — todo lo que vive tu escena, en un solo lugar.',
        'filtro_categoria'      => 'Categoría',
        'filtro_ubicacion'      => 'Ubicación',
        'filtro_favoritos'      => 'Favoritos',
        'filtro_todas'          => 'Todas',
        'filtro_todas_ciudades' => 'Todas las ciudades',
        'filtro_solo_favoritos' => 'Solo favoritos',
        'filtro_limpiar'        => 'Limpiar',
        'sin_resultados_titulo' => 'Sin resultados para estos filtros',
        'sin_resultados_sub'    => 'Prueba a cambiar la categoría o la ciudad',
        'ver_todo'              => 'Ver todo',
        'seccion_eventos'       => 'Eventos',
    ],

    /*
    |--------------------------------------------------------------------------
    | Cuenta pendiente de verificación
    |--------------------------------------------------------------------------
    */
    'pendiente' => [
        'titulo'  => 'Cuenta pendiente de verificación',
        'texto'   => 'Tu registro se ha completado correctamente. El administrador revisará tu solicitud y, cuando sea verificada, recibirás un correo electrónico de confirmación.',
        'subtext' => 'Si crees que ha habido un error, contacta con el equipo de VIBEZ.',
        'volver'  => 'Volver al inicio de sesión',
    ],

    /*
    |--------------------------------------------------------------------------
    | Dashboard (index)
    |--------------------------------------------------------------------------
    */
    'dashboard' => [
        'hola'         => 'Hola, :nombre',
        'sesion_activa'=> 'Sesión activa',
        'label_nombre' => 'Nombre',
        'label_email'  => 'Email',
        'label_id'     => 'ID',
        'label_rol'    => 'Rol',
        'rol_admin'    => '⚡ Administrador',
        'btn_logout'   => 'Cerrar sesión',
    ],

    /*
    |--------------------------------------------------------------------------
    | Perfil de usuario
    |--------------------------------------------------------------------------
    */
    'perfil' => [
        'guardar_foto'          => 'Guardar foto',
        'datos_titulo'          => 'Datos personales',
        'datos_sub'             => 'Edita tu información y pulsa "Guardar"',
        'campo_nombre'          => 'Nombre',
        'campo_apellido1'       => 'Primer apellido',
        'campo_apellido2'       => 'Segundo apellido',
        'campo_telefono'        => 'Teléfono',
        'campo_fecha_nac'       => 'Fecha de nacimiento',
        'campo_bio'             => 'Biografía',
        'bio_publica'           => 'Pública',
        'bio_placeholder'       => 'Cuéntanos algo sobre ti...',
        'bio_hint'              => 'Máx. 500 caracteres · Visible para todos tus amigos',
        'btn_guardar'           => 'Guardar cambios',
        'mood_titulo'           => 'Estado de ánimo',
        'mood_sub'              => 'Visible para <strong>todos</strong> (amigos o no) y aparece en la barra de navegación',
        'mood_sin_estado'       => '— Sin estado —',
        'mood_btn'              => 'Guardar estado',
        'amigos_titulo'         => 'Amigos',
        'amigos_buscar_label'   => 'Buscar por nombre o email',
        'amigos_buscar_ph'      => 'Escribe al menos 2 caracteres...',
        'amigos_solicitudes'    => 'Solicitudes recibidas',
        'amigos_lista'          => 'Tus amigos (:count)',
        'amigos_vacio'          => 'Aún no tienes amigos en VIBEZ. ¡Busca a alguien!',
    ],

    /*
    |--------------------------------------------------------------------------
    | Bolsa de trabajo
    |--------------------------------------------------------------------------
    */
    'trabajos' => [
        'badge'                 => '💼 Trabaja en la escena',
        'hero_titulo'           => 'Encuentra tu sitio',
        'hero_titulo_span'      => 'en la escena',
        'hero_sub'              => 'Fotógrafos, técnicos de sonido, relaciones públicas, camareros… Trabaja en los mejores eventos y festivales del país.',
        'stat_ofertas'          => 'Ofertas activas',
        'stat_ciudades'         => 'Ciudades',
        'stat_categorias'       => 'Categorías',
        'filtro_cat'            => 'Categoría',
        'filtro_ciudad'         => 'Ciudad',
        'filtro_todas_cat'      => 'Todas las categorías',
        'filtro_todas_ciudades' => 'Todas las ciudades',
        'filtro_limpiar'        => 'Limpiar',
        'sin_resultados_titulo' => 'No hay ofertas para estos filtros',
        'sin_resultados_sub'    => 'Prueba con otra categoría o ciudad',
        'ver_todas'             => 'Ver todas las ofertas',
        'salario_label'         => 'Salario',
        'btn_ver_oferta'        => 'Ver oferta',
        'cta_titulo'            => '¿Organizas eventos?',
        'cta_sub'               => 'Publica tus ofertas de trabajo y encuentra al equipo perfecto para tus festivales, conciertos y eventos.',
        'cta_btn'               => 'Explorar la plataforma',

        // Detalle oferta
        'det_volver'           => 'Volver a Bolsa de Trabajo',
        'det_badge'            => 'Oferta de trabajo',
        'det_descripcion'      => 'Descripción del puesto',
        'det_requisitos'       => 'Requisitos',
        'det_detalles'         => 'Detalles',
        'det_vacantes'         => 'Vacantes',
        'det_inicio'           => 'Inicio',
        'det_fin_contrato'     => 'Fin contrato',
        'det_salario'          => 'Salario',
        'det_vac_sing'         => 'vacante disponible',
        'det_vac_plur'         => 'vacantes disponibles',
        'det_postular'         => 'Postularme ahora',
        'det_cand_sub'         => 'Tu candidatura se enviará al organizador',
        'mod_como'             => '¿Cómo quieres postularte?',
        'mod_elige'            => 'Elige cómo enviar tu candidatura',
        'mod_form_titulo'      => 'Rellenar formulario',
        'mod_form_sub'         => 'Completa tu CV con tus datos, experiencia y formación',
        'mod_arch_titulo'      => 'Subir archivo',
        'mod_arch_sub'         => 'Sube tu CV en PDF o Word ya preparado',
        'cv_titulo'            => 'Currículum Vitae',
        'cv_subtitulo'         => 'Completa tu perfil para postularte',
        'cv_info_personal'     => 'Información Personal',
        'cv_nombre'            => 'Nombre',
        'cv_apellidos'         => 'Apellidos',
        'cv_email'             => 'Email',
        'cv_telefono'          => 'Teléfono',
        'cv_ciudad'            => 'Ciudad',
        'cv_linkedin'          => 'LinkedIn / Portfolio',
        'cv_perfil'            => 'Perfil Profesional',
        'cv_exp'               => 'Experiencia Laboral',
        'cv_exp_hint'          => 'Añade tus experiencias más relevantes',
        'cv_empresa'           => 'Empresa',
        'cv_cargo'             => 'Cargo / Puesto',
        'cv_desde'             => 'Desde',
        'cv_hasta'             => 'Hasta (vacío = actualidad)',
        'cv_tareas'            => 'Descripción de tareas y logros',
        'cv_add_exp'           => 'Añadir experiencia',
        'cv_formacion'         => 'Formación Académica',
        'cv_formacion_hint'    => 'Estudios, cursos y certificaciones',
        'cv_institucion'       => 'Institución',
        'cv_titulacion'        => 'Titulación / Certificación',
        'cv_ano_inicio'        => 'Año inicio',
        'cv_ano_fin'           => 'Año fin',
        'cv_add_titulacion'    => 'Añadir titulación',
        'cv_habilidades'       => 'Habilidades',
        'cv_habilidades_hint'  => 'Separa las habilidades con comas',
        'cv_idiomas'           => 'Idiomas',
        'cv_carta'             => 'Carta de Presentación',
        'cv_volver'            => 'Volver',
        'cv_enviar'            => 'Enviar candidatura',
        'up_titulo'            => 'Subir CV',
        'up_subtitulo'         => 'Adjunta tu currículum',
        'up_arrastra'          => 'Arrastra tu CV aquí',
        'up_click'             => 'o haz clic para seleccionar un archivo',
        'up_formatos'          => 'Formatos aceptados: PDF, DOC, DOCX · Máximo 5 MB',
        'up_carta'             => 'Carta de presentación',
        'up_carta_opcional'    => '(opcional)',
        'mod_exito_titulo'     => '¡Candidatura enviada!',
        'mod_exito_sub'        => 'Tu candidatura ha sido recibida correctamente. El equipo de selección revisará tu perfil y se pondrá en contacto contigo.',
        'mod_exito_btn'        => 'Perfecto',
        'js_enviando'          => 'Enviando...',
        'js_error_cv'          => 'Error al enviar la candidatura.',
        'js_error_conexion'    => 'Error de conexión. Inténtalo de nuevo.',
        'js_sin_archivo'       => 'Por favor, selecciona un archivo CV antes de enviar.',
        'js_error_archivo'     => 'Error al enviar el CV.',
        'js_formato'           => 'Formato no permitido. Usa PDF, DOC o DOCX.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Detalle de evento
    |--------------------------------------------------------------------------
    */
    'eventos' => [
        'volver'             => 'Volver',
        'comprar_entrada'    => 'Comprar entrada',
        'reservar_gratuita'  => 'Reservar entrada gratuita',
        'reservar_gratis'    => 'Reservar gratis',
        'confirmar_compra'   => 'Confirmar compra',
        'gratuito'           => 'Gratis',
        'descripcion'        => 'Descripción',
        'sobre_evento'       => 'Sobre el evento',
        'sin_descripcion'    => 'No hay descripción disponible.',
        'info_adicional'     => 'Información adicional',
        'aforo_maximo'       => 'Aforo máximo',
        'personas'           => 'personas',
        'disponibles'        => 'Disponibles',
        'entradas_disp'      => 'entradas disponibles',
        'pct_ocupado'        => '% ocupado',
        'finaliza'           => 'Finaliza',
        'organiza'           => 'Organiza',
        'visitar_web'        => 'Visitar web →',
        'galeria'            => 'Galería',
        'precio_label'       => 'Precio',
        'por_persona_iva'    => 'por persona · IVA incluido',
        'en_favoritos'       => 'En favoritos',
        'guardar_favoritos'  => 'Guardar en favoritos',
        'ver_web_oficial'    => 'Ver en web oficial',
        'compra_segura'      => '🔒 Compra segura · Entrada con código QR',
        'ubicacion'          => 'Ubicación',
        'abrir_maps'         => 'Abrir en Google Maps →',
        'sin_ubicacion'      => '📍 Ubicación no disponible',
        'modal_titulo'       => 'Comprar entradas',
        'modal_cantidad'     => 'Cantidad de entradas',
        'modal_total'        => 'Total',
        'modal_seguro'       => '🔒 Transacción segura · Recibirás tu QR al instante',
        'procesando'         => 'Procesando...',
        'redirigiendo'       => '¡Redirigiendo...',
        'error_compra'       => 'Error al procesar la compra.',
        'error_conexion'     => 'Error de conexión. Inténtalo de nuevo.',
        'organizador'        => 'Organizador',
        'cuando'             => 'Cuándo',
        'donde'              => 'Dónde',
        'precio'             => 'Precio',
        'aforo'              => 'Aforo',
        'edad_min'           => 'Edad mínima',
        'sin_aforo'          => 'Sin límite de aforo',
        'agotado'            => 'Entradas agotadas',
        'ver_mapa'           => 'Ver en el mapa',
    ],

    /*
    |--------------------------------------------------------------------------
    | Social / Mensajes
    |--------------------------------------------------------------------------
    */
    'social' => [
        'titulo'            => 'Social',
        'panel_titulo'      => 'Social',
        'tab_mensajes'      => 'Mensajes',
        'tab_amigos'        => 'Amigos',
        'tab_descubrir'     => 'Descubrir',
        'solicitudes'       => 'Solicitudes recibidas',
        'mis_amigos'        => 'Mis amigos',
        'buscar_placeholder'=> 'Buscar personas por nombre o email…',
        'buscar_hint'       => 'Escribe al menos 2 caracteres para buscar',
        'chat_vacio_titulo' => 'Tus mensajes',
        'chat_vacio_sub'    => 'Selecciona una conversación o empieza una nueva desde tu lista de amigos',
        'chat_placeholder'  => 'Escribe un mensaje…',
        'volver'            => 'Volver',
    ],

    /*
    |--------------------------------------------------------------------------
    | Mis entradas
    |--------------------------------------------------------------------------
    */
    'entradas' => [
        'titulo'        => 'Mis entradas',
        'volver'        => 'Volver',
        'vacio_titulo'  => 'Aún no tienes entradas',
        'vacio_sub'     => 'Explora los eventos disponibles y compra tu primera entrada.',
        'explorar_btn'  => 'Explorar eventos',
        'pedido_num'    => 'Pedido #:id',
        'gratis'        => 'Gratis',
        'entrada_sing'  => 'entrada',
        'entrada_plur'  => 'entradas',
        'entrada_label' => 'Entrada #:n',
        'qr_hint'       => 'Presenta este QR en la entrada del evento',
        'ver_qr'        => 'Ver QR',
        'ocultar_qr'    => 'Ocultar QR',
        'conf_titulo'   => 'Confirmación de compra — VIBEZ',
        'conf_exito'    => '¡Compra realizada!',
        'conf_resumen'  => 'Resumen del pedido',
        'conf_total'    => 'Total',
        'conf_tus'      => 'Tus entradas',
        'conf_explorar' => 'Explorar más eventos',
        'conf_perfil'   => 'Ver mi perfil',
    ],

    /*
    |--------------------------------------------------------------------------
    | Panel de empresa
    |--------------------------------------------------------------------------
    */
    'empresa' => [
        'panel_badge'          => 'Panel de empresa',
        'subtitulo'            => 'Gestiona tus eventos, revisa tu equipo y haz crecer tu presencia en VIBEZ.',
        'btn_crear_evento'     => 'Crear evento',

        'stats_eventos'        => 'Eventos',
        'stats_trabajadores'   => 'Trabajadores',
        'stats_ofertas'        => 'Ofertas activas',

        'info_titulo'          => 'Información de la empresa',
        'info_sub'             => 'Datos registrados de tu empresa',
        'info_nombre'          => 'Nombre',
        'info_razon_social'    => 'Razón social',
        'info_nif'             => 'NIF / CIF',
        'info_descripcion'     => 'Descripción',
        'info_web'             => 'Sitio web',
        'info_telefono'        => 'Teléfono',
        'info_direccion'       => 'Dirección',

        'eventos_titulo'       => 'Tus eventos',
        'eliminar'             => 'Eliminar',
        'confirmar_eliminar'   => '¿Seguro que quieres eliminar el evento «:titulo»? Esta acción no se puede deshacer.',
        'empty_eventos_titulo' => 'Aún no tienes eventos',
        'empty_eventos_sub'    => 'Crea tu primer evento y llega a miles de jóvenes en VIBEZ.',

        'equipo_titulo'        => 'Tu equipo',
        'empty_equipo_titulo'  => 'Sin miembros en tu equipo',
        'empty_equipo_sub'     => 'Los organizadores asignados a tu empresa aparecerán aquí.',

        'acciones_titulo'      => 'Acciones rápidas',
        'acciones_sub'         => 'Gestiona tu empresa desde aquí',
        'accion_crear_titulo'  => 'Crear evento',
        'accion_crear_desc'    => 'Publica un nuevo evento y llega a tu público objetivo.',
        'accion_cvs_titulo'    => 'Revisar Currículums',
        'accion_cvs_desc'      => 'Ver todos los candidatos postulados a tus ofertas de trabajo.',

        // Dashboard simple
        'panel_titulo'         => 'Panel de Empresa',
        'panel_bienvenido'     => 'Bienvenido, :nombre',
        'panel_empresa_label'  => 'Empresa:',
        'panel_nif_label'      => 'NIF/CIF:',
        'panel_acciones'       => 'Desde aquí podrás gestionar cupones, patrocinios, ofertas de trabajo y visualizar estadísticas de tu empresa.',
        'panel_logout'         => 'Cerrar sesión',

        // Crear evento
        'crear_badge'          => 'Nuevo evento',
        'crear_titulo'         => 'Crear',
        'crear_titulo_span'    => 'evento',
        'crear_subtitulo'      => 'Rellena los datos de tu evento y publícalo en VIBEZ.',
        'crear_revisar'        => '⚠ Revisa los siguientes campos:',
        'crear_info_basica'    => 'Información básica',
        'crear_tit_evento'     => 'Título del evento',
        'crear_descripcion'    => 'Descripción',
        'crear_categoria'      => 'Categoría',
        'crear_sel_cat'        => 'Selecciona categoría',
        'crear_tipo'           => 'Tipo de evento',
        'crear_presencial'     => 'Presencial',
        'crear_online'         => 'Online',
        'crear_fecha_hora'     => 'Fecha y hora',
        'crear_fecha_inicio'   => 'Fecha de inicio',
        'crear_fecha_fin'      => 'Fecha de fin',
        'crear_fecha_hint'     => 'Opcional. Déjalo vacío si es un evento de un solo momento.',
        'crear_ubicacion'      => 'Ubicación',
        'crear_lugar_nombre'   => 'Nombre del lugar',
        'crear_direccion'      => 'Dirección',
        'crear_latitud'        => 'Latitud',
        'crear_longitud'       => 'Longitud',
        'crear_precio_aforo'   => 'Precio y aforo',
        'crear_es_gratuito'    => 'Este evento es gratuito',
        'crear_precio_base'    => 'Precio base (€)',
        'crear_aforo'          => 'Aforo máximo',
        'crear_aforo_hint'     => 'Opcional. Déjalo vacío si no hay límite.',
        'crear_edad_min'       => 'Edad mínima',
        'crear_edad_hint'      => 'Opcional. Déjalo vacío si no hay restricción.',
        'crear_url_externa'    => 'URL externa',
        'crear_url_hint'       => 'Enlace a la web del evento, si la tiene.',
        'crear_imagen'         => 'Imagen de portada',
        'crear_upload_texto'   => '<strong>Haz clic o arrastra</strong> una imagen aquí',
        'crear_upload_formatos'=> 'JPG, PNG, WebP o GIF · Máx. 5 MB',
        'crear_publicar'       => 'Publicar evento',
        'crear_cancelar'       => 'Cancelar',

        // Ofertas / candidaturas
        'panel_breadcrumb'     => 'Panel de empresa',
        'ofertas_badge'        => 'Revisar Currículums',
        'ofertas_titulo'       => 'Tus ofertas publicadas',
        'ofertas_stat_ofertas' => 'Ofertas',
        'ofertas_stat_cands'   => 'Candidaturas',
        'ofertas_todas'        => 'Todas',
        'ofertas_activas'      => 'Activas',
        'ofertas_cerradas'     => 'Cerradas',
        'ofertas_ordenar'      => 'Ordenar:',
        'ofertas_reciente'     => 'Más reciente',
        'ofertas_candidatos'   => 'Más candidatos',
        'ofertas_alfabetico'   => 'Alfabético',
        'ofertas_limpiar'      => 'Limpiar',
        'ofertas_activa'       => 'Activa',
        'ofertas_cerrada'      => 'Cerrada',
        'ofertas_cand_sing'    => 'candidato',
        'ofertas_cand_plur'    => 'candidatos',
        'ofertas_ver_cvs'      => 'Ver CVs',
        'ofertas_vacio_titulo' => 'No hay ofertas publicadas',
        'ofertas_vacio_sub'    => 'Cuando publiques ofertas de trabajo, aparecerán aquí.',

        // Detalle candidaturas
        'cands_titulo'         => 'Candidatos',
        'cands_activa'         => '● Activa',
        'cands_cerrada'        => '○ Cerrada',
        'cands_total_sing'     => 'candidatura',
        'cands_total_plur'     => 'candidaturas',
        'cands_en_estado'      => 'en este estado',
        'cands_breadcrumb'     => 'Mis ofertas',
        'cands_todos'          => 'Todos',
        'cands_nuevos'         => 'Nuevos',
        'cands_revisados'      => 'Revisados',
        'cands_preselec'       => 'Preseleccionados',
        'cands_rechazados'     => 'Rechazados',
        'cands_ordenar'        => 'Ordenar:',
        'cands_reciente'       => 'Más reciente',
        'cands_nombre'         => 'Nombre A–Z',
        'cands_estado'         => 'Por estado',
        'cands_col_candidato'  => 'Candidato',
        'cands_col_acciones'   => 'Acciones',
        'cands_pdf'            => 'PDF adjunto',
        'cands_nuevo'          => 'Nuevo',
        'cands_revisado'       => 'Revisado',
        'cands_preseleccionado'=> 'Preseleccionado',
        'cands_rechazado'      => 'Rechazado',
        'cands_ver_cv'         => 'Ver CV completo',
        'cands_descargar'      => 'Descargar CV',
        'cands_contactar'      => 'Contactar por email',
        'cands_vacio'          => 'Sin candidaturas',
        'cands_vacio_sub'      => 'Cuando alguien se postule aparecerá aquí.',
        'cands_ver_todas'      => 'Ver todas las candidaturas',
        'cv_titulo'            => 'CV Candidato',
        'cv_postulado'         => 'Postulado el :fecha',
        'cv_descargar_pdf'     => 'Descargar PDF',
        'cv_contactar'         => 'Contactar',
        'cv_cerrar'            => 'Cerrar',
        'cv_info_personal'     => 'Información Personal',
        'cv_perfil'            => 'Perfil Profesional',
        'cv_carta'             => 'Carta de Presentación',
        'cv_habilidades'       => 'Habilidades',
        'cv_idiomas'           => 'Idiomas',
        'cv_email'             => 'Email',
        'cv_telefono'          => 'Teléfono',
        'cv_ciudad'            => 'Ciudad',
        'cv_linkedin'          => 'LinkedIn',
        'cv_solo_archivo'      => 'Este candidato subió su CV como archivo adjunto.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Panel de organizador
    |--------------------------------------------------------------------------
    */
    'organizador' => [
        'titulo'    => 'Panel de Organizador',
        'bienvenido'=> 'Bienvenido, :nombre',
        'organiza'  => 'Organizas eventos para:',
        'acciones'  => 'Desde aquí podrás crear y gestionar eventos, vender entradas y consultar estadísticas.',
        'btn_logout'=> 'Cerrar sesión',
    ],

    /*
    |--------------------------------------------------------------------------
    | Panel de administración
    |--------------------------------------------------------------------------
    */
    'admin' => [
        'nav_inicio'           => 'Inicio',
        'nav_eventos'          => 'Eventos',
        'nav_empresas'         => 'Empresas',
        'nav_usuarios'         => 'Usuarios',
        'nav_pedidos'          => 'Pedidos',
        'nav_pagos'            => 'Pagos',

        'dashboard_titulo'     => 'Dashboard inicial',
        'dashboard_sub'        => 'Primera version del panel. Actualmente se administran eventos, usuarios y empresas.',
        'dashboard_volver'     => 'Volver al inicio',
        'acciones_rapidas'     => 'Acciones rapidas',
        'crear_evento'         => 'Crear evento',
        'crear_usuario'        => 'Crear usuario',
        'gestionar_empresas'   => 'Gestionar empresas',
        'crear_pedido'         => 'Crear pedido',
        'registrar_pago'       => 'Registrar pago',
        'eventos_activos'      => 'Eventos activos',
        'usuarios_activos'     => 'Usuarios activos',
        'empresas_pendientes'  => 'Empresas pendientes',
        'requieren_revision'   => 'Requieren revisión',
        'pedidos'              => 'Pedidos',
        'pagos'                => 'Pagos',

        'usuarios_titulo'      => 'Gestión de Usuarios',
        'usuarios_sub'         => 'Administra las cuentas registradas en la plataforma.',
        'nuevo_usuario'        => 'Nuevo usuario',
        'col_id'               => 'ID',
        'col_nombre'           => 'Nombre',
        'col_email'            => 'Email',
        'col_cuenta'           => 'Cuenta',
        'col_registro'         => 'Registro',
        'col_admin'            => 'Admin',
        'col_estado'           => 'Estado',
        'col_acciones'         => 'Acciones',
        'col_telefono'         => 'Teléfono',
        'col_fecha_solicitud'  => 'Fecha solicitud',
        'col_hash'             => '#',
        'si'                   => 'Sí',
        'no'                   => 'No',
        'activo'               => 'Activo',
        'inactivo'             => 'Inactivo',
        'editar'               => 'Editar',
        'eliminar'             => 'Eliminar',
        'conf_eliminar_usuario'=> '¿Eliminar a :nombre?',
        'no_usuarios'          => 'No hay usuarios registrados.',

        'empresas_titulo'      => 'Gestión de Empresas',
        'empresas_sub'         => 'Aprueba o rechaza las solicitudes de registro de cuentas de empresa.',
        'pendientes_titulo'    => 'Solicitudes pendientes',
        'historial_titulo'     => 'Historial',
        'aprobar'              => 'Aprobar',
        'rechazar'             => 'Rechazar',
        'conf_aprobar'         => '¿Aprobar la cuenta de :nombre?',
        'conf_rechazar'        => '¿Rechazar la solicitud de :nombre?',
        'aprobada'             => 'Aprobada',
        'rechazada'            => 'Rechazada',
        'no_pendientes'        => 'No hay solicitudes pendientes en este momento.',
        'no_gestionadas'       => 'Todavía no hay empresas gestionadas.',

        'menu_abrir'           => 'Abrir menú',
        'pag_mostrando'        => 'Mostrando',
        'pag_a'                => 'a',
        'pag_de'               => 'de',
        'pag_resultados'       => 'resultados',
        'pag_anterior'         => 'Página anterior',
        'pag_siguiente'        => 'Página siguiente',
        'pag_ir_a'             => 'Ir a la página :num',
        'pag_usuarios'         => 'Paginación de usuarios',
    ],

];
