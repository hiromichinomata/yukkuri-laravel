import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('chat-form');
    const input = document.getElementById('chat-input');
    const messages = document.getElementById('chat-messages');

    if (!form || !input || !messages) {
        return;
    }

    const appendMessage = (payload) => {
        const item = document.createElement('div');
        item.className = 'rounded-md bg-gray-50 px-3 py-2 text-sm';
        item.textContent = `${payload.user}: ${payload.message}`;
        messages.appendChild(item);
        messages.scrollTop = messages.scrollHeight;
    };

    if (window.Echo) {
        window.Echo.channel('chat')
            .listen('MessageSent', (event) => {
                appendMessage(event.message);
            });
    }

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const message = input.value.trim();
        if (!message) {
            return;
        }

        const response = await fetch('/chat/send', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            },
            body: JSON.stringify({ message }),
        });

        if (!response.ok) {
            return;
        }

        const json = await response.json();
        appendMessage(json.data);
        input.value = '';
    });
});
