<script>
(function () {
    const subjectsUrl = @json(route('application-form.subjects'));
    const theme = @json($multiSelectTheme ?? 'chip');

    function normalizeIds(values) {
        return Array.from(new Set((values || []).map(function (v) {
            return String(v);
        }).filter(Boolean)));
    }

    function initChipMultiSelect(container) {
        const select = container.querySelector('select');
        const chips = container.querySelector('.ms-chips');
        const search = container.querySelector('.ms-search');
        const dropdown = container.querySelector('.ms-dropdown');
        const box = container.querySelector('.ms-box');
        if (!select || !chips || !search || !dropdown) return;

        let focusedIndex = -1;

        function selectedOptions() {
            return Array.from(select.options).filter(function (option) {
                return option.selected && option.value;
            });
        }

        function availableOptions(filter) {
            const term = (filter || '').trim().toLowerCase();
            return Array.from(select.options).filter(function (option) {
                if (!option.value || option.selected) return false;
                if (!term) return true;
                return option.text.toLowerCase().indexOf(term) !== -1;
            });
        }

        function emitChange() {
            select.dispatchEvent(new Event('change', { bubbles: true }));
            container.dispatchEvent(new CustomEvent('multiselect:change', { bubbles: true }));
        }

        function renderChips() {
            chips.innerHTML = '';
            selectedOptions().forEach(function (option) {
                const chip = document.createElement('span');
                chip.className = 'ms-chip';
                chip.innerHTML = option.text + '<button type="button" aria-label="Remove">&times;</button>';
                chip.querySelector('button').addEventListener('click', function () {
                    option.selected = false;
                    render();
                    emitChange();
                });
                chips.appendChild(chip);
            });
        }

        function renderDropdown() {
            const options = availableOptions(search.value);
            dropdown.innerHTML = '';
            focusedIndex = -1;

            if (!options.length) {
                dropdown.innerHTML = '<div class="ms-empty">No options found</div>';
                return;
            }

            options.forEach(function (option, index) {
                const item = document.createElement('div');
                item.className = 'ms-option';
                item.textContent = option.text;
                item.dataset.index = String(index);
                item.addEventListener('mousedown', function (event) {
                    event.preventDefault();
                    option.selected = true;
                    search.value = '';
                    render();
                    emitChange();
                    search.focus();
                });
                dropdown.appendChild(item);
            });
        }

        function openDropdown() {
            dropdown.classList.add('open');
            renderDropdown();
        }

        function closeDropdown() {
            dropdown.classList.remove('open');
            focusedIndex = -1;
        }

        function render() {
            renderChips();
            updateCount();
            if (dropdown.classList.contains('open')) renderDropdown();
        }

        function updateCount() {
            const countEl = document.querySelector('[data-ms-count="' + select.id + '"]');
            if (!countEl) return;
            const count = selectedOptions().length;
            countEl.textContent = count + ' selected';
        }

        container._msRefresh = render;

        if (container.dataset.msInitialized === '1') {
            render();
            return;
        }

        container.dataset.msInitialized = '1';

        search.addEventListener('focus', openDropdown);
        search.addEventListener('input', function () {
            openDropdown();
        });

        search.addEventListener('keydown', function (event) {
            const items = dropdown.querySelectorAll('.ms-option');
            if (event.key === 'ArrowDown') {
                event.preventDefault();
                focusedIndex = Math.min(focusedIndex + 1, items.length - 1);
            } else if (event.key === 'ArrowUp') {
                event.preventDefault();
                focusedIndex = Math.max(focusedIndex - 1, 0);
            } else if (event.key === 'Enter' && focusedIndex >= 0 && items[focusedIndex]) {
                event.preventDefault();
                items[focusedIndex].dispatchEvent(new MouseEvent('mousedown', { bubbles: true }));
                return;
            } else if (event.key === 'Escape') {
                closeDropdown();
                return;
            } else {
                return;
            }

            items.forEach(function (item, index) {
                item.classList.toggle('focused', index === focusedIndex);
            });
        });

        document.addEventListener('click', function (event) {
            if (!container.contains(event.target)) closeDropdown();
        });

        box.addEventListener('click', function () {
            search.focus();
        });

        render();
    }

    function initSelect2MultiSelect() {
        if (typeof jQuery === 'undefined' || !jQuery.fn.select2) return;

        jQuery('.inquiry-ms-select2').each(function () {
            const $el = jQuery(this);
            if ($el.data('select2')) return;

            $el.select2({
                placeholder: $el.data('placeholder') || 'Select options',
                allowClear: true,
                width: '100%',
                closeOnSelect: false
            });
        });
    }

    function getSelectedUniversityIds() {
        const select = document.getElementById('university_ids');
        if (!select) return [];

        if (theme === 'select2' && typeof jQuery !== 'undefined') {
            return normalizeIds(jQuery(select).val() || []);
        }

        return normalizeIds(Array.from(select.selectedOptions).map(function (option) {
            return option.value;
        }));
    }

    function getSelectedSubjectIds() {
        const select = document.getElementById('subject_ids');
        if (!select) return [];

        if (theme === 'select2' && typeof jQuery !== 'undefined') {
            return normalizeIds(jQuery(select).val() || []);
        }

        return normalizeIds(Array.from(select.selectedOptions).map(function (option) {
            return option.value;
        }));
    }

    function setSubjectOptions(subjects, selectedIds) {
        const select = document.getElementById('subject_ids');
        if (!select) return;

        const keepSelected = normalizeIds(selectedIds);
        const availableIds = normalizeIds((subjects || []).map(function (subject) {
            return subject.id;
        }));

        select.innerHTML = '';
        (subjects || []).forEach(function (subject) {
            const option = document.createElement('option');
            option.value = subject.id;
            option.textContent = subject.name;
            if (keepSelected.indexOf(String(subject.id)) !== -1) {
                option.selected = true;
            }
            select.appendChild(option);
        });

        keepSelected.forEach(function (id) {
            if (availableIds.indexOf(id) === -1) {
                const option = document.createElement('option');
                option.value = id;
                option.textContent = 'Previously selected #' + id;
                option.selected = true;
                select.appendChild(option);
            }
        });

        if (theme === 'select2' && typeof jQuery !== 'undefined') {
            jQuery(select).val(keepSelected).trigger('change.select2');
        } else {
            const wrap = select.closest('[data-multi-select]');
            if (wrap) initChipMultiSelect(wrap);
        }
    }

    function loadSubjects() {
        const universityIds = getSelectedUniversityIds();
        const url = new URL(subjectsUrl, window.location.origin);

        universityIds.forEach(function (id) {
            url.searchParams.append('university_ids[]', id);
        });

        fetch(url.toString(), { headers: { 'Accept': 'application/json' } })
            .then(function (response) { return response.json(); })
            .then(function (data) {
                setSubjectOptions(data.subjects || [], getSelectedSubjectIds());
            });
    }

    document.querySelectorAll('[data-multi-select]').forEach(initChipMultiSelect);
    initSelect2MultiSelect();

    const universitySelect = document.getElementById('university_ids');
    if (universitySelect) {
        universitySelect.addEventListener('change', loadSubjects);
        if (theme === 'select2' && typeof jQuery !== 'undefined') {
            jQuery(universitySelect).on('change', loadSubjects);
        }

        if (getSelectedUniversityIds().length) {
            loadSubjects();
        }
    }

    window.refreshApplicationMultiSelect = function () {
        document.querySelectorAll('[data-multi-select]').forEach(function (container) {
            if (typeof container._msRefresh === 'function') {
                container._msRefresh();
            } else {
                initChipMultiSelect(container);
            }
        });
    };
})();
</script>
