<?php
// Simple landing page for Leadership Assessment
?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Evaluación de Estilos de Liderazgo</title>
  <script src="https://cdn.tailwindcss.com"></script>
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
            accent: '#22c55e',
          },
          boxShadow: {
            glow: '0 10px 30px rgba(43, 131, 246, 0.35)'
          }
        }
      }
    }
  </script>
</head>
<body class="min-h-screen bg-gradient-to-br from-primary-50 via-white to-primary-100 text-slate-800">
  <header class="relative">
    <div class="absolute inset-0 bg-gradient-to-r from-primary-600 to-primary-400 opacity-10"></div>
    <div class="container mx-auto px-6 py-6 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="h-10 w-10 rounded-xl bg-primary-600 text-white grid place-content-center font-bold">L</div>
        <span class="text-lg font-semibold text-primary-800">Leadership Assessment</span>
      </div>
      <nav class="hidden sm:flex items-center gap-6 text-sm">
        <a href="/quiz.php" class="text-primary-700 hover:text-primary-900 transition">Cuestionario</a>
        <a href="/admin/login.php" class="text-primary-700 hover:text-primary-900 transition">Dashboard</a>
      </nav>
    </div>
  </header>

  <main class="container mx-auto px-6 pt-10 pb-20">
    <section class="grid lg:grid-cols-2 gap-10 items-center">
      <div>
        <span class="inline-flex items-center gap-2 rounded-full bg-primary-100 text-primary-800 px-3 py-1 text-xs font-medium">
          <span class="h-2 w-2 rounded-full bg-accent animate-pulse"></span>
          Basado en 7 estilos de liderazgo
        </span>
        <h1 class="mt-4 text-4xl sm:text-5xl font-extrabold text-primary-900 leading-tight">
          Descubre tu estilo de liderazgo
        </h1>
        <p class="mt-4 text-lg text-slate-600 max-w-xl">
          Responde 70 preguntas en un flujo dinámico y obtén un análisis visual de tus estilos de liderazgo dominantes.
        </p>
        <div class="mt-8 flex flex-wrap gap-4 items-center">
          <a href="/quiz.php" class="inline-flex items-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-semibold px-6 py-3 rounded-xl shadow-glow transition transform hover:-translate-y-0.5">
            Comenzar Evaluación
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
              <path fill-rule="evenodd" d="M12.97 3.97a.75.75 0 0 1 1.06 0l6 6a.75.75 0 0 1 0 1.06l-6 6a.75.75 0 1 1-1.06-1.06l4.72-4.72H4.5a.75.75 0 0 1 0-1.5h13.19l-4.72-4.72a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
            </svg>
          </a>
          <a href="/admin/login.php" class="inline-flex items-center gap-2 bg-white text-primary-700 border border-primary-200 hover:border-primary-300 hover:bg-primary-50 font-semibold px-6 py-3 rounded-xl transition">
            Ver Dashboard
          </a>
        </div>
        <div class="mt-10 grid grid-cols-2 sm:grid-cols-4 gap-6 text-center">
          <div class="p-4 bg-white/60 rounded-xl shadow">
            <div class="text-2xl font-bold text-primary-700">70</div>
            <div class="text-xs text-slate-500">Preguntas</div>
          </div>
          <div class="p-4 bg-white/60 rounded-xl shadow">
            <div class="text-2xl font-bold text-primary-700">7</div>
            <div class="text-xs text-slate-500">Estilos</div>
          </div>
          <div class="p-4 bg-white/60 rounded-xl shadow">
            <div class="text-2xl font-bold text-primary-700">15s</div>
            <div class="text-xs text-slate-500">Por pregunta</div>
          </div>
          <div class="p-4 bg-white/60 rounded-xl shadow">
            <div class="text-2xl font-bold text-primary-700">Responsive</div>
            <div class="text-xs text-slate-500">Mobile-first</div>
          </div>
        </div>
      </div>
      <div class="relative">
        <div class="absolute -inset-4 bg-gradient-to-tr from-primary-200/60 to-primary-100/30 rounded-3xl blur-2xl"></div>
        <div class="relative bg-white rounded-3xl shadow-2xl p-6">
          <div class="flex items-center justify-between mb-4">
            <div class="text-sm text-slate-500">Vista previa</div>
            <div class="flex items-center gap-2 text-xs text-slate-400">
              <span class="h-2 w-2 rounded-full bg-green-400"></span>
              Tiempo real
            </div>
          </div>
          <div class="space-y-4">
            <div class="h-3 bg-primary-100 rounded-full overflow-hidden">
              <div class="h-full w-2/5 bg-primary-500 rounded-full"></div>
            </div>
            <div class="p-4 rounded-xl border border-slate-100">
              <div class="text-xs font-semibold text-primary-700 mb-2">PREGUNTA 28 DE 70</div>
              <div class="text-slate-800 font-semibold">Creo que la toma de decisiones debe ser un proceso inclusivo y transparente.</div>
              <div class="mt-4 grid grid-cols-5 gap-2">
                <div class="p-2 rounded-lg text-center border">1</div>
                <div class="p-2 rounded-lg text-center border">2</div>
                <div class="p-2 rounded-lg text-center bg-primary-600 text-white border border-primary-600">3</div>
                <div class="p-2 rounded-lg text-center border">4</div>
                <div class="p-2 rounded-lg text-center border">5</div>
              </div>
            </div>
            <div class="flex items-center justify-between">
              <button class="px-4 py-2 rounded-lg bg-slate-100 text-slate-700">Anterior</button>
              <div class="flex items-center gap-3">
                <div class="text-xs text-slate-500">Tiempo restante</div>
                <div class="h-8 w-8 rounded-full bg-primary-100 grid place-content-center text-primary-700 font-bold">12</div>
              </div>
              <button class="px-4 py-2 rounded-lg bg-primary-600 text-white">Siguiente</button>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <footer class="py-10 text-center text-xs text-slate-400">
    Construido con PHP + TailwindCSS. Estructura lista para back-end.
  </footer>
</body>
</html>
