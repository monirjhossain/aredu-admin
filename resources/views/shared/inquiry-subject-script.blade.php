<script>
    (function () {
        const universitySelect = document.getElementById('university_id');
        const subjectSelect = document.getElementById('subject_id');
        if (!universitySelect || !subjectSelect) return;

        const subjectsUrl = @json(route('application-form.subjects'));
        const initialSubjectId = @json(old('subject_id'));

        function setSubjectOptions(subjects, selectedId) {
            subjectSelect.innerHTML = '<option value="">— Select subject —</option>';
            subjects.forEach(function (subject) {
                const option = document.createElement('option');
                option.value = subject.id;
                option.textContent = subject.name;
                if (String(selectedId) === String(subject.id)) {
                    option.selected = true;
                }
                subjectSelect.appendChild(option);
            });
        }

        function loadSubjects(universityId, selectedId) {
            const url = new URL(subjectsUrl, window.location.origin);
            if (universityId) {
                url.searchParams.set('university_id', universityId);
            }

            fetch(url.toString(), { headers: { 'Accept': 'application/json' } })
                .then(function (response) { return response.json(); })
                .then(function (data) {
                    setSubjectOptions(data.subjects || [], selectedId || '');
                });
        }

        universitySelect.addEventListener('change', function () {
            loadSubjects(this.value, '');
        });

        if (universitySelect.value) {
            loadSubjects(universitySelect.value, initialSubjectId);
        }
    })();
</script>
