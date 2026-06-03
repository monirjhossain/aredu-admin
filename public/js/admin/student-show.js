(function () {
    window.confirmDeleteStudentApplication = function (id) {
        if (typeof Swal === 'undefined') {
            var form = document.getElementById('deleteForm' + id);
            if (form && confirm('Are you sure you want to delete this application?')) {
                form.submit();
            }
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then(function (result) {
            if (result.isConfirmed) {
                var form = document.getElementById('deleteForm' + id);
                if (form) {
                    form.submit();
                }
            }
        });
    };

    function showToast(type, message, title) {
        if (typeof toastr !== 'undefined' && typeof toastr[type] === 'function') {
            toastr[type](message, title || (type === 'success' ? 'Success' : 'Error'));
            return;
        }
        alert(message);
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
                    data: {
                        message: response.status === 419
                            ? 'Session expired. Please refresh the page.'
                            : 'Unexpected server response.'
                    }
                };
            }
        });
    }

    function initStudentPreferences() {
        var root = document.getElementById('student-profile-preferences');
        if (!root) {
            return;
        }

        var csrf = root.dataset.csrf || '';
        var urls = {
            subject: root.dataset.attachSubjectUrl || '',
            university: root.dataset.attachUniversityUrl || ''
        };
        var payloadKeys = {
            subject: 'subject_id',
            university: 'university_id'
        };
        var tagClasses = {
            subject: 'label label-light-primary label-inline mb-1',
            university: 'label label-light-success label-inline mb-1'
        };

        function getTagsContainer(type) {
            return root.querySelector('[data-preference-tags="' + type + '"]');
        }

        function getNewAppSelect(type) {
            return document.querySelector('[data-new-app-select="' + type + '"]');
        }

        function getPreferenceSelect(type) {
            return root.querySelector('[data-preference-select="' + type + '"]');
        }

        function updateStat(type) {
            var stat = document.querySelector('[data-student-stat="' + type + 's"]');
            if (!stat) {
                return;
            }
            var tags = getTagsContainer(type);
            if (!tags) {
                return;
            }
            stat.textContent = String(tags.querySelectorAll('[data-preference-tag-id]').length);
        }

        function ensureNewAppOption(type, id, name) {
            var select = getNewAppSelect(type);
            if (!select) {
                return;
            }
            var existing = select.querySelector('option[value="' + id + '"]');
            if (existing) {
                return;
            }
            var option = document.createElement('option');
            option.value = String(id);
            option.textContent = name;
            select.appendChild(option);
        }

        function appendTag(type, id, name) {
            var container = getTagsContainer(type);
            if (!container) {
                return;
            }

            var empty = container.querySelector('[data-preference-empty="' + type + '"]');
            if (empty) {
                empty.remove();
            }

            if (container.querySelector('[data-preference-tag-id="' + id + '"]')) {
                return;
            }

            var tag = document.createElement('span');
            tag.className = tagClasses[type];
            tag.setAttribute('data-preference-tag-id', String(id));
            tag.textContent = name;
            container.appendChild(tag);
            updateStat(type);
        }

        function removePreferenceOption(type, id) {
            var select = getPreferenceSelect(type);
            if (!select) {
                return;
            }
            var option = select.querySelector('option[value="' + id + '"]');
            if (option) {
                option.remove();
            }
            select.value = '';
        }

        function attachPreference(type) {
            var select = getPreferenceSelect(type);
            var url = urls[type];
            var payloadKey = payloadKeys[type];

            if (!select || !url || !payloadKey) {
                return;
            }

            var id = select.value;
            if (!id) {
                showToast('warning', 'Please select an item first.');
                return;
            }

            var button = root.querySelector('[data-preference-add="' + type + '"]');
            if (button) {
                button.disabled = true;
            }
            select.disabled = true;

            var body = {};
            body[payloadKey] = id;

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(body),
                credentials: 'same-origin'
            })
                .then(parseJsonResponse)
                .then(function (result) {
                    if (button) {
                        button.disabled = false;
                    }
                    select.disabled = false;

                    if (!result.ok || !result.data.success) {
                        showToast('error', result.data.message || 'Could not save.');
                        return;
                    }

                    var item = result.data.item || {};
                    appendTag(type, item.id, item.name);
                    ensureNewAppOption(type, item.id, item.name);
                    removePreferenceOption(type, id);

                    var toastType = result.data.already_exists ? 'info' : 'success';
                    showToast(toastType, result.data.message || 'Saved.');
                })
                .catch(function () {
                    if (button) {
                        button.disabled = false;
                    }
                    select.disabled = false;
                    showToast('error', 'Network error. Please try again.');
                });
        }

        root.querySelectorAll('[data-preference-add]').forEach(function (button) {
            button.addEventListener('click', function () {
                attachPreference(button.getAttribute('data-preference-add'));
            });
        });
    }

    document.addEventListener('DOMContentLoaded', initStudentPreferences);
})();
