import * as $ from 'jquery';
import swal from 'sweetalert2';

export default (function () {
    $(document).on('click', "form.delete button", function(e) {
        var _this = $(this);
        e.preventDefault();
        swal({
            title: 'Are you sure?', // Opération Dangereuse
            text: 'Are you sure you want to continue ?', // Êtes-vous sûr de continuer ?
            type: 'error',
            showCancelButton: true,
            confirmButtonColor: 'null',
            cancelButtonColor: 'null',
            confirmButtonClass: 'btn btn-danger',
            cancelButtonClass: 'btn btn-primary',
            confirmButtonText: 'Yes, sure!', // Oui, sûr
            cancelButtonText: 'Cancel', // Annuler
            closeOnConfirm: false
        }).then(res => {
            if (res.value) {
                _this.closest("form").submit();
            }
        });
    });
}())
