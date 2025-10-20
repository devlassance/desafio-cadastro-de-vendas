<!DOCTYPE html>
<html lang="pt-BR">
<head><meta charset="utf-8"><title>Resumo diário</title></head>
<body>
<p>Olá, {{ $sellerName }}!</p>
<p>Resumo de {{ $date }}:</p>
<ul>
    <li>Quantidade de vendas: {{ $salesCount }}</li>
    <li>Valor total das vendas: R$ {{ number_format($totalAmount, 2, ',', '.') }}</li>
    <li>Valor total das comissões: R$ {{ number_format($totalCommission, 2, ',', '.') }}</li>
</ul>
<p>Até amanhã.</p>
</body>
</html>
