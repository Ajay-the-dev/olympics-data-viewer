<script setup>
import { ref, onMounted, computed, watch } from 'vue'
import { Chart, registerables } from 'chart.js'
Chart.register(...registerables)

const props = defineProps({
  width: { type: Number, default: 200 },
  height: { type: Number, default: 200 },
  propLabels: { type: Array, default: () => [] }
})

const canvasRef = ref(null)
const chartInstance = ref(null)

const containerStyle = computed(() => ({
  width: `${props.width}px`,
  height: `${props.height}px`,
  position: 'relative'
}))

const labels = computed(() => props.propLabels.map((_, i) => `COURSE ${i + 1}`))
const labelValues = computed(() => props.propLabels.map(el => parseFloat(el.totalGained || 0)))

watch(() => props.propLabels, () => {
  if (canvasRef.value) {
    renderChart()
  }
}, { immediate: true, deep: true })

function renderChart() {
  if (!canvasRef.value) return; // ⛔ Prevent Chart init if canvas not ready

  if (chartInstance.value) {
    chartInstance.value.destroy()
  }

  chartInstance.value = new Chart(canvasRef.value, {
    type: 'bar',
    data: {
      labels: labels.value,
      datasets: [{
        label: 'GAIN',
        data: labelValues.value,
        backgroundColor: ['#ff6384', '#36a2eb', '#4bc0c0', '#9966ff']
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        y: { beginAtZero: true }
      }
    }
  })
}
</script>
