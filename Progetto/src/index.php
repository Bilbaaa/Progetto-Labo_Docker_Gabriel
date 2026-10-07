<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Chat</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="chat-container">

        <h1>Daily Chat</h1>

        <p class="subtitle">
            I messaggi vengono cancellati automaticamente a mezzanotte.
        </p>

        <div id="messages"></div>

        <div class="language-box">
            <label for="language">Lingua:</label>

            <select id="language">
                <option value="it">Italiano</option>
                <option value="en">English</option>
                <option value="de">Deutsch</option>
                <option value="fr">Français</option>
            </select>
        </div>

        <form id="chat-form">
            <input
                type="text"
                id="username"
                placeholder="Nome"
                maxlength="30"
                required
            >

            <input
                type="text"
                id="message"
                placeholder="Scrivi un messaggio..."
                maxlength="500"
                required
            >

            <button type="submit">Invia</button>
        </form>

    </div>

    <script src="script.js"></script>

</body>
</html>