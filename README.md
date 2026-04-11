# madeira madeira 
Tema: Sistema de agendamento de venda de madeira 

Bom, o sistema vai funcionar de uma forma para organizar clientes, controlar estoque, registrar vendas e acompanhar o faturamento, tornando a gestão da empresa mais eficiente.
basicamente essa sera a atualização


<?php


class Madeira {
    private string $tipo;
    private float $preco;
    private float $quantidade; // Em m² ou unidades

    public function __construct(string $tipo, float $preco, float $quantidade) {
        $this->tipo = $tipo;
        $this->setPreco($preco);
        $this->quantidade = $quantidade;
    }

    public function getTipo(): string { return $this->tipo; }
    public function getPreco(): float { return $this->preco; }

    public function setPreco(float $preco): void {
        if ($preco < 0) throw new InvalidArgumentException("Preço inválido!");
        $this->preco = $preco;
    }
}


abstract class Pagamento {
    protected float $valorTotal;

    public function __construct(float $valor) {
        $this->valorTotal = $valor;
    }

    abstract public function processar(): string;
}

class PagamentoPix extends Pagamento {
    private string $chave = "madeireira@contato.com";
    public function processar(): string {
        return "Gerando QR Code para R$ {$this->valorTotal} via PIX (Chave: {$this->chave}).";
    }
}

class PagamentoCartao extends Pagamento {
    public function processar(): string {
        return "Processando pagamento de R$ {$this->valorTotal} no cartão de crédito/débito.";
    }
}

{
    public string $cliente;
    public Madeira $item;
    public string $dataHora;

    public function __construct(string $cliente, Madeira $item, string $dataHora) {
        $this->cliente = $cliente;
        $this->item = $item;
        $this->dataHora = $dataHora;
    }

    public function salvarAgendamento() {
        $dados = [
            'cliente' => $this->cliente,
            'madeira' => $this->item->getTipo(),
            'valor'   => $this->item->getPreco(),
            'data'    => $this->dataHora
        ];
        
        
        $json = json_encode($dados, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents("agendamentos.json", $json . PHP_EOL, FILE_APPEND);
        return "Agendamento de {$this->item->getTipo()} salvo com sucesso!";
    }
}



$madeiraSelecionada = new Madeira("Pinho", 150.00, 10);

$novoAgendamento = new Agendamento("João Silva", $madeiraSelecionada, "2026-04-15 14:00");
echo $novoAgendamento->salvarAgendamento() . "\n";

$pagamento = new PagamentoPix($madeiraSelecionada->getPreco());
echo $pagamento->processar();

$pagamento = new PagamentoPix($madeiraSelecionada->getPreco());
echo $pagamento->processar();
