<?php
use yii\helpers\Html;
?>
<div class="container" style="max-width: 800px; margin-top: 30px;">
    <h1>Task Report</h1>
    <form method="post" class="mb-4 no-print">
        <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
        <button type="submit" class="btn btn-primary">Generate Report</button>
        <?php if ($report): ?><button type="button" class="btn btn-secondary" onclick="window.print()">Print / Save as PDF</button><?php endif; ?>
    </form>
    <?php if ($report): ?>
        <div class="card"><div class="card-body" style="line-height:1.6;"><?= $report ?></div></div>
    <?php else: ?>
        <p class="text-muted"><em>No report yet — click Generate Report.</em></p>
    <?php endif; ?>
</div>
<style>@media print { .no-print { display: none !important; } }</style>