document.addEventListener('DOMContentLoaded', () => {
    const messageForm = document.getElementById('message-form');
    if (!messageForm) {
        return; // Exit if the form isn't on the page
    }

    const messageBody = document.getElementById('body');
    const messagesEl = document.getElementById('messages');
    const sendBtn = document.getElementById('sendBtn');

    // Read values from hidden inputs
    const receiverId = document.getElementById('receiver_id').value;
    const chatId = document.getElementById('chatId').value;

    // Read values from data attributes on the form
    const sendUrl = messageForm.dataset.sendUrl;
    const csrfToken = messageForm.dataset.csrfToken;
    const authId = messageForm.dataset.authId;

    // Scroll to the bottom of the chat on load
    if (messagesEl) {
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    /**
     * Reusable function to append a message to the chat window.
     * @param {object} message - The message object (from axios or echo)
     * @param {boolean} isMe - True if this is the authenticated user
     */
    const appendMessage = (message, isMe) => {
        // Create new message elements based on the new structure
        const bubbleEl = document.createElement('div');
        bubbleEl.className = `message-bubble ${isMe ? 'sender-me' : 'sender-other'}`;

        const contentEl = document.createElement('div');
        contentEl.className = `message-content ${isMe ? 'sender-me' : 'sender-other'}`;

        const textEl = document.createElement('p');
        textEl.textContent = message.body;

        const timeEl = document.createElement('p');
        timeEl.className = `message-timestamp ${isMe ? 'sender-me' : 'sender-other'}`;

        // Format timestamp (e.g., 10:30 AM)
        const messageDate = new Date(message.created_at);
        timeEl.textContent = messageDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

        // Assemble the message
        contentEl.appendChild(textEl);
        contentEl.appendChild(timeEl);
        bubbleEl.appendChild(contentEl);

        // Add to container and scroll
        messagesEl.appendChild(bubbleEl);
        messagesEl.scrollTop = messagesEl.scrollHeight;
    };


    const handleSend = () => {
        const body = messageBody.value.trim();
        if (body === '') {
            return;
        }

        const messageText = body;
        messageBody.value = '';

        console.log('Sending message via Axios...');

        axios.post(sendUrl, {
            _token: csrfToken,
            receiver_id: receiverId,
            body: messageText,
            chatId: chatId,
        })
        .then(response => {
            console.log('Axios success. Appending own message:', response.data.message);
            // On success, immediately append our own message.
            appendMessage(response.data.message, true);
        })
        .catch(error => {
            console.error('Error sending message:', error);
            messageBody.value = messageText;
            alert('Error sending message.');
        });
    };

    // Handle form submit
    messageForm.addEventListener('submit', (e) => e.preventDefault());

    // Handle button click (it's type="button" so it won't submit form)
    sendBtn.addEventListener('click', handleSend);

    // Handle 'Enter' key press
    messageBody.addEventListener('keypress', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            handleSend();
        }
    });

    // Listen for messages
    if (typeof Echo !== 'undefined' && chatId) {
        console.log(`Echo: Subscribing to private channel: chat.${chatId}`);

        Echo.private('chat.' + chatId)
            .listen('MessageSent', (e) => {
                // This is the most important log!
                console.log('Echo: Received "MessageSent" event:', e);

                const isMe = e.message.sender_id == authId;
                console.log(`Echo: Message sender_id is ${e.message.sender_id}, my authId is ${authId}. Is this me? ${isMe}`);

                if (!isMe) {
                    console.log('Echo: Appending message from other user.');
                    appendMessage(e.message, false);
                } else {
                    console.log('Echo: Ignoring my own message (already appended by Axios).');
                }
            })
            // ADDED: Listen for subscription success
            .listenToAll((eventName, data) => {
                if (eventName.includes('subscription_succeeded')) {
                    console.log(`Echo: Successfully subscribed to channel: chat.${chatId}`);
                }
                if (eventName.includes('subscription_error')) {
                    console.error(`Echo: FAILED to subscribe to channel: chat.${chatId}`, data);
                }
            });

    } else {
        console.error('Echo or chatId not found. Real-time messages will not work.');
    }
});
