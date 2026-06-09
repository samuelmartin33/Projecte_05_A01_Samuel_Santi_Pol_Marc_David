/**
 * Inicializa el escáner QR usando html5-qrcode.
 * API limpia sin llamadas al DOM prohibidas.
 *
 * @param {string} divId - ID del contenedor (ej. 'qr-reader')
 * @param {function} onDecoded - Callback que recibe el texto escaneado
 */
function initScanner(divId, onDecoded) {
    // Html5QrcodeScanner se inicializa recibiendo el *string* del ID,
    // que es la única excepción permitida a la regla de no usar selectores DOM.
    const scanner = new Html5QrcodeScanner(divId, {
        fps: 10,
        qrbox: { width: 260, height: 260 }
    });

    scanner.render(
        (decodedText) => {
            // Pausa el escáner para evitar lecturas repetidas instantáneas
            scanner.pause();
            
            // Llama al callback de Alpine.js
            onDecoded(decodedText);
            
            // Reactiva el escáner tras 2 segundos
            setTimeout(() => {
                scanner.resume();
            }, 2000);
        },
        () => {
            // Callback de error de lectura/búsqueda (se ignora para no saturar consola)
        }
    );
}

/**
 * Envía la petición POST para canjear un bono.
 *
 * @param {string} codigoQr - El código escaneado
 * @param {string} tipoProducto - 'cocktail', 'destilado', o 'sin_alcohol'
 * @param {number} eventoId - ID del evento seleccionado
 * @param {string} urlCanjear - URL de destino extraída del data-attribute
 * @param {string} csrf - Token CSRF
 * @returns {Promise<object>} JSON del servidor
 */
async function canjear(codigoQr, tipoProducto, eventoId, urlCanjear, csrf) {
    const cuerpo = new URLSearchParams();
    cuerpo.append('codigo_qr', codigoQr);
    cuerpo.append('tipo_producto', tipoProducto);
    cuerpo.append('evento_id', eventoId);

    const respuesta = await fetch(urlCanjear, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrf,
            'Content-Type': 'application/x-www-form-urlencoded',
            'Accept':       'application/json',
        },
        body: cuerpo.toString(),
    });

    return await respuesta.json();
}

// ── Exposición global para Alpine.js ──────────────────────────────────────────

window.CanjeBono = {
    initScanner,
    canjear,
};

window.canjeBonoApp = function(csrfToken, urlCanjearBase) {
    return {
        eventoId: '',
        scannerInicializado: false,
        
        modalAbierto: false,
        qrPendiente: null,
        procesando: false,
        error: null,
        
        ultimoEscaneo: null,
        historial: [],

        init() {
            // Observamos cambios en eventoId para iniciar el escáner cuando se seleccione uno
            this.$watch('eventoId', (valor) => {
                if (valor && !this.scannerInicializado) {
                    this.iniciarScanner();
                }
            });
        },

        iniciarScanner() {
            // Llama a la lógica centralizada. El ID 'qr-reader' se pasa como string (API de la librería)
            window.CanjeBono.initScanner('qr-reader', (textoQR) => {
                this.procesarQR(textoQR);
            });
            this.scannerInicializado = true;
        },

        procesarQR(textoQR) {
            // Abrimos modal pidiendo tipo de bebida
            this.qrPendiente = textoQR;
            this.error = null;
            this.modalAbierto = true;
        },

        async confirmarCanje(tipoProducto) {
            if (!this.eventoId || !this.qrPendiente) return;

            this.procesando = true;

            const resultado = await window.CanjeBono.canjear(
                this.qrPendiente, 
                tipoProducto, 
                this.eventoId, 
                urlCanjearBase, 
                csrfToken
            );

            this.procesando = false;

            if (resultado.ok) {
                this.ultimoEscaneo = resultado;
                
                // Añadir al historial
                const ahora = new Date();
                this.historial.unshift({
                    hora: ahora.getHours().toString().padStart(2, '0') + ':' + ahora.getMinutes().toString().padStart(2, '0'),
                    cliente: resultado.cliente,
                    tipo_producto: resultado.tipo_producto,
                    bebidas_restantes: resultado.bebidas_restantes
                });
                
                this.cerrarModal();
            } else {
                this.error = resultado.mensaje;
                this.cerrarModal();
            }
        },

        cerrarModal() {
            this.modalAbierto = false;
            this.qrPendiente = null;
        },

        formatTipoProducto(tipo) {
            const mapas = {
                'cocktail': 'Cóctel',
                'destilado': 'Destilado',
                'sin_alcohol': 'Sin Alcohol'
            };
            return mapas[tipo] || tipo;
        }
    };
};
