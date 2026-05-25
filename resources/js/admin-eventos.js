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
            formularios[indiceFormulario].onsubmit = function () {
                var formulario = this;

                if (window.Swal && typeof window.Swal.fire === 'function') {
                    window.Swal.fire({
                        title: 'Eliminar evento',
                        text: 'Esta accion no se puede deshacer.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Si, eliminar',
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d'
                    }).then(function (resultado) {
                        if (resultado.isConfirmed) {
                            formulario.submit();
                        }
                    });

                    return false;
            indiceFormulario = indiceFormulario + 1;

                return window.confirm('Estas seguro de eliminar este evento?');
            };
            i = i + 1;
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
