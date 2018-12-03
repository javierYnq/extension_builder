/**
 * Created by Nicolas Carrasco on 25-05-14.
 */
$( document ).ready(function() {
    console.log( "ready!" );
    $('.confirm-delete').on('click', function(e) {
        e.preventDefault();
        $('#deleteModal').data('href', $(e.target).attr('href')).modal('show');
    });

    $('#deleteModal').on('show.bs.modal', function(e) {
        $(this).find('.btn-danger').attr('href', $('#deleteModal').data('href'));
    });

    $(".doSelect2").select2();

    $(".bootstrapDatepicker").datepicker({
        format: "dd-mm-yyyy",
        language: "es",
        autoclose: true
    });
    $(".dropzone").each(function(){
        var dropzoneOptions = {
            // The camelized version of the ID of the form element
            url: '?eID='+$("#"+$(this).attr('id')).data('input-eid'),//Script EID
            // The configuration we've talked about above
            parallelUploads: 1,
            maxFiles: 1,
            addRemoveLinks: true,
            dictRemoveFile: 'Quitar Archivo',
            dictCancelUpload: 'Cancelar',
            dictDefaultMessage: "Arrastrar o hacer click",
            dictFallbackMessage: "Su navegador actual no soporta la opción de subida",
            dictInvalidFileType: "El tipo de archivo, no es valido",

            // The setting up of the dropzone
            init: function () {
                var inputId = $(this.element).data('input-hidden');
                $(this.element).data('input-file', $("#"+inputId).val());
                // Listen to the sendingmultiple event. In this case, it's the sendingmultiple event instead
                // of the sending event because uploadMultiple is set to true.
                this.on("sendingmultiple", function () {
                    // Gets triggered when the form is actually being sent.
                    // Hide the success button or the complete form.
                });
                this.on("success", function (files, response) {
                    $("#" + inputId).val(response.filePath);
                    // Gets triggered when the files have successfully been sent.
                    // Redirect user or notify of success.
                });
                this.on("errormultiple", function (files, response) {
                    // Gets triggered when there was an error sending the files.
                    // Maybe show form again, and notify user of error
                });
                this.on("maxfilesexceeded", function (file) {

                    var filePath= $("#" + inputId).val();
                    this.removeFile(file);
                    $("#" + inputId).val(filePath);
                });
                this.on("removedfile", function (file) {
                    $("#" + inputId).val($(this.element).data('input-file'));
                });
            }
        };

        $("#"+$(this).attr('id')).dropzone(dropzoneOptions);
    });
});

Dropzone.autoDiscover = false;
