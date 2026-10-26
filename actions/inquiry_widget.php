<?php
$assistantEndpoint = $assistantEndpoint ?? 'actions/inquiry_assistant.php';
$assistantTitle = $assistantTitle ?? 'Inquiry Assistant';
$assistantIntro = $assistantIntro ?? 'Ask about AICS, Solo Parent, requirements, eligibility, or application steps.';
?>
<div class="inquiry-assistant" data-endpoint="<?= htmlspecialchars($assistantEndpoint) ?>">
    <button type="button" class="inquiry-assistant__toggle" aria-expanded="false" aria-controls="inquiryAssistantPanel">
        <span class="inquiry-assistant__toggle-icon" aria-hidden="true">
            <svg viewBox="0 0 64 64" role="img" focusable="false">
                <circle cx="32" cy="12" r="5"></circle>
                <path d="M24 18h16c7.73 0 14 6.27 14 14v12c0 7.73-6.27 14-14 14H24c-7.73 0-14-6.27-14-14V32c0-7.73 6.27-14 14-14Z"></path>
                <circle cx="25" cy="36" r="4"></circle>
                <circle cx="39" cy="36" r="4"></circle>
                <path d="M24 46c2.4 2.67 5.12 4 8 4s5.6-1.33 8-4"></path>
                <path d="M18 27h-6"></path>
                <path d="M52 27h-6"></path>
            </svg>
        </span>
        <span class="inquiry-assistant__toggle-label">Chat</span>
    </button>

    <div class="inquiry-assistant__panel" id="inquiryAssistantPanel" hidden>
        <div class="inquiry-assistant__header">
            <div>
                <strong><?= htmlspecialchars($assistantTitle) ?></strong>
                <p><?= htmlspecialchars($assistantIntro) ?></p>
            </div>
            <button type="button" class="inquiry-assistant__close" aria-label="Close assistant">&times;</button>
        </div>

        <div class="inquiry-assistant__messages"></div>

        <div class="inquiry-assistant__quick-replies">
            <button type="button" data-message="AICS requirements">AICS requirements</button>
            <button type="button" data-message="Solo Parent requirements">Solo Parent requirements</button>
            <button type="button" data-message="How to apply">How to apply</button>
            <button type="button" data-message="What is the difference?">Difference</button>
        </div>

        <form class="inquiry-assistant__form">
            <input type="text" name="message" placeholder="Type your question here..." autocomplete="off">
            <button type="submit">Send</button>
        </form>
    </div>
</div>

<style>
.inquiry-assistant {
    position: fixed;
    right: 20px;
    bottom: 20px;
    z-index: 1200;
    font-family: Arial, sans-serif;
}

.inquiry-assistant__toggle {
    border: none;
    border-radius: 999px;
    background: linear-gradient(135deg, #0b4cc2, #1f78ff);
    color: #fff;
    width: 72px;
    height: 72px;
    padding: 0;
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 14px 34px rgba(29, 75, 216, 0.32);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    position: relative;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.inquiry-assistant__toggle:hover {
    transform: translateY(-2px);
    box-shadow: 0 18px 40px rgba(29, 75, 216, 0.35);
}

.inquiry-assistant__toggle-icon {
    width: 34px;
    height: 34px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.inquiry-assistant__toggle-icon svg {
    width: 100%;
    height: 100%;
    fill: none;
    stroke: currentColor;
    stroke-width: 3;
    stroke-linecap: round;
    stroke-linejoin: round;
}

.inquiry-assistant__toggle-label {
    position: absolute;
    right: 78px;
    white-space: nowrap;
    background: #ffffff;
    color: #123a7a;
    border-radius: 999px;
    padding: 8px 12px;
    font-size: 12px;
    font-weight: 700;
    box-shadow: 0 10px 24px rgba(20, 30, 55, 0.14);
}

.inquiry-assistant__panel {
    width: min(360px, calc(100vw - 24px));
    height: 520px;
    background: #fff;
    border-radius: 18px;
    box-shadow: 0 18px 40px rgba(20, 30, 55, 0.18);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    margin-top: 12px;
    border: 1px solid #d8e4ff;
}

.inquiry-assistant__panel[hidden] {
    display: none !important;
}

.inquiry-assistant__header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 10px;
    padding: 16px;
    background: linear-gradient(135deg, #0d47a1, #1976d2);
    color: #fff;
}

.inquiry-assistant__header p {
    margin: 4px 0 0;
    font-size: 13px;
    color: rgba(255, 255, 255, 0.9);
}

.inquiry-assistant__close {
    border: none;
    background: transparent;
    color: #fff;
    font-size: 24px;
    line-height: 1;
    cursor: pointer;
}

.inquiry-assistant__messages {
    flex: 1;
    overflow-y: auto;
    padding: 14px;
    background: #f7faff;
}

.inquiry-assistant__message {
    max-width: 85%;
    margin-bottom: 10px;
    padding: 10px 12px;
    border-radius: 14px;
    line-height: 1.45;
    font-size: 14px;
    white-space: pre-wrap;
}

.inquiry-assistant__message--bot {
    background: #e7f0ff;
    color: #17315e;
    border-bottom-left-radius: 4px;
}

.inquiry-assistant__message--user {
    background: #1d4bd8;
    color: #fff;
    margin-left: auto;
    border-bottom-right-radius: 4px;
}

.inquiry-assistant__quick-replies {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    padding: 12px 12px 0;
    background: #fff;
}

.inquiry-assistant__quick-replies button {
    border: 1px solid #bfd3ff;
    background: #f3f7ff;
    color: #1d4bd8;
    border-radius: 999px;
    padding: 7px 11px;
    font-size: 12px;
    cursor: pointer;
}

.inquiry-assistant__form {
    display: flex;
    gap: 8px;
    padding: 12px;
    border-top: 1px solid #e7eefb;
    background: #fff;
}

.inquiry-assistant__form input {
    flex: 1;
    border: 1px solid #bfd3ff;
    border-radius: 10px;
    padding: 10px 12px;
    font-size: 14px;
}

.inquiry-assistant__form button {
    border: none;
    border-radius: 10px;
    background: #1d4bd8;
    color: #fff;
    padding: 10px 14px;
    font-weight: 700;
    cursor: pointer;
}

@media (max-width: 640px) {
    .inquiry-assistant {
        right: 12px;
        bottom: 12px;
    }

    .inquiry-assistant__toggle {
        width: 64px;
        height: 64px;
    }

    .inquiry-assistant__panel {
        width: calc(100vw - 24px);
        height: 70vh;
    }

    .inquiry-assistant__toggle-label {
        display: none;
    }
}
</style>

<script>
(function () {
    const assistant = document.querySelector('.inquiry-assistant:last-of-type');
    if (!assistant || assistant.dataset.bound === 'true') {
        return;
    }
    assistant.dataset.bound = 'true';

    const endpoint = assistant.dataset.endpoint;
    const toggle = assistant.querySelector('.inquiry-assistant__toggle');
    const panel = assistant.querySelector('.inquiry-assistant__panel');
    const closeButton = assistant.querySelector('.inquiry-assistant__close');
    const messages = assistant.querySelector('.inquiry-assistant__messages');
    const form = assistant.querySelector('.inquiry-assistant__form');
    const input = form.querySelector('input[name="message"]');
    const quickReplies = assistant.querySelector('.inquiry-assistant__quick-replies');

    function appendMessage(role, text) {
        const bubble = document.createElement('div');
        bubble.className = 'inquiry-assistant__message inquiry-assistant__message--' + role;
        bubble.textContent = text;
        messages.appendChild(bubble);
        messages.scrollTop = messages.scrollHeight;
    }

    function replaceQuickReplies(suggestions) {
        quickReplies.innerHTML = '';
        (suggestions || []).slice(0, 4).forEach((suggestion) => {
            const button = document.createElement('button');
            button.type = 'button';
            button.dataset.message = suggestion;
            button.textContent = suggestion;
            quickReplies.appendChild(button);
        });
    }

    async function sendMessage(message) {
        const trimmed = message.trim();
        if (!trimmed) {
            return;
        }

        appendMessage('user', trimmed);
        input.value = '';

        try {
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ message: trimmed }),
            });

            const data = await response.json();
            appendMessage('bot', data.reply || 'Sorry, I could not process that inquiry right now.');
            replaceQuickReplies(data.suggestions || []);
        } catch (error) {
            appendMessage('bot', 'The inquiry assistant is temporarily unavailable. Please try again later.');
        }
    }

    function openAssistant() {
        panel.hidden = false;
        panel.style.display = 'flex';
        toggle.setAttribute('aria-expanded', 'true');
        if (!messages.dataset.started) {
            messages.dataset.started = 'true';
            sendMessage('hello');
        }
        setTimeout(() => input.focus(), 50);
    }

    function closeAssistant() {
        panel.hidden = true;
        panel.style.display = 'none';
        toggle.setAttribute('aria-expanded', 'false');
    }

    toggle.addEventListener('click', () => {
        if (panel.hidden) {
            openAssistant();
        } else {
            closeAssistant();
        }
    });

    closeButton.addEventListener('click', (event) => {
        event.preventDefault();
        event.stopPropagation();
        closeAssistant();
    });

    form.addEventListener('submit', (event) => {
        event.preventDefault();
        sendMessage(input.value);
    });

    quickReplies.addEventListener('click', (event) => {
        const button = event.target.closest('button[data-message]');
        if (!button) {
            return;
        }
        sendMessage(button.dataset.message);
    });
})();
</script>
