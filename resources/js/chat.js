// // Wait for the DOM to be fully loaded
// document.addEventListener('DOMContentLoaded', function () {
//     // Find the message form, which now holds our data
//     const messageForm = document.getElementById('message-form');
//     if (!messageForm) {
//         return; // Exit if the form isn't on the page
//     }

//     // Read data from the form's data-* attributes
//     const userId = parseInt(messageForm.dataset.userId, 10);
//     const chatId = messageForm.dataset.chatId;
//     const sendRoute = messageForm.dataset.sendRoute;

//     // Get other elements
//     const receiverId = document.getElementById('receiver_id').value;
//     const messagesDiv = document.getElementById('messages');
//     const bodyInput = document.getElementById('body');
//     const sendBtn = document.getElementById('sendBtn');
//     const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

//     // Scroll to the bottom of the messages
//     if (messagesDiv) {
//         messagesDiv.scrollTop = messagesDiv.scrollHeight;
//     }

//     /**
//      * Appends a message to the chat window.
//      * @param {object} data - The message data.
//      * @param {boolean} [me=false] - True if the message is from the current user.
//      */
//     function appendMessage(data, me = false) {
//         if (!messagesDiv) return;

//         const el = document.createElement('div');
//         el.classList.add('mb-2');

//         // Use data.sender.firstName if it exists, otherwise default to 'Other'
//         const senderName = me ? 'You' : (data.sender?.firstName || 'Other');

//         el.innerHTML = `<strong>${senderName}</strong>: ${data.body} <div><small class="text-gray-400">${data.created_at}</small></div>`;
//         messagesDiv.appendChild(el);
//         messagesDiv.scrollTop = messagesDiv.scrollHeight; // Scroll to new message
//     }

//     /**
//      * Handles sending a message.
//      */
//     async function sendMessage() {
//         const body = bodyInput.value.trim();
//         if (!body) return;

//         try {
//             const res = await axios.post(sendRoute, {
//                 receiver_id: receiverId,
//                 body: body,
//                 chatId: chatId
//             }, {
//                 headers: {'X-CSRF-TOKEN': csrfToken}
//             });

//             // Append our own message immediately
//             appendMessage({
//                 sender: {firstName: 'You'}, // We know this is 'You'
//                 body: body,
//                 created_at: res.data.message.created_at
//             }, true);

//             bodyInput.value = ''; // Clear input
//         } catch (err) {
//             console.error('Message failed to send:', err);
//             alert('Message failed to send. Please try again.');
//         }
//     }

//     // --- Event Listeners ---

//     // Send message on button click
//     if (sendBtn) {
//         sendBtn.addEventListener('click', sendMessage);
//     }

//     // Send message on Enter key press in the input field
//     if (bodyInput) {
//         bodyInput.addEventListener('keypress', function (e) {
//             if (e.key === 'Enter') {
//                 e.preventDefault(); // Prevent default form submission
//                 sendMessage();
//             }
//         });
//     }

//     // Setup Echo to listen for incoming messages
//     // Echo is available via resources/js/bootstrap.js which is imported by app.js
//     if (window.Echo) {
//         window.Echo.private('chat.' + chatId)
//             .listen('MessageSent', (e) => {
//                 // Don't duplicate our own messages
//                 if (e.sender_id === userId) return;

//                 appendMessage({
//                     sender: e.sender,
//                     body: e.body,
//                     created_at: e.created_at
//                 }, false);
//             });
//     } else {
//         console.error('Laravel Echo not found. Real-time messages will not work.');
//     }
// });

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

    const handleSend = () => {
        if (messageBody.value.trim() === '') {
            return;
        }

        axios.post(sendUrl, {
            _token: csrfToken,
            receiver_id: receiverId,
            body: messageBody.value,
            chatId: chatId,
        })
        .then(response => {
            console.log('Message sent');
        })
        .catch(error => {
            console.error('Error sending message:', error);
        });

        messageBody.value = '';
    };

    // Handle form submit
    messageForm.addEventListener('submit', (e) => {
        e.preventDefault();
        handleSend();
    });

    // Handle button click
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
        Echo.private('chat.' + chatId)
            .listen('MessageSent', (e) => {
                const isMe = e.message.sender_id == authId; // Use authId from data attribute

                // Create new message elements based on the new structure
                const bubbleEl = document.createElement('div');
                bubbleEl.className = `message-bubble ${isMe ? 'sender-me' : 'sender-other'}`;

                const contentEl = document.createElement('div');
                contentEl.className = `message-content ${isMe ? 'sender-me' : 'sender-other'}`;

                const textEl = document.createElement('p');
                textEl.textContent = e.message.body;

                const timeEl = document.createElement('p');
                timeEl.className = `message-timestamp ${isMe ? 'sender-me' : 'sender-other'}`;

                // Format timestamp (e.g., 10:30 AM)
                const messageDate = new Date(e.message.created_at);
                timeEl.textContent = messageDate.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                // Assemble the message
                contentEl.appendChild(textEl);
                contentEl.appendChild(timeEl);
                bubbleEl.appendChild(contentEl);

                // Add to container and scroll
                messagesEl.appendChild(bubbleEl);
                messagesEl.scrollTop = messagesEl.scrollHeight;
            });
    }
});
