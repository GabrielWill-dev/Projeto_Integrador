<?php
try {
  // Contando Tarefas Concluídas
  $sqlConcluidas = "SELECT COUNT(*) as totalConcluidas FROM cl203156.tbtarefas WHERE statusTarefa = 1";
  $resultConcluidas = mysqli_query($conexao, $sqlConcluidas);
  $dataConcluidas = mysqli_fetch_assoc($resultConcluidas);
  $totalConcluidas = $dataConcluidas['totalConcluidas'];

  // Contando Tarefas Não Concluídas
  $sqlNaoConcluidas = "SELECT COUNT(*) as totalNaoConcluidas FROM cl203156.tbtarefas WHERE statusTarefa = 0";
  $resultNaoConcluidas = mysqli_query($conexao, $sqlNaoConcluidas);
  $dataNaoConcluidas = mysqli_fetch_assoc($resultNaoConcluidas);
  $totalNaoConcluidas = $dataNaoConcluidas['totalNaoConcluidas'];

  //Contando Eventos Concluídos
  $sqlEventosConcluidas = "SELECT COUNT(*) as totalEventosConcluidas FROM cl203156.tbeventos WHERE statusEvento = 1";
  $resultEventosConcluidas = mysqli_query($conexao, $sqlEventosConcluidas);
  $dataEventosConcluidas = mysqli_fetch_assoc($resultEventosConcluidas);
  $totalEventosConcluidas = $dataEventosConcluidas['totalEventosConcluidas'];

  // Contando Eventos Não Concluídas
  $sqlEventosNaoConcluidas = "SELECT COUNT(*) as totalEventosNaoConcluidas FROM cl203156.tbeventos WHERE statusEvento = 0";
  $resultEventosNaoConcluidas = mysqli_query($conexao, $sqlEventosNaoConcluidas);
  $dataEventosNaoConcluidas = mysqli_fetch_assoc($resultEventosNaoConcluidas);
  $totalEventosNaoConcluidas = $dataEventosNaoConcluidas['totalEventosNaoConcluidas'];

  unset($resultConcluidas);
  unset($resultNaoConcluidas);
  unset($resultEventosConcluidas);
  unset($resultEventosNaoConcluidas);
} catch (Exception $e) {
  die("Erro: " . $e->getMessage());
}

$schedules = $conexao->query("SELECT * FROM tbtarefas");
$sched_res = [];
while ($row = $schedules->fetch_assoc()) {
    $row['sdate'] = date("F d, Y h:i A", strtotime($row['dataInicioTarefa']));
    $row['edate'] = date("F d, Y h:i A", strtotime($row['dataConclusaoTarefa']));
    $sched_res[$row['idTarefa']] = $row;
}
?>


<html lang="pt-br">

<head>
  <!-- Montserrat Font -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap"
    rel="stylesheet">

  <!-- Material Icons -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">


  <style>
    .chartBox {
      width: 400px;
      padding: 20px;
      border-radius: 20px;
      border: solid 3px rgba(54, 162, 235, 1);
      background: bg-light;
    }

    .main-cards {
      display: grid;
      grid-template-columns: 1fr 1fr 1fr 1fr;
      gap: 10px;
      margin: 10px 0;
    }

    .card-inner {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .card-inner>.material-icons-outlined {
      font-size: 45px;
    }

    .card {
      max-width: 18rem;
      display: flex;
      flex-direction: column;
      justify-content: space-around;
      padding: 15px;
      border-radius: 5px;
    }
  </style>
</head>

<body>
  <div class="main-cards">

    <div class="card text-bg- mb-3">
      <a href="?menuop=tarefas" style="text-decoration: none;"
        class="link-dark link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">
        <div class="card-inner">
          <h3>TAREFAS</h3>
          <span class="material-icons-outlined"><i class="bi bi-list-task"></i></span>
        </div>
        <?php
        echo "<h1><i class='bi bi-check-square'></i> $totalConcluidas</h1>";
        echo "<p><h1><i class='bi bi-x-square'></i> $totalNaoConcluidas</h1></p>";
        ?>
      </a>
    </div>

    <div class="card text-bg-primary mb-3">
      <a href="?menuop=eventos" style="text-decoration: none;"
        class="link-dark link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover">
        <div class="card-inner">
          <h3>EVENTOS</h3>
          <span class="material-icons-outlined"><i class="bi bi-calendar-check"></i></span>
        </div>
        <?php
        echo "<h1><i class='bi bi-check-square'></i> $totalEventosConcluidas</h1>";
        echo "<p><h1><i class='bi bi-x-square'></i> $totalEventosNaoConcluidas</h1></p>";
        ?>
      </a>
    </div>

    <div class="chartBox">
      <div class="input-group">
        <input type="date" class="form-control text-bg-secondary" onchange="startDateFilter(this)" value="2024-10-01"
          min="2024-01-01" max="2025-10-30">
        <input type="date" class="form-control text-bg-secondary" onchange="endDateFilter(this)" value="2024-10-30"
          min="2024-01-01" max="2025-10-30">
      </div>
      <canvas id="myChart"></canvas>
    </div>

    <?php
    try {
      // Ajustando a consulta para trazer apenas tarefas concluídas (statusTarefa = 1)
      $sql = "SELECT dataConclusaoTarefa FROM cl203156.tbtarefas WHERE statusTarefa = 1";
      $result = mysqli_query($conexao, $sql);

      $dateArray = [];
      if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
          $dateArray[] = $row["dataConclusaoTarefa"];
        }
        unset($result);
      } else {
        echo 'Sem resultados no DB';
      }
    } catch (Exception $e) {
      die("Erro: " . $e->getMessage());
    }
    unset($pdo);
    ?>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/chart.js/dist/chart.umd.min.js"></script>
    <script
      src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.35.5/apexcharts.min.js"></script>
    <script>
      const dateArrayJS = <?php echo json_encode($dateArray); ?>;

      // Contando o número de tarefas concluídas por data
      const taskCounts = dateArrayJS.reduce((acc, date) => {
        const formattedDate = new Date(date).toISOString().split('T')[0]; // Formata a data para 'YYYY-MM-DD'
        acc[formattedDate] = (acc[formattedDate] || 0) + 1; // Incrementa a contagem para a data
        return acc;
      }, {});

      // Convertendo para arrays para o gráfico
      const labels = Object.keys(taskCounts).map(date => new Date(date));
      const dataCounts = Object.values(taskCounts);

      // Setup 
      const data = {
        labels: labels,
        datasets: [{
          label: 'Tarefas Concluídas',
          data: dataCounts,
          backgroundColor: 'rgba(54, 162, 235, 0.2)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1
        }]
      };

      // Config 
      const config = {
        type: 'bar',
        data,
        options: {
          scales: {
            x: {
              type: 'time',
              time: {
                unit: 'day'
              },
              min: new Date('2024-10-01'),
              max: new Date('2024-10-30'),
            },
            y: {
              beginAtZero: true
            }
          }
        }
      };

      // Render init block
      const myChart = new Chart(document.getElementById('myChart'), config);

      function startDateFilter(date) {
        const startDate = new Date(date.value);
        myChart.config.options.scales.x.min = startDate;
        myChart.update();
      }

      function endDateFilter(date) {
        const endDate = new Date(date.value);
        myChart.config.options.scales.x.max = endDate;
        myChart.update();
      }
    </script>
</body>

</html>