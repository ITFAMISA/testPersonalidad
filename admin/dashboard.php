<?php
session_start();
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
  $_SESSION = [];
  session_destroy();
  header('Location: /admin/login.php');
  exit;
}
if (!($_SESSION['admin_logged_in'] ?? false)) {
  header('Location: /admin/login.php');
  exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Dashboard | Evaluación de Liderazgo</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js" integrity="sha512-Yk9dZcQXrSqwWYSuNQJqfPGptRkPZAmX4OuB+RDuSWKfrQkS44BfC2mARkQghuWz5m7D2qZisEiosAKkf5p0hA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" integrity="sha512-BNa5q/sfEtO61FrYtVoE0t5rsB6wG7sYEespFmkJcHF0j0fDvN6p3wqjZnYv8jCa9gNJM12EuvdRULO8boXfHQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: {
              50: '#eef7ff',
              100: '#d9ecff',
              200: '#b7dbff',
              300: '#8ec6ff',
              400: '#57a4ff',
              500: '#2b83f6',
              600: '#1867d6',
              700: '#1454ad',
              800: '#154a8f',
              900: '#153f75',
            },
          }
        }
      }
    }
  </script>
</head>
<body class="min-h-screen bg-gradient-to-br from-white to-primary-50 text-slate-800">
  <header class="border-b bg-white/80 backdrop-blur sticky top-0 z-10">
    <div class="container mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="h-9 w-9 rounded-lg bg-primary-600 text-white grid place-content-center font-bold">L</div>
        <div class="font-semibold text-primary-900">Dashboard Admin</div>
      </div>
      <nav class="flex items-center gap-3 text-sm">
        <a href="/quiz.php" class="text-primary-700 hover:text-primary-900">Ir al cuestionario</a>
        <a href="?action=logout" class="inline-flex items-center gap-1 text-rose-700 hover:text-rose-800">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
            <path fill-rule="evenodd" d="M7.5 3.75A.75.75 0 0 1 8.25 3h8.25A2.25 2.25 0 0 1 18.75 5.25v13.5A2.25 2.25 0 0 1 16.5 21H8.25a.75.75 0 0 1 0-1.5H16.5A.75.75 0 0 0 17.25 18V6A.75.75 0 0 0 16.5 5.25H8.25A.75.75 0 0 1 7.5 4.5v-.75Z" clip-rule="evenodd" />
            <path fill-rule="evenodd" d="M12.53 15.78a.75.75 0 0 1-1.06 0l-3-3a.75.75 0 0 1 0-1.06l3-3a.75.75 0 0 1 1.06 1.06L10.81 11.25H20.25a.75.75 0 0 1 0 1.5H10.81l1.72 1.72a.75.75 0 0 1 0 1.06Z" clip-rule="evenodd" />
          </svg>
          Cerrar sesión
        </a>
      </nav>
    </div>
  </header>

  <main class="container mx-auto px-4 sm:px-6 py-8">
    <div id="report" class="space-y-8">
      <section class="grid lg:grid-cols-3 gap-6 items-start">
        <div class="lg:col-span-2 bg-white rounded-2xl shadow border border-slate-100 p-6">
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-slate-800">Comparativa de Estilos</h2>
            <div class="text-xs text-slate-500" id="timestamp"></div>
          </div>
          <canvas id="stylesChart" height="160"></canvas>
        </div>
        <div id="dominantCard" class="bg-white rounded-2xl shadow border border-slate-100 p-6"></div>
      </section>

      <section class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6" id="scoreCards"></section>
    </div>

    <div class="mt-6 flex flex-wrap gap-3">
      <button id="exportBtn" class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-semibold px-5 py-3 rounded-xl">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M19.5 21a.75.75 0 0 0 .75-.75V15a.75.75 0 0 0-1.5 0v3.69l-4.72-4.72a.75.75 0 1 0-1.06 1.06l4.72 4.72H15a.75.75 0 0 0 0 1.5h4.5Z" /><path d="M15.75 3A2.25 2.25 0 0 1 18 5.25v5.5A2.25 2.25 0 0 1 15.75 13H7.5a.75.75 0 0 0 0 1.5h8.25A3.75 3.75 0 0 0 19.5 10.75v-5.5A3.75 3.75 0 0 0 15.75 1.5H6A3.75 3.75 0 0 0 2.25 5.25v10.5A3.75 3.75 0 0 0 6 19.5h1.5a.75.75 0 0 0 0-1.5H6A2.25 2.25 0 0 1 3.75 15.75V5.25A2.25 2.25 0 0 1 6 3h9.75Z" /></svg>
        Exportar PDF
      </button>
      <button id="resetBtn" class="inline-flex items-center gap-2 bg-white text-rose-700 border border-rose-200 hover:bg-rose-50 font-semibold px-5 py-3 rounded-xl">
        Reiniciar evaluación
      </button>
    </div>

    <div id="emptyState" class="hidden mt-12 bg-white rounded-2xl shadow border border-dashed border-slate-200 p-8 text-center">
      <div class="text-lg font-semibold text-slate-800 mb-2">No hay resultados recientes</div>
      <p class="text-sm text-slate-500 mb-4">Realiza primero la evaluación para visualizar el dashboard.</p>
      <a href="/quiz.php" class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-semibold px-5 py-3 rounded-xl">Ir al cuestionario</a>
    </div>

  </main>

  <script>
    const STYLES = ['AUTORITARIO','DEMOCRÁTICO','TRANSFORMACIONAL','TRANSACCIONAL','LAISSEZ-FAIRE','CARISMÁTICO','SITUACIONAL'];

    const DESCRIPTIONS = {
      'AUTORITARIO': 'Enfocado en control, dirección y cumplimiento. Efectivo en crisis, puede reducir autonomía.',
      'DEMOCRÁTICO': 'Promueve participación y consenso. Potencia creatividad y compromiso del equipo.',
      'TRANSFORMACIONAL': 'Inspira con visión y propósito. Desarrolla personas y fomenta innovación.',
      'TRANSACCIONAL': 'Basado en recompensas y métricas. Óptimo para procesos claros y estabilidad.',
      'LAISSEZ-FAIRE': 'Alta delegación y autonomía. Funciona con equipos maduros y autodirigidos.',
      'CARISMÁTICO': 'Influencia a través de la comunicación y la presencia. Genera motivación y lealtad.',
      'SITUACIONAL': 'Adapta el estilo al contexto y madurez del equipo. Flexible y orientado a las necesidades.'
    };

    function loadResults() {
      try {
        const raw = localStorage.getItem('leadershipAssessmentResult');
        if (!raw) return null;
        return JSON.parse(raw);
      } catch (e) {
        return null;
      }
    }

    function render() {
      const data = loadResults();
      const empty = document.getElementById('emptyState');
      if (!data) {
        empty.classList.remove('hidden');
        return;
      }
      document.getElementById('timestamp').textContent = new Date(data.timestamp).toLocaleString();

      // Dominant card
      const dom = data.dominantStyle;
      const domEl = document.getElementById('dominantCard');
      domEl.innerHTML = `
        <div class="text-xs font-semibold text-slate-500">Estilo dominante</div>
        <div class="text-2xl font-bold text-primary-800 mt-1">${dom}</div>
        <p class="mt-3 text-sm text-slate-600">${DESCRIPTIONS[dom]}</p>
        <div class="mt-4 p-4 rounded-xl bg-primary-50 border border-primary-100">
          <div class="text-sm text-primary-700">Puntaje: <span class="font-semibold">${data.styleScores[dom]}</span></div>
          <div class="text-sm text-primary-700">Normalizado: <span class="font-semibold">${data.normalized[dom]}%</span></div>
        </div>
      `;

      // Chart
      const ctx = document.getElementById('stylesChart');
      const labels = STYLES;
      const scores = labels.map(l => data.styleScores[l]);
      const colors = ['#ef4444','#10b981','#6366f1','#f59e0b','#64748b','#a21caf','#0ea5e9'];
      new Chart(ctx, {
        type: 'bar',
        data: {
          labels,
          datasets: [{
            label: 'Puntaje por estilo (10-50)',
            data: scores,
            backgroundColor: colors,
            borderRadius: 8,
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: { display: false },
            tooltip: { enabled: true }
          },
          scales: {
            y: { beginAtZero: true, suggestedMax: 50, ticks: { stepSize: 10 } }
          }
        }
      });

      // Score cards
      const container = document.getElementById('scoreCards');
      container.innerHTML = '';
      labels.forEach((s, i) => {
        const div = document.createElement('div');
        div.className = 'p-5 rounded-2xl bg-white shadow border border-slate-100';
        div.innerHTML = `
          <div class="flex items-center justify-between">
            <div class="font-semibold text-slate-800">${s}</div>
            <span class="h-2 w-2 rounded-full" style="background:${colors[i]}"></span>
          </div>
          <div class="mt-3 grid grid-cols-2 gap-3 text-sm">
            <div class="p-3 bg-slate-50 rounded-xl border">
              <div class="text-slate-500">Puntaje</div>
              <div class="text-lg font-bold text-slate-800">${data.styleScores[s]}</div>
            </div>
            <div class="p-3 bg-slate-50 rounded-xl border">
              <div class="text-slate-500">Normalizado</div>
              <div class="text-lg font-bold text-slate-800">${data.normalized[s]}%</div>
            </div>
          </div>
          <p class="mt-3 text-xs text-slate-500">${DESCRIPTIONS[s]}</p>
        `;
        container.appendChild(div);
      });
    }

    async function exportPDF() {
      const el = document.getElementById('report');
      const canvas = await html2canvas(el, { scale: 2, backgroundColor: '#ffffff' });
      const imgData = canvas.toDataURL('image/png');
      const pdf = new jspdf.jsPDF('p', 'mm', 'a4');
      const pageWidth = pdf.internal.pageSize.getWidth();
      const pageHeight = pdf.internal.pageSize.getHeight();

      const imgWidth = pageWidth - 20;
      const imgHeight = canvas.height * imgWidth / canvas.width;

      let y = 10;
      if (imgHeight < pageHeight) {
        pdf.addImage(imgData, 'PNG', 10, y, imgWidth, imgHeight);
      } else {
        let remaining = imgHeight;
        let position = y;
        let canvasHeight = canvas.height;
        const ratio = imgWidth / canvas.width;
        while (remaining > 0) {
          const canvasSlice = document.createElement('canvas');
          canvasSlice.width = canvas.width;
          canvasSlice.height = Math.min(canvasHeight, (pageHeight - 20) / ratio * canvas.width / imgWidth);
          const ctx = canvasSlice.getContext('2d');
          ctx.drawImage(canvas, 0, position / ratio, canvas.width, canvasSlice.height, 0, 0, canvas.width, canvasSlice.height);
          const sliceData = canvasSlice.toDataURL('image/png');
          pdf.addImage(sliceData, 'PNG', 10, 10, imgWidth, canvasSlice.height * ratio);
          remaining -= canvasSlice.height * ratio;
          position += canvasSlice.height * ratio;
          if (remaining > 0) pdf.addPage();
        }
      }
      pdf.save('evaluacion-liderazgo.pdf');
    }

    document.getElementById('exportBtn').addEventListener('click', exportPDF);
    document.getElementById('resetBtn').addEventListener('click', () => {
      localStorage.removeItem('leadershipAssessmentResult');
      window.location.href = '/quiz.php';
    });

    render();
  </script>
</body>
</html>
