<script>
    document.addEventListener('DOMContentLoaded', () => {
        const createUrl = '{{ route('admin.departments.store') }}';
        const csrfToken = '{{ csrf_token() }}';
        const selects = document.querySelectorAll('.js-department-select');

        if (!selects.length) {
            return;
        }

        const ensureCreateOption = (select) => {
            if ([...select.options].some((opt) => opt.value === '__create__')) {
                return;
            }

            const option = document.createElement('option');
            option.value = '__create__';
            option.textContent = '+ Create new department...';
            select.appendChild(option);
        };

        const insertDepartmentOption = (select, department) => {
            const idValue = String(department.id);
            if ([...select.options].some((opt) => opt.value === idValue)) {
                return;
            }

            const option = document.createElement('option');
            option.value = idValue;
            option.textContent = `${department.name} (${department.code})`;

            const createOption = [...select.options].find((opt) => opt.value === '__create__');
            if (createOption) {
                select.insertBefore(option, createOption);
            } else {
                select.appendChild(option);
            }
        };

        const insertDepartmentIntoAll = (department) => {
            selects.forEach((select) => {
                insertDepartmentOption(select, department);
            });
        };

        const requestCreateDepartment = async (payload) => {
            const response = await fetch(createUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify(payload),
            });

            if (!response.ok) {
                throw new Error('Failed to create department');
            }

            const data = await response.json();
            return data.data;
        };

        const handleCreate = async (select) => {
            const nameInput = window.prompt('Enter new department name (e.g. Computer Science):');
            if (!nameInput || !nameInput.trim()) {
                return;
            }

            const codeInput = window.prompt('Enter department code (e.g. CS):');
            if (!codeInput || !codeInput.trim()) {
                return;
            }

            try {
                const department = await requestCreateDepartment({
                    name: nameInput.trim(),
                    code: codeInput.trim().toUpperCase(),
                });

                if (!department?.id) {
                    throw new Error('Invalid department response');
                }

                insertDepartmentIntoAll(department);
                select.value = String(department.id);
            } catch (error) {
                console.error(error);
                window.alert('Unable to create department. Please try again.');
            }
        };

        selects.forEach((select) => {
            ensureCreateOption(select);

            select.addEventListener('focus', () => {
                select.dataset.prevValue = select.value;
            });

            select.addEventListener('change', () => {
                if (select.value !== '__create__') {
                    select.dataset.prevValue = select.value;
                    return;
                }

                const previousValue = select.dataset.prevValue ?? '';
                select.value = previousValue;
                void handleCreate(select);
            });
        });
    });
</script>
