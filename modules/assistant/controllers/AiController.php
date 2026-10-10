<?php

namespace app\modules\assistant\controllers;

use Yii;
use yii\web\Controller;
use app\models\Task;

class AiController extends Controller
{
    public function actionReport()
    {
        $report = null;

        if (Yii::$app->request->isPost) {
            $tasks = Task::find()->with('category')->all();

            $summary = '';
            foreach ($tasks as $t) {
                $cat = $t->category ? $t->category->name : 'Uncategorized';
                $summary .= "{$t->title} | Status: {$t->status} | Priority: {$t->priority} | "
                    . "Due: {$t->due_date} | Category: {$cat}\n";
            }

            $prompt = "You are a productivity coach. Based on this task list, write a short "
                . "plain-English report: how many tasks are done/in progress/todo, which "
                . "categories have the most tasks, which high-priority tasks need attention, "
                . "and one practical suggestion.\n\n" . $summary;

            $report = $this->markdownToHtml($this->askGemini($prompt));
        }

        return $this->render('report', ['report' => $report]);
    }

    public function actionChat()
    {
        $session = Yii::$app->session;
        $history = $session->get('chatHistory', []);

        if (Yii::$app->request->isPost) {
            $question = trim(Yii::$app->request->post('question'));

            if ($question !== '') {
                $tasks = Task::find()->with('category')->all();
                $context = '';
                foreach ($tasks as $t) {
                    $cat = $t->category ? $t->category->name : 'Uncategorized';
                    $context .= "{$t->title} | {$t->status} | {$t->priority} | due {$t->due_date} | {$cat}\n";
                }

                $prompt = "Here is the current task list:\n{$context}\n"
                    . "Answer this question briefly and clearly: {$question}";

                $answer = $this->askGemini($prompt);
                $history[] = ['question' => $question, 'answer' => $answer];
                $session->set('chatHistory', $history);
            }

            return $this->refresh();
        }

        return $this->render('chat', ['history' => $history]);
    }

    public function actionClearChat()
    {
        Yii::$app->session->remove('chatHistory');
        return $this->redirect(['chat']);
    }

    private function askGemini($prompt)
    {
        $apiKey = Yii::$app->params['geminiApiKey'] ?? '';
        if ($apiKey === '') {
            return 'Gemini API key is not configured. Copy config/params-local.php.example to config/params-local.php and add your key (see README).';
        }
        $url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-3.6-flash:generateContent';

        $payload = ['contents' => [['parts' => [['text' => $prompt]]]]];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'x-goog-api-key: ' . $apiKey]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);
        return $result['candidates'][0]['content']['parts'][0]['text'] ?? 'No response received.';
    }

    private function markdownToHtml($text)
    {
        $text = \yii\helpers\Html::encode($text);
        $lines = preg_split('/\r\n|\r|\n/', $text);
        $html = ''; $inList = false;
        foreach ($lines as $line) {
            $t = trim($line);
            if ($t === '') { if ($inList) { $html .= '</ul>'; $inList = false; } continue; }
            if (preg_match('/^#{1,3}\s+(.+)$/', $t, $m)) { if ($inList) { $html.='</ul>'; $inList=false; } $html .= '<h4>'.$this->inlineFormat($m[1]).'</h4>'; continue; }
            if ($t === '---') { if ($inList) { $html.='</ul>'; $inList=false; } $html .= '<hr>'; continue; }
            if (preg_match('/^[\*\-]\s+(.+)$/', $t, $m)) { if (!$inList) { $html.='<ul>'; $inList=true; } $html .= '<li>'.$this->inlineFormat($m[1]).'</li>'; continue; }
            if (preg_match('/^\d+\.\s+(.+)$/', $t, $m)) { if ($inList) { $html.='</ul>'; $inList=false; } $html .= '<p><strong>'.$this->inlineFormat($m[1]).'</strong></p>'; continue; }
            if ($inList) { $html .= '</ul>'; $inList = false; }
            $html .= '<p>'.$this->inlineFormat($t).'</p>';
        }
        if ($inList) $html .= '</ul>';
        return $html;
    }

    private function inlineFormat($text)
    {
        $text = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $text);
        return preg_replace('/\*(.+?)\*/', '<em>$1</em>', $text);
    }
}