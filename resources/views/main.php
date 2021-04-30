<? include _views.'header.php'?>
<form  id="add_user">
    <div class="container">


        <label for="name"><b>ФИО</b></label>
        <input v-model="name" type="text" placeholder="Введите ФИО" name="name">

        <label for="email"><b>email</b></label>
        <input v-model="email" placeholder="Введите почту" name="email">

        <label for="phone"><b>телефон</b></label>
        <input v-model="phone" placeholder="Введите телефон" name="phone">

        <button v-on:click='add_user' type="button">Добавить</button>

        <div class="method">
            <input v-model="pick" type="radio" id="db"
                   name="type" value="db">
            <label for="db">DB</label>

            <input v-model="pick" type="radio" id="cache"
                   name="type" value="cache">
            <label for="cache">Cache</label>

            <input v-model="pick" type="radio" id="json"
                   name="type" value="json">
            <label for="json">Json</label>

            <input v-model="pick" type="radio" id="xlsx"
                   name="type" value="xlsx">
            <label for="xlsx">xlsx</label>
        </div>

    </div>
    <div id="errors">
        <ul>
            <li v-for="error in errors">{{ error }}</li>
        </ul>
    </div>
</form>
<div id="info_block">
    <div id="type_name">
        <h1>Вывод</h1>
    </div>

    <div class="type_btn">
        <button v-on:click="get_db()">DB</button>
        <button v-on:click="get_cache()">Cache</button>
        <div>
            <button v-on:click="get_json()">Json</button>
            <button type="button" onclick="location.href='/api/user/get_json'">Json-скачать</button>
        </div>
        <div>
            <button v-on:click="get_xlsx()">xlsx</button>
            <button type="button" onclick="location.href='/api/user/get_xlsx_file'">xlsx-скачать</button>
        </div>
        <button v-on:click="refresh_db()">Обновить базу данных</button>
    </div>

    <div id="type_name">
        <h1>{{name}}</h1>
    </div>

    <div id="users_info" class="user_table">
        <table>
            <tr>
                <td>id</td>
                <td>name</td>
                <td>email</td>
                <td>phone</td>
            </tr>
            <tr v-for='user in data'>
                <td class='id'>{{user.id}}</td>
                <td>{{user.name}}</td>
                <td>{{user.email}}</td>
                <td>{{user.phone}}</td>
            </tr>
        </table>
    </div>
</div>
