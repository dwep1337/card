class LoginForm {
    constructor() {
        this.$form = $('#loginForm');
        this.$username = $('#username');
        this.$password = $('#password');
        this.$btnLogin = $('#btnLogin');
        this.$message = $('#loginMessage');
        this.initEvents();
    }

    initEvents() {
        this.$form.on('submit', (e) => this.handleSubmit(e));
    }

    async handleSubmit(e) {
        e.preventDefault();

        const username = this.$username.val().trim();
        const password = this.$password.val().trim();

        if (!username) {
            this.showMessage('Por favor, insira o usuário', 'danger');
            return;
        }

        if (!password) {
            this.showMessage('Por favor, insira a senha', 'danger');
            return;
        }

        await this.login(username, password);
    }

    async login(username, password) {
        const data = { username, password };

        try {
            const response = await fetch('/login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.error) {
                this.showMessage(result.message, 'danger');
                return;
            }

            this.showMessage(result.message, 'success');

            localStorage.setItem('auth_token', result.token);

            setTimeout(() => {
                window.location.href = '/admin/dashboard';
            }, 1000);

        } catch (error) {
            this.showMessage('Erro ao processar o login', 'danger');
            console.error(error);
        }
    }

    showMessage(message, type) {
        const colors = {
            success: '#d4edda',
            danger: '#f8d7da',
            info: '#d1ecf1'
        };
        const textColors = {
            success: '#155724',
            danger: '#721c24',
            info: '#0c5460'
        };
        this.$message
            .text(message)
            .css({
                'background-color': colors[type] || '#d1ecf1',
                'color': textColors[type] || '#0c5460',
                'padding': '10px',
                'border-radius': '5px',
                'margin-top': '15px',
                'font-weight': '600'
            });
    }
}

$(document).ready(() => {
    new LoginForm();
});