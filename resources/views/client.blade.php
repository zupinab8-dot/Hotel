<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MadeiraBR - Portal do Cliente</title>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
</head>
<body class="dashboard">
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="header-left">
                <h1>MadeiraBR</h1>
                <p>Portal do Cliente</p>
            </div>
            <div class="header-right">
                <div class="user-info">
                    <p id="userName"></p>
                    <p class="email" id="userEmail"></p>
                </div>
                <button class="btn btn-outline" onclick="logout()">🚪 Sair</button>
            </div>
        </div>
    </header>

    <!-- Container -->
    <div class="container">
        <!-- Welcome Card -->
        <div class="card welcome-card">
            <div class="welcome-content">
                <div>
                    <h2>Bem-vindo de volta, <span id="userFirstName"></span>!</h2>
                    <p>Gerencie seus agendamentos e faça novas compras de madeiras</p>
                </div>
                <button class="btn btn-amber btn-large" onclick="openNewAppointmentModal()">
                    ➕ Novo Agendamento
                </button>
            </div>
        </div>

        <!-- Appointments List -->
        <div>
            <h3 class="card-title">Meus Agendamentos</h3>
            <div id="appointmentsList" class="grid grid-cols-3"></div>
        </div>

        <!-- Woods Catalog -->
        <div style="margin-top: 48px;">
            <h3 class="card-title">Catálogo de Madeiras</h3>
            <div id="woodsCatalog" class="grid grid-cols-4"></div>
        </div>
    </div>

    <!-- New Appointment Modal -->
    <div id="newAppointmentModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Novo Agendamento</h2>
                <p>Preencha os dados para agendar sua compra de madeira</p>
            </div>
            
            <form id="appointmentForm">
                <div class="form-group">
                    <label for="woodSelect">Tipo de Madeira</label>
                    <select id="woodSelect" class="form-group input" required onchange="updateWoodPreview()">
                        <option value="">Selecione uma madeira</option>
                    </select>
                </div>

                <div id="woodPreview" class="wood-preview" style="display: none;"></div>

                <div class="form-group">
                    <label for="quantity">Quantidade (m³)</label>
                    <input type="number" id="quantity" min="1" value="1" required oninput="updateTotal()">
                </div>

                <div id="totalPreview" class="total-preview" style="display: none;"></div>

                <div class="grid grid-cols-2" style="gap: 16px;">
                    <div class="form-group">
                        <label for="date">Data</label>
                        <input type="date" id="date" required>
                    </div>
                    <div class="form-group">
                        <label for="time">Horário</label>
                        <input type="time" id="time" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="address">Endereço de Entrega</label>
                    <input type="text" id="address" placeholder="Rua, número, bairro, cidade, estado" required>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-outline" onclick="closeNewAppointmentModal()">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Confirmar Agendamento</button>
                </div>
            </form>
        </div>
    </div>

    <div id="toast" class="toast"></div>

    <script src="{{ asset('js/data.js') }}"></script>
    <script src="{{ asset('js/auth.js') }}"></script>
    <script>
        let currentUser = checkAuth('client');
        
        if (currentUser) {
            document.getElementById('userName').textContent = currentUser.name;
            document.getElementById('userEmail').textContent = currentUser.email;
            document.getElementById('userFirstName').textContent = currentUser.name.split(' ')[0];
            
            // Set min date to today
            document.getElementById('date').min = new Date().toISOString().split('T')[0];
            
            // Load appointments
            loadAppointments();
            
            // Load woods catalog
            loadWoodsCatalog();
            
            // Populate wood select
            populateWoodSelect();
        }

        function loadAppointments() {
            const appointments = getAppointmentsByUserId(currentUser.id);
            const container = document.getElementById('appointmentsList');
            
            if (appointments.length === 0) {
                container.innerHTML = `
                    <div class="card empty-state" style="grid-column: 1 / -1;">
                        <div class="empty-icon">📅</div>
                        <h3>Nenhum agendamento ainda</h3>
                        <p>Crie seu primeiro agendamento para começar</p>
                        <button class="btn btn-primary" onclick="openNewAppointmentModal()">➕ Criar Agendamento</button>
                    </div>
                `;
            } else {
                container.innerHTML = appointments.map(apt => `
                    <div class="appointment-card">
                        <div class="appointment-header">
                            <div>
                                <h3>${apt.woodName}</h3>
                                <p style="font-size: 0.75rem; color: #6b7280;">Pedido #${apt.id.slice(-6)}</p>
                            </div>
                            <span class="badge badge-${apt.status}">${getStatusLabel(apt.status)}</span>
                        </div>
                        <div class="appointment-info">
                            <div class="info-row">📦 ${apt.quantity} m³</div>
                            <div class="info-row">📅 ${new Date(apt.date).toLocaleDateString('pt-BR')}</div>
                            <div class="info-row">🕐 ${apt.time}</div>
                            <div class="info-row">📍 ${apt.deliveryAddress}</div>
                        </div>
                        <div class="appointment-total">
                            R$ ${apt.totalPrice.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}
                        </div>
                        ${apt.status === 'pending' ? `
                            <button class="btn btn-danger btn-block" onclick="cancelAppointmentById('${apt.id}')">
                                ❌ Cancelar Pedido
                            </button>
                        ` : ''}
                    </div>
                `).join('');
            }
        }

        function loadWoodsCatalog() {
            const container = document.getElementById('woodsCatalog');
            const displayWoods = woodsData.slice(0, 8);
            
            container.innerHTML = displayWoods.map(wood => `
                <div class="wood-card">
                    <img src="${wood.image}" alt="${wood.name}">
                    <div class="wood-card-content">
                        <h3>${wood.name}</h3>
                        <p class="scientific">${wood.scientificName}</p>
                        <p class="description">${wood.description}</p>
                        <p class="price">R$ ${wood.pricePerM3.toLocaleString('pt-BR')}/m³</p>
                    </div>
                </div>
            `).join('');
            
            if (woodsData.length > 8) {
                container.innerHTML += `
                    <div style="grid-column: 1 / -1; text-align: center; color: #6b7280; margin-top: 16px;">
                        + ${woodsData.length - 8} tipos de madeiras disponíveis
                    </div>
                `;
            }
        }

        function populateWoodSelect() {
            const select = document.getElementById('woodSelect');
            woodsData.forEach(wood => {
                const option = document.createElement('option');
                option.value = wood.id;
                option.textContent = `${wood.name} - R$ ${wood.pricePerM3.toLocaleString('pt-BR')}/m³`;
                select.appendChild(option);
            });
        }

        function updateWoodPreview() {
            const woodId = document.getElementById('woodSelect').value;
            const preview = document.getElementById('woodPreview');
            
            if (woodId) {
                const wood = woodsData.find(w => w.id === woodId);
                preview.style.display = 'block';
                preview.innerHTML = `
                    <img src="${wood.image}" alt="${wood.name}">
                    <h4>${wood.name}</h4>
                    <p>${wood.description}</p>
                    <p><strong>Densidade:</strong> ${wood.density}</p>
                `;
                updateTotal();
            } else {
                preview.style.display = 'none';
            }
        }

        function updateTotal() {
            const woodId = document.getElementById('woodSelect').value;
            const quantity = document.getElementById('quantity').value;
            const totalPreview = document.getElementById('totalPreview');
            
            if (woodId && quantity) {
                const wood = woodsData.find(w => w.id === woodId);
                const total = wood.pricePerM3 * quantity;
                totalPreview.style.display = 'block';
                totalPreview.innerHTML = `
                    <p>Total: R$ ${total.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}</p>
                `;
            } else {
                totalPreview.style.display = 'none';
            }
        }

        function openNewAppointmentModal() {
            document.getElementById('newAppointmentModal').classList.add('show');
        }

        function closeNewAppointmentModal() {
            document.getElementById('newAppointmentModal').classList.remove('show');
            document.getElementById('appointmentForm').reset();
            document.getElementById('woodPreview').style.display = 'none';
            document.getElementById('totalPreview').style.display = 'none';
        }

        document.getElementById('appointmentForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const woodId = document.getElementById('woodSelect').value;
            const wood = woodsData.find(w => w.id === woodId);
            const quantity = parseInt(document.getElementById('quantity').value);
            const date = document.getElementById('date').value;
            const time = document.getElementById('time').value;
            const address = document.getElementById('address').value;
            
            const appointment = {
                userId: currentUser.id,
                userName: currentUser.name,
                userEmail: currentUser.email,
                woodId: wood.id,
                woodName: wood.name,
                quantity: quantity,
                totalPrice: wood.pricePerM3 * quantity,
                date: date,
                time: time,
                deliveryAddress: address,
                status: 'pending'
            };
            
            createAppointment(appointment);
            showToast('Agendamento criado com sucesso!', 'success');
            closeNewAppointmentModal();
            loadAppointments();
        });

        function cancelAppointmentById(id) {
            if (confirm('Tem certeza que deseja cancelar este pedido?')) {
                cancelAppointment(id);
                showToast('Agendamento cancelado', 'success');
                loadAppointments();
            }
        }

        function getStatusLabel(status) {
            const labels = {
                pending: 'Pendente',
                confirmed: 'Confirmado',
                cancelled: 'Cancelado',
                completed: 'Concluído'
            };
            return labels[status] || status;
        }

        function showToast(message, type) {
            const toast = document.getElementById('toast');
            toast.textContent = message;
            toast.className = `toast toast-${type} toast-show`;
            setTimeout(() => {
                toast.className = 'toast';
            }, 3000);
        }

        // Close modal when clicking outside
        document.getElementById('newAppointmentModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeNewAppointmentModal();
            }
        });
    </script>
</body>
</html>
