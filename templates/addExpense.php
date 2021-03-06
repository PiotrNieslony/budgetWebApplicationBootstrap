<?php

if (!isset($budget)) {
    die();
} ?>
<header>
    <h1>Dodaj wydatek</h1>
</header>
<div class="add-expense">
    <form role="form .form-horizontal" method="post">
        <div class="row">
            <div class="col-md-6">
                <?= (isset($_SESSION['success'])) ? "<p class=\"alert alert-success\">" . $_SESSION['success'] . "</p>" : "";
                unset($_SESSION['success']); ?>
                <div class="row limit-message-row">
                    <div class="col-md-12">
                        <p>Informacja o limicie: </p>
                        <span class="limit-message">Wpisz kwotę</span>
                    </div>
                </div>
                <div class="row limit-value" style="display: none;">
                    <div class="col-md-3">
                        <p>Limit: </p>
                        <span class="limit-amount"></span>
                    </div>
                    <div class="col-md-3">
                        <p>Dotychcas wydano: </p>
                        <span class="spent-amount"></span>
                    </div>
                    <div class="col-md-3">
                        <p>Różnica: </p>
                        <span class="limit-subtraction"></span>
                    </div>
                    <div class="col-md-3">
                        <p>Wydatki + wpisana kwota: </p>
                        <span class="spent-amount-plus-typed-amount"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong>Kwota</strong>
                            <input name="expenseAmount" class="form-control" type="number" step="0.01"/>
                            <?= (isset($_SESSION['e_expenseAmount'])) ? "<p class='alert alert-danger'>" . $_SESSION['e_expenseAmount'] . "</p>" : "";
                            unset($_SESSION['e_expenseAmount']); ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <strong>Data</strong>
                            <input name="expenseDate" class="date form-control" type="text" placeholder="RRRR-MM-DD"
                                   required
                                   pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))"
                                   title="Wpisz datę w formacie YYYY-MM-DD"/>
                            <?= (isset($_SESSION['e_expenseDate'])) ? "<p class='alert alert-danger'>" . $_SESSION['e_expenseDate'] . "</p>" : "";
                            unset($_SESSION['e_expenseDate']); ?>
                        </div>
                    </div>
                    <diV class="col-md-12">
                        <div class="form-group">
                            <strong>Sposób płatności</strong>
                            <select name="paymentType" class="form-control">
                                <?php
                                $budget->showExpensPaymentMethod('addExpense');
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <strong>Kategoria</strong>
                            <?php
                            $budget->showExpensCategory('show');
                            ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <strong>Komentarz:</strong>
                        <input name="expenseComment" type="text" class="form-control"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-primary btn-block ">Dodaj</button>
                    </div>
                    <div class="col-md-6">
                        <button type="button" class="btn btn-warning btn-block ">Anuluj</button>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <strong>Ostatnie wydadki:</strong>
                <?php
                $expenses = $budget->getExpenses();
                $lastExpenses = $expenses->getLastExpense();
                ?>
                <table class="table table-striped">
                    <tr>
                        <th>Data</th>
                        <th>Kwota</th>
                        <th>Kategoria</th>
                        <th>Płatność</th>
                        <th>Komentarz</th>
                    </tr>
                    <?php
                    foreach ($lastExpenses as $expens) {
                        echo '<tr>';
                            echo '<td>' . $expens['expense_date'] . '</td>';
                            echo '<td>' . $expens['amount'] . '</td>';
                            echo '<td>' . $expens['category'] . '</td>';
                            echo '<td>' . $expens['payment_method'] . '</td>';
                            echo '<td>' . $expens['comment'] . '</td>';
                        echo '</tr>';
                    }
                    ?>
                </table>
            </div>
        </div>
    </form>
</div>
<?php
include "templates/addExpense-modal-window/limitAlertModal.php"; ?>

<script src="js/expense-limit-check.js?v=<?= filemtime('js/expense-limit-check.js') ?>"></script>
