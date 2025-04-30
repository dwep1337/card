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
        if (!this.$numeroCartao.val() || this.$numeroCartao.val().replace(/\s/g, "").length < 16) {
            this.showResult("Por favor, insira um número de cartão válido", "danger");
            return false;
        }

        if (!this.$nomeTitular.val()) {
            this.showResult("Por favor, insira o nome do titular", "danger");
            return false;
        }

        if (!this.$validade.val() || this.$validade.val().length < 5) {
            this.showResult("Por favor, insira uma validade válida (MM/AA)", "danger");
            return false;
        }

        if (!this.$cvv.val() || this.$cvv.val().length < 3) {
            this.showResult("Por favor, insira um CVV válido", "danger");
            return false;
        }

        return true;
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