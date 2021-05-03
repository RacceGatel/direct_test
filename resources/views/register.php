<header><h1 id="head_auth">Регистрация</h1></header>

<form id="register_user" class="auth" action="/api/auth/register">
    <div class="container">
        <label for="name"><b>Логин</b></label>
        <input v-model="name" type="text" placeholder="Введите Логин(ФИО)" name="uname" required>

        <label for="email"><b>email</b></label>
        <input type="email" v-model="email" placeholder="Введите почту" name="email" required>

        <label for="phone"><b>телефон</b>(без +)</label>
        <input type="tel" v-model="phone" placeholder="Введите телефон" name="phone" required>

        <label for="psw"><b>Пароль</b></label>
        <input type="password" v-model="psw" placeholder="Введите пароль" name="psw" required>

        <button v-on:click='register_user' type="button">Зарегистрироваться</button>
        <div id="errors">
            <ul>
                <li v-for="error in errors">{{ error }}</li>
                <?=$_GET['error']=="already_exist"?"Такой пользователь уже существует":""?>
            </ul>
        </div>
    </div>
</form>