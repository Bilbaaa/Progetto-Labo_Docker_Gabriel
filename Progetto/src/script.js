const form = document.getElementById("chat-form");

const usernameInput =
    document.getElementById("username");

const messageInput =
    document.getElementById("message");

const messagesDiv =
    document.getElementById("messages");

const languageSelect =
    document.getElementById("language");


async function loadMessages() {

    try {

        const language =
            languageSelect.value;

        const response =
            await fetch(
                `api.php?lang=${language}`
            );

        const messages =
            await response.json();

        messagesDiv.innerHTML = "";

        messages.forEach(message => {

            const div =
                document.createElement("div");

            div.classList.add("message");

            div.innerHTML = `
                <div class="message-header">
                    <span class="username">
                        ${escapeHtml(message.username)}
                    </span>

                    <span class="time">
                        ${escapeHtml(message.time)}
                    </span>
                </div>

                <div>
                    ${escapeHtml(message.message)}
                </div>
            `;

            messagesDiv.appendChild(div);

        });

        messagesDiv.scrollTop =
            messagesDiv.scrollHeight;

    } catch (error) {

        console.error(
            "Errore caricamento messaggi:",
            error
        );

    }

}

languageSelect.addEventListener(
    "change",
    loadMessages
);

form.addEventListener(
    "submit",
    async (event) => {

        event.preventDefault();


        const username =
            usernameInput.value.trim();

        const message =
            messageInput.value.trim();


        if (!username || !message) {
            return;
        }


        try {

            await fetch(
                "api.php",
                {
                    method: "POST",

                    headers: {
                        "Content-Type":
                            "application/json"
                    },

                    body: JSON.stringify({
                        username,
                        message
                    })
                }
            );


            messageInput.value = "";

            await loadMessages();

        } catch (error) {

            console.error(
                "Errore invio messaggio:",
                error
            );

        }

    }
);


function escapeHtml(text) {

    const div =
        document.createElement("div");

    div.textContent = text;

    return div.innerHTML;
}


loadMessages();

setInterval(
    loadMessages,
    3000
);