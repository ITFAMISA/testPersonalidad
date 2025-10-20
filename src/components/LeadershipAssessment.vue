<template>
  <div class="w-full min-h-screen flex flex-col">
    <!-- Header -->
    <header class="no-print sticky top-0 z-10 bg-white/70 backdrop-blur border-b border-slate-200">
      <div class="max-w-5xl mx-auto px-4 py-3 flex items-center justify-between">
        <div class="flex items-center gap-3">
          <div class="h-9 w-9 rounded-md bg-primary-600 text-white grid place-content-center font-bold">L</div>
          <div>
            <h1 class="text-base font-semibold">Evaluación de Estilos de Liderazgo</h1>
            <p class="text-xs text-slate-500">Vue 3 + Tailwind • 7 estilos • 70 preguntas</p>
          </div>
        </div>
        <div class="text-xs text-slate-500" v-if="view === 'questionnaire'">Tiempo por pregunta: 15s</div>
      </div>
    </header>

    <!-- Main content -->
    <main class="flex-1">
      <!-- Welcome Screen -->
      <section v-if="view === 'welcome'" class="max-w-5xl mx-auto px-4 py-16">
        <div class="grid md:grid-cols-2 gap-10 items-center">
          <div class="space-y-6">
            <h2 class="text-3xl md:text-4xl font-extrabold leading-tight text-slate-900">
              Descubre tu estilo de liderazgo
            </h2>
            <p class="text-slate-600 leading-relaxed">
              Responde 70 afirmaciones en menos de 15 minutos. Al finalizar verás un dashboard con tus resultados por estilo y tu estilo dominante. Datos locales, sin backend.
            </p>
            <ul class="text-slate-700 text-sm space-y-2">
              <li class="flex items-center gap-2"><span class="h-2 w-2 bg-primary-500 rounded-full"></span> 7 estilos: Autoritario, Democrático, Transformacional, Transaccional, Laissez-Faire, Carismático y Situacional</li>
              <li class="flex items-center gap-2"><span class="h-2 w-2 bg-primary-500 rounded-full"></span> 70 preguntas (10 por estilo), escala 1-5</li>
              <li class="flex items-center gap-2"><span class="h-2 w-2 bg-primary-500 rounded-full"></span> Temporizador de 15 segundos por pregunta</li>
              <li class="flex items-center gap-2"><span class="h-2 w-2 bg-primary-500 rounded-full"></span> Guardado automático en tu navegador</li>
            </ul>
            <div class="flex flex-wrap gap-3 pt-4">
              <button @click="startAssessment" class="inline-flex items-center justify-center px-5 py-3 rounded-md bg-primary-600 hover:bg-primary-700 text-white font-medium shadow-sm transition">
                Comenzar evaluación
              </button>
              <button @click="resumeIfAny" class="inline-flex items-center justify-center px-5 py-3 rounded-md bg-white border border-slate-300 text-slate-700 hover:bg-slate-50 font-medium shadow-sm transition">
                Reanudar progreso
              </button>
            </div>
          </div>
          <div class="relative">
            <div class="aspect-[4/3] rounded-xl bg-gradient-to-br from-primary-50 to-emerald-50 border border-slate-200 p-6">
              <div class="grid grid-cols-7 gap-2 h-full">
                <div v-for="(s, i) in styles" :key="s.key" class="flex flex-col justify-end">
                  <div class="bg-primary-500/10 border border-primary-500/20 rounded-t-md" :style="{ height: `${(i+3)*10}%`, backgroundColor: s.color + '22', borderColor: s.color + '44' }"></div>
                  <span class="text-[11px] text-slate-500 mt-2 text-center truncate">{{ s.short }}</span>
                </div>
              </div>
            </div>
            <div class="absolute -bottom-4 -right-2 bg-white border border-slate-200 shadow rounded-md px-3 py-2 text-xs text-slate-600">UI de ejemplo • Sin backend</div>
          </div>
        </div>
      </section>

      <!-- Questionnaire View -->
      <section v-else-if="view === 'questionnaire'" class="max-w-4xl mx-auto px-4 py-8">
        <div class="mb-6">
          <ProgressBar :current="currentIndex + 1" :total="totalQuestions" />
        </div>

        <div class="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
          <div class="px-5 md:px-8 py-4 flex items-center justify-between gap-3 border-b border-slate-200">
            <div class="text-sm text-slate-600">Pregunta <span class="font-semibold text-slate-900">{{ currentIndex + 1 }}</span> de {{ totalQuestions }}</div>
            <div class="flex items-center gap-2">
              <span class="text-xs text-slate-500 hidden sm:inline">Tiempo restante</span>
              <div class="px-3 py-1 rounded-full text-sm font-medium" :class="timeExpired ? 'bg-red-100 text-red-700' : timeLeft <= 5 ? 'bg-amber-100 text-amber-700' : 'bg-primary-50 text-primary-700'">
                {{ timeLeft }}s
              </div>
            </div>
          </div>

          <QuestionCard
            :question="questions[currentIndex]"
            :selected="answers[currentIndex]"
            :timeLeft="timeLeft"
            :timeExpired="timeExpired"
            @select="selectAnswer"
          />

          <div class="flex items-center justify-between px-5 md:px-8 py-4 border-t border-slate-200 bg-slate-50/50">
            <NavigationButtons
              :canPrev="currentIndex > 0"
              :canNext="answers[currentIndex] !== null"
              :isLast="currentIndex === totalQuestions - 1"
              @prev="prevQuestion"
              @next="nextQuestion"
            />
          </div>
        </div>

        <div v-if="!answers[currentIndex] && timeExpired" class="mt-3 text-sm text-red-600">Tiempo agotado. Debes seleccionar una opción para continuar.</div>
        <div class="mt-3 text-xs text-slate-500">Guardado automático activado. Puedes reanudar más tarde desde este dispositivo.</div>
      </section>

      <!-- Admin Login before results -->
      <section v-else-if="view === 'adminLogin'" class="max-w-md mx-auto px-4 py-16">
        <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
          <h3 class="text-xl font-bold">Dashboard de Resultados — Acceso</h3>
          <p class="text-slate-600 text-sm mt-1">Accede con tu clave de administrador para ver el resumen y exportar.</p>
          <div class="mt-6 space-y-4">
            <div>
              <label class="block text-sm font-medium text-slate-700">Contraseña</label>
              <input v-model="adminPassword" type="password" class="mt-1 w-full rounded-md border-slate-300 focus:border-primary-500 focus:ring-primary-500" placeholder="Ingresa tu clave (p. ej. admin)" />
            </div>
            <div>
              <button @click="handleLogin" class="w-full inline-flex items-center justify-center px-4 py-2 rounded-md bg-primary-600 hover:bg-primary-700 text-white font-medium shadow-sm transition">Ingresar</button>
            </div>
            <p v-if="loginError" class="text-sm text-red-600">{{ loginError }}</p>
            <button @click="goBackToQuestions" class="text-sm text-slate-600 underline">Volver al cuestionario</button>
          </div>
        </div>
      </section>

      <!-- Results View -->
      <section v-else class="max-w-6xl mx-auto px-4 py-10 print:py-0">
        <div class="flex items-center justify-between flex-wrap gap-3 no-print">
          <div>
            <h3 class="text-2xl font-extrabold">Dashboard de Resultados</h3>
            <p class="text-slate-600 text-sm">Resumen de puntajes y comparación entre estilos</p>
          </div>
          <div class="flex items-center gap-3">
            <button @click="exportAsImage" class="inline-flex items-center justify-center px-4 py-2 rounded-md border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-sm">Exportar imagen</button>
            <button @click="printResults" class="inline-flex items-center justify-center px-4 py-2 rounded-md border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-sm">Exportar PDF</button>
            <button @click="resetAll" class="inline-flex items-center justify-center px-4 py-2 rounded-md bg-primary-600 hover:bg-primary-700 text-white text-sm">Reiniciar evaluación</button>
          </div>
        </div>

        <!-- Dominant style highlight -->
        <div class="mt-6 grid md:grid-cols-3 gap-6 items-stretch">
          <div class="md:col-span-1">
            <div class="h-full bg-white border border-slate-200 rounded-xl shadow-sm p-6">
              <h4 class="text-sm font-semibold text-slate-700">Estilo dominante</h4>
              <div class="mt-3 flex items-start gap-3">
                <div class="h-10 w-10 rounded-md" :style="{ backgroundColor: dominantStyle.color + '22', border: `1px solid ${dominantStyle.color}44` }"></div>
                <div>
                  <div class="text-lg font-bold">{{ dominantStyle.label }}</div>
                  <div class="text-slate-600 text-sm">{{ scoresByStyle[dominantIndex].score }} / 50 puntos</div>
                  <div class="text-slate-600 text-sm">Normalizado: {{ Math.round(normalizedByStyle[dominantIndex]) }}%</div>
                </div>
              </div>
              <p class="text-sm text-slate-700 leading-relaxed mt-4">{{ dominantStyle.description }}</p>
            </div>
          </div>
          <div class="md:col-span-2">
            <div ref="chartRef" class="h-full bg-white border border-slate-200 rounded-xl shadow-sm p-6">
              <h4 class="text-sm font-semibold text-slate-700">Comparativa por estilo</h4>
              <div class="mt-4 space-y-3">
                <div v-for="(s, i) in styles" :key="s.key" class="">
                  <div class="flex items-center justify-between text-xs text-slate-600">
                    <span class="font-medium">{{ s.label }}</span>
                    <span>{{ scoresByStyle[i].score }} / 50 · {{ Math.round(normalizedByStyle[i]) }}%</span>
                  </div>
                  <div class="mt-1 h-3 w-full bg-slate-100 rounded-full overflow-hidden border border-slate-200">
                    <div class="h-full rounded-full transition-all duration-700" :style="{ width: normalizedByStyle[i] + '%', backgroundColor: s.color }"></div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Grid of scores with descriptions -->
        <div class="mt-8 grid md:grid-cols-2 lg:grid-cols-3 gap-6">
          <ScoreCard
            v-for="(s, i) in styles"
            :key="s.key"
            :name="s.label"
            :score="scoresByStyle[i].score"
            :percentage="Math.round(normalizedByStyle[i])"
            :isDominant="i === dominantIndex"
            :description="s.description"
            :color="s.color"
          />
        </div>
      </section>
    </main>

    <!-- Footer -->
    <footer class="no-print border-t border-slate-200 mt-10">
      <div class="max-w-5xl mx-auto px-4 py-6 text-xs text-slate-500 flex items-center justify-between">
        <span>© {{ new Date().getFullYear() }} Leadership Assessment UI</span>
        <span>Vue 3 + Tailwind (CDN) • Datos en memoria/localStorage</span>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref, reactive, computed, watch, onMounted, onBeforeUnmount, defineComponent } from 'vue'

// Metadata for styles
const styles = [
  { key: 'AUTORITARIO', short: 'Auto', label: 'Autoritario', color: '#1e40af', description: 'Decisiones centralizadas, reglas claras y control directo orientado a la ejecución rápida.' },
  { key: 'DEMOCRATICO', short: 'Demo', label: 'Democrático', color: '#0f766e', description: 'Participación del equipo, procesos inclusivos y toma de decisiones por consenso.' },
  { key: 'TRANSFORMACIONAL', short: 'Transf', label: 'Transformacional', color: '#7c3aed', description: 'Inspiración, visión a largo plazo y desarrollo individual para lograr cambios profundos.' },
  { key: 'TRANSACCIONAL', short: 'Transa', label: 'Transaccional', color: '#be123c', description: 'Intercambio claro de recompensas por resultados, supervisión y estabilidad operacional.' },
  { key: 'LAISSEZ_FAIRE', short: 'L-F', label: 'Laissez-Faire', color: '#065f46', description: 'Alta autonomía, mínima intervención y confianza en la autogestión del equipo.' },
  { key: 'CARISMATICO', short: 'Caris', label: 'Carismático', color: '#b45309', description: 'Influencia personal, comunicación emocional y energía para movilizar al equipo.' },
  { key: 'SITUACIONAL', short: 'Situa', label: 'Situacional', color: '#2563eb', description: 'Adaptación del estilo a la madurez, competencia y contexto de cada situación.' },
]

// Questions per style (10 each)
const QUESTIONS_BY_STYLE = {
  AUTORITARIO: [
    'Yo tomo la mayoría de las decisiones sin consultar al equipo, pero explico el "por qué".',
    'La obediencia y el cumplimiento estricto de las reglas son mis expectativas más altas.',
    'Prefiero que mi equipo se centre en ejecutar las tareas tal como las he diseñado.',
    'En situaciones de crisis, mi voz es la única que debe escucharse para actuar rápido.',
    'Considero que soy el único responsable de los resultados, tanto buenos como malos.',
    'La retroalimentación se da principalmente para corregir errores de ejecución y procedimiento.',
    'Mantengo cierta distancia personal para preservar mi autoridad como líder.',
    'Asigno tareas a las personas basándome estrictamente en su capacidad demostrada.',
    'Los objetivos son establecidos por mí y luego comunicados al equipo.',
    'Las recompensas y castigos son herramientas efectivas para motivar el cumplimiento.',
  ],
  DEMOCRATICO: [
    'Fomento la discusión abierta y la entrada de ideas antes de tomar una decisión.',
    'Comparto la información relevante con el equipo para que tomen decisiones informadas.',
    'Mi rol es más de facilitador o coach que de director.',
    'El consenso del equipo a menudo produce soluciones más creativas que mi propia idea.',
    'Animo a los miembros del equipo a asumir la responsabilidad de las decisiones grupales.',
    'Las reuniones se utilizan para debatir y votar sobre cursos de acción.',
    'Creo que la toma de decisiones debe ser un proceso inclusivo y transparente.',
    'Busco activamente la opinión de los miembros junior del equipo.',
    'Si hay un desacuerdo, me esfuerzo por encontrar una solución que satisfaga a la mayoría.',
    'La satisfacción del equipo con el proceso es tan importante como el resultado final.',
  ],
  TRANSFORMACIONAL: [
    'Inspiro a mi equipo para que vean su trabajo como parte de una misión o propósito mayor.',
    'Animo a mi equipo a desafiar el statu quo y a ser innovadores.',
    'Dedico tiempo a la tutoría y al desarrollo individual, tratando a cada uno de forma única.',
    'Transmito una visión clara y optimista del futuro que el equipo está ayudando a construir.',
    'Me gano el respeto y la confianza actuando como un modelo a seguir (role model).',
    'Motivo al equipo apelando a sus valores e ideales, más que a recompensas materiales.',
    'Fomento el pensamiento crítico y la resolución creativa de problemas en el equipo.',
    'Mis acciones a menudo se centran en el crecimiento a largo plazo en lugar de las ganancias inmediatas.',
    'Hago que el equipo sea consciente de la importancia de sus tareas para la organización.',
    'El equipo está dispuesto a hacer un esfuerzo adicional por mí debido a la fe que tiene en la visión.',
  ],
  TRANSACCIONAL: [
    'Establezco claramente las recompensas que se darán por alcanzar los objetivos de rendimiento.',
    'Intervengo activamente solo cuando se identifican errores o se incumplen los estándares.',
    'El desempeño se evalúa con métricas y sistemas de feedback formales y regulares.',
    'Utilizo un sistema de incentivos y castigos (contingencia de recompensa) para dirigir el comportamiento del equipo.',
    'Los acuerdos y expectativas de trabajo se basan en un intercambio claro de esfuerzo por recompensa.',
    'El control y la supervisión son clave para asegurar que las reglas se cumplan.',
    'No me enfoco en la motivación, sino en dejar claros los pasos y los resultados esperados.',
    'Mi estilo es efectivo para mantener la estabilidad y la rutina en la organización.',
    'Utilizo los informes de rendimiento para determinar promociones y aumentos salariales.',
    'El equipo sabe exactamente qué debe hacer y qué obtendrá a cambio.',
  ],
  LAISSEZ_FAIRE: [
    'Doy total autonomía a mi equipo para que resuelvan sus propios problemas.',
    'Evito interferir o microgestionar el trabajo diario del equipo.',
    'Ofrezco los recursos necesarios y luego me retiro para que el equipo trabaje a su aire.',
    'No intervengo a menos que se me solicite ayuda o que haya un problema grave.',
    'Creo que el equipo altamente competente trabaja mejor sin supervisión directa.',
    'La toma de decisiones suele recaer en el miembro del equipo con más conocimiento o interés.',
    'Mi presencia no es esencial para que el equipo sepa qué hacer.',
    'Dejo que mi equipo decida la mejor forma y el ritmo para alcanzar los objetivos.',
    'Evito dar feedback no solicitado, confiando en el juicio profesional de mi equipo.',
    'Soy el último recurso y solo actúo cuando la delegación fracasa.',
  ],
  CARISMATICO: [
    'Mi entusiasmo por la visión es contagioso y motiva a mi equipo.',
    'La gente se siente inspirada por mi confianza en mí mismo y en el futuro.',
    'Utilizo historias, símbolos y lenguaje emocional para conectar con mi equipo.',
    'Mis ideas y sugerencias son a menudo aceptadas por la fuerza de mi personalidad.',
    'El equipo siente un fuerte vínculo emocional y lealtad hacia mí.',
    'Puedo hacer que la gente crea en metas que parecen casi imposibles.',
    'Me importa la impresión que doy y me aseguro de proyectar confianza y competencia.',
    'La forma en que presento las ideas es tan importante como las ideas mismas.',
    'La gente está dispuesta a hacer sacrificios personales por la causa que yo lidero.',
    'Mi energía y pasión impulsan el estado de ánimo y la moral del equipo.',
  ],
  SITUACIONAL: [
    'Adapto mi estilo de liderazgo basándome en la madurez y competencia de la persona o equipo.',
    'En tareas nuevas, soy directivo; en tareas conocidas, soy delegativo.',
    'Utilizo un estilo de apoyo y colaboración si el equipo tiene la capacidad, pero le falta confianza.',
    'Soy capaz de moverme sin problemas entre el control estricto y la delegación total.',
    'Evalúo la necesidad del momento (urgencia, complejidad) antes de decidir mi enfoque.',
    'Mi primer paso al liderar una tarea es evaluar la disposición (habilidad + voluntad) del equipo.',
    'Creo que no existe un "mejor" estilo de liderazgo, solo el más adecuado para el contexto.',
    'Puedo ser firme y centrado en la tarea un día y muy centrado en las personas al día siguiente.',
    'Mis métodos de coaching varían significativamente de un empleado a otro.',
    'Si el equipo está desmotivado pero es capaz, me enfoco en el apoyo emocional.',
  ],
}

const questions = []
for (const s of styles) {
  const arr = QUESTIONS_BY_STYLE[s.key]
  arr.forEach((text, idx) => {
    questions.push({
      id: `${s.key}-${idx + 1}`,
      styleKey: s.key,
      styleIndex: styles.findIndex(ss => ss.key === s.key),
      text,
    })
  })
}

// App state
const STORAGE_KEY = 'leadership_assessment_v1'
const view = ref('welcome') // 'welcome' | 'questionnaire' | 'adminLogin' | 'results'
const totalQuestions = questions.length
const currentIndex = ref(0)
const answers = ref(Array(totalQuestions).fill(null))
const timeLeft = ref(15)
const timeExpired = ref(false)
let intervalId = null

const startTimer = () => {
  clearTimer()
  timeLeft.value = 15
  timeExpired.value = false
  intervalId = setInterval(() => {
    if (timeLeft.value > 0) timeLeft.value -= 1
    if (timeLeft.value <= 0) {
      timeLeft.value = 0
      timeExpired.value = true
      clearTimer()
    }
  }, 1000)
}
const clearTimer = () => { if (intervalId) { clearInterval(intervalId); intervalId = null } }

function startAssessment() {
  view.value = 'questionnaire'
  startTimer()
}

function resumeIfAny() {
  const saved = loadState()
  if (saved) {
    view.value = saved.view || 'questionnaire'
    currentIndex.value = saved.currentIndex ?? 0
    answers.value = saved.answers?.slice(0, totalQuestions) ?? answers.value
    startTimer()
  } else {
    startAssessment()
  }
}

function selectAnswer(val) {
  answers.value[currentIndex.value] = val
  persistState()
}

function nextQuestion() {
  if (answers.value[currentIndex.value] == null) return
  if (currentIndex.value < totalQuestions - 1) {
    currentIndex.value += 1
    startTimer()
  } else {
    clearTimer()
    view.value = 'adminLogin'
    persistState()
  }
}

function prevQuestion() {
  if (currentIndex.value > 0) {
    currentIndex.value -= 1
    startTimer()
  }
}

function goBackToQuestions() {
  view.value = 'questionnaire'
  startTimer()
}

// Admin login placeholder
const adminPassword = ref('')
const loginError = ref('')
function handleLogin() {
  loginError.value = ''
  // Placeholder: simple check or allow if any non-empty. You can replace with real auth later.
  if (adminPassword.value.trim().length === 0) {
    loginError.value = 'Por favor ingresa la contraseña (placeholder: "admin").'
    return
  }
  view.value = 'results'
}

// Scoring
const scoresByStyle = computed(() => {
  const scores = styles.map((s, i) => ({ key: s.key, label: s.label, score: 0 }))
  answers.value.forEach((ans, idx) => {
    const styleIdx = Math.floor(idx / 10)
    scores[styleIdx].score += ans ? Number(ans) : 0
  })
  return scores
})

const maxScore = computed(() => Math.max(...scoresByStyle.value.map(s => s.score)))
const normalizedByStyle = computed(() => {
  const max = maxScore.value || 1
  return scoresByStyle.value.map(s => (s.score / max) * 100)
})
const dominantIndex = computed(() => {
  let max = -Infinity
  let idx = 0
  scoresByStyle.value.forEach((s, i) => { if (s.score > max) { max = s.score; idx = i } })
  return idx
})
const dominantStyle = computed(() => styles[dominantIndex.value])

// Persistence
function persistState() {
  const payload = { view: view.value, currentIndex: currentIndex.value, answers: answers.value }
  try { localStorage.setItem(STORAGE_KEY, JSON.stringify(payload)) } catch (e) {}
}
function loadState() {
  try {
    const raw = localStorage.getItem(STORAGE_KEY)
    if (!raw) return null
    return JSON.parse(raw)
  } catch (e) { return null }
}
function resetAll() {
  clearTimer()
  localStorage.removeItem(STORAGE_KEY)
  view.value = 'welcome'
  currentIndex.value = 0
  answers.value = Array(totalQuestions).fill(null)
  adminPassword.value = ''
  loginError.value = ''
  setTimeout(() => window.scrollTo({ top: 0, behavior: 'smooth' }), 0)
}

// Export helpers
const chartRef = ref(null)
async function exportAsImage() {
  const targetEl = document.querySelector('section:last-of-type')
  const fn = window.html2canvas
  if (!fn) { alert('html2canvas no disponible'); return }
  const canvas = await fn(targetEl, { backgroundColor: '#ffffff', scale: window.devicePixelRatio || 2 })
  const link = document.createElement('a')
  link.download = 'resultados-liderazgo.png'
  link.href = canvas.toDataURL('image/png')
  link.click()
}
function printResults() { window.print() }

watch([currentIndex, answers, view], () => { persistState() }, { deep: true })

onMounted(() => {
  const saved = loadState()
  if (saved && Array.isArray(saved.answers) && saved.answers.some(v => v !== null)) {
    view.value = 'welcome'
  }
})

onBeforeUnmount(() => { clearTimer() })

// UI sub-components
const ProgressBar = defineComponent({
  name: 'ProgressBar',
  props: { current: Number, total: Number },
  computed: {
    percent() { return Math.round((this.current / this.total) * 100) }
  },
  template: `
    <div>
      <div class="flex items-center justify-between text-xs text-slate-600 mb-1">
        <span>Progreso</span>
        <span>{{ current }} / {{ total }} · {{ percent }}%</span>
      </div>
      <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden border border-slate-200">
        <div class="h-full bg-primary-600 rounded-full transition-all" :style="{ width: percent + '%' }"></div>
      </div>
    </div>
  `
})

const QuestionCard = defineComponent({
  name: 'QuestionCard',
  props: { question: Object, selected: [Number, null], timeLeft: Number, timeExpired: Boolean },
  emits: ['select'],
  setup(props, { emit }) {
    const options = [1,2,3,4,5]
    const labels = {
      1: 'Totalmente en desacuerdo',
      3: 'Neutral',
      5: 'Totalmente de acuerdo'
    }
    function choose(v) { emit('select', v) }
    return { options, labels, choose }
  },
  template: `
    <div class="px-5 md:px-8 py-8">
      <div class="text-sm uppercase tracking-wide text-slate-500">{{ question.styleKey.replace('_','-') }}</div>
      <p class="mt-2 text-xl font-semibold leading-snug">{{ question.text }}</p>

      <div class="mt-6 grid grid-cols-5 gap-2 sm:gap-3">
        <button v-for="n in options" :key="n" @click="choose(n)" :aria-pressed="selected === n"
          class="relative group h-12 sm:h-14 rounded-md border transition text-sm sm:text-base"
          :class="selected === n ? 'bg-primary-600 text-white border-primary-600 shadow' : 'bg-white border-slate-300 hover:border-primary-400 hover:bg-primary-50'">
          <span class="font-semibold">{{ n }}</span>
          <span class="absolute -bottom-6 left-1/2 -translate-x-1/2 text-[10px] text-slate-500" v-if="n === 1">{{ labels[1] }}</span>
          <span class="absolute -bottom-6 left-1/2 -translate-x-1/2 text-[10px] text-slate-500" v-if="n === 3">{{ labels[3] }}</span>
          <span class="absolute -bottom-6 left-1/2 -translate-x-1/2 text-[10px] text-slate-500" v-if="n === 5">{{ labels[5] }}</span>
        </button>
      </div>
    </div>
  `
})

const NavigationButtons = defineComponent({
  name: 'NavigationButtons',
  props: { canPrev: Boolean, canNext: Boolean, isLast: Boolean },
  emits: ['prev', 'next'],
  template: `
    <div class="w-full flex items-center justify-between">
      <button @click="$emit('prev')" :disabled="!canPrev" class="px-4 py-2 rounded-md border bg-white text-slate-700 disabled:opacity-50 disabled:cursor-not-allowed">Anterior</button>
      <button @click="$emit('next')" :disabled="!canNext" class="px-4 py-2 rounded-md bg-primary-600 hover:bg-primary-700 text-white disabled:opacity-50 disabled:cursor-not-allowed">{{ isLast ? 'Finalizar' : 'Siguiente' }}</button>
    </div>
  `
})

const ScoreCard = defineComponent({
  name: 'ScoreCard',
  props: { name: String, score: Number, percentage: Number, isDominant: Boolean, description: String, color: String },
  template: `
    <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-5">
      <div class="flex items-center justify-between gap-3">
        <div>
          <div class="text-base font-bold">{{ name }}</div>
          <div class="text-sm text-slate-600">{{ score }} / 50 · {{ percentage }}%</div>
        </div>
        <div class="h-10 w-10 rounded-md border" :style="{ backgroundColor: color + '22', borderColor: color + '44' }"></div>
      </div>
      <p class="text-sm text-slate-700 leading-relaxed mt-3">{{ description }}</p>
      <div class="mt-4 h-2 w-full bg-slate-100 rounded-full overflow-hidden border border-slate-200">
        <div class="h-full rounded-full" :style="{ width: percentage + '%', backgroundColor: color }"></div>
      </div>
      <div v-if="isDominant" class="mt-3 inline-block text-[11px] font-medium text-primary-700 bg-primary-50 border border-primary-100 rounded px-2 py-0.5">Dominante</div>
    </div>
  `
})
</script>

<style scoped>
</style>
