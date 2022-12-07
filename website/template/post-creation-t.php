        <form action="#" method="post" name="signup_form">
            <h2>Post Creation</h2>
            <ul>
                <!-- <li> -->
                    <!-- <label for="profilepic">Profile Picture:</label><input type="file" id="profilepic"  me="profilepic" /> -->
                <!-- </li> -->
                <li>
                    <label for="post_title">Titolo Post:</label><input type="text" name="post_title" id="post_title">
                </li>
                <li>
                    <label for="photo">Photo:</label><input type="file" name="photo" id="photo">
                </li>
                <li>
                    <label for="description">Email:</label><input type="text" id="description" name="description"/>
                </li>
                <li>
                    <label for="location">Location:</label><input type="text" id="location" name="location" />
                </li>
                <li>
                    <input type="button" name="creation-button" value="Create" onclick="form.submit()" />
                </li>
            </ul>
        </form>