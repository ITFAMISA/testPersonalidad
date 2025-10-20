<?php
session_start();

$ERROR = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $user = $_POST['username'] ?? '';
  $pass = $_POST['password'] ?? '';

  // Placeholder credentials - replace with DB/auth later
  if ($user === 'admin' && $pass === 'admin123') {
    $_SESSION['admin_logged_in'] = true;
    header('Location: /admin/dashboard.php');
    exit;
  } else {
    $ERROR = 'Credenciales inválidas';
  }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login Admin | Evaluación de Liderazgo</title>
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
          }
        }
      }
    }
  </script>
</head>
<body class="min-h-screen bg-gradient-to-br from-primary-50 via-white to-primary-100 text-slate-800 grid place-items-center p-6">
  <div class="w-full max-w-md">
    <div class="bg-white/80 backdrop-blur rounded-2xl shadow-xl border border-slate-100 p-8">
      <div class="flex items-center gap-3 mb-6">
        <div class="h-10 w-10 rounded-xl bg-primary-600 text-white grid place-content-center font-bold">L</div>
        <div>
          <div class="text-lg font-semibold text-primary-900">Dashboard Admin</div>
          <div class="text-xs text-slate-500">Evaluación de Estilos de Liderazgo</div>
        </div>
      </div>

      <?php if ($ERROR): ?>
      <div class="mb-4 text-sm text-rose-700 bg-rose-50 border border-rose-200 px-3 py-2 rounded-lg"><?php echo htmlspecialchars($ERROR); ?></div>
      <?php endif; ?>

      <form method="post" class="space-y-4">
        <div>
          <label class="text-sm font-medium text-slate-700">Usuario</label>
          <input type="text" name="username" required class="mt-1 w-full rounded-xl border-slate-200 focus:border-primary-500 focus:ring-primary-500" placeholder="admin" />
        </div>
        <div>
          <label class="text-sm font-medium text-slate-700">Contraseña</label>
          <input type="password" name="password" required class="mt-1 w-full rounded-xl border-slate-200 focus:border-primary-500 focus:ring-primary-500" placeholder="admin123" />
        </div>
        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-semibold px-6 py-3 rounded-xl transition">Entrar</button>
      </form>

      <div class="mt-6 text-center text-xs text-slate-500">
        Tip: Usuario: admin — Contraseña: admin123
      </div>
    </div>

    <div class="text-center mt-6">
      <a href="/" class="text-xs text-primary-700 hover:text-primary-900">Volver al inicio</a>
    </div>
  </div>
</body>
</html>
