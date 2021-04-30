<header><h1 id="head_auth">Регистрация</h1></header>

<form id="auth" action="/api/auth/register">
    <div class="container">
        <label for="uname"><b>Логин</b></label>
        <input type="text" placeholder="Введите логин" name="uname" required>

        <label for="email"><b>Почта</b></label>
        <input type="text" placeholder="Введите почту" name="email" required>

        <label for="phone"><b>Телефон</b></label>
        <input type="text" placeholder="Введите телефон" name="phone" required>

        <label for="psw"><b>Пароль</b></label>
        <input type="password" placeholder="Введите пароль" name="psw" required>

        <button type="submit">Зарегистрироваться</button>
    </div>
</form>