
$(document).ready(function () {
    var showOnlyUnRead = false;
    var base_url = $("#base_url").val();
    
    // Function to load alerts
    function loadAlerts() {
        $.ajax({
            url: $("#base_url").val() + 'alertas/get_alerts',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                var alertTableBody = $('#alertTableBody');
                alertTableBody.empty();

                $.each(data, function (index, alert) {
                    if (!showOnlyUnRead || alert.lido == 0) {
                        var readClass = alert.lido == 1 ? 'checked' : 'unchecked';
                        var readIcon = alert.lido == 1 ? 'fa-check-square-o' : 'fa-square-o';

                        alertTableBody.append(`
                            <tr>
                                <td>${alert.id}</td>
                                <td>${alert.titulo}</td>
                                <td>${alert.urgencia}</td>
                                <td>${alert.data}</td>
                                <td><a href="${base_url}convenios/convenio/${alert.convenio}">${alert.convenio}</a></td>
                                <td>
                                    <i class="fa ${readIcon} mark-read-icon ${readClass}" data-id="${alert.id}"></i>
                                </td>
                            </tr>
                        `);
                    }
                });
            },
            error: function() {
                alert('Failed to load alerts.');
            }
        });
    }

    // Load alerts on page load
    loadAlerts();

    // Toggle filter for read alerts
    $('#toggleReadFilter').on('click', function () {
        showOnlyUnRead = !showOnlyUnRead;
        var buttonText = showOnlyUnRead ? 'Mostrar todos os alertas' : 'Mostrar apenas não lidos';
        $(this).text(buttonText);
        loadAlerts();
    });

    // Mark as read action
    $(document).on('click', '.mark-read-icon', function() {
        var alertId = $(this).data('id');
        var icon = $(this);

        $.ajax({
            url: $("#base_url").val() + 'alertas/update_alert',
            method: 'POST',
            data: { id: alertId },
            success: function() {
                icon.removeClass('fa-circle unchecked').addClass('fa-check-circle checked');
                swal.fire({ icon: 'success', title: 'Alerta marcado como lido', text: 'O alerta foi marcado como lido com sucesso.' });
                loadAlerts();
            },
            error: function() {
                swal.fire({
                    icon: 'error',
                    title: 'Opss...',
                    text: 'Falha ao marcar alerta como lido.'
                });
            }
        });
    });
});