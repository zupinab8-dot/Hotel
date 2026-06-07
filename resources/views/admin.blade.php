    ''<!DOCTYPE html>
    <html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>MadeiraBR - Painel Administrativo</title>
        <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    </head>
    <body class="dashboard">
        <!-- Header -->
        <header class="header admin-header">
            <div class="header-content">
                <div class="header-left">
                    <h1>MadeiraBR - Painel Administrativo</h1>
                    <p>Gerencie todos os agendamentos</p>
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
            <!-- Statistics -->
            <div class="grid grid-cols-4">
                <div class="stat-card">
                    <div class="stat-header">
                        <h3>Total de Pedidos</h3>
                        <span style="font-size: 1.5rem;">📊</span>
                    </div>
                    <div class="stat-value" id="statTotal">0</div>
                    <p class="stat-description">Total de agendamentos</p>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <h3>Pendentes</h3>
                        <span style="font-size: 1.5rem;">🕐</span>
                    </div>
                    <div class="stat-value" style="color: #f59e0b;" id="statPending">0</div>
                    <p class="stat-description">Aguardando confirmação</p>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <h3>Confirmados</h3>
                        <span style="font-size: 1.5rem;">✅</span>
                    </div>
                    <div class="stat-value" style="color: #10b981;" id="statConfirmed">0</div>
                    <p class="stat-description">Pedidos confirmados</p>
                </div>

                <div class="stat-card">
                    <div class="stat-header">
                        <h3>Receita Total</h3>
                        <span style="font-size: 1.5rem;">💰</span>
                    </div>
                    <div class="stat-value" style="color: #3b82f6;" id="statRevenue">R$ 0</div>
                    <p class="stat-description">Excluindo cancelados</p>
                </div>
            </div>

            <!-- Appointments Table -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Todos os Agendamentos</h3>
                    <p class="card-description">Gerencie todos os pedidos de clientes (<span id="totalAppointments">0</span> total)</p>
                </div>
                <div id="appointmentsTable"></div>
            </div>

            <!-- Woods Catalog -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Catálogo de Madeiras Disponíveis</h3>
                    <p class="card-description"><span id="totalWoods">0</span> tipos de madeiras cadastradas</p>
                </div>
                <div id="woodsCatalog" class="grid grid-cols-4"></div>
            </div>
        </div>

        <!-- Edit Status Modal -->
        <div id="editModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Editar Agendamento</h2>
                    <p>Atualize o status do pedido #<span id="editAppointmentId"></span></p>
                </div>
                
                <div id="editAppointmentDetails" style="background: #f9fafb; padding: 16px; border-radius: 8px; margin-bottom: 20px;"></div>

                <div class="form-group">
                    <label for="statusSelect">Status</label>
                    <select id="statusSelect" class="form-group input" required>
                        <option value="pending">Pendente</option>
                        <option value="confirmed">Confirmado</option>
                        <option value="completed">Concluído</option>
                        <option value="cancelled">Cancelado</option>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="button" class="btn btn-outline" onclick="closeEditModal()">Cancelar</button>
                    <button type="button" class="btn btn-primary" onclick="saveStatusUpdate()">Salvar Alterações</button>
                </div>
            </div>
        </div>

        <div id="toast" class="toast"></div>

        <script src="{{ asset('js/data.js') }}"></script>
        <script src="{{ asset('js/auth.js') }}"></script>
        <script>
            let currentUser = checkAuth('admin');
            let editingAppointmentId = null;
            
            if (currentUser) {
                document.getElementById('userName').textContent = currentUser.name;
                document.getElementById('userEmail').textContent = currentUser.email;
                
                loadStatistics();
                loadAppointments();
                loadWoodsCatalog();
            }

            function loadStatistics() {
                const appointments = getAllAppointments();
                
                const stats = {
                    total: appointments.length,
                    pending: appointments.filter(a => a.status === 'pending').length,
                    confirmed: appointments.filter(a => a.status === 'confirmed').length,
                    cancelled: appointments.filter(a => a.status === 'cancelled').length,
                    completed: appointments.filter(a => a.status === 'completed').length,
                    revenue: appointments
                        .filter(a => a.status !== 'cancelled')
                        .reduce((sum, a) => sum + a.totalPrice, 0)
                };

                document.getElementById('statTotal').textContent = stats.total;
                document.getElementById('statPending').textContent = stats.pending;
                document.getElementById('statConfirmed').textContent = stats.confirmed;
                document.getElementById('statRevenue').textContent = `R$ ${(stats.revenue / 1000).toFixed(1)}k`;
            }

            function loadAppointments() {
                const appointments = getAllAppointments();
                const container = document.getElementById('appointmentsTable');
                document.getElementById('totalAppointments').textContent = appointments.length;
                
                if (appointments.length === 0) {
                    container.innerHTML = `
                        <div class="empty-state">
                            <div class="empty-icon">📅</div>
                            <h3>Nenhum agendamento ainda</h3>
                            <p>Aguarde os clientes fazerem pedidos</p>
                        </div>
                    `;
                } else {
                    container.innerHTML = `
                        <table>
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Cliente</th>
                                    <th>Madeira</th>
                                    <th>Qtd</th>
                                    <th>Data/Hora</th>
                                    <th>Endereço</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th style="text-align: right;">Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${appointments.map(apt => {
                                    const wood = woodsData.find(w => w.id === apt.woodId);
                                    return `
                                        <tr>
                                            <td style="font-family: monospace; font-size: 0.875rem;">#${apt.id.slice(-6)}</td>
                                            <td>
                                                <div style="font-weight: 500;">${apt.userName}</div>
                                                <div style="font-size: 0.75rem; color: #6b7280;">${apt.userEmail}</div>
                                            </td>
                                            <td>
                                                <div style="font-weight: 500;">${apt.woodName}</div>
                                                <div style="font-size: 0.75rem; color: #6b7280; font-style: italic;">
                                                    ${wood ? wood.scientificName : ''}
                                                </div>
                                            </td>
                                            <td>${apt.quantity} m³</td>
                                            <td>
                                                <div>${new Date(apt.date).toLocaleDateString('pt-BR')}</div>
                                                <div style="font-size: 0.75rem; color: #6b7280;">${apt.time}</div>
                                            </td>
                                            <td style="max-width: 200px;">
                                                <div style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 0.875rem;">
                                                    ${apt.deliveryAddress}
                                                </div>
                                            </td>
                                            <td style="font-weight: 600; color: #16a34a;">
                                                R$ ${apt.totalPrice.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}
                                            </td>
                                            <td>
                                                <span class="badge badge-${apt.status}">${getStatusLabel(apt.status)}</span>
                                            </td>
                                            <td>
                                                <div class="table-actions" style="justify-content: flex-end;">
                                                    <button class="btn-icon" onclick="openEditModal('${apt.id}')" title="Editar">✏️</button>
                                                    ${apt.status !== 'cancelled' ? `
                                                        <button class="btn-icon" onclick="cancelAppointmentById('${apt.id}')" title="Cancelar">❌</button>
                                                    ` : ''}
                                                    <button class="btn-icon btn-danger" onclick="deleteAppointmentById('${apt.id}')" title="Excluir">🗑️</button>
                                                </div>
                                            </td>
                                        </tr>
                                    `;
                                }).join('')}
                            </tbody>
                        </table>
                    `;
                }
            }

            function loadWoodsCatalog() {
                const container = document.getElementById('woodsCatalog');
                document.getElementById('totalWoods').textContent = woodsData.length;
                
                container.innerHTML = woodsData.map(wood => `
                    <div class="small-wood-card">
                        <img src="${wood.image}" alt="${wood.name}">
                        <div class="small-wood-card-content">
                            <h4>${wood.name}</h4>
                            <p class="scientific">${wood.scientificName}</p>
                            <p class="price">R$ ${wood.pricePerM3.toLocaleString('pt-BR')}/m³</p>
                        </div>
                    </div>
                `).join('');
            }

            function openEditModal(appointmentId) {
                const appointments = getAllAppointments();
                const appointment = appointments.find(a => a.id === appointmentId);
                
                if (appointment) {
                    editingAppointmentId = appointmentId;
                    document.getElementById('editAppointmentId').textContent = appointmentId.slice(-6);
                    document.getElementById('statusSelect').value = appointment.status;
                    
                    document.getElementById('editAppointmentDetails').innerHTML = `
                        <p style="margin: 8px 0;"><strong>Cliente:</strong> ${appointment.userName}</p>
                        <p style="margin: 8px 0;"><strong>Madeira:</strong> ${appointment.woodName}</p>
                        <p style="margin: 8px 0;"><strong>Quantidade:</strong> ${appointment.quantity} m³</p>
                        <p style="margin: 8px 0;"><strong>Total:</strong> R$ ${appointment.totalPrice.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}</p>
                    `;
                    
                    document.getElementById('editModal').classList.add('show');
                }
            }

            function closeEditModal() {
                document.getElementById('editModal').classList.remove('show');
                editingAppointmentId = null;
            }

            function saveStatusUpdate() {
                const newStatus = document.getElementById('statusSelect').value;
                updateAppointment(editingAppointmentId, { status: newStatus });
                showToast('Status atualizado com sucesso', 'success');
                closeEditModal();
                loadStatistics();
                loadAppointments();
            }

            function cancelAppointmentById(id) {
                if (confirm('Tem certeza que deseja cancelar este agendamento?')) {
                    cancelAppointment(id);
                    showToast('Agendamento cancelado', 'success');
                    loadStatistics();
                    loadAppointments();
                }
            }

            function deleteAppointmentById(id) {
                if (confirm('Tem certeza que deseja excluir este agendamento? Esta ação não pode ser desfeita.')) {
                    deleteAppointment(id);
                    showToast('Agendamento excluído', 'success');
                    loadStatistics();
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
            document.getElementById('editModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeEditModal();
                }
            });
        </script>
    </body>
    </html>
