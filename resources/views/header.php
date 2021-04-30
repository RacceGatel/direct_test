<header>

    <div id="login_info" class="profile">
        <? if(!$data) {
            echo "<p>Привет, <b>".$_SESSION['user']."</b></p> <a href='/api/auth/logout'>выйти</a>";
        }else
            echo "<a href='/login'>Вход</a> <a href='/register'>Регистрация</a>";
        ?>
    </div>
</header>