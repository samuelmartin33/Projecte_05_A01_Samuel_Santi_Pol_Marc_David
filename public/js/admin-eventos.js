var AdminEventos = {
    iniciar: function () {
        AdminEventos.configurarConfirmacionBorrado();
        AdminEventos.configurarEventoGratuito();
    },

    configurarConfirmacionBorrado: function () {
        var formularios = document.querySelectorAll('.delete-form');
        var i = 0;

        while (i < formularios.length) {
            formularios[i].onsubmit = function () {
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
