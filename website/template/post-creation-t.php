        <form action="#" method="post" name="signup_form">
            <h2>Creazione Post</h2>
            <ul>
                <!-- <li> -->
                    <!-- <label for="profilepic">Profile Picture:</label><input type="file" id="profilepic"  me="profilepic" /> -->
                <!-- </li> -->
                <li>
                    <label for="title">Titolo Post:</label><input type="text" name="title" id="title">
                </li>
                <li>
                    <label for="photo">Foto:</label><input type="file" name="photo" id="photo">
                </li>
                <li>
                    <label for="description">Descrizione:</label><input type="text" id="description" name="description"/>
                </li>
                <li>
                    <label for="location">Luogo:</label><input type="text" id="location" name="location" />
                </li>
                    <label for="event-datetime">Data e Ora dell'Evento: </label><input type="datetime-local" name="event-datetime" id="event-datetime">
                    <p style="color: red; display: inline" id="date-error-msg"></p>
                </li> 
                <li>
                    <input type="button" name="creation-button" value="Create" />
                </li>
            </ul>
        </form>