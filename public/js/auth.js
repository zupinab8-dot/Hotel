// Usuários pré-cadastrados
const users = [
    { id: '1', email: 'cliente@email.com', name: 'Cliente Teste', role: 'client', password: '123456' },
    { id: '2', email: 'admin@email.com', name: 'Administrador', role: 'admin', password: 'admin123' }
];

// Login
function login(email, password) {
    const user = users.find(u => u.email === email && u.password === password);
    if (user) {
        const userToStore = { id: user.id, email: user.email, name: user.name, role: user.role };
        localStorage.setItem('currentUser', JSON.stringify(userToStore));
        return userToStore;
    }
    return null;
}

// Logout
function logout() {
    localStorage.removeItem('currentUser');
    window.location.href = '/';
}

// Get current user
function getCurrentUser() {
    const userStr = localStorage.getItem('currentUser');
    return userStr ? JSON.parse(userStr) : null;
}

// Check authentication
function checkAuth(requiredRole) {
    const user = getCurrentUser();
    if (!user) {
        window.location.href = '/';
        return null;
    }
    if (requiredRole && user.role !== requiredRole) {
        window.location.href = user.role === 'admin' ? '/admin' : '/cliente';
        return null;
    }
    return user;
}

// Appointments functions
function getAllAppointments() {
    const appointments = localStorage.getItem('appointments');
    return appointments ? JSON.parse(appointments) : [];
}

function getAppointmentsByUserId(userId) {
    return getAllAppointments().filter(apt => apt.userId === userId);
}

function createAppointment(appointment) {
    const appointments = getAllAppointments();
    const newAppointment = {
        ...appointment,
        id: Date.now().toString(),
        createdAt: new Date().toISOString()
    };
    appointments.push(newAppointment);
    localStorage.setItem('appointments', JSON.stringify(appointments));
    return newAppointment;
}

function updateAppointment(id, updates) {
    const appointments = getAllAppointments();
    const index = appointments.findIndex(apt => apt.id === id);
    if (index !== -1) {
        appointments[index] = { ...appointments[index], ...updates };
        localStorage.setItem('appointments', JSON.stringify(appointments));
    }
}

function deleteAppointment(id) {
    const appointments = getAllAppointments();
    const filtered = appointments.filter(apt => apt.id !== id);
    localStorage.setItem('appointments', JSON.stringify(filtered));
}

function cancelAppointment(id) {
    updateAppointment(id, { status: 'cancelled' });
}
