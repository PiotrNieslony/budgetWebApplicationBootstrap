<?php
    session_start();
    if(!isset($_SESSION['loggedUser'])) header('Location:zaloguj');
    require_once 'dbconnect.php';
    $loggedUserId = $_SESSION['loggedUser']['id'];
    if(isset($_POST['date-scope'])){
        if($_POST['date-scope'] == "current-month"){
            $balaceDateFrom = '2018-10-01';// TODO currnet month date
            $balaceDateTo   = '2018-10-31';
            $_SESSION['current-month'] = true;
        } elseif ($_POST['date-scope'] == "previous-month"){
            $balaceDateFrom = '2018-09-01';
            $balaceDateTo   = '2018-09-31';
            $_SESSION['previous-month'] = true;
        }
    } elseif (isset($_POST['dateFrom'])){
        $balaceDateFrom = $_POST['dateFrom'];
        $balaceDateTo   = $_POST['dateTo'];
        $_SESSION['custom'] = true;
    } else {
        $balaceDateFrom = '2018-10-01';
        $balaceDateTo   = '2018-10-31';
    }

    $queryIncoms = $db->query("SELECT  icatu.name AS category, SUM(incomes.amount) AS amount
                    FROM incomes
                    INNER JOIN
                    incomes_category_assigned_to_users icatu
                    ON incomes.income_category_assigned_to_user_id = icatu.id
                    WHERE incomes.user_id = $loggedUserId
                    AND date_of_income >= '$balaceDateFrom'
                    AND date_of_income < '$balaceDateTo'
                    GROUP BY incomes.income_category_assigned_to_user_id
                    ORDER BY SUM(incomes.amount) DESC;");
    $queryExpens = $db->query("SELECT  ecatu.name AS category, SUM(expenses.amount) AS amount
                    FROM expenses
                    INNER JOIN
                    expenses_category_assigned_to_users ecatu
                    ON expenses.expense_category_assigned_to_user_id = ecatu.id
                    WHERE expenses.user_id = $loggedUserId
                    AND date_of_expense >= '$balaceDateFrom'
                    AND date_of_expense < '$balaceDateTo'
                    GROUP BY expenses.expense_category_assigned_to_user_id
                    ORDER BY SUM(expenses.amount) DESC;");
    $incomes = $queryIncoms->fetchAll();
    $expenses = $queryExpens->fetchAll();

?>
<!DOCTYPE HTML>
<html lang="pl">


<head>
	<meta charset="utf-8" />
	<title>Budget - Rejestracja</title>

	<meta name="description" content="Opis w Google" />
	<meta name="keywords" content="słowa, kluczowe, wypisane, po, porzecinku" />

	<link rel="stylesheet" href="bootstrap/bootstrap.min.css" type="text/css" />
	<link rel="stylesheet" href="style.css" type="text/css" />
	<link rel="stylesheet" href="fontello/fontello.css" type="text/css" />
	<link rel="stylesheet" href="jquery/jquery-ui.min.css" type="text/css" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
	<div class="container-fluid">
		<div class="header">
			<header>
				<h1 id="logo"><i>$</i> Your<span>Budget</span></h1>
			</header>
		</div>
                <?= require_once 'sidebar.php' ?>
				<div class="content budget">
					<main>
						<header>
							<h1>Bilans </h1>
                            <h4>Za okres<?= "od ".$balaceDateFrom." do ".$balaceDateTo ?></h4>
						</header>
						<div class="row">
							<div class="
								col-lg-3 col-lg-push-9
								col-md-3 col-md-push-9">
                                <form method="post">
                                    <select id="date-scope" class="select-date form-control" name="date-scope">
                                        <option value="current-month" <?= (isset($_SESSION['current-month'])) ? "selected" : "" ; unset($_SESSION['current-month']);?> >Bieżący miesiąć</option>
                                        <option value="previous-month" <?= (isset($_SESSION['previous-month'])) ? "selected" : ""; unset($_SESSION['previous-month']);?> >Poprzedni miesiąć</option>
                                        <option value="custom" <?= (isset($_SESSION['custom'])) ? "selected" : ""; unset($_SESSION['custom']);?> >Niestandardowy</option>
                                    </select>
                                </form>
                                <div class="modal fade" id="dateModal" tabindex="-1" role="dialog">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <form id="dateModalForm" method="post">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    <h4 class="modal-title">Wbierz datę</h4>
                                                </div>
                                                <div class="modal-body">

                                                        <div class="form-group">
                                                            <strong>Data od</strong>
                                                            <input name="dateFrom" class="date form-control" type="text" placeholder="RRRR-MM-DD" required
                                                                   pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))"
                                                                   title="Wpisz datę w formacie YYYY-MM-DD"/>
                                                        </div>
                                                        <div class="form-group">
                                                            <strong>Data do</strong>
                                                            <input name="dateTo" class="date form-control" type="text" placeholder="RRRR-MM-DD" required
                                                                   pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))"
                                                                   title="Wpisz datę w formacie YYYY-MM-DD"/>
                                                        </div>

                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Anuluj</button>
                                                    <button type="submit" class="btn btn-primary">OK</button>
                                                </div>
                                            </form>
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div><!-- /.modal -->
							</div>
							<div class="
								col-lg-3 col-lg-pull-3
								col-md-3 col-md-pull-3
								summary">
								<div id="columnchart" class="chart">
									<img class="loader" src="img/ajax-loader.gif" alt="loader"/>
								</div>
							</div>
							<div id="summary-message" class="
								col-lg-4 col-lg-pull-3 col-lg-offset-0
								col-md-5 col-md-pull-3 col-md-offset-1">
								<strong>Gratulacje!</strong><br />
								Wspaniale zarządzasz finansami.
								Posoztało ci <strong> 200 zł</strong> wolnych środków. <br />
							</div>
						</div>
						<div class="row incomes">
								<div class="col-md-12">
									<h2>Przychody</h2>
								</div>
								<div class="
								col-lg-3 col-lg-push-9
								col-md-5 col-md-push-7">
									<div id="piechart-incomes" class="chart">
										<img class="loader" src="img/ajax-loader.gif" alt="loader"/>
									</div>
								</div>
								<div class="
									col-lg-9 col-lg-pull-3
									col-md-7 col-md-pull-5">
									<div class="budget-table incomes-table">
										<table class="table table-bordered table-striped table-hover">
											<caption>Tabela przychodów</caption>
											<thead>
												<tr>
													<th>l.p.</th>
													<th>Kategoria</th>
													<th>Wartość</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>1</td>
													<td>Wynagrodzenie</td>
													<td>5000</td>
												</tr>
												<tr>
													<td>2</td>
													<td>Odsetki bankowe</td>
													<td>90</td>
												</tr>
												<tr>
													<td>3</td>
													<td>Sprzedaż na allegro</td>
													<td>300</td>

												</tr>
												<tr>
													<td>4</td>
													<td>Inne</td>
													<td>0</td>
												</tr>
												<tr>
													<td colspan="2">Suma</td>
													<td>5390</td>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
						</div>
						<div class="row expenses">
							<div class="col-md-12">
								<h2>Wydatki</h2>
							</div>
							<div class="
							col-lg-3 col-lg-push-9
							col-md-5 col-md-push-7">
								<div id="piechart-espenses" class="chart">
									<img class="loader" src="img/ajax-loader.gif" alt="loader"/>
								</div>
							</div>
							<div class="
								col-lg-9 col-lg-pull-3
								col-md-7 col-md-pull-5">
								<div class="budget-table expeses-table">
									<table class="table table-bordered table-striped table-hover">
										<caption>Tabela wydatków</caption>
										<thead>
											<tr>
												<th>l.p.</th>
												<th>Kategoria</th>
												<th>Wartość</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>1</td>
												<td>Mieszkanie</td>
												<td>1600</td>
											</tr>
											<tr>
												<td>2</td>
												<td>Jedzenie</td>
												<td>1050</td>
											</tr>
											<tr>
												<td colspan="2">Suma</td>
												<td>00</td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
					</div>
				</main>
			</div>
	</div>
	<script src="jquery/jquery-3.3.1.min.js"></script>
		<script src="bootstrap/bootstrap.min.js"></script>
	<script src="jquery/jquery-ui.min.js"	></script>
	<script  src="https://www.gstatic.com/charts/loader.js"></script>
    <script>
        //incomes data
        var incomesArray = [['Category', 'Amount']];
        incomesArray.push(<?php foreach($incomes as $income){echo "[\"$income[0]\", $income[1]],";} ?>);

        console.log(incomesArray);
        //expenses data
        var expensesArray = [['Category', 'Amount']];
        expensesArray.push(<?php foreach($expenses as $expens){echo "[\"$expens[0]\", $expens[1]],";} ?>);

    </script>
	<script src="balance.js"	></script>
	<script src="main.js"	></script>
</body>
</html>
