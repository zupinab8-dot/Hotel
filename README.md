# madeira madeira 
Tema: Sistema de agendamento de venda de madeira 

Bom, o sistema vai funcionar de uma forma para organizar clientes, controlar estoque, registrar vendas e acompanhar o faturamento, tornando a gestão da empresa mais eficiente.
basicamente essa sera a atualização


<?php

$clientes = [];
$produtos = [];
$vendas = [];
$faturamento = 0;

$clientes[] = [
    "nome" => "João",
    "telefone" => "11999999999"
];

//
$produtos[] = [
    "nome" => "Madeira Pinus",
    "quantidade" => 100,
    "preco" => 50
];

$produtos[] = [
    "nome" => "Madeira MDF",
    "quantidade" => 80,
    "preco" => 70
];

$quantidadeVendida = 2;
$valorVenda = $produtos[0]["preco"] * $quantidadeVendida;

$produtos[0]["quantidade"] -= $quantidadeVendida;
$faturamento += $valorVenda;

$vendas[] = [
    "cliente" => "João",
    "produto" => "Madeira Pinus",
    "quantidade" => $quantidadeVendida,
    "total" => $valorVenda
];

echo "<h2>Sistema de Gestão</h2>";

echo "<h3>Clientes</h3>";
foreach ($clientes as $cliente) {
    echo "Nome: " . $cliente["nome"] . " - Telefone: " . $cliente["telefone"] . "<br>";
}

echo "<h3>Estoque</h3>";
foreach ($produtos as $produto) {
    echo $produto["nome"] . " - Quantidade: " . $produto["quantidade"] . " - Preço: R$ " . $produto["preco"] . "<br>";
}

echo "<h3>Vendas</h3>";
foreach ($vendas as $venda) {
    echo "Cliente: " . $venda["cliente"] . " | Produto: " . $venda["produto"] . " | Quantidade: " . $venda["quantidade"] . " | Total: R$ " . $venda["total"] . "<br>";
}

echo "<h3>Faturamento Total: R$ " . $faturamento . "</h3>";
?>
