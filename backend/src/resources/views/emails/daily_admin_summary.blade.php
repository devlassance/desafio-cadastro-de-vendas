<!DOCTYPE html>
<html lang="pt-BR">
<head><meta charset="utf-8"><title>Resumo diário do sistema</title></head>
<body>
<p>Resumo geral de {{ $date }}:</p>
<ul>
    <li>Soma total das vendas: R$ {{ number_format($totalAmount, 2, ',', '.') }}</li>
</ul>
</body>
</html>
