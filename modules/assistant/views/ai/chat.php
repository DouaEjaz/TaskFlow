<?php
use yii\helpers\Html;
?>
<div class="container" style="max-width: 700px; margin-top: 30px;">
    <h1>Task Assistant Chat</h1>
    <p class="text-muted">Ask anything about your tasks — type, or use the microphone.</p>

    <div style="max-height: 400px; overflow-y: auto; margin-bottom: 20px;">
        <?php foreach ($history as $turn): ?>
            <p><strong>You:</strong> <?= Html::encode($turn['question']) ?></p>
            <p style="margin-left: 15px;"><strong>Assistant:</strong> <?= Html::encode($turn['answer']) ?></p>
            <hr>
        <?php endforeach; ?>
    </div>

    <form method="post" id="chat-form">
        <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
        <input type="text" name="question" id="question-input" class="form-control" placeholder="Ask a question..." autocomplete="off">
        <button type="button" id="mic-btn" class="btn btn-secondary mt-2">🎤 Speak</button>
        <button type="submit" class="btn btn-primary mt-2">Send</button>
    </form>

    <?= Html::a('Clear chat', ['clear-chat'], ['class' => 'btn btn-link mt-2']) ?>
</div>

<script>
const micBtn = document.getElementById('mic-btn');
const input = document.getElementById('question-input');

if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
    const recognition = new SpeechRecognition();
    recognition.lang = 'en-US';

    micBtn.addEventListener('click', () => {
        recognition.start();
        micBtn.textContent = '🎙️ Listening...';
    });
    recognition.addEventListener('result', (event) => {
        input.value = event.results[0][0].transcript;
        micBtn.textContent = '🎤 Speak';
    });
    recognition.addEventListener('end', () => { micBtn.textContent = '🎤 Speak'; });
} else {
    micBtn.disabled = true;
    micBtn.textContent = 'Voice not supported in this browser';
}
</script>