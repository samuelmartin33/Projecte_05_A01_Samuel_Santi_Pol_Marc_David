/**
 * moderador-confirmar.js — confirmacion SweetAlert2 para formularios de borrado
 */
document.querySelectorAll('.delete-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            var msg = form.getAttribute('data-confirm-msg') || '¿Confirmar acción?';
            Swal.fire({
                title: msg,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#ef4444',
            }).then(function(result) {
                if (result.isConfirmed) { form.submit(); }
            });
        });
    });
