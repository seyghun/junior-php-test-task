<?php

class ReportApp {
    private array $orders;
    private string $file;
    private int $validOrdersCount = 0;
    private float $totalPaid = 0;
    private float $avgAmount = 0;

    public function __construct(array $orders, string $file) {
        $this->orders = $orders;
        $this->file = $file;
    }

    public function process(): void {
        foreach ($this->orders as $order) {
            if (isset($order['status'], $order['amount']) 
                && $order['status'] === 'paid' 
                && $order['amount'] > 0
            ) {
                $this->validOrdersCount++;
                $this->totalPaid += $order['amount'];
            }
        }
        
        if ($this->validOrdersCount > 0) {
            $this->avgAmount = $this->totalPaid / $this->validOrdersCount;
        }
    }

    public function summary(): void {
        echo "Start report\n";
        echo "Valid orders: " . $this->validOrdersCount . "\n";
        echo "Total paid: " . $this->totalPaid . "\n";
        echo "Avg amount: " . $this->avgAmount . "\n";
    }

    public function write(): void {
        $txt = "Total paid = " . $this->totalPaid . PHP_EOL;
        if (file_put_contents($this->file, $txt, LOCK_EX) === false) {
            error_log("Помилка: не вдалося записати файл {$this->file}");
        }
    }

    public function __destruct() {
        $logMessage = "[" . date('Y-m-d H:i:s') . "] Процес генерації звіту завершено для: {$this->file}\n";
        file_put_contents("app.log", $logMessage, FILE_APPEND | LOCK_EX);
    }
}

$orders = [
    ["id"=>1, "user"=>"Ivan", "amount"=>100, "status"=>"paid"],
    ["id"=>2, "user"=>"Oksana", "amount"=>-50, "status"=>"paid"],
    ["id"=>3, "user"=>"Ivan", "amount"=>200, "status"=>"pending"],
    ["id"=>4, "user"=>"Petro", "amount"=>300, "status"=>"paid"],
];

$app = new ReportApp($orders, "report.txt");
$app->process();
$app->summary();
$app->write();