import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.data('projectForm', (initialData = {}) => ({
    loading: false,
    form: {
        id: initialData.id || null,
        name: initialData.name || '',
        base_url: initialData.base_url || '',
        description: initialData.description || '',
        url_list: initialData.url_list || '',
        user_id: initialData.user_id || null,
        selectors: []
    },

    init() {
        console.log('Project form initialized with data:', this.form);

        // Если есть url_list и это строка, можно преобразовать в массив если нужно
        if (this.form.url_list && typeof this.form.url_list === 'string') {
            // Оставляем как есть для textarea
        }

        // Инициализируем селекторы
        this.initSelectors();
    },

    initSelectors() {
        // Если есть селекторы из базы данных
        if (this.form.id && initialData.selectors && initialData.selectors.length > 0) {
            // Используем селекторы из базы
            this.form.selectors = initialData.selectors.map(selector => ({
                id: selector.id || null,
                title: selector.title || '',
                selector: selector.selector || '',
                selector_type: selector.selector_type || 'text'
            }));
        }
        // Если это новый проект или нет селекторов в базе
        else {
            // Создаем 3 пустых селектора
            this.form.selectors = [
                { id: null, title: '', selector: '', selector_type: 'text' },
                { id: null, title: '', selector: '', selector_type: 'text' },
                { id: null, title: '', selector: '', selector_type: 'text' }
            ];
        }
    },

    addSelector() {
        this.form.selectors.push({
            id: null,
            title: '',
            selector: '',
            selector_type: 'text'
        });
    },

    removeSelector(index) {
        if (this.form.selectors.length > 1) {
            this.form.selectors.splice(index, 1);
        } else {
            alert('At least one selector is required');
        }
    },

    async submit() {
        this.loading = true;
        this.error = null;

        try {
            const method = this.form.id ? 'PUT' : 'POST';
            const url = this.form.id
                ? `/projects/${this.form.id}`
                : '/projects';

            // Подготавливаем данные для отправки
            const submitData = {
                name: this.form.name,
                base_url: this.form.base_url,
                description: this.form.description,
                url_list: this.form.url_list,
                user_id: this.form.user_id,
                selectors: this.form.selectors.map(selector => ({
                    id: selector.id,
                    title: selector.title,
                    selector: selector.selector,
                    selector_type: selector.selector_type
                }))
            };

            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(submitData)
            });

            const data = await response.json();

            if (!response.ok) {
                // Обрабатываем ошибки валидации
                if (response.status === 422 && data.errors) {
                    const errorMessages = Object.values(data.errors).flat().join('\n');
                    throw new Error(errorMessages);
                }
                // Обрабатываем ошибку существующего проекта
                if (response.status === 409) {
                    throw new Error(data.message || 'Проект с таким base_url уже существует');
                }
                throw new Error(data.message || 'Ошибка при сохранении проекта');
            }

            // Успешное сохранение
            if (data.redirect) {
                // Если сервер вернул URL для редиректа
                window.location.href = data.redirect;
            } else {
                // Если это создание нового, обновляем id
                if (!this.form.id && data.id) {
                    this.form.id = data.id;
                    // Если вернулись селекторы с id, обновляем их
                    if (data.selectors) {
                        this.form.selectors = data.selectors;
                    }
                }

                // Показываем сообщение об успехе
                alert(data.message || 'Проект успешно сохранен');

                // Делаем редирект на список проектов через 1 секунду
                setTimeout(() => {
                    window.location.href = '/projects';
                }, 1000);
            }

        } catch (error) {
            console.error('Error:', error);
            this.error = error.message;
            alert(error.message || 'Ошибка при сохранении проекта');
        } finally {
            this.loading = false;
        }
    },

    async deleteProject() {
        if (!this.form.id) return;

        if (!confirm('Вы уверены, что хотите удалить этот проект?')) return;

        this.loading = true;

        try {
            const response = await fetch(`/projects/${this.form.id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Ошибка при удалении проекта');
            }

            alert(data.message || 'Проект успешно удален');
            window.location.href = '/projects';

        } catch (error) {
            console.error('Error:', error);
            alert(error.message || 'Ошибка при удалении проекта');
        } finally {
            this.loading = false;
        }
    },

    async startParse() {

        if (!this.form.id) return;

        this.loading = true;

        try {
            const response = await fetch(`/parse/${this.form.id}`, {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Ошибка сканирования');
            }

            alert(data.message || 'Успешно выполнено');
            //window.location.href = '/projects';

        } catch (error) {
            console.error('Error:', error);
            alert(error.message || 'Ошибка сканирования');
        } finally {
            this.loading = false;
        }
    },
}));

Alpine.start();


