import './bootstrap';

import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

window.Alpine = Alpine;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    const cpfInput = document.getElementById('cpf');

    if (!cpfInput) {
        return;
    }

    function formatarCpf(valor) {
        let cpf = valor.replace(/\D/g, '');

        cpf = cpf.slice(0, 11);
        cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
        cpf = cpf.replace(/(\d{3})(\d)/, '$1.$2');
        cpf = cpf.replace(/(\d{3})(\d{1,2})$/, '$1-$2');

        return cpf;
    }

    cpfInput.value = formatarCpf(cpfInput.value);

    cpfInput.addEventListener('input', (event) => {
        event.target.value = formatarCpf(event.target.value);
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('front-produtos-chart')
        ?? document.getElementById('produto-by-month-chart');

    if (!canvas) {
        return;
    }

    const labels = JSON.parse(canvas.dataset.labels ?? '[]');
    const values = JSON.parse(canvas.dataset.values ?? '[]');

    const valueLabelsPlugin = {
        id: 'valueLabels',

        afterDatasetsDraw(chart) {
            const { ctx } = chart;
            const metadata = chart.getDatasetMeta(0);

            ctx.save();
            ctx.fillStyle = '#ffffff';
            ctx.font = '600 12px Montserrat, sans-serif';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'bottom';

            metadata.data.forEach((bar, index) => {
                ctx.fillText(String(values[index]), bar.x, bar.y - 6);
            });

            ctx.restore();
        },
    };

    new Chart(canvas, {
        type: 'bar',

        data: {
            labels,
            datasets: [
                {
                    data: values,
                    backgroundColor: '#42B9A6',
                    hoverBackgroundColor: '#52C8B5',
                    borderRadius: {
                        topLeft: 6,
                        topRight: 6,
                    },
                    borderSkipped: false,
                    maxBarThickness: 64,
                },
            ],
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,

            layout: {
                padding: {
                    top: 20,
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
                    backgroundColor: '#ffffff',
                    titleColor: '#042434',
                    bodyColor: '#042434',
                    borderColor: '#42B9A6',
                    borderWidth: 1,
                    padding: 12,
                    displayColors: false,

                    callbacks: {
                        label(context) {
                            const total = context.parsed.y;
                            const palavra = total === 1 ? 'produto' : 'produtos';

                            return `${total} ${palavra}`;
                        },
                    },
                },
            },

            scales: {
                x: {
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

                y: {
                    beginAtZero: true,

                    border: {
                        color: 'rgba(148, 163, 184, 0.25)',
                    },

                    grid: {
                        color: 'rgba(148, 163, 184, 0.15)',
                    },

                    ticks: {
                        color: '#d1d5db',
                        precision: 0,
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

        plugins: [valueLabelsPlugin],
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
            return '(' + ddd + ') ' + numero.slice(0, 4) + '-' + numero.slice(4, 8);
        }

        return '(' + ddd + ') ' + numero.slice(0, 5) + '-' + numero.slice(5, 9);
    }

    function formatarTelefone(valor) {
        const possuiCodigoInternacional = valor.trimStart().startsWith('+');
        const numeros = valor.replace(/\D/g, '').slice(0, 15);

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

document.addEventListener('DOMContentLoaded', () => {
    const cepInput = document.getElementById('cep');
    const logradouroInput = document.getElementById('logradouro');
    const bairroInput = document.getElementById('bairro');
    const cidadeInput = document.getElementById('cidade');
    const estadoInput = document.getElementById('estado');
    const numeroInput = document.getElementById('numero');

    if (!cepInput || cepInput.disabled) {
        return;
    }

    let ultimoCepConsultado = '';
    let temporizador = null;

    function formatarCep(valor) {
        const numeros = valor.replace(/\D/g, '').slice(0, 8);

        if (numeros.length <= 5) {
            return numeros;
        }

        return numeros.slice(0, 5) + '-' + numeros.slice(5);
    }

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

    async function consultarCep() {
        const cep = cepInput.value.replace(/\D/g, '');

        if (cep.length !== 8 || cep === ultimoCepConsultado) {
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
                throw new Error(dados.message ?? 'Não foi possível consultar o CEP.');
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

            if (numeroInput) {
                numeroInput.focus();
            }
        } catch (erro) {
            ultimoCepConsultado = '';

            limparEndereco();

            cepInput.setCustomValidity(erro.message ?? 'CEP não encontrado.');
            cepInput.reportValidity();
        }
    }

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

        temporizador = setTimeout(consultarCep, 350);
    });

    cepInput.addEventListener('blur', consultarCep);
});

document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('front-sales-chart');

    if (!canvas) {
        return;
    }

    const labels = JSON.parse(canvas.dataset.labels ?? '[]');
    const values = JSON.parse(canvas.dataset.values ?? '[]');

    const valueLabelsPlugin = {
        id: 'salesValueLabels',

        afterDatasetsDraw(chart) {
            const { ctx } = chart;
            const metadata = chart.getDatasetMeta(0);

            ctx.save();
            ctx.fillStyle = '#ffffff';
            ctx.font = '600 12px Montserrat, sans-serif';
            ctx.textAlign = 'center';
            ctx.textBaseline = 'bottom';

            metadata.data.forEach((point, index) => {
                ctx.fillText(String(values[index]), point.x, point.y - 10);
            });

            ctx.restore();
        },
    };

    new Chart(canvas, {
        type: 'line',

        data: {
            labels,
            datasets: [
                {
                    label: 'Vendas',
                    data: values,
                    borderColor: '#42B9A6',
                    backgroundColor: 'rgba(66, 185, 166, 0.15)',
                    pointBackgroundColor: '#42B9A6',
                    pointBorderColor: '#ffffff',
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
                    backgroundColor: '#ffffff',
                    titleColor: '#042434',
                    bodyColor: '#042434',
                    borderColor: '#42B9A6',
                    borderWidth: 1,
                    padding: 12,
                    displayColors: false,

                    callbacks: {
                        label(context) {
                            const total = context.parsed.y;
                            const palavra = total === 1 ? 'venda' : 'vendas';

                            return `${total} ${palavra}`;
                        },
                    },
                },
            },

            scales: {
                x: {
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

                y: {
                    beginAtZero: true,

                    border: {
                        color: 'rgba(148, 163, 184, 0.25)',
                    },

                    grid: {
                        color: 'rgba(148, 163, 184, 0.15)',
                    },

                    ticks: {
                        color: '#d1d5db',
                        precision: 0,
                        stepSize: 1,
                        font: {
                            family: 'Montserrat',
                            size: 12,
                        },
                    },

                    title: {
                        display: true,
                        text: 'Quantidade de vendas',
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

        plugins: [valueLabelsPlugin],
    });
});