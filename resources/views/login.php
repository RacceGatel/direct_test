<header><h1 id="head_auth">Вход</h1></header>

<form id="auth" action="/api/auth/login">
    <div class="container">
        <label for="uname"><b>Логин</b></label>
        <input type="text" placeholder="Введите логин" name="uname" required>

        <label for="psw"><b>Пароль</b></label>
        <input type="password" placeholder="Введите пароль" name="psw" required>

        <button type="submit">ВОЙТИ</button>
    </div>
</form>