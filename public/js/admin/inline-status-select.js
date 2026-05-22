(function () {
    var roots = document.querySelectorAll('[data-inline-status-root]');
    if (!roots.length) {
        return;
    }

    function showToast(type, message, title) {
        if (typeof toastr !== 'undefined' && typeof toastr[type] === 'function') {
            toastr[type](message, title || (type === 'success' ? 'Success' : 'Error'));
            return;
        }
        alert(message);
    }

    function parseToneMap(root) {
        try {
            return JSON.parse(root.dataset.statusTones || '{}');
        } catch (e) {
            return {};
        }
    }

    function getCsrfToken(root) {
        if (root && root.dataset.csrf) {
            return root.dataset.csrf;
        }
        var meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.getAttribute('content') : '';
    }

    function applyStatusTone(select, toneMap) {
        var wrap = select.closest('.uni-status-wrap');
        if (!wrap) {
            return;
        }
        wrap.classList.remove('status-tone-success', 'status-tone-danger', 'status-tone-warning', 'status-tone-primary');
        wrap.classList.add('status-tone-' + (toneMap[select.value] || 'primary'));
    }

    function parseJsonResponse(response) {
        return response.text().then(function (text) {
            if (!text) {
                return { ok: response.ok, data: {} };
            }
            try {
                return { ok: response.ok, data: JSON.parse(text) };
            } catch (e) {
                return {
                    ok: false,
                    data: { message: response.status === 419 ? 'Session expired. Please refresh the page.' : 'Unexpected server response.' }
                };
            }
        });
    }

    roots.forEach(function (root) {
        var toneMap = parseToneMap(root);
        root.querySelectorAll('.inline-status-select').forEach(function (select) {
            applyStatusTone(select, toneMap);
        });
    });

    document.addEventListener('change', function (event) {
        var select = event.target;
        if (!select.classList.contains('inline-status-select')) {
            return;
        }

        var root = select.closest('[data-inline-status-root]');
        if (!root) {
            return;
        }

        var toneMap = parseToneMap(root);
        var csrf = getCsrfToken(root);
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
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ status_id: select.value }),
            credentials: 'same-origin'
        })
            .then(parseJsonResponse)
            .then(function (result) {
                select.classList.remove('is-saving');
                if (wrap) {
                    wrap.classList.remove('is-busy');
                }

                if (result.ok && result.data.success) {
                    select.dataset.original = select.value;
                    applyStatusTone(select, toneMap);
                    select.classList.add('is-saved');
                    setTimeout(function () { select.classList.remove('is-saved'); }, 1200);
                    showToast('success', result.data.message || 'Status updated.', 'Success');
                    return;
                }

                select.value = previous;
                applyStatusTone(select, toneMap);
                showToast('error', (result.data && result.data.message) || 'Status update failed.', 'Error');
            })
            .catch(function () {
                select.classList.remove('is-saving');
                if (wrap) {
                    wrap.classList.remove('is-busy');
                }
                select.value = previous;
                applyStatusTone(select, toneMap);
                showToast('error', 'Status update failed.', 'Error');
            });
    });
})();
