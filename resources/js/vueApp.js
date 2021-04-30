var adders = new Vue({
    el: '#add_user',
    data: {
        name: null,
        email: null,
        phone: null,
        pick: null,
        errors: []
    },

    methods: {
        add_user: function () {

            if (!this.checkForm()) {
                return;
            }

            switch (this.pick) {
                //Добавление в базу данных
                case "db":
                    axios
                        .post(window.location.href + '/api/user/put_data/?name='
                            + this.name + '&email=' + this.email + '&phone=' + this.phone + '&type=' + this.pick).then(() => {
                        this.clear_form();
                        renderer.get_db();
                    }).catch((error) => {
                        this.errors.push(error.response.statusText);
                    });

                    break;
                //Добавление в кеш
                case "cache":
                    let cur_storage = undefined;

                    if ((cur_storage = localStorage.getItem('users')) == null) {
                        cur_storage = {
                            users: [],
                            toString() {
                                return JSON.stringify(cur_storage);
                            }
                        };
                    }

                    if (localStorage.getItem('users') != null) {
                        cur_storage = (JSON.parse(localStorage.getItem('users')));
                    }
                    axios
                        .get(window.location.href + '/api/user/put_data/?id=' + (cur_storage['users'].length + 1) + '&name='
                            + this.name + '&email=' + this.email + '&phone=' + this.phone + '&type=' + this.pick).then((res) => {
                        cur_storage['users'].push(res.data);
                        localStorage.setItem('users', JSON.stringify(cur_storage))
                        this.clear_form();
                        renderer.get_cache();
                    }).catch((error) => {
                        this.errors.push(error.response.statusText);
                    });

                    break;
                //Добавление в json
                case 'json':
                    axios
                        .post(window.location.href + '/api/user/put_data/?name='
                            + this.name + '&email=' + this.email + '&phone=' + this.phone + '&type=' + this.pick).then(() => {
                        this.clear_form();
                        renderer.get_json();
                    }).catch((error) => {
                        this.errors.push(error.response.statusText);
                    });
                    break;
                //Добавление в xlsx
                case 'xlsx':
                    axios
                        .post(window.location.href + '/api/user/put_data/?name='
                            + this.name + '&email=' + this.email + '&phone=' + this.phone + '&type=' + this.pick).then(() => {
                        this.clear_form();
                        renderer.get_xlsx();
                    }).catch((error) => {
                        this.errors.push(error.response.statusText);
                    });
                    break;
            }
        },
        clear_form() {
            this.name = null;
            this.email = null;
            this.phone = null;
            this.pick = null;
        },
        checkForm: function (e) {
            if (this.name && this.email && this.phone && this.pick) {
                this.errors = [];
                return true;
            }

            this.errors = [];

            if (!this.name) {
                this.errors.push('Требуется указать ФИО');
            }
            if (!this.email) {
                this.errors.push('Требуется указать почту');
            }
            if (!this.phone) {
                this.errors.push('Требуется указать телефон');
            }
            if (!this.pick) {
                this.errors.push('Требуется выбрать источник данных');
            }

        },

    }
});

var renderer = new Vue({
    el: '#info_block',
    data: {
        data: [],
        name: null
    },
    methods: {
        get_db() {
            axios
                .get('http://direct.ru/api/user/get_all')
                .then(response => {
                    this.name = "База Данных";
                    this.data = response.data
                })
        },
        get_cache() {
            this.name = "Кэш";
            if (localStorage.getItem('users') != null)
                this.data = JSON.parse(localStorage.getItem('users')).users;
            else
                this.data = [];
        },
        get_json() {
            axios
                .get('http://direct.ru/api/user/get_json')
                .then(response => {
                    this.name = "Json";
                    this.data = response.data
                })
        },
        get_xlsx() {
            axios
                .get('http://direct.ru/api/user/get_xlsx')
                .then(response => {
                    this.name = "xlsx";
                    this.data = response.data
                })
        },
        refresh_db() {
            axios
                .post(window.location.href + '/api/user/refresh_db').then(() => {
                this.get_db();
            }).catch((error) => {
                adders.errors = [];
                adders.errors.push(error.response.statusText);
            });
        }
    }
});


