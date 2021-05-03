<header><h1 id="head_auth">Вход</h1></header>

<form class="auth" action="/api/auth/login">
    <div class="container">
        <label for="name"><b>Логин</b></label>
        <input type="text" placeholder="Введите логин" name="name" required>

        <label for="psw"><b>Пароль</b></label>
        <input type="password" placeholder="Введите пароль" name="psw" required>

        <button type="submit">ВОЙТИ</button>
        <div id="errors">
            <ul>
                <?=$_GET['error']=="wrong_data"?"Неверно указаны данные":""?>
            </ul>
        </div>
    </div>
</form>