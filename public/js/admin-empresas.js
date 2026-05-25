function inicializarConfirmacionEmpresas() {
    var formulariosConfirmacion = document.getElementsByClassName('js-confirm-empresa');

    for (var indice = 0; indice < formulariosConfirmacion.length; indice++) {
        formulariosConfirmacion[indice].onsubmit = async function (event) {
            event.preventDefault();

            var formulario = this;
            var accion = formulario.dataset.actionLabel;
            var nombreEmpresa = formulario.dataset.empresa;
            var esAprobacion = accion === 'aprobar';

            var resultado = await Swal.fire({
                title: esAprobacion ? '¿Aprobar empresa?' : '¿Rechazar solicitud?',
                html: 'Empresa: <strong>' + nombreEmpresa + '</strong>',
                icon: esAprobacion ? 'question' : 'warning',
                showCancelButton: true,
                confirmButtonText: esAprobacion ? 'Sí, aprobar' : 'Sí, rechazar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: esAprobacion ? '#059669' : '#dc2626',
            });

            if (resultado.isConfirmed) {
                formulario.submit();
            }
        };
    }
}

inicializarConfirmacionEmpresas();