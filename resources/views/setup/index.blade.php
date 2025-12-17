<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Setup Wizard</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <body class="bg-light">

        <div class="container py-5">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4>Database Setup Wizard</h4>
                </div>
                <div class="card-body">
                    <form id="setup-form">
                        @csrf
                        <div class="mb-3">
                            <label>DB Host</label>
                            <input type="text" name="DB_HOST" class="form-control" value="127.0.0.1" required readonly>
                        </div>
                        <div class="mb-3">
                            <label>DB Port</label>
                            <input type="text" name="DB_PORT" class="form-control" value="3306" required readonly>
                        </div>
                        <div class="mb-3">
                            <label>Username</label>
                            <input type="text" name="DB_USERNAME" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="DB_PASSWORD" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label>Database Name</label>
                            <input type="text" name="DB_DATABASE" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Application Name</label>
                            <input type="text" name="APP_NAME" class="form-control" value="{{ env('APP_NAME') }}" required>
                        </div>

                        <button type="button" id="test-connection" class="btn btn-warning">Test Connection</button>
                        <button type="submit" class="btn btn-success" id="save-config">Save Configuration</button>
                    </form>

                    <div id="response" class="mt-3"></div>
                </div>
            </div>
        </div>

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
        $(document).ready(function() {

            const $form = $('#setup-form');
            const $saveButton = $('#save-config');
            $saveButton.prop('disabled', true);
            const $testButton = $('#test-connection');
            $form.on('input change', 'input, select, textarea', function() {
                $saveButton.prop('disabled', true);
                $testButton.prop('disabled', false);
                $('#response').html('');
            });
            
        });
        $('#test-connection').click(function() {
            $.post('{{ route("setup.test") }}', $('#setup-form').serialize(), function(response) {
                if (response.success) {
                    $('#response').html('<div class="alert alert-success">Connection successful. Press "Save Configuration" to Continue.</div>');
                    const $form = $('#setup-form');
                    const $saveButton = $('#save-config');
                    const $testButton = $('#test-connection');
                    $form.on('input change', 'input, select, textarea', function() {
                        $saveButton.prop('disabled', true);
                        $testButton.prop('disabled', false);
                    });
                    $saveButton.prop('disabled', false);
                    
                }else {
                    let msg = response.message || 'Connection failed.';
                    if (!msg.toLowerCase().includes('unknown database')){
                        $('#response').html('<div class="alert alert-danger">Connection failed: ' + msg + '</div>');
                    }
                    if (msg.toLowerCase().includes('unknown database')) {
                        const dbName = $('input[name="DB_DATABASE"]').val();

                        if (confirm(`The database "${dbName}" does not exist. Do you want to create it now?`)) {
                            $.post('{{ route("setup.createDatabase") }}', $('#setup-form').serialize(), function(createResponse) {
                                if (createResponse.success) {
                                    const $saveButton = $('#save-config');
                                    $saveButton.prop('disabled', false);
                                    $('#response').html('<div class="alert alert-success">Database created. Press Save Configuration to Continue.');
                                } else {
                                    $('#response').html('<div class="alert alert-danger">Failed to create database: ' + createResponse.message + '</div>');
                                }
                            }).fail(function() {
                                $('#response').html('<div class="alert alert-danger">Error creating database.</div>');
                            });
                        }
                    }
                }
            })
            .fail(function(xhr) {
                if (xhr.status === 422) {
                    const errors = xhr.responseJSON.errors;
                    let errorHtml = '<div class="alert alert-danger"><ul>';
                    $.each(errors, function(key, messages) {
                        messages.forEach(msg => {
                            errorHtml += `<li>${msg}</li>`;
                        });
                    });
                    errorHtml += '</ul></div>';
                    $('#response').html(errorHtml);
                } else {
                    $('#response').html('<div class="alert alert-danger">Unexpected error testing connection.</div>');
                }
            });
        });


        $('#setup-form').submit(function(e) {
            e.preventDefault();

            $.post('{{ route("setup.save") }}', $(this).serialize(), function(response) {
                if (response.success) {
                    window.location.href = response.redirect;
                } else {
                    $('#response').html('<div class="alert alert-danger">Failed to save configuration.</div>');
                }
            }).fail(function() {
                $('#response').html('<div class="alert alert-danger">Error saving configuration.</div>');
            });
        });
        </script>

    </body>
</html>
