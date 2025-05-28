window.graficasEmpleado = (() => {
    let chart = null, porcentajeChart = null, faltasChart = null, lineasChart = null;

    function actualizarComparativas(data) {
        const c = document.getElementById('comparacion');
        const m = document.getElementById('mensaje-mensual');

        if (data.diferenciaPorcentaje > 0) {
            c.textContent = `Este empleado tiene un ${data.diferenciaPorcentaje}% más asistencia que la media (${data.media}%).`;
            c.className = "text-green-600 font-semibold mt-2";
        } else if (data.diferenciaPorcentaje < 0) {
            c.textContent = `Este empleado tiene un ${Math.abs(data.diferenciaPorcentaje)}% menos asistencia que la media (${data.media}%).`;
            c.className = "text-red-600 font-semibold mt-2";
        } else {
            c.textContent = `Este empleado tiene exactamente la media de asistencia (${data.media}%).`;
            c.className = "text-yellow-600 font-semibold mt-2";
        }

        if (data.diferenciaFaltas > 0) {
            m.textContent = `Este mes tiene ${data.diferenciaFaltas} falta(s) más que el anterior 😕`;
            m.className = 'text-red-600 text-sm font-semibold mt-2';
        } else if (data.diferenciaFaltas < 0) {
            m.textContent = `Este mes tiene ${Math.abs(data.diferenciaFaltas)} falta(s) menos que el anterior 🎉`;
            m.className = 'text-green-600 text-sm font-semibold mt-2';
        } else {
            m.textContent = `Tiene el mismo número de faltas que el mes anterior.`;
            m.className = 'text-yellow-600 text-sm font-semibold mt-2';
        }
    }

    function actualizarChart(type, ref, canvasId, labels, data, bgColor, label = '') {
        const ctx = document.getElementById(canvasId).getContext('2d');
        if (ref) {
            ref.data.datasets[0].data = data;
            ref.update();
        } else {
            ref = new Chart(ctx, {
                type,
                data: {
                    labels,
                    datasets: [{
                        label,
                        data,
                        backgroundColor: bgColor,
                        borderColor: bgColor.map(c => c.replace('0.6', '1')),
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { position: 'bottom' } },
                    scales: { y: { beginAtZero: true, max: type === 'bar' ? 100 : undefined } }
                }
            });
        }

        if (canvasId === 'asistenciaChart') chart = ref;
        if (canvasId === 'comparativaPorcentajeChart') porcentajeChart = ref;
        if (canvasId === 'comparativaFaltasChart') faltasChart = ref;
    }

    function actualizarLineaFaltas({ meses, faltas }) {
        const ctx = document.getElementById('graficoLineasFaltas').getContext('2d');
        if (lineasChart) {
            lineasChart.data.labels = meses;
            lineasChart.data.datasets[0].data = faltas;
            lineasChart.update();
        } else {
            lineasChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: meses,
                    datasets: [{
                        label: 'Faltas por mes',
                        data: faltas,
                        fill: false,
                        borderColor: '#d23e5d',
                        tension: 0.2,
                        pointBackgroundColor: '#872829'
                    }]
                },
                options: {
                    responsive: true,
                    scales: { y: { beginAtZero: true } },
                    plugins: { legend: { labels: { color: '#1d1d1b' } } }
                }
            });
        }
    }

    async function cargarDatosEmpleado(id) {
        if (!id) return;

        const res = await fetch(`/faltas/graficas/datos/${id}`);
        const data = await res.json();

        document.getElementById('graficas-seccion').classList.remove('hidden');
        document.getElementById('empleado-info').classList.remove('hidden');
        document.getElementById('info').classList.remove('hidden');

        const foto = document.getElementById('foto-empleado');
        foto.src = data.foto ? `/storage/${data.foto}` : "";
        foto.classList.toggle('hidden', !data.foto);
        document.getElementById('nombre-empleado').textContent = data.nombre;

        document.getElementById('asistencias').textContent = data.asistencias;
        document.getElementById('faltas').textContent = data.faltas;
        document.getElementById('porcentaje').textContent = data.porcentaje;
        document.getElementById('minutosRetraso').textContent = data.minutosRetraso;
        document.getElementById('diasRetraso').textContent = data.diasConRetraso;

        actualizarComparativas(data);
        actualizarChart('doughnut', chart, 'asistenciaChart', ['Asistencias', 'Faltas'], [data.asistencias, data.faltas], ['#7ebdb3', '#d23e5d']);
        actualizarChart('bar', porcentajeChart, 'comparativaPorcentajeChart', ['Empleado', 'Media'], [data.porcentaje, data.media], ['#317080', '#7ebdb3'], '% Asistencia');
        actualizarChart('bar', faltasChart, 'comparativaFaltasChart', ['Este mes', 'Mes anterior'], [data.faltas, data.faltasMesAnterior], ['#d23e5d', '#872829'], 'Faltas');

        const resLineas = await fetch(`/faltas/graficas/faltas-mensuales/${id}`);
        const datosLineas = await resLineas.json();
        actualizarLineaFaltas(datosLineas);
    }

    function cargarGraficaGlobal(url, id, color) {
        fetch(url)
            .then(res => res.json())
            .then(({ labels, data }) => {
                new Chart(document.getElementById(id), {
                    type: 'bar',
                    data: {
                        labels,
                        datasets: [{
                            label: '',
                            data,
                            backgroundColor: color + '99',
                            borderColor: color,
                            borderWidth: 1
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        plugins: { legend: { display: false } },
                        scales: { x: { beginAtZero: true, precision: 0 } }
                    }
                });
            });
    }

    return {
        cargarDatosEmpleado,
        cargarGraficaGlobal
    };
})();
