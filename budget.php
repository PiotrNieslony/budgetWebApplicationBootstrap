<?php
    session_start();
    if(!isset($_SESSION['loggedUser'])) header('Location:zaloguj');
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
							<h1>Bilans</h1>
						</header>
						<div class="row">
							<div class="
								col-lg-3 col-lg-push-9
								col-md-3 col-md-push-9">
								<select class="select-date form-control">
									<option value="current-month">Bieżący miesiąć</option>
									<option value="previous-month">Poprzedni miesiąć</option>
									<option value="current-month">Poprzedni miesiąć</option>
									<option value="custom">Niestandardowy</option>
								</select>
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
	<script src="main.js"	></script>
</body>
</html>
