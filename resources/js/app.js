import './bootstrap';

import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => { //carrega so dps do HTML
    const canvas = document.getElementById('products-by-month-chart');  

    if (!canvas) {  //checa se ta na pagina certa por meio de ver se o canvas é nulo (só a dashboard tem valor)
        return;
    }

    const labels = JSON.parse(canvas.dataset.labels ?? '[]');   // acessa para pegar os nomes dos meses / parse para transformar em vetor em js
    const values = JSON.parse(canvas.dataset.values ?? '[]');   // acessa para pegar o count    /   ?? '[]' para nao dar erro de n achado, caso n tenha retorna []

    const valueLabelsPlugin = { // Faz com que tenha medida
        id: 'valueLabels',
        afterDatasetsDraw(chart) {  //dps que as barras do grafico forem criadas
            const { ctx } = chart;  // para desenhar
            const metadata = chart.getDatasetMeta(0);

            ctx.save(); //salva antes para fazer uma ideia de Dinamico / para caso tenha outra ocorrencia ele va normal
            ctx.fillStyle = '#ffffff';      //STYLE
            ctx.font = '600 12px Montserrat, sans-serif';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'bottom';

            metadata.data.forEach((bar, index) => {     //escreve o valor em cima da barra
                ctx.fillText(String(values[index]), bar.x, bar.y - 6);  // converte para texto / posiciona
            });

            ctx.restore(); // restora
        },
    };

    new Chart(canvas, {     //Criacao do grafico
        type: 'bar',        // de barras
        data: {
            labels, // nomes dos meses
            datasets: [
                {
                    data: values,       // valores
                    backgroundColor: '#42B9A6',     //estilo
                    hoverBackgroundColor: '#52C8B5',        //estilo
                    borderRadius: {     //arredonda
                        topLeft: 6,
                        topRight: 6,
                    },
                    borderSkipped: false,       //considera todas as barras
                    maxBarThickness: 64,        //Largura da Barras
                },
            ],
        },
        options: {
            responsive: true,       //responsiva
            maintainAspectRatio: false,     //usa o tamanho definido pelo container
            layout: {
                padding: {
                    top: 20,
                },
            },
            interaction: {      //interacao de mouse
                intersect: false,       // n precisa ta exatasmente em cima pra ativar a caixinha
                mode: 'index',
            },
            plugins: {
                legend: {
                    display: false,     //esconde o Produtos
                },
                tooltip: {      //estiliza a caixinha
                    backgroundColor: '#ffffff',
                    titleColor: '#042434',
                    bodyColor: '#042434',
                    borderColor: '#42B9A6',
                    borderWidth: 1,
                    padding: 12,
                    displayColors: false,
                    callbacks: {    
                        label(context) {
                            const total = context.parsed.y;     //pega a altura
                            const palavra = total === 1 ? 'produto' : 'produtos';   //checa pra ver se tem plural ou nao 

                            return `${total} ${palavra}`;   //concatena
                        },
                    },
                },
            },
            scales: {
                x: {    //eixo X - meses
                    border: {
                        color: 'rgba(148, 163, 184, 0.25)',
                    },
                    grid: {
                        display: false,
                    },
                    ticks: {
                        color: '#d1d5db',
                        font: {
                            family: 'Montserrat',
                            size: 12,
                        },
                    },
                },
                y: {     //eixo Y - Quantidade de prod
                    beginAtZero: true,
                    border: {
                        color: 'rgba(148, 163, 184, 0.25)',
                    },
                    grid: {
                        color: 'rgba(148, 163, 184, 0.15)',
                    },
                    ticks: {
                        color: '#d1d5db',
                        precision: 0,      //term que ser inteiro
                        font: {
                            family: 'Montserrat',
                            size: 12,
                        },
                    },
                    title: {
                        display: true,
                        text: 'Quantidade de produtos',
                        color: '#d1d5db',
                        font: {
                            family: 'Montserrat',
                            size: 12,
                            weight: '500',
                        },
                    },
                },
            },
        },
        plugins: [valueLabelsPlugin],      //Faz mostrar os valores em cima
    });
});
