document.addEventListener('DOMContentLoaded', () => {
    const messageForm = document.getElementById('message-form');
    if (!messageForm) return;

    const messageBody = document.getElementById('body');
    const messagesEl = document.getElementById('messages');
    const sendBtn = document.getElementById('sendBtn');

    // ** Get the image path input & preview area **
    const imagePathInput = document.getElementById('image_path');
    const imagePreviewArea = document.getElementById('image-preview-area');

    const receiverId = document.getElementById('receiver_id').value;
    const chatId = document.getElementById('chatId').value;
    const sendUrl = messageForm.dataset.sendUrl;
    const csrfToken = messageForm.dataset.csrfToken;
    const authId = messageForm.dataset.authId;

    if (messagesEl) {
        messagesEl.scrollTop = messagesEl.scrollHeight;
    }

    const appendMessage = (message, isMe) => {
        const bubbleEl = document.createElement('div');
        bubbleEl.className = `message-bubble ${isMe ? 'sender-me' : 'sender-other'}`;

        const contentEl = document.createElement('div');
        contentEl.className = `message-content ${isMe ? 'sender-me' : 'sender-other'}`;

        // ** CHANGED: Use innerHTML to render the embedded <img> tag **
        const textDiv = document.createElement('div');
        textDiv.innerHTML = message.body;

        const timeEl = document.createElement('p');
        timeEl.className = `message-timestamp ${isMe ? 'sender-me' : 'sender-other'}`;
        const messageDate = new Date(message.created_at);
        timeEl.textContent = messageDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

        contentEl.appendChild(textDiv);
        contentEl.appendChild(timeEl);
        bubbleEl.appendChild(contentEl);

        messagesEl.appendChild(bubbleEl);
        messagesEl.scrollTop = messagesEl.scrollHeight;
    };

    const handleSend = () => {
        const text = messageBody.value.trim();
        if (text === '') return;

        // ** Construct the HTML Body **
        let finalBody = text;
        const currentImagePath = imagePathInput ? imagePathInput.value : null;

        if (currentImagePath) {
            // Embed the image HTML
            const imgTag = `<br><img src="${currentImagePath.startsWith('http') ? currentImagePath : '/' + currentImagePath}" class="chat-embedded-image" style="max-width: 200px; border-radius: 8px; margin-top: 5px; display: block;">`;
            finalBody = text + imgTag;
        }

        // ** CLEAR INPUTS & HIDE PREVIEW **
        messageBody.value = '';
        if (imagePathInput) imagePathInput.value = '';
        if (imagePreviewArea) imagePreviewArea.style.display = 'none'; // This hides the preview box

        console.log('Sending embedded HTML body:', finalBody);

        axios.post(sendUrl, {
            _token: csrfToken,
            receiver_id: receiverId,
            body: finalBody,
            chatId: chatId
        })
        .then(response => {
            appendMessage(response.data.message, true);
        })
        .catch(error => {
            console.error('Error sending message:', error);
            // Restore if error
            messageBody.value = text;
            if (imagePathInput) imagePathInput.value = currentImagePath;
            if (imagePreviewArea && currentImagePath) imagePreviewArea.style.display = 'flex';
            alert('Error sending message.');
        });
    };

    messageForm.addEventListener('submit', (e) => e.preventDefault());
    sendBtn.addEventListener('click', handleSend);
    messageBody.addEventListener('keypress', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            handleSend();
        }
    });

    if (typeof Echo !== 'undefined' && chatId) {
        Echo.private('chat.' + chatId)
            .listen('MessageSent', (e) => {
                const isMe = e.message.sender_id == authId;
                if (!isMe) {
                    appendMessage(e.message, false);
                }
            });
    }
});
