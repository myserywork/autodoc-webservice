$(document).ready(function () {

    const base_url = $("#base_url").val();

    $('.nav-tabs a').click(function () {
        $(this).tab('show');
    });

    function loadDocuments() {
        $.ajax({
            url: base_url + 'auth/listarTipoDocumentos',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                var tableBody = $('#documentTable tbody');
                tableBody.empty();

                data.forEach(function(doc) {
                    var statusText = doc.ativo == 1 ? 'Ativo' : 'Inativo';
                    var row = `
                        <tr data-id="${doc.id}">
                            <td>${doc.id}</td>
                            <td>${doc.nome}</td>
                            <td>${doc.numero}</td>
                            <td>${statusText}</td>
                            <td>
                                <button class="btn btn-secondary edit-btn">Editar</button>
                                <button class="btn btn-warning toggle-status-btn">Alterar Status</button>
                            </td>
                        </tr>
                    `;
                    tableBody.append(row);
                });

                // Rebind event handlers
                $('.edit-btn').on('click', function() {
                    var row = $(this).closest('tr');
                    var id = row.data('id');
                    var name = row.find('td:eq(1)').text();
                    var number = row.find('td:eq(2)').text();

                    $('#documentId').val(id);
                    $('#documentName').val(name);
                    $('#documentNumber').val(number);
                    $('#documentModal').modal('show');
                });

                $('.toggle-status-btn').on('click', function() {
                    var row = $(this).closest('tr');
                    var id = row.data('id');

                    $.ajax({
                        url: base_url + 'auth/inativarTipoDocumento/' + id,
                        type: 'POST',
                        success: function (response) {
                            var response = JSON.parse(response);
                            if(response.status == "success"){
                                Swal.fire({
                                    title: "Ótimo!",
                                    text: response.message,
                                    icon: "success",
                                });
                            } else {
                                Swal.fire({
                                    title: "Opss!",
                                    text: response.message,
                                    icon: "error",
                                });
                            }
                            loadDocuments();
                        },
                        error: function (xhr) {
                            Swal.fire({
                                title: "Opss!",
                                text: 'Tivemos um erro ao alterar o status do tipo de documento, tente novamente mais tarde!',
                                icon: "error",
                            });
                        }
                    });
                });
            },
            error: function(xhr) {
                alert('Erro ao carregar documentos: ' + xhr.responseText);
            }
        });
    }

    function loadConveniosSincronizados() {
        $.ajax({
            url: base_url + 'auth/listarConveniosSincronizados',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                var tableBody = $('#conveniosTable tbody');
                tableBody.empty();

                data.forEach(function(convenio) {
                    var row = `
                        <tr data-id="${convenio.id}">
                            <td>${convenio.numero_convenio}</td>
                            <td>${convenio.ano_convenio}</td>
                            <td>
                                <button class="btn btn-secondary edit-cnv-btn">Editar</button>
                                <button class="btn btn-danger exclude-btn">Excluir</button>
                            </td>
                        </tr>
                    `;
                    tableBody.append(row);
                });

                // Rebind event handlers
                $('.edit-cnv-btn').on('click', function() {
                    var row = $(this).closest('tr');
                    var id = row.data('id');
                    var numero = row.find('td:eq(0)').text();
                    var ano = row.find('td:eq(1)').text();

                    $('#convenioId').val(id);
                    $('#convenioNumero').val(numero);
                    $('#convenioAno').val(ano);
                    $('#convenioModal').modal('show');
                });

                $('.exclude-btn').on('click', function() {
                    var row = $(this).closest('tr');
                    var id = row.data('id');

                    $.ajax({
                        url: base_url + 'auth/excluirConvenioSincronizado/' + id,
                        type: 'POST',
                        success: function (response) {
                            var response = JSON.parse(response);
                            if(response.status == "success"){
                                Swal.fire({
                                    title: "Ótimo!",
                                    text: response.message,
                                    icon: "success",
                                });
                            } else {
                                Swal.fire({
                                    title: "Opss!",
                                    text: response.message,
                                    icon: "error",
                                });
                            }
                            loadConveniosSincronizados();
                        },
                        error: function (xhr) {
                            Swal.fire({
                                title: "Opss!",
                                text: 'Tivemos um erro ao excluir o convenio da lista de sincronismo, tente novamente mais tarde!',
                                icon: "error",
                            });
                        }
                    });
                });
            },
            error: function(xhr) {
                alert('Erro ao carregar convenios sincronizados: ' + xhr.responseText);
            }
        });
    }

    // Carregar documentos ao carregar a página
    loadDocuments();
    loadConveniosSincronizados();

    // Adicionar novo documento
    $('#addDocumentBtn').on('click', function() {
        $('#documentForm')[0].reset();
        $('#documentId').val('');
        $('#documentModal').modal('show');
    });

    // Adicionar novo convenio
    $('#addConvenioBtn').on('click', function() {
        $('#convenioForm')[0].reset();
        $('#convenioId').val('');
        $('#convenioModal').modal('show');
    });

    // Salvar documento
    $('#saveDocumentBtn').on('click', function() {
        var id = $('#documentId').val();
        var name = $('#documentName').val();
        var number = $('#documentNumber').val();

        var url = id ? base_url + 'auth/editarTipoDocumento/' + id : base_url + 'auth/incluirTipoDocumento';
        var data = { nome: name, numero: number };

        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            success: function(response) {
                $('#documentModal').modal('hide');
                var response = JSON.parse(response);
                if(response.status == "success"){
                    Swal.fire({
                        title: "Ótimo!",
                        text: response.message,
                        icon: "success",
                    });
                } else {
                    Swal.fire({
                        title: "Opss!",
                        text: response.message,
                        icon: "error",
                    });
                }
                loadDocuments();
            },
            error: function(xhr) {
                Swal.fire({
                    title: "Opss!",
                    text: 'Tivemos um erro ao salvar o tipo de documento, tente novamente mais tarde!',
                    icon: "error",
                });
            }
        });
    });

    $('#saveConvenioBtn').on('click', function () {
        var id = $('#convenioId').val();
        var ano = $('#convenioAno').val();
        var numero = $('#convenioNumero').val();

        var url = id ? base_url + 'auth/editarSincronismoConvenio/' + id : base_url + 'auth/incluirSincronismoConvenio';
        var data = { ano_convenio: ano, numero_convenio: numero };

        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            success: function(response) {
                $('#convenioModal').modal('hide');
                var response = JSON.parse(response);
                if(response.status == "success"){
                    Swal.fire({
                        title: "Ótimo!",
                        text: response.message,
                        icon: "success",
                    });
                } else {
                    Swal.fire({
                        title: "Opss!",
                        text: response.message,
                        icon: "error",
                    });
                }
                loadConveniosSincronizados();
            },
            error: function(xhr) {
                Swal.fire({
                    title: "Opss!",
                    text: 'Tivemos um erro ao salvar o convenio, tente novamente mais tarde!',
                    icon: "error",
                });
            }
        });
    });

    // Alternar status do documento
    $('.toggle-status-btn').on('click', function() {
        var row = $(this).closest('tr');
        var id = row.data('id');

        $.ajax({
            url: base_url + 'auth/inativarTipoDocumento/' + id,
            type: 'POST',
            success: function (response) {
                var response = JSON.parse(response);
                if(response.status == "success"){
                    Swal.fire({
                        title: "Ótimo!",
                        text: response.message,
                        icon: "success",
                    });
                } else {
                    Swal.fire({
                        title: "Opss!",
                        text: response.message,
                        icon: "error",
                    });
                }
                loadDocuments();
            },
            error: function(xhr) {
                Swal.fire({
                    title: "Opss!",
                    text: 'Tivemos um erro ao alterar o status do tipo de documento, tente novamente mais tarde!',
                    icon: "error",
                });
            }
        });
    });

    // Alternar status do documento
    $('.exclude-btn').on('click', function() {
        var row = $(this).closest('tr');
        var id = row.data('id');

        $.ajax({
            url: base_url + 'auth/excluirConvenioSincronizado/' + id,
            type: 'POST',
            success: function (response) {
                var response = JSON.parse(response);
                if(response.status == "success"){
                    Swal.fire({
                        title: "Ótimo!",
                        text: response.message,
                        icon: "success",
                    });
                } else {
                    Swal.fire({
                        title: "Opss!",
                        text: response.message,
                        icon: "error",
                    });
                }
                loadConveniosSincronizados();
            },
            error: function(xhr) {
                Swal.fire({
                    title: "Opss!",
                    text: 'Tivemos um erro ao excluir o convenio da lista de sincronismo, tente novamente mais tarde!',
                    icon: "error",
                });
            }
        });
    });

    $('#fetchConvenios').on('click', function() {
        $.ajax({
            url: base_url + 'api/cron_atualizacao_convenios',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                Swal.fire({
                    title: "Dados dos convênios:",
                    text: JSON.stringify(data),
                    icon: "success",
                });
            // Manipule os dados conforme necessário
            },
            error: function(jqXHR, textStatus, errorThrown) {
                Swal.fire({
                    title: "Erro na requisição:",
                    text: textStatus + " " + errorThrown,
                    icon: "error",
                });
            }
        });
    });

    $('#integrateButton').on('click', function() {
        var username = $('#seiUsername').val();
        var password = $('#seiPassword').val();
        var server = $('#seiServer').val();

        if (!username || !password || !server) {
            alert('Por favor, preencha todos os campos.');
            return;
        }

        var data = {
            username: username,
            password: password,
            server: server
        };

        $.ajax({
            url: 'auth/atualizarUsuarioSEI',  // Altere para o URL do seu controlador
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        title: "Ótimo!",
                        text: "Conta Atualizada com sucesso!",
                        icon: "success",
                    });
                    $('#seiModal').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                } else {
                    alert('Erro na integração: ' + response.message);
                }
            },
            error: function(xhr) {
                alert('Erro: ' + xhr.responseText);
            }
        });
    });
});
