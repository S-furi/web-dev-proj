        <form action="#" method="post" name="signup_form">
            <h2>Sign up</h2>
            <ul>
                <li>
                    <label for="first_name">Nome:</label><input type="text" name="first_name" id="first_name">
                </li>
                <li>
                    <label for="last_name">Cognome:</label><input type="text" name="last_name" id="last_name">
                </li>
                <li>
                    <label for="mail">Email:</label><input type="text" id="mail" name="mail"/>
                </li>
                <li>
                    <label for="username">Username:</label><input type="text" id="username" name="username" />
                </li>
                <li>
                    <label for="password">Password:</label><input type="password" id="password" name="password" />
                </li>
                <li>
                    <input type="button" name="sub_register" value="Invia" onclick="form.submit()" />
                </li>
            </ul>
        </form>
