<?php
// Quiz page: shows one question at a time, 15s timer, previous/next navigation
?><!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cuestionario | Evaluación de Estilos de Liderazgo</title>
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
          }
        }
      }
    }
  </script>
</head>
<body class="min-h-screen bg-gradient-to-br from-white to-primary-50 text-slate-800">
  <div class="container mx-auto px-4 sm:px-6 py-6">
    <div class="flex items-center justify-between">
      <a href="/" class="inline-flex items-center gap-2 text-primary-700 hover:text-primary-900">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
          <path fill-rule="evenodd" d="M11.03 3.97a.75.75 0 0 1 0 1.06L6.31 9.75h13.19a.75.75 0 0 1 0 1.5H6.31l4.72 4.72a.75.75 0 1 1-1.06 1.06l-6-6a.75.75 0 0 1 0-1.06l6-6a.75.75 0 0 1 1.06 0Z" clip-rule="evenodd" />
        </svg>
        Inicio
      </a>
      <div class="text-sm text-slate-500">Evaluación de Estilos de Liderazgo</div>
    </div>

    <div class="max-w-3xl mx-auto mt-8">
      <div class="bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden">
        <div class="p-6 sm:p-8">
          <div class="flex items-center justify-between">
            <div class="text-xs font-semibold text-primary-700 tracking-wider" id="questionCounter">PREGUNTA 1 DE 70</div>
            <div class="flex items-center gap-3">
              <div class="text-xs text-slate-500">Tiempo restante</div>
              <div id="timerCircle" class="h-9 w-9 rounded-full bg-primary-100 grid place-content-center text-primary-700 font-bold">15</div>
            </div>
          </div>
          <div class="mt-4 h-2 bg-primary-100 rounded-full overflow-hidden">
            <div id="progressBar" class="h-full w-0 bg-primary-500 transition-all duration-300"></div>
          </div>

          <div id="questionCard" class="mt-6 transition-all duration-300 opacity-0 translate-y-1">
            <div class="text-xs text-slate-500 mb-1" id="questionStyle">Estilo: -</div>
            <h2 id="questionText" class="text-xl font-semibold text-slate-800">Cargando...</h2>
            <div id="options" class="mt-6 grid grid-cols-5 gap-2"></div>
            <div id="timeoutMsg" class="mt-4 hidden text-sm text-rose-600 bg-rose-50 border border-rose-200 px-3 py-2 rounded-lg">Tiempo agotado. Debes responder para continuar.</div>
          </div>

          <div class="mt-8 flex items-center justify-between">
            <button id="prevBtn" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 border border-slate-200 disabled:opacity-50 disabled:cursor-not-allowed">Anterior</button>
            <div class="flex-1"></div>
            <button id="nextBtn" class="px-4 py-2 rounded-xl bg-primary-600 text-white font-semibold disabled:opacity-50 disabled:cursor-not-allowed">Siguiente</button>
          </div>
        </div>
      </div>

      <div class="text-center text-xs text-slate-400 mt-4">1 = Totalmente en desacuerdo, 5 = Totalmente de acuerdo</div>
    </div>
  </div>

  <script>
    // Hardcoded data: 7 styles x 10 questions each
    const STYLES = [
      'AUTORITARIO', 'DEMOCRÁTICO', 'TRANSFORMACIONAL', 'TRANSACCIONAL', 'LAISSEZ-FAIRE', 'CARISMÁTICO', 'SITUACIONAL'
    ];

    const QUESTIONS_BY_STYLE = {
      'AUTORITARIO': [
        'Yo tomo la mayoría de las decisiones sin consultar al equipo, pero explico el "por qué".',
        'La obediencia y el cumplimiento estricto de las reglas son mis expectativas más altas.',
        'Prefiero que mi equipo se centre en ejecutar las tareas tal como las he diseñado.',
        'En situaciones de crisis, mi voz es la única que debe escucharse para actuar rápido.',
        'Considero que soy el único responsable de los resultados, tanto buenos como malos.',
        'La retroalimentación se da principalmente para corregir errores de ejecución y procedimiento.',
        'Mantengo cierta distancia personal para preservar mi autoridad como líder.',
        'Asigno tareas a las personas basándome estrictamente en su capacidad demostrada.',
        'Los objetivos son establecidos por mí y luego comunicados al equipo.',
        'Las recompensas y castigos son herramientas efectivas para motivar el cumplimiento.'
      ],
      'DEMOCRÁTICO': [
        'Fomento la discusión abierta y la entrada de ideas antes de tomar una decisión.',
        'Comparto la información relevante con el equipo para que tomen decisiones informadas.',
        'Mi rol es más de facilitador o coach que de director.',
        'El consenso del equipo a menudo produce soluciones más creativas que mi propia idea.',
        'Animo a los miembros del equipo a asumir la responsabilidad de las decisiones grupales.',
        'Las reuniones se utilizan para debatir y votar sobre cursos de acción.',
        'Creo que la toma de decisiones debe ser un proceso inclusivo y transparente.',
        'Busco activamente la opinión de los miembros junior del equipo.',
        'Si hay un desacuerdo, me esfuerzo por encontrar una solución que satisfaga a la mayoría.',
        'La satisfacción del equipo con el proceso es tan importante como el resultado final.'
      ],
      'TRANSFORMACIONAL': [
        'Inspiro a mi equipo para que vean su trabajo como parte de una misión o propósito mayor.',
        'Animo a mi equipo a desafiar el statu quo y a ser innovadores.',
        'Dedico tiempo a la tutoría y al desarrollo individual, tratando a cada uno de forma única.',
        'Transmito una visión clara y optimista del futuro que el equipo está ayudando a construir.',
        'Me gano el respeto y la confianza actuando como un modelo a seguir (role model).',
        'Motivo al equipo apelando a sus valores e ideales, más que a recompensas materiales.',
        'Fomento el pensamiento crítico y la resolución creativa de problemas en el equipo.',
        'Mis acciones a menudo se centran en el crecimiento a largo plazo en lugar de las ganancias inmediatas.',
        'Hago que el equipo sea consciente de la importancia de sus tareas para la organización.',
        'El equipo está dispuesto a hacer un esfuerzo adicional por mí debido a la fe que tiene en la visión.'
      ],
      'TRANSACCIONAL': [
        'Establezco claramente las recompensas que se darán por alcanzar los objetivos de rendimiento.',
        'Intervengo activamente solo cuando se identifican errores o se incumplen los estándares.',
        'El desempeño se evalúa con métricas y sistemas de feedback formales y regulares.',
        'Utilizo un sistema de incentivos y castigos (contingencia de recompensa) para dirigir el comportamiento del equipo.',
        'Los acuerdos y expectativas de trabajo se basan en un intercambio claro de esfuerzo por recompensa.',
        'El control y la supervisión son clave para asegurar que las reglas se cumplan.',
        'No me enfoco en la motivación, sino en dejar claros los pasos y los resultados esperados.',
        'Mi estilo es efectivo para mantener la estabilidad y la rutina en la organización.',
        'Utilizo los informes de rendimiento para determinar promociones y aumentos salariales.',
        'El equipo sabe exactamente qué debe hacer y qué obtendrá a cambio.'
      ],
      'LAISSEZ-FAIRE': [
        'Doy total autonomía a mi equipo para que resuelvan sus propios problemas.',
        'Evito interferir o microgestionar el trabajo diario del equipo.',
        'Ofrezco los recursos necesarios y luego me retiro para que el equipo trabaje a su aire.',
        'No intervengo a menos que se me solicite ayuda o que haya un problema grave.',
        'Creo que el equipo altamente competente trabaja mejor sin supervisión directa.',
        'La toma de decisiones suele recaer en el miembro del equipo con más conocimiento o interés.',
        'Mi presencia no es esencial para que el equipo sepa qué hacer.',
        'Dejo que mi equipo decida la mejor forma y el ritmo para alcanzar los objetivos.',
        'Evito dar feedback no solicitado, confiando en el juicio profesional de mi equipo.',
        'Soy el último recurso y solo actúo cuando la delegación fracasa.'
      ],
      'CARISMÁTICO': [
        'Mi entusiasmo por la visión es contagioso y motiva a mi equipo.',
        'La gente se siente inspirada por mi confianza en mí mismo y en el futuro.',
        'Utilizo historias, símbolos y lenguaje emocional para conectar con mi equipo.',
        'Mis ideas y sugerencias son a menudo aceptadas por la fuerza de mi personalidad.',
        'El equipo siente un fuerte vínculo emocional y lealtad hacia mí.',
        'Puedo hacer que la gente crea en metas que parecen casi imposibles.',
        'Me importa la impresión que doy y me aseguro de proyectar confianza y competencia.',
        'La forma en que presento las ideas es tan importante como las ideas mismas.',
        'La gente está dispuesta a hacer sacrificios personales por la causa que yo lidero.',
        'Mi energía y pasión impulsan el estado de ánimo y la moral del equipo.'
      ],
      'SITUACIONAL': [
        'Adapto mi estilo de liderazgo basándome en la madurez y competencia de la persona o equipo.',
        'En tareas nuevas, soy directivo; en tareas conocidas, soy delegativo.',
        'Utilizo un estilo de apoyo y colaboración si el equipo tiene la capacidad, pero le falta confianza.',
        'Soy capaz de moverme sin problemas entre el control estricto y la delegación total.',
        'Evalúo la necesidad del momento (urgencia, complejidad) antes de decidir mi enfoque.',
        'Mi primer paso al liderar una tarea es evaluar la disposición (habilidad + voluntad) del equipo.',
        'Creo que no existe un "mejor" estilo de liderazgo, solo el más adecuado para el contexto.',
        'Puedo ser firme y centrado en la tarea un día y muy centrado en las personas al día siguiente.',
        'Mis métodos de coaching varían significativamente de un empleado a otro.',
        'Si el equipo está desmotivado pero es capaz, me enfoco en el apoyo emocional.'
      ]
    };

    const ALL_QUESTIONS = Object.entries(QUESTIONS_BY_STYLE).flatMap(([style, list]) => list.map((text, idx) => ({ style, text, idx })));

    const totalQuestions = ALL_QUESTIONS.length; // 70

    const state = {
      current: 0,
      answers: Array(totalQuestions).fill(null),
      timer: 15,
      interval: null,
    };

    const elCounter = document.getElementById('questionCounter');
    const elProgressBar = document.getElementById('progressBar');
    const elQuestionText = document.getElementById('questionText');
    const elQuestionStyle = document.getElementById('questionStyle');
    const elOptions = document.getElementById('options');
    const elPrev = document.getElementById('prevBtn');
    const elNext = document.getElementById('nextBtn');
    const elTimer = document.getElementById('timerCircle');
    const elTimeoutMsg = document.getElementById('timeoutMsg');
    const elCard = document.getElementById('questionCard');

    const styleColors = {
      'AUTORITARIO': 'from-rose-100 to-rose-50 text-rose-700',
      'DEMOCRÁTICO': 'from-emerald-100 to-emerald-50 text-emerald-700',
      'TRANSFORMACIONAL': 'from-indigo-100 to-indigo-50 text-indigo-700',
      'TRANSACCIONAL': 'from-amber-100 to-amber-50 text-amber-700',
      'LAISSEZ-FAIRE': 'from-slate-100 to-slate-50 text-slate-700',
      'CARISMÁTICO': 'from-fuchsia-100 to-fuchsia-50 text-fuchsia-700',
      'SITUACIONAL': 'from-sky-100 to-sky-50 text-sky-700'
    };

    function renderQuestion() {
      const q = ALL_QUESTIONS[state.current];
      // Fade-out before updating
      elCard.classList.add('opacity-0', 'translate-y-1');

      elCounter.textContent = `PREGUNTA ${state.current + 1} DE ${totalQuestions}`;
      const pct = ((state.current) / (totalQuestions)) * 100;
      elProgressBar.style.width = pct + '%';

      elQuestionStyle.innerHTML = `Estilo: <span class="inline-flex items-center px-2 py-0.5 rounded-lg bg-gradient-to-r ${styleColors[q.style]} border border-black/5">${q.style}</span>`;
      elQuestionText.textContent = q.text;

      elOptions.innerHTML = '';
      for (let i=1; i<=5; i++) {
        const isSelected = state.answers[state.current] === i;
        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = `option-btn p-3 sm:p-4 rounded-xl border text-center font-semibold transition w-full ${isSelected ? 'bg-primary-600 text-white border-primary-600' : 'bg-white hover:bg-primary-50 border-slate-200 text-slate-700'}`;
        btn.textContent = i;
        btn.setAttribute('data-value', i);
        btn.addEventListener('click', () => onSelect(i));
        elOptions.appendChild(btn);
      }

      // Buttons state
      elPrev.disabled = state.current === 0;
      const answered = state.answers[state.current] !== null;
      elNext.disabled = !answered;
      elNext.textContent = (state.current === totalQuestions - 1) ? 'Finalizar' : 'Siguiente';

      // Reset timer
      resetTimer();

      // Fade-in after DOM update
      requestAnimationFrame(() => {
        elCard.classList.remove('opacity-0', 'translate-y-1');
        elCard.classList.add('opacity-100', 'translate-y-0');
      });
    }

    function onSelect(value) {
      state.answers[state.current] = value;
      // Update button visuals
      [...elOptions.children].forEach((child) => {
        const v = Number(child.getAttribute('data-value'));
        child.className = `option-btn p-3 sm:p-4 rounded-xl border text-center font-semibold transition w-full ${v === value ? 'bg-primary-600 text-white border-primary-600' : 'bg-white hover:bg-primary-50 border-slate-200 text-slate-700'}`;
      });
      elNext.disabled = false;
      elTimeoutMsg.classList.add('hidden');
    }

    function next() {
      if (state.answers[state.current] === null) {
        // Not answered
        elTimeoutMsg.textContent = 'Debes responder antes de continuar.';
        elTimeoutMsg.classList.remove('hidden');
        pulseTimer();
        return;
      }
      if (state.current < totalQuestions - 1) {
        state.current++;
        renderQuestion();
      } else {
        finish();
      }
    }

    function prev() {
      if (state.current > 0) {
        state.current--;
        renderQuestion();
      }
    }

    function resetTimer() {
      if (state.interval) clearInterval(state.interval);
      state.timer = 15;
      elTimer.textContent = state.timer;
      elTimer.className = 'h-9 w-9 rounded-full bg-primary-100 grid place-content-center text-primary-700 font-bold';
      elTimeoutMsg.classList.add('hidden');
      state.interval = setInterval(() => {
        state.timer--;
        elTimer.textContent = state.timer;
        if (state.timer <= 5) {
          elTimer.className = 'h-9 w-9 rounded-full bg-amber-100 grid place-content-center text-amber-700 font-bold';
        }
        if (state.timer <= 0) {
          clearInterval(state.interval);
          elTimer.className = 'h-9 w-9 rounded-full bg-rose-100 grid place-content-center text-rose-700 font-bold animate-pulse';
          elTimeoutMsg.textContent = 'Tiempo agotado. Debes responder para continuar.';
          elTimeoutMsg.classList.remove('hidden');
          pulseTimer();
        }
      }, 1000);
    }

    function pulseTimer() {
      elTimer.classList.add('ring-2', 'ring-rose-400', 'ring-offset-2');
      setTimeout(() => elTimer.classList.remove('ring-2', 'ring-rose-400', 'ring-offset-2'), 1500);
    }

    function finish() {
      // Compute scores
      const styleScores = STYLES.reduce((acc, s) => (acc[s] = 0, acc), {});
      ALL_QUESTIONS.forEach((q, idx) => {
        const v = Number(state.answers[idx] || 0);
        styleScores[q.style] += v;
      });
      const maxScore = Math.max(...Object.values(styleScores));
      const normalized = {};
      for (const s of STYLES) {
        normalized[s] = maxScore > 0 ? Math.round((styleScores[s] / maxScore) * 100) : 0;
      }

      const dominantStyle = STYLES.reduce((best, s) => styleScores[s] > styleScores[best] ? s : best, STYLES[0]);

      const payload = {
        timestamp: new Date().toISOString(),
        answers: state.answers,
        styleScores,
        normalized,
        dominantStyle,
        totalQuestions,
      };

      try {
        localStorage.setItem('leadershipAssessmentResult', JSON.stringify(payload));
      } catch (e) {
        console.warn('No se pudo guardar en localStorage', e);
      }

      window.location.href = '/admin/login.php';
    }

    elNext.addEventListener('click', next);
    elPrev.addEventListener('click', prev);

    window.addEventListener('beforeunload', (e) => {
      // Warn if quiz in progress
      if (state.answers.some(a => a !== null) && state.answers.some(a => a === null)) {
        e.preventDefault();
        e.returnValue = '';
      }
    });

    // Initialize
    renderQuestion();
  </script>
</body>
</html>
