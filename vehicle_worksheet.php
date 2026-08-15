<?php
// vehicle_worksheet.php
// A single-file vehicle price / retail payment / lease payment worksheet.
// Change the default values below to fit your dealership.

$defaults = [
    'msrp' => 61735.00,
    'trade_allowance' => 0.00,
    'trade_payoff' => 0.00,
    'total_before_ttl' => 61735.00,

    // Retail financing defaults
    'retail_apr' => 6.99,
    'retail_rebate' => 0.00,

    // Lease defaults
    'lease_money_factor' => 0.00250,
    'lease_rebate' => 0.00,
    'lease_sales_tax' => 5.00
];

$retailInvestments = [30000, 35000, 41000];
$retailTerms = [48, 60, 72];

$leaseInvestments = [20000, 25000, 30000];
$leaseTerms = [
    ['months' => 36, 'miles' => 12000, 'residual_percent' => 60],
    ['months' => 39, 'miles' => 12000, 'residual_percent' => 58],
    ['months' => 48, 'miles' => 12000, 'residual_percent' => 52]
];

function old($key, $default = '') {
    return htmlspecialchars($_POST[$key] ?? $default, ENT_QUOTES, 'UTF-8');
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = 'Worksheet values received successfully.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Vehicle Purchase Worksheet</title>

<style>
    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        padding: 25px;
        background: #f2f2f2;
        font-family: Arial, Helvetica, sans-serif;
        color: #222;
    }

    .sheet {
        max-width: 1200px;
        margin: 0 auto;
        background: #fff;
        border: 2px solid #333;
        box-shadow: 0 4px 16px rgba(0,0,0,.12);
    }

    .top-grid,
    .bottom-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
    }

    .box {
        min-height: 360px;
        border-right: 1px solid #333;
        border-bottom: 1px solid #333;
        padding: 18px 24px 25px;
    }

    .top-grid .box:nth-child(2),
    .bottom-grid .box:nth-child(2) {
        border-right: 0;
    }

    .bottom-grid .box {
        border-bottom: 0;
        min-height: 430px;
    }

    .box-title {
        text-align: center;
        font-size: 22px;
        font-weight: 500;
        letter-spacing: .4px;
        margin: -18px -24px 25px;
        padding: 10px 0;
        border-bottom: 1px solid #333;
    }

    .price-row {
        display: grid;
        grid-template-columns: 1.35fr 1fr;
        align-items: center;
        gap: 16px;
        margin: 18px 0;
    }

    .price-row label {
        font-size: 19px;
    }

    .money-wrap {
        position: relative;
    }

    .money-wrap::before {
        content: "$";
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 18px;
    }

    .money-input,
    .text-input,
    .small-input {
        width: 100%;
        border: 0;
        border-bottom: 2px solid #666;
        background: transparent;
        padding: 7px 8px;
        font-size: 18px;
        outline: none;
    }

    .money-input {
        padding-left: 25px;
        text-align: right;
    }

    .money-input:focus,
    .text-input:focus,
    .small-input:focus {
        border-bottom-color: #000;
        background: #fafafa;
    }

    .includes-line {
        margin: 16px 0;
    }

    .includes-line input {
        font-size: 18px;
    }

    .settings {
        display: grid;
        grid-template-columns: repeat(2, minmax(150px, 1fr));
        gap: 12px 18px;
        margin-bottom: 20px;
        padding: 12px;
        background: #f8f8f8;
        border: 1px solid #ddd;
    }

    .setting label {
        display: block;
        font-size: 13px;
        font-weight: bold;
        margin-bottom: 4px;
    }

    .setting input {
        width: 100%;
        padding: 7px;
        font-size: 15px;
        border: 1px solid #aaa;
    }

    .payment-table {
        width: 100%;
        border-collapse: collapse;
        table-layout: fixed;
    }

    .payment-table th,
    .payment-table td {
        padding: 8px 5px;
        text-align: center;
        vertical-align: middle;
    }

    .payment-table th {
        font-size: 14px;
        text-decoration: underline;
        font-weight: normal;
    }

    .investment-input {
        width: 95px;
        border: 0;
        border-bottom: 1px solid #555;
        font-size: 17px;
        text-align: center;
        padding: 3px;
    }

    .payment {
        font-size: 20px;
        font-weight: 600;
        text-decoration: underline;
        white-space: nowrap;
    }

    .rebate-label {
        display: block;
        margin-top: 7px;
        font-size: 12px;
    }

    .rebate-value {
        display: block;
        font-size: 12px;
        text-decoration: underline;
        margin-top: 3px;
    }

    .message {
        max-width: 1200px;
        margin: 0 auto 12px;
        padding: 10px 14px;
        background: #eaf6ea;
        border: 1px solid #9ac79a;
    }

    .actions {
        max-width: 1200px;
        margin: 18px auto 0;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }

    button {
        padding: 11px 18px;
        font-size: 16px;
        cursor: pointer;
        border: 1px solid #444;
        background: white;
    }

    button:hover {
        background: #eee;
    }

    .note {
        max-width: 1200px;
        margin: 15px auto;
        font-size: 13px;
        line-height: 1.5;
        color: #555;
    }

    @media (max-width: 850px) {
        .top-grid,
        .bottom-grid {
            grid-template-columns: 1fr;
        }

        .box {
            border-right: 0;
        }

        .price-row {
            grid-template-columns: 1fr;
            gap: 5px;
        }

        .sheet {
            min-width: 650px;
        }

        body {
            overflow-x: auto;
        }
    }

    @media print {
        body {
            background: white;
            padding: 0;
        }

        .sheet {
            box-shadow: none;
            max-width: none;
        }

        .actions,
        .note,
        .message {
            display: none;
        }

        .settings {
            border: 0;
            padding: 0;
            background: transparent;
        }

        input {
            color: #000;
        }
    }
</style>
</head>

<body>
<?php 
require("toolbar_sales.php"); 

    if(isset($_GET['find'])){
            $_SESSION['find'] = $_GET['find'];
          
          }
          
          $find = $_SESSION['find'];

?>
<?php if ($message): ?>
<div class="message"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<form method="post" id="worksheetForm">
<div class="sheet">

    <!-- TOP ROW -->
    <div class="top-grid">

        <!-- PRICE BOX -->
        <section class="box">
            <div class="box-title">PRICE</div>

            <div class="price-row">
                <label for="msrp">MSRP/BUYER'S GUIDE:</label>
                <div class="money-wrap">
                    <input class="money-input calc-source"
                           type="number"
                           id="msrp"
                           name="msrp"
                           step="0.01"
                           value="<?= old('msrp', $defaults['msrp']) ?>">
                </div>
            </div>

            <div class="price-row">
                <label for="trade_allowance">TRADE ALLOWANCE:</label>
                <div class="money-wrap">
                    <input class="money-input calc-source"
                           type="number"
                           id="trade_allowance"
                           name="trade_allowance"
                           step="0.01"
                           value="<?= old('trade_allowance', $defaults['trade_allowance']) ?>">
                </div>
            </div>

            <div class="price-row">
                <label for="trade_payoff">TRADE PAYOFF:</label>
                <div class="money-wrap">
                    <input class="money-input calc-source"
                           type="number"
                           id="trade_payoff"
                           name="trade_payoff"
                           step="0.01"
                           value="<?= old('trade_payoff', $defaults['trade_payoff']) ?>">
                </div>
            </div>

            <div class="price-row">
                <label for="total_before_ttl">
                    TOTAL BEFORE TAX, TITLE,<br>LICENSE, AND FEES:
                </label>
                <div class="money-wrap">
                    <input class="money-input"
                           type="number"
                           id="total_before_ttl"
                           name="total_before_ttl"
                           step="0.01"
                           value="<?= old('total_before_ttl', $defaults['total_before_ttl']) ?>">
                </div>
            </div>
        </section>

        <!-- INCLUDES BOX -->
        <section class="box">
            <div class="box-title">INCLUDES</div>

            <?php for ($i = 1; $i <= 6; $i++): ?>
                <div class="includes-line">
                    <input class="text-input"
                           type="text"
                           name="include_<?= $i ?>"
                           id="include_<?= $i ?>"
                           placeholder="Enter item <?= $i ?>"
                           value="<?= old("include_$i") ?>">
                </div>
            <?php endfor; ?>
        </section>
    </div>

    <!-- BOTTOM ROW -->
    <div class="bottom-grid">

        <!-- RETAIL PAYMENTS -->
        <section class="box">
            <div class="box-title">RETAIL PAYMENTS</div>

            <div class="settings">
                <div class="setting">
                    <label for="retail_apr">Retail APR %</label>
                    <input type="number"
                           id="retail_apr"
                           name="retail_apr"
                           step="0.01"
                           value="<?= old('retail_apr', $defaults['retail_apr']) ?>">
                </div>

                <div class="setting">
                    <label for="retail_rebate">Retail Rebate $</label>
                    <input type="number"
                           id="retail_rebate"
                           name="retail_rebate"
                           step="0.01"
                           value="<?= old('retail_rebate', $defaults['retail_rebate']) ?>">
                </div>
            </div>

            <table class="payment-table">
                <thead>
                    <tr>
                        <th>INVESTMENT</th>
                        <?php foreach ($retailTerms as $term): ?>
                            <th><?= $term ?> months</th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($retailInvestments as $row => $investment): ?>
                    <tr>
                        <td>
                            $<input type="number"
                                   class="investment-input retail-investment"
                                   name="retail_investment_<?= $row ?>"
                                   step="100"
                                   value="<?= old("retail_investment_$row", $investment) ?>">
                            <span class="rebate-label">Rebate</span>
                            <span class="rebate-value retail-rebate-display">$0.00</span>
                        </td>

                        <?php foreach ($retailTerms as $term): ?>
                            <td>
                                <span class="payment retail-payment"
                                      data-row="<?= $row ?>"
                                      data-term="<?= $term ?>">$0</span>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </section>

        <!-- LEASE PAYMENTS -->
        <section class="box">
            <div class="box-title">LEASE PAYMENTS</div>

            <div class="settings">
                <div class="setting">
                    <label for="lease_money_factor">Money Factor</label>
                    <input type="number"
                           id="lease_money_factor"
                           name="lease_money_factor"
                           step="0.00001"
                           value="<?= old('lease_money_factor', $defaults['lease_money_factor']) ?>">
                </div>

                <div class="setting">
                    <label for="lease_rebate">Lease Rebate $</label>
                    <input type="number"
                           id="lease_rebate"
                           name="lease_rebate"
                           step="0.01"
                           value="<?= old('lease_rebate', $defaults['lease_rebate']) ?>">
                </div>

                <div class="setting">
                    <label for="lease_sales_tax">Lease Tax %</label>
                    <input type="number"
                           id="lease_sales_tax"
                           name="lease_sales_tax"
                           step="0.01"
                           value="<?= old('lease_sales_tax', $defaults['lease_sales_tax']) ?>">
                </div>
            </div>

            <table class="payment-table">
                <thead>
                    <tr>
                        <th>INVESTMENT</th>
                        <?php foreach ($leaseTerms as $index => $lease): ?>
                            <th>
                                <?= $lease['months'] ?> Mo / <?= $lease['miles'] ?> Mi
                                <br>
                                <small>
                                    Residual
                                    <input type="number"
                                           class="lease-residual"
                                           data-column="<?= $index ?>"
                                           name="lease_residual_<?= $index ?>"
                                           value="<?= old("lease_residual_$index", $lease['residual_percent']) ?>"
                                           step="0.1"
                                           style="width:55px;">%
                                </small>
                            </th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($leaseInvestments as $row => $investment): ?>
                    <tr>
                        <td>
                            $<input type="number"
                                   class="investment-input lease-investment"
                                   name="lease_investment_<?= $row ?>"
                                   step="100"
                                   value="<?= old("lease_investment_$row", $investment) ?>">
                            <span class="rebate-label">Rebate</span>
                            <span class="rebate-value lease-rebate-display">$0.00</span>
                        </td>

                        <?php foreach ($leaseTerms as $column => $lease): ?>
                            <td>
                                <span class="payment lease-payment"
                                      data-row="<?= $row ?>"
                                      data-column="<?= $column ?>"
                                      data-term="<?= $lease['months'] ?>">$0</span>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </section>

    </div>
</div>

<div class="actions">
    <button type="button" id="calculateBtn">Calculate Payments</button>
    <button type="button" id="calculateTotalBtn">Calculate Price Total</button>
    <button type="button" onclick="window.print()">Print Worksheet</button>
    <button type="submit">Save / Submit</button>
</div>
</form>

<div class="note">
    Retail payments use the standard amortized loan formula. Lease payments use a basic
    depreciation + finance charge formula. Actual dealership lease figures can differ because
    acquisition fees, taxes, residual rules, incentives, security deposits, registration,
    dealer fees and manufacturer programs may vary.
</div>

<script>
function numberValue(id) {
    const value = parseFloat(document.getElementById(id).value);
    return Number.isFinite(value) ? value : 0;
}

function money(value, decimals = 0) {
    return '$' + value.toLocaleString('en-US', {
        minimumFractionDigits: decimals,
        maximumFractionDigits: decimals
    });
}

// PRICE:
// Vehicle price - trade allowance + trade payoff
function calculatePriceTotal() {
    const msrp = numberValue('msrp');
    const tradeAllowance = numberValue('trade_allowance');
    const tradePayoff = numberValue('trade_payoff');

    const total = msrp - tradeAllowance + tradePayoff;
    document.getElementById('total_before_ttl').value = total.toFixed(2);

    calculateAllPayments();
}

// Standard retail loan payment
function calculateRetailPayment(principal, annualRate, months) {
    if (principal <= 0) return 0;

    const monthlyRate = annualRate / 100 / 12;

    if (monthlyRate === 0) {
        return principal / months;
    }

    return principal *
        (monthlyRate * Math.pow(1 + monthlyRate, months)) /
        (Math.pow(1 + monthlyRate, months) - 1);
}

function calculateRetail() {
    const sellingPrice = numberValue('total_before_ttl');
    const apr = numberValue('retail_apr');
    const rebate = numberValue('retail_rebate');

    document.querySelectorAll('.retail-rebate-display').forEach(el => {
        el.textContent = money(rebate, 2);
    });

    const investments = [...document.querySelectorAll('.retail-investment')];

    document.querySelectorAll('.retail-payment').forEach(paymentEl => {
        const row = parseInt(paymentEl.dataset.row, 10);
        const months = parseInt(paymentEl.dataset.term, 10);
        const investment = parseFloat(investments[row].value) || 0;

        const amountFinanced = Math.max(0, sellingPrice - investment - rebate);
        const payment = calculateRetailPayment(amountFinanced, apr, months);

        paymentEl.textContent = money(Math.round(payment), 0) + "'s";
        paymentEl.title =
            'Estimated exact payment: ' + money(payment, 2) +
            ' | Amount financed: ' + money(amountFinanced, 2);
    });
}

// Basic lease formula:
// Adjusted cap cost = selling price - investment - rebate
// Residual value = MSRP x residual percentage
// Depreciation = (cap cost - residual) / term
// Finance charge = (cap cost + residual) x money factor
// Payment before tax = depreciation + finance charge
// Payment with tax = payment before tax x (1 + tax rate)
function calculateLease() {
    const msrp = numberValue('msrp');
    const sellingPrice = numberValue('total_before_ttl');
    const moneyFactor = numberValue('lease_money_factor');
    const rebate = numberValue('lease_rebate');
    const taxRate = numberValue('lease_sales_tax') / 100;

    document.querySelectorAll('.lease-rebate-display').forEach(el => {
        el.textContent = money(rebate, 2);
    });

    const investments = [...document.querySelectorAll('.lease-investment')];
    const residualInputs = [...document.querySelectorAll('.lease-residual')];

    document.querySelectorAll('.lease-payment').forEach(paymentEl => {
        const row = parseInt(paymentEl.dataset.row, 10);
        const column = parseInt(paymentEl.dataset.column, 10);
        const months = parseInt(paymentEl.dataset.term, 10);

        const investment = parseFloat(investments[row].value) || 0;
        const residualPercent = parseFloat(residualInputs[column].value) || 0;

        const adjustedCapCost = Math.max(0, sellingPrice - investment - rebate);
        const residualValue = msrp * (residualPercent / 100);

        const depreciation =
            Math.max(0, adjustedCapCost - residualValue) / months;

        const financeCharge =
            (adjustedCapCost + residualValue) * moneyFactor;

        const beforeTax = depreciation + financeCharge;
        const payment = beforeTax * (1 + taxRate);

        paymentEl.textContent = money(Math.round(payment), 0) + "'s";
        paymentEl.title =
            'Estimated exact lease payment: ' + money(payment, 2) +
            ' | Residual value: ' + money(residualValue, 2) +
            ' | Adjusted cap cost: ' + money(adjustedCapCost, 2);
    });
}

function calculateAllPayments() {
    calculateRetail();
    calculateLease();
}

document.getElementById('calculateBtn')
    .addEventListener('click', calculateAllPayments);

document.getElementById('calculateTotalBtn')
    .addEventListener('click', calculatePriceTotal);

// Recalculate live whenever a payment-related value changes.
document.querySelectorAll(
    '#msrp, #total_before_ttl, #retail_apr, #retail_rebate, ' +
    '#lease_money_factor, #lease_rebate, #lease_sales_tax, ' +
    '.retail-investment, .lease-investment, .lease-residual'
).forEach(input => {
    input.addEventListener('input', calculateAllPayments);
});

window.addEventListener('DOMContentLoaded', calculateAllPayments);
</script>

<h1>
<button id="start-record-btn">Start Recording</button>
  <button id="stop-record-btn" disabled>Stop Recording</button>
  <audio id="audio-playback" controls></audio>
</h1>
  <br>
  <br>

  <?php

echo "<form action=objection_training.php?find=$find method=post>";

?>



<h1><input   type="submit" name="objection" value="Objection"/></h1>
  <script>
    let mediaRecorder;
    let audioChunks = [];

    const startRecordBtn = document.getElementById('start-record-btn');
    const stopRecordBtn = document.getElementById('stop-record-btn');
    const audioPlayback = document.getElementById('audio-playback');

    startRecordBtn.addEventListener('click', async () => {
      const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
      mediaRecorder = new MediaRecorder(stream);
      
      mediaRecorder.start();
      startRecordBtn.disabled = true;
      stopRecordBtn.disabled = false;

      mediaRecorder.ondataavailable = event => {
        audioChunks.push(event.data);
      };

      mediaRecorder.onstop = async () => {
        const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
        const audioUrl = URL.createObjectURL(audioBlob);
        audioPlayback.src = audioUrl;

        // Send audio data to server
        const formData = new FormData();
        formData.append('audio', audioBlob, 'recording.wav');

        await fetch('upload_audio.php', {
          method: 'POST',
          body: formData
        });

        audioChunks = [];
        startRecordBtn.disabled = false;
      };
    });

    stopRecordBtn.addEventListener('click', () => {
      mediaRecorder.stop();
      stopRecordBtn.disabled = true;
    });

  
    
  </script>

</body>
</html>
