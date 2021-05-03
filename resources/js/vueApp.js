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
                            + this.name + '&email=' + this.email + '&phone=' + this.phone + '&type=' + this.pick).then((response) => {
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
        checkForm() {
            this.errors = [];

            if (!this.name) {
                this.errors.push('Требуется указать ФИО');
            } else if (!this.validName(this.name)) {
                this.errors.push('Укажите корректный ФИО');
            }

            if (!this.email) {
                this.errors.push('Требуется указать почту');
            } else if (!this.validEmail(this.email)) {
                this.errors.push('Укажите корректный адрес электронной почты');
            }

            if (!this.phone) {
                this.errors.push('Требуется указать телефон');
            } else if (!this.validPhone(this.phone)) {
                this.errors.push('Укажите корректный номер телефона');
            }

            if (!this.pick) {
                this.errors.push('Требуется выбрать источник данных');
            }
            console.log(this.errors);
            if (this.errors.length === 0) {
                return true;
            }
        },
        validName: function (name) {
            var re = /^[a-zA-Zа-яёА-ЯЁ\s\-]{3,40}$/u;
            return re.test(name);
        },
        validEmail: function (email) {
            var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        },
        validPhone: function (phone) {
            var re = /^((7|8)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/;
            return re.test(phone);
        }
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
                window.location.reload();
            }).catch((error) => {
                adders.errors = [];
                adders.errors.push(error.response.statusText);
            });
        }
    }
});

new Vue({
    el: '#register_user',
    data: {
        name: null,
        email: null,
        phone: null,
        psw: null,
        errors: []
    },

    methods: {
        register_user: function () {

            if (!this.checkForm()) {
                return;
            }
            window.location.href = '/api/auth/register/?name='
            + this.name + '&email=' + this.email + '&phone=' + this.phone + '&psw=' + this.psw;
        },
        clear_form() {
            this.name = null;
            this.email = null;
            this.phone = null;
            this.psw = null;
        },
        checkForm() {
            this.errors = [];

            if (!this.name) {
                this.errors.push('Требуется указать ФИО');
            } else if (!this.validName(this.name)) {
                this.errors.push('Укажите корректный ФИО');
            }

            if (!this.email) {
                this.errors.push('Требуется указать почту');
            } else if (!this.validEmail(this.email)) {
                this.errors.push('Укажите корректный адрес электронной почты');
            }

            if (!this.phone) {
                this.errors.push('Требуется указать телефон');
            } else if (!this.validPhone(this.phone)) {
                this.errors.push('Укажите корректный номер телефона');
            }

            if (!this.psw) {
                this.errors.push('Пароль не введен');
            }

            console.log(this.errors);
            if (this.errors.length === 0) {
                return true;
            }
        },
        validName: function (name) {
            var re = /^[a-zA-Zа-яёА-ЯЁ\s\-]{3,40}$/u;
            return re.test(name);
        },
        validEmail: function (email) {
            var re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
            return re.test(email);
        },
        validPhone: function (phone) {
            var re = /^((7|8)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/;
            return re.test(phone);
        }
    }
});

