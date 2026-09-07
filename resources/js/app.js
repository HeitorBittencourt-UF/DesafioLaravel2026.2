import './bootstrap';

import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

window.Alpine = Alpine;

Alpine.start();



document.addEventListener('DOMContentLoaded', () => {
    const cpfInput = document.getElementById('cpf');

    // Se não estiver em uma página que possui CPF skippa
    if (!cpfInput) {
        return;
    }

    function formatarCpf(valor) {
        // Remove tudo que não for número
        let cpf = valor.replace(/\D/g, '');

        // Permite no máximo 11 números
        cpf = cpf.slice(0, 11);

        // Primeiro ponto
        cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');

        // Segundo ponto
        cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');

        // Tracinho
        cpf = cpf.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

        return cpf;
    }

    // Formata também caso o Laravel tenha preenchido  o campo com old('cpf')
    cpfInput.value = formatarCpf(cpfInput.value);

    // Formata enquanto o usuário digita
    cpfInput.addEventListener('input', (event) => {
        event.target.value = formatarCpf(event.target.value);
    });

});

document.addEventListener('DOMContentLoaded', () => { //carrega so dps do HTML
    const canvas =
    document.getElementById('front-produtos-chart') ??
    document.getElementById('produto-by-month-chart');  

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

document.addEventListener('DOMContentLoaded', () => {
    const telefoneInput = document.getElementById('telefone');

    if (!telefoneInput) {
        return;
    }

    function formatarNumeroBrasileiro(numeros) {
        const telefone = numeros.slice(0, 11);

        if (telefone.length === 0) {
            return '';
        }

        if (telefone.length <= 2) {
            return '(' + telefone;
        }

        const ddd = telefone.slice(0, 2);
        const numero = telefone.slice(2);

        if (numero.length <= 4) {
            return '(' + ddd + ') ' + numero;
        }

        if (telefone.length <= 10) {
            return (
                '(' +
                ddd +
                ') ' +
                numero.slice(0, 4) +
                '-' +
                numero.slice(4, 8)
            );
        }

        return (
            '(' +
            ddd +
            ') ' +
            numero.slice(0, 5) +
            '-' +
            numero.slice(5, 9)
        );
    }

    function formatarTelefone(valor) {
        const possuiCodigoInternacional = valor
            .trimStart()
            .startsWith('+');

        const numeros = valor
            .replace(/\D/g, '')
            .slice(0, 15);

        if (!possuiCodigoInternacional) {
            return formatarNumeroBrasileiro(numeros);
        }

        if (!numeros.startsWith('55')) {
            return '+' + numeros;
        }

        const numeroBrasileiro = numeros.slice(2, 13);

        if (numeroBrasileiro.length === 0) {
            return '+55';
        }

        return '+55 ' + formatarNumeroBrasileiro(numeroBrasileiro);
    }

    telefoneInput.addEventListener('input', function () {
        this.value = formatarTelefone(this.value);
    });

    telefoneInput.value = formatarTelefone(telefoneInput.value);
});
/*
|--------------------------------------------------------------------------
| Consulta automática de CEP
|--------------------------------------------------------------------------
*/

document.addEventListener('DOMContentLoaded', () => {
    const cepInput = document.getElementById('cep');

    const logradouroInput = document.getElementById('logradouro');
    const bairroInput = document.getElementById('bairro');
    const cidadeInput = document.getElementById('cidade');
    const estadoInput = document.getElementById('estado');
    const numeroInput = document.getElementById('numero');

    // Não estamos na página de usuário/admin
    if (!cepInput) {
        return;
    }

    // Página de visualização possui os campos desabilitados
    if (cepInput.disabled) {
        return;
    }

    let ultimoCepConsultado = '';
    let temporizador = null;


    /*
    |--------------------------------------------------------------------------
    | Máscara do CEP
    |--------------------------------------------------------------------------
    */

    function formatarCep(valor) {
        const numeros = valor
            .replace(/\D/g, '')
            .slice(0, 8);

        if (numeros.length <= 5) {
            return numeros;
        }

        return (
            numeros.slice(0, 5) +
            '-' +
            numeros.slice(5)
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Limpa campos automáticos
    |--------------------------------------------------------------------------
    */

    function limparEndereco() {
        if (logradouroInput) {
            logradouroInput.value = '';
        }

        if (bairroInput) {
            bairroInput.value = '';
        }

        if (cidadeInput) {
            cidadeInput.value = '';
        }

        if (estadoInput) {
            estadoInput.value = '';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Consulta nossa API Laravel
    |--------------------------------------------------------------------------
    */

    async function consultarCep() {
        const cep = cepInput.value.replace(/\D/g, '');

        if (cep.length !== 8) {
            return;
        }

        if (cep === ultimoCepConsultado) {
            return;
        }

        ultimoCepConsultado = cep;

        try {
            cepInput.setCustomValidity('');

            const response = await fetch(`/api/cep/${cep}`, {
                headers: {
                    Accept: 'application/json',
                },
            });

            const dados = await response.json();

            if (!response.ok) {
                throw new Error(
                    dados.message ?? 'Não foi possível consultar o CEP.'
                );
            }

            if (logradouroInput) {
                logradouroInput.value = dados.logradouro ?? '';
            }

            if (bairroInput) {
                bairroInput.value = dados.bairro ?? '';
            }

            if (cidadeInput) {
                cidadeInput.value = dados.localidade ?? '';
            }

            if (estadoInput) {
                estadoInput.value = dados.uf ?? '';
            }

            cepInput.setCustomValidity('');

            // Depois de localizar o endereço,
            // o usuário normalmente precisa preencher o número.
            if (numeroInput) {
                numeroInput.focus();
            }

        } catch (erro) {
            ultimoCepConsultado = '';

            limparEndereco();

            cepInput.setCustomValidity(
                erro.message ?? 'CEP não encontrado.'
            );

            cepInput.reportValidity();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Eventos
    |--------------------------------------------------------------------------
    */

    cepInput.value = formatarCep(cepInput.value);

    cepInput.addEventListener('input', () => {
        cepInput.value = formatarCep(cepInput.value);

        cepInput.setCustomValidity('');

        const cep = cepInput.value.replace(/\D/g, '');

        clearTimeout(temporizador);

        if (cep.length !== 8) {
            ultimoCepConsultado = '';
            return;
        }

        // Pequeno intervalo para evitar chamadas repetidas
        temporizador = setTimeout(() => {
            consultarCep();
        }, 350);
    });

    cepInput.addEventListener('blur', consultarCep);
});
/*
|--------------------------------------------------------------------------
| RF014 - Gráfico de vendas realizadas
|--------------------------------------------------------------------------
*/

document.addEventListener('DOMContentLoaded', () => {

    const canvas =
        document.getElementById('front-sales-chart');


    /*
    |--------------------------------------------------------------------------
    | Página sem gráfico
    |--------------------------------------------------------------------------
    */

    if (!canvas) {
        return;
    }


    /*
    |--------------------------------------------------------------------------
    | Dados enviados pelo Laravel
    |--------------------------------------------------------------------------
    */

    const labels = JSON.parse(
        canvas.dataset.labels ?? '[]'
    );

    const values = JSON.parse(
        canvas.dataset.values ?? '[]'
    );


    /*
    |--------------------------------------------------------------------------
    | Plugin para mostrar os números nos pontos
    |--------------------------------------------------------------------------
    */

    const valueLabelsPlugin = {

        id: 'salesValueLabels',

        afterDatasetsDraw(chart) {

            const { ctx } = chart;

            const metadata =
                chart.getDatasetMeta(0);

            ctx.save();

            ctx.fillStyle =
                '#ffffff';

            ctx.font =
                '600 12px Montserrat, sans-serif';

            ctx.textAlign =
                'center';

            ctx.textBaseline =
                'bottom';


            metadata.data.forEach(
                (point, index) => {

                    ctx.fillText(
                        String(values[index]),
                        point.x,
                        point.y - 10
                    );

                }
            );


            ctx.restore();
        },
    };


    /*
    |--------------------------------------------------------------------------
    | Criação do gráfico
    |--------------------------------------------------------------------------
    */

    new Chart(canvas, {

        type: 'line',

        data: {

            labels,

            datasets: [
                {
                    label: 'Vendas',

                    data: values,

                    borderColor:
                        '#42B9A6',

                    backgroundColor:
                        'rgba(66, 185, 166, 0.15)',

                    pointBackgroundColor:
                        '#42B9A6',

                    pointBorderColor:
                        '#ffffff',

                    pointBorderWidth: 2,

                    pointRadius: 5,

                    pointHoverRadius: 7,

                    borderWidth: 3,

                    tension: 0.3,

                    fill: true,
                },
            ],
        },


        options: {

            responsive: true,

            maintainAspectRatio: false,


            layout: {
                padding: {
                    top: 25,
                },
            },


            interaction: {
                intersect: false,
                mode: 'index',
            },


            plugins: {

                legend: {
                    display: false,
                },


                tooltip: {

                    backgroundColor:
                        '#ffffff',

                    titleColor:
                        '#042434',

                    bodyColor:
                        '#042434',

                    borderColor:
                        '#42B9A6',

                    borderWidth: 1,

                    padding: 12,

                    displayColors: false,


                    callbacks: {

                        label(context) {

                            const total =
                                context.parsed.y;

                            const palavra =
                                total === 1
                                    ? 'venda'
                                    : 'vendas';

                            return `${total} ${palavra}`;
                        },
                    },
                },
            },


            scales: {

                x: {

                    border: {
                        color:
                            'rgba(148, 163, 184, 0.25)',
                    },

                    grid: {
                        display: false,
                    },

                    ticks: {

                        color:
                            '#d1d5db',

                        font: {
                            family:
                                'Montserrat',

                            size: 12,
                        },
                    },
                },


                y: {

                    beginAtZero: true,


                    border: {
                        color:
                            'rgba(148, 163, 184, 0.25)',
                    },


                    grid: {
                        color:
                            'rgba(148, 163, 184, 0.15)',
                    },


                    ticks: {

                        color:
                            '#d1d5db',

                        precision: 0,

                        stepSize: 1,

                        font: {
                            family:
                                'Montserrat',

                            size: 12,
                        },
                    },


                    title: {

                        display: true,

                        text:
                            'Quantidade de vendas',

                        color:
                            '#d1d5db',

                        font: {
                            family:
                                'Montserrat',

                            size: 12,

                            weight:
                                '500',
                        },
                    },
                },
            },
        },


        plugins: [
            valueLabelsPlugin,
        ],
    });
});