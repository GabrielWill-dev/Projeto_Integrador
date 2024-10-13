<html lang="pt-br">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Getting Started with Chart JS</title>
    <style>
      .chartMenu {
        width: 100vw;
        height: 40px;
        background: #1A1A1A;
        color: rgba(54, 162, 235, 1);
      }
      .chartBox {
        width: 700px;
        padding: 20px;
        border-radius: 20px;
        border: solid 3px rgba(54, 162, 235, 1);
        background: bg-light;
      }
    </style>
  </head>
  <body>
      <div class="chartBox">
        <div class="input-group">
          <input type="date" class="form-control" onchange="startDateFilter(this)" value="2024-10-01" min="2024-01-01" max="2025-10-30">
          <input type="date" class="form-control" onchange="endDateFilter(this)" value="2024-10-30" min="2024-01-01" max="2025-10-30">
        </div>
        <button onclick="filterTasks(1)">Tarefas Concluídas</button>
        <button onclick="filterTasks(0)">Tarefas Não Concluídas</button>
        <canvas id="myChart"></canvas>
      </div>

      <?php 
      try {
        // Selecionando todas as tarefas, tanto concluídas quanto não concluídas
        $sql = "SELECT * FROM cl203156.tbtarefas";
        $result = mysqli_query($conexao, $sql);
        $tasks = [];

        if (mysqli_num_rows($result) > 0) {
          while ($row = mysqli_fetch_assoc($result)) {
            $tasks[] = $row;
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
      <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
      <script>
        // Função para filtrar tarefas
        function filterTasks(status) {
          const filteredTasks = tasks.filter(task => task.statusTarefa == status);
          updateChart(filteredTasks);
        }

        // Inicializando tarefas
        const tasks = <?php echo json_encode($tasks); ?>;

        // Configuração do gráfico
        const myChart = new Chart(document.getElementById('myChart'), {
          type: 'bar',
          data: {
            datasets: [],
          },
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
        });

        // Atualiza o gráfico com as tarefas filtradas
        function updateChart(filteredTasks) {
          const data = {
            datasets: [{
              label: filteredTasks.length >= 0 ? (filteredTasks[0].statusTarefa == 1 ? 'Tarefas Concluídas' : 'Tarefas Não Concluídas') : 'Nenhuma Tarefa',
              data: filteredTasks.map(task => ({
                x: new Date(task.dataConclusaoTarefa),
                y: task.statusTarefa
              })),
              backgroundColor: filteredTasks[0].statusTarefa == 1 ? 'rgba(54, 162, 235, 0.2)' : 'rgba(255, 26, 104, 0.2)',
              borderColor: filteredTasks[0].statusTarefa == 1 ? 'rgba(54, 162, 235, 1)' : 'rgba(255, 26, 104, 1)',
              borderWidth: 1
            }]
          };

          myChart.data = data;
          myChart.update();
        }

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
