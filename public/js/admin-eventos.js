// Interacciones de administración de eventos en VIBEZ.
var AdminEventos = {
    iniciar: function () {
        AdminEventos.configurarConfirmacionBorrado();
        AdminEventos.configurarEventoGratuito();
        AdminEventos.configurarMenuHamburguesa();
    },

    configurarMenuHamburguesa: function () {
        var toggle = document.getElementById('menuToggle');
        var menu   = document.getElementById('mainMenu');

        if (!toggle || !menu) {
            return;
        }

        toggle.onclick = function () {
            var isOpen = menu.classList.toggle('open');
            toggle.setAttribute('aria-expanded', isOpen);
        };

        document.onclick = function (eventoClic) {
            if (!toggle.contains(eventoClic.target) && !menu.contains(eventoClic.target)) {
                menu.classList.remove('open');
                toggle.setAttribute('aria-expanded', 'false');
            }
        };
    },

    configurarConfirmacionBorrado: function () {
        var formularios = document.getElementsByClassName('delete-form');
        var indiceFormulario = 0;

        while (indiceFormulario < formularios.length) {
            formularios[indiceFormulario].onsubmit = function (eventoEnvio) {
                eventoEnvio.preventDefault(); // Detener el envío estándar
                var formulario = this;
                var mensajeConfirmacion = formulario.getAttribute('data-confirm-msg') || 'Esta accion no se puede deshacer.';

                if (window.Swal && typeof window.Swal.fire === 'function') {
                    window.Swal.fire({
                        title: 'Confirmar desactivación',
                        text: mensajeConfirmacion,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Sí, eliminar',
                        cancelButtonText: 'Cancelar'
                    }).then(function (resultado) {
                        if (resultado.isConfirmed) {
                            formulario.submit();
                        }
                    });
                } else {
                    if (window.confirm(mensajeConfirmacion)) {
                        formulario.submit();
                    }
                }
            };
            indiceFormulario = indiceFormulario + 1;
        }
    },

    configurarEventoGratuito: function () {
        var checkGratis = document.getElementById('es_gratuito');
        var inputPrecio = document.getElementById('precio_base');

        if (!checkGratis || !inputPrecio) {
            return;
        }

        checkGratis.onchange = function () {
            if (checkGratis.checked) {
                inputPrecio.value = '0';
                inputPrecio.readOnly = true;
            } else {
                inputPrecio.readOnly = false;
            }
        };

        checkGratis.onchange();
    }
};

window.onload = function () {
    AdminEventos.iniciar();
};
