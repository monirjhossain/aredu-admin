(function () {
    function initPortalToggle() {
        var radios = document.querySelectorAll('input[name="portal_type"]');
        var fields = document.getElementById('credentials_fields');
        var hint = document.getElementById('agent_portal_hint');

        if (!radios.length) {
            return;
        }

        function togglePortalFields() {
            var selected = document.querySelector('input[name="portal_type"]:checked');
            if (!selected) {
                return;
            }
            var isAgent = selected.value === '1';
            if (fields) {
                fields.style.display = isAgent ? 'none' : 'block';
            }
            if (hint) {
                hint.style.display = isAgent ? 'block' : 'none';
            }
        }

        radios.forEach(function (radio) {
            radio.addEventListener('change', togglePortalFields);
        });
        togglePortalFields();
    }

    function initFileLabels() {
        document.querySelectorAll('.custom-file-input').forEach(function (input) {
            input.addEventListener('change', function () {
                var label = input.nextElementSibling;
                if (label && label.classList.contains('custom-file-label')) {
                    label.textContent = input.files.length ? input.files[0].name : 'Choose file';
                }
            });
        });
    }

    if (typeof jQuery !== 'undefined') {
        jQuery(document).ready(function () {
            initPortalToggle();
            initFileLabels();
        });
    } else {
        document.addEventListener('DOMContentLoaded', function () {
            initPortalToggle();
            initFileLabels();
        });
    }
})();
