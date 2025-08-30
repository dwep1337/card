class CardValidator {
    constructor() {
        this.$creditCard = $(".credit-card");
        this.$numeroCartao = $("#numeroCartao");
        this.$nomeTitular = $("#nomeTitular");
        this.$validade = $("#validade");
        this.$cvv = $("#cvv");
        this.$btnVerificar = $("#btnVerificar");
        this.$btnToggleCvv = $(".btn-toggle-cvv");
        this.$resultado = $("#resultado");
        this.initEvents();
    }

    initEvents() {
        this.$numeroCartao.on("input", () => this.formatCardNumber());
        this.$validade.on("input", () => this.formatValidity());

        this.$cvv
            .on("focus", () => this.$creditCard.addClass("flipped"))
            .on("blur", () => this.$creditCard.removeClass("flipped"));

        this.$btnToggleCvv.click(() => this.toggleCvv());
        this.$btnVerificar.click(() => this.verifyCard());

        this.$creditCard.click((e) => {
            if (!$(e.target).is("input") && !$(e.target).is("button")) {
                this.$creditCard.toggleClass("flipped");
            }
        });
    }

    formatCardNumber() {
        let value = this.$numeroCartao.val().replace(/\D/g, "");
        value = value.replace(/(\d{4})(?=\d)/g, "$1 ");
        this.$numeroCartao.val(value.substring(0, 19));
    }

    formatValidity() {
        let value = this.$validade.val().replace(/\D/g, "");
        if (value.length > 2) {
            value = value.substring(0, 2) + "/" + value.substring(2, 4);
        }
        this.$validade.val(value.substring(0, 5));
    }

    toggleCvv() {
        const isPassword = this.$cvv.attr("type") === "password";
        this.$cvv.attr("type", isPassword ? "text" : "password");
        this.$btnToggleCvv.html(
            isPassword ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>'
        );
    }

    async verifyCard() {
        if (!this.validateInputs()) return;

        this.showLoadingMessage();

        try {
            const response = await fetch("/check-card", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    numero: this.$numeroCartao.val(),
                    nome: this.$nomeTitular.val(),
                    validade: this.$validade.val(),
                    cvv: this.$cvv.val(),
                }),
            });

            const data = await response.json();

            if (data.error) {
                this.showResult(data.message, "danger");
            } else {
                this.showResult(data.message, "success");
                this.clearInputs();
            }
        } catch (error) {
            this.showResult("Erro ao verificar cartão. Tente novamente.", "danger");
        }
    }

    validateInputs() {
        const numero = this.$numeroCartao.val().replace(/\s/g, "");
        const nome = this.$nomeTitular.val();
        const validade = this.$validade.val();
        const cvv = this.$cvv.val();

        if (!numero || numero.length < 16 || !this.luhnCheck(numero)) {
            this.showResult("Por favor, insira um número de cartão válido", "danger");
            return false;
        }

        if (!nome) {
            this.showResult("Por favor, insira o nome do titular", "danger");
            return false;
        }

        if (!validade || !this.isExpirationValid(validade)) {
            this.showResult("Por favor, insira uma validade válida (MM/AA)", "danger");
            return false;
        }

        if (!cvv || !/^\d{3,4}$/.test(cvv)) {
            this.showResult("Por favor, insira um CVV válido", "danger");
            return false;
        }

        return true;
    }

    luhnCheck(number) {
        let sum = 0;
        let shouldDouble = false;

        for (let i = number.length - 1; i >= 0; i--) {
            let digit = parseInt(number[i], 10);

            if (shouldDouble) {
                digit *= 2;
                if (digit > 9) digit -= 9;
            }

            sum += digit;
            shouldDouble = !shouldDouble;
        }

        return sum % 10 === 0;
    }

    isExpirationValid(value) {
        if (!/^\d{2}\/\d{2}$/.test(value)) return false;

        const [monthStr, yearStr] = value.split("/");
        const month = parseInt(monthStr, 10);
        const year = parseInt("20" + yearStr, 10);

        if (month < 1 || month > 12) return false;

        const now = new Date();
        const currentMonth = now.getMonth() + 1;
        const currentYear = now.getFullYear();

        return !(year < currentYear || (year === currentYear && month < currentMonth));
    }

    showLoadingMessage() {
        this.showResult(
            '<i class="bi bi-shield-lock"></i> Verificando segurança do seu cartão...',
            "info"
        );
    }

    showResult(message, type) {
        this.$resultado.html(`<div class="alert alert-${type}">${message}</div>`);
    }

    clearInputs() {
        this.$numeroCartao.val("");
        this.$nomeTitular.val("");
        this.$validade.val("");
        this.$cvv.val("");
        this.$creditCard.removeClass("flipped");
        this.$cvv.attr("type", "password");
        this.$btnToggleCvv.html('<i class="bi bi-eye"></i>');
    }
}

$(document).ready(() => new CardValidator());
