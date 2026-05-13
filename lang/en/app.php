<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Main navigation
    |--------------------------------------------------------------------------
    */
    'nav' => [
        'explorar'       => 'Explore',
        'bolsa'          => 'Job Board',
        'social'         => 'Social',
        'panel'          => 'Dashboard',
        'candidaturas'   => 'Applications',
        'entrar'         => 'Sign in',
        'registro'       => 'Sign up',
    ],

    /*
    |--------------------------------------------------------------------------
    | User dropdown menu
    |--------------------------------------------------------------------------
    */
    'usuario' => [
        'mi_perfil'      => 'My profile',
        'panel_empresa'  => 'Company dashboard',
        'curriculos'     => 'Review CVs',
        'mis_entradas'   => 'My tickets',
        'amigos'         => 'Friends',
        'panel_admin'    => 'Admin panel',
        'cerrar_sesion'  => 'Sign out',
    ],

    /*
    |--------------------------------------------------------------------------
    | Language selector
    |--------------------------------------------------------------------------
    */
    'idioma' => [
        'es'    => 'ES',
        'en'    => 'EN',
        'label' => 'Language',
    ],

    /*
    |--------------------------------------------------------------------------
    | Footer
    |--------------------------------------------------------------------------
    */
    'footer' => [
        'privacidad' => 'Privacy',
        'contacto'   => 'Contact',
        'tagline'    => 'Event platform for young people',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication
    |--------------------------------------------------------------------------
    */
    'auth' => [
        'login_titulo'       => 'Welcome back',
        'login_subtitulo'    => 'Sign in to your account to continue',
        'login_btn'          => 'Sign in',
        'login_sin_cuenta'   => "Don't have an account?",
        'login_registrate'   => 'Sign up',

        'register_titulo'    => 'Create your account',
        'register_subtitulo' => 'Join VIBEZ and start vibing',
        'register_btn'       => 'Create account',
        'register_ya_cuenta' => 'Already have an account?',
        'register_login'     => 'Sign in',

        'email'              => 'Email address',
        'password'           => 'Password',
        'password_confirm'   => 'Confirm password',
        'nombre'             => 'First name',
        'apellido1'          => 'First surname',
        'apellido2'          => 'Second surname',
        'fecha_nacimiento'   => 'Date of birth',
        'telefono'           => 'Phone',
        'tipo_cuenta'        => 'Account type',
        'tipo_cliente'       => 'Client',
        'tipo_empresa'       => 'Company',

        'mostrar_password'   => 'Show password',
        'ocultar_password'   => 'Hide password',
        'login_page_title'   => 'Sign in — VIBEZ',

        'js_email_oblig'     => 'Email is required',
        'js_email_invalido'  => 'Please enter a valid email',
        'js_pass_oblig'      => 'Password is required',
        'js_pass_corta'      => 'Minimum 8 characters',
        'js_sesion_ok'       => 'Signed in!',
        'js_credenciales'    => 'Incorrect credentials. Please try again.',
        'js_error_conexion'  => 'Connection error. Please check your network and try again.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Landing page (welcome)
    |--------------------------------------------------------------------------
    */
    'welcome' => [
        'titulo'        => 'Discover your next event',
        'hero_titulo'   => 'Discover your next',
        'hero_span'     => 'event',
        'subtitulo'     => 'The event platform for young people. Discover, create, buy tickets and connect with your scene.',
        'btn_registro'  => 'Sign up for free',
        'pill_entradas' => '🎟️ QR Tickets',
        'pill_eventos'  => '🎉 Create events',
        'pill_cupones'  => '🏷️ Coupons',
        'pill_bolsa'    => '💼 Job board',
        'pill_social'   => '👥 Social',
        'copyright'     => '© :year VIBEZ — All rights reserved.',
        'btn_admin'     => 'Admin panel',
        'btn_cerrar'    => 'Sign out',
        'btn_entrar'    => 'Sign in',
    ],

    /*
    |--------------------------------------------------------------------------
    | Explore events (home)
    |--------------------------------------------------------------------------
    */
    'home' => [
        'badge'                 => 'The platform of the young scene',
        'titulo'                => 'Your next',
        'titulo_span'           => 'adventure starts here',
        'subtitulo'             => 'Events, concerts, festivals and jobs — everything your scene lives, in one place.',
        'filtro_categoria'      => 'Category',
        'filtro_ubicacion'      => 'Location',
        'filtro_favoritos'      => 'Favourites',
        'filtro_todas'          => 'All',
        'filtro_todas_ciudades' => 'All cities',
        'filtro_solo_favoritos' => 'Favourites only',
        'filtro_limpiar'        => 'Clear',
        'sin_resultados_titulo' => 'No results for these filters',
        'sin_resultados_sub'    => 'Try changing the category or city',
        'ver_todo'              => 'View all',
        'seccion_eventos'       => 'Events',
    ],

    /*
    |--------------------------------------------------------------------------
    | Pending verification
    |--------------------------------------------------------------------------
    */
    'pendiente' => [
        'titulo'  => 'Account pending verification',
        'texto'   => 'Your registration has been completed successfully. The administrator will review your request and, once verified, you will receive a confirmation email.',
        'subtext' => 'If you think there has been an error, please contact the VIBEZ team.',
        'volver'  => 'Back to sign in',
    ],

    /*
    |--------------------------------------------------------------------------
    | Dashboard (index)
    |--------------------------------------------------------------------------
    */
    'dashboard' => [
        'hola'          => 'Hello, :nombre',
        'sesion_activa' => 'Active session',
        'label_nombre'  => 'Name',
        'label_email'   => 'Email',
        'label_id'      => 'ID',
        'label_rol'     => 'Role',
        'rol_admin'     => '⚡ Administrator',
        'btn_logout'    => 'Sign out',
    ],

    /*
    |--------------------------------------------------------------------------
    | User profile
    |--------------------------------------------------------------------------
    */
    'perfil' => [
        'guardar_foto'          => 'Save photo',
        'datos_titulo'          => 'Personal details',
        'datos_sub'             => 'Edit your information and click "Save"',
        'campo_nombre'          => 'First name',
        'campo_apellido1'       => 'First surname',
        'campo_apellido2'       => 'Second surname',
        'campo_telefono'        => 'Phone',
        'campo_fecha_nac'       => 'Date of birth',
        'campo_bio'             => 'Biography',
        'bio_publica'           => 'Public',
        'bio_placeholder'       => 'Tell us something about yourself...',
        'bio_hint'              => 'Max. 500 characters · Visible to all your friends',
        'btn_guardar'           => 'Save changes',
        'mood_titulo'           => 'Mood',
        'mood_sub'              => 'Visible to <strong>everyone</strong> (friends or not) and shown in the navigation bar',
        'mood_sin_estado'       => '— No status —',
        'mood_btn'              => 'Save mood',
        'amigos_titulo'         => 'Friends',
        'amigos_buscar_label'   => 'Search by name or email',
        'amigos_buscar_ph'      => 'Type at least 2 characters...',
        'amigos_solicitudes'    => 'Received requests',
        'amigos_lista'          => 'Your friends (:count)',
        'amigos_vacio'          => "You don't have any friends on VIBEZ yet. Search for someone!",
    ],

    /*
    |--------------------------------------------------------------------------
    | Job board
    |--------------------------------------------------------------------------
    */
    'trabajos' => [
        'badge'                 => '💼 Work in the scene',
        'hero_titulo'           => 'Find your place',
        'hero_titulo_span'      => 'in the scene',
        'hero_sub'              => 'Photographers, sound technicians, PR, bartenders… Work at the best events and festivals in the country.',
        'stat_ofertas'          => 'Active listings',
        'stat_ciudades'         => 'Cities',
        'stat_categorias'       => 'Categories',
        'filtro_cat'            => 'Category',
        'filtro_ciudad'         => 'City',
        'filtro_todas_cat'      => 'All categories',
        'filtro_todas_ciudades' => 'All cities',
        'filtro_limpiar'        => 'Clear',
        'sin_resultados_titulo' => 'No listings for these filters',
        'sin_resultados_sub'    => 'Try another category or city',
        'ver_todas'             => 'View all listings',
        'salario_label'         => 'Salary',
        'btn_ver_oferta'        => 'View listing',
        'cta_titulo'            => 'Do you organise events?',
        'cta_sub'               => 'Post your job listings and find the perfect team for your festivals, concerts and events.',
        'cta_btn'               => 'Explore the platform',

        // Job listing detail
        'det_volver'           => 'Back to Job Board',
        'det_badge'            => 'Job listing',
        'det_descripcion'      => 'Job description',
        'det_requisitos'       => 'Requirements',
        'det_detalles'         => 'Details',
        'det_vacantes'         => 'Vacancies',
        'det_inicio'           => 'Start date',
        'det_fin_contrato'     => 'Contract end',
        'det_salario'          => 'Salary',
        'det_vac_sing'         => 'vacancy available',
        'det_vac_plur'         => 'vacancies available',
        'det_postular'         => 'Apply now',
        'det_cand_sub'         => 'Your application will be sent to the organiser',
        'mod_como'             => 'How do you want to apply?',
        'mod_elige'            => 'Choose how to send your application',
        'mod_form_titulo'      => 'Fill in form',
        'mod_form_sub'         => 'Complete your CV with your data, experience and education',
        'mod_arch_titulo'      => 'Upload file',
        'mod_arch_sub'         => 'Upload your CV as a PDF or Word document',
        'cv_titulo'            => 'Curriculum Vitae',
        'cv_subtitulo'         => 'Complete your profile to apply',
        'cv_info_personal'     => 'Personal Information',
        'cv_nombre'            => 'First name',
        'cv_apellidos'         => 'Last name',
        'cv_email'             => 'Email',
        'cv_telefono'          => 'Phone',
        'cv_ciudad'            => 'City',
        'cv_linkedin'          => 'LinkedIn / Portfolio',
        'cv_perfil'            => 'Professional Profile',
        'cv_exp'               => 'Work Experience',
        'cv_exp_hint'          => 'Add your most relevant experience',
        'cv_empresa'           => 'Company',
        'cv_cargo'             => 'Job title / Role',
        'cv_desde'             => 'From',
        'cv_hasta'             => 'To (leave blank if current)',
        'cv_tareas'            => 'Tasks and achievements description',
        'cv_add_exp'           => 'Add experience',
        'cv_formacion'         => 'Education',
        'cv_formacion_hint'    => 'Studies, courses and certifications',
        'cv_institucion'       => 'Institution',
        'cv_titulacion'        => 'Degree / Certification',
        'cv_ano_inicio'        => 'Start year',
        'cv_ano_fin'           => 'End year',
        'cv_add_titulacion'    => 'Add qualification',
        'cv_habilidades'       => 'Skills',
        'cv_habilidades_hint'  => 'Separate skills with commas',
        'cv_idiomas'           => 'Languages',
        'cv_carta'             => 'Cover Letter',
        'cv_volver'            => 'Back',
        'cv_enviar'            => 'Submit application',
        'up_titulo'            => 'Upload CV',
        'up_subtitulo'         => 'Attach your CV',
        'up_arrastra'          => 'Drag your CV here',
        'up_click'             => 'or click to select a file',
        'up_formatos'          => 'Accepted formats: PDF, DOC, DOCX · Max 5 MB',
        'up_carta'             => 'Cover letter',
        'up_carta_opcional'    => '(optional)',
        'mod_exito_titulo'     => 'Application submitted!',
        'mod_exito_sub'        => 'Your application has been received. The recruitment team will review your profile and contact you.',
        'mod_exito_btn'        => 'Great',
        'js_enviando'          => 'Sending...',
        'js_error_cv'          => 'Error submitting application.',
        'js_error_conexion'    => 'Connection error. Please try again.',
        'js_sin_archivo'       => 'Please select a CV file before submitting.',
        'js_error_archivo'     => 'Error uploading CV.',
        'js_formato'           => 'Format not allowed. Use PDF, DOC or DOCX.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Event detail
    |--------------------------------------------------------------------------
    */
    'eventos' => [
        'volver'             => 'Back',
        'comprar_entrada'    => 'Buy ticket',
        'reservar_gratuita'  => 'Reserve free ticket',
        'reservar_gratis'    => 'Reserve for free',
        'confirmar_compra'   => 'Confirm purchase',
        'gratuito'           => 'Free',
        'descripcion'        => 'Description',
        'sobre_evento'       => 'About the event',
        'sin_descripcion'    => 'No description available.',
        'info_adicional'     => 'Additional info',
        'aforo_maximo'       => 'Max capacity',
        'personas'           => 'people',
        'disponibles'        => 'Available',
        'entradas_disp'      => 'tickets available',
        'pct_ocupado'        => '% sold',
        'finaliza'           => 'Ends at',
        'organiza'           => 'Organised by',
        'visitar_web'        => 'Visit website →',
        'galeria'            => 'Gallery',
        'precio_label'       => 'Price',
        'por_persona_iva'    => 'per person · VAT included',
        'en_favoritos'       => 'Saved',
        'guardar_favoritos'  => 'Save to favourites',
        'ver_web_oficial'    => 'View official website',
        'compra_segura'      => '🔒 Secure purchase · QR ticket delivered instantly',
        'ubicacion'          => 'Location',
        'abrir_maps'         => 'Open in Google Maps →',
        'sin_ubicacion'      => '📍 Location not available',
        'modal_titulo'       => 'Buy tickets',
        'modal_cantidad'     => 'Number of tickets',
        'modal_total'        => 'Total',
        'modal_seguro'       => '🔒 Secure transaction · You will receive your QR instantly',
        'procesando'         => 'Processing...',
        'redirigiendo'       => 'Redirecting...',
        'error_compra'       => 'Error processing purchase.',
        'error_conexion'     => 'Connection error. Please try again.',
        'organizador'        => 'Organiser',
        'cuando'             => 'When',
        'donde'              => 'Where',
        'precio'             => 'Price',
        'aforo'              => 'Capacity',
        'edad_min'           => 'Minimum age',
        'sin_aforo'          => 'No capacity limit',
        'agotado'            => 'Sold out',
        'ver_mapa'           => 'View on map',
    ],

    /*
    |--------------------------------------------------------------------------
    | Social / Messages
    |--------------------------------------------------------------------------
    */
    'social' => [
        'titulo'            => 'Social',
        'panel_titulo'      => 'Social',
        'tab_mensajes'      => 'Messages',
        'tab_amigos'        => 'Friends',
        'tab_descubrir'     => 'Discover',
        'solicitudes'       => 'Friend requests',
        'mis_amigos'        => 'My friends',
        'buscar_placeholder'=> 'Search people by name or email…',
        'buscar_hint'       => 'Type at least 2 characters to search',
        'chat_vacio_titulo' => 'Your messages',
        'chat_vacio_sub'    => 'Select a conversation or start a new one from your friends list',
        'chat_placeholder'  => 'Write a message…',
        'volver'            => 'Back',
    ],

    /*
    |--------------------------------------------------------------------------
    | My tickets
    |--------------------------------------------------------------------------
    */
    'entradas' => [
        'titulo'        => 'My tickets',
        'volver'        => 'Back',
        'vacio_titulo'  => "You don't have any tickets yet",
        'vacio_sub'     => 'Explore the available events and buy your first ticket.',
        'explorar_btn'  => 'Explore events',
        'pedido_num'    => 'Order #:id',
        'gratis'        => 'Free',
        'entrada_sing'  => 'ticket',
        'entrada_plur'  => 'tickets',
        'entrada_label' => 'Ticket #:n',
        'qr_hint'       => 'Show this QR at the event entrance',
        'ver_qr'        => 'Show QR',
        'ocultar_qr'    => 'Hide QR',
        'conf_titulo'   => 'Purchase confirmation — VIBEZ',
        'conf_exito'    => 'Purchase complete!',
        'conf_resumen'  => 'Order summary',
        'conf_total'    => 'Total',
        'conf_tus'      => 'Your tickets',
        'conf_explorar' => 'Explore more events',
        'conf_perfil'   => 'View my profile',
    ],

    /*
    |--------------------------------------------------------------------------
    | Company panel
    |--------------------------------------------------------------------------
    */
    'empresa' => [
        'panel_badge'          => 'Company panel',
        'subtitulo'            => 'Manage your events, review your team and grow your presence on VIBEZ.',
        'btn_crear_evento'     => 'Create event',

        'stats_eventos'        => 'Events',
        'stats_trabajadores'   => 'Staff',
        'stats_ofertas'        => 'Active listings',

        'info_titulo'          => 'Company information',
        'info_sub'             => 'Registered data for your company',
        'info_nombre'          => 'Name',
        'info_razon_social'    => 'Legal name',
        'info_nif'             => 'Tax number',
        'info_descripcion'     => 'Description',
        'info_web'             => 'Website',
        'info_telefono'        => 'Phone',
        'info_direccion'       => 'Address',

        'eventos_titulo'       => 'Your events',
        'eliminar'             => 'Delete',
        'confirmar_eliminar'   => 'Are you sure you want to delete the event «:titulo»? This action cannot be undone.',
        'empty_eventos_titulo' => "You don't have any events yet",
        'empty_eventos_sub'    => 'Create your first event and reach thousands of young people on VIBEZ.',

        'equipo_titulo'        => 'Your team',
        'empty_equipo_titulo'  => 'No team members',
        'empty_equipo_sub'     => 'Organisers assigned to your company will appear here.',

        'acciones_titulo'      => 'Quick actions',
        'acciones_sub'         => 'Manage your company from here',
        'accion_crear_titulo'  => 'Create event',
        'accion_crear_desc'    => 'Publish a new event and reach your target audience.',
        'accion_cvs_titulo'    => 'Review CVs',
        'accion_cvs_desc'      => 'View all candidates who applied to your job listings.',

        // Simple dashboard
        'panel_titulo'         => 'Company Panel',
        'panel_bienvenido'     => 'Welcome, :nombre',
        'panel_empresa_label'  => 'Company:',
        'panel_nif_label'      => 'Tax number:',
        'panel_acciones'       => 'From here you can manage coupons, sponsorships, job listings and view your company statistics.',
        'panel_logout'         => 'Sign out',

        // Create event
        'crear_badge'          => 'New event',
        'crear_titulo'         => 'Create',
        'crear_titulo_span'    => 'event',
        'crear_subtitulo'      => 'Fill in your event details and publish it on VIBEZ.',
        'crear_revisar'        => '⚠ Please review the following fields:',
        'crear_info_basica'    => 'Basic information',
        'crear_tit_evento'     => 'Event title',
        'crear_descripcion'    => 'Description',
        'crear_categoria'      => 'Category',
        'crear_sel_cat'        => 'Select category',
        'crear_tipo'           => 'Event type',
        'crear_presencial'     => 'In person',
        'crear_online'         => 'Online',
        'crear_fecha_hora'     => 'Date and time',
        'crear_fecha_inicio'   => 'Start date',
        'crear_fecha_fin'      => 'End date',
        'crear_fecha_hint'     => 'Optional. Leave empty if it is a single-moment event.',
        'crear_ubicacion'      => 'Location',
        'crear_lugar_nombre'   => 'Venue name',
        'crear_direccion'      => 'Address',
        'crear_latitud'        => 'Latitude',
        'crear_longitud'       => 'Longitude',
        'crear_precio_aforo'   => 'Price and capacity',
        'crear_es_gratuito'    => 'This event is free',
        'crear_precio_base'    => 'Base price (€)',
        'crear_aforo'          => 'Maximum capacity',
        'crear_aforo_hint'     => 'Optional. Leave empty if there is no limit.',
        'crear_edad_min'       => 'Minimum age',
        'crear_edad_hint'      => 'Optional. Leave empty if there is no restriction.',
        'crear_url_externa'    => 'External URL',
        'crear_url_hint'       => 'Link to the event website, if it has one.',
        'crear_imagen'         => 'Cover image',
        'crear_upload_texto'   => '<strong>Click or drag</strong> an image here',
        'crear_upload_formatos'=> 'JPG, PNG, WebP or GIF · Max. 5 MB',
        'crear_publicar'       => 'Publish event',
        'crear_cancelar'       => 'Cancel',

        // Job listings
        'panel_breadcrumb'     => 'Company panel',
        'ofertas_badge'        => 'Review CVs',
        'ofertas_titulo'       => 'Your published listings',
        'ofertas_stat_ofertas' => 'Listings',
        'ofertas_stat_cands'   => 'Applications',
        'ofertas_todas'        => 'All',
        'ofertas_activas'      => 'Active',
        'ofertas_cerradas'     => 'Closed',
        'ofertas_ordenar'      => 'Sort:',
        'ofertas_reciente'     => 'Most recent',
        'ofertas_candidatos'   => 'Most applicants',
        'ofertas_alfabetico'   => 'Alphabetical',
        'ofertas_limpiar'      => 'Clear',
        'ofertas_activa'       => 'Active',
        'ofertas_cerrada'      => 'Closed',
        'ofertas_cand_sing'    => 'applicant',
        'ofertas_cand_plur'    => 'applicants',
        'ofertas_ver_cvs'      => 'View CVs',
        'ofertas_vacio_titulo' => 'No listings published',
        'ofertas_vacio_sub'    => 'When you publish job listings, they will appear here.',

        // Application detail
        'cands_titulo'         => 'Applicants',
        'cands_activa'         => '● Active',
        'cands_cerrada'        => '○ Closed',
        'cands_total_sing'     => 'application',
        'cands_total_plur'     => 'applications',
        'cands_en_estado'      => 'in this status',
        'cands_breadcrumb'     => 'My listings',
        'cands_todos'          => 'All',
        'cands_nuevos'         => 'New',
        'cands_revisados'      => 'Reviewed',
        'cands_preselec'       => 'Shortlisted',
        'cands_rechazados'     => 'Rejected',
        'cands_ordenar'        => 'Sort:',
        'cands_reciente'       => 'Most recent',
        'cands_nombre'         => 'Name A–Z',
        'cands_estado'         => 'By status',
        'cands_col_candidato'  => 'Applicant',
        'cands_col_acciones'   => 'Actions',
        'cands_pdf'            => 'PDF attached',
        'cands_nuevo'          => 'New',
        'cands_revisado'       => 'Reviewed',
        'cands_preseleccionado'=> 'Shortlisted',
        'cands_rechazado'      => 'Rejected',
        'cands_ver_cv'         => 'View full CV',
        'cands_descargar'      => 'Download CV',
        'cands_contactar'      => 'Contact by email',
        'cands_vacio'          => 'No applications',
        'cands_vacio_sub'      => 'When someone applies it will appear here.',
        'cands_ver_todas'      => 'View all applications',
        'cv_titulo'            => 'Applicant CV',
        'cv_postulado'         => 'Applied on :fecha',
        'cv_descargar_pdf'     => 'Download PDF',
        'cv_contactar'         => 'Contact',
        'cv_cerrar'            => 'Close',
        'cv_info_personal'     => 'Personal Information',
        'cv_perfil'            => 'Professional Profile',
        'cv_carta'             => 'Cover Letter',
        'cv_habilidades'       => 'Skills',
        'cv_idiomas'           => 'Languages',
        'cv_email'             => 'Email',
        'cv_telefono'          => 'Phone',
        'cv_ciudad'            => 'City',
        'cv_linkedin'          => 'LinkedIn',
        'cv_solo_archivo'      => 'This applicant uploaded their CV as a file attachment.',
    ],

    /*
    |--------------------------------------------------------------------------
    | Organiser panel
    |--------------------------------------------------------------------------
    */
    'organizador' => [
        'titulo'    => 'Organiser Panel',
        'bienvenido'=> 'Welcome, :nombre',
        'organiza'  => 'You organise events for:',
        'acciones'  => 'From here you can create and manage events, sell tickets and view statistics.',
        'btn_logout'=> 'Sign out',
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin panel
    |--------------------------------------------------------------------------
    */
    'admin' => [
        'nav_inicio'           => 'Home',
        'nav_eventos'          => 'Events',
        'nav_empresas'         => 'Companies',
        'nav_usuarios'         => 'Users',
        'nav_pedidos'          => 'Orders',
        'nav_pagos'            => 'Payments',

        'dashboard_titulo'     => 'Initial dashboard',
        'dashboard_sub'        => 'First version of the panel. Currently managing events, users and companies.',
        'dashboard_volver'     => 'Back to home',
        'acciones_rapidas'     => 'Quick actions',
        'crear_evento'         => 'Create event',
        'crear_usuario'        => 'Create user',
        'gestionar_empresas'   => 'Manage companies',
        'crear_pedido'         => 'Create order',
        'registrar_pago'       => 'Register payment',
        'eventos_activos'      => 'Active events',
        'usuarios_activos'     => 'Active users',
        'empresas_pendientes'  => 'Pending companies',
        'requieren_revision'   => 'Require review',
        'pedidos'              => 'Orders',
        'pagos'                => 'Payments',

        'usuarios_titulo'      => 'User Management',
        'usuarios_sub'         => 'Manage the accounts registered on the platform.',
        'nuevo_usuario'        => 'New user',
        'col_id'               => 'ID',
        'col_nombre'           => 'Name',
        'col_email'            => 'Email',
        'col_cuenta'           => 'Account',
        'col_registro'         => 'Registration',
        'col_admin'            => 'Admin',
        'col_estado'           => 'Status',
        'col_acciones'         => 'Actions',
        'col_telefono'         => 'Phone',
        'col_fecha_solicitud'  => 'Request date',
        'col_hash'             => '#',
        'si'                   => 'Yes',
        'no'                   => 'No',
        'activo'               => 'Active',
        'inactivo'             => 'Inactive',
        'editar'               => 'Edit',
        'eliminar'             => 'Delete',
        'conf_eliminar_usuario'=> 'Delete :nombre?',
        'no_usuarios'          => 'No users registered.',

        'empresas_titulo'      => 'Company Management',
        'empresas_sub'         => 'Approve or reject company account registration requests.',
        'pendientes_titulo'    => 'Pending requests',
        'historial_titulo'     => 'History',
        'aprobar'              => 'Approve',
        'rechazar'             => 'Reject',
        'conf_aprobar'         => "Approve :nombre's account?",
        'conf_rechazar'        => "Reject :nombre's request?",
        'aprobada'             => 'Approved',
        'rechazada'            => 'Rejected',
        'no_pendientes'        => 'No pending requests at this time.',
        'no_gestionadas'       => 'No companies managed yet.',

        'menu_abrir'           => 'Open menu',
        'pag_mostrando'        => 'Showing',
        'pag_a'                => 'to',
        'pag_de'               => 'of',
        'pag_resultados'       => 'results',
        'pag_anterior'         => 'Previous page',
        'pag_siguiente'        => 'Next page',
        'pag_ir_a'             => 'Go to page :num',
        'pag_usuarios'         => 'User pagination',
    ],

];
