(function () {
    var table = document.getElementById('uni-application-list');
    if (!table) {
        return;
    }

    var statusToneById = {};
    try {
        statusToneById = JSON.parse(table.dataset.statusTones || '{}');
    } catch (e) {
        statusToneById = {};
    }

    var csrf = table.dataset.csrf || '';

    function applyStatusTone(select) {
        var wrap = select.closest('.uni-status-wrap');
        if (!wrap) {
            return;
        }
        wrap.classList.remove('status-tone-success', 'status-tone-danger', 'status-tone-warning', 'status-tone-primary');
        wrap.classList.add('status-tone-' + (statusToneById[select.value] || 'primary'));
    }

    table.querySelectorAll('.uni-app-status-select').forEach(applyStatusTone);

    table.addEventListener('change', function (event) {
        var select = event.target;
        if (!select.classList.contains('uni-app-status-select')) {
            return;
        }

        var previous = select.dataset.original;
        var wrap = select.closest('.uni-status-wrap');

        select.classList.add('is-saving');
        select.classList.remove('is-saved');
        if (wrap) {
            wrap.classList.add('is-busy');
        }

        fetch(select.dataset.url, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': csrf
            },
            body: JSON.stringify({ status_id: select.value })
        })
            .then(function (response) { return response.json(); })
            .then(function (data) {
                select.classList.remove('is-saving');
                if (wrap) {
                    wrap.classList.remove('is-busy');
                }

                if (data.success) {
                    select.dataset.original = select.value;
                    applyStatusTone(select);
                    select.classList.add('is-saved');
                    setTimeout(function () { select.classList.remove('is-saved'); }, 1200);
                    return;
                }

                select.value = previous;
                applyStatusTone(select);
                alert(data.message || 'Status update failed.');
            })
            .catch(function () {
                select.classList.remove('is-saving');
                if (wrap) {
                    wrap.classList.remove('is-busy');
                }
                select.value = previous;
                applyStatusTone(select);
                alert('Status update failed.');
            });
    });
})();
